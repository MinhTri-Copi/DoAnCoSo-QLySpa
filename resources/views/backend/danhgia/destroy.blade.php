@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Đánh Giá')

@section('styles')
<style>
    .custom-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }
    .custom-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #ff6b95;
    }
    .card-header-danger {
        background-color: #dc3545;
        color: white;
        border-bottom: none;
    }
    .btn-danger-gradient {
        background: linear-gradient(45deg, #dc3545, #ff4d5f);
        border: none;
        color: white;
    }
    .btn-danger-gradient:hover {
        background: linear-gradient(45deg, #c82333, #e83446);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        color: white;
    }
    .alert-warning-custom {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        border-radius: 0.25rem;
    }
    .review-info {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
    }
    .review-text {
        background-color: #f0f0f0;
        border-left: 4px solid #6c757d;
        padding: 15px;
        border-radius: 0 8px 8px 0;
    }
    .badge-rating {
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        background-color: #ff6b95;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Xác Nhận Xóa Đánh Giá</h1>
        <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card mb-4 custom-card">
        <div class="card-header card-header-danger">
            <i class="fas fa-exclamation-triangle me-1"></i>
            <span class="fw-bold">Cảnh báo: Hành động này không thể hoàn tác</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning-custom p-4 mb-4">
                <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-circle me-2"></i> Bạn có chắc chắn muốn xóa đánh giá này?</h5>
                <p class="mb-0">Bạn đang chuẩn bị xóa đánh giá có mã <strong class="text-danger">{{ $danhGia->MaDG }}</strong>. Hành động này không thể hoàn tác và tất cả dữ liệu liên quan sẽ bị mất vĩnh viễn.</p>
            </div>
            
            <div class="card mb-4 custom-card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1 text-primary"></i> Thông tin đánh giá
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="review-info mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <p class="mb-0 fw-bold"><i class="fas fa-hashtag text-secondary me-1"></i> Mã đánh giá:</p>
                                        <h5>{{ $danhGia->MaDG }}</h5>
                                    </div>
                                    <div class="badge-rating">
                                        {{ $danhGia->Danhgiasao }}/5
                                        <i class="fas fa-star ms-1"></i>
                                    </div>
                                </div>
                                
                                <p class="mb-1 fw-bold"><i class="far fa-calendar-alt text-secondary me-1"></i> Ngày đánh giá:</p>
                                <p>{{ \Carbon\Carbon::parse($danhGia->Ngaydanhgia)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="review-info mb-3">
                                <p class="mb-1 fw-bold"><i class="fas fa-user text-secondary me-1"></i> Người dùng:</p>
                                <p>
                                    {{ $danhGia->user->Hoten ?? 'Không có thông tin' }} 
                                    <span class="text-muted">({{ $danhGia->Manguoidung }})</span>
                                </p>
                                
                                <p class="mb-1 fw-bold"><i class="fas fa-receipt text-secondary me-1"></i> Hóa đơn:</p>
                                <p>
                                    <span class="badge bg-primary">{{ $danhGia->MaHD }}</span>
                                </p>
                                
                                <p class="mb-1 fw-bold"><i class="fas fa-door-open text-secondary me-1"></i> Phòng:</p>
                                <p>{{ $danhGia->hoaDon->phong->Tenphong ?? 'Không có thông tin' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="mb-1 fw-bold"><i class="fas fa-comment text-secondary me-1"></i> Nhận xét:</p>
                        <div class="review-text">
                            {{ $danhGia->Nhanxet ?? 'Không có nhận xét' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.danhgia.destroy', $danhGia->MaDG) }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-danger-gradient px-4 py-2">
                        <i class="fas fa-trash me-1"></i> Xác nhận xóa
                    </button>
                    <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Hủy và quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection