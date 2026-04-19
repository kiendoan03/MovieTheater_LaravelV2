@extends('layouts.management')
@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead th {
        text-align: left;
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
    }

    .table-custom tbody tr {
        height: 72px;
        border-bottom: 1px solid var(--border);
        /* CHỈ LINE Ở ĐÂY */
        transition: 0.2s;
    }

    .table-custom tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .table-custom td {
        padding: 16px;
        vertical-align: middle;
    }

    .room-main {
        display: block;
        font-weight: 500;
        border: none !important;
        /* QUAN TRỌNG: bỏ line trong cell */
    }

    .room-sub {
        display: block;
        font-size: 12px;
        color: var(--muted);
        margin-top: 4px;
    }

    .mono-id {
        font-family: 'JetBrains Mono', monospace;
        color: var(--muted);
    }

    .btn-group-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .btn-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text);
        background: transparent;
        transition: 0.2s;
    }

    .btn-circle:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .btn-del:hover {
        border-color: #ef4444;
        color: #ef4444;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: var(--muted);
    }

    .empty-state-icon {
        font-size: 28px;
        margin-bottom: 10px;
    }
</style>

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- HEADER -->
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

        <!-- TABLE -->
        <div class="cw-card">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thông tin loại phòng</th>
                        <th>Sức chứa</th>
                        <th class="text-center" width="120">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($roomTypes as $roomType)
                    <tr>
                        <!-- ID -->
                        <td class="mono-id">
                            #{{ str_pad($roomType->id, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- TYPE -->
                        <td>
                            <span class="room-main">
                                {{ $roomType->type }}
                            </span>
                        </td>

                        <!-- CAPACITY -->
                        <td>
                            {{ $roomType->capacity }} ghế
                        </td>

                        <!-- ACTION -->
                        <td class="text-center">
                            <div class="btn-group-actions">
                                <a href="{{ route('admin.room_types.edit', $roomType) }}" class="btn-circle">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.room_types.destroy', $roomType) }}" method="POST"
                                    onsubmit="return confirm('Xóa loại phòng?')">
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

            <!-- EMPTY -->
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