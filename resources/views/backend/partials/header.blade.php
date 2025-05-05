<header class="admin-header">
    <div class="header-left">
        <button id="sidebarToggle" class="btn-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="search-container">
            <form action="{{ route('admin.search') }}" method="GET" autocomplete="off">
                <div class="search-box">
                    <input type="text" name="query" placeholder="Tìm kiếm chức năng (VD: đặt lịch, khách hàng, hỗ trợ...)" class="form-control no-outline" required style="outline: none !important; box-shadow: none !important;">
                    <button type="submit" class="btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="quick-actions">
            <button class="btn-quick-action" data-bs-toggle="tooltip" title="Thêm đặt lịch mới">
                <i class="fas fa-calendar-plus"></i>
            </button>
            <button class="btn-quick-action" data-bs-toggle="tooltip" title="Thêm khách hàng mới">
                <i class="fas fa-user-plus"></i>
            </button>
            <button class="btn-quick-action" data-bs-toggle="tooltip" title="Tạo hóa đơn mới">
                <i class="fas fa-file-invoice"></i>
            </button>
        </div>
    </div>
    
    <div class="header-right">
        <div class="header-actions">
            <div class="action-item">
                <button class="btn-action" id="fullscreenToggle" data-bs-toggle="tooltip" title="Toàn màn hình">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
            
            <div class="action-item">
                <button class="btn-action" id="darkModeToggle" data-bs-toggle="tooltip" title="Chế độ tối">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
            
            <div class="action-item dropdown">
                <button class="btn-action" id="notificationDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="fas fa-bell"></i>
                    <span class="badge">5</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <div class="dropdown-header">
                        <h6>Thông báo</h6>
                        <a href="#">Đánh dấu tất cả đã đọc</a>
                    </div>
                    <div class="notification-list">
                        <a href="#" class="notification-item unread">
                            <div class="notification-icon bg-primary">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="notification-content">
                                <p>Có 5 lịch hẹn mới cần xác nhận</p>
                                <span class="time">30 phút trước</span>
                            </div>
                        </a>
                        <a href="#" class="notification-item unread">
                            <div class="notification-icon bg-success">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="notification-content">
                                <p>Có 3 khách hàng mới đăng ký</p>
                                <span class="time">1 giờ trước</span>
                            </div>
                        </a>
                        <a href="#" class="notification-item unread">
                            <div class="notification-icon bg-warning">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="notification-content">
                                <p>Có đánh giá mới cần duyệt</p>
                                <span class="time">3 giờ trước</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
                
            <div class="action-item dropdown">
                <button class="btn-action" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end user-dropdown" aria-labelledby="userDropdown">
                    <div class="dropdown-header">
                        <div class="user-info">
                            <div class="user-avatar">
                                <img src="{{ asset('admin/images/admin-avatar.jpg') }}" alt="User Avatar">
                            </div>
                            <div class="user-details">
                                <h6>{{ Auth::user()->Hoten ?? 'Admin' }}</h6>
                                <p>Quản trị viên</p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user me-2"></i>Thông tin cá nhân
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog me-2"></i>Cài đặt tài khoản
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-history me-2"></i>Lịch sử hoạt động
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item text-danger" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Thông báo lỗi -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Thông báo gợi ý từ tìm kiếm -->
@if (session('suggestion'))
    <div class="alert alert-info alert-dismissible fade show search-suggestion" role="alert">
        <i class="fas fa-info-circle me-2"></i> {{ session('suggestion') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif 