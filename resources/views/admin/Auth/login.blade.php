<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — NETFNIX</title>
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
            overflow: hidden;
            position: relative;
        }

        /* Animated background blobs */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            animation: floatBlob 12s ease-in-out infinite;
            pointer-events: none;
        }

        .bg-blob-1 {
            width: 500px;
            height: 500px;
            background: var(--accent);
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .bg-blob-2 {
            width: 400px;
            height: 400px;
            background: #7c6dfa;
            bottom: -100px;
            right: -100px;
            animation-delay: -6s;
        }

        @keyframes floatBlob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(30px, 20px) scale(1.08);
            }
        }

        /* Card */
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
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo */
        .brand-logo {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .brand-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 13.5px;
            color: var(--muted);
            margin-top: 0.4rem;
        }

        /* Divider */
        .form-divider {
            height: 1px;
            background: var(--border);
            margin: 1.75rem 0;
        }

        /* Form elements */
        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap>i {
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

        .form-control:focus+i,
        .input-wrap:focus-within>i {
            color: var(--accent);
        }

        .form-control::placeholder {
            color: var(--muted);
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
            pointer-events: all;
        }

        .toggle-pw:hover {
            color: var(--text);
        }

        /* Submit button */
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
        }

        .btn-login:hover:not(:disabled) {
            opacity: 0.88;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(232, 201, 106, 0.25);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Spinner */
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

        /* Alert */
        .alert-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            color: #fca5a5;
            font-size: 13.5px;
            padding: 0.75rem 1rem;
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
        }

        .alert-error.show {
            display: flex;
            animation: fadeIn 0.25s ease;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 12px;
            color: #6ee7b7;
            font-size: 13.5px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Footer link */
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
    </style>
</head>

<body>
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>

    <div class="login-card">

        {{-- Brand --}}
        <div class="mb-4">
            <div class="brand-logo"><i class="fa-solid fa-clapperboard"></i> &nbsp;NETFNIX Cinema</div>
            <h1 class="brand-title">Đăng nhập</h1>
            <p class="brand-sub">Chào mừng trở lại — vui lòng xác thực tài khoản.</p>
        </div>

        <div class="form-divider"></div>

        {{-- Error alert --}}
        <div class="alert-error" id="alertError">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span id="alertMsg">Đã có lỗi xảy ra.</span>
        </div>

        {{-- Success alert --}}
        @if (session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="alert-success" id="alertRegistered" style="display: none;">
            <i class="fa-solid fa-circle-check"></i>
            <span>Đăng ký thành công! Vui lòng đăng nhập.</span>
        </div>

        {{-- Form --}}
        <form id="loginForm" novalidate>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrap">
                    <input id="email" name="email" type="email" class="form-control"
                        placeholder="you@example.com" autocomplete="email" required>
                    <i class="fa-regular fa-envelope"></i>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">Mật khẩu</label>
                <div class="input-wrap">
                    <input id="password" name="password" type="password" class="form-control" placeholder="••••••••"
                        autocomplete="current-password" required>
                    <i class="fa-solid fa-lock"></i>
                    <span class="toggle-pw" id="togglePw">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                <span id="btnText">Đăng nhập</span>
            </button>
        </form>

        <p class="footer-note">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a>
        </p>
    </div>

    <script>
        const API_BASE = '/api/auth';

        // Check for registered query param
        if (new URLSearchParams(window.location.search).has('registered')) {
            document.getElementById('alertRegistered').style.display = 'flex';
        }

        // Toggle password visibility
        document.getElementById('togglePw').addEventListener('click', function() {
            const pw = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            eye.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });

        // Show / hide error alert
        function showError(msg) {
            const el = document.getElementById('alertError');
            document.getElementById('alertMsg').textContent = msg;
            el.classList.add('show');
        }

        function hideError() {
            document.getElementById('alertError').classList.remove('show');
        }

        // Set loading state on button
        function setLoading(loading) {
            const btn = document.getElementById('btnLogin');
            const text = document.getElementById('btnText');
            btn.disabled = loading;
            if (loading) {
                text.innerHTML = '<span class="spinner"></span>';
            } else {
                text.textContent = 'Đăng nhập';
            }
        }

        // Submit
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            hideError();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                showError('Vui lòng nhập đầy đủ email và mật khẩu.');
                return;
            }

            setLoading(true);

            try {
                const res = await fetch(`${API_BASE}/process-login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        email,
                        password
                    }),
                });

                const data = await res.json();

                if (!res.ok) {
                    showError(data.message ?? 'Đăng nhập thất bại.');
                    return;
                }

                // Cookie access_token được backend set tự động qua Set-Cookie header
                // Chỉ lưu profile để hiển thị UI
                localStorage.setItem('profile', JSON.stringify(data.profile));

                // Decode JWT payload (phần thứ 2, base64url) để lấy role claim
                // JWT không mã hóa payload nên đọc được mà không cần secret
                const payload = JSON.parse(atob(data.access_token.split('.')[1].replace(/-/g, '+').replace(/_/g,
                    '/')));
                const role = payload.role; // 0=Admin, 1=Staff, 2=Customer

                // Redirect dựa trên role
                if (role === 2) {
                    // Customer → trang khách hàng
                    window.location.href = '/';
                } else {
                    // Admin (0) hoặc Staff (1) → trang quản trị
                    window.location.href = '{{ route('admin.dashboard') }}';
                }

            } catch (err) {
                showError('Không thể kết nối đến máy chủ. Vui lòng thử lại.');
            } finally {
                setLoading(false);
            }
        });
    </script>
</body>

</html>
