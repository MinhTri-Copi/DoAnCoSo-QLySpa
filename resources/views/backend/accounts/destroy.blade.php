@extends('backend.layouts.app')

@section('title', 'Xóa tài khoản')

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
        --spa-danger: #ff5757;
        --spa-danger-light: #ffe5e5;
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
    
    .btn-spa-secondary {
        background: var(--spa-light);
        border: none;
        color: var(--spa-dark);
    }
    
    .btn-spa-danger {
        background: var(--spa-danger-light);
        border: none;
        color: var(--spa-danger);
    }
    
    .btn-spa:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-spa-danger:hover {
        background: var(--spa-danger);
        color: white;
    }
    
    .warning-alert {
        background-color: var(--spa-danger-light);
        border: none;
        border-radius: 10px;
        padding: 1.5rem;
        color: var(--spa-danger);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .warning-icon {
        font-size: 2rem;
        margin-right: 1rem;
    }
    
    .account-info {
        background: var(--spa-light);
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .account-info span {
        font-weight: 600;
        color: var(--spa-dark);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-heading mb-4">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-white font-weight-bold">Xóa Tài Khoản</h1>
                <p class="mb-0 text-white opacity-75">
                    <i class="fas fa-trash mr-1"></i> Xác nhận xóa tài khoản
                </p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-spa btn-spa-secondary shadow" target="_self">
                    <i class="fas fa-arrow-left mr-2"></i>Quay Lại
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation -->
    <div class="spa-card">
        <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>Xác Nhận Xóa
            </h6>
        </div>
        <div class="spa-card-body">
            <div class="warning-alert">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div>
                    <h5 class="font-weight-bold mb-2">Cảnh báo! Hành động không thể hoàn tác</h5>
                    <p class="mb-0">Bạn có chắc chắn muốn xóa tài khoản này không? Dữ liệu đã xóa không thể phục hồi.</p>
                </div>
            </div>
            
            <div class="account-info">
                <p><span>Tên đăng nhập:</span> {{ $account->Tendangnhap }}</p>
                <p><span>Mã tài khoản:</span> {{ $account->MaTK }}</p>
                <p class="mb-0"><span>Vai trò:</span> {{ $account->role->Tenrole }}</p>
            </div>

            <form action="{{ route('admin.accounts.destroy', $account->MaTK) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="text-right mt-4">
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-spa btn-spa-secondary" target="_self">
                        <i class="fas fa-times mr-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-spa btn-spa-danger">
                        <i class="fas fa-trash mr-1"></i> Xác nhận xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection