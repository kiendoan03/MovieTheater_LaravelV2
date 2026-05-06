<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NetFnix')</title>

    <link rel="icon" href="/img/page_logo/download-removebg-preview.png">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/scroll/hideScrollBar.css') }}">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary: #ff1f45;
            --primary-light: #ff4d6d;
            --dark: #0b0b0f;
            --card: #13131a;
            --border: rgba(255, 255, 255, .08);
            --text-soft: #b9b9c7;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(255, 31, 69, .12), transparent 25%),
                radial-gradient(circle at bottom left, rgba(255, 31, 69, .08), transparent 25%),
                var(--dark);
            color: white;
            overflow-x: hidden;
            cursor: default;
        }

        a,
        button,
        .nav-icon,
        .search-toggle,
        [role="button"] {
            cursor: pointer !important;
        }

        input,
        textarea,
        select {
            cursor: text !important;
        }

        input,
        textarea,
        [contenteditable="true"] {
            caret-color: white !important;
        }

        *:not(input):not(textarea):not(select):not([contenteditable="true"]) {
            caret-color: transparent;
        }

        *:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        ::selection {
            background: rgba(255, 31, 69, 0.25);
            color: #fff;
        }

        a {
            text-decoration: none;
        }

        .navbar-custom {
            background: rgba(8, 8, 10, 0.65);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            transition: .3s;
        }

        .brand-logo {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 3px;
            color: var(--primary) !important;
            text-shadow: 0 0 18px rgba(255, 31, 69, .45);
        }

        .nav-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: .25s;
        }

        .nav-icon:hover {
            transform: translateY(-3px);
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(255, 31, 69, .45);
        }

        footer {
            margin-top: 120px;
            padding: 60px 0;
            border-top: 1px solid rgba(255, 255, 255, .06);
            background: rgba(255, 255, 255, .015);
        }

        .search-box {
            position: relative;
        }

        .search-panel {
            position: absolute;
            top: 55px;
            right: 0;
            width: 0;
            overflow: hidden;
            opacity: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(15, 15, 20, 0.92);
            border: 1px solid rgba(255, 255, 255, .08);
            backdrop-filter: blur(18px);
            border-radius: 999px;
            padding: 0 18px;
            transition: width .35s ease, opacity .25s ease, padding .25s ease;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .35), 0 0 25px rgba(255, 31, 69, .08);
            z-index: 999;
        }

        .search-panel.active {
            width: 320px;
            opacity: 1;
            padding: 14px 18px;
        }

        .search-icon {
            color: #888;
            font-size: 14px;
        }

        .search-panel input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: white;
            font-size: 14px;
        }

        .search-panel input::placeholder {
            color: #888;
        }

        /* ── User dropdown ── */
        .user-menu-wrap {
            position: relative;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            min-width: 240px;
            background: rgba(12, 12, 18, 0.96);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .5), 0 0 30px rgba(255, 31, 69, .07);
            backdrop-filter: blur(20px);
            z-index: 9999;
            overflow: hidden;
            animation: dropIn .2s ease;
        }

        @keyframes dropIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-dropdown.show {
            display: block;
        }

        .ud-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 16px 12px;
        }

        .ud-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 31, 69, .3), rgba(255, 31, 69, .06));
            border: 1.5px solid rgba(255, 31, 69, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff4468;
            font-size: 15px;
            flex-shrink: 0;
        }

        .ud-name {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .ud-email {
            font-size: 11px;
            color: rgba(255, 255, 255, .4);
            font-family: monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .ud-divider {
            height: 1px;
            background: rgba(255, 255, 255, .07);
            margin: 0 12px;
        }

        .ud-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: rgba(255, 255, 255, .75);
            font-size: 13px;
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .ud-item:hover {
            background: rgba(255, 255, 255, .05);
            color: #fff;
        }

        .ud-item i {
            width: 16px;
            text-align: center;
            font-size: 13px;
        }

        .ud-danger {
            color: rgba(255, 31, 69, .8) !important;
        }

        .ud-danger:hover {
            background: rgba(255, 31, 69, .08) !important;
            color: #ff4468 !important;
        }
    </style>
    @stack('styles')
    <script>
        if (!sessionStorage.getItem('introPlayed')) {
            document.documentElement.classList.add('preload-intro');
        }
    </script>
</head>



<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand brand-logo" href="{{ route('home') }}"> NETFNIX </a>
            <div class="d-flex align-items-center gap-3">
                {{-- Search --}}
                <div class="search-box">
                    <button class="nav-icon search-toggle" type="button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <form action="{{ route('movies.search') }}" method="GET" class="search-panel">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" name="keyword" id="movieSearch" placeholder="Tìm kiếm phim..."
                            autocomplete="off">
                    </form>
                </div>

                <a href="#" class="nav-icon"><i class="fa-regular fa-bell"></i></a>

                {{-- User menu --}}
                @php
                    $authUser = null;
                    try {
                        $authUser = auth()->guard('api')->user();
                    } catch (\Throwable $e) {
                    }
                @endphp

                @if ($authUser && $authUser->role === \App\Enums\UserRole::Customer)
                    <div class="user-menu-wrap" style="position:relative;">
                        @php
                            $navAvatar = $authUser->customer?->avatar
                                ? asset('storage/img/avatars/' . $authUser->customer->avatar)
                                : asset('images/default-avatar.png');
                        @endphp
                        <button class="nav-icon user-menu-toggle" type="button" title="{{ $authUser->email }}"
                            style="position:relative; padding:0; width:36px; height:36px; border-radius:50%; overflow:hidden; background:transparent; border:2px solid rgba(255,31,69,.4);">
                            <img src="{{ $navAvatar }}" alt=""
                                style="width:100%;height:100%;object-fit:cover;">
                            <span
                                style="
                                position:absolute; bottom:1px; right:1px;
                                width:8px; height:8px; border-radius:50%;
                                background:#22c55e;
                                border:1.5px solid #0b0b0f;
                            "></span>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <div class="ud-header">
                                <div class="ud-avatar" style="overflow:hidden; padding:0;">
                                    <img src="{{ $navAvatar }}" alt=""
                                        style="width:100%;height:100%;object-fit:cover;">
                                </div>
                                <div>
                                    <div class="ud-name">{{ $authUser->customer?->name ?? 'Thành viên' }}</div>
                                    <div class="ud-email">{{ $authUser->email }}</div>
                                </div>
                            </div>
                            <div class="ud-divider"></div>
                            <a href="{{ route('customer.profile.show') }}" class="ud-item">
                                <i class="fa-solid fa-id-card"></i> Hồ sơ của tôi
                            </a>
                            <a href="{{ route('customer.profile.edit') }}" class="ud-item">
                                <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa hồ sơ
                            </a>
                            <div class="ud-divider"></div>
                            <a href="{{ route('logout') }}" class="ud-item ud-danger">
                                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-icon" title="Đăng nhập">
                        <i class="fa-regular fa-user"></i>
                    </a>
                @endif
            </div>
        </div>
    </nav> @yield('content') <footer>
        <div class="container text-center">
            <h3 class="fw-bold text-danger"> NETFNIX </h3>
            <p class="mt-3"> Website xem phim hiện đại với trải nghiệm điện ảnh tuyệt vời. </p>
        </div>
    </footer>
</body>

</html>
<script>
    const toggleBtn = document.querySelector('.search-toggle');
    const searchPanel = document.querySelector('.search-panel');
    const searchInput = document.getElementById('movieSearch');
    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        searchPanel.classList.toggle('active');
        if (searchPanel.classList.contains('active')) {
            searchInput.focus({
                preventScroll: true
            });
            searchInput.style.caretColor = 'white';
        }
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-box')) {
            searchPanel.classList.remove('active');
            searchInput.blur();
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js "></script>

@stack('scripts')
