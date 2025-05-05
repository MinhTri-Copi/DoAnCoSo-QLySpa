@extends('backend.layouts.app')

@section('title', 'Quản lý phương thức')

@section('content')
<div class="container">
    <h1>Quản lý phương thức</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.phuongthuc.create') }}" class="btn btn-primary mb-3">Thêm phương thức mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã phương thức</th>
                <th>Tên phương thức</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($phuongThucs as $phuongThuc)
                <tr>
                    <td>{{ $phuongThuc->MaPT }}</td>
                    <td>{{ $phuongThuc->TenPT }}</td>
                    <td>{{ $phuongThuc->Mota ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.phuongthuc.edit', $phuongThuc->MaPT) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ route('admin.phuongthuc.confirm-destroy', $phuongThuc->MaPT) }}" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection