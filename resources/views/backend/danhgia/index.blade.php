@extends('backend.layouts.app')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container">
    <h1>Quản lý đánh giá</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã đánh giá</th>
                <th>Đánh giá sao</th>
                <th>Nhận xét</th>
                <th>Ngày đánh giá</th>
                <th>Người dùng</th>
                <th>Hóa đơn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($danhGias as $danhGia)
                <tr>
                    <td>{{ $danhGia->MaDG }}</td>
                    <td>{{ $danhGia->Danhgiasao }} sao</td>
                    <td>{{ $danhGia->Nhanxet ?? 'N/A' }}</td>
                    <td>{{ $danhGia->Ngaydanhgia }}</td>
                    <td>{{ $danhGia->user->name ?? 'N/A' }}</td>
                    <td>{{ $danhGia->hoaDon->MaHD ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.danhgia.show', $danhGia->MaDG) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.danhgia.edit', $danhGia->MaDG) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ route('admin.danhgia.confirm-destroy', $danhGia->MaDG) }}" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection