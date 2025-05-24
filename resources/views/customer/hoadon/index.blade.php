@extends('customer.layouts.app')

@section('title', 'Lịch Sử Hóa Đơn')

@section('content')
<div class="container py-5">
    <!-- Welcome Banner -->
    <div class="welcome-banner animate__animated animate__fadeIn mb-4">
        <h1><i class="fas fa-receipt"></i> Lịch sử hóa đơn</h1>
        <p>Xem và quản lý các hóa đơn thanh toán của bạn</p>
        <div class="shine-line"></div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex">
                    <div class="icon-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-money-bill-wave fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Tổng chi tiêu</h6>
                        <h4 class="card-title mb-0">{{ number_format($totalSpent, 0, ',', '.') }} VNĐ</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex">
                    <div class="icon-box bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-file-invoice fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Tổng hóa đơn</h6>
                        <h4 class="card-title mb-0">{{ $totalInvoices }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex">
                    <div class="icon-box bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Chưa thanh toán</h6>
                        <h4 class="card-title mb-0">{{ $unpaidInvoices }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-filter text-primary me-2"></i>Bộ lọc hóa đơn</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('customer.hoadon.index') }}" method="GET" class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="2" {{ request('payment_status') == '2' ? 'selected' : '' }}>Chưa thanh toán</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Phương thức</label>
                    <select name="payment_method" class="form-select">
                        <option value="">Tất cả phương thức</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->MaPT }}" {{ request('payment_method') == $method->MaPT ? 'selected' : '' }}>
                                {{ $method->TenPT }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label">Sắp xếp</label>
                    <select name="sort" class="form-select">
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Ngày mới nhất</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Ngày cũ nhất</option>
                        <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Giá trị cao → thấp</option>
                        <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Giá trị thấp → cao</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Lọc kết quả
                        </button>
                        <a href="{{ route('customer.hoadon.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-list text-primary me-2"></i>Danh sách hóa đơn</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">Mã HĐ</th>
                            <th class="py-3">Dịch vụ</th>
                            <th class="py-3">Ngày thanh toán</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr style="cursor: pointer;">
                                <td onclick="window.location='{{ route('customer.hoadon.show', $invoice->MaHD) }}'">
                                    <span class="fw-bold">#{{ $invoice->MaHD }}</span>
                                </td>
                                <td onclick="window.location='{{ route('customer.hoadon.show', $invoice->MaHD) }}'">
                                    {{ $invoice->datLich->dichVu->Tendichvu ?? 'N/A' }}
                                </td>
                                <td onclick="window.location='{{ route('customer.hoadon.show', $invoice->MaHD) }}'">
                                    @if($invoice->Ngaythanhtoan)
                                        {{ \Carbon\Carbon::parse($invoice->Ngaythanhtoan)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td onclick="window.location='{{ route('customer.hoadon.show', $invoice->MaHD) }}'">
                                    <span class="text-primary fw-bold">{{ number_format($invoice->Tongtien, 0, ',', '.') }} VNĐ</span>
                                </td>
                                <td onclick="window.location='{{ route('customer.hoadon.show', $invoice->MaHD) }}'">
                                    @if($invoice->Matrangthai == 1)
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @elseif($invoice->Matrangthai == 2)
                                        <span class="badge bg-warning">Chờ thanh toán</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $invoice->trangThai->Tentrangthai ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @php
                                            // Determine if the invoice has been paid by checking badge text
                                            $isPaid = false;
                                            if ($invoice->Matrangthai == 4) {
                                                $isPaid = true;
                                            } elseif ($invoice->trangThai && $invoice->trangThai->Tentrangthai == 'Đã thanh toán') {
                                                $isPaid = true;
                                            }
                                            // Check if already rated
                                            $hasRating = \App\Models\DanhGia::where('MaHD', $invoice->MaHD)
                                                ->where('Manguoidung', $customer->Manguoidung)
                                                ->exists();
                                        @endphp
                                        
                                        @if($invoice->Matrangthai == 2)
                                            <a href="{{ route('customer.hoadon.showPayment', $invoice->MaHD) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-credit-card me-1"></i>Thanh toán
                                            </a>
                                        @else
                                            <div class="btn-group">
                                                @if($isPaid && !$hasRating)
                                                <a href="{{ route('customer.danhgia.create.with_id', $invoice->MaHD) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-star me-1"></i>Đánh giá ngay
                                                </a>
                                                @elseif($isPaid && $hasRating)
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-check me-1"></i>Đã đánh giá
                                                </button>
                                                @endif
                                                <a href="{{ route('customer.hoadon.pdf', $invoice->MaHD) }}" class="btn btn-sm btn-outline-secondary ms-1">
                                                    <i class="fas fa-download me-1"></i>Tải PDF
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="{{ asset('images/empty-data.svg') }}" alt="No invoices" style="max-width: 120px; opacity: 0.7;">
                                    <p class="mt-3 mb-1 text-muted">Không có hóa đơn nào</p>
                                    <p class="text-muted mb-3">Hóa đơn sẽ được tạo tự động khi bạn hoàn thành các lịch hẹn tại spa</p>
                                    <a href="{{ route('customer.lichsudatlich.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-history me-1"></i> Xem lịch sử đặt lịch
                                    </a>
                                    <a href="{{ route('customer.datlich.create') }}" class="btn btn-sm btn-primary ms-2">
                                        <i class="fas fa-calendar-plus me-1"></i> Đặt lịch mới
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Hiển thị {{ $invoices->firstItem() ?? 0 }} - {{ $invoices->lastItem() ?? 0 }} của {{ $invoices->total() ?? 0 }} hóa đơn
                </div>
                {{ $invoices->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .icon-box {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
    }
    .table td, .table th {
        padding: 1rem;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endsection

@push('scripts')
<script>
    function redirectToDanhGia(event, invoiceId) {
        event.preventDefault();
        event.stopPropagation();
        
        console.log('Redirecting to review page for invoice: ' + invoiceId);
        
        // Tạo URL đánh giá
        var reviewUrl = "{{ url('/customer/danh-gia/create') }}/" + invoiceId;
        
        // Chuyển hướng đến trang đánh giá
        window.location.href = reviewUrl;
    }
</script>
@endpush

