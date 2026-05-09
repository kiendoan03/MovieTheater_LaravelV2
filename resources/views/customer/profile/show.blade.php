@extends('layouts.client')
@section('title', 'Hồ sơ cá nhân · NetFnix')

@push('styles')
<style>
.profile-page { padding: 110px 0 80px; min-height: 100vh; }

/* Hero */
.profile-hero {
    background: linear-gradient(135deg, rgba(255,31,69,.15), rgba(10,10,15,.0));
    border: 1px solid rgba(255,31,69,.15);
    border-radius: 24px;
    padding: 2.5rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content:'';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at top left, rgba(255,31,69,.12), transparent 60%);
    pointer-events: none;
}

/* Avatar */
.p-avatar {
    position: relative;
    width: 88px; height: 88px;
    border-radius: 50%;
    flex-shrink: 0;
    background: linear-gradient(135deg, rgba(255,31,69,.3), rgba(255,31,69,.06));
    border: 2.5px solid rgba(255,31,69,.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 34px; color: #ff4468; overflow: hidden;
    box-shadow: 0 0 28px rgba(255,31,69,.22);
}
.p-avatar img { width:100%; height:100%; object-fit:cover; }
.p-avatar-ring {
    position: absolute; inset: -5px; border-radius: 50%;
    border: 1.5px solid rgba(255,31,69,.28);
    animation: pulse-ring 3s ease-in-out infinite;
}
@keyframes pulse-ring {
    0%,100% { opacity:.4; transform:scale(1); }
    50% { opacity:.9; transform:scale(1.06); }
}

.p-info { flex:1; min-width:0; }
.p-name { font-size:1.55rem; font-weight:800; color:#fff; font-family:'Montserrat',sans-serif; margin-bottom:4px; }
.p-email { font-size:13px; color:rgba(255,255,255,.45); font-family:monospace; margin-bottom:10px; }
.p-badges { display:flex; flex-wrap:wrap; gap:7px; }
.p-badge { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:600; padding:3px 12px; border-radius:999px; }
.badge-active  { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.25); }
.badge-inactive{ background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.25); }
.badge-member  { background:rgba(255,255,255,.06); color:rgba(255,255,255,.45); border:1px solid rgba(255,255,255,.1); }

.p-actions { flex-shrink:0; }
.btn-red-pill {
    display:inline-flex; align-items:center; gap:8px;
    background: linear-gradient(135deg, #ff1f45, #ff4d6d);
    color:#fff; border:none; border-radius:999px;
    padding:10px 22px; font-size:13px; font-weight:700;
    text-decoration:none; transition:.25s;
    box-shadow:0 8px 22px rgba(255,31,69,.28);
    font-family:'Montserrat',sans-serif;
}
.btn-red-pill:hover { transform:translateY(-2px); box-shadow:0 14px 30px rgba(255,31,69,.42); color:#fff; }

/* Stats */
.stat-strip { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
.stat-card {
    background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08);
    border-radius:18px; padding:1.25rem; text-align:center; transition:.25s;
}
.stat-card:hover { border-color:rgba(255,31,69,.25); transform:translateY(-3px); }
.stat-value { font-size:2rem; font-weight:800; font-family:'Montserrat',sans-serif; line-height:1; margin-bottom:6px; }
.stat-label { font-size:10px; color:rgba(255,255,255,.4); text-transform:uppercase; letter-spacing:.08em; }
.sv-red{color:#ff4468;} .sv-green{color:#4ade80;} .sv-yellow{color:#fbbf24;}

/* Section */
.s-card { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07); border-radius:20px; padding:1.75rem; margin-bottom:1.5rem; }
.s-label { font-size:10px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.38); display:flex; align-items:center; gap:10px; margin-bottom:1.25rem; }
.s-label::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.07); }

/* Info grid */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:.7rem 2rem; }
.info-label { font-size:11px; color:rgba(255,255,255,.38); margin-bottom:2px; }
.info-value { font-size:14px; color:#fff; font-weight:500; }

/* Alert */
.alert-ok {
    background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.22);
    color:#4ade80; border-radius:12px; padding:.75rem 1.2rem; font-size:13px;
    display:flex; align-items:center; gap:10px; margin-bottom:1.25rem;
    animation:fadeIn .4s ease;
}
@keyframes fadeIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

/* Ticket */
.ticket-list { display:flex; flex-direction:column; gap:.75rem; }
.ticket-card { background:rgba(255,255,255,.025); border:1px solid rgba(255,255,255,.07); border-radius:16px; overflow:hidden; transition:border-color .2s; }
.ticket-card:hover { border-color:rgba(255,31,69,.22); }
.tc-header { display:flex; align-items:center; gap:14px; padding:1rem 1.25rem; cursor:pointer; user-select:none; }
.tc-icon { width:38px; height:38px; border-radius:10px; background:rgba(255,31,69,.1); border:1px solid rgba(255,31,69,.2); display:flex; align-items:center; justify-content:center; color:#ff4468; font-size:14px; flex-shrink:0; }
.tc-body { flex:1; min-width:0; }
.tc-code { font-size:11px; color:rgba(255,255,255,.38); font-family:monospace; margin-bottom:2px; }
.tc-movie { font-size:14px; font-weight:700; color:#fff; font-family:'Montserrat',sans-serif; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.tc-time { font-size:11px; color:rgba(255,255,255,.38); margin-top:2px; }
.tc-right { text-align:right; flex-shrink:0; }
.tc-price { font-size:15px; font-weight:800; color:#fbbf24; font-family:'Montserrat',sans-serif; }
.tc-status { display:inline-flex; align-items:center; gap:4px; font-size:10px; font-weight:700; padding:3px 10px; border-radius:999px; margin-top:5px; }
.s-paid { background:rgba(34,197,94,.1); color:#4ade80; border:1px solid rgba(34,197,94,.25); }
.s-pending { background:rgba(251,191,36,.1); color:#fbbf24; border:1px solid rgba(251,191,36,.25); }
.tc-chev { color:rgba(255,255,255,.3); font-size:12px; transition:transform .25s; flex-shrink:0; }
.ticket-card.open .tc-chev { transform:rotate(180deg); }

/* Detail */
.tc-detail { display:none; border-top:1px solid rgba(255,255,255,.07); padding:1rem 1.25rem 1.25rem; animation:fadeIn .2s ease; }
.ticket-card.open .tc-detail { display:block; }
.d-title { font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:rgba(255,255,255,.38); margin-bottom:.5rem; }
.seat-tags { display:flex; flex-wrap:wrap; gap:.4rem; margin-bottom:1rem; }
.seat-tag { background:rgba(255,31,69,.1); border:1px solid rgba(255,31,69,.2); color:#ff728d; border-radius:8px; padding:3px 11px; font-size:12px; font-family:monospace; font-weight:600; }
.seat-tag.couple { background:rgba(236,72,153,.1); border-color:rgba(236,72,153,.25); color:#f9a8d4; }
.d-meta-row { display:grid; grid-template-columns:1fr 1fr; gap:.5rem 1.5rem; }
.d-meta-label { font-size:10px; color:rgba(255,255,255,.38); margin-bottom:1px; }
.d-meta-value { font-size:13px; color:#fff; font-weight:500; }

/* Empty */
.ticket-empty { text-align:center; padding:3rem 0; color:rgba(255,255,255,.3); }
.ticket-empty i { font-size:40px; margin-bottom:12px; display:block; }
.ticket-empty p { font-size:14px; }

/* Responsive */
@media (max-width:768px) {
    .profile-hero { flex-direction:column; align-items:flex-start; }
    .stat-strip { grid-template-columns:1fr 1fr; }
    .info-grid { grid-template-columns:1fr; }
    .d-meta-row { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div class="profile-page">
<div class="container">
<div style="max-width:860px;margin:0 auto;">

    @php
        $cust         = $account->customer;
        $tickets      = $cust?->tickets ?? collect();
        $totalTickets = $tickets->count();
        $paidTickets  = $tickets->filter(fn($t) => $t->isFullyPaid())->count();
        $totalSpent   = $tickets->sum('final_price');
    @endphp

    @if(session('success'))
        <div class="alert-ok">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Profile Hero --}}
    <div class="profile-hero">
        <div class="p-avatar">
            <div class="p-avatar-ring"></div>
            @if($cust?->avatar)
                <img src="{{ asset('storage/img/avatars/' . $cust->avatar) }}" alt="">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" alt="">
            @endif
        </div>

        <div class="p-info">
            <div class="p-name">{{ $cust?->name ?? 'Chưa cập nhật' }}</div>
            <div class="p-email">{{ $account->email }}</div>
            <div class="p-badges">
                @if($account->is_active)
                    <span class="p-badge badge-active"><i class="fa-solid fa-circle" style="font-size:7px;"></i> Hoạt động</span>
                @else
                    <span class="p-badge badge-inactive"><i class="fa-solid fa-circle" style="font-size:7px;"></i> Vô hiệu hoá</span>
                @endif
                <span class="p-badge badge-member"><i class="fa-regular fa-clock" style="font-size:10px;"></i> Tham gia {{ $account->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="p-actions">
            <a href="{{ route('customer.profile.edit') }}" class="btn-red-pill">
                <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stat-strip">
        <div class="stat-card">
            <div class="stat-value sv-red">{{ $totalTickets }}</div>
            <div class="stat-label">Vé đã đặt</div>
        </div>
        <div class="stat-card">
            <div class="stat-value sv-green">{{ $paidTickets }}</div>
            <div class="stat-label">Đã thanh toán</div>
        </div>
        <div class="stat-card">
            <div class="stat-value sv-yellow">{{ number_format($totalSpent/1000, 0) }}K</div>
            <div class="stat-label">Chi tiêu (VNĐ)</div>
        </div>
    </div>

    {{-- Thông tin cá nhân --}}
    <div class="s-card">
        <div class="s-label">Thông tin cá nhân</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Họ và tên</div>
                <div class="info-value">{{ $cust?->name ?? '—' }}</div>
            </div>
            <div>
                <div class="info-label">Email</div>
                <div class="info-value" style="font-family:monospace;font-size:13px;">{{ $account->email }}</div>
            </div>
            <div>
                <div class="info-label">Số điện thoại</div>
                <div class="info-value">{{ $cust?->phonenumber ?? '—' }}</div>
            </div>
            <div>
                <div class="info-label">Ngày sinh</div>
                <div class="info-value">
                    @if($cust?->date_of_birth)
                        {{ \Carbon\Carbon::parse($cust->date_of_birth)->format('d/m/Y') }}
                        <span style="color:rgba(255,255,255,.4);font-size:12px;">({{ \Carbon\Carbon::parse($cust->date_of_birth)->age }} tuổi)</span>
                    @else —
                    @endif
                </div>
            </div>
            <div style="grid-column:1/-1">
                <div class="info-label">Địa chỉ</div>
                <div class="info-value">{{ $cust?->address ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Lịch sử đặt vé --}}
    <div class="s-card">
        <div class="s-label">Lịch sử đặt vé ({{ $totalTickets }})</div>

        @if($tickets->isNotEmpty())
            <div class="ticket-list">
                @foreach($tickets->sortByDesc('created_at') as $ticket)
                    @php
                        $isPaid    = $ticket->isFullyPaid();
                        $fb        = $ticket->bookings->first();
                        $movieName = $fb?->schedule?->movie?->movie_name ?? '—';
                        $showTime  = $fb?->schedule?->start_time
                            ? \Carbon\Carbon::parse($fb->schedule->start_time)->format('H:i · d/m/Y') : '—';
                        $roomName  = $fb?->schedule?->room?->room_name ?? '—';
                        $bookings  = $ticket->bookings;
                    @endphp
                    <div class="ticket-card" id="t-{{ $ticket->id }}">
                        <div class="tc-header" onclick="toggleTicket('t-{{ $ticket->id }}')">
                            <div class="tc-icon"><i class="fa-solid fa-ticket"></i></div>
                            <div class="tc-body">
                                <div class="tc-code">{{ $ticket->code }}</div>
                                <div class="tc-movie">{{ $movieName }}</div>
                                <div class="tc-time">
                                    <i class="fa-regular fa-clock" style="opacity:.5;"></i> {{ $showTime }}
                                    &nbsp;·&nbsp;
                                    <i class="fa-solid fa-door-open" style="opacity:.5;"></i> {{ $roomName }}
                                </div>
                            </div>
                            <div class="tc-right">
                                <div class="tc-price">{{ number_format($ticket->final_price,0,',','.') }}đ</div>
                                <div>
                                    @if($isPaid)
                                        <span class="tc-status s-paid"><i class="fa-solid fa-circle-check" style="font-size:8px;"></i> Đã thanh toán</span>
                                    @else
                                        <span class="tc-status s-pending"><i class="fa-solid fa-clock" style="font-size:8px;"></i> Chờ thanh toán</span>
                                    @endif
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down tc-chev"></i>
                        </div>

                        <div class="tc-detail">
                            @if($bookings->isNotEmpty())
                                <div class="d-title">Ghế đã đặt</div>
                                <div class="seat-tags">
                                    @foreach($bookings as $bk)
                                        @php
                                            $sl       = ($bk->seat?->row ?? '').($bk->seat?->column ?? '');
                                            $isCouple = $bk->seat?->seatType?->is_couple ?? false;
                                        @endphp
                                        <span class="seat-tag {{ $isCouple ? 'couple' : '' }}"
                                              title="{{ $bk->seat?->seatType?->type ?? '' }}">
                                            {{ $sl ?: '—' }}
                                            @if($isCouple)<i class="fa-solid fa-heart" style="font-size:9px;"></i>@endif
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-meta-row">
                                <div>
                                    <div class="d-meta-label">Mã vé</div>
                                    <div class="d-meta-value" style="font-family:monospace;">{{ $ticket->code }}</div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Phim</div>
                                    <div class="d-meta-value">{{ $movieName }}</div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Giờ chiếu</div>
                                    <div class="d-meta-value">{{ $showTime }}</div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Phòng chiếu</div>
                                    <div class="d-meta-value">{{ $roomName }}</div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Số ghế</div>
                                    <div class="d-meta-value">{{ $bookings->count() }} ghế</div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Tổng tiền</div>
                                    <div class="d-meta-value" style="color:#fbbf24;">{{ number_format($ticket->final_price,0,',','.') }} VNĐ</div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Trạng thái</div>
                                    <div class="d-meta-value">
                                        @if($isPaid)
                                            <span style="color:#4ade80;">✓ Đã thanh toán</span>
                                        @else
                                            <span style="color:#fbbf24;">⏳ Chờ thanh toán</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="d-meta-label">Thời gian tạo</div>
                                    <div class="d-meta-value">{{ $ticket->created_at->format('H:i · d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="ticket-empty">
                <i class="fa-regular fa-ticket"></i>
                <p>Bạn chưa đặt vé nào.</p>
                <a href="{{ route('index') }}" class="btn-red-pill" style="font-size:12px;padding:8px 20px;margin-top:12px;display:inline-flex;">
                    <i class="fa-solid fa-film"></i> Xem phim ngay
                </a>
            </div>
        @endif
    </div>

</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function toggleTicket(id) {
    document.getElementById(id).classList.toggle('open');
}
</script>
@endpush
