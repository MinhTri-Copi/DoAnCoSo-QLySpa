@extends('backend.layouts.app')

@section('title', 'Chi Tiết Trạng Thái Quảng Cáo')

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
        gap: 10px;
        align-items: center;
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
    }

    .spa-content-title i {
        color: var(--primary-pink);
    }

    .spa-content-body {
        padding: 2rem;
    }

    /* ===== INFO CARDS ===== */
    .spa-info-card {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-fast);
    }

    .spa-info-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .spa-info-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .spa-info-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .spa-info-card-title i {
        color: var(--primary-pink);
        font-size: 1.2rem;
    }

    .spa-info-card-badge {
        background: var(--light-pink);
        color: var(--primary-pink);
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .spa-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .spa-info-item {
        display: flex;
        padding: 0.8rem 0;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.05);
    }

    .spa-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
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
        background: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
    }

    .spa-btn-danger:hover {
        background: rgba(231, 76, 60, 0.25);
        color: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2);
        text-decoration: none;
    }

    /* ===== TABLES ===== */
    .spa-ads-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-top: -10px;
    }

    .spa-ads-table thead th {
        background: transparent;
        padding: 0.8rem 1.2rem;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }

    .spa-ads-table tbody tr {
        background: var(--light-gray);
        box-shadow: var(--shadow-sm);
        transition: var(--transition-fast);
    }

    .spa-ads-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: var(--light-pink);
    }

    .spa-ads-table tbody td {
        padding: 1rem 1.2rem;
        border: none;
        vertical-align: middle;
    }

    .spa-ads-table tbody td:first-child {
        border-top-left-radius: var(--radius-md);
        border-bottom-left-radius: var(--radius-md);
    }

    .spa-ads-table tbody td:last-child {
        border-top-right-radius: var(--radius-md);
        border-bottom-right-radius: var(--radius-md);
    }

    .spa-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: var(--transition-fast);
        border: none;
        outline: none;
        cursor: pointer;
        margin-right: 5px;
    }

    .spa-action-btn.view {
        background: rgba(52, 152, 219, 0.15);
        color: #3498db;
    }

    .spa-action-btn.edit {
        background: rgba(142, 68, 173, 0.15);
        color: #8e44ad;
    }

    .spa-action-btn:hover {
        transform: translateY(-2px);
    }

    .spa-action-btn.view:hover {
        background: rgba(52, 152, 219, 0.25);
    }

    .spa-action-btn.edit:hover {
        background: rgba(142, 68, 173, 0.25);
    }

    /* ===== STATS SECTION ===== */
    .spa-stats-card {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        transition: var(--transition-fast);
        height: 100%;
    }

    .spa-stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .spa-stats-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .spa-stats-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: var(--light-pink);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .spa-stats-icon i {
        color: var(--primary-pink);
        font-size: 1.2rem;
    }

    .spa-stats-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .spa-stats-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-pink);
        margin: 0.5rem 0;
    }

    .spa-stats-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* ===== EMPTY STATE ===== */
    .spa-empty-ads {
        padding: 2rem;
        text-align: center;
        background: var(--light-gray);
        border-radius: var(--radius-md);
    }

    .spa-empty-ads i {
        font-size: 3rem;
        color: #dbe1e8;
        margin-bottom: 1rem;
    }

    .spa-empty-ads h4 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .spa-empty-ads p {
        color: var(--text-secondary);
        margin-bottom: 0;
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

        .spa-header-actions {
            width: 100%;
            justify-content: space-between;
        }

        .spa-btn {
            padding: 0.7rem 1.2rem;
            font-size: 0.9rem;
        }

        .spa-content-body {
            padding: 1.5rem 1rem;
        }

        .spa-info-item {
            flex-direction: column;
        }

        .spa-info-label {
            width: 100%;
            margin-bottom: 0.3rem;
        }

        .spa-info-card-header {
            flex-direction: column;
        }

        .spa-info-card-badge {
            align-self: flex-start;
            margin-top: 0.5rem;
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
            <h1 class="spa-header-title">Chi Tiết Trạng Thái</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-bullhorn"></i>
                Thông tin về trạng thái quảng cáo: {{ $status->TenTT }}
            </p>
        </div>
        <div class="spa-header-actions">
            <a href="{{ route('admin.ad-statuses.edit', $status->MaTTQC) }}" class="spa-btn spa-btn-primary">
                <i class="fas fa-edit"></i>
                Chỉnh Sửa
            </a>

            @if($status->quangCao()->count() == 0)
                <a href="{{ route('admin.ad-statuses.confirm-destroy', $status->MaTTQC) }}" class="spa-btn spa-btn-danger">
                    <i class="fas fa-trash"></i>
                    Xóa
                </a>
            @endif
        </div>
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
                            <span class="spa-info-card-badge">ID: {{ $status->MaTTQC }}</span>
                        </div>
                        <ul class="spa-info-list">
                            <li class="spa-info-item">
                                <span class="spa-info-label">Mã Trạng Thái:</span>
                                <span class="spa-info-value">{{ $status->MaTTQC }}</span>
                            </li>
                            <li class="spa-info-item">
                                <span class="spa-info-label">Tên Trạng Thái:</span>
                                <span class="spa-info-value">{{ $status->TenTT }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.ad-statuses.index') }}" class="spa-btn spa-btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Quay Lại
                        </a>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection