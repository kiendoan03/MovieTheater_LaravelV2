@extends('layouts.client')

@section('title', 'Actor Profile')

@push('styles')
<style>
        :root {
            --bg:        #0b0b0f;
            --surface:   #13131a;
            --card:      #13131a;
            --border:    rgba(255,255,255,0.08);
            --accent:    #ff1f45;
            --accent2:   #ff1f45;
            --primary:   #ff1f45;
            --text:      #ffffff;
            --muted:     #b9b9c7;
            --radius:    18px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        /* ── Cinematic top strip ── */
        .page-strip {
            height: 3px;
            background: linear-gradient(90deg, #ff1f45, #ff728d, #ff1f45);
        }

        /* ── Main wrapper ── */
        .page-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            padding: 100px 32px 60px;
        }

        /* ── Layout: sidebar + content side by side ── */
        .layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 28px;
            align-items: start;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: sticky;
            top: 100px;
        }

        .profile-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .profile-banner {
            height: 90px;
            background: linear-gradient(135deg, rgba(255, 31, 69, .15) 0%, rgba(255, 31, 69, .08) 50%, #0b0b0f 100%);
            position: relative;
        }
        .profile-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 8px,
                rgba(255,255,255,0.015) 8px,
                rgba(255,255,255,0.015) 9px
            );
        }

        .profile-body {
            padding: 0 24px 28px;
        }

        .avatar-wrap {
            margin-top: 22px;
            margin-bottom: 16px;
            text-align: center;
        }
        .profile-avatar {
            width: 160px;
            height: 160px;
            border-radius: 14px;
            object-fit: cover;
            border: 3px solid var(--bg);
            box-shadow: 0 8px 24px rgba(0,0,0,0.5);
            display: inline-block;
        }

        .actor-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            letter-spacing: 0.04em;
            line-height: 1;
            color: #fff;
            margin-bottom: 4px;
        }
        .actor-role {
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #ff728d;
            margin-bottom: 20px;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 20px 0;
        }

        .profile-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .btn-pill {
            border: 1px solid rgba(255, 255, 255, .08);
            background: rgba(255,255,255,0.08);
            color: var(--text);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s;
        }
        .btn-pill:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, .15);
            color: var(--text);
            box-shadow: 0 15px 35px rgba(255, 255, 255, .15);
            transform: translateY(-3px);
        }
        .btn-pill.primary {
            background: linear-gradient(135deg, var(--primary), #ff365b);
            border-color: var(--primary);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(255, 31, 69, .3);
        }
        .btn-pill.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 31, 69, .45);
        }

        .info-label {
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #b9b9c7;
            font-weight: 500;
            margin-bottom: 12px;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table tr td {
            padding: 7px 0;
            font-size: 0.87rem;
            vertical-align: top;
            border-bottom: 1px solid var(--border);
        }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table td:first-child {
            color: #b9b9c7;
            width: 110px;
            padding-right: 12px;
            white-space: nowrap;
        }
        .info-table td:last-child { color: var(--text); }

        /* ── Main content ── */
        .content-area { min-width: 0; }

        /* Header row */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .section-heading {
            font-family: 'DM Sans', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .count-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 31, 69, .15);
            color: #ff728d;
            border: 1px solid rgba(255, 31, 69, .35);
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 3px 10px;
            letter-spacing: 0.05em;
            flex-shrink: 0;
        }

        /* Bio */
        .bio-block {
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 3px solid #ff1f45;
            border-radius: var(--radius);
            padding: 20px 24px;
            margin-bottom: 28px;
            font-size: 0.92rem;
            line-height: 1.85;
            color: #a8a8a8;
        }
        .bio-block .bio-title {
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #ff728d;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Movie grid */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
            gap: 16px;
        }

        .movie-card {
            background: var(--card);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: var(--radius);
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 30px rgba(255, 31, 69, .15);
            border-color: rgba(255, 31, 69, .2);
        }
        .movie-thumb-wrap {
            position: relative;
            overflow: hidden;
        }
        .movie-thumb {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .movie-card:hover .movie-thumb { transform: scale(1.04); }

        .movie-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 55%);
            opacity: 0;
            transition: opacity 0.22s;
            display: flex;
            align-items: flex-end;
            padding: 12px;
        }
        .movie-card:hover .movie-overlay { opacity: 1; }
        .overlay-play {
            width: 36px; height: 36px;
            background: #ff1f45;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 0.9rem;
        }

        .movie-body { padding: 14px; }
        .movie-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 6px;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .movie-meta {
            font-size: 0.78rem;
            color: #a8a8a8;
            line-height: 1.5;
        }
        .movie-year {
            display: inline-block;
            background: rgba(255,255,255,0.06);
            border-radius: 4px;
            padding: 1px 6px;
            margin-bottom: 3px;
        }

        /* Empty state */
        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: #b9b9c7;
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: 0.3; }

        /* Responsive */
        @media (max-width: 1024px) {
            .layout { grid-template-columns: 280px 1fr; }
        }
        @media (max-width: 768px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; }
            .page-wrapper { padding: 80px 16px 40px; }
            .profile-card { display: flex; }
            .profile-banner { width: 100px; height: auto; flex-shrink: 0; }
            .profile-banner::after { display:none; }
            .avatar-wrap { margin-top: 12px; }
            .movie-grid { grid-template-columns: repeat(auto-fill, minmax(145px, 1fr)); }
        }
    </style>
