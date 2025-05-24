@extends('customer.layouts.app')

@section('title', 'Phiếu hỗ trợ')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Phiếu hỗ trợ của bạn</h1>
        <a href="{{ route('customer.phieuhotro.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Tạo phiếu hỗ trợ mới
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

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(count($phieuHoTro) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Phương thức hỗ trợ</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phieuHoTro as $phieu)
                                <tr>
                                    <td>{{ $phieu->MaphieuHT }}</td>
                                    <td>{{ $phieu->ptHoTro->TenPT ?? 'Không xác định' }}</td>
                                    <td>
                                        @if($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đang xử lý')
                                            <span class="badge bg-warning">Đang xử lý</span>
                                        @elseif($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đã hoàn thành')
                                            <span class="badge bg-success">Đã hoàn thành</span>
                                        @elseif($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đã hủy')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $phieu->trangThai->Tentrangthai ?? '' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($phieu->MaphieuHT)
                                            <a href="{{ route('customer.phieuhotro.show', $phieu->MaphieuHT) }}" class="btn btn-sm btn-primary me-1" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($phieu->trangThai && $phieu->trangThai->Tentrangthai == 'Đang xử lý')
                                                <a href="{{ route('customer.phieuhotro.edit', $phieu->MaphieuHT) }}" class="btn btn-sm btn-info me-1" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('customer.phieuhotro.confirm-destroy', $phieu->MaphieuHT) }}" class="btn btn-sm btn-danger" title="Xoá phiếu">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $phieuHoTro->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <p class="lead">Bạn chưa có phiếu hỗ trợ nào.</p>
                    <a href="{{ route('customer.phieuhotro.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Tạo phiếu hỗ trợ mới
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 