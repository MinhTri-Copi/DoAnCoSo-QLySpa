@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản Lý Phương Thức Thanh Toán</h1>
        <a href="{{ route('admin.phuongthuc.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Thêm Phương Thức Mới</span>
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Payment Method List Card -->
            <div class="card shadow mb-4 animate__animated animate__fadeIn">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Danh Sách Phương Thức Thanh Toán</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.phuongthuc.create') }}">
                                <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                                Thêm Phương Thức Mới
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="exportPaymentMethods">
                                <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                                Xuất Danh Sách
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm mr-3" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Tìm kiếm phương thức..." id="searchInput">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <select class="form-control form-control-sm mr-2" id="bulkAction">
                                    <option value="">-- Hành động --</option>
                                    <option value="delete">Xóa đã chọn</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary" id="applyBulkAction">Áp dụng</button>
                            </div>
                        </div>
                        <div>
                            <span class="mr-2 text-xs">Hiển thị:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active">Tất cả</button>
                                <button type="button" class="btn btn-outline-primary">Có hóa đơn</button>
                                <button type="button" class="btn btn-outline-primary">Không có hóa đơn</button>
                            </div>
                        </div>
                    </div>

                    <form id="bulkActionForm" action="{{ route('admin.phuongthuc.index') }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionType" value="">
                        
                        @if($phuongThucs->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-credit-card fa-4x text-gray-300 mb-3"></i>
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
                                            <th width="40">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                                    <label class="custom-control-label" for="selectAll"></label>
                                                </div>
                                            </th>
                                            <th width="80">Mã PT</th>
                                            <th>Tên Phương Thức</th>
                                            <th>Mô Tả</th>
                                            <th width="120">Số Hóa Đơn</th>
                                            <th width="150">Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($phuongThucs as $phuongThuc)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="phuongThuc{{ $phuongThuc->MaPT }}" name="selectedPaymentMethods[]" value="{{ $phuongThuc->MaPT }}">
                                                        <label class="custom-control-label" for="phuongThuc{{ $phuongThuc->MaPT }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $phuongThuc->MaPT }}</td>
                                                <td>
                                                    <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="font-weight-bold text-primary">
                                                        {{ $phuongThuc->TenPT }}
                                                    </a>
                                                </td>
                                                <td>{{ Str::limit($phuongThuc->Mota, 50) }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $phuongThuc->hoaDon->count() }}</span>
                                                </td>
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
                    </form>
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
<script src="{{ asset('js/admin/phuongthuc.js') }}"></script>
<script src="{{ asset('js/admin/phuongthuc/index.js') }}"></script>
@endsection