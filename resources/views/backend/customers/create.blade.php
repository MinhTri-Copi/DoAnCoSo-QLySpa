@extends('backend.layouts.app')

@section('title', 'Thêm khách hàng')

@section('content')
    <div class="container-fluid">
        <h1>Thêm khách hàng</h1>

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

        <!-- Form thêm khách hàng -->
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="Manguoidung" class="form-label">Mã người dùng <span class="text-danger">*</span></label>
                <input type="number" name="Manguoidung" class="form-control" value="{{ $suggestedManguoidung }}" readonly>
                <small class="form-text text-muted">Mã người dùng được sinh tự động.</small>
            </div>
            <div class="mb-3">
                <label for="MaTK" class="form-label">Mã tài khoản <span class="text-danger">*</span></label>
                <select name="MaTK" class="form-control" required>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->MaTK }}">{{ $account->MaTK }} - {{ $account->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Hoten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="Hoten" class="form-control" value="{{ old('Hoten') }}" required>
            </div>
            <div class="mb-3">
                <label for="SDT" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT') }}" required>
            </div>
            <div class="mb-3">
                <label for="DiaChi" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                <input type="text" name="DiaChi" class="form-control" value="{{ old('DiaChi') }}" required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="Email" class="form-control" value="{{ old('Email') }}" required>
            </div>
            <div class="mb-3">
                <label for="Ngaysinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                <input type="date" name="Ngaysinh" class="form-control" value="{{ old('Ngaysinh') }}" required>
            </div>
            <div class="mb-3">
                <label for="Gioitinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                <select name="Gioitinh" class="form-control" required>
                    <option value="Nam" {{ old('Gioitinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('Gioitinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm khách hàng</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection