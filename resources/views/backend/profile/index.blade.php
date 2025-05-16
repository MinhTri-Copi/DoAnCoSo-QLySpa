@extends('backend.layouts.app')

@section('title', 'Thông Tin Cá Nhân')

@section('styles')
<style>
    .profile-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .profile-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0;
    }
    
    .profile-tabs {
        display: flex;
        border-bottom: none;
    }
    
    .profile-tabs .nav-item .nav-link {
        color: #6c757d;
        padding: 15px 25px;
        font-weight: 500;
        border: none;
        border-bottom: 3px solid transparent;
        background: transparent;
        transition: all 0.3s;
    }
    
    .profile-tabs .nav-item .nav-link.active {
        color: #ff6b95;
        border-bottom: 3px solid #ff6b95;
        font-weight: 600;
    }
    
    .profile-content {
        padding: 30px;
    }
    
    .profile-title {
        color: #ff6b95;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .info-group {
        margin-bottom: 25px;
    }
    
    .info-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
    }
    
    .info-group .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #ced4da;
        background-color: #f8f9fa;
        transition: all 0.3s;
    }
    
    .info-group .form-control:focus {
        border-color: #ff6b95;
        box-shadow: 0 0 0 0.25rem rgba(255, 107, 149, 0.25);
    }
    
    .info-group .form-control:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
    
    .info-group .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
    }
    
    .change-link {
        color: #ff6b95;
        text-decoration: none;
        margin-left: 10px;
        font-weight: 500;
    }
    
    .change-link:hover {
        text-decoration: underline;
    }
    
    .gender-options {
        display: flex;
        gap: 20px;
    }
    
    .gender-option {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .gender-option input {
        margin-right: 8px;
    }
    
    .btn-update {
        background-color: #ff6b95;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-update:hover {
        background-color: #e84a78;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(232, 74, 120, 0.3);
    }
    
    .modal-header {
        background-color: #ff6b95;
        color: white;
    }
    
    .modal-header .btn-close {
        color: white;
    }
    
    .modal-footer .btn-primary {
        background-color: #ff6b95;
        border-color: #ff6b95;
    }
    
    .modal-footer .btn-primary:hover {
        background-color: #e84a78;
        border-color: #e84a78;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="profile-container">
                <div class="profile-header">
                    <ul class="nav profile-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Thông Tin Cá Nhân</a>
                        </li>
                    </ul>
                </div>
                
                <div class="profile-content">
                    <h2 class="profile-title">
                        <i class="fas fa-user-circle me-2"></i>Thông tin cá nhân
                    </h2>
                    
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label for="fullname">Họ và tên</label>
                                    <input type="text" class="form-control" id="fullname" name="Hoten" value="{{ $user->Hoten ?? 'Ngô Vũ' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label for="birthdate">Ngày sinh</label>
                                    <input type="date" class="form-control" id="birthdate" name="Ngaysinh" value="{{ $user->Ngaysinh ?? '2004-08-19' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="Email" value="{{ $user->Email ?? 'ngophannnguyenvu24052019@gmail.com' }}" readonly>
                                        <a href="#" class="change-link" data-bs-toggle="modal" data-bs-target="#changeEmailModal">Thay đổi</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="SDT" value="{{ $user->SDT ?? '0347916325' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label for="password">Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" value="●●●●●●●●●●" disabled>
                                        <a href="#" class="change-link" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Thay đổi</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Giới tính</label>
                                    <div class="gender-options">
                                        <label class="gender-option">
                                            <input type="radio" name="Gioitinh" value="Nam" {{ ($user->Gioitinh ?? 'Nam') == 'Nam' ? 'checked' : '' }}> Nam
                                        </label>
                                        <label class="gender-option">
                                            <input type="radio" name="Gioitinh" value="Nữ" {{ ($user->Gioitinh ?? '') == 'Nữ' ? 'checked' : '' }}> Nữ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn-update">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Thay đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.change-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="new_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Email Modal -->
<div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeEmailModalLabel">Thay đổi email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.change-email') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_email" class="form-label">Email mới</label>
                        <input type="email" class="form-control" id="new_email" name="email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 