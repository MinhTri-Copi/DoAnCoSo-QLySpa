@extends('customer.layouts.app')

@section('title', 'Liên kết lịch đặt trước đây')

@section('styles')
<style>
    .booking-card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .booking-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    .booking-card.selected {
        border-color: #ff6b9d;
        background-color: #fff9fb;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        border-radius: 50px;
    }
    
    .service-image {
        height: 60px;
        width: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .header-banner {
        background: linear-gradient(135deg, #ff6b9d 0%, #ff8db3 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #ff6b9d;
        border-color: #ff6b9d;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="header-banner mb-4">
        <h1 class="h3 mb-2">Liên kết lịch đặt trước đây</h1>
        <p class="mb-0">Chúng tôi tìm thấy một số lịch đặt trước đây sử dụng số điện thoại của bạn. Bạn có muốn liên kết chúng với tài khoản mới của mình không?</p>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary">Thông tin tài khoản</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ tên:</strong> {{ $user->Hoten }}</p>
                    <p><strong>Email:</strong> {{ $user->Email }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Số điện thoại:</strong> {{ $user->SDT }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $user->DiaChi }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <form action="{{ route('customer.link-guest-bookings.store') }}" method="POST" id="linkForm">
        @csrf
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Lịch đặt trước đây ({{ $guestBookings->count() }})</h5>
            </div>
            <div class="card-body">
                @if ($guestBookings->isEmpty())
                    <div class="alert alert-info">
                        Không tìm thấy lịch đặt nào trước đây với số điện thoại của bạn.
                    </div>
                @else
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="selectAll">
                        <label class="custom-control-label" for="selectAll">Chọn tất cả</label>
                    </div>
                    
                    <div class="row">
                        @foreach ($guestBookings as $booking)
                        <div class="col-md-6 mb-3">
                            <div class="booking-card p-3" data-id="{{ $booking->MaDL }}">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input booking-checkbox" 
                                            id="booking{{ $booking->MaDL }}" 
                                            name="booking_ids[]" 
                                            value="{{ $booking->MaDL }}">
                                        <label class="custom-control-label" for="booking{{ $booking->MaDL }}"></label>
                                    </div>
                                    <div class="ml-3 flex-grow-1">
                                        <h5 class="mb-1">{{ $booking->dichVu->Tendichvu }}</h5>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Mã đặt lịch: #{{ $booking->MaDL }}</span>
                                            <span class="badge badge-pill status-badge 
                                                @if($booking->Trangthai_ == 'Chờ xác nhận') badge-warning
                                                @elseif($booking->Trangthai_ == 'Đã xác nhận') badge-primary
                                                @elseif($booking->Trangthai_ == 'Hoàn thành') badge-success
                                                @elseif($booking->Trangthai_ == 'Đã hủy') badge-danger
                                                @endif">
                                                {{ $booking->Trangthai_ }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    @if($booking->dichVu->Image)
                                    <img src="{{ asset($booking->dichVu->Image) }}" alt="{{ $booking->dichVu->Tendichvu }}" class="service-image mr-3">
                                    @else
                                    <div class="service-image mr-3 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-spa text-muted"></i>
                                    </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <p class="mb-1"><i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</p>
                                        <p class="mb-1"><i class="far fa-clock mr-2"></i> {{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</p>
                                        <p class="mb-0"><i class="far fa-user mr-2"></i> {{ $booking->Hoten_khach }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i> Liên kết lịch đặt sẽ giúp bạn xem lại lịch sử đặt lịch và trạng thái của chúng trong tài khoản của mình.
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <a href="{{ route('customer.link-guest-bookings.skip') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> Bỏ qua
                </a>
                <button type="submit" class="btn btn-primary" id="linkButton" {{ $guestBookings->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-link mr-1"></i> Liên kết lịch đặt đã chọn
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Xử lý chọn/bỏ chọn tất cả
        $('#selectAll').change(function() {
            var isChecked = $(this).prop('checked');
            $('.booking-checkbox').prop('checked', isChecked);
            updateCardSelection();
            updateSubmitButton();
        });
        
        // Xử lý khi chọn từng checkbox
        $('.booking-checkbox').change(function() {
            updateCardSelection();
            updateSubmitButton();
            
            // Kiểm tra nếu tất cả các checkbox đã được chọn
            var allChecked = $('.booking-checkbox:checked').length === $('.booking-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
        });
        
        // Xử lý khi click vào card
        $('.booking-card').click(function(e) {
            if (!$(e.target).is('input[type=checkbox]') && !$(e.target).is('label')) {
                var checkbox = $(this).find('.booking-checkbox');
                checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            }
        });
        
        // Cập nhật trạng thái nút submit
        function updateSubmitButton() {
            var checkedCount = $('.booking-checkbox:checked').length;
            $('#linkButton').prop('disabled', checkedCount === 0);
        }
        
        // Cập nhật hiển thị khi chọn/bỏ chọn
        function updateCardSelection() {
            $('.booking-card').each(function() {
                var isChecked = $(this).find('.booking-checkbox').prop('checked');
                $(this).toggleClass('selected', isChecked);
            });
        }
        
        // Kiểm tra form trước khi submit
        $('#linkForm').submit(function(e) {
            var checkedCount = $('.booking-checkbox:checked').length;
            if (checkedCount === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một lịch đặt để liên kết.');
                return false;
            }
            return true;
        });
        
        // Khởi tạo
        updateCardSelection();
        updateSubmitButton();
    });
</script>
@endsection 