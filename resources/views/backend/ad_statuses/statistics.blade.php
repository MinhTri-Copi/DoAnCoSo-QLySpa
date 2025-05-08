@extends('backend.layouts.app')

@section('title', 'Thống Kê Trạng Thái Quảng Cáo')

@section('styles')
<style>
    /* ===== MODERN SPA DESIGN ===== */
    :root {
        --primary-pink: #ff6b95;
        --light-pink: #ffdbe9;
        --dark-pink: #e84a78;
        --text-primary: #2c3e50;
        --text-secondary: #7a8ca0;
        --info: #3498db;
        --primary: #8e44ad;
        --danger: #e74c3c;
        --light-gray: #f7f9fc;
        --white: #ffffff;
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 5px 25px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.09);
        --shadow-pink: 0 8px 25px rgba(255, 107, 149, 0.14);
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
    }

    /* ===== HEADER ===== */
    .spa-dashboard-header {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        border-radius: var(--radius-lg);
        padding: 1.8rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-pink);
    }

    .spa-dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
        animation: pulse 6s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.1); opacity: 0.8; }
    }

    .header-shimmer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.1) 20%, 
            rgba(255,255,255,0.2) 40%, 
            rgba(255,255,255,0.1) 60%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 5s infinite linear;
        z-index: 2;
        pointer-events: none;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .glitter-dot {
        position: absolute;
        background: white;
        border-radius: 50%;
        opacity: 0;
        z-index: 3;
        box-shadow: 0 0 10px 2px rgba(255,255,255,0.8);
        animation: glitter 8s infinite;
    }

    .glitter-dot:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 25%;
        left: 10%;
        animation-delay: 0s;
    }

    .glitter-dot:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 40%;
        left: 30%;
        animation-delay: 2s;
    }

    .glitter-dot:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 20%;
        right: 25%;
        animation-delay: 4s;
    }

    .glitter-dot:nth-child(4) {
        width: 5px;
        height: 5px;
        bottom: 30%;
        right: 15%;
        animation-delay: 6s;
    }

    @keyframes glitter {
        0% { transform: scale(0); opacity: 0; }
        10% { transform: scale(1); opacity: 0.8; }
        20% { transform: scale(0.2); opacity: 0.2; }
        30% { transform: scale(1.2); opacity: 0.7; }
        40% { transform: scale(0.5); opacity: 0.5; }
        50% { transform: scale(1); opacity: 0.9; }
        60% { transform: scale(0.3); opacity: 0.3; }
        100% { transform: scale(0); opacity: 0; }
    }

    .spa-header-content {
        position: relative;
        z-index: 4;
        max-width: 70%;
    }

    .spa-header-title {
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.3rem;
        letter-spacing: 0.5px;
    }

    .spa-header-subtitle {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        display: flex;
        align-items: center;
    }

    .spa-header-subtitle i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }

    .spa-header-actions {
        position: relative;
        z-index: 4;
    }

    /* ===== BUTTONS ===== */
    .spa-btn {
        font-weight: 600;
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: var(--transition-fast);
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        box-shadow: var(--shadow-sm);
        text-decoration: none;
    }

    .spa-btn i {
        font-size: 0.9rem;
    }

    .spa-btn-secondary {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-pink);
    }

    .spa-btn-secondary:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: var(--dark-pink);
        text-decoration: none;
    }

    /* ===== CONTENT WRAPPER ===== */
    .spa-content-wrapper {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
        position: relative;
    }

    .spa-content-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .spa-content-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .spa-content-title i {
        color: var(--primary-pink);
    }

    .spa-content-body {
        padding: 2rem;
    }

    /* ===== STATS CARDS ===== */
    .spa-stats-card {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        transition: var(--transition-fast);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .spa-stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .spa-stats-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .spa-stats-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .spa-stats-icon.pink {
        background: var(--light-pink);
    }

    .spa-stats-icon.pink i {
        color: var(--primary-pink);
    }

    .spa-stats-icon.purple {
        background: rgba(142, 68, 173, 0.1);
    }

    .spa-stats-icon.purple i {
        color: var(--primary);
    }

    .spa-stats-icon.blue {
        background: rgba(52, 152, 219, 0.1);
    }

    .spa-stats-icon.blue i {
        color: var(--info);
    }

    .spa-stats-icon i {
        font-size: 1.2rem;
    }

    .spa-stats-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .spa-stats-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0.5rem 0;
    }

    .spa-stats-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* ===== DONUT CHART ===== */
    .spa-chart-container {
        position: relative;
        padding: 1rem;
        height: 100%;
    }

    .spa-chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        text-align: center;
    }

    .spa-donut-container {
        position: relative;
        width: 100%;
        max-width: 250px;
        margin: 0 auto;
    }

    .spa-donut-chart {
        width: 100%;
        height: auto;
    }

    .spa-donut-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        font-weight: 700;
        color: var(--text-primary);
    }

    .spa-donut-label .total {
        font-size: 2rem;
        display: block;
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .spa-donut-label .text {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* ===== LEGEND ===== */
    .spa-chart-legend {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        margin-top: 1.5rem;
    }

    .spa-legend-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    .spa-legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 8px;
    }

    .spa-legend-label {
        flex: 1;
    }

    .spa-legend-value {
        font-weight: 600;
    }

    /* ===== LINE CHART ===== */
    .spa-line-chart-container {
        height: 320px;
        width: 100%;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 767px) {
        .spa-dashboard-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .spa-header-content {
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .spa-header-actions {
            align-self: flex-start;
        }

        .spa-content-body {
            padding: 1.5rem 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Modern SPA Dashboard Header -->
    <div class="spa-dashboard-header">
        <div class="header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="spa-header-content">
            <h1 class="spa-header-title">Thống Kê Trạng Thái Quảng Cáo</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-chart-pie"></i>
                Phân tích và thống kê về việc sử dụng các trạng thái quảng cáo trong hệ thống
            </p>
        </div>
        <div class="spa-header-actions">
            <a href="{{ route('admin.ad-statuses.index') }}" class="spa-btn spa-btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="spa-stats-card">
                <div class="spa-stats-header">
                    <div class="spa-stats-icon pink">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h4 class="spa-stats-title">Tổng Trạng Thái</h4>
                </div>
                <div class="spa-stats-value">{{ $statusCounts->count() }}</div>
                <p class="spa-stats-desc">Số lượng trạng thái quảng cáo trong hệ thống</p>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="spa-stats-card">
                <div class="spa-stats-header">
                    <div class="spa-stats-icon blue">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h4 class="spa-stats-title">Tổng Quảng Cáo</h4>
                </div>
                <div class="spa-stats-value">{{ $statusCounts->sum('quangCao_count') }}</div>
                <p class="spa-stats-desc">Tổng số quảng cáo được quản lý bởi các trạng thái</p>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="spa-stats-card">
                <div class="spa-stats-header">
                    <div class="spa-stats-icon purple">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <h4 class="spa-stats-title">Hiệu Suất Sử Dụng</h4>
                </div>
                @php
                    $totalStatuses = $statusCounts->count();
                    $usedStatuses = $statusCounts->where('quangCao_count', '>', 0)->count();
                    $utilizationRate = $totalStatuses > 0 ? round(($usedStatuses / $totalStatuses) * 100) : 0;
                @endphp
                <div class="spa-stats-value">{{ $utilizationRate }}%</div>
                <p class="spa-stats-desc">Tỷ lệ trạng thái đang được sử dụng</p>
            </div>
        </div>
    </div>

    <!-- Distribution and Trends -->
    <div class="row">
        <!-- Distribution -->
        <div class="col-md-5 mb-4">
            <div class="spa-content-wrapper h-100">
                <div class="spa-content-header">
                    <h2 class="spa-content-title">
                        <i class="fas fa-chart-pie"></i>
                        Phân Bố Quảng Cáo
                    </h2>
                </div>
                <div class="spa-content-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="spa-chart-container">
                                <h3 class="spa-chart-title">Phân bố theo trạng thái</h3>
                                <div class="spa-donut-container">
                                    <canvas id="distributionChart" class="spa-donut-chart"></canvas>
                                    <div class="spa-donut-label">
                                        <span class="total">{{ $statusCounts->sum('quangCao_count') }}</span>
                                        <span class="text">Tổng quảng cáo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="spa-chart-legend">
                                @php
                                    $colors = [
                                        '#FF6B95', '#FF92B6', '#FFA9C3', '#FFC1D4', '#FFD8E5', 
                                        '#8E44AD', '#9B59B6', '#A87BB7', '#C39BD3', '#D7BDE2',
                                        '#3498DB', '#5DADE2', '#85C1E9', '#AED6F1', '#D6EAF8'
                                    ];
                                @endphp
                                
                                @foreach($statusCounts as $index => $status)
                                    <div class="spa-legend-item">
                                        <span class="spa-legend-color" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                                        <span class="spa-legend-label">{{ $status->TenTT }}</span>
                                        <span class="spa-legend-value">{{ $status->quangCao_count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trends -->
        <div class="col-md-7 mb-4">
            <div class="spa-content-wrapper h-100">
                <div class="spa-content-header">
                    <h2 class="spa-content-title">
                        <i class="fas fa-chart-line"></i>
                        Xu Hướng Theo Thời Gian
                    </h2>
                </div>
                <div class="spa-content-body">
                    <div class="spa-line-chart-container">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Details Table -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-list"></i>
                Chi Tiết Trạng Thái
            </h2>
        </div>
        <div class="spa-content-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã TT</th>
                            <th>Tên Trạng Thái</th>
                            <th class="text-center">Tỷ Lệ (%)</th>
                            <th class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAds = $statusCounts->sum('quangCao_count');
                        @endphp
                        
                        @foreach($statusCounts as $status)
                            <tr>
                                <td>{{ $status->MaTTQC }}</td>
                                <td>{{ $status->TenTT }}</td>
                                <td class="text-center">{{ $totalAds > 0 ? round(($status->quangCao_count / $totalAds) * 100, 1) : 0 }}%</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.ad-statuses.show', $status->MaTTQC) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Chi Tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for Distribution Chart
        const statusLabels = {!! json_encode($statusCounts->pluck('TenTT')) !!};
        const statusData = {!! json_encode($statusCounts->pluck('quangCao_count')) !!};
        const colors = [
            '#FF6B95', '#FF92B6', '#FFA9C3', '#FFC1D4', '#FFD8E5', 
            '#8E44AD', '#9B59B6', '#A87BB7', '#C39BD3', '#D7BDE2',
            '#3498DB', '#5DADE2', '#85C1E9', '#AED6F1', '#D6EAF8'
        ];
        
        // Create Distribution Chart
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        const distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: colors.slice(0, statusData.length),
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Create Trends Chart
        const trendsChartData = {!! $chartData !!};
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        
        // Process data for trends chart
        const datasets = [];
        for (let i = 0; i < trendsChartData.length; i++) {
            datasets.push({
                label: trendsChartData[i].name,
                data: trendsChartData[i].data,
                borderColor: colors[i % colors.length],
                backgroundColor: colors[i % colors.length] + '33', // Add transparency
                tension: 0.3,
                pointBackgroundColor: colors[i % colors.length],
                pointRadius: 3,
                fill: false
            });
        }
        
        const trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'month',
                            displayFormats: {
                                month: 'MMM yyyy'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Thời gian'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số quảng cáo'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Xu hướng sử dụng trạng thái theo thời gian'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    });
</script>
@endsection 