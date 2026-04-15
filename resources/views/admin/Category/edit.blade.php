@extends('layouts.management')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
  <div class="container-fluid px-3 px-md-5">
    <div class="cw-head">
      <div>
        <h2>Chỉnh sửa danh mục</h2>
        <div class="cw-crumb">Quản lý danh mục</div>
      </div>
      <a href="{{ route('admin.categories.index') }}" class="btn-cancel">Quay lại</a>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
      @csrf
      @method('PUT')
      <div class="cw-card">
        <label class="cw-label" for="name">Tên danh mục</label>
        <input type="text" id="name" name="name" class="cw-input" value="{{ old('name', $category->name) }}" placeholder="Nhập tên danh mục" required>
        @error('name')<span class="cw-error">{{ $message }}</span>@enderror
      </div>

      <div class="cw-footer">
        <a href="{{ route('admin.categories.index') }}" class="btn-cancel">Hủy</a>
        <button type="submit" class="btn-submit">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>
@endsection
