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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
    }

    .btn-outline-secondary {
        color: var(--spa-primary);
        border-color: var(--spa-primary);
        background-color: transparent;
    }

    .btn-outline-secondary:hover {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
        color: white;
    }

    /* Custom radio buttons */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
    }

    .custom-control-label {
        cursor: pointer;
    }

    /* Form sections */
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

    /* Action buttons container */
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

    /* Animation for form */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
    }

    .animate-delay-1 {
        animation-delay: 0.1s;
    }

    .animate-delay-2 {
        animation-delay: 0.2s;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .form-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-actions .btn {
            width: 100%;
        }
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
                    <i class="fas fa-user-plus mr-2"></i>Thêm Khách Hàng Mới
                </h1>
                <p class="text-white-50 mb-0">Tạo hồ sơ cho khách hàng mới của spa</p>
            </div>
            <div>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left mr-1"></i>
                    <span>Quay Lại Danh Sách</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Create Customer Form -->
    <div class="card customer-card animate-fade-in-up">
        <div class="card-header-gradient py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-user-edit mr-2"></i>Thông Tin Khách Hàng
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.customers.store') }}" method="POST" id="createCustomerForm">
                @csrf
                
                <!-- Personal Information -->
                <div class="form-section animate-fade-in-up">
                    <h5 class="form-section-title">
                        <i class="fas fa-user-circle"></i> Thông Tin Cá Nhân
                    </h5>
                    <div class="form-section-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Manguoidung">Mã Khách Hàng <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('Manguoidung') is-invalid @enderror" 
                                            id="Manguoidung" name="Manguoidung" value="{{ $suggestedManguoidung }}" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="generateId">
                                                <i class="fas fa-random"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('Manguoidung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Mã khách hàng được tạo tự động</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="Hoten">Họ Tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('Hoten') is-invalid @enderror" 
                                        id="Hoten" name="Hoten" value="{{ old('Hoten') }}">
                                    @error('Hoten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="Ngaysinh">Ngày Sinh <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('Ngaysinh') is-invalid @enderror" 
                                        id="Ngaysinh" name="Ngaysinh" value="{{ old('Ngaysinh') }}">
                                    @error('Ngaysinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="NgaysinhFeedback" class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="MaTK">Tài Khoản <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('MaTK') is-invalid @enderror" id="MaTK" name="MaTK">
                                        <option value="">-- Chọn Tài Khoản --</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->MaTK }}">
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
                                                {{ old('Gioitinh') == 'Nam' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="genderMale">Nam</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="genderFemale" name="Gioitinh" value="Nữ" 
                                                class="custom-control-input @error('Gioitinh') is-invalid @enderror"
                                                {{ old('Gioitinh') == 'Nữ' ? 'checked' : '' }}>
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
                <div class="form-section animate-fade-in-up animate-delay-1">
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
                                            id="Email" name="Email" value="{{ old('Email') }}">
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
                                            id="SDT" name="SDT" value="{{ old('SDT') }}" placeholder="Nhập số điện thoại">
                                    </div>
                                    @error('SDT')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="SDTFeedback" class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="DiaChi">Địa Chỉ <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('DiaChi') is-invalid @enderror" 
                                        id="DiaChi" name="DiaChi" rows="4" placeholder="Nhập địa chỉ đầy đủ">{{ old('DiaChi') }}</textarea>
                                    @error('DiaChi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions animate-fade-in-up animate-delay-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Lưu Khách Hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize select2 if it exists
    if($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Chọn Tài Khoản --",
        });
    }
    
    // Generate random ID
    $('#generateId').on('click', function() {
        const randomId = Math.floor(1000 + Math.random() * 9000);
        $('#Manguoidung').val(randomId);
    });
    
    // Phone number validation
    $('#SDT').on('blur', function() {
        const phone = $(this).val().trim();
        if(phone && !/^(0|\+84)\d{9,10}$/.test(phone)) {
            $(this).addClass('is-invalid');
            $('#SDTFeedback').text('Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng số điện thoại Việt Nam').show();
        } else {
            $(this).removeClass('is-invalid');
            $('#SDTFeedback').hide();
        }
    });
    
    // Date of birth validation
    $('#Ngaysinh').on('blur', function() {
        const dob = new Date($(this).val());
        const today = new Date();
        const age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        
        if(dob > today) {
            $(this).addClass('is-invalid');
            $('#NgaysinhFeedback').text('Ngày sinh không hợp lệ, không thể là ngày trong tương lai').show();
        } else if(age > 100) {
            $(this).addClass('is-invalid');
            $('#NgaysinhFeedback').text('Ngày sinh không hợp lệ, tuổi quá cao').show();
        } else {
            $(this).removeClass('is-invalid');
            $('#NgaysinhFeedback').hide();
        }
    });
    
    // Form validation before submit
    $('#createCustomerForm').on('submit', function(e) {
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
            $('#SDTFeedback').text('Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng số điện thoại Việt Nam').show();
            isValid = false;
        }
        
        // Validate date of birth
        const dob = new Date($('#Ngaysinh').val());
        const today = new Date();
        if(dob > today) {
            $('#Ngaysinh').addClass('is-invalid');
            $('#NgaysinhFeedback').text('Ngày sinh không hợp lệ, không thể là ngày trong tương lai').show();
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