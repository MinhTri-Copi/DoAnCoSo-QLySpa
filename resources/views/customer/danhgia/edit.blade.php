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
                            <p>{{ $review->dichVu ? $review->dichVu->Tendichvu : 'Không có thông tin' }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="Diemdanhgia" class="form-label">Điểm đánh giá <span class="text-danger">*</span></label>
                            <div class="star-rating mb-2">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="Diemdanhgia" id="star{{ $i }}" value="{{ $i }}" {{ old('Diemdanhgia', $review->Diemdanhgia) == $i ? 'checked' : '' }} required>
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
                            <textarea class="form-control @error('Noidungdanhgia') is-invalid @enderror" id="Noidungdanhgia" name="Noidungdanhgia" rows="5" required>{{ old('Noidungdanhgia', $review->Noidungdanhgia) }}</textarea>
                            @error('Noidungdanhgia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if(isset($photos) && count($photos) > 0)
                        <div class="mb-4">
                            <label class="form-label">Hình ảnh hiện tại</label>
                            <div class="row g-2">
                                @foreach($photos as $index => $photo)
                                <div class="col-4">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail" alt="Hình ảnh đánh giá">
                                        <div class="form-check position-absolute top-0 end-0 m-2">
                                            <input class="form-check-input" type="checkbox" name="delete_photos[]" id="delete_photo{{ $index }}" value="{{ $photo }}">
                                            <label class="form-check-label" for="delete_photo{{ $index }}">
                                                <span class="badge bg-danger"><i class="fas fa-trash"></i></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Đánh dấu vào hình ảnh nếu bạn muốn xóa.</small>
                        </div>
                        @endif
                        
                        <div class="mb-4">
                            <label for="photos" class="form-label">Thêm hình ảnh mới</label>
                            <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photos" name="photos[]" multiple accept="image/*">
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Có thể chọn nhiều hình ảnh. Kích thước tối đa: 2MB/hình.</small>
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