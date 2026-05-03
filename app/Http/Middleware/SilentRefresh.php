<?php

namespace App\Http\Middleware;

use App\Http\Controllers\TokenController;
use App\Models\RefreshToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

/**
 * Silent Refresh Middleware
 *
 * Khi access token còn ít hơn REFRESH_THRESHOLD_MINUTES, middleware sẽ:
 *  1. Lấy refresh token từ cookie
 *  2. Validate & rotate refresh token
 *  3. Gắn access token mới vào Authorization header cho request hiện tại
 *  4. Set 2 cookie mới vào response
 *
 * Frontend KHÔNG cần tự gọi /api/auth/refresh — mọi thứ đều transparent.
 */
class SilentRefresh
{
    /**
     * Bao nhiêu phút còn lại trong access token thì bắt đầu silent refresh.
     * Đặt bằng khoảng 1/4 ~ 1/3 JWT_TTL để đủ buffer.
     */
    private const REFRESH_THRESHOLD_MINUTES = 5;

    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->cookie(TokenController::ACCESS_COOKIE)
            ?? $request->bearerToken();

        // Không có access token → để auth:api tự xử lý (sẽ trả 401)
        if (! $accessToken) {
            return $next($request);
        }

        // Kiểm tra access token có sắp hết hạn không
        $shouldRefresh = $this->isTokenExpiringSoon($accessToken);

        if (! $shouldRefresh) {
            return $next($request);
        }

        // Thử silent refresh
        $rawRefresh = $request->cookie(TokenController::REFRESH_COOKIE);

        if (! $rawRefresh) {
            return $next($request);
        }

        $hashedToken = hash('sha256', $rawRefresh);
        $record = RefreshToken::where('token', $hashedToken)
            ->where('is_revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            // Refresh token không hợp lệ → để request đi qua bình thường
            return $next($request);
        }

        // Rotation: phát token mới
        $account        = $record->account;
        $newAccessToken = JWTAuth::fromUser($account);
        $newRawRefresh  = Str::random(64);
        $newHashedRefresh = hash('sha256', $newRawRefresh);

        RefreshToken::create([
            'account_id' => $account->id,
            'token'      => $newRawRefresh,
            'expires_at' => now()->addDays(30),
            'is_revoked' => false,
        ]);

        $record->update([
            'is_revoked'        => true,
            'revoked_at'        => now(),
            'replaced_by_token' => $newHashedRefresh,
        ]);

        // Gắn access token mới vào request để auth:api nhận ra ngay
        $request->headers->set('Authorization', 'Bearer ' . $newAccessToken);

        $ttlMinutes = config('jwt.ttl', 60);

        /** @var Response $response */
        $response = $next($request);

        // Gắn cookie mới vào response
        $response->headers->setCookie(
            cookie(TokenController::ACCESS_COOKIE, $newAccessToken, $ttlMinutes, '/', null, false, true, false, 'Strict')
        );
        $response->headers->setCookie(
            cookie(TokenController::REFRESH_COOKIE, $newRawRefresh, 30 * 24 * 60, '/', null, false, true, false, 'Strict')
        );

        // Đánh dấu cho frontend biết token đã được làm mới (optional header)
        $response->headers->set('X-Token-Refreshed', 'true');

        return $response;
    }

    /**
     * Kiểm tra JWT có còn ≤ REFRESH_THRESHOLD_MINUTES phút sống hay không.
     * Trả true cả khi token đã expired (để còn kịp dùng refresh token).
     */
    private function isTokenExpiringSoon(string $token): bool
    {
        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            $exp     = $payload->get('exp'); // Unix timestamp
            $remaining = $exp - now()->timestamp; // giây còn lại

            return $remaining <= (self::REFRESH_THRESHOLD_MINUTES * 60);
        } catch (TokenExpiredException) {
            // Token đã hết hạn hoàn toàn → cần refresh
            return true;
        } catch (\Throwable) {
            // Token invalid/giả → không refresh
            return false;
        }
    }
}
