@extends('backend.layouts.app')

@section('title', 'Sửa khách hàng')

@section('content')
    <div class="container-fluid">
        <h1>Sửa khách hàng</h1>

        <!-- Thông báo lỗi -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form sửa khách hàng -->
        <form action="{{ route('admin.customers.update', $customer->Manguoidung) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="Manguoidung" class="form-label">Mã người dùng</label>
                <input type="text" class="form-control" value="{{ $customer->Manguoidung }}" disabled>
            </div>
            <div class="mb-3">
                <label for="MaTK" class="form-label">Mã tài khoản <span class="text-danger">*</span></label>
                <select name="MaTK" class="form-control" required>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->MaTK }}" {{ $customer->MaTK == $account->MaTK ? 'selected' : '' }}>
                            {{ $account->MaTK }} - {{ $account->username }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Hoten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="Hoten" class="form-control" value="{{ old('Hoten', $customer->Hoten) }}" required>
            </div>
            <div class="mb-3">
                <label for="SDT" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT', $customer->SDT) }}" required>
            </div>
            <div class="mb-3">
                <label for="DiaChi" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                <input type="text" name="DiaChi" class="form-control" value="{{ old('DiaChi', $customer->DiaChi) }}" required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="Email" class="form-control" value="{{ old('Email', $customer->Email) }}" required>
            </div>
            <div class="mb-3">
                <label for="Ngaysinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                <input type="date" name="Ngaysinh" class="form-control" value="{{ old('Ngaysinh', $customer->Ngaysinh) }}" required>
            </div>
            <div class="mb-3">
                <label for="Gioitinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                <select name="Gioitinh" class="form-control" required>
                    <option value="Nam" {{ old('Gioitinh', $customer->Gioitinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('Gioitinh', $customer->Gioitinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật khách hàng</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection