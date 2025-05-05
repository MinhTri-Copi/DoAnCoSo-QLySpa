@extends('backend.layouts.app')

@section('title', 'Quản lý phòng')

@section('content')
<div class="container">
    <h1>Quản lý phòng</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.phong.create') }}" class="btn btn-primary mb-3">Thêm phòng mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã phòng</th>
                <th>Tên phòng</th>
                <th>Loại phòng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($phongs as $phong)
                <tr>
                    <td>{{ $phong->Maphong }}</td>
                    <td>{{ $phong->Tenphong }}</td>
                    <td>{{ $phong->Loaiphong }}</td>
                    <td>{{ $phong->trangThaiPhong->Tentrangthai ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.phong.show', $phong->Maphong) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.phong.edit', $phong->Maphong) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ route('admin.phong.confirm-destroy', $phong->Maphong) }}" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection