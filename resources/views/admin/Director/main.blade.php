@extends('layouts.header')
@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="cw">
  <div class="container-fluid px-3 px-md-5">

    <div class="cw-head">
      <div>
        <h2>Quản lý đạo diễn</h2>
        <div class="cw-count">Tổng số: {{ $directors->count() }} đạo diễn</div>
      </div>
      <a href="{{ route('admin.directors.create') }}" class="btn-new">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tạo đạo diễn mới
      </a>
    </div>

    <div class="cw-card">
      <table class="table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Thông tin đạo diễn</th>
            <th>Ảnh đạo diễn</th>
            <th class="text-center" width="16">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($directors as $director)
          <tr>
            <td class="mono-id">
              #{{ str_pad($director->id, 3, '0', STR_PAD_LEFT) }}
            </td>
            <td>
              <span class="room-main">{{ $director->name }}</span>
              <span class="room-sub">Cập nhật: {{ $director->updated_at->format('d/m/Y') }}</span>
            </td>
            <td>
              <img class="rounded-image" src="{{ asset(\Illuminate\Support\Facades\Storage::url('img/director/'). $director->image) }}" alt="{{ $director->name }}">
            </td>
            <td class="text-center">
              <div class="btn-group-actions btn-group-actions-right">
                <a href="{{ route('admin.directors.edit', $director) }}" class="btn-circle" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('admin.directors.destroy', $director) }}" method="POST" onsubmit="return confirm('Xác nhận xóa đạo diễn này?')">
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

      @if($directors->isEmpty())
        <div class="empty-state">
          <div class="empty-state-icon">🎬</div>
          <p>Chưa có đạo diễn nào được thêm.</p>
        </div>
      @endif
    </div>

  </div>
</div>

@endsection

