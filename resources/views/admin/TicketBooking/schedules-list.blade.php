@php
    use Illuminate\Support\Carbon;
@endphp

<div id="schedulesList">
    @if($movie->schedules->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Không có lịch chiếu cho phim này
        </div>
    @else
        @foreach($movie->schedules as $schedule)
            <div class="schedule-item" onclick="selectSchedule({{ $schedule->id }})">
                <div class="row align-items-center g-0">
                    <div class="col-md-3">
                        <div class="fw-bold">
                            <i class="fas fa-clock text-primary"></i>
                            {{ $schedule->start_time->format('H:i') }}
                            -
                            {{ $schedule->end_time->format('H:i') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted">
                            <i class="fas fa-door-open"></i>
                            Phòng: {{ $schedule->room->room_name }}
                        </div>
                    </div>
                    <div class="col-md-5 text-end">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $schedule->start_time->format('d/m/Y') }}
                        </small>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-chair"></i> Chọn ghế
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
function selectSchedule(scheduleId) {
    window.location.href = `/Admin/TicketBooking/seat-layout/${scheduleId}`;
}
</script>
