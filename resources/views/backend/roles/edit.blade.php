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

    .spa-btn-view i {
        background: rgba(52, 152, 219, 0.15);
    }

    .spa-btn-view {
        color: var(--info);
    }

    .spa-btn-view:hover {
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

    /* Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-group .required-indicator {
        color: var(--primary-pink);
        font-weight: bold;
        margin-left: 3px;
    }

    .form-control {
        padding: 0.8rem 1.2rem;
        border-radius: var(--radius-md);
        border: 1px solid rgba(0, 0, 0, 0.1);
        background-color: var(--light-gray);
        color: var(--text-primary);
        transition: var(--transition-fast);
    }

    .form-control:focus {
        border-color: var(--primary-pink);
        background-color: var(--white);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 149, 0.2);
    }

    .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.03);
        cursor: not-allowed;
    }

    .form-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-top: 0.4rem;
    }

    .is-invalid {
        border-color: var(--danger) !important;
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 0.4rem;
    }

    /* Form Action Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        margin-top: 2rem;
    }

    .spa-btn {
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition-fast);
        cursor: pointer;
    }

    .spa-btn-primary {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.25);
    }

    .spa-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 149, 0.3);
    }

    .spa-btn-secondary {
        background: var(--light-gray);
        color: var(--text-secondary);
    }

    .spa-btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .spa-btn-danger {
        background: var(--danger);
        color: white;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.25);
    }

    .spa-btn-danger:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
    }

    /* Stats Section */
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
        text-align: center;
    }

    .role-id {
        display: inline-block;
        background: var(--light-pink);
        color: var(--primary-pink);
        font-weight: 700;
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
        font-size: 0.85rem;
        margin: 0 auto;
        text-align: center;
    }

    .role-stats {
        margin-top: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .stat-item {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 1.2rem;
        width: 100%;
        text-align: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        margin-bottom: 0.5rem;
    }

    .stat-icon i {
        font-size: 1.8rem;
        color: var(--primary-pink);
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0.3rem;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .spa-alert {
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .spa-alert-warning {
        background-color: rgba(246, 194, 62, 0.15);
        border-left: 4px solid #f6c23e;
        color: #856404;
    }

    .spa-alert-info {
        background-color: rgba(54, 185, 204, 0.15);
        border-left: 4px solid #36b9cc;
        color: #0c5460;
    }

    .spa-alert i {
        font-size: 1.2rem;
    }

    .delete-action {
        text-align: center;
        margin-top: 1.5rem;
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
            <h1 class="spa-header-title">Chỉnh Sửa Vai Trò</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-edit"></i>
                Cập nhật thông tin vai trò và phân quyền hệ thống
            </p>
        </div>
        <div class="spa-header-action">
            <a href="{{ route('admin.roles.show', $role->RoleID) }}" class="spa-btn-header spa-btn-view">
                <i class="fas fa-eye"></i>
                Xem Chi Tiết
            </a>
            <a href="{{ route('admin.roles.index') }}" class="spa-btn-header">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Role Edit Form -->
        <div class="col-lg-8">
            <div class="spa-panel animate__animated animate__fadeIn">
                <div class="spa-panel-header">
                    <h6 class="spa-panel-title">
                        <i class="fas fa-user-tag"></i>
                        Thông Tin Vai Trò
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.roles.show', $role->RoleID) }}">
                                <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i>
                                Xem Chi Tiết
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
                <div class="spa-panel-body">
                    <form action="{{ route('admin.roles.update', $role->RoleID) }}" method="POST" id="editRoleForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="RoleID">Mã Vai Trò</label>
                                    <input type="text" class="form-control" id="RoleID" value="{{ $role->RoleID }}" readonly>
                                    <small class="form-text text-muted">Mã vai trò không thể thay đổi</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Tenrole">Tên Vai Trò <span class="required-indicator">*</span></label>
                                    <input type="text" class="form-control @error('Tenrole') is-invalid @enderror" 
                                        id="Tenrole" name="Tenrole" value="{{ old('Tenrole', $role->Tenrole) }}">
                                    @error('Tenrole')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="TenroleFeedback" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">Tên vai trò không được vượt quá 50 ký tự</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="spa-btn spa-btn-secondary" onclick="window.history.back();">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                            <button type="submit" class="spa-btn spa-btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Lưu Thay Đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Role Info Sidebar -->
        <div class="col-lg-4">
            <!-- Role Stats -->
            <div class="spa-panel animate__animated animate__fadeInRight" style="animation-delay: 0.2s">
                <div class="spa-panel-header">
                    <h6 class="spa-panel-title">
                        <i class="fas fa-chart-pie"></i>
                        Thống Kê
                    </h6>
                </div>
                <div class="spa-panel-body">
                    <div class="text-center mb-3">
                        <div class="role-icon-circle">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <h4 class="role-name">{{ $role->Tenrole }}</h4>
                        <div class="role-id">Mã: {{ $role->RoleID }}</div>
                    </div>
                    
                    <div class="role-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-label">Tài Khoản Sử Dụng</div>
                            <div class="stat-value">{{ $role->accounts->count() }}</div>
                        </div>
                    </div>
                    
                    @if($role->accounts->count() > 0)
                        <div class="spa-alert spa-alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>Vai trò này đang được sử dụng bởi {{ $role->accounts->count() }} tài khoản. Không thể xóa.</div>
                        </div>
                    @else
                        <div class="spa-alert spa-alert-info">
                            <i class="fas fa-info-circle"></i>
                            <div>Vai trò này chưa được sử dụng bởi tài khoản nào.</div>
                        </div>
                        
                        <div class="delete-action">
                            <a href="{{ route('admin.roles.confirmDestroy', $role->RoleID) }}" class="spa-btn spa-btn-danger">
                                <i class="fas fa-trash"></i> Xóa Vai Trò
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
<script src="{{ asset('js/admin/roles/edit.js') }}"></script>
@endsection