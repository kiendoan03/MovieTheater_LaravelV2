<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Lấy access_token từ cookie và gắn vào Authorization header
 * để JWT guard (auth:api) có thể đọc được.
 *
 * Middleware này chỉ gắn header khi chưa có Authorization header sẵn,
 * tránh ghi đè cho các request API đã gửi Bearer token trong header.
 */
class JWTFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('access_token');

        if ($token && ! $request->hasHeader('Authorization')) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}
