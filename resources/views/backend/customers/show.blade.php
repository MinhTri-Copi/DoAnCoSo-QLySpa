@extends('backend.layouts.app')

@section('title', 'Chi tiết khách hàng')

@section('content')
    <div class="container-fluid">
        <h1>Chi tiết khách hàng</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Khách hàng: {{ $customer->Hoten }}</h5>
                <p class="card-text">
                    <strong>Mã người dùng:</strong> {{ $customer->Manguoidung }}<br>
                    <strong>Mã tài khoản:</strong> {{ $customer->MaTK }}<br>
                    <strong>Họ tên:</strong> {{ $customer->Hoten }}<br>
                    <strong>Số điện thoại:</strong> {{ $customer->SDT }}<br>
                    <strong>Địa chỉ:</strong> {{ $customer->DiaChi }}<br>
                    <strong>Email:</strong> {{ $customer->Email }}<br>
                    <strong>Ngày sinh:</strong> {{ $customer->Ngaysinh }}<br>
                    <strong>Giới tính:</strong> {{ $customer->Gioitinh }}
                </p>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('admin.customers.edit', $customer->Manguoidung) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.customers.confirm-destroy', $customer->Manguoidung) }}" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
@endsection