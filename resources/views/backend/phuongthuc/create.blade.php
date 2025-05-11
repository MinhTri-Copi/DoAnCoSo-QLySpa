@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm Phương Thức Thanh Toán Mới</h1>
        <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Quay Lại</span>
        </a>
    </div>

    <!-- Create Payment Method Form -->
    <div class="card shadow mb-4 animate__animated animate__fadeIn">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông Tin Phương Thức Thanh Toán</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.phuongthuc.store') }}" method="POST" id="createPaymentMethodForm">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="TenPT">Tên Phương Thức <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('TenPT') is-invalid @enderror" 
                                id="TenPT" name="TenPT" value="{{ old('TenPT') }}">
                            @error('TenPT')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="TenPTFeedback" class="invalid-feedback"></div>
                            <small class="form-text text-muted">Tên phương thức thanh toán không được vượt quá 255 ký tự</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Mota">Mô Tả</label>
                            <textarea class="form-control @error('Mota') is-invalid @enderror" 
                                id="Mota" name="Mota" rows="3">{{ old('Mota') }}</textarea>
                            @error('Mota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mô tả ngắn gọn về phương thức thanh toán</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-primary mb-3">Gợi Ý Phương Thức Phổ Biến</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="methodCash" name="paymentMethodTemplate" class="custom-control-input" value="cash">
                                            <label class="custom-control-label" for="methodCash">Tiền mặt</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="methodCard" name="paymentMethodTemplate" class="custom-control-input" value="card">
                                            <label class="custom-control-label" for="methodCard">Thẻ tín dụng/ghi nợ</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="methodTransfer" name="paymentMethodTemplate" class="custom-control-input" value="transfer">
                                            <label class="custom-control-label" for="methodTransfer">Chuyển khoản</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="methodMomo" name="paymentMethodTemplate" class="custom-control-input" value="momo">
                                            <label class="custom-control-label" for="methodMomo">Ví MoMo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="methodZaloPay" name="paymentMethodTemplate" class="custom-control-input" value="zalopay">
                                            <label class="custom-control-label" for="methodZaloPay">ZaloPay</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="font-weight-bold text-primary mb-3">Xem Trước Biểu Tượng</h6>
                                <i id="iconPreview" class="fas fa-money-bill-wave payment-method-cash fa-3x mb-3"></i>
                                <p class="text-muted">Biểu tượng sẽ được tự động chọn dựa trên tên phương thức</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save mr-1"></i> Lưu Phương Thức
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/phuongthuc.js') }}"></script>
<script src="{{ asset('js/admin/phuongthuc/create.js') }}"></script>
@endsection