<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin vé Netfnix</title>
</head>
<body style="margin: 0; padding: 0; background-color: #000000; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <div style="background-color: #141414; color: #ffffff; max-width: 600px; margin: 0 auto; padding: 30px 20px; border: 1px solid #333333;">
        
        {{-- HEADER --}}
        <div style="text-align: center; border-bottom: 2px solid #E50914; padding-bottom: 20px; margin-bottom: 30px;">
            <h1 style="color: #E50914; margin: 0; font-size: 32px; text-transform: uppercase; letter-spacing: 3px;">Netfnix</h1>
        </div>

        @php
            $firstBooking = $ticket->bookings->first();
            $movie = $firstBooking->schedule->movie ?? null;
            $schedule = $firstBooking->schedule ?? null;
            $room = $firstBooking->schedule->room ?? null;
        @endphp

        {{-- LỜI CẢM ƠN & MÃ VÉ --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #ffffff; margin-top: 0; margin-bottom: 10px; font-size: 24px;">Cảm ơn bạn đã đặt vé!</h2>
            <p style="color: #cccccc; font-size: 16px; margin: 0;">Mã vé của bạn: <strong style="color: #ffffff; font-size: 20px;">{{ $ticket->code }}</strong></p>
        </div>

        {{-- THÔNG TIN PHIM --}}
        <div style="margin-bottom: 30px; background-color: #222222; padding: 20px; border-radius: 8px;">
            <h3 style="color: #E50914; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #444; padding-bottom: 10px; font-size: 18px;">THÔNG TIN PHIM</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #aaaaaa; width: 40%;">Tên phim:</td>
                    <td style="padding: 8px 0; color: #ffffff; font-weight: bold;">{{ $movie ? $movie->movie_name : 'Đang cập nhật' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #aaaaaa;">Phòng chiếu:</td>
                    <td style="padding: 8px 0; color: #ffffff; font-weight: bold;">{{ $room ? $room->room_name : 'Đang cập nhật' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #aaaaaa;">Giờ chiếu:</td>
                    <td style="padding: 8px 0; color: #ffffff; font-weight: bold;">{{ $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i - d/m/Y') : 'Đang cập nhật' }}</td>
                </tr>
            </table>
        </div>

        {{-- THÔNG TIN GHẾ --}}
        <div style="margin-bottom: 30px; text-align: center;">
            <h3 style="color: #aaaaaa; margin-top: 0; margin-bottom: 15px; font-size: 16px;">GHẾ ĐÃ CHỌN</h3>
            <div>
                @foreach($ticket->bookings as $booking)
                    <span style="display: inline-block; padding: 10px 15px; background-color: #333333; color: #ffffff; border: 1px solid #555555; border-radius: 6px; margin: 0 5px 10px 5px; font-weight: bold; font-size: 16px;">
                        {{ chr(64 + $booking->seat->row) }}{{ $booking->seat->column }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- TỔNG TIỀN --}}
        <div style="text-align: center; margin-bottom: 40px;">
            <p style="margin: 0; font-size: 18px; color: #aaaaaa;">Tổng tiền đã thanh toán</p>
            <p style="margin: 5px 0 0 0; color: #E50914; font-weight: bold; font-size: 28px;">{{ number_format($ticket->final_price, 0, ',', '.') }} VNĐ</p>
        </div>

        {{-- FOOTER --}}
        <div style="text-align: center; font-size: 13px; color: #666666; border-top: 1px solid #333333; padding-top: 20px;">
            <p style="margin-bottom: 5px;">Hệ thống rạp chiếu phim Netfnix Việt Nam</p>
            <p style="margin-bottom: 5px;"><strong style="color: #999999;">Kết nối với chúng tôi</strong></p>
            <p style="margin: 0;">Hỗ trợ: <a href="mailto:netfnixcinema@gmail.com" style="color: #E50914; text-decoration: none;">netfnixcinema@gmail.com</a></p>
        </div>

    </div>
</body>
</html>