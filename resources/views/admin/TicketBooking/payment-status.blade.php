@extends('layouts.management')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body p-5">
                    <div id="statusContent">
                        <div class="spinner-border mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Đang kiểm tra trạng thái thanh toán...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        checkPaymentStatus('{{ $ticket->code }}');
    });

    function checkPaymentStatus(ticketCode) {
        fetch(`/api/ticket-booking/check-payment-status/${ticketCode}`)
            .then(response => response.json())
            .then(data => {
                const content = document.getElementById('statusContent');
                
                if (data.status === 'completed') {
                    content.innerHTML = `
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h4>Thanh toán thành công!</h4>
                            <p>Vé của bạn đã được xác nhận</p>
                        </div>
                        <div class="alert alert-info">
                            <strong>Mã vé:</strong> ${ticketCode}
                        </div>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> In vé
                        </button>
                        <button class="btn btn-secondary" onclick="window.location.href='/Admin/TicketBooking'">
                            Đặt vé khác
                        </button>
                    `;
                } else if (data.status === 'cancelled') {
                    content.innerHTML = `
                        <div class="alert alert-danger mb-3">
                            <i class="fas fa-times-circle fa-3x mb-3"></i>
                            <h4>Thanh toán bị hủy</h4>
                        </div>
                        <button class="btn btn-secondary" onclick="window.location.href='/Admin/TicketBooking'">
                            Quay lại
                        </button>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-clock fa-3x mb-3"></i>
                            <h4>Đang chờ xác nhận</h4>
                            <p>Vui lòng chờ...</p>
                        </div>
                        <button class="btn btn-secondary" onclick="location.reload()">
                            Tải lại
                        </button>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('statusContent').innerHTML = `
                    <div class="alert alert-danger">
                        Lỗi: ${error.message}
                    </div>
                `;
            });
    }
</script>
@endsection
