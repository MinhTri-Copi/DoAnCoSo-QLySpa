@extends('backend.layouts.app')

@section('title', 'Chi tiết tài khoản')

@section('content')
    <div class="container-fluid">
        <h1>Chi tiết tài khoản</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tài khoản: {{ $account->Tendangnhap }}</h5>
                <p class="card-text">
                    <strong>Mã tài khoản:</strong> {{ $account->MaTK }}<br>
                    <strong>Tên đăng nhập:</strong> {{ $account->Tendangnhap }}<br>
                    <strong>Vai trò:</strong> {{ $account->role->Tenrole }}
                </p>
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('admin.accounts.edit', $account->MaTK) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.accounts.confirm-destroy', $account->MaTK) }}" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
@endsection