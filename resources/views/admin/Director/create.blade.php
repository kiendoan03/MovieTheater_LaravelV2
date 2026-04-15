@extends('layouts.management')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
  <div class="container-fluid px-3 px-md-5">
    <div class="cw-head">
      <div>
        <h2>Thêm đạo diễn</h2>
        <div class="cw-crumb">Quản lý đạo diễn</div>
      </div>
      <a href="{{ route('admin.directors.index') }}" class="btn-cancel">Quay lại</a>
    </div>

    <form method="POST" action="{{ route('admin.directors.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="cw-card">
        <div class="row g-4">
          <div class="col-md-8">
            <label class="cw-label" for="name">Tên đạo diễn</label>
            <input type="text" id="name" name="name" class="cw-input" value="{{ old('name') }}" placeholder="Nhập tên đạo diễn" required>
            @error('name')<span class="cw-error">{{ $message }}</span>@enderror
          </div>
          <div class="col-md-4">
            <label class="cw-label" for="image">Ảnh đạo diễn</label>
            <input type="file" id="image" name="image" class="cw-input cw-file" accept="image/png, image/jpg, image/jpeg" onchange="show_img()" required>
            <img id="preview" class="cw-img-preview hidden" src="" alt="Ảnh xem trước">
            @error('image')<span class="cw-error">{{ $message }}</span>@enderror
          </div>
        </div>
      </div>

      <div class="cw-footer">
        <a href="{{ route('admin.directors.index') }}" class="btn-cancel">Hủy</a>
        <button type="submit" class="btn-submit">Lưu đạo diễn</button>
      </div>
    </form>
  </div>
</div>

<script>
  function show_img() {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
</script>
@endsection
