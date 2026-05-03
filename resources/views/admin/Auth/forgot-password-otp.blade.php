<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP — NETFNIX</title>
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

        /* OTP input boxes */
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 0.5rem 0 1.5rem;
        }

        .otp-box {
            width: 52px;
            height: 60px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            color: var(--text);
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            caret-color: var(--accent);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .otp-box:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232, 201, 106, 0.12);
        }

        .otp-box.filled { border-color: rgba(232, 201, 106, 0.4); }

        /* Resend timer */
        .resend-wrap {
            text-align: center;
            font-size: 13px;
            color: var(--muted);
            margin-top: 0.75rem;
        }

        .resend-wrap a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            display: none;
        }

        .resend-wrap a.visible { display: inline; }

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
        }
        .btn-login:hover:not(:disabled) { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(232,201,106,0.25); }
        .btn-login:disabled { opacity: 0.5; cursor: not-allowed; }

        .spinner { width: 16px; height: 16px; border: 2px solid rgba(13,15,20,0.3); border-top-color: #0d0f14; border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); border-radius: 12px; color: #fca5a5; font-size: 13.5px; padding: 0.75rem 1rem; display: none; align-items: center; gap: 10px; margin-bottom: 1.25rem; }
        .alert-error.show { display: flex; animation: fadeIn 0.25s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: none; }
        }

        .email-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(232,201,106,0.08);
            border: 1px solid rgba(232,201,106,0.2);
            border-radius: 20px;
            padding: 4px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--accent);
            margin-bottom: 1.25rem;
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
            <h1 class="brand-title">Xác nhận OTP</h1>
            <p class="brand-sub">Nhập mã 6 chữ số đã được gửi về email.</p>
        </div>

        <div class="step-indicator">
            <div class="step-dot done"></div>
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
        </div>

        <div class="form-divider"></div>

        <div class="alert-error" id="alertError">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span id="alertMsg">Đã có lỗi xảy ra.</span>
        </div>

        <div class="email-chip" id="emailDisplay">
            <i class="fa-regular fa-envelope"></i>
            <span id="emailText">—</span>
        </div>

        <label class="form-label">Mã OTP</label>
        <div class="otp-inputs" id="otpInputs">
            <input class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code">
            <input class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
            <input class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
        </div>

        <div class="resend-wrap">
            <span id="timerText">Gửi lại sau <strong id="countdown">60</strong>s</span>
            <a id="resendLink" onclick="resendOTP()">Gửi lại mã OTP</a>
        </div>

        <button class="btn-login" id="btnVerify" style="margin-top: 1.5rem;" onclick="verifyOTP()">
            <span id="btnText"><i class="fa-solid fa-shield-check"></i> Xác nhận</span>
        </button>

        <p class="footer-note">
            <a href="{{ route('forgot-password') }}"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
        </p>
    </div>

    <script>
        const API_BASE = '/api/auth/forgot-password';
        const params = new URLSearchParams(window.location.search);
        const email = params.get('email') || '';

        document.getElementById('emailText').textContent = email || '—';

        // OTP box navigation
        const boxes = Array.from(document.querySelectorAll('.otp-box'));

        boxes.forEach((box, i) => {
            box.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
                if (this.value) {
                    this.classList.add('filled');
                    if (i < boxes.length - 1) boxes[i + 1].focus();
                } else {
                    this.classList.remove('filled');
                }
            });

            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && i > 0) {
                    boxes[i - 1].focus();
                }
            });

            box.addEventListener('paste', function(e) {
                e.preventDefault();
                const text = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                boxes.forEach((b, j) => {
                    b.value = text[j] || '';
                    b.classList.toggle('filled', !!b.value);
                });
                const nextEmpty = boxes.findIndex(b => !b.value);
                (nextEmpty === -1 ? boxes[5] : boxes[nextEmpty]).focus();
            });
        });

        boxes[0].focus();

        // Countdown timer
        let seconds = 60;
        const countdownEl = document.getElementById('countdown');
        const timerText = document.getElementById('timerText');
        const resendLink = document.getElementById('resendLink');

        const timer = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                timerText.style.display = 'none';
                resendLink.classList.add('visible');
            }
        }, 1000);

        function getOTP() {
            return boxes.map(b => b.value).join('');
        }

        function showError(msg) {
            document.getElementById('alertMsg').textContent = msg;
            document.getElementById('alertError').classList.add('show');
        }

        function setLoading(loading) {
            const btn = document.getElementById('btnVerify');
            const text = document.getElementById('btnText');
            btn.disabled = loading;
            text.innerHTML = loading
                ? '<span class="spinner"></span>'
                : '<i class="fa-solid fa-shield-check"></i> Xác nhận';
        }

        async function verifyOTP() {
            document.getElementById('alertError').classList.remove('show');
            const otp = getOTP();

            if (otp.length < 6) {
                showError('Vui lòng nhập đầy đủ 6 chữ số OTP.');
                return;
            }

            setLoading(true);

            try {
                const res = await fetch(`${API_BASE}/verify-otp`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, otp }),
                });

                const data = await res.json();

                if (!res.ok) {
                    showError(data.message ?? 'Xác nhận OTP thất bại.');
                    return;
                }

                // Chuyển sang trang đặt mật khẩu mới
                window.location.href = '{{ route('forgot-password.reset') }}?email=' + encodeURIComponent(email);

            } catch (err) {
                showError('Không thể kết nối đến máy chủ. Vui lòng thử lại.');
            } finally {
                setLoading(false);
            }
        }

        async function resendOTP() {
            try {
                await fetch(`${API_BASE}/send-otp`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email }),
                });
                // Reset timer
                seconds = 60;
                countdownEl.textContent = seconds;
                timerText.style.display = '';
                resendLink.classList.remove('visible');
                const newTimer = setInterval(() => {
                    seconds--;
                    countdownEl.textContent = seconds;
                    if (seconds <= 0) { clearInterval(newTimer); timerText.style.display = 'none'; resendLink.classList.add('visible'); }
                }, 1000);
            } catch (e) {
                showError('Không thể gửi lại OTP. Vui lòng thử lại.');
            }
        }
    </script>
</body>
</html>
