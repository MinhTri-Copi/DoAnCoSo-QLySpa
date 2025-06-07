@extends('customer.layouts.app')

@section('title', 'Trang chủ - Spa & Làm đẹp')

@section('content')
<!-- Hero Section with Video Background -->
<section class="hero-section position-relative">
    <div class="video-background">
        <div id="video-container">
            <video id="spa-video" muted playsinline autoplay>
                <source src="{{ asset('videos/trailer/Standard_Mode_16x9_m_nh_c_n_b_n_t_o_cho_m_nh_1_vide.mp4') }}" type="video/mp4" id="video-source">
            </video>
        </div>
        <div class="video-overlay"></div>
    </div>
    <div class="container position-relative z-index-2">
        <div class="row min-vh-75 align-items-center py-5">
            <div class="col-md-7 text-center text-md-start">
                <h1 class="display-4 text-white fw-bold mb-4">Chào mừng đến với dịch vụ spa của chúng tôi</h1>
                <p class="lead text-white mb-4">Đắm chìm trong không gian thư giãn và trải nghiệm dịch vụ chăm sóc cơ thể chuyên nghiệp</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-md-start">
                    <a href="{{ route('customer.dichvu.index') }}" class="btn btn-light btn-lg px-4">Khám phá dịch vụ</a>
                    <a href="{{ route('customer.datlich.create') }}" class="btn btn-outline-light btn-lg px-4">Đặt lịch ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Advertisements Section -->
@if(count($featuredAds) > 0)
<section class="featured-ads py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Ưu đãi đặc biệt</h2>
            <div class="section-divider">
                <span class="section-divider-line"></span>
                <span class="section-divider-icon"><i class="fas fa-gift"></i></span>
                <span class="section-divider-line"></span>
            </div>
            <p class="section-subtitle">Khám phá những ưu đãi hấp dẫn đang diễn ra tại Rosa Spa</p>
        </div>
        
        <div class="row">
            @foreach($featuredAds as $ad)
            <div class="col-md-4 mb-4">
                <div class="promo-card">
                    <div class="promo-card-img">
                        @if($ad->Image)
                        <img src="{{ asset($ad->Image) }}" alt="{{ $ad->Tieude }}">
                        @else
                        <div class="no-image">
                            <i class="fas fa-image"></i>
                        </div>
                        @endif
                        <div class="promo-badge">Nổi bật</div>
                        <div class="promo-overlay">
                            <a href="{{ route('customer.quangcao.show', $ad->MaQC) }}" class="btn-details">Xem chi tiết</a>
                        </div>
                    </div>
                    <div class="promo-card-body">
                        <h3 class="promo-title">{{ $ad->Tieude }}</h3>
                        <div class="promo-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($ad->Ngaybatdau)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($ad->Ngayketthuc)->format('d/m/Y') }}</span>
                        </div>
                        <p class="promo-desc">{{ \Illuminate\Support\Str::limit($ad->Noidung, 100) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('customer.quangcao.index') }}" class="see-all-promos">
                <span>Xem tất cả ưu đãi</span>
                <i class="fas fa-long-arrow-alt-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Services Section -->
@if(count($featuredServices) > 0)
<section class="featured-services py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Dịch vụ nổi bật</h2>
            <div class="section-divider">
                <span class="section-divider-line"></span>
                <span class="section-divider-icon"><i class="fas fa-spa"></i></span>
                <span class="section-divider-line"></span>
            </div>
            <p class="section-subtitle">Trải nghiệm những dịch vụ cao cấp và phổ biến nhất tại Rosa Spa</p>
        </div>

        <div class="row">
            @foreach($featuredServices as $service)
            <div class="col-md-3 mb-4">
                <div class="service-card">
                    <a href="{{ route('customer.dichvu.show', $service->MaDV) }}" class="service-card-link">
                        <div class="service-card-img">
                            @if($service->Image)
                            <img src="{{ asset($service->Image) }}" alt="{{ $service->Tendichvu }}">
                            @else
                            <div class="no-image">
                                <i class="fas fa-spa"></i>
                            </div>
                            @endif
                            <div class="service-price">{{ number_format($service->Gia, 0, ',', '.') }} VND</div>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-title">{{ $service->Tendichvu }}</h3>
                            <p class="service-desc">{{ \Illuminate\Support\Str::limit($service->MoTa ?? 'Không có mô tả', 80) }}</p>
                        </div>
                    </a>
                    <div class="service-actions">
                        <a href="{{ route('customer.datlich.create', ['service_id' => $service->MaDV, 'step' => 2] ) }}" class="btn-book">Đặt lịch</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('customer.dichvu.index') }}" class="see-all-services">
                <span>Xem tất cả dịch vụ</span>
                <i class="fas fa-long-arrow-alt-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Upcoming Bookings Section (Only for logged in users) -->
