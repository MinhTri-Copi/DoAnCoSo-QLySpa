@extends('customer.layouts.app')

@section('title', 'Lịch Sử Đặt Lịch')

@section('styles')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-hover: #ff4785;
        --secondary-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e1e1e1;
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        margin-top: 2rem;
    }

    .pagination .page-item {
        margin: 0 2px;
    }

    .pagination .page-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        font-weight: 500;
        font-size: 14px;
        color: var(--text-color);
        background-color: #fff;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        box-shadow: 0 2px 5px rgba(255, 107, 157, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
    }

    .pagination .page-link:hover:not(.disabled) {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(255, 107, 157, 0.2);
    }

    .pagination-info {
        text-align: center;
        color: #6c757d;
        font-size: 14px;
        margin-top: 1rem;
    }

    /* Existing styles */
    .booking-history-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header Banner Styling */
    .booking-history-header {
        background: #ffb6c1;
        border-radius: 15px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(255, 182, 193, 0.3);
    }

    .booking-history-header h1 {
        color: #fff;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
    }

    .booking-history-header p {
        color: #fff;
        margin: 0;
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .booking-history-header i {
        font-size: 2rem;
        color: #fff;
    }

    /* Filter Section Styling */
    .filter-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .filter-card .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .filter-card .form-select,
    .filter-card .form-control {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }

    .filter-card .form-select:focus,
    .filter-card .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25);
    }

    /* Booking Card Styling */
    .booking-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .booking-header {
        padding: 1.2rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .service-image {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        overflow: hidden;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-details {
        padding-left: 1.5rem;
    }

    .service-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .service-price {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .service-duration {
        color: #666;
        font-size: 0.9rem;
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

    /* Button Styling */
    .btn {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn i {
        font-size: 1rem;
    }

    .btn-primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        color: #ff6b9d;
        border: 2px solid #ff6b9d;
        background: #fff;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: #fff;
        transform: translateY(-1px);
    }

    .booking-actions {
        display: flex;
        gap: 0.8rem;
        justify-content: flex-end;
        padding-top: 1.2rem;
        border-top: 1px solid var(--border-color);
        margin-top: 1.2rem;
    }

    /* Filter Buttons */
    .filter-buttons {
        display: flex;
        gap: 0.8rem;
        justify-content: flex-end;
    }

    .filter-buttons .btn {
        min-width: 100px;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
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
        <h1><i class="fas fa-history"></i> Lịch Sử Đặt Lịch</h1>
        <p>Quản lý và theo dõi các lịch đặt của bạn một cách dễ dàng và hiệu quả</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="filter-card">
        <form action="{{ route('customer.lichsudatlich.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="service" class="form-label">Dịch vụ</label>
                    <select class="form-select" id="service" name="service">
                        <option value="">Tất cả dịch vụ</option>
                        @foreach($services as $id => $name)
                        <option value="{{ $id }}" {{ request('service') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-6">
                    <label for="sort" class="form-label">Sắp xếp</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Mới nhất trước</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Cũ nhất trước</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="filter-buttons">
                        <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo"></i> Đặt lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Lọc kết quả
                        </button>
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

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="d-flex flex-column align-items-center mt-4">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($bookings->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookings->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                    <li class="page-item {{ $page == $bookings->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Next Page Link --}}
                @if ($bookings->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookings->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </div>
            <div class="pagination-info">
                Hiển thị {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} của {{ $bookings->total() }} lịch đặt
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
