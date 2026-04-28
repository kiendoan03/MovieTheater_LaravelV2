@extends('layouts.management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <style>
        :root {
            --bg: #0d0f14;
            --surface: #13161e;
            --card: #1a1e28;
            --border: rgba(255, 255, 255, 0.07);
            --border-h: rgba(255, 255, 255, 0.15);
            --text: #e8eaf0;
            --muted: #6b7280;
            --accent: #e8c96a;
            --accent-bg: rgba(232, 201, 106, 0.10);
            --success: #22c55e;
            --danger: #ef4444;
            --info: #3b82f6;
            --purple: #8b5cf6;
        }

        .page-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 2rem 0 4rem;
        }

        /* ── Back button ── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            padding: 0.4rem 0.9rem;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
            margin-bottom: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        .btn-back:hover {
            border-color: var(--border-h);
            color: var(--text);
        }

        /* ── Profile header card ── */
        .profile-header {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .profile-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(59, 130, 246, 0.08));
            border: 2px solid rgba(59, 130, 246, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 26px;
            color: #60a5fa;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            margin-bottom: 2px;
        }

        .profile-email {
            font-size: 13px;
            color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
        }

        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 0.6rem;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
        }

        .meta-chip i {
            font-size: 11px;
            opacity: 0.6;
        }

        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .btn-edit-profile {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.15s;
            white-space: nowrap;
        }

        .btn-edit-profile:hover {
            opacity: 0.85;
            transform: translateY(-1px);
            color: #fff;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        /* ── Stat strip ── */
        .stat-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1;
        }

        .stat-label {
            font-size: 11px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            margin-top: 5px;
        }

        .stat-card.info   .stat-value { color: #60a5fa; }
        .stat-card.success .stat-value { color: var(--success); }
        .stat-card.accent .stat-value  { color: var(--accent); }

        /* ── Section ── */
        .section-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
        }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.1rem;
            font-family: 'Inter', sans-serif;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1.5rem;
        }

        .info-item {}

        .info-item-label {
            font-size: 11px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            margin-bottom: 3px;
        }

        .info-item-value {
            font-size: 14px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        /* ── Ticket list ── */
        .ticket-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .ticket-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .ticket-card:hover {
            border-color: var(--border-h);
        }

        .ticket-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1.1rem;
            cursor: pointer;
            user-select: none;
        }

        .ticket-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.22);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 13px;
            flex-shrink: 0;
        }

        .ticket-header-body {
            flex: 1;
            min-width: 0;
        }

        .ticket-code {
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--muted);
            margin-bottom: 1px;
        }

        .ticket-movie {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ticket-time {
            font-size: 11px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            margin-top: 2px;
        }

        .ticket-header-right {
            text-align: right;
            flex-shrink: 0;
        }

        .ticket-price {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent);
            font-family: 'Inter', sans-serif;
        }

        .ticket-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 4px;
        }

        .status-paid {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .status-pending {
            background: rgba(234, 179, 8, 0.1);
            color: #eab308;
            border: 1px solid rgba(234, 179, 8, 0.25);
        }

        .ticket-chevron {
            color: var(--muted);
            font-size: 12px;
            transition: transform 0.25s;
            flex-shrink: 0;
        }

        .ticket-card.open .ticket-chevron {
            transform: rotate(180deg);
        }

        /* ── Ticket detail (expandable) ── */
        .ticket-detail {
            display: none;
            border-top: 1px solid var(--border);
            padding: 1rem 1.1rem 1.1rem;
            animation: fadeSlide 0.2s ease;
        }

        .ticket-card.open .ticket-detail {
            display: block;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .detail-section-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.6rem;
            font-family: 'Inter', sans-serif;
        }

        .seat-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-bottom: 0.9rem;
        }

        .seat-tag {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.22);
            color: #60a5fa;
            border-radius: 8px;
            padding: 3px 10px;
            font-size: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
        }

        .seat-tag.couple {
            background: rgba(236, 72, 153, 0.1);
            border-color: rgba(236, 72, 153, 0.25);
            color: #f9a8d4;
        }

        .detail-meta-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem 1.25rem;
        }

        .detail-meta-item {}

        .detail-meta-label {
            font-size: 10px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            margin-bottom: 1px;
        }

        .detail-meta-value {
            font-size: 13px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        .ticket-empty {
            text-align: center;
            padding: 2rem 0;
            color: var(--muted);
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }

        @media (max-width: 640px) {
            .profile-header { flex-direction: column; align-items: flex-start; }
            .stat-strip { grid-template-columns: 1fr 1fr; }
            .info-grid { grid-template-columns: 1fr; }
            .detail-meta-row { grid-template-columns: 1fr; }
        }
    </style>

    <div class="cw">
        <div class="container-fluid px-3 px-md-5">
            <div class="page-wrap">

                <a href="{{ route('admin.accounts.staff.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Danh sách nhân viên
                </a>

                @php
                    $stf     = $staff->staff;
                    $tickets = $stf?->tickets ?? collect();

                    // Tính toán thống kê
                    $totalTickets = $tickets->count();
                    $paidTickets  = $tickets->filter(fn($t) => $t->isFullyPaid())->count();
                    $totalRevenue = $tickets->sum('final_price');

                    // Tổng số ghế đã bán
                    $totalSeats = $tickets->sum(fn($t) => $t->bookings->count());
                @endphp

                {{-- ── Profile header ── --}}
                <div class="profile-header">
                    <div class="profile-avatar">
                        @if ($stf?->avatar)
                            <img src="{{ $stf->avatar }}" alt="">
                        @else
                            <i class="fa-solid fa-user-tie"></i>
                        @endif
                    </div>

                    <div class="profile-info">
                        <div class="profile-name">{{ $stf?->name ?? '—' }}</div>
                        <div class="profile-email">{{ $staff->email }}</div>
                        <div class="profile-meta">
                            @if ($stf?->phonenumber)
                                <span class="meta-chip">
                                    <i class="fa-solid fa-phone"></i> {{ $stf->phonenumber }}
                                </span>
                            @endif
                            @if ($stf?->date_of_birth)
                                <span class="meta-chip">
                                    <i class="fa-solid fa-cake-candles"></i>
                                    {{ \Carbon\Carbon::parse($stf->date_of_birth)->format('d/m/Y') }}
                                    ({{ \Carbon\Carbon::parse($stf->date_of_birth)->age }} tuổi)
                                </span>
                            @endif
                            @if ($stf?->address)
                                <span class="meta-chip">
                                    <i class="fa-solid fa-location-dot"></i> {{ $stf->address }}
                                </span>
                            @endif
                            <span class="meta-chip">
                                <i class="fa-regular fa-clock"></i>
                                Tham gia {{ $staff->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="profile-actions">
                        @if ($staff->is_active)
                            <span class="badge-status badge-active">
                                <i class="fa-solid fa-circle" style="font-size:7px;"></i> Hoạt động
                            </span>
                        @else
                            <span class="badge-status badge-inactive">
                                <i class="fa-solid fa-circle" style="font-size:7px;"></i> Vô hiệu
                            </span>
                        @endif
                        <a href="{{ route('admin.accounts.staff.edit', $staff->id) }}" class="btn-edit-profile">
                            <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>

                {{-- ── Stat strip ── --}}
                <div class="stat-strip">
                    <div class="stat-card info">
                        <div class="stat-value">{{ $totalTickets }}</div>
                        <div class="stat-label">Vé đã bán</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-value">{{ $paidTickets }}</div>
                        <div class="stat-label">Đã thanh toán</div>
                    </div>
                    <div class="stat-card accent">
                        <div class="stat-value">{{ number_format($totalRevenue / 1000, 0) }}K</div>
                        <div class="stat-label">Doanh thu xử lý (VNĐ)</div>
                    </div>
                </div>

                {{-- ── Personal info ── --}}
                <div class="section-card">
                    <div class="section-label">Thông tin tài khoản</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">ID tài khoản</div>
                            <div class="info-item-value" style="font-family:'JetBrains Mono',monospace;">
                                #{{ str_pad($staff->id, 4, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Email</div>
                            <div class="info-item-value" style="font-family:'JetBrains Mono',monospace;font-size:13px;">
                                {{ $staff->email }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Ngày tham gia</div>
                            <div class="info-item-value">{{ $staff->created_at->format('H:i · d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Cập nhật lần cuối</div>
                            <div class="info-item-value">{{ $staff->updated_at->format('H:i · d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- ── Ticket history ── --}}
                <div class="section-card">
                    <div class="section-label">Lịch sử vé đã bán ({{ $totalTickets }})</div>

                    @if ($tickets->isNotEmpty())
                        <div class="ticket-list">
                            @foreach ($tickets->sortByDesc('created_at') as $ticket)
                                @php
                                    $isPaid     = $ticket->isFullyPaid();
                                    $firstBook  = $ticket->bookings->first();
                                    $movieName  = $firstBook?->schedule?->movie?->movie_name ?? '—';
                                    $showTime   = $firstBook?->schedule?->start_time
                                        ? \Carbon\Carbon::parse($firstBook->schedule->start_time)->format('H:i · d/m/Y')
                                        : '—';
                                    $roomName   = $firstBook?->schedule?->room?->room_name ?? '—';
                                @endphp

                                <div class="ticket-card" id="ticket-{{ $ticket->id }}">
                                    {{-- Header — click to expand --}}
                                    <div class="ticket-card-header"
                                        onclick="toggleTicket('ticket-{{ $ticket->id }}')">
                                        <div class="ticket-icon">
                                            <i class="fa-solid fa-ticket"></i>
                                        </div>

                                        <div class="ticket-header-body">
                                            <div class="ticket-code">{{ $ticket->code }}</div>
                                            <div class="ticket-movie">{{ $movieName }}</div>
                                            <div class="ticket-time">
                                                <i class="fa-regular fa-clock" style="opacity:.5;"></i>
                                                {{ $showTime }}
                                                &nbsp;·&nbsp;
                                                <i class="fa-solid fa-door-open" style="opacity:.5;"></i>
                                                {{ $roomName }}
                                            </div>
                                        </div>

                                        <div class="ticket-header-right">
                                            <div class="ticket-price">
                                                {{ number_format($ticket->final_price, 0, ',', '.') }}đ
                                            </div>
                                            <div>
                                                @if ($isPaid)
                                                    <span class="ticket-status-badge status-paid">
                                                        <i class="fa-solid fa-circle-check" style="font-size:8px;"></i>
                                                        Đã thanh toán
                                                    </span>
                                                @else
                                                    <span class="ticket-status-badge status-pending">
                                                        <i class="fa-solid fa-clock" style="font-size:8px;"></i>
                                                        Chờ thanh toán
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <i class="fa-solid fa-chevron-down ticket-chevron"></i>
                                    </div>

                                    {{-- Detail panel --}}
                                    <div class="ticket-detail">
                                        @php
                                            $bookings = $ticket->bookings;
                                        @endphp

                                        @if ($bookings->isNotEmpty())
                                            <div class="detail-section-title">Ghế đã đặt</div>
                                            <div class="seat-tags">
                                                @foreach ($bookings as $booking)
                                                    @php
                                                        $seatLabel  = ($booking->seat?->row ?? '') . ($booking->seat?->column ?? '');
                                                        $seatType   = $booking->seat?->seatType?->type ?? '';
                                                        $isCouple   = $booking->seat?->seatType?->is_couple ?? false;
                                                    @endphp
                                                    <span class="seat-tag {{ $isCouple ? 'couple' : '' }}"
                                                        title="{{ $seatType }}">
                                                        {{ $seatLabel ?: '—' }}
                                                        @if ($isCouple)
                                                            <i class="fa-solid fa-heart" style="font-size:9px;"></i>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Detail meta --}}
                                        <div class="detail-meta-row">
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Mã vé</div>
                                                <div class="detail-meta-value"
                                                    style="font-family:'JetBrains Mono',monospace;">
                                                    {{ $ticket->code }}
                                                </div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Phim</div>
                                                <div class="detail-meta-value">{{ $movieName }}</div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Giờ chiếu</div>
                                                <div class="detail-meta-value">{{ $showTime }}</div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Phòng chiếu</div>
                                                <div class="detail-meta-value">{{ $roomName }}</div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Số ghế</div>
                                                <div class="detail-meta-value">{{ $bookings->count() }} ghế</div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Tổng tiền</div>
                                                <div class="detail-meta-value" style="color:var(--accent);">
                                                    {{ number_format($ticket->final_price, 0, ',', '.') }} VNĐ
                                                </div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Trạng thái</div>
                                                <div class="detail-meta-value">
                                                    @if ($isPaid)
                                                        <span style="color:#22c55e;">✓ Đã thanh toán</span>
                                                    @else
                                                        <span style="color:#eab308;">⏳ Chờ thanh toán</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="detail-meta-item">
                                                <div class="detail-meta-label">Thời gian tạo</div>
                                                <div class="detail-meta-value">
                                                    {{ $ticket->created_at->format('H:i · d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ticket-empty">
                            <i class="fa-regular fa-ticket"
                                style="font-size:28px;opacity:.3;display:block;margin-bottom:10px;"></i>
                            Nhân viên này chưa bán vé nào.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleTicket(id) {
            const card = document.getElementById(id);
            card.classList.toggle('open');
        }
    </script>
@endsection
