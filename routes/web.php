<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
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

// ==========================================
// Admin Web Routes
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
});

Route::prefix('Admin/Category')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/create', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{category}/edit', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{category}/edit', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}/delete', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
});
Route::prefix('Admin/Actor')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ActorController::class, 'index'])->name('actors.index');
    Route::get('/create', [\App\Http\Controllers\ActorController::class, 'create'])->name('actors.create');
    Route::post('/create', [\App\Http\Controllers\ActorController::class, 'store'])->name('actors.store');
    Route::get('/{actor}/edit', [\App\Http\Controllers\ActorController::class, 'edit'])->name('actors.edit');
    Route::put('/{actor}/edit', [\App\Http\Controllers\ActorController::class, 'update'])->name('actors.update');
    Route::delete('/{actor}/delete', [\App\Http\Controllers\ActorController::class, 'destroy'])->name('actors.destroy');
});
Route::prefix('Admin/Director')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DirectorController::class, 'index'])->name('directors.index');
    Route::get('/create', [\App\Http\Controllers\DirectorController::class, 'create'])->name('directors.create');
    Route::post('/create', [\App\Http\Controllers\DirectorController::class, 'store'])->name('directors.store');
    Route::get('/{director}/edit', [\App\Http\Controllers\DirectorController::class, 'edit'])->name('directors.edit');
    Route::put('/{director}/edit', [\App\Http\Controllers\DirectorController::class, 'update'])->name('directors.update');
    Route::delete('/{director}/delete', [\App\Http\Controllers\DirectorController::class, 'destroy'])->name('directors.destroy');
});

Route::prefix('Admin/Room')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\RoomController::class, 'index'])->name('rooms.index');
    Route::get('/create', [App\Http\Controllers\RoomController::class, 'create'])->name('rooms.create');
    Route::post('/create', [App\Http\Controllers\RoomController::class, 'store'])->name('rooms.store');
    Route::get('/{room}', [App\Http\Controllers\RoomController::class, 'show'])->name('rooms.show');
    Route::get('/{room}/edit', [App\Http\Controllers\RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/{room}/edit', [App\Http\Controllers\RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/{room}/delete', [App\Http\Controllers\RoomController::class, 'destroy'])->name('rooms.destroy');
});

Route::prefix('Admin/RoomType')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\RoomTypeController::class, 'index'])->name('room_types.index');
    Route::get('/create', [App\Http\Controllers\RoomTypeController::class, 'create'])->name('room_types.create');
    Route::post('/create', [App\Http\Controllers\RoomTypeController::class, 'store'])->name('room_types.store');
    Route::get('/{roomType}/edit', [App\Http\Controllers\RoomTypeController::class, 'edit'])->name('room_types.edit');
    Route::put('/{roomType}/edit', [App\Http\Controllers\RoomTypeController::class, 'update'])->name('room_types.update');
    Route::delete('/{roomType}/delete', [App\Http\Controllers\RoomTypeController::class, 'destroy'])->name('room_types.destroy');
});
Route::prefix('Admin/SeatType')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\SeatTypeController::class, 'index'])->name('seat_types.index');
    Route::get('/create', [App\Http\Controllers\SeatTypeController::class, 'create'])->name('seat_types.create');
    Route::post('/create', [App\Http\Controllers\SeatTypeController::class, 'store'])->name('seat_types.store');
    Route::get('/{seatType}/edit', [App\Http\Controllers\SeatTypeController::class, 'edit'])->name('seat_types.edit');
    Route::put('/{seatType}/edit', [App\Http\Controllers\SeatTypeController::class, 'update'])->name('seat_types.update');
    Route::delete('/{seatType}/delete', [App\Http\Controllers\SeatTypeController::class, 'destroy'])->name('seat_types.destroy');
});

Route::prefix('Admin/Schedule')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/create', [App\Http\Controllers\ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/create', [App\Http\Controllers\ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/{schedule}/edit', [App\Http\Controllers\ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/{schedule}/edit', [App\Http\Controllers\ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/{schedule}/delete', [App\Http\Controllers\ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('/by-room', [ScheduleController::class, 'byRoom'])->name('schedules.by-room');
});
Route::prefix('Admin/Movie')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\MovieController::class, 'index'])->name('movies.index');
    Route::get('/create', [App\Http\Controllers\MovieController::class, 'create'])->name('movies.create');
    Route::post('/create', [App\Http\Controllers\MovieController::class, 'store'])->name('movies.store');
    Route::get('/{movie}', [App\Http\Controllers\MovieController::class, 'show'])->name('movies.show');
    Route::get('/{movie}/edit', [App\Http\Controllers\MovieController::class, 'edit'])->name('movies.edit');
    Route::put('/{movie}/edit', [App\Http\Controllers\MovieController::class, 'update'])->name('movies.update');
    Route::delete('/{movie}/delete', [App\Http\Controllers\MovieController::class, 'destroy'])->name('movies.destroy');
});


// ==========================================
// Customer web routes
// ==========================================
Route::prefix('/')->group(function(){
    Route::get('/', [App\Http\Controllers\MovieController::class, 'show'])->name('index');
    Route::get('/{movie_actor}/actor', [App\Http\Controllers\ActorController::class, 'show'])->name('actor');
    Route::get('/{movie_director}/director', [App\Http\Controllers\DirectorController::class, 'show'])->name('director');
});

