@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Phiếu Hỗ Trợ')

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
        --dark-red: #c0392b;
        --white: #ffffff;
        --light-gray: #f7f9fc;
        --border-color: #e6e9ed;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    .confirm-delete-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .confirm-delete-header {
        padding: 1.5rem;
        background: var(--pink-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .confirm-delete-header::before {
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

    .confirm-delete-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .confirm-delete-title i {
        color: rgba(255, 255, 255, 0.8);
    }

    .confirm-delete-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }

    .confirm-delete-body {
        padding: 2rem;
    }

    .confirm-delete-message {
        font-size: 1.1rem;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .confirm-delete-alert {
        background-color: rgba(231, 76, 60, 0.1);
        border-left: 4px solid var(--red);
        color: var(--red);
        padding: 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .confirm-delete-info {
        background-color: var(--light-gray);
        border-radius: var(--radius-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        width: 140px;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .info-value {
        flex: 1;
        color: var(--text-primary);
        font-weight: 500;
    }

    .confirm-delete-footer {
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

    .btn-danger-support {
        background: var(--red);
        color: white;
    }

    .btn-danger-support:hover {
        background: var(--dark-red);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(231, 76, 60, 0.25);
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

    @media (max-width: 768px) {
        .confirm-delete-footer {
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
    <div class="confirm-delete-card">
        <div class="confirm-delete-header">
            <h1 class="confirm-delete-title">
                <i class="fas fa-trash-alt"></i>
                Xác Nhận Xóa Phiếu Hỗ Trợ
            </h1>
            <p class="confirm-delete-subtitle">
                Vui lòng xác nhận hành động xóa
            </p>
        </div>
        
        <div class="confirm-delete-body">
            <div class="confirm-delete-message">
                Bạn có chắc chắn muốn xóa phiếu hỗ trợ này không?
            </div>
            
            <div class="confirm-delete-alert">
                <i class="fas fa-exclamation-triangle"></i>
                Hành động này không thể hoàn tác!
            </div>
            
            <div class="confirm-delete-info">
                <div class="info-item">
                    <div class="info-label">Mã Phiếu Hỗ Trợ:</div>
                    <div class="info-value">{{ $phieuHoTro->MaphieuHT }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Nội Dung Yêu Cầu:</div>
                    <div class="info-value">{{ $phieuHoTro->Noidungyeucau }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Người Dùng:</div>
                    <div class="info-value">{{ $phieuHoTro->user->Hoten ?? 'N/A' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Trạng Thái:</div>
                    <div class="info-value">{{ $phieuHoTro->trangThai->Tentrangthai ?? 'N/A' }}</div>
                </div>
            </div>

            <form action="{{ route('admin.phieuhotro.destroy', $phieuHoTro->MaphieuHT) }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="confirm-delete-footer">
                    <button type="submit" class="btn-support btn-danger-support">
                        <i class="fas fa-trash-alt"></i>
                        Xóa Phiếu Hỗ Trợ
                    </button>
                    <a href="{{ route('admin.phieuhotro.index') }}" class="btn-support btn-secondary-support">
                        <i class="fas fa-times"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection