@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Dịch Vụ')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .delete-confirmation-page {
        background-color: #f8f9fa;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 0;
    }

    .delete-confirmation-container {
        max-width: 600px;
        width: 100%;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .delete-header {
        background: linear-gradient(120deg, #ff9a9e 0%, #fad0c4 100%);
        padding: 25px;
        color: white;
        text-align: center;
        position: relative;
    }

    .delete-icon {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: #ff6b6b;
        font-size: 2.5rem;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
    }

    .delete-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .delete-subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .delete-body {
        padding: 30px;
    }

    .service-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .service-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .service-id {
        background: #e9ecef;
        color: #495057;
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 20px;
        margin-left: 10px;
    }

    .service-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .detail-value {
        font-weight: 600;
        color: #343a40;
    }

    .warning-message {
        background-color: #fff5f5;
        border-left: 4px solid #ff6b6b;
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 5px;
    }

    .warning-message h4 {
        color: #e74c3c;
        font-size: 1.1rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .warning-message h4 i {
        margin-right: 8px;
    }

    .warning-message p {
        color: #6c757d;
        margin-bottom: 0;
    }

    .delete-actions {
        display: flex;
        gap: 15px;
    }

    .btn-action {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        text-align: center;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-danger {
        background-color: #ff6b6b;
        color: white;
    }

    .btn-danger:hover {
        background-color: #ff5252;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        transform: translateY(-2px);
    }

    .btn-light {
        background-color: #e9ecef;
        color: #495057;
    }

    .btn-light:hover {
        background-color: #dee2e6;
        transform: translateY(-2px);
    }

    .usage-warning {
        display: flex;
        align-items: center;
        background: #fff3cd;
        color: #856404;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .usage-warning i {
        font-size: 1.5rem;
        margin-right: 15px;
    }

    @media (max-width: 768px) {
        .service-details {
            grid-template-columns: 1fr;
        }
        
        .delete-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid page-destroy-dichvu" style="max-width: 1600px; margin: 0 auto; padding: 0 20px;">
    <div class="confirm-delete-container animate__animated animate__fadeIn">
        <div class="confirm-header">
            <h2>Xác Nhận Xóa Dịch Vụ</h2>
            <p>Bạn có chắc chắn muốn xóa dịch vụ này không?</p>
        </div>
        
        <div class="service-info">
            <div class="service-image">
                @if($dichVu->Image)
                    <img src="{{ asset($dichVu->Image) }}" alt="{{ $dichVu->Tendichvu }}">
                @else
                    <div class="no-image">
                        <i class="fas fa-spa"></i>
                        <span>Không có hình ảnh</span>
                    </div>
                @endif
            </div>
            
            <div class="service-details">
                <h3 class="service-name">{{ $dichVu->Tendichvu }}</h3>
                <div class="service-id">Mã dịch vụ: {{ $dichVu->MaDV }}</div>
                <div class="service-price">Giá: {{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ</div>
                
                @if($usageCount > 0)
                    <div class="usage-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Dịch vụ này đang được sử dụng trong {{ $usageCount }} lịch đặt</span>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="delete-actions">
            <a href="{{ route('admin.dichvu.index') }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại</span>
            </a>
            
            @if($usageCount > 0)
                <button class="btn-delete-disabled" disabled>
                    <i class="fas fa-trash"></i>
                    <span>Không thể xóa</span>
                </button>
            @else
                <form action="{{ route('admin.dichvu.destroy', $dichVu->MaDV) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-confirm">
                        <i class="fas fa-trash"></i>
                        <span>Xác nhận xóa</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection