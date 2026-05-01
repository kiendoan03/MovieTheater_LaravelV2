@extends('layouts.client')
@section('title', 'Chỉnh sửa hồ sơ · NetFnix')

@push('styles')
    <style>
        .edit-page {
            padding: 110px 0 80px;
            min-height: 100vh;
        }

        /* Back link */
        .btn-back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: rgba(255, 255, 255, .45);
            font-size: 13px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 999px;
            padding: 5px 16px;
            transition: .2s;
            margin-bottom: 1.5rem;
        }

        .btn-back-link:hover {
            border-color: rgba(255, 255, 255, .25);
            color: #fff;
        }

        /* Card */
        .edit-card {
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 24px;
            padding: 2.25rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .edit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff1f45, #ff4d6d, transparent);
        }

        /* Section label */
        .s-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .38);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .s-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .07);
        }

        /* Avatar preview area */
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
        }

        .avatar-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 31, 69, .3), rgba(255, 31, 69, .06));
            border: 2px solid rgba(255, 31, 69, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #ff4468;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 0 22px rgba(255, 31, 69, .18);
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-hint {
            font-size: 13px;
            color: rgba(255, 255, 255, .5);
            line-height: 1.6;
        }

        .avatar-hint strong {
            color: rgba(255, 255, 255, .8);
        }

        /* Form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-full {
            grid-column: 1 / -1;
        }

        /* Form group */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .5);
        }

        .form-control-dark {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 12px;
            color: #fff;
            padding: 11px 16px;
            font-size: 14px;
            transition: border-color .2s, background .2s;
            width: 100%;
            outline: none;
            font-family: 'Poppins', sans-serif;
        }

        .form-control-dark:focus {
            border-color: rgba(255, 31, 69, .5);
            background: rgba(255, 31, 69, .06);
            box-shadow: 0 0 0 3px rgba(255, 31, 69, .12);
        }

        .form-control-dark::placeholder {
            color: rgba(255, 255, 255, .25);
        }

        /* Validation errors */
        .field-error {
            font-size: 12px;
            color: #f87171;
            margin-top: 3px;
        }

        .alert-errors {
            background: rgba(239, 68, 68, .1);
            border: 1px solid rgba(239, 68, 68, .22);
            color: #f87171;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            font-size: 13px;
            margin-bottom: 1.5rem;
        }

        .alert-errors ul {
            margin: 6px 0 0 1rem;
            padding: 0;
        }

        .alert-errors li {
            margin-bottom: 3px;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.75rem;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #ff1f45, #ff4d6d);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 11px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .25s;
            box-shadow: 0 8px 22px rgba(255, 31, 69, .28);
            font-family: 'Montserrat', sans-serif;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(255, 31, 69, .42);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: rgba(255, 255, 255, .45);
            font-size: 13px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 999px;
            padding: 11px 22px;
            transition: .2s;
            background: transparent;
            font-family: 'Poppins', sans-serif;
        }

        .btn-cancel:hover {
            border-color: rgba(255, 255, 255, .25);
            color: #fff;
        }

        /* Readonly info */
        .readonly-field {
            background: rgba(255, 255, 255, .025);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 14px;
            color: rgba(255, 255, 255, .45);
            font-family: monospace;
        }

        /* ── Avatar Drop Zone ── */
        .avatar-drop-zone {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border: 2px dashed rgba(255, 31, 69, .3);
            border-radius: 14px;
            padding: 1.5rem;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            text-align: center;
            background: rgba(255, 31, 69, .03);
        }

        .avatar-drop-zone:hover,
        .avatar-drop-zone.drag-over {
            border-color: rgba(255, 31, 69, .7);
            background: rgba(255, 31, 69, .07);
        }

        .avatar-drop-zone.has-file {
            border-color: rgba(34, 197, 94, .5);
            background: rgba(34, 197, 94, .04);
        }

        .drop-main {
            font-size: 13px;
            color: rgba(255, 255, 255, .7);
        }

        .drop-main u {
            color: #ff4468;
        }

        .drop-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
        }

        .drop-filename {
            font-size: 12px;
            color: #4ade80;
            font-family: monospace;
            margin-top: 4px;
            word-break: break-all;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .avatar-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-row {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-save,
            .btn-cancel {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="edit-page">
        <div class="container">
            <div style="max-width: 760px; margin: 0 auto;">

                <a href="{{ route('customer.profile.show') }}" class="btn-back-link">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại hồ sơ
                </a>

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert-errors">
                        <strong><i class="fa-solid fa-triangle-exclamation"></i> Vui lòng kiểm tra lại thông tin:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="edit-card">
                        <div class="s-label">Thông tin cơ bản</div>

                        @php $cust = $account->customer; @endphp

                        {{-- Avatar upload --}}
                        <div class="avatar-section">
                            <div class="avatar-preview" id="avatarPreview">
                                @if ($cust?->avatar)
                                    <img src="{{ asset('storage/img/avatars/' . $cust->avatar) }}" alt=""
                                        id="avatarImg">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="" id="avatarImg">
                                @endif
                            </div>
                            <div class="avatar-hint">
                                <strong>Ảnh đại diện</strong><br>
                                Chọn ảnh từ máy tính (JPG, PNG, GIF, WebP — tối đa 2MB).<br>
                                Ảnh sẽ xem trước ngay lập tức.
                            </div>
                        </div>

                        {{-- Hidden file input triggered by drop zone --}}
                        <div class="form-group form-full" style="margin-bottom:0;">
                            <label class="form-label" for="avatar">Ảnh đại diện</label>
                            <label for="avatarFile" class="avatar-drop-zone" id="avatarDropZone">
                                <i class="fa-solid fa-cloud-arrow-up"
                                    style="font-size:24px;color:rgba(255,31,69,.6);margin-bottom:8px;"></i>
                                <span class="drop-main">Kéo thả ảnh vào đây hoặc <u>chọn file</u></span>
                                <span class="drop-sub">JPG, PNG, GIF, WebP · Tối đa 2MB</span>
                                <span class="drop-filename" id="dropFilename"></span>
                            </label>
                            <input type="file" id="avatarFile" name="avatar" accept="image/*" style="display:none;">
                            @error('avatar')
                                <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-grid">
                            {{-- Name --}}
                            <div class="form-group form-full">
                                <label class="form-label" for="name">Họ và tên <span
                                        style="color:#ff4468;">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control-dark @error('name') is-invalid @enderror"
                                    value="{{ old('name', $cust?->name) }}" placeholder="Nhập họ và tên đầy đủ">
                                @error('name')
                                    <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label class="form-label" for="phonenumber">Số điện thoại <span
                                        style="color:#ff4468;">*</span></label>
                                <input type="tel" id="phonenumber" name="phonenumber"
                                    class="form-control-dark @error('phonenumber') is-invalid @enderror"
                                    value="{{ old('phonenumber', $cust?->phonenumber) }}" placeholder="0xxxxxxxxx">
                                @error('phonenumber')
                                    <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Date of birth --}}
                            <div class="form-group">
                                <label class="form-label" for="date_of_birth">Ngày sinh <span
                                        style="color:#ff4468;">*</span></label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                    class="form-control-dark @error('date_of_birth') is-invalid @enderror"
                                    value="{{ old('date_of_birth', $cust?->date_of_birth ? \Carbon\Carbon::parse($cust->date_of_birth)->format('Y-m-d') : '') }}"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_of_birth')
                                    <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="form-group form-full">
                                <label class="form-label" for="address">Địa chỉ</label>
                                <input type="text" id="address" name="address"
                                    class="form-control-dark @error('address') is-invalid @enderror"
                                    value="{{ old('address', $cust?->address) }}"
                                    placeholder="Số nhà, đường, quận/huyện, tỉnh/thành phố">
                                @error('address')
                                    <span class="field-error"><i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}</span>
                                @enderror
                            </div>



                            {{-- Email (readonly) --}}
                            <div class="form-group">
                                <label class="form-label">Email (không thể thay đổi)</label>
                                <div class="readonly-field">{{ $account->email }}</div>
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn-save">
                                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('customer.profile.show') }}" class="btn-cancel">
                                <i class="fa-solid fa-xmark"></i> Huỷ bỏ
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const fileInput = document.getElementById('avatarFile');
        const dropZone = document.getElementById('avatarDropZone');
        const preview = document.getElementById('avatarPreview');
        const dropFilename = document.getElementById('dropFilename');

        function applyPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            dropFilename.textContent = file.name;
            const reader = new FileReader();
            reader.onload = e => {
                let img = preview.querySelector('img');
                const icon = preview.querySelector('i');
                if (icon) icon.remove();
                if (!img) {
                    img = document.createElement('img');
                    preview.appendChild(img);
                }
                img.src = e.target.result;
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            };
            reader.readAsDataURL(file);
            dropZone.classList.add('has-file');
        }

        fileInput.addEventListener('change', () => applyPreview(fileInput.files[0]));

        // Drag & drop
        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                applyPreview(file);
            }
        });
    </script>
@endpush
