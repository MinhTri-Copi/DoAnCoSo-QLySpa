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
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="fas fa-search text-primary me-2"></i>Tìm kiếm dịch vụ</h5>
            <button type="button" class="btn-filter-toggle" id="toggleSearchForm">
                <i class="fas fa-filter"></i>
            </button>
        </div>
        <div class="card-body" id="searchFormContainer">
            <form action="{{ route('customer.dichvu.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="search" name="search" value="{{ request('search') }}" placeholder="Tên dịch vụ...">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Khoảng giá (VNĐ)</label>
                    <div class="row">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-tag text-primary"></i>
                                </span>
                                <input type="number" class="form-control border-start-0" name="min_price" value="{{ request('min_price') }}" placeholder="Từ" min="0" step="10000">
                            </div>
                        </div>
                        <div class="col-auto pt-2">đến</div>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-tags text-primary"></i>
                                </span>
                                <input type="number" class="form-control border-start-0" name="max_price" value="{{ request('max_price') }}" placeholder="Đến" min="0" step="10000">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sắp xếp</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-sort text-primary"></i>
                        </span>
                        <select class="form-select border-start-0" id="sort" name="sort">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                        </select>
                    </div>
                </div>
                
                <!-- Lọc theo đặc điểm -->
                <div class="col-md-12 mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Lọc theo đánh giá - Giữ nguyên phần này -->
                            <label class="form-label fw-bold">Lọc theo đánh giá</label>
                            <div class="card shadow-sm mb-3">
                                <div class="card-body p-3">
                                    <h6 class="mb-3 d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        Lọc chính xác theo số sao
                                    </h6>
                                    
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
                        
                        <div class="col-md-6">
                            <!-- Bộ lọc theo loại dịch vụ -->
                            <!-- Đã xoá block chọn loại dịch vụ theo yêu cầu -->
                        </div>
                    </div>
                </div>
                
                <div class="col-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-search me-2"></i>Lọc kết quả
                        </button>
                        <a href="{{ route('customer.dichvu.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Đặt lại bộ lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Services Section - Only show if there are featured services -->
    @php
        $featuredServices = $services->filter(function($service) {
            return $service->featured;
        });
    @endphp
    
    @if($featuredServices->count() > 0)
    <div class="mb-5">
        <div class="featured-header p-3 mb-4" style="background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 99%); border-radius: 8px;">
            <h3 class="text-white mb-0"><i class="fas fa-crown me-2"></i>Dịch vụ nổi bật</h3>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($featuredServices as $service)
                <div class="col">
                    <div class="card h-100 shadow-sm hover-shadow">
                        <div class="position-absolute top-0 start-0 p-2 z-index-1">
                            <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">
                                <i class="fas fa-star me-1"></i>Nổi bật
                            </span>
                        </div>
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
                                <a href="{{ $bookingRoute }}?service_id={{ $service->MaDV }}&step=2" class="btn btn-primary">
                                    <i class="fas fa-calendar-check me-1"></i>Đặt lịch
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All Services Section -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-4">Tất cả dịch vụ</h3>
        </div>
    </div>

    <!-- Service List -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($services as $service)
            <div class="col">
                <div class="card h-100 shadow-sm hover-shadow">
                    @if($service->featured)
                    <div class="position-absolute top-0 start-0 p-2 z-index-1">
                        <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">
                            <i class="fas fa-star me-1"></i>Nổi bật
                        </span>
                    </div>
                    @endif
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
                            <a href="{{ $bookingRoute }}?service_id={{ $service->MaDV }}&step=2" class="btn btn-primary">
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
    /* Thêm style cho thanh tìm kiếm và bộ lọc */
    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 0.5rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #FF9A9E;
        box-shadow: 0 0 0 0.25rem rgba(255, 154, 158, 0.25);
    }
    
    .input-group-text {
        color: #FF9A9E;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #FF9A9E 0%, #FF6B6B 100%);
        border-color: #FF9A9E;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
        border-color: #FF6B6B;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
    }
    
    .btn-outline-secondary {
        color: #666;
        border-color: #ccc;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #333;
        border-color: #bbb;
    }
    
    /* Hiệu ứng cho các card */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    /* Kiểu dáng cho các badge */
    .badge {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.7rem;
    }
    
    /* Kiểu dáng cho các checkbox và radio */
    .form-check-input:checked {
        background-color: #FF9A9E;
        border-color: #FF9A9E;
    }
    
    .form-check-input:focus {
        border-color: #FF9A9E;
        box-shadow: 0 0 0 0.25rem rgba(255, 154, 158, 0.25);
    }
    
    /* Giữ nguyên các style khác */
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
    
    /* Style cho nút toggle bộ lọc */
    .btn-filter-toggle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #FF9A9E;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-filter-toggle:hover {
        background-color: #FF9A9E;
        color: white;
        box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
    }
    
    .btn-filter-toggle.active {
        background-color: #FF9A9E;
        color: white;
        transform: rotate(180deg);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Hiệu ứng cho phần searchFormContainer */
    #searchFormContainer {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .collapsed {
        max-height: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle search form visibility
    const toggleBtn = document.getElementById('toggleSearchForm');
    const searchFormContainer = document.getElementById('searchFormContainer');
    
    // Check if there's a stored preference
    const isCollapsed = localStorage.getItem('searchFormCollapsed') === 'true';
    
    // Set initial state based on stored preference
    if (isCollapsed) {
        searchFormContainer.classList.add('collapsed');
        toggleBtn.classList.add('active');
    }
    
    // Add click event to toggle button
    toggleBtn.addEventListener('click', function() {
        searchFormContainer.classList.toggle('collapsed');
        toggleBtn.classList.toggle('active');
        
        // Store the preference
        localStorage.setItem('searchFormCollapsed', searchFormContainer.classList.contains('collapsed'));
    });
    
    // If any filter is active, expand the form automatically
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('min_price') || urlParams.has('max_price') || 
        urlParams.has('sort') || urlParams.has('star_rating') || urlParams.has('featured') || 
        urlParams.has('has_promotion') || urlParams.has('is_new')) {
        
        searchFormContainer.classList.remove('collapsed');
        toggleBtn.classList.remove('active');
        localStorage.setItem('searchFormCollapsed', 'false');
    }
});
</script>
@endsection 