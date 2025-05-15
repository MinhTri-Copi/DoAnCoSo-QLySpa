@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Dịch Vụ')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .delete-confirmation-page {
        padding: 2rem;
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-card {
        background-color: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        transform-style: preserve-3d;
        transition: all 0.5s ease;
    }

    .delete-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .delete-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
        padding: 2.5rem 2rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .delete-header::before {
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

    .delete-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .delete-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }

    .delete-icon {
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        position: relative;
        z-index: 2;
        box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
    }

    .delete-icon i {
        font-size: 3rem;
        color: #ff6b6b;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .delete-body {
        padding: 2.5rem;
    }

    .service-card {
        background: #f9f9f9;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 1.5rem;
        border: 1px solid #eee;
        position: relative;
        overflow: hidden;
    }

    .service-image {
        width: 120px;
        height: 120px;
        overflow: hidden;
        border-radius: 12px;
        position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-card:hover .service-image img {
        transform: scale(1.1);
    }

    .no-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f1 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #b2bac5;
    }

    .no-image i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .no-image span {
        font-size: 0.8rem;
        text-align: center;
    }

    .service-details {
        display: flex;
        flex-direction: column;
    }

    .service-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .service-id {
        display: inline-block;
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        margin-left: 0.8rem;
    }

    .service-price {
        font-size: 1.25rem;
        font-weight: 600;
        color: #ff6b6b;
        margin: 0.5rem 0 1rem;
        display: inline-flex;
        align-items: center;
    }

    .service-price::before {
        content: "💰";
        margin-right: 0.5rem;
    }

    .usage-warning {
        display: flex;
        align-items: center;
        background: #fff8e1;
        border-left: 4px solid #ffc107;
        color: #856404;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 0.5rem;
        animation: fadeIn 0.5s ease;
    }

    .usage-warning i {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: #ffc107;
    }

    .warning-message {
        background-color: #fff5f5;
        border-left: 4px solid #ff6b6b;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }

    .warning-message::before {
        content: "!";
        position: absolute;
        right: -15px;
        top: -15px;
        font-size: 8rem;
        font-weight: bold;
        color: rgba(255, 107, 107, 0.1);
        line-height: 1;
    }

    .warning-message h4 {
        color: #e74c3c;
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
    }

    .warning-message h4 i {
        margin-right: 0.8rem;
    }

    .warning-message p {
        color: #6c757d;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .delete-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-cancel {
        flex: 1;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        color: #495057;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel:hover {
        background-color: #e9ecef;
        color: #212529;
        transform: translateY(-3px);
    }

    .btn-cancel i {
        margin-right: 0.5rem;
    }

    .btn-delete-confirm {
        flex: 1;
        padding: 1rem 1.5rem;
        background-color: #ff6b6b;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete-confirm:hover {
        background-color: #ff5252;
        box-shadow: 0 10px 20px rgba(255, 82, 82, 0.3);
        transform: translateY(-3px);
    }

    .btn-delete-confirm i {
        margin-right: 0.5rem;
    }

    .btn-delete-disabled {
        flex: 1;
        padding: 1rem 1.5rem;
        background-color: #e9ecef;
        color: #adb5bd;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: not-allowed;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete-disabled i {
        margin-right: 0.5rem;
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

    @media (max-width: 768px) {
        .service-card {
            grid-template-columns: 1fr;
        }
        
        .service-image {
            width: 100%;
            height: 180px;
            margin-bottom: 1rem;
        }
        
        .delete-actions {
            flex-direction: column;
        }

        .delete-header h2 {
            font-size: 1.7rem;
        }
    }
</style>
@endsection

@section('content')
<div class="delete-confirmation-page">
    <div class="delete-card animate__animated animate__fadeIn">
        <div class="delete-header">
            <div class="delete-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h2>Xác Nhận Xóa Dịch Vụ</h2>
            <p>Bạn có chắc chắn muốn xóa dịch vụ này không?</p>
        </div>
        
        <div class="delete-body">
            <div class="service-card">
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
                    <h3 class="service-name">
                        {{ $dichVu->Tendichvu }}
                        <span class="service-id">Mã: {{ $dichVu->MaDV }}</span>
                    </h3>
                    <div class="service-price">{{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ</div>
                    
                    @if($usageCount > 0)
                        <div class="usage-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Dịch vụ này đang được sử dụng trong <strong>{{ $usageCount }}</strong> lịch đặt</span>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($usageCount > 0)
                <div class="warning-message">
                    <h4><i class="fas fa-info-circle"></i>Không thể xóa dịch vụ</h4>
                    <p>Dịch vụ này đang được sử dụng trong các lịch đặt hiện tại. Bạn không thể xóa cho đến khi tất cả các lịch đặt liên quan được hoàn thành hoặc hủy bỏ.</p>
                </div>
            @else
                <div class="warning-message">
                    <h4><i class="fas fa-exclamation-triangle"></i>Cảnh báo</h4>
                    <p>Việc xóa dịch vụ là không thể khôi phục. Tất cả thông tin liên quan đến dịch vụ này sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
                </div>
            @endif
            
            <div class="delete-actions">
                <a href="{{ route('admin.dichvu.index') }}" class="btn-cancel">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
                
                @if($usageCount > 0)
                    <button class="btn-delete-disabled" disabled>
                        <i class="fas fa-trash-alt"></i>
                        <span>Không thể xóa</span>
                    </button>
                @else
                    <form action="{{ route('admin.dichvu.destroy', $dichVu->MaDV) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-confirm">
                            <i class="fas fa-trash-alt"></i>
                            <span>Xác nhận xóa</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection