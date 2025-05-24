<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $invoice->MaHD }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
        }
        
        .invoice-container {
            width: 100%;
            position: relative;
        }
        
        .invoice-header {
            padding: 40px;
            background-color: #ff6b8b;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.5;
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
        
        .info-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #ff6b8b;
            border-bottom: 2px solid #ff6b8b;
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .info-row {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: 600;
            display: inline-block;
            width: 40%;
            color: #555;
        }
        
        .info-value {
            display: inline-block;
            width: 60%;
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
        
        .text-right {
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
        
        .total-label {
            color: #ff6b8b;
        }
        
        .total-value {
            color: #ff6b8b;
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
            margin-bottom: 40px;
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
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(255, 107, 139, 0.05);
            font-weight: bold;
            z-index: 10;
            white-space: nowrap;
        }
        
        .reward-points {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #fff3cd;
            border-radius: 5px;
            color: #856404;
            margin-bottom: 20px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            margin-bottom: 40px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 50px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 10px;
        }
        
        .col-half {
            float: left;
            width: 50%;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        @if($invoice->Matrangthai == 1)
        <div class="watermark">ĐÃ THANH TOÁN</div>
        @endif
        
        <div class="invoice-header">
            <div class="col-half">
                <div class="company-name">Rosa Spa Beauty</div>
                <div class="company-details">
                    123 Đường Nguyễn Huệ, Quận 1, TP.HCM<br>
                    Điện thoại: (028) 1234 5678<br>
                    Email: info@rosaspabeauty.com
                </div>
            </div>
            <div class="col-half" style="text-align: right;">
                <div class="invoice-title">HÓA ĐƠN</div>
                <div class="invoice-id">#{{ $invoice->MaHD }}</div>
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="clearfix">
                <div class="col-half">
                    <div class="info-section">
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
                </div>
                
                <div class="col-half">
                    <div class="info-section">
                        <div class="info-title">THÔNG TIN THANH TOÁN</div>
                        <div class="info-row">
                            <div class="info-label">Ngày thanh toán:</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($invoice->Ngaythanhtoan)->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phương thức:</div>
                            <div class="info-value">{{ $invoice->phuongThuc->TenPT ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Trạng thái:</div>
                            <div class="info-value">{{ $invoice->trangThai->Tentrangthai ?? 'N/A' }}</div>
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
                <strong>Điểm thưởng:</strong> Quý khách được cộng {{ $invoice->lsDiemThuong->first()->Sodiem ?? 0 }} điểm vào tài khoản thành viên.
            </div>
            @endif
            
            <div class="notes-title">Ghi Chú</div>
            <div class="notes-content">
                <p>1. Hóa đơn đã xuất không được hoàn trả.</p>
                <p>2. Vui lòng giữ hóa đơn để đối chiếu khi cần thiết.</p>
                <p>3. Mọi thắc mắc vui lòng liên hệ hotline: (028) 1234 5678.</p>
            </div>
            
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-title">Người lập hóa đơn</div>
                    <div class="signature-line"></div>
                    <div>Nhân viên Rosa Spa Beauty</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">Khách hàng</div>
                    <div class="signature-line"></div>
                    <div>{{ $invoice->datLich->user->Hoten ?? 'Khách hàng' }}</div>
                </div>
            </div>
        </div>
        
        <div class="invoice-footer">
            <div class="footer-thanks">Cảm ơn quý khách đã sử dụng dịch vụ!</div>
            <div class="footer-message">Chúc quý khách có những trải nghiệm tuyệt vời tại Rosa Spa Beauty.</div>
            <div class="footer-info">
                <div>© {{ date('Y') }} Rosa Spa Beauty. Tất cả các quyền được bảo lưu.</div>
                <div>Mã số thuế: 0123456789</div>
            </div>
        </div>
    </div>
</body>
</html> 