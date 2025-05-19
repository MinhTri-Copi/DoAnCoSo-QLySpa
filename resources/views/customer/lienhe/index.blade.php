@extends('customer.layouts.app')

@section('title', 'Liên hệ')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h2 class="mb-4">Liên hệ với chúng tôi</h2>
            <p class="lead mb-4">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy liên hệ với chúng tôi qua các kênh sau:</p>
            
            <div class="contact-info mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Địa chỉ</h5>
                        <p class="mb-0">123 Đường ABC, Quận XYZ, TP.HCM</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-phone text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Điện thoại</h5>
                        <p class="mb-0">(028) 1234 5678</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-envelope text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Email</h5>
                        <p class="mb-0">info@spa.com</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-clock text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Giờ làm việc</h5>
                        <p class="mb-0">8:00 - 20:00 (Thứ 2 - Chủ nhật)</p>
                    </div>
                </div>
            </div>
            
            <div class="social-links">
                <h5 class="mb-3">Kết nối với chúng tôi</h5>
                <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f me-2"></i>Facebook</a>
                <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-instagram me-2"></i>Instagram</a>
                <a href="#" class="btn btn-outline-primary"><i class="fab fa-youtube me-2"></i>YouTube</a>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4">Gửi tin nhắn cho chúng tôi</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="container-fluid px-0">
    <div class="map-container" style="height: 400px;">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4241679834767!2d106.6981!3d10.7756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQ2JzMyLjEiTiAxMDbCsDQxJzUzLjIiRQ!5e0!3m2!1svi!2s!4v1620000000000!5m2!1svi!2s" 
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>
</div>
@endsection

@section('styles')
<style>
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .map-container {
        position: relative;
        overflow: hidden;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }
    
    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>
@endsection 