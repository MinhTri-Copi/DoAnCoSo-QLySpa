@extends('backend.layouts.app')

@section('title', 'Thêm Đánh Giá Mới')

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
    .btn-pink {
        background-color: #ff6b95;
        border-color: #ff6b95;
        color: white;
    }
    .btn-pink:hover {
        background-color: #e84a78;
        border-color: #e84a78;
        color: white;
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    .form-control:focus, .form-select:focus {
        border-color: #ff6b95;
        box-shadow: 0 0 0 0.25rem rgba(255, 107, 149, 0.25);
    }
    .star-rating .form-check-inline {
        margin-right: 1rem;
    }
    .star-rating .form-check-input:checked {
        background-color: #ff6b95;
        border-color: #ff6b95;
    }
    .bg-pink-500 {
        background-color: #ff6b95 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="card bg-pink-500 text-white mb-4 custom-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-2">Thêm Đánh Giá Mới</h1>
                    <p class="mb-0">
                        <i class="fas fa-star me-1"></i> Ghi nhận đánh giá và phản hồi từ khách hàng
                    </p>
                </div>
                <a href="{{ route('admin.danhgia.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-4 custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-plus-circle me-1 text-pink-500"></i>
                <span class="fw-bold">Thông tin đánh giá mới</span>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.danhgia.store') }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Manguoidung" class="form-label">
                                <i class="fas fa-user me-1 text-secondary"></i> Người dùng
                            </label>
                            <select class="form-select @error('Manguoidung') is-invalid @enderror" id="Manguoidung" name="Manguoidung">
                                <option value="">Chọn người dùng</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                                        {{ $user->Hoten }} ({{ $user->Manguoidung }})
                                    </option>
                                @endforeach
                            </select>
                            @error('Manguoidung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="MaHD" class="form-label">
                                <i class="fas fa-receipt me-1 text-secondary"></i> Hóa đơn
                            </label>
                            <select class="form-select @error('MaHD') is-invalid @enderror" id="MaHD" name="MaHD">
                                <option value="">Chọn hóa đơn</option>
                                @foreach ($hoaDons as $hoaDon)
                                    <option value="{{ $hoaDon->MaHD }}" {{ old('MaHD') == $hoaDon->MaHD ? 'selected' : '' }}>
                                        Hóa đơn #{{ $hoaDon->MaHD }} - 
                                        @if ($hoaDon->phong)
                                            Phòng: {{ $hoaDon->phong->Tenphong }} - 
                                        @endif
                                        {{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ
                                    </option>
                                @endforeach
                            </select>
                            @error('MaHD')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 p-3 bg-light rounded">
                    <label class="form-label">
                        <i class="fas fa-star me-1 text-warning"></i> Đánh giá sao
                    </label>
                    <div class="star-rating">
                        <div class="d-flex">
                            @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Danhgiasao" id="star{{ $i }}" value="{{ $i }}" {{ old('Danhgiasao', 5) == $i ? 'checked' : '' }}>
                                    <label class="form-check-label" for="star{{ $i }}">
                                        {{ $i }} <i class="fas fa-star text-warning"></i>
                                    </label>
                                </div>
                            @endfor
                        </div>
                        @error('Danhgiasao')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="Nhanxet" class="form-label">
                        <i class="fas fa-comment me-1 text-secondary"></i> Nhận xét
                    </label>
                    <textarea class="form-control @error('Nhanxet') is-invalid @enderror" id="Nhanxet" name="Nhanxet" rows="4" placeholder="Nhập nhận xét của khách hàng...">{{ old('Nhanxet') }}</textarea>
                    @error('Nhanxet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-pink">
                        <i class="fas fa-plus me-1"></i> Thêm đánh giá
                    </button>
                    <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Khi người dùng được chọn, cập nhật danh sách hóa đơn
        $('#Manguoidung').change(function() {
            const userId = $(this).val();
            const invoiceSelect = $('#MaHD');
            
            // Xóa tất cả các option hiện tại ngoại trừ option mặc định
            invoiceSelect.find('option:not(:first)').remove();
            
            if (userId) {
                // Hiển thị thông báo đang tải
                invoiceSelect.append('<option value="" disabled>Đang tải hóa đơn...</option>');
                
                // Gọi API để lấy hóa đơn của người dùng đã chọn
                $.ajax({
                    url: '{{ url("admin/danhgia/get-user-invoices") }}/' + userId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Xóa thông báo đang tải
                        invoiceSelect.find('option:disabled').remove();
                        
                        if (response.success && response.hoaDons.length > 0) {
                            // Thêm các option mới
                            $.each(response.hoaDons, function(index, hoaDon) {
                                let roomInfo = '';
                                if (hoaDon.phong) {
                                    roomInfo = ' - Phòng: ' + hoaDon.phong.Tenphong;
                                }
                                
                                const formattedTotal = new Intl.NumberFormat('vi-VN').format(hoaDon.Tongtien);
                                
                                invoiceSelect.append(
                                    $('<option></option>')
                                        .attr('value', hoaDon.MaHD)
                                        .text('Hóa đơn #' + hoaDon.MaHD + roomInfo + ' - ' + formattedTotal + ' VNĐ')
                                );
                            });
                        } else {
                            // Không có hóa đơn
                            invoiceSelect.append('<option value="" disabled>Không có hóa đơn nào</option>');
                        }
                    },
                    error: function() {
                        // Xóa thông báo đang tải
                        invoiceSelect.find('option:disabled').remove();
                        
                        // Hiển thị thông báo lỗi
                        invoiceSelect.append('<option value="" disabled>Lỗi khi tải hóa đơn</option>');
                    }
                });
            }
        });
    });
</script>
@endsection 