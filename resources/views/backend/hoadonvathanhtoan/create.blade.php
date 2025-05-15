@extends('backend.layouts.app')

@section('title', 'Thêm Hóa Đơn Mới')

@section('content')
<style>
    :root {
        --primary-color: #ff6b8b;
        --primary-light: #ffd0d9;
        --primary-dark: #e84e6f;
        --text-on-primary: #ffffff;
        --secondary-color: #f8f9fa;
        --border-color: #e9ecef;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
    }

    .header-container {
        background-color: var(--primary-color);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        color: var(--text-on-primary);
    }

    .header-title {
        font-size: 24px;
        font-weight: bold;
    }

    .header-subtitle {
        font-size: 14px;
        margin-top: 5px;
        opacity: 0.9;
    }

    .content-card {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .form-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23495057' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px 12px;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #6c757d;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 12px;
        color: var(--danger-color);
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    .booking-info {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px dashed var(--border-color);
    }

    .booking-info-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .booking-info-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .booking-detail {
        display: flex;
        margin-bottom: 8px;
    }

    .booking-detail-label {
        width: 120px;
        font-weight: 500;
        color: #6c757d;
    }

    .booking-detail-value {
        flex: 1;
        color: #495057;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
    }

    .form-check-input {
        margin-right: 10px;
    }

    .form-check-label {
        font-size: 14px;
        color: #495057;
    }

    @media (max-width: 768px) {
        .btn-container {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>

<div class="header-container">
    <div class="header-title">Thêm Hóa Đơn Mới</div>
    <div class="header-subtitle">Tạo hóa đơn thanh toán mới</div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-file-invoice-dollar"></i> Thông Tin Hóa Đơn
        </div>
    </div>
    
    @if($errors->any())
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <ul style="margin-bottom: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
    @endif
    
    <form action="{{ route('admin.hoadonvathanhtoan.store') }}" method="POST" id="invoiceForm">
        @csrf
        
        <div class="form-group">
            <label for="MaHD" class="form-label">Mã Hóa Đơn</label>
            <input type="text" class="form-control" id="MaHD" name="MaHD" value="{{ old('MaHD', $suggestedMaHD) }}" readonly>
            <small class="form-text">Mã hóa đơn được tạo tự động.</small>
        </div>
        
        <div class="form-group">
            <label for="MaDL" class="form-label">Đặt Lịch <span class="text-danger">*</span></label>
            <select class="form-select @error('MaDL') is-invalid @enderror" id="MaDL" name="MaDL" required>
                <option value="">-- Chọn lịch đặt --</option>
                @foreach($datLichs as $datLich)
                    <option value="{{ $datLich->MaDL }}" data-user="{{ $datLich->Manguoidung }}" {{ old('MaDL') == $datLich->MaDL ? 'selected' : '' }}>
                        {{ $datLich->MaDL }} - {{ optional($datLich->dichVu)->Tendichvu ?? 'N/A' }} ({{ \Carbon\Carbon::parse($datLich->Thoigiandatlich)->format('d/m/Y H:i') }})
                    </option>
                @endforeach
            </select>
            @error('MaDL')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="booking-info" id="bookingInfo" style="display: none;">
            <div class="booking-info-title">
                <i class="fas fa-info-circle"></i> Thông Tin Đặt Lịch
            </div>
            <div class="booking-detail">
                <div class="booking-detail-label">Dịch vụ:</div>
                <div class="booking-detail-value" id="serviceName">-</div>
            </div>
            <div class="booking-detail">
                <div class="booking-detail-label">Thời gian:</div>
                <div class="booking-detail-value" id="bookingTime">-</div>
            </div>
            <div class="booking-detail">
                <div class="booking-detail-label">Giá dịch vụ:</div>
                <div class="booking-detail-value" id="servicePrice">-</div>
            </div>
            <div class="booking-detail">
                <div class="booking-detail-label">Trạng thái:</div>
                <div class="booking-detail-value" id="bookingStatus">-</div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="update_booking_status" name="update_booking_status" value="1" checked>
                <label class="form-check-label" for="update_booking_status">
                    Cập nhật trạng thái đặt lịch thành "Hoàn thành" sau khi tạo hóa đơn
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="Manguoidung" class="form-label">Người Dùng <span class="text-danger">*</span></label>
            <select class="form-select @error('Manguoidung') is-invalid @enderror" id="Manguoidung" name="Manguoidung" required>
                <option value="">-- Chọn người dùng --</option>
                @foreach($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->Hoten }} ({{ $user->SDT ?? 'Không có SĐT' }})
                    </option>
                @endforeach
            </select>
            @error('Manguoidung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Maphong" class="form-label">Phòng <span class="text-danger">*</span></label>
            <select class="form-select @error('Maphong') is-invalid @enderror" id="Maphong" name="Maphong" required>
                <option value="">-- Chọn phòng --</option>
                @foreach($phongs as $phong)
                    <option value="{{ $phong->Maphong }}" {{ old('Maphong') == $phong->Maphong ? 'selected' : '' }}>
                        {{ $phong->Tenphong }} ({{ $phong->Loaiphong }})
                    </option>
                @endforeach
            </select>
            @error('Maphong')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Ngaythanhtoan" class="form-label">Ngày Thanh Toán <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('Ngaythanhtoan') is-invalid @enderror" id="Ngaythanhtoan" name="Ngaythanhtoan" value="{{ old('Ngaythanhtoan', now()->format('Y-m-d\TH:i')) }}" required>
            @error('Ngaythanhtoan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Tongtien" class="form-label">Tổng Tiền (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('Tongtien') is-invalid @enderror" id="Tongtien" name="Tongtien" value="{{ old('Tongtien', 0) }}" min="0" required>
            @error('Tongtien')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text">Điểm thưởng sẽ được tự động tính dựa trên tổng tiền.</small>
        </div>
        
        <div class="form-group">
            <label for="MaPT" class="form-label">Phương Thức Thanh Toán <span class="text-danger">*</span></label>
            <select class="form-select @error('MaPT') is-invalid @enderror" id="MaPT" name="MaPT" required>
                <option value="">-- Chọn phương thức thanh toán --</option>
                @foreach($phuongThucs as $phuongThuc)
                    <option value="{{ $phuongThuc->MaPT }}" {{ old('MaPT') == $phuongThuc->MaPT ? 'selected' : '' }}>
                        {{ $phuongThuc->TenPT }}
                    </option>
                @endforeach
            </select>
            @error('MaPT')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Matrangthai" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
            <select class="form-select @error('Matrangthai') is-invalid @enderror" id="Matrangthai" name="Matrangthai" required>
                <option value="">-- Chọn trạng thái --</option>
                @foreach($trangThais as $trangThai)
                    <option value="{{ $trangThai->Matrangthai }}" {{ old('Matrangthai') == $trangThai->Matrangthai ? 'selected' : '' }}>
                        {{ $trangThai->Tentrangthai }}
                    </option>
                @endforeach
            </select>
            @error('Matrangthai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="btn-container">
            <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu Hóa Đơn</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const datLichSelect = document.getElementById('MaDL');
    const userSelect = document.getElementById('Manguoidung');
    const bookingInfo = document.getElementById('bookingInfo');
    const serviceName = document.getElementById('serviceName');
    const bookingTime = document.getElementById('bookingTime');
    const servicePrice = document.getElementById('servicePrice');
    const bookingStatus = document.getElementById('bookingStatus');
    const tongTienInput = document.getElementById('Tongtien');
    
    // Cập nhật thông tin đặt lịch khi chọn
    datLichSelect.addEventListener('change', function() {
        if (this.value) {
            // Hiển thị loading
            bookingInfo.style.display = 'block';
            serviceName.textContent = 'Đang tải...';
            bookingTime.textContent = 'Đang tải...';
            servicePrice.textContent = 'Đang tải...';
            bookingStatus.textContent = 'Đang tải...';
            
            // Gọi API để lấy thông tin chi tiết về đặt lịch
            fetch(`/admin/api/datlich/${this.value}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        const booking = data.data;
                        
                        // Log dữ liệu để debug
                        console.log('Booking data:', booking);
                        
                        serviceName.textContent = booking.dich_vu ? booking.dich_vu.Tendichvu : 'N/A';
                        bookingTime.textContent = new Date(booking.Thoigiandatlich).toLocaleString('vi-VN');
                        bookingStatus.textContent = booking.Trangthai_;
                        
                        // Hiển thị giá dịch vụ và cập nhật tổng tiền
                        if (booking.dich_vu && booking.dich_vu.Gia) {
                            // Đảm bảo rằng giá là số
                            let serviceGia = parseFloat(booking.dich_vu.Gia);
                            console.log('Service price:', serviceGia);
                            
                            if (!isNaN(serviceGia)) {
                                // Hiển thị giá dịch vụ đã định dạng
                                servicePrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(serviceGia);
                                
                                // Cập nhật tổng tiền
                                tongTienInput.value = serviceGia;
                            } else {
                                servicePrice.textContent = 'Không xác định';
                            }
                        } else {
                            servicePrice.textContent = 'N/A';
                        }
                        
                        // Cập nhật người dùng
                        if (booking.Manguoidung) {
                            userSelect.value = booking.Manguoidung;
                        }
                    } else {
                        serviceName.textContent = 'Không thể tải dữ liệu';
                        bookingTime.textContent = 'Không thể tải dữ liệu';
                        servicePrice.textContent = 'Không thể tải dữ liệu';
                        bookingStatus.textContent = 'Không thể tải dữ liệu';
                        console.error('API error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching booking details:', error);
                    serviceName.textContent = 'Lỗi kết nối';
                    bookingTime.textContent = 'Lỗi kết nối';
                    servicePrice.textContent = 'Lỗi kết nối';
                    bookingStatus.textContent = 'Lỗi kết nối';
                });
        } else {
            bookingInfo.style.display = 'none';
            tongTienInput.value = 0;
        }
    });
    
    // Khởi tạo giá trị ban đầu nếu đã có
    if (datLichSelect.value) {
        datLichSelect.dispatchEvent(new Event('change'));
    }
    
    // Cập nhật người dùng khi chọn đặt lịch
    datLichSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.user) {
            userSelect.value = selectedOption.dataset.user;
        }
    });
});
</script>
@endsection

### 3. View Edit (Chỉnh sửa hóa đơn)
