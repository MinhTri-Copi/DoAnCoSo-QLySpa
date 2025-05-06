@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/customers.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Xác Nhận Xóa Khách Hàng</h1>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-arrow-left"></i>
        </span>
        <span class="text">Quay Lại</span>
    </a>
</div>

<!-- Confirmation Card -->
<div class="card shadow mb-4 animate__animated animate__fadeIn">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Cảnh Báo</h6>
    </div>
    <div class="card-body">
        <div class="text-center mb-4">
            <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
            <h4 class="text-danger">Bạn có chắc chắn muốn xóa khách hàng này?</h4>
            <p class="text-muted">
                Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến khách hàng này sẽ bị xóa vĩnh viễn.
            </p>
        </div>
        
        <div class="customer-info p-4 bg-light rounded mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Thông Tin Khách Hàng</h5>
                    <div class="info-item">
                        <span class="info-label">Mã Khách Hàng:</span>
                        <span class="info-value">{{ $customer->Manguoidung }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Họ Tên:</span>
                        <span class="info-value">{{ $customer->Hoten }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $customer->Email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Số Điện Thoại:</span>
                        <span class="info-value">{{ $customer->SDT }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Dữ Liệu Liên Quan</h5>
                    <div class="info-item">
                        <span class="info-label">Đơn Hàng:</span>
                        <span class="info-value">{{ $customer->hoaDon->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Lịch Hẹn:</span>
                        <span class="info-value">{{ $customer->datLich->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Đánh Giá:</span>
                        <span class="info-value">{{ $customer->danhGia->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phiếu Hỗ Trợ:</span>
                        <span class="info-value">{{ $customer->phieuHoTro->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-danger">
            <i class="fas fa-info-circle mr-1"></i> Lưu ý: Việc xóa khách hàng sẽ ảnh hưởng đến các dữ liệu liên quan như đơn hàng, lịch hẹn, đánh giá và điểm thưởng.
        </div>
        
        <form action="{{ route('admin.customers.destroy', $customer->Manguoidung) }}" method="POST" id="deleteCustomerForm">
            @csrf
            @method('DELETE')
            
            <div class="form-group">
                <label for="confirmDelete">Nhập "XÓA" để xác nhận:</label>
                <input type="text" class="form-control" id="confirmDelete" placeholder="XÓA">
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-lg mr-2">
                    <i class="fas fa-times mr-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-danger btn-lg" id="deleteBtn" disabled>
                    <i class="fas fa-trash mr-1"></i> Xóa Khách Hàng
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/customers.js') }}"></script>
<script src="{{ asset('js/admin/customers/destroy.js') }}"></script>
@endsection