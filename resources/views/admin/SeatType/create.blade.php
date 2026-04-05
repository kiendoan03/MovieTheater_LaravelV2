@extends('layouts.header')
@section('content')

<!-- Title -->
<div class="row">
    <div class="col">
        <h2 class="text-light mb-4">Add Seat Type</h2>
    </div>
</div>

<!-- Main -->
<div class="row">
    <form role="form" method="post" action="{{ route('admin.seat_types.store') }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="type" class="form-label text-light">Seat Type</label>
                    <input type="text" class="form-control bg-dark border-0 shadow-none text-light"
                        id="type" name="type" required>
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="price" class="form-label text-light">Price</label>
                    <input type="number" step="0.01" class="form-control bg-dark border-0 shadow-none text-light"
                        id="price" name="price" min="0" required>
                </div>
            </div>
        </div>

        <input type="submit" class="btn btn-danger my-2 col-2" value="Add" name="submit_btn">
    </form>
</div>

@endsection