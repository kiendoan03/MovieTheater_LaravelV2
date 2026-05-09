<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomTypes = RoomType::paginate(10);

        return view('admin.RoomType.main', [
            'roomTypes' => $roomTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.RoomType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $array = [];
        $array = Arr::add($array, 'type', $request->type);
        $array = Arr::add($array, 'capacity', $request->capacity);

        RoomType::create($array);

        return redirect()->route('admin.room_types.index')->with('success', 'Room type added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomType $roomType)
    {
        return view('admin.RoomType.edit', [
            'roomType' => $roomType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:2|multiple_of:2',
        ], [
            'capacity.multiple_of' => 'Capacity phải là số chẵn!',
        ]);

        $array = [];
        $array = Arr::add($array, 'type', $request->type);
        $array = Arr::add($array, 'capacity', $request->capacity);

        $roomType->update($array);

        return redirect()->route('admin.room_types.index')->with('success', 'Room type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return redirect()->route('admin.room_types.index')->with('success', 'Room type deleted successfully!');
    }
}
