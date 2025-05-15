@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}?v={{ time() }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* Inline styles để đảm bảo ghi đè các styles khác */
    .payment-create-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-create-card .card-header {
        background: linear-gradient(40deg, #ff6b95, #ffa7bc) !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem 1.5rem !important;
    }
    
    .payment-create-card .card-header h6 {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
    }
    
    .text-primary {
        color: #ff6b95 !important;
    }
    
    .font-weight-bold.text-primary {
        color: #ff6b95 !important;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
        border-radius: 15px !important;
        border: none !important;
    }
    
    .btn-primary {
        background-color: #ff6b95 !important;
        border-color: #ff6b95 !important;
        color: white !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-primary:hover {
        background-color: #ff4c7f !important;
        border-color: #ff4c7f !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.3) !important;
    }
    
    .btn-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268 !important;
        border-color: #545b62 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3) !important;
    }
    
    .payment-method-cash {
        color: #ff6b95 !important;
    }
    
    /* Custom control styling */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #ff6b95 !important;
        border-color: #ff6b95 !important;
    }
    
    .custom-radio .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 149, 0.25) !important;
    }
    
    /* Form control styling */
    .form-control:focus {
        border-color: #ff6b95 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 149, 0.25) !important;
    }
    
    /* Header styling */
    .payment-header {
        background: #ff6b95 !important;
        color: white !important;
        padding: 1.5rem !important;
        border-radius: 15px !important;
        margin-bottom: 2rem !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.3) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }
    
    .payment-header h1 {
        margin: 0 !important;
        font-size: 1.75rem !important;
        font-weight: 600 !important;
        color: white !important;
    }
    
    .payment-header p {
        margin: 0 !important;
        opacity: 0.9 !important;
        margin-top: 0.25rem !important;
    }
    
    .back-btn {
        background-color: white !important;
        color: #ff6b95 !important;
        border: none !important;
        border-radius: 50px !important;
        padding: 0.6rem 1.5rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }
    
    .back-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
        background-color: #f8f9fa !important;
    }
    
    .back-btn i {
        margin-right: 0.5rem !important;
    }
    
    /* Card preview styling */
    .payment-method-preview-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
        height: 100% !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- New Header -->
    <div class="payment-header animate__animated animate__fadeIn">
        <div>
            <h1>Thêm Phương Thức Thanh Toán Mới</h1>
            <p><i class="fas fa-credit-card mr-2"></i> Cung cấp thêm lựa chọn thanh toán cho khách hàng</p>
        </div>
        <a href="{{ route('admin.phuongthuc.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Quay Lại</span>
        </a>
    </div>

    <!-- Create Payment Method Form -->
    <div class="card payment-create-card shadow mb-4 animate__animated animate__fadeIn">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Thông Tin Phương Thức Thanh Toán</h6>
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
                        <div class="card payment-method-preview-card">
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
                        <div class="card payment-method-preview-card">
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
<script src="{{ asset('js/admin/phuongthuc.js') }}?v={{ time() }}"></script>
<script src="{{ asset('js/admin/phuongthuc/create.js') }}?v={{ time() }}"></script>
@endsection