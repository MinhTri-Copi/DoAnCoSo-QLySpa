@extends('backend.layouts.app')

@section('title', 'Sửa Dịch Vụ')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .page-edit-dichvu {
        background-color: #f8f9fa;
    }

    .edit-form-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 50px;
    }

    .form-header {
        background: linear-gradient(120deg, #ff9a9e 0%, #fad0c4 100%);
        margin: -30px -30px 30px;
        padding: 25px 30px;
        border-radius: 15px 15px 0 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .form-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 2;
    }

    .form-header p {
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 2;
    }

    .form-header::after {
        content: "";
        position: absolute;
        right: -20px;
        bottom: -50px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    }

    .form-header::before {
        content: "";
        position: absolute;
        left: -50px;
        top: -50px;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 1;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .full-width {
        grid-column: span 2;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e1e5eb;
        transition: all 0.3s;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ff9a9e;
        box-shadow: 0 0 0 3px rgba(255, 154, 158, 0.25);
        outline: none;
    }

    .form-control::placeholder {
        color: #adb5bd;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .form-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .required-label::after {
        content: "*";
        color: #dc3545;
        margin-left: 4px;
    }

    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .image-upload-container:hover {
        border-color: #ff9a9e;
        background-color: #fff9f9;
    }

    .image-upload-icon {
        font-size: 2.5rem;
        color: #adb5bd;
        margin-bottom: 10px;
    }

    .image-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 10px;
        margin-top: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .current-image-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 10px;
        margin-top: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .checkboxes-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
    }

    .custom-checkbox input[type="checkbox"] {
        margin-right: 8px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }

    .btn-primary-pink {
        background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 100%);
        color: white;
    }

    .btn-primary-pink:hover {
        box-shadow: 0 5px 15px rgba(255, 154, 158, 0.3);
        transform: translateY(-2px);
    }

    .btn-secondary-light {
        background: #e9ecef;
        color: #495057;
    }

    .btn-secondary-light:hover {
        background: #dee2e6;
    }

    .btn-danger-light {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-danger-light:hover {
        background: #f5c6cb;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #e1e5eb;
        border-radius: 10px 0 0 10px;
        padding: 12px 15px;
        font-weight: 600;
        color: #495057;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
        border-left: 0;
    }

    .input-slider {
        margin-top: 10px;
    }

    .time-picker-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        pointer-events: none;
    }

    .service-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.2);
        color: #856404;
    }

    .status-processing {
        background-color: rgba(52, 152, 219, 0.2);
        color: #204d74;
    }

    .status-completed {
        background-color: rgba(46, 204, 113, 0.2);
        color: #186a3b;
    }

    .status-cancelled {
        background-color: rgba(231, 76, 60, 0.2);
        color: #a93226;
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
            flex-direction: column-reverse;
            gap: 15px;
        }
        
        .btn-action {
            width: 100%;
        }

        .checkboxes-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid page-edit-dichvu" style="max-width: 1600px; margin: 0 auto; padding: 0 20px;">
    <div class="edit-form-container animate__animated animate__fadeIn">
        <div class="form-header">
            <h2>Chỉnh Sửa Dịch Vụ</h2>
            <p>Cập nhật thông tin dịch vụ #{{ $dichVu->MaDV }} - {{ $dichVu->Tendichvu }}</p>
        </div>
        
        <form method="POST" action="{{ route('admin.dichvu.update', $dichVu->MaDV) }}" enctype="multipart/form-data" id="editServiceForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="MaDV" class="required-label">Mã Dịch Vụ</label>
                    <input id="MaDV" type="number" class="form-control @error('MaDV') is-invalid @enderror" name="MaDV" value="{{ old('MaDV', $dichVu->MaDV) }}" required>
                    @error('MaDV')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Mã dịch vụ phải là duy nhất trong hệ thống</small>
                </div>

                <div class="form-group">
                    <label for="Tendichvu" class="required-label">Tên Dịch Vụ</label>
                    <input id="Tendichvu" type="text" class="form-control @error('Tendichvu') is-invalid @enderror" name="Tendichvu" value="{{ old('Tendichvu', $dichVu->Tendichvu) }}" required>
                    @error('Tendichvu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Gia" class="required-label">Giá</label>
                    <div class="input-group">
                        <span class="input-group-text">VNĐ</span>
                        <input id="Gia" type="number" step="1000" class="form-control @error('Gia') is-invalid @enderror" name="Gia" value="{{ old('Gia', $dichVu->Gia) }}" required>
                    </div>
                    @error('Gia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Thoigian" class="required-label">Thời Gian Thực Hiện</label>
                    <div style="position: relative;">
                        <input id="Thoigian" type="text" class="form-control timepicker @error('Thoigian') is-invalid @enderror" name="Thoigian" value="{{ old('Thoigian', $dichVu->Thoigian ? $dichVu->Thoigian->format('H:i') : '') }}" required placeholder="HH:mm">
                        <i class="fas fa-clock time-picker-icon"></i>
                    </div>
                    @error('Thoigian')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="MoTa">Mô Tả</label>
                <textarea id="MoTa" class="form-control @error('MoTa') is-invalid @enderror" name="MoTa" rows="4">{{ old('MoTa', $dichVu->MoTa) }}</textarea>
                @error('MoTa')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image_upload">Hình Ảnh</label>
                @if($dichVu->Image && file_exists(public_path($dichVu->Image)))
                    <div class="text-center mb-3">
                        <p class="mb-2">Hình ảnh hiện tại:</p>
                        <img src="{{ asset($dichVu->Image) }}" alt="{{ $dichVu->Tendichvu }}" class="current-image-preview">
                        <input type="hidden" name="current_image" value="{{ $dichVu->Image }}">
                    </div>
                @endif

                <div class="image-upload-container" id="image_upload_container">
                    <i class="fas fa-cloud-upload-alt image-upload-icon"></i>
                    <p>Nhấp để chọn hoặc kéo thả hình ảnh mới vào đây</p>
                    <input type="file" id="image_upload" name="image_upload" class="d-none @error('image_upload') is-invalid @enderror" accept="image/*">
                    <div id="image_preview_container" class="mt-3 d-none">
                        <img id="image_preview" class="image-preview">
                    </div>
                </div>
                @error('image_upload')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Định dạng hỗ trợ: JPEG, PNG, JPG, GIF. Tối đa 2MB.</small>
            </div>

            <div class="form-group">
                <label>Ngày Có Sẵn</label>
                @php
                    $availableDays = json_decode($dichVu->available_days ?? '[]', true);
                @endphp
                <div class="checkboxes-container">
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_mon" name="available_days[]" value="monday" {{ in_array('monday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_mon">Thứ 2</label>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_tue" name="available_days[]" value="tuesday" {{ in_array('tuesday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_tue">Thứ 3</label>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_wed" name="available_days[]" value="wednesday" {{ in_array('wednesday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_wed">Thứ 4</label>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_thu" name="available_days[]" value="thursday" {{ in_array('thursday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_thu">Thứ 5</label>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_fri" name="available_days[]" value="friday" {{ in_array('friday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_fri">Thứ 6</label>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_sat" name="available_days[]" value="saturday" {{ in_array('saturday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_sat">Thứ 7</label>
                    </div>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="day_sun" name="available_days[]" value="sunday" {{ in_array('sunday', old('available_days', $availableDays)) ? 'checked' : '' }}>
                        <label for="day_sun">CN</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-checkbox">
                    <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $dichVu->featured ?? false) ? 'checked' : '' }}>
                    <label for="featured">Đánh dấu là dịch vụ nổi bật</label>
                </div>
            </div>

            <div class="form-actions">
                <div class="left-actions">
                    <a href="{{ route('admin.dichvu.index') }}" class="btn btn-action btn-secondary-light">
                        <i class="fas fa-arrow-left me-2"></i>Quay Lại
                    </a>
                    <a href="{{ route('admin.dichvu.confirm-destroy', $dichVu->MaDV) }}" class="btn btn-action btn-danger-light ms-2">
                        <i class="fas fa-trash me-2"></i>Xóa
                    </a>
                </div>
                <button type="submit" class="btn btn-action btn-primary-pink">
                    <i class="fas fa-save me-2"></i>Cập Nhật Dịch Vụ
                </button>
            </div>
        </form>
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
                this.classList.add('border-primary');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            imageUploadContainer.addEventListener(eventName, function() {
                this.classList.remove('border-primary');
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
        const form = document.getElementById('editServiceForm');
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