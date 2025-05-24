@extends('customer.layouts.app')

@section('title', 'Chi tiết phiếu hỗ trợ')

@section('content')
<style>
    .rosa-card {
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px 0 rgba(251,113,133,0.10);
        border: none;
        background: #fff;
        margin-bottom: 2rem;
    }
    .rosa-badge {
        font-size: 1rem;
        padding: 0.5em 1.2em;
        border-radius: 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
    }
    .rosa-badge-warning { background: linear-gradient(90deg, #fb7185 0%, #f472b6 100%); color: #fff; }
    .rosa-badge-success { background: #f9a8d4; color: #047857; }
    .rosa-badge-danger { background: #fb7185; color: #fff; }
    .rosa-badge-secondary { background: #f3f4f6; color: #6b7280; }
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
    .rosa-label {
        color: #fb7185;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .rosa-content-box {
        background: #fff0f6;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1.5px solid #f9a8d4;
        min-height: 120px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .rosa-header-flex {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem 0 1rem 0;
    }
    .rosa-action-row {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rosa-card">
                <div class="rosa-header-flex">
                    <h2 class="mb-2" style="color:#fb7185;font-weight:700;">Phiếu hỗ trợ #{{ $phieuHoTro->MaphieuHT }}</h2>
                    <div class="mb-2">
                        @if($phieuHoTro->trangThai->Tentrangthai == 'Đang xử lý')
                            <span class="rosa-badge rosa-badge-warning"><i class="fas fa-spinner rosa-icon"></i>Đang xử lý</span>
                        @elseif($phieuHoTro->trangThai->Tentrangthai == 'Đã hoàn thành')
                            <span class="rosa-badge rosa-badge-success"><i class="fas fa-check-circle rosa-icon"></i>Đã hoàn thành</span>
                        @elseif($phieuHoTro->trangThai->Tentrangthai == 'Đã hủy')
                            <span class="rosa-badge rosa-badge-danger"><i class="fas fa-times-circle rosa-icon"></i>Đã hủy</span>
                        @else
                            <span class="rosa-badge rosa-badge-secondary">{{ $phieuHoTro->trangThai->Tentrangthai }}</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <span class="rosa-label">Phương thức hỗ trợ:</span>
                        <span style="font-weight:600; color:#fb7185;">{{ $phieuHoTro->ptHoTro->TenPT ?? 'Không xác định' }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2 justify-content-center">
                            <i class="fas fa-align-left rosa-icon text-pink-400 me-2"></i>
                            <span class="rosa-label">Nội dung yêu cầu</span>
                        </div>
                        <div class="rosa-content-box">
                            {{ $phieuHoTro->Noidungyeucau }}
                        </div>
                    </div>
                    <div class="rosa-action-row">
                        @if($phieuHoTro->trangThai->Tentrangthai == 'Đang xử lý')
                            <a href="{{ route('customer.phieuhotro.edit', $phieuHoTro->MaphieuHT) }}" class="rosa-btn rosa-btn-outline">
                                <i class="fas fa-edit rosa-icon"></i>Chỉnh sửa
                            </a>
                            <a href="{{ route('customer.phieuhotro.confirm-destroy', $phieuHoTro->MaphieuHT) }}" class="rosa-btn rosa-btn-outline">
                                <i class="fas fa-trash rosa-icon"></i>Xoá phiếu
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <a href="{{ route('customer.phieuhotro.index') }}" class="rosa-btn rosa-btn-primary">
                    <i class="fas fa-arrow-left rosa-icon me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 