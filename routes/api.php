<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SeatTypeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TicketBookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// VIBE CODE CỰC CĂNG
// ==========================================
// CỤM 0: AUTHENTICATION (Không cần token)
// ==========================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/process-login', [AuthController::class, 'login']); // Bỏ throttle chờ reCAPTCHA
    Route::post('/refresh', [TokenController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/send-otp', [OTPController::class, 'sendOTP']);
    Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);
});

// ==========================================
// CỤM 1: PUBLIC ROUTES (Khách vãng lai cũng xem được)
// ==========================================
Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
Route::apiResource('schedules', ScheduleController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('actors', ActorController::class)->only(['index', 'show']);
Route::apiResource('directors', DirectorController::class)->only(['index', 'show']);
Route::apiResource('room-types', RoomTypeController::class)->only(['index', 'show']);
Route::apiResource('rooms', RoomController::class)->only(['index', 'show']);
Route::get('/rooms/{room}/seats', [RoomController::class, 'getSeats']);
Route::apiResource('seat-types', SeatTypeController::class)->only(['index', 'show']);
Route::apiResource('seats', SeatController::class)->only(['index', 'show']);

// ==========================================
// CỤM 2: PROTECTED ROUTES (Bắt buộc phải Đăng Nhập)
// ==========================================
Route::middleware(['jwt.cookie', 'auth:api'])->group(function () {

    // --- ZONE A: Dành cho TẤT CẢ User (Customer, Staff, Admin đều làm được) ---
    // Đặt vé, xem vé của mình...
    Route::apiResource('bookings', BookingController::class);
    Route::apiResource('tickets', TicketController::class);

    // Customer chỉ được xem/sửa profile của chính họ, cấm lấy danh sách (index)
    Route::apiResource('customers', CustomerController::class)->except(['store', 'index']);

    // --- ZONE B: VÙNG CẤM - Chỉ Staff hoặc Admin mới được vào ---
    Route::middleware('role:staff,admin')->group(function () {

        // Quản lý nhân viên
        Route::apiResource('staff', StaffController::class);

        // Các thao tác Write (Thêm, Sửa, Xóa) phim, rạp, ghế, lịch chiếu...
        Route::apiResource('movies', MovieController::class)->except(['index', 'show']);
        Route::apiResource('schedules', ScheduleController::class)->except(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('actors', ActorController::class)->except(['index', 'show']);
        Route::apiResource('directors', DirectorController::class)->except(['index', 'show']);
        Route::apiResource('room-types', RoomTypeController::class)->except(['index', 'show']);
        Route::apiResource('rooms', RoomController::class)->except(['index', 'show']);
        Route::apiResource('seat-types', SeatTypeController::class)->except(['index', 'show']);
        Route::apiResource('seats', SeatController::class)->except(['index', 'show']);

        // Ticket Booking API Routes (Đặt vé tại quầy)
        // Support both JWT and session auth
        Route::prefix('ticket-booking')->group(function () {
            Route::get('/schedule-seats/{scheduleId}', [TicketBookingController::class, 'getScheduleSeats']);
            Route::post('/update-seat-status', [TicketBookingController::class, 'updateSeatStatus']);
            Route::post('/calculate-total', [TicketBookingController::class, 'calculateTotal']);
            Route::post('/create-ticket-cash', [TicketBookingController::class, 'createTicketCash']);
            Route::post('/init-payment-payos', [TicketBookingController::class, 'initPaymentPayOs']);
            Route::get('/ticket/{ticketCode}', [TicketBookingController::class, 'getTicket']);
            Route::get('/check-payment-status/{ticketCode}', [TicketBookingController::class, 'checkPaymentStatus']);
        });
    });
});

// // auth
// Route::prefix('auth')->group(function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class,  'login']);
//     Route::post('/refresh', [TokenController::class, 'refresh']);
//     Route::post('/logout', [TokenController::class, 'logout']); // không cần auth:api — logout phải hoạt động kể cả khi access token hết hạn
//     Route::post('/send-otp', [\App\Http\Controllers\OTPController::class, 'sendOTP']);
//     Route::post('/verify-otp', [\App\Http\Controllers\OTPController::class, 'verifyOTP']);
// });

// // accounts (removed custom register)

// Route::apiResource('room-types', RoomTypeController::class);
// Route::apiResource('rooms', RoomController::class);
// Route::get('/rooms/{room}/seats', [RoomController::class, 'getSeats']);
// Route::apiResource('seat-types', SeatTypeController::class);
// Route::apiResource('seats', SeatController::class);
// Route::apiResource('movies', MovieController::class);
// Route::apiResource('schedules', ScheduleController::class);
// Route::apiResource('bookings', BookingController::class);
// Route::apiResource('tickets', TicketController::class);
// Route::apiResource('customers', CustomerController::class)->except(['store']);
// Route::apiResource('staff', StaffController::class);
// Route::apiResource('actors', ActorController::class);
// Route::apiResource('directors', DirectorController::class);
// Route::apiResource('categories', CategoryController::class);
