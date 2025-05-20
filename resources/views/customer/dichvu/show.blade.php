@extends('customer.layouts.app')

@section('title', $service->Tendichvu)

@section('content')
<div class="container py-5">
    <!-- Welcome Banner Header Component -->
    <div class="welcome-banner animate__animated animate__fadeIn mb-4">
        <h1><i class="fas fa-spa"></i> Chi tiết dịch vụ</h1>
        <p>Khám phá thông tin đầy đủ về dịch vụ của chúng tôi</p>
        <div class="shine-line"></div>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dichvu.index') }}">Dịch vụ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $service->Tendichvu }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm service-image-card">
                @if($service->Hinhanh)
                    <img src="{{ route('storage.image', ['path' => 'services/' . $service->Hinhanh]) }}" class="card-img-top" alt="{{ $service->Tendichvu }}" style="height: 400px; object-fit: cover;">
                @elseif($service->Image)
                    <img src="{{ asset($service->Image) }}" class="card-img-top" alt="{{ $service->Tendichvu }}" style="height: 400px; object-fit: cover;">
                @else
                    <div class="bg-light text-center py-5" style="height: 400px;">
                        <i class="fas fa-spa fa-5x text-muted mt-5"></i>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-7">
            <div class="service-details-card">
                <h1 class="service-title mb-3">{{ $service->Tendichvu }}</h1>
                
                <div class="d-flex align-items-center mb-3">
                    @if($service->featured)
                        <span class="badge bg-info me-2">Nổi bật</span>
                    @endif
                </div>
                
                <h3 class="service-price mb-4">{{ number_format($service->Gia, 0, ',', '.') }} VNĐ</h3>
                
                <div class="service-description mb-4">
                    <h5 class="section-title mb-3">Mô tả dịch vụ:</h5>
                    <p>{{ $service->MoTa }}</p>
                </div>
                
                <div class="service-info mb-4">
                    <h5 class="section-title mb-3">Thông tin chi tiết:</h5>
                    <div class="row service-info-items">
                        <div class="col-md-6 mb-3">
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <span>Thời gian: {{ $service->Thoigian ? $service->Thoigian->format('H:i') : 'Liên hệ để biết thêm' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-user-check me-2"></i>
                                <span>Độ tuổi phù hợp: {{ $service->Dotuoiphuhop ?? 'Mọi lứa tuổi' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-sync-alt me-2"></i>
                                <span>Số lần điều trị: {{ $service->Solandieutri ?? 'Tùy theo nhu cầu' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item d-flex align-items-center">
                                <i class="fas fa-certificate me-2"></i>
                                <span>Hiệu quả: {{ $service->Hieuqua ?? 'Tùy theo cơ địa từng người' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex mt-4">
                    <a href="{{ $bookingUrl }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-check me-2"></i>Đặt lịch ngay
                    </a>
                    <a href="{{ route('customer.dichvu.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(count($relatedServices) > 0)
    <div class="mt-5 related-services-section">
        <h3 class="section-title mb-4">Dịch vụ liên quan</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($relatedServices as $relatedService)
                <div class="col">
                    <div class="card h-100 shadow-sm hover-shadow">
                        @if($relatedService->Hinhanh)
                            <img src="{{ route('storage.image', ['path' => 'services/' . $relatedService->Hinhanh]) }}" class="card-img-top" alt="{{ $relatedService->Tendichvu }}" style="height: 150px; object-fit: cover;">
                        @elseif($relatedService->Image)
                            <img src="{{ asset($relatedService->Image) }}" class="card-img-top" alt="{{ $relatedService->Tendichvu }}" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light text-center py-4" style="height: 150px;">
                                <i class="fas fa-spa fa-3x text-muted mt-3"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedService->Tendichvu }}</h5>
                            <p class="text-primary fw-bold">{{ number_format($relatedService->Gia, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('customer.dichvu.show', $relatedService->MaDV) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Đánh giá và bình luận -->
    <div class="mt-5 reviews-section">
        <h3 class="section-title mb-4">Đánh giá từ khách hàng</h3>
        
        <!-- Hiển thị đánh giá trung bình -->
        <div class="card border-0 shadow-sm mb-4 rating-summary-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <h1 class="display-4 fw-bold text-primary mb-0">{{ $averageRating }}</h1>
                        <p class="text-muted">trên 5</p>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageRating)
                                    <i class="fas fa-star text-warning fa-lg"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt text-warning fa-lg"></i>
                                @else
                                    <i class="far fa-star text-warning fa-lg"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="mb-0">{{ $ratingCount }} đánh giá</p>
                    </div>
                    <div class="col-md-9">
                        <div class="row align-items-center mb-2">
                            <div class="col-3 text-end">
                                <span>5 <i class="fas fa-star text-warning"></i></span>
                            </div>
                            <div class="col-7">
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $fiveStars = $ratingCount > 0 ? round($reviews->where('Danhgiasao', 5)->count() / $ratingCount * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fiveStars }}%"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <span class="text-muted small">{{ $fiveStars }}%</span>
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-3 text-end">
                                <span>4 <i class="fas fa-star text-warning"></i></span>
                            </div>
                            <div class="col-7">
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $fourStars = $ratingCount > 0 ? round($reviews->where('Danhgiasao', 4)->count() / $ratingCount * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fourStars }}%"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <span class="text-muted small">{{ $fourStars }}%</span>
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-3 text-end">
                                <span>3 <i class="fas fa-star text-warning"></i></span>
                            </div>
                            <div class="col-7">
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $threeStars = $ratingCount > 0 ? round($reviews->where('Danhgiasao', 3)->count() / $ratingCount * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $threeStars }}%"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <span class="text-muted small">{{ $threeStars }}%</span>
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-3 text-end">
                                <span>2 <i class="fas fa-star text-warning"></i></span>
                            </div>
                            <div class="col-7">
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $twoStars = $ratingCount > 0 ? round($reviews->where('Danhgiasao', 2)->count() / $ratingCount * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $twoStars }}%"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <span class="text-muted small">{{ $twoStars }}%</span>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-3 text-end">
                                <span>1 <i class="fas fa-star text-warning"></i></span>
                            </div>
                            <div class="col-7">
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $oneStar = $ratingCount > 0 ? round($reviews->where('Danhgiasao', 1)->count() / $ratingCount * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $oneStar }}%"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <span class="text-muted small">{{ $oneStar }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if(count($reviews) > 0)
            <div class="card border-0 shadow-sm reviews-list-card">
                <div class="card-body">
                    @foreach($reviews as $review)
                        <div class="d-flex mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }} review-item">
                            <div class="flex-shrink-0">
                                <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ strtoupper(substr($review->Hoten ?? 'Khách hàng', 0, 1)) }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">{{ $review->Hoten ?? 'Khách hàng' }}</h5>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->Danhgiasao)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 text-muted small">{{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y') }}</span>
                                </div>
                                <p class="mb-0">{{ $review->Nhanxet }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-5 bg-light rounded no-reviews">
                <i class="far fa-comment-alt fa-3x text-muted mb-3"></i>
                <p class="lead">Chưa có đánh giá nào cho dịch vụ này</p>
                <p>Hãy là người đầu tiên đánh giá sau khi trải nghiệm dịch vụ!</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* Hover effect */
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Welcome banner */
    .welcome-banner {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background: linear-gradient(145deg, #f58cba, #db7093);
        animation: softPulse 4s infinite alternate, floatAnimation 6s ease-in-out infinite;
        transition: all 0.5s ease;
        box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
        transform-origin: center center;
        padding: 25px 35px;
        margin-bottom: 25px;
        color: white;
    }
    
    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: white;
    }
    
    .welcome-banner p {
        font-size: 1.05rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }
    
    /* Service details styling */
    .service-details-card {
        padding: 0 10px;
    }
    
    .service-title {
        font-weight: 700;
        color: #333;
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
    }
    
    .service-title:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        left: 0;
        bottom: 0;
        background: linear-gradient(to right, #FF9A9E, #FECFEF);
    }
    
    .service-price {
        background: linear-gradient(to right, #FF9A9E, #FECFEF);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: bold;
        font-size: 1.8rem;
    }
    
    .section-title {
        position: relative;
        padding-left: 15px;
        font-weight: 600;
    }
    
    .section-title:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #FF9A9E, #FECFEF);
        border-radius: 4px;
    }
    
    .service-image-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1) !important;
    }
    
    .service-image-card img {
        transition: all 0.5s ease;
    }
    
    .service-image-card:hover img {
        transform: scale(1.05);
    }
    
    .info-item {
        padding: 10px 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .info-item:hover {
        background-color: #fff5f7;
        transform: translateX(5px);
    }
    
    .info-item i {
        color: #FF9A9E;
    }
    
    /* Review section */
    .rating-summary-card, .reviews-list-card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .reviews-section .progress {
        border-radius: 5px;
        background-color: #f1f1f1;
    }
    
    .review-item .avatar {
        background: linear-gradient(145deg, #FF9A9E, #FECFEF);
        box-shadow: 0 3px 8px rgba(255, 154, 158, 0.3);
    }
    
    .no-reviews {
        border-radius: 10px;
        background-color: #fff5f7;
        border: 1px dashed #ffd1d9;
    }
    
    /* Override footer styling to match pink theme */
    footer {
        background: linear-gradient(to right, #FF9A9E, #FECFEF);
        padding: 3rem 0;
        margin-top: 3rem;
        color: white;
        box-shadow: 0 -5px 15px rgba(255, 154, 158, 0.15);
    }
    
    footer .footer-title {
        color: white;
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    footer .footer-title:after {
        content: '';
        display: block;
        width: 40px;
        height: 3px;
        background-color: white;
        margin-top: 8px;
    }
    
    footer .footer-link {
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.3s;
    }
    
    footer .footer-link:hover {
        color: white;
        text-decoration: none;
        padding-left: 5px;
    }
    
    footer .social-links a {
        color: white;
        background-color: rgba(255, 255, 255, 0.2);
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 10px;
        transition: all 0.3s;
    }
    
    footer .social-links a:hover {
        background-color: white;
        color: #FF9A9E;
        transform: translateY(-3px);
    }
    
    footer hr {
        background-color: rgba(255, 255, 255, 0.2);
        margin: 2rem 0;
    }
    
    /* Animations */
    @keyframes softPulse {
        0% {
            box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
        }
        100% {
            box-shadow: 0 8px 25px rgba(219, 112, 147, 0.5);
        }
    }
    
    @keyframes floatAnimation {
        0% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
        100% {
            transform: translateY(0);
        }
    }
</style>
@endsection 