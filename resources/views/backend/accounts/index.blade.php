@extends('backend.layouts.app')

@section('title', 'Quản lý tài khoản')

@section('styles')
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
    
    /* Account Dashboard Header Styling */
    .account-dashboard-header {
        background: var(--pink-gradient);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(0, 109, 119, 0.25);
        color: white;
    }

    .account-dashboard-header::before {
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
    
    .btn-account {
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

    .btn-account i {
        font-size: 0.9rem;
    }

    .btn-primary-account {
        background: white;
        color: var(--spa-primary-dark);
    }

    .btn-primary-account:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: var(--spa-dark);
    }
    
    .spa-card {
        border: none;
        border-radius: 10px;
        box-shadow: var(--spa-card-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
    }
    
    .spa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 109, 119, 0.2);
    }
    
    .spa-card-header {
        background: var(--spa-light);
        border-bottom: none;
        font-weight: 600;
        color: var(--spa-dark);
        padding: 1.25rem 1.5rem;
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
        box-shadow: 0 4px 10px rgba(0, 109, 119, 0.2);
    }
    
    .btn-spa-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 109, 119, 0.3);
        color: white;
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
    }
    
    .spa-table tbody tr:hover {
        background-color: rgba(131, 197, 190, 0.05);
        transform: scale(1.01);
    }
    
    .spa-table tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
    }
    
    .role-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .role-admin {
        background-color: var(--spa-primary-dark);
        color: white;
    }
    
    .role-customer {
        background-color: var(--spa-primary);
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
    
    .btn-view {
        background-color: #e7f3ff;
        color: #3c8dbc;
    }
    
    .btn-edit {
        background-color: #f1e7ff;
        color: #8950d0;
    }
    
    .btn-delete {
        background-color: #ffe7e7;
        color: #e74c3c;
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
    
    .stat-title {
        color: var(--spa-text);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .stat-card-spa .mt-3 .progress {
        height: 5px;
        border-radius: 3px;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="account-dashboard-header">
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="header-shimmer"></div>
        
        <div class="header-content">
            <h1 class="header-title">Quản Lý Tài Khoản</h1>
            <p class="header-subtitle">
                <i class="fas fa-user-shield mr-1"></i> Quản lý quyền và thông tin tài khoản người dùng
            </p>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('admin.accounts.create') }}" class="btn-account btn-primary-account" target="_self">
                <i class="fas fa-user-plus"></i> Thêm Tài Khoản Mới
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Accounts List -->
    <div class="spa-card mb-4">
        <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                    <i class="fas fa-users mr-2"></i>Danh Sách Tài Khoản
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
            @if ($accounts->isEmpty())
                <div class="text-center my-5">
                    <div class="mb-3">
                        <i class="fas fa-users-slash fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Chưa có tài khoản nào</h5>
                    <p class="text-muted mb-4">Hãy thêm tài khoản mới để bắt đầu</p>
                    <a href="{{ route('admin.accounts.create') }}" class="btn btn-spa btn-spa-primary" target="_self">
                        <i class="fas fa-user-plus mr-2"></i>Thêm Tài Khoản
                    </a>
                </div>
            @else
                <table class="table spa-table">
                    <thead>
                        <tr>
                            <th>Mã tài khoản</th>
                            <th>Tên đăng nhập</th>
                            <th>Vai trò</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->MaTK }}</td>
                                <td>{{ $account->Tendangnhap }}</td>
                                <td>
                                    <span class="role-badge {{ $account->RoleID == 1 ? 'role-admin' : 'role-customer' }}">
                                        {{ $account->role->Tenrole }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.accounts.show', $account->MaTK) }}" class="btn rounded-circle" style="background-color: #e7f3ff; color: #3c8dbc; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accounts.edit', $account->MaTK) }}" class="btn rounded-circle" style="background-color: #f1e7ff; color: #8950d0; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.accounts.confirm-destroy', $account->MaTK) }}" class="btn rounded-circle" style="background-color: #ffe7e7; color: #e74c3c; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" data-toggle="tooltip" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ff6b95, #e84a78);">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-title">Tổng tài khoản</h5>
                    <p class="stat-value">{{ $accounts->count() }}</p>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 100%; background-color: #ff6b95;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ff8eb4, #ff6b95);">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-title">Tài khoản mới</h5>
                    <p class="stat-value">0</p>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 25%; background-color: #ff8eb4;" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ff6b95, #e84a78);">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-title">Tài khoản đã kích hoạt</h5>
                    <p class="stat-value">0</p>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 75%; background-color: #ff6b95;" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ffb0c8, #ff8eb4);">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-title">Tài khoản đã khóa</h5>
                    <p class="stat-value">0</p>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 10%; background-color: #ffb0c8;" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Extra JavaScript for interactivity could be added here
    });
</script>
@endsection