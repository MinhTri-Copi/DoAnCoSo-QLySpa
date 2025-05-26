@extends('customer.layouts.app')

@section('title', 'Liên kết lịch đặt')

@section('styles')
<style>
    .link-guest-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 0;
    }
    .booking-card {
        border: 1px solid #e1e1e1;
        border-radius: 10px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .booking-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .booking-card.selected {
        border-color: #ff6b9d;
        background-color: #fff9fb;
    }
    .booking-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e1e1e1;
    }
    .booking-body {
        padding: 1.5rem;
    }
    .booking-footer {
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #e1e1e1;
        border-radius: 0 0 10px 10px;
    }
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
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
    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #ff6b9d;
        background-color: #ff6b9d;
    }
</style>
@endsection

@section('content')
<div class="link-guest-container">
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="h3 mb-4">Liên kết lịch đặt trước đây</h1>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Chúng tôi đã tìm thấy một số lịch đặt trước đây với số điện thoại {{ $user->SDT }}.</strong> 
                <p class="mb-0">Bạn có thể liên kết các lịch đặt này vào tài khoản của mình để theo dõi lịch sử đặt lịch và nhận điểm thưởng (nếu có).</p>
            </div>

            <form action="{{ route('customer.link-guest-bookings.link') }}" method="POST" id="link-bookings-form">
                @csrf
                <div class="d-flex align-items-center mb-4">
                    <div class="custom-control custom-checkbox mr-3">
                        <input type="checkbox" class="custom-control-input" id="select-all">
                        <label class="custom-control-label" for="select-all">Chọn tất cả</label>
                    </div>
                    <button type="submit" class="btn btn-primary ml-auto" id="link-button" disabled>
                        Liên kết lịch đặt đã chọn
                    </button>
                </div>

                <div class="booking-list">
                    @foreach($guestBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-header d-flex justify-content-between align-items-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input booking-checkbox" 
                                    id="booking-{{ $booking->MaDL }}" 
                                    name="booking_ids[]" 
                                    value="{{ $booking->MaDL }}">
                                <label class="custom-control-label" for="booking-{{ $booking->MaDL }}">
                                    <strong>{{ $booking->dichVu->Tendichvu }}</strong>
                                </label>
                            </div>
                            <div>
                                @php
                                    $statusClass = '';
                                    switch($booking->Trangthai_) {
                                        case 'Chờ xác nhận':
                                            $statusClass = 'status-pending';
                                            break;
                                        case 'Đã xác nhận':
                                            $statusClass = 'status-confirmed';
                                            break;
                                        case 'Hoàn thành':
                                            $statusClass = 'status-completed';
                                            break;
                                        case 'Đã hủy':
                                            $statusClass = 'status-cancelled';
                                            break;
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $booking->Trangthai_ }}</span>
                            </div>
                        </div>
                        <div class="booking-body row">
                            <div class="col-md-6">
                                <p><strong>Mã đặt lịch:</strong> {{ $booking->MaDL }}</p>
                                <p><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i - d/m/Y') }}</p>
                                <p><strong>Họ tên:</strong> {{ $booking->Hoten_khach }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Số điện thoại:</strong> {{ $booking->SDT_khach }}</p>
                                <p><strong>Dịch vụ:</strong> {{ $booking->dichVu->Tendichvu }}</p>
                                <p><strong>Giá:</strong> {{ number_format($booking->dichVu->Gia, 0, ',', '.') }} VNĐ</p>
                            </div>
                            @if($booking->Ghichu)
                            <div class="col-12 mt-3">
                                <p><strong>Ghi chú:</strong> {{ $booking->Ghichu }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
            Bỏ qua và đến trang chủ
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
});
</script>
@endsection 