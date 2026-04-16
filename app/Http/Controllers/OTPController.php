<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function generateOTP(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:accounts,email', // Validate chưa dk mới cho gửi
        ], [
            'email.unique' => 'Email này đã được đăng ký tài khoản.',
        ]);

        $email = $request->email;
        $otpCode = $this->generateOTP();

        // Theo yêu cầu của bạn, tồn tại trong 300s thì gán cache 300s
        Cache::put('otp_'.$email, $otpCode, now()->addSeconds(300));

        try {
            Mail::to($email)->send(new OTPMail($otpCode));

            return response()->json(['message' => 'Mã OTP đã được gửi về email của bạn.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Lỗi khi gửi email: '.$e->getMessage()], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $email = $request->email;
        $otpCode = $request->otp;

        $cachedOtp = Cache::get('otp_'.$email);

        if (! $cachedOtp) {
            return response()->json(['message' => 'Mã OTP đã hết hạn hoặc không tồn tại.'], 400);
        }

        if ($cachedOtp !== $otpCode) {
            return response()->json(['message' => 'Mã OTP không chính xác.'], 400);
        }

        // OTP đúng, ta xóa otp cũ
        Cache::forget('otp_'.$email);

        // Lưu lại cờ chứng nhận là email này đã được xác thực (chờ user submit Form đăng ký)
        // Cho tồn tại 15 phút để thoải mái đăng ký
        Cache::put('email_verified_'.$email, true, now()->addMinutes(15));

        return response()->json([
            'message' => 'Xác thực email thành công.',
            'isVerify' => true,
        ]);
    }
}
