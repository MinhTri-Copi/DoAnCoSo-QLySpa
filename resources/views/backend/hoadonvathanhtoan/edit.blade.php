@extends('backend.layouts.app')

@section('title', 'Sửa hóa đơn')

@section('content')
<div class="container">
    <h1>Sửa hóa đơn</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.hoadonvathanhtoan.update', $hoaDon->MaHD) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MaHD" class="form-label">Mã hóa đơn</label>
            <input type="text" class="form-control" id="MaHD" name="MaHD" value="{{ $hoaDon->MaHD }}" readonly>
        </div>
        <div class="mb-3">
            <label for="Ngaythanhtoan" class="form-label">Ngày thanh toán</label>
            <input type="date" class="form-control" id="Ngaythanhtoan" name="Ngaythanhtoan" value="{{ old('Ngaythanhtoan', $hoaDon->Ngaythanhtoan) }}">
        </div>
        <div class="mb-3">
            <label for="Tongtien" class="form-label">Tổng tiền (VNĐ)</label>
            <input type="number" class="form-control" id="Tongtien" name="Tongtien" value="{{ old('Tongtien', $hoaDon->Tongtien) }}">
        </div>
        <div class="mb-3">
            <label for="MaDL" class="form-label">Mã đặt lịch</label>
            <select class="form-control" id="MaDL" name="MaDL">
                @foreach ($datLichs as $datLich)
                    <option value="{{ $datLich->MaDL }}" {{ old('MaDL', $hoaDon->MaDL) == $datLich->MaDL ? 'selected' : '' }}>
                        {{ $datLich->MaDL }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Manguoidung" class="form-label">Người dùng</label>
            <select class="form-control" id="Manguoidung" name="Manguoidung">
                @foreach ($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung', $hoaDon->Manguoidung) == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Maphong" class="form-label">Phòng</label>
            <select class="form-control" id="Maphong" name="Maphong">
                @foreach ($phongs as $phong)
                    <option value="{{ $phong->Maphong }}" {{ old('Maphong', $hoaDon->Maphong) == $phong->Maphong ? 'selected' : '' }}>
                        {{ $phong->Tenphong }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="MaPT" class="form-label">Phương thức</label>
            <select class="form-control" id="MaPT" name="MaPT">
                @foreach ($phuongThucs as $phuongThuc)
                    <option value="{{ $phuongThuc->MaPT }}" {{ old('MaPT', $hoaDon->MaPT) == $phuongThuc->MaPT ? 'selected' : '' }}>
                        {{ $phuongThuc->TenPT }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Matrangthai" class="form-label">Trạng thái</label>
            <select class="form-control" id="Matrangthai" name="Matrangthai">
                @foreach ($trangThais as $trangThai)
                    <option value="{{ $trangThai->Matrangthai }}" {{ old('Matrangthai', $hoaDon->Matrangthai) == $trangThai->Matrangthai ? 'selected' : '' }}>
                        {{ $trangThai->Tentrangthai }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection