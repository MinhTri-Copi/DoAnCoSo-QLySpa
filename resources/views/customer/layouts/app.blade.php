<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ROSA SPA</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FF9A9E;
            --secondary-color: #FECFEF;
            --accent-color: #FF6B6B;
            --text-color: #333333;
            --light-bg: #FFF5F7;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
            background-color: #fff;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 18px 0;
            width: 100%;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        
        .brand-section {
            width: 15%;
            padding-left: 15px;
        }
        
        .nav-section {
            width: 70%;
            display: flex;
            justify-content: center;
        }
        
        .user-section {
            width: 15%;
            display: flex;
            justify-content: flex-end;
            padding-right: 15px;
        }
        
        .rosa-logo {
            font-weight: 750;
            color: var(--accent-color);
            font-size: 1.8rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-right: -10px;
            text-shadow: 0px 2px 4px rgba(255, 107, 107, 0.2);
            transition: all 0.3s ease;
            letter-spacing: 1px;
            position: relative;
        }
        
        .rosa-logo i {
            color: var(--accent-color);
            filter: drop-shadow(0px 2px 3px rgba(255, 107, 107, 0.3));
            transition: all 0.3s ease;
        }
        
        .rosa-logo:hover {
            transform: translateY(-2px);
            color: #FF5B94;
        }
        
        .rosa-logo:hover i {
            transform: rotate(10deg) scale(1.1);
        }
        
        .rosa-logo::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 30px;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-color), transparent);
            transform: scaleX(0.7);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .rosa-logo:hover::after {
            transform: scaleX(1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.8rem;
            margin-right: 2rem;
            display: flex;
            align-items: center;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 10px 15px !important;
            border-radius: 5px;
            display: flex;
            align-items: center;
            font-size: 1.05rem;
            white-space: nowrap;
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(255, 154, 158, 0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .navbar-nav .nav-item {
            margin: 0 2px;
        }
        
        .navbar .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 10px 0;
        }
        
        .navbar .dropdown-item {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            font-size: 1rem;
        }
        
        .navbar .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            color: var(--primary-color);
            font-size: 1.05rem;
        }
        
        .navbar .dropdown-item:hover {
            background-color: rgba(255, 154, 158, 0.1);
            color: var(--primary-color);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .navbar .notification-badge {
            position: relative;
        }
        
        .navbar .notification-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.65rem;
        }
        
        .action-buttons .btn {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            font-size: 1.05rem;
        }
        
        .action-buttons .btn i {
            margin-right: 6px;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }

        .user-actions {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .logout-button {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            margin-left: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            background-color: rgba(255, 107, 107, 0.1);
            color: var(--accent-color);
        }

        footer {
            background-color: var(--light-bg);
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .footer-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .footer-link {
            color: var(--text-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--primary-color);
        }

        .social-links a {
            color: var(--text-color);
            margin-right: 1rem;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: var(--primary-color);
        }

        @yield('styles')
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <div class="nav-container">
                <div class="brand-section">
                    <a class="rosa-logo" href="{{ route('customer.home') }}">
                        <i class="fas fa-spa me-2"></i>ROSA SPA
                    </a>
                </div>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="nav-section collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.home') }}">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.dichvu.index') }}">
                                <i class="fas fa-hand-sparkles"></i> Dịch vụ
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bullhorn"></i> Ưu đãi
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('customer.quangcao.index') }}">
                                    <i class="fas fa-tags"></i> Tất cả khuyến mãi
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.quangcao.promotions') }}">
                                    <i class="fas fa-percent"></i> Khuyến mãi hot
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.quangcao.events') }}">
                                    <i class="fas fa-calendar-alt"></i> Sự kiện
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.quangcao.newservices') }}">
                                    <i class="fas fa-star"></i> Dịch vụ mới
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.datlich.create') }}">
                                <i class="fas fa-calendar-check"></i> Đặt lịch
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.thanhvien.index') }}">
                                <i class="fas fa-crown"></i> Thành viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.lienhe') }}">
                                <i class="fas fa-phone-alt"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="user-section">
                    @auth
                        <div class="d-flex me-3">
                            <a href="{{ route('customer.phieuhotro.index') }}" class="btn btn-link nav-link notification-badge" title="Phiếu hỗ trợ">
                                <i class="fas fa-headset"></i>
                            </a>
                            <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-link nav-link notification-badge" title="Lịch đã đặt">
                                <i class="fas fa-calendar"></i>
                            </a>
                            <a href="{{ route('customer.hoadon.index') }}" class="btn btn-link nav-link notification-badge" title="Hóa đơn">
                                <i class="fas fa-receipt"></i>
                            </a>
                        </div>
                        <div class="dropdown">
                            <div class="user-avatar dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;" title="{{ Auth::user()->Hoten }}">
                                <i class="fas fa-user"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('customer.profile') }}">
                                    <i class="fas fa-user-circle"></i> Thông tin cá nhân
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.lichsudatlich.index') }}">
                                    <i class="fas fa-history"></i> Lịch sử đặt lịch
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.hoadon.index') }}">
                                    <i class="fas fa-file-invoice-dollar"></i> Hóa đơn của tôi
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.thanhvien.pointHistory') }}">
                                    <i class="fas fa-coins"></i> Lịch sử điểm thưởng
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.phieuhotro.index') }}">
                                    <i class="fas fa-headset"></i> Phiếu hỗ trợ
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.danhgia.index') }}">
                                    <i class="fas fa-star"></i> Đánh giá của tôi
                                </a></li>
                            </ul>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-button" title="Đăng xuất">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <div class="action-buttons">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Về chúng tôi</h5>
                    <p>Chúng tôi cam kết mang đến cho bạn những trải nghiệm spa và làm đẹp tốt nhất, với đội ngũ chuyên gia giàu kinh nghiệm và dịch vụ chất lượng cao.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="footer-title">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('customer.dichvu.index') }}" class="footer-link">Dịch vụ</a></li>
                        <li class="mb-2"><a href="{{ route('customer.quangcao.index') }}" class="footer-link">Khuyến mãi</a></li>
                        <li class="mb-2"><a href="{{ route('customer.lienhe') }}" class="footer-link">Liên hệ</a></li>
                        <li class="mb-2"><a href="{{ route('customer.thanhvien.index') }}" class="footer-link">Thành viên</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>(028) 1234 5678</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>info@rosaspa.com</li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i>8:00 - 20:00 (Thứ 2 - Chủ nhật)</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} ROSA SPA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 