@extends('backend.layouts.app')

@section('title', 'Quản Lý Lịch Sử Điểm Thưởng')

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

    .header-container {
        background-color: var(--primary-color);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        color: var(--text-on-primary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        font-size: 24px;
        font-weight: bold;
    }

    .header-subtitle {
        font-size: 14px;
        margin-top: 5px;
        opacity: 0.9;
    }

    .btn-pink {
        background-color: var(--text-on-primary);
        color: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 8px 16px;
        font-weight: bold;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        text-decoration: none;
        height: 40px;
    }

    .btn-pink:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-pink i {
        margin-right: 8px;
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        flex: 1;
        min-width: 200px;
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #6c757d;
        font-size: 14px;
    }

    .stat-progress {
        height: 4px;
        background-color: #e9ecef;
        border-radius: 2px;
        margin-top: 15px;
        overflow: hidden;
    }

    .stat-progress-bar {
        height: 100%;
        border-radius: 2px;
    }

    .progress-1 {
        background-color: #4cd964;
        width: 75%;
    }

    .progress-2 {
        background-color: var(--primary-color);
        width: 45%;
    }

    .progress-3 {
        background-color: #007bff;
        width: 60%;
    }

    .progress-4 {
        background-color: #ff9500;
        width: 30%;
    }

    .content-card {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .search-filter-container {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 8px 15px 8px 35px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        height: 40px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-box {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
        height: 40px;
    }

    /* Date and point range filters */
    .date-range-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .date-range-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .date-range-inputs {
        display: flex;
        gap: 10px;
    }

    .date-input-group {
        position: relative;
        flex: 1;
    }

    .date-input-group label {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 3px;
    }

    .date-input-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
    }

    .from-date input {
        border-left: 3px solid #007bff;
    }

    .to-date input {
        border-left: 3px solid var(--primary-color);
    }

    /* Points range filters */
    .points-range-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .points-range-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .points-range-inputs {
        display: flex;
        gap: 10px;
    }

    .points-input-group {
        position: relative;
        flex: 1;
    }

    .points-input-group label {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 3px;
    }

    .points-input-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
    }

    .from-points input {
        border-left: 3px solid #28a745;
    }

    .to-points input {
        border-left: 3px solid #dc3545;
    }

    .pagination-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
    }

    .pagination {
        display: flex;
        gap: 6px;
        margin: 0;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: white;
        border: 1px solid var(--border-color);
        color: #495057;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 14px;
        padding: 0 10px;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 2px 5px rgba(255, 107, 139, 0.3);
    }

    .page-item.disabled .page-link {
        color: #adb5bd;
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .page-link:hover:not(.disabled) {
        background-color: var(--primary-light);
        color: var(--primary-color);
        border-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-pink {
            margin-top: 15px;
            align-self: flex-start;
        }
        
        .stats-container {
            flex-direction: column;
        }
        
        .search-filter-container {
            flex-direction: column;
        }
        
        .filter-box {
            flex-direction: column;
        }
    }

    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: flex-end;
        align-items: center;
        height: 100%;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-view {
        background-color: var(--info-color);
    }

    .btn-edit {
        background-color: var(--warning-color);
    }

    .btn-delete {
        background-color: var(--danger-color);
    }

    /* Points display styling */
    .points-display {
        font-weight: 500;
        color: var(--success-color);
        display: flex;
        flex-direction: column;
    }

    .points-value {
        font-size: 16px;
    }

    /* Make table cells consistent height */
    .table td {
        vertical-align: middle;
        height: 72px;
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .table th {
        background-color: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid var(--border-color);
    }

    .text-end {
        text-align: right !important;
    }
    
    /* Set width for action column */
    .actions-column {
        width: 120px;
    }
</style>

<div class="header-container">
    <div>
        <div class="header-title">Quản Lý Điểm Thưởng</div>
        <div class="header-subtitle">Thêm, sửa, xóa và theo dõi lịch sử điểm thưởng của khách hàng</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.lsdiemthuong.statistics') }}" class="btn-pink">
            <i class="fas fa-chart-bar"></i> Thống Kê
        </a>
        <a href="{{ route('admin.lsdiemthuong.create') }}" class="btn-pink">
            <i class="fas fa-plus"></i> Thêm điểm thưởng
        </a>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-value">{{ number_format($totalPoints) }}</div>
        <div class="stat-label">Tổng Điểm Thưởng</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-1"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-value">{{ number_format($pointsThisMonth) }}</div>
        <div class="stat-label">Điểm Thưởng Tháng Này</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-2"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $usersWithPoints }}</div>
        <div class="stat-label">Số Người Dùng Có Điểm</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-3"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="stat-value">
            @if($pointsByUser->isNotEmpty())
                {{ $pointsByUser->first()->user->Hoten ?? 'N/A' }}
            @else
                N/A
            @endif
        </div>
        <div class="stat-label">Người Dùng Điểm Cao Nhất</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-4"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Danh Sách Lịch Sử Điểm Thưởng
        </div>
        <div>
            <button class="btn-action" style="background-color: var(--primary-color);" id="toggleFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    
    <form action="{{ route('admin.lsdiemthuong.index') }}" method="GET" id="filterForm">
        <div class="search-filter-container" id="filterContainer">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="maLSDT" placeholder="Tìm kiếm theo mã lịch sử điểm thưởng..." value="{{ request('maLSDT') }}">
            </div>
            
            <div class="filter-box">
                <select name="user_id" class="filter-select">
                    <option value="">-- Tất cả người dùng --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ request('user_id') == $user->Manguoidung ? 'selected' : '' }}>
                            {{ $user->Hoten }}
                        </option>
                    @endforeach
                </select>
                
                <select name="invoice_id" class="filter-select">
                    <option value="">-- Tất cả hóa đơn --</option>
                    @foreach($hoaDons as $hoaDon)
                        <option value="{{ $hoaDon->MaHD }}" {{ request('invoice_id') == $hoaDon->MaHD ? 'selected' : '' }}>
                            HD-{{ $hoaDon->MaHD }}
                        </option>
                    @endforeach
                </select>
                
                <select name="sort" class="filter-select">
                    <option value="Thoigian" {{ request('sort') == 'Thoigian' ? 'selected' : '' }}>Sắp xếp theo thời gian</option>
                    <option value="Sodiem" {{ request('sort') == 'Sodiem' ? 'selected' : '' }}>Sắp xếp theo số điểm</option>
                    <option value="MaLSDT" {{ request('sort') == 'MaLSDT' ? 'selected' : '' }}>Sắp xếp theo mã</option>
                </select>
            </div>
            
            <div class="filter-box">
                <div class="date-range-container">
                    <div class="date-range-title">Khoảng Thời Gian</div>
                    <div class="date-range-inputs">
                        <div class="date-input-group from-date">
                            <label for="date_from">Từ ngày:</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="date-input-group to-date">
                            <label for="date_to">Đến ngày:</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                
                <div class="points-range-container">
                    <div class="points-range-title">Khoảng Điểm</div>
                    <div class="points-range-inputs">
                        <div class="points-input-group from-points">
                            <label for="points_from">Điểm từ:</label>
                            <input type="number" id="points_from" name="points_from" placeholder="Điểm từ" value="{{ request('points_from') }}">
                        </div>
                        
                        <div class="points-input-group to-points">
                            <label for="points_to">Điểm đến:</label>
                            <input type="number" id="points_to" name="points_to" placeholder="Điểm đến" value="{{ request('points_to') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="filter-box">
                <select name="direction" class="filter-select">
                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                </select>
                
                <button type="submit" class="btn-pink">
                    <i class="fas fa-search"></i> Tìm Kiếm
                </button>
                
                <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn-pink" style="background-color: #6c757d;">
                    <i class="fas fa-sync"></i> Đặt Lại
                </a>
                
                <a href="{{ route('admin.lsdiemthuong.exportExcel') }}" class="btn-pink" style="background-color: #28a745;">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>
        </div>
    </form>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã LS</th>
                    <th>Thời gian</th>
                    <th>Số điểm</th>
                    <th>Người dùng</th>
                    <th>Hóa đơn</th>
                    <th class="text-end actions-column">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lsDiemThuongs as $lsDiemThuong)
                    <tr>
                        <td>{{ $lsDiemThuong->MaLSDT }}</td>
                        <td>{{ \Carbon\Carbon::parse($lsDiemThuong->Thoigian)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="points-display">
                                <span class="points-value">{{ number_format($lsDiemThuong->Sodiem) }}</span>
                                <small class="text-muted">điểm</small>
                            </div>
                        </td>
                        <td>{{ $lsDiemThuong->user->Hoten ?? 'N/A' }}</td>
                        <td>
                            @if($lsDiemThuong->MaHD)
                                <a href="{{ route('admin.hoadonvathanhtoan.show', $lsDiemThuong->MaHD) }}">
                                    HD-{{ $lsDiemThuong->MaHD }}
                                </a>
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.lsdiemthuong.show', $lsDiemThuong->MaLSDT) }}" class="btn-action btn-view" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.lsdiemthuong.edit', $lsDiemThuong->MaLSDT) }}" class="btn-action btn-edit" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.lsdiemthuong.confirmDestroy', $lsDiemThuong->MaLSDT) }}" class="btn-action btn-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination-info">
            Hiển thị {{ $lsDiemThuongs->firstItem() ?? 0 }} đến {{ $lsDiemThuongs->lastItem() ?? 0 }} của {{ $lsDiemThuongs->total() }} bản ghi
        </div>
        <div>
            {{ $lsDiemThuongs->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters
    const toggleFilters = document.getElementById('toggleFilters');
    const filterContainer = document.getElementById('filterContainer');
    
    toggleFilters.addEventListener('click', function() {
        filterContainer.style.display = filterContainer.style.display === 'none' ? 'flex' : 'none';
    });
    
    // Initialize tooltips if using Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection
