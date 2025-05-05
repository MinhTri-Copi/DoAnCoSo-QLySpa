@extends('backend.layouts.app')

@section('title', 'Sửa Đặt Lịch')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa Đặt Lịch</h2>
    <form method="POST" action="{{ route('admin.datlich.update', $datLich->MaDL) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="Manguoidung" class="form-label">Người Dùng</label>
            <select id="Manguoidung" class="form-control @error('Manguoidung') is-invalid @enderror" name="Manguoidung" required>
                <option value="">Chọn người dùng</option>
                @foreach ($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ $datLich->Manguoidung == $user->Manguoidung ? 'selected' : '' }}>{{ $user->Hoten }}</option>
                @endforeach
            </select>
            @error('Manguoidung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Thoigiandatlich" class="form-label">Thời Gian Đặt Lịch</label>
            <input id="Thoigiandatlich" type="datetime-local" class="form-control @error('Thoigiandatlich') is-invalid @enderror" name="Thoigiandatlich" value="{{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('Y-m-d\TH:i') }}" required>
            @error('Thoigiandatlich')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Trangthai_" class="form-label">Trạng Thái</label>
            <select id="Trangthai_" class="form-control @error('Trangthai_') is-invalid @enderror" name="Trangthai_" required>
                <option value="">Chọn trạng thái</option>
                <option value="Chờ xác nhận" {{ $datLich->Trangthai_ == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="Đã xác nhận" {{ $datLich->Trangthai_ == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="Đã hủy" {{ $datLich->Trangthai_ == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            @error('Trangthai_')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="MaDV" class="form-label">Dịch Vụ</label>
            <select id="MaDV" class="form-control @error('MaDV') is-invalid @enderror" name="MaDV" required>
                <option value="">Chọn dịch vụ</option>
                @foreach ($dichVus as $dichVu)
                    <option value="{{ $dichVu->MaDV }}" {{ $datLich->MaDV == $dichVu->MaDV ? 'selected' : '' }}>{{ $dichVu->Tendichvu }}</option>
                @endforeach
            </select>
            @error('MaDV')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('admin.datlich.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection