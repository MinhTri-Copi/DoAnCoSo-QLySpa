@extends('backend.layouts.app')

@section('title', 'Thống Kê Đặt Lịch')

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
</style>

<div class="header-container">
    <div>
        <div class="header-title">Thống Kê Đặt Lịch</div>
        <div class="header-subtitle">Phân tích dữ liệu đặt lịch</div>
    </div>
    <a href="{{ route('admin.datlich.index') }}" class="btn-white">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<div class="filter-container">
    <div class="filter-title">
        <i class="fas fa-filter"></i> Bộ Lọc Thống Kê
    </div>
    <form action="{{ route('admin.datlich.statistics') }}" method="GET" class="filter-form">
        <div class="filter-group">
            <label for="from_date" class="filter-label">Từ Ngày</label>
            <input type="date" id="from_date" name="from_date" class="filter-control" value="{{ request('from_date', $startDate->format('Y-m-d')) }}">
        </div>
        <div class="filter-group">
            <label for="to_date" class="filter-label">Đến Ngày</label>
            <input type="date" id="to_date" name="to_date" class="filter-control" value="{{ request('to_date', $endDate->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="filter-button">
            <i class="fas fa-search"></i> Áp Dụng
        </button>
    </form>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #FF6B8B;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-value">{{ $totalBookings }}</div>
        <div class="stat-label">Tổng Số Lịch Đặt</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #ffc107;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $pendingCount }}</div>
        <div class="stat-label">Chờ Xác Nhận</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #28a745;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $confirmedCount }}</div>
        <div class="stat-label">Đã Xác Nhận</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #dc3545;">
            <i class="fas fa-ban"></i>
        </div>
        <div class="stat-value">{{ $cancelledCount }}</div>
        <div class="stat-label">Đã Hủy</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #17a2b8;">
            <i class="fas fa-flag-checkered"></i>
        </div>
        <div class="stat-value">{{ $completedCount }}</div>
        <div class="stat-label">Hoàn Thành</div>
    </div>
</div>

<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title">
            <i class="fas fa-chart-line"></i> Biểu Đồ Đặt Lịch Theo Ngày
        </div>
    </div>
    <div class="chart-body">
        <canvas id="bookingsByDateChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ đặt lịch theo ngày
    const bookingsByDateCtx = document.getElementById('bookingsByDateChart').getContext('2d');
    const bookingsByDateChart = new Chart(bookingsByDateCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($bookingsByDate as $booking)
                    '{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Số lượng đặt lịch',
                data: [
                    @foreach($bookingsByDate as $booking)
                        {{ $booking->count }},
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
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: 'rgba(255, 107, 139, 0.3)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        title: function(tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function(context) {
                            return 'Số lượng đặt lịch: ' + context.raw;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection 