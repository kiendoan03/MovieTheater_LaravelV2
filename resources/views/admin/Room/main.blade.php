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
    --danger:     #f87171;
    --info:       #3b82f6;
  }

  .cw { font-family:'Sora',sans-serif; color:var(--text); padding:2rem 0 5rem; }

  /* Header Section */
  .cw-head { 
    display:flex; justify-content: space-between; align-items:flex-end; 
    margin-bottom:2.5rem; border-bottom: 1px solid var(--border);
    padding-bottom: 1.5rem;
  }
  .cw-head h2 { font-size:1.5rem; font-weight:600; letter-spacing:-.02em; margin:0; }
  .cw-count { font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--muted); margin-top:4px; }

  /* Table Custom */
  .cw-card {
    background:var(--card); border:1px solid var(--border);
    border-radius:16px; overflow: hidden;
  }

  .table-custom { width: 100%; border-collapse: collapse; }
  .table-custom th {
    background: rgba(255,255,255,0.02);
    padding: 1rem 1.25rem; text-align: left;
    font-family:'JetBrains Mono',monospace; font-size:11px;
    text-transform: uppercase; letter-spacing: .1em; color: var(--muted);
    border-bottom: 1px solid var(--border);
  }
  .table-custom td {
    padding: 1.25rem; border-bottom: 1px solid var(--border);
    font-size: 14px; vertical-align: middle;
  }
  .table-custom tr:last-child td { border-bottom: none; }
  .table-custom tr:hover td { background: rgba(255,255,255,0.01); }

  /* Room Info Cell */
  .room-main { font-weight: 600; color: var(--text); display: block; }
  .room-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

  /* Badge */
  .badge-type {
    display: inline-flex; align-items: center;
    padding: 4px 10px; border-radius: 6px;
    font-size: 11px; font-weight: 600;
    background: var(--accent-bg); color: var(--accent);
    border: 1px solid rgba(232,201,106,0.2);
  }
  .badge-cap {
    font-family:'JetBrains Mono',monospace; font-size: 12px; color: var(--text);
  }

  /* Progress Bar Mini */
  .prog-wrap { width: 100px; margin-top: 6px; }
  .prog-bar { height: 4px; background: rgba(255,255,255,0.05); border-radius: 2px; overflow: hidden; }
  .prog-fill { height: 100%; background: var(--accent); border-radius: 2px; }

  /* Action Buttons */
  .btn-group-actions { display: flex; gap: 8px; }
  .btn-circle {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--border); background: var(--surface);
    color: var(--muted); transition: all .2s; cursor: pointer;
    text-decoration: none;
  }
  .btn-circle:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-2px); }
  .btn-circle.btn-del:hover { border-color: var(--danger); color: var(--danger); }

  /* New Room Button */
  .btn-new {
    background: var(--accent); color: #0d0f14;
    padding: 10px 22px; border-radius: 10px;
    font-size: 14px; font-weight: 600; text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all .2s;
  }
  .btn-new:hover { background: #f0d47a; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(232,201,106,0.2); }

</style>

<div class="cw">
  <div class="container-fluid px-3 px-md-5">

    <div class="cw-head">
      <div>
        <h2>Quản lý phòng chiếu</h2>
        <div class="cw-count">Tổng số: {{ $rooms->count() }} phòng chiếu</div>
      </div>
      <a href="{{ route('admin.rooms.create') }}" class="btn-new">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tạo phòng mới
      </a>
    </div>

    <div class="cw-card">
      <table class="table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Thông tin phòng</th>
            <th>Loại phòng</th>
            <th>Sức chứa</th>
            <th>Cấu hình ghế</th>
            <th style="text-align: right;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rooms as $room)
          @php
            $percent = ($room->capacity > 0) ? ($room->seats->count() / $room->capacity) * 100 : 0;
            $percent = min(100, $percent);
          @endphp
          <tr>
            <td style="font-family:'JetBrains Mono'; color: var(--muted); width: 80px;">
              #{{ str_pad($room->id, 3, '0', STR_PAD_LEFT) }}
            </td>
            <td>
              <span class="room-main">{{ $room->room_name }}</span>
              <span class="room-sub">Cập nhật: {{ $room->updated_at->format('d/m/Y') }}</span>
            </td>
            <td>
              <span class="badge-type">{{ $room->roomType->type }}</span>
            </td>
            <td>
              <span class="badge-cap">{{ $room->capacity }} ghế</span>
            </td>
            <td>
              <div class="room-main" style="font-size: 13px;">
                {{ $room->seats->count() }} / {{ $room->capacity }}
              </div>
              <div class="prog-wrap">
                <div class="prog-bar">
                  <div class="prog-fill" style="width: {{ $percent }}%; background: {{ $percent == 100 ? '#10b981' : '#e8c96a' }}"></div>
                </div>
              </div>
            </td>
            <td>
              <div class="btn-group-actions" style="justify-content: flex-end;">
                <a href="{{ route('admin.rooms.show', $room) }}" class="btn-circle" title="Xem chi tiết">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="{{ route('admin.rooms.edit', $room) }}" class="btn-circle" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Xác nhận xóa phòng này?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-circle btn-del" title="Xóa">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      
      @if($rooms->isEmpty())
        <div style="text-align: center; padding: 4rem; color: var(--muted);">
          <div style="font-size: 40px; margin-bottom: 1rem; opacity: 0.2;">🎬</div>
          <p>Chưa có phòng chiếu nào được tạo.</p>
        </div>
      @endif
    </div>

  </div>
</div>

@endsection