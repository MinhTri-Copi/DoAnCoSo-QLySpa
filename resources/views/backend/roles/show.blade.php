@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/roles.css') }}" rel="stylesheet">
<style>
    /* ===== MODERN SPA DESIGN ===== */
    :root {
        --primary-pink: #ff6b95;
        --light-pink: #ffdbe9;
        --dark-pink: #e84a78;
        --text-primary: #2c3e50;
        --text-secondary: #7a8ca0;
        --info: #3498db;
        --primary: #8e44ad;
        --danger: #e74c3c;
        --light-gray: #f7f9fc;
        --white: #ffffff;
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 5px 25px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.09);
        --shadow-pink: 0 8px 25px rgba(255, 107, 149, 0.14);
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
    }

    /* ===== HEADER ===== */
    .spa-dashboard-header {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        border-radius: var(--radius-lg);
        padding: 1.8rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-pink);
        max-height: 140px;
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

    .spa-dashboard-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
    }

    /* Shimmer effect */
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

    /* Dot animation */
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
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.3rem;
        letter-spacing: 0.5px;
    }

    .spa-header-subtitle {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        display: flex;
        align-items: center;
    }

    .spa-header-subtitle i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .spa-header-action {
        position: relative;
        z-index: 4;
        display: flex;
        gap: 0.8rem;
    }

    .spa-btn-header {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-pink);
        border: none;
        font-size: 0.92rem;
        font-weight: 600;
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-btn-header i {
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

    .spa-btn-header:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: var(--dark-pink);
        text-decoration: none;
    }

    .spa-btn-header:hover i {
        background: rgba(255, 107, 149, 0.25);
        transform: rotate(-15deg);
    }

    .spa-btn-edit i {
        background: rgba(52, 152, 219, 0.15);
    }

    .spa-btn-edit {
        color: var(--info);
    }

    .spa-btn-edit:hover {
        color: #2980b9;
    }

    /* Content Panels */
    .spa-panel {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: var(--transition-medium);
    }

    .spa-panel:hover {
        box-shadow: var(--shadow-lg);
    }

    .spa-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .spa-panel-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .spa-panel-title i {
        color: var(--primary-pink);
    }

    .spa-panel-body {
        padding: 2rem;
    }

    /* Content Cards & Role Info */
    .role-icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--light-pink);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
    }

    .role-icon-circle i {
        font-size: 2.2rem;
        color: var(--primary-pink);
    }

    .role-name {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .role-info-list {
        margin-top: 1.5rem;
    }

    .role-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .role-info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label i {
        color: var(--primary-pink);
        font-size: 0.9rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .role-counter {
        display: inline-block;
        background: var(--light-pink);
        color: var(--primary-pink);
        font-weight: 700;
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
        font-size: 0.85rem;
    }

    .role-divider {
        margin: 1.5rem 0;
        border-color: rgba(0,0,0,0.05);
    }

    .role-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .spa-action-btn {
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-btn-edit {
        background: rgba(52, 152, 219, 0.1);
        color: var(--info);
    }

    .spa-btn-edit:hover {
        background: var(--info);
        color: white;
        text-decoration: none;
    }

    .spa-btn-delete {
        background: rgba(231, 76, 60, 0.1);
        color: var(--danger);
    }

    .spa-btn-delete:hover {
        background: var(--danger);
        color: white;
        text-decoration: none;
    }

    .spa-btn-disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .spa-btn-disabled:hover {
        background: rgba(231, 76, 60, 0.1);
        color: var(--danger);
    }

    /* Chart Styles */
    .chart-container {
        height: 200px;
        position: relative;
    }

    .chart-legend {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Advanced Statistics Styles */
    .stats-control {
        display: flex;
        align-items: center;
    }

    .stats-period-selector {
        display: flex;
        background: var(--light-gray);
        border-radius: 20px;
        padding: 3px;
    }

    .period-btn {
        background: transparent;
        border: none;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .period-btn.active {
        background: var(--white);
        color: var(--primary-pink);
        box-shadow: var(--shadow-sm);
        font-weight: 600;
    }

    .stats-nav {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .stats-nav::-webkit-scrollbar {
        display: none;
    }

    .stats-nav-btn {
        background: transparent;
        border: none;
        padding: 0.5rem 1rem;
        color: var(--text-secondary);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: var(--transition-fast);
        min-width: 80px;
        font-size: 0.9rem;
    }

    .stats-nav-btn i {
        font-size: 1.2rem;
        opacity: 0.7;
        transition: var(--transition-fast);
    }

    .stats-nav-btn:hover {
        color: var(--primary-pink);
    }

    .stats-nav-btn:hover i {
        opacity: 1;
    }

    .stats-nav-btn.active {
        color: var(--primary-pink);
        border-bottom-color: var(--primary-pink);
        font-weight: 600;
    }

    .stats-nav-btn.active i {
        opacity: 1;
    }

    .stats-content-wrapper {
        position: relative;
        min-height: 350px;
    }

    .stats-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
    }

    .stats-content.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        position: relative;
    }

    .chart-header {
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.3rem;
    }

    .chart-description {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0;
    }

    .top-staff-container {
        min-height: 250px;
    }

    .top-staff-list {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .staff-item {
        display: flex;
        align-items: center;
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 1rem;
        transition: var(--transition-fast);
    }

    .staff-item:hover {
        transform: translateX(5px);
        box-shadow: var(--shadow-sm);
    }

    .staff-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--white);
        overflow: hidden;
        margin-right: 1rem;
        border: 2px solid var(--white);
        box-shadow: var(--shadow-sm);
    }

    .staff-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .staff-info {
        flex: 1;
    }

    .staff-name {
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.2rem;
        font-size: 1rem;
    }

    .staff-position {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .staff-stats {
        width: 120px;
        text-align: right;
    }

    .staff-value {
        font-weight: 700;
        color: var(--primary-pink);
        font-size: 1.1rem;
    }

    .staff-metric {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .staff-rank {
        width: 26px;
        height: 26px;
        background: var(--primary-pink);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 1rem;
    }

    .top-staff-empty {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 250px;
    }

    .empty-indicator {
        text-align: center;
        color: var(--text-secondary);
    }

    .empty-indicator i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .stats-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .export-stats-btn {
        background: var(--light-gray);
        color: var(--text-secondary);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .export-stats-btn:hover {
        background: var(--text-primary);
        color: white;
    }

    .stats-last-updated {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-style: italic;
    }

    @media (max-width: 767px) {
        .stats-nav {
            justify-content: flex-start;
        }
    }

    /* Account Table */
    .account-search-wrapper {
        margin-bottom: 1.5rem;
    }

    .search-input-group {
        position: relative;
        max-width: 300px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .search-input {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 2.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        transition: var(--transition-fast);
        background: var(--light-gray);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-pink);
        background: var(--white);
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .spa-table-responsive {
        overflow-x: auto;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
    }

    .spa-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .spa-table thead th {
        background: var(--light-gray);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1.2rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        white-space: nowrap;
    }

    .spa-table thead th:first-child {
        border-top-left-radius: var(--radius-md);
    }

    .spa-table thead th:last-child {
        border-top-right-radius: var(--radius-md);
    }

    .spa-table tbody tr {
        transition: var(--transition-fast);
    }

    .spa-table tbody tr:hover {
        background-color: rgba(255, 107, 149, 0.03);
    }

    .spa-table tbody td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        color: var(--text-primary);
    }

    .spa-table tbody tr:last-child td {
        border-bottom: none;
    }

    .spa-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: var(--radius-md);
    }

    .spa-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: var(--radius-md);
    }

    .user-link {
        color: var(--primary-pink);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition-fast);
    }

    .user-link:hover {
        color: var(--dark-pink);
        text-decoration: none;
    }

    .empty-data {
        color: var(--text-secondary);
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .view-btn {
        background: rgba(52, 152, 219, 0.1);
        color: var(--info);
    }

    .view-btn:hover {
        background: var(--info);
        color: white;
        transform: translateY(-2px);
    }

    .edit-btn {
        background: rgba(255, 107, 149, 0.1);
        color: var(--primary-pink);
    }

    .edit-btn:hover {
        background: var(--primary-pink);
        color: white;
        transform: translateY(-2px);
    }

    /* Empty Accounts Container */
    .empty-accounts-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }

    .empty-accounts-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .empty-accounts-icon i {
        font-size: 2rem;
        color: var(--text-secondary);
    }

    .empty-accounts-text {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .spa-btn-primary {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.25);
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 149, 0.3);
        color: white;
        text-decoration: none;
    }

    @media (max-width: 991px) {
        .spa-dashboard-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.2rem;
            max-height: none;
        }

        .spa-header-action {
            align-self: flex-start;
        }

        .spa-panel-body {
            padding: 1.5rem;
        }
    }

    .chart-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }
    
    .chart-loading i {
        margin-right: 0.5rem;
        color: var(--primary-pink);
    }
    
    .chart-error {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        color: var(--danger);
        font-size: 0.9rem;
    }
    
    .empty-data-message {
        text-align: center;
        padding: 2rem;
        color: var(--text-secondary);
        font-style: italic;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Modern SPA Dashboard Header -->
    <div class="spa-dashboard-header">
        <div class="header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="spa-header-content">
            <h1 class="spa-header-title">Chi Tiết Vai Trò</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-info-circle"></i>
                Xem thông tin chi tiết về vai trò và tài khoản đang sử dụng
            </p>
        </div>
        <div class="spa-header-action">
            <a href="{{ route('admin.roles.edit', $role->RoleID) }}" class="spa-btn-header spa-btn-edit">
                <i class="fas fa-edit"></i>
                Chỉnh Sửa
            </a>
            <a href="{{ route('admin.roles.index') }}" class="spa-btn-header">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <!-- Role Info -->
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <!-- Role Info Card -->
            <div class="spa-panel animate__animated animate__fadeIn">
                <div class="spa-panel-header">
                    <h6 class="spa-panel-title">
                        <i class="fas fa-user-tag"></i>
                        Thông Tin Vai Trò
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="roleDropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="roleDropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.roles.edit', $role->RoleID) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                Chỉnh Sửa
                            </a>
                            @if($role->accounts->count() == 0)
                                <a class="dropdown-item" href="{{ route('admin.roles.confirmDestroy', $role->RoleID) }}">
                                    <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Xóa Vai Trò
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="spa-panel-body">
                    <div class="text-center mb-4">
                        <div class="role-icon-circle">
                            <i class="fas fa-user-tag text-pink"></i>
                        </div>
                        <h4 class="role-name">{{ $role->Tenrole }}</h4>
                    </div>
                    
                    <div class="role-info-list">
                        <div class="role-info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag"></i>
                                Mã Vai Trò:
                            </div>
                            <div class="info-value">{{ $role->RoleID }}</div>
                        </div>
                        <div class="role-info-item">
                            <div class="info-label">
                                <i class="fas fa-tag"></i>
                                Tên Vai Trò:
                            </div>
                            <div class="info-value">{{ $role->Tenrole }}</div>
                        </div>
                        <div class="role-info-item">
                            <div class="info-label">
                                <i class="fas fa-users"></i>
                                Số Tài Khoản:
                            </div>
                            <div class="info-value">
                                <span class="role-counter">{{ $role->accounts->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="role-divider">
                    
                    <div class="role-actions">
                        <a href="{{ route('admin.roles.edit', $role->RoleID) }}" class="spa-action-btn spa-btn-edit">
                            <i class="fas fa-edit"></i> Chỉnh Sửa
                        </a>
                        @if($role->accounts->count() == 0)
                            <a href="{{ route('admin.roles.confirmDestroy', $role->RoleID) }}" class="spa-action-btn spa-btn-delete">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        @else
                            <button class="spa-action-btn spa-btn-delete spa-btn-disabled" disabled data-toggle="tooltip" title="Không thể xóa vai trò đang được sử dụng">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Role Statistics Cards -->
            <div class="spa-panel animate__animated animate__fadeIn" style="animation-delay: 0.2s">
                <div class="spa-panel-header">
                    <h6 class="spa-panel-title">
                        <i class="fas fa-chart-pie"></i>
                        Thống Kê & Phân Tích
                    </h6>
                    <div class="stats-control">
                        <div class="stats-period-selector">
                            <button class="period-btn active" data-period="week">Tuần</button>
                            <button class="period-btn" data-period="month">Tháng</button>
                            <button class="period-btn" data-period="quarter">Quý</button>
                        </div>
                    </div>
                </div>
                <div class="spa-panel-body">
                    <!-- Stats Navigation -->
                    <div class="stats-nav">
                        <button class="stats-nav-btn active" data-target="distribution">
                            <i class="fas fa-chart-pie"></i>
                            <span>Phân Bố</span>
                        </button>
                        <button class="stats-nav-btn" data-target="permissions">
                            <i class="fas fa-shield-alt"></i>
                            <span>Quyền Hạn</span>
                        </button>
                        <button class="stats-nav-btn" data-target="performance">
                            <i class="fas fa-chart-line"></i>
                            <span>Hiệu Suất</span>
                        </button>
                        <button class="stats-nav-btn" data-target="topstaff">
                            <i class="fas fa-award"></i>
                            <span>Top Nhân Viên</span>
                        </button>
                    </div>

                    <!-- Stats Content -->
                    <div class="stats-content-wrapper">
                        <!-- Distribution Tab -->
                        <div class="stats-content active" id="distribution-content">
                            <div class="chart-header">
                                <h3 class="chart-title">Phân Bố Vai Trò Theo Bộ Phận</h3>
                                <p class="chart-description">Biểu đồ hiển thị số lượng tài khoản đang sử dụng vai trò "{{ $role->Tenrole }}" trong từng bộ phận</p>
                            </div>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="departmentDistributionChart"></canvas>
                            </div>
                            <div class="chart-legend">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: var(--primary-pink)"></span>
                                    <span class="legend-text">Lễ Tân</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #36b9cc"></span>
                                    <span class="legend-text">Kỹ Thuật Viên</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #4e73df"></span>
                                    <span class="legend-text">Quản Lý</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #f6c23e"></span>
                                    <span class="legend-text">Marketing</span>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Tab -->
                        <div class="stats-content" id="permissions-content">
                            <div class="chart-header">
                                <h3 class="chart-title">So Sánh Quyền Hạn</h3>
                                <p class="chart-description">Biểu đồ radar thể hiện phạm vi quyền hạn của vai trò "{{ $role->Tenrole }}" so với các vai trò khác</p>
                            </div>
                            <div class="chart-container" style="height: 280px;">
                                <canvas id="permissionsRadarChart"></canvas>
                            </div>
                        </div>

                        <!-- Performance Tab -->
                        <div class="stats-content" id="performance-content">
                            <div class="chart-header">
                                <h3 class="chart-title">Hiệu Suất Công Việc</h3>
                                <p class="chart-description">Hiệu suất trung bình của nhân viên có vai trò "{{ $role->Tenrole }}" theo thời gian</p>
                            </div>
                            <div class="chart-container" style="height: 250px;">
                                <canvas id="performanceLineChart"></canvas>
                            </div>
                        </div>

                        <!-- Top Staff Tab -->
                        <div class="stats-content" id="topstaff-content">
                            <div class="chart-header">
                                <h3 class="chart-title">Top Nhân Viên</h3>
                                <p class="chart-description">Top 5 nhân viên xuất sắc nhất có vai trò "{{ $role->Tenrole }}"</p>
                            </div>
                            
                            <div class="top-staff-container">
                                <div class="top-staff-empty" style="{{ $role->accounts->count() > 0 ? 'display: none;' : '' }}">
                                    <div class="empty-indicator">
                                        <i class="fas fa-user-slash"></i>
                                        <p>Chưa có dữ liệu nhân viên để hiển thị</p>
                                    </div>
                                </div>
                                
                                @if($role->accounts->count() > 0)
                                <div class="top-staff-list">
                                    <!-- Dynamically filled with JavaScript -->
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="stats-footer">
                        <button class="spa-btn-secondary export-stats-btn">
                            <i class="fas fa-download"></i> Xuất Báo Cáo
                        </button>
                        <div class="stats-last-updated">
                            Cập nhật lần cuối: {{ date('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 col-lg-7">
            <!-- Accounts with this role -->
            <div class="spa-panel animate__animated animate__fadeIn">
                <div class="spa-panel-header">
                    <h6 class="spa-panel-title">
                        <i class="fas fa-users"></i>
                        Tài Khoản Sử Dụng Vai Trò Này
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="accountDropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="accountDropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                                Xuất Danh Sách
                            </a>
                        </div>
                    </div>
                </div>
                <div class="spa-panel-body">
                    <div class="account-search-wrapper">
                        <div class="search-input-group">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Tìm kiếm tài khoản..." id="accountSearchInput">
                        </div>
                    </div>
                    
                    @if($role->accounts->count() > 0)
                        <div class="spa-table-responsive">
                            <table class="spa-table" id="accountsTable">
                                <thead>
                                    <tr>
                                        <th>Mã TK</th>
                                        <th>Tên Đăng Nhập</th>
                                        <th>Người Dùng</th>
                                        <th class="text-center">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->accounts as $account)
                                        <tr>
                                            <td>{{ $account->MaTK }}</td>
                                            <td>{{ $account->Tendangnhap }}</td>
                                            <td>
                                                @if($account->user)
                                                    <a href="{{ route('admin.customers.show', $account->user->Manguoidung) }}" class="user-link">
                                                        {{ $account->user->Hoten }}
                                                    </a>
                                                @else
                                                    <span class="empty-data">Chưa liên kết</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.accounts.show', $account->MaTK) }}" class="action-btn view-btn" data-toggle="tooltip" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.accounts.edit', $account->MaTK) }}" class="action-btn edit-btn" data-toggle="tooltip" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-accounts-container">
                            <div class="empty-accounts-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="empty-accounts-text">Chưa có tài khoản nào sử dụng vai trò này</p>
                            <a href="{{ route('admin.accounts.create') }}" class="spa-btn-primary">
                                <i class="fas fa-plus"></i> Thêm Tài Khoản Mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/roles.js') }}"></script>
<script src="{{ asset('js/admin/roles/show.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table search functionality
    const searchInput = document.getElementById('accountSearchInput');
    const table = document.getElementById('accountsTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Stats Tab Navigation
    const statsTabs = document.querySelectorAll('.stats-nav-btn');
    const statsContents = document.querySelectorAll('.stats-content');
    
    statsTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            statsTabs.forEach(t => t.classList.remove('active'));
            // Add active class to current tab
            this.classList.add('active');
            
            // Hide all content sections
            statsContents.forEach(content => content.classList.remove('active'));
            
            // Show the selected content
            const targetContent = document.getElementById(this.dataset.target + '-content');
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
    
    // Period selector
    const periodBtns = document.querySelectorAll('.period-btn');
    let currentPeriod = 'week';
    
    periodBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all period buttons
            periodBtns.forEach(b => b.classList.remove('active'));
            // Add active class to current button
            this.classList.add('active');
            
            // Update current period
            currentPeriod = this.dataset.period;
            
            // Update charts based on selected period
            updateChartsForPeriod(currentPeriod);
        });
    });
    
    // Export button functionality
    const exportBtn = document.querySelector('.export-stats-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            alert('Tính năng xuất báo cáo đang được phát triển!');
        });
    }
    
    // Initialize all charts
    initializeCharts();
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add shimmer effect to header elements
    const headerButtons = document.querySelectorAll('.spa-header-action .spa-btn-header');
    headerButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.classList.add('shimmer');
            setTimeout(() => {
                this.classList.remove('shimmer');
            }, 1000);
        });
    });
});

// Chart initialization
function initializeCharts() {
    fetchDepartmentDistribution();
    fetchPermissionsRadar('week');
    fetchPerformanceChart('week');
    fetchTopStaff('week');
}

// Update charts based on selected time period
function updateChartsForPeriod(period) {
    console.log(`Updating charts for period: ${period}`);
    
    // Department Distribution has no time-based component, no need to update
    
    // Update permissions radar with data based on period
    fetchPermissionsRadar(period);
    
    // Update performance line chart with data based on period
    fetchPerformanceChart(period);
    
    // Update top staff section
    fetchTopStaff(period);
}

// Department Distribution Chart
function fetchDepartmentDistribution() {
    const ctx = document.getElementById('departmentDistributionChart');
    if (!ctx) return;
    
    // Show loading state
    const parent = ctx.parentNode;
    if (parent) {
        parent.innerHTML = '<div class="chart-loading"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</div>';
    }
    
    // Get role ID from URL
    const roleId = window.location.pathname.split('/').filter(Boolean).pop();
    
    // Fetch data from API
    fetch(`/admin/roles/${roleId}/stats/department`)
        .then(response => response.json())
        .then(data => {
            // Restore canvas
            if (parent) {
                parent.innerHTML = '<canvas id="departmentDistributionChart"></canvas>';
            }
            
            // Get the new canvas
            const newCtx = document.getElementById('departmentDistributionChart');
            
            const chartData = {
                labels: data.labels,
                datasets: [{
                    label: 'Số lượng tài khoản',
                    data: data.data,
                    backgroundColor: [
                        'rgba(255, 107, 149, 0.7)',
                        'rgba(54, 185, 204, 0.7)',
                        'rgba(78, 115, 223, 0.7)',
                        'rgba(246, 194, 62, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 107, 149, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(78, 115, 223, 1)',
                        'rgba(246, 194, 62, 1)'
                    ],
                    borderWidth: 1
                }]
            };
            
            new Chart(newCtx, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#2c3e50',
                            bodyColor: '#2c3e50',
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1,
                            displayColors: false,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} tài khoản`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching department distribution:', error);
            if (parent) {
                parent.innerHTML = '<div class="chart-error">Không thể tải dữ liệu. Vui lòng thử lại sau.</div>';
            }
        });
}

// Permissions Radar Chart
function fetchPermissionsRadar(period) {
    const ctx = document.getElementById('permissionsRadarChart');
    if (!ctx) return;
    
    // Show loading state
    const parent = ctx.parentNode;
    if (parent) {
        parent.innerHTML = '<div class="chart-loading"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</div>';
    }
    
    // Get role ID from URL
    const roleId = window.location.pathname.split('/').filter(Boolean).pop();
    
    // Fetch data from API
    fetch(`/admin/roles/${roleId}/stats/permissions/${period}`)
        .then(response => response.json())
        .then(data => {
            // Restore canvas
            if (parent) {
                parent.innerHTML = '<canvas id="permissionsRadarChart"></canvas>';
            }
            
            // Get the new canvas
            const newCtx = document.getElementById('permissionsRadarChart');
            
            const datasets = data.datasets.map((dataset, index) => {
                const colors = index === 0 
                    ? ['rgba(255, 107, 149, 1)', 'rgba(255, 107, 149, 0.2)'] 
                    : ['rgba(54, 162, 235, 1)', 'rgba(54, 162, 235, 0.2)'];
                
                return {
                    label: dataset.label,
                    data: dataset.data,
                    fill: true,
                    backgroundColor: colors[1],
                    borderColor: colors[0],
                    pointBackgroundColor: colors[0],
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: colors[0]
                };
            });
            
            const chartData = {
                labels: data.labels,
                datasets: datasets
            };
            
            // Remove existing chart if it exists
            if (window.permissionsChart) {
                window.permissionsChart.destroy();
            }
            
            window.permissionsChart = new Chart(newCtx, {
                type: 'radar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: {
                                stepSize: 20,
                                backdropColor: 'transparent'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#2c3e50',
                            bodyColor: '#2c3e50',
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1,
                            displayColors: true,
                            padding: 10
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching permissions data:', error);
            if (parent) {
                parent.innerHTML = '<div class="chart-error">Không thể tải dữ liệu. Vui lòng thử lại sau.</div>';
            }
        });
}

// Performance Line Chart
function fetchPerformanceChart(period) {
    const ctx = document.getElementById('performanceLineChart');
    if (!ctx) return;
    
    // Show loading state
    const parent = ctx.parentNode;
    if (parent) {
        parent.innerHTML = '<div class="chart-loading"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</div>';
    }
    
    // Get role ID from URL
    const roleId = window.location.pathname.split('/').filter(Boolean).pop();
    
    // Fetch data from API
    fetch(`/admin/roles/${roleId}/stats/performance/${period}`)
        .then(response => response.json())
        .then(data => {
            // Restore canvas
            if (parent) {
                parent.innerHTML = '<canvas id="performanceLineChart"></canvas>';
            }
            
            // Get the new canvas
            const newCtx = document.getElementById('performanceLineChart');
            
            const chartData = {
                labels: data.labels,
                datasets: [{
                    label: 'Hiệu suất công việc',
                    data: data.data,
                    fill: false,
                    borderColor: 'rgba(255, 107, 149, 1)',
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 107, 149, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            };
            
            // Remove existing chart if it exists
            if (window.performanceChart) {
                window.performanceChart.destroy();
            }
            
            window.performanceChart = new Chart(newCtx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 50,
                            max: 100,
                            ticks: {
                                stepSize: 10
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#2c3e50',
                            bodyColor: '#2c3e50',
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1,
                            displayColors: false,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return `Hiệu suất: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching performance data:', error);
            if (parent) {
                parent.innerHTML = '<div class="chart-error">Không thể tải dữ liệu. Vui lòng thử lại sau.</div>';
            }
        });
}

// Populate Top Staff
function fetchTopStaff(period = 'week') {
    const container = document.querySelector('.top-staff-list');
    if (!container) return;
    
    // Show loading state
    container.innerHTML = '<div class="chart-loading"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</div>';
    
    // Get role ID from URL
    const roleId = window.location.pathname.split('/').filter(Boolean).pop();
    
    // Fetch data from API
    fetch(`/admin/roles/${roleId}/stats/topstaff/${period}`)
        .then(response => response.json())
        .then(data => {
            // Clear container
            container.innerHTML = '';
            
            // Hide empty message if we have staff
            const emptyContainer = document.querySelector('.top-staff-empty');
            if (emptyContainer) {
                emptyContainer.style.display = data.staff.length > 0 ? 'none' : 'block';
            }
            
            // If no staff data, show message and return
            if (!data.staff || data.staff.length === 0) {
                container.innerHTML = '<div class="empty-data-message">Không có dữ liệu nhân viên</div>';
                return;
            }
            
            // Add staff items
            data.staff.forEach((staff, index) => {
                const staffItem = document.createElement('div');
                staffItem.className = 'staff-item';
                staffItem.innerHTML = `
                    <div class="staff-rank">${index + 1}</div>
                    <div class="staff-avatar">
                        <img src="${staff.avatar}" alt="${staff.name}">
                    </div>
                    <div class="staff-info">
                        <div class="staff-name">${staff.name}</div>
                        <div class="staff-position">${staff.position}</div>
                    </div>
                    <div class="staff-stats">
                        <div class="staff-value">${staff.value}%</div>
                        <div class="staff-metric">${staff.metric}</div>
                    </div>
                `;
                container.appendChild(staffItem);
            });
        })
        .catch(error => {
            console.error('Error fetching top staff data:', error);
            container.innerHTML = '<div class="chart-error">Không thể tải dữ liệu. Vui lòng thử lại sau.</div>';
        });
}
</script>
@endsection