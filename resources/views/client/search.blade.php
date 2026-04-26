@extends('layouts.clientmanagement')

@section('title', 'Tìm kiếm phim')

@push('styles')

<style>
    .search-page {
        padding: 140px 0 80px;
        min-height: 100vh;
        position: relative;
        z-index: 1;
    }

    .search-filter {
        position: relative;
        z-index: 1000;
        padding: 32px;
        margin-bottom: 50px;
        background: rgba(255, 255, 255, .04);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 30px;
        backdrop-filter: blur(18px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, .35), 0 0 25px rgba(255, 31, 69, .06);
    }

    .search-title {
        font: 800 42px 'Montserrat', sans-serif;
        margin-bottom: 12px;
        color: #fff;
        user-select: none;
    }

    .search-sub {
        margin-bottom: 35px;
        font-size: 15px;
        color: #a1a1aa;
        user-select: none;
    }

    .filter-input,
    .form-control,
    .form-select {
        height: 58px;
        padding: 0 18px;
        border-radius: 18px !important;
        background: rgba(255, 255, 255, .05) !important;
        border: 1px solid rgba(255, 255, 255, .08) !important;
        color: #fff !important;
        box-shadow: none !important;
        transition: .25s;
    }

    .filter-input:focus,
    .form-control:focus,
    .form-select:focus {
        border-color: #ff1f45 !important;
        background: rgba(255, 255, 255, .06) !important;
        box-shadow: 0 0 0 4px rgba(255, 31, 69, .15) !important;
    }

    .filter-input::placeholder,
    .form-control::placeholder,
    #genreSearch::placeholder {
        color: #8b8b95;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: #fff !important;
        transition: background-color 9999s ease-in-out 0s;
    }

    .filter-btn,
    .btn-play,
    .btn-detail,
    .genre-option,
    .remove-tag {
        border: none;
        border-radius: 999px;
        font-weight: 600;
        transition: .3s ease;
        cursor: pointer;
    }

    .filter-btn {
        width: 100%;
        height: 58px;
        color: #fff;
        background: linear-gradient(135deg, #ff1f45, #ff4d6d);
        box-shadow: 0 10px 25px rgba(255, 31, 69, .28);
    }

    .filter-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 35px rgba(255, 31, 69, .38);
    }

    .btn-play,
    .btn-detail {
        padding: 10px 18px;
        font-size: 14px;
    }

    .btn-play {
        background: linear-gradient(135deg, #ff1f45, #ff4d6d);
        color: #fff;
        box-shadow: 0 10px 25px rgba(255, 31, 69, .25);
    }

    .btn-detail {
        background: rgba(255, 255, 255, .08);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .08);
        box-shadow: 0 10px 20px rgba(255, 255, 255, .06);
    }

    .btn-play:hover,
    .btn-detail:hover {
        transform: translateY(-3px);
    }

    .btn-play:hover {
        box-shadow: 0 15px 35px rgba(255, 31, 69, .45);
    }

    .btn-detail:hover {
        background: rgba(255, 255, 255, .15);
        box-shadow: 0 15px 35px rgba(255, 255, 255, .12);
    }

    .movie-card {
        position: relative;
        overflow: hidden;
        height: 470px;
        border-radius: 28px;
        background: rgba(255, 255, 255, .03);
        border: 1px solid rgba(255, 255, 255, .06);
        transition: .35s;
        z-index: 1;
    }

    .movie-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px rgba(0, 0, 0, .45), 0 0 35px rgba(255, 31, 69, .12);
    }

    .movie-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: .5s;
        pointer-events: none;
        user-select: none;
    }

    .movie-card:hover img {
        transform: scale(1.08);
    }

    .movie-overlay {
        position: absolute;
        inset: 0;
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        background: linear-gradient(to top, rgba(0, 0, 0, .96) 10%, rgba(0, 0, 0, .45) 45%, rgba(0, 0, 0, .05));
    }

    .movie-name {
        font: 700 24px 'Montserrat', sans-serif;
        margin-bottom: 12px;
        color: #fff;
    }

    .movie-meta {
        font-size: 14px;
        margin-bottom: 18px;
        color: #d4d4d8;
    }

    .movie-actions {
        display: flex;
        gap: 12px;
    }

    .empty-box {
        text-align: center;
        padding: 90px 20px;
        border-radius: 28px;
        background: rgba(255, 255, 255, .03);
        border: 1px solid rgba(255, 255, 255, .06);
        color: #9f9f9f;
    }

    .pagination {
        justify-content: center;
    }

    .page-link {
        background: rgba(255, 255, 255, .05) !important;
        border: 1px solid rgba(255, 255, 255, .08) !important;
        color: #fff !important;
    }

    .page-item.active .page-link {
        background: #ff1f45 !important;
        border-color: #ff1f45 !important;
    }

    .genre-wrapper {
        position: relative;
        z-index: 9999;
    }

    .genre-input-box {
        min-height: 58px;
        padding: 10px 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, .05);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 18px;
        transition: .25s;
        cursor: text;
    }

    .genre-input-box:focus-within {
        border-color: #ff1f45;
        box-shadow: 0 0 0 4px rgba(255, 31, 69, .15);
    }

    .genre-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .genre-tag {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: linear-gradient(135deg, #ff1f45, #ff4d6d);
    }

    #genreSearch {
        flex: 1;
        min-width: 120px;
        height: 36px;
        border: none;
        outline: none;
        background: transparent;
        color: #fff;
        caret-color: #fff;
    }

    .genre-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        left: 0;
        width: 100%;
        max-height: 280px;
        overflow-y: auto;
        display: none;
        z-index: 99999;
        background: #16161f;
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 20px;
        backdrop-filter: blur(18px);
        box-shadow: 0 25px 45px rgba(0, 0, 0, .55), 0 0 35px rgba(255, 31, 69, .12);
    }

    .genre-dropdown.active {
        display: block;
    }

    .genre-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .genre-dropdown::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .12);
        border-radius: 999px;
    }

    .genre-option {
        padding: 14px 18px;
        font-size: 14px;
        color: #d7d7d7;
    }

    .genre-option:hover {
        background: rgba(255, 31, 69, .12);
        color: #fff;
    }

    .genre-option.active {
        background: rgba(255, 31, 69, .18);
        color: #ff6f8d;
    }

    .movie-card,
    .movie-overlay,
    .movie-name,
    .movie-meta,
    .movie-actions,
    .search-title,
    .search-sub,
    .empty-box {
        cursor: default;
    }

    @media (max-width: 992px) {
        .search-title {
            font-size: 32px;
        }

        .movie-card {
            height: 420px;
        }
    }

    @media (max-width: 768px) {
        .search-filter {
            padding: 22px;
        }

        .search-title {
            font-size: 28px;
        }

        .movie-card {
            height: 380px;
        }

        .movie-name {
            font-size: 20px;
        }
    }
</style>

@endpush

@section('content')

<div class="container search-page">

    <div class="search-filter">

        <div class="search-title">
            Tìm kiếm phim
        </div>

        <div class="search-sub">
            Khám phá những bộ phim yêu thích theo tên, thể loại và ngày chiếu.
        </div>

        <form
            action="{{ route('movies.search') }}"
            method="GET"
            autocomplete="off">

            <div class="row g-3">

                <div class="col-lg-4">

                    <input
                        type="text"
                        name="keyword"
                        value="{{ $keyword }}"
                        class="form-control filter-input"
                        placeholder="Nhập tên phim...">

                </div>

                <div class="col-lg-3">

                    <div class="genre-wrapper">

                        <div class="genre-input-box" id="genreBox">

                            <div class="genre-tags" id="genreTags">

                                @foreach($categories as $category)

                                @if(in_array($category->id, $genre ?? []))

                                <span class="genre-tag">

                                    {{ $category->name }}

                                    <button
                                        type="button"
                                        class="remove-tag"
                                        data-id="{{ $category->id }}">

                                        <i class="fa-solid fa-xmark"></i>

                                    </button>

                                </span>

                                @endif

                                @endforeach

                            </div>

                            <input
                                type="text"
                                id="genreSearch"
                                placeholder="Chọn thể loại...">

                        </div>

                        <div class="genre-dropdown" id="genreDropdown">

                            @foreach($categories as $category)

                            <div
                                class="genre-option
                    {{ in_array($category->id, $genre ?? []) ? 'active' : '' }}"
                                data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}">

                                {{ $category->name }}

                            </div>

                            @endforeach

                        </div>

                    </div>

                    <div id="genreHiddenInputs">

                        @foreach($genre ?? [] as $item)

                        <input
                            type="hidden"
                            name="genre[]"
                            value="{{ $item }}"
                            id="genre-input-{{ $item }}">

                        @endforeach

                    </div>

                </div>

                <div class="col-lg-3">

                    <input
                        type="date"
                        name="release_date"
                        value="{{ request('release_date') }}"
                        class="form-control filter-input">

                </div>

                <div class="col-lg-2">

                    <button
                        type="submit"
                        class="w-100 filter-btn">

                        <i class="fa-solid fa-magnifying-glass"></i>
                        Tìm kiếm

                    </button>

                </div>

            </div>

        </form>

    </div>

    <div class="row g-4">

        @forelse($movies as $movie)

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

                        <button class="btn-play">

                            <i class="fa-solid fa-play"></i>
                            Xem ngay

                        </button>

                        <button class="btn-detail">

                            <i class="fa-solid fa-circle-info"></i>
                            Chi tiết

                        </button>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="col-12">

            <div class="empty-box">

                <h3 class="mb-3">
                    Không tìm thấy phim
                </h3>

                <div class="text-secondary">
                    Hãy thử tìm kiếm với từ khóa khác.
                </div>

            </div>

        </div>

        @endforelse

    </div>

    <div class="mt-5">

        {{ $movies->links() }}

    </div>

