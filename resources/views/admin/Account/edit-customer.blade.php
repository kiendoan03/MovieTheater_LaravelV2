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
        }

        .staff-wrap {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem 0 3rem;
        }

        .staff-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.25rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

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
            margin-bottom: 1.25rem;
            font-family: 'Inter', sans-serif;
        }

        .btn-back:hover {
            border-color: var(--border-h);
            color: var(--text);
        }

        .brand-logo {
            font-size: 11px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.4rem;
            font-family: 'Inter', sans-serif;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
        }

        .brand-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 0.3rem;
            font-family: 'Inter', sans-serif;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: #93c5fd;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-radius: 20px;
            padding: 4px 12px;
            margin-bottom: 0.9rem;
            font-family: 'Inter', sans-serif;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.4rem 0;
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
            margin-bottom: 1rem;
            font-family: 'Inter', sans-serif;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Identity chip */
        .staff-identity {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(232, 201, 106, 0.05);
            border: 1px solid rgba(232, 201, 106, 0.14);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
        }

        .staff-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(232, 201, 106, 0.25), rgba(232, 201, 106, 0.08));
            border: 1.5px solid rgba(232, 201, 106, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
            color: var(--accent);
            overflow: hidden;
        }

        .staff-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staff-identity-info {
            flex: 1;
            min-width: 0;
        }

        .staff-identity-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .staff-identity-email {
            font-size: 12px;
            color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .staff-id-chip {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 3px 9px;
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--muted);
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Ticket stats strip */
        .stat-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }

        .stat-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.7rem 0.9rem;
            text-align: center;
        }

        .stat-value {
            font-size: 1.3rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            color: var(--text);
        }

        .stat-label {
            font-size: 11px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            margin-top: 2px;
        }

        .stat-item.highlight .stat-value {
            color: var(--accent);
        }

        /* Status toggle */
        .status-toggle-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
        }

        .status-toggle-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .status-toggle-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            font-family: 'Inter', sans-serif;
        }

        .status-toggle-sub {
            font-size: 11px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
        }

        /* Toggle switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            cursor: pointer;
            transition: background 0.25s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            left: 3px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.25s;
        }

        .toggle-switch input:checked+.toggle-slider {
            background: var(--success);
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(20px);
        }

        /* Form fields */
        .form-label-s {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.45rem;
            display: block;
            font-family: 'Inter', sans-serif;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap>i.prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrap:focus-within>i.prefix {
            color: var(--accent);
        }

        .inp {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            padding: 0.7rem 1rem 0.7rem 2.7rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .inp.no-icon {
            padding-left: 1rem;
        }

        .inp:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232, 201, 106, 0.12);
            background: var(--surface);
            color: var(--text);
        }

        .inp[readonly] {
            opacity: 0.55;
            cursor: default;
        }

        .inp::placeholder {
            color: var(--muted);
        }

        .inp.is-invalid {
            border-color: var(--danger) !important;
        }

        .err-msg {
            font-size: 12px;
            color: #fca5a5;
            margin-top: 5px;
            display: none;
        }

        .err-msg.show {
            display: block;
        }

        /* Alerts */
        .flash-success {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 12px;
            padding: 0.65rem 1rem;
            display: flex;
            gap: 9px;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 13px;
            color: #86efac;
            font-family: 'Inter', sans-serif;
        }

        .laravel-errors {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 9px;
            align-items: flex-start;
        }

        .laravel-errors i {
            color: #ef4444;
            font-size: 14px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .laravel-errors ul {
            margin: 0;
            padding: 0 0 0 1rem;
        }

        .laravel-errors li {
            font-size: 13px;
            color: #fca5a5;
            line-height: 1.6;
        }

        /* Form row */
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        @media (max-width: 480px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Action buttons */
        .btn-actions {
            display: flex;
            gap: 0.6rem;
            margin-top: 1.75rem;
        }

        .btn-submit {
            flex: 1;
            background: var(--accent);
            color: #0d0f14;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            padding: 0.85rem;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            opacity: 0.88;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(232, 201, 106, 0.25);
        }

        .btn-cancel {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 0.85rem 1.25rem;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-cancel:hover {
            border-color: var(--border-h);
            color: var(--text);
        }

        /* Ticket history section */
        .ticket-list {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .ticket-row {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .ticket-row-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a5b4fc;
            font-size: 13px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .ticket-row-body {
            flex: 1;
            min-width: 0;
        }

        .ticket-row-code {
            font-size: 12px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--muted);
            margin-bottom: 2px;
        }

        .ticket-row-movie {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ticket-row-meta {
            font-size: 11px;
            color: var(--muted);
            font-family: 'Inter', sans-serif;
            margin-top: 2px;
        }

        .ticket-row-right {
            text-align: right;
            flex-shrink: 0;
        }

        .ticket-price {
            font-size: 13px;
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

        .ticket-empty {
            text-align: center;
            padding: 1.5rem 0;
            color: var(--muted);
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }

        /* ── Avatar drop zone (admin) ── */
        .admin-drop-zone {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border: 2px dashed rgba(232, 201, 106, .25);
            border-radius: 12px;
            padding: 1.25rem;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            text-align: center;
            background: rgba(232, 201, 106, .03);
        }
        .admin-drop-zone:hover,
        .admin-drop-zone.drag-over {
            border-color: rgba(232, 201, 106, .6);
            background: rgba(232, 201, 106, .06);
        }
        .admin-drop-zone.has-file { border-color: rgba(34,197,94,.5); background: rgba(34,197,94,.04); }
        .drop-main-a { font-size: 13px; color: var(--text); }
        .drop-main-a u { color: var(--accent); }
        .drop-sub-a { font-size: 11px; color: var(--muted); }
        .drop-fname-a { font-size: 12px; color: #4ade80; font-family: 'JetBrains Mono', monospace; margin-top: 4px; word-break: break-all; }
    </style>

    <div class="cw">
        <div class="container-fluid px-3 px-md-5">
            <div class="staff-wrap">

                <a href="{{ route('admin.accounts.customer.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Danh sách khách hàng
                </a>

                <div class="staff-card">



                    <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
                    <h2 class="brand-title">Chỉnh sửa khách hàng</h2>
                    <p class="brand-sub">Cập nhật thông tin và trạng thái tài khoản khách hàng.</p>

                    <div class="divider"></div>

                    {{-- Flash --}}
                    @if (session('success'))
                        <div class="flash-success">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="laravel-errors">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Identity chip --}}
                    <div class="staff-identity">
                        <div class="staff-avatar">
                            @if ($customer->customer?->avatar)
                                <img src="{{ asset('storage/img/avatars/' . $customer->customer->avatar) }}" alt="" id="adminAvatarImg">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="" id="adminAvatarImg">
                            @endif
                        </div>
                        <div class="staff-identity-info">
                            <div class="staff-identity-name">{{ $customer->customer?->name ?? '—' }}</div>
                            <div class="staff-identity-email">{{ $customer->email }}</div>
                        </div>
                        <div class="staff-id-chip">
                            #{{ str_pad($customer->customer?->id ?? $customer->id, 3, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    {{-- Ticket stats --}}
                    @php
                        $tickets = $customer->customer?->tickets ?? collect();
                        $totalTickets = $tickets->count();
                        $paidTickets = $tickets->filter(fn($t) => $t->isFullyPaid())->count();
                        $totalSpent = $tickets->sum('final_price');
                    @endphp
                    <div class="stat-strip">
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalTickets }}</div>
                            <div class="stat-label">Vé đã đặt</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $paidTickets }}</div>
                            <div class="stat-label">Đã thanh toán</div>
                        </div>
                        <div class="stat-item highlight">
                            <div class="stat-value">{{ number_format($totalSpent / 1000, 0) }}K</div>
                            <div class="stat-label">Tổng chi tiêu</div>
                        </div>
                    </div>

                    {{-- Edit form --}}
                    <form method="POST" action="{{ route('admin.accounts.customer.update', $customer) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="section-label">Thông tin tài khoản</div>

                        <div class="mb-3">
                            <label class="form-label-s">Địa chỉ Email</label>
                            <div class="input-wrap">
                                <input type="email" class="inp" value="{{ $customer->email }}" readonly>
                                <i class="fa-regular fa-envelope prefix"></i>
                            </div>
                        </div>

                        <div class="status-toggle-wrap">
                            <div class="status-toggle-info">
                                <span class="status-toggle-label">Trạng thái tài khoản</span>
                                <span class="status-toggle-sub">Tắt để vô hiệu hoá đăng nhập của khách hàng</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="divider"></div>
                        <div class="section-label">Thông tin cá nhân</div>

                        <div class="mb-3">
                            <label class="form-label-s" for="cust-name">Họ và tên</label>
                            <div class="input-wrap">
                                <input id="cust-name" name="name" type="text"
                                    class="inp @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A"
                                    value="{{ old('name', $customer->customer?->name) }}" autocomplete="off">
                                <i class="fa-regular fa-user prefix"></i>
                            </div>
                            @error('name')
                                <div class="err-msg show">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row-2 mb-3">
                            <div>
                                <label class="form-label-s" for="cust-phone">Số điện thoại</label>
                                <div class="input-wrap">
                                    <input id="cust-phone" name="phonenumber" type="tel"
                                        class="inp @error('phonenumber') is-invalid @enderror" placeholder="0987 xxx xxx"
                                        value="{{ old('phonenumber', $customer->customer?->phonenumber) }}">
                                    <i class="fa-solid fa-phone prefix"></i>
                                </div>
                                @error('phonenumber')
                                    <div class="err-msg show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label-s" for="cust-dob">Ngày sinh</label>
                                <input id="cust-dob" name="date_of_birth" type="date"
                                    class="inp no-icon @error('date_of_birth') is-invalid @enderror"
                                    style="color-scheme: dark;"
                                    value="{{ old('date_of_birth', $customer->customer?->date_of_birth ? \Carbon\Carbon::parse($customer->customer->date_of_birth)->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="err-msg show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label-s" for="cust-address">
                                Địa chỉ <span style="color:var(--muted);font-size:10px;">(tuỳ chọn)</span>
                            </label>
                            <div class="input-wrap">
                                <input id="cust-address" name="address" type="text" class="inp"
                                    placeholder="123 Đường ABC, Hà Nội"
                                    value="{{ old('address', $customer->customer?->address) }}">
                                <i class="fa-solid fa-location-dot prefix"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-s">Ảnh đại diện <span style="color:var(--muted);font-size:10px;">(tuỳ chọn)</span></label>
                            <label for="adminAvatarFile" class="admin-drop-zone" id="adminDropZone">
                                <i class="fa-solid fa-cloud-arrow-up" style="font-size:20px;color:var(--accent);margin-bottom:6px;"></i>
                                <span class="drop-main-a">Kéo thả ảnh hoặc <u>chọn file</u></span>
                                <span class="drop-sub-a">JPG, PNG, GIF, WebP · Tối đa 2MB</span>
                                <span class="drop-fname-a" id="adminDropFname"></span>
                            </label>
                            <input type="file" id="adminAvatarFile" name="avatar" accept="image/*" style="display:none;">
                            @error('avatar')
                                <div class="err-msg show">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="btn-actions">
                            <a href="{{ route('admin.accounts.customer.index') }}" class="btn-cancel">
                                <i class="fa-solid fa-xmark"></i> Huỷ
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Lưu thay đổi
                            </button>
                        </div>

                    </form>

                    {{-- Ticket history (read-only) --}}
                    <div class="divider"></div>
                    <div class="section-label">Lịch sử đặt vé</div>

                    @if ($tickets->isNotEmpty())
                        <div class="ticket-list">
                            @foreach ($tickets->sortByDesc('created_at')->take(10) as $ticket)
                                @php
                                    $isPaid = $ticket->isFullyPaid();
                                    // Lấy tên phim từ booking đầu tiên
                                    $firstBook = $ticket->bookings->first();
                                    $movieName = $firstBook?->schedule?->movie?->movie_name ?? '—';
                                    $showTime = $firstBook?->schedule?->start_time
                                        ? \Carbon\Carbon::parse($firstBook->schedule->start_time)->format('H:i · d/m/Y')
                                        : '—';
                                    $seatList = $ticket->bookings
                                        ->map(fn($b) => ($b->seat?->row ?? '') . ($b->seat?->column ?? ''))
                                        ->filter()
                                        ->join(', ');
                                @endphp
                                <div class="ticket-row">
                                    <div class="ticket-row-icon">
                                        <i class="fa-solid fa-ticket"></i>
                                    </div>
                                    <div class="ticket-row-body">
                                        <div class="ticket-row-code">{{ $ticket->code }}</div>
                                        <div class="ticket-row-movie">{{ $movieName }}</div>
                                        <div class="ticket-row-meta">
                                            <i class="fa-regular fa-clock" style="opacity:.5;"></i> {{ $showTime }}
                                            @if ($seatList)
                                                &nbsp;·&nbsp;
                                                <i class="fa-solid fa-couch" style="opacity:.5;"></i> {{ $seatList }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ticket-row-right">
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
                                </div>
                            @endforeach
                        </div>

                        @if ($tickets->count() > 10)
                            <p
                                style="text-align:center;font-size:12px;color:var(--muted);margin-top:0.75rem;font-family:'Inter',sans-serif;">
                                Hiển thị 10 vé gần nhất / tổng {{ $tickets->count() }} vé
                            </p>
                        @endif
                    @else
                        <div class="ticket-empty">
                            <i class="fa-regular fa-ticket"
                                style="font-size:24px;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Khách hàng chưa đặt vé nào.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        const fi   = document.getElementById('adminAvatarFile');
        const dz   = document.getElementById('adminDropZone');
        const fn   = document.getElementById('adminDropFname');
        const prev = document.querySelector('.staff-avatar');

        function applyPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            fn.textContent = file.name;
            dz.classList.add('has-file');
            const reader = new FileReader();
            reader.onload = e => {
                let img = prev.querySelector('img');
                const icon = prev.querySelector('i');
                if (icon) icon.remove();
                if (!img) { img = document.createElement('img'); img.style.cssText='width:100%;height:100%;object-fit:cover;'; prev.appendChild(img); }
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        fi.addEventListener('change', () => applyPreview(fi.files[0]));
        dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
        dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
        dz.addEventListener('drop', e => {
            e.preventDefault(); dz.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) { fi.files = e.dataTransfer.files; applyPreview(file); }
        });
    })();
</script>
@endpush
