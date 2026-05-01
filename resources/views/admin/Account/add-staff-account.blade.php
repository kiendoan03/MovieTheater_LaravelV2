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
        }

        .staff-wrap {
            max-width: 560px;
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

        /* Back */
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

        /* Brand */
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

        /* Info box */
        .info-box {
            background: rgba(232, 201, 106, 0.06);
            border: 1px solid rgba(232, 201, 106, 0.18);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }

        .info-box i {
            color: var(--accent);
            font-size: 14px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        .info-box p {
            font-size: 12.5px;
            color: #c9a83c;
            line-height: 1.55;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .info-box code {
            background: rgba(232, 201, 106, 0.15);
            border-radius: 5px;
            padding: 1px 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--accent);
        }

        /* Form */
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

        /* Laravel validation errors */
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

        /* Success flash */
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

        /* Submit */
        .btn-submit {
            width: 100%;
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
            margin-top: 1.75rem;
        }

        .btn-submit:hover {
            opacity: 0.88;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(232, 201, 106, 0.25);
        }
    </style>

    <div class="cw">
        <div class="container-fluid px-3 px-md-5">
            <div class="staff-wrap">

                <a href="{{ route('admin.accounts.staff.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Danh sách nhân viên
                </a>

                <div class="staff-card">

                    <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
                    <h2 class="brand-title">Thêm nhân viên</h2>

                    <div class="divider"></div>

                    {{-- Flash success --}}
                    @if (session('success'))
                        <div class="flash-success">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Laravel validation errors --}}
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

                    {{-- Default password notice --}}
                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>Mật khẩu mặc định của nhân viên là <code>Abc@123456</code>. Nhân viên nên đổi mật khẩu sau lần
                            đăng nhập đầu tiên.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.accounts.staff.store') }}" novalidate>
                        @csrf

                        <div class="section-label">Thông tin tài khoản</div>

                        <div class="mb-3">
                            <label class="form-label-s" for="staff-email">Địa chỉ Email</label>
                            <div class="input-wrap">
                                <input id="staff-email" name="email" type="email"
                                    class="inp @error('email') is-invalid @enderror" placeholder="nhanvien@netfnix.com"
                                    value="{{ old('email') }}" autocomplete="off">
                                <i class="fa-regular fa-envelope prefix"></i>
                            </div>
                            @error('email')
                                <div class="err-msg show">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="divider"></div>
                        <div class="section-label">Thông tin cá nhân</div>

                        <div class="mb-3">
                            <label class="form-label-s" for="staff-name">Họ và tên</label>
                            <div class="input-wrap">
                                <input id="staff-name" name="name" type="text"
                                    class="inp @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A"
                                    value="{{ old('name') }}" autocomplete="off">
                                <i class="fa-regular fa-user prefix"></i>
                            </div>
                            @error('name')
                                <div class="err-msg show">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row-2 mb-3">
                            <div>
                                <label class="form-label-s" for="staff-phone">Số điện thoại</label>
                                <div class="input-wrap">
                                    <input id="staff-phone" name="phonenumber" type="tel"
                                        class="inp @error('phonenumber') is-invalid @enderror" placeholder="0987 xxx xxx"
                                        value="{{ old('phonenumber') }}">
                                    <i class="fa-solid fa-phone prefix"></i>
                                </div>
                                @error('phonenumber')
                                    <div class="err-msg show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label-s" for="staff-dob">Ngày sinh</label>
                                <input id="staff-dob" name="date_of_birth" type="date"
                                    class="inp no-icon @error('date_of_birth') is-invalid @enderror"
                                    style="color-scheme: dark;" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="err-msg show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label-s" for="staff-address">
                                Địa chỉ <span style="color:var(--muted);font-size:10px;">(tuỳ chọn)</span>
                            </label>
                            <div class="input-wrap">
                                <input id="staff-address" name="address" type="text" class="inp"
                                    placeholder="123 Đường ABC, Hà Nội" value="{{ old('address') }}">
                                <i class="fa-solid fa-location-dot prefix"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-user-plus"></i>
                            Tạo tài khoản nhân viên
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
