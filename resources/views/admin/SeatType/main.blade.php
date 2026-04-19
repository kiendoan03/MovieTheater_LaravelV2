@extends('layouts.management')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom th,
    .table-custom td {
        padding: 18px 16px;
        vertical-align: middle;
        text-align: left;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid var(--border);
    }

    .table-custom tbody tr:last-child {
        border-bottom: none;
    }

    /* ===== FIX CHÍNH LỆCH DÒNG ===== */
    .table-custom td {
        line-height: 1.4;
    }

    /* FIX layout loại ghế + màu */
    .seat-type-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        height: 100%;
        /* 🔥 đảm bảo cùng chiều cao */
    }

    .seat-color {
        width: 14px;
        height: 14px;
        border-radius: 4px;
        border: 1px solid var(--border);
        flex-shrink: 0;
    }

    .room-main {
        font-weight: 600;
        color: var(--text);
        display: inline-flex;
        /* 🔥 fix lệch chuẩn */
        align-items: center;
    }

    /* ID */
    .mono-id {
        font-family: 'JetBrains Mono', monospace;
        color: var(--muted);
        white-space: nowrap;
    }

    /* ACTION */
    .btn-group-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
</style>

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- HEADER -->
        <div class="cw-head">
            <div>
                <h2>Quản lý loại ghế</h2>
                <div class="cw-count">
                    Tổng số: {{ $seatTypes->count() }} loại ghế
                </div>
            </div>

            <a href="{{ route('admin.seat_types.create') }}" class="btn-new">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tạo loại ghế mới
            </a>
        </div>

        <!-- TABLE -->
        <div class="cw-card">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Loại ghế</th>
                        <th width="160">Giá</th>
                        <th class="text-center" width="140">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($seatTypes as $seatType)
                    <tr>

                        <!-- ID -->
                        <td class="mono-id">
                            #{{ str_pad($seatType->id, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- TYPE + COLOR -->
                        <td>
                            <div class="seat-type-wrap">

                                <!-- màu -->
                                <span class="seat-color"
                                    style="background: {{ $seatType->color ?? '#ccc' }}">
                                </span>

                                <!-- tên -->
                                <span class="room-main">
                                    {{ $seatType->type }}
                                </span>

                            </div>
                        </td>

                        <!-- PRICE -->
                        <td>
                            {{ number_format($seatType->price, 0) }} đ
                        </td>

                        <!-- ACTION -->
                        <td class="text-center">
                            <div class="btn-group-actions">
                                <a href="{{ route('admin.seat_types.edit', $seatType) }}" class="btn-circle">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.seat_types.destroy', $seatType) }}"
                                    method="POST"
                                    onsubmit="return confirm('Xóa loại ghế?')">
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

            @if($seatTypes->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">💺</div>
                <p>Chưa có loại ghế nào.</p>
            </div>
            @endif

        </div>

    </div>
</div>

@endsection