@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/roles.css') }}" rel="stylesheet">
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
        max-height: 140px;
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

    .spa-dashboard-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
    }

    /* Shimmer effect */
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

    /* Dot animation */
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

    .spa-header-action {
        position: relative;
        z-index: 4;
    }

    .spa-btn-add {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-pink);
        border: none;
        font-size: 0.92rem;
        font-weight: 600;
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-btn-add i {
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

    .spa-btn-add:hover {
        background: white;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: var(--dark-pink);
        text-decoration: none;
    }

    .spa-btn-add:hover i {
        background: rgba(255, 107, 149, 0.25);
        transform: rotate(90deg);
    }

    /* ===== CONTENT WRAPPER ===== */
    .spa-content-wrapper {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 3rem;
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
    }

    .spa-content-title i {
        color: var(--primary-pink);
    }

    .spa-search-box {
        position: relative;
        max-width: 400px;
        margin: 0 0 1.5rem 0;
    }

    .spa-search-box input {
        width: 100%;
        padding: 0.8rem 1.2rem;
        padding-left: 3rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 50px;
        font-size: 0.95rem;
        transition: var(--transition-fast);
        background: var(--light-gray);
    }

    .spa-search-box input:focus {
        outline: none;
        border-color: var(--primary-pink);
        background: var(--white);
        box-shadow: 0 3px 15px rgba(255, 107, 149, 0.1);
    }

    .spa-search-box i {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .spa-content-body {
        padding: 1.5rem 2rem 2rem;
    }

    /* ===== ROLE TABLE ===== */
    .spa-role-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
        margin-top: -12px;
    }

    .spa-role-table thead th {
        background: transparent;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem 1.5rem 0.8rem;
        border: none;
        text-align: center;
    }

    .spa-role-table thead th:first-child,
    .spa-role-table thead th:nth-child(2) {
        text-align: left;
    }

    .spa-role-table tbody tr {
        background: var(--light-gray);
        box-shadow: var(--shadow-sm);
        transition: var(--transition-fast);
        border-radius: var(--radius-md);
    }

    .spa-role-table tbody tr td {
        padding: 1.2rem 1.5rem;
        border: none;
        vertical-align: middle;
    }

    .spa-role-table tbody tr td:first-child {
        border-top-left-radius: var(--radius-md);
        border-bottom-left-radius: var(--radius-md);
    }

    .spa-role-table tbody tr td:last-child {
        border-top-right-radius: var(--radius-md);
        border-bottom-right-radius: var(--radius-md);
    }

    .spa-role-table tbody tr:hover {
        background: var(--light-pink);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .spa-role-name {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--text-primary);
        margin-bottom: 0.2rem;
    }

    .spa-role-code {
        color: var(--text-secondary);
        font-size: 0.85rem;
        display: block;
    }

    .spa-role-id {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--primary-pink);
        background: var(--light-pink);
        border-radius: 50px;
    }

    .spa-role-level {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem 1.2rem;
        font-weight: 600;
        font-size: 0.95rem;
        color: #8e44ad;
        background: #f3e6f8;
        border-radius: 50px;
    }

    .spa-actions-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }

    .spa-action-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: var(--transition-fast);
        border: none;
        outline: none;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .spa-action-btn.view {
        background: rgba(52, 152, 219, 0.15);
        color: #3498db;
    }

    .spa-action-btn.edit {
        background: rgba(142, 68, 173, 0.15);
        color: #8e44ad;
    }

    .spa-action-btn.delete {
        background: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
    }

    .spa-action-btn:hover {
        transform: translateY(-2px);
    }

    .spa-action-btn.view:hover {
        background: #e6f7ff;
        color: #2980b9;
    }

    .spa-action-btn.edit:hover {
        background: #f4e6f8;
        color: #8e44ad;
    }

    .spa-action-btn.delete:hover {
        background: #fde4e2;
        color: #c0392b;
    }

    .spa-action-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Add a new style for showing tooltip on hover for disabled buttons */
    .spa-action-tooltip {
        position: relative;
    }

    .spa-action-tooltip .tooltip-text {
        visibility: hidden;
        width: 180px;
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 10;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.75rem;
        font-weight: normal;
        pointer-events: none;
    }

    .spa-action-tooltip .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
    }

    .spa-action-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    /* ===== EMPTY STATE ===== */
    .spa-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
    }

    .spa-empty-icon {
        font-size: 3.5rem;
        color: #dbe1e8;
        margin-bottom: 1.5rem;
    }

    .spa-empty-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.8rem;
    }

    .spa-empty-desc {
        font-size: 1rem;
        color: var(--text-secondary);
        max-width: 400px;
        margin: 0 auto 1.5rem;
    }

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 991px) {
        .spa-dashboard-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.2rem;
            max-height: none;
        }

        .spa-header-action {
            align-self: flex-start;
        }

        .spa-content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .spa-search-box {
            width: 100%;
            max-width: none;
        }
    }

    @media (max-width: 767px) {
        .spa-content-body {
            padding: 1rem;
            overflow-x: auto;
        }

        .spa-role-table thead th, 
        .spa-role-table tbody td {
            padding: 0.8rem;
        }

        .spa-action-btn {
            width: 32px;
            height: 32px;
            font-size: 0.85rem;
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
            <h1 class="spa-header-title">Quản Lý Vai Trò</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-shield-alt"></i>
                Phân quyền và tối ưu bảo mật hệ thống
            </p>
        </div>
        <div class="spa-header-action">
            <a href="{{ route('admin.roles.create') }}" class="spa-btn-add">
                <i class="fas fa-plus"></i>
                Thêm Vai Trò Mới
            </a>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="spa-content-wrapper">
        <div class="spa-content-header">
            <h2 class="spa-content-title">
                <i class="fas fa-user-tag"></i>
                Danh Sách Vai Trò
            </h2>
            <div class="dropdown">
                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('admin.roles.create') }}">
                        <i class="fas fa-plus fa-sm mr-2 text-gray-400"></i>
                        Thêm Vai Trò Mới
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="exportRoles">
                        <i class="fas fa-download fa-sm mr-2 text-gray-400"></i>
                        Xuất Danh Sách
                    </a>
                </div>
            </div>
        </div>

        <div class="spa-content-body">
            <div class="spa-search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Tìm kiếm vai trò..." id="searchInput">
            </div>

            <form id="bulkActionForm" action="{{ route('admin.roles.bulkAction') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="bulkActionType" value="">

                @if($roles->isEmpty())
                    <div class="spa-empty-state">
                        <i class="fas fa-user-shield spa-empty-icon"></i>
                        <h3 class="spa-empty-title">Chưa có vai trò nào</h3>
                        <p class="spa-empty-desc">Hệ thống chưa có vai trò nào được thiết lập. Bạn có thể tạo vai trò mới để phân quyền cho người dùng.</p>
                        <a href="{{ route('admin.roles.create') }}" class="spa-btn-add">
                            <i class="fas fa-plus"></i>
                            Thêm Vai Trò Mới
                        </a>
                    </div>
                @else
                    <table class="spa-role-table">
                        <thead>
                            <tr>
                                <th class="text-center">Mã</th>
                                <th>Tên Vai Trò</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td class="text-center">
                                        <span class="spa-role-id">{{ $role->RoleID }}</span>
                                    </td>
                                    <td>
                                        <div class="spa-role-name">{{ $role->Tenrole }}</div>
                                        <span class="spa-role-code">Mã: {{ $role->RoleID }}</span>
                                    </td>
                                    <td>
                                        <div class="spa-actions-wrapper">
                                            <a href="{{ route('admin.roles.show', $role->RoleID) }}" class="spa-action-btn view" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.roles.edit', $role->RoleID) }}" class="spa-action-btn edit" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($role->accounts->count() > 0)
                                                <div class="spa-action-tooltip">
                                                    <span class="spa-action-btn delete disabled">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    <span class="tooltip-text">Không thể xóa vai trò đang được sử dụng</span>
                                                </div>
                                            @else
                                                <a href="{{ route('admin.roles.confirmDestroy', $role->RoleID) }}" class="spa-action-btn delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </form>
        </div>
    </div>

    <!-- Additional Feature Cards Section (Optional) -->
    <div class="row mt-4 mb-5">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 h-100 shadow-sm" style="border-radius: var(--radius-md);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 rounded-circle mr-3" style="background: rgba(255, 107, 149, 0.1);">
                            <i class="fas fa-lock text-pink" style="color: var(--primary-pink);"></i>
                        </div>
                        <h5 class="m-0 font-weight-bold">Phân Quyền</h5>
                    </div>
                    <p class="text-muted mb-0">Quản lý quyền hạn chi tiết cho từng vai trò trong hệ thống.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 h-100 shadow-sm" style="border-radius: var(--radius-md);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 rounded-circle mr-3" style="background: rgba(52, 152, 219, 0.1);">
                            <i class="fas fa-user-shield" style="color: var(--info);"></i>
                        </div>
                        <h5 class="m-0 font-weight-bold">Bảo Mật</h5>
                    </div>
                    <p class="text-muted mb-0">Thiết lập bảo mật đa lớp với các vai trò riêng biệt.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 h-100 shadow-sm" style="border-radius: var(--radius-md);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 rounded-circle mr-3" style="background: rgba(142, 68, 173, 0.1);">
                            <i class="fas fa-users-cog" style="color: var(--primary);"></i>
                        </div>
                        <h5 class="m-0 font-weight-bold">Nhóm Người Dùng</h5>
                    </div>
                    <p class="text-muted mb-0">Phân nhóm và quản lý người dùng theo từng phân quyền.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/roles.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.spa-role-table tbody tr');
                
                rows.forEach(row => {
                    const roleId = row.querySelector('.spa-role-id').textContent.toLowerCase();
                    const roleName = row.querySelector('.spa-role-name').textContent.toLowerCase();
                    
                    if (roleId.includes(searchTerm) || roleName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Export functionality
        const exportBtn = document.getElementById('exportRoles');
        if (exportBtn) {
            exportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Tính năng xuất danh sách đang được phát triển!');
            });
        }

        // Handle delete buttons
        const deleteButtons = document.querySelectorAll('.spa-action-btn.delete:not(.disabled)');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc chắn muốn xóa vai trò này không?')) {
                    e.preventDefault();
                }
            });
        });

        // Prevent clicks on disabled buttons
        const disabledButtons = document.querySelectorAll('.spa-action-btn.disabled');
        disabledButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
        });

        // Highlight rows on hover for better UX
        const tableRows = document.querySelectorAll('.spa-role-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(255, 107, 149, 0.05)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>
@endsection