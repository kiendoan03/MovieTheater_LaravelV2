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
  .sw-head { display:flex; align-items:center; gap:.75rem; margin-bottom:2rem; }
  .sw-head h2 { font-size:1.3rem; font-weight:600; margin:0; }
  .sw-crumb {
    font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--muted);
    background:var(--card); border:1px solid var(--border); padding:3px 10px; border-radius:20px;
  }
  .sw-badge-edit {
    font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--accent);
    background:var(--accent-bg); border:1px solid rgba(232,201,106,.25);
    padding:3px 10px; border-radius:20px;
  }

  .sw-card {
    background:var(--card); border:1px solid var(--border);
    border-radius:14px; padding:1.5rem; margin-bottom:1.25rem; transition:border-color .2s;
  }
  .sw-card:hover { border-color:var(--border-h); }
  .sw-section-label {
    font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:.12em;
    color:var(--muted); text-transform:uppercase; margin-bottom:1.25rem;
    display:flex; align-items:center; gap:10px;
  }
  .sw-section-label::after { content:''; flex:1; height:1px; background:var(--border); }

  .sw-label { display:block; font-size:11px; font-weight:500; color:var(--muted); margin-bottom:6px; letter-spacing:.04em; }
  .sw-input, .sw-select {
    width:100%; background:var(--surface); border:1px solid var(--border);
    border-radius:8px; color:var(--text); font-family:'Sora',sans-serif;
    font-size:13px; padding:9px 12px; outline:none; transition:border-color .2s;
    -webkit-appearance:none; appearance:none;
  }
  .sw-input:focus, .sw-select:focus { border-color:var(--accent); }
  .sw-select-wrap { position:relative; }
  .sw-select-wrap::after {
    content:''; position:absolute; right:12px; top:50%; transform:translateY(-50%);
    border:4px solid transparent; border-top-color:var(--muted); border-bottom:none; pointer-events:none;
  }
  .sw-select option { background:#1a1e28; }
  .sw-error { font-size:11px; color:var(--danger); margin-top:4px; display:block; }

  .movie-preview {
    background:var(--surface); border:1px solid var(--border); border-radius:10px;
    padding:12px 16px; margin-top:.75rem; display:none; gap:14px; align-items:flex-start;
  }
  .movie-preview.show { display:flex; }
  .movie-preview-title { font-size:14px; font-weight:500; margin-bottom:4px; }
  .movie-preview-meta { font-size:11px; color:var(--muted); line-height:1.6; }
  .movie-duration-badge {
    font-family:'JetBrains Mono',monospace; font-size:11px;
    background:var(--accent-bg); color:var(--accent);
    border:1px solid rgba(232,201,106,.2); padding:2px 8px; border-radius:20px;
    display:inline-block; margin-top:4px;
  }

  .time-row { display:grid; grid-template-columns:1fr 1fr auto; gap:12px; align-items:flex-end; }
  .btn-calc {
    background:var(--surface); border:1px solid var(--border); color:var(--muted);
    font-family:'Sora',sans-serif; font-size:12px; padding:9px 14px;
    border-radius:8px; cursor:pointer; transition:all .2s; white-space:nowrap;
  }
  .btn-calc:hover { border-color:var(--accent); color:var(--accent); }

  .conflict-banner {
    background:rgba(248,113,113,.08); border:1px solid rgba(248,113,113,.3);
    border-radius:10px; padding:12px 16px; margin-bottom:1rem;
    font-size:13px; color:#f87171; display:flex; gap:10px; align-items:flex-start;
  }

  .room-info {
    background:var(--surface); border:1px solid var(--border); border-radius:10px;
    padding:12px 16px; margin-top:.75rem; display:flex; flex-wrap:wrap; gap:16px;
  }
  .room-info-item { font-size:12px; color:var(--muted); }
  .room-info-item strong { color:var(--text); display:block; font-size:14px; font-weight:500; }

  .schedule-list { margin-top:.75rem; display:flex; flex-direction:column; gap:6px; }
  .schedule-item {
    background:var(--surface); border:1px solid var(--border); border-radius:8px;
    padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; font-size:12px;
  }
  .schedule-item-time { font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--accent); flex-shrink:0; }
  .schedule-item-movie { color:var(--text); font-weight:500; }
  .schedule-item-gap { font-size:11px; padding:2px 8px; border-radius:20px; flex-shrink:0; }
  .schedule-item-gap.ok   { background:rgba(74,222,128,.1); color:#4ade80; }
  .schedule-item-gap.warn { background:rgba(248,113,113,.1); color:#f87171; }

  /* lock warning nếu có confirmed booking */
  .lock-banner {
    background:rgba(232,201,106,.08); border:1px solid rgba(232,201,106,.3);
    border-radius:10px; padding:12px 16px; margin-bottom:1rem;
    font-size:13px; color:var(--accent); display:flex; gap:10px;
  }

  .sw-footer {
    display:flex; gap:10px; justify-content:flex-end; align-items:center;
    padding-top:1.5rem; border-top:1px solid var(--border); margin-top:1.5rem;
  }
  .btn-cancel {
    background:transparent; border:1px solid var(--border); color:var(--muted);
    font-family:'Sora',sans-serif; font-size:13px; padding:9px 20px;
    border-radius:8px; cursor:pointer; text-decoration:none;
    display:inline-flex; align-items:center; transition:all .2s;
  }
  .btn-cancel:hover { border-color:var(--border-h); color:var(--text); }
  .btn-submit {
    background:var(--accent); border:none; color:#0d0f14;
    font-family:'Sora',sans-serif; font-size:13px; font-weight:600;
    padding:10px 28px; border-radius:8px; cursor:pointer;
    display:inline-flex; align-items:center; gap:7px; transition:all .2s;
  }
  .btn-submit:hover { background:#f0d47a; transform:translateY(-1px); }
  .hint { font-size:11px; color:var(--muted); margin-top:6px; line-height:1.6; }
</style>

<div class="sw">
  <div class="container-fluid px-3 px-md-4">

    <div class="sw-head">
      <h2>Chỉnh sửa lịch chiếu</h2>
    </div>

    @php
      $confirmedCount = $schedule->bookings->where('status', '!=', 'pending')->count();
    @endphp

    @if($confirmedCount > 0)
      <div class="lock-banner">
        <span>⚠</span>
        <span>Lịch chiếu này đã có <strong>{{ $confirmedCount }}</strong> vé xác nhận. Một số thay đổi có thể bị hạn chế.</span>
      </div>
    @endif

    @if($errors->has('conflict'))
      <div class="conflict-banner">
        <span>⚠</span>
        <span>{{ $errors->first('conflict') }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" id="scheduleForm">
      @csrf
      @method('PUT')

      {{-- 01 PHIM --}}
      <div class="sw-card">
        <div class="sw-section-label">01 — Chọn phim</div>
        <div class="sw-select-wrap">
          <select class="sw-select" id="movie_id" name="movie_id" required onchange="onMovieChange()">
            <option value="">-- Chọn phim --</option>
            @foreach($movies as $m)
            {{ $m }}
              <option value="{{ $m->id }}"
                data-duration="{{ $m->length ?? '' }}"
                data-genre="{{ $m->category->name ?? '' }}"
                data-title="{{ $m->movie_name ?? '' }}"
                {{ old('movie_id', $schedule->movie_id) == $m->id ? 'selected' : '' }}>
                {{ $m->movie_name }} @if($m->length)({{ $m->length }} phút)@endif
              </option>
            @endforeach
          </select>
        </div>
        @error('movie_id')<span class="sw-error">{{ $message }}</span>@enderror

        <div class="movie-preview" id="moviePreview">
          <div style="width:44px;height:64px;border-radius:6px;background:rgba(255,255,255,.05);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:20px;opacity:.3">🎬</div>
          <div>
            <div class="movie-preview-title" id="previewTitle"></div>
            <div class="movie-preview-meta" id="previewMeta"></div>
            <span class="movie-duration-badge" id="previewDuration"></span>
          </div>
        </div>
      </div>

      {{-- 02 PHÒNG --}}
      <div class="sw-card">
        <div class="sw-section-label">02 — Chọn phòng chiếu</div>
        <div class="sw-select-wrap">
          <select class="sw-select" id="room_id" name="room_id" required onchange="onRoomChange()">
            <option value="">-- Chọn phòng --</option>
            @foreach($rooms as $r)
              <option value="{{ $r->id }}"
                data-capacity="{{ $r->capacity }}"
                data-type="{{ $r->roomType->type ?? '' }}"
                {{ old('room_id', $schedule->room_id) == $r->id ? 'selected' : '' }}>
                {{ $r->room_name }} ({{ $r->roomType->type ?? '' }} · {{ $r->capacity }} ghế)
              </option>
            @endforeach
          </select>
        </div>
        @error('room_id')<span class="sw-error">{{ $message }}</span>@enderror

        <div class="room-info" id="roomInfo">
          <div class="room-info-item"><strong id="roomInfoName">{{ $schedule->room->room_name ?? '—' }}</strong>Tên phòng</div>
          <div class="room-info-item"><strong id="roomInfoType">{{ $schedule->room->roomType->type ?? '—' }}</strong>Loại phòng</div>
          <div class="room-info-item"><strong id="roomInfoCapacity">{{ $schedule->room->capacity ?? '—' }} ghế</strong>Sức chứa</div>
        </div>
      </div>

      {{-- 03 NGÀY & GIỜ --}}
      <div class="sw-card">
        <div class="sw-section-label">03 — Ngày &amp; giờ chiếu</div>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="sw-label" for="start_time">Giờ bắt đầu</label>
            <input type="datetime-local" class="sw-input" id="start_time" name="start_time"
              value="{{ old('start_time', $schedule->start_time->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d\TH:i')) }}"
              required onchange="onDateTimeChange()">
            @error('start_time')<span class="sw-error">{{ $message }}</span>@enderror
          </div>
          <div class="col-md-6">
            <label class="sw-label" for="end_time">Giờ kết thúc</label>
            <div style="display:flex;gap:8px;align-items:flex-end">
              <div style="flex:1">
                <input type="datetime-local" class="sw-input" id="end_time" name="end_time"
                  value="{{ old('end_time', $schedule->end_time->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d\TH:i')) }}"
                  required onchange="onDateTimeChange()">
                @error('end_time')<span class="sw-error">{{ $message }}</span>@enderror
              </div>
              <button type="button" class="btn-calc" onclick="calcEndTime()" title="Tự tính giờ kết thúc từ thời lượng phim">
                ⏱ Tự tính
              </button>
            </div>
          </div>
          <div class="col-12">
            <p class="hint">
              <strong>Lưu ý:</strong> Chọn cả ngày và giờ bắt đầu, giờ kết thúc. 
              Nhấn <strong>Tự tính</strong> để điền giờ kết thúc tự động từ thời lượng phim.
              Nếu qua ngày hôm sau, hệ thống sẽ tự động chuyển ngày kết thúc.
              Giữa 2 lịch chiếu trong cùng phòng phải cách nhau ít nhất <strong>30 phút</strong> để dọn dẹp phòng.
            </p>
          </div>
        </div>
            <p class="hint">Giữa 2 lịch chiếu trong cùng phòng phải cách nhau ít nhất <strong>30 phút</strong>.</p>
          </div>
        </div>

        <div id="existingSchedules" style="display:none">
          <div style="font-size:11px;font-family:'JetBrains Mono',monospace;letter-spacing:.08em;color:var(--muted);text-transform:uppercase;margin-bottom:8px;">
            Lịch chiếu đã có trong phòng này hôm đó
          </div>
          <div class="schedule-list" id="scheduleList"></div>
        </div>
      </div>

      <div class="sw-footer">
        <a href="{{ route('admin.schedules.index') }}" class="btn-cancel">Hủy bỏ</a>
        <button type="submit" class="btn-submit">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
            <path d="M13.5 1h-11A1.5 1.5 0 001 2.5v11A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0013.5 1zM8 12.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5zM10.5 5h-7a.5.5 0 010-1h7a.5.5 0 010 1z"/>
          </svg>
          Lưu thay đổi
        </button>
      </div>
    </form>
  </div>
</div>

<div id="scheduleId" data-id="{{ $schedule->id }}" style="display:none"></div>

<script>
const BUFFER_MINUTES = 30;
const CURRENT_SCHEDULE_ID = parseInt(document.getElementById('scheduleId').dataset.id);
let selectedMovieDuration = null;

function onMovieChange() {
  const sel = document.getElementById('movie_id');
  const opt = sel.options[sel.selectedIndex];
  selectedMovieDuration = parseInt(opt.dataset.duration) || null;
  const preview = document.getElementById('moviePreview');
  if (opt.value) {
    preview.classList.add('show');
    document.getElementById('previewTitle').textContent   = opt.dataset.title;
    document.getElementById('previewMeta').textContent    = opt.dataset.genre || 'Không rõ thể loại';
    document.getElementById('previewDuration').textContent = selectedMovieDuration ? `⏱ ${selectedMovieDuration} phút` : 'Thời lượng chưa cập nhật';
  } else {
    preview.classList.remove('show');
  }
}

function onRoomChange() {
  const sel = document.getElementById('room_id');
  const opt = sel.options[sel.selectedIndex];
  if (opt.value) {
    document.getElementById('roomInfoName').textContent     = opt.text.split('(')[0].trim();
    document.getElementById('roomInfoType').textContent     = opt.dataset.type || '—';
    document.getElementById('roomInfoCapacity').textContent = opt.dataset.capacity ? opt.dataset.capacity + ' ghế' : '—';
  }
  loadExistingSchedules();
}

function onDateTimeChange() { loadExistingSchedules(); }

function calcEndTime() {
  const startVal = document.getElementById('start_time').value;
  if (!startVal) { alert('Vui lòng chọn giờ bắt đầu trước.'); return; }
  if (!selectedMovieDuration) { alert('Phim chưa có thông tin thời lượng.'); return; }

  // Parse datetime-local format (YYYY-MM-DDTHH:mm)
  const start = new Date(startVal + ':00'); // Thêm :00 để parse đúng
  const endMs = start.getTime() + selectedMovieDuration * 60 * 1000;
  const end = new Date(endMs);
  
  // Format lại thành datetime-local (YYYY-MM-DDTHH:mm)
  const year = end.getFullYear();
  const month = String(end.getMonth() + 1).padStart(2, '0');
  const day = String(end.getDate()).padStart(2, '0');
  const hour = String(end.getHours()).padStart(2, '0');
  const minute = String(end.getMinutes()).padStart(2, '0');
  
  document.getElementById('end_time').value = `${year}-${month}-${day}T${hour}:${minute}`;
  onDateTimeChange();
}

async function loadExistingSchedules() {
  const roomId = document.getElementById('room_id').value;
  const startVal = document.getElementById('start_time').value;
  if (!roomId || !startVal) { document.getElementById('existingSchedules').style.display='none'; return; }
  
  // Extract date from datetime-local
  const date = startVal.split('T')[0];

  try {
    const res  = await fetch(`/Admin/Schedule/by-room?room_id=${roomId}&date=${date}&exclude=${CURRENT_SCHEDULE_ID}`);
    const data = await res.json();
    renderScheduleList(data.schedules || []);
  } catch(e) { console.error(e); }
}

function renderScheduleList(schedules) {
  const wrap = document.getElementById('existingSchedules');
  const list = document.getElementById('scheduleList');
  if (!schedules.length) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'block';
  const newStart = document.getElementById('start_time').value;
  const newEnd   = document.getElementById('end_time').value;
  list.innerHTML = schedules.map(s => {
    let gapHtml = '';
    if (newStart && newEnd) {
      const gap = calcGap(s.start_time, s.end_time, newStart, newEnd);
      if (gap !== null) {
        const ok = gap >= BUFFER_MINUTES;
        gapHtml = `<span class="schedule-item-gap ${ok ? 'ok' : 'warn'}">${gap >= 0 ? '+' : ''}${gap} phút</span>`;
      }
    }
    // Hiển thị datetime dưới dạng HH:mm (nếu cùng ngày thì không cần full datetime)
    const startTime = s.start_time.slice(11, 16);
    const endTime = s.end_time.slice(11, 16);
    const startDate = s.start_time.slice(0, 10);
    const endDate = s.end_time.slice(0, 10);
    const timeDisplay = startDate === endDate 
      ? `${startTime} – ${endTime}`
      : `${startTime} – ${endTime} (qua ngày)`;
    
    return `<div class="schedule-item">
      <span class="schedule-item-time">${timeDisplay}</span>
      <span class="schedule-item-movie">${s.movie_name}</span>
      ${gapHtml}
    </div>`;
  }).join('');
}

/**
 * Tính khoảng cách (phút) giữa lịch mới và lịch cũ dùng datetime-local format.
 * Trả về số dương = khoảng cách, số âm = overlap.
 */
function calcGap(exStart, exEnd, newStart, newEnd) {
  // Format datetime-local (YYYY-MM-DDTHH:mm) to timestamps
  const toMs = dt => new Date(dt + ':00').getTime();
  const es = toMs(exStart), ee = toMs(exEnd);
  const ns = toMs(newStart), ne = toMs(newEnd);
  
  const MINUTE_MS = 60 * 1000;
  if (ne <= es) return Math.floor((es - ne) / MINUTE_MS); // new trước ex
  if (ns >= ee) return Math.floor((ns - ee) / MINUTE_MS); // new sau ex
  // overlap → trả về số âm (khoảng nhỏ nhất)
  return Math.floor(Math.min((ns - ee) / MINUTE_MS, (es - ne) / MINUTE_MS));
}

document.addEventListener('DOMContentLoaded', () => {
  onMovieChange();
  onRoomChange();
});
</script>

@endsection