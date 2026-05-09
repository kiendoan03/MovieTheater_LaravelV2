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

    .table-custom thead th {
        font-size: 12px;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid var(--border);
        transition: .2s;
    }

    .table-custom tbody tr:hover {
        background: rgba(255, 255, 255, .02);
    }

    .table-custom tbody tr:last-child {
        border-bottom: none;
    }

    .table-custom td {
        line-height: 1.4;
    }

    .seat-type-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        height: 100%;
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
        align-items: center;
    }

    .mono-id {
        font-family: 'JetBrains Mono', monospace;
        color: var(--muted);
        white-space: nowrap;
    }

    .btn-group-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .sw-pagination {
        display: flex;
        justify-content: center;
        padding: 1.25rem;
        gap: 4px;
    }

    .sw-pagination .page-link {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--muted);
        padding: 5px 11px;
        border-radius: 6px;
        text-decoration: none;
        transition: all .15s;
    }

    .sw-pagination .page-link:hover,
    .sw-pagination .page-link.active {
        border-color: var(--accent);
        color: var(--accent);
    }

    .sw-alert {
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 1rem;
    }

    .sw-alert.success {
        background: rgba(74, 222, 128, .1);
        border: 1px solid rgba(74, 222, 128, .25);
        color: #4ade80;
    }

    .sw-alert.danger {
        background: rgba(248, 113, 113, .1);
        border: 1px solid rgba(248, 113, 113, .25);
        color: #f87171;
    }
</style>

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- HEADER -->
        <div class="cw-head">
            <div>
                <h2>Quản lý loại ghế</h2>

                <div class="cw-count">
                    Tổng số: {{ $seatTypes->total() }} loại ghế
                </div>
            </div>

            <a href="{{ route('admin.seat_types.create') }}" class="btn-new">
                <svg width="18" height="18"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    viewBox="0 0 24 24">

                    <path d="M12 5v14M5 12h14" />
                </svg>

                Tạo loại ghế mới
            </a>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
        <div class="sw-alert success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="sw-alert danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- TABLE -->
        <div class="cw-card">

            @if($seatTypes->count())

            <table class="table-custom">

                <thead>
                    <tr>
                        <th width="70">STT</th>
                        <th>Loại ghế</th>
                        <th width="160">Giá</th>
                        <th class="text-center" width="140">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($seatTypes as $seatType)

                    <tr>

                        <!-- STT -->
                        <td class="mono-id">
                            {{ $loop->iteration + ($seatTypes->currentPage() - 1) * $seatTypes->perPage() }}
                        </td>
                        <!-- TYPE -->
                        <td>
                            <div class="seat-type-wrap">

                                <span class="seat-color"
                                    style="background: {{ $seatType->color ?? '#ccc' }}">
                                </span>

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

                                <a href="{{ route('admin.seat_types.edit', $seatType) }}"
                                    class="btn-circle">

                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form
                                    action="{{ route('admin.seat_types.destroy', $seatType) }}"
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

            {{-- PAGINATION --}}
            @if($seatTypes->hasPages())

            <div class="sw-pagination">

                @foreach($seatTypes->links()->elements[0] as $page => $url)

                <a href="{{ $url }}"
                    class="page-link {{ $seatTypes->currentPage() == $page ? 'active' : '' }}">

                    {{ $page }}

                </a>

                @endforeach

            </div>

            @endif

            @else

            <div class="empty-state">

                <div class="empty-state-icon">
                    💺
                </div>

                <p>
                    Chưa có loại ghế nào.
                </p>

            </div>

            @endif

        </div>

    </div>
</div>

@endsection