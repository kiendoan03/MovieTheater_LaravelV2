@extends('layouts.management')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
  <div class="container-fluid px-3 px-md-5">
    <div class="cw-head">
      <div>
        <h2>Chỉnh sửa đạo diễn</h2>
      </div>
      <a href="{{ route('admin.directors.index') }}" class="btn-cancel">Quay lại</a>
    </div>

    <form method="POST" action="{{ route('admin.directors.update', $director) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="cw-card">
        <div class="row g-4">
          <div class="col-md-8">
            <label class="cw-label" for="name">Tên đạo diễn</label>
            <input type="text" id="name" name="name" class="cw-input" value="{{ old('name', $director->name) }}" placeholder="Nhập tên đạo diễn" required>
            @error('name')<span class="cw-error">{{ $message }}</span>@enderror
          </div>
          <div class="col-md-4">
            <label class="cw-label" for="image">Ảnh đạo diễn</label>
            <div class="cw-image-card">
              <img id="preview" class="cw-img-preview" src="{{ $director->image ? asset(\Illuminate\Support\Facades\Storage::url('img/director/').$director->image) : 'https://static.vecteezy.com/system/resources/thumbnails/060/605/418/small/default-avatar-profile-icon-social-media-user-free-vector.jpg' }}" alt="Ảnh đạo diễn">
              <input type="file" id="image" name="image" class="cw-input cw-file" accept="image/png, image/jpg, image/jpeg" onchange="show_img()">
            </div>
            @error('image')<span class="cw-error">{{ $message }}</span>@enderror
          </div>
        </div>
      </div>

      <div class="cw-footer">
        <a href="{{ route('admin.directors.index') }}" class="btn-cancel">Hủy</a>
        <button type="submit" class="btn-submit">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>

<script>
  const defaultPreview = 'https://static.vecteezy.com/system/resources/thumbnails/060/605/418/small/default-avatar-profile-icon-social-media-user-free-vector.jpg';
  function show_img() {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    const file = input.files[0];
    if (!file) {
      preview.src = defaultPreview;
      return;
    }
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
</script>
@endsection
