@extends('backend.layouts.app')

@section('title', 'Thống Kê Hóa Đơn')

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

    .btn-white {
        background-color: white;
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

    .btn-white:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-white i {
        margin-right: 8px;
    }

    .filter-container {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .filter-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .filter-form {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
        font-size: 14px;
    }

    .filter-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
    }

    .filter-button {
        align-self: flex-end;
        padding: 10px 20px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-button:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
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

    .chart-container {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .chart-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .chart-body {
        height: 300px;
        position: relative;
    }

    .table-container {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
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

    .progress-container {
        width: 100%;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 5px;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-white {
            margin-top: 15px;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .stats-container {
            flex-direction: column;
        }
    }
</style>

<div class="header-container">
    <div>
        <div class="header-title">Thống Kê Hóa Đơn</div>
        <div class="header-subtitle">Phân tích dữ liệu hóa đơn và doanh thu</div>
    </div>
    <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn-white">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<div class="filter-container">
    <div class="filter-title">
        <i class="fas fa-filter"></i> Bộ Lọc Thống Kê
    </div>
    <form action="{{ route('admin.hoadonvathanhtoan.statistics') }}" method="GET" class="filter-form">
        <div class="filter-group">
            <label for="start_date" class="filter-label">Từ Ngày</label>
            <input type="date" id="start_date" name="start_date" class="filter-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
        </div>
        <div class="filter-group">
            <label for="end_date" class="filter-label">Đến Ngày</label>
            <input type="date" id="end_date" name="end_date" class="filter-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="filter-button">
            <i class="fas fa-search"></i> Áp Dụng
        </button>
    </form>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="stat-label">Tổng Doanh Thu (VNĐ)</div>
    </div>

    @foreach($invoicesByStatus as $status)
    <div class="stat-card">
        <div class="stat-icon" style="background-color: 
            @if($status->trangThai->Tentrangthai == 'Đã thanh toán')
                #28a745
            @elseif($status->trangThai->Tentrangthai == 'Chờ thanh toán')
                #ffc107
            @elseif($status->trangThai->Tentrangthai == 'Đã hủy')
                #dc3545
            @else
                #17a2b8
            @endif
        ">
            <i class="fas fa-
                @if($status->trangThai->Tentrangthai == 'Đã thanh toán')
                    check-circle
                @elseif($status->trangThai->Tentrangthai == 'Chờ thanh toán')
                    clock
                @elseif($status->trangThai->Tentrangthai == 'Đã hủy')
                    times-circle
                @else
                    info-circle
                @endif
            "></i>
        </div>
        <div class="stat-value">{{ $status->count }}</div>
        <div class="stat-label">{{ $status->trangThai->Tentrangthai }}</div>
        <div class="stat-value" style="font-size: 16px; margin-top: 10px;">{{ number_format($status->total, 0, ',', '.') }} VNĐ</div>
    </div>
    @endforeach
</div>

<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title">
            <i class="fas fa-chart-line"></i> Biểu Đồ Doanh Thu Theo Ngày
        </div>
    </div>
    <div class="chart-body">
        <canvas id="revenueByDateChart"></canvas>
    </div>
</div>

<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title">
            <i class="fas fa-chart-pie"></i> Biểu Đồ Hóa Đơn Theo Trạng Thái
        </div>
    </div>
    <div class="chart-body">
        <canvas id="invoicesByStatusChart"></canvas>
    </div>
</div>

<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title">
            <i class="fas fa-chart-bar"></i> Biểu Đồ Doanh Thu Theo Phương Thức Thanh Toán
        </div>
    </div>
    <div class="chart-body">
        <canvas id="revenueByPaymentMethodChart"></canvas>
    </div>
</div>

<div class="table-container">
    <div class="chart-header">
        <div class="chart-title">
            <i class="fas fa-users"></i> Top 10 Khách Hàng Có Doanh Thu Cao Nhất
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Khách Hàng</th>
                    <th>Số Lượng Hóa Đơn</th>
                    <th>Tổng Chi Tiêu</th>
                    <th>Tỷ Lệ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoicesByUser as $userInvoice)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: bold; margin-right: 10px;">
                                {{ substr($userInvoice->user->Hoten ?? 'N/A', 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 500;">{{ $userInvoice->user->Hoten ?? 'N/A' }}</div>
                                <div style="font-size: 12px; color: #6c757d;">{{ $userInvoice->user->Email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $userInvoice->count }}</td>
                    <td>{{ number_format($userInvoice->total, 0, ',', '.') }} VNĐ</td>
                    <td>
                        <div>{{ number_format($totalRevenue > 0 ? ($userInvoice->total / $totalRevenue * 100) : 0, 1) }}%</div>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: {{ $totalRevenue > 0 ? ($userInvoice->total / $totalRevenue * 100) : 0 }}%; background-color: var(--primary-color);"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ doanh thu theo ngày
    const revenueByDateCtx = document.getElementById('revenueByDateChart').getContext('2d');
    const revenueByDateChart = new Chart(revenueByDateCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($invoicesByDate as $invoice)
                    '{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Doanh thu',
                data: [
                    @foreach($invoicesByDate as $invoice)
                        {{ $invoice->total }},
                    @endforeach
                ],
                backgroundColor: 'rgba(255, 107, 139, 0.2)',
                borderColor: 'rgba(255, 107, 139, 1)',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: 'rgba(255, 107, 139, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + context.raw.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ hóa đơn theo trạng thái
    const invoicesByStatusCtx = document.getElementById('invoicesByStatusChart').getContext('2d');
    const invoicesByStatusChart = new Chart(invoicesByStatusCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($invoicesByStatus as $status)
                    '{{ $status->trangThai->Tentrangthai }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($invoicesByStatus as $status)
                        {{ $status->count }},
                    @endforeach
                ],
                backgroundColor: [
                    '#28a745', // Đã thanh toán
                    '#ffc107', // Chờ thanh toán
                    '#dc3545', // Đã hủy
                    '#17a2b8'  // Khác
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ doanh thu theo phương thức thanh toán
    const revenueByPaymentMethodCtx = document.getElementById('revenueByPaymentMethodChart').getContext('2d');
    const revenueByPaymentMethodChart = new Chart(revenueByPaymentMethodCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($invoicesByPaymentMethod as $method)
                    '{{ $method->phuongThuc->TenPT }}',
                @endforeach
            ],
            datasets: [{
                label: 'Doanh thu',
                data: [
                    @foreach($invoicesByPaymentMethod as $method)
                        {{ $method->total }},
                    @endforeach
                ],
                backgroundColor: 'rgba(255, 107, 139, 0.7)',
                borderColor: 'rgba(255, 107, 139, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + context.raw.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection