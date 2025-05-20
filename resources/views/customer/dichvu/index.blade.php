@extends('customer.layouts.app')

@section('title', 'Dịch vụ')

@section('content')
<div class="container py-5">
    <!-- Welcome Banner Header Component - Giống với trang admin -->
    <div class="welcome-banner animate__animated animate__fadeIn">
        <h1><i class="fas fa-hand-sparkles"></i> Dịch vụ của chúng tôi</h1>
        <p>Khám phá các dịch vụ cao cấp và chuyên nghiệp của Rosa Spa</p>
        <div class="shine-line"></div>
    </div>

    <!-- Filter and Search Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('customer.dichvu.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Tên dịch vụ...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Khoảng giá (VNĐ)</label>
                    <div class="row">
                        <div class="col">
                            <input type="number" class="form-control" name="min_price" value="{{ request('min_price') }}" placeholder="Từ" min="0" step="10000">
                        </div>
                        <div class="col-auto pt-2">đến</div>
                        <div class="col">
                            <input type="number" class="form-control" name="max_price" value="{{ request('max_price') }}" placeholder="Đến" min="0" step="10000">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sắp xếp</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Lọc theo đánh giá</label>
                    <!-- Bộ lọc chi tiết theo từng mức sao -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body p-3">
                            <h6 class="mb-3">Lọc chính xác theo số sao</h6>
                            
                            <div class="form-check d-flex align-items-center mb-2">
                                <input class="form-check-input" type="radio" name="star_rating" 
                                    id="star_rating_any" value="" 
                                    {{ !request('star_rating') ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="star_rating_any">
                                    Tất cả đánh giá
                                </label>
                            </div>
                            
                            @for($i = 5; $i >= 1; $i--)
                                <div class="form-check d-flex align-items-center mb-2">
                                    <input class="form-check-input" type="radio" name="star_rating" 
                                        id="star_rating_{{ $i }}" value="{{ $i }}" 
                                        {{ request('star_rating') == $i ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 d-flex align-items-center" for="star_rating_{{ $i }}">
                                        @for($j = 1; $j <= 5; $j++)
                                            @if($j <= $i)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 text-muted small">
                                            ({{ $starRatingCounts[$i] }} đánh giá)
                                        </span>
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Lọc kết quả
                    </button>
                    <a href="{{ route('customer.dichvu.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>Đặt lại bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Service List -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($services as $service)
            <div class="col">
                <div class="card h-100 shadow-sm hover-shadow">
                    @if($service->Hinhanh)
                        <img src="{{ route('storage.image', ['path' => 'services/' . $service->Hinhanh]) }}" class="card-img-top" alt="{{ $service->Tendichvu }}" style="height: 200px; object-fit: cover;">
                    @elseif($service->Image)
                        <img src="{{ asset($service->Image) }}" class="card-img-top" alt="{{ $service->Tendichvu }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light text-center py-5" style="height: 200px;">
                            <i class="fas fa-spa fa-4x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->Tendichvu }}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-primary fw-bold">{{ number_format($service->Gia, 0, ',', '.') }} VNĐ</span>
                            @if($service->featured)
                                <span class="badge bg-info">Nổi bật</span>
                            @endif
                        </div>
                        
                        <!-- Hiển thị đánh giá sao -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                @php $avgRating = isset($service->average_rating) ? $service->average_rating : 0; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avgRating)
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $avgRating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">
                                {{ $avgRating }} 
                                <span class="ms-1">({{ isset($service->rating_count) ? $service->rating_count : 0 }})</span>
                            </small>
                        </div>
                        
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($service->MoTa, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.dichvu.show', $service->MaDV) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Chi tiết
                            </a>
                            <a href="{{ $bookingRoute }}?service_id={{ $service->MaDV }}" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-1"></i>Đặt lịch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="lead">Không tìm thấy dịch vụ phù hợp với bộ lọc của bạn</p>
                <a href="{{ route('customer.dichvu.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-undo me-2"></i>Đặt lại bộ lọc
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            {{ $services->withQueryString()->links('pagination::bootstrap-4') }}
        </nav>
    </div>

    <!-- Info Section -->
    <div class="card mt-5 bg-light border-0">
        <div class="card-body">
            <h3 class="card-title">Tại sao chọn dịch vụ của chúng tôi?</h3>
            <div class="row mt-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-certificate text-primary fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Chất lượng hàng đầu</h5>
                            <p class="text-muted">Chúng tôi cam kết mang đến cho khách hàng những trải nghiệm dịch vụ chất lượng cao nhất.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Đội ngũ chuyên nghiệp</h5>
                            <p class="text-muted">Đội ngũ nhân viên giàu kinh nghiệm và được đào tạo chuyên sâu.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-heart text-primary fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h5>Sự hài lòng của khách hàng</h5>
                            <p class="text-muted">Mục tiêu hàng đầu của chúng tôi là sự hài lòng của khách hàng.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    .card .badge {
        font-size: 0.75rem;
    }
    
    /* Custom pagination styling */
    .pagination {
        margin-bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .page-item:not(.active) .page-link {
        color: #666;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    .page-item.active .page-link {
        background-color: #FF9A9E;
        border-color: #FF9A9E;
    }
    
    .page-item.disabled .page-link {
        color: #ccc;
    }
    
    .page-link {
        width: 40px;
        height: 40px;
        line-height: 25px;
        text-align: center;
        font-weight: 500;
        border-radius: 4px;
        margin: 0 3px;
        transition: all 0.2s;
    }
    
    .page-link:hover {
        background-color: #fff5f7;
        color: #FF6B6B;
        border-color: #ffd8db;
    }
    
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        width: 40px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Override footer styling to match pink theme */
    footer {
        background: linear-gradient(to right, #FF9A9E, #FECFEF);
        margin-top: 3rem;
        padding: 3rem 0;
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
</style>
@endsection 