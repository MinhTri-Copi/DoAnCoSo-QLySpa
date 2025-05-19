<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Spa & Làm đẹp</title>
    
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
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
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
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.home') }}">
                <i class="fas fa-spa me-2"></i>Spa & Làm đẹp
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.dichvu.index') }}">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.quangcao.index') }}">Khuyến mãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.lienhe') }}">Liên hệ</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-link nav-link dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->Hoten }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('customer.profile') }}">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.lichsudatlich.index') }}">Lịch sử đặt lịch</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.thanhvien.pointHistory') }}">Lịch sử điểm</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.phieuhotro.index') }}">Phiếu hỗ trợ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
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
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>info@spa.com</li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i>8:00 - 20:00 (Thứ 2 - Chủ nhật)</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Spa & Làm đẹp. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 