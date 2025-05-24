@extends('customer.layouts.app')

@section('title', 'Xác nhận xoá phiếu hỗ trợ')

@section('content')
<style>
    .rosa-card {
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px 0 rgba(251,113,133,0.10);
        border: none;
        background: #fff;
        margin-bottom: 2rem;
        padding: 2.5rem 2rem;
        text-align: center;
    }
    .rosa-icon-warning {
        color: #fb7185;
        font-size: 3.5rem;
        margin-bottom: 1rem;
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
    .rosa-btn-danger {
        background: linear-gradient(90deg, #fb7185 0%, #f472b6 100%);
        color: #fff;
        border: none;
    }
    .rosa-btn-danger:hover { background: #f472b6; color: #fff; }
    .rosa-btn-outline {
        border: 2px solid #fb7185;
        color: #fb7185;
        background: #fff;
    }
    .rosa-btn-outline:hover {
        background: #fb7185;
        color: #fff;
    }
    .rosa-label {
        color: #fb7185;
        font-weight: 600;
        font-size: 1.1rem;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="rosa-card">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle rosa-icon-warning"></i>
                </div>
                <h2 class="mb-3" style="color:#fb7185;font-weight:700;">Xác nhận xoá phiếu hỗ trợ</h2>
                <div class="mb-3">
                    <span class="rosa-label">Bạn có chắc chắn muốn xoá phiếu hỗ trợ <b>#{{ $phieuHoTro->MaphieuHT }}</b> không?</span>
                </div>
                <div class="mb-4">
                    <div class="mb-2"><b>Phương thức:</b> {{ $phieuHoTro->ptHoTro->TenPT ?? 'Không xác định' }}</div>
                    <div class="mb-2"><b>Trạng thái:</b> {{ $phieuHoTro->trangThai->Tentrangthai }}</div>
                    <div class="mb-2"><b>Nội dung:</b> <span style="color:#fb7185;">{{ $phieuHoTro->Noidungyeucau }}</span></div>
                </div>
                <form action="{{ route('customer.phieuhotro.destroy', $phieuHoTro->MaphieuHT) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rosa-btn rosa-btn-danger me-2">
                        <i class="fas fa-trash"></i> Xác nhận xoá
                    </button>
                </form>
                <a href="{{ route('customer.phieuhotro.show', $phieuHoTro->MaphieuHT) }}" class="rosa-btn rosa-btn-outline">
                    <i class="fas fa-arrow-left"></i> Huỷ, quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 