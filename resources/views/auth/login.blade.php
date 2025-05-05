<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosa Spa - Đăng nhập</title>
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link href="auth/css/login.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Phần bên trái - Ảnh -->
        <div class="left-section">
            <div class="overlay">
                <div class="spa-info">
                    <div class="logo-container">
                        <div class="logo-circle">
                            <span>R</span>
                        </div>
                    </div>
                    <h2>Rosa Spa</h2>
                    <p>Nơi chăm sóc sắc đẹp và thư giãn tuyệt vời</p>
                    <div class="features">
                        <div class="feature">
                            <i class="fas fa-spa"></i>
                            <span>Liệu pháp thư giãn</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-leaf"></i>
                            <span>Sản phẩm tự nhiên</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-heart"></i>
                            <span>Chăm sóc toàn diện</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Phần bên phải - Form đăng nhập -->
        <div class="right-section">
            <div class="login-container">
                <div class="welcome-text">
                    <h1>Chào mừng trở lại</h1>
                    <p>Đăng nhập để tiếp tục trải nghiệm dịch vụ của Rosa Spa</p>
                </div>
                
                <div class="alert alert-danger hidden">
                    <!-- Hiển thị lỗi nếu có -->
                </div>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf 
                    <div class="form-group">
                        <label for="Tendangnhap">Tên đăng nhập</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" name="Tendangnhap" id="Tendangnhap" placeholder="Nhập tên đăng nhập" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="Matkhau">Mật khẩu</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="Matkhau" id="Matkhau" placeholder="Nhập mật khẩu" required>
                        </div>
                        <div class="forgot-password">
                            <a href="#">Quên mật khẩu?</a>
                        </div>
                    </div>
                    
                    <div class="form-group remember-me">
                        <div class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-login">
                            <span>Đăng nhập</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
                
                <div class="register-link">
                    <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
                </div>
                
                <div class="social-login">
                    <p>Hoặc đăng nhập với</p>
                    <div class="social-buttons">
                        <a href="#" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-btn google">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-btn apple">
                            <i class="fab fa-apple"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
