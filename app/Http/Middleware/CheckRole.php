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
            // Nếu là web request thì redirect về trang login, API thì trả JSON
            if (! $request->expectsJson()) {
                return redirect()->route('login');
            }

            return response()->json(['message' => 'Bạn chưa đăng nhập.'], 401);
        }

        // Vì role là int-backed Enum (Admin=0, Staff=1, Customer=2)
        // Route truyền tên role dạng string ('admin', 'staff'), nên phải so sánh bằng tên enum (lowercase)
        // $userRole = strtolower($user->role->name ?? $user->role);
        $role = $user->role;

        // Handle cả enum lẫn raw int
        if ($role instanceof \App\Enums\UserRole) {
            $userRole = strtolower($role->name);
        } else {
            $userRole = strtolower(\App\Enums\UserRole::from((int) $role)->name);
        }
        // dd([
        //     'user' => $user,
        //     'role_raw' => $user?->role,
        //     'role_name' => $user?->role?->name ?? 'KHÔNG CÓ NAME',
        //     'userRole' => strtolower($user?->role?->name ?? $user?->role),
        //     'roles_required' => $roles,
        //     'match' => in_array(strtolower($user?->role?->name ?? $user?->role), $roles),
        // ]);

        // Kiểm tra xem role của user có nằm trong mảng roles được cấp phép không
        if (! in_array($userRole, $roles)) {
            if (! $request->expectsJson()) {
                abort(403, 'Không có quyền truy cập.');
            }

            return response()->json([
                'message' => 'Không hợp lệ',
            ], 403);
        }

        return $next($request);
    }
}
