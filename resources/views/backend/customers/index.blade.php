@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/customers.css') }}" rel="stylesheet">
<style>
    :root {
        --primary-pink: #ff6b95;
        --dark-pink: #e84a78;
        --light-pink: #ffdbe9;
        --light-pink-hover: #ffd0e1;
        --pink-gradient: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
        --secondary-color: #8e44ad;
        --text-primary: #2c3e50;
        --text-secondary: #7a8ca0;
        --green: #2ecc71;
        --yellow: #f1c40f;
        --red: #e74c3c;
        --white: #ffffff;
        --light-gray: #f7f9fc;
        --border-color: #e6e9ed;
        --spa-primary: #ff6b95;
        --spa-primary-dark: #e84a78;
        --spa-secondary: #ffdbe9;
        --spa-accent: #ff4778;
        --spa-light: #fff0f5;
        --spa-dark: #d23964;
        --spa-text: #2c3e50;
        --spa-card-shadow: 0 8px 20px rgba(255, 107, 149, 0.15);
        --spa-gradient: linear-gradient(135deg, var(--primary-pink) 0%, var(--dark-pink) 100%);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }
    
    /* Customer Dashboard Header Styling */
    .customer-dashboard-header {
        background: var(--pink-gradient);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(232, 74, 120, 0.25);
        color: white;
    }

    .customer-dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
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

    .header-content {
        position: relative;
        z-index: 4;
    }

    .header-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.85;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        position: relative;
        z-index: 4;
    }
    
    .btn-customer {
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-customer i {
        font-size: 0.9rem;
    }

    .btn-primary-customer {
        background: white;
        color: var(--spa-primary-dark);
    }

    .btn-primary-customer:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: var(--spa-dark);
    }
    
    .page-heading {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border-radius: 10px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-heading::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background-image: url('/img/zen-pattern.png');
        background-size: cover;
        opacity: 0.1;
    }
    
    .spa-card {
        border: none;
        border-radius: 10px;
        box-shadow: var(--spa-card-shadow);
        transition: var(--transition);
        overflow: hidden;
        background: white;
    }
    
    .spa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(255, 107, 149, 0.2);
    }
    
    .spa-card-header {
        background: var(--spa-light);
        border-bottom: none;
        font-weight: 600;
        color: var(--spa-dark);
        padding: 1.25rem 1.5rem;
    }
    
    .stat-card {
        border-radius: 10px;
        padding: 1.5rem;
        background: white;
        position: relative;
        overflow: hidden;
        border-left: 4px solid var(--spa-primary);
    }
    
    .stat-card-spa {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .stat-card-spa .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-bottom: 1rem;
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        color: white;
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.3);
    }
    
    .stat-card-spa .stat-icon i {
        font-size: 1.5rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--spa-dark);
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: var(--spa-text);
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .spa-table thead th {
        background-color: var(--spa-light);
        border: none;
        color: var(--spa-dark);
        font-weight: 600;
        font-size: 0.9rem;
        padding: 1rem 1.5rem;
    }
    
    .spa-table tbody tr {
        transition: all 0.3s;
        border-bottom: 1px solid #eee;
        position: relative;
        z-index: 1;
    }
    
    .spa-table tbody tr:hover {
        background-color: rgba(255, 219, 233, 0.15);
        transform: scale(1.01);
        z-index: 2;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.1);
    }
    
    .spa-table tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
    }
    
    .customer-name {
        font-weight: 600;
        color: var(--spa-dark);
        display: flex;
        align-items: flex-start;
        flex-direction: column;
    }
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 0.75rem;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }
    
    .btn-spa {
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }
    
    .btn-spa-primary {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border: none;
        color: white;
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.2);
    }
    
    .btn-spa-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 107, 149, 0.3);
        color: white;
    }
    
    .search-filter-spa {
        position: relative;
    }
    
    .search-filter-spa .form-control {
        border-radius: 50px;
        padding: 0.75rem 1.5rem 0.75rem 3rem;
        border: 1px solid var(--light-pink);
        box-shadow: 0 2px 10px rgba(255, 107, 149, 0.05);
        transition: all 0.3s;
    }
    
    .search-filter-spa .form-control:focus {
        border-color: var(--spa-primary);
        box-shadow: 0 2px 15px rgba(255, 107, 149, 0.15);
    }
    
    .search-filter-spa .search-icon {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--spa-primary-dark);
    }
    
    .spa-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .membership-regular {
        background-color: var(--spa-light);
        color: var(--spa-text);
    }
    
    .membership-vip {
        background: linear-gradient(120deg, #ffd700, #daa520);
        color: white;
    }
    
    .membership-platinum {
        background: linear-gradient(120deg, #e0e0e0, #a9a9a9);
        color: white;
    }
    
    .membership-diamond {
        background: linear-gradient(120deg, #b3e5fc, #4fc3f7);
        color: white;
    }
    
    .action-btns {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    /* Custom pagination styling */
    .pagination {
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .page-item {
        margin: 0 2px;
        list-style: none;
    }
    
    .page-link {
        border-radius: 50% !important;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--spa-dark);
        border: none;
        font-weight: 500;
        transition: all 0.3s;
        padding: 0;
        font-size: 14px;
        text-decoration: none;
    }
    
    .page-link:hover {
        background-color: var(--light-pink);
        color: var(--spa-primary-dark);
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background-color: var(--spa-primary);
        color: white;
        box-shadow: 0 4px 8px rgba(255, 107, 149, 0.2);
        transform: translateY(-2px);
    }
    
    .page-item.disabled .page-link {
        background-color: #f5f5f5;
        color: #aaa;
        cursor: not-allowed;
    }
    
    /* Fix for the pagination arrows */
    .pagination-arrow {
        font-size: 14px;
        width: auto !important;
        padding: 0 15px !important;
        border-radius: 20px !important;
    }
    
    /* Style for pagination text */
    .pagination-text {
        color: var(--spa-text);
        background: none !important;
        width: auto !important;
        padding: 0 !important;
        font-weight: normal;
    }
    
    /* Override Bootstrap pagination styles */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border-radius: 20px !important;
        margin: 0;
    }

    /* Remove any SVG styling that might be causing problems */
    .pagination svg {
        width: 20px !important;
        height: 20px !important;
        vertical-align: middle;
    }
    
    /* Fix for any flex issues */
    #dataTable_paginate {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    
    /* Filter dropdown styling */
    select.form-control {
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        border: 1px solid var(--light-pink);
        box-shadow: 0 2px 10px rgba(255, 107, 149, 0.05);
        color: var(--spa-dark);
        background-color: white;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    select.form-control:focus {
        border-color: var(--spa-primary);
        box-shadow: 0 2px 15px rgba(255, 107, 149, 0.15);
    }
    
    /* Improve dropdown animation */
    .dropdown-menu {
        border: none;
        border-radius: var(--radius-md);
        box-shadow: 0 5px 15px rgba(255, 107, 149, 0.15);
        animation: dropdownFade 0.3s ease;
    }
    
    @keyframes dropdownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .dropdown-item {
        padding: 0.6rem 1.5rem;
        color: var(--spa-text);
        transition: all 0.3s;
    }
    
    .dropdown-item:hover {
        background-color: var(--spa-light);
        color: var(--spa-primary-dark);
    }
    
    .dropdown-header {
        background-color: var(--spa-light);
        color: var(--spa-dark);
        font-weight: 600;
        padding: 0.6rem 1.5rem;
    }
    
    /* Service count badge */
    .service-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: var(--spa-light);
        color: var(--spa-dark);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
    }
    
    .service-badge:hover {
        background-color: var(--light-pink);
        transform: translateY(-2px);
    }
    
    .service-badge i {
        font-size: 0.7rem;
        color: var(--spa-primary);
    }
    
    .recent-service {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: var(--spa-dark);
        opacity: 0.7;
        padding-left: 0.5rem;
        border-left: 2px solid var(--light-pink);
        transition: all 0.3s;
    }
    
    tr:hover .recent-service {
        opacity: 1;
        border-left-color: var(--spa-primary);
    }
    
    /* Table loading shimmer effect */
    @keyframes tableShimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .table-loader {
        animation: tableShimmer 1.5s infinite linear;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,219,233,0.3) 50%, rgba(255,255,255,0) 100%);
        background-size: 1000px 100%;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .table-is-loading .table-loader {
        opacity: 1;
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--spa-text);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--light-pink);
        margin-bottom: 1rem;
    }
    
    .empty-state h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--spa-dark);
    }
    
    .empty-state p {
        max-width: 400px;
        margin: 0 auto 1.5rem;
        color: var(--text-secondary);
    }
    
    /* Animate entrance */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .spa-card {
        animation: fadeInUp 0.5s ease-out;
    }
    
    .spa-card:nth-child(2) {
        animation-delay: 0.1s;
    }
    
    .spa-card:nth-child(3) {
        animation-delay: 0.2s;
    }
    
    .spa-card:nth-child(4) {
        animation-delay: 0.3s;
    }

    tr:hover .customer-avatar {
        border-color: var(--light-pink);
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.2);
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="customer-dashboard-header">
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="header-shimmer"></div>
        
        <div class="header-content">
            <h1 class="header-title">Quản Lý Khách Hàng</h1>
            <p class="header-subtitle">
                <i class="fas fa-spa mr-1"></i> Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất
            </p>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('admin.customers.create') }}" class="btn-customer btn-primary-customer">
                <i class="fas fa-user-plus"></i> Thêm Khách Hàng Mới
            </a>
        </div>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $allCustomers->count() }}</div>
                    <div class="stat-label">Tổng Khách Hàng</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ff8eb4, #ff6b95);">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $allCustomers->where('Ngaysinh', '>=', now()->startOfMonth())->count() }}</div>
                    <div class="stat-label">Khách Hàng Mới (Tháng Này)</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ ($allCustomers->where('Ngaysinh', '>=', now()->startOfMonth())->count() / ($allCustomers->count() ?: 1)) * 100 }}%" 
                             aria-valuenow="{{ ($allCustomers->where('Ngaysinh', '>=', now()->startOfMonth())->count() / ($allCustomers->count() ?: 1)) * 100 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ff6b95, #e84a78);">
                    <i class="fas fa-venus-mars"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($allCustomers->where('Gioitinh', 'Nữ')->count() / ($allCustomers->count() ?: 1) * 100, 0) }}%</div>
                    <div class="stat-label">Tỷ Lệ Khách Hàng Nữ</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: {{ $allCustomers->where('Gioitinh', 'Nữ')->count() / ($allCustomers->count() ?: 1) * 100 }}%; background-color: #ec407a;" 
                             role="progressbar" 
                             aria-valuenow="{{ $allCustomers->where('Gioitinh', 'Nữ')->count() / ($allCustomers->count() ?: 1) * 100 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer List -->
    <div class="spa-card mb-4">
        <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                    <i class="fas fa-users mr-2"></i>Danh Sách Khách Hàng
                </h6>
            </div>
            <div class="dropdown no-arrow">
                <a class="btn btn-sm btn-action" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: var(--spa-light);">
                    <i class="fas fa-ellipsis-v text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Tùy Chọn:</div>
                    <a class="dropdown-item" href="#" id="exportCSV">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-muted"></i>Xuất CSV
                    </a>
                    <a class="dropdown-item" href="#" id="printList">
                        <i class="fas fa-print fa-sm fa-fw mr-2 text-muted"></i>In Danh Sách
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="refreshList">
                        <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-muted"></i>Làm Mới
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="search-filter-spa">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="Tìm kiếm khách hàng..." id="customerSearch">
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="d-flex align-items-center">
                        <label class="mr-2 mb-0" style="color: var(--spa-dark); font-weight: 500;">Lọc theo:</label>
                        <select class="form-control" style="width: auto;">
                            <option value="">Tất cả hạng thành viên</option>
                            <option value="Bac">Thành viên Bạc</option>
                            <option value="Vang">Thành viên Vàng</option>
                            <option value="BachKim">Thành viên Bạch Kim</option>
                            <option value="KimCuong">Thành viên Kim Cương</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table spa-table" id="dataTable" width="100%" cellspacing="0">
                    <div class="table-loader"></div>
                    <thead>
                        <tr>
                            <th>Khách Hàng</th>
                            <th>Liên Hệ</th>
                            <th>Dịch Vụ Sử Dụng</th>
                            <th>Hạng Thành Viên</th>
                            <th class="text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($customers->isEmpty())
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-user-slash"></i>
                                    <h5>Không tìm thấy khách hàng</h5>
                                    <p>Chưa có khách hàng nào trong hệ thống hoặc không khớp với bộ lọc hiện tại.</p>
                                    <a href="{{ route('admin.customers.create') }}" class="btn btn-spa btn-spa-primary">
                                        <i class="fas fa-user-plus mr-2"></i>Thêm Khách Hàng Mới
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @else
                        @foreach($customers as $customer)
                        <tr class="customer-row" data-id="{{ $customer->Manguoidung }}">
                            <td>
                                <div class="customer-name">
                                    <div>
                                        <div>{{ $customer->Hoten }}</div>
                                        <small class="text-muted">Mã KH: {{ $customer->Manguoidung }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="fas fa-phone-alt text-muted mr-2"></i>{{ $customer->SDT }}</div>
                                <div><i class="fas fa-envelope text-muted mr-2"></i>{{ $customer->Email }}</div>
                            </td>
                            <td>
                                <div>
                                    @php
                                        $serviceCount = $customer->datLich->count();
                                        $lastService = $customer->datLich->sortByDesc('Thoigiandatlich')->first();
                                    @endphp
                                    <span class="service-badge">
                                        <i class="fas fa-spa"></i>
                                        {{ $serviceCount }} dịch vụ
                                    </span>
                                    @if($lastService && $lastService->dichVu)
                                        <div class="recent-service">
                                            <i class="fas fa-history mr-1"></i> {{ $lastService->dichVu->Tendichvu }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $hangTV = $customer->hangThanhVien->first();
                                    $hangName = $hangTV ? $hangTV->Tenhang : 'Thường';
                                    $badgeClass = 'membership-regular';
                                    
                                    if($hangName == 'VIP') {
                                        $badgeClass = 'membership-vip';
                                    } elseif($hangName == 'Platinum') {
                                        $badgeClass = 'membership-platinum';
                                    } elseif($hangName == 'Diamond') {
                                        $badgeClass = 'membership-diamond';
                                    }
                                @endphp
                                <span class="spa-badge {{ $badgeClass }}">
                                    @if($hangName != 'Thường')
                                        <i class="fas fa-crown mr-1"></i>
                                    @endif
                                    {{ $hangName }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.customers.show', $customer->Manguoidung) }}" class="btn rounded-circle" style="background-color: #e7f3ff; color: #3c8dbc; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer->Manguoidung) }}" class="btn rounded-circle" style="background-color: #f1e7ff; color: #8950d0; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.confirmDestroy', $customer->Manguoidung) }}" class="btn rounded-circle" style="background-color: #ffe7e7; color: #e74c3c; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info text-muted" id="dataTable_info" role="status" aria-live="polite">
                        Hiển thị {{ $customers->firstItem() ?? 0 }} đến {{ $customers->lastItem() ?? 0 }} của {{ $allCustomers->count() }} khách hàng
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($customers->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link pagination-arrow">
                                        <i class="fas fa-chevron-left mr-1"></i> Trước
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link pagination-arrow" href="{{ $customers->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left mr-1"></i> Trước
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                                @if ($page == $customers->currentPage())
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
                            @if ($customers->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link pagination-arrow" href="{{ $customers->nextPageUrl() }}" rel="next">
                                        Sau <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link pagination-arrow">
                                        Sau <i class="fas fa-chevron-right ml-1"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/customers/index.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show loading state
        const tableEl = document.getElementById('dataTable');
        const searchEl = document.getElementById('customerSearch');
        const membershipFilterEl = document.querySelector('select.form-control');
        
        // Simulate loading state for demonstration
        setTimeout(() => {
            tableEl.classList.add('table-is-loading');
            setTimeout(() => {
                tableEl.classList.remove('table-is-loading');
            }, 600);
        }, 100);
        
        // Search functionality
        if(searchEl) {
            searchEl.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.customer-row');
                
                if(searchTerm.length > 0) {
                    tableEl.classList.add('table-is-loading');
                    
                    setTimeout(() => {
                        rows.forEach(row => {
                            const customerName = row.querySelector('.customer-name').textContent.toLowerCase();
                            const contactInfo = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                            
                            if(customerName.includes(searchTerm) || contactInfo.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                        
                        tableEl.classList.remove('table-is-loading');
                    }, 300);
                } else {
                    rows.forEach(row => {
                        row.style.display = '';
                    });
                }
            });
        }
        
        // Membership filter functionality
        if(membershipFilterEl) {
            membershipFilterEl.addEventListener('change', function(e) {
                const selectedMembership = e.target.value;
                const rows = document.querySelectorAll('.customer-row');
                
                tableEl.classList.add('table-is-loading');
                
                setTimeout(() => {
                    rows.forEach(row => {
                        if(selectedMembership === '') {
                            // Show all rows if "Tất cả hạng thành viên" is selected
                            row.style.display = '';
                        } else {
                            const membershipCell = row.querySelector('td:nth-child(4)');
                            const membershipBadge = membershipCell.querySelector('.spa-badge');
                            const membershipText = membershipBadge ? membershipBadge.textContent.trim() : '';
                            
                            console.log('Selected:', selectedMembership, 'Row text:', membershipText);
                            
                            // Map values from dropdown to potential text in the table
                            let shouldDisplay = false;
                            
                            if (selectedMembership === 'Bac' && membershipText.includes('Bạc')) {
                                shouldDisplay = true;
                            } else if (selectedMembership === 'Vang' && membershipText.includes('Vàng')) {
                                shouldDisplay = true;
                            } else if (selectedMembership === 'BachKim' && membershipText.includes('Bạch Kim')) {
                                shouldDisplay = true;
                            } else if (selectedMembership === 'KimCuong' && membershipText.includes('Kim Cương')) {
                                shouldDisplay = true;
                            }
                            
                            if (shouldDisplay) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                    
                    tableEl.classList.remove('table-is-loading');
                }, 300);
            });
        }
        
        // Make service badge clickable
        const serviceBadges = document.querySelectorAll('.service-badge');
        serviceBadges.forEach(badge => {
            badge.style.cursor = 'pointer';
            badge.addEventListener('click', function() {
                const customerId = this.closest('.customer-row').dataset.id;
                // Show service details in the future
                console.log(`Show services for customer ${customerId}`);
            });
        });
    });
</script>
@endsection