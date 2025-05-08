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
    }

    .spa-btn-back {
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

    .spa-btn-back i {
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

    .spa-btn-back:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: var(--dark-pink);
        text-decoration: none;
    }

    .spa-btn-back:hover i {
        background: rgba(255, 107, 149, 0.25);
        transform: rotate(-15deg);
    }

    /* Content Wrapper */
    .spa-content-wrapper {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 3rem;
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

    /* Form Elements */
    .spa-form-group {
        margin-bottom: 1.5rem;
    }

    .spa-form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .spa-form-label .required {
        color: var(--primary-pink);
        margin-left: 0.25rem;
    }

    .spa-form-control {
        width: 100%;
        padding: 0.8rem 1.2rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        transition: var(--transition-fast);
        background: var(--light-gray);
    }

    .spa-form-control:focus {
        outline: none;
        border-color: var(--primary-pink);
        background: var(--white);
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .spa-input-group {
        display: flex;
        position: relative;
    }

    .spa-input-group .spa-form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .spa-input-group-append {
        display: flex;
    }

    .spa-input-group-append .spa-btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: var(--light-gray);
        color: var(--text-secondary);
        padding: 0 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-fast);
    }

    .spa-input-group-append .spa-btn:hover {
        background: var(--primary-pink);
        color: white;
    }

    .spa-help-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Template Card */
    .spa-template-card {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .spa-template-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-pink);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .spa-template-title i {
        font-size: 1rem;
    }

    .spa-radio-group {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .spa-radio-group {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .spa-radio-option {
        position: relative;
        padding: 1rem;
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        transition: var(--transition-fast);
        cursor: pointer;
        border: 2px solid transparent;
    }

    .spa-radio-option:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .spa-radio-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .spa-radio-option input[type="radio"]:checked + label:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 2px solid var(--primary-pink);
        border-radius: var(--radius-md);
        z-index: 1;
    }

    .spa-radio-option input[type="radio"]:checked + label:after {
        content: '✓';
        position: absolute;
        top: -10px;
        right: -10px;
        width: 25px;
        height: 25px;
        background: var(--primary-pink);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        z-index: 2;
    }

    .spa-radio-option label {
        display: flex;
        flex-direction: column;
        cursor: pointer;
        margin: 0;
    }

    .spa-radio-icon {
        font-size: 1.8rem;
        color: var(--primary-pink);
        margin-bottom: 0.5rem;
    }

    .spa-radio-label {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Buttons */
    .spa-btn-row {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .spa-btn {
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition-fast);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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

    .spa-btn i {
        font-size: 0.9rem;
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

        .spa-content-body {
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
            <h1 class="spa-header-title">Thêm Vai Trò Mới</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-plus-circle"></i>
                Tạo và thiết lập vai trò mới cho hệ thống
            </p>
        </div>
        <div class="spa-header-action">
            <a href="{{ route('admin.roles.index') }}" class="spa-btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <!-- Form Content Wrapper -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-user-tag"></i>
                Thông Tin Vai Trò
            </h2>
        </div>
        <div class="spa-content-body">
            <form action="{{ route('admin.roles.store') }}" method="POST" id="createRoleForm">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="spa-form-group">
                            <label class="spa-form-label" for="RoleID">Mã Vai Trò <span class="required">*</span></label>
                            <div class="spa-input-group">
                                <input type="number" class="spa-form-control @error('RoleID') is-invalid @enderror" 
                                    id="RoleID" name="RoleID" value="{{ $suggestedRoleID }}" readonly>
                                <div class="spa-input-group-append">
                                    <button class="spa-btn" type="button" id="generateId">
                                        <i class="fas fa-random"></i>
                                    </button>
                                </div>
                            </div>
                            @error('RoleID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="RoleIDFeedback" class="invalid-feedback"></div>
                            <small class="spa-help-text">Mã vai trò được tạo tự động</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="spa-form-group">
                            <label class="spa-form-label" for="Tenrole">Tên Vai Trò <span class="required">*</span></label>
                            <input type="text" class="spa-form-control @error('Tenrole') is-invalid @enderror" 
                                id="Tenrole" name="Tenrole" value="{{ old('Tenrole') }}" placeholder="Nhập tên vai trò">
                            @error('Tenrole')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="TenroleFeedback" class="invalid-feedback"></div>
                            <small class="spa-help-text">Tên vai trò không được vượt quá 50 ký tự</small>
                        </div>
                    </div>
                </div>
                
                <div class="spa-template-card mt-4">
                    <h3 class="spa-template-title">
                        <i class="fas fa-lightbulb"></i>
                        Gợi Ý Vai Trò Phổ Biến
                    </h3>
                    <div class="spa-radio-group">
                        <div class="spa-radio-option">
                            <input type="radio" id="roleAdmin" name="roleTemplate" value="Admin">
                            <label for="roleAdmin">
                                <span class="spa-radio-icon"><i class="fas fa-user-shield"></i></span>
                                <span class="spa-radio-label">Quản trị viên</span>
                            </label>
                        </div>
                        <div class="spa-radio-option">
                            <input type="radio" id="roleManager" name="roleTemplate" value="Manager">
                            <label for="roleManager">
                                <span class="spa-radio-icon"><i class="fas fa-user-tie"></i></span>
                                <span class="spa-radio-label">Quản lý</span>
                            </label>
                        </div>
                        <div class="spa-radio-option">
                            <input type="radio" id="roleStaff" name="roleTemplate" value="Staff">
                            <label for="roleStaff">
                                <span class="spa-radio-icon"><i class="fas fa-user"></i></span>
                                <span class="spa-radio-label">Nhân viên</span>
                            </label>
                        </div>
                        <div class="spa-radio-option">
                            <input type="radio" id="roleUser" name="roleTemplate" value="User">
                            <label for="roleUser">
                                <span class="spa-radio-icon"><i class="fas fa-user-friends"></i></span>
                                <span class="spa-radio-label">Người dùng</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="spa-btn-row">
                    <button type="button" class="spa-btn spa-btn-secondary" onclick="window.history.back();">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="spa-btn spa-btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Lưu Vai Trò
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/roles.js') }}"></script>
<script src="{{ asset('js/admin/roles/create.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiệu ứng khi chọn template vai trò
    const radioOptions = document.querySelectorAll('.spa-radio-option input[type="radio"]');
    radioOptions.forEach(radio => {
        radio.addEventListener('change', function() {
            const option = this.closest('.spa-radio-option');
            if (this.checked) {
                option.classList.add('selected');
                // Tự động điền tên vai trò dựa vào lựa chọn
                const roleLabel = option.querySelector('.spa-radio-label').textContent;
                document.getElementById('Tenrole').value = roleLabel;
            } else {
                option.classList.remove('selected');
            }
        });
    });
    
    // Hiệu ứng nút generate ID
    const generateBtn = document.getElementById('generateId');
    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            this.classList.add('generating');
            setTimeout(() => {
                this.classList.remove('generating');
            }, 500);
        });
    }
});
</script>
@endsection