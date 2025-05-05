@extends('backend.layouts.app')

@section('title', 'Quản Lý Đặt Lịch')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Đặt Lịch</h2>
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

    <a href="{{ route('admin.datlich.create') }}" class="btn btn-primary mb-3">Thêm Đặt Lịch</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Đặt Lịch</th>
                <th>Người Dùng</th>
                <th>Thời Gian Đặt Lịch</th>
                <th>Trạng Thái</th>
                <th>Dịch Vụ</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datLichs as $datLich)
                <tr>
                    <td>{{ $datLich->MaDL }}</td>
                    <td>{{ $datLich->user->Hoten ?? 'N/A' }}</td>
                    <td>{{ $datLich->Thoigiandatlich }}</td>
                    <td>{{ $datLich->Trangthai_ }}</td>
                    <td>{{ $datLich->dichVu->Tendichvu ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.datlich.show', $datLich->MaDL) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.datlich.edit', $datLich->MaDL) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="{{ route('admin.datlich.confirm-destroy', $datLich->MaDL) }}" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection