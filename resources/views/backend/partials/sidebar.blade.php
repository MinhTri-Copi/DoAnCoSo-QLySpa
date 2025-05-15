<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-circle">
                <span>R</span>
            </div>
            <h5>Rosa Spa</h5>
        </div>
        <button id="sidebarCollapseBtn" class="btn-collapse">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    
    <div class="sidebar-profile">
        <div class="profile-image">
            <img src="{{ asset('admin/images/admin-avatar.jpg') }}" alt="Admin Profile">
        </div>
        <div class="profile-info">
            <p class="badge-text">Quản trị viên</p>
            <h6>{{ Auth::user()->Hoten ?? 'Admin' }}</h6>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="menu-link active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tổng quan</span>
            </a>
        </li>
        
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-users"></i>
                <span>Quản lý người dùng</span>
                <i class="fas fa-chevron-right submenu-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.customers.index') }}"><i class="fas fa-user-friends"></i> Khách hàng</a></li>
                <li><a href="{{ route('admin.accounts.index') }}"><i class="fas fa-user-shield"></i> Tài khoản</a></li>
                <li><a href="{{ route('admin.roles.index') }}"><i class="fas fa-user-tag"></i> Vai trò</a></li>
            </ul>
        </li>
        
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-spa"></i>
                <span>Quản lý dịch vụ</span>
                <i class="fas fa-chevron-right submenu-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.dichvu.index') }}"><i class="fas fa-list-alt"></i> Dịch vụ</a></li>
                <li><a href="{{ route('admin.datlich.index') }}"><i class="fas fa-calendar-check"></i> Đặt lịch</a></li>
                <li><a href="{{ route('admin.phong.index') }}"><i class="fas fa-door-open"></i> Phòng</a></li>
            </ul>
        </li>
        
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-headset"></i>
                <span>Quản lý hỗ trợ</span>
                <i class="fas fa-chevron-right submenu-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.phieuhotro.index') }}"><i class="fas fa-ticket-alt"></i> Phiếu hỗ trợ</a></li>
                <li><a href="{{ route('admin.pthotro.index') }}"><i class="fas fa-hands-helping"></i> Phương thức hỗ trợ</a></li>
            </ul>
        </li>
        
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-money-bill-wave"></i>
                <span>Quản lý tài chính</span>
                <i class="fas fa-chevron-right submenu-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.hoadonvathanhtoan.index') }}"><i class="fas fa-file-invoice-dollar"></i> Hóa đơn & thanh toán</a></li>
                <li><a href="{{ route('admin.lsdiemthuong.index') }}"><i class="fas fa-coins"></i> Lịch sử điểm thưởng</a></li>
            </ul>
        </li>
        
        <li class="menu-item">
            <a href="{{ route('admin.danhgia.index') }}" class="menu-link">
                <i class="fas fa-star"></i>
                <span>Quản lý đánh giá</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="fas fa-cog"></i>
                <span>Cài đặt hệ thống</span>
            </a>
        </li>
        
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
    
    <div class="sidebar-footer">
        <div class="version-info">
            <span>v1.2.0</span>
        </div>
        <div class="system-status online">
            <i class="fas fa-circle"></i>
            <span>Hệ thống hoạt động</span>
        </div>
    </div>
</nav> 