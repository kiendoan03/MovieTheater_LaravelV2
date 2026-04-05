@extends('layouts.header')
@section('content')
<!-- Title -->
<div class="row">
    <div class="col">
        <h2 class="text-light mb-4">Edit Seat Type</h2>
    </div>
</div>

<!-- Main -->
<div class="row">
    <div class="col-10">
        <form role="form" method="post" action="{{ route('admin.seat_types.update', $seatType) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="type" class="form-label text-light">Seat Type</label>
                <input type="text" class="form-control bg-dark text-light border-0 shadow-none"
                    id="type" name="type"
                    value="{{ $seatType->type }}" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label text-light">Price</label>
                <input type="number" step="0.01" class="form-control bg-dark text-light border-0 shadow-none"
                    id="price" name="price"
                    value="{{ $seatType->price }}" min="0" required>
            </div>

            <input type="submit" class="btn btn-danger my-3 col-2" value="Edit" name="submit_btn">

        </form>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>