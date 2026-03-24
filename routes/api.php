<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatTypeController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleSeatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('room-types', RoomTypeController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('seat-types', SeatTypeController::class);
Route::apiResource('seats', SeatController::class);
Route::apiResource('movies', MovieController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('schedule-seats', ScheduleSeatController::class);
Route::apiResource('tickets', TicketController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('staff', StaffController::class);
Route::apiResource('actors', ActorController::class);
Route::apiResource('directors', DirectorController::class);
Route::apiResource('categories', CategoryController::class);