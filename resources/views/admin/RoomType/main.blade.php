@extends('layouts.header')
@section('content')

<!-- Title -->
<div class="row">
    <div class="col">
        <h2 class="text-light">Room Types Management</h2>
    </div>
</div>

<!-- Main -->

<div class="row">
    <div class="col-12">
        <a href="{{ route('admin.room_types.create') }}" type="button" class="btn btn-outline-light my-4">
            <i class="fa-solid fa-plus"></i> New Room Type
        </a>
    </div>
</div>

<div class="row">
    <div class="col">
        <table id="myTable" class="table my-3 text-light">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Type</th>
                    <th scope="col">Capacity</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roomTypes as $roomType)
                <tr>
                    <th scope="row" class="col-1">{{ $roomType->id }}</th>
                    <td class="col-5">{{ $roomType->type }}</td>
                    <td class="col-4">{{ $roomType->capacity }}</td>
                    <td class="col-2">
                        <a href="{{ route('admin.room_types.edit', $roomType) }}" type="button" class="btn btn-outline-light my-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form class="d-inline" method="POST" action="{{ route('admin.room_types.destroy', $roomType) }}">
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