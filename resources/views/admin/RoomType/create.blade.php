@extends('layouts.header')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- Header -->
        <div class="cw-head">
            <div>
                <h2>Thêm loại phòng</h2>
                <div class="cw-crumb">Quản lý loại phòng</div>
            </div>
            <a href="{{ route('admin.room_types.index') }}" class="btn-cancel">Quay lại</a>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.room_types.store') }}">
            @csrf

            <div class="cw-card">
                <div class="row g-4">

                    <!-- Type -->
                    <div class="col-md-6 pt-2">
                        <label class="cw-label ms-2" for="type">Loại phòng</label>
                        <input type="text"
                            id="type"
                            name="type"
                            class="cw-input"
                            value="{{ old('type') }}"
                            placeholder="Nhập loại phòng"
                            required>
                        @error('type')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    <div class="col-md-6 pt-2">
                        <label class="cw-label ms-2" for="capacity">Sức chứa</label>
                        <input type="number"
                            id="capacity"
                            name="capacity"
                            class="cw-input"
                            min="2"
                            step="2"
                            value="{{ old('capacity') }}"
                            placeholder="Nhập sức chứa (số chẵn)"
                            required>
                        @error('capacity')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="cw-footer">
                <a href="{{ route('admin.room_types.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-submit">Lưu loại phòng</button>
            </div>

        </form>
    </div>
</div>


@endsection