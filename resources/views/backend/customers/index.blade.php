@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/customers.css') }}" rel="stylesheet">
<style>
    :root {
        --spa-primary: #83c5be;
        --spa-primary-dark: #3d7068;
        --spa-secondary: #ffddd2;
        --spa-accent: #e29578;
        --spa-light: #edf6f9;
        --spa-dark: #006d77;
        --spa-text: #2c3e50;
        --spa-card-shadow: 0 8px 20px rgba(0, 109, 119, 0.1);
    }
    
    .page-heading {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border-radius: 10px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-heading::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background-image: url('/img/zen-pattern.png');
        background-size: cover;
        opacity: 0.1;
    }
    
    .spa-card {
        border: none;
        border-radius: 10px;
        box-shadow: var(--spa-card-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
    }
    
    .spa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 109, 119, 0.2);
    }
    
    .spa-card-header {
        background: var(--spa-light);
        border-bottom: none;
        font-weight: 600;
        color: var(--spa-dark);
        padding: 1.25rem 1.5rem;
    }
    
    .stat-card {
        border-radius: 10px;
        padding: 1.5rem;
        background: white;
        position: relative;
        overflow: hidden;
        border-left: 4px solid var(--spa-primary);
    }
    
    .stat-card-spa {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .stat-card-spa .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-bottom: 1rem;
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        color: white;
        box-shadow: 0 4px 10px rgba(0, 109, 119, 0.2);
    }
    
    .stat-card-spa .stat-icon i {
        font-size: 1.5rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--spa-dark);
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: var(--spa-text);
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .spa-table thead th {
        background-color: var(--spa-light);
        border: none;
        color: var(--spa-dark);
        font-weight: 600;
        font-size: 0.9rem;
        padding: 1rem 1.5rem;
    }
    
    .spa-table tbody tr {
        transition: all 0.3s;
        border-bottom: 1px solid #eee;
    }
    
    .spa-table tbody tr:hover {
        background-color: rgba(131, 197, 190, 0.05);
        transform: scale(1.01);
    }
    
    .spa-table tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
    }
    
    .customer-name {
        font-weight: 600;
        color: var(--spa-dark);
        display: flex;
        align-items: center;
    }
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 0.75rem;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .btn-spa {
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }
    
    .btn-spa-primary {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border: none;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 109, 119, 0.2);
    }
    
    .btn-spa-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 109, 119, 0.3);
        color: white;
    }
    
    .search-filter-spa {
        position: relative;
    }
    
    .search-filter-spa .form-control {
        border-radius: 50px;
        padding: 0.75rem 1.5rem 0.75rem 3rem;
        border: 1px solid #eee;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .search-filter-spa .search-icon {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--spa-primary-dark);
    }
    
    .spa-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .membership-regular {
        background-color: var(--spa-light);
        color: var(--spa-text);
    }
    
    .membership-vip {
        background: linear-gradient(120deg, #ffd700, #daa520);
        color: white;
    }
    
    .membership-platinum {
        background: linear-gradient(120deg, #e0e0e0, #a9a9a9);
        color: white;
    }
    
    .membership-diamond {
        background: linear-gradient(120deg, #b3e5fc, #4fc3f7);
        color: white;
    }
    
    .action-btns {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .paging-custom .page-link {
        border-radius: 50%;
        margin: 0 3px;
        color: var(--spa-dark);
        border: none;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }
    
    .paging-custom .page-item.active .page-link {
        background-color: var(--spa-primary);
        color: white;
        box-shadow: 0 2px 5px rgba(0, 109, 119, 0.2);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-heading mb-4">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-white font-weight-bold">Quản Lý Khách Hàng</h1>
                <p class="mb-0 text-white opacity-75">
                    <i class="fas fa-spa mr-1"></i> Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất
                </p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.customers.create') }}" class="btn btn-spa btn-spa-primary shadow">
                    <i class="fas fa-user-plus mr-2"></i>Thêm Khách Hàng Mới
                </a>
            </div>
        </div>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $customers->count() }}</div>
                    <div class="stat-label">Tổng Khách Hàng</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #4db6ac, #26a69a);">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $customers->where('Ngaysinh', '>=', now()->startOfMonth())->count() }}</div>
                    <div class="stat-label">Khách Hàng Mới (Tháng Này)</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ ($customers->where('Ngaysinh', '>=', now()->startOfMonth())->count() / ($customers->count() ?: 1)) * 100 }}%" 
                             aria-valuenow="{{ ($customers->where('Ngaysinh', '>=', now()->startOfMonth())->count() / ($customers->count() ?: 1)) * 100 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #f48fb1, #ec407a);">
                    <i class="fas fa-venus-mars"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($customers->where('Gioitinh', 'Nữ')->count() / ($customers->count() ?: 1) * 100, 0) }}%</div>
                    <div class="stat-label">Tỷ Lệ Khách Hàng Nữ</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: {{ $customers->where('Gioitinh', 'Nữ')->count() / ($customers->count() ?: 1) * 100 }}%; background-color: #ec407a;" 
                             role="progressbar" 
                             aria-valuenow="{{ $customers->where('Gioitinh', 'Nữ')->count() / ($customers->count() ?: 1) * 100 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="spa-card stat-card-spa">
                <div class="stat-icon" style="background: linear-gradient(120deg, #ffb74d, #ff9800);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    @php
                        $pendingOrders = 0;
                        foreach($customers as $customer) {
                            $pendingOrders += $customer->hoaDon->where('Matrangthai', 1)->count();
                        }
                    @endphp
                    <div class="stat-value">{{ $pendingOrders }}</div>
                    <div class="stat-label">Đơn Hàng Chờ Xử Lý</div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer List -->
    <div class="spa-card mb-4">
        <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                    <i class="fas fa-users mr-2"></i>Danh Sách Khách Hàng
                </h6>
            </div>
            <div class="dropdown no-arrow">
                <a class="btn btn-sm btn-action" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: var(--spa-light);">
                    <i class="fas fa-ellipsis-v text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Tùy Chọn:</div>
                    <a class="dropdown-item" href="#" id="exportCSV">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-muted"></i>Xuất CSV
                    </a>
                    <a class="dropdown-item" href="#" id="printList">
                        <i class="fas fa-print fa-sm fa-fw mr-2 text-muted"></i>In Danh Sách
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="refreshList">
                        <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-muted"></i>Làm Mới
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="search-filter-spa">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="Tìm kiếm khách hàng..." id="customerSearch">
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="d-flex align-items-center">
                        <label class="mr-2 mb-0" style="color: var(--spa-dark);">Lọc theo:</label>
                        <select class="form-control" style="width: auto; border-radius: 50px;">
                            <option value="">Tất cả hạng thành viên</option>
                            <option value="Thường">Thường</option>
                            <option value="VIP">VIP</option>
                            <option value="Platinum">Platinum</option>
                            <option value="Diamond">Diamond</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table spa-table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Khách Hàng</th>
                            <th>Liên Hệ</th>
                            <th>Dịch Vụ Sử Dụng</th>
                            <th>Hạng Thành Viên</th>
                            <th>Trạng Thái</th>
                            <th class="text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr class="customer-row" data-id="{{ $customer->Manguoidung }}">
                            <td>
                                <div class="customer-name">
                                    <img src="{{ asset('img/undraw_profile.svg') }}" alt="{{ $customer->Hoten }}" class="customer-avatar">
                                    <div>
                                        <div>{{ $customer->Hoten }}</div>
                                        <small class="text-muted">Mã KH: {{ $customer->Manguoidung }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="fas fa-phone-alt text-muted mr-2"></i>{{ $customer->SDT }}</div>
                                <div><i class="fas fa-envelope text-muted mr-2"></i>{{ $customer->Email }}</div>
                            </td>
                            <td>
                                <div>
                                    @php
                                        $serviceCount = $customer->datLich->count();
                                        $lastService = $customer->datLich->sortByDesc('Thoigiandatlich')->first();
                                    @endphp
                                    <span class="badge badge-pill badge-light">{{ $serviceCount }} dịch vụ</span>
                                    @if($lastService && $lastService->dichVu)
                                        <div class="mt-1 small text-muted">
                                            Gần đây: {{ $lastService->dichVu->Tendichvu }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $hangTV = $customer->hangThanhVien->first();
                                    $hangName = $hangTV ? $hangTV->Tenhang : 'Thường';
                                    $badgeClass = 'membership-regular';
                                    
                                    if($hangName == 'VIP') {
                                        $badgeClass = 'membership-vip';
                                    } elseif($hangName == 'Platinum') {
                                        $badgeClass = 'membership-platinum';
                                    } elseif($hangName == 'Diamond') {
                                        $badgeClass = 'membership-diamond';
                                    }
                                @endphp
                                <span class="spa-badge {{ $badgeClass }}">
                                    @if($hangName != 'Thường')
                                        <i class="fas fa-crown mr-1"></i>
                                    @endif
                                    {{ $hangName }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $hasRecentActivity = $customer->hoaDon->sortByDesc('Ngaytao')->first() && 
                                        $customer->hoaDon->sortByDesc('Ngaytao')->first()->Ngaytao > now()->subDays(30);
                                    $hasUpcomingAppointment = $customer->datLich->where('Thoigiandatlich', '>', now())->count() > 0;
                                @endphp
                                
                                @if($hasUpcomingAppointment)
                                    <span class="spa-badge" style="background-color: #e3f2fd; color: #1976d2;">
                                        <i class="fas fa-calendar-check mr-1"></i> Lịch hẹn sắp tới
                                    </span>
                                @elseif($hasRecentActivity)
                                    <span class="spa-badge" style="background-color: #e8f5e9; color: #388e3c;">
                                        <i class="fas fa-check-circle mr-1"></i> Hoạt động gần đây
                                    </span>
                                @else
                                    <span class="spa-badge" style="background-color: #fafafa; color: #757575;">
                                        <i class="fas fa-clock mr-1"></i> Không hoạt động
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.customers.show', $customer->Manguoidung) }}" class="btn btn-action" style="background-color: #e3f2fd; color: #1976d2;" data-toggle="tooltip" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer->Manguoidung) }}" class="btn btn-action" style="background-color: #e8f5e9; color: #388e3c;" data-toggle="tooltip" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.confirmDestroy', $customer->Manguoidung) }}" class="btn btn-action" style="background-color: #ffebee; color: #d32f2f;" data-toggle="tooltip" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info text-muted" id="dataTable_info" role="status" aria-live="polite">
                        Hiển thị 1 đến {{ min(10, $customers->count()) }} của {{ $customers->count() }} khách hàng
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                        <ul class="pagination paging-custom">
                            <li class="paginate_button page-item previous disabled" id="dataTable_previous">
                                <a href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <li class="paginate_button page-item active">
                                <a href="#" aria-controls="dataTable" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                            </li>
                            @if($customers->count() > 10)
                            <li class="paginate_button page-item">
                                <a href="#" aria-controls="dataTable" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                            </li>
                            @endif
                            @if($customers->count() > 20)
                            <li class="paginate_button page-item">
                                <a href="#" aria-controls="dataTable" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                            </li>
                            @endif
                            @if($customers->count() > 10)
                            <li class="paginate_button page-item next" id="dataTable_next">
                                <a href="#" aria-controls="dataTable" data-dt-idx="7" tabindex="0" class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/customers/index.js') }}"></script>
@endsection