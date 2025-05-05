@extends('backend.layouts.app')

@section('title', 'Quản Lý Phiếu Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Phiếu Hỗ Trợ</h2>
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

    <a href="{{ route('admin.phieuhotro.create') }}" class="btn btn-primary mb-3">Thêm Phiếu Hỗ Trợ</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Phiếu Hỗ Trợ</th>
                <th>Nội Dung Yêu Cầu</th>
                <th>Trạng Thái</th>
                <th>Người Dùng</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($phieuHoTros as $phieuHoTro)
                <tr>
                    <td>{{ $phieuHoTro->MaphieuHT }}</td>
                    <td>{{ $phieuHoTro->Noidungyeucau }}</td>
                    <td>{{ $phieuHoTro->trangThai->Tentrangthai ?? 'N/A' }}</td>
                    <td>{{ $phieuHoTro->user->Hoten ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.phieuhotro.show', $phieuHoTro->MaphieuHT) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.phieuhotro.edit', $phieuHoTro->MaphieuHT) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="{{ route('admin.phieuhotro.confirm-destroy', $phieuHoTro->MaphieuHT) }}" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection