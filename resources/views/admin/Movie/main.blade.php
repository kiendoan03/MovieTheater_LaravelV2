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
        white-space: nowrap;
    }

    .table-custom tbody tr {
        height: 82px;
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
        font-weight: 600;
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

    .movie-poster {
        width: 60px;
        height: 82px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .movie-status {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;
        width: fit-content;
        white-space: nowrap;

        line-height: 1;
    }

    .status-showing {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
    }

    .status-upcoming {
        background: rgba(250, 204, 21, 0.15);
        color: #facc15;
    }

    .status-ended {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }

    .rating-box {
        font-weight: 600;
        color: #facc15;
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

    .sw-toast {
        position: absolute;

        top: 50%;
        left: 50%;

        transform: translate(-50%, -50%);

        z-index: 100;

        padding: 12px 16px;
        border-radius: 10px;

        background: #111;
        color: #fff;

        font-size: 13px;
        font-weight: 500;

        opacity: 0;
        transition: 0.25s;

        pointer-events: none;

        white-space: nowrap;
    }

    .sw-toast.success {
        background: rgba(74, 222, 128, 0.12);
        border: 1px solid rgba(74, 222, 128, 0.3);
        color: #4ade80;
    }

    .sw-toast.danger {
        background: rgba(248, 113, 113, 0.12);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: #f87171;
    }

    .sw-toast.show {
        opacity: 1;
    }

    .cw-card {
        position: relative;
    }

    .cw-head {
        position: relative;
    }
</style>

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- HEADER -->
        <div class="cw-head">
            <div>
                <h2>Quản lý phim</h2>

                <div class="cw-count">
                    Tổng số: {{ $movies->total() }} phim
                </div>
            </div>

            <a href="{{ route('admin.movies.create') }}" class="btn-new">
                <svg width="18" height="18" fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>

                Tạo phim mới
            </a>
            @if(session('success'))
            <div id="toast-success" class="sw-toast success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div id="toast-error" class="sw-toast danger">
                {{ session('error') }}
            </div>
            @endif
        </div>


        <!-- TABLE -->
        <div class="cw-card">

            @if($movies->count())

            <table class="table-custom">

                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Thông tin phim</th>
                        <th>Poster</th>
                        <th>Đánh giá</th>
                        <th>Thời lượng</th>
                        <th>Trạng thái</th>
                        <th class="text-center" width="130">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($movies as $movie)

                    <tr>

                        <!-- STT -->
                        <td class="mono-id">
                            {{ $loop->iteration + ($movies->currentPage() - 1) * $movies->perPage() }}
                        </td>

                        <!-- INFO -->
                        <td>
                            <span class="room-main">
                                {{ $movie->movie_name }}
                            </span>

                            <span class="room-sub">
                                {{ $movie->country }}
                                •
                                {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                            </span>
                        </td>

                        <!-- POSTER -->
                        <td>
                            <img class="movie-poster"
                                src="{{ asset(Storage::url('img/movie_poster/' . $movie->poster)) }}"
                                alt="{{ $movie->movie_name }}">
                        </td>

                        <!-- RATING -->
                        <td>
                            <span class="rating-box">
                                ⭐ {{ $movie->rating }}
                            </span>
                        </td>

                        <!-- LENGTH -->
                        <td>
                            {{ $movie->length }} phút
                        </td>

                        <!-- STATUS -->
                        <td>

                            @if(now()->between($movie->release_date, $movie->end_date))

                            <span class="movie-status status-showing">
                                ● Đang chiếu
                            </span>

                            @elseif(now()->lt($movie->release_date))

                            <span class="movie-status status-upcoming">
                                ● Sắp chiếu
                            </span>

                            @else

                            <span class="movie-status status-ended">
                                ● Ngừng chiếu
                            </span>

                            @endif

                        </td>

                        <!-- ACTION -->
                        <td class="text-center">

                            <div class="btn-group-actions">

                                <a href="{{ route('admin.movies.edit', $movie) }}"
                                    class="btn-circle">

                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form
                                    action="{{ route('admin.movies.destroy', $movie) }}"
                                    method="POST"
                                    onsubmit="return confirm('Xóa phim này?')">

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
            @if($movies->hasPages())

            <div class="sw-pagination">

                @foreach($movies->links()->elements[0] as $page => $url)

                <a href="{{ $url }}"
                    class="page-link {{ $movies->currentPage() == $page ? 'active' : '' }}">

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
                    Chưa có phim nào.
                </p>

            </div>

            @endif

        </div>

    </div>
</div>
<script>
    function showToast(id) {
        const el = document.getElementById(id);
        if (!el) return;

        el.classList.add("show");

        setTimeout(() => {
            el.classList.remove("show");
            setTimeout(() => el.remove(), 300);
        }, 3000);
    }

    showToast("toast-success");
    showToast("toast-error");
</script>
@endsection