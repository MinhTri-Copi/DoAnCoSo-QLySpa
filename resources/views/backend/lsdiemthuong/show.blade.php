@extends('backend.layouts.app')

@section('title', 'Chi Tiết Lịch Sử Điểm Thưởng')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none; background-color: #ff99b9;">
        <div class="card-body py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-white">
                        <i class="fas fa-gift me-2"></i> Chi Tiết Điểm Thưởng #{{ $lsDiemThuong->MaLSDT }}
                    </h1>
                    <p class="text-white mb-0">
                        <i class="fas fa-info-circle me-1"></i> Xem chi tiết lịch sử điểm thưởng
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.lsdiemthuong.edit', $lsDiemThuong->MaLSDT) }}" class="btn btn-warning rounded-pill me-2">
                        <i class="fas fa-edit me-1"></i> Sửa
                    </a>
                    <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-light rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin chính -->
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Điểm Thưởng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                        <span class="badge bg-success" style="font-size: 16px;">
                                            {{ number_format($lsDiemThuong->Sodiem) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Người dùng</th>
                                    <td>
                                        @if($lsDiemThuong->user)
                                            <a href="{{ route('admin.users.show', $lsDiemThuong->Manguoidung) }}">
                                                {{ $lsDiemThuong->user->Hoten }}
                                            </a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hóa đơn</th>
                                    <td>
                                        @if($lsDiemThuong->hoaDon)
                                            <a href="{{ route('admin.hoadonvathanhtoan.show', $lsDiemThuong->MaHD) }}">
                                                HD-{{ $lsDiemThuong->MaHD }}
                                            </a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ \Carbon\Carbon::parse($lsDiemThuong->created_at)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật</th>
                                    <td>{{ \Carbon\Carbon::parse($lsDiemThuong->updated_at)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            @if($lsDiemThuong->hoaDon)
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Hóa Đơn</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">Mã hóa đơn</th>
                                    <td>{{ $lsDiemThuong->hoaDon->MaHD }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày thanh toán</th>
                                    <td>{{ \Carbon\Carbon::parse($lsDiemThuong->hoaDon->Ngaythanhtoan)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền</th>
                                    <td>{{ number_format($lsDiemThuong->hoaDon->Tongtien) }} VND</td>
                                </tr>
                                <tr>
                                    <th>Phương thức thanh toán</th>
                                    <td>{{ $lsDiemThuong->hoaDon->phuongThuc->Tenphuongthuc ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge bg-{{ $lsDiemThuong->hoaDon->Matrangthai == 1 ? 'success' : 'warning' }}">
                                            {{ $lsDiemThuong->hoaDon->trangThai->Tentrangthai ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Thông tin người dùng -->
        <div class="col-lg-4">
            @if($lsDiemThuong->user)
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Người Dùng</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset('assets/img/user-avatar.png') }}" class="img-profile rounded-circle" style="width: 100px; height: 100px;">
                        <h5 class="mt-3 mb-0">{{ $lsDiemThuong->user->Hoten }}</h5>
                        <p class="text-muted">{{ $lsDiemThuong->user->Email }}</p>
                    </div>
                    <hr>
                    <div class="user-details">
                        <p><strong><i class="fas fa-phone me-2"></i> Số điện thoại:</strong> {{ $lsDiemThuong->user->SDT }}</p>
                        <p><strong><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ:</strong> {{ $lsDiemThuong->user->DiaChi }}</p>
                        <p><strong><i class="fas fa-birthday-cake me-2"></i> Ngày sinh:</strong> {{ \Carbon\Carbon::parse($lsDiemThuong->user->Ngaysinh)->format('d/m/Y') }}</p>
                        <p><strong><i class="fas fa-venus-mars me-2"></i> Giới tính:</strong> {{ $lsDiemThuong->user->Gioitinh == 1 ? 'Nam' : 'Nữ' }}</p>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('admin.users.show', $lsDiemThuong->Manguoidung) }}" class="btn btn-primary btn-sm" style="background-color: #ff99b9; border-color: #ff99b9;">
                            <i class="fas fa-user me-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-3" style="background-color: #fff; border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-primary">Tổng Điểm Thưởng</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="display-4 font-weight-bold" style="color: #ff99b9;">
                            {{ number_format(App\Models\LSDiemThuong::where('Manguoidung', $lsDiemThuong->Manguoidung)->sum('Sodiem')) }}
                        </h1>
                        <p class="text-muted">Tổng điểm tích lũy</p>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('admin.lsdiemthuong.index', ['user_id' => $lsDiemThuong->Manguoidung]) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-history me-1"></i> Xem lịch sử điểm
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
