<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Services\PayOsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TicketBookingController extends Controller
{
    protected $payOsService;

    public function __construct(PayOsService $payOsService)
    {
        $this->payOsService = $payOsService;
    }

    /**
     * Hiển thị danh sách phim đang chiếu
     */
    public function index()
    {
        $now = Carbon::now();

        // Lấy phim có lịch chiếu hiện tại hoặc tương lai
        $movies = Movie::whereHas('schedules', function ($query) use ($now) {
            $query->where('start_time', '>=', $now);
        })
            ->orderBy('movie_name')
            ->get();

        return view('admin.TicketBooking.movies-list', compact('movies'));
    }

    /**
     * Hiển thị danh sách lịch chiếu cho một phim
     */
    public function schedules($movieId)
    {
        $now = Carbon::now();

        $movie = Movie::with(['schedules' => function ($query) use ($now) {
            $query->where('start_time', '>=', $now)
                ->with('room')
                ->orderBy('start_time');
        }])->findOrFail($movieId);

        if ($movie->schedules->isEmpty()) {
            return response()->json(['message' => 'Không có lịch chiếu'], 404);
        }

        return view('admin.TicketBooking.schedules-list', compact('movie'));
    }

    /**
     * Hiển thị sơ đồ ghế của một lịch chiếu
     */
    public function seatLayout($scheduleId)
    {
        $schedule = Schedule::with(['movie', 'room.seats.seatType'])
            ->findOrFail($scheduleId);

        // Lấy thông tin booking của schedule này
        $bookings = Booking::where('schedule_id', $scheduleId)
            ->with(['seat', 'seat.seatType'])
            ->get();

        // Map thông tin booking theo seat_id
        $bookingMap = [];
        foreach ($bookings as $booking) {
            $bookingMap[$booking->seat_id] = [
                'status' => $booking->status,
                'staff_id' => $booking->staff_id,
                'price' => $booking->seat->seatType->price ?? 0,
            ];
        }

        $seats = $schedule->room->seats->map(function ($seat) use ($bookingMap) {
            return [
                'id' => $seat->id,
                'row' => $seat->row,
                'column' => $seat->column,
                'type_id' => $seat->type_id,
                'type_name' => $seat->seatType?->type ?? 'Lối đi',
                'price' => $seat->seatType?->price ?? 0,
                'color' => $seat->seatType?->color ?? 'transparent',
                'is_couple' => $seat->seatType?->is_couple ?? false,
                'booking_status' => $bookingMap[$seat->id]['status'] ?? BookingStatus::Available->value,
                'staff_id' => $bookingMap[$seat->id]['staff_id'] ?? null,
            ];
        });

        // $currentStaffId = auth('staff')->user()?->id;
        $user = auth('api')->user();
        $currentStaffId = $user?->staff?->id ?? $user?->id;


        return view('admin.TicketBooking.seat-layout', compact(
            'schedule',
            'seats',
            'currentStaffId'
        ));
    }

    /**
     * API: Lấy thông tin chi tiết lịch chiếu (cho AJAX)
     * Returns ALL seats from room with their booking status
     */
    public function getScheduleSeats($scheduleId)
    {
        $schedule = Schedule::with(['movie', 'room.seats.seatType'])
            ->findOrFail($scheduleId);

        // Get all bookings for this schedule
        $bookings = Booking::where('schedule_id', $scheduleId)
            ->get()
            ->keyBy('seat_id');

        // Map ALL seats from room + booking info
        $seats = $schedule->room->seats->map(function ($seat) use ($bookings) {
            $booking = $bookings->get($seat->id);

            return [
                'id' => $seat->id,
                'row' => $seat->row,
                'column' => $seat->column,
                'type_id' => $seat->type_id,
                'type_name' => $seat->seatType?->type ?? 'Lối đi',
                'price' => $seat->seatType?->price ?? 0,
                'color' => $seat->seatType?->color ?? 'transparent',
                'is_couple' => $seat->seatType?->is_couple ?? false,    
                'status' => $booking?->status ?? BookingStatus::Available->value,
                'staff_id' => $booking?->staff_id ?? null,
            ];
        })->values();

        return response()->json([
            'schedule_id' => $schedule->id,
            'movie_name' => $schedule->movie->movie_name,
            'start_time' => $schedule->start_time->format('H:i'),
            'room_name' => $schedule->room->room_name,
            'seats' => $seats,
        ]);
    }

    /**
     * API: Update trạng thái ghế (booking)
     */
    public function updateSeatStatus(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_id' => 'required|exists:seats,id',
            'status' => 'required|integer|in:0,1,2',
        ]);

        $user = auth('api')->user();
        $staffId = $user?->staff?->id ?? $user?->id;

        if (! $staffId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Tìm hoặc tạo booking record
        $booking = Booking::firstOrCreate(
            [
                'schedule_id' => $request->schedule_id,
                'seat_id' => $request->seat_id,
            ],
            [
                'status' => BookingStatus::Available->value,
            ]
        );

        // Cập nhật status và staff_id
        $booking->update([
            'status' => $request->status,
            'staff_id' => $staffId,
        ]);

        // Broadcast event để update realtime
        broadcast(new \App\Events\SeatStatusChanged(
            $request->schedule_id,
            $request->seat_id,
            $request->status,
            $staffId
        ))->toOthers();

        return response()->json([
            'message' => 'Ghế đã cập nhật',
            'booking' => $booking,
        ]);
    }

    /**
     * Lấy tổng tiền từ danh sách ghế đã chọn
     */
    public function calculateTotal(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $total = Seat::whereIn('seats.id', $request->seat_ids)
            ->join('seat_types', 'seats.type_id', '=', 'seat_types.id')
            ->sum('seat_types.price');

        return response()->json([
            'total' => number_format($total, 0, ',', '.'),
            'amount' => (int) $total,
        ]);
    }

    /**
     * Tạo ticket từ bookings đã chọn (thanh toán tiền mặt)
     */
    public function createTicketCash(Request $request)
    {
        // dd([
        //     'request_data' => $request->all(),
        //     'staff_id' => $this->resolveStaffId(),
        //     'user_api' => auth('api')->user()?->id,
        // ]);
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $staffId = $this->resolveStaffId();
        if (! $staffId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Tính tổng tiền
        $total = Seat::whereIn('seats.id', $request->seat_ids)
            ->join('seat_types', 'seats.type_id', '=', 'seat_types.id')
            ->sum('seat_types.price');
        // Tạo ticket
        $ticket = Ticket::create([
            'code' => 'TK'.time().random_int(1000, 9999),
            'final_price' => $total,
            'staff_id' => $staffId,
            'customer_id' => null, // Không có customer (đặt tại quầy)
        ]);

        // Update bookings
        Booking::where('schedule_id', $request->schedule_id)
            ->whereIn('seat_id', $request->seat_ids)
            ->update([
                'ticket_id' => $ticket->id,
                'status' => BookingStatus::Reserved->value,
                'staff_id' => $staffId,
            ]);

        return response()->json([
            'message' => 'Tạo vé thành công',
            'ticket' => $ticket,
        ]);
    }

    /**
     * Khởi tạo thanh toán qua PayOs
     */
    public function initPaymentPayOs(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        // Support both staff guard (session) and api guard (JWT)
        $staffId = $this->resolveStaffId();
        if (! $staffId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Tính tổng tiền
        $total = Seat::whereIn('seats.id', $request->seat_ids)
            ->join('seat_types', 'seats.type_id', '=', 'seat_types.id')
            ->sum('seat_types.price');

        // Tạo ticket (chưa thanh toán)
        $ticket = Ticket::create([
            'code' => 'TK'.time().random_int(1000, 9999),
            'final_price' => $total,
            'staff_id' => $staffId,
        ]);

        // Cập nhật bookings
        Booking::where('schedule_id', $request->schedule_id)
            ->whereIn('seat_id', $request->seat_ids)
            ->update([
                'ticket_id' => $ticket->id,
                'status' => BookingStatus::Booked->value,
                'staff_id' => $staffId,
            ]);

        // Gọi PayOs API để tạo QR code
        // Dùng ticket.code làm order code
        $returnUrl = route('admin.ticket-booking.payment-status', ['ticketCode' => $ticket->code]);

        $qrResponse = $this->payOsService->createQRCode(
            $ticket->code,  // Code từ ticket
            (int) $total,
            "Mua vé xem phim - {$ticket->code}",
            $returnUrl
        );

        if (! isset($qrResponse['success']) || ! $qrResponse['success']) {
            // Xóa ticket nếu tạo QR thất bại
            $ticket->delete();

            return response()->json([
                'message' => 'Lỗi khi tạo mã QR',
                'error' => $qrResponse['message'] ?? 'Unknown error',
            ], 400);
        }

        return response()->json([
            'ticket_code' => $ticket->code,
            'ticket_id' => $ticket->id,
            'amount' => (int) $total,
            'qr_data' => $qrResponse['data'] ?? null,
            'checkout_url' => $qrResponse['data']['checkoutUrl'] ?? null,
            'message' => 'QR code tạo thành công',
        ]);
    }

    /**
     * Lấy vé đã tạo
     */
    public function getTicket($ticketCode)
    {
        $ticket = Ticket::with(['bookings.seat.seatType', 'staff'])
            ->where('code', $ticketCode)
            ->firstOrFail();
            
        return response()->json([
            'ticket' => $ticket,
            'bookings' => $ticket->bookings,
        ]);
    }

    /**
     * Kiểm tra trạng thái thanh toán từ PayOs
     */
    public function checkPaymentStatus($ticketCode)
    {
        $ticket = Ticket::where('code', $ticketCode)->firstOrFail();

        // Kiểm tra xem booking đã Reserved chưa (webhook đã xử lý)
        $bookingsReserved = Booking::where('ticket_id', $ticket->id)
            ->where('status', BookingStatus::Reserved->value)
            ->count();

        $totalBookings = Booking::where('ticket_id', $ticket->id)->count();

        if ($bookingsReserved === $totalBookings && $totalBookings > 0) {
            // Đã thanh toán (webhook đã xử lý hoặc cash payment)
            return response()->json([
                'message' => 'Thanh toán thành công',
                'status' => 'completed',
                'ticket' => $ticket->load('bookings.seat.type'),
            ]);
        }

        // Kiểm tra trạng thái với PayOs (polling)
        $paymentStatus = $this->payOsService->getTransactionStatus($ticket->code);

        if (isset($paymentStatus['data'])) {
            $transactionStatus = $paymentStatus['data']['status'] ?? null;

            // Nếu đã thanh toán ở PayOs, cập nhật booking
            if ($transactionStatus === 'PAID' || $transactionStatus === 'COMPLETED') {
                // Cập nhật tất cả booking của ticket thành Reserved
                Booking::where('ticket_id', $ticket->id)->update([
                    'status' => BookingStatus::Reserved->value,
                ]);

                // Broadcast event để notify realtime
                broadcast(new \App\Events\PaymentCompleted($ticket))->toOthers();

                return response()->json([
                    'message' => 'Thanh toán thành công',
                    'status' => 'completed',
                    'ticket' => $ticket->load('bookings.seat.type'),
                ]);
            } elseif ($transactionStatus === 'CANCELLED') {
                return response()->json([
                    'message' => 'Thanh toán bị hủy',
                    'status' => 'cancelled',
                ]);
            }
        }

        return response()->json([
            'message' => 'Thanh toán đang chờ xử lý',
            'status' => 'pending',
        ]);
    }

    /**
     * Web route: Hiển thị trang kết quả thanh toán
     */
    public function paymentStatus($ticketCode)
    {
        $ticket = Ticket::where('code', $ticketCode)->firstOrFail();

        return view('admin.TicketBooking.payment-status', compact('ticket'));
    }

    private function resolveStaffId(): ?int
    {
        // JWT API guard
        $user = auth('api')->user();
        if ($user) {
            return $user->staff?->id ?? $user->id;
        }

        // Session guard (web blade)
        return auth('staff')->user()?->id;
    }
}
