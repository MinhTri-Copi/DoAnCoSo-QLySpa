@extends('customer.layouts.app')

@section('title', 'Thanh toán hóa đơn #' . $invoice->MaHD)

@section('content')
<div class="container py-5">
    <!-- Welcome Banner -->
    <div class="welcome-banner animate__animated animate__fadeIn mb-4">
        <h1><i class="fas fa-credit-card"></i> Thanh toán hóa đơn</h1>
        <p>Hoàn tất thanh toán hóa đơn #{{ $invoice->MaHD }}</p>
        <div class="shine-line"></div>
    </div>

    <!-- Action buttons -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('customer.hoadon.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
        <a href="{{ route('customer.hoadon.show', $invoice->MaHD) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>Xem chi tiết hóa đơn
        </a>
    </div>

    <div class="row">
        <!-- Payment Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-money-check-alt text-primary me-2"></i>Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.hoadon.processPayment', $invoice->MaHD) }}" method="POST" id="payment-form">
                        @csrf
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chọn phương thức thanh toán</label>
                            <div class="row g-3">
                                @foreach($paymentMethods as $method)
                                <div class="col-md-6">
                                    <div class="form-check payment-method-card border rounded p-3">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                            id="payment{{ $method->MaPT }}" value="{{ $method->MaPT }}" required>
                                        <label class="form-check-label w-100" for="payment{{ $method->MaPT }}">
                                            <div class="d-flex align-items-center">
                                                @if($method->TenPT == 'Tiền mặt')
                                                    <i class="fas fa-money-bill-wave fa-2x text-success me-3"></i>
                                                @elseif($method->TenPT == 'Chuyển khoản')
                                                    <i class="fas fa-university fa-2x text-primary me-3"></i>
                                                @elseif($method->TenPT == 'Thẻ tín dụng')
                                                    <i class="far fa-credit-card fa-2x text-danger me-3"></i>
                                                @elseif($method->TenPT == 'Ví điện tử')
                                                    <i class="fas fa-wallet fa-2x text-warning me-3"></i>
                                                @else
                                                    <i class="fas fa-money-check-alt fa-2x text-info me-3"></i>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $method->TenPT }}</div>
                                                    @if($method->MaPT == 2) <!-- Giả sử 2 là chuyển khoản -->
                                                    <div class="small text-muted">Rosa Spa Beauty - 0123456789 - Techcombank</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('payment_method')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" value="1" required>
                                <label class="form-check-label" for="agree_terms">
                                    Tôi đồng ý với <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản thanh toán</a> và xác nhận thông tin đã chính xác
                                </label>
                            </div>
                            @error('agree_terms')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Xác nhận thanh toán
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 position-sticky" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-receipt text-primary me-2"></i>Tóm tắt hóa đơn</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">Dịch vụ:</div>
                        <div class="fw-bold">{{ $invoice->datLich->dichVu->Tendichvu ?? 'N/A' }}</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">Thời gian:</div>
                        <div>{{ \Carbon\Carbon::parse($invoice->datLich->Thoigiandatlich ?? now())->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">Giá dịch vụ:</div>
                        <div>{{ number_format($invoice->Tongtien, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">Thuế VAT (10%):</div>
                        <div>{{ number_format($invoice->Tongtien * 0.1, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="fw-bold">Tổng thanh toán:</div>
                        <div class="fw-bold fs-5 text-primary">{{ number_format($invoice->Tongtien * 1.1, 0, ',', '.') }} VNĐ</div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <i class="fas fa-info-circle fa-lg mt-1 me-2"></i>
                            <div>
                                <div class="fw-bold">Chính sách điểm thưởng:</div>
                                <p class="mb-0 small">Với mỗi hóa đơn, bạn sẽ nhận được điểm thưởng dựa trên giá trị đơn hàng! Số điểm này sẽ được tích lũy để nâng hạng thành viên.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Điều khoản thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Chính sách thanh toán</h6>
                <p>Khi xác nhận thanh toán, quý khách đồng ý với các điều khoản dịch vụ của Rosa Spa Beauty và đồng ý thanh toán đầy đủ số tiền được hiển thị.</p>
                
                <h6>2. Quy định hoàn hủy</h6>
                <p>- Trường hợp huỷ lịch trước 24 giờ: Hoàn tiền 100%<br>
                - Trường hợp huỷ lịch trong vòng 12-24 giờ: Hoàn tiền 50%<br>
                - Trường hợp huỷ lịch trong vòng dưới 12 giờ: Không hoàn tiền</p>
                
                <h6>3. Bảo mật thông tin</h6>
                <p>Mọi thông tin thanh toán của quý khách sẽ được bảo mật theo chính sách của Rosa Spa Beauty.</p>
                
                <h6>4. Chính sách điểm thưởng</h6>
                <p>Với mỗi giao dịch thanh toán thành công, quý khách sẽ nhận được điểm thưởng tương ứng với giá trị đơn hàng:</p>
                <ul>
                    <li>Đơn hàng từ 100,000đ đến dưới 500,000đ: 100 điểm</li>
                    <li>Đơn hàng từ 500,000đ đến dưới 1,000,000đ: 300 điểm</li>
                    <li>Đơn hàng từ 1,000,000đ trở lên: 500 điểm</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-method-card {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .payment-method-card:hover {
        border-color: #ff6b8b !important;
        background-color: #fff9fa;
    }
    
    .form-check-input:checked + label .payment-method-card {
        border-color: #ff6b8b !important;
        background-color: #fff9fa;
    }
    
    .form-check {
        padding-left: 0;
    }
    
    .form-check-input {
        position: absolute;
        opacity: 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentCards = document.querySelectorAll('.payment-method-card');
        
        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Remove active class from all cards
                paymentCards.forEach(c => c.classList.remove('border-primary'));
                
                // Add active class to selected card
                this.classList.add('border-primary');
            });
        });
    });
</script>
@endsection 