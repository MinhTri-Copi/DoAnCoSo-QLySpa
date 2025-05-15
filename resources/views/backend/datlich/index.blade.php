@extends('backend.layouts.app')

@section('title', 'Quản Lý Đặt Lịch')

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
        padding: 8px 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        text-decoration: none;
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
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
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
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
    }

    .filter-date {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
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

    .btn-action:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 5px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: white;
        border: 1px solid var(--border-color);
        color: #495057;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .page-link:hover {
        background-color: #f8f9fa;
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
        <div class="header-title">Quản Lý Đặt Lịch</div>
        <div class="header-subtitle">Quản lý và theo dõi lịch đặt dịch vụ</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.datlich.statistics') }}" class="btn-pink">
            <i class="fas fa-chart-bar"></i> Thống Kê
        </a>
        <a href="{{ route('admin.datlich.create') }}" class="btn-pink">
            <i class="fas fa-plus"></i> Thêm Lịch Đặt
        </a>
    </div>
</div>

<div class="stats-container">
    @php
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        $todayBookings = $datLichs->filter(function($item) use ($today) {
            return \Carbon\Carbon::parse($item->Thoigiandatlich)->format('Y-m-d') == $today;
        })->count();
        
        $pendingBookings = $datLichs->where('Trangthai_', 'Chờ xác nhận')->count();
        $confirmedBookings = $datLichs->where('Trangthai_', 'Đã xác nhận')->count();
        $completedBookings = $datLichs->where('Trangthai_', 'Hoàn thành')->count();
    @endphp
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-value">{{ $todayBookings }}</div>
        <div class="stat-label">Lịch Đặt Hôm Nay</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-1" style="width: {{ min(100, ($todayBookings / 30) * 100) }}%"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $pendingBookings }}</div>
        <div class="stat-label">Chờ Xác Nhận</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-2"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $confirmedBookings }}</div>
        <div class="stat-label">Đã Xác Nhận</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-3"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-flag-checkered"></i>
        </div>
        <div class="stat-value">{{ $completedBookings }}</div>
        <div class="stat-label">Hoàn Thành</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-4"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Danh Sách Lịch Đặt
        </div>
        <div>
            <button class="btn-action" style="background-color: var(--primary-color);" id="toggleFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    
    <form action="{{ route('admin.datlich.index') }}" method="GET" id="filterForm">
        <div class="search-filter-container" id="filterContainer">
            <div class="filter-box">
                <select name="user_id" class="filter-select">
                    <option value="">-- Tất cả người dùng --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ request('user_id') == $user->Manguoidung ? 'selected' : '' }}>
                            {{ $user->Hoten }}
                        </option>
                    @endforeach
                </select>
                
                <select name="service_id" class="filter-select">
                    <option value="">-- Tất cả dịch vụ --</option>
                    @foreach($dichVus as $dichVu)
                        <option value="{{ $dichVu->MaDV }}" {{ request('service_id') == $dichVu->MaDV ? 'selected' : '' }}>
                            {{ $dichVu->Tendichvu }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" class="filter-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-box">
                <input type="date" name="date" class="filter-date" placeholder="Ngày" value="{{ request('date') }}">
                
                <input type="date" name="date_from" class="filter-date" placeholder="Từ ngày" value="{{ request('date_from') }}">
                
                <input type="date" name="date_to" class="filter-date" placeholder="Đến ngày" value="{{ request('date_to') }}">
            </div>
            
            <div class="filter-box">
                <button type="submit" class="btn-pink">
                    <i class="fas fa-search"></i> Tìm Kiếm
                </button>
                
                <a href="{{ route('admin.datlich.index') }}" class="btn-pink" style="background-color: #6c757d;">
                    <i class="fas fa-sync"></i> Đặt Lại
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
                    <th>Mã Đặt Lịch</th>
                    <th>Người Dùng</th>
                    <th>Dịch Vụ</th>
                    <th>Thời Gian</th>
                    <th>Trạng Thái</th>
                    <th class="text-end">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($datLichs as $datLich)
                <tr>
                    <td>{{ $datLich->MaDL }}</td>
                    <td>
                        @if($datLich->user)
                            <div class="d-flex align-items-center">
                                <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: bold; margin-right: 10px;">
                                    {{ substr($datLich->user->Hoten, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ $datLich->user->Hoten }}</div>
                                    <div style="font-size: 12px; color: #6c757d;">{{ $datLich->user->SDT ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($datLich->dichVu)
                            <div>
                                <div style="font-weight: 500;">{{ $datLich->dichVu->Tendichvu }}</div>
                                <div style="font-size: 12px; color: #6c757d;">{{ number_format($datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div>
                            <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('d/m/Y') }}</div>
                            <div style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('H:i') }}</div>
                        </div>
                    </td>
                    <td>
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
                        <span class="badge {{ $statusClass }}">{{ $datLich->Trangthai_ }}</span>
                    </td>
                    <td class="text-end">
                        <div class="action-buttons">
                            <a href="{{ route('admin.datlich.show', $datLich->MaDL) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.datlich.edit', $datLich->MaDL) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.datlich.confirmDestroy', $datLich->MaDL) }}" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>Không có dữ liệu</h4>
                            <p>Chưa có lịch đặt nào được tạo hoặc không có lịch đặt phù hợp với bộ lọc.</p>
                            <a href="{{ route('admin.datlich.create') }}" class="btn-pink">
                                <i class="fas fa-plus"></i> Thêm Lịch Đặt
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            Hiển thị {{ $datLichs->firstItem() ?? 0 }} đến {{ $datLichs->lastItem() ?? 0 }} của {{ $datLichs->total() }} bản ghi
        </div>
        <div>
            {{ $datLichs->appends(request()->query())->links() }}
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