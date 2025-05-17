@extends('backend.layouts.app')

@section('title', 'Quản lý quảng cáo')

@section('styles')
<style>
    body {
        background-color: #ffebf3 !important;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #e83e8c, #fd7e97);
        color: white;
        border-radius: 10px;
        padding: 20px 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.3);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-in-out;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .welcome-banner p {
        font-size: 1rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    .shine-line {
        position: absolute;
        top: 0;
        left: -150%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 3s infinite;
        transform: skewX(-25deg);
    }
    
    @keyframes shine {
        0% { left: -150%; }
        100% { left: 150%; }
    }
    
    .table-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 30px;
        animation: fadeUp 0.6s ease-in-out;
        border: 1px solid rgba(232, 62, 140, 0.1);
    }
    
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .ad-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .ad-table th {
        background-color: #f8f9fa;
        color: #e83e8c;
        padding: 14px 16px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        text-align: center;
    }
    
    .ad-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        text-align: center;
    }
    
    .ad-table tr:last-child td {
        border-bottom: none;
    }
    
    .ad-table tr:hover {
        background-color: #fff9fb;
    }
    
    .ad-thumbnail {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e83e8c;
        box-shadow: 0 2px 8px rgba(232, 62, 140, 0.2);
        transition: transform 0.3s;
        margin: 0 auto;
        display: block;
    }
    
    .ad-thumbnail:hover {
        transform: scale(1.1);
    }
    
    .ad-thumbnail.placeholder {
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }
    
    .btn-action {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 0;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .btn-action:hover {
        transform: translateY(-3px);
    }
    
    .btn-view {
        background-color: #e0f7fa;
        color: #00acc1;
        border: 1px solid #b2ebf2;
    }
    
    .btn-view:hover {
        background-color: #00acc1;
        color: white;
    }
    
    .btn-edit {
        background-color: #fff8e1;
        color: #ffb300;
        border: 1px solid #ffe082;
    }
    
    .btn-edit:hover {
        background-color: #ffb300;
        color: white;
    }
    
    .btn-delete {
        background-color: #ffebee;
        color: #e53935;
        border: 1px solid #ffcdd2;
    }
    
    .btn-delete:hover {
        background-color: #e53935;
        color: white;
    }
    
    .btn-add-ad {
        background: linear-gradient(135deg, #e83e8c, #fd7e97);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.3);
        transition: all 0.3s;
    }
    
    .btn-add-ad:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(232, 62, 140, 0.4);
        color: white;
    }
    
    .empty-state {
        padding: 50px 30px;
        text-align: center;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        animation: fadeUp 0.6s ease-in-out;
        border: 1px solid rgba(232, 62, 140, 0.1);
    }
    
    .empty-state-icon {
        font-size: 5rem;
        color: #e83e8c;
        opacity: 0.5;
        margin-bottom: 25px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .empty-state-title {
        font-size: 1.6rem;
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .empty-state-text {
        color: #6c757d;
        margin-bottom: 30px;
        font-size: 1.1rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .alert {
        border-radius: 10px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        animation: slideDown 0.5s ease-out;
    }
    
    @keyframes slideDown {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    .alert-success {
        background-color: #e8f5e9 !important;
        border-left: 4px solid #4caf50 !important;
    }
    
    .alert-danger {
        background-color: #ffebee !important;
        border-left: 4px solid #f44336 !important;
    }
    
    .page-title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        background-color: white;
        border-radius: 10px;
        padding: 15px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .page-title {
        margin: 0;
        color: #e83e8c;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-title i {
        color: #e83e8c;
    }
    
    .content-separator {
        height: 1px;
        background: linear-gradient(90deg, rgba(232, 62, 140, 0.1), rgba(232, 62, 140, 0.3), rgba(232, 62, 140, 0.1));
        margin: 15px 0;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1><i class="fas fa-spa"></i> Quản Lý Quảng Cáo</h1>
            <p>Quản lý và theo dõi các chiến dịch quảng cáo, khuyến mãi và sự kiện cho spa của bạn</p>
            <div class="shine-line"></div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2" style="font-size: 1.2rem; color: #4caf50;"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2" style="font-size: 1.2rem; color: #f44336;"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="page-title-wrapper">
            <h5 class="page-title"><i class="fas fa-list-alt"></i> Danh sách quảng cáo</h5>
            <a href="{{ route('admin.advertisements.create') }}" class="btn-add-ad">
                <i class="fas fa-plus-circle"></i> Thêm quảng cáo mới
            </a>
        </div>

        @if ($advertisements->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-ad"></i>
                </div>
                <h4 class="empty-state-title">Chưa có quảng cáo nào</h4>
                <p class="empty-state-text">Bạn chưa tạo quảng cáo nào. Hãy thêm quảng cáo mới để bắt đầu!</p>
                <a href="{{ route('admin.advertisements.create') }}" class="btn-add-ad">
                    <i class="fas fa-plus-circle"></i> Thêm quảng cáo đầu tiên
                </a>
            </div>
        @else
            <div class="table-container">
                <table class="ad-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Mã QC</th>
                            <th style="width: 90px;">Hình ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Người đăng</th>
                            <th>Loại</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th style="width: 130px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($advertisements as $advertisement)
                            <tr>
                                <td>{{ $advertisement->MaQC }}</td>
                                <td>
                                    @if ($advertisement->Image)
                                        @php
                                            $imagePath = $advertisement->Image;
                                        @endphp
                                        <img src="{{ route('storage.image', ['path' => $imagePath]) }}" 
                                             class="ad-thumbnail" 
                                             alt="{{ $advertisement->Tieude }}"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-ad.jpg') }}'; this.classList.add('error-image');">
                                    @else
                                        <div class="ad-thumbnail placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: #333; text-align: left;">{{ $advertisement->Tieude }}</div>
                                </td>
                                <td>{{ $advertisement->user->Hoten ?? 'Không xác định' }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        $adType = $advertisement->Loaiquangcao;
                                        
                                        if (empty($adType)) {
                                            $displayText = 'Chưa phân loại';
                                        } else {
                                            switch(strtolower(trim($adType))) {
                                                case 'khuyenmai':
                                                    $badgeClass = 'bg-info';
                                                    $displayText = 'Khuyến mãi';
                                                    break;
                                                case 'sukien':
                                                    $badgeClass = 'bg-warning';
                                                    $displayText = 'Sự kiện';
                                                    break;
                                                case 'thongbao':
                                                    $badgeClass = 'bg-primary';
                                                    $displayText = 'Thông báo';
                                                    break;
                                                default:
                                                    $displayText = $adType;
                                            }
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}" style="display: inline-block; padding: 6px 12px; font-size: 0.8rem; font-weight: 500; color: white;">
                                        {{ $displayText }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge" style="background-color: rgba(232, 62, 140, 0.1); color: #e83e8c;">
                                        {{ $advertisement->trangThaiQC->TenTT }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem;">
                                        <div>
                                            <i class="fas fa-calendar-day"></i> {{ date('d/m/Y', strtotime($advertisement->Ngaybatdau)) }}
                                        </div>
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-day"></i> {{ date('d/m/Y', strtotime($advertisement->Ngayketthuc)) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.advertisements.show', $advertisement->MaQC) }}" class="btn btn-action btn-view" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.advertisements.edit', $advertisement->MaQC) }}" class="btn btn-action btn-edit" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.advertisements.confirm-destroy', $advertisement->MaQC) }}" class="btn btn-action btn-delete" data-bs-toggle="tooltip" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo tooltips cho các nút
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    placement: 'top'
                })
            });
        });
    </script>
@endsection