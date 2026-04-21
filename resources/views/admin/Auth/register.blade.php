<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — NETFNIX</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@400;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Blobs */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.10;
            animation: floatBlob 14s ease-in-out infinite;
            pointer-events: none;
        }

        .bg-blob-1 {
            width: 500px;
            height: 500px;
            background: var(--accent);
            top: -150px;
            left: -150px;
        }

        .bg-blob-2 {
            width: 350px;
            height: 350px;
            background: #7c6dfa;
            bottom: -80px;
            right: -80px;
            animation-delay: -7s;
        }

        @keyframes floatBlob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(25px, 15px) scale(1.07);
            }
        }

        /* Card */
        .register-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Brand */
        .brand-logo {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.4rem;
        }

        .brand-title {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 0.35rem;
        }

        /* Step indicator */
        .steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin: 1.5rem 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 14px;
            left: 50%;
            width: 100%;
            height: 1px;
            background: var(--border);
            z-index: 0;
            transition: background 0.4s;
        }

        .step-item.done:not(:last-child)::after {
            background: var(--success);
        }

        .step-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            transition: all 0.3s;
        }

        .step-item.active .step-circle {
            border-color: var(--accent);
            background: var(--accent-bg);
            color: var(--accent);
        }

        .step-item.done .step-circle {
            border-color: var(--success);
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
        }

        .step-label {
            font-size: 10px;
            color: var(--muted);
            margin-top: 5px;
            letter-spacing: 0.04em;
            font-weight: 500;
        }

        .step-item.active .step-label {
            color: var(--accent);
        }

        .step-item.done .step-label {
            color: var(--success);
        }

        /* Form */
        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.45rem;
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

        .form-control {
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

        .form-control.no-icon {
            padding-left: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232, 201, 106, 0.12);
            background: var(--surface);
            color: var(--text);
        }

        .input-wrap:focus-within>i.prefix {
            color: var(--accent);
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        .form-control.is-valid {
            border-color: var(--success);
        }

        /* OTP input group */
        .otp-group {
            display: flex;
            gap: 0.5rem;
        }

        .otp-group .form-control {
            flex: 1;
            text-align: center;
            letter-spacing: 0.15em;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0.7rem 0.5rem;
        }

        .otp-group .btn-otp-send {
            white-space: nowrap;
            background: var(--accent-bg);
            border: 1px solid rgba(232, 201, 106, 0.25);
            border-radius: 12px;
            color: var(--accent);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 0 1rem;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .otp-group .btn-otp-send:hover:not(:disabled) {
            background: rgba(232, 201, 106, 0.18);
        }

        .otp-group .btn-otp-send:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Countdown */
        .otp-hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 0.4rem;
            min-height: 18px;
        }

        .otp-hint .countdown {
            color: var(--accent);
            font-weight: 600;
        }

        /* Toggle password */
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

        /* Submit button */
        .btn-submit {
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

        .btn-submit:hover:not(:disabled) {
            opacity: 0.88;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(232, 201, 106, 0.25);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Spinner */
        .spinner {
            width: 15px;
            height: 15px;
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

        /* Alert */
        .alert-msg {
            border-radius: 12px;
            font-size: 13px;
            padding: 0.65rem 1rem;
            display: none;
            align-items: center;
            gap: 9px;
            margin-bottom: 1rem;
        }

        .alert-msg.error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }

        .alert-msg.success {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #86efac;
        }

        .alert-msg.show {
            display: flex;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Step panels */
        .step-panel {
            display: none;
        }

        .step-panel.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        /* Footer */
        .footer-note {
            text-align: center;
            font-size: 13px;
            color: var(--muted);
            margin-top: 1.5rem;
        }

        .footer-note a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 0;
        }

        /* Two-column grid */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>

    <div class="register-card">

        {{-- Brand --}}
        <div class="mb-1">
            <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
            <h1 class="brand-title">Tạo tài khoản</h1>
            <p class="brand-sub">Xác thực email trước, sau đó điền thông tin.</p>
        </div>

        {{-- Step Indicator --}}
        <div class="steps" id="stepIndicator">
            <div class="step-item active" id="si-1">
                <div class="step-circle" id="sc-1">1</div>
                <div class="step-label">Email</div>
            </div>
            <div class="step-item" id="si-2">
                <div class="step-circle" id="sc-2">2</div>
                <div class="step-label">OTP</div>
            </div>
            <div class="step-item" id="si-3">
                <div class="step-circle" id="sc-3">3</div>
                <div class="step-label">Thông tin</div>
            </div>
        </div>

        {{-- Alert --}}
        <div class="alert-msg error" id="alertError"><i class="fa-solid fa-circle-exclamation"></i><span
                id="alertErrMsg"></span></div>
        <div class="alert-msg success" id="alertSuccess"><i class="fa-solid fa-circle-check"></i><span
                id="alertOkMsg"></span></div>

        {{-- STEP 1: Email + Gửi OTP --}}
        <div class="step-panel active" id="panel-1">
            <div class="mb-3">
                <label class="form-label" for="reg-email">Địa chỉ Email</label>
                <div class="input-wrap">
                    <input id="reg-email" type="email" class="form-control" placeholder="you@example.com"
                        autocomplete="email">
                    <i class="fa-regular fa-envelope prefix"></i>
                </div>
            </div>
            <button class="btn-submit" id="btnSendOtp">
                <span id="btnSendOtpText">Gửi mã OTP</span>
            </button>
        </div>

        {{-- STEP 2: Nhập OTP --}}
        <div class="step-panel" id="panel-2">
            <p style="font-size:13.5px; color:var(--muted); margin-bottom:1rem;">
                Mã OTP đã gửi đến <strong id="emailDisplay" style="color:var(--text)"></strong>. Hiệu lực <strong
                    style="color:var(--accent)">5 phút</strong>.
            </p>
            <div class="mb-3">
                <label class="form-label" for="reg-otp">Mã OTP</label>
                <div class="otp-group">
                    <input id="reg-otp" type="text" class="form-control" placeholder="• • • • • •" maxlength="6"
                        inputmode="numeric">
                    <button class="btn-otp-send" id="btnResendOtp" disabled>Gửi lại</button>
                </div>
                <div class="otp-hint" id="otpHint"></div>
            </div>
            <button class="btn-submit" id="btnVerifyOtp">
                <span id="btnVerifyOtpText">Xác thực OTP</span>
            </button>
        </div>

        {{-- STEP 3: Form điền thông tin --}}
        <div class="step-panel" id="panel-3">
            <div class="mb-3">
                <label class="form-label" for="reg-name">Họ và tên</label>
                <div class="input-wrap">
                    <input id="reg-name" type="text" class="form-control" placeholder="Nguyễn Văn A"
                        autocomplete="name">
                    <i class="fa-regular fa-user prefix"></i>
                </div>
            </div>

            <div class="form-row mb-3">
                <div>
                    <label class="form-label" for="reg-phone">Số điện thoại</label>
                    <div class="input-wrap">
                        <input id="reg-phone" type="tel" class="form-control" placeholder="0987 xxx xxx">
                        <i class="fa-solid fa-phone prefix"></i>
                    </div>
                </div>
                <div>
                    <label class="form-label" for="reg-dob">Ngày sinh</label>
                    <input id="reg-dob" type="date" class="form-control no-icon" style="color-scheme: dark;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="reg-address">Địa chỉ <span
                        style="color:var(--muted);font-size:10px;">(tuỳ chọn)</span></label>
                <div class="input-wrap">
                    <input id="reg-address" type="text" class="form-control" placeholder="123 Đường ABC, Hà Nội">
                    <i class="fa-solid fa-location-dot prefix"></i>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="reg-password">Mật khẩu</label>
                <div class="input-wrap">
                    <input id="reg-password" type="password" class="form-control" placeholder="Tối thiểu 6 ký tự">
                    <i class="fa-solid fa-lock prefix"></i>
                    <span class="toggle-pw" id="togglePw1"><i class="fa-regular fa-eye" id="eye1"></i></span>
                </div>
            </div>

            <div class="mb-1">
                <label class="form-label" for="reg-password-confirm">Xác nhận mật khẩu</label>
                <div class="input-wrap">
                    <input id="reg-password-confirm" type="password" class="form-control"
                        placeholder="Nhập lại mật khẩu">
                    <i class="fa-solid fa-shield-halved prefix"></i>
                    <span class="toggle-pw" id="togglePw2"><i class="fa-regular fa-eye" id="eye2"></i></span>
                </div>
            </div>

            <button class="btn-submit" id="btnRegister">
                <span id="btnRegisterText">Tạo tài khoản</span>
            </button>
        </div>

        <p class="footer-note">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
        </p>
    </div>

    <script>
        const API = '/api/auth';
        let verifiedEmail = '';
        let resendTimer = null;

        /* ---- Helpers ---- */
        function showErr(msg) {
            const el = document.getElementById('alertError');
            document.getElementById('alertErrMsg').textContent = msg;
            el.classList.add('show');
            document.getElementById('alertSuccess').classList.remove('show');
        }

        function showOk(msg) {
            const el = document.getElementById('alertSuccess');
            document.getElementById('alertOkMsg').textContent = msg;
            el.classList.add('show');
            document.getElementById('alertError').classList.remove('show');
        }

        function clearAlerts() {
            document.getElementById('alertError').classList.remove('show');
            document.getElementById('alertSuccess').classList.remove('show');
        }

        function setLoading(btnId, textId, loading, label) {
            const btn = document.getElementById(btnId);
            const txt = document.getElementById(textId);
            btn.disabled = loading;
            txt.innerHTML = loading ? '<span class="spinner"></span>' : label;
        }

        function goToStep(n) {
            [1, 2, 3].forEach(i => {
                document.getElementById(`panel-${i}`).classList.toggle('active', i === n);
                const si = document.getElementById(`si-${i}`);
                si.classList.toggle('active', i === n);
                si.classList.toggle('done', i < n);
                const sc = document.getElementById(`sc-${i}`);
                sc.innerHTML = i < n ? '<i class="fa-solid fa-check" style="font-size:10px"></i>' : i;
            });
            clearAlerts();
        }

        /* ---- Countdown cho nút gửi lại OTP ---- */
        function startResendCountdown(seconds = 60) {
            const btn = document.getElementById('btnResendOtp');
            const hint = document.getElementById('otpHint');
            btn.disabled = true;
            let s = seconds;
            clearInterval(resendTimer);
            resendTimer = setInterval(() => {
                hint.innerHTML = `Gửi lại sau <span class="countdown">${s}s</span>`;
                s--;
                if (s < 0) {
                    clearInterval(resendTimer);
                    btn.disabled = false;
                    hint.textContent = 'Bạn có thể yêu cầu gửi lại mã.';
                }
            }, 1000);
        }

        /* ---- Toggle password ---- */
        function initToggle(toggleId, inputId, eyeId) {
            document.getElementById(toggleId).addEventListener('click', () => {
                const inp = document.getElementById(inputId);
                const eye = document.getElementById(eyeId);
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                eye.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            });
        }
        initToggle('togglePw1', 'reg-password', 'eye1');
        initToggle('togglePw2', 'reg-password-confirm', 'eye2');

        /* ---- STEP 1: Gửi OTP ---- */
        async function sendOtp(email) {
            const res = await fetch(`${API}/send-otp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email
                }),
            });
            return {
                ok: res.ok,
                data: await res.json()
            };
        }

        document.getElementById('btnSendOtp').addEventListener('click', async () => {
            clearAlerts();
            const email = document.getElementById('reg-email').value.trim();
            if (!email) {
                showErr('Vui lòng nhập địa chỉ email.');
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showErr('Email không hợp lệ.');
                return;
            }

            setLoading('btnSendOtp', 'btnSendOtpText', true, 'Gửi mã OTP');
            const {
                ok,
                data
            } = await sendOtp(email).finally(() =>
                setLoading('btnSendOtp', 'btnSendOtpText', false, 'Gửi mã OTP')
            );

            if (!ok) {
                showErr(data.message ?? 'Không thể gửi OTP, thử lại.');
                return;
            }

            verifiedEmail = email;
            document.getElementById('emailDisplay').textContent = email;
            showOk('Mã OTP đã gửi về email của bạn.');
            goToStep(2);
            startResendCountdown(60);
        });

        /* ---- Gửi lại OTP ---- */
        document.getElementById('btnResendOtp').addEventListener('click', async () => {
            clearAlerts();
            const {
                ok,
                data
            } = await sendOtp(verifiedEmail);
            if (!ok) {
                showErr(data.message ?? 'Không thể gửi lại OTP.');
                return;
            }
            showOk('Đã gửi lại mã OTP.');
            startResendCountdown(60);
        });

        /* ---- STEP 2: Xác thực OTP ---- */
        document.getElementById('btnVerifyOtp').addEventListener('click', async () => {
            clearAlerts();
            const otp = document.getElementById('reg-otp').value.trim();
            if (otp.length !== 6) {
                showErr('OTP phải đủ 6 chữ số.');
                return;
            }

            setLoading('btnVerifyOtp', 'btnVerifyOtpText', true, 'Xác thực OTP');
            const res = await fetch(`${API}/verify-otp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: verifiedEmail,
                    otp
                }),
            });
            const data = await res.json();
            setLoading('btnVerifyOtp', 'btnVerifyOtpText', false, 'Xác thực OTP');

            if (!res.ok) {
                showErr(data.message ?? 'OTP không đúng.');
                return;
            }

            clearInterval(resendTimer);
            showOk('Email đã được xác thực!');
            goToStep(3);
        });

        /* ---- STEP 3: Đăng ký ---- */
        document.getElementById('btnRegister').addEventListener('click', async () => {
            clearAlerts();
            const name = document.getElementById('reg-name').value.trim();
            const phone = document.getElementById('reg-phone').value.trim();
            const dob = document.getElementById('reg-dob').value;
            const address = document.getElementById('reg-address').value.trim();
            const password = document.getElementById('reg-password').value;
            const confirm = document.getElementById('reg-password-confirm').value;

            if (!name) {
                showErr('Vui lòng nhập họ tên.');
                return;
            }
            if (!phone) {
                showErr('Vui lòng nhập số điện thoại.');
                return;
            }
            if (!dob) {
                showErr('Vui lòng chọn ngày sinh.');
                return;
            }
            if (password.length < 6) {
                showErr('Mật khẩu phải có ít nhất 6 ký tự.');
                return;
            }
            if (password !== confirm) {
                showErr('Mật khẩu xác nhận không khớp.');
                return;
            }

            setLoading('btnRegister', 'btnRegisterText', true, 'Tạo tài khoản');

            const res = await fetch(`${API}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    email: verifiedEmail,
                    password,
                    password_confirmation: confirm,
                    name,
                    phonenumber: phone,
                    address: address || null,
                    date_of_birth: dob,
                }),
            });
            const data = await res.json();
            setLoading('btnRegister', 'btnRegisterText', false, 'Tạo tài khoản');

            if (!res.ok) {
                showErr(data.message ?? 'Đăng ký thất bại.');
                return;
            }

            // Lưu profile, cookie set bởi backend
            localStorage.setItem('profile', JSON.stringify(data.profile));

            // Customer mới đăng ký → redirect về login
            window.location.href = '{{ route('login') }}?registered=1';
        });
    </script>
</body>

</html>
