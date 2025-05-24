@extends('customer.layouts.app')

@section('title', 'Lịch sử đặt lịch')

@section('styles')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-hover: #ff4785;
        --secondary-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e1e1e1;
    }

    .booking-history-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .booking-history-header {
        background: linear-gradient(135deg, #ff6b9d 0%, #ff8db3 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .filter-card {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .booking-card {
        border: 2px solid #ffe3ea;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(.4,2,.3,1);
        margin-bottom: 2.5rem;
        box-shadow: 0 8px 32px 0 rgba(255,107,157,0.10);
        background: #fff;
    }

    .booking-card:hover {
        box-shadow: 0 12px 40px 0 rgba(255,107,157,0.18);
        transform: translateY(-4px) scale(1.01);
    }

    .booking-header {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid #ffe3ea;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafd;
        border-radius: 20px 20px 0 0;
        font-size: 1.08rem;
        font-weight: 600;
        color: #ff6b9d;
    }

    .booking-id {
        font-weight: 700;
        color: #22223b;
        font-size: 1.08rem;
    }

    .booking-status {
        font-size: 1rem;
        font-weight: 600;
        background: none;
        border: none;
        color: #888;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-in-progress {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .booking-body {
        padding: 2rem 2.5rem 2rem 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .booking-service {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .service-image {
        width: 90px;
        height: 90px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px #ffe3ea;
        border: 2px solid #ffe3ea;
        background: #fff0f6;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .service-name {
        font-weight: 700;
        font-size: 1.15rem;
        color: #22223b;
        margin-bottom: 0.2rem;
    }

    .service-price {
        color: #ff6b9d;
        font-weight: 700;
        font-size: 1.08rem;
        margin-bottom: 0.2rem;
    }

    .service-duration {
        color: #888;
        font-size: 0.98rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .booking-details {
        display: flex;
        flex-wrap: wrap;
        gap: 2.5rem;
        margin-bottom: 0.5rem;
        justify-content: flex-start;
    }

    .booking-detail-item {
        min-width: 160px;
    }

    .detail-label {
        font-size: 0.97rem;
        color: #b0b0b0;
        margin-bottom: 0.1rem;
    }

    .detail-value {
        font-weight: 700;
        color: #22223b;
        font-size: 1.08rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 1.1rem;
        border-radius: 12px;
        font-size: 0.98rem;
        font-weight: 600;
        background: #fff7e6;
        color: #b68900;
        border: 1.5px solid #ffe3ea;
        box-shadow: 0 1px 4px #ffe3ea;
    }

    .status-badge.status-completed {
        background: linear-gradient(90deg,#d1fae5 0%,#a7f3d0 100%);
        color: #065f46;
        border: 1.5px solid #b7f7d8;
    }

    .booking-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.2rem;
        border-top: none;
        padding-top: 0;
    }

    .btn-primary, .btn-outline-primary, .btn-outline-danger {
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.02rem;
        padding: 0.7rem 1.5rem;
        transition: all 0.2s;
        box-shadow: 0 2px 8px #ffe3ea;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(90deg,#ff6b9d 0%,#ffb3d1 100%);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg,#ff4785 0%,#ffb3d1 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
    }

    .btn-outline-primary {
        color: #ff6b9d;
        border: 2px solid #ff6b9d;
        background: #fff;
    }

    .btn-outline-primary:hover {
        background: #ff6b9d;
        color: #fff;
        border: 2px solid #ff6b9d;
    }

    .btn-outline-danger {
        color: #ff6b9d;
        border: 2px solid #ff6b9d;
        background: #fff;
    }

    .btn-outline-danger:hover {
        background: #ffb3d1;
        color: #fff;
        border: 2px solid #ff6b9d;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #666;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 900px) {
        .booking-body {
            padding: 1.2rem 1rem 1.2rem 1rem;
        }
        .booking-header {
            padding: 1rem 1rem;
        }
        .booking-details {
            gap: 1.2rem;
        }
    }

    @media (max-width: 600px) {
        .booking-card {
            border-radius: 12px;
        }
        .booking-header {
            border-radius: 12px 12px 0 0;
            font-size: 0.98rem;
        }
        .booking-body {
            padding: 1rem 0.5rem 1rem 0.5rem;
        }
        .service-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
        }
        .service-name {
            font-size: 1rem;
        }
        .service-price {
            font-size: 0.98rem;
        }
        .booking-details {
            gap: 0.7rem;
        }
        .booking-detail-item {
            min-width: 120px;
        }
        .btn-primary, .btn-outline-primary, .btn-outline-danger {
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-history-container py-5">
    <div class="booking-history-header">
        <h1 class="mb-2">Lịch sử đặt lịch</h1>
        <p class="mb-0">Quản lý và theo dõi các lịch đặt của bạn</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="filter-card">
        <form action="{{ route('customer.lichsudatlich.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="service" class="form-label">Dịch vụ</label>
                    <select class="form-select" id="service" name="service">
                        <option value="">Tất cả dịch vụ</option>
                        @foreach($services as $id => $name)
                        <option value="{{ $id }}" {{ request('service') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sort" class="form-label">Sắp xếp</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Mới nhất trước</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Cũ nhất trước</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end mb-3">
                    <div class="d-flex gap-2 w-100 justify-content-end">
                        <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if($bookings->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="far fa-calendar-times"></i>
        </div>
        <h4>Không có lịch đặt nào</h4>
        <p class="empty-state-text">Bạn chưa có lịch đặt nào hoặc không có lịch đặt phù hợp với bộ lọc.</p>
        <a href="{{ route('customer.datlich.create') }}" class="btn btn-primary">Đặt lịch ngay</a>
    </div>
    @else
    <div class="booking-list">
        @foreach($bookings as $booking)
        <div class="booking-card">
            <div class="booking-header">
                <div class="booking-id">Mã đặt lịch: {{ $booking->MaDL }}</div>
                <div class="booking-status status-{{ strtolower(str_replace(' ', '-', $booking->Trangthai_)) }}">
                    {{ $booking->Trangthai_ }}
                </div>
            </div>
            <div class="booking-body">
                <div class="booking-service">
                    <div class="service-image">
                        <img src="{{ $booking->dichVu->Image ? asset($booking->dichVu->Image) : asset('images/default-service.jpg') }}" alt="{{ $booking->dichVu->Tendichvu }}">
                    </div>
                    <div class="service-details">
                        <div class="service-name">{{ $booking->dichVu->Tendichvu }}</div>
                        <div class="service-price">{{ $booking->dichVu->getFormattedPriceAttribute() }}</div>
                        <div class="service-duration">
                            <i class="far fa-clock"></i> {{ $booking->dichVu->Thoigian }} phút
                        </div>
                    </div>
                </div>
                <div class="booking-details">
                    <div class="booking-detail-item">
                        <div class="detail-label">Ngày đặt</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('d/m/Y') }}</div>
                    </div>
                    <div class="booking-detail-item">
                        <div class="detail-label">Giờ đặt</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($booking->Thoigiandatlich)->format('H:i') }}</div>
                    </div>
                    <div class="booking-detail-item">
                        <div class="detail-label">Trạng thái thanh toán</div>
                        <div class="detail-value">
                            @if($booking->hoaDon && $booking->hoaDon->count() > 0)
                                <span class="status-badge status-completed">Đã thanh toán</span>
                            @else
                                <span class="status-badge status-pending">Chưa thanh toán</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="booking-actions">
                    <a href="{{ route('customer.lichsudatlich.show', $booking->MaDL) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Xem chi tiết
                    </a>
                    
                    @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']) && !($booking->hoaDon && $booking->hoaDon->count() > 0))
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $booking->MaDL }}">
                        <i class="fas fa-calendar-alt"></i> Đổi lịch
                    </button>
                    @endif
                    
                    @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']) && !($booking->hoaDon && $booking->hoaDon->count() > 0))
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->MaDL }}">
                        <i class="fas fa-times"></i> Hủy lịch
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reschedule Modal -->
        <div class="modal fade" id="rescheduleModal{{ $booking->MaDL }}" tabindex="-1" aria-labelledby="rescheduleModalLabel{{ $booking->MaDL }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rescheduleModalLabel{{ $booking->MaDL }}">Đổi lịch đặt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('customer.lichsudatlich.reschedule', $booking->MaDL) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="new_date{{ $booking->MaDL }}" class="form-label">Ngày mới</label>
                                <input type="date" class="form-control" id="new_date{{ $booking->MaDL }}" name="new_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_time{{ $booking->MaDL }}" class="form-label">Giờ mới</label>
                                <input type="time" class="form-control" id="new_time{{ $booking->MaDL }}" name="new_time" required>
                            </div>
                            <div class="mb-3">
                                <label for="reason{{ $booking->MaDL }}" class="form-label">Lý do đổi lịch</label>
                                <textarea class="form-control" id="reason{{ $booking->MaDL }}" name="reason" rows="3" required></textarea>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Lưu ý: Việc đổi lịch cần được xác nhận lại từ phía spa.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal{{ $booking->MaDL }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $booking->MaDL }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel{{ $booking->MaDL }}">Xác nhận hủy lịch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn hủy lịch đặt này không?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Lưu ý: Bạn không thể hủy lịch trong vòng 24 giờ trước thời gian đặt.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <form action="{{ route('customer.lichsudatlich.cancel', $booking->MaDL) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
