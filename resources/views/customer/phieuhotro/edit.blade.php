@extends('customer.layouts.app')

@section('title', 'Chỉnh sửa phiếu hỗ trợ')

@section('content')
<style>
    .rosa-card {
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px 0 rgba(251,113,133,0.10);
        border: none;
        background: #fff;
        margin-bottom: 2rem;
    }
    .rosa-label {
        color: #fb7185;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .rosa-input, .rosa-select, .rosa-textarea {
        border-radius: 1rem;
        border: 2px solid #f9a8d4;
        font-size: 1.1rem;
        padding: 0.75em 1.2em;
        background: #fff;
        transition: border 0.2s;
    }
    .rosa-input:focus, .rosa-select:focus, .rosa-textarea:focus {
        border-color: #fb7185;
        box-shadow: 0 0 0 2px #fb718533;
    }
    .rosa-btn {
        border-radius: 2rem;
        font-weight: 600;
        padding: 0.5em 1.5em;
        font-size: 1.1rem;
        transition: background 0.2s, color 0.2s;
        min-width: 140px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
    }
    .rosa-btn-primary {
        background: linear-gradient(90deg, #fb7185 0%, #f472b6 100%);
        color: #fff;
        border: none;
    }
    .rosa-btn-primary:hover { background: #f472b6; color: #fff; }
    .rosa-btn-outline {
        border: 2px solid #fb7185;
        color: #fb7185;
        background: #fff;
    }
    .rosa-btn-outline:hover {
        background: #fb7185;
        color: #fff;
    }
    .rosa-icon {
        font-size: 1.5rem;
        vertical-align: middle;
    }
    .rosa-header-flex {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem 0 1rem 0;
    }
    .rosa-form-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rosa-card">
                <div class="rosa-header-flex">
                    <h3 class="mb-0" style="color:#fb7185;font-weight:700;"><i class="fas fa-edit rosa-icon me-2"></i>Chỉnh sửa phiếu hỗ trợ #{{ $phieuHoTro->MaphieuHT }}</h3>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('customer.phieuhotro.update', $phieuHoTro->MaphieuHT) }}" method="POST" class="rosa-form-row">
                        @csrf
                        @method('PUT')
                        <div class="w-100">
                            <label for="MaPTHT" class="rosa-label">Phương thức hỗ trợ <span class="text-danger">*</span></label>
                            <select class="rosa-select w-100 @error('MaPTHT') is-invalid @enderror" id="MaPTHT" name="MaPTHT" required>
                                <option value="">-- Chọn phương thức hỗ trợ --</option>
                                @foreach($phuongThucHoTro as $pt)
                                    <option value="{{ $pt->MaPTHT }}" {{ old('MaPTHT', $phieuHoTro->MaPTHT) == $pt->MaPTHT ? 'selected' : '' }}>{{ $pt->TenPT }}</option>
                                @endforeach
                            </select>
                            @error('MaPTHT')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-100">
                            <label for="Noidungyeucau" class="rosa-label">Nội dung yêu cầu <span class="text-danger">*</span></label>
                            <textarea class="rosa-textarea w-100 @error('Noidungyeucau') is-invalid @enderror" id="Noidungyeucau" name="Noidungyeucau" rows="6" required>{{ old('Noidungyeucau', $phieuHoTro->Noidungyeucau) }}</textarea>
                            @error('Noidungyeucau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaphieuHT) }}" class="rosa-btn rosa-btn-outline">
                                <i class="fas fa-arrow-left rosa-icon me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="rosa-btn rosa-btn-primary px-4 py-2">
                                <i class="fas fa-save rosa-icon me-2"></i>Lưu thay đổi
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