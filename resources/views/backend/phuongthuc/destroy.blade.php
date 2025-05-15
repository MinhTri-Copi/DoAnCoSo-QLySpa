@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}?v={{ time() }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* Inline styles để đảm bảo ghi đè các styles khác */
    .payment-delete-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-delete-card .card-header {
        background: linear-gradient(40deg, #ff6b95, #ffa7bc) !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem 1.5rem !important;
    }
    
    .payment-delete-card .card-header h6 {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
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
    
    .btn-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-danger {
        background-color: #f44336 !important;
        border-color: #f44336 !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-method-info {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.5rem rgba(255, 107, 149, 0.1) !important;
    }
    
    .info-label {
        color: #ff6b95 !important;
        font-weight: 600 !important;
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

    <!-- Tiêu đề trang xóa -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Xác Nhận Xóa Phương Thức Thanh Toán</h1>
        <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Quay Lại</span>
        </a>
    </div>

    <!-- Confirmation Card -->
    <div class="card payment-delete-card shadow mb-4 animate__animated animate__fadeIn">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Cảnh Báo</h6>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                <h4 class="text-danger">Bạn có chắc chắn muốn xóa phương thức thanh toán này?</h4>
                <p class="text-muted">
                    Hành động này không thể hoàn tác. Phương thức thanh toán sẽ bị xóa vĩnh viễn khỏi hệ thống.
                </p>
            </div>
            
            <div class="payment-method-info p-4 bg-light rounded mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông Tin Phương Thức</h5>
                        <div class="info-item">
                            <span class="info-label">Mã Phương Thức:</span>
                            <span class="info-value">{{ $phuongThuc->MaPT }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tên Phương Thức:</span>
                            <span class="info-value">{{ $phuongThuc->TenPT }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mô Tả:</span>
                            <span class="info-value">{{ $phuongThuc->Mota ?: 'Không có mô tả' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Dữ Liệu Liên Quan</h5>
                        <div class="info-item">
                            <span class="info-label">Số Hóa Đơn:</span>
                            <span class="info-value">{{ $phuongThuc->hoaDon->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($phuongThuc->hoaDon->count() > 0)
                <div class="alert alert-danger">
                    <i class="fas fa-ban mr-1"></i> Không thể xóa phương thức thanh toán này vì đang có {{ $phuongThuc->hoaDon->count() }} hóa đơn sử dụng.
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-1"></i> Quay Lại
                    </a>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i> Phương thức thanh toán này chưa được sử dụng bởi hóa đơn nào và có thể xóa an toàn.
                </div>
                
                <form action="{{ route('admin.phuongthuc.destroy', $phuongThuc->MaPT) }}" method="POST" id="deletePaymentMethodForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="form-group">
                        <label for="confirmDelete">Nhập "XÓA" để xác nhận:</label>
                        <input type="text" class="form-control" id="confirmDelete" placeholder="XÓA">
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-times mr-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-danger btn-lg" id="deleteBtn" disabled>
                            <i class="fas fa-trash mr-1"></i> Xóa Phương Thức
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/phuongthuc.js') }}"></script>
<script src="{{ asset('js/admin/phuongthuc/destroy.js') }}"></script>
@endsection