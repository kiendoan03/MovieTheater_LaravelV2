<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu — NETFNIX</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@400;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
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
            --danger: #ef4444;
            --success: #22c55e;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .bg-blob { position: fixed; border-radius: 50%; filter: blur(80px); opacity: 0.12; animation: floatBlob 12s ease-in-out infinite; pointer-events: none; }
        .bg-blob-1 { width: 500px; height: 500px; background: var(--accent); top: -150px; left: -150px; }
        .bg-blob-2 { width: 400px; height: 400px; background: #7c6dfa; bottom: -100px; right: -100px; animation-delay: -6s; }

        @keyframes floatBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 20px) scale(1.08); }
        }

        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.75rem 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo { font-size: 11px; font-weight: 500; letter-spacing: 0.25em; text-transform: uppercase; color: var(--accent); margin-bottom: 0.5rem; }
        .brand-title { font-size: 1.75rem; font-weight: 700; color: var(--text); line-height: 1.2; }
        .brand-sub { font-size: 13.5px; color: var(--muted); margin-top: 0.4rem; }
        .form-divider { height: 1px; background: var(--border); margin: 1.75rem 0; }

        .step-indicator { display: flex; align-items: center; gap: 6px; margin-bottom: 1.5rem; }
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--border); transition: background 0.3s, transform 0.3s; }
        .step-dot.active { background: var(--accent); transform: scale(1.3); }
        .step-dot.done { background: rgba(232,201,106,0.4); }

        .form-label { font-size: 12.5px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.5rem; display: block; }

        .input-wrap { position: relative; }

        .input-wrap > i.prefix {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-wrap:focus-within > i.prefix { color: var(--accent); }

        .form-control {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            padding: 0.75rem 2.75rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(232,201,106,0.12); background: var(--surface); color: var(--text); }
        .form-control::placeholder { color: var(--muted); }

        .toggle-pw {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--text); }

        /* Password strength bar */
        .strength-bar-wrap { margin-top: 8px; display: flex; align-items: center; gap: 8px; }
        .strength-bar { flex: 1; height: 4px; border-radius: 4px; background: var(--border); overflow: hidden; }
        .strength-bar-fill { height: 100%; border-radius: 4px; width: 0%; transition: width 0.3s, background 0.3s; }
        .strength-label { font-size: 11px; font-family: 'JetBrains Mono', monospace; color: var(--muted); min-width: 60px; text-align: right; }

        .err-msg { font-size: 12px; color: #fca5a5; margin-top: 5px; display: none; }
        .err-msg.show { display: block; }

        .btn-login {
            width: 100%;
            background: var(--accent);
            color: #0d0f14;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            padding: 0.8rem;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 1.5rem;
        }
        .btn-login:hover:not(:disabled) { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(232,201,106,0.25); }
        .btn-login:disabled { opacity: 0.5; cursor: not-allowed; }

        .spinner { width: 16px; height: 16px; border: 2px solid rgba(13,15,20,0.3); border-top-color: #0d0f14; border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); border-radius: 12px; color: #fca5a5; font-size: 13.5px; padding: 0.75rem 1rem; display: none; align-items: center; gap: 10px; margin-bottom: 1.25rem; }
        .alert-error.show { display: flex; animation: fadeIn 0.25s ease; }

        .alert-success { background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25); border-radius: 12px; color: #86efac; font-size: 13.5px; padding: 0.75rem 1rem; display: none; align-items: center; gap: 10px; margin-bottom: 1.25rem; }
        .alert-success.show { display: flex; animation: fadeIn 0.25s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: none; }
        }

        .footer-note { text-align: center; font-size: 13px; color: var(--muted); margin-top: 1.5rem; }
        .footer-note a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .footer-note a:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>

    <div class="login-card">

        <div class="mb-4">
            <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
            <h1 class="brand-title">Đặt lại mật khẩu</h1>
            <p class="brand-sub">Tạo mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        <div class="step-indicator">
            <div class="step-dot done"></div>
            <div class="step-dot done"></div>
            <div class="step-dot active"></div>
        </div>

        <div class="form-divider"></div>

        <div class="alert-error" id="alertError">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span id="alertMsg">Đã có lỗi xảy ra.</span>
        </div>

        <div class="alert-success" id="alertSuccess">
            <i class="fa-solid fa-circle-check"></i>
            <span id="successMsg"></span>
        </div>

        <form id="resetForm" novalidate>
            {{-- Mật khẩu mới --}}
            <div class="mb-3">
                <label class="form-label" for="new_password">Mật khẩu mới</label>
                <div class="input-wrap">
                    <input id="new_password" name="new_password" type="password"
                        class="form-control" placeholder="Tối thiểu 8 ký tự" autocomplete="new-password" required>
                    <i class="fa-solid fa-key prefix"></i>
                    <span class="toggle-pw" data-target="new_password">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                {{-- Thanh độ mạnh --}}
                <div class="strength-bar-wrap">
                    <div class="strength-bar">
                        <div class="strength-bar-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-label" id="strengthLabel">—</span>
                </div>
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="mb-3">
                <label class="form-label" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                <div class="input-wrap">
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password"
                        class="form-control" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password" required>
                    <i class="fa-solid fa-shield-halved prefix"></i>
                    <span class="toggle-pw" data-target="new_password_confirmation">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                <div class="err-msg" id="confirmErr">Mật khẩu xác nhận không khớp.</div>
            </div>

            <button type="submit" class="btn-login" id="btnReset">
                <span id="btnText"><i class="fa-solid fa-floppy-disk"></i> Đặt lại mật khẩu</span>
            </button>
        </form>

        <p class="footer-note">
            <a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Về trang đăng nhập</a>
        </p>
    </div>

    <script>
        const API_BASE = '/api/auth/forgot-password';
        const email = new URLSearchParams(window.location.search).get('email') || '';

        // Toggle hiện/ẩn mật khẩu
        document.querySelectorAll('.toggle-pw').forEach(function(el) {
            el.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            });
        });

        // Độ mạnh mật khẩu
        document.getElementById('new_password').addEventListener('input', function() {
            const val = this.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [
                { pct: '0%', color: 'transparent', text: '—' },
                { pct: '25%', color: '#ef4444', text: 'Yếu' },
                { pct: '50%', color: '#f97316', text: 'Trung bình' },
                { pct: '75%', color: '#eab308', text: 'Khá' },
                { pct: '100%', color: '#22c55e', text: 'Mạnh' },
            ];

            const lv = val.length === 0 ? levels[0] : levels[score];
            document.getElementById('strengthFill').style.width = lv.pct;
            document.getElementById('strengthFill').style.background = lv.color;
            document.getElementById('strengthLabel').textContent = lv.text;
            document.getElementById('strengthLabel').style.color = lv.color === 'transparent' ? 'var(--muted)' : lv.color;

            checkConfirm();
        });

        // Kiểm tra realtime
        document.getElementById('new_password_confirmation').addEventListener('input', checkConfirm);

        function checkConfirm() {
            const pw = document.getElementById('new_password').value;
            const cf = document.getElementById('new_password_confirmation').value;
            const errEl = document.getElementById('confirmErr');
            if (cf.length > 0 && pw !== cf) {
                errEl.classList.add('show');
            } else {
                errEl.classList.remove('show');
            }
        }

        function showError(msg) {
            document.getElementById('alertSuccess').classList.remove('show');
            document.getElementById('alertMsg').textContent = msg;
            document.getElementById('alertError').classList.add('show');
        }

        function showSuccess(msg) {
            document.getElementById('alertError').classList.remove('show');
            document.getElementById('successMsg').textContent = msg;
            document.getElementById('alertSuccess').classList.add('show');
        }

        function setLoading(loading) {
            const btn = document.getElementById('btnReset');
            const text = document.getElementById('btnText');
            btn.disabled = loading;
            text.innerHTML = loading
                ? '<span class="spinner"></span>'
                : '<i class="fa-solid fa-floppy-disk"></i> Đặt lại mật khẩu';
        }

        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            document.getElementById('alertError').classList.remove('show');

            const new_password = document.getElementById('new_password').value;
            const new_password_confirmation = document.getElementById('new_password_confirmation').value;

            if (!new_password || new_password.length < 8) {
                showError('Mật khẩu phải có ít nhất 8 ký tự.');
                return;
            }

            if (new_password !== new_password_confirmation) {
                showError('Mật khẩu xác nhận không khớp.');
                return;
            }

            if (!email) {
                showError('Không tìm thấy thông tin email. Vui lòng thực hiện lại từ đầu.');
                return;
            }

            setLoading(true);

            try {
                const res = await fetch(`${API_BASE}/reset`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, new_password, new_password_confirmation }),
                });

                const data = await res.json();

                if (!res.ok) {
                    showError(data.message ?? 'Đặt lại mật khẩu thất bại.');
                    return;
                }

                showSuccess(data.message + ' Đang chuyển hướng...');

                setTimeout(() => {
                    window.location.href = '{{ route('login') }}?reset=1';
                }, 2000);

            } catch (err) {
                showError('Không thể kết nối đến máy chủ. Vui lòng thử lại.');
            } finally {
                setLoading(false);
            }
        });
    </script>
</body>
</html>
