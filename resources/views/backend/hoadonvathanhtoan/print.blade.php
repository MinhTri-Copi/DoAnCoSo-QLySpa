<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #{{ $hoaDon->MaHD }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .invoice-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        
        .invoice-table tr:last-child td {
            border-bottom: none;
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
        
        .footer-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code img {
            max-width: 120px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ff6b8b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
        }
        
        .print-button i {
            margin-right: 8px;
        }
        
        .print-button:hover {
            background-color: #e84e6f;
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
        
        @media print {
            body {
                background-color: white;
            }
            
            .invoice-container {
                width: 100%;
                box-shadow: none;
            }
            
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> In Hóa Đơn
    </button>
    
    <div class="invoice-container">
        @if($hoaDon->trangThai && $hoaDon->trangThai->Tentrangthai == 'Đã thanh toán')
        <div class="watermark">ĐÃ THANH TOÁN</div>
        @endif
        
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">Rosa Spa Beauty</div>
                <div class="company-details">
                    123 Đường Nguyễn Huệ, Quận 1, TP.HCM<br>
                    Điện thoại: (028) 1234 5678<br>
                    Email: info@rosaspabeauty.com<br>
                    Website: www.rosaspabeauty.com
                </div>
            </div>
            <div>
                <div class="invoice-title">HÓA ĐƠN</div>
                <div class="invoice-id">#{{ $hoaDon->MaHD }}</div>
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="invoice-info">
                <div class="client-info">
                    <div class="info-title">THÔNG TIN KHÁCH HÀNG</div>
                    <div class="info-row">
                        <div class="info-label">Họ và tên:</div>
                        <div class="info-value">{{ $hoaDon->user->Hoten ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số điện thoại:</div>
                        <div class="info-value">{{ $hoaDon->user->SDT ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $hoaDon->user->Email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Địa chỉ:</div>
                        <div class="info-value">{{ $hoaDon->user->DiaChi ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="payment-info">
                    <div class="info-title">THÔNG TIN THANH TOÁN</div>
                    <div class="info-row">
                        <div class="info-label">Ngày thanh toán:</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($hoaDon->Ngaythanhtoan)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phương thức:</div>
                        <div class="info-value">{{ $hoaDon->phuongThuc->TenPT ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Trạng thái:</div>
                        <div class="info-value">{{ $hoaDon->trangThai->Tentrangthai ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phòng:</div>
                        <div class="info-value">{{ $hoaDon->phong->Tenphong ?? 'N/A' }}</div>
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
                    @if($hoaDon->datLich && $hoaDon->datLich->dichVu)
                    <tr>
                        <td>1</td>
                        <td>{{ $hoaDon->datLich->dichVu->Tendichvu }}</td>
                        <td>{{ \Carbon\Carbon::parse($hoaDon->datLich->Thoigiandatlich)->format('d/m/Y H:i') }}</td>
                        <td class="text-right">{{ number_format($hoaDon->datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</td>
                        <td class="text-right">{{ number_format($hoaDon->datLich->dichVu->Gia, 0, ',', '.') }} VNĐ</td>
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
                    <div>{{ number_format($hoaDon->Tongtien, 0, ',', '.') }} VNĐ</div>
                </div>
                <div class="summary-row">
                    <div>Thuế VAT (10%):</div>
                    <div>{{ number_format($hoaDon->Tongtien * 0.1, 0, ',', '.') }} VNĐ</div>
                </div>
                <div class="summary-row">
                    <div>Giảm giá:</div>
                    <div>0 VNĐ</div>
                </div>
                <div class="summary-row">
                    <div class="total-label">Tổng thanh toán:</div>
                    <div class="total-value">{{ number_format($hoaDon->Tongtien * 1.1, 0, ',', '.') }} VNĐ</div>
                </div>
            </div>
            
            @if($hoaDon->lsDiemThuong->isNotEmpty())
            <div class="reward-points">
                <i class="fas fa-star"></i>
                <div>
                    <strong>Điểm thưởng:</strong> Quý khách được cộng {{ $hoaDon->lsDiemThuong->first()->Sodiem }} điểm vào tài khoản thành viên.
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
            
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-title">Người lập hóa đơn</div>
                    <div class="signature-line"></div>
                    <div>Nhân viên Rosa Spa Beauty</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-title">Khách hàng</div>
                    <div class="signature-line"></div>
                    <div>{{ $hoaDon->user->Hoten ?? 'Khách hàng' }}</div>
                </div>
            </div>
            
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=SPABILL{{ $hoaDon->MaHD }}" alt="QR Code">
                <div style="margin-top: 5px; font-size: 12px; color: #6c757d;">Quét mã để xem chi tiết hóa đơn</div>
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
    
    <script>
        // Tự động in khi trang được tải xong nếu có tham số print=true
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                window.print();
            }
        });
    </script>
</body>
</html>