@extends('backend.layouts.app')

@section('title', 'Quản Lý Phương Thức Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Phương Thức Hỗ Trợ</h2>
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

    <a href="{{ route('admin.pthotro.create') }}" class="btn btn-primary mb-3">Thêm Phương Thức Hỗ Trợ</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Phương Thức Hỗ Trợ</th>
                <th>Tên Phương Thức</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pthotros as $pthotro)
                <tr>
                    <td>{{ $pthotro->MaPTHT }}</td>
                    <td>{{ $pthotro->TenPT }}</td>
                    <td>
                        <a href="{{ route('admin.pthotro.show', $pthotro->MaPTHT) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('admin.pthotro.edit', $pthotro->MaPTHT) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="{{ route('admin.pthotro.confirm-destroy', $pthotro->MaPTHT) }}" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection