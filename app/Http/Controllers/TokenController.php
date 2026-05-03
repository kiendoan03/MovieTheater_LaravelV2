<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\UserRole;

class TokenController extends Controller
{
    public const REFRESH_COOKIE = 'refresh_token';

    public const ACCESS_COOKIE = 'access_token';

    private const REFRESH_TTL_DAYS = 30;

    /**
     * Tạo refresh token ngẫu nhiên, lưu hash vào DB, trả plain-text cho cookie.
     */
    public function issueRefreshToken(Account $account): string
    {
        $raw = Str::random(64);

        RefreshToken::create([
            'account_id' => $account->id,
            'token'      => $raw,
            'expires_at' => now()->addDays(self::REFRESH_TTL_DAYS),
            'is_revoked' => false,
        ]);

        return $raw;
    }

    /**
     * Build response JSON + 2 cookie (access & refresh).
     * Dùng chung cho login và refresh.
     */
    public function buildTokenResponse(Account $account, string $accessToken, string $rawRefreshToken, $profile = null)
    {
        $ttlMinutes = config('jwt.ttl', 60);

        if ($account->role === UserRole::Customer) {
            $account->loadMissing('customer');
        } elseif ($account->role === UserRole::Staff || $account->role === UserRole::Admin) {
            $account->loadMissing('staff');
        }

        // Trích thông tin cá nhân từ profile, bỏ các id nội bộ
        $profileData = null;
        if ($profile) {
            $profileArr = is_array($profile) ? $profile : $profile->toArray();
            $profileData = collect($profileArr)->except(['account_id', 'created_at', 'updated_at'])->toArray();
        }

        return response()->json([
            'message'      => 'Thành công.',
            'token_type'   => 'Bearer',
            'expires_in'   => $ttlMinutes * 60,
            'access_token' => $accessToken,
            'profile'      => $profileData,
        ])
            ->cookie(self::ACCESS_COOKIE, $accessToken, $ttlMinutes, '/', null, false, true, false, 'Strict')
            ->cookie(self::REFRESH_COOKIE, $rawRefreshToken, self::REFRESH_TTL_DAYS * 24 * 60, '/', null, false, true, false, 'Strict');
    }

    /**
     * POST /api/auth/refresh
     *
     * Token Rotation với Reuse Attack Detection:
     * - Mỗi lần refresh → revoke token cũ, phát token mới
     * - Nếu token đã bị revoke mà vẫn dùng lại → nghi ngờ bị đánh cắp
     *   → revoke toàn bộ refresh token của account đó (force logout)
     */
    public function refresh(Request $request)
    {
        $raw = $request->cookie(self::REFRESH_COOKIE);

        if (! $raw) {
            return response()->json(['message' => 'Refresh token không tồn tại.'], 401);
        }

        $hashedToken = hash('sha256', $raw);

        // Tìm record (bất kể is_revoked để phát hiện reuse attack)
        $record = RefreshToken::where('token', $hashedToken)->first();

        if (! $record) {
            // Token giả / không tồn tại trong DB
            return response()
                ->json(['message' => 'Refresh token không hợp lệ.'], 401)
                ->withoutCookie(self::REFRESH_COOKIE)
                ->withoutCookie(self::ACCESS_COOKIE);
        }

        // --- REUSE ATTACK DETECTION ---
        // Nếu token đã bị revoke mà vẫn được dùng lại → có thể bị đánh cắp
        if ($record->is_revoked) {
            // Revoke toàn bộ refresh token của account này (force logout tất cả thiết bị)
            RefreshToken::where('account_id', $record->account_id)
                ->where('is_revoked', false)
                ->update([
                    'is_revoked' => true,
                    'revoked_at' => now(),
                ]);

            return response()
                ->json([
                    'message' => 'Phiên đăng nhập không hợp lệ. Vui lòng đăng nhập lại.',
                    'reason'  => 'token_reuse_detected',
                ], 401)
                ->withoutCookie(self::REFRESH_COOKIE)
                ->withoutCookie(self::ACCESS_COOKIE);
        }

        // Kiểm tra hết hạn
        if ($record->expires_at->isPast()) {
            $record->update(['is_revoked' => true, 'revoked_at' => now()]);

            return response()
                ->json(['message' => 'Refresh token đã hết hạn. Vui lòng đăng nhập lại.'], 401)
                ->withoutCookie(self::REFRESH_COOKIE)
                ->withoutCookie(self::ACCESS_COOKIE);
        }

        // --- ROTATION ---
        // 1. Phát token mới trước
        $account         = $record->account;
        $newAccessToken  = JWTAuth::fromUser($account);
        $newRawRefresh   = Str::random(64);
        $newHashedRefresh = hash('sha256', $newRawRefresh);

        RefreshToken::create([
            'account_id' => $account->id,
            'token'      => $newRawRefresh,    // Model tự hash qua Attribute mutator
            'expires_at' => now()->addDays(self::REFRESH_TTL_DAYS),
            'is_revoked' => false,
        ]);

        // 2. Revoke token cũ, ghi lại token thay thế (audit trail)
        $record->update([
            'is_revoked'        => true,
            'revoked_at'        => now(),
            'replaced_by_token' => $newHashedRefresh,
        ]);

        return $this->buildTokenResponse($account, $newAccessToken, $newRawRefresh);
    }

    /**
     * Revoke refresh token + invalidate JWT access token hiện tại.
     * Được gọi từ AuthController::logout().
     */
    public function revoke(Request $request): void
    {
        $raw = $request->cookie(self::REFRESH_COOKIE);

        if ($raw) {
            RefreshToken::where('token', hash('sha256', $raw))
                ->where('is_revoked', false)
                ->update([
                    'is_revoked' => true,
                    'revoked_at' => now(),
                ]);
        }

        try {
            JWTAuth::parseToken()->invalidate();
        } catch (\Throwable) {
            // Token đã hết hạn hoặc không có – bỏ qua
        }
    }

    /**
     * Lấy Account từ access token trong cookie/header (không throw exception).
     * Dùng nội bộ bởi middleware SilentRefresh.
     */
    public function getAccountFromToken(Request $request): ?Account
    {
        try {
            $token = $request->cookie(self::ACCESS_COOKIE)
                ?? $request->bearerToken();

            if (! $token) {
                return null;
            }

            return JWTAuth::setToken($token)->authenticate();
        } catch (\Throwable) {
            return null;
        }
    }
}
