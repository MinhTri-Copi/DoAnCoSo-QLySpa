@extends('backend.layouts.app')

@section('title', 'Quản lý tài khoản')

@section('content')
    <div class="container-fluid">
        <h1>Quản lý tài khoản</h1>

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

        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary mb-3">Thêm tài khoản mới</a>

        @if ($accounts->isEmpty())
            <p>Chưa có tài khoản nào.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mã tài khoản</th>
                        <th>Tên đăng nhập</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->MaTK }}</td>
                            <td>{{ $account->Tendangnhap }}</td>
                            <td>{{ $account->role->Tenrole }}</td>
                            <td>
                                <a href="{{ route('admin.accounts.show', $account->MaTK) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.accounts.edit', $account->MaTK) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="{{ route('admin.accounts.confirm-destroy', $account->MaTK) }}" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection