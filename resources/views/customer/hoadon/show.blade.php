@extends('customer.layouts.app')

@section('title', 'Chi tiết hóa đơn #' . $invoice->MaHD)

@section('content')
<div class="container py-5">
    <!-- Welcome Banner -->
    <div class="welcome-banner animate__animated animate__fadeIn mb-4">
        <h1><i class="fas fa-receipt"></i> Chi tiết hóa đơn</h1>
        <p>Thông tin chi tiết hóa đơn #{{ $invoice->MaHD }}</p>
        <div class="shine-line"></div>
    </div>

    <!-- Action buttons -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('customer.hoadon.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
        @if($invoice->Matrangthai == 2)
            <a href="{{ route('customer.hoadon.showPayment', $invoice->MaHD) }}" class="btn btn-primary">
                <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
            </a>
        @else
            <a href="{{ route('customer.hoadon.pdf', $invoice->MaHD) }}" class="btn btn-primary">
                <i class="fas fa-download me-2"></i>Tải hóa đơn PDF
            </a>
            @if (!$hasReview)
                <a href="{{ route('customer.danhgia.create.with_id', $invoice->MaHD) }}" class="btn btn-outline-primary" onclick="redirectToDanhGia(event, {{ $invoice->MaHD }})">
                    <i class="fas fa-star me-2"></i>Viết đánh giá
                </a>
            @endif
        @endif
    </div>

    <!-- Invoice Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="invoice-container bg-white">
                @if($invoice->Matrangthai == 1)
                <div class="watermark">ĐÃ THANH TOÁN</div>
                @endif
                
                <div class="invoice-header">
                    <div class="company-info">
                        <div class="company-name">Rosa Spa Beauty</div>
                        <div class="company-details">
                            123 Đường Nguyễn Huệ, Quận 1, TP.HCM<br>
                            Điện thoại: (028) 1234 5678<br>
                            Email: info@rosaspabeauty.com
                        </div>
                    </div>
                    <div>
                        <div class="invoice-title">HÓA ĐƠN</div>
                        <div class="invoice-id">#{{ $invoice->MaHD }}</div>
                    </div>
                </div>
                
                <div class="invoice-body">
                    <div class="invoice-info">
                        <div class="client-info">
                            <div class="info-title">THÔNG TIN KHÁCH HÀNG</div>
                            <div class="info-row">
                                <div class="info-label">Họ và tên:</div>
                                <div class="info-value">{{ $invoice->datLich->user->Hoten ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Số điện thoại:</div>
                                <div class="info-value">{{ $invoice->datLich->user->SDT ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email:</div>
                                <div class="info-value">{{ $invoice->datLich->user->Email ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Địa chỉ:</div>
                                <div class="info-value">{{ $invoice->datLich->user->DiaChi ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="payment-info">
                            <div class="info-title">THÔNG TIN THANH TOÁN</div>
                            <div class="info-row">
                                <div class="info-label">Ngày thanh toán:</div>
                                <div class="info-value">
                                    @if($invoice->Ngaythanhtoan)
                                        {{ \Carbon\Carbon::parse($invoice->Ngaythanhtoan)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Chưa thanh toán</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Phương thức:</div>
                                <div class="info-value">{{ $invoice->phuongThuc->TenPT ?? 'Chưa chọn' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Trạng thái:</div>
                                <div class="info-value">
                                    @if($invoice->Matrangthai == 1)
                                        <span class="text-success">Đã thanh toán</span>
                                    @elseif($invoice->Matrangthai == 2)
                                        <span class="text-warning">Chờ thanh toán</span>
                                    @else
                                        {{ $invoice->trangThai->Tentrangthai ?? 'N/A' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Dịch Vụ</th>
                                <th>Thời Gian</th>
                                <th class="text-right">Đơn Giá</th>
                                <th class="text-right">Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($invoice->datLich && $invoice->datLich->dichVu)
                            <tr>
                                <td>1</td>
                                <td>{{ $invoice->datLich->dichVu->Tendichvu }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</td>
                                <td class="text-right">{{ number_format($invoice->datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</td>
                                <td class="text-right">{{ number_format($invoice->datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="5" style="text-align: center;">Không có thông tin dịch vụ</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                    <div class="invoice-summary">
                        <div class="summary-row">
                            <div>Tổng tiền dịch vụ:</div>
                            <div>{{ number_format($invoice->Tongtien, 0, ',', '.') }} VNĐ</div>
                        </div>
                        <div class="summary-row">
                            <div>Thuế VAT (10%):</div>
                            <div>{{ number_format($invoice->Tongtien * 0.1, 0, ',', '.') }} VNĐ</div>
                        </div>
                        <div class="summary-row">
                            <div>Giảm giá:</div>
                            <div>0 VNĐ</div>
                        </div>
                        <div class="summary-row">
                            <div class="total-label">Tổng thanh toán:</div>
                            <div class="total-value">{{ number_format($invoice->Tongtien * 1.1, 0, ',', '.') }} VNĐ</div>
                        </div>
                    </div>
                    
                    @if(isset($invoice->lsDiemThuong) && $invoice->lsDiemThuong->isNotEmpty())
                    <div class="reward-points">
                        <i class="fas fa-star"></i>
                        <div>
                            <strong>Điểm thưởng:</strong> Quý khách được cộng {{ $invoice->lsDiemThuong->first()->Sodiem ?? 0 }} điểm vào tài khoản thành viên.
                        </div>
                    </div>
                    @endif
                    
                    <div class="invoice-notes">
                        <div class="notes-title">Ghi Chú</div>
                        <div class="notes-content">
                            <p>1. Hóa đơn đã xuất không được hoàn trả.</p>
                            <p>2. Vui lòng giữ hóa đơn để đối chiếu khi cần thiết.</p>
                            <p>3. Mọi thắc mắc vui lòng liên hệ hotline: (028) 1234 5678.</p>
                        </div>
                    </div>
                    
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=SPABILL{{ $invoice->MaHD }}" alt="QR Code">
                        <div style="margin-top: 5px; font-size: 12px; color: #6c757d;">Quét mã để xem chi tiết hóa đơn</div>
                    </div>
                </div>
                
                <div class="invoice-footer">
                    <div class="footer-thanks">Cảm ơn quý khách đã sử dụng dịch vụ!</div>
                    <div class="footer-message">Chúc quý khách có những trải nghiệm tuyệt vời tại Rosa Spa Beauty.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Invoice Styling */
    .invoice-container {
        position: relative;
        padding: 0;
        overflow: hidden;
    }
    
    .invoice-header {
        padding: 40px;
        background-color: #ff6b8b;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .company-info {
        display: flex;
        flex-direction: column;
    }
    
    .company-name {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .company-details {
        font-size: 14px;
        opacity: 0.9;
    }
    
    .invoice-title {
        font-size: 24px;
        font-weight: bold;
        text-align: right;
    }
    
    .invoice-id {
        font-size: 16px;
        opacity: 0.9;
        text-align: right;
        margin-top: 5px;
    }
    
    .invoice-body {
        padding: 40px;
    }
    
    .invoice-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
    }
    
    .client-info, .payment-info {
        flex: 1;
    }
    
    .info-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #ff6b8b;
        border-bottom: 2px solid #ff6b8b;
        padding-bottom: 5px;
        display: inline-block;
    }
    
    .info-row {
        margin-bottom: 8px;
        display: flex;
    }
    
    .info-label {
        font-weight: 600;
        width: 140px;
        color: #555;
    }
    
    .info-value {
        flex: 1;
    }
    
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }
    
    .invoice-table th {
        background-color: #ff6b8b;
        color: white;
        padding: 12px 15px;
        text-align: left;
    }
    
    .invoice-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .invoice-table .text-right {
        text-align: right;
    }
    
    .invoice-summary {
        width: 350px;
        margin-left: auto;
        margin-bottom: 40px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .summary-row:last-child {
        border-bottom: none;
        border-top: 2px solid #ff6b8b;
        font-weight: bold;
        font-size: 18px;
        padding-top: 15px;
        margin-top: 5px;
    }
    
    .summary-row .total-label {
        color: #ff6b8b;
    }
    
    .summary-row .total-value {
        color: #ff6b8b;
    }
    
    .invoice-notes {
        margin-bottom: 40px;
    }
    
    .notes-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #ff6b8b;
    }
    
    .notes-content {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
        border-left: 3px solid #ff6b8b;
    }
    
    .invoice-footer {
        text-align: center;
        padding: 20px 40px 40px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .footer-thanks {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #ff6b8b;
    }
    
    .footer-message {
        margin-bottom: 20px;
    }
    
    .qr-code {
        text-align: center;
        margin: 20px 0;
    }
    
    .qr-code img {
        max-width: 120px;
    }
    
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 100px;
        color: rgba(255, 107, 139, 0.05);
        font-weight: bold;
        pointer-events: none;
        z-index: 10;
        white-space: nowrap;
    }
    
    .reward-points {
        display: flex;
        align-items: center;
        margin-top: 10px;
        padding: 10px 15px;
        background-color: #fff3cd;
        border-radius: 5px;
        color: #856404;
    }
    
    .reward-points i {
        margin-right: 10px;
        color: #ffc107;
    }
    
    @media (max-width: 768px) {
        .invoice-info {
            flex-direction: column;
        }
        
        .client-info, .payment-info {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .invoice-summary {
            width: 100%;
        }
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