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
        ]);

        $account = DB::transaction(function () use ($data, $accountController, $customerController) {
            $acc = $accountController->createAccount($data);
            $customerController->createCustomer($acc, $data);

            return $acc;
        });

        // Đã gộp thành công, sinh token và trả về luôn giống như login
        $accessToken = JWTAuth::fromUser($account);
        $rawRefreshToken = $this->token->issueRefreshToken($account);

        return $this->token->buildTokenResponse($account, $accessToken, $rawRefreshToken);
    }
}
