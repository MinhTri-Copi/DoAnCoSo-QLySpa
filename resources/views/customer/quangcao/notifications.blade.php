@extends('customer.layouts.app')

@section('title', 'Thông báo - ROSA SPA')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-500 to-teal-500 text-white p-4 p-md-5 rounded-lg mb-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">Thông báo</h1>
                <p class="lead mb-0">Cập nhật các thông tin mới nhất từ ROSA SPA</p>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-end">
                <i class="fas fa-bell fa-4x opacity-75"></i>
            </div>
        </div>
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
                            
                            <!-- Notification Badge -->
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    Thông báo
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
                               class="btn btn-success w-100">
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
                <i class="fas fa-bell text-secondary fa-3x"></i>
            </div>
            <h3 class="h4 fw-bold text-muted mb-2">Không có thông báo nào</h3>
            <p class="text-muted">Hiện tại không có thông báo nào để hiển thị.</p>
            <a href="{{ route('customer.quangcao.index') }}" class="btn btn-outline-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i> Xem tất cả quảng cáo
            </a>
        </div>
    @endif
</div>

@section('styles')
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
@endsection