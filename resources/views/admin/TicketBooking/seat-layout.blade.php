@extends('layouts.management')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="reverb-key" content="{{ config('reverb.app_key') }}">
    <meta name="reverb-host" content="{{ config('reverb.host') }}">
    <meta name="reverb-port" content="{{ config('reverb.port') }}">
    <meta name="reverb-scheme" content="{{ config('reverb.scheme') }}">

    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4 sticky-top bg-white pt-3 pb-2">
            <div class="col-md-8">
                <h3 class="mb-0">
                    <i class="fas fa-film"></i> {{ $schedule->movie->movie_name }}
                </h3>
                <small class="text-muted">
                    <i class="fas fa-clock"></i> {{ $schedule->start_time->format('H:i') }} -
                    <i class="fas fa-door-open"></i> {{ $schedule->room->room_name }} -
                    <i class="fas fa-calendar-alt"></i> {{ $schedule->start_time->format('d/m/Y') }}
                </small>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-outline-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Sơ đồ ghế -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Screen -->
                        <div class="text-center mb-4">
                            <div class="screen-label">🎬 MÀN HÌNH 🎬</div>
                        </div>

                        <!-- Seat Layout -->
                        <div class="seat-layout-container">
                            <div id="seatLayout" class="seat-layout"></div>
                        </div>

                        <!-- Legend -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">📋 Chú thích</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="seat-legend available"></span> Có sẵn
                                    </div>
                                    <div class="mb-2">
                                        <span class="seat-legend selected"></span> Đang chọn
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="seat-legend booked-mine"></span> Tôi đã đặt
                                    </div>
                                    <div class="mb-2">
                                        <span class="seat-legend booked-other"></span> Người khác đặt
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Thông tin đặt vé -->
            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Thông tin đặt vé</h6>
                    </div>
                    <div class="card-body">
                        <!-- Loại ghế -->
                        <div class="mb-3">
                            <h6>Loại ghế:</h6>
                            <div class="seat-types">
                                @php
                                    $seatTypes = \App\Models\SeatType::all();
                                @endphp
                                @foreach ($seatTypes as $type)
                                    <div class="mb-2 d-flex align-items-center">
                                        <span class="seat-type-indicator"
                                            style="background-color: {{ $type->color ?? '#666' }}; width: 30px; height: 30px; border-radius: 4px; display: inline-block; margin-right: 10px;"></span>
                                        <span>{{ $type->type }} - {{ number_format($type->price, 0, ',', '.') }}
                                            VNĐ</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <!-- Ghế đã chọn -->
                        <div class="mb-3">
                            <h6>Ghế đã chọn:</h6>
                            <div id="selectedSeats" class="selected-seats-list">
                                <small class="text-muted">Chưa chọn ghế nào</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Tổng tiền -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Tổng cộng:</span>
                                <span id="totalAmount" class="h5 text-success fw-bold">0 VNĐ</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Thanh toán -->
                        <div class="mb-3">
                            <h6 class="mb-2">Hình thức thanh toán:</h6>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="paymentMethod" id="paymentCash" value="cash"
                                    checked>
                                <label class="btn btn-outline-primary" for="paymentCash">
                                    <i class="fas fa-money-bill"></i> Tiền mặt
                                </label>

                                <input type="radio" class="btn-check" name="paymentMethod" id="paymentTransfer"
                                    value="transfer">
                                <label class="btn btn-outline-primary" for="paymentTransfer">
                                    <i class="fas fa-qrcode"></i> Chuyển khoản
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" id="checkoutBtn" onclick="processPayment()" disabled>
                                <i class="fas fa-credit-card"></i> Thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Đang xử lý...</p>
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
                        <i class="fas fa-qrcode"></i> Thanh toán qua PayOs
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="payosQRContainer" class="text-center">
                        <div class="spinner-border mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Đang tải mã QR...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-muted small w-100">
                        <i class="fas fa-info-circle"></i> Quét mã QR bằng ứng dụng ngân hàng hoặc ví điện tử.
                        Hệ thống sẽ tự động cập nhật khi thanh toán hoàn tất.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">✅ Đặt vé thành công</h5>
                </div>
                <div class="modal-body">
                    <div id="ticketInfo"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="printTicket()">
                        <i class="fas fa-print"></i> In vé
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-redo"></i> Đặt vé khác
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .screen-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .seat-layout-container {
            max-height: 70vh;
            overflow-y: auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .seat-layout {
            display: grid;
            gap: 8px;
        }

        .seat-row {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .seat-row-label {
            min-width: 30px;
            text-align: right;
            font-weight: bold;
            color: #666;
        }

        .seat {
            width: 35px;
            height: 35px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            transition: all 0.2s;
            border: 2px solid transparent;
            color: white;
        }

        .seat:hover:not(.seat-empty):not(.seat-booked-other) {
            transform: scale(1.1);
        }

        .seat.available {
            background-color: #6c757d;
            cursor: pointer;
        }

        .seat.available:hover {
            background-color: #5a6268;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        }

        .seat.selected {
            background-color: #ffc107;
            border-color: #ff6b6b;
            box-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
        }

        .seat.booked-mine {
            background-color: #28a745;
            cursor: not-allowed;
        }

        .seat.booked-other {
            background-color: #dc3545;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .seat-empty {
            background-color: transparent;
            cursor: default;
        }

        .seat-legend {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 3px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .seat-legend.available {
            background-color: #6c757d;
        }

        .seat-legend.selected {
            background-color: #ffc107;
        }

        .seat-legend.booked-mine {
            background-color: #28a745;
        }

        .seat-legend.booked-other {
            background-color: #dc3545;
        }

        .selected-seats-list {
            max-height: 150px;
            overflow-y: auto;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        .seat-badge {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            margin: 4px 4px 4px 0;
            font-size: 12px;
        }

        .seat-badge .btn-close {
            margin-left: 6px;
            cursor: pointer;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.1/dist/echo.iife.js"></script>
    <script src="https://unpkg.com/@ably-labs/laravel-echo-ably/dist/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>

    <script>
        const currentStaffId = {{ $currentStaffId ?? 'null' }};
        const scheduleId = {{ $schedule->id }};
        let selectedSeats = new Map(); // seat_id => {id, row, column, type_name, price, color}
        let allSeats = new Map(); // seat_id => seat data
        let seatLayoutReady = false;
        let echo;

        // Initialize Echo for realtime
        document.addEventListener('DOMContentLoaded', function() {
            initializeEcho();
            loadSeats();
            subscribeToRealtimeUpdates();
        });

        function initializeEcho() {
            // Laravel Echo configuration
            try {
                if (window.Echo) {
                    echo = new window.Echo({
                        broadcaster: 'reverb',
                        key: document.querySelector('meta[name="reverb-key"]')?.content || 'default',
                        wsHost: document.querySelector('meta[name="reverb-host"]')?.content || '127.0.0.1',
                        wsPort: document.querySelector('meta[name="reverb-port"]')?.content || 8080,
                        wssPort: null,
                        scheme: document.querySelector('meta[name="reverb-scheme"]')?.content || 'http',
                        encrypted: false,
                        enabledTransports: ['ws', 'wss'],
                    });
                    console.log('Echo initialized successfully');
                } else {
                    console.warn('Laravel Echo not loaded. Realtime updates disabled.');
                }
            } catch (error) {
                console.error('Error initializing Echo:', error);
            }
        }

        function loadSeats() {
            fetch(`/api/ticket-booking/schedule-seats/${scheduleId}`, {
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    renderSeatLayout(data.seats);
                    seatLayoutReady = true;
                })
                .catch(error => {
                    console.error('Error loading seats:', error);
                    alert('Lỗi tải danh sách ghế: ' + error.message);
                });
        }

        function renderSeatLayout(seats) {
            // Group seats by row
            const rowMap = new Map();
            seats.forEach(seat => {
                if (!rowMap.has(seat.row)) {
                    rowMap.set(seat.row, []);
                }
                rowMap.get(seat.row).push(seat);
                allSeats.set(seat.id, seat);
            });

            const seatLayout = document.getElementById('seatLayout');
            seatLayout.innerHTML = '';

            // Sort rows A, B, C...
            const sortedRows = Array.from(rowMap.keys()).sort();

            sortedRows.forEach(row => {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';

                const rowLabel = document.createElement('div');
                rowLabel.className = 'seat-row-label';
                rowLabel.textContent = row;
                rowDiv.appendChild(rowLabel);

                const seatsInRow = rowMap.get(row).sort((a, b) => a.column - b.column);

                seatsInRow.forEach(seat => {
                    const seatEl = document.createElement('div');
                    seatEl.className = 'seat';
                    seatEl.id = `seat-${seat.id}`;

                    // Determine seat status
                    if (seat.status === 0) { // Available
                        seatEl.classList.add('available');
                        seatEl.textContent = seat.column;
                        seatEl.onclick = () => toggleSeat(seat);
                    } else if (seat.status === 1) { // Booked
                        if (seat.staff_id === currentStaffId) {
                            seatEl.classList.add('booked-mine');
                            seatEl.textContent = seat.column;
                            seatEl.title = 'Bạn đã đặt ghế này';
                        } else {
                            seatEl.classList.add('booked-other');
                            seatEl.textContent = seat.column;
                            seatEl.title = 'Ghế này đã được đặt';
                        }
                    } else if (seat.status === 2) { // Reserved
                        seatEl.classList.add('booked-other');
                        seatEl.textContent = seat.column;
                        seatEl.title = 'Ghế này đã được đặt';
                    }

                    rowDiv.appendChild(seatEl);
                });

                seatLayout.appendChild(rowDiv);
            });
        }

        function toggleSeat(seat) {
            if (seat.status !== 0) return; // Chỉ chọn được ghế available

            if (selectedSeats.has(seat.id)) {
                selectedSeats.delete(seat.id);
                document.getElementById(`seat-${seat.id}`).classList.remove('selected');
                updateSeatStatus(seat.id, 0);
            } else {
                selectedSeats.set(seat.id, seat);
                document.getElementById(`seat-${seat.id}`).classList.add('selected');
                updateSeatStatus(seat.id, 1);
            }

            updateUI();
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
                .catch(error => console.error('Error updating seat:', error));
        }

        function updateUI() {
            // Update selected seats display
            const list = document.getElementById('selectedSeats');
            if (selectedSeats.size === 0) {
                list.innerHTML = '<small class="text-muted">Chưa chọn ghế nào</small>';
            } else {
                const badges = Array.from(selectedSeats.values())
                    .sort((a, b) => (a.row + a.column).localeCompare(b.row + b.column))
                    .map(seat => `<span class="seat-badge">${seat.row}${seat.column}</span>`)
                    .join('');
                list.innerHTML = badges;
            }

            // Calculate total
            let total = 0;
            selectedSeats.forEach(seat => {
                total += seat.price;
            });

            document.getElementById('totalAmount').textContent = number_format(total) + ' VNĐ';
            document.getElementById('checkoutBtn').disabled = selectedSeats.size === 0;
        }

        function subscribeToRealtimeUpdates() {
            // Subscribe to seat updates via Laravel Echo (optional - polling works as fallback)
            if (echo) {
                try {
                    echo.channel(`schedule.${scheduleId}`)
                        .listen('SeatStatusChanged', (event) => {
                            console.log('Seat status changed:', event);
                            handleSeatStatusChange(event.seat_id, event.status, event.staff_id);
                        });
                    console.log('Subscribed to realtime updates');
                } catch (error) {
                    console.warn('Could not subscribe to realtime updates:', error);
                }
            } else {
                console.log('Echo not available - using polling only');
            }
        }

        function handleSeatStatusChange(seatId, newStatus, staffId) {
            const seatEl = document.getElementById(`seat-${seatId}`);
            if (!seatEl) return;

            const seat = allSeats.get(seatId);
            if (!seat) return;

            // Update seat data
            seat.status = newStatus;
            seat.staff_id = staffId;
            allSeats.set(seatId, seat);

            // Update UI
            seatEl.classList.remove('available', 'selected', 'booked-mine', 'booked-other');

            if (newStatus === 0) { // Available
                seatEl.classList.add('available');
                seatEl.onclick = () => toggleSeat(seat);
                // Remove from selected if was selected
                if (selectedSeats.has(seatId)) {
                    selectedSeats.delete(seatId);
                    updateUI();
                }
            } else if (newStatus === 1) { // Booked
                if (staffId === currentStaffId) {
                    seatEl.classList.add('booked-mine');
                } else {
                    seatEl.classList.add('booked-other');
                }
            } else if (newStatus === 2) { // Reserved
                seatEl.classList.add('booked-other');
            }
        }

        function handlePaymentCompleted(event) {
            console.log('Payment completed for ticket:', event.ticket_code);
            // Auto-refresh ticket info
            getTicketInfo(event.ticket_code);
        }

        function processPayment() {
            if (selectedSeats.size === 0) {
                alert('Vui lòng chọn ít nhất một ghế');
                return;
            }

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const seatIds = Array.from(selectedSeats.keys());

            showLoading();

            if (paymentMethod === 'cash') {
                createTicketCash(seatIds);
            } else {
                initPaymentPayOs(seatIds);
            }
        }

        function createTicketCash(seatIds) {
            fetch('/api/ticket-booking/create-ticket-cash', {
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
                            throw new Error(data.message || 'HTTP ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.ticket) {
                        getTicketInfo(data.ticket.code);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Lỗi khi tạo vé: ' + error.message);
                    console.error('Error:', error);
                });
        }

        function initPaymentPayOs(seatIds) {
            fetch('/api/ticket-booking/init-payment-payos', {
                    method: 'POST',
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
                            throw new Error(data.message || 'HTTP ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.checkout_url) {
                        // Show QR code modal
                        showPayOsModal(data);
                    } else {
                        alert('Lỗi: ' + (data.message || data.error));
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Lỗi khi khởi tạo thanh toán: ' + error.message);
                });
        }

        function showPayOsModal(paymentData) {
            const modal = new bootstrap.Modal(document.getElementById('payosModal'));

            // Display QR code
            const qrContainer = document.getElementById('payosQRContainer');
            const checkoutUrl = paymentData.checkout_url;

            if (checkoutUrl) {
                qrContainer.innerHTML = `
                <div class="text-center">
                    <p>Quét mã QR bằng ứng dụng ngân hàng hoặc ví điện tử để thanh toán</p>
                    <a href="${checkoutUrl}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt"></i> Thanh toán trực tuyến
                    </a>
                </div>
            `;
            }

            modal.show();

            // Store ticket code for later verification
            sessionStorage.setItem('currentTicketCode', paymentData.ticket_code);

            // Subscribe to payment completion event
            if (echo) {
                echo.private(`ticket.${paymentData.ticket_code}`)
                    .listen('PaymentCompleted', (event) => {
                        console.log('Payment completed:', event);
                        bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                        getTicketInfo(paymentData.ticket_code);
                    });
            }

            // Poll for payment status
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
                            bootstrap.Modal.getInstance(document.getElementById('payosModal'))?.hide();
                            getTicketInfo(ticketCode);
                        }
                    })
                    .catch(error => console.error('Error polling payment status:', error));
            }, 3000); // Check every 3 seconds

            // Stop polling after 15 minutes
            setTimeout(() => clearInterval(pollInterval), 15 * 60 * 1000);
        }

        function getTicketInfo(ticketCode) {
            fetch(`/api/ticket-booking/ticket/${ticketCode}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'HTTP ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showTicketModal(data);
                })
                .catch(error => {
                    console.error('Error loading ticket info:', error);
                    alert('Lỗi: ' + error.message);
                });
        }

        function showTicketModal(data) {
            const ticket = data.ticket;
            const bookings = data.bookings;

            let seatsHtml = bookings.map(b =>
                `<span class="badge bg-info">${b.seat.row}${b.seat.column}</span>`
            ).join(' ');

            const html = `
            <div class="ticket-info p-3">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Mã vé:</strong> ${ticket.code}
                    </div>
                    <div class="col-md-6">
                        <strong>Nhân viên:</strong> ${ticket.staff?.name || 'N/A'}
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Ghế:</strong><br>
                    ${seatsHtml}
                </div>
                <div class="alert alert-success mb-0">
                    <strong>Tổng cộng:</strong> <span class="h5">${number_format(ticket.final_price)} VNĐ</span>
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

        function getAuthToken() {
            // Session auth không cần token
            return '';
        }

        function getTicketCode() {
            return sessionStorage.getItem('currentTicketCode');
        }

        function getCookie(name) {
            const nameEQ = name + "=";
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i].trim();
                if (cookie.indexOf(nameEQ) === 0) return cookie.substring(nameEQ.length);
            }
            return '';
        }

        function number_format(num) {
            return new Intl.NumberFormat('vi-VN').format(num);
        }
    </script>
@endsection
