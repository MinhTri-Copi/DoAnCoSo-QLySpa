@extends('backend.layouts.app')

@section('title', 'Quản Lý Trạng Thái')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Trạng Thái</h2>
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

    <a href="{{ route('admin.trangthai.create') }}" class="btn btn-primary mb-3">Thêm Trạng Thái</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Trạng Thái</th>
                <th>Tên Trạng Thái</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trangThais as $trangThai)
                <tr>
                    <td>{{ $trangThai->Matrangthai }}</td>
                    <td>{{ $trangThai->Tentrangthai }}</td>
                    <td>
                        <a href="{{ route('admin.trangthai.show', $trangThai->Matrangthai) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.trangthai.edit', $trangThai->Matrangthai) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="{{ route('admin.trangthai.confirm-destroy', $trangThai->Matrangthai) }}" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection