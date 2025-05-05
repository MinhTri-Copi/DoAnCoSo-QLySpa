@extends('backend.layouts.app')

@section('title', 'Quản lý trạng thái quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Quản lý trạng thái quảng cáo</h1>

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

        <!-- Nút thêm trạng thái -->
        <a href="{{ route('ad-statuses.create') }}" class="btn btn-primary mb-3">Thêm trạng thái mới</a>

        <!-- Bảng danh sách trạng thái -->
        @if ($statuses->isEmpty())
            <p>Chưa có trạng thái quảng cáo nào.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mã trạng thái</th>
                        <th>Tên trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statuses as $status)
                        <tr>
                            <td>{{ $status->MaTTQC }}</td>
                            <td>{{ $status->TenTT }}</td>
                            <td>
                                <a href="{{ route('ad-statuses.show', $status->MaTTQC) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('ad-statuses.edit', $status->MaTTQC) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="{{ route('ad-statuses.confirm-destroy', $status->MaTTQC) }}" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection