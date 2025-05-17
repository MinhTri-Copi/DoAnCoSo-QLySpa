@extends('backend.layouts.app')

@section('title', 'Thêm Đánh Giá Mới')

@section('styles')
<style>
    body {
        background-color: #ffebf3 !important;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #e83e8c, #fd7e97);
        color: white;
        border-radius: 10px;
        padding: 20px 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.3);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-in-out;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .welcome-banner p {
        font-size: 1rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    .shine-line {
        position: absolute;
        top: 0;
        left: -150%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 3s infinite;
        transform: skewX(-25deg);
    }
    
    @keyframes shine {
        0% { left: -150%; }
        100% { left: 150%; }
    }

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
        border-bottom: 2px solid #e83e8c;
    }
    .btn-pink {
        background-color: #e83e8c;
        border-color: #e83e8c;
        color: white;
        transition: all 0.3s;
    }
    .btn-pink:hover {
        background-color: #d33077;
        border-color: #d33077;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(232, 62, 140, 0.3);
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    .form-control:focus, .form-select:focus {
        border-color: #e83e8c;
        box-shadow: 0 0 0 0.25rem rgba(232, 62, 140, 0.25);
    }
    .star-rating .form-check-inline {
        margin-right: 1rem;
    }
    .star-rating .form-check-input:checked {
        background-color: #e83e8c;
        border-color: #e83e8c;
    }
    .bg-pink-500 {
        background-color: #e83e8c !important;
    }
    
    .btn-back {
        background-color: white;
        color: #e83e8c;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .btn-back:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h1><i class="fas fa-spa"></i> Thêm Đánh Giá Mới</h1>
        <p>Ghi nhận đánh giá và phản hồi từ khách hàng</p>
        <div class="shine-line"></div>
        
        <div class="position-absolute" style="top: 20px; right: 20px;">
            <a href="{{ route('admin.danhgia.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
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