<?php

namespace App\Http\Controllers;

class OTPController extends Controller
{
    public function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendOTP(string $email): void
    {
        $otpCode = $this->generateOTP();
        $account = Account::where('email', $email)->first();

        $otpRecord = OTP::where('account_id', $account->id)->first();
        if ($otpRecord && now()->lt($otpRecord->expires_at)) {
            throw new Exception('OTP already sent');
        }

        OTP::updateOrCreate(
            ['account_id' => $account->id],
            [
                'code' => $otpCode,
                'expires_at' => now()->addMinutes(5),
                'used' => false,
            ]
        );

        Mail::to($email)->send(new OTPMail($otpCode));
    }

    public function verifyOTP(string $email, string $otpCode): bool
    {
        $account = Account::where('email', $email)->first();
        $otpRecord = OTP::where('account_id', $account->id)->first();

        if (! $otpRecord) {
            return false;
        }

        if ($otpRecord->code !== $otpCode) {
            return false;
        }

        if ($otpRecord->expires_at->lt(now())) {
            return false;
        }

        $otpRecord->update([
            'used' => true,
        ]);

        return true;
    }
}
