@extends('backend.layouts.app')

@section('title', 'Thêm Lịch Sử Điểm Thưởng')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none; background-color: #ff99b9;">
        <div class="card-body py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-white">
                        <i class="fas fa-gift me-2"></i> Thêm Điểm Thưởng
                    </h1>
                    <p class="text-white mb-0">
                        <i class="fas fa-info-circle me-1"></i> Thêm mới lịch sử điểm thưởng cho khách hàng
                    </p>
                </div>
                <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-light rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form thêm mới -->
    <div class="card shadow mb-4" style="border-radius: 15px; border: none;">
        <div class="card-body">
            <form action="{{ route('admin.lsdiemthuong.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="MaLSDT" class="form-label">Mã lịch sử điểm thưởng</label>
                        <input type="number" class="form-control @error('MaLSDT') is-invalid @enderror" id="MaLSDT" name="MaLSDT" value="{{ $suggestedMaLSDT }}" readonly>
                        @error('MaLSDT')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mã được tạo tự động</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="Thoigian" class="form-label">Thời gian</label>
                        <input type="datetime-local" class="form-control @error('Thoigian') is-invalid @enderror" id="Thoigian" name="Thoigian" value="{{ old('Thoigian', now()->format('Y-m-d\TH:i')) }}">
                        @error('Thoigian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="Sodiem" class="form-label">Số điểm</label>
                        <input type="number" class="form-control @error('Sodiem') is-invalid @enderror" id="Sodiem" name="Sodiem" value="{{ old('Sodiem', 100) }}">
                        @error('Sodiem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="Manguoidung" class="form-label">Người dùng</label>
                        <select class="form-select @error('Manguoidung') is-invalid @enderror" id="Manguoidung" name="Manguoidung" required>
                            <option value="">Chọn người dùng</option>
                            @foreach($users as $user)
                                <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                                    {{ $user->Hoten }} ({{ $user->SDT }})
                                </option>
                            @endforeach
                        </select>
                        @error('Manguoidung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="MaHD" class="form-label">Hóa đơn (tùy chọn)</label>
                        <select class="form-select @error('MaHD') is-invalid @enderror" id="MaHD" name="MaHD">
                            <option value="">Không liên kết với hóa đơn</option>
                            @foreach($hoaDons as $hoaDon)
                                <option value="{{ $hoaDon->MaHD }}" {{ old('MaHD') == $hoaDon->MaHD ? 'selected' : '' }}>
                                    HD-{{ $hoaDon->MaHD }} ({{ number_format($hoaDon->Tongtien) }} VND)
                                </option>
                            @endforeach
                        </select>
                        @error('MaHD')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thông tin người dùng</label>
                        <div class="card" id="userInfo" style="display: none; border-radius: 10px;">
                            <div class="card-body">
                                <div id="userInfoContent">
                                    <p class="mb-1"><strong>Họ tên:</strong> <span id="userName">-</span></p>
                                    <p class="mb-1"><strong>SĐT:</strong> <span id="userPhone">-</span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span id="userEmail">-</span></p>
                                    <p class="mb-0"><strong>Tổng điểm hiện tại:</strong> <span id="userTotalPoints">-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                        <small class="text-muted">Ghi chú này chỉ hiển thị trong trang quản trị, không hiển thị cho khách hàng</small>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary" style="background-color: #ff99b9; border-color: #ff99b9;">
                        <i class="fas fa-save me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo select2 cho dropdown
        $('.form-select').select2({
            theme: 'bootstrap4',
            placeholder: 'Chọn...',
            allowClear: true
        });
        
        // Lấy thông tin người dùng khi chọn
        $('#Manguoidung').change(function() {
            const userId = $(this).val();
            if (userId) {
                $.ajax({
                    url: `/admin/lsdiemthuong/get-user-details/${userId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const user = response.data;
                            $('#userName').text(user.Hoten || '-');
                            $('#userPhone').text(user.SDT || '-');
                            $('#userEmail').text(user.Email || '-');
                            $('#userTotalPoints').text(response.total_points || '0');
                            $('#userInfo').show();
                        }
                    },
                    error: function() {
                        $('#userInfo').hide();
                    }
                });
            } else {
                $('#userInfo').hide();
            }
        });
        
        // Lấy thông tin hóa đơn khi chọn
        $('#MaHD').change(function() {
            const invoiceId = $(this).val();
            if (invoiceId) {
                $.ajax({
                    url: `/admin/lsdiemthuong/get-invoice-details/${invoiceId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const invoice = response.data;
                            // Tự động chọn người dùng từ hóa đơn
                            if (invoice.Manguoidung) {
                                $('#Manguoidung').val(invoice.Manguoidung).trigger('change');
                            }
                            
                            // Tính điểm thưởng dựa trên tổng tiền
                            let points = 0;
                            const totalAmount = invoice.Tongtien;
                            
                            if (totalAmount >= 100000 && totalAmount < 500000) {
                                points = 100;
                            } else if (totalAmount >= 500000 && totalAmount < 1000000) {
                                points = 300;
                            } else if (totalAmount >= 1000000) {
                                points = 500;
                            }
                            
                            $('#Sodiem').val(points);
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
