@extends('customer.layouts.app')

@section('title', 'Chỉnh sửa phiếu hỗ trợ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Chỉnh sửa phiếu hỗ trợ</h1>
                <div>
                    <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaPhieu) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('customer.phieuhotro.update', $phieuHoTro->MaPhieu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="Tieude" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Tieude') is-invalid @enderror" id="Tieude" name="Tieude" value="{{ old('Tieude', $phieuHoTro->Tieude) }}" required>
                            @error('Tieude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="MaPTHT" class="form-label">Phương thức hỗ trợ <span class="text-danger">*</span></label>
                            <select class="form-select @error('MaPTHT') is-invalid @enderror" id="MaPTHT" name="MaPTHT" required>
                                <option value="">-- Chọn phương thức hỗ trợ --</option>
                                @foreach($phuongThucHoTro as $pt)
                                    <option value="{{ $pt->MaPTHT }}" {{ old('MaPTHT', $phieuHoTro->MaPTHT) == $pt->MaPTHT ? 'selected' : '' }}>{{ $pt->Ten }}</option>
                                @endforeach
                            </select>
                            @error('MaPTHT')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="Noidung" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('Noidung') is-invalid @enderror" id="Noidung" name="Noidung" rows="6" required>{{ old('Noidung', $phieuHoTro->Noidung) }}</textarea>
                            @error('Noidung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaPhieu) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Lưu ý</h5>
                    <ul class="mb-0">
                        <li>Chỉ có thể chỉnh sửa phiếu hỗ trợ khi đang ở trạng thái "Đang xử lý".</li>
                        <li>Sau khi phiếu hỗ trợ đã được tiếp nhận xử lý, bạn không thể chỉnh sửa nội dung.</li>
                        <li>Nếu muốn bổ sung thông tin cho phiếu đã được tiếp nhận, vui lòng sử dụng chức năng "Gửi thêm phản hồi".</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 