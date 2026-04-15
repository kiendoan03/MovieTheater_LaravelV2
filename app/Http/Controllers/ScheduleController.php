<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Schedule;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    // Thời gian buffer giữa 2 lịch chiếu cùng phòng (phút)
    const BUFFER_MINUTES = 30;

    /* ──────────────────────────────
       INDEX
    ────────────────────────────── */
    public function index()
    {
        $schedules = Schedule::with(['movie', 'room'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('admin.Schedule.main', compact('schedules'));
    }

    /* ──────────────────────────────
       CREATE
    ────────────────────────────── */
    public function create()
    {
        $movies = Movie::with('categories')->orderBy('movie_name')->get();
        $rooms  = Room::with('roomType')->orderBy('room_name')->get();

        return view('admin.Schedule.create', compact('movies', 'rooms'));
    }

    /* ──────────────────────────────
       STORE
    ────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'end_time'   => 'required|date_format:Y-m-d\TH:i|after:start_time',
        ], [
            'start_time.after_or_equal' => 'Giờ bắt đầu không được là thời gian trong quá khứ.',
            'end_time.after'             => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ]);

        // Chuyển đổi từ datetime-local (Y-m-d\TH:i) → +7 timezone → lưu UTC
        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time, 'Asia/Ho_Chi_Minh');

        $conflict = $this->checkConflict(
            $request->room_id,
            $startTime,
            $endTime
        );

        if ($conflict) {
            return back()->withInput()->withErrors([
                'conflict' => $conflict,
            ]);
        }

        $schedule = Schedule::create([
            'movie_id'   => $request->movie_id,
            'room_id'    => $request->room_id,
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);

        // Tạo booking placeholder cho toàn bộ ghế trong phòng
        $this->createBookingsForSchedule($schedule);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', "Đã thêm lịch chiếu thành công! Tạo " . $schedule->bookings()->count() . " ghế đặt chỗ.");
    }

    /* ──────────────────────────────
       SHOW
    ────────────────────────────── */
    public function show(Schedule $schedule)
    {
        $schedule->load(['movie', 'room.roomType', 'bookings.seat.seatType']);

        return view('admin.Schedule.show', compact('schedule'));
    }

    /* ──────────────────────────────
       EDIT
    ────────────────────────────── */
    public function edit(Schedule $schedule)
    {
        $movies = Movie::with('categories')->orderBy('movie_name')->get();
        $rooms  = Room::with('roomType')->orderBy('room_name')->get();

        return view('admin.Schedule.edit', compact('schedule', 'movies', 'rooms'));
    }

    /* ──────────────────────────────
       UPDATE
    ────────────────────────────── */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time'   => 'required|date_format:Y-m-d\TH:i|after:start_time',
        ]);

        // Kiểm tra xem có booking đã xác nhận chưa — nếu có thì chặn
        $confirmedBookings = $schedule->bookings()
            ->where('status', '!=', 'pending')
            ->count();

        if ($confirmedBookings > 0) {
            return back()->withInput()->withErrors([
                'conflict' => "Không thể chỉnh sửa lịch chiếu này vì đã có {$confirmedBookings} vé được xác nhận.",
            ]);
        }

        // Chuyển đổi từ datetime-local (Y-m-d\TH:i) → +7 timezone → lưu UTC
        $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time, 'Asia/Ho_Chi_Minh');

        $conflict = $this->checkConflict(
            $request->room_id,
            $startTime,
            $endTime,
            $schedule->id // exclude chính nó
        );

        if ($conflict) {
            return back()->withInput()->withErrors(['conflict' => $conflict]);
        }

        // Nếu đổi phòng → xóa booking cũ và tạo lại
        $roomChanged = $schedule->room_id != $request->room_id;

        $schedule->update([
            'movie_id'   => $request->movie_id,
            'room_id'    => $request->room_id,
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);

        if ($roomChanged) {
            $schedule->bookings()->delete();
            $this->createBookingsForSchedule($schedule);
        }

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Cập nhật lịch chiếu thành công!');
    }

    /* ──────────────────────────────
       DESTROY
    ────────────────────────────── */
    public function destroy(Schedule $schedule)
    {
        $confirmedBookings = $schedule->bookings()
            ->where('status', '!=', BookingStatus::Available->value)
            ->count();

        if ($confirmedBookings > 0) {
            return back()->withErrors([
                'conflict' => "Không thể xóa lịch chiếu vì đã có {$confirmedBookings} vé được xác nhận.",
            ]);
        }

        $schedule->bookings()->delete();
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Đã xóa lịch chiếu.');
    }

    /* ──────────────────────────────
       API: lấy lịch chiếu theo phòng + ngày (cho form preview conflict)
    ────────────────────────────── */
    public function byRoom(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date'    => 'required|date',
        ]);

        $date = Carbon::parse($request->date, 'Asia/Ho_Chi_Minh')->format('Y-m-d');

        $query = Schedule::with('movie')
            ->where('room_id', $request->room_id)
            ->whereDate('start_time', $date);

        if ($request->filled('exclude')) {
            $query->where('id', '!=', (int) $request->exclude);
        }

        $schedules = $query->orderBy('start_time')->get()->map(fn($s) => [
            'id'         => $s->id,
            'movie_name' => $s->movie->movie_name ?? '—',
            'start_time' => $s->start_time->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i'),
            'end_time'   => $s->end_time->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i'),
        ]);

        return response()->json(['schedules' => $schedules]);
    }

    /* ──────────────────────────────
       API: lấy thời lượng phim (cho form tự tính end_time)
    ────────────────────────────── */
    public function getMovieDuration(Movie $movie)
    {
        return response()->json([
            'length' => $movie->length ?? null,
            'movie_name'    => $movie->movie_name,
        ]);
    }

    /* ══════════════════════════════════════
       PRIVATE HELPERS
    ══════════════════════════════════════ */

    /**
     * Kiểm tra trùng lịch trong cùng phòng.
     * Quy tắc: 2 lịch chiếu cần cách nhau ít nhất BUFFER_MINUTES phút.
     *
     * Timeline check (A = lịch mới, B = lịch đã có):
     *   A.start < B.end + buffer  AND  A.end + buffer > B.start
     *   → trùng
     */
    private function checkConflict(
        int $roomId,
        Carbon $startTime,
        Carbon $endTime,
        ?int $excludeScheduleId = null
    ): ?string {
        $buffer = self::BUFFER_MINUTES;

        $query = Schedule::where('room_id', $roomId)
            ->whereDate('start_time', $startTime->format('Y-m-d'));

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $existing = $query->with('movie')->get();

        foreach ($existing as $s) {
            $exStart = $s->start_time;
            $exEnd   = $s->end_time;

            // Mở rộng khoảng hiện có thêm buffer ở 2 đầu
            $exStartWithBuffer = $exStart->copy()->subMinutes($buffer);
            $exEndWithBuffer   = $exEnd->copy()->addMinutes($buffer);

            // Kiểm tra overlap: newStart < exEnd+buffer AND newEnd > exStart-buffer
            if ($startTime->lt($exEndWithBuffer) && $endTime->gt($exStartWithBuffer)) {
                $movieName  = $s->movie->movie_name ?? 'Không rõ';
                $timeRange  = $exStart->format('H:i') . ' – ' . $exEnd->format('H:i');

                if ($startTime->lt($exEnd) && $endTime->gt($exStart)) {
                    return "Trùng lịch chiếu \"{$movieName}\" ({$timeRange}). Hai lịch chiếu không được chồng nhau.";
                }

                $minutesBetween = (int) abs($startTime->diffInMinutes($endTime->gt($exEnd) ? $exEnd : $exStart));
                return "Lịch chiếu quá gần với \"{$movieName}\" ({$timeRange}). Cần cách ít nhất {$buffer} phút để dọn dẹp phòng (hiện chỉ cách {$minutesBetween} phút).";
            }
        }

        return null;
    }

    /**
     * Tạo booking placeholder (status = available) cho tất cả ghế trong phòng.
     * Mỗi ghế = 1 bản ghi booking, chờ khách đặt.
     */
    private function createBookingsForSchedule(Schedule $schedule): void
    {
        $seats = $schedule->room->seats()->get();

        $bookings = $seats->map(fn($seat) => [
            'schedule_id' => $schedule->id,
            'seat_id'     => $seat->id,
            'ticket_id'   => null,
            'status'      => BookingStatus::Available->value,
            'customer_id' => null,
            'staff_id'    => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ])->toArray();

        Booking::insert($bookings);
    }
}