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
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .booking-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .booking-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fa;
    }

    .booking-id {
        font-weight: bold;
        color: var(--text-color);
    }

    .booking-status {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
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
        padding: 1.5rem;
    }

    .booking-service {
        display: flex;
        margin-bottom: 1rem;
    }

    .service-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 1rem;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-details {
        flex: 1;
    }

    .service-name {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .service-price {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .booking-details {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .booking-detail-item {
        flex: 1;
        min-width: 200px;
        margin-bottom: 0.5rem;
    }

    .detail-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-weight: bold;
        color: var(--text-color);
    }

    .booking-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1rem;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
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

    @media (max-width: 768px) {
        .booking-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .booking-status {
            margin-top: 0.5rem;
        }

        .booking-service {
            flex-direction: column;
        }

        .service-image {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .booking-actions {
            flex-direction: column;
        }

        .booking-actions .btn {
            width: 100%;
            margin-bottom: 0.5rem;
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
                    
                    @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']))
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $booking->MaDL }}">
                        <i class="fas fa-calendar-alt"></i> Đổi lịch
                    </button>
                    @endif
                    
                    @if(in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận']))
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
