@extends('customer.layouts.app')

@section('title', 'Quảng cáo - ROSA SPA')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="welcome-banner animate__animated animate__fadeIn">
        <h1><i class="fas fa-bullhorn"></i> Quảng cáo & Khuyến mãi</h1>
        <p>Khám phá những ưu đãi và sự kiện đặc biệt tại ROSA SPA</p>
        <div class="shine-line"></div>
    </div>

    <!-- Advertisements Grid -->
    @if($advertisements->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
            @foreach($advertisements as $ad)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 hover-shadow">
                        <!-- Image -->
                        <div class="position-relative">
                            @if($ad->Image)
                                <img src="{{ asset($ad->Image) }}" 
                                     alt="{{ $ad->Tieude }}" 
                                     class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image text-secondary fa-3x"></i>
                                </div>
                            @endif
                            
                            <!-- Ad Type Badge -->
                            <div class="position-absolute top-0 start-0 m-3">
                                @php
                                    $badgeColors = [
                                        'Khuyến mãi' => 'bg-danger',
                                        'Sự kiện' => 'bg-primary',
                                        'Thông báo' => 'bg-success'
                                    ];
                                    $badgeColor = $badgeColors[$ad->Loaiquangcao] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeColor }} rounded-pill px-3 py-2">
                                    {{ $ad->Loaiquangcao }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="card-body">
                            <h3 class="card-title h5 fw-bold text-truncate mb-3">{{ $ad->Tieude }}</h3>
                            
                            <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {!! Str::limit(strip_tags($ad->Noidung), 120) !!}
                            </p>
                            
                            <!-- Date Range -->
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>{{ \Carbon\Carbon::parse($ad->Ngaybatdau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($ad->Ngayketthuc)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="{{ route('customer.quangcao.show', $ad->MaQC) }}" 
                               class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $advertisements->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                <i class="fas fa-bullhorn text-secondary fa-3x"></i>
            </div>
            <h3 class="h4 fw-bold text-muted mb-2">Không có quảng cáo nào</h3>
            <p class="text-muted">Hiện tại không có quảng cáo nào phù hợp để hiển thị.</p>
        </div>
    @endif
</div>

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Welcome banner styles */
    .welcome-banner {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background: linear-gradient(145deg, #f58cba, #db7093);
        animation: softPulse 4s infinite alternate, floatAnimation 6s ease-in-out infinite;
        transition: all 0.5s ease;
        box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
        transform-origin: center center;
        width: 100%;
        padding: 30px 35px;
        margin-bottom: 30px;
        color: white;
    }

    .welcome-banner:before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        z-index: -1;
        background: linear-gradient(45deg, 
            #ff7eb3, #ff758c, #ff7eb3, #ff8e8c, 
            #fdae9e, #ff7eb3, #ff758c, #ff7eb3);
        background-size: 400%;
        border-radius: 14px;
        animation: borderGlow 12s linear infinite;
        filter: blur(10px);
        opacity: 0.7;
    }

    .welcome-banner:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.1) 25%, 
            rgba(255,255,255,0.2) 50%, 
            rgba(255,255,255,0.1) 75%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 6s infinite;
        z-index: 1;
        pointer-events: none;
    }

    .welcome-banner h1, .welcome-banner p {
        position: relative;
        z-index: 2;
    }

    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 12px;
        color: white;
    }

    .welcome-banner p {
        font-size: 1.05rem;
        opacity: 0.9;
        margin-bottom: 5px;
        position: relative;
        z-index: 1;
        max-width: 80%;
    }

    .shine-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.95);
        z-index: 2;
        animation: corner-to-corner 12s infinite cubic-bezier(0.25, 0.1, 0.25, 1);
        opacity: 0;
    }

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

    @keyframes borderGlow {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    @keyframes corner-to-corner {
        0% {
            opacity: 0;
            top: 2px;
            left: 2px;
            box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.7);
        }
        5% {
            opacity: 1;
            top: 2px;
            left: 2px;
            box-shadow: 0 0 15px 3px rgba(255, 255, 255, 0.8);
        }
        
        30% {
            top: 40%;
            left: 2px;
            box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.9);
            opacity: 1;
        }
        
        60% {
            top: calc(100% - 2px);
            left: 60%;
            box-shadow: 0 0 25px 6px rgba(255, 255, 255, 1);
            opacity: 1;
        }
        
        80% {
            top: calc(100% - 2px);
            left: calc(100% - 2px);
            box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.9);
            opacity: 1;
        }
        
        85% {
            opacity: 0.7;
        }
        
        90% {
            opacity: 0;
        }
        
        100% {
            opacity: 0;
            top: calc(100% - 2px);
            left: calc(100% - 2px);
        }
    }
</style>
@endsection
@endsection