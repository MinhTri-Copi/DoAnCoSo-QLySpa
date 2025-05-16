@extends('backend.layouts.app')

@section('title', 'Quản Lý Đặt Lịch')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection

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
        --primary-pink: #ff6b95;
        --light-pink: #ffdbe9;
        --dark-pink: #e84a78;
        --shadow-pink: 0 8px 25px rgba(255, 107, 149, 0.14);
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
        --radius-lg: 24px;
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 5px 25px rgba(0, 0, 0, 0.07);
    }

    .spa-dashboard-header {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        border-radius: var(--radius-lg);
        padding: 2.2rem 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-pink);
        max-height: 160px;
    }

    .spa-dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
        animation: pulse 6s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.1); opacity: 0.8; }
    }

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
        z-index: 2;
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
        z-index: 3;
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

    @keyframes glitter {
        0% { transform: scale(0); opacity: 0; }
        10% { transform: scale(1); opacity: 0.8; }
        20% { transform: scale(0.2); opacity: 0.2; }
        30% { transform: scale(1.2); opacity: 0.7; }
        40% { transform: scale(0.5); opacity: 0.5; }
        50% { transform: scale(1); opacity: 0.9; }
        60% { transform: scale(0.3); opacity: 0.3; }
        100% { transform: scale(0); opacity: 0; }
    }

    .spa-header-content {
        position: relative;
        z-index: 4;
    }

    .spa-header-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.4rem;
        letter-spacing: 0.5px;
    }

    .spa-header-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        display: flex;
        align-items: center;
    }

    .spa-header-subtitle i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }

    .spa-header-action {
        position: relative;
        z-index: 4;
    }

    .spa-btn-add {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-pink);
        border: none;
        font-size: 1.05rem;
        font-weight: 600;
        padding: 0.8rem 1.7rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-btn-add i {
        font-size: 0.8rem;
        background: rgba(255, 107, 149, 0.15);
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: var(--transition-fast);
    }

    .spa-btn-add:hover {
        background: white;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: var(--dark-pink);
        text-decoration: none;
    }

    .spa-btn-add:hover i {
        background: rgba(255, 107, 149, 0.25);
        transform: rotate(90deg);
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

    .btn-pink {
        background-color: var(--text-on-primary);
        color: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-pink:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-pink i {
        margin-right: 8px;
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        flex: 1;
        min-width: 200px;
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #6c757d;
        font-size: 14px;
    }

    .stat-progress {
        height: 4px;
        background-color: #e9ecef;
        border-radius: 2px;
        margin-top: 15px;
        overflow: hidden;
    }

    .stat-progress-bar {
        height: 100%;
        border-radius: 2px;
    }

    .progress-1 {
        background-color: #4cd964;
        width: 75%;
    }

    .progress-2 {
        background-color: var(--primary-color);
        width: 45%;
    }

    .progress-3 {
        background-color: #007bff;
        width: 60%;
    }

    .progress-4 {
        background-color: #ff9500;
        width: 30%;
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
        margin-bottom: 15px;
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

    .search-filter-container {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
        margin-bottom: 15px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 15px 12px 40px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .search-box input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-light);
        outline: none;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-box {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
    }

    .filter-date {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
    }

    /* CSS cho bộ lọc ngày tháng */
    .date-filters {
        background-color: #f8f9fa;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 15px;
        width: 100%;
        border: 1px solid var(--border-color);
    }
    
    .date-filter-title {
        font-weight: 600;
        font-size: 14px;
        color: var(--primary-color);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .date-filter-title::before {
        content: '';
        width: 4px;
        height: 14px;
        background-color: var(--primary-color);
        margin-right: 8px;
        border-radius: 2px;
        display: inline-block;
    }
    
    .date-filter-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .date-filter-item {
        flex: 1;
        min-width: 200px;
    }
    
    .date-filter-item label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        color: #495057;
    }
    
    .date-filter-item input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .date-filter-item input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-light);
        outline: none;
    }
    
    /* Màu sắc và style riêng cho từng loại */
    .single-date label i {
        color: #28a745; /* Xanh lá */
    }
    
    .from-date label i {
        color: #007bff; /* Xanh dương */
    }
    
    .to-date label i {
        color: #fd7e14; /* Cam */
    }
    
    .single-date input {
        border-left: 4px solid #28a745;
    }
    
    .from-date input {
        border-left: 4px solid #007bff;
    }
    
    .to-date input {
        border-left: 4px solid #fd7e14;
    }
    
    .date-range {
        position: relative;
    }
    
    .date-range::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50px;
        transform: translateX(-50%);
        width: 20px;
        height: 2px;
        background-color: #dee2e6;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid var(--border-color);
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .table tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
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

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-view {
        background-color: var(--info-color);
    }

    .btn-edit {
        background-color: var(--warning-color);
    }

    .btn-delete {
        background-color: var(--danger-color);
    }

    .btn-action:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 8px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: white;
        border: 1px solid var(--border-color);
        color: #495057;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 8px rgba(255, 107, 139, 0.3);
    }

    .page-link:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .page-item.disabled .page-link {
        background-color: #f8f9fa;
        color: #adb5bd;
        cursor: not-allowed;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .empty-state h4 {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #adb5bd;
        max-width: 400px;
        margin: 0 auto 20px;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-pink {
            margin-top: 15px;
            align-self: flex-start;
        }
        
        .stats-container {
            flex-direction: column;
        }
        
        .search-filter-container {
            flex-direction: column;
        }
        
        .filter-box {
            flex-direction: column;
        }
    }
</style>

<div class="spa-dashboard-header animate__animated animate__fadeIn">
    <div class="header-shimmer"></div>
    <div class="glitter-dot"></div>
    <div class="glitter-dot"></div>
    <div class="glitter-dot"></div>
    <div class="glitter-dot"></div>
    
    <div class="spa-header-content">
        <h1 class="spa-header-title" style="color: #ffffff;">Quản Lý Đặt Lịch</h1>
        <p class="spa-header-subtitle">
            <i class="fas fa-calendar-alt"></i>
            Quản lý và theo dõi lịch đặt dịch vụ
        </p>
    </div>
    
    <div class="spa-header-action">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.datlich.statistics') }}" class="spa-btn-add me-2">
                <i class="fas fa-chart-bar"></i>
                <span>Thống Kê</span>
            </a>
            <a href="{{ route('admin.datlich.create') }}" class="spa-btn-add">
                <i class="fas fa-plus"></i>
                <span>Thêm Lịch Đặt</span>
            </a>
        </div>
    </div>
</div>

<div class="stats-container">
    {{-- Sử dụng biến được truyền từ controller thay vì tính toán từ $datLichs --}}
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-value">{{ $todayBookings }}</div>
        <div class="stat-label">Lịch Đặt Hôm Nay</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-1" style="width: {{ min(100, ($todayBookings / max(1, $todayBookings)) * 100) }}%"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $pendingBookings }}</div>
        <div class="stat-label">Chờ Xác Nhận</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-2" style="width: {{ min(100, ($pendingBookings / max(1, $pendingBookings + $confirmedBookings + $completedBookings + $cancelledBookings)) * 100) }}%"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $confirmedBookings }}</div>
        <div class="stat-label">Đã Xác Nhận</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-3" style="width: {{ min(100, ($confirmedBookings / max(1, $pendingBookings + $confirmedBookings + $completedBookings + $cancelledBookings)) * 100) }}%"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-flag-checkered"></i>
        </div>
        <div class="stat-value">{{ $completedBookings }}</div>
        <div class="stat-label">Hoàn Thành</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-4" style="width: {{ min(100, ($completedBookings / max(1, $pendingBookings + $confirmedBookings + $completedBookings + $cancelledBookings)) * 100) }}%"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-ban"></i>
        </div>
        <div class="stat-value">{{ $cancelledBookings }}</div>
        <div class="stat-label">Đã Huỷ</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #dc3545; width: {{ min(100, ($cancelledBookings / max(1, $pendingBookings + $confirmedBookings + $completedBookings + $cancelledBookings)) * 100) }}%"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Danh Sách Lịch Đặt
        </div>
        <div>
            <button class="btn-action" style="background-color: var(--primary-color);" id="toggleFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    
    <form action="{{ route('admin.datlich.index') }}" method="GET" id="filterForm">
        <div class="search-filter-container" id="filterContainer">
            <div class="search-box mb-3 w-100">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên người dùng, dịch vụ, mã đặt lịch..." value="{{ request('search') }}">
                <i class="fas fa-search"></i>
            </div>
            
            <div class="filter-box">
                <select name="user_id" class="filter-select">
                    <option value="">-- Tất cả người dùng --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ request('user_id') == $user->Manguoidung ? 'selected' : '' }}>
                            {{ $user->Hoten }}
                        </option>
                    @endforeach
                </select>
                
                <select name="service_id" class="filter-select">
                    <option value="">-- Tất cả dịch vụ --</option>
                    @foreach($dichVus as $dichVu)
                        <option value="{{ $dichVu->MaDV }}" {{ request('service_id') == $dichVu->MaDV ? 'selected' : '' }}>
                            {{ $dichVu->Tendichvu }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" class="filter-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="date-filters">
                <div class="date-filter-title">Lọc theo thời gian</div>
                
                <div class="date-filter-group">
                    <div class="date-filter-item single-date">
                        <label for="date"><i class="fas fa-calendar-day"></i> Ngày cụ thể</label>
                        <input type="date" name="date" id="date" class="filter-date" placeholder="Ngày" value="{{ request('date') }}">
                    </div>
                </div>
                
                <div class="date-filter-title mt-3">Lọc theo khoảng thời gian</div>
                
                <div class="date-filter-group date-range">
                    <div class="date-filter-item from-date">
                        <label for="date_from"><i class="fas fa-calendar-minus"></i> Từ ngày</label>
                        <input type="date" name="date_from" id="date_from" class="filter-date" placeholder="Từ ngày" value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="date-filter-item to-date">
                        <label for="date_to"><i class="fas fa-calendar-plus"></i> Đến ngày</label>
                        <input type="date" name="date_to" id="date_to" class="filter-date" placeholder="Đến ngày" value="{{ request('date_to') }}">
                    </div>
                </div>
            </div>
            
            <div class="filter-box">
                <button type="submit" class="btn-pink" id="searchBtn">
                    <i class="fas fa-search"></i> Tìm Kiếm
                </button>
                
                <a href="{{ route('admin.datlich.index') }}" class="btn-pink" style="background-color: #6c757d;">
                    <i class="fas fa-sync"></i> Đặt Lại
                </a>
            </div>
        </div>
    </form>
    
    @if(session('success'))
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
    @endif
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Đặt Lịch</th>
                    <th>Người Dùng</th>
                    <th>Dịch Vụ</th>
                    <th>Thời Gian</th>
                    <th>Trạng Thái</th>
                    <th class="text-end">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($datLichs as $datLich)
                <tr>
                    <td>{{ $datLich->MaDL }}</td>
                    <td>
                        @if($datLich->user)
                            <div class="d-flex align-items-center">
                                <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: bold; margin-right: 10px;">
                                    {{ substr($datLich->user->Hoten, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ $datLich->user->Hoten }}</div>
                                    <div style="font-size: 12px; color: #6c757d;">{{ $datLich->user->SDT ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($datLich->dichVu)
                            <div>
                                <div style="font-weight: 500;">{{ $datLich->dichVu->Tendichvu }}</div>
                                <div style="font-size: 12px; color: #6c757d;">{{ number_format($datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div>
                            <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('d/m/Y') }}</div>
                            <div style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('H:i') }}</div>
                        </div>
                    </td>
                    <td>
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
                    </td>
                    <td class="text-end">
                        <div class="action-buttons">
                            <a href="{{ route('admin.datlich.show', $datLich->MaDL) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.datlich.edit', $datLich->MaDL) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.datlich.confirmDestroy', $datLich->MaDL) }}" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>Không có dữ liệu</h4>
                            <p>Chưa có lịch đặt nào được tạo hoặc không có lịch đặt phù hợp với bộ lọc.</p>
                            <a href="{{ route('admin.datlich.create') }}" class="btn-pink">
                                <i class="fas fa-plus"></i> Thêm Lịch Đặt
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            Hiển thị {{ $datLichs->firstItem() ?? 0 }} đến {{ $datLichs->lastItem() ?? 0 }} của {{ $datLichs->total() }} bản ghi
        </div>
        <div>
            @if ($datLichs->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($datLichs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $datLichs->appends(request()->except('page'))->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($datLichs->appends(request()->except('page'))->getUrlRange(max(1, $datLichs->currentPage() - 2), min($datLichs->lastPage(), $datLichs->currentPage() + 2)) as $page => $url)
                            @if ($page == $datLichs->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($datLichs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $datLichs->appends(request()->except('page'))->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters
    const toggleFilters = document.getElementById('toggleFilters');
    const filterContainer = document.getElementById('filterContainer');
    
    toggleFilters.addEventListener('click', function() {
        filterContainer.style.display = filterContainer.style.display === 'none' ? 'flex' : 'none';
    });
    
    // Xử lý form tìm kiếm
    const filterForm = document.getElementById('filterForm');
    const searchBtn = document.getElementById('searchBtn');
    
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Loại bỏ các trường không có giá trị để URL gọn hơn
        const formInputs = filterForm.querySelectorAll('input, select');
        let hasValue = false;
        
        formInputs.forEach(input => {
            if (!input.value) {
                input.disabled = true;
            } else {
                hasValue = true;
            }
        });
        
        // Nếu không có điều kiện nào, vẫn submit form để hiển thị tất cả
        if (!hasValue) {
            formInputs.forEach(input => {
                input.disabled = false;
            });
        }
        
        // Submit form
        filterForm.submit();
    });
    
    // Khi nhấn Enter trong ô tìm kiếm thì submit form
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });
    }
    
    // Xử lý các ô ngày tháng
    const dateInput = document.getElementById('date');
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    
    // Khi chọn ngày cụ thể, xóa khoảng thời gian
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            if (this.value) {
                // Nếu ngày cụ thể được chọn, reset khoảng thời gian
                if (dateFromInput) dateFromInput.value = '';
                if (dateToInput) dateToInput.value = '';
            }
        });
    }
    
    // Khi chọn khoảng thời gian, xóa ngày cụ thể
    const handleRangeChange = function() {
        if ((dateFromInput && dateFromInput.value) || (dateToInput && dateToInput.value)) {
            // Nếu một trong hai ô khoảng thời gian được nhập, reset ngày cụ thể
            if (dateInput) dateInput.value = '';
        }
    };
    
    if (dateFromInput) {
        dateFromInput.addEventListener('change', handleRangeChange);
    }
    
    if (dateToInput) {
        dateToInput.addEventListener('change', handleRangeChange);
    }
    
    // Tự động đặt ngày kết thúc nếu chưa có khi chọn ngày bắt đầu
    if (dateFromInput && dateToInput) {
        dateFromInput.addEventListener('change', function() {
            if (this.value && !dateToInput.value) {
                // Đặt ngày kết thúc mặc định là 7 ngày sau ngày bắt đầu
                const startDate = new Date(this.value);
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 7);
                
                // Format lại thành YYYY-MM-DD
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                dateToInput.value = `${year}-${month}-${day}`;
            }
        });
    }
    
    // Initialize tooltips if using Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection