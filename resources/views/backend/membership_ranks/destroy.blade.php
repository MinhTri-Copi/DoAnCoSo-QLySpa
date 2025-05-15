@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Hạng Thành Viên')

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
        display: flex;
        align-items: center;
    }

    .confirm-item-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 20px;
        margin-right: 15px;
    }

    .confirm-item-content {
        flex: 1;
    }

    .confirm-item-title {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 5px;
    }

    .confirm-item-subtitle {
        color: #6c757d;
        font-size: 14px;
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
            Bạn có chắc chắn muốn xóa hạng thành viên này? Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn và không thể khôi phục.
        </div>
        
        <div class="confirm-item">
            <div class="confirm-item-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="confirm-item-content">
                <div class="confirm-item-title">{{ $rank->Tenhang }}</div>
                <div class="confirm-item-subtitle">Mã hạng: {{ $rank->Mahang }}</div>
            </div>
        </div>
        
        <form action="{{ route('admin.membership_ranks.destroy', $rank->Mahang) }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="confirm-actions">
                <a href="{{ route('admin.membership_ranks.index') }}" class="btn btn-secondary">
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