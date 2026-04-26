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
        text-decoration: none;
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

    /* pagination */
    .cw-pagination {
        display: flex;
        justify-content: center;
        padding: 1.25rem;
        gap: 4px;
    }

    .cw-pagination .page-link {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--muted);
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        transition: .15s;
    }

    .cw-pagination .page-link:hover,
    .cw-pagination .page-link.active {
        border-color: var(--accent);
        color: var(--accent);
    }
</style>

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- HEADER -->
        <div class="cw-head">
            <div>
                <h2>Quản lý loại phòng</h2>

                <div class="cw-count">
                    Tổng số: {{ $roomTypes->total() }} loại phòng
                </div>
            </div>

            <a href="{{ route('admin.room_types.create') }}" class="btn-new">
                <svg width="18"
                    height="18"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    viewBox="0 0 24 24">

                    <path d="M12 5v14M5 12h14" />
                </svg>

                Tạo loại phòng mới
            </a>
        </div>

        <!-- TABLE -->
        <div class="cw-card">

            @if($roomTypes->count())

            <table class="table-custom">

                <thead>
                    <tr>
                        <th width="80">STT</th>
                        <th>Thông tin loại phòng</th>
                        <th>Sức chứa</th>
                        <th class="text-center" width="120">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($roomTypes as $roomType)

                    <tr>

                        <!-- STT -->
                        <td class="mono-id">
                            {{ $loop->iteration + ($roomTypes->currentPage() - 1) * $roomTypes->perPage() }}
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

                                <a href="{{ route('admin.room_types.edit', $roomType) }}"
                                    class="btn-circle">

                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.room_types.destroy', $roomType) }}"
                                    method="POST"
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

            {{-- PAGINATION --}}
            @if($roomTypes->hasPages())

            <div class="cw-pagination">

                @foreach($roomTypes->links()->elements[0] as $page => $url)

                <a href="{{ $url }}"
                    class="page-link {{ $roomTypes->currentPage() == $page ? 'active' : '' }}">

                    {{ $page }}

                </a>

                @endforeach

            </div>

            @endif

            @else

            <div class="empty-state">

                <div class="empty-state-icon">
                    🎬
                </div>

                <p>
                    Chưa có loại phòng nào.
                </p>

            </div>

            @endif

        </div>

    </div>
</div>

@endsection