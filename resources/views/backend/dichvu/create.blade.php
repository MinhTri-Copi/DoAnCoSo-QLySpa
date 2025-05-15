@extends('backend.layouts.app')

@section('title', 'Thêm Dịch Vụ')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .page-create-dichvu {
        padding: 2.5rem 0;
        background-color: #f9f9fd;
        min-height: calc(100vh - 100px);
        position: relative;
    }
    
    .page-create-dichvu::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 107, 107, 0.05) 0%, rgba(255, 107, 107, 0) 70%);
        z-index: 0;
    }

    .create-form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        padding: 45px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
        z-index: 1;
    }

    .create-form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .create-form-container::after {
        content: "";
        position: absolute;
        width: 200px;
        height: 200px;
        background: linear-gradient(120deg, rgba(255, 107, 107, 0.05) 0%, rgba(255, 142, 142, 0.05) 100%);
        border-radius: 50%;
        bottom: -100px;
        right: -100px;
        z-index: 0;
    }

    .form-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
        margin: -45px -45px 40px;
        padding: 40px 45px;
        border-radius: 20px 20px 0 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .form-header h2 {
        font-size: 2.4rem;
        font-weight: 700;
        margin-bottom: 12px;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-header p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 2;
    }

    .form-header::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 60%);
        transform: rotate(30deg);
        z-index: 1;
    }
    
    .form-header::after {
        content: "";
        position: absolute;
        right: -30px;
        bottom: -60px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    }

    .form-icon {
        position: absolute;
        top: 30px;
        right: 45px;
        width: 70px;
        height: 70px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    .form-icon i {
        font-size: 2rem;
        color: #ff6b6b;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }

    .full-width {
        grid-column: span 2;
    }

    .form-group {
        margin-bottom: 30px;
        position: relative;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 1.1rem;
        color: #343a40;
        margin-bottom: 12px;
        transition: all 0.3s;
    }

    .form-group:focus-within label {
        color: #ff6b6b;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 16px 20px;
        height: auto;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
        width: 100%;
        font-size: 1.1rem;
        background-color: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ff6b6b;
        box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.15);
        background-color: #fff;
        outline: none;
    }

    .form-control::placeholder {
        color: #adb5bd;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: 8px;
        font-weight: 500;
        animation: fadeIn 0.3s ease;
    }

    .form-text {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 8px;
    }

    .required-label::after {
        content: "*";
        color: #ff6b6b;
        margin-left: 4px;
    }

    .image-upload-container {
        border: 3px dashed #e9ecef;
        border-radius: 16px;
        padding: 50px 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        background-color: #f8f9fa;
    }

    .image-upload-container:hover {
        border-color: #ff6b6b;
        background-color: #fff5f5;
    }

    .image-upload-container.drag-active {
        border-color: #ff6b6b;
        background-color: #fff5f5;
        transform: scale(1.02);
    }

    .image-upload-icon {
        font-size: 3.5rem;
        color: #ff6b6b;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .image-upload-container:hover .image-upload-icon {
        transform: translateY(-5px);
    }

    .image-upload-container p {
        font-size: 1.1rem;
        margin-bottom: 0;
        color: #495057;
    }

    .image-upload-container .upload-hints {
        display: block;
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 10px;
    }

    .image-preview {
        max-width: 100%;
        max-height: 240px;
        border-radius: 12px;
        margin-top: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        animation: fadeIn 0.5s ease;
    }

    .checkboxes-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .custom-checkbox:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    .custom-checkbox input[type="checkbox"] {
        margin-right: 10px;
        transform: scale(1.3);
        accent-color: #ff6b6b;
    }

    .custom-checkbox label {
        margin-bottom: 0;
        font-weight: 500;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 60px;
        border-top: 1px solid #e9ecef;
        padding-top: 30px;
        position: relative;
        z-index: 2;
    }

    .btn-action {
        padding: 16px 32px;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 180px;
        text-align: center;
    }

    .btn-primary-pink {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
    }

    .btn-primary-pink:hover {
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        transform: translateY(-5px);
    }

    .btn-secondary-light {
        background: #e9ecef;
        color: #495057;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary-light:hover {
        background: #dee2e6;
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
    }

    .btn-action i {
        margin-right: 10px;
        font-size: 1rem;
    }

    .form-spacer {
        flex: 1 1 auto;
        max-width: 50px;
    }

    .input-group {
        display: flex;
        align-items: center;
        position: relative;
    }

    .input-group-text {
        background-color: #e9ecef;
        border: 2px solid #e9ecef;
        border-radius: 12px 0 0 12px;
        padding: 16px 20px;
        font-weight: 600;
        color: #495057;
        font-size: 1.1rem;
    }

    .input-group .form-control {
        border-radius: 0 12px 12px 0;
        border-left: none;
    }

    .time-picker-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        pointer-events: none;
    }

    .section-divider {
        display: flex;
        align-items: center;
        margin: 40px 0;
        color: #6c757d;
    }

    .section-divider::before,
    .section-divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e9ecef;
    }

    .section-divider::before {
        margin-right: 15px;
    }

    .section-divider::after {
        margin-left: 15px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Tooltip for help text */
    .form-tooltip {
        position: relative;
        display: inline-block;
        margin-left: 8px;
        color: #6c757d;
    }

    .form-tooltip .tooltip-text {
        visibility: hidden;
        width: 200px;
        background-color: #343a40;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 8px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.8rem;
        font-weight: normal;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .form-tooltip .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #343a40 transparent transparent transparent;
    }

    .form-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .full-width {
            grid-column: span 1;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 15px;
        }
        
        .btn-action {
            width: 100%;
            min-width: 100%;
        }

        .checkboxes-container {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-header h2 {
            font-size: 1.8rem;
        }
        
        .form-icon {
            display: none;
        }
        
        .image-upload-container {
            padding: 30px 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid page-create-dichvu">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="create-form-container animate__animated animate__fadeIn">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <h2>Thêm Dịch Vụ Mới</h2>
                <p>Điền đầy đủ thông tin dịch vụ để thêm vào hệ thống</p>
            </div>
            
            <form method="POST" action="{{ route('admin.dichvu.store') }}" enctype="multipart/form-data" id="createServiceForm">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="MaDV" class="required-label">Mã dịch vụ</label>
                        <input id="MaDV" type="number" class="form-control @error('MaDV') is-invalid @enderror" name="MaDV" value="{{ old('MaDV', $nextId ?? '') }}" required placeholder="Nhập mã dịch vụ">
                        @error('MaDV')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Mã dịch vụ phải là duy nhất trong hệ thống</small>
                    </div>

                    <div class="form-group">
                        <label for="Tendichvu" class="required-label">Tên dịch vụ</label>
                        <input id="Tendichvu" type="text" class="form-control @error('Tendichvu') is-invalid @enderror" name="Tendichvu" value="{{ old('Tendichvu') }}" required placeholder="Nhập tên dịch vụ">
                        @error('Tendichvu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="Gia" class="required-label">Giá dịch vụ</label>
                        <div class="input-group">
                            <span class="input-group-text">VNĐ</span>
                            <input id="Gia" type="number" step="1000" class="form-control @error('Gia') is-invalid @enderror" name="Gia" value="{{ old('Gia') }}" required placeholder="Nhập giá dịch vụ">
                        </div>
                        @error('Gia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Thoigian" class="required-label">Thời Gian Thực Hiện</label>
                        <div style="position: relative;">
                            <input id="Thoigian" type="text" class="form-control timepicker @error('Thoigian') is-invalid @enderror" name="Thoigian" value="{{ old('Thoigian') }}" required placeholder="HH:MM">
                            <i class="fas fa-clock time-picker-icon"></i>
                        </div>
                        @error('Thoigian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Thời gian dự kiến để hoàn thành dịch vụ</small>
                    </div>
                </div>

                <div class="section-divider">Thông tin chi tiết</div>

                <div class="form-group">
                    <label for="MoTa">Mô Tả Dịch Vụ</label>
                    <textarea id="MoTa" class="form-control @error('MoTa') is-invalid @enderror" name="MoTa" rows="6" placeholder="Nhập mô tả chi tiết về dịch vụ...">{{ old('MoTa') }}</textarea>
                    @error('MoTa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image_upload">Hình Ảnh Dịch Vụ</label>
                    <div class="image-upload-container" id="image_upload_container">
                        <i class="fas fa-cloud-upload-alt image-upload-icon"></i>
                        <p>Nhấp để chọn hoặc kéo thả hình ảnh vào đây</p>
                        <span class="upload-hints">Định dạng hỗ trợ: JPEG, PNG, JPG, GIF • Tối đa 2MB</span>
                        <input type="file" id="image_upload" name="image_upload" class="d-none @error('image_upload') is-invalid @enderror" accept="image/*">
                        <div id="image_preview_container" class="mt-3 d-none">
                            <img id="image_preview" class="image-preview">
                        </div>
                    </div>
                    @error('image_upload')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="section-divider">Lịch trình & Tùy chọn</div>

                <div class="form-group">
                    <label>Ngày Có Sẵn</label>
                    <div class="checkboxes-container">
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_mon" name="available_days[]" value="monday" {{ in_array('monday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_mon">Thứ 2</label>
                        </div>
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_tue" name="available_days[]" value="tuesday" {{ in_array('tuesday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_tue">Thứ 3</label>
                        </div>
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_wed" name="available_days[]" value="wednesday" {{ in_array('wednesday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_wed">Thứ 4</label>
                        </div>
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_thu" name="available_days[]" value="thursday" {{ in_array('thursday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_thu">Thứ 5</label>
                        </div>
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_fri" name="available_days[]" value="friday" {{ in_array('friday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_fri">Thứ 6</label>
                        </div>
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_sat" name="available_days[]" value="saturday" {{ in_array('saturday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_sat">Thứ 7</label>
                        </div>
                        <div class="custom-checkbox">
                            <input type="checkbox" id="day_sun" name="available_days[]" value="sunday" {{ in_array('sunday', old('available_days', [])) ? 'checked' : '' }}>
                            <label for="day_sun">CN</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-checkbox">
                        <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                        <label for="featured">Đánh dấu là dịch vụ nổi bật</label>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.dichvu.index') }}" class="btn btn-action btn-secondary-light">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                    <div class="form-spacer"></div>
                    <button type="submit" class="btn btn-action btn-primary-pink">
                        <i class="fas fa-save"></i> Lưu Dịch Vụ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize time picker
        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 15
        });

        // Image upload preview
        const imageUploadContainer = document.getElementById('image_upload_container');
        const imageUploadInput = document.getElementById('image_upload');
        const imagePreview = document.getElementById('image_preview');
        const imagePreviewContainer = document.getElementById('image_preview_container');

        imageUploadContainer.addEventListener('click', function() {
            imageUploadInput.click();
        });

        imageUploadInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('d-none');
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imageUploadContainer.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            imageUploadContainer.addEventListener(eventName, function() {
                this.classList.add('drag-active');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            imageUploadContainer.addEventListener(eventName, function() {
                this.classList.remove('drag-active');
            }, false);
        });

        imageUploadContainer.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files && files[0]) {
                imageUploadInput.files = files;
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('d-none');
                }
                
                reader.readAsDataURL(files[0]);
            }
        }, false);

        // Form validation
        const form = document.getElementById('createServiceForm');
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validate MaDV
            const maDV = document.getElementById('MaDV');
            if (!maDV.value.trim()) {
                isValid = false;
                maDV.classList.add('is-invalid');
                if (!maDV.nextElementSibling || !maDV.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    errorDiv.textContent = 'Mã dịch vụ không được để trống.';
                    maDV.parentNode.insertBefore(errorDiv, maDV.nextSibling);
                }
            } else {
                maDV.classList.remove('is-invalid');
            }

            // Validate Tendichvu
            const tenDV = document.getElementById('Tendichvu');
            if (!tenDV.value.trim()) {
                isValid = false;
                tenDV.classList.add('is-invalid');
                if (!tenDV.nextElementSibling || !tenDV.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    errorDiv.textContent = 'Tên dịch vụ không được để trống.';
                    tenDV.parentNode.insertBefore(errorDiv, tenDV.nextSibling);
                }
            } else {
                tenDV.classList.remove('is-invalid');
            }

            // Validate Gia
            const gia = document.getElementById('Gia');
            if (!gia.value.trim() || gia.value < 0) {
                isValid = false;
                gia.classList.add('is-invalid');
                if (!gia.nextElementSibling || !gia.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('invalid-feedback');
                    errorDiv.textContent = 'Giá phải là số dương.';
                    gia.parentNode.insertBefore(errorDiv, gia.nextSibling);
                }
            } else {
                gia.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
