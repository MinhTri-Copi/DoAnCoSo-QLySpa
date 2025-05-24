@extends('customer.layouts.app')

@section('title', 'Chi tiết phiếu hỗ trợ')

@section('content')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-dark: #e55a8a;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .show-container {
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

    .ticket-header-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }

    .ticket-header-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .ticket-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .ticket-id-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ticket-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .ticket-id {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .ticket-method {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-processing {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .status-completed {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .ticket-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #f3f4f6;
    }

    .info-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .info-value {
        color: #1f2937;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .content-section {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.5rem;
        align-items: start;
    }

    .main-content-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
    }

    .content-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .content-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    .content-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .content-text {
        color: #374151;
        line-height: 1.6;
        font-size: 1rem;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .sidebar-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
    }

    .sidebar-card h3 {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn-action {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #f59e0b;
        color: white;
    }

    .btn-edit:hover {
        background: #d97706;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .help-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
    }

    .help-card h3 {
        color: #0c4a6e;
    }

    .help-card p {
        color: #075985;
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0 0 1rem 0;
    }

    .btn-help {
        background: #0ea5e9;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-help:hover {
        background: #0284c7;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    @media (max-width: 1024px) {
        .content-section {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .show-container {
            padding: 1rem 0;
        }
        
        .ticket-header-card {
            padding: 1.5rem;
        }
        
        .ticket-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .ticket-info-grid {
            grid-template-columns: 1fr;
        }
        
        .main-content-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="show-container">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="{{ route('customer.phieuhotro.index') }}">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách phiếu hỗ trợ
                </a>
            </div>

        <!-- Ticket Header Card -->
        <div class="ticket-header-card">
            <div class="ticket-meta">
                <div class="ticket-id-section">
                    <div class="ticket-icon">
                        <i class="fas fa-headset"></i>
                        </div>
                        <div>
                        <h1 class="ticket-id">#{{ $phieuHoTro->MaphieuHT }}</h1>
                        <p class="ticket-method">{{ $phieuHoTro->ptHoTro->TenPT ?? 'Không xác định' }}</p>
                        </div>
                    </div>

                <div>
                    @if($phieuHoTro->trangThai && $phieuHoTro->trangThai->Tentrangthai == 'Đang xử lý')
                        <span class="status-badge status-processing">
                            <i class="fas fa-spinner fa-spin"></i>
                            {{ $phieuHoTro->trangThai->Tentrangthai }}
                        </span>
                    @elseif($phieuHoTro->trangThai && $phieuHoTro->trangThai->Tentrangthai == 'Đã hoàn thành')
                        <span class="status-badge status-completed">
                            <i class="fas fa-check-circle"></i>
                            {{ $phieuHoTro->trangThai->Tentrangthai }}
                        </span>
                    @elseif($phieuHoTro->trangThai && $phieuHoTro->trangThai->Tentrangthai == 'Đã hủy')
                        <span class="status-badge status-cancelled">
                            <i class="fas fa-times-circle"></i>
                            {{ $phieuHoTro->trangThai->Tentrangthai }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="ticket-info-grid">
                <div class="info-item">
                    <span class="info-label">Ngày tạo</span>
                    <span class="info-value">{{ $phieuHoTro->created_at ? $phieuHoTro->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                </div>
                <div class="info-item">
                    <span class="info-label">Cập nhật lần cuối</span>
                    <span class="info-value">{{ $phieuHoTro->updated_at ? $phieuHoTro->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Main Content -->
            <div class="main-content-card">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="fas fa-file-alt"></i>
    </div>
                    <h2 class="content-title">Nội dung yêu cầu hỗ trợ</h2>
</div>
                <div class="content-text">{{ $phieuHoTro->Noidungyeucau }}</div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Actions Card -->
                @if($phieuHoTro->trangThai->Tentrangthai == 'Đang xử lý')
                <div class="sidebar-card">
                    <h3>
                        <i class="fas fa-tools" style="color: var(--primary-color);"></i>
                        Thao tác
                    </h3>
                    <div class="action-buttons">
                        <a href="{{ route('customer.phieuhotro.edit', $phieuHoTro->MaphieuHT) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i>
                            Chỉnh sửa phiếu
                        </a>
                        <a href="{{ route('customer.phieuhotro.confirm-destroy', $phieuHoTro->MaphieuHT) }}" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i>
                            Xóa phiếu hỗ trợ
                        </a>
                    </div>
                </div>
                @endif

                <!-- Help Card -->
                <div class="sidebar-card help-card">
                    <h3>
                        <i class="fas fa-question-circle"></i>
                        Cần hỗ trợ thêm?
                    </h3>
                    <p>Nếu bạn cần hỗ trợ khẩn cấp, vui lòng liên hệ hotline của chúng tôi.</p>
                    <a href="tel:02812345678" class="btn-help">
                        <i class="fas fa-phone"></i>
                        Liên hệ hotline
                    </a>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection 
