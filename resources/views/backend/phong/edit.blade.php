@extends('backend.layouts.app')

@section('title', 'Chỉnh Sửa Phòng')

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
    
    /* Form validation */
    .is-invalid {
        border-color: var(--danger) !important;
    }
    
    .invalid-feedback {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 0.4rem;
    }
    
    /* Room form specific */
    .room-icon {
        font-size: 1.2rem;
        color: var(--primary-pink);
        margin-right: 0.5rem;
    }
    
    .form-status {
        position: relative;
        top: -1px;
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .status-available {
        background-color: #10b981;
    }
    
    .status-maintenance {
        background-color: #f59e0b;
    }
    
    .status-occupied {
        background-color: #ef4444;
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
            <h1 class="spa-header-title">Chỉnh Sửa Phòng</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-edit"></i> Cập nhật thông tin cho phòng {{ $phong->Tenphong }}
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
                <i class="fas fa-door-open"></i> Thông tin phòng
            </h2>
        </div>
        
        <div class="spa-content-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <h5 class="alert-heading mb-2"><i class="fas fa-exclamation-triangle"></i> Lỗi nhập liệu</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.phong.update', $phong->Maphong) }}" method="POST" class="spa-form">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Maphong">
                                <i class="fas fa-hashtag room-icon"></i>
                                Mã phòng <span class="required">*</span>
                            </label>
                            <input type="text" 
                                class="form-control @error('Maphong') is-invalid @enderror" 
                                id="Maphong" 
                                name="Maphong" 
                                value="{{ $phong->Maphong }}" 
                                readonly>
                            <p class="help-text">Mã phòng không thể thay đổi.</p>
                            @error('Maphong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Tenphong">
                                <i class="fas fa-signature room-icon"></i>
                                Tên phòng <span class="required">*</span>
                            </label>
                            <input type="text" 
                                class="form-control @error('Tenphong') is-invalid @enderror" 
                                id="Tenphong" 
                                name="Tenphong" 
                                value="{{ old('Tenphong', $phong->Tenphong) }}" 
                                placeholder="Nhập tên phòng">
                            @error('Tenphong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Loaiphong">
                                <i class="fas fa-tag room-icon"></i>
                                Loại phòng <span class="required">*</span>
                            </label>
                            <select class="form-control @error('Loaiphong') is-invalid @enderror" 
                                id="Loaiphong" 
                                name="Loaiphong">
                                <option value="">-- Chọn loại phòng --</option>
                                <option value="Thường" {{ old('Loaiphong', $phong->Loaiphong) == 'Thường' ? 'selected' : '' }}>Thường</option>
                                <option value="Cao cấp" {{ old('Loaiphong', $phong->Loaiphong) == 'Cao cấp' ? 'selected' : '' }}>Cao cấp</option>
                            </select>
                            @error('Loaiphong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MatrangthaiP">
                                <i class="fas fa-info-circle room-icon"></i>
                                Trạng thái phòng <span class="required">*</span>
                            </label>
                            <select class="form-control @error('MatrangthaiP') is-invalid @enderror" 
                                id="MatrangthaiP" 
                                name="MatrangthaiP">
                                <option value="">-- Chọn trạng thái --</option>
                                @foreach ($trangThaiPhongs as $trangThai)
                                    <option value="{{ $trangThai->MatrangthaiP }}" {{ old('MatrangthaiP', $phong->MatrangthaiP) == $trangThai->MatrangthaiP ? 'selected' : '' }}>
                                        {{ $trangThai->Tentrangthai }}
                                    </option>
                                @endforeach
                            </select>
                            @error('MatrangthaiP')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-actions mt-4 d-flex justify-content-end">
                    <a href="{{ route('admin.phong.index') }}" class="spa-btn spa-btn-secondary me-2">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="spa-btn spa-btn-primary">
                        <i class="fas fa-save"></i> Cập nhật phòng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection