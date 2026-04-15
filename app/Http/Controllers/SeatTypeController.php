<?php

namespace App\Http\Controllers;

use App\Models\SeatType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SeatTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seatTypes = SeatType::all();

        return view('admin.SeatType.main', [
            'seatTypes' => $seatTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.SeatType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $array = [];
        $array = Arr::add($array, 'type', $request->type);
        $array = Arr::add($array, 'price', $request->price);

        SeatType::create($array);

        return redirect()->route('admin.seat_types.index')
            ->with('success', 'Seat type added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SeatType $seatType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeatType $seatType)
    {
        return view('admin.SeatType.edit', [
            'seatType' => $seatType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeatType $seatType)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $array = [];
        $array = Arr::add($array, 'type', $request->type);
        $array = Arr::add($array, 'price', $request->price);

        $seatType->update($array);

        return redirect()->route('admin.seat_types.index')
            ->with('success', 'Seat type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeatType $seatType)
    {
        $seatType->delete();

        return redirect()->route('admin.seat_types.index')
            ->with('success', 'Seat type deleted successfully!');
    }
}
