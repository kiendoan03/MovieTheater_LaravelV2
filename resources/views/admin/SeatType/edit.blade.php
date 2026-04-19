@extends('layouts.management')
@section('content')

<style>
    body {
        background: var(--bg);
        color: var(--text);
    }

    .cw {
        font-family: 'Sora', sans-serif;
        padding: 2rem 0 5rem;
    }

    .cw-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .cw-head h2 {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }

    .cw-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
    }

    .cw-section-title {
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

    .cw-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .cw-label-line {
        margin-bottom: 6px;
    }

    .cw-label-line span {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--muted);
    }

    .cw-input {
        width: 100%;
        background: var(--surface) !important;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        color: var(--text) !important;
        outline: none;
        transition: all .2s;
    }

    .cw-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 1px rgba(232, 201, 106, 0.25);
    }

    .cw-input::placeholder {
        color: var(--muted);
    }

    .cw-input:-webkit-autofill,
    .cw-input:-webkit-autofill:hover,
    .cw-input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--text);
        -webkit-box-shadow: 0 0 0px 1000px var(--surface) inset;
    }

    input,
    textarea {
        background-color: var(--surface) !important;
        color: var(--text) !important;
    }

    input[type="color"] {
        width: 100%;
        height: 42px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 4px;
        background: var(--surface);
        cursor: pointer;
    }

    input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 6px;
    }

    input[type="color"]::-moz-color-swatch {
        border: none;
        border-radius: 6px;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 13px;
        color: var(--muted);
        text-decoration: none;
    }

    .btn-cancel:hover {
        border-color: var(--border-h);
        color: var(--text);
    }

    .btn-submit {
        background: var(--accent);
        color: #0d0f14;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-submit:hover {
        background: #f0d47a;
    }

    .cw-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 1.5rem;
    }

    .cw-error {
        color: var(--danger);
        font-size: 11px;
        margin-top: 4px;
        display: block;
    }
</style>

<div class="cw">
    <div class="container-fluid px-3 px-md-5">

        <!-- Header -->
        <div class="cw-head">
            <div>
                <h2>Chỉnh sửa loại ghế</h2>
            </div>
        </div>

        <!-- FORM EDIT -->
        <form method="POST" action="{{ route('admin.seat_types.update', $seatType) }}">
            @csrf
            @method('PUT')

            <div class="cw-card">

                <div class="cw-section-title">
                    Thông tin loại ghế
                </div>

                <div class="row g-3">

                    <!-- TYPE -->
                    <div class="col-md-4">
                        <div class="cw-label-line">
                            <span>Loại ghế</span>
                        </div>

                        <input type="text"
                            name="type"
                            class="cw-input"
                            value="{{ old('type', $seatType->type) }}"
                            placeholder="Nhập loại ghế"
                            required>

                        @error('type')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- PRICE -->
                    <div class="col-md-4">
                        <div class="cw-label-line">
                            <span>Giá</span>
                        </div>

                        <input type="number"
                            step="0.01"
                            name="price"
                            class="cw-input"
                            min="0"
                            value="{{ old('price', $seatType->price) }}"
                            placeholder="Nhập giá ghế"
                            required>

                        @error('price')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- COLOR -->
                    <div class="col-md-4">
                        <div class="cw-label-line">
                            <span>Màu ghế</span>
                        </div>

                        <input type="color"
                            name="color"
                            value="{{ old('color', $seatType->color ?? '#6366f1') }}">

                        @error('color')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="cw-footer">
                <a href="{{ route('admin.seat_types.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-submit">Lưu thay đổi</button>
            </div>

        </form>
    </div>
</div>

@endsection