@extends('customer.layouts.app')

@section('title', 'Trang chủ - Spa & Làm đẹp')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 99%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <h1 class="display-4 text-white fw-bold mb-4">Chào mừng đến với dịch vụ spa của chúng tôi</h1>
                <p class="lead text-white mb-4">Đắm chìm trong không gian thư giãn và trải nghiệm dịch vụ chăm sóc cơ thể chuyên nghiệp</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('customer.dichvu.index') }}" class="btn btn-light btn-lg px-4">Khám phá dịch vụ</a>
                    <a href="{{ route('customer.datlich.create') }}" class="btn btn-outline-light btn-lg px-4">Đặt lịch ngay</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/hero-spa.png') }}" alt="Spa experience" class="img-fluid rounded-3 shadow" onerror="this.src='https://placehold.co/600x400?text=Spa+Experience'">
            </div>
        </div>
    </div>
</section>

<!-- Featured Advertisements Section -->
@if(count($featuredAds) > 0)
<section class="featured-ads py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4">Ưu đãi đặc biệt</h2>
        <div class="row">
            @foreach($featuredAds as $ad)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        @if($ad->Hinhanh)
                        <img src="{{ asset($ad->Hinhanh) }}" class="card-img-top" alt="{{ $ad->Tieude }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                        @endif
                        <div class="position-absolute top-0 end-0 bg-primary text-white px-2 py-1 m-2 rounded-pill">
                            Nổi bật
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $ad->Tieude }}</h5>
                        <p class="card-text text-muted small">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            {{ \Carbon\Carbon::parse($ad->Ngaybatdau)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($ad->Ngayketthuc)->format('d/m/Y') }}
                        </p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($ad->Noidung, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('customer.quangcao.show', $ad->MaQC) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Services Section -->
@if(count($featuredServices) > 0)
<section class="featured-services py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Dịch vụ nổi bật</h2>
        <div class="row">
            @foreach($featuredServices as $service)
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        @if($service->Image)
                        <img src="{{ asset($service->Image) }}" class="card-img-top" alt="{{ $service->Tendichvu }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-spa fa-3x"></i>
                        </div>
                        @endif
                        <div class="position-absolute bottom-0 start-0 bg-primary text-white px-2 py-1 m-2">
                            {{ number_format($service->Gia, 0, ',', '.') }} VND
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->Tendichvu }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($service->MoTa ?? 'Không có mô tả', 80) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                        <a href="{{ route('customer.dichvu.show', $service->MaDV) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                        <a href="{{ route('customer.datlich.create', ['service_id' => $service->MaDV]) }}" class="btn btn-sm btn-primary">Đặt lịch</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('customer.dichvu.index') }}" class="btn btn-outline-primary">Xem tất cả dịch vụ</a>
        </div>
    </div>
</section>
@endif

<!-- Upcoming Bookings Section (Only for logged in users) -->
@if(count($upcomingBookings) > 0)
<section class="upcoming-bookings py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4">Lịch đặt sắp tới của bạn</h2>
        <div class="row">
            @foreach($upcomingBookings as $booking)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $booking->dichVu->Tendichvu ?? 'Dịch vụ không xác định' }}</h5>
                        <p class="card-text">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}
                        </p>
                        <p class="card-text">
                            <i class="fas fa-clock me-2"></i>
                            {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}
                        </p>
                        @if($booking->Trangthai_ == 1)
                            <span class="badge bg-warning">Chờ xác nhận</span>
                        @elseif($booking->Trangthai_ == 2)
                            <span class="badge bg-success">Đã xác nhận</span>
                        @elseif($booking->Trangthai_ == 3)
                            <span class="badge bg-info">Đang thực hiện</span>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="{{ route('customer.lichsudatlich.show', $booking->MaDL) }}" class="btn btn-sm btn-outline-primary w-100">Chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-outline-primary">Xem tất cả lịch đặt</a>
        </div>
    </div>
</section>
@endif

<!-- Promotional Banner -->
@if(count($promotionAds) > 0)
<section class="promo-banner py-5" style="background-color: #FFF5F7;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="h1 mb-4">{{ $promotionAds[0]->Tieude ?? 'Ưu đãi đặc biệt' }}</h2>
                <p class="lead mb-4">{{ $promotionAds[0]->Noidung ?? 'Hãy khám phá các ưu đãi độc quyền của chúng tôi dành cho bạn.' }}</p>
                @if(isset($promotionAds[0]))
                <a href="{{ route('customer.quangcao.show', $promotionAds[0]->MaQC) }}" class="btn btn-primary btn-lg">Khám phá ngay</a>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                @if(isset($promotionAds[0]) && $promotionAds[0]->Hinhanh)
                <img src="{{ asset($promotionAds[0]->Hinhanh) }}" class="img-fluid rounded-3 shadow" alt="Promotion">
                @else
                <img src="https://placehold.co/600x400?text=Ưu+đãi+đặc+biệt" class="img-fluid rounded-3 shadow" alt="Promotion">
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

<!-- Booking Form Section -->
<section class="booking-cta py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="mb-4">Bạn muốn đặt lịch ngay?</h2>
                <p class="lead mb-4">Chúng tôi sẵn sàng mang đến cho bạn trải nghiệm spa tuyệt vời nhất</p>
                <a href="{{ route('customer.datlich.create') }}" class="btn btn-light btn-lg px-5">Đặt lịch ngay</a>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
@if(count($latestReviews) > 0)
<section class="reviews py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Khách hàng nói gì về chúng tôi</h2>
        <div class="row">
            @foreach($latestReviews as $review)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $review->Manguoidung }}</h6>
                                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($review->diemdanhgia ?? 5))
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($review->Noidungdanhgia ?? 'Không có nội dung đánh giá', 120) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@section('styles')
<style>
    .hero-section {
        position: relative;
        overflow: hidden;
        padding: 80px 0;
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
        background: linear-gradient(to right, #FF9A9E, #FECFEF);
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
</style>
@endsection