@if(isset($upcomingBookings) && count($upcomingBookings) > 0)
<section class="upcoming-bookings py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Lịch đặt sắp tới của bạn</h2>
            <div class="section-divider">
                <span class="section-divider-line"></span>
                <span class="section-divider-icon"><i class="fas fa-calendar-check"></i></span>
                <span class="section-divider-line"></span>
            </div>
            <p class="section-subtitle">Theo dõi và quản lý các lịch hẹn spa của bạn</p>
        </div>
        
        <div class="row">
            @foreach($upcomingBookings as $booking)
            @php
                $bookingTime = \Carbon\Carbon::parse($booking->Thoigiandatlich);
                $now = \Carbon\Carbon::now();
                $hoursDiff = $now->diffInHours($bookingTime, false);
                $daysDiff = $now->diffInDays($bookingTime, false);
                $isWithin24Hours = $hoursDiff > 0 && $hoursDiff <= 24;
                $isWithin48Hours = $daysDiff >= 0 && $daysDiff <= 2;
                $comingClass = $isWithin24Hours ? 'appointment-coming-soon-24h' : ($isWithin48Hours ? 'appointment-coming-soon-48h' : '');
            @endphp
            <div class="col-md-4 mb-4">
                <div class="appointment-card {{ $comingClass }}">
                    @if($isWithin48Hours)
                    <div class="coming-soon-badge">
                        <i class="fas fa-stopwatch"></i> Sắp đến
                    </div>
                    @endif
                    <div class="appointment-header" style="background-image: url('{{ asset($booking->dichVu->Image ?? 'images/default-service.jpg') }}')">
                        <div class="appointment-overlay">
                            <div class="service-name">{{ $booking->dichVu->Tendichvu ?? 'Dịch vụ không xác định' }}</div>
                        </div>
                    </div>
                    <div class="appointment-body">
                        <div class="appointment-info">
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-tag"></i>
                                @if($booking->Trangthai_ == 1)
                                    <span class="status pending">Chờ xác nhận</span>
                                @elseif($booking->Trangthai_ == 2)
                                    <span class="status confirmed">Đã xác nhận</span>
                                @elseif($booking->Trangthai_ == 3)
                                    <span class="status in-progress">Đang thực hiện</span>
                                @endif
                            </div>
                            @if($isWithin24Hours)
                            <div class="info-item countdown-timer" data-booking-time="{{ $booking->Thoigiandatlich }}">
                                <i class="fas fa-hourglass-half"></i>
                                <span class="countdown-text">Còn lại: <span class="time-left">Đang tính...</span></span>
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('customer.lichsudatlich.show', $booking->MaDL) }}" class="btn-view-details">
                            <span>Xem chi tiết</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('customer.lichsudatlich.index') }}" class="see-all-appointments">
                <span>Xem tất cả lịch đặt</span>
                <i class="fas fa-long-arrow-alt-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Promotional Banner -->
