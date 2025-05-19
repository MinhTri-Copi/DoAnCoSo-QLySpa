@extends('customer.layouts.app')

@section('title', $service->Tendichvu)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dichvu.index') }}">Dịch vụ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $service->Tendichvu }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
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
            <h1 class="mb-3">{{ $service->Tendichvu }}</h1>
            
            <div class="d-flex align-items-center mb-3">
                @if($service->featured)
                    <span class="badge bg-info me-2">Nổi bật</span>
                @endif
            </div>
            
            <h3 class="text-primary mb-4">{{ number_format($service->Gia, 0, ',', '.') }} VNĐ</h3>
            
            <div class="mb-4">
                <h5 class="mb-3">Mô tả dịch vụ:</h5>
                <p>{{ $service->MoTa }}</p>
            </div>
            
            <div class="mb-4">
                <h5 class="mb-3">Thông tin chi tiết:</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span>Thời gian: {{ $service->Thoigian ? $service->Thoigian->format('H:i') : 'Liên hệ để biết thêm' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-check text-primary me-2"></i>
                            <span>Độ tuổi phù hợp: {{ $service->Dotuoiphuhop ?? 'Mọi lứa tuổi' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-sync-alt text-primary me-2"></i>
                            <span>Số lần điều trị: {{ $service->Solandieutri ?? 'Tùy theo nhu cầu' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-certificate text-primary me-2"></i>
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

    @if(count($relatedServices) > 0)
    <div class="mt-5">
        <h3 class="mb-4">Dịch vụ liên quan</h3>
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
    <div class="mt-5">
        <h3 class="mb-4">Đánh giá từ khách hàng</h3>
        
        @if(count($reviews) > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @foreach($reviews as $review)
                        <div class="d-flex mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
            <div class="text-center py-5 bg-light rounded">
                <i class="far fa-comment-alt fa-3x text-muted mb-3"></i>
                <p class="lead">Chưa có đánh giá nào cho dịch vụ này</p>
                <p>Hãy là người đầu tiên đánh giá sau khi trải nghiệm dịch vụ!</p>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection 