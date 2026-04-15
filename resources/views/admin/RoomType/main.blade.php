@extends('layouts.management')
@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <div class="cw-head">
            <div>
                <h2>Quản lý loại phòng</h2>
                <div class="cw-count">Tổng số: {{ $roomTypes->count() }} loại phòng</div>
            </div>

            <a href="{{ route('admin.room_types.create') }}" class="btn-new">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tạo loại phòng mới
            </a>
        </div>

        <div class="cw-card">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Loại phòng</th>
                        <th>Sức chứa</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomTypes as $roomType)
                    <tr>
                        <td class="mono-id">
                            #{{ str_pad($roomType->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="room-main">
                            {{ $roomType->type }}
                        </td>
                        <td>
                            {{ $roomType->capacity }} ghế
                        </td>
                        <td class="text-center">
                            <div class="btn-group-actions btn-group-actions-right">
                                <a href="{{ route('admin.room_types.edit', $roomType) }}" class="btn-circle">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.room_types.destroy', $roomType) }}" method="POST" onsubmit="return confirm('Xóa loại phòng?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-circle btn-del">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($roomTypes->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">🎬</div>
                <p>Chưa có loại phòng nào.</p>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection