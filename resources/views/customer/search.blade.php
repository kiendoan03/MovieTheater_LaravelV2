@extends('layouts.client')

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
        box-shadow:
            0 20px 45px rgba(0, 0, 0, .35),
            0 0 25px rgba(255, 31, 69, .06);
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
        box-shadow:
            0 25px 45px rgba(0, 0, 0, .55),
            0 0 35px rgba(255, 31, 69, .12);
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
    .movie-box {
    transition: .35s;
    width: 100%;
    }

    .movie-img {
        width: 100%;
        height: 180px;
        border-radius: 10px;
        object-fit: cover;
        opacity: .9;
        box-shadow:
            #fff 0px 5px 15px;
        transition:
            transform .35s ease,
            box-shadow .35s ease,
            opacity .35s ease;
    }

    .movie-box:hover .movie-img {

        transform:
            scale(1.05)
            translateY(-5px);

        opacity: 1;

        box-shadow:
            0 10px 30px rgba(255,255,255,.35),
            0 0 25px rgba(255,255,255,.15);
    }

    .movie-content {
        padding-top: 12px;
    }

    .movie-name {
        font-size: .9rem;
        color: #f5f5f5;
        font-weight: 500;
        letter-spacing: .2px;
        }

    @media(max-width:768px) {

    .movie-img {
        height: 160px;
    }

    .movie-name {
        font-size: .95rem;
        }
    }
    
    .sw-pagination {
        display: flex;

        justify-content: center;
        align-items: center;

        gap: 8px;

        margin-top: 50px;

        flex-wrap: wrap;
    }

    .sw-pagination .page-link {
        min-width: 42px;
        height: 42px;

        padding: 0 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        text-decoration: none;

        font-size: 13px;
        font-weight: 600;

        color: #d4d4d8;

        background: rgba(255, 255, 255, .04);

        border: 1px solid rgba(255, 255, 255, .08);

        backdrop-filter: blur(12px);

        transition: .25s;
    }

    .sw-pagination .page-link:hover {
        transform: translateY(-2px);

        color: #fff;

        border-color: rgba(255, 31, 69, .35);

        box-shadow:
            0 10px 20px rgba(255, 31, 69, .18);
    }

    .sw-pagination .page-link.active {
        color: #fff;

        border-color: transparent;

        background:
            linear-gradient(135deg,
                #ff1f45,
                #ff4d6d);

        box-shadow:
            0 12px 24px rgba(255, 31, 69, .35);
    }

    .sw-pagination .opacity-50 {
        opacity: .45;

        pointer-events: none;
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

<section>
    <div class="row gx-5 gy-5 mt-5">

        @forelse($movies as $movie)

        <div class="col-3 mb-5">

            <div class="movie-box">

                <a href="">
                    <img
                        src="{{ asset('storage/img/movie_thumbnail/' . $movie->thumbnail) }}"
                        class="movie-img"
                        alt="">
                </a>

                <div class="movie-content">
                    <span class="movie-name">
                        {{ $movie->movie_name }}
                    </span>
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
</section>


    {{-- PAGINATION --}}
    @if($movies->hasPages())

    <div class="sw-pagination">

        {{-- Prev --}}
        @if($movies->onFirstPage())

        <span class="page-link opacity-50">
            <i class="fa-solid fa-chevron-left"></i>
        </span>

        @else

        <a href="{{ $movies->previousPageUrl() }}"
            class="page-link">

            <i class="fa-solid fa-chevron-left"></i>

        </a>

        @endif


        {{-- Pages --}}
        @foreach($movies->getUrlRange(1, $movies->lastPage()) as $page => $url)

        <a href="{{ $url }}"
            class="page-link {{ $movies->currentPage() == $page ? 'active' : '' }}">

            {{ $page }}

        </a>

        @endforeach


        {{-- Next --}}
        @if($movies->hasMorePages())

        <a href="{{ $movies->nextPageUrl() }}"
            class="page-link">

            <i class="fa-solid fa-chevron-right"></i>

        </a>

        @else

        <span class="page-link opacity-50">
            <i class="fa-solid fa-chevron-right"></i>
        </span>

        @endif

    </div>

    @endif

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

</script>

@endpush
@endsection