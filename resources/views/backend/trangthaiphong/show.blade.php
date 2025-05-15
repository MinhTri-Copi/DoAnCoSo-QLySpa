@extends('backend.layouts.app')

@section('title', 'Chi Tiết Trạng Thái Phòng')

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

    .spa-header-actions {
        position: relative;
        z-index: 4;
        display: flex;
        gap: 0.8rem;
    }

    /* ===== CONTENT WRAPPER ===== */
    .spa-content-wrapper {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
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
        padding: 1.5rem 2rem;
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

    .spa-info-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 50px;
    }

    .spa-info-badge.badge-primary {
        background-color: var(--light-pink);
        color: var(--primary-pink);
    }

    /* ===== BUTTONS ===== */
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
        text-decoration: none;
    }

    .spa-btn i {
        font-size: 0.9rem;
    }

    .spa-btn-primary {
        background: var(--primary-pink);
        color: white;
    }

    .spa-btn-primary:hover {
        background: var(--dark-pink);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 149, 0.3);
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

    .spa-btn-danger {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .spa-btn-danger:hover {
        background: rgba(231, 76, 60, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2);
        color: #c0392b;
        text-decoration: none;
    }

    .spa-btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    /* ===== ACTION BUTTONS ===== */
    .spa-action-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: var(--transition-fast);
        border: none;
        outline: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background-color: white;
        color: var(--text-primary);
    }

    .spa-action-btn.edit {
        background: #8e44ad;
        color: white;
    }

    .spa-action-btn.delete {
        background: #e74c3c;
        color: white;
    }

    .spa-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .spa-action-btn.edit:hover {
        background: #9b59b6;
    }

    .spa-action-btn.delete:hover {
        background: #c0392b;
    }

    /* ===== BACK BUTTON ===== */
    .spa-back-section {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .spa-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-secondary);
        background-color: var(--light-gray);
        border-radius: 50px;
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-back-btn:hover {
        background-color: #e9ecef;
        color: var(--text-primary);
        transform: translateX(-2px);
        text-decoration: none;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .spa-dashboard-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .spa-header-content {
            max-width: 100%;
        }

        .spa-header-actions {
            align-self: flex-end;
        }

        .spa-info-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .spa-info-label {
            flex: none;
            margin-bottom: 0.5rem;
        }
    }

    @media (max-width: 767px) {
        .spa-content-body {
            padding: 1.5rem;
        }

        .spa-info-card {
            padding: 1.2rem;
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
            <h1 class="spa-header-title">{{ $trangThaiPhong->Tentrangthai }}</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-tag"></i>
                Mã trạng thái: {{ $trangThaiPhong->MatrangthaiP }}
            </p>
        </div>
        <div class="spa-header-actions">
            <a href="{{ route('admin.trangthaiphong.edit', $trangThaiPhong->MatrangthaiP) }}" class="spa-action-btn edit" title="Chỉnh sửa">
                <i class="fas fa-edit"></i>
            </a>
            <a href="{{ route('admin.trangthaiphong.confirm-destroy', $trangThaiPhong->MatrangthaiP) }}" class="spa-action-btn delete" title="Xóa">
                <i class="fas fa-trash"></i>
            </a>
        </div>
    </div>

    <!-- Back Button -->
    <div class="spa-back-section">
        <a href="{{ route('admin.trangthaiphong.index') }}" class="spa-back-btn">
            <i class="fas fa-arrow-left"></i>
            Quay lại danh sách
        </a>
    </div>

    <!-- Thông tin cơ bản -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="spa-content-wrapper">
                <div class="spa-content-header">
                    <h2 class="spa-content-title">
                        <i class="fas fa-info-circle"></i>
                        Thông Tin Cơ Bản
                    </h2>
                </div>
                <div class="spa-content-body">
                    <div class="spa-info-card">
                        <div class="spa-info-card-header">
                            <h3 class="spa-info-card-title">
                                <i class="fas fa-tag"></i>
                                Chi Tiết Trạng Thái
                            </h3>
                        </div>
                        <div class="spa-info-item">
                            <span class="spa-info-label">Mã Trạng Thái:</span>
                            <span class="spa-info-value">
                                <span class="spa-info-badge badge-primary">{{ $trangThaiPhong->MatrangthaiP }}</span>
                            </span>
                        </div>
                        <div class="spa-info-item">
                            <span class="spa-info-label">Tên Trạng Thái:</span>
                            <span class="spa-info-value">{{ $trangThaiPhong->Tentrangthai }}</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.trangthaiphong.edit', $trangThaiPhong->MatrangthaiP) }}" class="spa-btn spa-btn-primary">
                            <i class="fas fa-edit"></i>
                            Chỉnh Sửa
                        </a>
                        <a href="{{ route('admin.trangthaiphong.confirm-destroy', $trangThaiPhong->MatrangthaiP) }}" class="spa-btn spa-btn-danger">
                            <i class="fas fa-trash"></i>
                            Xóa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="spa-content-wrapper">
                <div class="spa-content-header">
                    <h2 class="spa-content-title">
                        <i class="fas fa-info-circle"></i>
                        Thông Tin Bổ Sung
                    </h2>
                </div>
                <div class="spa-content-body">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <strong>Trạng thái phòng</strong> là thông tin về tình trạng hiện tại của phòng trong hệ thống. Các trạng thái phòng phổ biến bao gồm: Còn trống, Đang sử dụng, Đang bảo trì, Đang dọn dẹp.
                    </div>
                    <p class="mt-4">
                        Để quản lý phòng hiệu quả, bạn nên:
                    </p>
                    <ul>
                        <li>Đảm bảo cập nhật trạng thái phòng thường xuyên</li>
                        <li>Kiểm tra trạng thái phòng trước khi đặt lịch cho khách hàng</li>
                        <li>Theo dõi số lượng phòng theo từng trạng thái</li>
                        <li>Tối ưu hóa việc sử dụng phòng dựa trên thống kê trạng thái</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê sử dụng trạng thái -->
    <div class="spa-content-wrapper mb-4">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-chart-pie"></i>
                Thống Kê Phòng Theo Trạng Thái
            </h2>
        </div>
        <div class="spa-content-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Phần thống kê phòng theo trạng thái sẽ được hiển thị khi có dữ liệu phòng liên quan.
            </div>
        </div>
    </div>
</div>
@endsection