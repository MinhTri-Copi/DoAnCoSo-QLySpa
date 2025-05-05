@extends('backend.layouts.app')

@section('title', 'Thêm tài khoản')

@section('content')
    <div class="container-fluid">
        <h1>Thêm tài khoản</h1>

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

        <form action="{{ route('admin.accounts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="MaTK" class="form-label">Mã tài khoản <span class="text-danger">*</span></label>
                <input type="number" name="MaTK" class="form-control" value="{{ $suggestedMaTK }}" readonly>
                <small class="form-text text-muted">Mã tài khoản được sinh tự động.</small>
            </div>
            <div class="mb-3">
                <label for="Tendangnhap" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="Tendangnhap" class="form-control" value="{{ old('Tendangnhap') }}" required>
            </div>
            <div class="mb-3">
                <label for="Matkhau" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                <input type="Matkhau" name="Matkhau" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="RoleID" class="form-label">Vai trò <span class="text-danger">*</span></label>
                <select name="RoleID" class="form-control" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->RoleID }}" {{ old('RoleID') == $role->RoleID ? 'selected' : '' }}>
                            {{ $role->Tenrole }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection