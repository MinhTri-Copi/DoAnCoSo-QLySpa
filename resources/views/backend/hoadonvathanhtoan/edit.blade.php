@extends('backend.layouts.app')

@section('title', 'Chỉnh Sửa Hóa Đơn')

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

    .form-control:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
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

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
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
    <div class="header-title">Chỉnh Sửa Hóa Đơn</div>
    <div class="header-subtitle">Cập nhật thông tin hóa đơn</div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-edit"></i> Thông Tin Hóa Đơn
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
    
    <form action="{{ route('admin.hoadonvathanhtoan.update', $hoaDon->MaHD) }}" method="POST" id="invoiceForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="MaHD" class="form-label">Mã Hóa Đơn</label>
            <input type="text" class="form-control" id="MaHD" value="{{ $hoaDon->MaHD }}" disabled>
            <small class="form-text">Mã hóa đơn không thể thay đổi.</small>
        </div>
        
        <div class="form-group">
            <label for="MaDL" class="form-label">Đặt Lịch <span class="text-danger">*</span></label>
            <select class="form-select @error('MaDL') is-invalid @enderror" id="MaDL" name="MaDL" required>
                <option value="">-- Chọn lịch đặt --</option>
                @foreach($datLichs as $datLich)
                    <option value="{{ $datLich->MaDL }}" data-user="{{ $datLich->Manguoidung }}" {{ old('MaDL', $hoaDon->MaDL) == $datLich->MaDL ? 'selected' : '' }}>
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
            <div id="guest-info" style="margin-top: 10px; padding: 10px; background-color: #fff8e1; border-left: 3px solid #ffb300; border-radius: 5px; display: none;">
                <div style="font-weight: 600; color: #f57c00; margin-bottom: 5px;">
                    <i class="fas fa-user-tag mr-2"></i> Thông tin khách vãng lai
                </div>
                <div style="color: #555; font-size: 0.9rem;">
                    <div style="margin-bottom: 5px;">
                        <strong>Họ tên:</strong> <span id="guestNameValue">-</span>
                    </div>
                    <div>
                        <strong>Số điện thoại:</strong> <span id="guestPhoneValue">-</span>
                    </div>
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="update_booking_status" name="update_booking_status" value="1">
                <label class="form-check-label" for="update_booking_status">
                    Cập nhật trạng thái đặt lịch thành "Hoàn thành"
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label for="Manguoidung" class="form-label">Người Dùng</label>
            <select class="form-select @error('Manguoidung') is-invalid @enderror" id="Manguoidung" name="Manguoidung">
                <option value="">-- Khách vãng lai --</option>
                @foreach($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung', $hoaDon->Manguoidung) == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->Hoten }} ({{ $user->SDT ?? 'Không có SĐT' }})
                    </option>
                @endforeach
            </select>
            @error('Manguoidung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Để trống nếu là khách vãng lai. Thông tin khách vãng lai sẽ được lấy từ đặt lịch.</small>
        </div>
        
        <div class="form-group">
            <label for="Maphong" class="form-label">Phòng <span class="text-danger">*</span></label>
            <select class="form-select @error('Maphong') is-invalid @enderror" id="Maphong" name="Maphong" required>
                <option value="">-- Chọn phòng --</option>
                @foreach($phongs as $phong)
                    <option value="{{ $phong->Maphong }}" {{ old('Maphong', $hoaDon->Maphong) == $phong->Maphong ? 'selected' : '' }}>
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
            <input type="datetime-local" class="form-control @error('Ngaythanhtoan') is-invalid @enderror" id="Ngaythanhtoan" name="Ngaythanhtoan" value="{{ old('Ngaythanhtoan', \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('Y-m-d\TH:i')) }}" required>
            @error('Ngaythanhtoan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Tongtien" class="form-label">Tổng Tiền (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('Tongtien') is-invalid @enderror" id="Tongtien" name="Tongtien" value="{{ old('Tongtien', $hoaDon->Tongtien) }}" min="0" required readonly>
            @error('Tongtien')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="discount-info" class="mt-2" style="display: none;">
                <div class="alert alert-info py-1 px-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small><i class="fas fa-tag me-1"></i> <span id="discount-text">Giảm giá 0%</span></small>
                        </div>
                        <div>
                            <small>Tiền giảm: <span id="discount-amount">0 VNĐ</span></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="form-text text-muted">Điểm thưởng sẽ được tự động tính dựa trên tổng tiền.</small>
                <small class="text-primary"><strong>Giá gốc: <span id="original-price">0 VNĐ</span></strong></small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="MaPT" class="form-label">Phương Thức Thanh Toán</label>
            <select id="MaPT" name="MaPT" class="form-select @error('MaPT') is-invalid @enderror">
                <option value="">-- Chọn phương thức thanh toán --</option>
                @foreach($phuongThucs as $phuongThuc)
                    <option value="{{ $phuongThuc->MaPT }}" {{ old('MaPT', $hoaDon->MaPT) == $phuongThuc->MaPT ? 'selected' : '' }}>
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
            <select id="Matrangthai" name="Matrangthai" class="form-select @error('Matrangthai') is-invalid @enderror" required>
                <option value="">-- Chọn trạng thái --</option>
                @foreach($trangThais as $trangThai)
                    <option value="{{ $trangThai->Matrangthai }}" {{ ($hoaDon->Matrangthai == $trangThai->Matrangthai) || (empty($hoaDon->Matrangthai) && $trangThai->Matrangthai == 6) ? 'selected' : '' }}>
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
            <a href="{{ route('admin.hoadonvathanhtoan.confirmDestroy', $hoaDon->MaHD) }}" class="btn btn-danger">Xóa</a>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
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
    const discountInfo = document.getElementById('discount-info');
    const discountText = document.getElementById('discount-text');
    const discountAmount = document.getElementById('discount-amount');
    const originalPrice = document.getElementById('original-price');
    const guestInfo = document.getElementById('guest-info');
    const guestNameValue = document.getElementById('guestNameValue');
    const guestPhoneValue = document.getElementById('guestPhoneValue');
    
    // Lưu trữ giá gốc
    let originalServicePrice = 0;
    let currentDiscountRate = 0;
    
    // Hàm tính và hiển thị giảm giá
    function applyDiscount() {
        const userId = userSelect.value;
        
        if (!userId || originalServicePrice <= 0) {
            // Không có người dùng hoặc không có giá dịch vụ - không giảm giá
            tongTienInput.value = originalServicePrice;
            discountInfo.style.display = 'none';
            originalPrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalServicePrice);
            return;
        }
        
        // Gọi API để kiểm tra hạng thành viên và tính giảm giá
        fetch(`/admin/api/check-membership-discount?userId=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentDiscountRate = data.discountRate;
                    const discountValue = originalServicePrice * currentDiscountRate;
                    const finalPrice = originalServicePrice - discountValue;
                    
                    // Hiển thị thông tin giảm giá
                    discountText.textContent = `Giảm giá ${currentDiscountRate * 100}% (${data.membershipRank})`;
                    discountAmount.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(discountValue);
                    tongTienInput.value = finalPrice;
                    discountInfo.style.display = 'block';
                    originalPrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalServicePrice);
                } else {
                    // Không có thông tin giảm giá
                    tongTienInput.value = originalServicePrice;
                    discountInfo.style.display = 'none';
                    originalPrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalServicePrice);
                }
            })
            .catch(error => {
                console.error("Error checking membership discount:", error);
                tongTienInput.value = originalServicePrice;
                discountInfo.style.display = 'none';
            });
    }
    
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
                                // Lưu giá gốc
                                originalServicePrice = serviceGia;
                                
                                // Hiển thị giá dịch vụ đã định dạng
                                servicePrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(serviceGia);
                                
                                // Áp dụng giảm giá nếu có người dùng đã chọn
                                applyDiscount();
                            } else {
                                servicePrice.textContent = 'Không xác định';
                                originalServicePrice = 0;
                                tongTienInput.value = 0;
                            }
                        } else {
                            servicePrice.textContent = 'N/A';
                            originalServicePrice = 0;
                            tongTienInput.value = 0;
                        }
                        
                        // Cập nhật người dùng
                        if (booking.Manguoidung) {
                            userSelect.value = booking.Manguoidung;
                            // Áp dụng giảm giá nếu đã có dịch vụ
                            if (originalServicePrice > 0) {
                                applyDiscount();
                            }
                            // Ẩn thông tin khách vãng lai
                            guestInfo.style.display = 'none';
                        }
                        // Kiểm tra và hiển thị thông tin khách vãng lai
                        else if (!booking.Manguoidung && (booking.Hoten_khach || booking.SDT_khach)) {
                            guestInfo.style.display = 'block';
                            guestNameValue.textContent = booking.Hoten_khach || 'Không có thông tin';
                            guestPhoneValue.textContent = booking.SDT_khach || 'Không có thông tin';
                            
                            // Xóa chọn người dùng nếu là khách vãng lai
                            userSelect.value = '';
                            
                            // Không có giảm giá cho khách vãng lai
                            discountInfo.style.display = 'none';
                        } else {
                            guestInfo.style.display = 'none';
                        }
                    } else {
                        serviceName.textContent = 'Không thể tải dữ liệu';
                        bookingTime.textContent = 'Không thể tải dữ liệu';
                        servicePrice.textContent = 'Không thể tải dữ liệu';
                        bookingStatus.textContent = 'Không thể tải dữ liệu';
                        guestInfo.style.display = 'none';
                        discountInfo.style.display = 'none';
                        console.error('API error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching booking details:', error);
                    serviceName.textContent = 'Lỗi kết nối';
                    bookingTime.textContent = 'Lỗi kết nối';
                    servicePrice.textContent = 'Lỗi kết nối';
                    bookingStatus.textContent = 'Lỗi kết nối';
                    guestInfo.style.display = 'none';
                    discountInfo.style.display = 'none';
                });
        } else {
            bookingInfo.style.display = 'none';
            guestInfo.style.display = 'none';
            discountInfo.style.display = 'none';
            originalServicePrice = 0;
        }
    });
    
    // Khi thay đổi người dùng, tính lại giảm giá
    userSelect.addEventListener('change', function() {
        applyDiscount();
    });
    
    // Khởi tạo giá trị ban đầu nếu đã có
    if (datLichSelect.value) {
        datLichSelect.dispatchEvent(new Event('change'));
    } else {
        // Nếu đã có dữ liệu hóa đơn, hiển thị giá gốc từ giá dịch vụ ban đầu
        // Đây là trường hợp khi đang sửa hóa đơn hiện tại
        const discountRate = {{ $hoaDon->TyLeGiamGia ?? 0 }} / 100;
        const discountAmount = {{ $hoaDon->GiamGia ?? 0 }};
        const totalAmount = {{ $hoaDon->Tongtien ?? 0 }};
        
        if (discountAmount > 0) {
            // Lấy giá gốc bằng cách cộng lại giảm giá
            originalServicePrice = totalAmount + discountAmount;
            
            // Hiển thị thông tin giảm giá
            discountText.textContent = `Giảm giá ${discountRate * 100}% ({{ $hoaDon->HangThanhVien ?? 'Không có' }})`;
            discountAmount.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(discountAmount);
            discountInfo.style.display = 'block';
            originalPrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalServicePrice);
        } else {
            // Không có giảm giá
            originalServicePrice = totalAmount;
            originalPrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(originalServicePrice);
        }
    }
});
</script>
@endsection