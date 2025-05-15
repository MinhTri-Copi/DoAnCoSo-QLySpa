@extends('backend.layouts.app')

@section('title', 'Dashboard - Rosa Spa')

@section('styles')
<link href="{{ asset('demoDashBoard/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('admin/css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('admin/css/white-background.css') }}" rel="stylesheet">
<link href="{{ asset('admin/css/time-filter.css') }}" rel="stylesheet">
<style>
    /* Đổi màu nền trang thành hồng nhạt */
    body {
        background-color: #ffebf3 !important;
    }
    
    /* Đảm bảo nền trang luôn hồng nhạt */
    .wrapper, .content-wrapper, .main-content {
        background-color: #ffebf3 !important;
    }
    
    /* Đảm bảo mọi thành phần wrapper khác cũng có màu hồng nhạt */
    html, body, #app, .admin-container, .wrapper-content {
        background-color: #ffebf3 !important;
    }
    
    /* Đảm bảo các nút và menu bộ lọc thời gian hiển thị ở trên các phần tử khác */
    .time-filter-container {
        position: relative;
        z-index: 100 !important;
        overflow: visible !important;
    }
    
    .time-filter-btn {
        position: relative;
        z-index: 101 !important;
    }
    
    /* Đảm bảo custom-date-menu hiển thị nổi bật */
    #custom-date-menu {
        z-index: 9999 !important;
    }
    
    /* Hệ thống thông báo */
    .notifications-container {
        position: fixed;
        bottom: 80px;
        right: 20px;
        z-index: 9999;
        width: 300px;
        max-width: 90%;
    }
    
    .notification {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 10px;
        padding: 15px;
        position: relative;
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s ease-out;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    
    .notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .notification-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 14px;
    }
    
    .notification-loading .notification-icon {
        background-color: #3498db;
        color: white;
    }
    
    .notification-success .notification-icon {
        background-color: #2ecc71;
        color: white;
    }
    
    .notification-error .notification-icon {
        background-color: #e74c3c;
        color: white;
    }
    
    .notification-content {
        flex-grow: 1;
    }
    
    .notification-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
        color: #333;
    }
    
    .notification-message {
        font-size: 12px;
        color: #666;
    }
    
    .notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background-color: #3498db;
        width: 100%;
    }
    
    .notification-loading .notification-progress {
        background: linear-gradient(to right, #3498db, #9b59b6);
        animation: progress 2s linear infinite;
        background-size: 200% 100%;
    }
    
    @keyframes progress {
        0% {
            background-position: 100% 0;
    }
        100% {
            background-position: -100% 0;
    }
    }
    
    /* Thêm style cho các trạng thái thông báo */
    .notification-success .notification-progress {
        background-color: #2ecc71;
    }
    
    .notification-error .notification-progress {
        background-color: #e74c3c;
    }
    
    /* Animation for removing rows */
    .booking-row {
        transition: all 0.3s ease;
    }
    
    .booking-row.fade-out {
        opacity: 0;
        transform: translateX(30px);
    }
    
    /* Pulsing dot for upcoming bookings within 1 hour */
    .urgent-booking-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #ff3860;
        margin-right: 6px;
        position: relative;
    }
    
    .urgent-booking-dot::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: rgba(255, 56, 96, 0.7);
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        70% {
            transform: scale(2);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 0;
        }
    }
    
    .user-name {
        color: white;
        font-size: 0.9rem;
        font-weight: 500;
        margin-right: 5px;
    }
    
    .user-dropdown-menu {
        position: absolute;
        top: 45px;
        right: 0;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        min-width: 180px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 100;
    }
    
    .user-dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .user-dropdown-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        color: #555;
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }
    
    .user-dropdown-item:hover {
        background-color: #f9f9f9;
        color: #db7093;
    }
    
    .user-dropdown-item i {
        margin-right: 10px;
        font-size: 0.9rem;
    }
    
    .user-dropdown-divider {
        height: 1px;
        background-color: #eee;
        margin: 5px 0;
    }
    
    /* Breadcrumbs */
    .dashboard-breadcrumbs {
        padding: 0;
        margin: 0 0 10px 0;
        list-style: none;
        display: flex;
        align-items: center;
    }
    
    .breadcrumb-item {
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.85rem;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        padding: 0 8px;
        color: rgba(255, 255, 255, 0.6);
    }
    
    .breadcrumb-item.active {
        color: white;
        font-weight: 500;
    }
    
    /* Responsive icon sizes for mobile */
    @media (max-width: 767px) {
        .stat-card-new .stat-decoration {
            font-size: 50px;
        }
    }
    
    /* Time filter styles are now in time-filter.css */
    
    /* Hiệu ứng viền sáng cho khung chào mừng - CSS mới */
    .welcome-banner {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background: linear-gradient(145deg, #f58cba, #db7093);
        animation: softPulse 4s infinite alternate, floatAnimation 6s ease-in-out infinite;
        transition: all 0.5s ease;
        box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
        transform-origin: center center;
        width: 100%; /* Đảm bảo chiếm toàn bộ chiều rộng */
    }
    
    /* Styles cho các nút lọc biểu đồ */
    .chart-filter-button {
        border-radius: 4px !important;
        border: none !important;
        margin-right: 5px;
        transition: all 0.2s ease-in-out;
        font-weight: 500;
        padding: 4px 12px;
    }
    
    .chart-filter-button:last-child {
        margin-right: 0;
    }
    
    .chart-filter-button.active {
        background-color: #db7093 !important;
        color: white !important;
        box-shadow: 0 2px 5px rgba(219, 112, 147, 0.3);
    }
    
    .chart-filter-button:not(.active) {
        background-color: #f5f5f5 !important;
        color: #555 !important;
    }
    
    .chart-filter-button:hover:not(.active) {
        background-color: #f0f0f0 !important;
        transform: translateY(-1px);
    }
    
    .welcome-banner:before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        z-index: -1;
        background: linear-gradient(45deg, 
            #ff7eb3, #ff758c, #ff7eb3, #ff8e8c, 
            #fdae9e, #ff7eb3, #ff758c, #ff7eb3);
        background-size: 400%;
        border-radius: 14px;
        animation: borderGlow 12s linear infinite;
        filter: blur(10px);
        opacity: 0.7;
    }
    
    .welcome-banner:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.1) 25%, 
            rgba(255,255,255,0.2) 50%, 
            rgba(255,255,255,0.1) 75%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 6s infinite;
        z-index: 1;
        pointer-events: none;
    }
    
    .welcome-banner h1, .welcome-banner p {
        position: relative;
        z-index: 2;
    }
    
    /* Hiệu ứng hover dùng để tăng cường, không phải hiệu ứng chính */
    .welcome-banner:hover {
        box-shadow: 0 8px 25px rgba(219, 112, 147, 0.5);
    }
    
    @keyframes softPulse {
        0% {
            box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
        }
        100% {
            box-shadow: 0 8px 25px rgba(219, 112, 147, 0.5);
    }
    }
    
    @keyframes floatAnimation {
        0% {
            transform: translateY(0);
        }
        50% {
        transform: translateY(-5px);
        }
        100% {
            transform: translateY(0);
        }
    }
    
    @keyframes borderGlow {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    /* Hiệu ứng đốm sáng chạy */
    .shine-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.95);
        z-index: 2;
        animation: corner-to-corner 12s infinite cubic-bezier(0.25, 0.1, 0.25, 1);
        opacity: 0;
    }
    
    @keyframes corner-to-corner {
        /* Bắt đầu ở góc trên trái */
        0% {
        opacity: 0;
            top: 2px;
            left: 2px;
            box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.7);
        }
        5% {
            opacity: 1;
            top: 2px;
            left: 2px;
            box-shadow: 0 0 15px 3px rgba(255, 255, 255, 0.8);
        }
        
        /* Di chuyển từ từ dọc theo viền trái xuống */
        30% {
            top: 40%;
            left: 2px;
            box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.9);
            opacity: 1;
        }
        
        /* Di chuyển theo viền dưới sang phải */
        60% {
            top: calc(100% - 2px);
            left: 60%;
            box-shadow: 0 0 25px 6px rgba(255, 255, 255, 1);
            opacity: 1;
        }
        
        /* Đến góc dưới phải */
        80% {
            top: calc(100% - 2px);
            left: calc(100% - 2px);
            box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.9);
            opacity: 1;
        }
        
        85% {
            opacity: 0.7;
        }
        
        90% {
        opacity: 0;
    }
        
        100% {
            opacity: 0;
            top: calc(100% - 2px);
            left: calc(100% - 2px);
        }
    }
    
    /* Dòng chào mừng */
    .welcome-banner {
        background: linear-gradient(145deg, #f58cba, #db7093);
        border-radius: 12px;
        padding: 30px 35px;  /* Tăng padding từ 20px 25px lên 30px 35px */
        margin-bottom: 30px; /* Tăng margin-bottom từ 25px lên 30px */
        color: white;
        box-shadow: 0 4px 15px rgba(219, 112, 147, 0.25);
        position: relative;
        overflow: hidden;
        width: 100%; /* Đảm bảo chiếm toàn bộ chiều rộng */
    }
    
    .welcome-banner h1 {
        font-size: 1.8rem; /* Tăng font size từ 1.5rem lên 1.8rem */
        font-weight: 600;
        margin-bottom: 12px; /* Tăng margin-bottom từ 8px lên 12px */
        position: relative;
        z-index: 1;
    }
    
    .welcome-banner p {
        font-size: 1.05rem; /* Tăng font size từ 0.95rem lên 1.05rem */
        opacity: 0.9;
        margin-bottom: 5px; /* Thêm margin-bottom 5px thay vì 0 */
        position: relative;
        z-index: 1;
        max-width: 80%; /* Giới hạn chiều rộng của đoạn văn bản */
    }
    
    .welcome-banner::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 100%;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" opacity="0.1"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-9h2v5h-2v-5zm0-3h2v2h-2V8z"/></svg>') no-repeat center center;
        opacity: 0.5;
        z-index: 0;
    }
    
    /* CSS cho dropdown tùy chỉnh */
    .custom-date-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .custom-date-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 9999;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        padding: 20px;
        width: 320px;
        border: 1px solid rgba(219, 112, 147, 0.2);
    }
    
    .custom-date-menu.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .custom-date-form .form-group {
        margin-bottom: 15px;
    }
    
    .custom-date-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }
    
    .custom-date-form input[type="datetime-local"] {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .custom-date-form input[type="datetime-local"]:focus {
        border-color: #db7093;
        outline: none;
        box-shadow: 0 0 0 2px rgba(219, 112, 147, 0.2);
    }
    
    .custom-date-form .btn-group {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
    
    #apply-custom-date {
        background: linear-gradient(145deg, #f58cba, #db7093);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    #apply-custom-date:hover {
        box-shadow: 0 4px 10px rgba(219, 112, 147, 0.3);
        transform: translateY(-2px);
    }
    
    #cancel-custom-date {
        background-color: #f5f5f5;
        color: #666;
        border: 1px solid #ddd;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    #cancel-custom-date:hover {
        background-color: #eee;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Time filter styles are now in time-filter.css */

    /* Style for rows with urgent bookings */
    .urgent-booking {
        background-color: rgba(255, 56, 96, 0.05) !important;
        border-left: 3px solid #ff3860 !important;
    }
    
    /* Subtle pulse animation for the urgent alert banner */
    @keyframes pulse-subtle {
        0% {
            box-shadow: 0 4px 12px rgba(255, 56, 96, 0.3);
        }
        50% {
            box-shadow: 0 4px 20px rgba(255, 56, 96, 0.5);
        }
        100% {
            box-shadow: 0 4px 12px rgba(255, 56, 96, 0.3);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Thêm banner chào mừng -->
    <div class="welcome-banner">
        <h1><i class="fas fa-spa"></i> Chào mừng đến với Rosa Admin!</h1>
        <p>Quản lý hoạt động spa của bạn một cách dễ dàng và hiệu quả</p>
        <div class="shine-line"></div>
    </div>
    
    @if($urgentBookingsCount > 0)
    <!-- Thông báo lịch đặt sắp tới trong vòng 1 giờ -->
    <div class="alert alert-urgent mb-4" style="background: linear-gradient(135deg, #ff3860, #ff5e57); color: white; border-radius: 8px; padding: 15px 20px; box-shadow: 0 4px 12px rgba(255, 56, 96, 0.3); border-left: 5px solid #ff1744; display: flex; align-items: center; justify-content: space-between; animation: pulse-subtle 2s infinite;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-2x mr-3" style="color: white;"></i>
            <div>
                <h5 style="font-weight: 600; margin-bottom: 5px; color: white; font-size: 16px;">Cảnh báo lịch đặt hôm nay!</h5>
                <p style="margin-bottom: 0; font-size: 14px;">
                    <span style="font-weight: 700; font-size: 16px;">{{ $urgentBookingsCount }}</span> cuộc hẹn trong hôm nay sẽ diễn ra trong vòng 1 giờ tới!
                </p>
            </div>
        </div>
        <a href="#booking-section" class="btn btn-light btn-sm" style="background-color: white; color: #ff3860; border: none; font-weight: 600; padding: 8px 16px; border-radius: 6px; transition: all 0.2s ease;">
            <i class="fas fa-eye mr-1"></i> Xem ngay
        </a>
    </div>
    @endif
    
    <!-- Bộ lọc thời gian -->
    <div class="position-relative" style="z-index: 100;">
        <div class="time-filter-container mb-4">
                <div class="time-filter-label">Bộ lọc thời gian</div>
            <button class="time-filter-btn ripple active" data-period="daily">
                <i class="fas fa-calendar-day"></i> Hôm nay
            </button>
            <button class="time-filter-btn ripple" data-period="weekly">
                <i class="fas fa-calendar-week"></i> Tuần này
            </button>
            <button class="time-filter-btn ripple" data-period="monthly">
                <i class="fas fa-calendar-alt"></i> Tháng này
            </button>
            <button class="time-filter-btn ripple" data-period="quarterly">
                <i class="fas fa-calendar"></i> Quý này
            </button>
            <button class="time-filter-btn ripple" data-period="yearly">
                <i class="fas fa-calendar-check"></i> Năm này
            </button>
            
            <div style="display: inline-block; position: relative; margin-bottom: 8px;">
                <button id="custom-date-btn" class="time-filter-btn custom-date-btn ripple">
                            <i class="fas fa-calendar-plus"></i> Tùy chỉnh
                        </button>

                <!-- Dropdown tùy chỉnh với style đảm bảo hiển thị -->
                <div id="custom-date-menu" style="position: absolute; top: 45px; left: 0; background-color: white; padding: 15px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2), 0 0 0 3px rgba(219, 112, 147, 0.3); width: 300px; z-index: 999999; border: 2px solid #db7093; display: none;">
                    <div style="margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                        <h6 style="margin: 0 0 8px 0; color: #db7093; font-weight: 600;">Tùy chỉnh thời gian</h6>
                                </div>
                    <div style="margin-bottom: 15px;">
                        <label for="start-date" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Ngày bắt đầu</label>
                        <input type="datetime-local" id="start-date" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                </div>
                    <div style="margin-bottom: 15px;">
                        <label for="end-date" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Ngày kết thúc</label>
                        <input type="datetime-local" id="end-date" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                                </div>
                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                        <button type="button" id="cancel-custom-date" style="padding: 8px 15px; background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 5px; cursor: pointer; font-weight: 500;">Hủy</button>
                        <button type="button" id="apply-custom-date" style="padding: 8px 15px; background: linear-gradient(145deg, #f58cba, #db7093); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 500;">Áp dụng</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card-new fade-in-up-delay-1" style="border-radius: 10px; border-top: 3px solid #db7093; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; padding: 20px; height: 100%; position: relative;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label" style="color: #6c757d; font-weight: 500; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng doanh thu</div>
                    <div id="revenue-badge" class="stat-badge" style="font-size: 0.7rem; padding: 3px 8px; background-color: rgba(219, 112, 147, 0.1); color: #db7093; border-radius: 20px; font-weight: 500;">
                        @if($period == 'daily')
                            Hôm nay
                        @elseif($period == 'weekly')
                            Tuần này
                        @else
                            Tháng này
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="stat-value" style="font-size: 1.8rem; font-weight: 700; color: #db7093; margin-right: 12px;">{{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}đ</div>
                    <div class="stat-change positive" style="display: inline-flex; align-items: center; font-size: 0.8rem; font-weight: 500; padding: 2px 8px; border-radius: 15px; background-color: rgba(72, 187, 120, 0.1); color: #48bb78;">
                        <i class="fas fa-arrow-up mr-1" style="color: #48bb78;"></i> <span class="time-comparison-text">0%</span>
                    </div>
                </div>
                <div style="margin-top: 5px; font-size: 0.75rem; color: #6c757d;">
                    <span class="comparison-period-text">
                        @if($period == 'daily')
                            so với hôm qua
                        @elseif($period == 'weekly')
                            so với tuần trước
                        @else
                            so với tháng trước
                        @endif
                    </span>
                </div>
                <div style="position: absolute; bottom: 15px; right: 15px; opacity: 0.5; font-size: 3rem;">
                    <i class="fas fa-money-bill-wave" style="color: #db7093;"></i>
                </div>
                

            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card-new fade-in-up-delay-2" style="border-radius: 10px; border-top: 3px solid #4299e1; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; padding: 20px; height: 100%; position: relative;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label" style="color: #6c757d; font-weight: 500; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng đặt lịch</div>
                    <div id="bookings-badge" class="stat-badge" style="font-size: 0.7rem; padding: 3px 8px; background-color: rgba(66, 153, 225, 0.1); color: #4299e1; border-radius: 20px; font-weight: 500;">Tháng này</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="stat-value" style="font-size: 1.8rem; font-weight: 600; color: #2d3748; margin-right: 12px;">{{ number_format($stats['total_bookings'], 0, ',', '.') }}</div>
                    <div class="stat-change positive" style="display: inline-flex; align-items: center; font-size: 0.8rem; font-weight: 500; padding: 2px 8px; border-radius: 15px; background-color: rgba(72, 187, 120, 0.1); color: #48bb78;">
                        <i class="fas fa-arrow-up mr-1" style="color: #48bb78;"></i> <span class="time-comparison-text">0%</span>
                    </div>
                </div>
                <div style="margin-top: 5px; font-size: 0.75rem; color: #6c757d;">
                    <span class="comparison-period-text">
                        @if($period == 'daily')
                            so với hôm qua
                        @elseif($period == 'weekly')
                            so với tuần trước
                        @else
                            so với tháng trước
                        @endif
                    </span>
                </div>
                <div style="position: absolute; bottom: 15px; right: 15px; opacity: 0.5; font-size: 3rem;">
                    <i class="fas fa-calendar-check" style="color: #4299e1;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card-new fade-in-up-delay-2" style="border-radius: 10px; border-top: 3px solid #48bb78; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; padding: 20px; height: 100%; position: relative;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label" style="color: #6c757d; font-weight: 500; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng khách hàng</div>
                    <div id="customers-badge" class="stat-badge" style="font-size: 0.7rem; padding: 3px 8px; background-color: rgba(72, 187, 120, 0.1); color: #48bb78; border-radius: 20px; font-weight: 500;">Hiện tại</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="stat-value" style="font-size: 1.8rem; font-weight: 600; color: #2d3748; margin-right: 12px;">{{ number_format($stats['total_users'], 0, ',', '.') }}</div>
                    <div class="stat-change positive" style="display: inline-flex; align-items: center; font-size: 0.8rem; font-weight: 500; padding: 2px 8px; border-radius: 15px; background-color: rgba(72, 187, 120, 0.1); color: #48bb78;">
                        <i class="fas fa-arrow-up mr-1" style="color: #48bb78;"></i> <span class="time-comparison-text">0%</span>
                    </div>
                </div>
                <div style="margin-top: 5px; font-size: 0.75rem; color: #6c757d;">
                    <span class="comparison-period-text">
                        @if($period == 'daily')
                            so với hôm qua
                        @elseif($period == 'weekly')
                            so với tuần trước
                        @else
                            so với tháng trước
                        @endif
                    </span>
                </div>
                <div style="position: absolute; bottom: 15px; right: 15px; opacity: 0.5; font-size: 3rem;">
                    <i class="fas fa-users" style="color: #48bb78;"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card-new fade-in-up-delay-3" style="border-radius: 10px; border-top: 3px solid #ecc94b; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; padding: 20px; height: 100%; position: relative;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-label" style="color: #6c757d; font-weight: 500; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng đánh giá</div>
                    <div id="reviews-badge" class="stat-badge" style="font-size: 0.7rem; padding: 3px 8px; background-color: rgba(236, 201, 75, 0.1); color: #ecc94b; border-radius: 20px; font-weight: 500;">Tổng số</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="stat-value" style="font-size: 1.8rem; font-weight: 600; color: #2d3748; margin-right: 12px;">{{ number_format($stats['total_reviews'], 0, ',', '.') }}</div>
                    <div class="stat-change positive" style="display: inline-flex; align-items: center; font-size: 0.8rem; font-weight: 500; padding: 2px 8px; border-radius: 15px; background-color: rgba(72, 187, 120, 0.1); color: #48bb78;">
                        <i class="fas fa-arrow-up mr-1" style="color: #48bb78;"></i> <span class="time-comparison-text">0%</span>
                    </div>
                </div>
                <div style="margin-top: 5px; font-size: 0.75rem; color: #6c757d;">
                    <span class="comparison-period-text">
                        @if($period == 'daily')
                            so với hôm qua
                        @elseif($period == 'weekly')
                            so với tuần trước
                        @else
                            so với tháng trước
                        @endif
                    </span>
                </div>
                <div style="position: absolute; bottom: 15px; right: 15px; opacity: 0.5; font-size: 3rem;">
                    <i class="fas fa-star" style="color: #ecc94b;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card fade-in-up-delay-3" style="border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; margin-bottom: 25px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white; border-bottom: 1px solid #f0f0f0; padding: 15px 20px;">
                    <h5 class="mb-0" style="font-weight: 600; color: #333;"><i class="fas fa-chart-bar mr-2" style="color: #db7093;"></i> Phân Tích Doanh Thu</h5>
                    <div class="header-actions">
                        <button class="btn btn-sm" style="background-color: #f8f9fa; border: 1px solid #eee; color: #666; margin-right: 5px;" title="Tải xuống báo cáo">
                            <i class="fas fa-download" style="color: #666;"></i>
                        </button>
                        <button class="btn btn-sm" style="background-color: #f8f9fa; border: 1px solid #eee; color: #666;" title="Tùy chọn hiển thị">
                            <i class="fas fa-ellipsis-v" style="color: #666;"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row m-0">
                        <div class="col-lg-8 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-0" style="font-weight: 600; color: #444;"><i class="fas fa-chart-line mr-2" style="color: #db7093;"></i> Biểu Đồ Doanh Thu Theo Thời Gian</h6>
                                <div class="filter-btn-group">
                                    <button type="button" class="btn btn-sm chart-filter-button" id="btn-day">Ngày</button>
                                    <button type="button" class="btn btn-sm chart-filter-button active" id="btn-month">Tháng</button>
                                    <button type="button" class="btn btn-sm chart-filter-button" id="btn-year">Năm</button>
                                </div>
                            </div>
                            
                            <div style="background-color: #f9f9f9; border-radius: 8px; padding: 15px; position: relative; height: 350px;">
                                <div id="revenue-chart" style="height: 320px; position: relative;">
                                    <!-- Static chart - will be hidden when ApexCharts loads -->
                                    <div id="static-chart" style="height: 100%; width: 100%; display: flex; align-items: flex-end; padding: 0 10px 30px 40px;">
                                        <div style="display: flex; height: 100%; width: 100%; align-items: flex-end; justify-content: space-between;">
                                            @foreach($chartData as $index => $value)
                                            <div style="display: flex; flex-direction: column; align-items: center; height: 100%; margin: 0 5px; position: relative;">
                                                <div class="chart-tooltip" style="position: absolute; top: -30px; opacity: 0; transition: opacity 0.2s; background-color: rgba(0,0,0,0.7); color: white; padding: 3px 8px; border-radius: 4px; font-size: 10px; z-index: 5; pointer-events: none; white-space: nowrap;">
                                                    {{ number_format($value, 0, ',', '.') }}đ
                                                </div>
                                                <?php 
                                                // Chuẩn hóa giá trị để hiển thị trực quan hơn
                                                $normalizedValue = min($value, 10000000); // Giới hạn giá trị tối đa để hiển thị
                                                $displayHeight = $normalizedValue > 0 ? min(280, ($normalizedValue / max(array_sum($chartData), 1)) * 280) : 0;
                                                ?>
                                                <div style="width: 24px; background: linear-gradient(to top, rgba(219, 112, 147, 0.8) 0%, rgba(219, 112, 147, 0.3) 100%); height: {{ $displayHeight }}px; border-radius: 4px; position: relative; cursor: pointer;" 
                                                     onmouseover="this.previousElementSibling.style.opacity=1" 
                                                     onmouseout="this.previousElementSibling.style.opacity=0">
                                                    <div style="position: relative; width: 100%; height: 20px; top: -25px; text-align: center; font-size: 10px; color: #666; font-weight: 500; white-space: nowrap;">
                                                        {{ $value > 1000000 ? number_format($value / 1000000, 1) . 'M' : number_format($value / 1000, 1) . 'K' }}
                                                    </div>
                                                </div>
                                                <div style="margin-top: 8px; font-size: 11px; color: #666; font-weight: 500;">T{{ $index + 1 }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Y-axis labels with better styling -->
                                        <div style="position: absolute; left: 0; top: 0; display: flex; flex-direction: column; justify-content: space-between; height: 280px; padding: 0 10px;">
                                            <div style="font-size: 10px; color: #888; font-weight: 500; line-height: 20px;">9.1M đ</div>
                                            <div style="font-size: 10px; color: #888; font-weight: 500; line-height: 20px;">6.8M đ</div>
                                            <div style="font-size: 10px; color: #888; font-weight: 500; line-height: 20px;">4.5M đ</div>
                                            <div style="font-size: 10px; color: #888; font-weight: 500; line-height: 20px;">2.3M đ</div>
                                            <div style="font-size: 10px; color: #888; font-weight: 500; line-height: 20px;">0 đ</div>
                                        </div>
                                        
                                        <!-- Thêm nhãn trục X -->
                                        <div style="position: absolute; bottom: 0; left: 40px; right: 10px; text-align: center; font-size: 11px; color: #666; font-weight: 500;">
                                            Các tháng trong năm {{ date('Y') }}
                                        </div>
                                        
                                        <!-- Thêm nhãn trục Y -->
                                        <div style="position: absolute; left: 5px; top: 50%; transform: rotate(-90deg) translateY(-50%); transform-origin: left center; font-size: 11px; color: #666; font-weight: 500; white-space: nowrap;">
                                            Doanh thu (triệu đồng)
                                        </div>
                                    </div>
                                </div>
                                <div style="position: absolute; bottom: 10px; right: 10px; font-size: 10px; color: #999;">Cập nhật lần cuối: {{ date('d/m/Y H:i') }}</div>
                                
                                <!-- Thêm nút xuất dữ liệu -->
                                <div style="position: absolute; top: 10px; right: 10px;">
                                    <button class="btn btn-sm" style="background-color: white; border: 1px solid #eee; color: #666; font-size: 11px; padding: 2px 8px;" id="btn-export">
                                        <i class="fas fa-file-export mr-1" style="color: #666;"></i> Xuất CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 p-4" style="border-left: 1px solid #f5f5f5;">
                            <h6 class="mb-4" style="font-weight: 600; color: #444;"><i class="fas fa-chart-pie mr-2" style="color: #db7093;"></i> Phân Tích Doanh Thu</h6>
                            
                            <div class="stats-summary">
                                <div class="mb-4 p-3" style="background-color: #f9f9f9; border-radius: 8px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div style="font-size: 0.85rem; color: #555; font-weight: 500;">Tổng đơn đặt lịch</div>
                                        <div style="font-size: 0.7rem; padding: 2px 8px; background-color: rgba(219, 112, 147, 0.1); color: #db7093; border-radius: 20px; font-weight: 500;">48%</div>
                                    </div>
                                    <div style="font-size: 1.5rem; font-weight: 600; color: #333; margin-bottom: 10px;">{{ $stats['total_bookings'] }}</div>
                                    <div class="progress" style="height: 5px; border-radius: 3px; overflow: hidden; background-color: #eee;">
                                        <div class="progress-bar" style="width: 48%; background-color: #db7093;" role="progressbar" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div style="margin-top: 8px; font-size: 0.75rem; color: #28a745;">
                                        <i class="fas fa-arrow-up mr-1" style="color: #28a745;"></i> 48% tăng trưởng so với tháng trước
                                    </div>
                                </div>
                                
                                <div class="mb-4 p-3" style="background-color: #f9f9f9; border-radius: 8px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div style="font-size: 0.85rem; color: #555; font-weight: 500;">Tổng dịch vụ</div>
                                        <div style="font-size: 0.7rem; padding: 2px 8px; background-color: rgba(72, 187, 120, 0.1); color: #48bb78; border-radius: 20px; font-weight: 500;">60%</div>
                                    </div>
                                    <div style="font-size: 1.5rem; font-weight: 600; color: #333; margin-bottom: 10px;">{{ $stats['total_services'] }}</div>
                                    <div class="progress" style="height: 5px; border-radius: 3px; overflow: hidden; background-color: #eee;">
                                        <div class="progress-bar" style="width: 60%; background-color: #48bb78;" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div style="margin-top: 8px; font-size: 0.75rem; color: #28a745;">
                                        <i class="fas fa-arrow-up mr-1" style="color: #28a745;"></i> 60% tăng trưởng so với tháng trước
                                    </div>
                                </div>
                                
                                <div class="p-3" style="background-color: #f9f9f9; border-radius: 8px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div style="font-size: 0.85rem; color: #555; font-weight: 500;">Doanh thu (triệu đồng)</div>
                                        <div style="font-size: 0.7rem; padding: 2px 8px; background-color: rgba(66, 153, 225, 0.1); color: #4299e1; border-radius: 20px; font-weight: 500;">22%</div>
                                    </div>
                                    <div style="font-size: 1.5rem; font-weight: 600; color: #db7093; margin-bottom: 10px;">{{ number_format($stats['total_revenue'] / 1000000, 1) }}M</div>
                                    <div class="progress" style="height: 5px; border-radius: 3px; overflow: hidden; background-color: #eee;">
                                        <div class="progress-bar" style="width: 22%; background-color: #db7093;" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div style="margin-top: 8px; font-size: 0.75rem; color: #28a745;">
                                        <i class="fas fa-bolt mr-1" style="color: #db7093;"></i> 22% tăng trưởng so với tháng trước
                                    </div>
                                </div>
                                
                                <!-- Thêm mini sparkline chart -->
                                <div class="mt-4">
                                    <div style="font-size: 0.85rem; color: #555; font-weight: 500; margin-bottom: 10px;">Xu hướng doanh thu</div>
                                    <div class="d-flex" style="height: 30px;">
                                        @foreach(array_slice($chartData, max(0, count($chartData)-6), min(6, count($chartData))) as $value)
                                            <div style="flex: 1; margin: 0 1px; position: relative;">
                                                <div style="position: absolute; bottom: 0; left: 0; width: 100%; background-color: #db7093; opacity: 0.7; border-top-left-radius: 2px; border-top-right-radius: 2px; height: {{ max(5, $value / max(array_sum($chartData), 1) * 30) }}px;"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div style="margin-top: 8px; font-size: 0.75rem; color: #666; text-align: right;">
                                        6 tháng gần nhất
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6 mb-4" id="booking-section">
                    <div class="dashboard-card" style="border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); background-color: white; height: 100%;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white; border-bottom: 1px solid #f0f0f0; padding: 18px 24px; border-radius: 15px 15px 0 0;">
                            <h5 class="mb-0" style="font-weight: 600; color: #333;"><i class="fas fa-calendar-alt mr-2" style="color: #db7093;"></i> Lịch Đặt Sắp Tới</h5>
                            <div class="d-flex align-items-center">
                                <span class="badge d-flex align-items-center mr-2" style="background-color: #db7093; color: white; border-radius: 20px; padding: 5px 12px; font-size: 12px;">
                                    <i class="fas fa-clock mr-1"></i> {{ count($recentBookings) }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm" style="background-color: white; border: 1px solid #eee; color: #666; border-radius: 8px;" title="Tùy chọn">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Thêm bộ lọc và sắp xếp -->
                            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="background-color: #f9f9f9; border-bottom: 1px solid #f0f0f0;">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div style="font-size: 0.85rem; color: #666; font-weight: 500; margin-bottom: 5px;">Lọc theo ngày:</div>
                                        <div class="d-flex align-items-center">
                                            <input type="date" id="booking-filter-date" class="form-control form-control-sm booking-filter" style="display: inline-block; width: auto; padding: 4px 8px; font-size: 0.85rem; border-radius: 6px; border-color: #e0e0e0;">
                                            <button id="reset-date-filter" class="btn btn-sm ml-2" style="background-color: #f1f1f1; border: none; color: #666; padding: 4px 8px; font-size: 0.8rem; border-radius: 4px; width: 32px; height: 32px;">
                                                <i class="fas fa-redo-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div style="font-size: 0.85rem; color: #666; font-weight: 500; margin-bottom: 5px;">Sắp xếp:</div>
                                        <div>
                                            <select id="booking-sort-order" class="form-control form-control-sm booking-filter" style="display: inline-block; width: auto; padding: 4px 8px; font-size: 0.85rem; border-radius: 6px; border-color: #e0e0e0;">
                                                <option value="soonest">Gần nhất trước</option>
                                                <option value="latest">Xa nhất trước</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="data-table table mb-0" style="font-size: 0.9rem;">
                                    <thead style="background-color: #f9f9f9;">
                                        <tr>
                                            <th class="pl-4 py-3" style="border-top: none; color: #666; font-weight: 600; text-transform: uppercase; font-size: 0.75rem;">Thời gian</th>
                                            <th class="py-3" style="border-top: none; color: #666; font-weight: 600; text-transform: uppercase; font-size: 0.75rem;">Khách hàng</th>
                                            <th class="pr-4 py-3" style="border-top: none; color: #666; font-weight: 600; text-transform: uppercase; font-size: 0.75rem;">Dịch vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="booking-table-body">
                                        @forelse($recentBookings as $booking)
                                        @php
                                            $bookingTime = \Carbon\Carbon::parse($booking->Thoigiandatlich);
                                            $isUrgent = $bookingTime->diffInMinutes(now()) < 60 && $bookingTime->isFuture() && $bookingTime->isToday();
                                        @endphp
                                        <tr style="border-bottom: 1px solid #f5f5f5; cursor: pointer;" 
                                            class="booking-row {{ $isUrgent ? 'urgent-booking' : '' }}" 
                                            data-status="{{ $booking->Trangthai_ }}" 
                                            data-id="{{ $booking->MaDL }}"
                                            onclick="window.location.href='{{ route('admin.datlich.show', $booking->MaDL) }}'">
                                            <td class="pl-4 py-3">
                                                <div class="d-flex flex-column">
                                                    <div style="font-weight: 600; color: #333; margin-bottom: 3px;">
                                                        @if($isUrgent)
                                                        <span class="urgent-booking-dot" title="Sắp diễn ra (dưới 1 giờ)"></span>
                                                        @endif
                                                        {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}
                                                    </div>
                                                    <div style="font-size: 0.8rem; color: #888;">
                                                        <i class="far fa-clock mr-1" style="color: #db7093;"></i>
                                                        {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}
                                                    </div>
                                                    <div style="margin-top: 5px;">
                                                        @if($booking->Trangthai_ == 'Đã đặt')
                                                            <span style="display: inline-block; padding: 4px 8px; border-radius: 20px; background-color: rgba(66, 153, 225, 0.15); color: #4299e1; font-size: 0.7rem; font-weight: 500;">
                                                                <i class="fas fa-calendar-check mr-1"></i> Đã đặt
                                                            </span>
                                                        @elseif($booking->Trangthai_ == 'Chờ xác nhận')
                                                            <span style="display: inline-block; padding: 4px 8px; border-radius: 20px; background-color: rgba(246, 173, 85, 0.15); color: #f6ad55; font-size: 0.7rem; font-weight: 500;">
                                                                <i class="fas fa-clock mr-1"></i> Chờ xác nhận
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; background-color: {{ '#'.substr(md5($booking->user->Hoten ?? 'N/A'), 0, 6) }}; color: white; font-size: 0.8rem; font-weight: 600; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                        {{ strtoupper(substr($booking->user->Hoten ?? 'N/A', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 500; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px;">
                                                            {{ $booking->user->Hoten ?? 'N/A' }}
                                                        </div>
                                                        @if($booking->user && $booking->user->SDT)
                                                            <div style="font-size: 0.75rem; color: #888;">
                                                                <i class="fas fa-phone-alt mr-1" style="color: #db7093;"></i> {{ $booking->user->SDT }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="pr-4 py-3" style="color: #555;">
                                                <div class="d-flex align-items-center" style="margin-bottom: 5px;">
                                                    <div style="width: 24px; height: 24px; background-color: rgba(219, 112, 147, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
                                                        <i class="fas fa-spa" style="color: #db7093; font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span style="font-weight: 500;" data-bs-toggle="tooltip" title="{{ $booking->dichVu->Tendichvu ?? 'N/A' }}">
                                                        {{ Str::limit($booking->dichVu->Tendichvu ?? 'N/A', 20) }}
                                                    </span>
                                                </div>
                                                @if($booking->dichVu && $booking->dichVu->Gia)
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 24px; height: 24px; background-color: rgba(219, 112, 147, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
                                                        <i class="fas fa-tag" style="color: #db7093; font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span style="font-size: 0.75rem; color: #777;">
                                                        {{ number_format($booking->dichVu->Gia, 0, ',', '.') }} VNĐ
                                                    </span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div style="padding: 20px;">
                                                    <div style="width: 70px; height: 70px; margin: 0 auto 15px; background-color: rgba(219, 112, 147, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-calendar-times" style="font-size: 32px; color: #db7093;"></i>
                                                    </div>
                                                    <p style="color: #777; margin: 0; font-size: 0.9rem;">Không có lịch đặt nào cần xác nhận</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center p-3" style="border-top: 1px solid #f0f0f0;">
                                <a href="{{ route('admin.datlich.index') ?? url('/admin/datlich') }}" class="btn" style="background-color: rgba(219, 112, 147, 0.1); color: #db7093; border: none; padding: 8px 16px; font-size: 0.85rem; border-radius: 8px; transition: all 0.2s;">
                                    <i class="fas fa-list-ul mr-1"></i> Xem tất cả
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="dashboard-card" style="border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; height: 100%;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white; border-bottom: 1px solid #f0f0f0; padding: 15px 20px;">
                            <h5 class="mb-0" style="font-weight: 600; color: #333;"><i class="fas fa-star mr-2" style="color: #ecc94b;"></i> Đánh Giá Gần Đây</h5>
                            <div class="d-flex align-items-center">
                                <span class="badge d-flex align-items-center mr-2" style="background-color: #ecc94b; color: white; border-radius: 20px; padding: 3px 10px; font-size: 10px;">
                                    <i class="fas fa-star mr-1"></i> {{ count($recentReviews) }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm" style="background-color: white; border: 1px solid #eee; color: #666;" title="Tùy chọn">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @forelse($recentReviews as $review)
                            <div style="padding: 15px 20px; border-bottom: 1px solid #f0f0f0;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; background-color: {{ '#'.substr(md5($review->user->Hoten ?? 'Khách hàng'), 0, 6) }}; color: white; font-size: 0.8rem; font-weight: 600;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #333; font-size: 0.9rem;">{{ $review->user->Hoten ?? 'Khách hàng' }}</div>
                                            <div style="color: #888; font-size: 0.75rem;">
                                                <i class="fas fa-clock mr-1" style="color: #db7093;"></i> {{ \Carbon\Carbon::parse($review->Ngaydanhgia)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <div style="margin-right: 10px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->Danhgiasao)
                                                    <i class="fas fa-star" style="color: #ecc94b; font-size: 0.8rem;"></i>
                                                @else
                                                    <i class="far fa-star" style="color: #d3d3d3; font-size: 0.8rem;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div style="background-color: #f8f9fa; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                            <div style="font-size: 0.7rem; font-weight: 600; color: #666;">{{ $review->Danhgiasao }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-size: 0.85rem; color: #555; line-height: 1.5; margin-bottom: 10px;">
                                    {{ Str::limit($review->Nhanxet, 100) }}
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm mr-1" style="background-color: rgba(66, 153, 225, 0.1); color: #4299e1; border: none; padding: 3px 8px; font-size: 0.7rem;">
                                        <i class="fas fa-reply mr-1" style="color: #4299e1;"></i> Trả lời
                                    </button>
                                    <button class="btn btn-sm" style="background-color: rgba(72, 187, 120, 0.1); color: #48bb78; border: none; padding: 3px 8px; font-size: 0.7rem;">
                                        <i class="fas fa-eye mr-1" style="color: #48bb78;"></i> Xem chi tiết
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="far fa-star" style="font-size: 3rem; color: #eee; margin-bottom: 15px; display: block;"></i>
                                <p style="color: #888; margin: 0;">Không có đánh giá gần đây</p>
                                <button class="btn btn-sm mt-3" style="background-color: rgba(236, 201, 75, 0.1); color: #ecc94b; border: 1px solid rgba(236, 201, 75, 0.3); padding: 5px 15px; font-size: 0.8rem;">
                                    <i class="fas fa-plus mr-1" style="color: #ecc94b;"></i> Thêm đánh giá
                                </button>
                            </div>
                            @endforelse
                            
                            @if(count($recentReviews) > 0)
                            <div class="d-flex justify-content-center p-3" style="border-top: 1px solid #f0f0f0;">
                                <a href="{{ route('admin.danhgia.index') ?? url('/admin/danhgia') }}" class="btn btn-sm" style="background-color: #f9f9f9; color: #666; border: none; padding: 5px 15px; font-size: 0.8rem;">
                                    <i class="fas fa-eye mr-1" style="color: #ecc94b;"></i> Xem tất cả đánh giá
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="dashboard-card" style="border: 1px solid #f9f1d1; box-shadow: 0 0 15px rgba(236, 201, 75, 0.1); background-color: #fffdf7;">
                        <div class="card-header" style="background-color: #fffaea; border-bottom: 2px solid #f9f1d1; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
                            <h5 class="mb-0" style="font-weight: 600; color: #333;"><i class="fas fa-home mr-2" style="color: #ecc94b;"></i> Thống Kê Phòng</h5>
                            <div class="d-flex align-items-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm" style="background-color: white; border: 1px solid #eee; color: #666;" title="Tùy chọn">
                                        <i class="fas fa-filter" style="color: #ecc94b;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Biểu đồ tròn cho thống kê phòng -->
                                    <div style="position: relative; height: 180px; width: 180px; margin: 0 auto;">
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: conic-gradient(
                                            #48bb78 0% {{ 100 * $roomStats['available'] / max(array_sum($roomStats), 1) }}%, 
                                            #4299e1 {{ 100 * $roomStats['available'] / max(array_sum($roomStats), 1) }}% {{ 100 * ($roomStats['available'] + $roomStats['occupied']) / max(array_sum($roomStats), 1) }}%, 
                                            #e53e3e {{ 100 * ($roomStats['available'] + $roomStats['occupied']) / max(array_sum($roomStats), 1) }}% 100%
                                        );"></div>
                                        <div style="position: absolute; top: 25%; left: 25%; width: 50%; height: 50%; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 700; color: #333;">
                                            {{ array_sum($roomStats) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Chú thích cho biểu đồ -->
                                    <div class="d-flex justify-content-center mt-3">
                                        <div class="d-flex align-items-center mr-3">
                                            <div style="width: 12px; height: 12px; background-color: #48bb78; border-radius: 2px; margin-right: 5px;"></div>
                                            <span style="font-size: 0.8rem; color: #555;">Sẵn sàng</span>
                                        </div>
                                        <div class="d-flex align-items-center mr-3">
                                            <div style="width: 12px; height: 12px; background-color: #4299e1; border-radius: 2px; margin-right: 5px;"></div>
                                            <span style="font-size: 0.8rem; color: #555;">Đang sử dụng</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 12px; height: 12px; background-color: #e53e3e; border-radius: 2px; margin-right: 5px;"></div>
                                            <span style="font-size: 0.8rem; color: #555;">Bảo trì</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div style="border-radius: 8px; padding: 15px; background-color: rgba(72, 187, 120, 0.1); border-left: 3px solid #48bb78; display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <div style="font-size: 0.85rem; color: #555; font-weight: 500;">Phòng Sẵn Sàng</div>
                                                    <div style="font-size: 1.5rem; font-weight: 600; color: #48bb78;">{{ $roomStats['available'] }}</div>
                                                </div>
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-check-circle" style="color: #48bb78; font-size: 1.2rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mb-3">
                                            <div style="border-radius: 8px; padding: 15px; background-color: rgba(66, 153, 225, 0.1); border-left: 3px solid #4299e1; display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <div style="font-size: 0.85rem; color: #555; font-weight: 500;">Phòng Đang Sử Dụng</div>
                                                    <div style="font-size: 1.5rem; font-weight: 600; color: #4299e1;">{{ $roomStats['occupied'] }}</div>
                                                </div>
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-users" style="color: #4299e1; font-size: 1.2rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div style="border-radius: 8px; padding: 15px; background-color: rgba(229, 62, 62, 0.1); border-left: 3px solid #e53e3e; display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <div style="font-size: 0.85rem; color: #555; font-weight: 500;">Phòng Bảo Trì</div>
                                                    <div style="font-size: 1.5rem; font-weight: 600; color: #e53e3e;">{{ $roomStats['maintenance'] }}</div>
                                                </div>
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-wrench" style="color: #e53e3e; font-size: 1.2rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Thêm nút xem chi tiết -->
                            <div class="d-flex justify-content-center mt-3">
                                <a href="#" class="btn btn-sm" style="background-color: #ecc94b; color: white; border: none; padding: 5px 15px; font-size: 0.8rem; border-radius: 20px;">
                                    <i class="fas fa-eye mr-1" style="color: #fff;"></i> Xem chi tiết phòng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="dashboard-card" style="border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); background-color: white; height: 100%;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: white; border-bottom: 1px solid #f0f0f0; padding: 15px 20px;">
                    <h5 class="mb-0" style="font-weight: 600; color: #333;"><i class="fas fa-ticket-alt mr-2" style="color: #db7093;"></i> Phiếu Hỗ Trợ Đang Mở</h5>
                    <div class="d-flex align-items-center">
                        <span class="badge d-flex align-items-center mr-2" style="background-color: #db7093; color: white; border-radius: 20px; padding: 3px 10px; font-size: 10px;">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ count($openSupportTickets) }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-sm" style="background-color: white; border: 1px solid #eee; color: #666;" title="Tùy chọn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="background-color: #f9f9f9; border-radius: 8px; padding: 10px 15px; margin-bottom: 15px; display: flex; align-items: center;">
                        <i class="fas fa-info-circle mr-2" style="color: #db7093;"></i>
                        <span style="color: #666; font-size: 0.85rem;">
                            Bạn có <span style="font-weight: 600; color: #db7093;">{{ count($openSupportTickets) }}</span> phiếu hỗ trợ đang mở cần xử lý
                        </span>
                    </div>
                    
                    @forelse($openSupportTickets as $ticket)
                    <div style="border: 1px solid #f0f0f0; border-radius: 8px; padding: 15px; margin-bottom: 15px; position: relative;">
                        <div style="position: absolute; top: -6px; right: 10px; background-color: #db7093; color: white; font-size: 10px; padding: 2px 8px; border-radius: 10px;">
                            #{{ $ticket->MaphieuHT }}
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; background-color: {{ '#'.substr(md5($ticket->user->Hoten ?? 'Khách hàng'), 0, 6) }}; color: white; font-size: 0.8rem; font-weight: 600;">
                                {{ substr($ticket->user->Hoten ?? 'Khách hàng', 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #333; font-size: 0.9rem;">{{ $ticket->user->Hoten ?? 'Khách hàng' }}</div>
                                <div style="color: #888; font-size: 0.75rem; display: flex; align-items: center;">
                                    <span class="badge mr-2" style="background-color: rgba(219, 112, 147, 0.1); color: #db7093; font-size: 0.7rem; padding: 2px 6px; border-radius: 10px;">{{ $ticket->ptHoTro->Tenpt ?? 'N/A' }}</span>
                                    <i class="fas fa-clock mr-1" style="color: #db7093;"></i> Đang xử lý
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 0.85rem; color: #555; line-height: 1.5; margin-bottom: 10px; padding: 8px 12px; background-color: #f9f9f9; border-radius: 6px;">
                            {{ Str::limit($ticket->Noidungyeucau, 100) }}
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm mr-1" style="background-color: rgba(72, 187, 120, 0.1); color: #48bb78; border: none; padding: 3px 8px; font-size: 0.7rem;">
                                <i class="fas fa-check mr-1" style="color: #48bb78;"></i> Hoàn thành
                            </button>
                            <button class="btn btn-sm" style="background-color: rgba(66, 153, 225, 0.1); color: #4299e1; border: none; padding: 3px 8px; font-size: 0.7rem;">
                                <i class="fas fa-reply mr-1" style="color: #4299e1;"></i> Trả lời
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #48bb78; margin-bottom: 15px; display: block;"></i>
                        <p style="color: #555; margin: 0 0 5px 0; font-weight: 500;">Tuyệt vời!</p>
                        <p style="color: #888; margin: 0;">Không có phiếu hỗ trợ nào đang mở</p>
                        <button class="btn btn-sm mt-3" style="background-color: rgba(219, 112, 147, 0.1); color: #db7093; border: 1px solid rgba(219, 112, 147, 0.3); padding: 5px 15px; font-size: 0.8rem;">
                            <i class="fas fa-plus mr-1" style="color: #db7093;"></i> Tạo phiếu hỗ trợ
                        </button>
                    </div>
                    @endforelse
                    
                    @if(count($openSupportTickets) > 0)
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('admin.phieuhotro.index') ?? url('/admin/phieuhotro') }}" class="btn btn-sm" style="background-color: #db7093; color: white; border: none; padding: 5px 15px; font-size: 0.8rem; border-radius: 20px;">
                            <i class="fas fa-list-ul mr-1" style="color: #fff;"></i> Xem tất cả phiếu
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Container cho thông báo - sử dụng ID riêng biệt -->
<div id="single-notification-container" class="notifications-container"></div>
@endsection

@section('scripts')
<!-- Load ApexCharts from CDN with fallback -->
<script src="{{ asset('admin/js/time-filter.js') }}"></script>
<script>
    // Function to activate specific chart filter button and update chart
    function activateChartFilterButton(period) {
        console.log('Activating button for period:', period);
        
        // Get all buttons
        const btnDay = document.getElementById('btn-day');
        const btnMonth = document.getElementById('btn-month');
        const btnYear = document.getElementById('btn-year');
        
        if (!btnDay || !btnMonth || !btnYear) {
            console.error('One or more chart filter buttons not found');
            return;
        }
        
        // Reset all buttons first
        btnDay.classList.remove('active');
        btnMonth.classList.remove('active');
        btnYear.classList.remove('active');
        
        // Activate the correct button
        if (period === 'daily') {
            btnDay.classList.add('active');
        } else if (period === 'yearly') {
            btnYear.classList.add('active');
        } else {
            // Default to monthly
            btnMonth.classList.add('active');
        }
        
        // Update chart if it exists
        if (window.revenueChart) {
            console.log('Updating chart data for period:', period);
            
            // Load data for the selected period - thông báo sẽ được xử lý trong hàm này
            loadChartData(window.revenueChart, period);
            
            // Update chart title
            const titleText = period === 'daily' ? 'Doanh thu theo ngày' : 
                             period === 'yearly' ? 'Doanh thu theo năm' : 
                             'Doanh thu theo tháng';
            
            window.revenueChart.updateOptions({
                title: {
                    text: titleText,
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#2d3748'
                    }
                }
            });
        } else {
            console.error('Chart instance not found');
        }
    }
    
    // Try to load ApexCharts from CDN
    function loadApexCharts() {
        return new Promise((resolve, reject) => {
            if (typeof ApexCharts !== 'undefined') {
                console.log('ApexCharts already loaded');
                resolve();
                return;
            }
            
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js';
            script.onload = () => {
                console.log('ApexCharts loaded successfully');
                resolve();
            };
            script.onerror = () => {
                console.error('Failed to load ApexCharts from primary CDN');
                reject(new Error('Failed to load ApexCharts'));
            };
            document.head.appendChild(script);
        });
    }
    
    // Initialize on DOM content loaded
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            await loadApexCharts();
            initializeChart();
            
            // Biến cờ để theo dõi trạng thái notification
            window.isNotificationInProgress = false;
            
            // Thêm hàm xử lý thông báo
            window.showNotification = function(type, title, message, duration = 3000) {
                // Nếu đang có thông báo hiển thị và là loại loading, không tạo thông báo mới
                if (window.isNotificationInProgress) {
                    console.log("Notification already in progress, not creating a new one");
                    return null;
                }
                
                // Đánh dấu đang có thông báo hiển thị
                window.isNotificationInProgress = true;
                
                const id = 'notification-' + Date.now();
                const container = document.getElementById('single-notification-container');
                
                if (!container) {
                    window.isNotificationInProgress = false;
                    return null;
                }
                
                // Xóa tất cả các thông báo cũ trước khi tạo thông báo mới
                container.innerHTML = '';
                console.log("Creating new notification, cleared previous notifications");
                
                const notification = document.createElement('div');
                notification.id = id;
                notification.className = 'notification notification-' + type;
                notification.innerHTML = `
                    <div class="notification-icon">
                        <i class="fas ${type === 'loading' ? 'fa-spinner fa-spin' : type === 'success' ? 'fa-check' : 'fa-exclamation'}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${title}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <div class="notification-progress"></div>
                `;
                
                container.appendChild(notification);
                
                // Hiệu ứng hiển thị
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);
                
                // Tự động ẩn sau duration nếu không phải loading
                let timeoutId = null;
                if (type !== 'loading') {
                    timeoutId = setTimeout(() => {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                            // Đánh dấu không còn thông báo hiển thị
                            window.isNotificationInProgress = false;
                        }, 300);
                    }, duration);
                }
                
                return id;
            };
            
            window.updateNotification = function(id, newType, newTitle, newMessage, duration = 3000) {
                const notification = document.getElementById(id);
                if (!notification) {
                    // Thông báo không còn tồn tại, tạo thông báo mới
                    console.log("Notification not found, creating new one");
                    return window.showNotification(newType, newTitle, newMessage, duration);
                }
                
                console.log("Updating existing notification");
                
                // Thay đổi lớp
                notification.className = 'notification notification-' + newType + ' show';
                
                // Cập nhật nội dung
                const icon = notification.querySelector('.notification-icon i');
                if (icon) {
                    icon.className = 'fas ' + (newType === 'loading' ? 'fa-spinner fa-spin' : newType === 'success' ? 'fa-check' : 'fa-exclamation');
                }
                
                const title = notification.querySelector('.notification-title');
                if (title) {
                    title.textContent = newTitle;
                }
                
                const message = notification.querySelector('.notification-message');
                if (message) {
                    message.textContent = newMessage;
                }
                
                // Tự động ẩn sau duration nếu không phải loading
                if (newType !== 'loading') {
                    setTimeout(() => {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                            // Đánh dấu không còn thông báo hiển thị
                            window.isNotificationInProgress = false;
                        }, 300);
                    }, duration);
                }
                
                return id;
            };
        } catch (error) {
            console.error('Failed to load ApexCharts:', error);
            showStaticChart();
        }
    });
    
    // Function to initialize chart
    function initializeChart() {
        console.log('Initializing chart...');
        const chartElement = document.getElementById('revenue-chart');
        if (!chartElement) {
            console.error('Chart element not found');
            return;
        }
        
        // Hide static chart
        const staticChart = document.getElementById('static-chart');
        if (staticChart) {
            staticChart.style.display = 'none';
        }
        
        // Define chart options
        const chartOptions = {
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Roboto, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            series: [{
                name: 'Doanh thu',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] // Dữ liệu mặc định
            }],
            xaxis: {
                categories: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                labels: {
                    style: {
                        colors: '#888',
                        fontSize: '12px',
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return formatCurrency(value);
                    },
                    style: {
                        colors: '#888',
                        fontSize: '12px',
                    }
                }
            },
            colors: ['#db7093'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.2,
                    gradientToColors: undefined,
                    inverseColors: false,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 5,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            markers: {
                size: 5,
                colors: "#fff",
                strokeColors: '#db7093',
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(value) {
                        return formatCurrency(value) + ' đ';
                    }
                }
            },
            title: {
                text: 'Doanh thu theo tháng',
                align: 'left',
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    color: '#2d3748'
                }
            }
        };
        
        try {
            // Create and render chart
            const chart = new ApexCharts(chartElement, chartOptions);
            chart.render();
            
            // Set the chart ID explicitly to make it accessible for other functions
            chart.el.id = 'revenue-chart';
            window.revenueChart = chart; // Store the chart in global window object for easy access
            
            // Add success indicator
            const successIndicator = document.createElement('div');
            successIndicator.style.position = 'absolute';
            successIndicator.style.bottom = '30px';
            successIndicator.style.left = '10px';
            successIndicator.style.fontSize = '10px';
            successIndicator.style.color = 'green';
            successIndicator.style.padding = '2px 5px';
            successIndicator.style.backgroundColor = 'rgba(0,255,0,0.1)';
            successIndicator.style.borderRadius = '3px';
            successIndicator.textContent = 'Biểu đồ đã được khởi tạo thành công';
            chartElement.appendChild(successIndicator);
            
            // Remove indicator after 5 seconds
            setTimeout(() => successIndicator.remove(), 5000);
            
            // Initialize chart with default period (monthly)
            console.log('Loading initial chart data...');
            loadChartData(chart, 'monthly');
            
            console.log('Chart initialized successfully');
            return chart;
        } catch (error) {
            console.error('Error initializing chart:', error);
            showStaticChart(error.message);
            return null;
        }
    }
    
    // Function to show static chart
    function showStaticChart(errorMessage = 'Không thể tải biểu đồ động') {
        console.log('Showing static chart');
        const staticChart = document.getElementById('static-chart');
        if (staticChart) {
            staticChart.style.display = 'flex';
        }
        
        // Show error message
        const chartElement = document.getElementById('revenue-chart');
        if (chartElement) {
            const errorMsg = document.createElement('div');
            errorMsg.style.position = 'absolute';
            errorMsg.style.top = '10px';
            errorMsg.style.left = '50%';
            errorMsg.style.transform = 'translateX(-50%)';
            errorMsg.style.padding = '5px 10px';
            errorMsg.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
            errorMsg.style.border = '1px solid red';
            errorMsg.style.borderRadius = '5px';
            errorMsg.style.color = 'red';
            errorMsg.style.fontSize = '11px';
            errorMsg.style.zIndex = '100';
            errorMsg.textContent = errorMessage;
            chartElement.appendChild(errorMsg);
                    }
        }
        
    // Function to format currency
        function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'decimal',
            maximumFractionDigits: 0
            }).format(value);
        }
        
    // Function to load chart data from server
    function loadChartData(chart, period, customDateRange = null) {
        console.log('Loading chart data for period:', period, 'with chart:', chart ? 'Chart found' : 'Chart not found');
        
        // Xóa tất cả các thông báo cũ trước khi tạo thông báo mới
        const container = document.getElementById('single-notification-container');
        if (container) {
            container.innerHTML = '';
        }
        
        // Tạo notification để hiển thị đang tải - chỉ tạo một thông báo duy nhất
        const notification = window.showNotification ? 
            window.showNotification('loading', 'Đang tải', `Đang cập nhật dữ liệu theo ${period === 'daily' ? 'ngày' : period === 'yearly' ? 'năm' : 'tháng'}...`) : null;
        
        // Show loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'chart-loading-indicator';
        loadingIndicator.style.position = 'absolute';
        loadingIndicator.style.top = '50%';
        loadingIndicator.style.left = '50%';
        loadingIndicator.style.transform = 'translate(-50%, -50%)';
        loadingIndicator.style.padding = '10px 20px';
        loadingIndicator.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
        loadingIndicator.style.borderRadius = '5px';
        loadingIndicator.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        loadingIndicator.style.zIndex = '100';
        loadingIndicator.innerHTML = '<i class="fas fa-circle-notch fa-spin" style="color: #db7093;"></i> Đang tải dữ liệu...';
        
        const chartElement = document.getElementById('revenue-chart');
        if (chartElement) {
            // Xóa loading indicator cũ nếu có
            const oldIndicator = document.getElementById('chart-loading-indicator');
            if (oldIndicator) {
                oldIndicator.remove();
            }
            chartElement.appendChild(loadingIndicator);
        }
        
        // Prepare URL and parameters
        let url = '/admin/dashboard/revenue-data?period=' + period;
        if (customDateRange && period === 'custom') {
            url += '&start=' + customDateRange.start + '&end=' + customDateRange.end;
        }
        
        console.log('Fetching data from URL:', url);
        
        // Fetch data from server
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Remove loading indicator
                if (loadingIndicator.parentNode) {
                    loadingIndicator.parentNode.removeChild(loadingIndicator);
                }
                
                console.log('Chart data received:', data);
                
                // Process data based on period
                let categories = [];
                let values = [];
                
                if (period === 'daily') {
                    // Process daily data
                    console.log('Processing daily data');
                    data.forEach(item => {
                        if (item.date) {
                            const date = new Date(item.date);
                            categories.push(date.getDate() + '/' + (date.getMonth() + 1));
                            const value = parseFloat(item.total || 0);
                            values.push(isNaN(value) ? 0 : value);
                        }
                    });
                    
                    // If no data, set default values for the last 30 days
                    if (categories.length === 0) {
                        for (let i = 0; i < 30; i++) {
                            const date = new Date();
                            date.setDate(date.getDate() - 29 + i);
                            categories.push(date.getDate() + '/' + (date.getMonth() + 1));
                            values.push(0);
                        }
                    }
                } else if (period === 'yearly') {
                    // Process yearly data
                    console.log('Processing yearly data');
                    data.forEach(item => {
                        if (item.year) {
                            categories.push(item.year.toString());
                            const value = parseFloat(item.total || 0);
                            values.push(isNaN(value) ? 0 : value);
                        }
                    });
                    
                    // If no data, set default values for the last 5 years
                    if (categories.length === 0) {
                        const currentYear = new Date().getFullYear();
                        for (let i = 0; i < 5; i++) {
                            categories.push((currentYear - 4 + i).toString());
                            values.push(0);
                        }
                    }
                } else if (period === 'custom') {
                    // Process custom date range data
                    console.log('Processing custom date range data');
                    data.forEach(item => {
                        if (item.date) {
                            const date = new Date(item.date);
                            categories.push(date.getDate() + '/' + (date.getMonth() + 1));
                            const value = parseFloat(item.total || 0);
                            values.push(isNaN(value) ? 0 : value);
                        }
                    });
                    
                    // If no data, set default values
                    if (categories.length === 0) {
                        categories.push('Không có dữ liệu');
                        values.push(0);
                    }
                } else {
                    // Process monthly data (default)
                    console.log('Processing monthly data');
                    const monthNames = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
                    
                    // Initialize with zero values for all months
                    categories = [...monthNames];
                    values = Array(12).fill(0);
                    
                    // Update with actual data
                    data.forEach(item => {
                        if (item.month) {
                            const monthIndex = parseInt(item.month) - 1;
                            if (monthIndex >= 0 && monthIndex < 12) {
                                const value = parseFloat(item.total || 0);
                                values[monthIndex] = isNaN(value) ? 0 : value;
                            }
                        }
                    });
                }
                
                console.log('Processed chart data:', { categories, values });
                
                // Update chart with new data
                chart.updateOptions({
                    xaxis: {
                        categories: categories
                    }
                });
                
                chart.updateSeries([{
                    name: 'Doanh thu',
                    data: values
                }]);
                
                console.log('Chart updated successfully with new data');
                
                // Update notification if available
                if (window.updateNotification && notification) {
                    setTimeout(() => {
                        window.updateNotification(
                            notification, 
                            'success', 
                            'Thành công', 
                            `Đã cập nhật dữ liệu theo ${period === 'daily' ? 'ngày' : period === 'yearly' ? 'năm' : 'tháng'}`
                        );
                    }, 500);
                }
            })
            .catch(error => {
                // Remove loading indicator
                if (loadingIndicator.parentNode) {
                    loadingIndicator.parentNode.removeChild(loadingIndicator);
                }
                
                console.error('Error fetching chart data:', error);
                
                // Update notification if available
                if (window.updateNotification && notification) {
                    window.updateNotification(
                        notification, 
                        'error', 
                        'Lỗi', 
                        'Không thể tải dữ liệu biểu đồ: ' + error.message
                    );
                }
                
                // Show error message
                const errorIndicator = document.createElement('div');
                errorIndicator.style.position = 'absolute';
                errorIndicator.style.bottom = '30px';
                errorIndicator.style.left = '10px';
                errorIndicator.style.fontSize = '10px';
                errorIndicator.style.color = 'red';
                errorIndicator.style.padding = '2px 5px';
                errorIndicator.style.backgroundColor = 'rgba(255,0,0,0.1)';
                errorIndicator.style.borderRadius = '3px';
                errorIndicator.textContent = 'Lỗi tải dữ liệu: ' + error.message;
                
                if (chartElement) {
                    chartElement.appendChild(errorIndicator);
                    setTimeout(() => {
                        if (errorIndicator.parentNode) {
                            errorIndicator.parentNode.removeChild(errorIndicator);
                        }
                    }, 5000);
                }
            });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DEBUG: Kiểm tra dropdown tùy chỉnh");
        
        const customDateBtn = document.getElementById('custom-date-btn');
        const customDateMenu = document.getElementById('custom-date-menu');
        
        console.log("Custom date button:", customDateBtn);
        console.log("Custom date menu:", customDateMenu);
        
        if (customDateBtn) {
            console.log("Button đã tồn tại");
            
            // Thêm event listener test
            customDateBtn.addEventListener('click', function() {
                console.log("DEBUG: Nút tùy chỉnh đã được click");
                
                if (customDateMenu) {
                    if (customDateMenu.style.display === 'none' || customDateMenu.style.display === '') {
                        console.log("DEBUG: Hiển thị dropdown");
                        customDateMenu.style.display = 'block';
                    } else {
                        console.log("DEBUG: Ẩn dropdown");
                        customDateMenu.style.display = 'none';
                    }
                } else {
                    console.error("DEBUG: Không tìm thấy dropdown menu");
                }
            });
        } else {
            console.error("DEBUG: Không tìm thấy nút tùy chỉnh");
        }
        
        // Kích hoạt tooltips
        $(function () {
            // Sử dụng Bootstrap 5 tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    container: 'body',
                    boundary: 'window'
                });
            });
            
            // Hiệu ứng hover cho các nút hành động
            $('.btn').hover(
                function() {
                    $(this).css('transform', 'translateY(-2px)');
                    $(this).css('box-shadow', '0 4px 8px rgba(0,0,0,0.1)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                    $(this).css('box-shadow', 'none');
                }
            );
        });
    });
</script>

<!-- Script xử lý bộ lọc và sắp xếp -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các phần tử DOM
        const filterStatus = document.getElementById('filter-status');
        const sortOrder = document.getElementById('sort-order');
        const bookingTable = document.querySelector('.data-table');
        
        if (!filterStatus || !sortOrder || !bookingTable) return;
        
        const bookingRows = bookingTable.querySelectorAll('tbody tr');
        if (bookingRows.length === 0) return;
        
        // Lưu lại các hàng gốc để có thể khôi phục
        const originalRows = Array.from(bookingRows);
        
        // Hàm lọc và sắp xếp
        function filterAndSortBookings() {
            // Lấy giá trị từ các bộ lọc
            const statusValue = filterStatus.value;
            const sortValue = sortOrder.value;
            
            // Clone mảng các hàng gốc để xử lý
            let filteredRows = [...originalRows];
            
            // Lọc theo trạng thái
            if (statusValue !== 'all') {
                filteredRows = filteredRows.filter(row => {
                    const statusCell = row.querySelector('td:first-child span');
                    
                    // Hiển thị thông tin debug
                    console.log("Đang lọc theo trạng thái:", statusValue);
                    console.log("Nội dung ô trạng thái:", statusCell ? statusCell.textContent.trim() : "Không có");
                    console.log("Title của ô trạng thái:", statusCell ? statusCell.getAttribute('title') : "Không có");
                    
                    // Kiểm tra trạng thái dựa trên cả nội dung hiển thị và thuộc tính title
                    if (statusCell) {
                        const cellText = statusCell.textContent.trim();
                        const cellTitle = statusCell.getAttribute('title') || '';
                        
                        // Kiểm tra đặc biệt cho "Chờ xác nhận" - có thể hiển thị dưới dạng rút gọn hoặc đầy đủ
                        if (statusValue === 'Chờ xác nhận') {
                            return cellText.includes('Chờ') || cellTitle.includes('Chờ xác nhận') || 
                                  cellText.includes('Đã đặt') || cellTitle.includes('Đã đặt');
                        }
                        
                        // Kiểm tra đặc biệt cho "Đã hủy"
                        if (statusValue === 'Đã hủy') {
                            return cellText.includes('Hủy') || cellTitle.includes('Hủy') || 
                                  cellText.includes('Đã hủy') || cellTitle.includes('Đã hủy');
                        }
                        
                        // Cho các trạng thái khác
                        return cellText.includes(statusValue) || cellTitle.includes(statusValue);
                    }
                    return false;
                });
            }
            
            // Sắp xếp theo thời gian
            filteredRows.sort((a, b) => {
                const dateA = getDateFromRow(a);
                const dateB = getDateFromRow(b);
                
                return sortValue === 'newest' ? 
                    dateB.getTime() - dateA.getTime() : // Mới nhất trước
                    dateA.getTime() - dateB.getTime();  // Cũ nhất trước
            });
            
            const tbody = bookingTable.querySelector('tbody');
            
            // Xóa tất cả các hàng hiện tại
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }
            
            // Hiển thị "Không có dữ liệu" nếu không có kết quả
            if (filteredRows.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="4" class="text-center py-5">
                        <div style="padding: 20px;">
                            <div style="width: 70px; height: 70px; margin: 0 auto 15px; background-color: rgba(219, 112, 147, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-search" style="font-size: 32px; color: #db7093;"></i>
                            </div>
                            <p style="color: #777; margin: 0; font-size: 0.9rem;">Không tìm thấy đặt lịch phù hợp</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(emptyRow);
            } else {
                // Thêm lại các hàng đã lọc và sắp xếp
                filteredRows.forEach(row => {
                    tbody.appendChild(row);
                });
            }
        }
        
        // Hàm lấy ngày từ một hàng
        function getDateFromRow(row) {
            try {
                const dateText = row.querySelector('td:nth-child(3) .d-flex:last-child span');
                if (!dateText) return new Date();
                
                const dateStr = dateText.textContent.trim();
                const dateParts = dateStr.split(' ')[0].split('/');
                const timeParts = dateStr.split(' ')[1].split(':');
                
                // Định dạng: DD/MM/YYYY HH:MM
                const date = new Date(
                    parseInt(dateParts[2]), // năm
                    parseInt(dateParts[1]) - 1, // tháng (0-11)
                    parseInt(dateParts[0]), // ngày
                    parseInt(timeParts[0]), // giờ
                    parseInt(timeParts[1])  // phút
                );
                
                return isNaN(date.getTime()) ? new Date() : date;
            } catch (error) {
                console.error('Lỗi khi lấy ngày từ hàng:', error);
                return new Date();
            }
        }
        
        // Gắn sự kiện cho các bộ lọc
        filterStatus.addEventListener('change', filterAndSortBookings);
        sortOrder.addEventListener('change', filterAndSortBookings);
        
        // Thêm debug logs vào console khi trang tải xong
        console.log("Script lọc đặt lịch đã được khởi tạo");
        console.log("Tổng số hàng:", originalRows.length);
        
        // Hiển thị các giá trị trạng thái có trong bảng
        const statuses = [];
        originalRows.forEach(row => {
            const statusCell = row.querySelector('td:first-child span');
            if (statusCell) {
                statuses.push({
                    text: statusCell.textContent.trim(),
                    title: statusCell.getAttribute('title') || 'Không có title'
                });
            }
        });
        console.log("Các trạng thái trong bảng:", statuses);
    });
