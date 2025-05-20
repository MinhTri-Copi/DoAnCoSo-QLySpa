@extends('customer.layouts.app')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Chi tiết đánh giá</h1>
        <a href="{{ route('customer.danhgia.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 pb-2 border-bottom">Thông tin đánh giá</h5>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Dịch vụ</h6>
                        @if($review->dichVu)
                            <p>{{ $review->dichVu->Tendichvu }}</p>
                        @else
                            <p class="text-muted fst-italic">Không có thông tin</p>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Điểm đánh giá</h6>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->Diemdanhgia)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="ms-2">{{ $review->Diemdanhgia }}/5 sao</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Nội dung đánh giá</h6>
                        <p>{{ $review->Noidungdanhgia }}</p>
                    </div>
                    
                    @if($photos && count($photos) > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold">Hình ảnh đính kèm</h6>
                            <div class="row g-2">
                                @foreach($photos as $photo)
                                    <div class="col-4">
                                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail" alt="Hình ảnh đánh giá">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div>
                        <h6 class="fw-bold">Thời gian đánh giá</h6>
                        <p>{{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
            
            @if($review->PhanHoi)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3 pb-2 border-bottom">Phản hồi từ Rosa Spa</h5>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Nhân viên Rosa Spa</h6>
                                <small class="text-muted">{{ $review->NgayPhanHoi ? \Carbon\Carbon::parse($review->NgayPhanHoi)->format('d/m/Y H:i:s') : '' }}</small>
                            </div>
                        </div>
                        <div class="ps-5">
                            <p>{{ $review->PhanHoi }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(!$review->PhanHoi)
                <div class="d-flex mt-4">
                    <a href="{{ route('customer.danhgia.edit', $review->MaDG) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa đánh giá
                    </a>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Thông tin hóa đơn</h5>
                    @if($review->hoaDon)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Mã hóa đơn:</span>
                                <strong>{{ $review->hoaDon->MaHD }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ngày thanh toán:</span>
                                <strong>{{ \Carbon\Carbon::parse($review->hoaDon->Ngaythanhtoan)->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Tổng tiền:</span>
                                <strong>{{ number_format($review->hoaDon->Tongtien, 0, ',', '.') }} VNĐ</strong>
                            </div>
                        </div>
                        <a href="{{ route('customer.hoadon.show', $review->hoaDon->MaHD) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-receipt me-2"></i>Xem chi tiết hóa đơn
                        </a>
                    @else
                        <p class="text-muted">Không có thông tin hóa đơn</p>
                    @endif
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Lưu ý</h5>
                    <ul class="ps-3 mb-0">
                        <li class="mb-2">Đánh giá của bạn sẽ giúp cải thiện chất lượng dịch vụ.</li>
                        <li class="mb-2">Bạn chỉ có thể chỉnh sửa đánh giá khi chưa nhận được phản hồi.</li>
                        <li>Cảm ơn bạn đã dành thời gian đánh giá dịch vụ của chúng tôi!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 