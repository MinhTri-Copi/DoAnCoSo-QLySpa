@extends('customer.layouts.app')

@section('title', 'Tạo phiếu hỗ trợ')

@section('content')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-dark: #e55a8a;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .create-container {
        background: var(--bg-gradient);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .create-header {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 6px 25px rgba(255, 107, 157, 0.3);
        position: relative;
        overflow: hidden;
    }

    .header-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .page-subtitle {
        font-size: 1.125rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 8px 30px rgba(255, 107, 157, 0.08);
        border: 1px solid rgba(255, 107, 157, 0.1);
        position: relative;
        overflow: hidden;
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .form-label i {
        color: var(--primary-color);
    }

    .required {
        color: #ef4444;
        font-weight: 700;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
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
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 3rem;
    }

    .textarea-wrapper {
        position: relative;
    }

    .char-counter {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #fef2f2;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid #fecaca;
    }

    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
    }

    .btn-secondary {
        background: transparent;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .btn-secondary:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        text-decoration: none;
    }

    .info-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .info-title {
        color: #0c4a6e;
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.75rem;
    }

    .info-list li {
        color: #075985;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .info-list li::before {
        content: "✓";
        color: #0ea5e9;
        font-weight: bold;
        font-size: 1rem;
        margin-top: 0.1rem;
    }

    .alert {
        border-radius: 16px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    @media (max-width: 768px) {
        .form-container {
            margin: 0 1rem;
            padding: 2rem 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
    }
</style>

<div class="create-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('customer.phieuhotro.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Quay lại
        </a>

        <!-- Header -->
        <div class="create-header">
            <div class="header-icon">
                <i class="fas fa-plus"></i>
            </div>
            <h1 class="page-title">Tạo phiếu hỗ trợ mới</h1>
            <p class="page-subtitle">Mô tả chi tiết vấn đề của bạn để chúng tôi có thể hỗ trợ tốt nhất</p>
            </div>

        <!-- Alerts -->
                    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                    @endif

        <!-- Form -->
        <div class="form-container">
            <form action="{{ route('customer.phieuhotro.store') }}" method="POST" id="createForm">
                        @csrf
                        
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
                            <option value="{{ $pt->MaPTHT }}" {{ old('MaPTHT') == $pt->MaPTHT ? 'selected' : '' }}>
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
                            rows="6" 
                            required
                            placeholder="Vui lòng mô tả chi tiết vấn đề của bạn..."
                            style="resize: vertical; min-height: 150px; padding-bottom: 3rem;"
                            oninput="updateCounter(this)"
                        >{{ old('Noidungyeucau') }}</textarea>
                        <div class="char-counter" id="charCounter">
                            <span id="charCount">{{ strlen(old('Noidungyeucau', '')) }}</span> ký tự
                        </div>
                    </div>
                    @error('Noidungyeucau')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle"></i>{{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Vui lòng mô tả chi tiết vấn đề của bạn để chúng tôi có thể hỗ trợ tốt nhất.
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Gửi phiếu hỗ trợ
                </button>
            </form>
            </div>
            
        <!-- Info Card -->
        <div class="info-card">
            <div class="info-title">
                <i class="fas fa-lightbulb"></i>
                Thông tin hữu ích
            </div>
            <ul class="info-list">
                <li>Phiếu hỗ trợ của bạn sẽ được xử lý trong vòng 24 giờ làm việc</li>
                <li>Bạn có thể theo dõi trạng thái phiếu hỗ trợ trong mục "Phiếu hỗ trợ"</li>
                <li>Đối với vấn đề cấp bách, vui lòng liên hệ trực tiếp qua hotline: <strong>(028) 1234 5678</strong></li>
                <li>Hãy cung cấp thông tin chi tiết để chúng tôi hỗ trợ bạn nhanh chóng</li>
            </ul>
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
