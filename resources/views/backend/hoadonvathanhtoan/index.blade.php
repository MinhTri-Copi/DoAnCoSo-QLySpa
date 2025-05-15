@extends('backend.layouts.app')

@section('title', 'Quản Lý Hóa Đơn và Thanh Toán')

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

    .filter-date {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
        height: 40px;
    }

    /* Add new styles for date range filters */
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

    /* Add new styles for amount range filters */
    .amount-range-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .amount-range-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .amount-range-inputs {
        display: flex;
        gap: 10px;
    }

    .amount-input-group {
        position: relative;
        flex: 1;
    }

    .amount-input-group label {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 3px;
    }

    .amount-input-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
    }

    .from-amount input {
        border-left: 3px solid #28a745;
    }

    .to-amount input {
        border-left: 3px solid #dc3545;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid var(--border-color);
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        height: 72px; /* Add consistent height for all cells */
    }

    .table tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
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

    .btn-print {
        background-color: var(--primary-color);
    }

    .btn-action:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    /* Updated pagination styles */
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

    .page-link.prev-next {
        font-size: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .empty-state h4 {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #adb5bd;
        max-width: 400px;
        margin: 0 auto 20px;
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
</style>

<div class="header-container">
    <div>
        <div class="header-title">Quản Lý Hóa Đơn và Thanh Toán</div>
        <div class="header-subtitle">Quản lý và theo dõi hóa đơn thanh toán dịch vụ</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.hoadonvathanhtoan.statistics') }}" class="btn-pink">
            <i class="fas fa-chart-bar"></i> Thống Kê
        </a>
        <a href="{{ route('admin.hoadonvathanhtoan.create') }}" class="btn-pink">
            <i class="fas fa-plus"></i> Thêm Hóa Đơn
        </a>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="stat-value">{{ number_format($hoaDons->count(), 0, ',', '.') }}</div>
        <div class="stat-label">Tổng Số Hóa Đơn</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-1"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Tổng Doanh Thu (VNĐ)</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-2"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-value">{{ number_format($revenueThisMonth, 0, ',', '.') }}</div>
        <div class="stat-label">Doanh Thu Tháng Này (VNĐ)</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-3"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        @php
            $completedCount = $invoicesByStatus->where('trangThai.Tentrangthai', 'Đã thanh toán')->first()->count ?? 0;
            $pendingCount = $invoicesByStatus->where('trangThai.Tentrangthai', 'Chờ thanh toán')->first()->count ?? 0;
        @endphp
        <div class="stat-value">{{ $completedCount }}</div>
        <div class="stat-label">Đã Thanh Toán</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-4" style="width: {{ ($hoaDons->count() > 0) ? ($completedCount / $hoaDons->count() * 100) : 0 }}%"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Danh Sách Hóa Đơn
        </div>
        <div>
            <button class="btn-action" style="background-color: var(--primary-color);" id="toggleFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    
    <form action="{{ route('admin.hoadonvathanhtoan.index') }}" method="GET" id="filterForm">
        <div class="search-filter-container" id="filterContainer">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="maHD" placeholder="Tìm kiếm theo mã hóa đơn..." value="{{ request('maHD') }}">
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
                
                <select name="payment_method" class="filter-select">
                    <option value="">-- Tất cả phương thức --</option>
                    @foreach($phuongThucs as $phuongThuc)
                        <option value="{{ $phuongThuc->MaPT }}" {{ request('payment_method') == $phuongThuc->MaPT ? 'selected' : '' }}>
                            {{ $phuongThuc->TenPT }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" class="filter-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($trangThais as $trangThai)
                        <option value="{{ $trangThai->Matrangthai }}" {{ request('status') == $trangThai->Matrangthai ? 'selected' : '' }}>
                            {{ $trangThai->Tentrangthai }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-box">
                <div class="date-range-container">
                    <div class="date-range-title">Khoảng Thời Gian</div>
                    <div class="date-range-inputs">
                        <div class="date-input-group from-date">
                            <label for="date_from">Từ ngày:</label>
                            <input type="date" id="date_from" name="date_from" placeholder="Từ ngày" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="date-input-group to-date">
                            <label for="date_to">Đến ngày:</label>
                            <input type="date" id="date_to" name="date_to" placeholder="Đến ngày" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                
                <div class="amount-range-container">
                    <div class="amount-range-title">Khoảng Giá Trị</div>
                    <div class="amount-range-inputs">
                        <div class="amount-input-group from-amount">
                            <label for="amount_from">Số tiền từ:</label>
                            <input type="number" id="amount_from" name="amount_from" placeholder="Số tiền từ" value="{{ request('amount_from') }}">
                        </div>
                        
                        <div class="amount-input-group to-amount">
                            <label for="amount_to">Số tiền đến:</label>
                            <input type="number" id="amount_to" name="amount_to" placeholder="Số tiền đến" value="{{ request('amount_to') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="filter-box">
                <button type="submit" class="btn-pink">
                    <i class="fas fa-search"></i> Tìm Kiếm
                </button>
                
                <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn-pink" style="background-color: #6c757d;">
                    <i class="fas fa-sync"></i> Đặt Lại
                </a>
                
                <a href="{{ route('admin.hoadonvathanhtoan.exportExcel') }}" class="btn-pink" style="background-color: #28a745;">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>
        </div>
    </form>
    
    @if(session('success'))
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
    @endif
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã HĐ</th>
                    <th>Người Dùng</th>
                    <th>Dịch Vụ</th>
                    <th>Ngày Thanh Toán</th>
                    <th>Tổng Tiền</th>
                    <th>Phương Thức</th>
                    <th>Trạng Thái</th>
                    <th class="text-end">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hoaDons as $hoaDon)
                <tr>
                    <td>{{ $hoaDon->MaHD }}</td>
                    <td>
                        @if($hoaDon->user)
                            <div class="d-flex align-items-center">
                                <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: bold; margin-right: 10px;">
                                    {{ substr($hoaDon->user->Hoten, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ $hoaDon->user->Hoten }}</div>
                                    <div style="font-size: 12px; color: #6c757d;">{{ $hoaDon->user->SDT ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($hoaDon->datLich && $hoaDon->datLich->dichVu)
                            <div>
                                <div style="font-weight: 500;">{{ $hoaDon->datLich->dichVu->Tendichvu }}</div>
                                <div style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($hoaDon->datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div>
                            <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('d/m/Y') }}</div>
                            <div style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('H:i') }}</div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500; color: var(--primary-color);">{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</div>
                        <div style="font-size: 12px; color: #6c757d;">Đã bao gồm VAT (10%)</div>
                    </td>
                    <td>
                        @if($hoaDon->phuongThuc)
                            <div style="font-weight: 500;">{{ $hoaDon->phuongThuc->TenPT }}</div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($hoaDon->trangThai)
                            <span>{{ $hoaDon->trangThai->Tentrangthai }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-buttons">
                            <a href="{{ route('admin.hoadonvathanhtoan.show', $hoaDon->MaHD) }}" class="btn-action btn-view" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.hoadonvathanhtoan.edit', $hoaDon->MaHD) }}" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.hoadonvathanhtoan.confirmDestroy', $hoaDon->MaHD) }}" class="btn-action btn-delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                            <a href="{{ route('admin.hoadonvathanhtoan.print', $hoaDon->MaHD) }}" class="btn-action btn-print" title="In hóa đơn" target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-file-invoice"></i>
                            <h4>Không có dữ liệu</h4>
                            <p>Chưa có hóa đơn nào được tạo hoặc không có hóa đơn phù hợp với bộ lọc.</p>
                            <a href="{{ route('admin.hoadonvathanhtoan.create') }}" class="btn-pink">
                                <i class="fas fa-plus"></i> Thêm Hóa Đơn
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination-info">
            Hiển thị {{ $hoaDons->firstItem() ?? 0 }} đến {{ $hoaDons->lastItem() ?? 0 }} của {{ $hoaDons->total() }} bản ghi
        </div>
        <div>
            {{ $hoaDons->appends(request()->query())->links('pagination::bootstrap-4') }}
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