@extends('backend.layouts.app')

@section('title', 'Chi tiết tài khoản')

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
    
    .spa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 109, 119, 0.2);
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
    
    .detail-label {
        font-weight: 600;
        color: var(--spa-dark);
        margin-bottom: 0.3rem;
    }
    
    .detail-value {
        margin-bottom: 1.2rem;
        color: var(--spa-text);
    }
    
    .account-profile {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .account-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-right: 1.5rem;
    }
    
    .account-info h4 {
        color: var(--spa-dark);
        margin-bottom: 0.3rem;
    }
    
    .account-info p {
        color: var(--spa-text);
        margin-bottom: 0;
    }
    
    .role-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 0.5rem;
    }
    
    .role-admin {
        background-color: var(--spa-accent);
        color: white;
    }
    
    .role-customer {
        background-color: var(--spa-primary);
        color: white;
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
    
    .btn-spa-warning {
        background: var(--spa-secondary);
        border: none;
        color: var(--spa-accent);
    }
    
    .btn-spa-danger {
        background: #ffe5e5;
        border: none;
        color: #ff5757;
    }
    
    .btn-spa:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-heading mb-4">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-white font-weight-bold">Chi Tiết Tài Khoản</h1>
                <p class="mb-0 text-white opacity-75">
                    <i class="fas fa-user mr-1"></i> Xem thông tin chi tiết của tài khoản
                </p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-spa btn-spa-secondary shadow" target="_self">
                    <i class="fas fa-arrow-left mr-2"></i>Quay Lại
                </a>
            </div>
        </div>
    </div>

    <!-- Account Details -->
    <div class="spa-card">
        <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                <i class="fas fa-user-circle mr-2"></i>Thông Tin Tài Khoản
            </h6>
        </div>
        <div class="spa-card-body">
            <div class="account-profile">
                <div class="account-avatar">
                    {{ strtoupper(substr($account->Tendangnhap, 0, 1)) }}
                </div>
                <div class="account-info">
                    <h4>{{ $account->Tendangnhap }}</h4>
                    <p>Mã tài khoản: <strong>{{ $account->MaTK }}</strong></p>
                    <span class="role-badge {{ $account->RoleID == 1 ? 'role-admin' : 'role-customer' }}">
                        {{ $account->role->Tenrole }}
                    </span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <p class="detail-label">Mã tài khoản</p>
                    <p class="detail-value">{{ $account->MaTK }}</p>
                </div>
                <div class="col-md-6">
                    <p class="detail-label">Tên đăng nhập</p>
                    <p class="detail-value">{{ $account->Tendangnhap }}</p>
                </div>
                <div class="col-md-6">
                    <p class="detail-label">Vai trò</p>
                    <p class="detail-value">{{ $account->role->Tenrole }}</p>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-spa btn-spa-secondary" target="_self">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
                <a href="{{ route('admin.accounts.edit', $account->MaTK) }}" class="btn btn-spa btn-spa-warning" target="_self">
                    <i class="fas fa-edit mr-1"></i> Sửa
                </a>
                <a href="{{ route('admin.accounts.confirm-destroy', $account->MaTK) }}" class="btn btn-spa btn-spa-danger" target="_self">
                    <i class="fas fa-trash mr-1"></i> Xóa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection