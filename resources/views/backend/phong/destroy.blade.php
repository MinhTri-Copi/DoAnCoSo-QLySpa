@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Phòng')

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
        --light-danger: #fee2e2;
        --dark-danger: #c0392b;
        --light-gray: #f7f9fc;
        --white: #ffffff;
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 5px 25px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.09);
        --shadow-danger: 0 8px 25px rgba(231, 76, 60, 0.14);
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
    }

    /* ===== HEADER ===== */
    .spa-dashboard-header {
        background: linear-gradient(135deg, var(--danger) 0%, #ff6347 100%);
        border-radius: var(--radius-lg);
        padding: 1.8rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-danger);
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
        max-width: 80%;
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
        font-size: 1.1rem;
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
    }

    .spa-content-title i {
        color: var(--danger);
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

    .spa-info-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .spa-info-title i {
        color: var(--danger);
    }

    .spa-info-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
        align-items: flex-start;
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

    .spa-btn-danger {
        background: var(--danger);
        color: white;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
    }

    .spa-btn-danger:hover, .spa-btn-danger:focus {
        background: var(--dark-danger);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
    }

    .spa-btn-secondary {
        background: var(--light-gray);
        color: var(--text-primary);
    }

    .spa-btn-secondary:hover, .spa-btn-secondary:focus {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    /* ===== WARNING BOX ===== */
    .spa-warning-box {
        background-color: var(--light-danger);
        color: var(--danger);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 5px solid var(--danger);
    }

    .spa-warning-box-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .spa-warning-box-title i {
        font-size: 1.4rem;
    }

    .spa-warning-box-text {
        color: #9a3b37;
        margin-bottom: 0;
        line-height: 1.6;
    }
    
    .spa-warning-box-text strong {
        font-weight: 700;
    }
    
    /* Room info specific */
    .room-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        background-color: var(--light-gray);
        color: var(--text-secondary);
        margin-right: 0.5rem;
    }
    
    .room-badge i {
        margin-right: 0.5rem;
    }
    
    .room-badge-primary {
        background-color: var(--light-pink);
        color: var(--primary-pink);
    }
    
    .room-badge-success {
        background-color: #e6f6f0;
        color: #10b981;
    }
    
    .room-delete-info {
        margin-bottom: 2rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="spa-dashboard-header">
        <div class="header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        
        <div class="spa-header-content">
            <h1 class="spa-header-title">Xác Nhận Xóa Phòng</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-exclamation-triangle"></i> Bạn đang chuẩn bị xóa phòng {{ $phong->Tenphong }}
            </p>
        </div>
        
        <div class="spa-header-actions">
            <a href="{{ route('admin.phong.index') }}" class="spa-btn spa-btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-trash-alt"></i> Xác nhận xóa
            </h2>
        </div>
        
        <div class="spa-content-body">
            <div class="spa-warning-box">
                <h3 class="spa-warning-box-title">
                    <i class="fas fa-exclamation-triangle"></i> Cảnh báo
                </h3>
                <p class="spa-warning-box-text">
                    Bạn đang chuẩn bị xóa phòng <strong>{{ $phong->Tenphong }}</strong> khỏi hệ thống. Hành động này không thể hoàn tác và tất cả dữ liệu liên quan đến phòng này cũng sẽ bị xóa. Vui lòng xác nhận lại thông tin trước khi thực hiện.
                </p>
            </div>
            
            <div class="room-delete-info">
                <div class="spa-info-card">
                    <h4 class="spa-info-title">
                        <i class="fas fa-info-circle"></i> Thông tin phòng sẽ bị xóa
                    </h4>
                    
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
                            <span class="room-badge {{ $phong->Loaiphong == 'Cao cấp' ? 'room-badge-primary' : '' }}">
                                <i class="fas {{ $phong->Loaiphong == 'Cao cấp' ? 'fa-gem' : 'fa-tag' }}"></i>
                                {{ $phong->Loaiphong }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="spa-info-item">
                        <div class="spa-info-label">Trạng thái</div>
                        <div class="spa-info-value">{{ $phong->trangThaiPhong->Tentrangthai ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.phong.destroy', $phong->Maphong) }}" method="POST" class="text-center">
                @csrf
                @method('DELETE')
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.phong.index') }}" class="spa-btn spa-btn-secondary me-3">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="spa-btn spa-btn-danger">
                        <i class="fas fa-trash-alt"></i> Xác nhận xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection