@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}?v={{ time() }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* Inline styles để đảm bảo ghi đè các styles khác */
    .payment-edit-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-edit-card .card-header {
        background: linear-gradient(40deg, #ff6b95, #ffa7bc) !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem 1.5rem !important;
    }
    
    .payment-edit-card .card-header h6 {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
    }
    
    .payment-stats-card {
        border: none !important;
        border-radius: 15px !important;
        background-color: #ffffff !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
    }
    
    .payment-stats-card .card-header {
        background: linear-gradient(40deg, #ff6b95, #ffa7bc) !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem 1.5rem !important;
    }
    
    .btn-pink {
        background-color: #ff6b95 !important;
        border-color: #ff6b95 !important;
        color: white !important;
        font-weight: 500 !important;
        padding: 0.5rem 1.5rem !important;
        border-radius: 50px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 10px rgba(255, 107, 149, 0.2) !important;
    }
    
    .btn-outline-pink {
        background-color: transparent !important;
        border-color: #ff6b95 !important;
        color: #ff6b95 !important;
        font-weight: 500 !important;
        padding: 0.5rem 1.5rem !important;
        border-radius: 50px !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-outline-pink:hover {
        background-color: #ff6b95 !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.2) !important;
    }
    
    .btn-pink:hover {
        background-color: #ff4c7f !important;
        border-color: #ff4c7f !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.3) !important;
    }
    
    .btn-group-edit {
        display: flex !important;
        justify-content: center !important;
        gap: 10px !important;
        margin-top: 1.5rem !important;
    }
    
    .stat-item-pink {
        background-color: rgba(255, 107, 149, 0.05) !important;
        border-radius: 15px !important;
        padding: 1.25rem !important;
        transition: all 0.3s ease !important;
    }
    
    .stat-icon-pink {
        color: #ff6b95 !important;
    }
    
    .badge-pink {
        background-color: #ff6b95 !important;
        color: white !important;
        padding: 0.5em 1em !important;
        border-radius: 50px !important;
        font-weight: 500 !important;
    }
    
    .required-field {
        color: #ff6b95 !important;
        font-weight: bold !important;
    }
    
    .icon-preview-section {
        background-color: rgba(255, 107, 149, 0.05) !important;
        padding: 2rem !important;
        border-radius: 15px !important;
        text-align: center !important;
        margin: 1.5rem 0 !important;
    }
    
    #iconPreview {
        font-size: 3rem !important;
        color: #ff6b95 !important;
        display: block !important;
        margin: 0 auto 1rem auto !important;
    }
    
    .text-pink {
        color: #ff6b95 !important;
    }
    
    .pink-icon {
        color: #ff6b95 !important;
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
    
    .add-payment-btn {
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
    
    .add-payment-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
        background-color: #f8f9fa !important;
    }
    
    .add-payment-btn i {
        margin-right: 0.5rem !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header giống hình mẫu -->
    <div class="payment-header animate__animated animate__fadeIn">
        <div>
            <h1>Quản Lý Phương Thức Thanh Toán</h1>
            <p><i class="fas fa-credit-card mr-2"></i> Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất</p>
        </div>
        <a href="{{ route('admin.phuongthuc.create') }}" class="add-payment-btn">
            <i class="fas fa-plus"></i>
            <span>Thêm Phương Thức Mới</span>
        </a>
    </div>

    <!-- Phần tiêu đề chi tiết -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh Sửa Phương Thức Thanh Toán</h1>
        <div>
            <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="btn btn-outline-pink mr-2">
                <i class="fas fa-eye mr-1"></i>
                <span class="text">Xem Chi Tiết</span>
            </a>
            <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-outline-pink">
                <i class="fas fa-arrow-left mr-1"></i>
                <span class="text">Quay Lại</span>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Payment Method Edit Form -->
        <div class="col-lg-8">
            <div class="card payment-edit-card shadow mb-4 animate__animated animate__fadeIn">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Thông Tin Phương Thức Thanh Toán</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}">
                                <i class="fas fa-eye fa-sm fa-fw mr-2 pink-icon"></i>
                                Xem Chi Tiết
                            </a>
                            @if($phuongThuc->hoaDon->count() == 0)
                                <a class="dropdown-item" href="{{ route('admin.phuongthuc.confirmDestroy', $phuongThuc->MaPT) }}">
                                    <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Xóa Phương Thức
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.phuongthuc.update', $phuongThuc->MaPT) }}" method="POST" id="editPaymentMethodForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="MaPT" class="form-label">Mã Phương Thức</label>
                                    <input type="text" class="form-control" id="MaPT" value="{{ $phuongThuc->MaPT }}" readonly>
                                    <small class="form-text">Mã phương thức không thể thay đổi</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TenPT" class="form-label">Tên Phương Thức <span class="required-field">*</span></label>
                                    <input type="text" class="form-control @error('TenPT') is-invalid @enderror" 
                                        id="TenPT" name="TenPT" value="{{ old('TenPT', $phuongThuc->TenPT) }}">
                                    @error('TenPT')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="TenPTFeedback" class="invalid-feedback"></div>
                                    <small class="form-text">Tên phương thức thanh toán không được vượt quá 255 ký tự</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Mota" class="form-label">Mô Tả</label>
                                    <textarea class="form-control @error('Mota') is-invalid @enderror" 
                                        id="Mota" name="Mota" rows="4">{{ old('Mota', $phuongThuc->Mota) }}</textarea>
                                    @error('Mota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">Mô tả ngắn gọn về phương thức thanh toán</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="icon-preview-section">
                                    <h6 class="font-weight-bold text-pink mb-3">Biểu Tượng Phương Thức</h6>
                                    <i id="iconPreview" class="fas"></i>
                                    <p class="text-muted mt-2">Biểu tượng sẽ được tự động cập nhật dựa trên tên phương thức</p>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group-edit">
                                    <button type="button" class="btn btn-outline-pink" onclick="window.history.back();">
                                        <i class="fas fa-times mr-1"></i> Hủy
                                    </button>
                                    <button type="submit" class="btn btn-pink" id="submitBtn">
                                        <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Payment Method Info Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Method Stats -->
            <div class="card payment-stats-card shadow mb-4 animate__animated animate__fadeInRight">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Thống Kê</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 payment-method-item" data-name="{{ $phuongThuc->TenPT }}">
                        <div class="payment-method-icon mb-3">
                            <i class="fas fa-3x"></i>
                        </div>
                        <h4 class="font-weight-bold">{{ $phuongThuc->TenPT }}</h4>
                        <span class="badge badge-pink">Mã: {{ $phuongThuc->MaPT }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="stat-item-pink text-center mb-3">
                        <div class="stat-icon-pink mb-2">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                        <div class="stat-label">Hóa Đơn Sử Dụng</div>
                        <div class="stat-value">{{ $phuongThuc->hoaDon->count() }}</div>
                    </div>
                    
                    @if($phuongThuc->hoaDon->count() > 0)
                        <div class="alert alert-warning rounded-3 mt-4">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Phương thức này đang được sử dụng bởi {{ $phuongThuc->hoaDon->count() }} hóa đơn. Không thể xóa.
                        </div>
                    @else
                        <div class="alert alert-info rounded-3 mt-4">
                            <i class="fas fa-info-circle mr-1"></i> Phương thức này chưa được sử dụng bởi hóa đơn nào.
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('admin.phuongthuc.confirmDestroy', $phuongThuc->MaPT) }}" class="btn btn-outline-pink">
                                <i class="fas fa-trash mr-1"></i> Xóa Phương Thức
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/phuongthuc.js') }}?v={{ time() }}"></script>
<script src="{{ asset('js/admin/phuongthuc/edit.js') }}?v={{ time() }}"></script>
@endsection