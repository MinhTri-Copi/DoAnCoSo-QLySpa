@extends('backend.layouts.app')

@section('title', 'Quản lý vai trò')

@section('content')
    <div class="container-fluid">
        <h1>Quản lý vai trò</h1>

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

        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">Thêm vai trò mới</a>

        @if ($roles->isEmpty())
            <p>Chưa có vai trò nào.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mã vai trò</th>
                        <th>Tên vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->RoleID }}</td>
                            <td>{{ $role->Tenrole }}</td>
                            <td>
                                <a href="{{ route('admin.roles.show', $role->RoleID) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.roles.edit', $role->RoleID) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="{{ route('admin.roles.confirm-destroy', $role->RoleID) }}" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection