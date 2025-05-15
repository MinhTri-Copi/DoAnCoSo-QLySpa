@extends('backend.layouts.app')

@section('title', 'Chi Tiết Dịch Vụ')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .service-detail-page {
        background-color: #f8f9fa;
    }

    .service-detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        background: linear-gradient(120deg, #ff9a9e 0%, #fad0c4 100%);
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 20px rgba(255, 154, 158, 0.2);
    }

    .page-title h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .page-subtitle {
        opacity: 0.9;
        font-weight: 300;
    }

    .service-id-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        backdrop-filter: blur(5px);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-white {
        background: white;
        color: #ff9a9e;
    }

    .btn-outline-white {
        background: transparent;
        border: 1px solid white;
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .service-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    .service-main-info {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .service-header {
        padding: 0;
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .service-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
        color: white;
        font-size: 3rem;
    }

    .service-status-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .status-pending {
        background-color: #FFC107;
        color: #333;
    }

    .status-processing {
        background-color: #3498DB;
        color: white;
    }

    .status-completed {
        background-color: #2ECC71;
        color: white;
    }

    .status-cancelled {
        background-color: #E74C3C;
        color: white;
    }

    .service-body {
        padding: 30px;
    }

    .service-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #343a40;
    }

    .service-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: #ff9a9e;
        margin-bottom: 20px;
        display: inline-block;
        padding: 5px 15px;
        background-color: #fff9f9;
        border-radius: 10px;
    }

    .service-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .meta-item i {
        color: #ff9a9e;
        font-size: 1.2rem;
    }

    .service-description {
        margin-bottom: 30px;
    }

    .service-description h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #343a40;
    }

    .service-description p {
        color: #6c757d;
        line-height: 1.6;
    }

    .available-days {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .day-badge {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        background-color: #e9ecef;
    }

    .day-badge.active {
        background-color: #ff9a9e;
        color: white;
    }

    .service-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar-widget {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .widget-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .widget-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }

    .widget-body {
        padding: 20px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .stat-value {
        font-weight: 600;
        color: #343a40;
    }

    .booking-list {
        margin-top: 15px;
    }

    .booking-item {
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
        transition: all 0.3s;
    }

    .booking-item:hover {
        background-color: #e9ecef;
    }

    .booking-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .booking-user {
        font-weight: 600;
        color: #343a40;
    }

    .booking-date {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .booking-status {
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 5px;
        display: inline-block;
    }

    .empty-state {
        padding: 20px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2rem;
        color: #dee2e6;
        margin-bottom: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .service-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: space-between;
        }
        
        .service-meta {
            flex-direction: column;
            gap: 10px;
        }
    }

    /* Header đẹp hơn */
    .details-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
        border-radius: 20px;
        padding: 35px 40px;
        margin-bottom: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(255, 154, 158, 0.25);
    }

    .details-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .details-subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
        font-weight: 400;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
    }

    .details-subtitle i {
        margin-right: 8px;
        background: rgba(255,255,255,0.2);
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    /* Hiệu ứng cho header */
    .header-shimmer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.1) 20%, 
            rgba(255,255,255,0.2) 40%, 
            rgba(255,255,255,0.1) 60%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 5s infinite linear;
        z-index: 1;
        pointer-events: none;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .glitter-dot {
        position: absolute;
        background: white;
        border-radius: 50%;
        opacity: 0;
        z-index: 1;
        box-shadow: 0 0 10px 2px rgba(255,255,255,0.8);
        animation: glitter 8s infinite;
    }

    .glitter-dot:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 25%;
        left: 10%;
        animation-delay: 0s;
    }

    .glitter-dot:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 40%;
        left: 30%;
        animation-delay: 2s;
    }

    .glitter-dot:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 20%;
        right: 25%;
        animation-delay: 4s;
    }

    .glitter-dot:nth-child(4) {
        width: 5px;
        height: 5px;
        bottom: 30%;
        right: 15%;
        animation-delay: 6s;
    }

    /* Nút đẹp hơn */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    
    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
    }
    
    .btn-edit, .btn-delete {
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        text-decoration: none;
    }
    
    .btn-edit {
        background: rgba(255, 255, 255, 0.9);
        color: #ff9a9e;
    }
    
    .btn-delete {
        background: rgba(231, 76, 60, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .btn-edit:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.25);
        transform: translateY(-2px);
    }
    
    .btn-edit i, .btn-delete i {
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid page-show-dichvu" style="max-width: 1600px; margin: 0 auto; padding: 0 20px;">
    <div class="service-details-container animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="details-header">
            <div class="header-shimmer"></div>
            <div class="glitter-dot"></div>
            <div class="glitter-dot"></div>
            <div class="glitter-dot"></div>
            <div class="glitter-dot"></div>
            
            <div class="header-content">
                <h1 class="details-title">{{ $dichVu->Tendichvu }}</h1>
                <p class="details-subtitle">
                    <i class="fas fa-hashtag"></i>
                    Mã dịch vụ: {{ $dichVu->MaDV }}
                </p>
            </div>
            
            <div class="header-actions">
                <a href="{{ route('admin.dichvu.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
                
                <div class="action-buttons">
                    <a href="{{ route('admin.dichvu.edit', $dichVu->MaDV) }}" class="btn-edit">
                        <i class="fas fa-edit"></i>
                        <span>Chỉnh sửa</span>
                    </a>
                    
                    <a href="{{ route('admin.dichvu.confirm-destroy', $dichVu->MaDV) }}" class="btn-delete">
                        <i class="fas fa-trash"></i>
                        <span>Xóa</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="service-content animate__animated animate__fadeIn">
            <div class="service-main-info">
                <div class="service-header">
                    @if($dichVu->Image && file_exists(public_path($dichVu->Image)))
                        <img src="{{ asset($dichVu->Image) }}" alt="{{ $dichVu->Tendichvu }}" class="service-image">
                    @else
                        <div class="service-image-placeholder">
                            <i class="fas fa-spa"></i>
                        </div>
                    @endif
                </div>
                
                <div class="service-body">
                    <h2 class="service-title">{{ $dichVu->Tendichvu }}</h2>
                    
                    <div class="service-price">
                        {{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ
                    </div>
                    
                    <div class="service-meta">
                        <div class="meta-item">
                            <i class="far fa-clock"></i>
                            <span>Thời lượng: {{ $dichVu->Thoigian ? $dichVu->Thoigian->format('H:i') : 'N/A' }}</span>
                        </div>
                        
                        @if($dichVu->featured ?? false)
                            <div class="meta-item">
                                <i class="fas fa-star"></i>
                                <span>Dịch vụ nổi bật</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="service-description">
                        <h3>Mô Tả Dịch Vụ</h3>
                        <p>{{ $dichVu->MoTa ?? 'Không có mô tả chi tiết cho dịch vụ này.' }}</p>
                    </div>
                    
                    <div class="service-availability">
                        <h3>Ngày Có Sẵn</h3>
                        @php
                            $availableDays = json_decode($dichVu->available_days ?? '[]', true);
                            $days = [
                                'monday' => 'T2', 
                                'tuesday' => 'T3', 
                                'wednesday' => 'T4', 
                                'thursday' => 'T5', 
                                'friday' => 'T6', 
                                'saturday' => 'T7', 
                                'sunday' => 'CN'
                            ];
                        @endphp
                        
                        <div class="available-days">
                            @foreach($days as $key => $label)
                                <div class="day-badge {{ in_array($key, $availableDays) ? 'active' : '' }}">
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="service-sidebar">
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Thông Tin Đặt Lịch</h3>
                    </div>
                    <div class="widget-body">
                        <div class="stat-item">
                            <div class="stat-label">Tổng số lượt đặt</div>
                            <div class="stat-value">{{ $bookingStats->total_bookings }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Đã xác nhận</div>
                            <div class="stat-value">{{ $bookingStats->confirmed_bookings }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Đã hủy</div>
                            <div class="stat-value">{{ $bookingStats->canceled_bookings }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Lịch Đặt Gần Đây</h3>
                    </div>
                    <div class="widget-body">
                        @if(count($recentBookings) > 0)
                            <div class="booking-list">
                                @foreach($recentBookings as $booking)
                                    <div class="booking-item">
                                        <div class="booking-info">
                                            <span class="booking-user">{{ $booking->user->Hoten ?? 'N/A' }}</span>
                                            <span class="booking-date">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <span class="booking-status 
                                            {{ $booking->Trangthai_ == 'Đã xác nhận' ? 'bg-success text-white' : 
                                               ($booking->Trangthai_ == 'Đã hủy' ? 'bg-danger text-white' : 'bg-warning') }}">
                                            {{ $booking->Trangthai_ }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="far fa-calendar-times"></i>
                                <p>Chưa có lượt đặt lịch nào</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Thời Gian Phổ Biến</h3>
                    </div>
                    <div class="widget-body">
                        @if(count($popularTimes) > 0)
                            <div class="booking-list">
                                @foreach($popularTimes as $time)
                                    <div class="booking-item">
                                        <div class="booking-info">
                                            <span class="booking-user">{{ $time->hour }}:00</span>
                                            <span class="booking-date">{{ $time->count }} lượt đặt</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="far fa-clock"></i>
                                <p>Không có dữ liệu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script trống - đã xóa phần biểu đồ
</script>
@endsection