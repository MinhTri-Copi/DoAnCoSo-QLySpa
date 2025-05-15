@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}?v={{ time() }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* Inline styles để đảm bảo ghi đè các styles khác */
    .payment-detail-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-detail-card .card-header {
        background: linear-gradient(40deg, #ff6b95, #ffa7bc) !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem 1.5rem !important;
    }
    
    .payment-detail-card .card-header h6 {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
    }
    
    .btn-pink {
        background-color: #ff6b95 !important;
        border-color: #ff6b95 !important;
        color: white !important;
        font-weight: 500 !important;
        padding: 0.5rem 1.5rem !important;
        border-radius: 50px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 10px rgba(255, 107, 149, 0.2) !important;
    }
    
    .btn-outline-pink {
        background-color: transparent !important;
        border-color: #ff6b95 !important;
        color: #ff6b95 !important;
        font-weight: 500 !important;
        padding: 0.5rem 1.5rem !important;
        border-radius: 50px !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-outline-pink:hover {
        background-color: #ff6b95 !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.2) !important;
    }
    
    .btn-pink:hover {
        background-color: #ff4c7f !important;
        border-color: #ff4c7f !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.3) !important;
    }
    
    .text-primary {
        color: #ff6b95 !important;
    }
    
    .btn-primary {
        background-color: #ff6b95 !important;
        border-color: #ff6b95 !important;
        color: white !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease !important;
    }
    
    .badge-pink {
        background-color: #ff6b95 !important;
        color: white !important;
        padding: 0.5em 1em !important;
        border-radius: 50px !important;
        font-weight: 500 !important;
    }
    
    .payment-method-icon {
        color: #ff6b95 !important;
    }
    
    .timeline-item-marker-indicator {
        background-color: #ff6b95 !important;
    }
    
    .stat-item {
        background-color: rgba(255, 107, 149, 0.05) !important;
        border-radius: 15px !important;
        padding: 1.25rem !important;
        transition: all 0.3s ease !important;
    }
    
    .stat-icon {
        color: #ff6b95 !important;
    }
    
    .info-label {
        color: #ff6b95 !important;
    }
    
    /* Header styling */
    .payment-header {
        background: #ff6b95 !important;
        color: white !important;
        padding: 1.5rem !important;
        border-radius: 15px !important;
        margin-bottom: 2rem !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.3) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }
    
    .payment-header h1 {
        margin: 0 !important;
        font-size: 1.75rem !important;
        font-weight: 600 !important;
        color: white !important;
    }
    
    .payment-header p {
        margin: 0 !important;
        opacity: 0.9 !important;
        margin-top: 0.25rem !important;
    }
    
    .add-payment-btn {
        background-color: white !important;
        color: #ff6b95 !important;
        border: none !important;
        border-radius: 50px !important;
        padding: 0.6rem 1.5rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }
    
    .add-payment-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
        background-color: #f8f9fa !important;
    }
    
    .add-payment-btn i {
        margin-right: 0.5rem !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header giống hình mẫu -->
    <div class="payment-header animate__animated animate__fadeIn">
        <div>
            <h1>Quản Lý Phương Thức Thanh Toán</h1>
            <p><i class="fas fa-credit-card mr-2"></i> Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất</p>
        </div>
        <a href="{{ route('admin.phuongthuc.create') }}" class="add-payment-btn">
            <i class="fas fa-plus"></i>
            <span>Thêm Phương Thức Mới</span>
        </a>
    </div>

    <!-- Thông tin chi tiết phương thức -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi Tiết Phương Thức Thanh Toán</h1>
        <div>
            <a href="{{ route('admin.phuongthuc.edit', $phuongThuc->MaPT) }}" class="btn btn-primary btn-icon-split mr-2">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Chỉnh Sửa</span>
            </a>
            <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Quay Lại</span>
            </a>
        </div>
    </div>

    <!-- Payment Method Info -->
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 animate__animated animate__fadeIn">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Phương Thức</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.phuongthuc.edit', $phuongThuc->MaPT) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                Chỉnh Sửa
                            </a>
                            @if($phuongThuc->hoaDon->count() == 0)
                                <a class="dropdown-item" href="{{ route('admin.phuongthuc.confirmDestroy', $phuongThuc->MaPT) }}">
                                    <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Xóa Phương Thức
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="text-center mb-4 payment-method-item" data-name="{{ $phuongThuc->TenPT }}">
                        <div class="payment-method-icon mb-3">
                            <i class="fas fa-4x"></i>
                        </div>
                        <h4 class="font-weight-bold text-gray-800">{{ $phuongThuc->TenPT }}</h4>
                    </div>
                    
                    <div class="payment-method-info">
                        <div class="info-item">
                            <div class="info-label">Mã Phương Thức:</div>
                            <div class="info-value">{{ $phuongThuc->MaPT }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tên Phương Thức:</div>
                            <div class="info-value">{{ $phuongThuc->TenPT }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Mô Tả:</div>
                            <div class="info-value">{{ $phuongThuc->Mota ?: 'Không có mô tả' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Số Hóa Đơn:</div>
                            <div class="info-value">
                                <span class="badge badge-info">{{ $phuongThuc->hoaDon->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('admin.phuongthuc.edit', $phuongThuc->MaPT) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i> Chỉnh Sửa
                        </a>
                        @if($phuongThuc->hoaDon->count() == 0)
                            <a href="{{ route('admin.phuongthuc.confirmDestroy', $phuongThuc->MaPT) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash mr-1"></i> Xóa
                            </a>
                        @else
                            <button class="btn btn-danger btn-sm" disabled data-toggle="tooltip" title="Không thể xóa phương thức đang được sử dụng">
                                <i class="fas fa-trash mr-1"></i> Xóa
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment Method Statistics Card -->
            <div class="card shadow mb-4 animate__animated animate__fadeIn" style="animation-delay: 0.2s">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống Kê</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="stat-item text-center">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-file-invoice fa-2x"></i>
                                </div>
                                <div class="stat-label">Tổng Hóa Đơn</div>
                                <div class="stat-value">{{ $phuongThuc->hoaDon->count() }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-item text-center">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                                <div class="stat-label">Tổng Doanh Thu</div>
                                <div class="stat-value">
                                    @php
                                        $totalRevenue = 0;
                                        foreach($phuongThuc->hoaDon as $hoaDon) {
                                            $totalRevenue += $hoaDon->Tongtien;
                                        }
                                    @endphp
                                    {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($phuongThuc->hoaDon->count() > 0)
                        <div class="chart-area mt-4">
                            <canvas id="usageChart" data-usage='[
                                @php
                                    $usageData = [];
                                    $sixMonthsAgo = \Carbon\Carbon::now()->subMonths(6);
                                    $currentDate = \Carbon\Carbon::now();
                                    
                                    while($sixMonthsAgo <= $currentDate) {
                                        $monthYear = $sixMonthsAgo->format('m/Y');
                                        $monthStart = $sixMonthsAgo->copy()->startOfMonth();
                                        $monthEnd = $sixMonthsAgo->copy()->endOfMonth();
                                        
                                        $count = 0;
                                        foreach($phuongThuc->hoaDon as $hoaDon) {
                                            $hoaDonDate = \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan);
                                            if($hoaDonDate >= $monthStart && $hoaDonDate <= $monthEnd) {
                                                $count++;
                                            }
                                        }
                                        
                                        $usageData[] = [
                                            'month' => $monthYear,
                                            'count' => $count
                                        ];
                                        
                                        $sixMonthsAgo->addMonth();
                                    }
                                    
                                    echo collect($usageData)->map(function($item) {
                                        return json_encode($item);
                                    })->implode(',');
                                @endphp
                            ]'></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 col-lg-7">
            <!-- Invoices with this payment method -->
            <div class="card shadow mb-4 animate__animated animate__fadeIn">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Hóa Đơn Sử Dụng Phương Thức Này</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                                Xuất Danh Sách
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Tìm kiếm hóa đơn..." id="invoiceSearchInput">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    @if($phuongThuc->hoaDon->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="invoicesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Mã HĐ</th>
                                        <th>Ngày Thanh Toán</th>
                                        <th>Khách Hàng</th>
                                        <th>Tổng Tiền</th>
                                        <th>Trạng Thái</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($phuongThuc->hoaDon as $hoaDon)
                                        <tr>
                                            <td>{{ $hoaDon->MaHD }}</td>
                                            <td>{{ $hoaDon->Ngaythanhtoan ? \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                @if($hoaDon->user)
                                                    <a href="{{ route('admin.customers.show', $hoaDon->user->Manguoidung) }}">
                                                        {{ $hoaDon->user->Hoten }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Không xác định</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</td>
                                            <td>
                                                @php
                                                    $statusClass = 'secondary';
                                                    if($hoaDon->trangThai) {
                                                        if($hoaDon->trangThai->Tentrangthai == 'Hoàn thành') {
                                                            $statusClass = 'success';
                                                        } elseif($hoaDon->trangThai->Tentrangthai == 'Đang xử lý') {
                                                            $statusClass = 'warning';
                                                        } elseif($hoaDon->trangThai->Tentrangthai == 'Hủy') {
                                                            $statusClass = 'danger';
                                                        }
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ $hoaDon->trangThai ? $hoaDon->trangThai->Tentrangthai : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.hoadonvathanhtoan.show', $hoaDon->MaHD) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Revenue Chart -->
                        <div class="mt-4">
                            <h6 class="text-primary">Biểu Đồ Doanh Thu</h6>
                            <div class="chart-area">
                                <canvas id="revenueChart" data-revenue='[
                                    @php
                                        $revenueData = [];
                                        $sixMonthsAgo = \Carbon\Carbon::now()->subMonths(6);
                                        $currentDate = \Carbon\Carbon::now();
                                        
                                        while($sixMonthsAgo <= $currentDate) {
                                            $monthYear = $sixMonthsAgo->format('m/Y');
                                            $monthStart = $sixMonthsAgo->copy()->startOfMonth();
                                            $monthEnd = $sixMonthsAgo->copy()->endOfMonth();
                                            
                                            $amount = 0;
                                            foreach($phuongThuc->hoaDon as $hoaDon) {
                                                $hoaDonDate = \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan);
                                                if($hoaDonDate >= $monthStart && $hoaDonDate <= $monthEnd) {
                                                    $amount += $hoaDon->Tongtien;
                                                }
                                            }
                                            
                                            $revenueData[] = [
                                                'month' => $monthYear,
                                                'amount' => $amount
                                            ];
                                            
                                            $sixMonthsAgo->addMonth();
                                        }
                                        
                                        echo collect($revenueData)->map(function($item) {
                                            return json_encode($item);
                                        })->implode(',');
                                    @endphp
                                ]'></canvas>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-4x text-gray-300 mb-3"></i>
                            <p class="mb-0 text-gray-500">Chưa có hóa đơn nào sử dụng phương thức này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/phuongthuc.js') }}"></script>
<script src="{{ asset('js/admin/phuongthuc/show.js') }}"></script>
@endsection