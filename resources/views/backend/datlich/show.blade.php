@extends('backend.layouts.app')

@section('title', 'Chi Tiết Lịch Đặt')

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
    }
</style>

<div class="header-container">
    <div>
        <div class="header-title">Chi Tiết Lịch Đặt</div>
        <div class="header-subtitle">Xem thông tin chi tiết về lịch đặt</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.datlich.edit', $datLich->MaDL) }}" class="btn btn-white">
            <i class="fas fa-edit"></i> Chỉnh Sửa
        </a>
        <a href="{{ route('admin.datlich.confirmDestroy', $datLich->MaDL) }}" class="btn btn-outline">
            <i class="fas fa-trash"></i> Xóa
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-info-circle"></i> Thông Tin Lịch Đặt
        </div>
    </div>
    
    <div class="info-section">
        @php
            $statusClass = 'badge-pending';
            if($datLich->Trangthai_ == 'Đã xác nhận') {
                $statusClass = 'badge-confirmed';
            } elseif($datLich->Trangthai_ == 'Đã hủy') {
                $statusClass = 'badge-cancelled';
            } elseif($datLich->Trangthai_ == 'Hoàn thành') {
                $statusClass = 'badge-completed';
            }
        @endphp
        
        <span class="badge {{ $statusClass }}">{{ $datLich->Trangthai_ }}</span>
        
        <div class="info-row">
            <div class="info-label">Mã Đặt Lịch</div>
            <div class="info-value">{{ $datLich->MaDL }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Thời Gian Đặt Lịch</div>
            <div class="info-value">
                {{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('d/m/Y H:i') }}
                ({{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->diffForHumans() }})
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Trạng Thái</div>
            <div class="info-value">{{ $datLich->Trangthai_ }}</div>
        </div>
    </div>
    
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-user"></i> Thông Tin Người Dùng
        </div>
        
        @if($datLich->user)
        <div class="user-card">
            <div class="user-avatar">{{ substr($datLich->user->Hoten, 0, 1) }}</div>
            <div class="user-info">
                <div class="user-name">{{ $datLich->user->Hoten }}</div>
                <div class="user-email">{{ $datLich->user->Email }}</div>
                <div class="user-details">
                    <div class="user-detail">
                        <i class="fas fa-phone"></i>
                        <span>{{ $datLich->user->SDT ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-venus-mars"></i>
                        <span>{{ $datLich->user->Gioitinh ? ($datLich->user->Gioitinh == 1 ? 'Nam' : 'Nữ') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-birthday-cake"></i>
                        <span>{{ $datLich->user->Ngaysinh ? \Carbon\Carbon::parse($datLich->user->Ngaysinh)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $datLich->user->DiaChi ?? 'Chưa cập nhật' }}</span>
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
        
        @if($datLich->dichVu)
        <div class="service-card">
            <div class="service-header">
                <div class="service-name">{{ $datLich->dichVu->Tendichvu }}</div>
                <div class="service-price">{{ number_format($datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</div>
            </div>
            <div class="service-details">
                <div class="service-detail">
                    <i class="fas fa-clock"></i>
                    <span>Thời gian: {{ $datLich->dichVu->Thoigian }} phút</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-calendar-check"></i>
                    <span>Ngày đặt: {{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('d/m/Y') }}</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-hourglass-start"></i>
                    <span>Giờ bắt đầu: {{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('H:i') }}</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-hourglass-end"></i>
                    <span>Giờ kết thúc: {{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->addMinutes($datLich->dichVu->Thoigian)->format('H:i') }}</span>
                </div>
            </div>
            @if($datLich->dichVu->MoTa)
            <div class="service-description">
                {{ $datLich->dichVu->MoTa }}
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
            <i class="fas fa-history"></i> Lịch Sử Trạng Thái
        </div>
        
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-dot">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Đặt lịch thành công</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($datLich->created_at ?? $datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            @if($datLich->Trangthai_ == 'Đã xác nhận')
            <div class="timeline-item">
                <div class="timeline-dot">
                    <i class="fas fa-check"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Đã xác nhận lịch đặt</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($datLich->updated_at ?? $datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif
            
            @if($datLich->Trangthai_ == 'Đã hủy')
            <div class="timeline-item">
                <div class="timeline-dot">
                    <i class="fas fa-times"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Đã hủy lịch đặt</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($datLich->updated_at ?? $datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif
            
            @if($datLich->Trangthai_ == 'Hoàn thành')
            <div class="timeline-item">
                <div class="timeline-dot">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-title">Hoàn thành dịch vụ</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($datLich->updated_at ?? $datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="btn-container" style="display: flex; justify-content: center; margin-top: 20px;">
    <a href="{{ route('admin.datlich.index') }}" class="btn" style="background-color: var(--primary-color); color: white;">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection