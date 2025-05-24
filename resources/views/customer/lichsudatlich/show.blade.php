@extends('customer.layouts.app')

@section('title', 'Chi tiết đặt lịch')

@section('styles')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-hover: #ff4785;
        --secondary-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e1e1e1;
    }

    .booking-detail-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .booking-detail-header {
        background: linear-gradient(135deg, #ff6b9d 0%, #ff8db3 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .booking-status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-in-progress {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .booking-receipt {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .receipt-header {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .receipt-logo {
        max-width: 150px;
        margin-bottom: 1rem;
    }

    .receipt-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .receipt-subtitle {
        color: #666;
        margin-bottom: 0;
    }

    .receipt-body {
        padding: 1.5rem;
    }

    .receipt-section {
        margin-bottom: 2rem;
    }

    .receipt-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-weight: bold;
        margin-bottom: 1rem;
        color: var(--text-color);
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--primary-color);
    }

    .receipt-info {
        display: flex;
        flex-wrap: wrap;
    }

    .receipt-info-item {
        flex: 1;
        min-width: 200px;
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: bold;
        color: var(--text-color);
    }

    .service-details {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .service-header {
        background-color: #f8f9fa;
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        font-weight: bold;
    }

    .service-body {
        padding: 1rem;
        display: flex;
        align-items: center;
    }

    .service-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 1rem;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-info {
        flex: 1;
    }

    .service-name {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .service-price {
        color: var(--primary-color);
        font-weight: bold;
    }

    .service-duration {
        font-size: 0.9rem;
        color: #666;
    }

    .receipt-total {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-weight: bold;
        color: var(--text-color);
    }

    .total-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .receipt-footer {
        text-align: center;
        padding: 1.5rem;
        border-top: 1px solid var(--border-color);
        color: #666;
    }

    .receipt-qr {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .receipt-qr img {
        max-width: 150px;
    }

    .receipt-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .countdown-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .countdown-title {
        font-weight: bold;
        margin-bottom: 1rem;
        color: var(--text-color);
    }

    .countdown {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .countdown-item {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        min-width: 80px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .countdown-value {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .countdown-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
        margin-bottom: 1.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px;
        width: 2px;
        background-color: var(--border-color);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: var(--primary-color);
        border: 3px solid white;
        top: 0;
    }

    .timeline-content {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
    }

    .timeline-date {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .timeline-title {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .timeline-description {
        color: #666;
    }

    @media print {
        .booking-detail-header, .receipt-actions, .countdown-container, .no-print {
            display: none;
        }

        .booking-receipt {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        body {
            background-color: white;
        }
    }

    @media (max-width: 768px) {
        .receipt-info-item {
            flex: 100%;
        }

        .service-body {
            flex-direction: column;
            align-items: flex-start;
        }

        .service-image {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .receipt-actions {
            flex-direction: column;
        }

        .receipt-actions .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-detail-container py-5">
    <div class="booking-detail-header">
        <h1 class="mb-2">Chi tiết đặt lịch</h1>
        <p class="mb-0">Mã đặt lịch: {{ $booking->MaDL }}</p>
        <div class="booking-status-badge status-{{ strtolower(str_replace(' ', '-', $booking->Trangthai_)) }}">
            {{ $booking->Trangthai_ }}
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']) && \Carbon\Carbon::parse($booking->Thoigiandatlich) > \Carbon\Carbon::now())
    <div class="countdown-container">
        <h4 class="countdown-title">Thời gian còn lại đến lịch hẹn</h4>
        <div class="countdown">
            <div class="countdown-item">
                <div class="countdown-label">Ngày</div>
                <div class="countdown-value">{{ isset($timeLeftData['days']) ? $timeLeftData['days'] : '0' }}</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-label">Giờ</div>
                <div class="countdown-value">{{ isset($timeLeftData['hours']) ? $timeLeftData['hours'] : '0' }}</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-label">Phút</div>
                <div class="countdown-value">{{ isset($timeLeftData['minutes']) ? $timeLeftData['minutes'] : '0' }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="booking-receipt" id="booking-receipt">
        <div class="receipt-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="receipt-logo">
            <h2 class="receipt-title">Phiếu đặt lịch</h2>
            <p class="receipt-subtitle">Mã đặt lịch: {{ $booking->MaDL }}</p>
        </div>
        <div class="receipt-body">
            <div class="receipt-section">
                <h4 class="section-title">Thông tin khách hàng</h4>
                <div class="receipt-info">
                    <div class="receipt-info-item">
                        <div class="info-label">Họ tên</div>
                        <div class="info-value">{{ $booking->user->Hoten }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value">{{ $booking->user->SDT }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $booking->user->Email }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Địa chỉ</div>
                        <div class="info-value">{{ $booking->user->DiaChi }}</div>
                    </div>
                </div>
            </div>

            <div class="receipt-section">
                <h4 class="section-title">Thông tin đặt lịch</h4>
                <div class="receipt-info">
                    <div class="receipt-info-item">
                        <div class="info-label">Ngày đặt</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Giờ đặt</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Trạng thái</div>
                        <div class="info-value">{{ $booking->Trangthai_ }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Ngày tạo</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="receipt-section">
                <h4 class="section-title">Chi tiết dịch vụ</h4>
                <div class="service-details">
                    <div class="service-header">
                        Thông tin dịch vụ
                    </div>
                    <div class="service-body">
                        <div class="service-image">
                            <img src="{{ $booking->dichVu->Image ? asset($booking->dichVu->Image) : asset('images/default-service.jpg') }}" alt="{{ $booking->dichVu->Tendichvu }}">
                        </div>
                        <div class="service-info">
                            <div class="service-name">{{ $booking->dichVu->Tendichvu }}</div>
                            <div class="service-price">{{ $booking->dichVu->getFormattedPriceAttribute() }}</div>
                            <div class="service-duration">
                                <i class="far fa-clock"></i> {{ $booking->dichVu->Thoigian }} phút
                            </div>
                        </div>
                    </div>
                </div>

                <div class="receipt-total">
                    <div class="total-label">Tổng tiền</div>
                    <div class="total-value">{{ $booking->dichVu->getFormattedPriceAttribute() }}</div>
                </div>
            </div>

            @if($booking->hoaDon && $booking->hoaDon->count() > 0)
            <div class="receipt-section">
                <h4 class="section-title">Thông tin thanh toán</h4>
                <div class="receipt-info">
                    @foreach($booking->hoaDon as $hoaDon)
                    <div class="receipt-info-item">
                        <div class="info-label">Mã hóa đơn</div>
                        <div class="info-value">{{ $hoaDon->MaHD }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Ngày thanh toán</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Tổng tiền</div>
                        <div class="info-value">{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div class="receipt-info-item">
                        <div class="info-label">Phương thức thanh toán</div>
                        <div class="info-value">{{ $hoaDon->phuongThuc ? $hoaDon->phuongThuc->TenPT : 'Không có thông tin' }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($statusHistory) && $statusHistory->count() > 0)
            <div class="receipt-section">
                <h4 class="section-title">Lịch sử trạng thái</h4>
                <div class="timeline">
                    @foreach($statusHistory as $history)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-date">{{ \Carbon\Carbon::parse($history->ThoigianCapNhat)->format('d/m/Y H:i') }}</div>
                            <div class="timeline-title">{{ $history->TrangthaiCu }} → {{ $history->TrangthaiMoi }}</div>
                            @if($history->GhiChu)
                            <div class="timeline-description">{{ $history->GhiChu }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="receipt-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrCodeData) }}" alt="QR Code">
                <p class="mt-2">Quét mã QR để xem chi tiết đặt lịch</p>
            </div>
        </div>
        <div class="receipt-footer">
            <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
            <p>Mọi thắc mắc xin vui lòng liên hệ: 0123 456 789 hoặc email: support@spa.com</p>
        </div>
    </div>

    <div class="receipt-actions no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> In phiếu đặt lịch
        </button>
        
        <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        
        @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']))
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
            <i class="fas fa-calendar-alt"></i> Đổi lịch
        </button>
        @endif
        
        @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']))
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="fas fa-times"></i> Hủy lịch
        </button>
        @endif
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">Đổi lịch đặt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customer.lichsudatlich.reschedule', $booking->MaDL) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_date" class="form-label">Ngày mới</label>
                        <input type="date" class="form-control" id="new_date" name="new_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_time" class="form-label">Giờ mới</label>
                        <input type="time" class="form-control" id="new_time" name="new_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do đổi lịch</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Lưu ý: Việc đổi lịch cần được xác nhận lại từ phía spa.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Xác nhận hủy lịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy lịch đặt này không?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Lưu ý: Bạn không thể hủy lịch trong vòng 24 giờ trước thời gian đặt.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <form action="{{ route('customer.lichsudatlich.cancel', $booking->MaDL) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