@endpush

@section('content')

<div class="page-strip"></div>

<div class="page-wrapper">
    <div class="layout">

        {{-- ── Sidebar ── --}}
        <aside class="sidebar">
            <div class="profile-card">

                <div class="profile-body">
                    <div class="avatar-wrap">
                        <img
                            src="{{ asset(\Illuminate\Support\Facades\Storage::url('img/actor/') . $actors->image) }}"
                            alt="{{ $actors->name }}"
                            class="profile-avatar"
                        >
                    </div>

                    <div class="actor-name">{{ $actors->name }}</div>
                    <div class="actor-role">Diễn viên</div>

                    <div class="profile-actions">
                        <a href="#" class="btn-pill primary">
                            <i class="fa-regular fa-heart"></i> Yêu thích
                        </a>
                        <a href="#" class="btn-pill">
                            <i class="fa-solid fa-share-nodes"></i> Chia sẻ
                        </a>
                    </div>

                    <div class="divider"></div>

                    <div class="info-label">Thông tin cá nhân</div>
                    <table class="info-table">
                        <tr>
                            <td>Tên khác</td>
                            <td>{{ $actors->other_name ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td>Giới tính</td>
                            <td>{{ $actors->gender ?? 'Nam' }}</td>
                        </tr>
                        <tr>
                            <td>Ngày sinh</td>
                            <td>{{ $actors->birthday ?? 'Chưa có' }}</td>
                        </tr>
                        <tr>
                            <td>Nơi sinh</td>
                            <td>{{ $actors->birthplace ?? 'Chưa có' }}</td>
                        </tr>
                        <tr>
                            <td>Nghề nghiệp</td>
                            <td>{{ $actors->profession ?? 'Diễn viên' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </aside>

        {{-- ── Main content ── --}}
        <main class="content-area">

            <div class="content-header">
                <div>
                    <span class="section-heading">
                        Phim đang chiếu tại rạp
                        <span class="count-badge">{{ $movie_actor->where('end_date', '>=', now())->count() }}</span>
                    </span>
                </div>
            </div>

            <div class="movie-grid">
                @foreach($movie_actor->where('end_date', '>=', now()) as $movie)
                    <a href="#" class="movie-card">
                        <div class="movie-thumb-wrap">
                            <img
                                src="{{ asset(\Illuminate\Support\Facades\Storage::url('img/movie_poster/') . $movie->poster) }}"
                                alt="{{ $movie->movie_name }}"
                                class="movie-thumb"
                            >
                            <div class="movie-overlay">
                                <div class="overlay-play"><i class="fa-solid fa-play"></i></div>
                            </div>
                        </div>
                        <div class="movie-body">
                            <div class="movie-name">{{ $movie->movie_name }}</div>
                            <div class="movie-meta">
                                <span class="movie-year">{{ $movie->release_year ?? '—' }}</span><br>
                                {{ $movie->category->category_name ?? 'Không rõ' }}
                            </div>
                        </div>
                    </a>
                @endforeach
                @if($movie_actor->where('end_date', '>=', now())->isEmpty())
                    <div class="empty-state">
                        <div><i class="fa-regular fa-film"></i></div>
                        <p>Không có phim nào trong dữ liệu.</p>
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="/bootstrapLib/bootstrap.bundle.min.js"></script>
@endpush
