@extends('layouts.management')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500;700&display=swap');

    :root {
        --bg:        #0d0f14;
        --surface:   #13161e;
        --card:      #1a1e28;
        --border:    rgba(255,255,255,0.07);
        --text:      #e8eaf0;
        --muted:     #6b7280;
        --accent:    #e8c96a;
        --accent-bg: rgba(232,201,106,0.1);
    }

    body { background-color: var(--bg); color: var(--text); font-family: 'Sora', sans-serif; }

    /* Header Section */
    .booking-head { margin-bottom: 2.5rem; }
    .booking-head h1 { font-size: 1.6rem; font-weight: 600; letter-spacing: -0.02em; margin: 0; }
    .booking-head .badge-count { 
        font-family: 'JetBrains Mono'; font-size: 11px; background: var(--accent-bg); 
        color: var(--accent); padding: 5px 14px; border-radius: 20px; 
        border: 1px solid rgba(232,201,106,0.25);
    }

    /* Movie Card Grid */
    .movie-card {
        background: var(--card); border: 1px solid var(--border); border-radius: 20px;
        overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%; display: flex; flex-direction: column; position: relative;
    }
    .movie-card:hover {
        transform: translateY(-10px); border-color: rgba(232,201,106,0.4);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }

    .poster-wrapper { position: relative; height: 360px; overflow: hidden; background: #000; }
    .poster-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .movie-card:hover .poster-wrapper img { transform: scale(1.08); }

    .rating-badge {
        position: absolute; top: 15px; right: 15px;
        background: rgba(13, 15, 20, 0.7); backdrop-filter: blur(10px);
        padding: 6px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);
        font-family: 'JetBrains Mono'; font-size: 12px; font-weight: 700; color: var(--accent);
        z-index: 2;
    }

    .movie-info { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
    .movie-title { font-size: 1.15rem; font-weight: 600; margin-bottom: 0.6rem; line-height: 1.4; color: #fff; }
    .movie-meta { font-family: 'JetBrains Mono'; font-size: 11px; color: var(--muted); margin-bottom: 1.2rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .movie-meta span { margin-right: 15px; display: inline-flex; align-items: center; gap: 5px; }

    .btn-booking {
        background: var(--accent); color: #0d0f14; font-weight: 700; font-size: 13px;
        border: none; padding: 12px; border-radius: 12px; transition: all 0.2s;
        width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
        text-transform: uppercase; letter-spacing: 0.02em;
    }
    .btn-booking:hover { background: #f0d47a; transform: scale(1.02); color: #0d0f14; }

    /* Modal / Popup Styling */
    .modal-content { 
        background: #13161e; border: 1px solid rgba(255,255,255,0.1); 
        border-radius: 24px; color: var(--text); box-shadow: 0 25px 50px rgba(0,0,0,0.6);
    }
    .modal-header { border-bottom: 1px solid var(--border); padding: 1.5rem 2rem; position: relative; }
    .modal-title { font-size: 1.25rem; font-weight: 600; }
    .btn-close { 
        filter: invert(1) grayscale(100%) brightness(200%); 
        opacity: 0.6; padding: 1rem; position: absolute; right: 1.5rem; top: 1.5rem;
    }

    /* Schedule Items - Cố định lỗi không bấm được */
    #schedulesList { padding: 0.5rem 0; }
    
    .schedule-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1.5px solid var(--border);
        border-radius: 16px;
        padding: 18px 22px;
        margin-bottom: 14px;
        transition: all 0.25s ease;
        cursor: pointer !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .schedule-item:hover {
        border-color: var(--accent);
        background: rgba(232, 201, 106, 0.08);
        transform: translateY(-2px);
    }

    .sch-time { 
        font-family: 'JetBrains Mono', monospace; 
        font-size: 1.4rem; 
        font-weight: 700; 
        color: var(--accent); 
        line-height: 1;
        margin-bottom: 4px;
    }
    
    .sch-date { font-size: 11px; color: var(--muted); font-family: 'JetBrains Mono'; }
    .sch-room { font-size: 15px; font-weight: 600; color: #fff; text-align: right; margin-bottom: 2px; }
    .sch-hint { font-size: 11px; color: var(--muted); text-align: right; }

    .spinner-border { color: var(--accent); width: 1.5rem; height: 1.5rem; }
</style>

<div class="container-fluid py-4 px-md-4">
    <div class="booking-head d-flex flex-wrap align-items-center gap-3">
        <h1>🎬 Đặt vé tại quầy</h1>
        <span class="badge-count">{{ $movies->count() }} Phim đang chiếu</span>
    </div>

    @if($movies->isEmpty())
        <div class="text-center py-5 rounded-4" style="background: var(--card); border: 1px dashed var(--border);">
            <i class="fas fa-film fa-3x mb-3" style="color: var(--border);"></i>
            <h5 class="text-muted">Hiện không có phim nào đang có lịch chiếu.</h5>
        </div>
    @else
        <div class="row g-4">
            @foreach($movies as $movie)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="movie-card">
                        <div class="poster-wrapper">
                            @if($movie->poster)
                                <img src="{{ asset('storage/img/movies/' . $movie->poster) }}" alt="{{ $movie->movie_name }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 bg-secondary bg-opacity-10">
                                    <span class="text-muted small font-mono">NO POSTER</span>
                                </div>
                            @endif
                            <div class="rating-badge">
                                <i class="fas fa-star text-warning me-1"></i> {{ number_format($movie->rating, 1) }}
                            </div>
                        </div>
                        <div class="movie-info">
                            <h5 class="movie-title">{{ $movie->movie_name }}</h5>
                            <div class="movie-meta">
                                <span><i class="far fa-clock"></i> {{ $movie->length }} PHÚT</span>
                                <span><i class="far fa-calendar-alt"></i> {{ $movie->release_date?->format('d/m/Y') }}</span>
                            </div>
                            <button class="btn-booking mt-auto" onclick="selectMovie({{ $movie->id }}, '{{ addslashes($movie->movie_name) }}')">
                                <i class="fas fa-ticket-alt"></i> Xem lịch chiếu
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="modal fade" id="schedulesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="text-muted small font-mono text-uppercase mb-1" style="letter-spacing: 1px;">Lịch chiếu phim</div>
                    <h5 class="modal-title" id="movieTitle" style="color: var(--accent);"></h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="schedulesLoading" class="text-center py-5">
                    <div class="spinner-border" role="status"></div>
                    <div class="mt-3 font-mono small text-muted">Đang tải lịch chiếu...</div>
                </div>
                <div id="schedulesList">
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Mở Modal và load lịch chiếu qua AJAX
     */
    function selectMovie(movieId, movieName) {
        document.getElementById('movieTitle').textContent = movieName;
        const listContainer = document.getElementById('schedulesList');
        const loading = document.getElementById('schedulesLoading');
        
        listContainer.innerHTML = '';
        loading.style.display = 'block';
        
        const modalElement = document.getElementById('schedulesModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        fetch(`/Admin/TicketBooking/schedules/${movieId}`)
            .then(response => response.text())
            .then(html => {
                loading.style.display = 'none';
                
                // Server nên trả về HTML dạng: 
                // <div class="schedule-item" onclick="selectSchedule(ID)">...</div>
                listContainer.innerHTML = html;
                
                // Kiểm tra nếu server trả về rỗng hoặc không có item nào
                if (listContainer.innerHTML.trim() === "" || !listContainer.querySelector('.schedule-item')) {
                    listContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="far fa-calendar-times fa-2x mb-3 text-muted"></i>
                            <p class="text-muted">Hiện tại phim này không có suất chiếu nào.</p>
                        </div>`;
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                loading.style.display = 'none';
                listContainer.innerHTML = '<div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger border-0 rounded-3">Lỗi tải dữ liệu lịch chiếu.</div>';
            });
    }

    /**
     * Chuyển hướng sang trang chọn ghế
     */
    function selectSchedule(scheduleId) {
        if (!scheduleId) return;
        // Thêm hiệu ứng loading trước khi chuyển trang
        document.body.style.opacity = '0.6';
        window.location.href = `/Admin/TicketBooking/seat-layout/${scheduleId}`;
    }
</script>
@endsection