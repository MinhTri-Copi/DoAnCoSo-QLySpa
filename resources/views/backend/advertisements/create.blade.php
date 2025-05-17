@extends('backend.layouts.app')

@section('title', 'Thêm quảng cáo')

@section('styles')
<style>
    body {
        background-color: #ffebf3 !important;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #e83e8c, #fd7e97);
        color: white;
        border-radius: 10px;
        padding: 20px 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.3);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-in-out;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .welcome-banner p {
        font-size: 1rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    .shine-line {
        position: absolute;
        top: 0;
        left: -150%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 3s infinite;
        transform: skewX(-25deg);
    }
    
    @keyframes shine {
        0% { left: -150%; }
        100% { left: 150%; }
    }
    
    .form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        animation: fadeUp 0.6s ease-in-out;
        border: 1px solid rgba(232, 62, 140, 0.1);
    }
    
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .form-section {
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(232, 62, 140, 0.1);
        padding-bottom: 25px;
    }
    
    .form-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    
    .form-section-title {
        color: #e83e8c;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(232, 62, 140, 0.1);
    }
    
    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid #e1e1e1;
        padding: 12px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #e83e8c;
        box-shadow: 0 0 0 0.2rem rgba(232, 62, 140, 0.25);
    }
    
    .form-text {
        color: #888;
        font-size: 0.8rem;
        margin-top: 5px;
    }
    
    .required-star {
        color: #e83e8c;
        margin-left: 5px;
        font-size: 1rem;
    }
    
    .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #e83e8c, #fd7e97);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.3);
        transition: all 0.3s;
        min-width: 180px;
    }
    
    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(232, 62, 140, 0.4);
        color: white;
    }
    
    .btn-cancel {
        background-color: #f8f9fa;
        color: #555;
        border: 1px solid #ddd;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        min-width: 180px;
    }
    
    .btn-cancel:hover {
        background-color: #e9ecef;
        transform: translateY(-3px);
    }
    
    .file-upload-container {
        border: 2px dashed #e1e1e1;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s;
        background-color: #f9f9f9;
        position: relative;
        cursor: pointer;
    }
    
    .file-upload-container:hover {
        border-color: #e83e8c;
        background-color: #fff9fb;
    }
    
    .file-upload-icon {
        font-size: 3rem;
        color: #e83e8c;
        margin-bottom: 15px;
        animation: float 2s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .file-upload-text {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 15px;
        font-weight: 500;
    }
    
    .file-upload-info {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }
    
    .file-upload-preview {
        margin-top: 20px;
        border-radius: 10px;
        overflow: hidden;
        display: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 2px solid #e83e8c;
    }
    
    .file-upload-preview img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    
    .custom-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .alert {
        border-radius: 10px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        animation: slideDown 0.5s ease-out;
    }
    
    @keyframes slideDown {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    .alert-danger {
        background-color: #ffebee !important;
        border-left: 4px solid #f44336 !important;
    }
    
    .form-floating {
        position: relative;
        margin-bottom: 20px;
    }
    
    .form-floating-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #e83e8c;
        font-size: 1.2rem;
        z-index: 2;
    }
    
    .form-floating-input {
        padding-left: 45px !important;
    }
    
    .content-separator {
        height: 1px;
        background: linear-gradient(90deg, rgba(232, 62, 140, 0.1), rgba(232, 62, 140, 0.3), rgba(232, 62, 140, 0.1));
        margin: 15px 0;
    }
    
    /* Custom Select Styling */
    .custom-select-wrapper {
        position: relative;
    }
    
    .custom-select-trigger {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .custom-select-trigger:after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.8rem;
        color: #e83e8c;
        margin-left: 5px;
    }
    
    .custom-select-wrapper .dropdown-menu {
        width: 100%;
        padding: 0;
        margin-top: 5px;
        border-radius: 8px;
        border: 1px solid rgba(232, 62, 140, 0.2);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .custom-select-wrapper .dropdown-item {
        padding: 12px 15px;
        color: #333;
        transition: all 0.3s;
    }
    
    .custom-select-wrapper .dropdown-item:hover,
    .custom-select-wrapper .dropdown-item:focus {
        background-color: rgba(232, 62, 140, 0.1);
        color: #e83e8c;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1><i class="fas fa-spa"></i> Thêm Quảng Cáo Mới</h1>
            <p>Tạo mới quảng cáo, khuyến mãi hoặc sự kiện cho spa của bạn</p>
            <div class="shine-line"></div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-circle" style="font-size: 1.5rem; color: #f44336;"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading" style="font-size: 1.1rem; font-weight: 600; margin-bottom: 10px;">Vui lòng kiểm tra lại thông tin!</h5>
                        <ul class="mb-0" style="padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="form-container">
            <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data" id="ad-form">
                @csrf
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-info-circle"></i> Thông tin cơ bản
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="MaQC" class="form-label">
                                <i class="fas fa-hashtag" style="color: #e83e8c; margin-right: 8px;"></i>
                                Mã quảng cáo
                            </label>
                            <input type="number" name="MaQC" class="form-control" value="{{ $suggestedMaQC }}" readonly>
                            <small class="form-text">Mã quảng cáo được sinh tự động.</small>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="Manguoidung" class="form-label">
                                <i class="fas fa-user" style="color: #e83e8c; margin-right: 8px;"></i>
                                Người đăng quảng cáo<span class="required-star">*</span>
                            </label>
                            <select name="Manguoidung" class="form-control" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                                        {{ $user->Hoten }} (Mã: {{ $user->Manguoidung }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label for="Tieude" class="form-label">
                                <i class="fas fa-heading" style="color: #e83e8c; margin-right: 8px;"></i>
                                Tiêu đề quảng cáo<span class="required-star">*</span>
                            </label>
                            <input type="text" name="Tieude" class="form-control" value="{{ old('Tieude') }}" required placeholder="Nhập tiêu đề quảng cáo">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label for="Noidung" class="form-label">
                                <i class="fas fa-align-left" style="color: #e83e8c; margin-right: 8px;"></i>
                                Nội dung quảng cáo<span class="required-star">*</span>
                            </label>
                            <textarea name="Noidung" class="form-control" rows="5" required placeholder="Nhập nội dung chi tiết về quảng cáo">{{ old('Noidung') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-image"></i> Hình ảnh quảng cáo
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="file-upload-container mb-2">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">
                                    Kéo và thả hình ảnh vào đây, hoặc click để chọn
                                </div>
                                <div class="file-upload-info">
                                    Định dạng: .jpg, .jpeg, .png, .gif | Dung lượng tối đa: 2MB
                                </div>
                                <input type="file" name="Image" class="custom-file-input" id="ad-image">
                                <div class="file-upload-preview" id="image-preview"></div>
                            </div>
                            <div class="text-center" id="remove-image-btn" style="display: none;">
                                <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 20px; padding: 5px 15px;">
                                    <i class="fas fa-trash-alt"></i> Xóa hình ảnh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-sliders-h"></i> Phân loại và thời gian
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="Loaiquangcao" class="form-label">
                                <i class="fas fa-tag" style="color: #e83e8c; margin-right: 8px;"></i>
                                Loại quảng cáo<span class="required-star">*</span>
                            </label>
                            @php
                                $loaiQuangCaoMapping = [
                                    'Khuyenmai' => 'Khuyến mãi',
                                    'Sukien' => 'Sự kiện',
                                    'Thongbao' => 'Thông báo'
                                ];
                                $selectedType = old('Loaiquangcao', $adTypes[0] ?? '');
                                $selectedDisplayText = $loaiQuangCaoMapping[$selectedType] ?? $selectedType;
                            @endphp
                            <div class="custom-select-wrapper">
                                <input type="hidden" name="Loaiquangcao" id="selected-type" value="{{ $selectedType }}">
                                <div class="form-control custom-select-trigger" id="selected-display" data-bs-toggle="dropdown">
                                    {{ $selectedDisplayText }}
                                </div>
                                <div class="dropdown-menu w-100">
                                    @foreach ($adTypes as $type)
                                        <a class="dropdown-item type-option" href="#" 
                                           data-value="{{ $type }}" 
                                           data-display="{{ $loaiQuangCaoMapping[$type] ?? $type }}">
                                            {{ $loaiQuangCaoMapping[$type] ?? $type }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="MaTTQC" class="form-label">
                                <i class="fas fa-toggle-on" style="color: #e83e8c; margin-right: 8px;"></i>
                                Trạng thái quảng cáo<span class="required-star">*</span>
                            </label>
                            <select name="MaTTQC" class="form-control" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->MaTTQC }}" {{ old('MaTTQC') == $status->MaTTQC ? 'selected' : '' }}>
                                        {{ $status->TenTT }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="Ngaybatdau" class="form-label">
                                <i class="fas fa-calendar-plus" style="color: #e83e8c; margin-right: 8px;"></i>
                                Ngày bắt đầu<span class="required-star">*</span>
                            </label>
                            <input type="date" name="Ngaybatdau" class="form-control" value="{{ old('Ngaybatdau') }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="Ngayketthuc" class="form-label">
                                <i class="fas fa-calendar-minus" style="color: #e83e8c; margin-right: 8px;"></i>
                                Ngày kết thúc<span class="required-star">*</span>
                            </label>
                            <input type="date" name="Ngayketthuc" class="form-control" value="{{ old('Ngayketthuc') }}" required>
                            <small class="form-text">Ngày kết thúc phải sau hoặc trùng với ngày bắt đầu.</small>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times-circle"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-plus-circle"></i> Thêm quảng cáo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('ad-image');
            const imagePreview = document.getElementById('image-preview');
            const removeBtn = document.getElementById('remove-image-btn');
            
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                        imagePreview.style.display = 'block';
                        removeBtn.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            removeBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.innerHTML = '';
                imagePreview.style.display = 'none';
                removeBtn.style.display = 'none';
            });

            // Xử lý cho dropdown tùy chỉnh loại quảng cáo
            const typeOptions = document.querySelectorAll('.type-option');
            const selectedTypeInput = document.getElementById('selected-type');
            const selectedDisplay = document.getElementById('selected-display');
            
            typeOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.dataset.value;
                    const display = this.dataset.display;
                    
                    selectedTypeInput.value = value;
                    selectedDisplay.textContent = display;
                });
            });
        });
    </script>
@endsection