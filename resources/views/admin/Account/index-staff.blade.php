@extends('layouts.management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="cw">
        <div class="container-fluid px-3 px-md-5">

            <div class="cw-head">
                <div>
                    <h2>Quản lý nhân viên</h2>
                    {{-- $staffs là collection Account, mỗi item là 1 Account có eager load staff --}}
                    <div class="cw-count">Tổng số: {{ $staffs->total() }} nhân viên</div>
                </div>
                <a href="{{ route('admin.accounts.staff.create') }}" class="btn-new">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Thêm nhân viên mới
                </a>
            </div>

            <div class="cw-card">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>Số điện thoại</th>
                            <th>Ngày sinh</th>
                            <th>Trạng thái</th>
                            <th class="text-center" width="100">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- $account là 1 Account, $account->staff là Staff liên kết --}}
                        @foreach ($staffs as $account)
                            <tr>
                                <td class="mono-id">
                                    {{ $loop->iteration + ($staffs->currentPage() - 1) * $staffs->perPage() }}
                                </td>

                                <td>
                                    <span class="room-main">{{ $account->staff?->name ?? '—' }}</span>
                                </td>

                                <td>
                                    <span class="room-main">{{ $account->staff?->phonenumber ?? '—' }}</span>
                                </td>

                                <td>
                                    @if ($account->staff?->date_of_birth)
                                        <span class="room-main">
                                            {{ \Carbon\Carbon::parse($account->staff->date_of_birth)->format('d/m/Y') }}
                                        </span>
                                        <span class="room-sub">
                                            {{ \Carbon\Carbon::parse($account->staff->date_of_birth)->age }} tuổi
                                        </span>
                                    @else
                                        <span class="room-main">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($account->is_active)
                                        <span class="badge-status badge-active">
                                            <i class="fa-solid fa-circle" style="font-size:7px;"></i> Hoạt động
                                        </span>
                                    @else
                                        <span class="badge-status badge-inactive">
                                            <i class="fa-solid fa-circle" style="font-size:7px;"></i> Vô hiệu
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group-actions btn-group-actions-right">

                                        <a href="{{ route('admin.accounts.staff.show', $account) }}" class="btn-circle"
                                            title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.accounts.staff.edit', $account) }}" class="btn-circle"
                                            title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('admin.accounts.staff.destroy', $account) }}" method="POST"
                                            onsubmit="return confirm('Xác nhận xóa nhân viên {{ addslashes($account->staff?->name ?? $account->email) }}?')">
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

                @if ($staffs->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">👤</div>
                        <p>Chưa có nhân viên nào được thêm.</p>
                    </div>
                @endif

                @if ($staffs->hasPages())
                    <div class="pagination-wrapper">
                        {{ $staffs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }
    </style>
@endsection
