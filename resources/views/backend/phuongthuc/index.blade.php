@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}?v={{ time() }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* Inline styles để đảm bảo ghi đè các styles khác */
    .payment-list-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-list-card .card-header {
        background: linear-gradient(40deg, #ff6b95, #ffa7bc) !important;
        color: white !important;
        border-bottom: none !important;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.25rem 1.5rem !important;
    }
    
    .payment-list-card .card-header h6 {
        color: white !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
    }
    
    .payment-method-card {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(255, 107, 149, 0.15) !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease !important;
    }
    
    .payment-method-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 0.5rem 2rem 0 rgba(255, 107, 149, 0.2) !important;
    }
    
    .border-left-primary {
        border-left: 4px solid #ff6b95 !important;
    }
    
    .border-left-success {
        border-left: 4px solid #4caf50 !important;
    }
    
    .text-primary {
        color: #ff6b95 !important;
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
    
    .btn-success {
        background-color: #4caf50 !important;
        border-color: #4caf50 !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem !important;
    }
    
    .btn-info {
        background-color: #56ccf2 !important;
        border-color: #56ccf2 !important;
        color: white !important;
        border-radius: 50px !important;
        padding: 0.375rem 0.75rem !important;
    }
    
    .btn-danger {
        background-color: #f44336 !important;
        border-color: #f44336 !important;
        border-radius: 50px !important;
        padding: 0.375rem 0.75rem !important;
    }
    
    .badge-info {
        background-color: #56ccf2 !important;
        color: white !important;
        padding: 0.5em 1em !important;
        border-radius: 50px !important;
    }
    
    .payment-method-icon {
        color: #ff6b95 !important;
        margin-bottom: 1rem !important;
    }
    
    .payment-method-count {
        background-color: #ff6b95 !important;
    }
    
    .table {
        border-radius: 10px !important;
        overflow: hidden !important;
    }
    
    .table thead {
        background-color: rgba(255, 107, 149, 0.1) !important;
    }
    
    .table thead th {
        color: #ff6b95 !important;
        font-weight: 600 !important;
        border-bottom: none !important;
    }
    
    .table td {
        vertical-align: middle !important;
    }
    
    .form-control:focus {
        border-color: #ff6b95 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 107, 149, 0.25) !important;
    }
    
    /* Header mới cho trang quản lý phương thức thanh toán */
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
    
    /* Filter button group styling */
    .filter-button-group {
        border-radius: 20px !important;
        background: #f5f5f5 !important;
        padding: 3px !important;
        display: inline-flex !important;
        border: 1px solid #e0e0e0 !important;
    }
    
    .filter-button-group .btn {
        border-radius: 20px !important;
        border: none !important;
        margin: 0 1px !important;
        font-size: 12px !important;
        padding: 4px 12px !important;
        background: transparent !important;
        color: #777 !important;
    }
    
    .filter-button-group .btn.active {
        background: #ffffff !important;
        color: #ff6b95 !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Tùy chỉnh ô tìm kiếm để giống với hình mẫu */
    .search-container {
        border-radius: 30px !important;
        background-color: #f5f5f5 !important;
        padding: 5px 15px !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
    }
    
    .search-container input {
        border: none !important;
        background: transparent !important;
        padding: 8px 5px !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    .search-container button {
        border: none !important;
        background: transparent !important;
        color: #ff6b95 !important;
        padding: 5px !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="payment-header animate__animated animate__fadeIn">
        <div>
            <h1>Danh Sách Phương Thức Thanh Toán</h1>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.phuongthuc.create') }}" class="add-payment-btn">
                <i class="fas fa-plus"></i>
                <span>Thêm Phương Thức Mới</span>
            </a>
            <div class="dropdown ml-2">
                <button class="btn btn-light" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Payment Method List Card -->
            <div class="card payment-list-card shadow mb-4 animate__animated animate__fadeIn">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Danh Sách Phương Thức Thanh Toán</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="search-container">
                            <input type="text" placeholder="Tìm kiếm phương thức..." id="searchInput">
                            <button type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    @if($phuongThucs->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-4x text-pink mb-3"></i>
                            <p class="mb-0 text-gray-500">Chưa có phương thức thanh toán nào được tạo</p>
                            <a href="{{ route('admin.phuongthuc.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus mr-1"></i> Thêm Phương Thức Mới
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="paymentMethodsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="80">Mã PT</th>
                                        <th>Tên Phương Thức</th>
                                        <th>Mô Tả</th>
                                        <th width="150">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($phuongThucs as $phuongThuc)
                                        <tr class="payment-method-row {{ $phuongThuc->hoaDon->count() > 0 ? 'has-invoice' : 'no-invoice' }}">
                                            <td>{{ $phuongThuc->MaPT }}</td>
                                            <td>
                                                <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="font-weight-bold text-primary">
                                                    {{ $phuongThuc->TenPT }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($phuongThuc->Mota, 50) }}</td>
                                            <td>
                                                <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.phuongthuc.edit', $phuongThuc->MaPT) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.phuongthuc.confirmDestroy', $phuongThuc->MaPT) }}" class="btn btn-sm btn-danger {{ $phuongThuc->hoaDon->count() > 0 ? 'disabled' : '' }}" data-toggle="tooltip" title="{{ $phuongThuc->hoaDon->count() > 0 ? 'Không thể xóa phương thức đang được sử dụng' : 'Xóa' }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Cards -->
    <div class="row">
        @foreach($phuongThucs as $phuongThuc)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 payment-method-card animate__animated animate__fadeInUp payment-method-item" data-payment-method-id="{{ $phuongThuc->MaPT }}" data-name="{{ $phuongThuc->TenPT }}" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Phương Thức Thanh Toán
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $phuongThuc->TenPT }}</div>
                                <div class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-id-card mr-1"></i> Mã: {{ $phuongThuc->MaPT }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="payment-method-icon">
                                    <i class="fas"></i>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method-count">{{ $phuongThuc->hoaDon->count() }}</div>
                        <div class="mt-3 text-center payment-method-actions">
                            <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye mr-1"></i> Xem
                            </a>
                            <a href="{{ route('admin.phuongthuc.edit', $phuongThuc->MaPT) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit mr-1"></i> Sửa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Add New Payment Method Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 payment-method-card animate__animated animate__fadeInUp" style="animation-delay: {{ count($phuongThucs) * 0.1 }}s">
                <div class="card-body text-center">
                    <div class="payment-method-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Thêm Phương Thức Mới</div>
                    <p class="text-gray-500 mt-2">Tạo phương thức thanh toán mới cho hệ thống</p>
                    <a href="{{ route('admin.phuongthuc.create') }}" class="btn btn-success mt-3">
                        <i class="fas fa-plus mr-1"></i> Tạo Phương Thức
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/phuongthuc.js') }}?v={{ time() }}"></script>
<script src="{{ asset('js/admin/phuongthuc/index.js') }}?v={{ time() }}"></script>
<script>
    $(document).ready(function() {
        // Chức năng tìm kiếm
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#paymentMethodsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection