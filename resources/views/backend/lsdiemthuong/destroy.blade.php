@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Lịch Sử Điểm Thưởng')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none; background-color: #ff99b9;">
        <div class="card-body py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-white">
                        <i class="fas fa-trash me-2"></i> Xác Nhận Xóa
                    </h1>
                    <p class="text-white mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i> Xác nhận xóa lịch sử điểm thưởng
                    </p>
                </div>
                <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-light rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Xác nhận xóa -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
        <div class="card-body">
            <div class="text-center mb-4">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
                <h2 class="mt-3">Bạn có chắc chắn muốn xóa?</h2>
                <p class="text-muted">
                    Bạn đang chuẩn bị xóa lịch sử điểm thưởng <strong>#{{ $lsDiemThuong->MaLSDT }}</strong>. 
                    Hành động này không thể hoàn tác.
                </p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card mb-4" style="border-radius: 10px; background-color: #f8f9fc;">
                        <div class="card-body">
                            <h5 class="card-title">Thông tin lịch sử điểm thưởng</h5>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Mã lịch sử điểm thưởng</th>
                                        <td>{{ $lsDiemThuong->MaLSDT }}</td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian</th>
                                        <td>{{ \Carbon\Carbon::parse($lsDiemThuong->Thoigian)->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Số điểm</th>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($lsDiemThuong->Sodiem) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Người dùng</th>
                                        <td>{{ $lsDiemThuong->user->Hoten ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Hóa đơn</th>
                                        <td>
                                            @if($lsDiemThuong->MaHD)
                                                HD-{{ $lsDiemThuong->MaHD }}
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <form action="{{ route('admin.lsdiemthuong.destroy', $lsDiemThuong->MaLSDT) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Xác nhận xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
