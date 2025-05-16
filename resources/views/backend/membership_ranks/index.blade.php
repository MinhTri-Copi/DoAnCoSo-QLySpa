@extends('backend.layouts.app')

@section('title', 'Quản Lý Hạng Thành Viên')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection

@section('content')
<style>
    /* ===== MODERN SPA DESIGN ===== */
    :root {
        --primary-pink: #ff6b95;
        --light-pink: #ffd0d9;
        --dark-pink: #e84a78;
        --text-primary: #2c3e50;
        --text-secondary: #7a8ca0;
        --info: #3498db;
        --primary: #8e44ad;
        --danger: #e74c3c;
        --warning: #f39c12;
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
    .header-container {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        border-radius: var(--radius-lg);
        padding: 2.2rem 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-pink);
        max-height: 160px;
    }

    .header-container::before {
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

    .header-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.4rem;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 4;
    }

    .header-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        position: relative;
        z-index: 4;
    }

    .btn-pink {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-pink);
        border: none;
        font-size: 1.05rem;
        font-weight: 600;
        padding: 0.8rem 1.7rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: var(--transition-fast);
        text-decoration: none;
        position: relative;
        z-index: 4;
    }

    .btn-pink i {
        font-size: 0.8rem;
        background: rgba(255, 107, 149, 0.15);
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: var(--transition-fast);
    }

    .btn-pink:hover {
        background: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-pink:hover i {
        background: rgba(255, 107, 149, 0.25);
    }

    .header-buttons {
        display: flex;
        align-items: center;
        z-index: 4;
        position: relative;
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
        border-radius: var(--radius-md);
        padding: 20px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: var(--transition-medium);
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    .stat-card:nth-child(5) { animation-delay: 0.5s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
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
        position: relative;
        z-index: 1;
    }

    .stat-icon::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        opacity: 0.2;
        z-index: -1;
        transform: scale(1.3);
        transition: var(--transition-medium);
    }

    .stat-card:hover .stat-icon::after {
        transform: scale(1.8);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--text-primary);
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .stat-progress {
        height: 5px;
        background-color: var(--light-gray);
        border-radius: 20px;
        margin-top: 15px;
        overflow: hidden;
    }

    .stat-progress-bar {
        height: 100%;
        border-radius: 20px;
        width: 0;
        animation: progressGrow 1.5s ease forwards;
    }

    @keyframes progressGrow {
        to { width: var(--progress-width, 75%); }
    }

    .content-card {
        background-color: white;
        border-radius: var(--radius-md);
        padding: 25px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 30px;
        animation: fadeIn 0.6s ease forwards;
        animation-delay: 0.6s;
        opacity: 0;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--light-gray);
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--primary-pink);
        margin-right: 10px;
        font-size: 1.1rem;
    }

    /* Rest of the styles can remain the same but let's update the badges */
    .badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-left: 8px;
    }

    .badge-silver {
        background-color: #f1f3f5;
        color: #495057;
    }

    .badge-gold {
        background-color: #ffd700;
        color: #856404;
    }

    .badge-platinum {
        background-color: #e2e2e2;
        color: #444;
        border: 1px solid #ccc;
    }

    .badge-diamond {
        background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        color: #1a477e;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        margin: 0 3px;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
    }

    .btn-view {
        background-color: var(--info);
    }

    .btn-edit {
        background-color: var(--warning);
    }

    .btn-delete {
        background-color: var(--danger);
    }

    .btn-action:hover {
        opacity: 0.85;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: var(--light-gray);
        padding: 14px 18px;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        border-bottom: 2px solid #e9ecef;
    }

    .table td {
        padding: 14px 18px;
        border-bottom: 1px solid var(--light-gray);
        vertical-align: middle;
        transition: var(--transition-fast);
    }

    .table tr {
        transition: var(--transition-fast);
        cursor: pointer;
    }

    .table tr:hover {
        background-color: rgba(247, 249, 252, 0.7);
    }

    .table tr:hover td {
        color: var(--primary-pink);
    }

    /* Styling for pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
        gap: 8px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: var(--white);
        color: var(--text-primary);
        border: 1px solid #e9ecef;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 1px 5px rgba(0,0,0,0.03);
        cursor: pointer;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        color: white;
        border-color: var(--primary-pink);
        box-shadow: 0 5px 15px rgba(255, 107, 149, 0.2);
        transform: translateY(-2px);
    }

    .page-link:hover:not(.active) {
        background-color: #f6f9fc;
        border-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.07);
    }

    .page-item.disabled .page-link {
        color: #adb5bd;
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #edeff2;
    }

    .page-item.prev-next .page-link {
        background-color: var(--light-gray);
        color: var(--primary-pink);
        font-size: 1.1rem;
    }

    .page-item.prev-next .page-link:hover {
        background-color: var(--primary-pink);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 149, 0.2);
    }

    /* Animation for pagination */
    .pagination .page-item {
        opacity: 0;
        animation: fadeInUp 0.4s ease forwards;
    }

    .pagination .page-item:nth-child(1) { animation-delay: 0.7s; }
    .pagination .page-item:nth-child(2) { animation-delay: 0.75s; }
    .pagination .page-item:nth-child(3) { animation-delay: 0.8s; }
    .pagination .page-item:nth-child(4) { animation-delay: 0.85s; }
    .pagination .page-item:nth-child(5) { animation-delay: 0.9s; }

    /* Styling for filters */
    .search-filter-container {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
        animation: fadeIn 0.6s ease forwards;
        animation-delay: 0.65s;
        opacity: 0;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 14px 20px 14px 50px;
        border: 1px solid var(--light-gray);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        color: var(--text-primary);
        background-color: #f9fafc;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 0 0 4px rgba(255, 107, 149, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1.05rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus + i {
        color: var(--primary-pink);
    }

    .filter-box {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .filter-select-container {
        position: relative;
        min-width: 200px;
    }

    .filter-select-container::after {
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .filter-select-container:hover::after {
        color: var(--primary-pink);
    }

    .filter-select {
        appearance: none;
        width: 100%;
        padding: 14px 20px;
        border: 1px solid var(--light-gray);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        color: var(--text-primary);
        background-color: #f9fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 0 0 4px rgba(255, 107, 149, 0.1);
    }

    .filter-badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: var(--light-gray);
        color: var(--text-secondary);
        border-radius: 30px;
        font-size: 0.75rem;
        margin-left: 10px;
        transition: all 0.3s ease;
    }

    .filter-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 15px;
        height: 48px;
        background-color: var(--light-gray);
        color: var(--text-secondary);
        border: none;
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .filter-btn:hover {
        background-color: var(--primary-pink);
        color: white;
        box-shadow: 0 5px 15px rgba(255, 107, 149, 0.2);
    }

    .filter-btn i {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .search-filter-container {
            flex-direction: column;
        }
        
        .filter-box {
            width: 100%;
        }
        
        .filter-select-container {
            flex: 1;
            min-width: auto;
        }
    }
</style>

<div class="header-container">
    <div class="header-shimmer"></div>
    <div>
        <div class="header-title">Quản Lý Hạng Thành Viên</div>
        <div class="header-subtitle">Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất</div>
    </div>
    <div class="header-buttons">
        <a href="{{ route('admin.membership_ranks.cleanup-duplicates') }}" class="btn-pink" style="margin-right: 10px; background-color: #e74c3c;" onclick="return confirm('Bạn có chắc chắn muốn dọn dẹp các hạng thành viên trùng lặp không?');">
            <i class="fas fa-broom"></i> Dọn Trùng Lặp
        </a>
        <a href="{{ route('admin.membership_ranks.update-all') }}" class="btn-pink" style="margin-right: 10px; background-color: #17a2b8;">
            <i class="fas fa-sync-alt"></i> Cập Nhật Tất Cả Hạng
        </a>
        <a href="{{ route('admin.membership_ranks.create') }}" class="btn-pink">
            <i class="fas fa-plus"></i> Thêm Hạng Thành Viên Mới
        </a>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: var(--primary-pink);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $totalRanks ?? count($ranks) }}</div>
        <div class="stat-label">Tổng Hạng Thành Viên</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: var(--primary-pink); --progress-width: 100%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #c0c0c0;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-value">{{ $silverCount ?? $ranks->where('Tenhang', 'Thành viên Bạc')->count() }}</div>
        <div class="stat-label">Thành Viên Bạc</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #c0c0c0; --progress-width: 75%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #ffd700;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-value">{{ $goldCount ?? $ranks->where('Tenhang', 'Thành viên Vàng')->count() }}</div>
        <div class="stat-label">Thành Viên Vàng</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #ffd700; --progress-width: 60%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #e5e4e2;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-value">{{ $platinumCount ?? $ranks->where('Tenhang', 'Thành viên Bạch Kim')->count() }}</div>
        <div class="stat-label">Thành Viên Bạch Kim</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #e5e4e2; --progress-width: 45%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #b9f2ff;">
            <i class="fas fa-crown"></i>
        </div>
        <div class="stat-value">{{ $diamondCount ?? $ranks->where('Tenhang', 'Thành viên Kim Cương')->count() }}</div>
        <div class="stat-label">Thành Viên Kim Cương</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background: linear-gradient(90deg, #a1c4fd 0%, #c2e9fb 100%); --progress-width: 30%;"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Danh Sách Hạng Thành Viên
        </div>
        <div>
            <button class="btn-action" style="background-color: var(--primary-pink);" id="toggleFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    
    <div class="search-filter-container" id="filterContainer">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, mã, mô tả...">
            <i class="fas fa-search"></i>
        </div>
        
        <div class="filter-box">
            <div class="filter-select-container">
                <select class="filter-select" id="rankTypeFilter">
                    <option value="">Tất cả hạng thành viên</option>
                    <option value="Thành viên Bạc">Thành viên Bạc</option>
                    <option value="Thành viên Vàng">Thành viên Vàng</option>
                    <option value="Thành viên Bạch Kim">Thành viên Bạch Kim</option>
                    <option value="Thành viên Kim Cương">Thành viên Kim Cương</option>
                </select>
            </div>
            
            <button class="filter-btn" id="applyFilters">
                <i class="fas fa-sliders-h"></i> Áp dụng
            </button>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="table-responsive">
        <table class="table" id="ranksTable">
            <thead>
                <tr>
                    <th>Mã Hạng</th>
                    <th>Tên Hạng</th>
                    <th>Mô Tả</th>
                    <th>Người Dùng</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ranks as $rank)
                <tr class="rank-row" data-url="{{ route('admin.membership_ranks.show', $rank->Mahang) }}" data-rank-type="{{ $rank->Tenhang }}">
                    <td>{{ $rank->Mahang }}</td>
                    <td>
                        {{ $rank->Tenhang }}
                        @php
                            $badgeClass = 'badge-silver';
                            if($rank->Tenhang == 'Thành viên Vàng') {
                                $badgeClass = 'badge-gold';
                            } elseif($rank->Tenhang == 'Thành viên Bạch Kim') {
                                $badgeClass = 'badge-platinum';
                            } elseif($rank->Tenhang == 'Thành viên Kim Cương') {
                                $badgeClass = 'badge-diamond';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}" style="margin-left: 5px;">{{ $rank->Tenhang }}</span>
                    </td>
                    <td>{{ $rank->Mota }}</td>
                    <td>{{ $rank->user->Hoten ?? 'N/A' }}</td>
                    <td onclick="event.stopPropagation()">
                        <div class="action-buttons">
                            <a href="{{ route('admin.membership_ranks.edit', $rank->Mahang) }}" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.membership_ranks.confirm-destroy', $rank->Mahang) }}" class="btn-action btn-delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <h4>Không có dữ liệu</h4>
                            <p>Chưa có hạng thành viên nào được tạo. Hãy thêm hạng thành viên mới.</p>
                            <a href="{{ route('admin.membership_ranks.create') }}" class="btn-pink">
                                <i class="fas fa-plus"></i> Thêm Hạng Thành Viên
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container">
        <ul class="pagination">
            <li class="page-item prev-next {{ $ranks->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $ranks->previousPageUrl() }}" {{ $ranks->onFirstPage() ? 'aria-disabled="true"' : '' }}>
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            
            @for ($i = 1; $i <= $ranks->lastPage(); $i++)
                <li class="page-item {{ $ranks->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $ranks->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            
            <li class="page-item prev-next {{ $ranks->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $ranks->nextPageUrl() }}" {{ $ranks->hasMorePages() ? '' : 'aria-disabled="true"' }}>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
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
    
    // Row click navigation
    const rankRows = document.querySelectorAll('.rank-row');
    
    rankRows.forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.url;
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const rankTypeFilter = document.getElementById('rankTypeFilter');
    const table = document.getElementById('ranksTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const rankType = rankTypeFilter.value;
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            if (row.cells.length < 5) continue; // Skip empty state row
            
            const mahang = row.cells[0].textContent.toLowerCase();
            const tenhang = row.cells[1].textContent.toLowerCase();
            const mota = row.cells[2].textContent.toLowerCase();
            const user = row.cells[3].textContent.toLowerCase();
            
            // Lấy giá trị data-rank-type để so sánh chính xác
            const rowRankType = row.getAttribute('data-rank-type');
            
            const matchesSearch = mahang.includes(searchTerm) || 
                                tenhang.includes(searchTerm) || 
                                mota.includes(searchTerm) || 
                                user.includes(searchTerm);
                                
            const matchesRankType = rankType === '' || rowRankType === rankType;
            
            row.style.display = (matchesSearch && matchesRankType) ? '' : 'none';
        }
    }
    
    searchInput.addEventListener('keyup', filterTable);
    rankTypeFilter.addEventListener('change', filterTable);
    
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