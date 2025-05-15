@extends('backend.layouts.app')

@section('title', 'Chi Tiết Phiếu Hỗ Trợ')

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

    .support-detail-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .support-detail-header {
        padding: 1.5rem;
        background: var(--pink-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .support-detail-header::before {
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

    .support-detail-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .support-id-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        font-size: 0.9rem;
        margin-left: 1rem;
        backdrop-filter: blur(5px);
    }

    .support-detail-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }

    .support-detail-body {
        padding: 2rem;
    }

    .detail-item {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px dashed var(--border-color);
        display: flex;
        flex-wrap: wrap;
    }

    .detail-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
        width: 180px;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .detail-value {
        flex: 1;
        color: var(--text-primary);
        font-weight: 500;
        font-size: 1rem;
        min-width: 250px;
    }

    .detail-value.content {
        background: var(--light-gray);
        padding: 1rem;
        border-radius: var(--radius-sm);
        border-left: 4px solid var(--primary-pink);
        font-style: italic;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background: rgba(241, 196, 15, 0.1);
        color: var(--yellow);
        border: 1px solid rgba(241, 196, 15, 0.3);
    }

    .status-processing {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .status-completed {
        background: rgba(46, 204, 113, 0.1);
        color: var(--green);
        border: 1px solid rgba(46, 204, 113, 0.3);
    }

    .status-cancelled {
        background: rgba(231, 76, 60, 0.1);
        color: var(--red);
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .support-detail-footer {
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

    @media (max-width: 768px) {
        .detail-label, 
        .detail-value {
            width: 100%;
        }
        
        .detail-label {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="support-detail-card">
        <div class="support-detail-header">
            <h1 class="support-detail-title">
                Chi Tiết Phiếu Hỗ Trợ
                <span class="support-id-badge">#{{ $phieuHoTro->MaphieuHT }}</span>
            </h1>
            <p class="support-detail-subtitle">
                <i class="fas fa-info-circle"></i>
                Thông tin chi tiết về phiếu hỗ trợ
            </p>
        </div>
        
        <div class="support-detail-body">
            <div class="detail-item">
                <div class="detail-label">Mã Phiếu Hỗ Trợ:</div>
                <div class="detail-value">{{ $phieuHoTro->MaphieuHT }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Nội Dung Yêu Cầu:</div>
                <div class="detail-value content">{{ $phieuHoTro->Noidungyeucau }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Trạng Thái:</div>
                <div class="detail-value">
                    @php
                        $statusName = $phieuHoTro->trangThai->Tentrangthai ?? 'Chờ xử lý';
                        $statusClass = 'status-pending';
                        
                        switch(strtolower($statusName)) {
                            case 'đang xử lý':
                                $statusClass = 'status-processing';
                                break;
                            case 'hoàn thành':
                                $statusClass = 'status-completed';
                                break;
                            case 'đã hủy':
                                $statusClass = 'status-cancelled';
                                break;
                        }
                    @endphp
                    
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusName }}
                    </span>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Người Dùng:</div>
                <div class="detail-value">{{ $phieuHoTro->user->Hoten ?? 'N/A' }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Phương Thức Hỗ Trợ:</div>
                <div class="detail-value">{{ $phieuHoTro->ptHoTro->TenPT ?? 'N/A' }}</div>
            </div>
        </div>
        
        <div class="support-detail-footer">
            <a href="{{ route('admin.phieuhotro.edit', $phieuHoTro->MaphieuHT) }}" class="btn-support btn-primary-support">
                <i class="fas fa-edit"></i>
                Chỉnh Sửa
            </a>
            <a href="{{ route('admin.phieuhotro.index') }}" class="btn-support btn-secondary-support">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>
</div>
@endsection