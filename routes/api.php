<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SeatTypeController;
use App\Http\Controllers\StaffController;
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

// auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class,  'login']);
    Route::post('/refresh', [TokenController::class, 'refresh']);
    Route::post('/logout', [TokenController::class, 'logout']); // không cần auth:api — logout phải hoạt động kể cả khi access token hết hạn
});

// accounts (removed custom register)

Route::apiResource('room-types', RoomTypeController::class);
Route::apiResource('rooms', RoomController::class);
Route::get('/rooms/{room}/seats', [RoomController::class, 'getSeats']);
Route::apiResource('seat-types', SeatTypeController::class);
Route::apiResource('seats', SeatController::class);
Route::apiResource('movies', MovieController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('bookings', BookingController::class);
Route::apiResource('tickets', TicketController::class);
Route::apiResource('customers', CustomerController::class)->except(['store']);
Route::apiResource('staff', StaffController::class);
Route::apiResource('actors', ActorController::class);
Route::apiResource('directors', DirectorController::class);
Route::apiResource('categories', CategoryController::class);
