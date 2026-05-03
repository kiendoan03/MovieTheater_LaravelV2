<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Events\PaymentCompleted;
use App\Events\SeatStatusChanged;
use App\Services\PayOsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayOsWebhookController extends Controller
{
    protected $payOsService;

    public function __construct(PayOsService $payOsService)
    {
        $this->payOsService = $payOsService;
    }

    /**
     * Webhook handler từ PayOs
     */
    public function handle(Request $request)
    {
        // Verify signature - PayOs sẽ gửi signature trong  header
        // Format: x-signature: hmac-sha256 signature
        $signature = $request->header('x-signature');
        if (!$signature) {
            Log::warning('PayOs webhook missing signature');
            return response()->json(['message' => 'Missing signature'], 401);
        }

        // Lấy body content
        $body = file_get_contents('php://input');
        
        // Verify signature
        if (!$this->payOsService->verifyWebhookSignature($body, $signature)) {
            Log::warning('PayOs webhook invalid signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $data = json_decode($body, true);

        // Log webhook
        Log::info('PayOs webhook received', $data);

        // PayOs webhook data format:
        // {
        //   "code": "00",
        //   "desc": "Success",
        //   "data": {
        //     "orderCode": "TK123456",
        //     "status": "PAID",
        //     "amount": 500000,
        //     ...
        //   }
        // }

        if ($data['code'] !== '00') {
            return response()->json(['message' => 'Webhook code error'], 400);
        }

        $transactionData = $data['data'] ?? [];
        $orderCode = $transactionData['orderCode'] ?? null;  // orderCode = Ticket.code
        $status = $transactionData['status'] ?? null;

        if (!$orderCode) {
            Log::warning('PayOs webhook: Invalid transaction data', ['data' => $transactionData]);
            return response()->json(['message' => 'Invalid transaction data']);
        }

        // Tìm ticket theo code (code chính là orderCode từ PayOs)
        $ticket = Ticket::where('code', $orderCode)->first();
        if (!$ticket) {
            Log::warning('PayOs webhook: Ticket not found', ['ticket_code' => $orderCode]);
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        // Xử lý thanh toán thất bại (CANCELLED, EXPIRED)
        if (in_array($status, ['CANCELLED', 'EXPIRED'])) {
            $this->handlePaymentFailed($ticket);
            return response()->json([
                'message' => 'Payment failed, seats reset',
                'status' => $status,
                'ticket_id' => $ticket->id
            ]);
        }

        // Chỉ xử lý khi thanh toán thành công
        if ($status !== 'PAID') {
            Log::warning('PayOs webhook: Unknown status', ['status' => $status]);
            return response()->json(['message' => 'Unknown payment status']);
        }

        // Cập nhật tất cả booking của ticket thành Reserved (thanh toán thành công)
        Booking::where('ticket_id', $ticket->id)->update([
            'status' => BookingStatus::Reserved->value,
        ]);

        Log::info('PayOs webhook: Bookings updated to Reserved', [
            'ticket_id' => $ticket->id,
        ]);

        // Broadcast event để notify realtime
        broadcast(new PaymentCompleted($ticket))->toOthers();

        return response()->json(['message' => 'Webhook processed successfully', 'ticket_id' => $ticket->id]);
    }

    /**
     * Xử lý khi thanh toán thất bại (hủy, hết hạn)
     * - Update seat status về available (0)
     * - staffId = NULL
     * - ticketId = NULL
     * - Xóa ticket đã tạo
     */
    private function handlePaymentFailed(Ticket $ticket): void
    {
        // Lấy tất cả bookings của ticket trước khi xóa ticket_id
        $bookings = Booking::where('ticket_id', $ticket->id)->get();

        // Update tất cả bookings về available và xóa ticket_id, staff_id, customer_id
        Booking::where('ticket_id', $ticket->id)->update([
            'status' => BookingStatus::Available->value,
            'ticket_id' => null,
            'staff_id' => null,
            'customer_id' => null,
        ]);

        // Broadcast event để cập nhật realtime
        foreach ($bookings as $booking) {
            broadcast(new SeatStatusChanged(
                $booking->schedule_id,
                $booking->seat_id,
                BookingStatus::Available->value,
                null
            ));
        }

        // Xóa ticket đã tạo
        $ticket->delete();

        Log::info('Payment failed (webhook): Seats reset to available, ticket deleted', [
            'ticket_code' => $ticket->code,
            'seat_count' => $bookings->count(),
        ]);
    }
}
