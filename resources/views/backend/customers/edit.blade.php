@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/customers.css') }}" rel="stylesheet">
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
    
    /* Header styling */
    .customer-header {
        background: var(--pink-gradient);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: var(--spa-card-shadow);
        display: flex;
        align-items: center;
        min-height: 120px;
    }

    .customer-header-background {
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background-image: radial-gradient(circle, rgba(255,255,255,0.15) 10%, transparent 70%);
        z-index: 1;
    }

    .customer-header-shimmer {
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

    .customer-header .glitter-dot {
        position: absolute;
        background: white;
        border-radius: 50%;
        opacity: 0;
        z-index: 3;
        box-shadow: 0 0 10px 2px rgba(255,255,255,0.8);
        animation: glitter 8s infinite;
    }

    .customer-header .glitter-dot:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 25%;
        left: 10%;
        animation-delay: 0s;
    }

    .customer-header .glitter-dot:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 40%;
        left: 30%;
        animation-delay: 2s;
    }

    .customer-header .glitter-dot:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 20%;
        right: 25%;
        animation-delay: 4s;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
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

    .customer-header-content {
        position: relative;
        z-index: 5;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Card styling */
    .card.customer-card {
        border: none;
        border-radius: var(--radius-md);
        box-shadow: var(--spa-card-shadow);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card.customer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(255, 107, 149, 0.2);
    }

    /* Form Sections */
    .form-section {
        background-color: white;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--spa-primary);
    }

    .form-section-title {
        color: var(--spa-primary-dark);
        margin-bottom: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .form-section-title i {
        margin-right: 0.5rem;
    }

    .form-section-content {
        padding: 0.5rem 0;
    }

    /* Stats card styling */
    .stat-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.2rem;
        background-color: white;
        border-radius: var(--radius-md);
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 1rem;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255, 107, 149, 0.15);
    }

    .stat-card-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 0.75rem;
        color: white;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
    }

    .stat-card-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-card-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    /* Customer Profile Card */
    .customer-profile {
        padding: 2rem;
        text-align: center;
        position: relative;
        background-color: var(--spa-light);
        border-radius: var(--radius-md);
    }

    .customer-profile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, var(--light-pink) 0%, rgba(255,255,255,0) 100%);
        opacity: 0.3;
        z-index: 0;
        border-radius: var(--radius-md);
    }

    /* Customer Avatar */
    .customer-avatar {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background-color: white;
        padding: 4px;
        box-shadow: 0 5px 15px rgba(255, 107, 149, 0.2);
        z-index: 1;
    }

    .customer-avatar-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        background: var(--pink-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 300;
    }

    .customer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .customer-avatar-name {
        display: none;
    }

    .customer-avatar::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
        top: 0;
        left: 0;
        z-index: 2;
    }

    @keyframes avatar-pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 107, 149, 0.5); }
        70% { box-shadow: 0 0 0 10px rgba(255, 107, 149, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 107, 149, 0); }
    }

    .customer-profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        color: var(--spa-dark);
        position: relative;
        z-index: 1;
    }

    /* Membership badge styling */
    .customer-membership-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .membership-regular {
        background-color: var(--light-pink);
        color: var(--spa-dark);
    }

    .membership-vip {
        background: linear-gradient(120deg, #ffd700, #daa520);
        color: white;
        box-shadow: 0 3px 10px rgba(218, 165, 32, 0.3);
    }

    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        margin-top: 1rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20px;
        height: 100%;
        width: 2px;
        background-color: var(--light-pink);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        top: 0;
        left: -1.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-top: 5px;
        background-color: var(--spa-primary);
    }

    .timeline-content {
        padding: 1rem;
        background-color: white;
        border-radius: var(--radius-sm);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 0.5rem;
    }

    .timeline-date {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .timeline-body {
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    /* Card header gradient style */
    .card-header-gradient {
        background: var(--pink-gradient);
        color: white;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 100%);
        transform: skewX(-30deg);
    }

    /* Button styling */
    .btn {
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
    }

    .btn-primary:hover {
        background-color: var(--spa-primary-dark);
        border-color: var(--spa-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.3);
    }

    .btn-light {
        background-color: white;
        color: var(--spa-primary);
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: none;
        display: flex;
        align-items: center;
    }

    .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        background-color: white;
        color: var(--spa-primary-dark);
    }

    /* Animation for page */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: var(--radius-sm);
        padding: 0.7rem 1rem;
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary-pink);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 149, 0.25);
    }

    .input-group-text {
        border-radius: var(--radius-sm);
        background-color: var(--light-pink);
        border-color: var(--border-color);
        color: var(--spa-primary-dark);
    }

    label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    /* Custom radio buttons */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
    }

    .custom-control-label {
        cursor: pointer;
    }

    /* Form Action Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem 0;
    }

    .form-actions .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
    }

    .form-actions .btn i {
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="customer-header mb-4">
        <div class="customer-header-background"></div>
        <div class="customer-header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        
        <div class="customer-header-content">
            <div>
                <h1 class="h3 mb-0 text-white">
                    <i class="fas fa-user-edit mr-2"></i>Chỉnh Sửa Khách Hàng
                </h1>
                <p class="text-white-50 mb-0">Cập nhật thông tin khách hàng: {{ $customer->Hoten }}</p>
            </div>
            <div>
                <a href="{{ route('admin.customers.show', $customer->Manguoidung) }}" class="btn btn-light mr-2">
                    <i class="fas fa-eye mr-1"></i>
                    <span>Xem Chi Tiết</span>
                </a>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left mr-1"></i>
                    <span>Quay Lại</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Edit Form -->
        <div class="col-lg-8">
            <div class="card customer-card animate-fade-in">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-edit mr-2"></i>Thông Tin Khách Hàng
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.customers.update', $customer->Manguoidung) }}" method="POST" id="editCustomerForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-user-circle"></i> Thông Tin Cá Nhân
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Manguoidung">Mã Khách Hàng</label>
                                            <input type="text" class="form-control" id="Manguoidung" value="{{ $customer->Manguoidung }}" readonly>
                                            <small class="form-text text-muted">Mã khách hàng không thể thay đổi</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="Hoten">Họ Tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('Hoten') is-invalid @enderror" 
                                                id="Hoten" name="Hoten" value="{{ old('Hoten', $customer->Hoten) }}">
                                            @error('Hoten')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="Ngaysinh">Ngày Sinh <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('Ngaysinh') is-invalid @enderror" 
                                                id="Ngaysinh" name="Ngaysinh" value="{{ old('Ngaysinh', $customer->Ngaysinh) }}">
                                            @error('Ngaysinh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="MaTK">Tài Khoản <span class="text-danger">*</span></label>
                                            <select class="form-control @error('MaTK') is-invalid @enderror" id="MaTK" name="MaTK">
                                                <option value="">-- Chọn Tài Khoản --</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->MaTK }}" {{ old('MaTK', $customer->MaTK) == $account->MaTK ? 'selected' : '' }}>
                                                        {{ $account->Tendangnhap }} ({{ $account->MaTK }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('MaTK')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Giới Tính <span class="text-danger">*</span></label>
                                            <div class="d-flex">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="genderMale" name="Gioitinh" value="Nam" 
                                                        class="custom-control-input @error('Gioitinh') is-invalid @enderror"
                                                        {{ old('Gioitinh', $customer->Gioitinh) == 'Nam' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="genderMale">Nam</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="genderFemale" name="Gioitinh" value="Nữ" 
                                                        class="custom-control-input @error('Gioitinh') is-invalid @enderror"
                                                        {{ old('Gioitinh', $customer->Gioitinh) == 'Nữ' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="genderFemale">Nữ</label>
                                                </div>
                                            </div>
                                            @error('Gioitinh')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <i class="fas fa-address-card"></i> Thông Tin Liên Hệ
                            </h5>
                            <div class="form-section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Email">Email <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control @error('Email') is-invalid @enderror" 
                                                    id="Email" name="Email" value="{{ old('Email', $customer->Email) }}">
                                            </div>
                                            @error('Email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="SDT">Số Điện Thoại <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" class="form-control @error('SDT') is-invalid @enderror" 
                                                    id="SDT" name="SDT" value="{{ old('SDT', $customer->SDT) }}">
                                            </div>
                                            @error('SDT')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="DiaChi">Địa Chỉ <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('DiaChi') is-invalid @enderror" 
                                                id="DiaChi" name="DiaChi" rows="4">{{ old('DiaChi', $customer->DiaChi) }}</textarea>
                                            @error('DiaChi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Lưu Thay Đổi
                            </button>
                            <a href="{{ route('admin.customers.confirmDestroy', $customer->Manguoidung) }}" class="btn btn-danger ml-2">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Customer Profile Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Profile Card -->
            <div class="card customer-card animate-fade-in">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-id-card mr-2"></i>Thông Tin Khách Hàng
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="customer-profile">
                        <div class="customer-avatar">
                            <div class="customer-avatar-inner">
                                @if(isset($customer->avatar) && !empty($customer->avatar))
                                    <img src="{{ asset('storage/avatars/' . $customer->avatar) }}" alt="{{ $customer->Hoten }}">
                                @else
                                    <span class="customer-avatar-name">{{ substr($customer->Hoten, 0, 1) }}</span>
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                        </div>
                        
                        <h4 class="customer-profile-name">{{ $customer->Hoten }}</h4>
                        
                        @php
                            $hangTV = $customer->hangThanhVien->first();
                            $hangName = $hangTV ? $hangTV->Tenhang : 'Thành viên Bạc';
                            $badgeClass = 'membership-regular';
                            
                            if($hangName == 'Thành viên Vàng') {
                                $badgeClass = 'membership-vip';
                            } elseif($hangName == 'Thành viên Bạch Kim') {
                                $badgeClass = 'membership-platinum';
                            } elseif($hangName == 'Thành viên Kim Cương') {
                                $badgeClass = 'membership-diamond';
                            }
                        @endphp
                        
                        <div class="customer-membership-badge {{ $badgeClass }}">
                            @if($hangName != 'Thành viên Bạc')
                                <i class="fas fa-crown mr-1"></i>
                            @endif
                            {{ $hangName }}
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-4">
                                <div class="stat-card">
                                    <div class="stat-card-icon bg-gradient-primary">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="stat-card-value">{{ $customer->hoaDon->count() }}</div>
                                    <div class="stat-card-label">Đơn hàng</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-card">
                                    <div class="stat-card-icon bg-gradient-primary">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="stat-card-value">{{ $customer->datLich->count() }}</div>
                                    <div class="stat-card-label">Lịch hẹn</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-card">
                                    <div class="stat-card-icon bg-gradient-primary">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div class="stat-card-value">
                                        @php
                                            $pointsEarned = $customer->lsDiemThuong->where('Loai', 'Cộng')->sum('Diem');
                                            $pointsSpent = $customer->lsDiemThuong->where('Loai', 'Trừ')->sum('Diem');
                                            $currentPoints = $pointsEarned - $pointsSpent;
                                        @endphp
                                        {{ $currentPoints }}
                                    </div>
                                    <div class="stat-card-label">Điểm</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Card -->
            <div class="card customer-card animate-fade-in mt-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-history mr-2"></i>Hoạt Động Gần Đây
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="timeline">
                        @php
                            $recentActivities = collect();
                            
                            // Add orders
                            foreach($customer->hoaDon->take(2) as $order) {
                                $recentActivities->push([
                                    'date' => $order->Ngaytao,
                                    'type' => 'order',
                                    'title' => 'Đặt đơn hàng #' . $order->MaHD,
                                    'content' => 'Tổng giá trị: ' . number_format($order->Tongtien, 0, ',', '.') . ' VNĐ',
                                    'status' => $order->trangThai ? $order->trangThai->Tentrangthai : 'N/A',
                                ]);
                            }
                            
                            // Add appointments
                            foreach($customer->datLich->take(2) as $appointment) {
                                $recentActivities->push([
                                    'date' => $appointment->Ngaydat ?? $appointment->Thoigiandatlich,
                                    'type' => 'appointment',
                                    'title' => 'Đặt lịch hẹn dịch vụ',
                                    'content' => ($appointment->dichVu ? $appointment->dichVu->Tendichvu : 'N/A'),
                                    'status' => $appointment->Trangthai_ ?? 'N/A',
                                ]);
                            }
                            
                            // Sort by date (newest first)
                            $recentActivities = $recentActivities->sortByDesc('date')->take(3);
                        @endphp
                        
                        @forelse($recentActivities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="timeline-title">
                                        @if($activity['type'] == 'order')
                                            <i class="fas fa-shopping-cart mr-1 text-primary"></i>
                                        @else
                                            <i class="fas fa-calendar-check mr-1 text-primary"></i>
                                        @endif
                                        {{ $activity['title'] }}
                                    </div>
                                    <div class="timeline-body">
                                        {{ $activity['content'] }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">Không có hoạt động gần đây</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Phone number validation
    $('#SDT').on('blur', function() {
        const phone = $(this).val().trim();
        if(phone && !/^(0|\+84)\d{9,10}$/.test(phone)) {
            $(this).addClass('is-invalid');
            $('<div class="invalid-feedback">Số điện thoại không hợp lệ</div>').insertAfter($(this).parent());
        } else {
            $(this).removeClass('is-invalid');
            $(this).parent().next('.invalid-feedback').remove();
        }
    });
    
    // Form validation before submit
    $('#editCustomerForm').on('submit', function(e) {
        let isValid = true;
        
        // Validate required fields
        $(this).find('[required]').each(function() {
            if($(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validate phone number
        const phone = $('#SDT').val().trim();
        if(phone && !/^(0|\+84)\d{9,10}$/.test(phone)) {
            $('#SDT').addClass('is-invalid');
            isValid = false;
        }
        
        if(!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
        }
    });
});
</script>
@endsection