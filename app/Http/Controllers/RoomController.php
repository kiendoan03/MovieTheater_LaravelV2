<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Seat;
use App\Models\SeatType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->get();

        return view('admin.Room.main', [
            'rooms' => $rooms,
        ]);
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        $seatTypes = SeatType::all();

        return view('admin.Room.create', [
            'roomTypes' => $roomTypes,
            'seatTypes' => $seatTypes,
        ]);
    }

    public function store(Request $request)
    {
        // Decode JSON string từ hidden input trước khi validate
        $seatsRaw = $request->input('seats', '[]');
        $seats = is_string($seatsRaw) ? json_decode($seatsRaw, true) : $seatsRaw;

        // Gắn lại vào request để validator đọc được dạng array
        $request->merge(['seats' => $seats]);

        $request->validate([
            'room_name'          => 'required|string|max:255',
            'type_id'            => 'required|exists:room_types,id',
            'seats'              => 'required|array|min:1',
            'seats.*.row'        => 'required|integer|min:1',
            'seats.*.column'     => 'required|integer|min:1',
            'seats.*.type_id'    => 'nullable|exists:seat_types,id',
        ]);

        $roomType = RoomType::findOrFail($request->type_id);

        $room = Room::create([
            'room_name' => $request->room_name,
            'type_id'   => $request->type_id,
            'capacity'  => $roomType->capacity,
        ]);

        $seatInserts = collect($seats)->map(fn($s) => [
            'room_id'    => $room->id,
            'row'        => $s['row'],
            'column'     => $s['column'],
            'type_id'    => $s['type_id'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        // Insert bulk thay vì loop — nhanh hơn nhiều khi phòng có 100+ ghế
        Seat::insert($seatInserts);

        // Count actual seats (excluding aisles with type_id = null)
        $seatCount = collect($seats)->filter(fn($s) => $s['type_id'] !== null)->count();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', "Tạo phòng \"{$room->room_name}\" thành công với " . $seatCount . " ghế!");
    }

    public function show(Room $room)
    {
        $room->load('roomType', 'seats.seatType');

        return view('admin.Room.show', [
            'room' => $room,
        ]);
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        $seatTypes = SeatType::all();
        $seats     = $room->seats()->with('seatType')->get();

        $maxRow = $seats->max('row') ?? 5;
        $maxCol = $seats->max('column') ?? 10;

        return view('admin.Room.edit', [
            'room'      => $room,
            'roomTypes' => $roomTypes,
            'seatTypes' => $seatTypes,
            'seats'     => $seats,
            'maxRow'    => $maxRow,
            'maxCol'    => $maxCol,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        // Decode JSON string từ hidden input trước khi validate
        $seatsRaw = $request->input('seats', '[]');
        $seats = is_string($seatsRaw) ? json_decode($seatsRaw, true) : $seatsRaw;

        $request->merge(['seats' => $seats]);

        $request->validate([
            'room_name'          => 'required|string|max:255',
            'type_id'            => 'required|exists:room_types,id',
            'seats'              => 'required|array|min:1',
            'seats.*.row'        => 'required|integer|min:1',
            'seats.*.column'     => 'required|integer|min:1',
            'seats.*.type_id'    => 'nullable|exists:seat_types,id',
        ]);

        $roomType = RoomType::findOrFail($request->type_id);

        $room->update([
            'room_name' => $request->room_name,
            'type_id'   => $request->type_id,
            'capacity'  => $roomType->capacity,
        ]);

        // Xóa ghế cũ và insert lại bulk
        $room->seats()->delete();

        $seatInserts = collect($seats)->map(fn($s) => [
            'room_id'    => $room->id,
            'row'        => $s['row'],
            'column'     => $s['column'],
            'type_id'    => $s['type_id'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        Seat::insert($seatInserts);

        // Count actual seats (excluding aisles with type_id = null)
        $seatCount = collect($seats)->filter(fn($s) => $s['type_id'] !== null)->count();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', "Cập nhật phòng \"{$room->room_name}\" thành công!");
    }

    public function destroy(Room $room)
    {
        $name = $room->room_name;
        $room->seats()->delete();
        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', "Đã xóa phòng \"{$name}\".");
    }

    public function getSeats(Room $room)
    {
        $seats = $room->seats()
            ->with('seatType')
            ->select(['id', 'room_id', 'row', 'column', 'type_id'])
            ->get();

        return response()->json($seats);
    }
}