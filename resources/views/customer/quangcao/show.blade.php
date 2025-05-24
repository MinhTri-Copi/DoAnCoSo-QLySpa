@extends('customer.layouts.app')

@section('title', $advertisement->Tieude . ' - ROSA SPA')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.quangcao.index') }}">Quảng cáo</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $advertisement->Tieude }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <!-- Image Header -->
                @if($advertisement->Image)
                    <img src="{{ asset($advertisement->Image) }}" 
                         alt="{{ $advertisement->Tieude }}" 
                         class="card-img-top" style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <i class="fas fa-image text-secondary fa-5x"></i>
                    </div>
                @endif
                
                <!-- Content -->
                <div class="card-body p-4">
                    <!-- Badge and Date -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @php
                            $badgeColors = [
                                'Khuyến mãi' => 'bg-danger',
                                'Sự kiện' => 'bg-primary',
                                'Thông báo' => 'bg-success'
                            ];
                            $badgeColor = $badgeColors[$advertisement->Loaiquangcao] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeColor }} px-3 py-2">
                            {{ $advertisement->Loaiquangcao }}
                        </span>
                        
                        <div class="text-muted small">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ \Carbon\Carbon::parse($advertisement->Ngaybatdau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($advertisement->Ngayketthuc)->format('d/m/Y') }}
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="card-title h3 fw-bold mb-4">{{ $advertisement->Tieude }}</h1>
                    
                    <!-- Content -->
                    <div class="card-text mb-4">
                        {!! $advertisement->Noidung !!}
                    </div>
                    
                    <!-- Related Service -->
                    @if($advertisement->dichVu)
                        <div class="bg-light p-3 rounded mb-4">
                            <h5 class="mb-3"><i class="fas fa-spa me-2 text-primary"></i> Dịch vụ liên quan</h5>
                            <div class="d-flex align-items-center">
                                @if($advertisement->dichVu->Image)
                                    <img src="{{ asset($advertisement->dichVu->Image) }}" 
                                         alt="{{ $advertisement->dichVu->TenDV }}" 
                                         class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-spa fa-2x"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $advertisement->dichVu->TenDV }}</h6>
                                    <p class="text-muted mb-1 small">{{ Str::limit($advertisement->dichVu->MoTa, 100) }}</p>
                                    <a href="{{ route('customer.dichvu.show', $advertisement->dichVu->MaDV) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Xem dịch vụ
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Quay lại
                        </a>
                        <a href="{{ route('customer.quangcao.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i> Xem tất cả quảng cáo
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Ads -->
            @if($relatedAds->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">Quảng cáo liên quan</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($relatedAds as $relatedAd)
                                <a href="{{ route('customer.quangcao.show', $relatedAd->MaQC) }}" 
                                   class="list-group-item list-group-item-action border-0 px-0">
                                    <div class="d-flex">
                                        @if($relatedAd->Image)
                                            <img src="{{ asset($relatedAd->Image) }}" 
                                                 alt="{{ $relatedAd->Tieude }}" 
                                                 class="me-3 rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-3 rounded" 
                                                 style="width: 70px; height: 70px;">
                                                <i class="fas fa-image text-secondary fa-2x"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1 text-truncate">{{ $relatedAd->Tieude }}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($relatedAd->Ngaybatdau)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Ad Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">Danh mục quảng cáo</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('customer.quangcao.index') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bullhorn me-2 text-primary"></i> Tất cả quảng cáo</span>
                        </a>
                        <a href="{{ route('customer.quangcao.featured') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-star me-2 text-warning"></i> Quảng cáo nổi bật</span>
                        </a>
                        <a href="{{ route('customer.quangcao.promotions') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-percent me-2 text-danger"></i> Khuyến mãi</span>
                        </a>
                        <a href="{{ route('customer.quangcao.events') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-alt me-2 text-primary"></i> Sự kiện</span>
                        </a>
                        <a href="{{ route('customer.quangcao.notifications') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bell me-2 text-success"></i> Thông báo</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection