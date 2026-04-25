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
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        padding: 10px 12px;
        outline: none;
        transition: border-color .2s;
    }

    .sw-input:focus,
    .sw-select:focus,
    textarea:focus {
        border-color: var(--accent);
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
        align-items: flex-start;
    }

    .movie-preview img {
        width: 90px;
        height: 130px;
        border-radius: 8px;
        object-fit: cover;
        background: #0f1117;
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
        transition: all .2s;
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
        transition: all .2s;
    }

    .btn-submit:hover {
        background: #f0d47a;
    }

    .ts-control,
    .ts-dropdown {
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        color: var(--text) !important;
        border-radius: 8px !important;
    }

    .ts-control input {
        color: var(--text) !important;
    }

    .ts-dropdown .option {
        background: var(--surface);
        color: var(--text);
    }

    .ts-dropdown .active {
        background: rgba(255, 255, 255, .08) !important;
    }

    .ts-wrapper.multi .ts-control>div {
        background: var(--accent-bg);
        color: var(--accent);
        border: 1px solid rgba(232, 201, 106, .2);
        border-radius: 999px;
        padding: 2px 8px;
    }

    textarea {
        resize: none;
        min-height: 120px;
    }
</style>

<div class="sw">
    <div class="container-fluid px-3 px-md-4">

        <div class="sw-head">
            <h2>Thêm phim mới</h2>
            <span class="sw-crumb">Admin / Movie / Create</span>
        </div>

        <form method="POST"
            action="{{ route('admin.movies.store') }}"
            enctype="multipart/form-data">

            @csrf

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
                            value="{{ old('movie_name') }}"
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
                            value="{{ old('movie_length') }}"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="sw-label">Độ tuổi</label>
                        <input type="number"
                            name="movie_age"
                            class="sw-input"
                            value="{{ old('movie_age') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Ngày khởi chiếu</label>
                        <input type="date"
                            name="movie_release_date"
                            class="sw-input"
                            value="{{ old('movie_release_date') }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Ngày kết thúc</label>
                        <input type="date"
                            name="movie_end_date"
                            class="sw-input"
                            value="{{ old('movie_end_date') }}"
                            required>
                    </div>

                    {{-- NGÔN NGỮ --}}
                    <div class="col-md-6">
                        <label class="sw-label">Ngôn ngữ</label>

                        <input type="text"
                            name="movie_language"
                            class="sw-input"
                            placeholder="Ví dụ: English, Tiếng Việt, Japanese..."
                            value="{{ old('movie_language') }}"
                            required>

                        @error('movie_language')
                        <span class="sw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- QUỐC GIA --}}
                    <div class="col-md-6">
                        <label class="sw-label">Quốc gia</label>

                        <select name="movie_country"
                            id="movie_country"
                            required>

                            <option value="">Chọn quốc gia</option>

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

                            @foreach($countries as $country)
                            <option value="{{ $country }}"
                                {{ old('movie_country') == $country ? 'selected' : '' }}>
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

                        <textarea name="movie_description">{{ old('movie_description') }}</textarea>
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
                        <label class="sw-label">Thể loại</label>

                        <select name="movie_genre[]"
                            id="movie_genre"
                            multiple>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DIỄN VIÊN --}}
                    <div class="col-md-4">
                        <label class="sw-label">Diễn viên</label>

                        <select name="movie_actor[]"
                            id="movie_actor"
                            multiple>
                            @foreach($actors as $actor)
                            <option value="{{ $actor->id }}">
                                {{ $actor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ĐẠO DIỄN --}}
                    <div class="col-md-4">
                        <label class="sw-label">Đạo diễn</label>

                        <select name="movie_director[]"
                            id="movie_director"
                            multiple>
                            @foreach($directors as $director)
                            <option value="{{ $director->id }}">
                                {{ $director->name }}
                            </option>
                            @endforeach
                        </select>
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
                            onchange="previewPoster(event)">
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Logo</label>

                        <input type="file"
                            name="movie_logo"
                            class="sw-input"
                            accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label class="sw-label">Thumbnail</label>

                        <input type="file"
                            name="movie_thumbnail"
                            class="sw-input"
                            accept="image/*">
                    </div>

                    <div class="col-md-12"> <label class="sw-label">Link trailer YouTube</label> <input type="url" name="movie_trailer" class="sw-input" placeholder="https://www.youtube.com/watch?v=xxxx" value="{{ old('movie_trailer') }}"> </div>

                </div>

                <div class="movie-preview mt-4">
                    <img id="posterPreview"
                        src="https://placehold.co/300x450?text=Poster">

                    <div class="movie-preview-info">
                        <div class="movie-preview-title">
                            Preview poster
                        </div>

                        <div class="movie-preview-meta">
                            Poster phim sẽ hiển thị ở đây sau khi upload.
                        </div>
                    </div>
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
                    Lưu phim
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

    function previewPoster(event) {
        const file = event.target.files[0];

        if (file) {
            document.getElementById('posterPreview').src =
                URL.createObjectURL(file);
        }
    }
</script>

@endsection