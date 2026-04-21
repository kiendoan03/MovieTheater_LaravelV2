@extends('layouts.management')
@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

  :root {
    --bg:        #0d0f14;
    --surface:   #13161e;
    --card:      #1a1e28;
    --border:    rgba(255,255,255,0.07);
    --border-h:  rgba(255,255,255,0.15);
    --text:      #e8eaf0;
    --muted:     #6b7280;
    --accent:    #e8c96a;
    --accent-bg: rgba(232,201,106,0.10);
    --danger:    #f87171;
  }

  .cw { font-family:'Sora',sans-serif; color:var(--text); padding:2rem 0 5rem; }

  .cw-head { display:flex; align-items:center; gap:.75rem; margin-bottom:2rem; }
  .cw-head h2 { font-size:1.3rem; font-weight:600; letter-spacing:-.01em; margin:0; }
  .cw-crumb {
    font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--muted);
    background:var(--card); border:1px solid var(--border);
    padding:3px 10px; border-radius:20px;
  }
  .cw-badge-edit {
    font-family:'JetBrains Mono',monospace; font-size:11px;
    color:var(--accent); background:var(--accent-bg);
    border:1px solid rgba(232,201,106,.25);
    padding:3px 10px; border-radius:20px;
  }

  .cw-card {
    background:var(--card); border:1px solid var(--border);
    border-radius:14px; padding:1.5rem; margin-bottom:1.25rem;
    transition:border-color .2s;
  }
  .cw-card:hover { border-color:var(--border-h); }
  .cw-section-label {
    font-family:'JetBrains Mono',monospace; font-size:10px;
    letter-spacing:.12em; color:var(--muted); text-transform:uppercase;
    margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;
  }
  .cw-section-label::after { content:''; flex:1; height:1px; background:var(--border); }

  .cw-label { display:block; font-size:11px; font-weight:500; color:var(--muted); margin-bottom:6px; letter-spacing:.04em; }
  .cw-input, .cw-select {
    width:100%; background:var(--surface); border:1px solid var(--border);
    border-radius:8px; color:var(--text); font-family:'Sora',sans-serif;
    font-size:13px; padding:9px 12px; outline:none; transition:border-color .2s;
    -webkit-appearance:none; appearance:none;
  }
  .cw-input:focus, .cw-select:focus { border-color:var(--accent); }
  .cw-select-wrap { position:relative; }
  .cw-select-wrap::after {
    content:''; position:absolute; right:12px; top:50%; transform:translateY(-50%);
    border:4px solid transparent; border-top-color:var(--muted);
    border-bottom:none; pointer-events:none;
  }
  .cw-select option { background:#1a1e28; }
  .cw-error { font-size:11px; color:var(--danger); margin-top:4px; display:block; }

  /* capacity bar */
  .cap-wrap {
    background:var(--surface); border:1px solid var(--border);
    border-radius:10px; padding:12px 16px; margin-top:.75rem;
  }
  .cap-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
  .cap-top span { font-size:12px; color:var(--muted); }
  .cap-badge {
    font-family:'JetBrains Mono',monospace; font-size:12px;
    color:var(--accent); background:var(--accent-bg);
    padding:2px 10px; border-radius:20px; transition:color .2s, background .2s;
  }
  .cap-badge.over { color:#f87171; background:rgba(248,113,113,.12); }
  .cap-bar { height:4px; background:rgba(255,255,255,0.05); border-radius:2px; overflow:hidden; }
  .cap-fill { height:100%; border-radius:2px; background:var(--accent); width:0%; transition:width .4s cubic-bezier(.4,0,.2,1), background .3s; }

  /* grid controls */
  .grid-ctrl { display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:12px; align-items:flex-end; }
  @media(max-width:768px){ .grid-ctrl { grid-template-columns:1fr 1fr; } }

  /* ── PALETTE ── */
  .palette-wrap { display:flex; flex-direction:column; gap:10px; margin-bottom:1.25rem; }
  .palette-group { display:flex; flex-wrap:wrap; gap:7px; align-items:center; }
  .palette-divider { width:1px; height:28px; background:var(--border); flex-shrink:0; margin:0 4px; }
  .palette-group-label {
    font-family:'JetBrains Mono',monospace; font-size:9px;
    letter-spacing:.1em; color:var(--muted); text-transform:uppercase; margin-bottom:4px;
  }

  .tool-btn {
    display:flex; align-items:center; gap:7px;
    padding:6px 13px; border-radius:8px;
    border:1.5px solid var(--border); background:var(--surface);
    color:var(--text); font-family:'Sora',sans-serif;
    font-size:12px; font-weight:500; cursor:pointer;
    transition:all .15s; user-select:none; white-space:nowrap;
  }
  .tool-btn:hover { border-color:var(--border-h); }
  .tool-btn.active { background:rgba(255,255,255,0.05); }
  .tool-dot { width:11px; height:11px; border-radius:3px; flex-shrink:0; }
  .tool-price { font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--muted); }

  .scope-btn {
    display:flex; align-items:center; gap:5px;
    padding:5px 11px; border-radius:7px;
    border:1.5px solid var(--border); background:var(--surface);
    color:var(--muted); font-family:'Sora',sans-serif;
    font-size:11px; font-weight:500; cursor:pointer; transition:all .15s; white-space:nowrap;
  }
  .scope-btn:hover { border-color:var(--border-h); color:var(--text); }
  .scope-btn.active { border-color:var(--accent); color:var(--accent); background:var(--accent-bg); }
  .scope-icon { font-size:12px; line-height:1; }

  .aisle-tool-btn {
    display:flex; align-items:center; gap:7px;
    padding:6px 13px; border-radius:8px;
    border:1.5px dashed var(--border); background:transparent;
    color:var(--muted); font-family:'Sora',sans-serif;
    font-size:12px; font-weight:500; cursor:pointer; transition:all .15s; white-space:nowrap;
  }
  .aisle-tool-btn:hover, .aisle-tool-btn.active {
    border-color:rgba(232,201,106,.6); color:var(--accent); background:rgba(232,201,106,.05);
  }
  .erase-btn {
    display:flex; align-items:center; gap:7px;
    padding:6px 13px; border-radius:8px;
    border:1.5px solid var(--border); background:var(--surface);
    color:var(--muted); font-family:'Sora',sans-serif;
    font-size:12px; font-weight:500; cursor:pointer; transition:all .15s; white-space:nowrap;
  }
  .erase-btn:hover, .erase-btn.active {
    border-color:var(--danger); color:var(--danger); background:rgba(248,113,113,.05);
  }

  /* highlight row / col */
  .seat-row.row-highlight .seat { box-shadow:0 0 0 1.5px rgba(232,201,106,.35); }
  .seat.col-highlight { box-shadow:0 0 0 1.5px rgba(232,201,106,.35); }
  .seat-row.row-erase .seat { box-shadow:0 0 0 1.5px rgba(248,113,113,.35); }
  .seat.col-erase { box-shadow:0 0 0 1.5px rgba(248,113,113,.35); }

  /* screen */
  .screen-area { text-align:center; margin:1.5rem 0 1.75rem; }
  .screen-bar {
    display:inline-block; width:55%; height:5px; border-radius:3px;
    background:linear-gradient(90deg,transparent,rgba(232,201,106,.45),transparent);
    position:relative;
  }
  .screen-bar::after {
    content:''; position:absolute; bottom:-12px; left:10%; right:10%; height:22px;
    background:linear-gradient(180deg,rgba(232,201,106,.07),transparent);
    border-radius:0 0 50% 50%;
  }
  .screen-lbl {
    font-family:'JetBrains Mono',monospace; font-size:10px;
    letter-spacing:.18em; color:rgba(232,201,106,.35); margin-top:14px;
  }

  /* seat grid */
  .seat-grid-wrap { overflow-x:auto; padding-bottom:4px; }
  .seat-grid-inner { display:inline-flex; flex-direction:column; align-items:flex-start; }
  .seat-grid-center { text-align:center; }

  .seat-row { display:flex; align-items:center; gap:4px; margin-bottom:5px; flex-wrap:nowrap; }
  .row-lbl {
    font-family:'JetBrains Mono',monospace; font-size:11px;
    color:var(--muted); width:20px; text-align:center; flex-shrink:0;
    cursor:default; transition:color .15s;
  }
  .row-lbl.clickable { cursor:pointer; color:var(--accent); font-weight:600; }
  .row-lbl.clickable:hover { color:#fff; }

  .seat {
    width:32px; height:32px; border-radius:6px;
    border:1.5px solid transparent; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:500;
    flex-shrink:0; transition:transform .1s, box-shadow .1s; user-select:none;
  }
  .seat:hover { transform:scale(1.12); z-index:2; position:relative; }
  .seat.empty {
    background:rgba(255,255,255,0.025); border-color:rgba(255,255,255,0.05);
  }
  .seat.empty:hover { border-color:rgba(255,255,255,0.18); background:rgba(255,255,255,.055); }

  .col-header-row { display:flex; align-items:center; gap:4px; margin-bottom:3px; flex-wrap:nowrap; }
  .col-hdr {
    width:32px; height:18px; display:flex; align-items:center; justify-content:center;
    font-family:'JetBrains Mono',monospace; font-size:9px; color:var(--muted);
    cursor:default; border-radius:4px; transition:all .15s; flex-shrink:0; user-select:none;
  }
  .col-hdr.clickable { cursor:pointer; color:var(--accent); font-weight:600; }
  .col-hdr.clickable:hover { background:rgba(232,201,106,.1); color:#fff; }
  .col-hdr-spacer { width:20px; flex-shrink:0; }
  .col-hdr-aisle  { width:18px; flex-shrink:0; }

  .seat-aisle {
    width:18px; flex-shrink:0; display:flex; align-items:center; justify-content:center;
    position:relative; cursor:pointer;
  }
  .seat-aisle::before {
    content:''; position:absolute; top:4px; bottom:4px; left:50%;
    transform:translateX(-50%); width:1px;
    background:rgba(232,201,106,.2); border-radius:1px;
  }
  .seat-aisle:hover::before { background:rgba(248,113,113,.6); }
  .seat-aisle .aisle-del { display:none; font-size:9px; color:var(--danger); z-index:1; line-height:1; }
  .seat-aisle:hover .aisle-del { display:block; }

  /* legend */
  .legend { display:flex; flex-wrap:wrap; gap:14px; padding-top:1rem; border-top:1px solid var(--border); margin-top:1rem; }
  .legend-item { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--muted); }
  .legend-dot { width:12px; height:12px; border-radius:3px; }

  /* stats */
  .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); gap:10px; }
  .stat-box { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:12px 14px; }
  .stat-lbl { font-size:10px; color:var(--muted); margin-bottom:4px; }
  .stat-val { font-size:20px; font-weight:600; font-family:'JetBrains Mono',monospace; }

  /* footer */
  .cw-footer {
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
  .btn-submit:active { transform:translateY(0); }
  .btn-gen {
    background:var(--surface); border:1px solid var(--border); color:var(--text);
    font-family:'Sora',sans-serif; font-size:13px; font-weight:500;
    padding:9px 20px; border-radius:8px; cursor:pointer; transition:all .2s; white-space:nowrap;
  }
  .btn-gen:hover { border-color:var(--accent); color:var(--accent); }
  .hint { font-size:11px; color:var(--muted); margin-top:8px; line-height:1.7; }

  /* toast */
  .toast {
    position:fixed; bottom:2rem; left:50%; transform:translateX(-50%) translateY(80px);
    background:#1a1e28; border:1px solid rgba(248,113,113,.4); color:#f87171;
    font-family:'Sora',sans-serif; font-size:13px; font-weight:500;
    padding:10px 20px; border-radius:10px; z-index:9999;
    transition:transform .3s cubic-bezier(.4,0,.2,1), opacity .3s;
    opacity:0; pointer-events:none; white-space:nowrap;
  }
  .toast.show { transform:translateX(-50%) translateY(0); opacity:1; }
</style>

<div class="cw">
  <div class="container-fluid px-3 px-md-4">

    <div class="cw-head">
      <h2>Chỉnh sửa phòng chiếu</h2>
      <span class="cw-badge-edit">{{ $room->room_name }}</span>
      <span class="cw-crumb">Admin / Phòng chiếu / Chỉnh sửa</span>
    </div>

    <form method="POST" action="{{ route('admin.rooms.update', $room) }}" id="editRoomForm">
      @csrf
      @method('PUT')

      {{-- 01 THÔNG TIN PHÒNG --}}
      <div class="cw-card">
        <div class="cw-section-label">01 — Thông tin phòng chiếu</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="cw-label" for="room_name">Tên phòng chiếu</label>
            <input type="text" class="cw-input" id="room_name" name="room_name"
              value="{{ old('room_name', $room->room_name) }}" required>
            @error('room_name')<span class="cw-error">{{ $message }}</span>@enderror
          </div>
          <div class="col-md-6">
            <label class="cw-label" for="type_id">Loại phòng chiếu</label>
            <div class="cw-select-wrap">
              <select class="cw-select" id="type_id" name="type_id"
                required onchange="handleRoomTypeChange()">
                <option value="">-- Chọn loại phòng --</option>
                @foreach($roomTypes as $rt)
                  <option value="{{ $rt->id }}" data-capacity="{{ $rt->capacity }}"
                    {{ old('type_id', $room->type_id) == $rt->id ? 'selected' : '' }}>
                    {{ $rt->type }} · Sức chứa {{ $rt->capacity }} ghế
                  </option>
                @endforeach
              </select>
            </div>
            @error('type_id')<span class="cw-error">{{ $message }}</span>@enderror
          </div>
        </div>

        {{-- Capacity bar — luôn hiện vì đã có loại phòng sẵn --}}
        <div class="cap-wrap" id="capWrap">
          <div class="cap-top">
            <span>Số ghế đã thiết kế / sức chứa tối đa</span>
            <span class="cap-badge" id="capBadge">0 / 0 ghế</span>
          </div>
          <div class="cap-bar"><div class="cap-fill" id="capFill"></div></div>
        </div>
      </div>

      {{-- 02 CẤU HÌNH LƯỚI --}}
      <div class="cw-card">
        <div class="cw-section-label">02 — Cấu hình lưới ghế</div>
        <div class="grid-ctrl">
          <div>
            <label class="cw-label" for="inp_rows">Số hàng (A – Z)</label>
            <input type="number" class="cw-input" id="inp_rows" name="rows"
              min="1" max="26" value="{{ old('rows', $maxRow) }}">
          </div>
          <div>
            <label class="cw-label" for="inp_cols">Số cột mỗi hàng</label>
            <input type="number" class="cw-input" id="inp_cols" name="columns"
              min="1" max="30" value="{{ old('columns', $maxCol) }}">
          </div>
          <div>
            <label class="cw-label" for="inp_def">Loại ghế mặc định (khi tạo lại)</label>
            <div class="cw-select-wrap">
              <select class="cw-select" id="inp_def">
                @foreach($seatTypes as $st)
                  <option value="{{ $st->id }}">{{ $st->type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div>
            <button type="button" class="btn-gen" onclick="regenerateGrid()">↺ Tạo lại</button>
          </div>
        </div>
        <p class="hint">
          Sơ đồ đang hiển thị ghế hiện tại của phòng. Nhấn <strong>Tạo lại</strong> để reset toàn bộ theo số hàng/cột mới.
          Hoặc chỉnh trực tiếp từng ghế bên dưới.
        </p>
      </div>

      {{-- 03 SƠ ĐỒ GHẾ --}}
      <div class="cw-card">
        <div class="cw-section-label">03 — Sơ đồ ghế ngồi</div>

        <div class="palette-wrap">
          <div>
            <div class="palette-group-label">Loại ghế / công cụ</div>
            <div class="palette-group" id="paletteTools"></div>
          </div>
          <div>
            <div class="palette-group-label">Áp dụng cho</div>
            <div class="palette-group" id="paletteScope">
              <button type="button" class="scope-btn active" id="scope-single" onclick="setScope('single')">
                <span class="scope-icon">▪</span> Một ghế
              </button>
              <button type="button" class="scope-btn" id="scope-row" onclick="setScope('row')">
                <span class="scope-icon">▬</span> Cả hàng
              </button>
              <button type="button" class="scope-btn" id="scope-col" onclick="setScope('col')">
                <span class="scope-icon">▮</span> Cả cột
              </button>
            </div>
          </div>
        </div>

        <div class="screen-area">
          <div class="screen-bar"></div>
          <div class="screen-lbl">MÀN HÌNH</div>
        </div>

        <div class="seat-grid-wrap">
          <div class="seat-grid-center" id="seatGridCenter"></div>
        </div>

        <div class="legend" id="legend"></div>
      </div>

      {{-- 04 THỐNG KÊ --}}
      <div class="cw-card" id="statsCard">
        <div class="cw-section-label">04 — Thống kê sơ đồ</div>
        <div class="stats-grid" id="statsGrid"></div>
      </div>

      {{-- Footer --}}
      <div class="cw-footer">
        <a href="{{ route('admin.rooms.index') }}" class="btn-cancel">Hủy bỏ</a>
        <button type="submit" class="btn-submit">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
            <path d="M13.5 1h-11A1.5 1.5 0 001 2.5v11A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-11A1.5 1.5 0 0013.5 1zM8 12.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5zM10.5 5h-7a.5.5 0 010-1h7a.5.5 0 010 1z"/>
          </svg>
          Lưu thay đổi
        </button>
      </div>

      <input type="hidden" id="seatsInput" name="seats" value="[]">
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>

@php
// No longer needed - color comes from database
@endphp

<script type="application/json" id="seatTypesData">
[
  @foreach($seatTypes as $st)
  { "id": {{ $st->id }}, "name": @json($st->type), "price": {{ (int) $st->price }}, "color": @json($st->color ?? '#6b7280') }{{ !$loop->last ? ',' : '' }}
  @endforeach
]
</script>

{{-- Ghế hiện có của phòng (load sẵn khi mở trang) --}}
<script type="application/json" id="existingSeatsData">
[
  @foreach($seats as $seat)
  { "row": {{ $seat->row }}, "column": {{ $seat->column }}, "type_id": {{ $seat->type_id }} }{{ !$loop->last ? ',' : '' }}
  @endforeach
]
</script>

{{-- Meta: capacity hiện tại --}}
<div id="formMeta"
  data-capacity="{{ $room->roomType->capacity ?? $room->capacity }}"
  data-max-row="{{ $maxRow }}"
  data-max-col="{{ $maxCol }}"
  style="display:none"></div>

<script>
const SEAT_TYPES   = JSON.parse(document.getElementById('seatTypesData').textContent);
const EXISTING     = JSON.parse(document.getElementById('existingSeatsData').textContent);
const typeMap      = Object.fromEntries(SEAT_TYPES.map(t => [t.id, t]));

/* ── State ── */
let activeTool  = SEAT_TYPES[0]?.id ?? null;
let activeScope = 'single';
let gridData    = [];
let aisles      = [];
let nRows = 0, nCols = 0, maxCap = 0;

/* ════════════════════════════════
   PALETTE
════════════════════════════════ */
function buildPalette() {
  const el = document.getElementById('paletteTools');
  el.innerHTML = '';

  SEAT_TYPES.forEach(t => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tool-btn' + (activeTool === t.id ? ' active' : '');
    btn.style.borderColor = activeTool === t.id ? t.color : '';
    btn.innerHTML = `<span class="tool-dot" style="background:${t.color}"></span>${t.name}<span class="tool-price">${(t.price/1000).toFixed(0)}k ₫</span>`;
    btn.onclick = () => { activeTool = t.id; buildPalette(); };
    el.appendChild(btn);
  });

  const div = document.createElement('div');
  div.className = 'palette-divider';
  el.appendChild(div);

  const aisleBtn = document.createElement('button');
  aisleBtn.type = 'button';
  aisleBtn.className = 'aisle-tool-btn' + (activeTool === 'aisle' ? ' active' : '');
  aisleBtn.innerHTML = `<span style="font-size:13px;opacity:.8">⇕</span> Lối đi`;
  aisleBtn.title = 'Click vào cột để chèn lối đi';
  aisleBtn.onclick = () => { activeTool = 'aisle'; activeScope = 'col'; buildPalette(); updateScopeBtns(); };
  el.appendChild(aisleBtn);

  const eraseBtn = document.createElement('button');
  eraseBtn.type = 'button';
  eraseBtn.className = 'erase-btn' + (activeTool === 'erase' ? ' active' : '');
  eraseBtn.innerHTML = '<span style="font-size:13px;line-height:1">✕</span> Xóa ghế';
  eraseBtn.onclick = () => { activeTool = 'erase'; buildPalette(); };
  el.appendChild(eraseBtn);
}

/* ════════════════════════════════
   SCOPE
════════════════════════════════ */
function setScope(scope) {
  if (activeTool === 'aisle') return;
  activeScope = scope;
  updateScopeBtns();
  renderGrid();
}

function updateScopeBtns() {
  ['single','row','col'].forEach(s =>
    document.getElementById('scope-' + s).classList.toggle('active', activeScope === s)
  );
}

/* ════════════════════════════════
   ROOM TYPE CHANGE
════════════════════════════════ */
function handleRoomTypeChange() {
  const opt = document.getElementById('type_id').options[document.getElementById('type_id').selectedIndex];
  maxCap = parseInt(opt?.dataset?.capacity) || 0;
  refreshCapBar();
}

function refreshCapBar() {
  if (!maxCap) return;
  const used = countSeats();
  const over = used > maxCap;
  const pct  = Math.min(100, Math.round(used / maxCap * 100));
  const badge = document.getElementById('capBadge');
  badge.textContent = `${used} / ${maxCap} ghế`;
  badge.classList.toggle('over', over);
  const fill = document.getElementById('capFill');
  fill.style.width = pct + '%';
  fill.style.background = over ? '#f87171' : pct > 85 ? '#f59e0b' : '#e8c96a';
}

function countSeats() {
  let n = 0;
  for (let r = 0; r < nRows; r++)
    for (let c = 0; c < nCols; c++)
      if (gridData[r]?.[c] !== null) n++;
  return n;
}

/* ════════════════════════════════
   LOAD EXISTING SEATS vào gridData
════════════════════════════════ */
function loadExistingSeats() {
  /* Tính kích thước grid từ ghế hiện có */
  nRows = parseInt(document.getElementById('inp_rows').value) || 1;
  nCols = parseInt(document.getElementById('inp_cols').value) || 1;

  /* Khởi tạo grid toàn ô trống */
  gridData = [];
  for (let r = 0; r < nRows; r++) {
    gridData[r] = [];
    for (let c = 0; c < nCols; c++) gridData[r][c] = null;
  }

  /* Đặt ghế hiện có vào đúng vị trí (row/col là 1-based từ DB) */
  EXISTING.forEach(s => {
    const r = s.row - 1;
    const c = s.column - 1;
    if (r >= 0 && r < nRows && c >= 0 && c < nCols) {
      gridData[r][c] = { type_id: s.type_id };
    }
  });
}

/* ════════════════════════════════
   REGENERATE — reset toàn bộ
════════════════════════════════ */
function regenerateGrid() {
  const newRows = Math.min(26, Math.max(1, parseInt(document.getElementById('inp_rows').value) || 6));
  const newCols = Math.min(30, Math.max(1, parseInt(document.getElementById('inp_cols').value) || 10));
  const defId   = parseInt(document.getElementById('inp_def').value) || SEAT_TYPES[0]?.id;

  if (newRows * newCols > maxCap) {
    showToast(`${newRows} × ${newCols} = ${newRows*newCols} ghế — vượt sức chứa ${maxCap}.`);
    return;
  }

  nRows = newRows;
  nCols = newCols;
  aisles = [];
  gridData = [];
  for (let r = 0; r < nRows; r++) {
    gridData[r] = [];
    for (let c = 0; c < nCols; c++) gridData[r][c] = { type_id: defId };
  }

  renderGrid();
  refreshStats();
  refreshCapBar();
}

/* ════════════════════════════════
   RENDER GRID DOM
════════════════════════════════ */
function renderGrid() {
  const center = document.getElementById('seatGridCenter');
  const oldInner = document.getElementById('seatGridInner');
  if (oldInner) oldInner.remove();

  const inner = document.createElement('div');
  inner.id = 'seatGridInner';
  inner.className = 'seat-grid-inner';

  /* Col header */
  const colHdrRow = document.createElement('div');
  colHdrRow.className = 'col-header-row';
  const spacer = document.createElement('div');
  spacer.className = 'col-hdr-spacer';
  colHdrRow.appendChild(spacer);

  for (let c = 0; c < nCols; c++) {
    if (aisles.includes(c)) {
      const ah = document.createElement('div'); ah.className = 'col-hdr-aisle';
      colHdrRow.appendChild(ah);
    }
    const hdr = document.createElement('div');
    hdr.className = 'col-hdr' + (activeScope === 'col' && activeTool !== 'aisle' ? ' clickable' : '');
    hdr.textContent = c + 1;
    hdr.dataset.c = c;
    if (activeScope === 'col' && activeTool !== 'aisle') {
      hdr.onclick = () => applyCol(c);
      hdr.onmouseenter = () => highlightCol(c, true);
      hdr.onmouseleave = () => highlightCol(c, false);
    }
    colHdrRow.appendChild(hdr);
  }
  inner.appendChild(colHdrRow);

  /* Seat rows */
  for (let r = 0; r < nRows; r++) {
    const row = document.createElement('div');
    row.className = 'seat-row';
    row.dataset.r = r;

    const lbl = document.createElement('span');
    lbl.className = 'row-lbl' + (activeScope === 'row' && activeTool !== 'aisle' ? ' clickable' : '');
    lbl.textContent = String.fromCharCode(65 + r);
    lbl.dataset.r = r;
    if (activeScope === 'row' && activeTool !== 'aisle') {
      lbl.onclick = () => applyRow(r);
      lbl.onmouseenter = () => highlightRow(r, true);
      lbl.onmouseleave = () => highlightRow(r, false);
    }
    row.appendChild(lbl);

    for (let c = 0; c < nCols; c++) {
      if (aisles.includes(c)) row.appendChild(makeAisleEl(c));

      const seatEl = document.createElement('div');
      seatEl.className = 'seat';
      seatEl.dataset.r = r;
      seatEl.dataset.c = c;
      applySeatStyle(seatEl, gridData[r][c], r, c);
      seatEl.onclick      = () => handleSeatClick(r, c, seatEl);
      seatEl.onmouseenter = () => { if (activeScope==='row') highlightRow(r,true); if (activeScope==='col') highlightCol(c,true); };
      seatEl.onmouseleave = () => { if (activeScope==='row') highlightRow(r,false); if (activeScope==='col') highlightCol(c,false); };
      row.appendChild(seatEl);
    }
    inner.appendChild(row);
  }
  center.appendChild(inner);
  syncHiddenInput();
}

/* ════════════════════════════════
   HIGHLIGHT
════════════════════════════════ */
function highlightRow(r, on) {
  const cls = activeTool === 'erase' ? 'row-erase' : 'row-highlight';
  const row = document.querySelector(`.seat-row[data-r="${r}"]`);
  if (row) row.classList.toggle(cls, on);
}
function highlightCol(c, on) {
  const cls = activeTool === 'erase' ? 'col-erase' : 'col-highlight';
  document.querySelectorAll(`.seat[data-c="${c}"]`).forEach(el => el.classList.toggle(cls, on));
}

/* ════════════════════════════════
   APPLY ROW / COL
════════════════════════════════ */
function applyRow(r) {
  if (activeTool === 'erase') {
    for (let c = 0; c < nCols; c++) gridData[r][c] = null;
  } else if (activeTool !== 'aisle') {
    const emptyInRow = gridData[r].filter(c => c === null).length;
    if (countSeats() + emptyInRow > maxCap) {
      showToast(`Thêm cả hàng ${String.fromCharCode(65+r)} sẽ vượt sức chứa ${maxCap} ghế!`); return;
    }
    for (let c = 0; c < nCols; c++) gridData[r][c] = { type_id: activeTool };
  }
  renderGrid(); refreshStats(); refreshCapBar();
}
function applyCol(c) {
  if (activeTool === 'erase') {
    for (let r = 0; r < nRows; r++) gridData[r][c] = null;
  } else if (activeTool !== 'aisle') {
    const emptyInCol = gridData.filter(row => row[c] === null).length;
    if (countSeats() + emptyInCol > maxCap) {
      showToast(`Thêm cả cột ${c+1} sẽ vượt sức chứa ${maxCap} ghế!`); return;
    }
    for (let r = 0; r < nRows; r++) gridData[r][c] = { type_id: activeTool };
  }
  renderGrid(); refreshStats(); refreshCapBar();
}

/* ════════════════════════════════
   AISLE
════════════════════════════════ */
function makeAisleEl(colIdx) {
  const el = document.createElement('div');
  el.className = 'seat-aisle';
  el.title = 'Click để xóa lối đi';
  el.innerHTML = '<span class="aisle-del">✕</span>';
  el.onclick = () => { aisles = aisles.filter(a => a !== colIdx); renderGrid(); refreshStats(); refreshCapBar(); };
  return el;
}

/* ════════════════════════════════
   SEAT STYLING
════════════════════════════════ */
function applySeatStyle(el, cell, r, c) {
  if (!cell) { setEmpty(el); return; }
  const t = typeMap[cell.type_id];
  if (!t) { setEmpty(el); return; }
  el.classList.remove('empty');
  el.style.background  = t.color + '28';
  el.style.borderColor = t.color;
  el.style.color       = t.color;
  el.textContent       = c + 1;
  el.title = `${String.fromCharCode(65+r)}${c+1} · ${t.name} · ${(t.price/1000).toFixed(0)}k ₫`;
}
function setEmpty(el) {
  el.classList.add('empty');
  el.style.background = el.style.borderColor = el.style.color = '';
  el.textContent = ''; el.title = 'Ô trống';
}

/* ════════════════════════════════
   SEAT CLICK
════════════════════════════════ */
function handleSeatClick(r, c, el) {
  if (activeTool === 'aisle') {
    if (!aisles.includes(c)) { aisles.push(c); aisles.sort((a,b)=>a-b); renderGrid(); }
    return;
  }
  if (activeScope === 'row') { applyRow(r); return; }
  if (activeScope === 'col') { applyCol(c); return; }

  if (activeTool === 'erase') {
    gridData[r][c] = null; setEmpty(el);
  } else {
    const wasEmpty = gridData[r][c] === null;
    if (wasEmpty && countSeats() >= maxCap) { showToast(`Đã đạt sức chứa tối đa ${maxCap} ghế!`); return; }
    gridData[r][c] = { type_id: activeTool };
    applySeatStyle(el, gridData[r][c], r, c);
  }
  syncHiddenInput(); refreshStats(); refreshCapBar();
}

/* ════════════════════════════════
   LEGEND
════════════════════════════════ */
function renderLegend() {
  const leg = document.getElementById('legend');
  leg.style.display = 'flex';
  leg.innerHTML =
    SEAT_TYPES.map(t =>
      `<div class="legend-item"><div class="legend-dot" style="background:${t.color}"></div>${t.name}</div>`
    ).join('') +
    `<div class="legend-item"><div class="legend-dot" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08)"></div>Ô trống</div>` +
    `<div class="legend-item"><div style="width:12px;height:12px;border-radius:2px;border:1px dashed rgba(232,201,106,.4)"></div>Lối đi</div>`;
}

/* ════════════════════════════════
   STATS
════════════════════════════════ */
function refreshStats() {
  const counts = {};
  SEAT_TYPES.forEach(t => { counts[t.id] = 0; });
  let total = 0;
  for (let r = 0; r < nRows; r++)
    for (let c = 0; c < nCols; c++) {
      const cell = gridData[r]?.[c];
      if (cell) { counts[cell.type_id] = (counts[cell.type_id]||0)+1; total++; }
    }
  const totalRev = SEAT_TYPES.reduce((s,t) => s + (counts[t.id]||0)*t.price, 0);
  document.getElementById('statsGrid').innerHTML =
    `<div class="stat-box"><div class="stat-lbl">Tổng ghế</div><div class="stat-val">${total}</div></div>` +
    SEAT_TYPES.map(t => `<div class="stat-box"><div class="stat-lbl">${t.name}</div><div class="stat-val" style="color:${t.color}">${counts[t.id]||0}</div></div>`).join('') +
    `<div class="stat-box"><div class="stat-lbl">Doanh thu tối đa</div><div class="stat-val" style="font-size:14px">${(totalRev/1e6).toFixed(1)}M ₫</div></div>`;
}

/* ════════════════════════════════
   SYNC HIDDEN INPUT
════════════════════════════════ */
function syncHiddenInput() {
  const seats = [];
  for (let r = 0; r < nRows; r++)
    for (let c = 0; c < nCols; c++) {
      const cell = gridData[r]?.[c];
      if (cell) seats.push({ row: r+1, column: c+1, type_id: cell.type_id });
    }
  document.getElementById('seatsInput').value = JSON.stringify(seats);
}

/* ════════════════════════════════
   TOAST
════════════════════════════════ */
let toastTimer;
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
}

/* ════════════════════════════════
   FORM SUBMIT GUARD
════════════════════════════════ */
document.getElementById('editRoomForm').addEventListener('submit', function(e) {
  if (!document.getElementById('type_id').value) {
    e.preventDefault(); showToast('Vui lòng chọn loại phòng chiếu.'); return;
  }
  const seats = JSON.parse(document.getElementById('seatsInput').value || '[]');
  if (!seats.length) {
    e.preventDefault(); showToast('Sơ đồ không có ghế nào. Vui lòng kiểm tra lại.'); return;
  }
  if (seats.length > maxCap) {
    e.preventDefault(); showToast(`Số ghế (${seats.length}) vượt sức chứa tối đa (${maxCap}).`); return;
  }
});

/* ════════════════════════════════
   INIT — load ghế hiện có ngay khi mở trang
════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {
  /* Đọc capacity từ loại phòng đang chọn sẵn */
  const sel = document.getElementById('type_id');
  const opt = sel.options[sel.selectedIndex];
  maxCap = parseInt(opt?.dataset?.capacity) || parseInt(document.getElementById('formMeta').dataset.capacity) || 0;
  refreshCapBar();

  buildPalette();
  renderLegend();
  loadExistingSeats();
  renderGrid();
  refreshStats();
  refreshCapBar();
});
</script>

@endsection
