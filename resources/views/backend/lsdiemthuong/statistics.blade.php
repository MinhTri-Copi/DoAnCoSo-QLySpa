@extends('backend.layouts.app')

@section('title', 'Thống Kê Điểm Thưởng')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none; background-color: #ff99b9;">
        <div class="card-body py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-white">
                        <i class="fas fa-chart-bar me-2"></i> Thống Kê Điểm Thưởng
                    </h1>
                    <p class="text-white mb-0">
                        <i class="fas fa-info-circle me-1"></i> Xem thống kê và phân tích điểm thưởng
                    </p>
                </div>
                <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-light rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Bộ lọc thời gian -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
        <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
            <h6 class="m-0 font-weight-bold text-primary">Chọn Khoảng Thời Gian</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lsdiemthuong.statistics') }}" method="GET" class="mb-0">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="start_date" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_date" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2" style="background-color: #ff99b9; border-color: #ff99b9;">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.lsdiemthuong.statistics') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt me-1"></i> Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tổng quan -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
        <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
            <h6 class="m-0 font-weight-bold text-primary">Tổng Quan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100" style="border-radius: 10px; border: none; background-color: #f8f9fc;">
                        <div class="card-body text-center">
                            <h5 class="card-title">Tổng Điểm Thưởng</h5>
                            <h1 class="display-4 font-weight-bold" style="color: #ff99b9;">{{ number_format($totalPoints) }}</h1>
                            <p class="text-muted">Trong khoảng thời gian đã chọn</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" style="border-radius: 10px; border: none; background-color: #f8f9fc;">
                        <div class="card-body text-center">
                            <h5 class="card-title">Số Người Dùng</h5>
                            <h1 class="display-4 font-weight-bold" style="color: #ff99b9;">{{ count($pointsByUser) }}</h1>
                            <p class="text-muted">Đã nhận điểm thưởng</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" style="border-radius: 10px; border: none; background-color: #f8f9fc;">
                        <div class="card-body text-center">
                            <h5 class="card-title">Số Lần Tích Điểm</h5>
                            <h1 class="display-4 font-weight-bold" style="color: #ff99b9;">{{ $pointsByDate->sum('count') }}</h1>
                            <p class="text-muted">Tổng số lần tích điểm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Biểu đồ điểm theo ngày -->
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Biểu Đồ Điểm Thưởng Theo Ngày</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="pointsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top người dùng -->
        <div class="col-lg-4">
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Top Người Dùng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Điểm</th>
                                    <th>Số lần</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointsByUser as $userPoint)
                                    <tr>
                                        <td>{{ $userPoint->user->Hoten ?? 'N/A' }}</td>
                                        <td>{{ number_format($userPoint->total_points) }}</td>
                                        <td>{{ $userPoint->count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Điểm thưởng theo hóa đơn -->
        <div class="col-lg-12">
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Điểm Thưởng Theo Hóa Đơn</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã hóa đơn</th>
                                    <th>Ngày thanh toán</th>
                                    <th>Tổng tiền</th>
                                    <th>Người dùng</th>
                                    <th>Điểm thưởng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointsByInvoice as $invoicePoint)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.hoadonvathanhtoan.show', $invoicePoint->MaHD) }}">
                                                HD-{{ $invoicePoint->MaHD }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($invoicePoint->hoaDon)
                                                {{ \Carbon\Carbon::parse($invoicePoint->hoaDon->Ngaythanhtoan)->format('d/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($invoicePoint->hoaDon)
                                                {{ number_format($invoicePoint->hoaDon->Tongtien) }} VND
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($invoicePoint->hoaDon && $invoicePoint->hoaDon->user)
                                                {{ $invoicePoint->hoaDon->user->Hoten }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ number_format($invoicePoint->total_points) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Dữ liệu cho biểu đồ
        const dates = @json($pointsByDate->pluck('date'));
        const points = @json($pointsByDate->pluck('total_points'));
        const counts = @json($pointsByDate->pluck('count'));
        
        // Biểu đồ điểm theo ngày
        const ctx = document.getElementById('pointsChart').getContext('2d');
        const pointsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString('vi-VN');
                }),
                datasets: [
                    {
                        label: 'Điểm thưởng',
                        data: points,
                        backgroundColor: 'rgba(255, 153, 185, 0.7)',
                        borderColor: 'rgba(255, 153, 185, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Số lần tích điểm',
                        data: counts,
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Điểm thưởng'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Số lần tích điểm'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Ngày'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
