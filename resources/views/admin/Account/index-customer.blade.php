@extends('layouts.management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

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

        /* Pagination */
        .cw-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.25rem;
            gap: 4px;
        }

        .cw-pagination .page-link {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            transition: .15s;
        }

        .cw-pagination .page-link:hover,
        .cw-pagination .page-link.active {
            border-color: var(--accent);
            color: var(--accent);
        }

        .cw-pagination .page-link.disabled {
            opacity: 0.35;
            pointer-events: none;
        }

        .pagination-info {
            font-size: 12px;
            color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            text-align: center;
            padding-bottom: 0.5rem;
        }
    </style>

    <div class="cw">
        <div class="container-fluid px-3 px-md-5">

            <div class="cw-head">
                <div>
                    <h2>Quản lý khách hàng</h2>
                    <div class="cw-count">Tổng số: {{ $customers->total() }} khách hàng</div>
                </div>
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
                            <th class="text-center" width="130">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $account)
                            <tr>
                                <td class="mono-id">
                                    {{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}
                                </td>

                                <td>
                                    <span class="room-main">{{ $account->customer?->name ?? '—' }}</span>
                                    <span class="room-sub" style="font-family:'JetBrains Mono',monospace;font-size:11px;">
                                        {{ $account->email }}
                                    </span>
                                </td>

                                <td>
                                    <span class="room-main">{{ $account->customer?->phonenumber ?? '—' }}</span>
                                </td>

                                <td>
                                    @if ($account->customer?->date_of_birth)
                                        <span class="room-main">
                                            {{ \Carbon\Carbon::parse($account->customer->date_of_birth)->format('d/m/Y') }}
                                        </span>
                                        <span class="room-sub">
                                            {{ \Carbon\Carbon::parse($account->customer->date_of_birth)->age }} tuổi
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

                                        <a href="{{ route('admin.accounts.customer.show', $account->id) }}"
                                            class="btn-circle" title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.accounts.customer.edit', $account->id) }}"
                                            class="btn-circle" title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('admin.accounts.customer.destroy', $account->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Xác nhận vô hiệu hoá khách hàng {{ addslashes($account->customer?->name ?? $account->email) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-circle btn-del" title="Vô hiệu hoá">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($customers->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">👤</div>
                        <p>Chưa có khách hàng nào.</p>
                    </div>
                @endif

                {{-- PAGINATION --}}
                @if ($customers->hasPages())
                    <div class="pagination-info">
                        Hiển thị {{ $customers->firstItem() }} tới {{ $customers->lastItem() }}
                        của {{ $customers->total() }} dữ liệu
                    </div>
                    <div class="cw-pagination">
                        {{-- Trước --}}
                        @if ($customers->onFirstPage())
                            <span class="page-link disabled">← Trước</span>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}" class="page-link">← Trước</a>
                        @endif

                        {{-- Số trang --}}
                        @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                            <a href="{{ $url }}"
                                class="page-link {{ $customers->currentPage() == $page ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        {{-- Sau --}}
                        @if ($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}" class="page-link">Sau →</a>
                        @else
                            <span class="page-link disabled">Sau →</span>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
