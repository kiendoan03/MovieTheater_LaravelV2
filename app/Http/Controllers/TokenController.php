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
            'token' => $raw,
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
            'message' => 'Thành công.',
            'token_type' => 'Bearer',
            'expires_in' => $ttlMinutes * 60,
            'access_token' => $accessToken,
            'profile' => $profileData,
        ])
            ->cookie(self::ACCESS_COOKIE, $accessToken, $ttlMinutes, '/', null, false, true, false, 'Strict')
            ->cookie(self::REFRESH_COOKIE, $rawRefreshToken, self::REFRESH_TTL_DAYS * 24 * 60, '/', null, false, true, false, 'Strict');
    }

    /**
     * POST /api/auth/refresh
     */
    public function refresh(Request $request)
    {
        $raw = $request->cookie(self::REFRESH_COOKIE);

        if (! $raw) {
            return response()->json(['message' => 'Refresh token không tồn tại.'], 401);
        }

        $record = RefreshToken::whereToken($raw)
            ->where('is_revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            return response()->json(['message' => 'Refresh token không hợp lệ hoặc đã hết hạn.'], 401);
        }

        // Revoke token cũ
        $record->update([
            'is_revoked' => true,
            'revoked_at' => now(),
        ]);

        // Phát cặp token mới
        $account = $record->account;
        $newAccessToken = JWTAuth::fromUser($account);
        $newRefreshToken = $this->issueRefreshToken($account);

        return $this->buildTokenResponse($account, $newAccessToken, $newRefreshToken);
    }

    public function revoke(Request $request)
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
}
