@extends('backend.layouts.app')

@section('title', 'Xóa Trạng Thái Quảng Cáo')

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
        max-width: 70%;
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
        margin: 0;
    }

    .spa-content-title i {
        color: var(--primary-pink);
    }

    .spa-content-body {
        padding: 2rem;
    }

    /* ===== WARNING CARD ===== */
    .spa-warning-card {
        background-color: #fff5f5;
        border-radius: var(--radius-md);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        border-left: 5px solid var(--danger);
    }

    .spa-warning-icon {
        position: absolute;
        top: -15px;
        left: -15px;
        width: 45px;
        height: 45px;
        background-color: var(--danger);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
    }

    .spa-warning-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 1rem;
    }

    .spa-warning-desc {
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .spa-warning-highlight {
        font-weight: 700;
        color: var(--danger);
    }

    /* ===== INFO CARD ===== */
    .spa-info-card {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .spa-info-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .spa-info-title i {
        color: var(--info);
    }

    .spa-info-item {
        display: flex;
        margin-bottom: 0.5rem;
        padding: 0.5rem 0;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.05);
    }

    .spa-info-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .spa-info-label {
        width: 180px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .spa-info-value {
        flex: 1;
        color: var(--text-secondary);
    }

    /* ===== BUTTONS ===== */
    .spa-btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .spa-btn {
        font-weight: 600;
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: var(--transition-fast);
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        box-shadow: var(--shadow-sm);
    }

    .spa-btn i {
        font-size: 0.9rem;
    }

    .spa-btn-danger {
        background: var(--danger);
        color: white;
    }

    .spa-btn-danger:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        color: white;
        text-decoration: none;
    }

    .spa-btn-secondary {
        background: #f1f3f5;
        color: var(--text-primary);
    }

    .spa-btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        color: var(--text-primary);
        text-decoration: none;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 767px) {
        .spa-dashboard-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .spa-header-content {
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .spa-content-body {
            padding: 1.5rem;
        }

        .spa-btn-group {
            flex-direction: column;
            width: 100%;
        }

        .spa-btn {
            width: 100%;
        }

        .spa-info-item {
            flex-direction: column;
        }

        .spa-info-label {
            width: 100%;
            margin-bottom: 0.3rem;
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
            <h1 class="spa-header-title">Xóa Trạng Thái Quảng Cáo</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-exclamation-triangle"></i>
                Xác nhận xóa trạng thái quảng cáo
            </p>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-trash-alt"></i>
                Xác Nhận Xóa
            </h2>
        </div>

        <div class="spa-content-body">
            <div class="spa-warning-card">
                <div class="spa-warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="spa-warning-title">Cảnh Báo</h3>
                <p class="spa-warning-desc">
                    Bạn có chắc chắn muốn xóa trạng thái <span class="spa-warning-highlight">{{ $status->TenTT }}</span> (Mã: {{ $status->MaTTQC }}) không? Hành động này không thể hoàn tác.
                </p>
            </div>

            <div class="spa-info-card">
                <h3 class="spa-info-title">
                    <i class="fas fa-info-circle"></i>
                    Thông Tin Trạng Thái
                </h3>
                <div class="spa-info-item">
                    <span class="spa-info-label">Mã Trạng Thái:</span>
                    <span class="spa-info-value">{{ $status->MaTTQC }}</span>
                </div>
                <div class="spa-info-item">
                    <span class="spa-info-label">Tên Trạng Thái:</span>
                    <span class="spa-info-value">{{ $status->TenTT }}</span>
                </div>
            </div>

            <form action="{{ route('admin.ad-statuses.destroy', $status->MaTTQC) }}" method="POST">
            @csrf
            @method('DELETE')
                <div class="spa-btn-group">
                    <button type="submit" class="spa-btn spa-btn-danger">
                        <i class="fas fa-trash-alt"></i>
                        Xác Nhận Xóa
                    </button>
                    <a href="{{ route('admin.ad-statuses.index') }}" class="spa-btn spa-btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Hủy Và Quay Lại
                    </a>
                </div>
        </form>
        </div>
    </div>
    </div>
@endsection