</script>

<!-- Thêm code xử lý nút lọc cho biểu đồ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khai báo biến flag để theo dõi trạng thái đang tải
    let isLoading = false;
    
    // Xử lý các nút lọc cho biểu đồ
    const btnDay = document.getElementById('btn-day');
    const btnMonth = document.getElementById('btn-month');
    const btnYear = document.getElementById('btn-year');
    const chartElement = document.getElementById('revenue-chart');
    
    if (btnDay && btnMonth && btnYear && chartElement) {
        console.log("Chart filter buttons found");
        
        // Gắn sự kiện click cho từng nút
        btnDay.addEventListener('click', function() {
            // Kiểm tra nếu đang tải hoặc đã active thì bỏ qua
            if (isLoading || btnDay.classList.contains('active')) return;
            
            console.log('Day button clicked');
            // Đặt flag đang tải
            isLoading = true;
            
            // Sử dụng hàm activateChartFilterButton để xử lý
            activateChartFilterButton('daily');
            
            // Đặt lại flag sau khi hoàn thành
            setTimeout(() => {
                isLoading = false;
            }, 1000);
        });
        
        btnMonth.addEventListener('click', function() {
            // Kiểm tra nếu đang tải hoặc đã active thì bỏ qua
            if (isLoading || btnMonth.classList.contains('active')) return;
            
            console.log('Month button clicked');
            // Đặt flag đang tải
            isLoading = true;
            
            // Sử dụng hàm activateChartFilterButton để xử lý
            activateChartFilterButton('monthly');
            
            // Đặt lại flag sau khi hoàn thành
            setTimeout(() => {
                isLoading = false;
            }, 1000);
        });
        
        btnYear.addEventListener('click', function() {
            // Kiểm tra nếu đang tải hoặc đã active thì bỏ qua
            if (isLoading || btnYear.classList.contains('active')) return;
            
            console.log('Year button clicked');
            // Đặt flag đang tải
            isLoading = true;
            
            // Sử dụng hàm activateChartFilterButton để xử lý
            activateChartFilterButton('yearly');
            
            // Đặt lại flag sau khi hoàn thành
            setTimeout(() => {
                isLoading = false;
            }, 1000);
        });
        
        // Set monthly as default period for the chart
        console.log("Setting default period to monthly");
        
        // Make sure month button is active by default
        btnMonth.classList.add('active');
        btnDay.classList.remove('active'); 
        btnYear.classList.remove('active');
    } else {
        console.error("One or more chart filter buttons not found");
    }
});
</script>

<!-- Get custom date filter elements -->
<script>
    const applyCustomDateBtn = document.getElementById('apply-custom-date');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    
    if (applyCustomDateBtn && startDateInput && endDateInput) {
        console.log("Custom date filter buttons found");
        
        applyCustomDateBtn.addEventListener('click', function() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (!startDate || !endDate) {
                alert('Vui lòng chọn ngày bắt đầu và ngày kết thúc');
                return;
            }
            
            try {
                // Use globally stored chart
                if (window.revenueChart) {
                    // Update chart with custom date range - thông báo sẽ được xử lý trong hàm này
                    loadChartData(window.revenueChart, 'custom', {
                        start: startDate,
                        end: endDate
                    });
                    
                    // Update chart title
                    window.revenueChart.updateOptions({
                        title: {
                            text: 'Doanh thu từ ' + startDate.split('T')[0] + ' đến ' + endDate.split('T')[0],
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#2d3748'
                            }
                        }
                    });
                    
                    // Also update the revenue analytics section with the custom date range
                    if (typeof updateRevenueAnalytics === 'function') {
                        updateRevenueAnalytics('custom', null, {
                            start_date: startDate,
                            end_date: endDate
                        });
                    }
                } else {
                    console.error("Cannot find revenue chart instance for custom date range");
                    window.showNotification('error', 'Lỗi', 'Không tìm thấy biểu đồ');
                }
            } catch (error) {
                console.error("Error updating chart with custom date range:", error);
                window.showNotification('error', 'Lỗi', 'Không thể cập nhật biểu đồ: ' + error.message);
            }
        });
    }
</script>

<script>
    // ... existing script code ...

    // Booking confirmations and filtering
    document.addEventListener('DOMContentLoaded', function() {
        // Handle booking confirmation buttons
        const confirmButtons = document.querySelectorAll('.confirm-booking-btn');
        confirmButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-id');
                if (confirm('Bạn có chắc chắn muốn xác nhận đặt lịch này không?')) {
                    updateBookingStatus(bookingId, 'Đã xác nhận');
                }
            });
        });

        // Handle booking cancellation buttons
        const cancelButtons = document.querySelectorAll('.cancel-booking-btn');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-id');
                if (confirm('Bạn có chắc chắn muốn hủy đặt lịch này không?')) {
                    updateBookingStatus(bookingId, 'Đã hủy');
                }
            });
        });

        // Function to update booking status via AJAX
        function updateBookingStatus(bookingId, status) {
            // Show loading notification
            showNotification('loading', 'Đang cập nhật...', 'Vui lòng đợi trong khi chúng tôi cập nhật trạng thái đặt lịch.');
            
            fetch(`/admin/datlich/${bookingId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi khi cập nhật trạng thái');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    const row = document.querySelector(`.booking-row[data-id="${bookingId}"]`);
                    if (row) {
                        row.classList.add('fade-out');
                        setTimeout(() => {
                            row.remove();
                            // Check if table is empty
                            const tbody = document.getElementById('booking-table-body');
                            if (tbody.querySelectorAll('tr.booking-row').length === 0) {
                                tbody.innerHTML = `
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div style="padding: 20px;">
                                            <div style="width: 70px; height: 70px; margin: 0 auto 15px; background-color: rgba(219, 112, 147, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-calendar-times" style="font-size: 32px; color: #db7093;"></i>
                                            </div>
                                            <p style="color: #777; margin: 0; font-size: 0.9rem;">Không có lịch đặt nào cần xác nhận</p>
                                        </div>
                                    </td>
                                </tr>`;
                            }
                        }, 300);
                    }
                    
                    // Show success notification
                    showNotification('success', 'Cập nhật thành công', `Trạng thái đặt lịch đã được cập nhật thành ${status}`);
                } else {
                    throw new Error(data.message || 'Không thể cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Lỗi cập nhật', error.message);
            });
        }

        // Filter bookings by status
        const statusFilter = document.getElementById('booking-filter-status');
        const sortOrder = document.getElementById('booking-sort-order');
        
        document.querySelectorAll('.booking-filter').forEach(filter => {
            filter.addEventListener('change', filterBookings);
        });
        
        function filterBookings() {
            const selectedStatus = statusFilter.value;
            const rows = document.querySelectorAll('#booking-table-body .booking-row');
            
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                
                if (selectedStatus === 'all' || selectedStatus === rowStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Check if any rows are visible
            let visibleRows = false;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    visibleRows = true;
                }
            });
            
            // Show empty message if no rows visible
            const tbody = document.getElementById('booking-table-body');
            const emptyMessage = document.querySelector('#booking-table-body .empty-message');
            
            if (!visibleRows) {
                if (!emptyMessage) {
                    tbody.innerHTML += `
                    <tr class="empty-message">
                        <td colspan="4" class="text-center py-5">
                            <div style="padding: 20px;">
                                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background-color: rgba(219, 112, 147, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-filter" style="font-size: 32px; color: #db7093;"></i>
                                </div>
                                <p style="color: #777; margin: 0; font-size: 0.9rem;">Không có lịch đặt nào phù hợp với bộ lọc</p>
                            </div>
                        </td>
                    </tr>`;
                }
            } else if (emptyMessage) {
                emptyMessage.remove();
            }
        }
        
        // Notification system
        function showNotification(type, title, message) {
            const container = document.querySelector('.notifications-container') || createNotificationContainer();
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            notification.innerHTML = `
                <div class="notification-icon">
                    ${type === 'success' ? '<i class="fas fa-check"></i>' : 
                      type === 'error' ? '<i class="fas fa-times"></i>' : 
                      '<i class="fas fa-spinner fa-spin"></i>'}
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <div class="notification-progress"></div>
            `;
            
            container.appendChild(notification);
            
            // Show animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto remove after delay (except for loading)
            if (type !== 'loading') {
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }
            
            return notification;
        }
        
        function createNotificationContainer() {
            const container = document.createElement('div');
            container.className = 'notifications-container';
            document.body.appendChild(container);
            return container;
        }
        
        function updateNotification(notification, type, title, message) {
            notification.className = `notification notification-${type} show`;
            
            const icon = notification.querySelector('.notification-icon');
            icon.innerHTML = type === 'success' ? '<i class="fas fa-check"></i>' : 
                             type === 'error' ? '<i class="fas fa-times"></i>' : 
                             '<i class="fas fa-spinner fa-spin"></i>';
            
            notification.querySelector('.notification-title').textContent = title;
            notification.querySelector('.notification-message').textContent = message;
            
            if (type !== 'loading') {
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }
        }
    });

</script>

<script>
    // ... existing script code ...

    // Xử lý lọc theo ngày
    document.addEventListener('DOMContentLoaded', function() {
        const dateFilter = document.getElementById('booking-filter-date');
        const resetBtn = document.getElementById('reset-date-filter');
        const rows = document.querySelectorAll('#booking-table-body .booking-row');
        
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                const selectedDate = this.value;
                
                if (selectedDate) {
                    rows.forEach(row => {
                        const dateCell = row.querySelector('td:first-child .d-flex:first-child div:first-child');
                        if (dateCell) {
                            const rowDate = convertDateFormat(dateCell.textContent.trim());
                            if (rowDate === selectedDate) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                } else {
                    // Nếu không chọn ngày, hiển thị tất cả
                    rows.forEach(row => {
                        row.style.display = '';
                    });
                }
                
                // Kiểm tra nếu không có kết quả hiển thị
                checkEmptyResults();
            });
        }
        
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (dateFilter) {
                    dateFilter.value = '';
                    // Hiển thị lại tất cả các hàng
                    rows.forEach(row => {
                        row.style.display = '';
                    });
                    
                    // Xóa thông báo không có kết quả nếu có
                    const emptyMessage = document.querySelector('#booking-table-body .empty-message');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                }
            });
        }
        
        // Hàm để chuyển định dạng ngày từ dd/mm/yyyy sang yyyy-mm-dd
        function convertDateFormat(dateStr) {
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
            }
            return '';
        }
        
        // Hàm kiểm tra và hiển thị thông báo khi không có kết quả
        function checkEmptyResults() {
            let visibleRows = false;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    visibleRows = true;
                }
            });
            
            const tbody = document.getElementById('booking-table-body');
            const emptyMessage = document.querySelector('#booking-table-body .empty-message');
            
            if (!visibleRows) {
                if (!emptyMessage) {
                    const tr = document.createElement('tr');
                    tr.className = 'empty-message';
                    tr.innerHTML = `
                        <td colspan="3" class="text-center py-5">
                            <div style="padding: 20px;">
                                <div style="width: 70px; height: 70px; margin: 0 auto 15px; background-color: rgba(219, 112, 147, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-times" style="font-size: 32px; color: #db7093;"></i>
                                </div>
                                <p style="color: #777; margin: 0; font-size: 0.9rem;">Không có lịch đặt nào cho ngày đã chọn</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                }
            } else if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    });
</script>
@endsection