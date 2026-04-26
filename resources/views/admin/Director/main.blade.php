@extends('layouts.management')
@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

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
      <table class="table-custom" id="directorsTable">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script>
  $(document).ready(function() {
    $('#directorsTable').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json"
      },
      "pageLength": 10,
      "responsive": true,
      "columnDefs": [
        {
          "targets": -1,
          "orderable": false
        }
      ]
    });
  });
</script>

@endsection

