@extends('backend.layouts.app')

@section('title', 'Chi Tiết Phiếu Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi Tiết Phiếu Hỗ Trợ</h2>
    <div class="mb-3">
        <label class="form-label">Mã Phiếu Hỗ Trợ:</label>
        <p>{{ $phieuHoTro->MaphieuHT }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Nội Dung Yêu Cầu:</label>
        <p>{{ $phieuHoTro->Noidungyeucau }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Trạng Thái:</label>
        <p>{{ $phieuHoTro->trangThai->Tentrangthai ?? 'N/A' }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Người Dùng:</label>
        <p>{{ $phieuHoTro->user->Hoten ?? 'N/A' }}</p>
    </div>

    <a href="{{ route('admin.phieuhotro.index') }}" class="btn btn-secondary">Quay Lại</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Về Dashboard</a>
</div>
@endsection