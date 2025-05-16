@extends('backend.layouts.app')

@section('title', 'Chi Tiết Đánh Giá')

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
    .card-header-pink {
        background-color: #ff6b95;
        color: white;
    }
    .badge-rating {
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        background-color: #ff6b95;
        color: white;
    }
    .review-text {
        background-color: #f9f9f9;
        border-left: 4px solid #ff6b95;
        padding: 15px;
        border-radius: 0 8px 8px 0;
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
    .profile-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: linear-gradient(45deg, #ff6b95, #ff8d6b);
        color: white;
    }
    .reply-box {
        background-color: #f0f8ff;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #03a9f4;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Chi Tiết Đánh Giá</h1>
        <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card mb-4 custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-star me-1 text-warning"></i>
                <span class="fw-bold">Chi tiết đánh giá #{{ $danhGia->MaDG }}</span>
            </div>
            <div class="badge-rating">
                {{ $danhGia->Danhgiasao }}/5
                <i class="fas fa-star ms-1"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 custom-card">
                        <div class="card-header">
                            <i class="fas fa-user me-1 text-primary"></i> Thông Tin Khách Hàng
                        </div>
                        <div class="card-body">
                            @if ($danhGia->user)
                                <div class="mb-3 d-flex align-items-center">
                                    <div class="profile-circle me-3">
                                        {{ substr($danhGia->user->Hoten ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $danhGia->user->Hoten ?? 'Không có thông tin' }}</h5>
                                        <p class="text-muted mb-0">Mã KH: {{ $danhGia->user->Manguoidung }}</p>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-phone-alt me-2 text-secondary"></i>Số điện thoại:</strong> 
                                    {{ $danhGia->user->Sodienthoai ?? 'Không có thông tin' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-envelope me-2 text-secondary"></i>Email:</strong> 
                                    {{ $danhGia->user->Email ?? 'Không có thông tin' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt me-2 text-secondary"></i>Địa chỉ:</strong> 
                                    {{ $danhGia->user->Diachi ?? 'Không có thông tin' }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <p>Không có thông tin người dùng</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4 custom-card">
                        <div class="card-header">
                            <i class="fas fa-receipt me-1 text-success"></i> Thông Tin Hóa Đơn
                        </div>
                        <div class="card-body">
                            @if ($danhGia->hoaDon)
                                <div class="mb-2">
                                    <strong>Mã hóa đơn:</strong> 
                                    <span class="badge bg-primary">{{ $danhGia->hoaDon->MaHD }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Ngày thanh toán:</strong> 
                                    <i class="far fa-calendar-alt me-1 text-secondary"></i>
                                    {{ \Carbon\Carbon::parse($danhGia->hoaDon->Ngaythanhtoan)->format('d/m/Y H:i') }}
                                </div>
                                <div class="mb-2">
                                    <strong>Tổng tiền:</strong> 
                                    <span class="fw-bold text-success">
                                        {{ number_format($danhGia->hoaDon->Tongtien, 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Phòng:</strong> 
                                    <i class="fas fa-door-open me-1 text-secondary"></i>
                                    {{ $danhGia->hoaDon->phong->Tenphong ?? 'Không có thông tin' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Phương thức thanh toán:</strong> 
                                    <i class="fas fa-credit-card me-1 text-secondary"></i>
                                    {{ $danhGia->hoaDon->phuongThuc->Tenpt ?? 'Không có thông tin' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Trạng thái:</strong> 
                                    <span class="badge bg-success">
                                        {{ $danhGia->hoaDon->trangThai->Tentrangthai ?? 'Không có thông tin' }}
                                    </span>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                    <p>Không có thông tin hóa đơn</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 custom-card">
                <div class="card-header">
                    <i class="fas fa-comment-dots me-1 text-pink-500"></i> Nội Dung Đánh Giá
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="mb-0 me-3">Đánh giá:</h5>
                            <div class="d-flex align-items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $danhGia->Danhgiasao)
                                        <i class="fas fa-star text-warning fa-lg me-1"></i>
                                    @else
                                        <i class="far fa-star text-warning fa-lg me-1"></i>
                                    @endif
                                @endfor
                                <span class="ms-2 badge-rating">{{ $danhGia->Danhgiasao }}/5</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Thời gian đánh giá:</strong></p>
                            <div class="d-flex align-items-center">
                                <i class="far fa-calendar-alt me-2 text-secondary"></i>
                                <span>{{ \Carbon\Carbon::parse($danhGia->Ngaydanhgia)->format('d/m/Y') }}</span>
                                <i class="far fa-clock ms-3 me-2 text-secondary"></i>
                                <span>{{ \Carbon\Carbon::parse($danhGia->Ngaydanhgia)->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Nhận xét:</strong></p>
                            <div class="review-text">
                                {{ $danhGia->Nhanxet ?? 'Không có nhận xét' }}
                            </div>
                        </div>
                    </div>

                    <!-- Phản hồi đánh giá -->
                    <div class="mt-4">
                        <h5 class="border-bottom pb-2">Phản Hồi Đánh Giá</h5>
                        @if (isset($danhGia->PhanHoi) && $danhGia->PhanHoi)
                            <div class="reply-box">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <div class="profile-circle" style="width: 40px; height: 40px; font-size: 18px;">
                                            A
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Admin</div>
                                        <div class="mb-2">{{ $danhGia->PhanHoi }}</div>
                                        <div class="text-muted small">
                                            <i class="far fa-clock me-1"></i>
                                            {{ isset($danhGia->NgayPhanHoi) ? \Carbon\Carbon::parse($danhGia->NgayPhanHoi)->format('d/m/Y H:i') : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('admin.danhgia.reply', $danhGia->MaDG) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="reply" rows="3" placeholder="Nhập phản hồi của bạn..."></textarea>
                                    @error('reply')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-pink">
                                    <i class="fas fa-paper-plane me-1"></i> Gửi phản hồi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.danhgia.edit', $danhGia->MaDG) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.danhgia.confirmDestroy', $danhGia->MaDG) }}" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Xóa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection