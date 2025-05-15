@extends('backend.layouts.app')

@section('title', 'Sửa tài khoản')

@section('styles')
<style>
    :root {
        --spa-primary: #83c5be;
        --spa-primary-dark: #3d7068;
        --spa-secondary: #ffddd2;
        --spa-accent: #e29578;
        --spa-light: #edf6f9;
        --spa-dark: #006d77;
        --spa-text: #2c3e50;
        --spa-card-shadow: 0 8px 20px rgba(0, 109, 119, 0.1);
    }
    
    .page-heading {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border-radius: 10px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-heading::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background-image: url('/img/zen-pattern.png');
        background-size: cover;
        opacity: 0.1;
    }
    
    .spa-card {
        border: none;
        border-radius: 10px;
        box-shadow: var(--spa-card-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
        margin-bottom: 1.5rem;
    }
    
    .spa-card-header {
        background: var(--spa-light);
        border-bottom: none;
        font-weight: 600;
        color: var(--spa-dark);
        padding: 1.25rem 1.5rem;
    }
    
    .spa-card-body {
        padding: 1.5rem;
    }
    
    .btn-spa {
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        transition: all 0.3s;
        margin-right: 0.5rem;
    }
    
    .btn-spa-primary {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border: none;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 109, 119, 0.2);
    }
    
    .btn-spa-secondary {
        background: var(--spa-light);
        border: none;
        color: var(--spa-dark);
    }
    
    .btn-spa:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
    
    /* Form styling */
    .form-label {
        font-weight: 600;
        color: var(--spa-dark);
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        padding: 0.6rem 1rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(131, 197, 190, 0.25);
        border-color: var(--spa-primary);
    }
    
    .form-select {
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        padding: 0.6rem 1rem;
        transition: all 0.3s;
    }
    
    .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(131, 197, 190, 0.25);
        border-color: var(--spa-primary);
    }
    
    .text-danger {
        color: #ff5757;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-heading mb-4">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-white font-weight-bold">Sửa Tài Khoản</h1>
                <p class="mb-0 text-white opacity-75">
                    <i class="fas fa-user-edit mr-1"></i> Cập nhật thông tin tài khoản
                </p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-spa btn-spa-secondary shadow" target="_self">
                    <i class="fas fa-arrow-left mr-2"></i>Quay Lại
                </a>
            </div>
        </div>
    </div>

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

    <!-- Account Edit Form -->
    <div class="spa-card">
        <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                <i class="fas fa-user-edit mr-2"></i>Thông Tin Tài Khoản
            </h6>
        </div>
        <div class="spa-card-body">
            <form action="{{ route('admin.accounts.update', $account->MaTK) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="MaTK" class="form-label">Mã tài khoản</label>
                        <input type="text" class="form-control" value="{{ $account->MaTK }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="Tendangnhap" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" name="Tendangnhap" class="form-control" value="{{ old('Tendangnhap', $account->Tendangnhap) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="RoleID" class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="RoleID" class="form-select" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->RoleID }}" {{ old('RoleID', $account->RoleID) == $role->RoleID ? 'selected' : '' }}>
                                    {{ $role->Tenrole }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="text-right mt-4">
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-spa btn-spa-secondary" target="_self">
                        <i class="fas fa-times mr-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-spa btn-spa-primary">
                        <i class="fas fa-save mr-1"></i> Cập nhật tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection