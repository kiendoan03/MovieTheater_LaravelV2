@extends('layouts.management')
@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');
  :root {
    --surface:#13161e; --card:#1a1e28; --border:rgba(255,255,255,0.07);
    --border-h:rgba(255,255,255,0.15); --text:#e8eaf0; --muted:#6b7280;
    --accent:#e8c96a; --accent-bg:rgba(232,201,106,0.10); --danger:#f87171;
  }
  .sw { font-family:'Sora',sans-serif; color:var(--text); padding:2rem 0 5rem; }
  .sw-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; }
  .sw-head h2 { font-size:1.3rem; font-weight:600; margin:0; }
  .btn-add {
    background:var(--accent); border:none; color:#0d0f14;
    font-family:'Sora',sans-serif; font-size:13px; font-weight:600;
    padding:9px 20px; border-radius:8px; cursor:pointer;
    display:inline-flex; align-items:center; gap:6px; text-decoration:none;
    transition:background .2s;
  }
  .btn-add:hover { background:#f0d47a; color:#0d0f14; }

  .sw-card {
    background:var(--card); border:1px solid var(--border);
    border-radius:14px; overflow:hidden;
  }

  /* alert */
  .sw-alert {
    padding:12px 16px; border-radius:10px; font-size:13px;
    margin-bottom:1rem; display:flex; align-items:center; gap:8px;
  }
  .sw-alert.success { background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.25); color:#4ade80; }
  .sw-alert.danger  { background:rgba(248,113,113,.1); border:1px solid rgba(248,113,113,.25); color:#f87171; }

  /* table */
  .sw-table { width:100%; border-collapse:collapse; }
  .sw-table thead th {
    font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:.1em;
    color:var(--muted); text-transform:uppercase; padding:12px 16px;
    border-bottom:1px solid var(--border); text-align:left; white-space:nowrap;
  }
  .sw-table tbody tr { transition:background .15s; }
  .sw-table tbody tr:hover { background:rgba(255,255,255,.025); }
  .sw-table tbody td {
    padding:14px 16px; font-size:13px; border-bottom:1px solid var(--border);
    vertical-align:middle;
  }
  .sw-table tbody tr:last-child td { border-bottom:none; }

  .movie-title { font-weight:500; color:var(--text); }
  .movie-genre { font-size:11px; color:var(--muted); margin-top:2px; }

  .time-badge {
    font-family:'JetBrains Mono',monospace; font-size:12px;
    background:var(--surface); border:1px solid var(--border);
    padding:3px 9px; border-radius:6px; white-space:nowrap;
  }
  .date-text { font-size:12px; color:var(--muted); margin-top:3px; }

  .room-badge {
    font-size:12px; background:var(--accent-bg);
    color:var(--accent); border:1px solid rgba(232,201,106,.2);
    padding:3px 9px; border-radius:6px; font-weight:500;
  }

  .seat-count { font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--muted); }
  .seat-fill { color:var(--accent); }

  .status-dot {
    display:inline-flex; align-items:center; gap:5px;
    font-size:12px; font-weight:500;
  }
  .status-dot::before {
    content:''; width:6px; height:6px; border-radius:50%; display:inline-block;
  }
  .status-dot.upcoming::before { background:#4ade80; }
  .status-dot.past::before     { background:var(--muted); }
  .status-dot.today::before    { background:var(--accent); }

  .actions { display:flex; gap:6px; }
  .btn-icon {
    width:30px; height:30px; border-radius:6px; border:1px solid var(--border);
    background:var(--surface); color:var(--muted); cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; text-decoration:none; transition:all .15s;
  }
  .btn-icon:hover { border-color:var(--border-h); color:var(--text); }
  .btn-icon.del:hover { border-color:#f87171; color:#f87171; background:rgba(248,113,113,.05); }

  /* pagination */
  .sw-pagination { display:flex; justify-content:center; padding:1.25rem; gap:4px; }
  .sw-pagination .page-link {
    font-family:'JetBrains Mono',monospace; font-size:12px;
    background:var(--surface); border:1px solid var(--border); color:var(--muted);
    padding:5px 11px; border-radius:6px; text-decoration:none; transition:all .15s;
  }
  .sw-pagination .page-link:hover,
  .sw-pagination .page-link.active { border-color:var(--accent); color:var(--accent); }

  .empty-state {
    text-align:center; padding:4rem 2rem; color:var(--muted); font-size:13px;
  }
  .empty-icon { font-size:2.5rem; opacity:.2; margin-bottom:.75rem; }
</style>

<div class="sw">
  <div class="container-fluid px-3 px-md-4">

    <div class="sw-head">
      <h2>Quản lý lịch chiếu</h2>
      <a href="{{ route('admin.schedules.create') }}" class="btn-add">
        <span style="font-size:16px;line-height:1">+</span> Thêm lịch chiếu
      </a>
    </div>

    @if(session('success'))
      <div class="sw-alert success">{{ session('success') }}</div>
    @endif
    @if($errors->has('conflict'))
      <div class="sw-alert danger">{{ $errors->first('conflict') }}</div>
    @endif

    <div class="sw-card">
      @if($schedules->count())
        <table class="sw-table">
          <thead>
            <tr>
              <th>STT</th>
              <th>Phim</th>
              <th>Phòng</th>
              <th>Ngày chiếu</th>
              <th>Giờ chiếu</th>
              {{-- <th>Ghế</th> --}}
              <th>Trạng thái</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($schedules as $s)
              @php
                $today    = now('Asia/Ho_Chi_Minh')->toDateString();
                $startTimeVN = $s->start_time->setTimezone('Asia/Ho_Chi_Minh');
                $endTimeVN = $s->end_time->setTimezone('Asia/Ho_Chi_Minh');
                $scheduleDate = $startTimeVN->toDateString();
                $isToday  = $scheduleDate === $today;
                $isPast   = $scheduleDate < $today;
                $statusClass = $isPast ? 'past' : ($isToday ? 'today' : 'upcoming');
                $statusLabel = $isPast ? 'Đã chiếu' : ($isToday ? 'Hôm nay' : 'Sắp chiếu');

                $totalSeats   = $s->bookings_count ?? $s->bookings->count();
                $bookedSeats  = $s->bookings->where('status', '!=', 'pending')->count();
              @endphp
              <tr>
                <td class="seat-count">{{ $loop->iteration  + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td> 
                <td>
                  <div class="movie-title">{{ $s->movie->movie_name ?? '—' }}</div>
                  {{-- <div class="movie-genre">{{ $s->movie->category->name ?? '' }}</div> --}}
                </td>
                <td><span class="room-badge">{{ $s->room->room_name ?? '—' }}</span></td>
                <td>
                  <div>{{ $startTimeVN->format('d/m/Y') }}</div>
                  <div class="date-text">{{ $startTimeVN->locale('vi')->dayName }}</div>
                </td>
                <td>
                  <span class="time-badge">{{ $startTimeVN->format('H:i') }} – {{ $endTimeVN->format('H:i') }}</span>
                </td>
                {{-- <td>
                  <span class="seat-count">
                    <span class="seat-fill">{{ $bookedSeats }}</span> / {{ $totalSeats }}
                  </span>
                </td> --}}
                <td>
                  <span class="status-dot {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td>
                  <div class="actions">
                    {{-- <a href="{{ route('admin.schedules.show', $s) }}" class="btn-icon" title="Chi tiết">👁</a> --}}
                    <a href="{{ route('admin.schedules.edit', $s) }}" class="btn-icon" title="Chỉnh sửa">✏️</a>
                    <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}"
                      onsubmit="return confirm('Xóa lịch chiếu này? Toàn bộ booking pending sẽ bị xóa.')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn-icon del" title="Xóa">✕</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        @if($schedules->hasPages())
          <div class="sw-pagination">
            @foreach($schedules->links()->elements[0] as $page => $url)
              <a href="{{ $url }}" class="page-link {{ $schedules->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
          </div>
        @endif

      @else
        <div class="empty-state">
          <div class="empty-icon">🎬</div>
          Chưa có lịch chiếu nào. <a href="{{ route('admin.schedules.create') }}" style="color:var(--accent)">Thêm ngay</a>
        </div>
      @endif
    </div>

  </div>
</div>

@endsection