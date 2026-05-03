@extends('layouts.management')
@section('content')
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

        .pw-wrap {
            max-width: 460px;
            margin: 0 auto;
            padding: 2rem 0 3rem;
        }

        .pw-card {
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

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.4rem 0;
        }

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
            padding: 0.75rem 2.75rem 0.75rem 2.75rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
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

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            transition: color 0.2s;
        }

        .toggle-pw:hover {
            color: var(--text);
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

        .alert-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 9px;
            align-items: flex-start;
        }

        .alert-error i {
            color: #ef4444;
            font-size: 14px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-error ul {
            margin: 0;
            padding: 0 0 0 1rem;
        }

        .alert-error li {
            font-size: 13px;
            color: #fca5a5;
            line-height: 1.6;
        }

        /* Password strength bar */
        .strength-bar-wrap {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            border-radius: 4px;
            background: var(--border);
            overflow: hidden;
        }

        .strength-bar-fill {
            height: 100%;
            border-radius: 4px;
            width: 0%;
            transition: width 0.3s, background 0.3s;
        }

        .strength-label {
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--muted);
            min-width: 50px;
            text-align: right;
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

        .btn-submit:hover:not(:disabled) {
            opacity: 0.88;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(232, 201, 106, 0.25);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(13, 15, 20, 0.3);
            border-top-color: #0d0f14;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="cw">
        <div class="container-fluid px-3 px-md-5">
            <div class="pw-wrap">

                <a href="javascript:history.back()" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </a>

                <div class="pw-card">

                    <div class="admin-badge">
                        <i class="fa-solid fa-shield-halved"></i> Admin Panel
                    </div>

                    <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
                    <h2 class="brand-title">Đổi mật khẩu</h2>
                    <p class="brand-sub">Cập nhật mật khẩu để bảo vệ tài khoản của bạn.</p>

                    <div class="divider"></div>

                    {{-- Flash success --}}
                    @if (session('success'))
                        <div class="flash-success">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.accounts.change-password.update') }}"
                        id="changePasswordForm" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Mật khẩu cũ --}}
                        <div class="mb-3">
                            <label class="form-label-s" for="current_password">Mật khẩu hiện tại</label>
                            <div class="input-wrap">
                                <input id="current_password" name="current_password" type="password"
                                    class="inp @error('current_password') is-invalid @enderror"
                                    placeholder="Nhập mật khẩu hiện tại" autocomplete="current-password" required>
                                <i class="fa-solid fa-lock prefix"></i>
                                <span class="toggle-pw" data-target="current_password">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                            @error('current_password')
                                <div class="err-msg show">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="divider"></div>

                        {{-- Mật khẩu mới --}}
                        <div class="mb-3">
                            <label class="form-label-s" for="new_password">Mật khẩu mới</label>
                            <div class="input-wrap">
                                <input id="new_password" name="new_password" type="password"
                                    class="inp @error('new_password') is-invalid @enderror" placeholder="Tối thiểu 8 ký tự"
                                    autocomplete="new-password" required>
                                <i class="fa-solid fa-key prefix"></i>
                                <span class="toggle-pw" data-target="new_password">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                            {{-- Thanh độ mạnh mật khẩu --}}
                            <div class="strength-bar-wrap">
                                <div class="strength-bar">
                                    <div class="strength-bar-fill" id="strengthFill"></div>
                                </div>
                                <span class="strength-label" id="strengthLabel">—</span>
                            </div>
                            @error('new_password')
                                <div class="err-msg show">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Xác nhận mật khẩu mới --}}
                        <div class="mb-1">
                            <label class="form-label-s" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                            <div class="input-wrap">
                                <input id="new_password_confirmation" name="new_password_confirmation" type="password"
                                    class="inp" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password" required>
                                <i class="fa-solid fa-shield-halved prefix"></i>
                                <span class="toggle-pw" data-target="new_password_confirmation">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                            <div class="err-msg" id="confirmErr">Mật khẩu xác nhận không khớp.</div>
                        </div>

                        <div class="btn-actions">
                            <a href="javascript:history.back()" class="btn-cancel">
                                <i class="fa-solid fa-xmark"></i> Huỷ
                            </a>
                            <button type="submit" class="btn-submit" id="btnSubmit">
                                <span id="btnText"><i class="fa-solid fa-floppy-disk"></i> Lưu mật khẩu</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle hiện/ẩn mật khẩu
        document.querySelectorAll('.toggle-pw').forEach(function(el) {
            el.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                icon.className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            });
        });

        // Đo độ mạnh mật khẩu mới
        document.getElementById('new_password').addEventListener('input', function() {
            const val = this.value;
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [{
                    pct: '0%',
                    color: 'transparent',
                    text: '—'
                },
                {
                    pct: '25%',
                    color: '#ef4444',
                    text: 'Yếu'
                },
                {
                    pct: '50%',
                    color: '#f97316',
                    text: 'Trung bình'
                },
                {
                    pct: '75%',
                    color: '#eab308',
                    text: 'Khá'
                },
                {
                    pct: '100%',
                    color: '#22c55e',
                    text: 'Mạnh'
                },
            ];

            const lv = val.length === 0 ? levels[0] : levels[score];
            fill.style.width = lv.pct;
            fill.style.background = lv.color;
            label.textContent = lv.text;
            label.style.color = lv.color === 'transparent' ? 'var(--muted)' : lv.color;
        });

        // Kiểm tra xác nhận mật khẩu realtime
        document.getElementById('new_password_confirmation').addEventListener('input', checkConfirm);
        document.getElementById('new_password').addEventListener('input', checkConfirm);

        function checkConfirm() {
            const pw = document.getElementById('new_password').value;
            const cf = document.getElementById('new_password_confirmation').value;
            const errEl = document.getElementById('confirmErr');
            if (cf.length > 0 && pw !== cf) {
                errEl.classList.add('show');
                document.getElementById('new_password_confirmation').classList.add('is-invalid');
            } else {
                errEl.classList.remove('show');
                document.getElementById('new_password_confirmation').classList.remove('is-invalid');
            }
        }

        // Loading khi submit
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const pw = document.getElementById('new_password').value;
            const cf = document.getElementById('new_password_confirmation').value;
            if (pw !== cf) {
                e.preventDefault();
                document.getElementById('confirmErr').classList.add('show');
                return;
            }

            const btn = document.getElementById('btnSubmit');
            const text = document.getElementById('btnText');
            btn.disabled = true;
            text.innerHTML = '<span class="spinner"></span>';
        });
    </script>

@endsection
