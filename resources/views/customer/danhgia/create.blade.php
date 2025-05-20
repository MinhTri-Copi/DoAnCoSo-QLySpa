@extends('customer.layouts.app')

@section('title', 'Thêm đánh giá mới')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Đánh giá dịch vụ</h1>
                <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Thông tin dịch vụ</h5>
                    
                    <div class="d-flex mb-4">
                        @if($hoaDon->datLich->dichVu->Hinhanh)
                            <img src="{{ route('storage.image', ['path' => 'services/' . $hoaDon->datLich->dichVu->Hinhanh]) }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $hoaDon->datLich->dichVu->Tendichvu }}">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-spa fa-2x text-muted"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h5 class="mb-1">{{ $hoaDon->datLich->dichVu->Tendichvu }}</h5>
                            <p class="text-muted mb-1">Sử dụng ngày: {{ \Carbon\Carbon::parse($hoaDon->datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</p>
                            <p class="text-primary mb-0">{{ number_format($hoaDon->datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Đánh giá của bạn</h5>
                    
                    <form action="{{ route('customer.danhgia.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{ $hoaDon->MaHD }}">
                        
                        <div class="mb-4">
                            <label for="Diemdanhgia" class="form-label">Điểm đánh giá <span class="text-danger">*</span></label>
                            <div class="star-rating mb-2">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="Diemdanhgia" id="star{{ $i }}" value="{{ $i }}" {{ old('Diemdanhgia') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }} required>
                                        <label class="form-check-label" for="star{{ $i }}">
                                            @for($j = 1; $j <= 5; $j++)
                                                @if($j <= $i)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2">{{ $i }} sao</span>
                                        </label>
                                    </div>
                                    <br>
                                @endfor
                            </div>
                            @error('Diemdanhgia')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="Noidungdanhgia" class="form-label">Nội dung đánh giá <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('Noidungdanhgia') is-invalid @enderror" id="Noidungdanhgia" name="Noidungdanhgia" rows="5" placeholder="Chia sẻ trải nghiệm của bạn về dịch vụ..." required>{{ old('Noidungdanhgia') }}</textarea>
                            @error('Noidungdanhgia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="photos" class="form-label">Hình ảnh (tùy chọn)</label>
                            <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" multiple accept="image/*">
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Có thể chọn nhiều hình ảnh. Kích thước tối đa: 2MB/hình.</small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .star-rating .form-check {
        margin-bottom: 10px;
    }
    .star-rating .form-check-input {
        margin-top: 0.3rem;
    }
</style>
@endsection 