@extends('customer.layouts.app')

@section('title', 'Lịch sử điểm thưởng')

@section('content')
@php
    $user = auth()->user()->user ?? null;
    $lsDiemThuong = $user ? $user->lsDiemThuong()->orderByDesc('Thoigian')->get() : collect();
    
    // Calculate statistics
    $totalEarned = $lsDiemThuong->where('Sodiem', '>', 0)->sum('Sodiem');
    $totalUsed = abs($lsDiemThuong->where('Sodiem', '<', 0)->sum('Sodiem'));
    $currentBalance = $totalEarned - $totalUsed;
@endphp

<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-dark: #e55a8a;
        --primary-light: #ffb3d1;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .points-container {
        background: var(--bg-gradient);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .page-header {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow-lg);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-light) 100%);
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .title-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(255, 107, 157, 0.3);
    }

    .title-text h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .title-text p {
        color: #6b7280;
        margin: 0;
        font-size: 1rem;
    }

    .header-action .btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border: none;
        border-radius: 12px;
        font-weight: 600;
        color: white;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(255, 107, 157, 0.3);
    }

    .header-action .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 107, 157, 0.4);
        color: white;
        text-decoration: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-lg);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient);
    }

    .stat-card.earned::before {
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    }

    .stat-card.used::before {
        background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
    }

    .stat-card.balance::before {
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-light) 100%);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .stat-icon.earned {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    }

    .stat-icon.used {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    }

    .stat-icon.balance {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        margin: 0;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .stat-trend {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow-lg);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .card-header-modern {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    .filter-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #374151;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        text-decoration: none;
    }

    .table-container {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .modern-table td {
        padding: 1rem 1.5rem;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: #fef7ff;
    }

    .record-id {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.875rem;
    }

    .record-time {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .points-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .points-badge.positive {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #10b981;
    }

    .points-badge.negative {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .user-id, .invoice-id {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.875rem;
        color: #6b7280;
        background: #f9fafb;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin: 0 0 0.5rem 0;
    }

    .empty-text {
        color: #6b7280;
        margin: 0;
    }

    @media (max-width: 768px) {
        .points-container {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .title-text h1 {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .card-header-modern {
            padding: 1rem 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filter-section {
            width: 100%;
            justify-content: flex-start;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .points-badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
    }
</style>

<div class="points-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <div class="title-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="title-text">
                        <h1>Lịch sử điểm thưởng</h1>
                        <p>Theo dõi và quản lý điểm thưởng của bạn</p>
                    </div>
                </div>
                <div class="header-action">
                    <a href="{{ route('customer.thanhvien.index') }}" class="btn">
                        <i class="fas fa-crown"></i>
                        Xem hạng thành viên
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card earned">
                <div class="stat-header">
                    <div class="stat-icon earned">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <p class="stat-label">Điểm đã tích lũy</p>
                </div>
                <h3 class="stat-value">{{ number_format($totalEarned) }}</h3>
                <p class="stat-trend">Tổng điểm kiếm được</p>
            </div>

            <div class="stat-card balance">
                <div class="stat-header">
                    <div class="stat-icon balance">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <p class="stat-label">Số dư hiện tại</p>
                </div>
                <h3 class="stat-value">{{ number_format($currentBalance) }}</h3>
                <p class="stat-trend">Điểm có thể sử dụng</p>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="main-card">
            <div class="card-header-modern">
                <h2 class="card-title">
                    <div class="card-title-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    Chi tiết giao dịch
                </h2>
                <!-- <div class="filter-section">
                    <a href="#" class="filter-btn active">Tất cả</a>
                    <a href="#" class="filter-btn">Tích lũy</a>
                    <a href="#" class="filter-btn">Sử dụng</a>
                    <a href="#" class="filter-btn">Tháng này</a>
                </div> -->
            </div>

            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Mã giao dịch</th>
                            <th>Thời gian</th>
                            <th>Số điểm</th>
                            <!-- <th>Mã người dùng</th> -->
                            <th>Mã hóa đơn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lsDiemThuong as $ls)
                            <tr>
                                <td>
                                    <span class="record-id">#{{ $ls->MaLSDT }}</span>
                                </td>
                                <td>
                                    <span class="record-time">
                                        {{ \Carbon\Carbon::parse($ls->Thoigian)->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="points-badge {{ $ls->Sodiem > 0 ? 'positive' : 'negative' }}">
                                        <i class="fas fa-{{ $ls->Sodiem > 0 ? 'plus' : 'minus' }}"></i>
                                        {{ $ls->Sodiem > 0 ? '+' : '' }}{{ number_format($ls->Sodiem) }}
                                    </span>
                                </td>
                                <!-- <td>
                                    <span class="user-id">{{ $ls->Manguoidung }}</span>
                                </td> -->
                                <td>
                                    @if($ls->MaHD)
                                        <span class="invoice-id">HD-{{ $ls->MaHD }}</span>
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <h3 class="empty-title">Chưa có lịch sử điểm thưởng</h3>
                                        <p class="empty-text">Bạn chưa có giao dịch điểm thưởng nào. Hãy sử dụng dịch vụ để tích lũy điểm!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
