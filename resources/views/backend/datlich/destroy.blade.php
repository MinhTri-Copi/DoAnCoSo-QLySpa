@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Lịch Đặt')

@section('content')
<style>
    :root {
        --primary-color: #ff6b8b;
        --primary-light: #ffd0d9;
        --primary-dark: #e84e6f;
        --text-on-primary: #ffffff;
        --secondary-color: #f8f9fa;
        --border-color: #e9ecef;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
    }

    .confirm-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .confirm-header {
        background-color: var(--danger-color);
        padding: 20px;
        color: white;
        text-align: center;
    }

    .confirm-icon {
        font-size: 48px;
        margin-bottom: 10px;
    }

    .confirm-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .confirm-subtitle {
        font-size: 14px;
        opacity: 0.9;
    }

    .confirm-body {
        padding: 30px;
    }

    .confirm-message {
        text-align: center;
        margin-bottom: 30px;
        color: #495057;
        font-size: 16px;
        line-height: 1.6;
    }

    .confirm-item {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .confirm-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .confirm-item-title {
        font-weight: 600;
        color: #343a40;
        font-size: 18px;
    }

    .confirm-item-badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-confirmed {
        background-color: #28a745;
        color: white;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: white;
    }

    .badge-completed {
        background-color: #17a2b8;
        color: white;
    }

    .confirm-item-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .confirm-item-detail {
        display: flex;
        align-items: center;
    }

    .confirm-item-detail i {
        color: var(--primary-color);
        margin-right: 8px;
        width: 16px;
    }

    .confirm-actions {
        display: flex;
        gap: 15px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .confirm-actions {
            flex-direction: column;
        }
        
        .confirm-item-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="confirm-container">
    <div class="confirm-header">
        <div class="confirm-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="confirm-title">Xác Nhận Xóa</div>
        <div class="confirm-subtitle">Hành động này không thể hoàn tác</div>
    </div>
    
    <div class="confirm-body">
        <div class="confirm-message">
            Bạn có chắc chắn muốn xóa lịch đặt này? Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn và không thể khôi phục.
        </div>
        
        <div class="confirm-item">
            <div class="confirm-item-header">
                <div class="confirm-item-title">Lịch Đặt #{{ $datLich->MaDL }}</div>
                @php
                    $statusClass = 'badge-pending';
                    if($datLich->Trangthai_ == 'Đã xác nhận') {
                        $statusClass = 'badge-confirmed';
                    } elseif($datLich->Trangthai_ == 'Đã hủy') {
                        $statusClass = 'badge-cancelled';
                    } elseif($datLich->Trangthai_ == 'Hoàn thành') {
                        $statusClass = 'badge-completed';
                    }
                @endphp
                <span class="confirm-item-badge {{ $statusClass }}">{{ $datLich->Trangthai_ }}</span>
            </div>
            
            <div class="confirm-item-details">
                <div class="confirm-item-detail">
                    <i class="fas fa-user"></i>
                    <span>{{ $datLich->user->Hoten ?? 'N/A' }}</span>
                </div>
                <div class="confirm-item-detail">
                    <i class="fas fa-spa"></i>
                    <span>{{ $datLich->dichVu->Tendichvu ?? 'N/A' }}</span>
                </div>
                <div class="confirm-item-detail">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('d/m/Y') }}</span>
                </div>
                <div class="confirm-item-detail">
                    <i class="fas fa-clock"></i>
                    <span>{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('H:i') }}</span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.datlich.destroy', $datLich->MaDL) }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="confirm-actions">
                <a href="{{ route('admin.datlich.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Xác Nhận Xóa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection