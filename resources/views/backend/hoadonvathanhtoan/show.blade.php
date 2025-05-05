@extends('backend.layouts.app')

@section('title', 'Chi tiết hóa đơn')

@section('content')
<div class="container">
    <h1>Chi tiết hóa đơn</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Mã hóa đơn:</strong> {{ $hoaDon->MaHD }}</p>
            <p><strong>Ngày thanh toán:</strong> {{ $hoaDon->Ngaythanhtoan }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</p>
            <p><strong>Mã đặt lịch:</strong> {{ $hoaDon->datLich->MaDL ?? 'N/A' }}</p>
            <p><strong>Người dùng:</strong> {{ $hoaDon->user->name ?? 'N/A' }}</p>
            <p><strong>Phòng:</strong> {{ $hoaDon->phong->Tenphong ?? 'N/A' }}</p>
            <p><strong>Phương thức:</strong> {{ $hoaDon->phuongThuc->TenPT ?? 'N/A' }}</p>
            <p><strong>Trạng thái:</strong> {{ $hoaDon->trangThai->Tentrangthai ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection