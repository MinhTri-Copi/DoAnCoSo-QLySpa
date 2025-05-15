@extends('backend.layouts.app')

@section('title', 'Quản lý hạng thành viên')

@section('content')
    <div class="container-fluid">
        <h1>Quản lý hạng thành viên</h1>

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

        <a href="{{ route('admin.membership_ranks.create') }}" class="btn btn-primary mb-3">Thêm hạng thành viên mới</a>

        @if ($ranks->isEmpty())
            <p>Chưa có hạng thành viên nào.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mã hạng</th>
                        <th>Tên hạng</th>
                        <th>Mô tả</th>
                        <th>Người tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ranks as $rank)
                        <tr>
                            <td>{{ $rank->Mahang }}</td>
                            <td>{{ $rank->Tenhang }}</td>
                            <td>{{ $rank->Mota ?? 'Không có mô tả' }}</td>
                            <td>{{ $rank->user->Hoten ?? 'Không xác định' }}</td>
                            <td>
                                <a href="{{ route('admin.membership_ranks.show', $rank->Mahang) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.membership_ranks.edit', $rank->Mahang) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="{{ route('admin.membership_ranks.confirm-destroy', $rank->Mahang) }}" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection