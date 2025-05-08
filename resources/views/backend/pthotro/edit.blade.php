@extends('backend.layouts.app')

@section('title', 'Chỉnh Sửa Phương Thức Hỗ Trợ')

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
        color: var(--primary-pink);
    }

    .spa-content-body {
        padding: 2rem;
    }

    /* ===== FORM STYLES ===== */
    .spa-form .form-group {
        margin-bottom: 1.8rem;
    }

    .spa-form label {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .spa-form label .required {
        color: var(--danger);
        margin-left: 3px;
    }

    .spa-form .form-control {
        padding: 0.8rem 1.2rem;
        border-radius: var(--radius-sm);
        border: 1px solid rgba(0, 0, 0, 0.1);
        font-size: 1rem;
        transition: var(--transition-fast);
        box-shadow: none;
        background-color: var(--light-gray);
    }

    .spa-form .form-control:focus {
        border-color: var(--primary-pink);
        background-color: var(--white);
        box-shadow: 0 3px 15px rgba(255, 107, 149, 0.1);
    }

    .spa-form .form-control:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
        opacity: 0.7;
        border-color: #ddd;
    }

    .spa-form .help-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
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

    .spa-btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* ===== ALERTS ===== */
    .spa-alert {
        border-radius: var(--radius-md);
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
        position: relative;
        display: flex;
        align-items: flex-start;
    }

    .spa-alert i {
        margin-right: 1rem;
        font-size: 1.1rem;
        margin-top: 0.2rem;
    }

    .spa-alert-danger {
        background-color: #feeceb;
        color: #d63031;
    }

    .spa-alert-success {
        background-color: #e8f9f0;
        color: #27ae60;
    }

    .spa-alert ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }

    .spa-alert li {
        margin-bottom: 0.3rem;
    }

    .spa-alert li:last-child {
        margin-bottom: 0;
    }

    /* ===== INFO BLOCK ===== */
    .spa-info-block {
        background-color: #f8f9fa;
        border-left: 4px solid var(--primary-pink);
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    }

    .spa-info-block-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .spa-info-block-title i {
        color: var(--primary-pink);
    }

    .spa-info-block-text {
        color: var(--text-secondary);
        margin-bottom: 0;
        font-size: 0.95rem;
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
            <h1 class="spa-header-title">Chỉnh Sửa Phương Thức Hỗ Trợ</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-edit"></i>
                Cập nhật thông tin phương thức hỗ trợ: {{ $pthotro->TenPT }}
            </p>
        </div>
        </div>

    <!-- Main Content Wrapper -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-edit"></i>
                Thông Tin Phương Thức
            </h2>
        </div>

        <div class="spa-content-body">
            <div class="spa-info-block">
                <h3 class="spa-info-block-title">
                    <i class="fas fa-info-circle"></i>
                    Thông Tin Chỉnh Sửa
                </h3>
                <p class="spa-info-block-text">
                    Bạn đang chỉnh sửa phương thức hỗ trợ. Hãy cập nhật thông tin phương thức với mã và tên phương thức hỗ trợ.
                </p>
            </div>

            <!-- Thông báo lỗi -->
            @if ($errors->any())
                <div class="spa-alert spa-alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form chỉnh sửa phương thức -->
            <form action="{{ route('admin.pthotro.update', $pthotro->MaPTHT) }}" method="POST" class="spa-form">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã Phương Thức <span class="required">*</span></label>
                            <input type="text" name="MaPTHT" class="form-control" value="{{ old('MaPTHT', $pthotro->MaPTHT) }}" required>
                            <div class="help-text">Mã phương thức hỗ trợ hiện tại</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tên Phương Thức <span class="required">*</span></label>
                            <input type="text" name="TenPT" class="form-control" value="{{ old('TenPT', $pthotro->TenPT) }}" required>
                            <div class="help-text">Nhập tên mô tả phương thức hỗ trợ (vd: Hỗ trợ trực tuyến, Hỗ trợ qua điện thoại...)</div>
                        </div>
                    </div>
                </div>
                
                <div class="spa-btn-group">
                    <button type="submit" class="spa-btn spa-btn-primary">
                        <i class="fas fa-save"></i>
                        Lưu Thay Đổi
                    </button>
                    <a href="{{ route('admin.pthotro.index') }}" class="spa-btn spa-btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Quay Lại
                    </a>
                </div>
    </form>
        </div>
    </div>
</div>
@endsection