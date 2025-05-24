@extends('customer.layouts.app')

@section('title', 'Chỉnh sửa phiếu hỗ trợ')

@section('content')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-dark: #e55a8a;
        --warning-color: #f59e0b;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .edit-container {
        background: var(--bg-gradient);
        min-height: 100vh;
        padding: 1.5rem 0;
    }

    .breadcrumb-nav {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
    }

    .breadcrumb-nav a {
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s;
    }

    .breadcrumb-nav a:hover {
        color: var(--primary-color);
    }

    .page-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--warning-color) 0%, #d97706 100%);
    }

    .header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
    }

    .warning-alert {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid var(--warning-color);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .warning-icon {
        color: var(--warning-color);
        font-size: 1.125rem;
        margin-top: 0.125rem;
        flex-shrink: 0;
    }

    .warning-content h4 {
        color: #92400e;
        font-weight: 600;
        font-size: 0.875rem;
        margin: 0 0 0.5rem 0;
    }

    .warning-content p {
        color: #92400e;
        font-size: 0.875rem;
        margin: 0;
        line-height: 1.5;
    }

    .form-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-label i {
        color: var(--primary-color);
        font-size: 0.875rem;
    }

    .required {
        color: #ef4444;
        font-weight: 700;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ff6b9d' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
    }

    .textarea-wrapper {
        position: relative;
    }

    .char-counter {
        position: absolute;
        bottom: 0.75rem;
        right: 0.75rem;
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fef2f2;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid #fecaca;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: center;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 140px;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        color: #374151;
        text-decoration: none;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #10b981;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    @media (max-width: 768px) {
        .edit-container {
            padding: 1rem 0;
        }
        
        .form-container {
            margin: 0 1rem;
            padding: 1.5rem;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn {
            min-width: auto;
        }
    }
</style>

<div class="edit-container">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaphieuHT) }}">
                <i class="fas fa-arrow-left"></i>
                Quay lại chi tiết phiếu #{{ $phieuHoTro->MaphieuHT }}
            </a>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="header-icon">
                <i class="fas fa-edit"></i>
            </div>
            <h1 class="page-title">Chỉnh sửa phiếu hỗ trợ</h1>
            <p class="page-subtitle">Cập nhật thông tin yêu cầu hỗ trợ của bạn</p>
        </div>

        <!-- Alerts -->
        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Warning Alert -->
        <div class="warning-alert">
            <i class="fas fa-exclamation-triangle warning-icon"></i>
            <div class="warning-content">
                <h4>Lưu ý quan trọng</h4>
                <p>Chỉ có thể chỉnh sửa phiếu hỗ trợ khi đang ở trạng thái "Đang xử lý". Sau khi phiếu đã được tiếp nhận xử lý, bạn không thể chỉnh sửa nội dung.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form action="{{ route('customer.phieuhotro.update', $phieuHoTro->MaphieuHT) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')
                
                <!-- Support Method -->
                <div class="form-group">
                    <label for="MaPTHT" class="form-label">
                        <i class="fas fa-cogs"></i>
                        Phương thức hỗ trợ 
                        <span class="required">*</span>
                    </label>
                    <select class="form-select @error('MaPTHT') is-invalid @enderror" id="MaPTHT" name="MaPTHT" required>
                        <option value="">-- Chọn phương thức hỗ trợ --</option>
                        @foreach($phuongThucHoTro as $pt)
                            <option value="{{ $pt->MaPTHT }}" {{ old('MaPTHT', $phieuHoTro->MaPTHT) == $pt->MaPTHT ? 'selected' : '' }}>
                                {{ $pt->TenPT }}
                            </option>
                        @endforeach
                    </select>
                    @error('MaPTHT')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Content -->
                <div class="form-group">
                    <label for="Noidungyeucau" class="form-label">
                        <i class="fas fa-edit"></i>
                        Nội dung yêu cầu 
                        <span class="required">*</span>
                    </label>
                    <div class="textarea-wrapper">
                        <textarea 
                            class="form-control @error('Noidungyeucau') is-invalid @enderror" 
                            id="Noidungyeucau" 
                            name="Noidungyeucau" 
                            rows="8" 
                            required
                            placeholder="Vui lòng mô tả chi tiết vấn đề của bạn..."
                            style="resize: vertical; min-height: 200px; padding-bottom: 2.5rem;"
                            oninput="updateCounter(this)"
                        >{{ old('Noidungyeucau', $phieuHoTro->Noidungyeucau) }}</textarea>
                        <div class="char-counter" id="charCounter">
                            <span id="charCount">{{ strlen(old('Noidungyeucau', $phieuHoTro->Noidungyeucau)) }}</span> ký tự
                        </div>
                    </div>
                    @error('Noidungyeucau')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Action Buttons -->
                <div class="btn-group">
                    <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaphieuHT) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateCounter(textarea) {
    const charCount = textarea.value.length;
    document.getElementById('charCount').textContent = charCount;
}

// Initialize counter on page load
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('Noidungyeucau');
    if (textarea) {
        updateCounter(textarea);
    }
});
</script>
@endsection
