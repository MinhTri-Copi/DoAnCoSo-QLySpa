@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/customers.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-circle text-primary mr-2"></i>Chi Tiết Khách Hàng
            </h1>
            <p class="text-muted mt-1 mb-0">
                <i class="fas fa-id-card mr-1"></i> Mã KH: <strong>{{ $customer->Manguoidung }}</strong> | 
                <i class="fas fa-clock mr-1"></i> Tham gia: <strong>{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') : 'N/A' }}</strong>
            </p>
        </div>
        <div class="d-flex">
            <a href="{{ route('admin.customers.edit', $customer->Manguoidung) }}" class="btn btn-primary btn-icon-split mr-2">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Chỉnh Sửa</span>
            </a>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" 
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i> Thao Tác
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuButton">
                    <h6 class="dropdown-header">Tùy Chọn:</h6>
                    <a class="dropdown-item" href="#" id="addPointsBtn">
                        <i class="fas fa-coins fa-sm fa-fw mr-2 text-yellow"></i>
                        Thêm Điểm Thưởng
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-calendar-plus fa-sm fa-fw mr-2 text-primary"></i>
                        Đặt Lịch Hẹn Mới
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-print fa-sm fa-fw mr-2 text-primary"></i>
                        In Thông Tin
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('admin.customers.confirmDestroy', $customer->Manguoidung) }}">
                        <i class="fas fa-trash fa-sm fa-fw mr-2"></i>
                        Xóa Khách Hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Customer Profile Column -->
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-id-card mr-2"></i>Thông Tin Khách Hàng
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="customer-profile">
                        <img class="customer-profile-avatar" src="{{ asset('img/undraw_profile.svg') }}" alt="{{ $customer->Hoten }}">
                        <h4 class="customer-profile-name">{{ $customer->Hoten }}</h4>
                        
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
                        
                        <div class="customer-membership-badge {{ $badgeClass }}">
                            @if($hangName == 'VIP' || $hangName == 'Platinum' || $hangName == 'Diamond')
                                <i class="fas fa-crown mr-1"></i>
                            @endif
                            {{ $hangName }}
                        </div>
                        
                        <div class="customer-info-list">
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Email</div>
                                    <div class="customer-info-value">{{ $customer->Email }}</div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Số Điện Thoại</div>
                                    <div class="customer-info-value">{{ $customer->SDT }}</div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Địa Chỉ</div>
                                    <div class="customer-info-value">{{ $customer->DiaChi }}</div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Ngày Sinh</div>
                                    <div class="customer-info-value">
                                        {{ \Carbon\Carbon::parse($customer->Ngaysinh)->format('d/m/Y') }}
                                        <small class="text-muted ml-2">
                                            ({{ \Carbon\Carbon::parse($customer->Ngaysinh)->age }} tuổi)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-venus-mars"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Giới Tính</div>
                                    <div class="customer-info-value">
                                        @if($customer->Gioitinh == 'Nam')
                                            <span class="badge badge-primary">Nam</span>
                                        @else
                                            <span class="badge badge-info">Nữ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Tài Khoản</div>
                                    <div class="customer-info-value">
                                        {{ $customer->account ? $customer->account->Tendangnhap : 'N/A' }}
                                        @if($customer->account && $customer->account->role)
                                            <span class="badge badge-secondary ml-2">{{ $customer->account->role->Tenquyen }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Statistics Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-pie mr-2"></i>Thống Kê Khách Hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="stat-card-value">{{ $customer->hoaDon->count() }}</div>
                                <div class="stat-card-label">Đơn Hàng</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-info">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-card-value">{{ $customer->datLich->count() }}</div>
                                <div class="stat-card-label">Lịch Hẹn</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-warning">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-card-value">{{ $customer->danhGia->count() }}</div>
                                <div class="stat-card-label">Đánh Giá</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-success">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-card-value">
                                    @php
                                        $totalPoints = 0;
                                        foreach($customer->lsDiemThuong as $point) {
                                            if($point->Loai == 'Cộng') {
                                                $totalPoints += $point->Diem;
                                            } else {
                                                $totalPoints -= $point->Diem;
                                            }
                                        }
                                    @endphp
                                    {{ $totalPoints }}
                                </div>
                                <div class="stat-card-label">Điểm Thưởng</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button class="btn btn-primary btn-custom btn-icon" id="addPointsBtn2">
                            <span class="btn-icon"><i class="fas fa-plus-circle"></i></span>
                            Thêm Điểm Thưởng
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-history mr-2"></i>Hoạt Động Gần Đây
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="timeline p-3">
                        @php
                            $recentActivities = collect();
                            
                            // Add orders
                            foreach($customer->hoaDon->take(2) as $order) {
                                $recentActivities->push([
                                    'date' => $order->Ngaytao,
                                    'type' => 'order',
                                    'icon' => 'fa-shopping-cart',
                                    'color' => 'primary',
                                    'title' => 'Đặt đơn hàng #' . $order->MaHD,
                                    'content' => 'Tổng giá trị: ' . number_format($order->Tongtien, 0, ',', '.') . ' VNĐ',
                                    'link' => '#',
                                    'link_text' => 'Xem chi tiết'
                                ]);
                            }
                            
                            // Add appointments
                            foreach($customer->datLich->take(2) as $appointment) {
                                $recentActivities->push([
                                    'date' => $appointment->Thoigiandatlich,
                                    'type' => 'appointment',
                                    'icon' => 'fa-calendar-check',
                                    'color' => 'info',
                                    'title' => 'Đặt lịch hẹn dịch vụ',
                                    'content' => ($appointment->dichVu ? $appointment->dichVu->Tendichvu : 'N/A'),
                                    'link' => '#',
                                    'link_text' => 'Xem chi tiết'
                                ]);
                            }
                            
                            // Add points
                            foreach($customer->lsDiemThuong->take(2) as $point) {
                                $recentActivities->push([
                                    'date' => $point->Ngay,
                                    'type' => 'points',
                                    'icon' => 'fa-coins',
                                    'color' => $point->Loai == 'Cộng' ? 'success' : 'danger',
                                    'title' => $point->Loai . ' ' . $point->Diem . ' điểm',
                                    'content' => $point->Ghichu,
                                    'link' => '#',
                                    'link_text' => 'Lịch sử điểm'
                                ]);
                            }
                            
                            // Sort by date (newest first)
                            $recentActivities = $recentActivities->sortByDesc('date')->take(5);
                        @endphp
                        
                        @forelse($recentActivities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $activity['color'] }}"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas {{ $activity['icon'] }} mr-1 text-{{ $activity['color'] }}"></i>
                                        {{ $activity['title'] }}
                                    </div>
                                    <div class="timeline-body">
                                        {{ $activity['content'] }}
                                    </div>
                                    <a href="{{ $activity['link'] }}" class="btn btn-sm btn-link px-0">
                                        {{ $activity['link_text'] }} <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                                <p class="mb-0 text-gray-500">Không có hoạt động gần đây</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Detail Tabs Column -->
        <div class="col-xl-8 col-lg-7">
            <!-- Tabs Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-folder-open mr-2"></i>Chi Tiết Khách Hàng
                    </h6>
                </div>
                <div class="card-body p-0">
                    <!-- Tabs Nav -->
                    <ul class="nav nav-tabs custom-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="true">
                                <i class="fas fa-shopping-cart"></i> Đơn Hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="appointments-tab" data-toggle="tab" href="#appointments" role="tab" aria-controls="appointments" aria-selected="false">
                                <i class="fas fa-calendar-check"></i> Lịch Hẹn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="points-tab" data-toggle="tab" href="#points" role="tab" aria-controls="points" aria-selected="false">
                                <i class="fas fa-coins"></i> Điểm Thưởng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                                <i class="fas fa-star"></i> Đánh Giá
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="spending-tab" data-toggle="tab" href="#spending" role="tab" aria-controls="spending" aria-selected="false">
                                <i class="fas fa-chart-line"></i> Chi Tiêu
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Tabs Content -->
                    <div class="tab-content p-4" id="customerTabsContent">
                        <!-- Orders Tab -->
                        <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-cart text-primary mr-2"></i>Lịch Sử Đơn Hàng
                                    <span class="badge badge-primary ml-2" id="visibleOrdersCount">{{ $customer->hoaDon->count() }}</span>
                                </h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary order-filter active" data-filter="all">Tất Cả</button>
                                    <button class="btn btn-sm btn-outline-success order-filter" data-filter="Hoàn thành">Hoàn Thành</button>
                                    <button class="btn btn-sm btn-outline-warning order-filter" data-filter="Đang xử lý">Đang Xử Lý</button>
                                    <button class="btn btn-sm btn-outline-danger order-filter" data-filter="Đã hủy">Đã Hủy</button>
                                </div>
                            </div>
                            
                            @if($customer->hoaDon->count() > 0)
                                <div class="table-responsive">
                                    <table class="table customer-table">
                                        <thead>
                                            <tr>
                                                <th>Mã ĐH</th>
                                                <th>Ngày Đặt</th>
                                                <th>Dịch Vụ</th>
                                                <th>Tổng Tiền</th>
                                                <th>Trạng Thái</th>
                                                <th class="text-right">Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->hoaDon as $order)
                                                @php
                                                    $statusClass = 'secondary';
                                                    $status = $order->trangThai ? $order->trangThai->Tentrangthai : 'N/A';
                                                    
                                                    if($status == 'Hoàn thành') {
                                                        $statusClass = 'success';
                                                    } elseif($status == 'Đang xử lý') {
                                                        $statusClass = 'warning';
                                                    } elseif($status == 'Đã hủy') {
                                                        $statusClass = 'danger';
                                                    }
                                                @endphp
                                                
                                                <tr class="order-row" 
                                                    data-id="{{ $order->MaHD }}" 
                                                    data-status="{{ $status }}"
                                                    data-date="{{ \Carbon\Carbon::parse($order->Ngaytao)->format('d/m/Y') }}"
                                                    data-amount="{{ number_format($order->Tongtien, 0, ',', '.') }} VNĐ"
                                                    data-method="{{ $order->phuongThuc ? $order->phuongThuc->Tenphuongthuc : 'N/A' }}">
                                                    <td>
                                                        <span class="font-weight-bold">HD{{ $order->MaHD }}</span>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($order->Ngaytao)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($order->datLich && $order->datLich->dichVu)
                                                            {{ $order->datLich->dichVu->Tendichvu }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($order->Tongtien, 0, ',', '.') }} VNĐ</td>
                                                    <td>
                                                        <span class="badge-status badge-status-{{ $statusClass }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <button class="btn btn-sm btn-primary view-order" data-id="{{ $order->MaHD }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-4x text-gray-300 mb-3"></i>
                                    <p class="mb-0 text-gray-500">Khách hàng chưa có đơn hàng nào</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Appointments Tab -->
                        <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-check text-info mr-2"></i>Lịch Hẹn
                                    <span class="badge badge-info ml-2" id="visibleAppointmentsCount">{{ $customer->datLich->count() }}</span>
                                </h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary appointment-filter active" data-filter="all">Tất Cả</button>
                                    <button class="btn btn-sm btn-outline-success appointment-filter" data-filter="Hoàn thành">Hoàn Thành</button>
                                    <button class="btn btn-sm btn-outline-warning appointment-filter" data-filter="Đã xác nhận">Đã Xác Nhận</button>
                                    <button class="btn btn-sm btn-outline-danger appointment-filter" data-filter="Đã hủy">Đã Hủy</button>
                                </div>
                            </div>
                            
                            @if($customer->datLich->count() > 0)
                                <div class="table-responsive">
                                    <table class="table customer-table">
                                        <thead>
                                            <tr>
                                                <th>Mã ĐL</th>
                                                <th>Thời Gian</th>
                                                <th>Dịch Vụ</th>
                                                <th>Trạng Thái</th>
                                                <th class="text-right">Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->datLich as $appointment)
                                                @php
                                                    $statusClass = 'secondary';
                                                    $trangthai = $appointment->Trangthai_;
                                                    if($trangthai) {
                                                        if($trangthai == 'Hoàn thành') {
                                                            $statusClass = 'success';
                                                        } elseif($trangthai == 'Đã xác nhận' || $trangthai == 'Đang chờ' || $trangthai == 'Chờ xác nhận') {
                                                            $statusClass = 'warning';
                                                        } elseif($trangthai == 'Đã hủy') {
                                                            $statusClass = 'danger';
                                                        }
                                                    }
                                                @endphp
                                                
                                                <tr class="appointment-row" 
                                                    data-id="{{ $appointment->MaDL }}" 
                                                    data-status="{{ $trangthai }}"
                                                    data-date="{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('d/m/Y') }}"
                                                    data-time="{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('H:i') }}"
                                                    data-service="{{ $appointment->dichVu ? $appointment->dichVu->Tendichvu : 'N/A' }}">
                                                    <td>
                                                        <span class="font-weight-bold">DL{{ $appointment->MaDL }}</span>
                                                    </td>
                                                    <td>
                                                        <div>{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($appointment->dichVu)
                                                            {{ $appointment->dichVu->Tendichvu }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge-status badge-status-{{ $statusClass }}">
                                                            {{ $trangthai }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <button class="btn btn-sm btn-primary view-appointment" data-id="{{ $appointment->MaDL }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-4x text-gray-300 mb-3"></i>
                                    <p class="mb-0 text-gray-500">Khách hàng chưa có lịch hẹn nào</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Points Modal -->
    <div class="modal fade" id="addPointsModal" tabindex="-1" role="dialog" aria-labelledby="addPointsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header card-header-gradient">
                    <h5 class="modal-title text-white" id="addPointsModalLabel">
                        <i class="fas fa-coins mr-2"></i>Thêm Điểm Thưởng
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.customers.addPoints', $customer->Manguoidung) }}" method="POST" id="addPointsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="point_type" class="form-label">Loại Giao Dịch <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="pointTypeAdd" name="point_type" value="Cộng" class="custom-control-input" checked>
                                    <label class="custom-control-label text-success" for="pointTypeAdd">Cộng Điểm</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="pointTypeSubtract" name="point_type" value="Trừ" class="custom-control-input">
                                    <label class="custom-control-label text-danger" for="pointTypeSubtract">Trừ Điểm</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pointsAmount" class="form-label">
                                <span id="pointTypeLabel" class="text-success">Cộng Điểm</span> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                </div>
                                <input type="number" class="form-control" id="pointsAmount" name="points" min="1" required>
                            </div>
                            <small class="form-text text-muted">Nhập số điểm cần thêm/trừ</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="pointsNote" class="form-label">Ghi Chú <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="pointsNote" name="note" rows="3" required placeholder="Nhập lý do thêm/trừ điểm"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary" id="submitPoints">
                            <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="{{ asset('js/admin/customers/show.js') }}"></script>
@endsection