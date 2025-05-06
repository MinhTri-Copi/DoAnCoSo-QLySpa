@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/customers.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm Khách Hàng Mới</h1>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Quay Lại</span>
        </a>
    </div>

    <!-- Create Customer Form -->
    <div class="card shadow mb-4 animate__animated animate__fadeIn">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông Tin Khách Hàng</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.customers.store') }}" method="POST" id="createCustomerForm">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Manguoidung">Mã Khách Hàng <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('Manguoidung') is-invalid @enderror" 
                                    id="Manguoidung" name="Manguoidung" value="{{ $suggestedManguoidung }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="generateId">
                                        <i class="fas fa-random"></i>
                                    </button>
                                </div>
                            </div>
                            @error('Manguoidung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mã khách hàng được tạo tự động</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="MaTK">Tài Khoản <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('MaTK') is-invalid @enderror" id="MaTK" name="MaTK">
                                <option value="">-- Chọn Tài Khoản --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->MaTK }}">
                                        {{ $account->Tendangnhap }} ({{ $account->MaTK }})
                                    </option>
                                @endforeach
                            </select>
                            @error('MaTK')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="Hoten">Họ Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Hoten') is-invalid @enderror" 
                                id="Hoten" name="Hoten" value="{{ old('Hoten') }}">
                            @error('Hoten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="SDT">Số Điện Thoại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control @error('SDT') is-invalid @enderror" 
                                    id="SDT" name="SDT" value="{{ old('SDT') }}">
                            </div>
                            @error('SDT')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="SDTFeedback" class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Email">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control @error('Email') is-invalid @enderror" 
                                    id="Email" name="Email" value="{{ old('Email') }}">
                            </div>
                            @error('Email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="DiaChi">Địa Chỉ <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('DiaChi') is-invalid @enderror" 
                                id="DiaChi" name="DiaChi" rows="3">{{ old('DiaChi') }}</textarea>
                            @error('DiaChi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="Ngaysinh">Ngày Sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('Ngaysinh') is-invalid @enderror" 
                                id="Ngaysinh" name="Ngaysinh" value="{{ old('Ngaysinh') }}">
                            @error('Ngaysinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="NgaysinhFeedback" class="invalid-feedback"></div>
                        </div>
                        
                        <div class="form-group">
                            <label>Giới Tính <span class="text-danger">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="genderMale" name="Gioitinh" value="Nam" 
                                    class="custom-control-input @error('Gioitinh') is-invalid @enderror"
                                    {{ old('Gioitinh') == 'Nam' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="genderMale">Nam</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="genderFemale" name="Gioitinh" value="Nữ" 
                                    class="custom-control-input @error('Gioitinh') is-invalid @enderror"
                                    {{ old('Gioitinh') == 'Nữ' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="genderFemale">Nữ</label>
                            </div>
                            @error('Gioitinh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save mr-1"></i> Lưu Khách Hàng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/customers.js') }}"></script>
<script src="{{ asset('js/admin/customers/create.js') }}"></script>
@endsection