@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/phuongthuc.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh Sửa Phương Thức Thanh Toán</h1>
        <div>
            <a href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}" class="btn btn-info btn-icon-split mr-2">
                <span class="icon text-white-50">
                    <i class="fas fa-eye"></i>
                </span>
                <span class="text">Xem Chi Tiết</span>
            </a>
            <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Quay Lại</span>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Payment Method Edit Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4 animate__animated animate__fadeIn">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Phương Thức Thanh Toán</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.phuongthuc.show', $phuongThuc->MaPT) }}">
                                <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i>
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
                                    <label for="MaPT">Mã Phương Thức</label>
                                    <input type="text" class="form-control" id="MaPT" value="{{ $phuongThuc->MaPT }}" readonly>
                                    <small class="form-text text-muted">Mã phương thức không thể thay đổi</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TenPT">Tên Phương Thức <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('TenPT') is-invalid @enderror" 
                                        id="TenPT" name="TenPT" value="{{ old('TenPT', $phuongThuc->TenPT) }}">
                                    @error('TenPT')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="TenPTFeedback" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">Tên phương thức thanh toán không được vượt quá 255 ký tự</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Mota">Mô Tả</label>
                                    <textarea class="form-control @error('Mota') is-invalid @enderror" 
                                        id="Mota" name="Mota" rows="4">{{ old('Mota', $phuongThuc->Mota) }}</textarea>
                                    @error('Mota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Mô tả ngắn gọn về phương thức thanh toán</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <h6 class="font-weight-bold text-primary mb-3">Biểu Tượng Phương Thức</h6>
                                <i id="iconPreview" class="fas"></i>
                                <p class="text-muted mt-2">Biểu tượng sẽ được tự động cập nhật dựa trên tên phương thức</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Hủy</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Payment Method Info Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Method Stats -->
            <div class="card shadow mb-4 animate__animated animate__fadeInRight">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống Kê</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 payment-method-item" data-name="{{ $phuongThuc->TenPT }}">
                        <div class="payment-method-icon mb-3">
                            <i class="fas fa-3x"></i>
                        </div>
                        <h4 class="font-weight-bold">{{ $phuongThuc->TenPT }}</h4>
                        <span class="badge badge-primary">Mã: {{ $phuongThuc->MaPT }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="stat-item text-center mb-3">
                        <div class="stat-icon mb-2">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                        <div class="stat-label">Hóa Đơn Sử Dụng</div>
                        <div class="stat-value">{{ $phuongThuc->hoaDon->count() }}</div>
                    </div>
                    
                    @if($phuongThuc->hoaDon->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Phương thức này đang được sử dụng bởi {{ $phuongThuc->hoaDon->count() }} hóa đơn. Không thể xóa.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Phương thức này chưa được sử dụng bởi hóa đơn nào.
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.phuongthuc.confirmDestroy', $phuongThuc->MaPT) }}" class="btn btn-danger">
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
<script src="{{ asset('js/admin/phuongthuc.js') }}"></script>
<script src="{{ asset('js/admin/phuongthuc/edit.js') }}"></script>
@endsection