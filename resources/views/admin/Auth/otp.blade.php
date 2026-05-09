<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác thực OTP - Netfnix</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0c0c0c;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #ffffff;
        }

        .email-container {
            max-width: 580px;
            margin: 40px auto;
            background-color: #141414;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            border: 1px solid #2a2a2a;
        }

        .email-header {
            background-color: #000000;
            padding: 30px 0;
            text-align: center;
            border-bottom: 2px solid #e50914;
        }

        .email-title {
            color: #e50914;
            margin: 0;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .email-body {
            padding: 40px 35px;
            text-align: center;
        }

        .email-body h2 {
            margin-top: 0;
            color: #ffffff;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .email-body p {
            color: #b3b3b3;
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }

        .otp-wrapper {
            background: linear-gradient(145deg, #1e1e1e, #181818);
            border: 1px solid #333333;
            border-radius: 12px;
            padding: 25px;
            margin: 35px 0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .otp-label {
            color: #8c8c8c;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            display: block;
        }

        .otp-code {
            color: #ffffff;
            letter-spacing: 12px;
            font-size: 48px;
            font-weight: 800;
            margin: 0;
            text-shadow: 0 0 15px rgba(229, 9, 20, 0.3);
        }

        .notice {
            background-color: rgba(229, 9, 20, 0.1);
            border-left: 3px solid #e50914;
            padding: 10px 15px;
            border-radius: 4px;
            margin: 25px 0;
            text-align: left;
        }

        .notice p {
            font-size: 14px;
            color: #cccccc;
            margin: 0;
        }

        .email-footer {
            background-color: #0a0a0a;
            padding: 30px;
            color: #0b0b0bff;
            font-size: 13px;
            line-height: 1.8;
            text-align: center;
            border-top: 1px solid #222222;
        }

        .email-footer h3 {
            margin: 0 0 10px 0;
            font-weight: bold;
            font-size: 15px;
            color: #999999;
        }

        .email-footer a {
            color: #e50914;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: #333;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1 class="email-title">NETFNIX</h1>
        </div>

        <div class="email-body">
            <h2>Xác thực tài khoản của bạn</h2>
            <p>Xin chào,</p>
            <p>Cảm ơn bạn đã lựa chọn NETFNIX Cinema. Để hoàn tất quy trình đăng ký, vui lòng sử dụng mã xác thực dưới
                đây:</p>

            <div class="otp-wrapper">
                <span class="otp-label">Mã OTP của bạn</span>
                <h1 class="otp-code">{{ $otpCode }}</h1>
            </div>

            <div class="notice">
                <p>Mã này chỉ có hiệu lực trong <strong>60 giây</strong>. Không chia sẻ mã này cho bất kỳ ai.</p>
            </div>

            <p style="font-size: 14px;">Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
        </div>

        <div class="email-footer">
            <p>Hệ thống gửi tự động từ <strong>NETFNIX Cinema Vietnam</strong></p>
            <h3>Kết nối với chúng tôi</h3>
            <p>Hỗ trợ: <a href="mailto:{{ \App\Const\MailAddress::MAIL }}">{{ \App\Const\MailAddress::MAIL }}</a> |
                Hotline: 1900 xxxx</p>
            <p style="margin-top: 15px;">© {{ date('Y') }} NETFNIX Cinema. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