@if(isset($promotionAds) && count($promotionAds) > 0)
<section class="promo-banner py-5" style="background-color: #FFF5F7;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="h1 mb-4">Phun xăm thẩm mỹ giảm giá sốc</h2>
                <ul class="promo-sticker-list mb-4">
                    <li>✨ <b>PHUN XĂM THẨM MỸ – GIẢM GIÁ 15%</b></li>
                    <li>💖 Đẹp tự nhiên – Không đau – An toàn tuyệt đối</li>
                    <li>🎯 Kỹ thuật chuẩn y khoa – Mực hữu cơ nhập khẩu 🦉</li>
                    <li>👁️ Dáng mày hài hòa, sắc nét, khắc phục khuyết điểm</li>
                    <li>📍 Chuyên viên tay nghề cao – Trang thiết bị vô trùng 🎁</li>
                    <li>Đặt lịch ngay – Ưu đãi chỉ áp dụng trong tuần này!</li>
                </ul>
                <a href="#" class="btn btn-pink btn-lg">Khám phá ngay</a>
            </div>
            <div class="col-lg-6 text-center">
                @if(isset($promotionAds[0]) && $promotionAds[0]->Image)
                    <img src="{{ asset($promotionAds[0]->Image) }}"
                         onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Promotion';"
                         class="img-fluid rounded-3 shadow promo-banner-img"
                         alt="Promotion"
                         style="max-width: 100%; max-height: 520px; object-fit: cover; object-position: center;">
                @else
                    <img src="https://placehold.co/600x400?text=Promotion"
                         class="img-fluid rounded-3 shadow promo-banner-img"
                         alt="Promotion"
                         style="max-width: 100%; max-height: 520px; object-fit: cover; object-position: center;">
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Membership Section -->
@if(isset($membershipRank))
<section class="membership py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title">Hạng thành viên của bạn</h3>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-crown fa-2x"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $membershipRank->Tenhangtv ?? 'Thành viên mới' }}</h4>
                                <p class="text-muted mb-0">{{ $user->Diemtichluy ?? 0 }} điểm</p>
                            </div>
                        </div>
                        
                        @if($nextRank)
                        <div class="progress mb-3" style="height: 10px;">
                            @php
                                $currentPoints = $user->Diemtichluy ?? 0;
                                $nextRankPoints = $nextRank->Diemtoithieu;
                                $currentRankPoints = $membershipRank->Diemtoithieu;
                                $range = $nextRankPoints - $currentRankPoints;
                                $progress = $range > 0 ? (($currentPoints - $currentRankPoints) / $range) * 100 : 100;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="small text-muted mb-3">Còn {{ $pointsNeeded }} điểm để lên hạng {{ $nextRank->Tenhangtv }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.thanhvien.index') }}" class="btn btn-outline-primary">Xem chi tiết</a>
                            <a href="{{ route('customer.thanhvien.pointHistory') }}" class="btn btn-outline-secondary">Lịch sử điểm</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4 mb-lg-0 order-1 order-lg-2">
                <h2 class="h1 mb-4">Tận hưởng đặc quyền thành viên</h2>
                <p class="lead">Cùng khám phá những ưu đãi độc quyền dành riêng cho hạng thành viên của bạn.</p>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Tích điểm với mỗi lần sử dụng dịch vụ</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Ưu đãi giảm giá cho thành viên</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Trải nghiệm dịch vụ VIP</li>
                    <li><i class="fas fa-check-circle text-primary me-2"></i> Quà sinh nhật đặc biệt</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Guest Membership Promotion (Only for non-logged in users) -->
@guest
<section class="membership-promo py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="h1 mb-4">Đăng ký để nhận đặc quyền thành viên</h2>
                <p class="lead mb-4">Trở thành thành viên của ROSA SPA để nhận nhiều ưu đãi hấp dẫn và tích lũy điểm thưởng với mỗi lần sử dụng dịch vụ.</p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Nhận ưu đãi độc quyền dành cho thành viên</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Tích điểm với mỗi lần sử dụng dịch vụ</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Nhận quà sinh nhật đặc biệt</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Đặt lịch ưu tiên và quản lý lịch hẹn dễ dàng</li>
                </ul>
                <div class="d-flex gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký ngay</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="membership-card-container position-relative">
                    <div class="membership-card shadow p-4 rounded-3 mx-auto diamond-effect" style="max-width: 350px; background: linear-gradient(135deg, #FF6B81 0%, #e84c60 100%); color: #fff; position: relative; overflow: hidden;">
                        <!-- Lớp chứa hiệu ứng kim cương -->
                        <div class="diamond-shine-container"></div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
                            <h4 class="m-0 fw-bold" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">ROSA SPA</h4>
                            <i class="fas fa-crown fa-2x text-warning" style="filter: drop-shadow(0 0 5px gold);"></i>
                        </div>
                        <div class="mb-3 position-relative">
                            <h5 class="mb-1 fw-bold" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Thành viên VIP</h5>
                            <p class="small m-0 fw-bold" style="text-shadow: 0px 1px 2px rgba(0,0,0,0.5);">Hưởng đặc quyền và ưu đãi độc quyền</p>
                        </div>
                        <div class="mt-4 d-flex justify-content-between position-relative">
                            <span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"><i class="fas fa-star me-1"></i><i class="fas fa-star me-1"></i><i class="fas fa-star me-1"></i><i class="fas fa-star me-1"></i><i class="fas fa-star"></i></span>
                            <span class="fw-bold" style="text-shadow: 0px 1px 2px rgba(0,0,0,0.5);">Tích điểm mỗi lần sử dụng dịch vụ</span>
                        </div>
                    </div>
                    <div class="membership-card-shadow position-absolute" style="top: 15px; left: 50%; width: 90%; height: 100%; background-color: rgba(0,0,0,0.2); border-radius: 12px; z-index: -1; transform: translateX(-50%);"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endguest

<!-- Booking Form Section -->
<section class="booking-cta py-5 bg-pink position-relative overflow-hidden animated-bg">
    <div class="animation-circles">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="mb-4 animate__animated animate__fadeInDown text-dark">Bạn muốn đặt lịch ngay?</h2>
                <p class="lead mb-4 animate__animated animate__fadeInUp text-dark">Chúng tôi sẵn sàng mang đến cho bạn trải nghiệm spa tuyệt vời nhất</p>
                
                @auth
                <!-- Đã đăng nhập - Hiển thị nút đặt lịch bình thường -->
                <a href="{{ route('customer.datlich.create') }}" class="btn btn-cta btn-lg px-5 py-3 animate__animated animate__pulse animate__infinite">Đặt lịch ngay</a>
                @else
                <!-- Chưa đăng nhập - Hiển thị nút giống với đã đăng nhập nhưng chuyển hướng tới login -->
                <a href="{{ route('login') }}?redirect=booking" class="btn btn-cta btn-lg px-5 py-3 animate__animated animate__pulse animate__infinite">Đặt lịch ngay</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
@if(isset($latestReviews) && count($latestReviews) > 0)
<section class="reviews py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Khách hàng nói gì về chúng tôi</h2>
        <div class="row">
            @foreach($latestReviews as $review)
            <div class="col-md-6 mb-4">
                <div class="card review-card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $review->Hoten ?? 'Khách hàng' }}</h6>
                                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($review->Danhgiasao ?? 5))
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="card-text mb-3">{{ \Illuminate\Support\Str::limit($review->Nhanxet ?? 'Không có nội dung đánh giá', 100) }}</p>
                        <div class="mt-auto text-end">
                            <span class="badge bg-light text-pink rounded-pill px-3 py-2">
                                <i class="fas fa-spa me-1"></i>
                                {{ $review->TenDichVu ?? 'Dịch vụ spa' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Quick Contact Float Menu -->
<div class="quick-contact-menu">
    <div class="quick-contact-toggle" id="toggleQuickContact">
        <i class="fas fa-times"></i>
    </div>
    <div class="quick-contact-items">
        <a href="#" class="quick-contact-item zalo-item">
            <img src="{{ asset('images/icons/zalo-icon-removebg-preview.png') }}" alt="Zalo" onerror="this.src='https://sp-ao.shortpixel.ai/client/to_auto,q_glossy,ret_img,w_32,h_32/https://ads-network.net/wp-content/uploads/2022/06/zalo-icon.png'">
        </a>
        <a href="#" class="quick-contact-item messenger-item">
            <i class="fab fa-facebook-messenger" style="border-bottom: none;"></i>
        </a>
        <a href="#" class="quick-contact-item phone-item">
            <i class="fas fa-phone" style="border-bottom: none;"></i>
        </a>
    </div>
</div>

@endsection

@section('styles')
<style>
    html, body {
        overflow-x: hidden;
        width: 100%;
    }

    /* Full screen hero section styles */
    .hero-section {
        position: relative;
        overflow: hidden;
        min-height: 75vh;
        width: 100%;
        max-width: 100%;
    }
    
    .video-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }
    
    #video-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    #spa-video {
        position: absolute;
        min-width: 100%;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        left: 0;
        top: 0;
    }
    
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }
    
    .z-index-2 {
        z-index: 2;
        position: relative;
    }
    
    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
        color: #333;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: linear-gradient(to right, #FF9500, #FFC107);
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Review cards */
    .review-card {
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }
    
    .review-card .card-body {
        padding: 1.5rem;
    }
    
    .avatar-circle {
        background-color: #FF9A9E !important;
    }
    
    .rating {
        font-size: 1rem;
    }
    
    .badge {
        font-weight: normal;
    }
    
    .text-pink {
        color: #FF9A9E !important;
    }
    
    /* Booking CTA Section with Pink Background and Animation */
    .bg-pink {
        background: linear-gradient(135deg, #f76c82 0%, #e77c96 99%, #e28090 100%);
        position: relative;
    }
    
    .animated-bg {
        position: relative;
        overflow: hidden;
    }
    
    .animation-circles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    .circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        animation: float 8s infinite ease-in-out;
    }
    
    .circle-1 {
        width: 150px;
        height: 150px;
        top: -50px;
        left: 10%;
        animation-delay: 0s;
    }
    
    .circle-2 {
        width: 80px;
        height: 80px;
        bottom: 20px;
        right: 20%;
        animation-delay: 2s;
    }
    
    .circle-3 {
        width: 200px;
        height: 200px;
        bottom: -100px;
        left: 40%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 0.6;
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
            opacity: 0.9;
        }
        100% {
            transform: translateY(0) rotate(360deg);
            opacity: 0.6;
        }
    }
    
    /* Button CTA with glowing effect */
    .btn-cta {
        background: #ffffff;
        color: #ff6b81;
        font-weight: 600;
        border: none;
        border-radius: 50px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(255, 107, 129, 0.4);
        transition: all 0.3s ease;
        z-index: 1;
    }
    
    .btn-cta:hover, .btn-cta:focus {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 12px 30px rgba(255, 107, 129, 0.6);
        color: #ff4757;
    }
    
    .btn-cta::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        transform: translateX(-100%);
        transition: 0.6s;
        z-index: -1;
    }
    
    .btn-cta:hover::after {
        transform: translateX(100%);
    }
    
    /* Import Animate.css classes */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    
    /* Animation classes */
    .animate__animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }
    
    .animate__fadeInDown {
        animation-name: fadeInDown;
    }
    
    .animate__fadeInUp {
        animation-name: fadeInUp;
    }
    
    .animate__pulse {
        animation-name: pulse;
        animation-duration: 2s;
    }
    
    .animate__infinite {
        animation-iteration-count: infinite;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    
    @keyframes pulse {
        from {
            transform: scale3d(1, 1, 1);
        }
        50% {
            transform: scale3d(1.05, 1.05, 1.05);
        }
        to {
            transform: scale3d(1, 1, 1);
        }
    }

    /* Diamond effect styles */
    .diamond-effect {
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(255, 215, 0, 0.5);
    }
    
    .diamond-shine-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 10;
    }
    
    .diamond-shine {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 8px 4px rgba(255, 255, 255, 0.8), 
                   0 0 12px 6px rgba(255, 215, 0, 0.6);
        opacity: 0;
        transform: scale(0);
        pointer-events: none;
    }

    /* Section Header Styles */
    .section-header {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }
    
    .section-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .section-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 15px 0;
    }
    
    .section-divider-line {
        height: 2px;
        width: 70px;
        background: linear-gradient(90deg, transparent, #ff6b9d, transparent);
    }
    
    .section-divider-icon {
        margin: 0 15px;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ff6b9d 0%, #e55a8a 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 10px rgba(255, 107, 157, 0.3);
    }

    /* Service Card Styling */
    .service-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.07);
        transition: all 0.3s ease;
        height: 100%;
        background-color: #fff;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .service-card-img {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .service-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .service-card:hover .service-card-img img {
        transform: scale(1.08);
    }

    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #FFE6E6, #FFD1D1);
        color: #FF9A9E;
        font-size: 3rem;
    }

    .service-price {
        position: absolute;
        top: 15px;
        right: 0;
        background-color: #e5686d;
        color: white;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 20px 0 0 20px;
        box-shadow: -2px 2px 10px rgba(255, 154, 158, 0.3);
    }

    .service-card-body {
        padding: 25px 20px 15px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .service-title {
        margin-bottom: 12px;
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }

    .service-desc {
        color: #777;
        font-size: 0.95rem;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .service-actions {
        padding: 0 20px 20px;
        display: flex;
        justify-content: center;
    }

    .btn-view, .btn-book {
        padding: 10px 15px;
        border-radius: 30px;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .btn-view {
        color: #FF9A9E;
        background-color: rgba(255, 154, 158, 0.1);
        flex: 1;
        margin-right: 10px;
    }

    .btn-book {
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 700;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        text-decoration: none;
        color: white;
        background-color: #e5686d;
        flex: 1;
        display: block;
        max-width: 200px;
        margin: 0 auto;
        box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
    }

    .btn-view:hover {
        background-color: rgba(255, 154, 158, 0.2);
        color: #FF6B6B;
    }

    .btn-book:hover {
        background-color: #FF6B6B;
    }

    .see-all-services {
        display: inline-flex;
        align-items: center;
        color: #FF9A9E;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        padding: 10px 25px;
        border: 2px solid rgba(255, 154, 158, 0.3);
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .see-all-services span {
        margin-right: 10px;
    }

    .see-all-services:hover {
        background-color: #FF9A9E;
        color: white;
        border-color: #FF9A9E;
    }

    /* Promo Card Styling */
    .promo-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        height: 100%;
        background-color: #fff;
        position: relative;
    }

    .promo-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 22px rgba(0,0,0,0.09);
    }

    .promo-card-img {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .promo-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .promo-card:hover .promo-card-img img {
        transform: scale(1.05);
    }

    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #F8E4E8, #FFD1D1);
        color: #FF9A9E;
        font-size: 3rem;
    }

    .promo-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: rgba(255, 107, 107, 0.85);
        color: white;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 20px;
        backdrop-filter: blur(3px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .promo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .promo-card:hover .promo-overlay {
        opacity: 1;
    }

    .btn-details {
        background: white;
        color: #FF6B6B;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transform: translateY(20px);
        transition: all 0.3s ease;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .promo-card:hover .btn-details {
        transform: translateY(0);
    }

    .btn-details:hover {
        background: #FF6B6B;
        color: white;
    }

    .promo-card-body {
        padding: 20px;
    }

    .promo-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
        line-height: 1.4;
    }

    .promo-date {
        display: flex;
        align-items: center;
        color: #888;
        font-size: 0.85rem;
        margin-bottom: 12px;
    }

    .promo-date i {
        margin-right: 6px;
        color: #FF9A9E;
    }

    .promo-desc {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .see-all-promos {
        display: inline-flex;
        align-items: center;
        color: #FF9A9E;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        padding: 10px 25px;
        border: 2px solid rgba(255, 154, 158, 0.3);
        border-radius: 30px;
        transition: all 0.3s ease;
    }

    .see-all-promos span {
        margin-right: 10px;
    }

    .see-all-promos:hover {
        background-color: #FF9A9E;
        color: white;
        border-color: #FF9A9E;
    }

    .service-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    /* Appointment Card Styles */
    .appointment-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .appointment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 107, 157, 0.1);
    }
    
    .appointment-header {
        height: 160px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .appointment-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.7));
        display: flex;
        align-items: flex-end;
        padding: 20px;
    }
    
    .service-name {
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 10px;
    }
    
    .appointment-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .appointment-info {
        margin-bottom: 20px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .info-item i {
        width: 20px;
        color: #ff6b9d;
        margin-right: 10px;
    }
    
    .status {
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
    }
    
    .status.pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status.confirmed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status.in-progress {
        background-color: #cce5ff;
        color: #004085;
    }
    
    .btn-view-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background: linear-gradient(135deg, #ff6b9d 0%, #e55a8a 100%);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-view-details:hover {
        background: linear-gradient(135deg, #e55a8a 0%, #d04b7b 100%);
        color: white;
        text-decoration: none;
    }
    
    .btn-view-details i {
        transition: transform 0.3s ease;
    }
    
    .btn-view-details:hover i {
        transform: translateX(5px);
    }
    
    .see-all-appointments {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #ff6b9d;
        text-decoration: none;
        padding: 10px 20px;
        border: 2px solid #ff6b9d;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    
    .see-all-appointments:hover {
        background-color: #ff6b9d;
        color: white;
        text-decoration: none;
    }
    
    .see-all-appointments i {
        transition: transform 0.3s ease;
    }
    
    .see-all-appointments:hover i {
        transform: translateX(5px);
    }

    /* Hiệu ứng pulse cho lịch đặt trong vòng 24h */
    .appointment-coming-soon-24h {
        animation: pulse-shadow 2s infinite;
        border: 2px solid #ff6b9d;
    }

    .appointment-coming-soon-48h {
        border: 2px solid #ffaacc;
        box-shadow: 0 5px 15px rgba(255, 107, 157, 0.2);
    }

    @keyframes pulse-shadow {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 107, 157, 0.4);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(255, 107, 157, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 107, 157, 0);
        }
    }

    /* Nhãn Sắp đến */
    .coming-soon-badge {
        position: absolute;
        top: 10px;
        right: 20px;
        background: linear-gradient(135deg, #ff6b9d, #ff4d82);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        z-index: 10;
        box-shadow: 0 3px 10px rgba(255, 107, 157, 0.4);
        animation: float 3s ease-in-out infinite;
    }

    .coming-soon-badge i {
        margin-right: 5px;
        animation: pulse 1.5s infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-5px);
        }
        100% {
            transform: translateY(0px);
        }
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
        100% {
            opacity: 1;
        }
    }

    /* Countdown timer */
    .countdown-timer {
        background-color: #fff3cd;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: 10px;
        border-left: 3px solid #ff6b9d;
    }

    .countdown-timer i {
        color: #ff6b9d;
        animation: rotateHourglass 2s linear infinite;
    }

    @keyframes rotateHourglass {
        0% {
            transform: rotate(0deg);
        }
        25% {
            transform: rotate(10deg);
        }
        75% {
            transform: rotate(-10deg);
        }
        100% {
            transform: rotate(0deg);
        }
    }

    .time-left {
        font-weight: 600;
        color: #e55a8a;
    }

    /* Hiệu ứng glow cho tất cả thẻ lịch đặt */
    .appointment-card {
        position: relative;
    }

    .appointment-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        box-shadow: 0 0 15px rgba(255, 107, 157, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .appointment-card:hover::after {
        opacity: 1;
    }

    .quick-contact-menu {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .quick-contact-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #4a4a4a;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        transition: all 0.3s ease;
        font-size: 24px; /* Tăng kích thước icon */
    }

    .quick-contact-toggle:hover {
        transform: scale(1.1);
    }

    .quick-contact-items {
        display: flex;
        flex-direction: row; /* Thay đổi từ column-reverse sang row để hiển thị theo chiều ngang */
        gap: 16px; /* Khoảng cách giữa các nút */
        position: absolute;
        right: 80px; /* Điều chỉnh từ 70px lên 80px để tạo khoảng cách phù hợp */
        bottom: 10px; /* Căn chỉnh theo chiều dọc */
    }

    .quick-contact-item {
        width: 60px; /* Kích thước nút */
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateX(20px); /* Thay đổi từ translateY sang translateX */
        pointer-events: none;
        font-size: 28px;
        text-decoration: none;
    }
    
    .quick-contact-item i {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border-bottom: none;
    }

    .quick-contact-menu.active .quick-contact-item {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }

    .zalo-item {
        background-color: #0068ff;
    }

    .zalo-item img {
        width: 36px;
        height: 36px;
        padding: 4px;
    }

    .messenger-item {
        background-color: #0084ff;
        color: white;
        font-size: 30px;
    }
    
    .messenger-item i {
        margin-bottom: 0;
    }

    .phone-item {
        background-color: #4caf50;
        color: white;
        font-size: 30px;
    }
    
    .phone-item i {
        margin-bottom: 0;
    }

    .quick-contact-item:hover {
        transform: scale(1.1);
    }

    /* Timing for animations - từ phải sang trái */
    .quick-contact-menu.active .phone-item {
        transition-delay: 0.3s; /* Nút xa nhất */
    }
    
    .quick-contact-menu.active .messenger-item {
        transition-delay: 0.2s; /* Nút ở giữa */
    }
    
    .quick-contact-menu.active .zalo-item {
        transition-delay: 0.1s; /* Nút gần nhất */
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('spa-video');
        const videoSource = document.getElementById('video-source');
        
        // Both video sources
        const videoSources = [
            "{{ asset('videos/trailer/Standard_Mode_16x9_m_nh_c_n_b_n_t_o_cho_m_nh_1_vide.mp4') }}",
            "{{ asset('videos/trailer/380867662404993031.mp4') }}"
        ];
        
        let currentVideoIndex = 0;
        
        // Function to switch to the next video
        function playNextVideo() {
            // Log current state
            console.log('Current video index before switch:', currentVideoIndex);
            console.log('Current video source:', video.currentSrc);
            
            // Switch to next video
            currentVideoIndex = (currentVideoIndex + 1) % videoSources.length;
            console.log('Switching to video index:', currentVideoIndex);
            
            // Update source and play
            video.src = videoSources[currentVideoIndex];
            video.load();
            const playPromise = video.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    console.log('Video started playing successfully:', videoSources[currentVideoIndex]);
                }).catch(error => {
                    console.error('Error playing video:', error);
                });
            }
        }
        
        // Add ended event listener to video element
        video.addEventListener('ended', function() {
            console.log('Video ended event triggered');
            playNextVideo();
        });
        
        // Remove loop attribute to ensure ended event fires
        video.removeAttribute('loop');
        
        // Force logging of current video source for debugging
        setInterval(() => {
            if (video.currentTime > 0) {
                console.log('Current playback time:', video.currentTime, 'of', video.duration);
                console.log('Current video source is:', video.currentSrc);
                
                // If video is not playing, try to restart it
                if (video.paused && !video.ended) {
                    console.log('Video paused unexpectedly, trying to resume');
                    video.play();
                }
            }
        }, 5000);
        
        // Set up a backup timer to switch videos if ended event doesn't fire
        video.addEventListener('timeupdate', function() {
            // If we're near the end of the video (last 0.5 seconds)
            if (video.duration > 0 && video.currentTime >= (video.duration - 0.5) && !video.paused) {
                console.log('Near end of video, preparing to switch');
            }
        });

        // Diamond Shine Effect for VIP Card
        const cardElement = document.querySelector('.diamond-effect');
        const shineContainer = document.querySelector('.diamond-shine-container');
        
        if (cardElement && shineContainer) {
            // Create diamond shine points
            const createDiamondShine = () => {
                const shine = document.createElement('div');
                shine.classList.add('diamond-shine');
                
                // Random position within the card
                const x = Math.random() * 100; // percentage
                const y = Math.random() * 100; // percentage
                shine.style.left = `${x}%`;
                shine.style.top = `${y}%`;
                
                // Random size (slightly varied)
                const size = 4 + Math.random() * 8; // between 4px and 12px
                shine.style.width = `${size}px`;
                shine.style.height = `${size}px`;
                
                // Add to container
                shineContainer.appendChild(shine);
                
                // Animate the shine
                setTimeout(() => {
                    shine.style.transition = 'all 0.5s ease-in-out';
                    shine.style.opacity = '1';
                    shine.style.transform = 'scale(1)';
                    
                    // Sparkle effect
                    setTimeout(() => {
                        shine.style.opacity = '0';
                        shine.style.transform = 'scale(1.5)';
                        
                        // Remove after animation completes
                        setTimeout(() => {
                            shine.remove();
                        }, 600);
                    }, 300 + Math.random() * 400);
                }, 10);
            };
            
            // Create shine effects regularly
            const createShineEffect = () => {
                // Create 1-3 shine points at once
                const shineCount = 1 + Math.floor(Math.random() * 3);
                for (let i = 0; i < shineCount; i++) {
                    setTimeout(() => {
                        createDiamondShine();
                    }, i * 150); // Stagger the creation
                }
            };
            
            // Start the shine effects
            setInterval(createShineEffect, 800);
            
            // Also trigger when hovering over the card for extra effect
            cardElement.addEventListener('mousemove', (e) => {
                // Create shine at mouse position
                const shine = document.createElement('div');
                shine.classList.add('diamond-shine');
                
                // Position at mouse within the card
                const rect = cardElement.getBoundingClientRect();
                const x = e.clientX - rect.left; // mouse position relative to card
                const y = e.clientY - rect.top;
                
                shine.style.left = `${x}px`;
                shine.style.top = `${y}px`;
                
                // Random size (slightly larger on hover)
                const size = 8 + Math.random() * 10;
                shine.style.width = `${size}px`;
                shine.style.height = `${size}px`;
                
                // Add to container
                shineContainer.appendChild(shine);
                
                // Animate
                setTimeout(() => {
                    shine.style.transition = 'all 0.6s ease-out';
                    shine.style.opacity = '1';
                    shine.style.transform = 'scale(1)';
                    
                    setTimeout(() => {
                        shine.style.opacity = '0';
                        shine.style.transform = 'scale(2)';
                        
                        setTimeout(() => {
                            shine.remove();
                        }, 700);
                    }, 200);
                }, 10);
            });
        }
    });

    // Hàm tính toán thời gian còn lại và cập nhật đồng hồ đếm ngược
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(function(timer) {
            const bookingTimeStr = timer.dataset.bookingTime;
            const bookingTime = new Date(bookingTimeStr).getTime();
            const now = new Date().getTime();
            const timeLeft = bookingTime - now;
            
            // Nếu thời gian còn lại là dương (chưa tới lịch hẹn)
            if (timeLeft > 0) {
                // Tính giờ, phút, giây
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                
                // Hiển thị kết quả
                timer.querySelector('.time-left').textContent = 
                    hours + "h " + minutes + "m " + seconds + "s";
            } else {
                // Nếu đã đến thời gian lịch hẹn
                timer.querySelector('.time-left').textContent = "Đã đến giờ!";
                timer.classList.add('time-reached');
            }
        });
    }
    
    // Cập nhật đồng hồ đếm ngược ngay khi trang tải xong
    document.addEventListener('DOMContentLoaded', function() {
        updateCountdowns();
        // Cập nhật mỗi giây
        setInterval(updateCountdowns, 1000);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const quickContactMenu = document.querySelector('.quick-contact-menu');
        const toggleButton = document.getElementById('toggleQuickContact');
        
        toggleButton.addEventListener('click', function() {
            quickContactMenu.classList.toggle('active');
            
            // Change icon based on state
            const icon = toggleButton.querySelector('i');
            if (quickContactMenu.classList.contains('active')) {
                icon.classList.remove('fa-comments');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-comments');
            }
        });
        
        // Initial icon
        const icon = toggleButton.querySelector('i');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-comments');
    });
</script>

<!-- Thêm chat widget -->
@include('components.chat-widget')
@endpush