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
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .cw-head h2 {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }

    .cw-crumb {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--muted);
        background: var(--card);
        border: 1px solid var(--border);
        padding: 3px 10px;
        border-radius: 20px;
        margin-top: 6px;
        display: inline-block;
    }

    .cw-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
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
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }

    .cw-label-line span {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--muted);
    }

    /* ===== CHỈ ẨN LINE Ở LABEL CẦN ===== */
    .no-line::after {
        display: none;
    }

    .cw-label-line::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .cw-input {
        width: 100%;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13px;
        font-family: 'Sora', sans-serif;
        color: var(--text);
        outline: none;
        transition: all .2s;
    }

    .cw-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 1px rgba(232, 201, 106, 0.2);
    }

    .cw-input::placeholder {
        color: var(--muted);
    }

    /* FIX trắng */
    input,
    select,
    textarea {
        background-color: var(--surface) !important;
        color: var(--text) !important;
    }

    select:focus,
    select:active {
        background-color: var(--surface) !important;
        color: var(--text) !important;
    }

    select option {
        background: #0b1220;
        color: #fff;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    select:-webkit-autofill {
        -webkit-text-fill-color: var(--text);
        -webkit-box-shadow: 0 0 0px 1000px var(--surface) inset;
        transition: background-color 9999s ease-in-out 0s;
    }

    /* BUTTON */
    .btn-cancel {
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 13px;
        color: var(--muted);
        text-decoration: none;
    }

    .btn-submit {
        background: var(--accent);
        color: #0d0f14;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 600;
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
    <div class="container-fluid px-3 px-md-4">

        <div class="cw-head">
            <div>
                <h2>Thêm loại ghế</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.seat_types.store') }}">
            @csrf

            <div class="cw-card">

                <div class="cw-section-title">
                    Thông tin loại ghế
                </div>

                <div class="row g-3">

                    <!-- Type -->
                    <div class="col-md-6">
                        <div class="cw-label-line no-line">
                            <span>Loại ghế</span>
                        </div>

                        <input type="text"
                            name="type"
                            class="cw-input"
                            value="{{ old('type') }}"
                            placeholder="Nhập loại ghế"
                            required>

                        @error('type')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <div class="cw-label-line no-line">
                            <span>Giá</span>
                        </div>

                        <input type="number"
                            step="0.01"
                            name="price"
                            class="cw-input"
                            min="0"
                            value="{{ old('price') }}"
                            placeholder="Nhập giá ghế"
                            required>

                        @error('price')
                        <span class="cw-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="cw-footer">
                <a href="{{ route('admin.seat_types.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-submit">Lưu loại ghế</button>
            </div>

        </form>
    </div>
</div>

@endsection