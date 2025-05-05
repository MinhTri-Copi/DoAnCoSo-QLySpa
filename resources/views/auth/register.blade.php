<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosa Spa - Đăng Ký</title>
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link href="auth/css/register.css" rel="stylesheet">
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
                    <p>Trở thành thành viên để nhận nhiều ưu đãi đặc biệt</p>
                    <div class="benefits">
                        <div class="benefit">
                            <i class="fas fa-gift"></i>
                            <span>Ưu đãi độc quyền cho thành viên</span>
                        </div>
                        <div class="benefit">
                            <i class="fas fa-percent"></i>
                            <span>Giảm giá đặc biệt vào sinh nhật</span>
                        </div>
                        <div class="benefit">
                            <i class="fas fa-calendar-check"></i>
                            <span>Đặt lịch ưu tiên</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Phần bên phải - Form đăng ký -->
        <div class="right-section">
            <div class="register-container">
                <div class="welcome-text">
                    <h1>Đăng ký tài khoản</h1>
                    <p>Điền thông tin của bạn để trở thành thành viên của Rosa Spa</p>
                </div>
                
                <!-- Hiển thị thông báo thành công -->
                <div class="alert alert-success hidden" id="success-alert">
                    <i class="fas fa-check-circle"></i>
                    <span>Đăng ký thành công!</span>
                    <button type="button" class="close-btn" onclick="this.parentElement.classList.add('hidden')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Hiển thị thông báo lỗi -->
                <div class="alert alert-danger hidden" id="error-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0" id="error-list">
                        <!-- Danh sách lỗi sẽ được thêm vào đây -->
                    </ul>
                    <button type="button" class="close-btn" onclick="this.parentElement.classList.add('hidden')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Form đăng ký -->
                <form action="{{ route('register') }}" method="POST" id="register-form">
                    @csrf
                    
                    <!-- Input hidden cho vai trò mặc định là user -->
                    <input type="hidden" name="RoleID" value="2">
                    
                    <div class="form-row">
                        <!-- Tên đăng nhập -->
                        <div class="form-group">
                            <label for="Tendangnhap">Tên đăng nhập <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" name="Tendangnhap" id="Tendangnhap" placeholder="Nhập tên đăng nhập" value="{{ old('Tendangnhap') }}" required>
                            </div>
                        </div>
                        
                        <!-- Mật khẩu -->
                        <div class="form-group">
                            <label for="Matkhau">Mật khẩu <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="Matkhau" id="Matkhau" placeholder="Nhập mật khẩu" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('Matkhau')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <!-- Họ tên -->
                        <div class="form-group">
                            <label for="Hoten">Họ tên <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-id-card"></i>
                                <input type="text" name="Hoten" id="Hoten" placeholder="Nhập họ tên đầy đủ" value="{{ old('Hoten') }}" required>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="form-group">
                            <label for="Email">Email <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="Email" id="Email" placeholder="Nhập địa chỉ email" value="{{ old('Email') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <!-- Số điện thoại -->
                        <div class="form-group">
                            <label for="SDT">Số điện thoại</label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone"></i>
                                <input type="text" name="SDT" id="SDT" placeholder="Nhập số điện thoại" value="{{ old('SDT') }}">
                            </div>
                        </div>
                        
                        <!-- Ngày sinh -->
                        <div class="form-group">
                            <label for="Ngaysinh">Ngày sinh</label>
                            <div class="input-with-icon">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="Ngaysinh" id="Ngaysinh" value="{{ old('Ngaysinh') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <!-- Giới tính -->
                        <div class="form-group">
                            <label for="Gioitinh">Giới tính</label>
                            <div class="select-with-icon">
                                <i class="fas fa-venus-mars"></i>
                                <select name="Gioitinh" id="Gioitinh">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" {{ old('Gioitinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nu" {{ old('Gioitinh') == 'Nu' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khac" {{ old('Gioitinh') == 'Khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Địa chỉ -->
                        <div class="form-group">
                            <label for="DiaChi">Địa chỉ</label>
                            <div class="input-with-icon">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="DiaChi" id="DiaChi" placeholder="Nhập địa chỉ" value="{{ old('DiaChi') }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Điều khoản và điều kiện -->
                    <div class="form-group terms-checkbox">
                        <div class="checkbox-container">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a> của Rosa Spa</label>
                        </div>
                    </div>
                    
                    <!-- Nút Đăng ký -->
                    <div class="form-group">
                        <button type="submit" class="btn-register">
                            <span>Đăng ký</span>
                            <i class="fas fa-user-plus"></i>
                        </button>
                    </div>
                    
                    <!-- Liên kết đến trang đăng nhập -->
                    <div class="login-link">
                        <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hiển thị thông báo thành công nếu có
            @if (session('success'))
                document.getElementById('success-alert').classList.remove('hidden');
            @endif
            
            // Hiển thị thông báo lỗi nếu có
            @if ($errors->any())
                const errorList = document.getElementById('error-list');
                errorList.innerHTML = '';
                
                @foreach ($errors->all() as $error)
                    const li = document.createElement('li');
                    li.textContent = '{{ $error }}';
                    errorList.appendChild(li);
                @endforeach
                
                document.getElementById('error-alert').classList.remove('hidden');
            @endif
        });
        
        // Hàm hiển thị/ẩn mật khẩu
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
