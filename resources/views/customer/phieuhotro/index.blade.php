@extends('customer.layouts.app')

@section('title', 'Phiếu hỗ trợ')

@section('content')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-dark: #e55a8a;
        --primary-light: #ffb3d1;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        --card-shadow: 0 4px 20px rgba(255, 107, 157, 0.08);
        --card-shadow-hover: 0 8px 30px rgba(255, 107, 157, 0.15);
    }

    .main-container {
        background: var(--bg-gradient);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .page-header {
        background: linear-gradient(135deg, rgba(255, 107, 157, 0.1) 0%, rgba(255, 255, 255, 0.8) 100%);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 107, 157, 0.1);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 40%;
        height: 200%;
        background: radial-gradient(ellipse, rgba(255, 107, 157, 0.05) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .page-subtitle {
        font-size: 1.125rem;
        color: #6b7280;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .search-section {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #f1f5f9;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.125rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
        color: white;
        text-decoration: none;
    }

    .tickets-grid {
        display: grid;
        gap: 1.5rem;
    }

    .ticket-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .ticket-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
    }

    .ticket-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .ticket-header {
        display: flex;
        align-items: center;
        justify-content: between;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }

    .ticket-id {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .ticket-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .ticket-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
    }

    .ticket-method {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
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
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .status-completed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #10b981;
    }

    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .ticket-content {
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ticket-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
    }

    .ticket-date {
        font-size: 0.875rem;
        color: #9ca3af;
    }

    .ticket-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .action-view {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .action-view:hover {
        background: #bfdbfe;
        transform: scale(1.1);
    }

    .action-edit {
        background: #fef3c7;
        color: #d97706;
    }

    .action-edit:hover {
        background: #fde68a;
        transform: scale(1.1);
    }

    .action-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-delete:hover {
        background: #fecaca;
        transform: scale(1.1);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2rem;
        color: #9ca3af;
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
        .page-header {
            padding: 2rem 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .ticket-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .ticket-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }
</style>

<div class="main-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-headset me-3"></i>Phiếu hỗ trợ
            </h1>
            <p class="page-subtitle">Quản lý và theo dõi các yêu cầu hỗ trợ của bạn</p>
            <a href="{{ route('customer.phieuhotro.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Tạo phiếu mới
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Search Section -->
        <div class="search-section">
            <div class="position-relative">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Tìm kiếm phiếu hỗ trợ...">
            </div>
        </div>

        <!-- Tickets Grid -->
        @if(count($phieuHoTro) > 0)
            <div class="tickets-grid">
                @foreach($phieuHoTro as $phieu)
                    <div class="ticket-card">
                        <div class="ticket-header">
                            <div class="ticket-id">
                                <div class="ticket-avatar">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div>
                                    <div class="ticket-number">#{{ $phieu->MaphieuHT }}</div>
                                    <div class="ticket-method">{{ $phieu->ptHoTro->TenPT ?? 'Không xác định' }}</div>
                                </div>
                            </div>
                            
                            <div class="ms-auto">
                                @if($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đang xử lý')
                                    <span class="status-badge status-processing">
                                        <i class="fas fa-spinner fa-spin"></i>Đang xử lý
                                    </span>
                                @elseif($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đã hoàn thành')
                                    <span class="status-badge status-completed">
                                        <i class="fas fa-check-circle"></i>Đã hoàn thành
                                    </span>
                                @elseif($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đã hủy')
                                    <span class="status-badge status-cancelled">
                                        <i class="fas fa-times-circle"></i>Đã hủy
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="ticket-content">
                            {{ Str::limit($phieu->Noidungyeucau, 120) }}
                        </div>

                        <div class="ticket-footer">
                            <div class="ticket-date">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $phieu->created_at ? $phieu->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                            
                            <div class="ticket-actions">
                                <button class="action-btn action-view" 
                                        onclick="window.location.href='{{ route('customer.phieuhotro.show', $phieu->MaphieuHT) }}'"
                                        title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đang xử lý')
                                    <button class="action-btn action-edit" 
                                            onclick="window.location.href='{{ route('customer.phieuhotro.edit', $phieu->MaphieuHT) }}'"
                                            title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-delete" 
                                            onclick="window.location.href='{{ route('customer.phieuhotro.confirm-destroy', $phieu->MaphieuHT) }}'"
                                            title="Xóa phiếu">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $phieuHoTro->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 style="color: #374151; font-weight: 600; margin-bottom: 1rem;">
                    Chưa có phiếu hỗ trợ nào
                </h3>
                <p style="color: #6b7280; margin-bottom: 2rem;">
                    Bạn chưa tạo phiếu hỗ trợ nào. Hãy tạo phiếu đầu tiên của bạn.
                </p>
                <a href="{{ route('customer.phieuhotro.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Tạo phiếu mới
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
