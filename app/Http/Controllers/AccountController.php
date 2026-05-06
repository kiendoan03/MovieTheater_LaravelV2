<?php

namespace App\Http\Controllers;

use App\Const\Regex;
use App\Enums\UserRole;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct(
        private readonly TokenController $token,
    ) {}

    public function createAccount(array $data): Account
    {
        return Account::create([
            'email' => $data['email'],
            'password' => $data['password'], // tự hash qua $casts
            'role' => UserRole::Customer,
        ]);
    }

    public function deactivateAccount($id)
    {
        $account = Account::findOrFail($id);
        $account->update(['is_active' => false]);
    }

    public function activateAccount($id)
    {
        $account = Account::findOrFail($id);
        $account->update(['is_active' => true]);
    }

    public function changePasswordForm()
    {
        return view('admin.Account.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'regex:'.Regex::PASSWORD, 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.regex' => 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường và số.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $account = auth('api')->user();

        if (! $account) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        if (! Hash::check($request->current_password, $account->password)) {
            return back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])
                ->withInput();
        }

        $account->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Reset mật khẩu sau khi xác thực OTP quên mật khẩu.
     * Không cần mật khẩu cũ — kiểm tra cờ Cache thay thế.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'        => 'required|email|exists:accounts,email',
            'new_password' => ['required', 'string', 'regex:'.Regex::PASSWORD, 'confirmed'],
        ], [
            'email.exists'           => 'Email không tồn tại.',
            'new_password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'new_password.regex'     => 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường và số.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if (! Cache::get('pwd_reset_verified_'.$request->email)) {
            return response()->json(['message' => 'Phiên xác thực OTP đã hết hạn hoặc không hợp lệ.'], 403);
        }

        $account = Account::where('email', $request->email)->firstOrFail();
        $account->update([
            'password' => Hash::make($request->new_password),
        ]);

        Cache::forget('pwd_reset_verified_'.$request->email);

        return response()->json(['message' => 'Đặt lại mật khẩu thành công!']);
    }
}
