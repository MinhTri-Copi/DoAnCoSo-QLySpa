@extends('backend.layouts.app')

@section('title', 'Quản lý khách hàng')

@section('content')
    <div class="container-fluid">
        <h1>Quản lý khách hàng</h1>

        <!-- Thông báo -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Nút thêm khách hàng -->
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary mb-3">Thêm khách hàng mới</a>

        <!-- Bảng danh sách khách hàng -->
        @if ($customers->isEmpty())
            <p>Chưa có khách hàng nào.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mã người dùng</th>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Giới tính</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->Manguoidung }}</td>
                            <td>{{ $customer->Hoten }}</td>
                            <td>{{ $customer->SDT }}</td>
                            <td>{{ $customer->Email }}</td>
                            <td>{{ $customer->Gioitinh }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->Manguoidung) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.customers.edit', $customer->Manguoidung) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="{{ route('admin.customers.confirm-destroy', $customer->Manguoidung) }}" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection