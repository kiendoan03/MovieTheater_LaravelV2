@extends('layouts.management')
@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

  :root {
    --bg: #0d0f14; --surface: #13161e; --card: #1a1e28;
    --border: rgba(255,255,255,0.07); --border-h: rgba(255,255,255,0.15);
    --text: #e8eaf0; --muted: #6b7280; --accent: #e8c96a;
    --accent-bg: rgba(232,201,106,0.10); --danger: #f87171;
  }

  .cw { font-family:'Sora',sans-serif; color:var(--text); padding:2rem 0 5rem; }
  .cw-head { display:flex; align-items:center; gap:.75rem; margin-bottom:2rem; }
  .cw-head h2 { font-size:1.3rem; font-weight:600; letter-spacing:-.01em; margin:0; }

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
    appearance:none;
  }
  .cw-input:focus, .cw-select:focus { border-color:var(--accent); }
  .cw-input:disabled, .cw-select:disabled { opacity:.4; cursor:not-allowed; }
  .cw-select-wrap { position:relative; }
  .cw-select-wrap::after {
    content:''; position:absolute; right:12px; top:50%; transform:translateY(-50%);
    border:4px solid transparent; border-top-color:var(--muted); pointer-events:none;
  }

  /* Capacity bar */
  .cap-wrap { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:12px 16px; margin-top:.75rem; display:none; }
  .cap-wrap.show { display:block; }
  .cap-badge { font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--accent); background:var(--accent-bg); padding:2px 10px; border-radius:20px; }
  .cap-badge.over { color:#f87171; background:rgba(248,113,113,.12); }
  .cap-bar { height:4px; background:rgba(255,255,255,0.05); border-radius:2px; overflow:hidden; margin-top:8px; }
  .cap-fill { height:100%; background:var(--accent); width:0%; transition:width .4s; }

  /* Grid Control */
  .grid-ctrl { display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:12px; align-items:flex-end; }

  /* Palette */
  .palette-wrap { display:flex; flex-direction:column; gap:10px; margin-bottom:1.25rem; }
  .palette-group { display:flex; flex-wrap:wrap; gap:7px; align-items:center; }
  .tool-btn {
    display:flex; align-items:center; gap:7px; padding:6px 13px; border-radius:8px;
    border:1.5px solid var(--border); background:var(--surface); color:var(--text);
    font-size:12px; cursor:pointer; transition:all .15s;
  }
  .tool-btn:hover { border-color:var(--border-h); }
  .tool-btn.active { border-color:var(--accent); background:var(--accent-bg); }
  .tool-dot { width:11px; height:11px; border-radius:3px; }

  /* Seat Grid */
  .screen-area { text-align:center; margin:1.5rem 0 1.75rem; }
  .screen-bar {
    display:inline-block; width:55%; height:5px; border-radius:3px;
    background:linear-gradient(90deg,transparent,rgba(232,201,106,.45),transparent);
    position:relative;
  }
  .seat-grid-inner { display:inline-flex; flex-direction:column; align-items:flex-start; }
  .seat-row { display:flex; align-items:center; gap:4px; margin-bottom:5px; }
  .row-lbl { font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--muted); width:25px; text-align:center; cursor:default; }
  
  .seat {
    width:32px; height:32px; border-radius:6px; border:1.5px solid transparent;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    font-family:'JetBrains Mono',monospace; font-size:9px; transition:transform .1s;
    background:rgba(255,255,255,0.025); border-color:rgba(255,255,255,0.05);
  }
  .seat:hover { transform:scale(1.12); z-index:2; border-color:var(--accent); }
  .seat.is-aisle { background:transparent !important; border:1px dashed rgba(232,201,106,0.2) !important; color:transparent; }
  .seat.is-aisle::after { content:'↕'; color:rgba(232,201,106,0.2); font-size:12px; }

  /* Stats */
  .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:10px; }
  .stat-box { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:12px 14px; }
  .stat-val { font-size:18px; font-weight:600; font-family:'JetBrains Mono',monospace; }

  .cw-footer { display:flex; gap:10px; justify-content:flex-end; padding-top:1.5rem; border-top:1px solid var(--border); margin-top:1.5rem; }
  .btn-submit { background:var(--accent); border:none; color:#0d0f14; font-weight:600; padding:10px 28px; border-radius:8px; cursor:pointer; }
  .btn-gen { background:var(--surface); border:1px solid var(--border); color:var(--text); padding:9px 20px; border-radius:8px; cursor:pointer; }
  
  .toast {
    position:fixed; bottom:2rem; left:50%; transform:translateX(-50%) translateY(80px);
    background:#1a1e28; border:1px solid var(--danger); color:var(--danger);
    padding:10px 20px; border-radius:10px; z-index:9999; transition:all .3s; opacity:0;
  }
  .toast.show { transform:translateX(-50%) translateY(0); opacity:1; }

  /* Container chứa toàn bộ ghế trong 1 hàng để căn giữa */
  .row-seats-wrapper {
      display: flex;
      justify-content: center; /* Căn giữa cụm ghế */
      gap: 4px;
      flex: 1; /* Chiếm hết khoảng trống giữa 2 nhãn */
  }

  /* Đảm bảo hàng luôn có độ rộng bằng nhau */
  .seat-row {
      display: flex;
      align-items: center;
      width: 100%;
      max-width: 1000px; /* Hoặc độ rộng tối đa bạn muốn */
      margin: 0 auto 6px;
      padding: 0 10px;
  }

  /* Nhãn hàng bên phải */
  .row-lbl.right {
      margin-left: 10px;
  }
  .row-lbl.left {
      margin-right: 10px;
  }
</style>

<div class="cw">
  <div class="container-fluid px-4">
    <div class="cw-head"><h2>Tạo phòng chiếu & Thiết kế sơ đồ</h2></div>

    <form method="POST" action="{{ route('admin.rooms.store') }}" id="createRoomForm">
      @csrf

      {{-- 01 THÔNG TIN --}}
      <div class="cw-card">
        <div class="cw-section-label">01 — Thông tin phòng chiếu</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="cw-label">Tên phòng</label>
            <input type="text" class="cw-input" name="room_name" placeholder="VD: Phòng Gold 01" required>
          </div>
          <div class="col-md-6">
            <label class="cw-label">Loại phòng</label>
            <div class="cw-select-wrap">
              <select class="cw-select" id="type_id" name="type_id" required onchange="handleRoomTypeChange()">
                <option value="">-- Chọn loại phòng --</option>
                @foreach($roomTypes as $rt)
                  <option value="{{ $rt->id }}" data-capacity="{{ $rt->capacity }}">{{ $rt->type }} (Max: {{ $rt->capacity }} ghế)</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="cap-wrap" id="capWrap">
          <div class="d-flex justify-content-between">
            <span style="font-size:11px; color:var(--muted)">Sức chứa thiết kế / tối đa</span>
            <span class="cap-badge" id="capBadge">0 / 0</span>
          </div>
          <div class="cap-bar"><div class="cap-fill" id="capFill"></div></div>
        </div>
      </div>

      {{-- 02 CẤU HÌNH LƯỚI --}}
      <div class="cw-card">
        <div class="cw-section-label">02 — Cấu hình lưới ban đầu</div>
        <div class="grid-ctrl">
          <div>
            <label class="cw-label">Số hàng</label>
            <input type="number" class="cw-input" id="inp_rows" min="1" max="26" value="8" disabled>
          </div>
          <div>
            <label class="cw-label">Số cột (Bắt buộc số chẵn)</label>
            <input type="number" class="cw-input" id="inp_cols" min="1" max="30" value="10" disabled>
          </div>
          <div>
            <label class="cw-label">Ghế mặc định</label>
            <div class="cw-select-wrap">
              <select class="cw-select" id="inp_def" disabled>
                @foreach($seatTypes->where('is_couple', false) as $st)
                  <option value="{{ $st->id }}">{{ $st->type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <button type="button" class="btn-gen" id="btnGenerate" onclick="generateGrid()" disabled>↺ Tạo sơ đồ</button>
        </div>
      </div>

      {{-- 03 SƠ ĐỒ --}}
      <div class="cw-card" id="designArea" style="display:none">
        <div class="cw-section-label">03 — Thiết kế (Click ghế đổi cả hàng, Click lối đi đổi cả cột)</div>
        
        <div class="palette-wrap">
          <div class="palette-group" id="paletteTools"></div>
        </div>

        <div class="screen-area">
          <div class="screen-bar"></div>
          <div style="font-size:10px; color:var(--muted); margin-top:10px">MÀN HÌNH</div>
        </div>

        <div class="d-flex justify-content-center">
          <div class="seat-grid-inner" id="gridContainer"></div>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="cw-section-label mt-5">04 — Thống kê</div>
        <div class="stats-grid" id="statsGrid"></div>

        <div class="cw-footer">
          <a href="{{ route('admin.rooms.index') }}" style="color:var(--muted); text-decoration:none; padding:10px">Hủy bỏ</a>
          <button type="submit" class="btn-submit">Lưu phòng chiếu</button>
        </div>
      </div>

      <input type="hidden" id="seatsInput" name="seats">
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>

<script type="application/json" id="seatTypesData">
[
  @foreach($seatTypes as $st)
  { "id": {{ $st->id }}, "name": @json($st->type), "price": {{ (int)$st->price }}, "color": @json($st->color ?? '#6b7280'), "is_couple": {{ $st->is_couple ? 'true' : 'false' }} }{{ !$loop->last ? ',' : '' }}
  @endforeach
]
</script>

<script>
const SEAT_TYPES = JSON.parse(document.getElementById('seatTypesData').textContent);
const typeMap = Object.fromEntries(SEAT_TYPES.map(t => [t.id, t]));

let activeTool = SEAT_TYPES[0]?.id; 
let gridData = []; 
let nRows = 0, nCols = 0, maxCap = 0;

// Helper: Kiểm tra xem một hàng có phải là hàng ghế đôi không
function isRowCouple(r) {
    if (!gridData[r] || gridData[r].length === 0) return false;
    // Kiểm tra ô đầu tiên không phải lối đi của hàng đó
    const firstSeat = gridData[r].find(cell => cell.type_id !== null);
    if (!firstSeat) return false;
    return typeMap[firstSeat.type_id]?.is_couple === true;
}

function buildPalette() {
    const el = document.getElementById('paletteTools');
    el.innerHTML = '';
    SEAT_TYPES.forEach(t => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `tool-btn ${activeTool === t.id ? 'active' : ''}`;
        btn.innerHTML = `<span class="tool-dot" style="background:${t.color}"></span> ${t.name}`;
        btn.onclick = () => { activeTool = t.id; buildPalette(); };
        el.appendChild(btn);
    });

    const aisleBtn = document.createElement('button');
    aisleBtn.type = 'button';
    aisleBtn.className = `tool-btn ${activeTool === 'aisle' ? 'active' : ''}`;
    aisleBtn.innerHTML = `<span>↔</span> Thêm lối đi (Chèn cột)`;
    aisleBtn.onclick = () => { activeTool = 'aisle'; buildPalette(); };
    el.appendChild(aisleBtn);
}

function handleRoomTypeChange() {
    const sel = document.getElementById('type_id');
    maxCap = parseInt(sel.options[sel.selectedIndex].dataset.capacity) || 0;
    ['inp_rows','inp_cols','inp_def','btnGenerate'].forEach(id => document.getElementById(id).disabled = maxCap === 0);
    document.getElementById('capWrap').classList.toggle('show', maxCap > 0);
}

function generateGrid() {
    const rowsInp = document.getElementById('inp_rows');
    const colsInp = document.getElementById('inp_cols');
    nRows = parseInt(rowsInp.value) || 0;
    nCols = parseInt(colsInp.value) || 0;
    const defId = parseInt(document.getElementById('inp_def').value);

    if (nCols % 2 !== 0) {
        showToast("Số cột bắt buộc phải là số chẵn!");
        return;
    }
    if (nRows * nCols > maxCap) {
        showToast(`Vượt quá sức chứa tối đa (${maxCap} ghế)!`);
        return;
    }

    gridData = [];
    for (let r = 0; r < nRows; r++) {
        gridData[r] = [];
        for (let c = 0; c < nCols; c++) gridData[r][c] = { type_id: defId };
    }
    document.getElementById('designArea').style.display = 'block';
    renderGrid();
}

function handleAction(r, c) {
    if (activeTool === 'aisle') {
        const isCurrentlyAisle = gridData[r][c].type_id === null;

        if (isCurrentlyAisle) {
            // XÓA LỐI ĐI: Duyệt tất cả các hàng, hàng nào có lối đi tại cột c thì xóa
            for (let i = 0; i < nRows; i++) {
                if (gridData[i][c] && gridData[i][c].type_id === null) {
                    gridData[i].splice(c, 1);
                }
            }
        } else {
            // THÊM LỐI ĐI: Chèn vào các hàng THƯỜNG, bỏ qua hàng COUPLE
            for (let i = 0; i < nRows; i++) {
                if (!isRowCouple(i)) {
                    gridData[i].splice(c, 0, { type_id: null });
                }
                // Nếu là hàng Couple, chúng ta KHÔNG splice, hàng này sẽ ngắn hơn 1 cột
            }
        }
    } else {
        const targetType = typeMap[activeTool];

        // VALIDATE GHẾ COUPLE
        if (targetType.is_couple) {
            // 1. Chỉ cho phép ở hàng cuối
            if (r !== nRows - 1) {
                showToast("Ghế Couple chỉ được phép đặt ở hàng cuối cùng của phòng!");
                return;
            }
            // 2. Kiểm tra số lượng ghế (không tính lối đi) phải chẵn
            let seatCount = gridData[r].filter(cell => cell.type_id !== null).length;
            if (seatCount % 2 !== 0) {
                showToast("Hàng cuối phải có số ghế chẵn mới đặt được Couple!");
                return;
            }
            // 3. Nếu hàng cuối đang có lối đi, phải xóa lối đi ở hàng này trước khi biến thành Couple
            // (Theo logic của bạn: hàng couple không bị ảnh hưởng bởi lối đi)
            gridData[r] = gridData[r].filter(cell => cell.type_id !== null);
        }

        // Áp dụng đổi loại ghế cho cả hàng (chỉ đổi những ô không phải lối đi)
        for (let j = 0; j < gridData[r].length; j++) {
            if (gridData[r][j].type_id !== null) {
                gridData[r][j].type_id = activeTool;
            }
        }
    }
    renderGrid();
}

function renderGrid() {
    const container = document.getElementById('gridContainer');
    container.innerHTML = '';

    for (let r = 0; r < nRows; r++) {
        const rowEl = document.createElement('div');
        rowEl.className = 'seat-row';
        
        // 1. Nhãn hàng bên trái
        const lblLeft = document.createElement('div');
        lblLeft.className = 'row-lbl left';
        lblLeft.textContent = String.fromCharCode(65 + r);
        rowEl.appendChild(lblLeft);

        // 2. Wrapper chứa cụm ghế (để căn giữa)
        const seatsWrapper = document.createElement('div');
        seatsWrapper.className = 'row-seats-wrapper';

        for (let c = 0; c < gridData[r].length; c++) {
            const cell = gridData[r][c];
            const seatEl = document.createElement('div');
            seatEl.className = 'seat';
            
            if (cell.type_id === null) {
                seatEl.classList.add('is-aisle');
            } else {
                const t = typeMap[cell.type_id];
                seatEl.style.color = t.color;
                seatEl.style.borderColor = t.color + '44';
                seatEl.style.background = t.color + '15';
                seatEl.textContent = String.fromCharCode(65 + r) + (c + 1);
            }
            seatEl.onclick = () => handleAction(r, c);
            seatsWrapper.appendChild(seatEl);
        }
        rowEl.appendChild(seatsWrapper);

        // 3. Nhãn hàng bên phải (tạo đối xứng)
        const lblRight = document.createElement('div');
        lblRight.className = 'row-lbl right';
        lblRight.textContent = String.fromCharCode(65 + r);
        rowEl.appendChild(lblRight);

        container.appendChild(rowEl);
    }
    syncData();
}

function syncData() {
    const seats = [];
    let currentSeatCount = 0;
    const stats = {};
    SEAT_TYPES.forEach(t => stats[t.id] = 0);

    for (let r = 0; r < nRows; r++) {
        for (let c = 0; c < gridData[r].length; c++) {
            const tid = gridData[r][c].type_id;
            seats.push({ row: r + 1, column: c + 1, type_id: tid });
            if (tid !== null) {
                currentSeatCount++;
                stats[tid]++;
            }
        }
    }
    document.getElementById('seatsInput').value = JSON.stringify(seats);
    
    // UI Updates
    const badge = document.getElementById('capBadge');
    badge.textContent = `${currentSeatCount} / ${maxCap} ghế`;
    badge.classList.toggle('over', currentSeatCount > maxCap);
    const pct = Math.min(100, (currentSeatCount / maxCap) * 100);
    document.getElementById('capFill').style.width = pct + '%';
    document.getElementById('capFill').style.background = currentSeatCount > maxCap ? 'var(--danger)' : 'var(--accent)';

    let html = `<div class="stat-box"><div class="cw-label">Tổng ghế</div><div class="stat-val">${currentSeatCount}</div></div>`;
    SEAT_TYPES.forEach(t => {
        html += `<div class="stat-box"><div class="cw-label" style="color:${t.color}">${t.name}</div><div class="stat-val">${stats[t.id]}</div></div>`;
    });
    document.getElementById('statsGrid').innerHTML = html;
}

function showToast(m) {
    const t = document.getElementById('toast');
    t.textContent = m; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

buildPalette();
</script>

@endsection