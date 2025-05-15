@extends('backend.layouts.app')

@section('title', 'Quản Lý Hạng Thành Viên')

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

    .btn-pink {
        background-color: var(--text-on-primary);
        color: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }

    .btn-pink:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-pink i {
        margin-right: 8px;
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

    .stat-progress {
        height: 4px;
        background-color: #e9ecef;
        border-radius: 2px;
        margin-top: 15px;
        overflow: hidden;
    }

    .stat-progress-bar {
        height: 100%;
        border-radius: 2px;
    }

    .progress-1 {
        background-color: #4cd964;
        width: 75%;
    }

    .progress-2 {
        background-color: var(--primary-color);
        width: 45%;
    }

    .progress-3 {
        background-color: #007bff;
        width: 60%;
    }

    .content-card {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .search-filter-container {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-box {
        display: flex;
        gap: 10px;
    }

    .filter-select {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 14px;
        min-width: 150px;
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

    .badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-silver {
        background-color: #e9ecef;
        color: #495057;
    }

    .badge-gold {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-platinum {
        background-color: #6c757d;
        color: white;
    }

    .badge-diamond {
        background-color: #17a2b8;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-view {
        background-color: var(--info-color);
    }

    .btn-edit {
        background-color: var(--warning-color);
    }

    .btn-delete {
        background-color: var(--danger-color);
    }

    .btn-action:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 5px;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: white;
        border: 1px solid var(--border-color);
        color: #495057;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .page-link:hover {
        background-color: #f8f9fa;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .empty-state h4 {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #adb5bd;
        max-width: 400px;
        margin: 0 auto 20px;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-pink {
            margin-top: 15px;
            align-self: flex-start;
        }
        
        .stats-container {
            flex-direction: column;
        }
        
        .search-filter-container {
            flex-direction: column;
        }
    }
</style>

<div class="header-container">
    <div>
        <div class="header-title">Quản Lý Hạng Thành Viên</div>
        <div class="header-subtitle">Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất</div>
    </div>
    <a href="{{ route('admin.membership_ranks.create') }}" class="btn-pink">
        <i class="fas fa-plus"></i> Thêm Hạng Thành Viên Mới
    </a>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $totalRanks ?? count($ranks) }}</div>
        <div class="stat-label">Tổng Hạng Thành Viên</div>
        <div class="stat-progress">
            <div class="stat-progress-bar progress-1"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #e9ecef;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-value">{{ $silverCount ?? $ranks->where('Tenhang', 'Thành viên Bạc')->count() }}</div>
        <div class="stat-label">Thành Viên Bạc</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #e9ecef; width: 75%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #ffc107;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-value">{{ $goldCount ?? $ranks->where('Tenhang', 'Thành viên Vàng')->count() }}</div>
        <div class="stat-label">Thành Viên Vàng</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #ffc107; width: 50%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #6c757d;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-value">{{ $platinumCount ?? $ranks->where('Tenhang', 'Thành viên Bạch Kim')->count() }}</div>
        <div class="stat-label">Thành Viên Bạch Kim</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #6c757d; width: 35%;"></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #17a2b8;">
            <i class="fas fa-crown"></i>
        </div>
        <div class="stat-value">{{ $diamondCount ?? $ranks->where('Tenhang', 'Thành viên Kim Cương')->count() }}</div>
        <div class="stat-label">Thành Viên Kim Cương</div>
        <div class="stat-progress">
            <div class="stat-progress-bar" style="background-color: #17a2b8; width: 25%;"></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Danh Sách Hạng Thành Viên
        </div>
        <div>
            <button class="btn-action" style="background-color: var(--primary-color);" id="toggleFilters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    
    <div class="search-filter-container" id="filterContainer">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, mã...">
        </div>
        
        <div class="filter-box">
            <select class="filter-select" id="rankTypeFilter">
                <option value="">Tất cả hạng</option>
                <option value="Thành viên Bạc">Thành viên Bạc</option>
                <option value="Thành viên Vàng">Thành viên Vàng</option>
                <option value="Thành viên Bạch Kim">Thành viên Bạch Kim</option>
                <option value="Thành viên Kim Cương">Thành viên Kim Cương</option>
            </select>
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
                <tr>
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
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.membership_ranks.show', $rank->Mahang) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.membership_ranks.edit', $rank->Mahang) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.membership_ranks.confirm-destroy', $rank->Mahang) }}" class="btn-action btn-delete">
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
    <div class="pagination">
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">
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
            
            const matchesSearch = mahang.includes(searchTerm) || 
                                tenhang.includes(searchTerm) || 
                                mota.includes(searchTerm) || 
                                user.includes(searchTerm);
                                
            const matchesRankType = rankType === '' || tenhang.includes(rankType.toLowerCase());
            
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