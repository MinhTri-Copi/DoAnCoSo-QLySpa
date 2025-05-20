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
                                <th>Tiêu đề</th>
                                <th>Phương thức hỗ trợ</th>
                                <th>Ngày gửi</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phieuHoTro as $phieu)
                                <tr>
                                    <td>{{ $phieu->MaPhieu }}</td>
                                    <td>{{ $phieu->Tieude }}</td>
                                    <td>{{ $phieu->phuongThucHoTro->Ten ?? 'Không xác định' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($phieu->Ngaygui)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($phieu->Trangthai == 'Đang xử lý')
                                            <span class="badge bg-warning">Đang xử lý</span>
                                        @elseif($phieu->Trangthai == 'Đã hoàn thành')
                                            <span class="badge bg-success">Đã hoàn thành</span>
                                        @elseif($phieu->Trangthai == 'Đã hủy')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $phieu->Trangthai }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.phieuhotro.show', $phieu->MaPhieu) }}" class="btn btn-sm btn-primary me-1" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($phieu->Trangthai == 'Đang xử lý')
                                            <a href="{{ route('customer.phieuhotro.edit', $phieu->MaPhieu) }}" class="btn btn-sm btn-info me-1" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $phieu->MaPhieu }}" title="Hủy phiếu">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <!-- Modal xác nhận hủy -->
                                            <div class="modal fade" id="cancelModal{{ $phieu->MaPhieu }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $phieu->MaPhieu }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="cancelModalLabel{{ $phieu->MaPhieu }}">Xác nhận hủy phiếu hỗ trợ</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Bạn có chắc chắn muốn hủy phiếu hỗ trợ này không?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            <form action="{{ route('customer.phieuhotro.cancel', $phieu->MaPhieu) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Hủy phiếu</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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