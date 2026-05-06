@extends('layouts.management')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

    :root {
        --surface: #13161e;
        --card: #1a1e28;
        --border: rgba(255, 255, 255, 0.07);
        --border-h: rgba(255, 255, 255, 0.15);
        --text: #e8eaf0;
        --muted: #6b7280;
        --accent: #e8c96a;
        --accent-bg: rgba(232, 201, 106, 0.10);
        --danger: #f87171;
    }

    .sw {
        font-family: 'Sora', sans-serif;
        color: var(--text);
        padding: 2rem 0 5rem;
    }

    .sw-head {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 2rem;
    }

    .sw-head h2 {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }

    .sw-crumb {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--muted);
        background: var(--card);
        border: 1px solid var(--border);
        padding: 3px 10px;
        border-radius: 20px;
    }

    .sw-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .sw-section-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: .12em;
        color: var(--muted);
        text-transform: uppercase;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sw-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .sw-label {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: var(--muted);
        margin-bottom: 6px;
        letter-spacing: .04em;
    }

    .sw-input,
    .sw-select,
    textarea {
        width: 100%;
        min-height: 44px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        padding: 10px 12px;
        outline: none;
        box-sizing: border-box;
        transition: .2s;
    }

    .sw-input:focus,
    .sw-select:focus,
    textarea:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(232, 201, 106, .08);
    }

    .sw-input:-webkit-autofill,
    .sw-input:-webkit-autofill:hover,
    .sw-input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--text);
        -webkit-box-shadow: 0 0 0 1000px var(--surface) inset;
        transition: background-color 9999s ease-in-out 0s;
        caret-color: var(--text);
        border: 1px solid var(--accent);
    }

    .sw-error {
        font-size: 11px;
        color: var(--danger);
        margin-top: 4px;
        display: block;
    }

    .movie-preview {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: .75rem;
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .movie-preview img {
        width: 90px;
        height: 130px;
        border-radius: 8px;
        object-fit: cover;
        background: #0f1117;
    }
    #thumbnailPreview {
        width: 160px;
        aspect-ratio: 16 / 9;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .movie-preview-info {
        flex: 1;
    }

    .movie-preview-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .movie-preview-meta {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.7;
    }

    .sw-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
        margin-top: 1.5rem;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--muted);
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        padding: 9px 20px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: .2s;
    }

    .btn-cancel:hover {
        border-color: var(--border-h);
        color: var(--text);
    }

    .btn-submit {
        background: var(--accent);
        border: none;
        color: #0d0f14;
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 28px;
        border-radius: 8px;
        cursor: pointer;
        transition: .2s;
    }

    .btn-submit:hover {
        background: #f0d47a;
    }

    .ts-wrapper {
        width: 100%;
    }

    .ts-wrapper.single .ts-control,
    .ts-wrapper.multi .ts-control {
        width: 100%;
        min-height: 44px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        padding: 6px 12px;
        box-shadow: none;
        transition: .2s;
    }

    .ts-wrapper.single .ts-control:hover,
    .ts-wrapper.multi .ts-control:hover {
        border-color: var(--border-h);
    }

    .ts-wrapper.focus .ts-control {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(232, 201, 106, .08);
    }

    .ts-wrapper.single .ts-control .item,
    .ts-wrapper.multi .ts-control .item {
        color: var(--text);
        font-size: 13px;
        font-family: 'Sora', sans-serif;
    }

    .ts-control input {
        background: transparent;
        color: var(--text);
        font-size: 13px;
        font-family: 'Sora', sans-serif;
        margin: 0;
        padding: 0;
    }

    .ts-control input::placeholder {
        color: var(--muted);
    }

    /* autofill tomselect */

    .ts-control input:-webkit-autofill,
    .ts-control input:-webkit-autofill:hover,
    .ts-control input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--text);
        -webkit-box-shadow: 0 0 0 1000px var(--surface) inset;
        transition: background-color 9999s ease-in-out 0s;
        caret-color: var(--text);
    }

    /* multi item */

    .ts-wrapper.multi .ts-control>div {
        background: var(--accent-bg);
        color: var(--accent);
        border: 1px solid rgba(232, 201, 106, .2);
        border-radius: 999px;
        padding: 4px 10px;
        margin: 3px;
        font-size: 12px;
    }

    .ts-wrapper.multi .ts-control>div .remove {
        border-left: none;
        color: var(--accent);
        margin-left: 6px;
    }

    /* FIX WHITE BACKGROUND WHEN CLICK COUNTRY */

    .ts-wrapper.single .ts-control,
    .ts-wrapper.single .ts-control input,
    .ts-wrapper.single.focus .ts-control,
    .ts-wrapper.single.focus .ts-control input,
    .ts-wrapper.single.input-active .ts-control,
    .ts-wrapper.single.input-active .ts-control input {
        background: var(--surface) !important;
        color: var(--text) !important;
        box-shadow: none;
    }

    .ts-wrapper.single .ts-control input:-webkit-autofill,
    .ts-wrapper.single .ts-control input:-webkit-autofill:hover,
    .ts-wrapper.single .ts-control input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--text) !important;
        -webkit-box-shadow: 0 0 0 1000px var(--surface) inset !important;
        background: var(--surface) !important;
        transition: background-color 9999s ease-in-out 0s;
    }

    .ts-dropdown {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 6px;
    }

    .ts-dropdown .option {
        background: var(--card);
        color: var(--text);
        padding: 10px 12px;
        font-size: 13px;
        transition: .15s;
    }

    .ts-dropdown .option:hover,
    .ts-dropdown .active {
        background: rgba(255, 255, 255, .06);
        color: var(--text);
    }

    .ts-dropdown .selected {
        background: rgba(232, 201, 106, .12);
        color: var(--accent);
    }

    textarea {
        resize: none;
        min-height: 120px;
    }
