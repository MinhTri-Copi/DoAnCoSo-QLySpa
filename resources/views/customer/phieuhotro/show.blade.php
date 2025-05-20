@extends('customer.layouts.app')

@section('title', 'Chi tiết phiếu hỗ trợ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Chi tiết phiếu hỗ trợ</h1>
                <a href="{{ route('customer.phieuhotro.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $phieuHoTro->Tieude }}</h5>
                            <span class="text-muted small">Mã phiếu: {{ $phieuHoTro->MaPhieu }}</span>
                        </div>
                        <div>
                            @if($phieuHoTro->Trangthai == 'Đang xử lý')
                                <span class="badge bg-warning">Đang xử lý</span>
                            @elseif($phieuHoTro->Trangthai == 'Đã hoàn thành')
                                <span class="badge bg-success">Đã hoàn thành</span>
                            @elseif($phieuHoTro->Trangthai == 'Đã hủy')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">{{ $phieuHoTro->Trangthai }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="mb-1 text-muted">Ngày gửi:</p>
                            <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($phieuHoTro->Ngaygui)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Phương thức hỗ trợ:</p>
                            <p class="mb-0 fw-medium">{{ $phieuHoTro->phuongThucHoTro->Ten ?? 'Không xác định' }}</p>
                        </div>
                    </div>

                    <h6 class="mb-3">Nội dung:</h6>
                    <div class="bg-light p-3 rounded mb-4" style="white-space: pre-wrap;">{{ $phieuHoTro->Noidung }}</div>

                    @if($phieuHoTro->Trangthai != 'Đã hủy' && !in_array($phieuHoTro->Trangthai, ['Đã hoàn thành', 'Đã hủy']))
                        <div class="d-flex justify-content-end mt-3 gap-2">
                            @if($phieuHoTro->Trangthai == 'Đang xử lý')
                                <a href="{{ route('customer.phieuhotro.edit', $phieuHoTro->MaPhieu) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="fas fa-times me-2"></i>Hủy phiếu
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($phieuHoTro->Trangthai != 'Đã hủy')
                <!-- Form gửi phản hồi bổ sung -->
                @if(!in_array($phieuHoTro->Trangthai, ['Đã hoàn thành', 'Đã hủy']))
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Gửi thêm phản hồi</h5>
                            <form action="{{ route('customer.phieuhotro.feedback', $phieuHoTro->MaPhieu) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control @error('feedback') is-invalid @enderror" id="feedback" name="feedback" rows="4" placeholder="Nhập phản hồi của bạn..." required>{{ old('feedback') }}</textarea>
                                    @error('feedback')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi phản hồi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal xác nhận hủy -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Xác nhận hủy phiếu hỗ trợ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy phiếu hỗ trợ này không?</p>
                <p class="text-muted">Lưu ý: Bạn sẽ không thể khôi phục lại phiếu hỗ trợ sau khi đã hủy.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <form action="{{ route('customer.phieuhotro.cancel', $phieuHoTro->MaPhieu) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 