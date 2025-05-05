@extends('backend.layouts.app')

@section('title', 'Chi tiết vai trò')

@section('content')
    <div class="container-fluid">
        <h1>Chi tiết vai trò</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Vai trò: {{ $role->RoleName }}</h5>
                <p class="card-text">
                    <strong>Mã vai trò:</strong> {{ $role->RoleID }}<br>
                    <strong>Tên vai trò:</strong> {{ $role->Tenrole }}<br>
                    <strong>Số lượng tài khoản sử dụng:</strong> {{ $role->accounts()->count() }}
                </p>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('admin.roles.edit', $role->RoleID) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.roles.confirm-destroy', $role->RoleID) }}" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
@endsection