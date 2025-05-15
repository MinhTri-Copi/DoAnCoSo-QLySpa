@extends('backend.layouts.app')

@section('title', 'Chi Tiết Hóa Đơn')

@section('content')
<style>
    :root {
        --primary-color: #ff6b8b;
        --primary-light: #ffd0d9;
        --primary-dark: #e84e6f;
        --text-on-primary: #ffffff;
        --secondary-color: #f8f9fa;
        --border-color: #e9ecef;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
    }

    .header-container {
        background-color: var(--primary-color);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        color: var(--text-on-primary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        font-size: 24px;
        font-weight: bold;
    }

    .header-subtitle {
        font-size: 14px;
        margin-top: 5px;
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-white {
        background-color: white;
        color: var(--primary-color);
    }

    .btn-white:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-outline {
        background-color: transparent;
        color: white;
        border: 1px solid white;
    }

    .btn-outline:hover {
        background-color: rgba(255,255,255,0.1);
        transform: translateY(-2px);
    }

    .content-card {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 15px;
        display: inline-block;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-confirmed {
        background-color: #28a745;
        color: white;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: white;
    }

    .badge-completed {
        background-color: #17a2b8;
        color: white;
    }

    .info-section {
        margin-bottom: 30px;
    }

    .info-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .info-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .info-row {
        display: flex;
        margin-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
    }

    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        width: 200px;
        font-weight: 500;
        color: #495057;
    }

    .info-value {
        flex: 1;
        color: #212529;
    }

    .user-card {
        display: flex;
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 24px;
        font-weight: bold;
        margin-right: 20px;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-size: 18px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 5px;
    }

    .user-email {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .user-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .user-detail {
        display: flex;
        align-items: center;
    }

    .user-detail i {
        color: var(--primary-color);
        margin-right: 8px;
        width: 16px;
    }

    .service-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .service-name {
        font-size: 18px;
        font-weight: 600;
        color: #343a40;
    }

    .service-price {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .service-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .service-detail {
        display: flex;
        align-items: center;
    }

    .service-detail i {
        color: var(--primary-color);
        margin-right: 8px;
        width: 16px;
    }

    .service-description {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
        color: #6c757d;
    }

    .payment-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .payment-title {
        font-size: 18px;
        font-weight: 600;
        color: #343a40;
    }

    .payment-amount {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
    }

    .payment-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .payment-detail {
        display: flex;
        align-items: center;
    }

    .payment-detail i {
        color: var(--primary-color);
        margin-right: 8px;
        width: 16px;
    }

    .reward-points {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
    }

    .reward-points i {
        color: #ffc107;
        margin-right: 8px;
    }

    .reward-points-value {
        font-weight: 600;
        color: #343a40;
    }

    .timeline {
        position: relative;
        margin-top: 20px;
        padding-left: 30px;
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
        margin-bottom: 20px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
    }

    .timeline-content {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }

    .timeline-title {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 5px;
    }

    .timeline-date {
        font-size: 12px;
        color: #6c757d;
    }

    .rating-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .rating-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .rating-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .rating-stars {
        display: flex;
        margin-bottom: 10px;
    }

    .rating-star {
        color: #ffc107;
        font-size: 20px;
        margin-right: 5px;
    }

    .rating-date {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .rating-comment {
        padding: 15px;
        background-color: white;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-actions {
            margin-top: 15px;
            width: 100%;
        }
        
        .btn {
            flex: 1;
        }
        
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .user-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .user-avatar {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .user-details {
            grid-template-columns: 1fr;
        }
        
        .service-details {
            grid-template-columns: 1fr;
        }
        
        .payment-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="header-container">
    <div>
        <div class="header-title">Chi Tiết Hóa Đơn</div>
        <div class="header-subtitle">Xem thông tin chi tiết về hóa đơn</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.hoadonvathanhtoan.edit', $hoaDon->MaHD) }}" class="btn btn-white">
            <i class="fas fa-edit"></i> Chỉnh Sửa
        </a>
        <a href="{{ route('admin.hoadonvathanhtoan.print', $hoaDon->MaHD) }}" class="btn btn-white" target="_blank">
            <i class="fas fa-print"></i> In Hóa Đơn
        </a>
        <a href="{{ route('admin.hoadonvathanhtoan.confirmDestroy', $hoaDon->MaHD) }}" class="btn btn-outline">
            <i class="fas fa-trash"></i> Xóa
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-info-circle"></i> Thông Tin Hóa Đơn
        </div>
    </div>
    
    <div class="info-section">
        @if($hoaDon->trangThai)
            @php
                $statusClass = 'badge-pending';
                if($hoaDon->trangThai->Tentrangthai == 'Đã thanh toán') {
                    $statusClass = 'badge-confirmed';
                } elseif($hoaDon->trangThai->Tentrangthai == 'Đã hủy') {
                    $statusClass = 'badge-cancelled';
                } elseif($hoaDon->trangThai->Tentrangthai == 'Hoàn thành') {
                    $statusClass = 'badge-completed';
                }
            @endphp
            <span class="badge {{ $statusClass }}">{{ $hoaDon->trangThai->Tentrangthai }}</span>
        @endif
        
        <div class="info-row">
            <div class="info-label">Mã Hóa Đơn</div>
            <div class="info-value">{{ $hoaDon->MaHD }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Ngày Thanh Toán</div>
            <div class="info-value">
                {{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('d/m/Y H:i') }}
                ({{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->diffForHumans() }})
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Tổng Tiền</div>
            <div class="info-value" style="font-weight: 700; color: var(--primary-color);">{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Phương Thức Thanh Toán</div>
            <div class="info-value">{{ $hoaDon->phuongThuc->TenPT ?? 'N/A' }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Phòng</div>
            <div class="info-value">{{ $hoaDon->phong->Tenphong ?? 'N/A' }} ({{ $hoaDon->phong->Loaiphong ?? 'N/A' }})</div>
        </div>
    </div>
    
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-user"></i> Thông Tin Khách Hàng
        </div>
        
        @if($hoaDon->user)
        <div class="user-card">
            <div class="user-avatar">{{ substr($hoaDon->user->Hoten, 0, 1) }}</div>
            <div class="user-info">
                <div class="user-name">{{ $hoaDon->user->Hoten }}</div>
                <div class="user-email">{{ $hoaDon->user->Email }}</div>
                <div class="user-details">
                    <div class="user-detail">
                        <i class="fas fa-phone"></i>
                        <span>{{ $hoaDon->user->SDT ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-venus-mars"></i>
                        <span>{{ $hoaDon->user->Gioitinh ? ($hoaDon->user->Gioitinh == 1 ? 'Nam' : 'Nữ') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-birthday-cake"></i>
                        <span>{{ $hoaDon->user->Ngaysinh ? \Carbon\Carbon::parse($hoaDon->user->Ngaysinh)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $hoaDon->user->DiaChi ?? 'Chưa cập nhật' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 5px;">
            Không tìm thấy thông tin người dùng.
        </div>
        @endif
    </div>
    
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-spa"></i> Thông Tin Dịch Vụ
        </div>
        
        @if($hoaDon->datLich && $hoaDon->datLich->dichVu)
        <div class="service-card">
            <div class="service-header">
                <div class="service-name">{{ $hoaDon->datLich->dichVu->Tendichvu }}</div>
                <div class="service-price">{{ number_format($hoaDon->datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</div>
            </div>
            <div class="service-details">
                <div class="service-detail">
                    <i class="fas fa-clock"></i>
                    <span>Thời gian: {{ $hoaDon->datLich->dichVu->Thoigian }} phút</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-calendar-check"></i>
                    <span>Ngày đặt: {{ \Carbon\Carbon::parse($hoaDon->datLich->Thoigiandatlich)->format('d/m/Y') }}</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-hourglass-start"></i>
                    <span>Giờ bắt đầu: {{ \Carbon\Carbon::parse($hoaDon->datLich->Thoigiandatlich)->format('H:i') }}</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-hourglass-end"></i>
                    <span>Giờ kết thúc: {{ \Carbon\Carbon::parse($hoaDon->datLich->Thoigiandatlich)->addMinutes($hoaDon->datLich->dichVu->Thoigian)->format('H:i') }}</span>
                </div>
            </div>
            @if($hoaDon->datLich->dichVu->MoTa)
            <div class="service-description">
                {{ $hoaDon->datLich->dichVu->MoTa }}
            </div>
            @endif
        </div>
        @else
        <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 5px;">
            Không tìm thấy thông tin dịch vụ.
        </div>
        @endif
    </div>
    
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-money-bill-wave"></i> Thông Tin Thanh Toán
        </div>
        
        <div class="payment-card">
            <div class="payment-header">
                <div class="payment-title">Tổng Thanh Toán</div>
                <div class="payment-amount">{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</div>
            </div>
            <div class="payment-details">
                <div class="payment-detail">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Ngày thanh toán: {{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="payment-detail">
                    <i class="fas fa-credit-card"></i>
                    <span>Phương thức: {{ $hoaDon->phuongThuc->TenPT ?? 'N/A' }}</span>
                </div>
                <div class="payment-detail">
                    <i class="fas fa-tag"></i>
                    <span>Trạng thái: {{ $hoaDon->trangThai->Tentrangthai ?? 'N/A' }}</span>
                </div>
                <div class="payment-detail">
                    <i class="fas fa-door-open"></i>
                    <span>Phòng: {{ $hoaDon->phong->Tenphong ?? 'N/A' }}</span>
                </div>
            </div>
            
            @if($hoaDon->lsDiemThuong->isNotEmpty())
            <div class="reward-points">
                <i class="fas fa-star"></i>
                <span class="reward-points-value">Điểm thưởng: {{ $hoaDon->lsDiemThuong->first()->Sodiem }} điểm</span>
            </div>
            @endif
        </div>
    </div>
    
    @if($hoaDon->danhGia->isNotEmpty())
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-star"></i> Đánh Giá
        </div>
        
        @foreach($hoaDon->danhGia as $danhGia)
        <div class="rating-card">
            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    <div class="rating-star">
                        @if($i <= $danhGia->Danhgiasao)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    </div>
                @endfor
            </div>
            <div class="rating-date">
                Đánh giá vào: {{ \Carbon\Carbon::parse($danhGia->Ngaydanhgia)->format('d/m/Y H:i') }}
            </div>
            @if($danhGia->Nhanxet)
            <div class="rating-comment">
                {{ $danhGia->Nhanxet }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-star"></i> Đánh Giá
        </div>
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px;">
            Chưa có đánh giá nào cho hóa đơn này.
            <a href="{{ route('admin.hoadonvathanhtoan.createDanhGia', $hoaDon->MaHD) }}" style="color: #0c5460; text-decoration: underline; font-weight: bold;">Thêm đánh giá</a>
        </div>
    </div>
    @endif
</div>

<div class="btn-container" style="display: flex; justify-content: center; margin-top: 20px;">
    <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn" style="background-color: var(--primary-color); color: white;">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection