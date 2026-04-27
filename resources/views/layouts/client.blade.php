<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NetFnix')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container"> <a class="navbar-brand brand-logo" href="{{ route('home') }}"> NETFNIX </a>
            <div class="d-flex align-items-center gap-3">
                <div class="search-box"> <button class="nav-icon search-toggle" type="button"> <i class="fa-solid fa-magnifying-glass"></i> </button>
                    <form action="{{ route('movies.search') }}" method="GET" class="search-panel"> <i class="fa-solid fa-magnifying-glass search-icon"></i> <input type="text" name="keyword" id="movieSearch" placeholder="Tìm kiếm phim..." autocomplete="off"> </form>
                </div> <a href="#" class="nav-icon"> <i class="fa-regular fa-bell"></i> </a>
                <div class="nav-icon"> <i class="fa-regular fa-user"></i> </div>
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
</script> @stack('scripts')