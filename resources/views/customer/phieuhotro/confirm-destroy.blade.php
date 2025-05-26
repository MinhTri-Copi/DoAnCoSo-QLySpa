@extends('customer.layouts.app')

@section('title', 'Xác nhận xóa phiếu hỗ trợ')

@section('content')
<style>
    :root {
        --danger-color: #ef4444;
        --danger-dark: #dc2626;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .confirm-container {
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
        color: var(--danger-color);
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
        background: linear-gradient(90deg, var(--danger-color) 0%, var(--danger-dark) 100%);
    }

    .danger-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.75rem;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }

    .page-subtitle {
        color: #991b1b;
        font-weight: 600;
        font-size: 1rem;
        background: rgba(239, 68, 68, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 12px;
        display: inline-block;
        border: 1px solid rgba(239, 68, 68, 0.2);
        margin: 0;
    }

    .ticket-preview {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .ticket-preview::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--danger-color) 0%, var(--danger-dark) 100%);
    }

    .ticket-header {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .ticket-id {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .danger-badge {
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .ticket-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #f3f4f6;
    }

    .detail-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .detail-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
    }

    .detail-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }

    .detail-value {
        color: #1f2937;
        font-weight: 600;
        font-size: 1rem;
    }

    .content-preview {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #f3f4f6;
    }

    .content-header {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
    }

    .content-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
    }

    .content-text {
        color: #4b5563;
        line-height: 1.6;
        font-size: 0.875rem;
        max-height: 100px;
        overflow: hidden;
        position: relative;
    }

    .content-text::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(transparent, #f9fafb);
    }

    .warning-section {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 1.5rem;
    }

    .warning-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #92400e;
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .warning-text {
        color: #92400e;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.5;
        margin: 0;
    }

    .action-section {
        text-align: center;
        max-width: 500px;
        margin: 0 auto;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        min-width: 140px;
        justify-content: center;
        cursor: pointer;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, var(--danger-dark) 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        color: #374151;
        transform: translateY(-1px);
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .confirm-container {
            padding: 1rem 0;
        }
        
        .ticket-preview {
            margin: 0 1rem 1.5rem;
            padding: 1.5rem;
        }
        
        .ticket-details {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-action {
            min-width: 200px;
        }
    }
</style>

<div class="confirm-container">
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
            <div class="danger-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="page-title">Xác nhận xóa phiếu hỗ trợ</h1>
            <div class="page-subtitle">
                Hành động này không thể hoàn tác
            </div>
        </div>

        <!-- Ticket Preview -->
        <div class="ticket-preview">
            <div class="ticket-header">
                <div class="ticket-id">
                    <i class="fas fa-hashtag"></i>
                    {{ $phieuHoTro->MaphieuHT }}
                </div>
                <div class="danger-badge">
                    <i class="fas fa-trash"></i>
                    Sẽ bị xóa vĩnh viễn
                </div>
            </div>
            
            <div class="ticket-details">
                <div class="detail-item">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="detail-label">Phương thức hỗ trợ</div>
                    </div>
                    <div class="detail-value">{{ $phieuHoTro->ptHoTro->TenPT ?? 'Không xác định' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="detail-label">Trạng thái hiện tại</div>
                    </div>
                    <div class="detail-value">{{ $phieuHoTro->trangThai->Tentrangthai }}</div>
                </div>
            </div>
            
            <div class="content-preview">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    Nội dung yêu cầu
                </div>
                <div class="content-text">
                    {{ Str::limit($phieuHoTro->Noidungyeucau, 200) }}
                </div>
            </div>
        </div>

        <!-- Warning Section -->
        <div class="warning-section">
            <div class="warning-title">
                <i class="fas fa-exclamation-triangle"></i>
                Bạn có chắc chắn muốn xóa phiếu hỗ trợ này?
            </div>
            <div class="warning-text">
                Sau khi xóa, tất cả thông tin liên quan sẽ bị mất vĩnh viễn và không thể khôi phục.
            </div>
        </div>

        <!-- Action Section -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaphieuHT) }}" class="btn-action btn-cancel">
                    <i class="fas fa-arrow-left"></i>
                    Hủy bỏ
                </a>
                
                <form action="{{ route('customer.phieuhotro.destroy', $phieuHoTro->MaphieuHT) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu hỗ trợ này? Hành động này không thể hoàn tác!')">
                        <i class="fas fa-trash"></i>
                        Xác nhận xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
