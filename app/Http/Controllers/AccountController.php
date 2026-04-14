<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountController extends Controller
{
    public function __construct(
        private readonly TokenController $token,
    ) {}

    public function createAccount(array $data): Account
    {
        return Account::create([
            'email'    => $data['email'],
            'password' => $data['password'], // tự hash qua $casts
            'role'     => UserRole::Customer,
        ]);
    }
}
