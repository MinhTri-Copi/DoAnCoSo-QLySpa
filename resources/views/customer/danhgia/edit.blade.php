@extends('customer.layouts.app')

@section('title', 'Chỉnh sửa đánh giá')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Chỉnh sửa đánh giá</h1>
                <a href="{{ route('customer.danhgia.show', $review->MaDG) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('customer.danhgia.update', $review->MaDG) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h6 class="fw-bold">Dịch vụ</h6>
                            <p>{{ $review->getTenDichVu() }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="Diemdanhgia" class="form-label">Điểm đánh giá <span class="text-danger">*</span></label>
                            <div class="star-rating mb-2">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="Diemdanhgia" id="star{{ $i }}" value="{{ $i }}" {{ old('Diemdanhgia', $review->Danhgiasao) == $i ? 'checked' : '' }} required>
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
                            <textarea class="form-control @error('Noidungdanhgia') is-invalid @enderror" id="Noidungdanhgia" name="Noidungdanhgia" rows="5" required>{{ old('Noidungdanhgia', $review->Nhanxet) }}</textarea>
                            @error('Noidungdanhgia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.danhgia.show', $review->MaDG) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
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