<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailInfoTicketController extends Controller
{
    public function sendInfoTicket(Request $request)
    {
        // Thêm validate cho ticket_code
        $request->validate([
            'email' => 'required|email', // Lưu ý: Nếu gửi vé cho khách đã có tài khoản, bạn nên cân nhắc bỏ unique:accounts,email
            'ticket_code' => 'required|string|exists:tickets,code'
        ]);

        $email = $request->email;
        
        // Truy vấn lấy Ticket kèm theo toàn bộ thông tin Phim, Lịch chiếu, Phòng và Ghế
        $ticket = Ticket::with([
            'bookings.schedule.movie',
            'bookings.schedule.room',
            'bookings.seat.seatType'
        ])->where('code', $request->ticket_code)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Không tìm thấy thông tin vé.'], 404);
        }

        try {
            // Truyền đối tượng $ticket đã đầy đủ thông tin vào Mail
            Mail::to($email)->send(new TicketMail($ticket));

            return response()->json(['message' => 'Thông tin vé đã được gửi về email của bạn.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Lỗi khi gửi email: '.$e->getMessage()], 500);
        }
    }
}