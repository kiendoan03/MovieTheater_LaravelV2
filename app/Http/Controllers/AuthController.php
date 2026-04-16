<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        private readonly TokenController $token,
    ) {}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $account = Account::where('email', $credentials['email'])->first();

        if (! $account || ! Hash::check($credentials['password'], $account->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng.',
            ], 401);
        }

        $accessToken = JWTAuth::fromUser($account);
        $rawRefreshToken = $this->token->issueRefreshToken($account);

        return $this->token->buildTokenResponse($account, $accessToken, $rawRefreshToken);
    }

    public function register(Request $request, AccountController $accountController, CustomerController $customerController)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
            'isVerify' => 'required|boolean',
        ]);

        if (! $data['isVerify']) {
            return response()->json(['message' => 'Vui lòng xác thực email trước khi đăng ký.'], 400);
        }

        // Double check ở backend: Xác thực xem trong Cache cái email này lúc gửi / check OTP đã thực sự hợp lệ chưa
        $isVerified = \Illuminate\Support\Facades\Cache::get('email_verified_'.$data['email']);

        if (! $isVerified) {
            return response()->json(['message' => 'Email chưa được xác thực OTP hoặc quá thời gian. Bạn là bot hay bị cúc?'], 400);
        }

        // Đã xác nhận ok, xóa bỏ status đã verify cho email này khỏi cache luôn
        \Illuminate\Support\Facades\Cache::forget('email_verified_'.$data['email']);

        $account = DB::transaction(function () use ($data, $accountController, $customerController) {
            $acc = $accountController->createAccount($data);
            $customerController->createCustomer($acc, $data);

            return $acc;
        });

        // Đã gộp thành công, sinh token
        $accessToken = JWTAuth::fromUser($account);
        $rawRefreshToken = $this->token->issueRefreshToken($account);
        $ttlMinutes = config('jwt.ttl', 60);

        // Trả kết quả bóc tách thủ công từ $data tại chính hàm register này, không làm bẩn TokenResponse
        return response()->json([
            'message' => 'Đăng ký tài khoản thành công.',
            'access_token' => $accessToken,
            'customer' => [
                'name' => $data['name'],
                'email' => $data['email'],
                'phonenumber' => $data['phonenumber'],
            ],
        ])
            ->cookie('access_token', $accessToken, $ttlMinutes, '/', null, false, true, false, 'Strict')
            ->cookie('refresh_token', $rawRefreshToken, 30 * 24 * 60, '/', null, false, true, false, 'Strict');
    }
}
