@extends('layouts.management')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- Header -->
        <div class="cw-head pt-2">
            <div>
                <h2>Chỉnh sửa loại ghế</h2>
                <div class="cw-crumb">Quản lý loại ghế</div>
            </div>
            <a href="{{ route('admin.seat_types.index') }}" class="btn-cancel">Quay lại</a>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.seat_types.update', $seatType) }}">
            @csrf
            @method('PUT')

            <div class="cw-card">
                <div class="row g-4">

                    <!-- Type -->
                    <div class="col-md-6 pt-2">
                        <label class="cw-label ms-2" for="type">Loại ghế</label>
                        <input type="text"
                            id="type"
                            name="type"
                            class="cw-input"
                            value="{{ old('type', $seatType->type) }}"
                            required>
                        @error('type')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 pt-2">
                        <label class="cw-label ms-2 " for="price">Giá</label>
                        <input type="number"
                            step="0.01"
                            id="price"
                            name="price"
                            class="cw-input"
                            min="0"
                            value="{{ old('price', $seatType->price) }}"
                            required>
                        @error('price')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="cw-footer">
                <a href="{{ route('admin.seat_types.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-submit">Lưu thay đổi</button>
            </div>

        </form>
    </div>
</div>

@endsection