</style>

<div class="sw">
    <div class="container-fluid px-3 px-md-4">

        <div class="sw-head">
            <h2>Chỉnh sửa phim</h2>
        </div>

        <form method="POST"
            action="{{ route('admin.movies.update', $movie) }}"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- THÔNG TIN PHIM --}}
            <div class="sw-card">
                <div class="sw-section-label">
                    01 — Thông tin phim
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="sw-label">Tên phim</label>

                        <input type="text"
                            name="movie_name"
                            class="sw-input"
                            value="{{ old('movie_name', $movie->movie_name) }}"
                            required>

                        @error('movie_name')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="sw-label">Thời lượng (phút)</label>

                        <input type="number"
                            name="movie_length"
                            class="sw-input"
                            value="{{ old('movie_length', $movie->length) }}"
                            required>
                        @error('movie_length')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="sw-label">Độ tuổi</label>

                        <input type="number"
                            name="movie_age"
                            class="sw-input"
                            value="{{ old('movie_age', $movie->age_restricted) }}">
                        @error('movie_age')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Ngày khởi chiếu</label>

                        <input type="date"
                            name="movie_release_date"
                            class="sw-input"
                            value="{{ old('movie_release_date', $movie->release_date?->format('Y-m-d')) }}"
                            required>
                        @error('movie_release_date')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Ngày kết thúc</label>

                        <input type="date"
                            name="movie_end_date"
                            class="sw-input"
                            value="{{ old('movie_end_date', $movie->end_date?->format('Y-m-d')) }}"
                            required>
                        @error('movie_end_date')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NGÔN NGỮ --}}
                    <div class="col-md-6">
                        <label class="sw-label">Ngôn ngữ</label>

                        <input type="text"
                            name="movie_language"
                            class="sw-input"
                            placeholder="Ví dụ: English, Japanese, Korean..."
                            value="{{ old('movie_language', $movie->language) }}"
                            required>

                        @error('movie_language')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- QUỐC GIA --}}
                    <div class="col-md-6">
                        <label class="sw-label">Quốc gia</label>

                        @php
                        $countries = [
                        'Việt Nam',
                        'Hoa Kỳ',
                        'Hàn Quốc',
                        'Nhật Bản',
                        'Trung Quốc',
                        'Thái Lan',
                        'Ấn Độ',
                        'Anh',
                        'Pháp',
                        'Đức',
                        'Canada',
                        'Úc',
                        'Nga',
                        'Ý',
                        'Tây Ban Nha',
                        'Indonesia',
                        'Malaysia',
                        'Singapore',
                        'Philippines',
                        'Brazil',
                        ];
                        @endphp

                        <select name="movie_country"
                            id="movie_country"
                            required>

                            <option value="">Chọn quốc gia</option>

                            @foreach($countries as $country)
                            <option value="{{ $country }}"
                                {{ old('movie_country', $movie->country) == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                            @endforeach

                        </select>

                        @error('movie_country')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="sw-label">Mô tả phim</label>

                        <textarea name="movie_description">{{ old('movie_description', $movie->synopsis) }}</textarea>
                        @error('movie_poster')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- THỂ LOẠI & NHÂN SỰ --}}
            <div class="sw-card">

                <div class="sw-section-label">
                    02 — Thể loại & nhân sự
                </div>

                <div class="row g-3">

                    {{-- THỂ LOẠI --}}
                    <div class="col-md-4">

                        <label class="sw-label">
                            Thể loại
                        </label>

                        <select
                            name="movie_genre[]"
                            id="movie_genre"
                            multiple>

                            @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ in_array(
                        $category->id,
                        old(
                            'movie_genre',
                            $movie->categories->pluck('id')->toArray()
                        )
                    ) ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>

                            @endforeach

                        </select>
                        @error('movie_genre')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror

                    </div>

                    {{-- DIỄN VIÊN --}}
                    <div class="col-md-4">

                        <label class="sw-label">
                            Diễn viên
                        </label>

                        <select
                            name="movie_actor[]"
                            id="movie_actor"
                            multiple>

                            @foreach($actors as $actor)

                            <option
                                value="{{ $actor->id }}"
                                {{ in_array(
                        $actor->id,
                        old(
                            'movie_actor',
                            $movie->actors->pluck('id')->toArray()
                        )
                    ) ? 'selected' : '' }}>

                                {{ $actor->name }}

                            </option>

                            @endforeach

                        </select>
                        @error('movie_actor')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror

                    </div>

                    {{-- ĐẠO DIỄN --}}
                    <div class="col-md-4">

                        <label class="sw-label">
                            Đạo diễn
                        </label>

                        <select
                            name="movie_director[]"
                            id="movie_director"
                            multiple>

                            @foreach($directors as $director)

                            <option
                                value="{{ $director->id }}"
                                {{ in_array(
                        $director->id,
                        old(
                            'movie_director',
                            $movie->directors->pluck('id')->toArray()
                        )
                    ) ? 'selected' : '' }}>

                                {{ $director->name }}

                            </option>

                            @endforeach

                        </select>
                        @error('movie_director')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- MEDIA --}}
            <div class="sw-card">
                <div class="sw-section-label">
                    03 — Hình ảnh & trailer
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="sw-label">Poster</label>

                        <input type="file"
                            name="movie_poster"
                            class="sw-input"
                            accept="image/*"
                            onchange="previewImage(event, 'posterPreview')">
                        @error('movie_poster')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Logo</label>

                        <input type="file"
                            name="movie_logo"
                            class="sw-input"
                            accept="image/*"
                            onchange="previewImage(event, 'logoPreview')">
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Thumbnail</label>

                        <input type="file"
                            name="movie_thumbnail"
                            class="sw-input"
                            accept="image/*"
                            onchange="previewImage(event, 'thumbnailPreview')">
                        @error('movie_thumbnail')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="sw-label">Trailer phim</label>

                        <input type="file"
                            name="movie_trailer"
                            class="sw-input"
                            accept="video/*">

                        @error('movie_trailer')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="row mt-4 g-4">

                    {{-- POSTER --}}
                    <div class="col-md-3">

                        <div class="movie-preview">

                            <img
                                id="posterPreview"
                                src="{{ $movie->poster
                    ? asset('storage/img/movie_poster/' . $movie->poster)
                    : 'https://placehold.co/300x450?text=Poster' }}">

                            <div class="movie-preview-info">

                                <div class="movie-preview-title">
                                    Poster
                                </div>

                                <div class="movie-preview-meta">
                                    Ảnh poster hiện tại
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- LOGO --}}
                    <div class="col-md-3">

                        <div class="movie-preview">

                            <img
                                id="logoPreview"
                                src="{{ $movie->logo
                    ? asset('storage/img/movie_logo/' . $movie->logo)
                    : 'https://placehold.co/300x450?text=Logo' }}">

                            <div class="movie-preview-info">

                                <div class="movie-preview-title">
                                    Logo
                                </div>

                                <div class="movie-preview-meta">
                                    Logo hiện tại
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- THUMBNAIL --}}
                    <div class="col-md-6">

                        <div class="movie-preview">

                            <img
                                id="thumbnailPreview"
                                src="{{ $movie->thumbnail
                    ? asset('storage/img/movie_thumbnail/' . $movie->thumbnail)
                    : 'https://placehold.co/300x450?text=Thumbnail' }}">

                            <div class="movie-preview-info">

                                <div class="movie-preview-title">
                                    Thumbnail
                                </div>

                                <div class="movie-preview-meta">
                                    Thumbnail hiện tại
                                </div>

                            </div>

                        </div>

                    </div>
                   @if($movie->trailer)

                    <div class="col-md-12">

                        <div class="movie-preview">

                            <video
                                controls
                                style="
                                    width: 100%;
                                    max-width: 700px;
                                    aspect-ratio: 16 / 9;
                                    border-radius: 10px;
                                    background: #000;
                                    object-fit: cover;
                                ">

                                <source
                                    src="{{ asset('storage/video/movie_trailer/' . $movie->trailer) }}"
                                    type="video/mp4">

                            </video>

                            <div class="movie-preview-info">

                                <div class="movie-preview-title">
                                    Trailer
                                </div>

                                <div class="movie-preview-meta">
                                    Trailer hiện tại của phim
                                </div>

                            </div>

                        </div>

                    </div>

                    @endif

                </div>


            </div>

            {{-- FOOTER --}}
            <div class="sw-footer">

                <a href="{{ route('admin.movies.index') }}"
                    class="btn-cancel">
                    Hủy
                </a>

                <button type="submit"
                    class="btn-submit">
                    Cập nhật phim
                </button>

            </div>

        </form>
    </div>
</div>

<script>
    new TomSelect('#movie_genre', {
        plugins: ['remove_button'],
        placeholder: 'Chọn thể loại'
    });

    new TomSelect('#movie_actor', {
        plugins: ['remove_button'],
        placeholder: 'Chọn diễn viên'
    });

    new TomSelect('#movie_director', {
        plugins: ['remove_button'],
        placeholder: 'Chọn đạo diễn'
    });

    new TomSelect('#movie_country', {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: 'Chọn quốc gia'
    });

    function previewImage(event, id) {

        const file = event.target.files[0];

        if (file) {

            document.getElementById(id).src =
                URL.createObjectURL(file);
        }
    }
</script>

@endsection