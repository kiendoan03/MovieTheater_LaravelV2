<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PayOSController;
use App\Http\Controllers\PayOsWebhookController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeatTypeController;
use App\Http\Controllers\TicketBookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==========================================
// Auth Routes - dùng chung cho mọi người
// ==========================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']);

// ==========================================
// Admin Web Routes — Yêu cầu đăng nhập + role admin
// ==========================================
Route::prefix('/Admin/Dashboard')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
// Route::get('/payos', [PayOSController::class, 'index'])->name('payos.index');
// Route::post('/payos/login', [PayOSController::class, 'login'])->name('payos.login');
// Route::get('/payos/statistics', [PayOSController::class, 'statistics'])->name('payos.statistics');

// Route::get('/Admin/Dashboard',[DashboardController::class, 'index'])->name('dashboard');
Route::middleware(['jwt.cookie', 'role:admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
    });

    Route::prefix('Admin/Category')->name('admin.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/create', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{category}/edit', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::prefix('Admin/Actor')->name('admin.')->group(function () {
        Route::get('/', [ActorController::class, 'index'])->name('actors.index');
        Route::get('/create', [ActorController::class, 'create'])->name('actors.create');
        Route::post('/create', [ActorController::class, 'store'])->name('actors.store');
        Route::get('/{actor}/edit', [ActorController::class, 'edit'])->name('actors.edit');
        Route::put('/{actor}/edit', [ActorController::class, 'update'])->name('actors.update');
        Route::delete('/{actor}/delete', [ActorController::class, 'destroy'])->name('actors.destroy');
    });

    Route::prefix('Admin/Director')->name('admin.')->group(function () {
        Route::get('/', [DirectorController::class, 'index'])->name('directors.index');
        Route::get('/create', [DirectorController::class, 'create'])->name('directors.create');
        Route::post('/create', [DirectorController::class, 'store'])->name('directors.store');
        Route::get('/{director}/edit', [DirectorController::class, 'edit'])->name('directors.edit');
        Route::put('/{director}/edit', [DirectorController::class, 'update'])->name('directors.update');
        Route::delete('/{director}/delete', [DirectorController::class, 'destroy'])->name('directors.destroy');
    });

    Route::prefix('Admin/Room')->name('admin.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/create', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/{room}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/{room}/edit', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/{room}/delete', [RoomController::class, 'destroy'])->name('rooms.destroy');
    });

    Route::prefix('Admin/RoomType')->name('admin.')->group(function () {
        Route::get('/', [RoomTypeController::class, 'index'])->name('room_types.index');
        Route::get('/create', [RoomTypeController::class, 'create'])->name('room_types.create');
        Route::post('/create', [RoomTypeController::class, 'store'])->name('room_types.store');
        Route::get('/{roomType}/edit', [RoomTypeController::class, 'edit'])->name('room_types.edit');
        Route::put('/{roomType}/edit', [RoomTypeController::class, 'update'])->name('room_types.update');
        Route::delete('/{roomType}/delete', [RoomTypeController::class, 'destroy'])->name('room_types.destroy');
    });

    Route::prefix('Admin/SeatType')->name('admin.')->group(function () {
        Route::get('/', [SeatTypeController::class, 'index'])->name('seat_types.index');
        Route::get('/create', [SeatTypeController::class, 'create'])->name('seat_types.create');
        Route::post('/create', [SeatTypeController::class, 'store'])->name('seat_types.store');
        Route::get('/{seatType}/edit', [SeatTypeController::class, 'edit'])->name('seat_types.edit');
        Route::put('/{seatType}/edit', [SeatTypeController::class, 'update'])->name('seat_types.update');
        Route::delete('/{seatType}/delete', [SeatTypeController::class, 'destroy'])->name('seat_types.destroy');
    });

    Route::prefix('Admin/Schedule')->name('admin.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/create', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/{schedule}/edit', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/{schedule}/delete', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::get('/by-room', [ScheduleController::class, 'byRoom'])->name('schedules.by-room');
    });

    Route::prefix('Admin/Movie')->name('admin.')->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('movies.index');
        Route::get('/create', [MovieController::class, 'create'])->name('movies.create');
        Route::post('/create', [MovieController::class, 'store'])->name('movies.store');
        Route::get('/{movie}', [MovieController::class, 'show'])->name('movies.show');
        Route::get('/{movie}/edit', [MovieController::class, 'edit'])->name('movies.edit');
        Route::put('/{movie}/edit', [MovieController::class, 'update'])->name('movies.update');
        Route::delete('/{movie}/delete', [MovieController::class, 'destroy'])->name('movies.destroy');
    });
}); // end admin middleware group

// ==========================================
// Ticket Booking Routes (Đặt vé tại quầy - Staff & Admin)
// ==========================================
Route::middleware(['jwt.cookie', 'role:staff,admin'])->group(function () {
    Route::prefix('Admin/TicketBooking')->name('admin.ticket-booking.')->group(function () {
        Route::get('/', [TicketBookingController::class, 'index'])->name('index');
        Route::get('/schedules/{movieId}', [TicketBookingController::class, 'schedules'])->name('schedules');
        Route::get('/seat-layout/{scheduleId}', [TicketBookingController::class, 'seatLayout'])->name('seat-layout');
        Route::get('/payment-status/{ticketCode}', [TicketBookingController::class, 'paymentStatus'])->name('payment-status');
    });
});

// cho khách cần auth
Route::middleware(['jwt.cookie', 'role:customer'])->group(function () {
    Route::prefix('customer')->name('customer.')->group(function () {});

});

// ==========================================
// Customer web routes
// ==========================================
Route::prefix('/')->group(function () {
    Route::get('/', [MovieController::class, 'show'])->name('index');
    Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
    Route::get('/{movie_actor}/actor', [ActorController::class, 'show'])->name('actor');
    Route::get('/{movie_director}/director', [DirectorController::class, 'show'])->name('director');
});

// ==========================================
// PayOs Webhook (Public - không cần auth, đã exclude CSRF)
// ==========================================
Route::post('/webhook/payos', [PayOsWebhookController::class, 'handle']);
