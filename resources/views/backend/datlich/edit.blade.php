@extends('backend.layouts.app')

@section('title', 'Chỉnh Sửa Lịch Đặt')

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

    .service-info {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px dashed var(--border-color);
    }

    .service-info-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .service-info-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .service-detail {
        display: flex;
        margin-bottom: 8px;
    }

    .service-detail-label {
        width: 120px;
        font-weight: 500;
        color: #6c757d;
    }

    .service-detail-value {
        flex: 1;
        color: #495057;
    }

    .time-slots-container {
        margin-top: 20px;
    }

    .time-slots-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #495057;
    }

    .time-slots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }

    .time-slot {
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
    }

    .time-slot.available {
        background-color: #f8f9fa;
        color: #495057;
    }

    .time-slot.available:hover {
        background-color: var(--primary-light);
        border-color: var(--primary-color);
        color: var(--primary-dark);
    }

    .time-slot.unavailable {
        background-color: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .time-slot.selected {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .btn-container {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
        
        .time-slots-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }
    }
</style>

<div class="header-container">
    <div class="header-title">Chỉnh Sửa Lịch Đặt</div>
    <div class="header-subtitle">Cập nhật thông tin lịch đặt</div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-edit"></i> Thông Tin Lịch Đặt
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
    
    <form action="{{ route('admin.datlich.update', $datLich->MaDL) }}" method="POST" id="bookingForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="MaDL" class="form-label">Mã Đặt Lịch</label>
            <input type="text" class="form-control" id="MaDL" value="{{ $datLich->MaDL }}" disabled>
            <small class="form-text">Mã đặt lịch không thể thay đổi.</small>
        </div>
        
        <div class="form-group">
            <label for="Manguoidung" class="form-label">Người Dùng <span class="text-danger">*</span></label>
            <select class="form-select @error('Manguoidung') is-invalid @enderror" id="Manguoidung" name="Manguoidung" required>
                <option value="">-- Chọn người dùng --</option>
                @foreach($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung', $datLich->Manguoidung) == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->Hoten }} ({{ $user->SDT ?? 'Không có SĐT' }})
                    </option>
                @endforeach
            </select>
            @error('Manguoidung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="MaDV" class="form-label">Dịch Vụ <span class="text-danger">*</span></label>
            <select class="form-select @error('MaDV') is-invalid @enderror" id="MaDV" name="MaDV" required>
                <option value="">-- Chọn dịch vụ --</option>
                @foreach($dichVus as $dichVu)
                    <option value="{{ $dichVu->MaDV }}" data-time="{{ $dichVu->Thoigian }}" data-price="{{ $dichVu->Gia }}" {{ old('MaDV', $datLich->MaDV) == $dichVu->MaDV ? 'selected' : '' }}>
                        {{ $dichVu->Tendichvu }} ({{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ)
                    </option>
                @endforeach
            </select>
            @error('MaDV')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="service-info" id="serviceInfo" style="display: none;">
            <div class="service-info-title">
                <i class="fas fa-info-circle"></i> Thông Tin Dịch Vụ
            </div>
            <div class="service-detail">
                <div class="service-detail-label">Tên dịch vụ:</div>
                <div class="service-detail-value" id="serviceName">-</div>
            </div>
            <div class="service-detail">
                <div class="service-detail-label">Giá:</div>
                <div class="service-detail-value" id="servicePrice">-</div>
            </div>
            <div class="service-detail">
                <div class="service-detail-label">Thời gian:</div>
                <div class="service-detail-value" id="serviceTime">-</div>
            </div>
        </div>
        
        @php
            $bookingDateTime = \Carbon\Carbon::parse($datLich->Thoigiandatlich);
            $bookingDate = $bookingDateTime->format('Y-m-d');
            $bookingTime = $bookingDateTime->format('H:i');
        @endphp
        
        <div class="form-group">
            <label for="bookingDate" class="form-label">Ngày Đặt Lịch <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('bookingDate') is-invalid @enderror" id="bookingDate" name="bookingDate" value="{{ old('bookingDate', $bookingDate) }}" min="{{ date('Y-m-d') }}" required>
            @error('bookingDate')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="bookingTime" class="form-label">Giờ Đặt Lịch <span class="text-danger">*</span></label>
            <input type="time" class="form-control @error('bookingTime') is-invalid @enderror" id="bookingTime" name="bookingTime" value="{{ old('bookingTime', $bookingTime) }}" required>
            @error('bookingTime')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text">Chọn thời gian từ 8:00 đến 18:00.</small>
        </div>
        
        <div class="time-slots-container" id="timeSlotsContainer" style="display: none;">
            <div class="time-slots-title">Chọn Khung Giờ Có Sẵn</div>
            <div class="time-slots-grid" id="timeSlotsGrid"></div>
        </div>
        
        <div class="form-group">
            <label for="Trangthai_" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
            <select class="form-select @error('Trangthai_') is-invalid @enderror" id="Trangthai_" name="Trangthai_" required>
                <option value="">-- Chọn trạng thái --</option>
                <option value="Chờ xác nhận" {{ old('Trangthai_', $datLich->Trangthai_) == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="Đã xác nhận" {{ old('Trangthai_', $datLich->Trangthai_) == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="Đã hủy" {{ old('Trangthai_', $datLich->Trangthai_) == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                <option value="Hoàn thành" {{ old('Trangthai_', $datLich->Trangthai_) == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
            </select>
            @error('Trangthai_')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="btn-container">
            <a href="{{ route('admin.datlich.index') }}" class="btn btn-secondary">Hủy</a>
            <a href="{{ route('admin.datlich.confirmDestroy', $datLich->MaDL) }}" class="btn btn-danger">Xóa</a>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('MaDV');
    const serviceInfo = document.getElementById('serviceInfo');
    const serviceName = document.getElementById('serviceName');
    const servicePrice = document.getElementById('servicePrice');
    const serviceTime = document.getElementById('serviceTime');
    
    const bookingDateInput = document.getElementById('bookingDate');
    const bookingTimeInput = document.getElementById('bookingTime');
    
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlotsGrid = document.getElementById('timeSlotsGrid');
    
    // Cập nhật thông tin dịch vụ khi chọn
    serviceSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const time = selectedOption.dataset.time;
            const price = selectedOption.dataset.price;
            
            serviceName.textContent = selectedOption.text.split(' (')[0];
            servicePrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            serviceTime.textContent = time + ' phút';
            
            serviceInfo.style.display = 'block';
            
            // Kiểm tra khung giờ có sẵn nếu đã chọn ngày
            if (bookingDateInput.value) {
                checkAvailableTimeSlots();
            }
        } else {
            serviceInfo.style.display = 'none';
            timeSlotsContainer.style.display = 'none';
        }
    });
    
    // Kiểm tra khung giờ có sẵn khi chọn ngày
    bookingDateInput.addEventListener('change', function() {
        if (this.value && serviceSelect.value) {
            checkAvailableTimeSlots();
        } else {
            timeSlotsContainer.style.display = 'none';
        }
    });
    
    // Kiểm tra khung giờ có sẵn
    function checkAvailableTimeSlots() {
        const date = bookingDateInput.value;
        const serviceId = serviceSelect.value;
        const currentBookingId = '{{ $datLich->MaDL }}';
        
        // Gọi API để kiểm tra khung giờ có sẵn
        fetch(`/admin/datlich/check-availability?date=${date}&service_id=${serviceId}&exclude_id=${currentBookingId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    alert(data.message);
                    return;
                }
                
                // Hiển thị các khung giờ có sẵn
                timeSlotsGrid.innerHTML = '';
                data.timeSlots.forEach(slot => {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = `time-slot ${slot.available ? 'available' : 'unavailable'}`;
                    timeSlot.textContent = slot.time;
                    
                    // Đánh dấu khung giờ hiện tại
                    if (slot.time === bookingTimeInput.value) {
                        timeSlot.classList.add('selected');
                    }
                    
                    if (slot.available) {
                        timeSlot.addEventListener('click', function() {
                            // Bỏ chọn tất cả các khung giờ khác
                            document.querySelectorAll('.time-slot.selected').forEach(el => {
                                el.classList.remove('selected');
                            });
                            
                            // Chọn khung giờ này
                            this.classList.add('selected');
                            
                            // Cập nhật giá trị giờ đặt lịch
                            bookingTimeInput.value = slot.time;
                        });
                    }
                    
                    timeSlotsGrid.appendChild(timeSlot);
                });
                
                timeSlotsContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error checking availability:', error);
            });
    }
    
    // Cập nhật giá trị Thoigiandatlich khi submit form
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        // Kiểm tra xem đã chọn đủ thông tin chưa
        if (!bookingDateInput.value || !bookingTimeInput.value) {
            e.preventDefault();
            alert('Vui lòng chọn ngày và giờ đặt lịch.');
            return;
        }
        
        // Log for debugging
        console.log('Form submitted with:');
        console.log('Date:', bookingDateInput.value);
        console.log('Time:', bookingTimeInput.value);
    });
    
    // Khởi tạo giá trị ban đầu nếu đã có
    if (serviceSelect.value) {
        serviceSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection