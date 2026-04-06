@extends('layouts.header')
@section('content')

<!-- Title -->
<div class="row">
    <div class="col">
        <h2 class="text-light mb-4">Add Room Type</h2>
    </div>
</div>

<!-- Main -->
<div class="row">
    <form role="form" method="post" action="{{ route('admin.room_types.store') }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="type" class="form-label text-light">Room Type</label>
                    <input type="text" class="form-control bg-dark border-0 shadow-none text-light"
                        id="type" name="type" required>
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="capacity" class="form-label text-light">Capacity</label>
                    <input type="number" class="form-control bg-dark border-0 shadow-none text-light"
                        id="capacity" name="capacity" min="1" required>
                </div>
            </div>
        </div>

        <input type="submit" class="btn btn-danger my-2 col-2" value="Add" name="submit_btn">
    </form>
</div>

@endsection