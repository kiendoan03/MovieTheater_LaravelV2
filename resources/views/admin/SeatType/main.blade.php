@extends('layouts.header')
@section('content')

<!-- Title -->
<div class="row">
    <div class="col">
        <h2 class="text-light">Seat Types Management</h2>
    </div>
</div>

<!-- Main -->

<div class="row">
    <div class="col-12">
        <a href="{{ route('admin.seat_types.create') }}" type="button" class="btn btn-outline-light my-4">
            <i class="fa-solid fa-plus"></i> New Seat Type
        </a>
    </div>
</div>

<div class="row">
    <div class="col">
        <table id="myTable" class="table my-3 text-light">
            <thead>
                <tr>
                    <th scope="col" class="col-1">No</th>
                    <th scope="col" class="col-5">Type</th>
                    <th scope="col" class="col-4">Price</th>
                    <th scope="col" class="col-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seatTypes as $seatType)
                <tr>
                    <th scope="row">{{ $seatType->id }}</th>
                    <td>{{ $seatType->type }}</td>
                    <td>{{ number_format($seatType->price, 2) }} $</td>
                    <td>
                        <a href="{{ route('admin.seat_types.edit', $seatType) }}" type="button" class="btn btn-outline-light my-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form class="d-inline" method="POST" action="{{ route('admin.seat_types.destroy', $seatType) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger my-1">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection