<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * Chỉ Admin/Staff mới được gọi (đã chặn ở route).
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * Không dùng trong API.
     */
    public function create()
    {
        //
    }

    /**
     * POST /api/customers
     * Bước 2 đăng ký: điền thông tin cá nhân sau khi đã có account.
     */
    public function store(Request $request)
    {
        $account = auth()->user();

        if ($account->customer()->exists()) {
            return response()->json(['message' => 'Thông tin cá nhân đã được thiết lập.'], 409);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
        ]);

        $customer = $this->createCustomer($account, $data);

        return response()->json([
            'message' => 'Thiết lập thông tin cá nhân thành công.',
            'customer' => $customer,
        ], 201);
    }

    public function createCustomer(Account $account, array $data): Customer
    {
        return Customer::create([
            'account_id' => $account->id,
            'name' => $data['name'],
            'phonenumber' => $data['phonenumber'],
            'address' => $data['address'] ?? null,
            'avatar' => $data['avatar'] ?? null,
            'date_of_birth' => $data['date_of_birth'],
        ]);
    }

    /**
     * GET /api/customers/{customer}
     *
     * Trả về thông tin cá nhân của customer kèm danh sách vé đã đặt.
     * Mỗi vé bao gồm: tên phim, suất chiếu, danh sách ghế và trạng thái thanh toán.
     *
     * Chỉ được xem profile của chính mình (trừ admin/staff).
     */
    public function show(Customer $customer)
    {
        $account = auth()->user();

        // Chỉ cho phép xem profile của chính mình
        // Admin/Staff nếu cần xem của người khác thì mở thêm role check ở đây
        if ($account->customer?->id !== $customer->id) {
            return response()->json(['message' => 'Bạn không có quyền xem thông tin này.'], 403);
        }

        // Load vé kèm các booking → seat, seatType + schedule → movie, room
        $customer->load([
            'tickets.bookings.seat.seatType',
            'tickets.bookings.schedule.movie',
            'tickets.bookings.schedule.room',
        ]);

        $tickets = $customer->tickets->map(function ($ticket) {
            // Nhóm bookings theo schedule (1 vé có thể gồm nhiều ghế cùng 1 suất)
            $bookingsBySchedule = $ticket->bookings->groupBy('schedule_id');

            $schedules = $bookingsBySchedule->map(function ($bookings) {
                $firstBooking = $bookings->first();
                $schedule = $firstBooking->schedule;
                $movie = $schedule?->movie;

                $seats = $bookings->map(fn ($b) => [
                    'seat_id' => $b->seat_id,
                    'seat_code' => $b->seat ? ($b->seat->row.$b->seat->column) : null,
                    'seat_type' => $b->seat?->seatType?->type,
                    'price' => $b->seat?->seatType?->price,
                    'status' => $b->status, // Available / Booked / Reserved
                ])->values();

                return [
                    'schedule_id' => $schedule?->id,
                    'movie_name' => $movie?->movie_name,
                    'start_time' => $schedule?->start_time,
                    'end_time' => $schedule?->end_time,
                    'room_name' => $schedule?->room?->room_name,
                    'seats' => $seats,
                ];
            })->values();

            // Trạng thái thanh toán toàn vé
            // Reserved = đã thanh toán, Booked = chờ thanh toán, Available = chưa đặt (edge case)
            $allPaid = $ticket->bookings->every(fn ($b) => $b->status === \App\Enums\BookingStatus::Reserved->value);
            $anyBooked = $ticket->bookings->contains(fn ($b) => $b->status === \App\Enums\BookingStatus::Booked->value);

            $paymentStatus = match (true) {
                $allPaid => 'paid',       // Đã thanh toán
                $anyBooked => 'pending',    // Đang chờ thanh toán
                default => 'unknown',
            };

            return [
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->code,
                'final_price' => $ticket->final_price,
                'payment_status' => $paymentStatus,
                'schedules' => $schedules,
                'created_at' => $ticket->created_at,
            ];
        });

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phonenumber' => $customer->phonenumber,
                'address' => $customer->address,
                'avatar' => $customer->avatar,
                'date_of_birth' => $customer->date_of_birth,
                'is_active' => $customer->is_active,
                'account' => [
                    'email' => $customer->account?->email,
                    'role' => $customer->account?->role,
                ],
            ],
            'tickets' => $tickets,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Account::with(['customer.tickets.bookings.seat', 'customer.tickets.bookings.schedule.movie'])
            ->findOrFail($id);

        return view('admin.account.edit-customer', compact('customer'));
    }

    /**
     * PUT /api/customers/{customer}
     * Customer cập nhật thông tin cá nhân của chính mình.
     */
    public function update(Request $request, Customer $customer)
    {
        $account = auth()->user();

        if ($account->customer?->id !== $customer->id) {
            return response()->json(['message' => 'Bạn không có quyền chỉnh sửa thông tin này.'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phonenumber' => 'sometimes|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'date_of_birth' => 'sometimes|date',
        ]);

        $customer->update($data);

        return response()->json([
            'message' => 'Cập nhật thông tin thành công.',
            'customer' => $customer->fresh(),
        ]);
    }

    /**
     * DELETE /api/customers/{customer}
     */
    public function destroy(Customer $customer)
    {
        $account = auth()->user();

        if ($account->customer?->id !== $customer->id) {
            return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
        }

        $customer->update(['is_active' => false]);

        if ($customer->account) {
            $customer->account->update(['is_active' => false]);
        }

        auth()->logout();

        return response()->json(['message' => 'Xóa tài khoản thành công.']);
    }
}
