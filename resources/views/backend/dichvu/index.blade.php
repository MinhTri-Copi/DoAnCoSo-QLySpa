@extends('backend.layouts.app')

@section('title', 'Quản Lý Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Dịch Vụ</h2>
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

    <a href="{{ route('admin.dichvu.create') }}" class="btn btn-primary mb-3">Thêm Dịch Vụ</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Dịch Vụ</th>
                <th>Tên Dịch Vụ</th>
                <th>Giá</th>
                <th>Mô Tả</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dichVus as $dichVu)
                <tr>
                    <td>{{ $dichVu->MaDV }}</td>
                    <td>{{ $dichVu->Tendichvu }}</td>
                    <td>{{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $dichVu->MoTa ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.dichvu.show', $dichVu->MaDV) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.dichvu.edit', $dichVu->MaDV) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="{{ route('admin.dichvu.confirm-destroy', $dichVu->MaDV) }}" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection