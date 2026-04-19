<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Lấy user hiện tại từ token
        $user = Auth::guard('api')->user();

        if (! $user) {
            return response()->json(['message' => 'Bạn chưa đăng nhập.'], 401);
        }

        // Vì role của sếp đang dùng Enum (trong $casts), phải lấy value của nó ra để so sánh
        $userRole = $user->role->value ?? $user->role;

        // Kiểm tra xem role của user có nằm trong mảng roles được cấp phép không
        if (! in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Ai cho mà vào đây',
            ], 403);
        }

        return $next($request);
    }
}
