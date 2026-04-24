<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NetFnix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

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


        .hero {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;

            background:
                linear-gradient(to top,
                    rgba(11, 11, 15, 1) 6%,
                    rgba(11, 11, 15, .2) 45%,
                    rgba(0, 0, 0, .75) 100%);
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(.35);
            transform: scale(1.03);
        }

        .hero-content {
            position: absolute;
            z-index: 5;
            left: 7%;
            bottom: 12%;
            max-width: 650px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            padding: 10px 18px;
            border-radius: 999px;

            background: rgba(255, 31, 69, .12);
            border: 1px solid rgba(255, 31, 69, .35);

            color: #ff728d;
            font-size: 14px;
            font-weight: 600;

            backdrop-filter: blur(8px);

            margin-bottom: 22px;
        }

        .hero-title {
            font-size: 78px;
            font-weight: 800;
            line-height: 1.02;
            letter-spacing: -2px;

            text-shadow:
                0 4px 20px rgba(0, 0, 0, .5),
                0 0 35px rgba(255, 255, 255, .08);
        }

        .hero-desc {
            margin-top: 22px;
            color: #d2d2d2;
            line-height: 1.9;
            font-size: 15px;
            max-width: 90%;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;

            margin-top: 22px;
        }

        .hero-meta span {
            padding: 8px 16px;
            border-radius: 999px;

            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .08);

            font-size: 13px;
            color: #ececec;
        }

        .hero-buttons {
            margin-top: 32px;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-watch {
            background: linear-gradient(135deg, var(--primary), #ff365b);

            border: none;
            border-radius: 999px;

            padding: 15px 32px;

            font-weight: 600;
            color: white;

            transition: .3s;

            box-shadow: 0 10px 25px rgba(255, 31, 69, .3);
        }

        .btn-watch:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 31, 69, .45);
        }

        .btn-info-custom {
            background: rgba(255, 255, 255, .08);

            border: 1px solid rgba(255, 255, 255, .08);
            color: white;

            border-radius: 999px;
            padding: 15px 28px;

            font-weight: 600;

            transition: .25s;
        }

        .btn-info-custom:hover {
            background: rgba(255, 255, 255, .15);
            box-shadow: 0 15px 35px rgba(255, 255, 255, .15);
            transform: translateY(-3px);
        }


        .section {
            padding: 90px 0 20px;
        }

        .section-header {
            margin-bottom: 38px;
        }

        .section-header {
            margin-bottom: 55px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 14px;

            font-family: 'Montserrat', sans-serif;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 1px;

            margin-bottom: 18px;
        }

        .section-title i {
            width: 58px;
            height: 58px;

            border-radius: 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(135deg,
                    rgba(255, 31, 69, 0.22),
                    rgba(255, 31, 69, 0.06));

            border: 1px solid rgba(255, 31, 69, 0.25);

            color: #ff4468;
            font-size: 22px;

            box-shadow:
                0 0 25px rgba(255, 31, 69, 0.18);
        }

        .section-sub {
            font-family: 'Poppins', sans-serif;

            font-size: 16px;
            line-height: 1.9;

            color: #a8a8a8;

            max-width: 680px;

            padding-left: 72px;
        }

        .movie-card {
            position: relative;
            overflow: hidden;

            border-radius: 28px;

            background: linear-gradient(180deg,
                    rgba(255, 255, 255, .02),
                    rgba(255, 255, 255, .01));

            border: 1px solid rgba(255, 255, 255, .06);

            transition: .35s;

            height: 470px;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, .35),
                inset 0 1px 0 rgba(255, 255, 255, .03);
        }

        .movie-card::before {
            content: "";

            position: absolute;
            inset: 0;

            border-radius: 22px;
            padding: 1px;

            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, 0.18),
                    rgba(255, 255, 255, 0.02));

            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);

            -webkit-mask-composite: xor;
            mask-composite: exclude;

            opacity: 0;
            transition: .35s;

            z-index: 3;
            pointer-events: none;
        }

        .movie-card:hover::before {
            opacity: 1;
        }

        .movie-card:hover {
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.5),
                0 0 30px rgba(255, 31, 69, 0.15);
        }

        .movie-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;

            transition: .55s;
        }

        .movie-card:hover img {
            transform: scale(1.08);
        }

        .movie-overlay {
            position: absolute;
            inset: 0;
            z-index: 5;

            background:
                linear-gradient(to top,
                    rgba(0, 0, 0, .98) 8%,
                    rgba(0, 0, 0, .55) 40%,
                    rgba(0, 0, 0, .1) 100%);

            padding: 24px;

            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .movie-top {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .movie-rating {
            padding: 7px 14px;
            border-radius: 999px;

            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(10px);

            border: 1px solid rgba(255, 255, 255, .08);

            font-size: 13px;
            font-weight: 600;
        }

        .movie-age {
            width: 42px;
            height: 42px;

            border-radius: 50%;

            background: rgba(255, 31, 69, .18);
            border: 1px solid rgba(255, 31, 69, .35);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 13px;
            font-weight: 700;
            color: #ff6b87;
        }

        .movie-name {
            font-size: 23px;
            font-weight: 700;

            line-height: 1.4;

            font-family: 'Montserrat', sans-serif;

            transition: .3s;
        }

        .movie-card:hover .movie-name {
            color: #ff4f72;
        }

        .movie-desc {
            font-size: 13px;
            line-height: 1.7;
            color: #d0d0d0;

            margin-bottom: 18px;
        }

        .movie-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;

            margin-bottom: 20px;
        }

        .movie-meta span {
            padding: 8px 14px;

            border-radius: 999px;

            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .05);

            font-size: 12px;
            color: #e9e9e9;
        }

        .movie-actions {
            display: flex;
            gap: 12px;
        }

        .btn-card {
            border: none;
            border-radius: 999px;

            padding: 11px 18px;

            font-size: 14px;
            font-weight: 600;

            transition: .25s;
        }

        .btn-play {
            background:
                linear-gradient(135deg,
                    #ff1f45,
                    #ff4d6d);

            color: white;

            box-shadow:
                0 12px 25px rgba(255, 31, 69, 0.35);

            transition: .3s;
        }

        .btn-play:hover {
            transform: translateY(-2px);

            box-shadow:
                0 16px 30px rgba(255, 31, 69, 0.5);
        }

        .btn-play:hover {
            transform: translateY(-2px);
        }

        .btn-detail {
            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, .08),
                    rgba(255, 255, 255, .15));

            color: white;

            border: none;

            padding: 10px 18px;

            border-radius: 999px;

            font-size: 14px;
            font-weight: 600;

            transition: .3s;

            box-shadow:
                0 10px 20px rgba(255, 255, 255, .08);
        }

        .btn-detail:hover {
            transform: translateY(-2px);

            background:
                linear-gradient(135deg,
                    rgb(255, 255, 255, .08),
                    rgb(255, 255, 255, .15));

            box-shadow:
                0 14px 24px rgba(255, 255, 255, .15);
        }


        .top-card {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            height: 480px;
        }

        .top-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .55s;
        }

        .top-card:hover img {
            transform: scale(1.08);
        }

        .top-name {
            font-size: 23px;
            font-weight: 700;

            line-height: 1.4;

            font-family: 'Montserrat', sans-serif;

            transition: .3s;
        }

        .top-card:hover .top-name {
            color: #ff4f72;
        }


        .top-rank {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 4;

            width: 62px;
            height: 62px;

            border-radius: 50%;

            background:
                linear-gradient(135deg,
                    var(--primary),
                    #ff5576);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;
            font-weight: 800;

            box-shadow:
                0 0 25px rgba(255, 31, 69, .5),
                inset 0 2px 8px rgba(255, 255, 255, .15);
        }

        .upcoming-card {
            background: rgba(255, 255, 255, .03);

            border: 1px solid rgba(255, 255, 255, .05);

            border-radius: 28px;
            overflow: hidden;

            transition: .35s;

            height: 100%;
        }

        .upcoming-card:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 31, 69, .25);

            box-shadow:
                0 20px 40px rgba(0, 0, 0, .45),
                0 0 30px rgba(255, 31, 69, .08);
        }

        .upcoming-card img {
            width: 100%;
            height: 360px;
            object-fit: cover;
            transition: .45s;
        }

        .upcoming-card:hover img {
            transform: scale(1.05);
        }

        .upcoming-content {
            padding: 24px;
        }

        .upcoming-title {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.4;
        }

        .upcoming-desc {
            margin-top: 12px;

            color: var(--text-soft);
            line-height: 1.8;
            font-size: 14px;
        }

        .release-date {
            margin-top: 18px;

            display: inline-flex;
            align-items: center;
            gap: 10px;

            padding: 9px 16px;

            border-radius: 999px;

            background: rgba(255, 31, 69, .12);
            border: 1px solid rgba(255, 31, 69, .18);

            color: #ff6f8d;

            font-size: 13px;
            font-weight: 600;
        }

        footer {
            margin-top: 120px;

            padding: 60px 0;

            border-top: 1px solid rgba(255, 255, 255, .06);

            background: rgba(255, 255, 255, .015);
        }

        .footer-logo {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 3px;
        }

        .footer-text {
            margin-top: 18px;
            color: #9b9b9b;
            line-height: 1.9;
        }

        @media(max-width:991px) {

            .hero-title {
                font-size: 48px;
            }

            .movie-card {
                height: 420px;
            }

            .top-card {
                height: 400px;
            }
        }

        @media(max-width:768px) {

            .hero {
                height: 85vh;
            }

            .hero-title {
                font-size: 38px;
            }

            .hero-content {
                max-width: 90%;
            }

            .section-title {
                font-size: 30px;
            }

            .movie-card {
                height: 390px;
            }

            .top-card {
                height: 360px;
            }
        }
    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">

            <a class="navbar-brand brand-logo"
                href="{{ route('index') }}">
                NETFNIX
            </a>

            <div class="d-flex align-items-center gap-3">

                <a href="#" class="nav-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>

                <a href="#" class="nav-icon">
                    <i class="fa-regular fa-bell"></i>
                </a>

                <div class="nav-icon">
                    <i class="fa-regular fa-user"></i>
                </div>

            </div>

        </div>
    </nav>


    @if($movie_show->count())

    <section class="hero">

        <img
            src="{{ asset('storage/img/movie_thumbnail/' . $movie_show[0]->thumbnail) }}"
            alt="">

        <div class="hero-overlay"></div>

        <div class="hero-content">

            <div class="hero-badge">
                <i class="fa-solid fa-fire"></i>
                Trending Movie
            </div>

            <div class="hero-title">
                {{ $movie_show[0]->movie_name }}
            </div>

            <div class="hero-desc">
                {{ Str::limit($movie_show[0]->synopsis, 220) }}
            </div>

            <div class="hero-buttons">

                <a href="#"
                    class="btn btn-watch">
                    <i class="fa-solid fa-play"></i>
                    Xem ngay
                </a>

                <a href="#"
                    class="btn btn-info-custom">
                    <i class="fa-solid fa-circle-info"></i>
                    Chi tiết
                </a>

            </div>

        </div>

    </section>

    @endif

    <div class="container">

        <section class="section">

            <div class="section-header">

                <h2 class="section-title">
                    <i class="fa-solid fa-film"></i>
                    Phim đang chiếu
                </h2>

                <div class="section-sub">
                    Những bộ phim nổi bật đang được công chiếu trên hệ thống.
                </div>

            </div>

            <div class="row g-4">

                @foreach($movie_show as $movie)

                <div class="col-lg-3 col-md-6">

                    <div class="movie-card">

                        <img
                            src="{{ asset('storage/img/movie_poster/' . $movie->poster) }}"
                            alt="{{ $movie->movie_name }}">

                        <div class="movie-overlay">

                            <div class="movie-name">
                                {{ $movie->movie_name }}
                            </div>

                            <div class="movie-meta">

                                ⭐ {{ $movie->rating }}
                                &nbsp; • &nbsp;

                                {{ $movie->length }} phút
                                &nbsp; • &nbsp;

                                {{ $movie->country }}

                            </div>

                            <div class="movie-actions">

                                <button class="btn-card btn-play">
                                    <i class="fa-solid fa-play"></i>
                                    Xem ngay
                                </button>

                                <button class="btn-card btn-detail">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Chi tiết
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </section>


        <section class="section">

            <div class="section-header">

                <h2 class="section-title">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                    Top Movie
                </h2>

                <div class="section-sub">
                    Những bộ phim được yêu thích và có lượt xem cao nhất trên hệ thống.
                </div>

            </div>

            <div class="row g-4">

                @foreach($movie_show->take(4) as $index => $movie)

                <div class="col-lg-3 col-md-6">

                    <div class="top-card">

                        <div class="top-rank">
                            {{ $index + 1 }}
                        </div>

                        <img
                            src="{{ asset('storage/img/movie_poster/' . $movie->poster) }}"
                            alt="{{ $movie->movie_name }}">

                        <div class="movie-overlay">

                            <div class="top-name">
                                {{ $movie->movie_name }}
                            </div>

                            <div class="movie-meta">
                                ⭐ {{ $movie->rating }}
                                &nbsp; • &nbsp;
                                {{ $movie->length }} phút
                            </div>

                            <div class="movie-actions">

                                <button class="btn-card btn-play">
                                    <i class="fa-solid fa-play"></i>
                                    Xem ngay
                                </button>

                                <button class="btn-card btn-detail">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Chi tiết
                                </button>

                            </div>

                        </div>

                    </div>


                </div>

                @endforeach

            </div>

        </section>

        <section class="section">

            <div class="section-header">

                <h2 class="section-title">
                    <i class="fa-solid fa-clapperboard"></i>
                    Phim sắp chiếu
                </h2>

                <div class="section-sub">
                    Các bom tấn chuẩn bị ra mắt với nhiều nội dung hấp dẫn được mong chờ.
                </div>

            </div>

            <div class="row g-4">

                @foreach($upcoming_movies as $movie)

                <div class="col-lg-3 col-md-6">

                    <div class="upcoming-card">

                        <img
                            src="{{ asset('storage/img/movie_thumbnail/' . $movie->thumbnail) }}"
                            alt="{{ $movie->movie_name }}">

                        <div class="upcoming-content">

                            <h5 class="fw-bold">
                                {{ $movie->movie_name }}
                            </h5>

                            <div class="text-secondary mt-2">
                                {{ Str::limit($movie->synopsis, 70) }}
                            </div>

                            <div class="release-date">
                                <i class="fa-regular fa-calendar"></i>

                                {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </section>

    </div>

    <footer>
        <div class="container text-center">

            <h3 class="fw-bold text-danger">
                NETFNIX
            </h3>

            <p class="mt-3">
                Website xem phim hiện đại với trải nghiệm điện ảnh tuyệt vời.
            </p>

        </div>
    </footer>

</body>

</html>