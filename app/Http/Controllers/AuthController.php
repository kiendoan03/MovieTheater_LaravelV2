<?php

namespace App\Http\Controllers;

use App\Const\Regex;
use App\Enums\UserRole;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        private readonly TokenController $token,
    ) {}

    public function showLoginForm()
    {
        return view('admin.Auth.login');
    }

    public function showRegisterForm()
    {
        return view('admin.Auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $account = Account::where('email', $credentials['email'])->first();

        if (! $account || ! Hash::check($credentials['password'], $account->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng',
            ], 401);
        }

        if (! $account->is_active) {
            return response()->json([
                'message' => 'Tài khoản không hoạt động',
            ], 403);
        }

        // Load profile tùy theo role
        $profile = null;
        if ($account->role === UserRole::Customer) {
            $account->load('customer');
            $profile = $account->customer;
        } elseif ($account->role === UserRole::Staff) {
            $account->load('staff');
            $profile = $account->staff;
        } else {
            // Admin — có thể không có bản ghi staff/customer, trả về thông tin cơ bản
            $profile = ['email' => $account->email, 'role' => 'admin'];
        }

        $accessToken = JWTAuth::fromUser($account);
        $rawRefreshToken = $this->token->issueRefreshToken($account);

        return $this->token->buildTokenResponse($account, $accessToken, $rawRefreshToken, $profile);
    }

    public function register(Request $request) // Xóa bỏ AccountController và CustomerController ở đây
    {
        $data = $request->validate([
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string|max:255',
            'phonenumber' => ['required', 'string', 'max:20', 'regex:'.Regex::PHONE_NUMBER],
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
        ]);

        // Kiểm tra email đã xác thực OTP chưa (set bởi /verify-otp)
        if (! Cache::get('email_verified_'.$data['email'])) {
            return response()->json(['message' => 'Vui lòng xác thực email bằng OTP trước khi đăng ký'], 400);
        }

        Cache::forget('email_verified_'.$data['email']);

        // Gộp logic tạo dữ liệu vào đây bằng Eloquent Relationship
        $account = DB::transaction(function () use ($data) {
            // 1. Tạo Account trước
            $acc = Account::create([
                'email' => $data['email'],
                'password' => $data['password'], // Trong Model đã có casts hashed nên cứ tự tin ném raw password vào
                'role' => UserRole::Customer,    // Đăng ký ngoài form mặc định là Customer
                'is_active' => true,             // Mặc định cho phép hoạt động luôn
            ]);

            // 2. Tạo Customer ăn theo Account_id siêu gọn nhẹ
            $acc->customer()->create([
                'name' => $data['name'],
                'phonenumber' => $data['phonenumber'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
            ]);

            return $acc;
        });

        // Đã gộp thành công, sinh token
        $accessToken = JWTAuth::fromUser($account);
        $rawRefreshToken = $this->token->issueRefreshToken($account);

        // Bóc tách data để trả về cho Frontend (như bạn đã làm)
        $customerProfile = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phonenumber' => $data['phonenumber'],
        ];

        if ($request->expectsJson()) {
            // Tái sử dụng hàm buildTokenResponse, truyền thẳng profile vào!
            return $this->token->buildTokenResponse($account, $accessToken, $rawRefreshToken, $customerProfile);
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    public function logout(Request $request)
    {
        $this->token->revoke($request);

        if ($request->expectsJson()) {
            return response()
                ->json(['message' => 'Đăng xuất thành công.'])
                ->withoutCookie(TokenController::REFRESH_COOKIE)
                ->withoutCookie('access_token');
        }

        return redirect()->route('login')
            ->withoutCookie(TokenController::REFRESH_COOKIE)
            ->withoutCookie('access_token');
    }

    public function addStaffAccount(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string|max:255',
            'phonenumber' => ['required', 'string', 'max:20', 'regex:'.Regex::PHONE_NUMBER],
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
        ]);

        // Kiểm tra email đã xác thực OTP chưa (set bởi /verify-otp)
        if (! Cache::get('email_verified_'.$data['email'])) {
            return response()->json(['message' => 'Vui lòng xác thực email bằng OTP trước khi đăng ký'], 400);
        }

        Cache::forget('email_verified_'.$data['email']);

        // Gộp logic tạo dữ liệu vào đây bằng Eloquent Relationship
        $account = DB::transaction(function () use ($data) {
            // 1. Tạo Account trước
            $acc = Account::create([
                'email' => $data['email'],
                'password' => $data['password'], // Trong Model đã có casts hashed nên cứ tự tin ném raw password vào
                'role' => UserRole::Staff,    // Đăng ký ngoài form mặc định là Customer
                'is_active' => true,             // Mặc định cho phép hoạt động luôn
            ]);

            // 2. Tạo Customer ăn theo Account_id siêu gọn nhẹ
            $acc->customer()->create([
                'name' => $data['name'],
                'phonenumber' => $data['phonenumber'],
                'address' => $data['address'],
                'date_of_birth' => $data['date_of_birth'],
            ]);

            return $acc;
        });

        // Đã gộp thành công, sinh token
        $accessToken = JWTAuth::fromUser($account);
        $rawRefreshToken = $this->token->issueRefreshToken($account);

        // Bóc tách data để trả về cho Frontend (như bạn đã làm)
        $customerProfile = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phonenumber' => $data['phonenumber'],
        ];

        if ($request->expectsJson()) {
            // Tái sử dụng hàm buildTokenResponse, truyền thẳng profile vào!
            return $this->token->buildTokenResponse($account, $accessToken, $rawRefreshToken, $customerProfile);
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}
