@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
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