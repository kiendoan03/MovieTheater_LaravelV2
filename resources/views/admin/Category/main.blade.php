@extends('layouts.header')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
  <div class="container-fluid px-3 px-md-5">

    <div class="cw-head">
      <div>
        <h2>Quản lý danh mục</h2>
        <div class="cw-count">Tổng số: {{ $categories->count() }} danh mục</div>
      </div>
      <a href="{{ route('admin.categories.create') }}" class="btn-new">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tạo danh mục mới
      </a>
    </div>

    <div class="cw-card">
      <table class="table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Danh mục</th>
            <th class="text-center" width="16">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $category)
          <tr>
            <td class="mono-id">
              #{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
            </td>
            <td>
              <span class="room-main">{{ $category->name }}</span>
            </td>
            <td class="text-center">
              <div class="btn-group-actions btn-group-actions-right">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn-circle" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Xác nhận xóa danh mục này?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-circle btn-del" title="Xóa">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      @if($categories->isEmpty())
        <div class="empty-state">
          <div class="empty-state-icon">🏷️</div>
          <p>Chưa có danh mục nào được tạo.</p>
        </div>
      @endif
    </div>

  </div>
</div>

@endsection
