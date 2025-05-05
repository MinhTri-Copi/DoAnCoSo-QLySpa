@extends('backend.layouts.app')

@section('title', 'Quản lý hóa đơn và thanh toán')

@section('content')
<div class="container">
    <h1>Quản lý hóa đơn và thanh toán</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.hoadonvathanhtoan.create') }}" class="btn btn-primary mb-3">Thêm hóa đơn mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã hóa đơn</th>
                <th>Ngày thanh toán</th>
                <th>Tổng tiền</th>
                <th>Mã đặt lịch</th>
                <th>Người dùng</th>
                <th>Phòng</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hoaDons as $hoaDon)
                <tr>
                    <td>{{ $hoaDon->MaHD }}</td>
                    <td>{{ $hoaDon->Ngaythanhtoan }}</td>
                    <td>{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $hoaDon->datLich->MaDL ?? 'N/A' }}</td>
                    <td>{{ $hoaDon->user->name ?? 'N/A' }}</td>
                    <td>{{ $hoaDon->phong->Tenphong ?? 'N/A' }}</td>
                    <td>{{ $hoaDon->phuongThuc->TenPT ?? 'N/A' }}</td>
                    <td>{{ $hoaDon->trangThai->Tentrangthai ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.hoadonvathanhtoan.show', $hoaDon->MaHD) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.hoadonvathanhtoan.edit', $hoaDon->MaHD) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ route('admin.hoadonvathanhtoan.confirm-destroy', $hoaDon->MaHD) }}" class="btn btn-danger btn-sm">Xóa</a>
                        <a href="{{ route('admin.hoadonvathanhtoan.danhgia.create', $hoaDon->MaHD) }}" class="btn btn-success btn-sm">Đánh giá</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection