@extends('layouts.header')
@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

  :root {
    --bg:         #0d0f14;
    --surface:    #13161e;
    --card:       #1a1e28;
    --border:     rgba(255,255,255,0.07);
    --border-h:   rgba(255,255,255,0.15);
    --text:       #e8eaf0;
    --muted:      #6b7280;
    --accent:     #e8c96a;
    --accent-bg:  rgba(232,201,106,0.10);
    --danger:      #f87171;
    --success:     #10b981;
  }

  .cw { font-family:'Sora',sans-serif; color:var(--text); padding:2rem 0 5rem; }

  .cw-head { display:flex; justify-content: space-between; align-items:center; margin-bottom:2rem; }
  .cw-head h2 { font-size:1.3rem; font-weight:600; letter-spacing:-.01em; margin:0; }
  
  .cw-card {
    background:var(--card); border:1px solid var(--border);
    border-radius:14px; padding:1.5rem; margin-bottom:1.25rem;
  }

  .cw-section-label {
    font-family:'JetBrains Mono',monospace; font-size:10px;
    letter-spacing:.12em; color:var(--muted); text-transform:uppercase;
    margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;
  }
  .cw-section-label::after { content:''; flex:1; height:1px; background:var(--border); }

  .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
  .info-item { display: flex; flex-direction: column; gap: 4px; }
  .info-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
  .info-value { font-size: 15px; font-weight: 500; color: var(--text); }

  /* Status Badge */
  .badge-status {
    display: inline-flex; align-items:center; gap: 6px;
    padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
  }
  .status-full { background: rgba(16, 185, 129, 0.1); color: #10b981; }
  .status-warn { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

  /* Screen */
  .screen-area { text-align:center; margin:1.5rem 0 1.75rem; }
  .screen-bar {
    display:inline-block; width:55%; height:5px; border-radius:3px;
    background:linear-gradient(90deg,transparent,rgba(232,201,106,.45),transparent);
    position:relative;
  }
  .screen-lbl {
    font-family:'JetBrains Mono',monospace; font-size:10px;
    letter-spacing:.18em; color:rgba(232,201,106,.35); margin-top:14px;
  }

  /* Seat Grid */
  .seat-grid-wrap { overflow-x:auto; padding: 1rem 0; }
  .seat-grid-inner { display:inline-flex; flex-direction:column; align-items:center; min-width: 100%; }
  .seat-row { display:flex; align-items:center; gap:5px; margin-bottom:6px; }
  
  .row-lbl {
    font-family:'JetBrains Mono',monospace; font-size:11px;
    color:var(--muted); width:25px; text-align:center;
  }

  .seat {
    width:32px; height:32px; border-radius:6px;
    border:1.5px solid transparent;
    display:flex; align-items:center; justify-content:center;
    font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:600;
    transition: transform .2s;
  }
  .seat:hover { transform: scale(1.1); }
  .seat.empty { background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05); color: transparent; }

  /* Legend */
  .legend { display:flex; flex-wrap:wrap; gap:20px; margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid var(--border); }
  .legend-item { display:flex; align-items:center; gap:8px; font-size:12px; color:var(--muted); }
  .legend-dot { width:14px; height:14px; border-radius:4px; }

  /* Stats */
  .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:12px; }
  .stat-box { background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:1rem; }
  .stat-lbl { font-size:11px; color:var(--muted); margin-bottom:6px; }
  .stat-val { font-size:22px; font-weight:600; font-family:'JetBrains Mono',monospace; }

  /* Footer Actions */
  .cw-footer {
    display:flex; gap:12px; justify-content:flex-start;
    padding-top:1.5rem; border-top:1px solid var(--border); margin-top:1rem;
  }
  .btn-action {
    padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 500;
    text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s;
  }
  .btn-edit { background: var(--accent); color: #0d0f14; }
  .btn-edit:hover { background: #f0d47a; transform: translateY(-1px); }
  .btn-back { background: var(--surface); border: 1px solid var(--border); color: var(--text); }
  .btn-back:hover { border-color: var(--border-h); }
</style>

@php
function seatColorMap(string $type): string {
    return match(strtolower(trim($type))) {
        'standard', 'thường', 'thuong', 'tiêu chuẩn' => '#3b82f6',
        'vip'                                         => '#a855f7',
        'premium'                                     => '#f59e0b',
        'couple', 'đôi', 'doi'                        => '#ec4899',
        default                                       => '#6b7280',
    };
}

$maxRow = $room->seats->max('row') ?? 0;
$maxCol = $room->seats->max('column') ?? 0;
$seatsByPosition = $room->seats->groupBy(fn($item) => $item->row . '-' . $item->column);
$seatTypeCounts = $room->seats->groupBy('type_id');
@endphp

<div class="cw">
  <div class="container-fluid px-3 px-md-5">

    <div class="cw-head">
      <div>
        <h2 class="mb-1">{{ $room->room_name }}</h2>
        <div style="font-family:'JetBrains Mono'; font-size:11px; color:var(--muted)">ID: #ROOM-{{ str_pad($room->id, 4, '0', STR_PAD_LEFT) }}</div>
      </div>
      <div class="cw-footer" style="margin:0; border:0; padding:0">
        <a href="{{ route('admin.rooms.index') }}" class="btn-action btn-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Quay lại
        </a>
        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn-action btn-edit">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>
            Chỉnh sửa
        </a>
      </div>
    </div>

    <div class="row">
      {{-- BÊN TRÁI: THÔNG TIN & THỐNG KÊ --}}
      <div class="col-lg-4">
        <div class="cw-card">
          <div class="cw-section-label">Thông tin cơ bản</div>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Loại phòng</span>
              <span class="info-value">{{ $room->roomType->type }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Trạng thái cấu hình</span>
              <div>
                @if($room->seats->count() == $room->capacity)
                  <span class="badge-status status-full">● Hoàn tất thiết kế</span>
                @else
                  <span class="badge-status status-warn">● Chưa đủ số ghế</span>
                @endif
              </div>
            </div>
            <div class="info-item">
              <span class="info-label">Sức chứa tối đa</span>
              <span class="info-value">{{ $room->capacity }} ghế</span>
            </div>
          </div>
        </div>

        <div class="cw-card">
          <div class="cw-section-label">Thống kê loại ghế</div>
          <div class="stats-grid">
            <div class="stat-box">
              <div class="stat-lbl">Đã thiết kế</div>
              <div class="stat-val">{{ $room->seats->count() }}</div>
            </div>
            @foreach($seatTypeCounts as $typeId => $grouped)
              @php $st = $grouped->first()->seatType; @endphp
              <div class="stat-box">
                <div class="stat-lbl">{{ $st->type }}</div>
                <div class="stat-val" style="color: {{ seatColorMap($st->type) }};">
                  {{ $grouped->count() }}
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- BÊN PHẢI: SƠ ĐỒ --}}
      <div class="col-lg-8">
        <div class="cw-card">
          <div class="cw-section-label">Sơ đồ vị trí ghế</div>
          
          <div class="screen-area">
            <div class="screen-bar"></div>
            <div class="screen-lbl">MÀN HÌNH CHÍNH</div>
          </div>

          <div class="seat-grid-wrap">
            <div class="seat-grid-inner">
              @for($r = 1; $r <= $maxRow; $r++)
                <div class="seat-row">
                  <span class="row-lbl">{{ chr(64 + $r) }}</span>
                  @for($c = 1; $c <= $maxCol; $c++)
                    @php 
                      $seat = $seatsByPosition->get($r . '-' . $c)?->first(); 
                      $color = $seat ? seatColorMap($seat->seatType->type) : '';
                    @endphp
                    
                    @if($seat)
                      <div class="seat" 
                           style="background: {{ $color }}20; border-color: {{ $color }}; color: {{ $color }};"
                           title="{{ chr(64 + $r) }}{{ $c }} - {{ $seat->seatType->type }}">
                        {{ $c }}
                      </div>
                    @else
                      <div class="seat empty"></div>
                    @endif
                  @endfor
                </div>
              @endfor
            </div>
          </div>

          <div class="legend">
            @foreach($room->seats->map(fn($s) => $s->seatType)->unique('id') as $st)
              <div class="legend-item">
                <div class="legend-dot" style="background: {{ seatColorMap($st->type) }};"></div>
                <span>{{ $st->type }} ({{ number_format($st->price, 0, ',', '.') }}₫)</span>
              </div>
            @endforeach
            <div class="legend-item">
                <div class="legend-dot" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border);"></div>
                <span>Lối đi / Trống</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection