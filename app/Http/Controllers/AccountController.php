<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Account;

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
}
