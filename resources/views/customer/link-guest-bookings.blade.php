@extends('customer.layouts.app')

@section('title', 'Liên kết lịch đặt')

@section('styles')
<style>
    .link-guest-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 0;
    }
    
    .page-header {
        position: relative;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .page-header:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 80px;
        height: 2px;
        background: linear-gradient(to right, #ff6b9d, #ff9d6b);
    }
    
    .page-header h1 {
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .info-alert {
        background: linear-gradient(145deg, #e1f5fe, #e3f2fd);
        border-left: 4px solid #29b6f6;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .info-alert:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .info-alert i {
        color: #0288d1;
        font-size: 1.3rem;
        margin-right: 0.5rem;
    }
    
    .info-alert strong {
        display: block;
        margin-bottom: 0.5rem;
        color: #0277bd;
    }
    
    .booking-card {
        border: 1px solid #e1e1e1;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .booking-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transform: translateY(-3px);
    }
    
    .booking-card.selected {
        border-color: #ff6b9d;
        background-color: #fff9fb;
        box-shadow: 0 5px 15px rgba(255,107,157,0.2);
    }
    
    .booking-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e1e1e1;
        background-color: #fcfcfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .booking-body {
        padding: 1.5rem;
    }
    
    .booking-footer {
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #e1e1e1;
    }
    
    .service-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 1rem;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    
    .service-info {
        display: flex;
        align-items: center;
    }
    
    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .status-badge i {
        margin-right: 0.4rem;
        font-size: 0.9rem;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-confirmed {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .booking-details {
        margin-top: 1rem;
    }
    
    .detail-item {
        display: flex;
        margin-bottom: 0.7rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: #555;
        width: 130px;
        flex-shrink: 0;
    }
    
    .detail-value {
        color: #333;
    }
    
    .note-section {
        background-color: #fffde7;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border-left: 3px solid #ffd54f;
    }
    
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    
    .select-all-container {
        display: flex;
        align-items: center;
    }
    
    .custom-checkbox {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        height: 0;
        width: 0;
    }
    
    .checkmark {
        height: 22px;
        width: 22px;
        background-color: #fff;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin-right: 8px;
        position: relative;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .custom-checkbox input:checked ~ .checkmark {
        background-color: #ff6b9d;
        border-color: #ff6b9d;
    }
    
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .custom-checkbox input:checked ~ .checkmark:after {
        display: block;
    }
    
    .btn-link {
        background: linear-gradient(to right, #ff6b9d, #ff9d6b);
        color: white;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(255, 107, 157, 0.3);
        display: flex;
        align-items: center;
    }
    
    .btn-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 107, 157, 0.4);
    }
    
    .btn-link:disabled {
        background: #f0f0f0;
        color: #999;
        box-shadow: none;
    }
    
    .btn-link i {
        margin-right: 0.5rem;
    }
    
    .btn-skip {
        border: 2px solid #ddd;
        background: transparent;
        color: #666;
        padding: 0.7rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-skip:hover {
        background-color: #f5f5f5;
        color: #333;
        border-color: #ccc;
    }
    
    .skip-container {
        text-align: center;
        margin-top: 2rem;
    }
    
    .booking-price {
        font-weight: 700;
        color: #ff6b9d;
        font-size: 1.1rem;
    }
    
    .booking-id {
        color: #777;
        font-size: 0.85rem;
    }
    
    /* Animation for selected bookings */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 107, 157, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 107, 157, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 107, 157, 0);
        }
    }
    
    .booking-card.selected {
        animation: pulse 1.5s infinite;
    }
</style>
@endsection

@section('content')
<div class="link-guest-container">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="page-header">
                <h1 class="h3">Lịch đặt trước đây ({{ count($guestBookings) }})</h1>
                <p class="text-muted">Liên kết lịch đặt cũ với tài khoản của bạn</p>
            </div>
            
            <div class="info-alert">
                <i class="fas fa-info-circle"></i>
                <strong>Chúng tôi đã tìm thấy {{ count($guestBookings) }} lịch đặt trước đây với số điện thoại {{ $user->SDT }}</strong> 
                <p class="mb-0">Liên kết lịch đặt sẽ giúp bạn xem lại lịch sử đặt lịch và trạng thái của chúng trong tài khoản của mình.</p>
            </div>

            <form action="{{ route('customer.link-guest-bookings.link') }}" method="POST" id="link-bookings-form">
                @csrf
                <div class="action-buttons">
                    <div class="select-all-container">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="select-all">
                            <span class="checkmark"></span>
                            <span>Chọn tất cả</span>
                        </label>
                    </div>
                    <button type="submit" class="btn-link" id="link-button" disabled>
                        <i class="fas fa-link"></i> Liên kết lịch đặt đã chọn
                    </button>
                </div>

                <div class="booking-list">
                    @foreach($guestBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-header">
                            <div class="d-flex align-items-center">
                                <label class="custom-checkbox mr-3 mb-0">
                                    <input type="checkbox" class="booking-checkbox" 
                                        id="booking-{{ $booking->MaDL }}" 
                                        name="booking_ids[]" 
                                        value="{{ $booking->MaDL }}">
                                    <span class="checkmark"></span>
                                </label>
                                <div class="service-info">
                                    <img src="{{ asset('images/services/' . ($booking->dichVu->Hinhanh ?: 'default-service.jpg')) }}" alt="{{ $booking->dichVu->Tendichvu }}" class="service-image">
                                    <div>
                                        <h5 class="mb-1">{{ $booking->dichVu->Tendichvu }}</h5>
                                        <span class="booking-id">Mã đặt lịch: #{{ $booking->MaDL }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    switch($booking->Trangthai_) {
                                        case 'Chờ xác nhận':
                                            $statusClass = 'status-pending';
                                            $statusIcon = 'fa-clock';
                                            break;
                                        case 'Đã xác nhận':
                                            $statusClass = 'status-confirmed';
                                            $statusIcon = 'fa-check-circle';
                                            break;
                                        case 'Hoàn thành':
                                            $statusClass = 'status-completed';
                                            $statusIcon = 'fa-check-double';
                                            break;
                                        case 'Đã hủy':
                                            $statusClass = 'status-cancelled';
                                            $statusIcon = 'fa-times-circle';
                                            break;
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i>
                                    {{ $booking->Trangthai_ }}
                                </span>
                            </div>
                        </div>
                        <div class="booking-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="far fa-calendar-alt mr-2"></i>Ngày đặt:</div>
                                        <div class="detail-value">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="far fa-clock mr-2"></i>Giờ đặt:</div>
                                        <div class="detail-value">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="far fa-user mr-2"></i>Họ tên:</div>
                                        <div class="detail-value">{{ $booking->Hoten_khach }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="fas fa-phone-alt mr-2"></i>Số điện thoại:</div>
                                        <div class="detail-value">{{ $booking->SDT_khach }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="fas fa-spa mr-2"></i>Dịch vụ:</div>
                                        <div class="detail-value">{{ $booking->dichVu->Tendichvu }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="fas fa-tag mr-2"></i>Giá:</div>
                                        <div class="detail-value booking-price">{{ number_format($booking->dichVu->Gia, 0, ',', '.') }} VNĐ</div>
                                    </div>
                                </div>
                            </div>
                            @if($booking->Ghichu)
                            <div class="note-section">
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-sticky-note mr-2"></i>Ghi chú:</div>
                                    <div class="detail-value">{{ $booking->Ghichu }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
    
    <div class="skip-container">
        <a href="{{ route('customer.home') }}" class="btn-skip">
            <i class="fas fa-times mr-2"></i> Bỏ qua
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Xử lý chọn tất cả
    $('#select-all').change(function() {
        $('.booking-checkbox').prop('checked', $(this).is(':checked'));
        updateSelectedBookings();
    });
    
    // Xử lý khi chọn từng lịch đặt
    $('.booking-checkbox').change(function() {
        updateSelectedBookings();
        
        // Cập nhật trạng thái "Chọn tất cả" nếu tất cả đều được chọn
        $('#select-all').prop('checked', $('.booking-checkbox:checked').length === $('.booking-checkbox').length);
    });
    
    // Cập nhật trạng thái của các lịch đặt được chọn
    function updateSelectedBookings() {
        // Thêm/xóa class selected cho các thẻ cha
        $('.booking-checkbox').each(function() {
            if($(this).is(':checked')) {
                $(this).closest('.booking-card').addClass('selected');
            } else {
                $(this).closest('.booking-card').removeClass('selected');
            }
        });
        
        // Enable/disable nút liên kết
        $('#link-button').prop('disabled', $('.booking-checkbox:checked').length === 0);
    }
    
    // Thêm hiệu ứng cho card khi hover
    $('.booking-card').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
        },
        function() {
            if (!$(this).hasClass('selected')) {
                $(this).css('transform', 'translateY(0)');
            }
        }
    );
});
</script>
@endsection 