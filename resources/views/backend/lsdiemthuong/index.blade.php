@extends('backend.layouts.app')

@section('title', 'Quản lý lịch sử điểm thưởng')

@section('content')
<div class="container">
    <h1>Quản lý lịch sử điểm thưởng</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.lsdiemthuong.create') }}" class="btn btn-primary mb-3">Thêm lịch sử điểm thưởng mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã lịch sử</th>
                <th>Thời gian</th>
                <th>Số điểm</th>
                <th>Người dùng</th>
                <th>Mã hóa đơn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pointHistories as $pointHistory)
                <tr>
                    <td>{{ $pointHistory->MaLSDT }}</td>
                    <td>{{ $pointHistory->Thoigian }}</td>
                    <td>{{ $pointHistory->Sodiem }}</td>
                    <td>{{ $pointHistory->user->name ?? 'N/A' }}</td>
                    <td>{{ $pointHistory->hoaDon->MaHD ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.lsdiemthuong.show', $pointHistory->MaLSDT) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.lsdiemthuong.edit', $pointHistory->MaLSDT) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="{{ route('admin.lsdiemthuong.confirm-destroy', $pointHistory->MaLSDT) }}" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection