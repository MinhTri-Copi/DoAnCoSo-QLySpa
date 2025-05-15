@extends('backend.layouts.app')

@section('title', 'Chi Tiết Phòng')

@section('styles')
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
        --shadow-pink: 0 8px 25px rgba(255, 107, 149, 0.25);
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
        --pink-gradient: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
    }

    /* Dashboard Header Styling */
    .room-dashboard-header {
        background: var(--pink-gradient);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(255, 107, 149, 0.25);
        color: white;
    }

    .room-dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.85;
        display: flex;
        align-items: center;
    }

    .header-subtitle i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    /* Button Styles */
    .btn-room {
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-room i {
        font-size: 0.9rem;
    }

    .btn-primary-room {
        background: white;
        color: var(--primary-pink);
    }

    .btn-primary-room:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: var(--dark-pink);
    }

    .btn-secondary-room {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(5px);
    }

    .btn-secondary-room:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: white;
    }

    /* ===== CONTENT WRAPPER ===== */
    .spa-content-wrapper {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 3rem;
        position: relative;
    }

    .spa-content-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .spa-content-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .spa-content-title i {
        color: var(--primary-pink);
    }

    .spa-content-body {
        padding: 2rem;
    }

    /* ===== INFO CARD ===== */
    .spa-info-card {
        padding: 1.5rem;
        background-color: var(--light-gray);
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
    }

    .spa-info-card-header {
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .spa-info-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .spa-info-card-title i {
        color: var(--primary-pink);
    }

    .spa-info-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
        align-items: center;
    }

    .spa-info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .spa-info-label {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary);
        flex: 0 0 150px;
    }

    .spa-info-value {
        font-size: 0.95rem;
        color: var(--text-secondary);
        flex-grow: 1;
    }

    /* ===== BUTTONS ===== */
    .spa-btn {
        font-weight: 600;
        padding: 0.8rem 1.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition-fast);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        border: none;
        font-size: 0.95rem;
    }

    .spa-btn i {
        font-size: 1rem;
    }

    .spa-btn-primary {
        background: var(--primary-pink);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.2);
    }

    .spa-btn-primary:hover, .spa-btn-primary:focus {
        background: var(--dark-pink);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 149, 0.3);
    }

    .spa-btn-secondary {
        background: var(--light-gray);
        color: var(--text-primary);
    }

    .spa-btn-secondary:hover, .spa-btn-secondary:focus {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    
    .spa-btn-danger {
        background: var(--danger);
        color: white;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
    }
    
    .spa-btn-danger:hover, .spa-btn-danger:focus {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
    }
    
    /* Status badges */
    .spa-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .spa-badge-primary {
        background-color: var(--light-pink);
        color: var(--primary-pink);
    }
    
    .spa-badge-success {
        background-color: #e6f6f0;
        color: #10b981;
    }
    
    .spa-badge-warning {
        background-color: #fef5e7;
        color: #f59e0b;
    }
    
    .spa-badge-info {
        background-color: #e6f3fb;
        color: var(--info);
    }
    
    /* Room type icon */
    .room-icon {
        font-size: 3rem;
        color: var(--primary-pink);
        margin-bottom: 1rem;
    }
    
    .room-type-normal {
        color: #8e44ad;
    }
    
    .room-type-premium {
        color: #f1c40f;
    }

    /* Responsive Media Queries */
    @media screen and (max-width: 992px) {
        .room-dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Modern Dashboard Header -->
    <div class="room-dashboard-header">
        <div class="header-content">
            <h1 class="header-title">Chi Tiết Phòng</h1>
            <p class="header-subtitle">
                <i class="fas fa-door-open"></i> 
                Xem thông tin chi tiết phòng {{ $phong->Tenphong }}
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.phong.index') }}" class="btn-room btn-secondary-room">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
            <a href="{{ route('admin.phong.edit', $phong->Maphong) }}" class="btn-room btn-primary-room">
                <i class="fas fa-edit"></i>
                Chỉnh sửa
            </a>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-info-circle"></i> Thông tin tổng quan
            </h2>
        </div>
        
        <div class="spa-content-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="p-4">
                        <i class="fas fa-door-open room-icon {{ $phong->Loaiphong == 'Cao cấp' ? 'room-type-premium' : 'room-type-normal' }}"></i>
                        <h4 class="font-weight-bold mb-2">{{ $phong->Tenphong }}</h4>
                        <div class="spa-badge {{ $phong->Loaiphong == 'Cao cấp' ? 'spa-badge-primary' : 'spa-badge-info' }}">
                            <i class="fas fa-tag mr-1"></i> {{ $phong->Loaiphong }}
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="spa-info-card">
                        <div class="spa-info-card-header">
                            <h4 class="spa-info-card-title">
                                <i class="fas fa-clipboard-list"></i> Chi tiết phòng
                            </h4>
                        </div>
                        
                        <div class="spa-info-item">
                            <div class="spa-info-label">Mã phòng</div>
                            <div class="spa-info-value">{{ $phong->Maphong }}</div>
                        </div>
                        
                        <div class="spa-info-item">
                            <div class="spa-info-label">Tên phòng</div>
                            <div class="spa-info-value">{{ $phong->Tenphong }}</div>
                        </div>
                        
                        <div class="spa-info-item">
                            <div class="spa-info-label">Loại phòng</div>
                            <div class="spa-info-value">
                                @if($phong->Loaiphong == 'Cao cấp')
                                    <span class="spa-badge spa-badge-primary">
                                        <i class="fas fa-gem mr-1"></i> {{ $phong->Loaiphong }}
                                    </span>
                                @else
                                    <span class="spa-badge spa-badge-info">
                                        <i class="fas fa-tag mr-1"></i> {{ $phong->Loaiphong }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="spa-info-item">
                            <div class="spa-info-label">Trạng thái</div>
                            <div class="spa-info-value">
                                @php
                                    $statusClass = 'spa-badge-info';
                                    $statusIcon = 'info-circle';
                                    
                                    if (isset($phong->trangThaiPhong)) {
                                        $status = strtolower($phong->trangThaiPhong->Tentrangthai);
                                        
                                        if (strpos($status, 'đang đợi') !== false || strpos($status, 'trống') !== false) {
                                            $statusClass = 'spa-badge-success';
                                            $statusIcon = 'check-circle';
                                        } elseif (strpos($status, 'bảo trì') !== false) {
                                            $statusClass = 'spa-badge-warning';
                                            $statusIcon = 'tools';
                                        } elseif (strpos($status, 'hoạt động') !== false || strpos($status, 'đang sử dụng') !== false) {
                                            $statusClass = 'spa-badge-primary';
                                            $statusIcon = 'user-clock';
                                        }
                                    }
                                @endphp
                                <span class="spa-badge {{ $statusClass }}">
                                    <i class="fas fa-{{ $statusIcon }} mr-1"></i> {{ $phong->trangThaiPhong->Tentrangthai ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.phong.index') }}" class="spa-btn spa-btn-secondary me-2">
                    <i class="fas fa-list"></i> Danh sách phòng
                </a>
                <a href="{{ route('admin.phong.edit', $phong->Maphong) }}" class="spa-btn spa-btn-primary me-2">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.phong.confirm-destroy', $phong->Maphong) }}" class="spa-btn spa-btn-danger">
                    <i class="fas fa-trash-alt"></i> Xóa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection