@extends('layouts.management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">🎬 Đặt vé tại quầy</h1>
            <p class="text-muted">Chọn phim để xem các lịch chiếu</p>
        </div>
    </div>

    @if($movies->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Không có phim nào đang chiếu
        </div>
    @else
        <div class="row">
            @foreach($movies as $movie)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card movie-card h-100" style="cursor: pointer; transition: transform 0.3s;">
                        <div class="position-relative overflow-hidden" style="height: 300px; background: #f0f0f0;">
                            @if($movie->poster)
                                <img src="{{ asset('storage/img/movies/' . $movie->poster) }}" 
                                     alt="{{ $movie->movie_name }}" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 bg-secondary text-white">
                                    <span>No Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $movie->movie_name }}</h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-calendar"></i> {{ $movie->release_date?->format('d/m/Y') }}
                            </p>
                            <p class="card-text small">
                                ⏱️ {{ $movie->length }} phút | 
                                <i class="fas fa-star text-warning"></i> {{ number_format($movie->rating, 1) }}/10
                            </p>
                            <button class="btn btn-primary mt-auto w-100" 
                                    onclick="selectMovie({{ $movie->id }}, '{{ $movie->movie_name }}')">
                                <i class="fas fa-ticket-alt"></i> Xem lịch chiếu
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal hiển thị danh sách lịch chiếu -->
<div class="modal fade" id="schedulesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-film"></i> Lịch chiếu: <span id="movieTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="schedulesLoading" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                </div>
                <div id="schedulesList"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .movie-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .schedule-item {
        padding: 15px;
        border-left: 4px solid #007bff;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .schedule-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0056b3;
    }
    
    .schedule-item.selected {
        background-color: #e7f3ff;
        border-left-color: #0056b3;
    }
</style>

<script>
function selectMovie(movieId, movieName) {
    document.getElementById('movieTitle').textContent = movieName;
    document.getElementById('schedulesList').innerHTML = '';
    document.getElementById('schedulesLoading').style.display = 'block';
    
    const modal = new bootstrap.Modal(document.getElementById('schedulesModal'));
    modal.show();
    
    // Load schedules
    fetch(`/Admin/TicketBooking/schedules/${movieId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('schedulesLoading').style.display = 'none';
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const schedulesList = doc.querySelector('#schedulesList');
            if (schedulesList) {
                document.getElementById('schedulesList').innerHTML = schedulesList.innerHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('schedulesLoading').style.display = 'none';
            document.getElementById('schedulesList').innerHTML = '<div class="alert alert-danger">Lỗi khi tải lịch chiếu</div>';
        });
}

function selectSchedule(scheduleId) {
    window.location.href = `/Admin/TicketBooking/seat-layout/${scheduleId}`;
}
</script>
@endsection
