@extends('backend.layouts.app')

@section('title', 'Thêm Phiếu Hỗ Trợ')

@section('styles')
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
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    .support-form-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .support-form-header {
        padding: 1.5rem;
        background: var(--pink-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .support-form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
    }

    .support-form-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .support-form-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }

    .support-form-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.95rem;
        transition: var(--transition);
        color: var(--text-primary);
        background-color: var(--light-gray);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .form-control:disabled, 
    .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-select {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.95rem;
        transition: var(--transition);
        color: var(--text-primary);
        background-color: var(--light-gray);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ff6b95' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .is-invalid {
        border-color: var(--red) !important;
    }

    .invalid-feedback {
        color: var(--red);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .support-form-footer {
        padding: 1.5rem 2rem;
        background: var(--light-gray);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-top: 1px solid var(--border-color);
    }

    .btn-support {
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-support i {
        font-size: 0.9rem;
    }

    .btn-primary-support {
        background: var(--primary-pink);
        color: white;
    }

    .btn-primary-support:hover {
        background: var(--dark-pink);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 107, 149, 0.25);
    }

    .btn-secondary-support {
        background: white;
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary-support:hover {
        background: var(--light-gray);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    }

    .form-required::after {
        content: '*';
        color: var(--red);
        margin-left: 4px;
    }

    @media (max-width: 768px) {
        .support-form-footer {
            flex-direction: column;
        }
        
        .btn-support {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="support-form-card">
        <div class="support-form-header">
            <h1 class="support-form-title">Thêm Phiếu Hỗ Trợ Mới</h1>
            <p class="support-form-subtitle">
                <i class="fas fa-plus-circle"></i>
                Vui lòng điền đầy đủ thông tin bên dưới
            </p>
        </div>
        
        <form method="POST" action="{{ route('admin.phieuhotro.store') }}" class="support-form-body">
            @csrf
            <div class="form-group">
                <label for="MaphieuHT" class="form-label">Mã Phiếu Hỗ Trợ</label>
                <input id="MaphieuHT" type="number" class="form-control @error('MaphieuHT') is-invalid @enderror" name="MaphieuHT" value="{{ $suggestedMaphieuHT }}" required readonly>
                @error('MaphieuHT')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Mã phiếu được tạo tự động</small>
            </div>

            <div class="form-group">
                <label for="Noidungyeucau" class="form-label form-required">Nội Dung Yêu Cầu</label>
                <textarea id="Noidungyeucau" class="form-control @error('Noidungyeucau') is-invalid @enderror" name="Noidungyeucau" required>{{ old('Noidungyeucau') }}</textarea>
                @error('Noidungyeucau')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Mô tả chi tiết yêu cầu hỗ trợ</small>
            </div>

            <div class="form-group">
                <label for="Matrangthai" class="form-label form-required">Trạng Thái</label>
                <select id="Matrangthai" class="form-select @error('Matrangthai') is-invalid @enderror" name="Matrangthai" required>
                    <option value="">Chọn trạng thái</option>
                    @foreach ($trangThais as $trangThai)
                        <option value="{{ $trangThai->Matrangthai }}" {{ old('Matrangthai') == $trangThai->Matrangthai ? 'selected' : '' }}>{{ $trangThai->Tentrangthai }}</option>
                    @endforeach
                </select>
                @error('Matrangthai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="Manguoidung" class="form-label form-required">Người Dùng</label>
                <select id="Manguoidung" class="form-select @error('Manguoidung') is-invalid @enderror" name="Manguoidung" required>
                    <option value="">Chọn người dùng</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>{{ $user->Hoten }}</option>
                    @endforeach
                </select>
                @error('Manguoidung')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="MaPTHT" class="form-label form-required">Phương Thức Hỗ Trợ</label>
                <select id="MaPTHT" class="form-select @error('MaPTHT') is-invalid @enderror" name="MaPTHT" required>
                    <option value="">Chọn phương thức hỗ trợ</option>
                    @foreach ($pthotros as $pthotro)
                        <option value="{{ $pthotro->MaPTHT }}" {{ old('MaPTHT') == $pthotro->MaPTHT ? 'selected' : '' }}>{{ $pthotro->TenPT }}</option>
                    @endforeach
                </select>
                @error('MaPTHT')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="support-form-footer">
                <button type="submit" class="btn-support btn-primary-support">
                    <i class="fas fa-save"></i>
                    Lưu Lại
                </button>
                <a href="{{ route('admin.phieuhotro.index') }}" class="btn-support btn-secondary-support">
                    <i class="fas fa-arrow-left"></i>
                    Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection