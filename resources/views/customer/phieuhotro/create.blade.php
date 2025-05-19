@extends('customer.layouts.app')

@section('title', 'Tạo phiếu hỗ trợ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Tạo phiếu hỗ trợ mới</h1>
                <a href="{{ route('customer.phieuhotro.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('customer.phieuhotro.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="Tieude" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Tieude') is-invalid @enderror" id="Tieude" name="Tieude" value="{{ old('Tieude') }}" required>
                            @error('Tieude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="MaPTHT" class="form-label">Phương thức hỗ trợ <span class="text-danger">*</span></label>
                            <select class="form-select @error('MaPTHT') is-invalid @enderror" id="MaPTHT" name="MaPTHT" required>
                                <option value="">-- Chọn phương thức hỗ trợ --</option>
                                @foreach($phuongThucHoTro as $pt)
                                    <option value="{{ $pt->MaPTHT }}" {{ old('MaPTHT') == $pt->MaPTHT ? 'selected' : '' }}>{{ $pt->Ten }}</option>
                                @endforeach
                            </select>
                            @error('MaPTHT')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="Noidung" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('Noidung') is-invalid @enderror" id="Noidung" name="Noidung" rows="6" required>{{ old('Noidung') }}</textarea>
                            @error('Noidung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Vui lòng mô tả chi tiết vấn đề của bạn để chúng tôi có thể hỗ trợ tốt nhất.</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-paper-plane me-2"></i>Gửi phiếu hỗ trợ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Lưu ý</h5>
                    <ul class="mb-0">
                        <li>Phiếu hỗ trợ của bạn sẽ được xử lý trong vòng 24 giờ làm việc.</li>
                        <li>Bạn có thể theo dõi trạng thái phiếu hỗ trợ trong mục "Phiếu hỗ trợ" trên trang cá nhân.</li>
                        <li>Đối với vấn đề cấp bách, vui lòng liên hệ trực tiếp với chúng tôi qua hotline: <strong>(028) 1234 5678</strong>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 