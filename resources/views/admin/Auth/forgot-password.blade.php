<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu — NETFNIX</title>
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

        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            animation: floatBlob 12s ease-in-out infinite;
            pointer-events: none;
        }
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

        .brand-logo {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .brand-title { font-size: 1.75rem; font-weight: 700; color: var(--text); line-height: 1.2; }
        .brand-sub { font-size: 13.5px; color: var(--muted); margin-top: 0.4rem; }

        .form-divider { height: 1px; background: var(--border); margin: 1.75rem 0; }

        /* Step indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1.5rem;
        }
        .step-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--border);
            transition: background 0.3s, transform 0.3s;
        }
        .step-dot.active { background: var(--accent); transform: scale(1.3); }
        .step-dot.done { background: rgba(232,201,106,0.4); }

        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-wrap { position: relative; }

        .input-wrap > i {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrap:focus-within > i { color: var(--accent); }

        .form-control {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232, 201, 106, 0.12);
            background: var(--surface);
            color: var(--text);
        }
        .form-control::placeholder { color: var(--muted); }

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
            letter-spacing: 0.03em;
            margin-top: 1.25rem;
        }
        .btn-login:hover:not(:disabled) { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(232,201,106,0.25); }
        .btn-login:disabled { opacity: 0.5; cursor: not-allowed; }

        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(13,15,20,0.3);
            border-top-color: #0d0f14;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 12px;
            color: #fca5a5;
            font-size: 13.5px;
            padding: 0.75rem 1rem;
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
        }
        .alert-error.show { display: flex; animation: fadeIn 0.25s ease; }

        .alert-success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 12px;
            color: #6ee7b7;
            font-size: 13.5px;
            padding: 0.75rem 1rem;
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
        }
        .alert-success.show { display: flex; animation: fadeIn 0.25s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: none; }
        }

        .footer-note { text-align: center; font-size: 13px; color: var(--muted); margin-top: 1.5rem; }
        .footer-note a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .footer-note a:hover { text-decoration: underline; }

        .hint-text {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 1.25rem;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>

    <div class="login-card">

        <div class="mb-4">
            <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
            <h1 class="brand-title">Quên mật khẩu</h1>
            <p class="brand-sub">Chúng tôi sẽ gửi mã OTP về email của bạn.</p>
        </div>

        {{-- Step indicator --}}
        <div class="step-indicator">
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
            <div class="step-dot"></div>
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

        <p class="hint-text">Nhập địa chỉ email đã đăng ký tài khoản. Mã OTP sẽ có hiệu lực trong 5 phút.</p>

        <form id="forgotForm" novalidate>
            <div class="mb-3">
                <label class="form-label" for="email">Địa chỉ Email</label>
                <div class="input-wrap">
                    <input id="email" name="email" type="email" class="form-control"
                        placeholder="you@example.com" autocomplete="email" required>
                    <i class="fa-regular fa-envelope"></i>
                </div>
            </div>

            <button type="submit" class="btn-login" id="btnSend">
                <span id="btnText"><i class="fa-solid fa-paper-plane"></i> Gửi mã OTP</span>
            </button>
        </form>

        <p class="footer-note">
            Nhớ mật khẩu rồi? <a href="{{ route('login') }}">Đăng nhập</a>
        </p>
    </div>

    <script>
        const API_BASE = '/api/auth/forgot-password';

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
            const btn = document.getElementById('btnSend');
            const text = document.getElementById('btnText');
            btn.disabled = loading;
            text.innerHTML = loading
                ? '<span class="spinner"></span>'
                : '<i class="fa-solid fa-paper-plane"></i> Gửi mã OTP';
        }

        document.getElementById('forgotForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value.trim();

            if (!email) {
                showError('Vui lòng nhập địa chỉ email.');
                return;
            }

            setLoading(true);

            try {
                const res = await fetch(`${API_BASE}/send-otp`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email }),
                });

                const data = await res.json();

                if (!res.ok) {
                    showError(data.message ?? 'Gửi OTP thất bại.');
                    return;
                }

                showSuccess(data.message);

                // Chuyển sang trang nhập OTP sau 1.5s
                setTimeout(() => {
                    window.location.href = '{{ route('forgot-password.otp') }}?email=' + encodeURIComponent(email);
                }, 1500);

            } catch (err) {
                showError('Không thể kết nối đến máy chủ. Vui lòng thử lại.');
            } finally {
                setLoading(false);
            }
        });
    </script>
</body>
</html>
