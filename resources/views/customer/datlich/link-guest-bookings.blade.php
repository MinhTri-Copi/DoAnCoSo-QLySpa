@extends('customer.layouts.app')

@section('title', 'Liên kết thông tin khách vãng lai')

@section('styles')
<style>
    .header-banner {
        background: linear-gradient(135deg, #ff6b9d 0%, #ff8db3 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(255, 107, 157, 0.2);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .header-banner::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        right: -50px;
        bottom: -50px;
    }
    
    .account-card {
        border-radius: 12px;
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .account-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-5px);
    }
    
    .booking-card {
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: #fff;
        margin-bottom: 16px;
    }
    
    .booking-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }
    
    .booking-card.selected {
        border-color: #ff6b9d;
        background-color: #fff9fb;
    }
    
    .invoice-card {
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: #f8f9ff;
        margin-bottom: 16px;
    }
    
    .invoice-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }
    
    .invoice-card.selected {
        border-color: #6b8aff;
        background-color: #f5f8ff;
    }
    
    .item-type-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .booking-badge {
        background-color: #fff0f5;
        color: #ff6b9d;
    }
    
    .invoice-badge {
        background-color: #f0f5ff;
        color: #4285f4;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    .service-image {
        height: 65px;
        width: 65px;
        object-fit: cover;
        border-radius: 10px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .info-item i {
        width: 20px;
        margin-right: 8px;
        color: #6c757d;
    }
    
    .info-divider {
        width: 100%;
        height: 1px;
        background: #e9ecef;
        margin: 16px 0;
    }
    
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #ff6b9d;
        border-color: #ff6b9d;
    }
    
    .tab-content {
        padding: 20px 0;
    }
    
    .nav-tabs {
        border-bottom: 2px solid #f1f2f3;
    }
    
    .nav-tabs .nav-item .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        position: relative;
    }
    
    .nav-tabs .nav-item .nav-link.active {
        color: #ff6b9d;
        background: transparent;
    }
    
    .nav-tabs .nav-item .nav-link.active::after {
        content: '';
        position: absolute;
        height: 3px;
        background: #ff6b9d;
        width: 80%;
        left: 10%;
        bottom: -2px;
        border-radius: 3px 3px 0 0;
    }
    
    .count-bubble {
        background: #ff6b9d;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        margin-left: 6px;
    }
    
    .btn-primary {
        background: #ff6b9d;
        border-color: #ff6b9d;
        box-shadow: 0 4px 8px rgba(255, 107, 157, 0.2);
    }
    
    .btn-primary:hover {
        background: #ff5691;
        border-color: #ff5691;
        box-shadow: 0 6px 12px rgba(255, 107, 157, 0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #545b62;
        box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
    }
    
    .text-gradient {
        background: linear-gradient(90deg, #ff6b9d, #ff9e6d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .alert-info {
        background-color: #e8f4ff;
        border-color: #c5e0ff;
        color: #004085;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="header-banner mb-4">
        <h1 class="h3 mb-2 fw-bold">Liên kết thông tin khách vãng lai</h1>
        <p class="mb-0 lead">
            Chúng tôi đã tìm thấy <strong>{{ $guestBookings->count() }}</strong> lịch đặt 
            và <strong>{{ session('guest_invoices_count') }}</strong> hóa đơn
            sử dụng số điện thoại của bạn. Bạn có muốn liên kết chúng với tài khoản mới không?
        </p>
    </div>
    
    <div class="card account-card mb-4">
        <div class="card-body p-4">
            <h5 class="card-title text-primary d-flex align-items-center mb-3">
                <i class="fas fa-user-circle me-2"></i> Thông tin tài khoản của bạn
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <span class="text-muted">Họ tên:</span>
                            <strong>{{ $user->Hoten }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <span class="text-muted">Email:</span>
                            <strong>{{ $user->Email }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <span class="text-muted">Số điện thoại:</span>
                            <strong>{{ $user->SDT }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <span class="text-muted">Địa chỉ:</span>
                            <strong>{{ $user->DiaChi }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <form action="{{ route('customer.link-guest-bookings.store') }}" method="POST" id="linkForm">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-white p-3">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">
                            Tất cả <span class="count-bubble">{{ $guestBookings->count() + session('guest_invoices_count') }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="bookings-tab" data-bs-toggle="tab" href="#bookings" role="tab">
                            Lịch đặt <span class="count-bubble">{{ $guestBookings->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="invoices-tab" data-bs-toggle="tab" href="#invoices" role="tab">
                            Hóa đơn <span class="count-bubble">{{ session('guest_invoices_count') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                @if ($guestBookings->isEmpty() && session('guest_invoices_count') == 0)
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2 fa-lg"></i>
                        <div>
                            Không tìm thấy lịch đặt hoặc hóa đơn nào trước đây với số điện thoại của bạn.
                        </div>
                    </div>
                @else
                    <div class="custom-control custom-checkbox mb-4">
                        <input type="checkbox" class="custom-control-input" id="selectAll">
                        <label class="custom-control-label fw-bold" for="selectAll">Chọn tất cả các mục</label>
                    </div>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <div class="row">
                                @foreach ($guestBookings as $booking)
                                <div class="col-md-6">
                                    <div class="booking-card p-3 position-relative">
                                        <span class="item-type-badge booking-badge">
                                            <i class="far fa-calendar-check me-1"></i> Lịch đặt
                                        </span>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input booking-checkbox" 
                                                    id="booking{{ $booking->MaDL }}" 
                                                    name="booking_ids[]" 
                                                    value="{{ $booking->MaDL }}">
                                                <label class="custom-control-label" for="booking{{ $booking->MaDL }}"></label>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <h5 class="mb-1 fw-bold">{{ $booking->dichVu->Tendichvu }}</h5>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Mã đặt lịch: #{{ $booking->MaDL }}</span>
                                                    <span class="badge status-badge 
                                                        @if($booking->Trangthai_ == 'Chờ xác nhận') bg-warning text-dark
                                                        @elseif($booking->Trangthai_ == 'Đã xác nhận') bg-primary
                                                        @elseif($booking->Trangthai_ == 'Hoàn thành') bg-success
                                                        @elseif($booking->Trangthai_ == 'Đã hủy') bg-danger
                                                        @endif">
                                                        {{ $booking->Trangthai_ }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="info-divider"></div>
                                        
                                        <div class="d-flex">
                                            @if($booking->dichVu->Image)
                                            <img src="{{ asset($booking->dichVu->Image) }}" alt="{{ $booking->dichVu->Tendichvu }}" class="service-image me-3">
                                            @else
                                            <div class="service-image me-3 bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-spa text-muted fa-2x"></i>
                                            </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="info-item">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <div>{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="far fa-clock"></i>
                                                    <div>{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="far fa-user"></i>
                                                    <div>{{ $booking->Hoten_khach }}</div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-phone-alt"></i>
                                                    <div>{{ $booking->SDT_khach }}</div>
                                                </div>
                                                @if($booking->dichVu->Gia)
                                                <div class="info-item">
                                                    <i class="fas fa-tag"></i>
                                                    <div class="text-danger fw-bold">
                                                        {{ number_format($booking->dichVu->Gia, 0, ',', '.') }} VNĐ
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if(session('guest_invoices_count') > 0)
                                    @foreach(range(1, min(session('guest_invoices_count'), 5)) as $i)
                                    <div class="col-md-6">
                                        <div class="invoice-card p-3 position-relative">
                                            <span class="item-type-badge invoice-badge">
                                                <i class="fas fa-file-invoice me-1"></i> Hóa đơn
                                            </span>
                                            <div class="d-flex mb-3">
                                                <div class="me-3">
                                                    <div class="bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                                        <i class="fas fa-file-invoice text-primary fa-2x"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1 fw-bold">Hóa đơn liên quan</h5>
                                                    <div class="text-muted small">Sẽ được liên kết khi bạn chọn lịch đặt</div>
                                                    <div class="mt-1">
                                                        <span class="badge bg-info">Sẽ được tự động liên kết</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="bookings" role="tabpanel">
                            <div class="row">
                                @foreach ($guestBookings as $booking)
                                <div class="col-md-6">
                                    <div class="booking-card p-3 position-relative">
                                        <span class="item-type-badge booking-badge">
                                            <i class="far fa-calendar-check me-1"></i> Lịch đặt
                                        </span>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input booking-checkbox" 
                                                    id="booking-tab{{ $booking->MaDL }}" 
                                                    name="booking_ids[]" 
                                                    value="{{ $booking->MaDL }}">
                                                <label class="custom-control-label" for="booking-tab{{ $booking->MaDL }}"></label>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <h5 class="mb-1 fw-bold">{{ $booking->dichVu->Tendichvu }}</h5>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Mã đặt lịch: #{{ $booking->MaDL }}</span>
                                                    <span class="badge status-badge 
                                                        @if($booking->Trangthai_ == 'Chờ xác nhận') bg-warning text-dark
                                                        @elseif($booking->Trangthai_ == 'Đã xác nhận') bg-primary
                                                        @elseif($booking->Trangthai_ == 'Hoàn thành') bg-success
                                                        @elseif($booking->Trangthai_ == 'Đã hủy') bg-danger
                                                        @endif">
                                                        {{ $booking->Trangthai_ }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="info-divider"></div>
                                        
                                        <div class="d-flex">
                                            @if($booking->dichVu->Image)
                                            <img src="{{ asset($booking->dichVu->Image) }}" alt="{{ $booking->dichVu->Tendichvu }}" class="service-image me-3">
                                            @else
                                            <div class="service-image me-3 bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-spa text-muted fa-2x"></i>
                                            </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="info-item">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <div>{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="far fa-clock"></i>
                                                    <div>{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="far fa-user"></i>
                                                    <div>{{ $booking->Hoten_khach }}</div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-phone-alt"></i>
                                                    <div>{{ $booking->SDT_khach }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="invoices" role="tabpanel">
                            <div class="row">
                                @if(session('guest_invoices_count') > 0)
                                    <div class="col-12 mb-3">
                                        <div class="alert alert-info d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2 fa-lg"></i>
                                            <div>
                                                Có <strong>{{ session('guest_invoices_count') }}</strong> hóa đơn liên quan đến các lịch đặt của bạn. 
                                                Các hóa đơn này sẽ tự động được liên kết khi bạn chọn lịch đặt tương ứng.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @foreach(range(1, min(session('guest_invoices_count'), 10)) as $i)
                                    <div class="col-md-6">
                                        <div class="invoice-card p-3 position-relative">
                                            <span class="item-type-badge invoice-badge">
                                                <i class="fas fa-file-invoice me-1"></i> Hóa đơn
                                            </span>
                                            <div class="d-flex mb-3">
                                                <div class="me-3">
                                                    <div class="bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                                        <i class="fas fa-file-invoice text-primary fa-2x"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1 fw-bold">Hóa đơn liên quan</h5>
                                                    <div class="text-muted small">Sẽ được liên kết khi bạn chọn lịch đặt</div>
                                                    <div class="mt-1">
                                                        <span class="badge bg-info">Sẽ được tự động liên kết</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            Không có hóa đơn nào liên quan đến lịch đặt của bạn.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4 d-flex align-items-center">
                        <i class="fas fa-info-circle me-3 fa-lg"></i>
                        <div>
                            <p class="mb-1 fw-semibold">Lợi ích khi liên kết tài khoản:</p>
                            <ul class="mb-0">
                                <li>Xem lại toàn bộ lịch sử đặt lịch và hóa đơn</li>
                                <li>Theo dõi trạng thái của các dịch vụ đã sử dụng</li>
                                <li>Tích lũy điểm thưởng từ các hóa đơn đã thanh toán</li>
                                <li>Nhận các ưu đãi và khuyến mãi dành riêng cho thành viên</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="card-footer bg-white d-flex justify-content-between p-3">
                <a href="{{ route('customer.link-guest-bookings.skip') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-2"></i> Bỏ qua
                </a>
                <button type="submit" class="btn btn-primary px-4" id="linkButton" {{ ($guestBookings->isEmpty() && session('guest_invoices_count') == 0) ? 'disabled' : '' }}>
                    <i class="fas fa-link me-2"></i> Liên kết thông tin
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
        
        // Đồng bộ trạng thái checkbox giữa các tab
        $('#bookings-tab, #all-tab, #invoices-tab').on('click', function() {
            setTimeout(function() {
                syncCheckboxes();
            }, 100);
        });
        
        function syncCheckboxes() {
            $('.booking-checkbox').each(function() {
                var id = $(this).val();
                var isChecked = $(this).prop('checked');
                
                $('input[name="booking_ids[]"][value="' + id + '"]').prop('checked', isChecked);
            });
            
            updateCardSelection();
        }
        
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