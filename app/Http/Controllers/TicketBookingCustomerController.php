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
use Illuminate\Support\Facades\Log;
use App\Mail\TicketMail; // Thêm dòng này
use Illuminate\Support\Facades\Mail; // Thêm dòng này

class TicketBookingCustomerController extends Controller
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
        // $now = Carbon::now();

        // // Lấy phim có lịch chiếu hiện tại hoặc tương lai
        // $movies = Movie::whereHas('schedules', function ($query) use ($now) {
        //     $query->where('start_time', '>=', $now);
        // })
        //     ->orderBy('movie_name')
        //     ->get();

        // return view('admin.TicketBooking.movies-list', compact('movies'));
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
    public function seatLayoutCustomer($scheduleId)
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
                'customer_id' => $booking->customer_id,
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
                'customer_id' => $bookingMap[$seat->id]['customer_id'] ?? null,
            ];
        });

        // $currentStaffId = auth('staff')->user()?->id;
        $currentCustomer = auth('api')->user();
        $currentCustomerId = $currentCustomer?->customer?->id ?? $currentCustomer?->id;


        return view('customer.orderTickets', compact(
            'schedule',
            'seats',
            'currentCustomerId'
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
                'customer_id' => $booking?->customer_id ?? null,
            ];
        })->values();

        return response()->json([
            'schedule_id' => $schedule->id,
            'movie_name' => $schedule->movie->movie_name,
            'poster' => $schedule->movie->poster,
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
        $customerId = $user?->customer?->id ?? $user?->id;
        
        if (! $customerId) {
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

        // Cập nhật status và customer_id
        $booking->update([
            'status' => $request->status,
            'customer_id' => $customerId = $request->status == BookingStatus::Available->value ? null : $customerId, // Nếu set lại thành Available thì xóa customer_id
        ]);

        // Broadcast event để update realtime
        broadcast(new \App\Events\SeatStatusChangedCustomer(
            $request->schedule_id,
            $request->seat_id,
            $request->status,
            $customerId
        ));
        // ))->toOthers();

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
        //     'customer_id' => $this->resolveStaffId(),
        //     'user_api' => auth('api')->user()?->id,
        // ]);
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $customerId = $this->resolveCustomerId();
        if (! $customerId) {
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
            'customer_id' => $customerId,
            // 'customer_id' => null, // Không có customer (đặt tại quầy)
        ]);

        // Update bookings
        Booking::where('schedule_id', $request->schedule_id)
            ->whereIn('seat_id', $request->seat_ids)
            ->update([
                'ticket_id' => $ticket->id,
                'status' => BookingStatus::Reserved->value,
                'customer_id' => $customerId,
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
        'seat_ids'    => 'required|array',
        'seat_ids.*'  => 'exists:seats,id',
    ]);

    // ✅ Không bắt buộc login — khách vãng lai vẫn đặt được
    $customerId = $this->resolveCustomerId(); // null nếu chưa login, không chặn

    $total = Seat::whereIn('seats.id', $request->seat_ids)
        ->join('seat_types', 'seats.type_id', '=', 'seat_types.id')
        ->sum('seat_types.price');

    $ticket = Ticket::create([
        'code'        => 'TK' . time() . random_int(1000, 9999),
        'final_price' => $total,
        'customer_id' => $customerId, // null nếu khách vãng lai — OK
    ]);

    // ✅ Tạo booking records trước khi update (phòng trường hợp chưa có)
    foreach ($request->seat_ids as $seatId) {
        Booking::firstOrCreate(
            ['schedule_id' => $request->schedule_id, 'seat_id' => $seatId],
            ['status' => BookingStatus::Available->value]
        );
    }

    Booking::where('schedule_id', $request->schedule_id)
        ->whereIn('seat_id', $request->seat_ids)
        ->update([
            'ticket_id'   => $ticket->id,
            'status'      => BookingStatus::Booked->value,
            'customer_id' => $customerId,
        ]);

    // ✅ Đổi returnUrl về route của customer
    $returnUrl = route('customer.customer.payment-status', ['ticketCode' => $ticket->code]);

    $qrResponse = $this->payOsService->createQRCode(
        $ticket->code,
        (int) $total,
        "Mua vé xem phim - {$ticket->code}",
        $returnUrl
    );

    if (!isset($qrResponse['success']) || !$qrResponse['success']) {
        $ticket->delete();
        return response()->json([
            'message' => 'Lỗi khi tạo mã QR',
            'error'   => $qrResponse['message'] ?? 'Unknown error',
        ], 400);
    }

    return response()->json([
        'ticket_code'  => $ticket->code,
        'ticket_id'    => $ticket->id,
        'amount'       => (int) $total,
        'qr_data'      => $qrResponse['data'] ?? null,
        'checkout_url' => $qrResponse['data']['checkoutUrl'] ?? null,
        'message'      => 'QR code tạo thành công',
    ]);
}

    /**
     * Lấy vé đã tạo
     */
    public function getTicket($ticketCode)
    {
        $ticket = Ticket::with(['bookings.seat.seatType', 'customer'])
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
    // public function checkPaymentStatus($ticketCode)
    // {
    //     $ticket = Ticket::where('code', $ticketCode)->firstOrFail();

    //     // Kiểm tra xem booking đã Reserved chưa (webhook đã xử lý)
    //     $bookingsReserved = Booking::where('ticket_id', $ticket->id)
    //         ->where('status', BookingStatus::Reserved->value)
    //         ->count();

    //     $totalBookings = Booking::where('ticket_id', $ticket->id)->count();

    //     if ($bookingsReserved === $totalBookings && $totalBookings > 0) {
    //         // Đã thanh toán (webhook đã xử lý hoặc cash payment)
    //         return response()->json([
    //             'message' => 'Thanh toán thành công',
    //             'status' => 'completed',
    //             'ticket' => $ticket->load('bookings.seat.type'),
    //         ]);
    //     }

    //     // Kiểm tra trạng thái với PayOs (polling)
    //     $paymentStatus = $this->payOsService->getTransactionStatus($ticket->code);

    //     if (isset($paymentStatus['data'])) {
    //         $transactionStatus = $paymentStatus['data']['status'] ?? null;

    //         // Nếu đã thanh toán ở PayOs, cập nhật booking
    //         if ($transactionStatus === 'PAID' || $transactionStatus === 'COMPLETED') {
    //             // Cập nhật tất cả booking của ticket thành Reserved
    //             Booking::where('ticket_id', $ticket->id)->update([
    //                 'status' => BookingStatus::Reserved->value,
    //             ]);

    //             // Broadcast event để notify realtime
    //             broadcast(new \App\Events\PaymentCompleted($ticket))->toOthers();

    //             return response()->json([
    //                 'message' => 'Thanh toán thành công',
    //                 'status' => 'completed',
    //                 'ticket' => $ticket->load('bookings.seat.type'),
    //             ]);
    //         } elseif ($transactionStatus === 'CANCELLED') {
    //             return response()->json([
    //                 'message' => 'Thanh toán bị hủy',
    //                 'status' => 'cancelled',
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'message' => 'Thanh toán đang chờ xử lý',
    //         'status' => 'pending',
    //     ]);
    // }
    public function checkPaymentStatus($ticketCode)
    {
        $ticket = Ticket::where('code', $ticketCode)->firstOrFail();

        // Check bookings đã Reserved chưa
        $bookingsReserved = Booking::where('ticket_id', $ticket->id)
            ->where('status', BookingStatus::Reserved->value)
            ->count();
        $totalBookings = Booking::where('ticket_id', $ticket->id)->count();

        if ($bookingsReserved === $totalBookings && $totalBookings > 0) {
            return response()->json(['status' => 'completed', 'ticket' => $ticket]);
        }

        // Lấy orderCode số từ ticket code (bỏ chữ TK)
        $orderCode = (int) preg_replace('/[^0-9]/', '', $ticket->code);

        $paymentStatus = $this->payOsService->getTransactionStatus($orderCode);

        Log::info('Check status orderCode: ' . $orderCode, $paymentStatus);

        if (isset($paymentStatus['data'])) {
            $transactionStatus = $paymentStatus['data']['status'] ?? null;

            if ($transactionStatus === 'PAID') {
                Booking::where('ticket_id', $ticket->id)->update([
                    'status' => BookingStatus::Reserved->value,
                ]);

                // ==========================================
                // BẮT ĐẦU: LOGIC GỬI EMAIL THÔNG BÁO VÉ
                // ==========================================
                
                // 1. Tải trước các dữ liệu liên quan để truyền vào view Email
                $ticket->load([
                    'customer', // Để lấy email khách hàng
                    'bookings.schedule.movie',
                    'bookings.schedule.room',
                    'bookings.seat.seatType'
                ]);

                // 2. Lấy email của khách (Tùy thuộc vào Database của bạn, có thể là $ticket->customer->email hoặc $ticket->customer->account->email)
                        $user = auth('api')->user();
                        $customerEmail = $user?->customer?->email ?? $user?->email;

                if ($customerEmail) {
                    try {
                        Mail::to($customerEmail)->send(new TicketMail($ticket));
                        Log::info("Đã gửi email vé {$ticket->code} thành công cho {$customerEmail}");
                    } catch (\Exception $e) {
                        Log::error("Lỗi gửi email vé {$ticket->code}: " . $e->getMessage());
                    }
                }
                
                // ==========================================
                // KẾT THÚC: LOGIC GỬI EMAIL THÔNG BÁO VÉ
                // ==========================================

                broadcast(new \App\Events\PaymentCompleted($ticket));
                return response()->json(['status' => 'completed', 'ticket' => $ticket]);
            }

            if ($transactionStatus === 'CANCELLED') {
                return response()->json(['status' => 'cancelled']);
            }
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * Web route: Hiển thị trang kết quả thanh toán
     */




    private function resolveCustomerId(): ?int
    {
        // JWT API guard
        $user = auth('api')->user();
        if ($user) {
            return $user->customer?->id ?? $user->id;
        }

        // Session guard (web blade)
        return auth('customer')->user()?->id;
    }
}
