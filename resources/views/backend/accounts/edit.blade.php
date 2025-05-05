@extends('backend.layouts.app')

@section('title', 'Sửa tài khoản')

@section('content')
    <div class="container-fluid">
        <h1>Sửa tài khoản</h1>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.accounts.update', $account->MaTK) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="MaTK" class="form-label">Mã tài khoản</label>
                <input type="text" class="form-control" value="{{ $account->MaTK }}" disabled>
            </div>
            <div class="mb-3">
                <label for="Tendangnhap" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="Tendangnhap" class="form-control" value="{{ old('Tendangnhap', $account->Tendangnhap) }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="RoleID" class="form-label">Vai trò <span class="text-danger">*</span></label>
                <select name="RoleID" class="form-control" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->RoleID }}" {{ old('RoleID', $account->RoleID) == $role->RoleID ? 'selected' : '' }}>
                            {{ $role->Tenrole }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật tài khoản</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection