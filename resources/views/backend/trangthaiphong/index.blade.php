@extends('backend.layouts.app')

@section('title', 'Quản Lý Trạng Thái Phòng')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Trạng Thái Phòng</h2>
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

    <a href="{{ route('admin.trangthaiphong.create') }}" class="btn btn-primary mb-3">Thêm Trạng Thái Phòng</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Trạng Thái Phòng</th>
                <th>Tên Trạng Thái</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trangThaiPhongs as $trangThaiPhong)
                <tr>
                    <td>{{ $trangThaiPhong->MatrangthaiP }}</td>
                    <td>{{ $trangThaiPhong->Tentrangthai }}</td>
                    <td>
                        <a href="{{ route('admin.trangthaiphong.show', $trangThaiPhong->MatrangthaiP) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.trangthaiphong.edit', $trangThaiPhong->MatrangthaiP) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="{{ route('admin.trangthaiphong.confirm-destroy', $trangThaiPhong->MatrangthaiP) }}" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection