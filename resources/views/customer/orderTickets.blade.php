


@extends('layouts.client')

@section('title', 'Đặt ghế')

@push('styles')
<link rel="stylesheet" href="\bootstrapLib\bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="icon" href="/img/page_logo/download-removebg-preview.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/scroll/hideScrollBar.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="stylesheet" href="/public/css/intro.css">

        <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --bg: #0d0f14; --surface: #13161e; --card: #1a1e28;
            --border: rgba(255,255,255,0.07); --border-h: rgba(255,255,255,0.15);
            --text: #e8eaf0; --muted: #6b7280; --accent: #e8c96a;
            --accent-bg: rgba(232,201,106,0.10); --danger: #f87171; --success: #10b981;
        }

        .cw { font-family:'Sora',sans-serif; color:var(--text); padding:2rem 0 5rem; }
        .cw-head { display:flex; align-items:center; gap:.75rem; margin-bottom:2rem; justify-content: space-between; }
        .cw-head h2 { font-size:1.3rem; font-weight:600; letter-spacing:-.01em; margin:0; }
        .cw-head small { font-family:'JetBrains Mono'; font-size:10px; color:var(--muted); }

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

        /* Screen */
        .screen-area { text-align:center; margin:1.5rem 0 1.75rem; }
        .screen-bar {
            display:inline-block; width:55%; height:5px; border-radius:3px;
            background:linear-gradient(90deg,transparent,rgba(232,201,106,.45),transparent);
            position:relative;
        }

        /* Seat Grid — giống create/edit */
        .seat-grid-inner { display:inline-flex; flex-direction:column; align-items:flex-start; }
        
        .seat-row { 
            display:flex; 
            align-items:center; 
            gap:4px; 
            margin-bottom:5px; 
            width:100%;
            max-width:1000px;
            margin: 0 auto 6px;
            padding: 0 10px;
        }
        
        .row-lbl {
            font-family:'JetBrains Mono',monospace; font-size:11px;
            color:var(--muted); width:25px; text-align:center; cursor:default;
        }
        .row-lbl.left { margin-right: 10px; }
        .row-lbl.right { margin-left: 10px; }

        /* Container chứa toàn bộ ghế trong 1 hàng để căn giữa */
        .row-seats-wrapper {
            display: flex;
            justify-content: center;
            gap: 4px;
            flex: 1;
        }

        /* Ghế — 32×32 giống create/edit */
        .seat {
            width:32px; height:32px; border-radius:6px;
            border:1.5px solid transparent;
            display:flex; align-items:center; justify-content:center;
            font-family:'JetBrains Mono',monospace; font-size:9px; font-weight:500;
            transition:transform .1s, box-shadow .1s;
            cursor:pointer;
        }
        .seat:hover { transform:scale(1.12); z-index:2; border-color:var(--accent); }

        /* Seat States */
        .seat.empty {
            background:rgba(255,255,255,0.025);
            border-color:rgba(255,255,255,0.05);
            cursor:default;
            pointer-events:none;
        }

        .seat.available:hover { filter: brightness(1.35); transform: scale(1.12); }

        .seat.selected {
            box-shadow: 0 0 14px rgba(232, 201, 106, 0.5);
        }

        .seat.booked-other {
            /* background: rgba(248,113,113,0.15) !important; */
            background-image: url('/img/logo/favicon.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-color: rgba(248,113,113,0.5) !important;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .seat.booked-other::after {
            /* content:'✕'; */
            background-repeat: no-repeat;
            position:absolute;
            font-size:13px;
            font-weight:bold;
            color:rgba(248,113,113,0.8);
        }

        /* Lối đi */
        .seat.is-aisle { 
            background:transparent !important; 
            border:1px dashed rgba(232,201,106,0.2) !important; 
            color:transparent; 
        }
        .seat.is-aisle::after { content:'↕'; color:rgba(232,201,106,0.2); font-size:12px; }

        /* Legend */
        .legend {
            display:flex; flex-wrap:wrap; gap:14px;
            padding-top:1rem; border-top:1px solid var(--border); margin-top:1rem;
        }
        .legend-item { display:flex; align-items:center; gap:6px; font-size:11px; color:var(--muted); }
        .legend-dot { width:12px; height:12px; border-radius:3px; }

        /* Sidebar items - giống create/edit */
        .seat-type-item { display:flex; align-items:center; gap:10px; padding:8px 0; font-size:13px; }
        .seat-type-color { width:24px; height:24px; border-radius:4px; flex-shrink:0; border:1px solid rgba(255,255,255,0.1); }
        
        .selected-seats-box { 
            background:var(--surface); border:1px solid var(--border); 
            border-radius:10px; padding:1.25rem; max-height:200px; overflow-y:auto; 
        }
        .seat-badge {
            display:inline-block; background:var(--accent-bg);
            border:1px solid rgba(232,201,106,.3); color:var(--accent);
            padding:6px 10px; border-radius:5px; margin:4px 4px 4px 0;
            font-size:11px; font-family:'JetBrains Mono',monospace; font-weight:600;
        }
        .total-amount { font-family:'JetBrains Mono',monospace; font-size:20px; font-weight:700; color:var(--accent); }
        
        .btn-checkout {
            background:linear-gradient(135deg, var(--success), #059669);
            color:white; border:none; padding:12px 20px; border-radius:8px;
            font-weight:600; font-size:14px; cursor:pointer; transition:all .2s;
            text-transform:uppercase; letter-spacing:.05em;
        }
        .btn-checkout:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 16px rgba(16,185,129,.3); }
        .btn-checkout:disabled { opacity:.5; cursor:not-allowed; }
        
        .btn-back {
            background:var(--surface); border:1px solid var(--border); color:var(--text);
            padding:10px 20px; border-radius:8px; font-size:13px; cursor:pointer;
            transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;
        }
        .btn-back:hover { border-color:var(--border-h); background:rgba(255,255,255,.05); }

        .payment-options { display:flex; gap:8px; flex-direction:column; }
        .payment-option {
            display:flex; align-items:center; gap:8px; cursor:pointer; padding:8px;
            border-radius:6px; border:1px solid var(--border); transition:.2s;
        }
        .payment-option:hover { border-color:var(--border-h); }
        .payment-option input { cursor:pointer; }

        .modal-content { background:var(--card); border:1px solid var(--border); color:var(--text); }
        .modal-header, .modal-footer { border-color:var(--border); background:var(--surface); }
        .spinner-border { border-color:rgba(232,201,106,.2); border-right-color:var(--accent); }

        /* Info grid */
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
        .info-item { display: flex; flex-direction: column; gap: 4px; }
        .info-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        .info-value { font-size: 14px; font-weight: 500; color: var(--text); }
    </style>
@endpush

@section('content')
<body style="background-color: black">
<div>
 <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="reverb-key" content="{{ config('reverb.app_key') }}">
    <meta name="reverb-host" content="{{ config('reverb.host') }}">
    <meta name="reverb-port" content="{{ config('reverb.port') }}">
    <meta name="reverb-scheme" content="{{ config('reverb.scheme') }}">

    <div class="cw">
        <div class="container-fluid px-4">
            <div class="cw-head mt-4">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-film"></i> {{ $schedule->movie->movie_name }}
                    </h2>
                    <small>
                        <i class="fas fa-clock"></i> {{ $schedule->start_time->format('H:i') }} 
                        <span style="margin: 0 8px;">•</span>
                        <i class="fas fa-door-open"></i> {{ $schedule->room->room_name }}
                        <span style="margin: 0 8px;">•</span>
                        <i class="fas fa-calendar"></i> {{ $schedule->start_time->format('d/m/Y') }}
                    </small>
                </div>
                <a href="javascript:history.back()" class="btn-back mt-5">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Quay lại
                </a>
            </div>

 <!-- BỌC TẤT CẢ VÀO 1 ROW -->
            <div class="row g-4">
                
{{-- 01 THÔNG TIN SUẤT CHIẾU (POSTER PHIM) --}}
                <div class="col-lg-4">
                    <div class="cw-card h-100 d-flex flex-column">
                        <div class="cw-section-label">01 — Phim đang chọn</div>
                        
                        <!-- Wrapper chứa ảnh để bo góc và đổ bóng -->
                        <div class="poster-wrapper flex-grow-1 w-100 d-flex justify-content-center" style="border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.4);">
                            <img src="{{asset('storage/img/movie_poster/' . $schedule->movie->poster)}}" 
                                 alt="{{ $schedule->movie->movie_name }}" 
                                 class="w-100"
                                 style="object-fit: cover; height: 100%; min-height: 300px;">
                        </div>

                        <!-- Thêm 1 chút thông tin cơ bản dưới ảnh để người dùng không quên họ đang đặt suất nào -->
                        <!-- <div class="mt-3 text-center">
                            <h5 class="mb-1" style="font-weight: 600; font-family: 'Sora', sans-serif; color: var(--text);">
                                {{ $schedule->movie->movie_name }}
                            </h5>
                            <small style="color: var(--muted); font-family: 'JetBrains Mono', monospace; font-size: 11px;">
                                <i class="fas fa-door-open"></i> {{ $schedule->room->room_name }} 
                                <span class="mx-2">•</span> 
                                <i class="fas fa-clock"></i> {{ $schedule->start_time->format('H:i') }} - {{ $schedule->start_time->format('d/m') }}
                            </small>
                        </div> -->
                    </div>
                </div>

                {{-- 02 SƠ ĐỒ GHẾ --}}
                <div class="col-lg-4">
                    <div class="cw-card h-100">
                        <div class="cw-section-label">02 — Sơ đồ vị trí ghế</div>

                        <div class="screen-area" style="margin-top: 0.5rem;">
                            <div class="screen-bar" style="width: 80%;"></div>
                            <div style="font-size:10px; color:var(--muted); margin-top:10px">MÀN HÌNH</div>
                        </div>

                        <!-- Thêm overflow-x: auto để cuộn ngang nếu sơ đồ quá to -->
                        <div class="d-flex justify-content-center" style="overflow-x: auto; padding-bottom: 10px;">
                            <div class="seat-grid-inner" id="gridContainer">
                                <!-- Rendered by JS -->
                            </div>
                        </div>

                        <div class="legend" id="legendContainer">
                            <!-- Rendered by JS -->
                        </div>
                    </div>
                </div>

                {{-- 03 CHỌN GHẾ & THANH TOÁN --}}
                <div class="col-lg-4">
                    <div class="cw-card h-100 d-flex flex-column">
                        <div class="cw-section-label">03 — Chọn ghế & Thanh toán</div>
                        
                        <div class="d-flex flex-column gap-4 flex-grow-1">
                            <!-- Loại ghế -->
                            <div>
                                <div class="cw-section-label" style="margin-bottom:1rem; font-size: 9px;">Loại ghế</div>
                                <div id="seatTypesContainer">
                                    <!-- Rendered by JS -->
                                </div>
                            </div>
                            
                            <!-- Ghế đã chọn -->
                            <div>
                                <div class="cw-section-label" style="margin-bottom:1rem; font-size: 9px;">Ghế đã chọn</div>
                                <div class="selected-seats-box" id="selectedSeats">
                                    <small style="color:var(--muted);">Chưa chọn ghế nào</small>
                                </div>
                            </div>
                            
                            <!-- Thanh toán (Đẩy xuống dưới cùng) -->
                            <div class="mt-auto pt-3" style="border-top: 1px solid var(--border);">
                                <div class="cw-section-label" style="margin-bottom:1rem; font-size: 9px;">Thanh toán</div>
                                
                                <div class="payment-options mb-3">
                                    <label class="payment-option">
                                        <input type="radio" name="paymentMethod" value="transfer" checked>
                                        <span>💳 PayOs</span>
                                    </label>
                                </div>
                                
                                <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:1rem;">
                                    <span style="font-size:12px; color:var(--muted); text-transform:uppercase;">Tổng cộng</span>
                                    <div class="total-amount" id="totalAmount">0</div>
                                </div>
                                
                                <button class="btn-checkout" id="checkoutBtn" onclick="processPayment()" disabled style="width:100%;">
                                    <i class="fas fa-check-circle" style="margin-right:6px;"></i>Xác nhận đặt vé
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- KẾT THÚC ROW -->
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center" style="padding:2rem;">
                    <div class="spinner-border mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="margin:0;">Đang xử lý...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PayOs Payment Modal -->
    <div class="modal fade" id="payosModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode"></i> Thanh toán PayOs
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:2rem;">
                    <div id="payosQRContainer" class="text-center">
                        <div class="spinner-border mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Đang tải mã QR...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <p style="margin:0; font-size:12px; color:var(--muted);">
                        <i class="fas fa-info-circle"></i> Quét mã QR bằng ứng dụng ngân hàng. Hệ thống sẽ tự động cập nhật khi thanh toán hoàn tất.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header" style="background: linear-gradient(135deg, var(--success), #059669);">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> Đặt vé thành công
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    <div id="ticketInfo"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>


</div>
</body>
   @endsection 
    
    


@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>

    <script>
        const currentCustomerId = {{ $currentCustomerId ?? 'null' }};
        const scheduleId = {{ $schedule->id }};
        let selectedSeats = new Map();
        let allSeats = new Map();
        let seatTypes = new Map();
        let echo = null;
        let paymentWindow = null; // Thêm dòng này để lưu tham chiếu tab thanh toán

        document.addEventListener('DOMContentLoaded', function() {
            console.clear();
            console.log('%c🎬 TICKET BOOKING DEBUG PANEL', 'color: #e8c96a; font-size: 16px; font-weight: bold;');
            console.log('%c=====================================', 'color: #e8c96a;');
            console.log('📍 Current Customer ID:', currentCustomerId);
            console.log('📅 Schedule ID:', scheduleId);
            console.log('%c=====================================', 'color: #e8c96a;');
            
            initializeEcho();
            loadSeats();
            subscribeToRealtimeUpdates();
        });

        function initializeEcho() {
            try {
                // echo = new window.Echo({
                //     broadcaster: 'pusher',
                //     key: 'tdtql3arrpu66stfqlru',
                //     wsHost: 'localhost',
                //     wsPort: 8080,
                //     wssPort: 8080,
                //     cluster: 'mt1',
                //     forceTLS: false,
                //     encrypted: false,
                //     enabledTransports: ['ws'],
                // });
                echo = new window.Echo({
                    broadcaster: 'pusher',
                    key: '{{ config("reverb.apps.apps.0.key") }}',
                    wsHost: '{{ config("reverb.apps.apps.0.options.host") ?? "localhost" }}',
                    wsPort: {{ config("reverb.servers.reverb.port", 8080) }},
                    wssPort: {{ config("reverb.servers.reverb.port", 8080) }},
                    cluster: 'mt1',
                    forceTLS: false,
                    encrypted: false,
                    enabledTransports: ['ws'],
                });

                console.log('✓ Echo initialized');
                console.log('connector:', echo.connector);
                console.log('connector type:', echo.options.broadcaster);

            } catch (error) {
                console.error('✗ Echo initialization error:', error);
            }
        }

        function loadSeats() {
            console.log('📍 DEBUG: currentCustomerId =', currentCustomerId);
            console.log('📍 DEBUG: scheduleId =', scheduleId);
            
            fetch(`/api/ticket-booking/schedule-seats/${scheduleId}`, {
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(data => {
                renderSeats(data.seats);
                renderSeatTypes(data.seats);
                updateLegend(data.seats);
            })
            .catch(error => {
                console.error('✗ Error loading seats:', error);
                alert('Lỗi tải danh sách ghế: ' + error.message);
            });
        }

        function renderSeats(seats) {
            const container = document.getElementById('gridContainer');
            container.innerHTML = '';
            selectedSeats.clear();

            // Group seats by row
            const rowMap = new Map();
            seats.forEach(seat => {
                if (!rowMap.has(seat.row)) rowMap.set(seat.row, []);
                rowMap.get(seat.row).push(seat);
                allSeats.set(seat.id, seat);
            });

            const sortedRows = Array.from(rowMap.keys()).sort((a, b) => a - b);

            // Tìm các cột lối đi (type_id === null)
            const aisleColumns = new Set();
            seats.forEach(s => { if (!s.type_id) aisleColumns.add(s.column); });

            // Số cột thực (không tính aisle)
            const allColumns = new Set();
            seats.forEach(s => { if (s.type_id) allColumns.add(s.column); });
            const sortedCols = Array.from(allColumns).sort((a, b) => a - b);

            // ── Seat rows ──
            sortedRows.forEach(rowNum => {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';
                rowDiv.dataset.r = rowNum;

                // Row label bên trái
                const lblLeft = document.createElement('div');
                lblLeft.className = 'row-lbl left';
                lblLeft.textContent = String.fromCharCode(64 + rowNum);
                rowDiv.appendChild(lblLeft);

                // Wrapper chứa cụm ghế (để căn giữa)
                const seatsWrapper = document.createElement('div');
                seatsWrapper.className = 'row-seats-wrapper';

                const seatsInRow = rowMap.get(rowNum).sort((a, b) => a.column - b.column);
let seatNumber = 0;
                seatsInRow.forEach((seat, idx) => { 
                    // Lối đi
                    if (!seat.type_id) {
                        const aisle = document.createElement('div');
                        aisle.className = 'seat is-aisle';
                        seatsWrapper.appendChild(aisle);
                        return;
                    }

                    // ── Couple seat: chèn gap trước mỗi ghế chẵn (đầu cặp mới) ──
                    else if (isCoupleType(seat.type_id) && seat.column % 2 === 1 && idx > 0) {
                        // Tìm seat trước (không phải aisle) để kiểm tra có phải cặp kế tiếp không
                        const prevSeat = seatsInRow[idx - 1];
                        if (prevSeat && prevSeat.type_id && isCoupleType(prevSeat.type_id)) {
                            const gap = document.createElement('div');
                            gap.style.cssText = 'width:8px; flex-shrink:0;';
                            seatsWrapper.appendChild(gap);
                        }
                    }
                    else{
                        seatNumber++;
                    }
                    // Ghế thường
                    const seatEl = document.createElement('div');
                    seatEl.className = 'seat';
                    seatEl.id = `seat-${seat.id}`;

                    const label = String.fromCharCode(64 + seat.row) + (!isCoupleType(seat.type_id) ? `${seatNumber}` : seat.column);
                    seatEl.textContent = label;
                    seatEl.title = `${label} · ${seat.type_name} · ${formatCurrency(seat.price)}`;
                    seat.label = label; // Gán label cho đối tượng ghế để dễ truy cập sau này
                    const color = seat.color || '#6b7280';

                    // Booking status
                    const status = seat.status ?? 0;
                    const isOccupied = status === 2 || (status === 1 && seat.customer_id !== currentCustomerId);
                    const isMine     = status === 1 && seat.customer_id === currentCustomerId;

                    if (isOccupied) {
                        seatEl.classList.add('booked-other');
                        seatEl.textContent = '';
                        seatEl.title = `${label} · Đã đặt`;
                        seatEl.style.borderColor = 'rgba(248,113,113,0.5)';
                        seatEl.style.backgroundImage = 'url("/img/logo/favicon.png")';
                        seatEl.style.backgroundSize = 'cover';
                        seatEl.style.backgroundPosition = 'center';
                        seatEl.style.backgroundRepeat = 'no-repeat';
                    } else {
                        seatEl.classList.add('available');
                        seatEl.style.background  = color + '28';
                        seatEl.style.borderColor = color;
                        seatEl.style.color       = color;
                        seatEl.onclick = () => toggleSeat(seat);

                        if (isMine) {
                            seatEl.classList.add('selected');
                            seatEl.style.background  = 'var(--accent)';
                            seatEl.style.borderColor = 'var(--accent)';
                            seatEl.style.color       = '#0d0f14';
                            selectedSeats.set(seat.id, seat);
                        }
                    }

                    seatsWrapper.appendChild(seatEl);
                });

                rowDiv.appendChild(seatsWrapper);

                // Row label bên phải (đối xứng)
                const lblRight = document.createElement('div');
                lblRight.className = 'row-lbl right';
                lblRight.textContent = String.fromCharCode(64 + rowNum);
                rowDiv.appendChild(lblRight);

                container.appendChild(rowDiv);
            });

            updateUI();
        }

        function renderSeatTypes(seats) {
            const typeMap = new Map();
            
            seats.forEach(seat => {
                if (!seat.type_id || typeMap.has(seat.type_id)) return;
                typeMap.set(seat.type_id, {
                    id: seat.type_id,
                    name: seat.type_name,
                    price: seat.price,
                    color: seat.color,
                });
                seatTypes.set(seat.type_id, {
                    name: seat.type_name,
                    price: seat.price,
                    color: seat.color,
                    is_couple: seat.is_couple ?? false, 
                });
            });

            const container = document.getElementById('seatTypesContainer');
            container.innerHTML = '';

            Array.from(typeMap.values()).forEach(type => {
                const item = document.createElement('div');
                item.className = 'seat-type-item';
                item.innerHTML = `
                    <div class="seat-type-color" style="background-color: ${type.color}; border-color: ${type.color}60;"></div>
                    <span>${type.name} - <strong>${formatCurrency(type.price)}</strong></span>
                `;
                container.appendChild(item);
            });
        }

        function updateLegend(seats) {
            const typeSet = new Set();
            seats.forEach(s => {
                if (s.type_id && !typeSet.has(s.type_id)) typeSet.add(s.type_id);
            });

            const legend = document.getElementById('legendContainer');
            legend.innerHTML = '';

            Array.from(typeSet).forEach(typeId => {
                const seat = seats.find(s => s.type_id === typeId);
                if (!seat) return;

                const item = document.createElement('div');
                item.className = 'legend-item';
                item.innerHTML = `
                    <div class="legend-dot" style="background: ${seat.color};"></div>
                    <span>${seat.type_name}</span>
                `;
                legend.appendChild(item);
            });

            const selected = document.createElement('div');
            selected.className = 'legend-item';
            selected.innerHTML = `
                <div class="legend-dot" style="background: var(--accent);"></div>
                <span>Đang chọn</span>
            `;
            legend.appendChild(selected);

            const bookedOther = document.createElement('div');
            bookedOther.className = 'legend-item';
            bookedOther.innerHTML = `
                <div class="legend-dot" style="
                    background-image: url('/img/logo/favicon.png');
                    background-size: 180%;
                    background-position: center;
                    background-repeat: no-repeat;
                "></div>
                <span>Đã đặt</span>
            `;
                // <div class="legend-dot" style="background: rgba(248,113,113,0.5);"></div>
            legend.appendChild(bookedOther);
        }

        // function toggleSeat(seat) {
        //     const seatEl = document.getElementById(`seat-${seat.id}`);
        //     const color  = seat.color || '#6b7280';
        //     const label  = String.fromCharCode(64 + seat.row) + seat.column;

        //     if (selectedSeats.has(seat.id)) {
        //         selectedSeats.delete(seat.id);
        //         seatEl.classList.remove('selected');
        //         seatEl.style.background  = color + '28';
        //         seatEl.style.borderColor = color;
        //         seatEl.style.color       = color;
        //         seatEl.textContent       = label;
        //         updateSeatStatus(seat.id, 0);
        //     } else {
        //         selectedSeats.set(seat.id, seat);
        //         seatEl.classList.add('selected');
        //         seatEl.style.background  = 'var(--accent)';
        //         seatEl.style.borderColor = 'var(--accent)';
        //         seatEl.style.color       = '#0d0f14';
        //         seatEl.textContent       = label;
        //         updateSeatStatus(seat.id, 1);
        //     }

        //     updateUI();
        // }

        function toggleSeat(seat) {
            const isCouple = isCoupleType(seat.type_id);
            const partner  = isCouple ? getCouplePartner(seat) : null;

            const willSelect = !selectedSeats.has(seat.id);
            // Toggle ghế được click
            _toggleSingleSeat(seat);

            // Nếu là couple và partner available → toggle luôn
            if (partner) {
                const partnerOccupied = partner.status === 2 ||
                    (partner.status === 1 && partner.customer_id !== currentCustomerId);

                if (!partnerOccupied) {
                    // Đồng bộ partner theo hướng đã xác định
                    const partnerAlreadySelected = selectedSeats.has(partner.id);
                    if (willSelect !== partnerAlreadySelected) {
                        _toggleSingleSeat(partner);
                    }
                }
            }

            updateUI();
        }

        function _toggleSingleSeat(seat) {
            const seatEl = document.getElementById(`seat-${seat.id}`);
            const color  = seat.color || '#6b7280';
            // const label  = String.fromCharCode(64 + seat.row) + seat.column;
const label  = seat.label || (String.fromCharCode(64 + seat.row) + seat.column);
            if (selectedSeats.has(seat.id)) {
                selectedSeats.delete(seat.id);
                seatEl.classList.remove('selected');
                seatEl.style.background  = color + '28';
                seatEl.style.borderColor = color;
                seatEl.style.color       = color;
                seatEl.textContent       = label;
                updateSeatStatus(seat.id, 0);
            } else {
                selectedSeats.set(seat.id, seat);
                seatEl.classList.add('selected');
                seatEl.style.background  = 'var(--accent)';
                seatEl.style.borderColor = 'var(--accent)';
                seatEl.style.color       = '#0d0f14';
                seatEl.textContent       = label;
                updateSeatStatus(seat.id, 1);
            }
        }

        function updateSeatStatus(seatId, status) {
            fetch('/api/ticket-booking/update-seat-status', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    schedule_id: scheduleId,
                    seat_id: seatId,
                    status: status,
                })
            })
            .catch(error => console.error('✗ Error updating seat:', error));
        }

        function getRowLabel(n) {
            return String.fromCharCode(64 + n);
        }

        function updateUI() {
            const list = document.getElementById('selectedSeats');
            
            console.log(`📋 SELECTED SEATS (${selectedSeats.size}):`, 
                // Array.from(selectedSeats.values()).map(s => `${getRowLabel(s.row)}${s.column}`).join(', ') || 'None'
                Array.from(selectedSeats.values()).map(s => s.label).join(', ') || 'None'

            );
            
            if (selectedSeats.size === 0) {
                list.innerHTML = '<small style="color:var(--muted);">Chưa chọn ghế nào</small>';
            } else {
                // const badges = Array.from(selectedSeats.values())
                //     .sort((a, b) => {
                //         const aPos = getRowLabel(a.row) + String(a.column).padStart(2, '0');
                //         const bPos = getRowLabel(b.row) + String(b.column).padStart(2, '0');
                //         return aPos.localeCompare(bPos);
                //     })
                //     .map(s => `<span class="seat-badge">${getRowLabel(s.row)}${s.column}</span>`)
                //     .join('');
                // list.innerHTML = badges;

                                const badges = Array.from(selectedSeats.values())
                    .sort((a, b) => a.label.localeCompare(b.label))
                    .map(s => `<span class="seat-badge">${s.label}</span>`)
                    .join('');
                list.innerHTML = badges;
            }

            const total = Array.from(selectedSeats.values())
                .reduce((sum, seat) => sum + Number(seat.price), 0);
            console.log(`💰 Total: ${total}`);
            document.getElementById('totalAmount').textContent = formatCurrency(total);
            document.getElementById('checkoutBtn').disabled = selectedSeats.size === 0;
        }

        function subscribeToRealtimeUpdates() {
            if (!echo) {
                console.log('⚠ Echo not available, realtime disabled');
                return;
            }

            try {
                echo.channel(`schedule.${scheduleId}`)
                    .listen('.seat.status.changed', (event) => {
                        console.log('  REALTIME EVENT received:', event);
                        handleSeatStatusChange(event.seat_id, event.status, event.customer_id);
                    });
                console.log(`✓ Subscribed to realtime channel: schedule.${scheduleId}`);
            } catch (error) {
                console.warn('⚠ Could not subscribe to realtime:', error);
            }
        }

        function handleSeatStatusChange(seatId, newStatus, customerId) {
            const seatEl = document.getElementById(`seat-${seatId}`);
            if (!seatEl) return;

            const seat = allSeats.get(seatId);
            if (!seat) return;

            seat.status   = newStatus;
            seat.customer_id = customerId;
            allSeats.set(seatId, seat);

            const color = seat.color || '#6b7280';
            // const label = String.fromCharCode(64 + seat.row) + seat.column;
            const label = seat.label || (String.fromCharCode(64 + seat.row) + seat.column);

            const isOccupied = newStatus === 2 || (newStatus === 1 && customerId !== currentCustomerId);
            const isMine     = newStatus === 1 && customerId === currentCustomerId;

            seatEl.classList.remove('available', 'selected', 'booked-other', 'empty');
            seatEl.textContent = label;

            if (newStatus === 0) {
                seatEl.classList.add('available');
                seatEl.style.background  = color + '28';
                seatEl.style.borderColor = color;
                seatEl.style.color       = color;
                seatEl.onclick = () => toggleSeat(seat);
                seatEl.style.cursor = 'pointer';
                if (selectedSeats.has(seatId)) {
                    selectedSeats.delete(seatId);
                    updateUI();
                }
            } else if (isOccupied) {
                seatEl.classList.add('booked-other');
                seatEl.textContent = '';
                // seatEl.style.background  = 'rgba(248,113,113,0.15)';
                seatEl.style.backgroundImage = 'url("/img/logo/favicon.png")'
                seatEl.style.backgroundSize = 'cover';
                seatEl.style.backgroundPosition = 'center';
                seatEl.style.backgroundRepeat = 'no-repeat';
                seatEl.style.borderColor = 'rgba(248,113,113,0.5)';
                seatEl.style.color       = '';
                seatEl.onclick = null;
                seatEl.style.cursor = 'not-allowed';
                if (selectedSeats.has(seatId)) {
                    selectedSeats.delete(seatId);
                    updateUI();
                }
            } else if (isMine) {
                seatEl.classList.add('available', 'selected');
                seatEl.style.background  = 'var(--accent)';
                seatEl.style.borderColor = 'var(--accent)';
                seatEl.style.color       = '#0d0f14';
                seatEl.onclick = () => toggleSeat(seat);
            }
        }

        function processPayment() {
            if (selectedSeats.size === 0) {
                alert('Vui lòng chọn ít nhất một ghế');
                return;
            }

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const seatIds = Array.from(selectedSeats.keys());
            // const seatNames = Array.from(selectedSeats.values()).map(s => `${getRowLabel(s.row)}${s.column}`).join(', ');
            const seatNames = Array.from(selectedSeats.values()).map(s => s.label).join(', ');

            const totalPrice = Array.from(selectedSeats.values()).reduce((sum, s) => sum + s.price, 0);

            console.log(`💳 PAYMENT PROCESS:`, {
                method: paymentMethod,
                seats: seatNames,
                seatIds: seatIds,
                total: totalPrice,
                customerId: currentCustomerId
            });

            showLoading();

            if (paymentMethod === 'transfer') {
            //     createTicketCash(seatIds);
            // } else {
                initPaymentPayOs(seatIds);
            }
        }

        // function createTicketCash(seatIds) {
        //     console.log('💵 Creating cash ticket with seats:', seatIds);
            
        //     fetch('/api/ticket-booking/create-ticket-cash', {
        //         method: 'POST',
        //         credentials: 'include',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        //         },
        //         body: JSON.stringify({
        //             schedule_id: scheduleId,
        //             seat_ids: seatIds,
        //         })
        //     })
        //     .then(response => {
        //         if (!response.ok) {
        //             return response.json().then(data => {
        //                 throw new Error(data.message || 'Lỗi ' + response.status);
        //             });
        //         }
        //         return response.json();
        //     })
        //     .then(data => {
        //         console.log('✅ Ticket created:', data.ticket);
        //         hideLoading();
        //         if (data.ticket) {
        //             getTicketInfo(data.ticket.code);
        //         }
        //     })
        //     .catch(error => {
        //         console.error('❌ Error:', error);
        //         hideLoading();
        //         alert('Lỗi: ' + error.message);
        //     });
        // }

        function initPaymentPayOs(seatIds) {
            console.log('🔗 Initiating PayOs payment with seats:', seatIds);
            
            fetch('/api/ticket-booking/init-payment-payos', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    schedule_id: scheduleId,
                    seat_ids: seatIds,
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Lỗi ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ PayOs initialized:', data);
                hideLoading();
                if (data.checkout_url) {
                    showPayOsModal(data);
                } else {
                    alert('Lỗi: ' + (data.message || data.error));
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
                hideLoading();
                alert('Lỗi khởi tạo thanh toán: ' + error.message);
            });
        }
        

        function showPayOsModal(paymentData) {
            const modal = new bootstrap.Modal(document.getElementById('payosModal'));
            const qrContainer = document.getElementById('payosQRContainer');
            const checkoutUrl = paymentData.checkout_url;

            if (checkoutUrl) {
// MỚI
qrContainer.innerHTML = `
    <div style="text-align:center;">
        <p style="color:var(--text); margin-bottom:1.5rem;">Quét mã QR bằng ứng dụng ngân hàng để thanh toán</p>
        <button onclick="openPaymentTab('${checkoutUrl}')" class="btn-checkout" style="display:inline-block; border:none;">
            <i class="fas fa-external-link-alt"></i> Thanh toán trực tuyến
        </button>
    </div>
`;
            }

            modal.show();
            sessionStorage.setItem('currentTicketCode', paymentData.ticket_code);

            // if (echo) {
            //     echo.private(`ticket.${paymentData.ticket_code}`)
            //         .listen('PaymentCompleted', (event) => {
            //             console.log('✓ Payment completed');
            //             bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
            //             getTicketInfo(paymentData.ticket_code);
            //         });
            // }
            if (echo) {
                echo.channel(`ticket.${paymentData.ticket_code}`)
                    .listen('.payment.completed', (event) => {
                        console.log('✓ Payment completed');

                        if (paymentWindow && !paymentWindow.closed) {
                paymentWindow.close();
            }
                        bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                        getTicketInfo(paymentData.ticket_code);
                    });
            }

            pollPaymentStatus(paymentData.ticket_code);
        }

        function pollPaymentStatus(ticketCode) {
            const pollInterval = setInterval(() => {
                fetch(`/api/ticket-booking/check-payment-status/${ticketCode}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'completed') {
                clearInterval(pollInterval);
                
                // THÊM DÒNG NÀY: Tự động đóng tab Hình 3
                if (paymentWindow && !paymentWindow.closed) {
                    paymentWindow.close();
                }

                bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                getTicketInfo(ticketCode);
            } else if (data.status === 'failed') {
                        clearInterval(pollInterval);
                        bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                        alert('Thanh toán thất bại. Vui lòng thử lại.');
                    } else if (data.status === 'cancelled') {
                        clearInterval(pollInterval);
                        bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                        alert('Thanh toán đã bị hủy. Vui lòng thử lại.');
                                        } else if (data.status === 'expired') {
                        clearInterval(pollInterval);
                        bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                        alert('Phiên thanh toán đã hết hạn. Vui lòng thử lại.');
                    } else {
                        console.log('⏳ Payment status:', data.status);
}
                })
                .catch(error => console.error('✗ Poll error:', error));
            }, 3000);

            setTimeout(() => clearInterval(pollInterval), 15 * 60 * 1000);
        }

        function getTicketInfo(ticketCode) {
            fetch(`/api/ticket-booking/ticket/${ticketCode}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Lỗi ' + response.status);
                return response.json();
            })
            .then(data => {
                                data.bookings.forEach(b => {
                    const selected = selectedSeats.get(b.seat.id);

                    if (selected && selected.label) {
                        b.seat.label = selected.label;
                    } else {
                        // fallback (trường hợp reload / realtime)
                        b.seat.label = String.fromCharCode(64 + b.seat.row) + b.seat.column;
                    }
                });
                showTicketModal(data);
                selectedSeats.clear();
                // loadSeats();
                setTimeout(loadSeats, 200);

            })
            .catch(error => {
                console.error('✗ Error:', error);
                alert('Lỗi: ' + error.message);
            });
        }

        function showTicketModal(data) {
            const ticket = data.ticket;
            const bookings = data.bookings;

            let seatsHtml = bookings.map(b =>
                // `<span class="seat-badge">${getRowLabel(b.seat.row)}${b.seat.column}</span>`
                `<span class="seat-badge">${b.seat.label}</span>`

            ).join('');

            const html = `
                <div style="color:var(--text);">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                        <div>
                            <small style="color:var(--muted); text-transform:uppercase; font-size:10px;">Mã vé</small>
                            <div style="font-family:'JetBrains Mono'; font-weight:600; font-size:14px;">${ticket.code}</div>
                        </div>
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <small style="color:var(--muted); text-transform:uppercase; font-size:10px; display:block; margin-bottom:0.75rem;">Ghế</small>
                        ${seatsHtml}
                    </div>
                    <div style="background:rgba(16, 185, 129, 0.1); border:1px solid rgba(16, 185, 129, 0.3); border-radius:8px; padding:1rem;">
                        <small style="color:var(--muted); text-transform:uppercase; font-size:10px;">Tổng cộng</small>
                        <div style="font-family:'JetBrains Mono'; font-size:20px; font-weight:700; color:#10b981;">${formatCurrency(ticket.final_price)}</div>
                    </div>
                </div>
            `;

            document.getElementById('ticketInfo').innerHTML = html;
            new bootstrap.Modal(document.getElementById('successModal')).show();
        }

        function printTicket() {
            window.print();
        }

        function showLoading() {
            new bootstrap.Modal(document.getElementById('loadingModal')).show();
        }

        function hideLoading() {
            bootstrap.Modal.getInstance(document.getElementById('loadingModal'))?.hide();
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
        }

        function getCouplePartner(seat) {
            // Ghế couple được ghép theo cặp: (1,2), (3,4), (5,6)...
            // Nếu column lẻ → partner là column+1, nếu chẵn → partner là column-1
            const partnerCol = seat.column % 2 !== 0 ? seat.column + 1 : seat.column - 1;
            return Array.from(allSeats.values()).find(
                s => s.row === seat.row && s.column === partnerCol && s.type_id === seat.type_id
            );
        }

        function isCoupleType(typeId) {
            const type = seatTypes.get(typeId);
            return !!(type && type.is_couple); 
        }

        // @if(session('payment_success_ticket'))
        //     document.addEventListener('DOMContentLoaded', function() {
        //         const successTicketCode = '{{ session('payment_success_ticket') }}';
        //         // Tự động gọi API lấy thông tin vé và hiển thị Success Modal
        //         getTicketInfo(successTicketCode);
        //     });
        // @endif


        // Bỏ chọn tất cả ghế khi người dùng rời khỏi trang hoặc reload
        window.addEventListener('beforeunload', function () {
            if (selectedSeats.size > 0) {
                const seatIds = Array.from(selectedSeats.keys());
                
                seatIds.forEach(seatId => {
                    fetch('/api/ticket-booking/update-seat-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            schedule_id: scheduleId,
                            seat_id: seatId,
                            status: 0 // 0 = Available (Trả lại ghế)
                        }),
                        keepalive: true // Cờ quan trọng: Đảm bảo request vẫn chạy ngầm thành công dù trang đang bị đóng
                    });
                });
            }
        });


        function openPaymentTab(url) {
    // Mở tab và lưu lại đối tượng window để có quyền đóng nó sau này
    paymentWindow = window.open(url, '_blank');
}
    </script>

@endpush