</div>
@push('scripts')
<script>
    const genreBox = document.getElementById('genreBox');
    const genreDropdown = document.getElementById('genreDropdown');
    const genreTags = document.getElementById('genreTags');
    const hiddenInputs = document.getElementById('genreHiddenInputs');
    const genreSearch = document.getElementById('genreSearch');

    genreBox.addEventListener('click', function(e) {
        genreDropdown.classList.add('active');

        if (e.target === genreSearch) {
            genreSearch.focus();
        } else {
            genreSearch.blur();
        }
    });

    const options = document.querySelectorAll('.genre-option');

    function updatePlaceholder() {
        const hasTag = genreTags.querySelectorAll('.genre-tag').length > 0;

        if (hasTag) {
            genreSearch.placeholder = '';
            genreSearch.classList.add('hide-placeholder');
        } else {
            genreSearch.placeholder = 'Chọn thể loại...';
            genreSearch.classList.remove('hide-placeholder');
        }
    }

    function bindRemoveEvent(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();

            const id = this.dataset.id;

            this.closest('.genre-tag').remove();

            document
                .querySelector(`.genre-option[data-id="${id}"]`)
                ?.classList.remove('active');

            document
                .getElementById(`genre-input-${id}`)
                ?.remove();

            updatePlaceholder();
        });
    }

    document.querySelectorAll('.remove-tag').forEach(bindRemoveEvent);

    options.forEach(option => {
        option.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;

            if (this.classList.contains('active')) return;

            this.classList.add('active');

            const tag = document.createElement('span');
            tag.className = 'genre-tag';

            tag.innerHTML = `
            ${name}
            <button type="button" class="remove-tag" data-id="${id}">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;

            genreTags.appendChild(tag);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'genre[]';
            input.value = id;
            input.id = `genre-input-${id}`;

            hiddenInputs.appendChild(input);

            bindRemoveEvent(tag.querySelector('.remove-tag'));

            updatePlaceholder();
            genreSearch.blur();
        });
    });

    updatePlaceholder();
    genreSearch.value = '';

    /* CARET HANDLING (giữ nguyên logic nhưng gọn) */
    genreSearch.addEventListener('focus', () => {
        genreSearch.style.caretColor = 'auto';
    });

    genreSearch.addEventListener('blur', () => {
        genreSearch.style.caretColor = 'transparent';
    });

    /* GỘP 2 document click handler → 1 handler duy nhất */
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.genre-wrapper')) {
            genreDropdown.classList.remove('active');
            genreSearch.blur();
            return;
        }

        const isInput = e.target.closest('input, textarea, [contenteditable]');
        if (isInput) {
            e.target.focus({
                preventScroll: true
            });
        }
    });
</script>

@endpush
@endsection