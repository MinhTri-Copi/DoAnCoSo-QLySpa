@extends('customer.layouts.app')

@section('title', 'Đặt lịch dịch vụ')

@section('styles')
<style>
    :root {
        --primary-color: #ff6b9d;
        --primary-hover: #ff4785;
        --secondary-color: #f8f9fa;
        --text-color: #333;
        --border-color: #e1e1e1;
    }

    .booking-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .booking-header {
        background: linear-gradient(135deg, #ff6b9d 0%, #ff8db3 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .booking-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .booking-steps::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e1e1e1;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: white;
        border: 2px solid var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--primary-color);
    }

    .step.active .step-number {
        background-color: var(--primary-color);
        color: white;
    }

    .step-label {
        font-size: 0.9rem;
        color: #666;
    }

    .step.active .step-label {
        color: var(--primary-color);
        font-weight: bold;
    }

    /* Customer info styles */
    .user-info-hoten, .user-info-email, .user-info-sdt, .user-info-diachi {
        font-weight: 600;
        color: var(--text-color);
    }
    
    .user-info-missing {
        color: #dc3545;
        font-style: italic;
        font-weight: 400;
    }
    
    .card-title {
        color: var(--primary-color);
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    /* End customer info styles */

    .service-card {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .service-image {
        height: 200px;
        overflow: hidden;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-card:hover .service-image img {
        transform: scale(1.05);
    }

    .service-info {
        padding: 1rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .service-title {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .service-price {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .service-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.5rem;
        border-top: 1px solid var(--border-color);
        position: relative;
    z-index: 10; /* Đảm bảo nút nằm trên các phần tử khác */
    }

    .service-duration {
        font-size: 0.8rem;
        color: #666;
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

    .filter-card {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-title {
        font-weight: bold;
        margin-bottom: 1rem;
        color: var(--text-color);
    }

    .date-selector {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding-bottom: 10px;
        margin-bottom: 1.5rem;
    }

    .date-item {
        min-width: 70px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .date-item:hover {
        border-color: var(--primary-color);
    }

    .date-item.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .date-day {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .date-month {
        font-size: 0.8rem;
    }

    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 1rem;
    }

    .time-slot {
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .time-slot:hover:not(.disabled) {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .time-slot.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .time-slot.disabled {
        background-color: #f5f5f5;
        color: #aaa;
        cursor: not-allowed;
    }

    .recommended-services {
        margin-bottom: 2rem;
    }

    .section-title {
        font-weight: bold;
        margin-bottom: 1rem;
        color: var(--text-color);
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--primary-color);
    }

    .booking-summary {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        position: sticky;
        top: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #666;
    }

    .summary-value {
        font-weight: bold;
        color: var(--text-color);
    }

    .summary-total {
        font-size: 1.2rem;
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .booking-steps {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .booking-steps::before {
            display: none;
        }

        .step {
            flex-direction: row;
            gap: 1rem;
        }

        .step-number {
            margin-bottom: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-container py-5">
    <div class="booking-header">
        <h1 class="mb-2">Đặt lịch dịch vụ</h1>
        <p class="mb-0">Chọn dịch vụ và thời gian phù hợp với bạn</p>
    </div>

    <div class="booking-steps mb-4">
    <div class="step {{ $step == 1 ? 'active' : '' }}">
    <div class="step-number">1</div>
    <div class="step-label">Chọn dịch vụ</div>
</div>
<div class="step {{ $step == 2 ? 'active' : '' }}" id="step2">
    <div class="step-number">2</div>
    <div class="step-label">Chọn ngày và giờ</div>
</div>
<div class="step {{ $step == 3 ? 'active' : '' }}" id="step3">
    <div class="step-number">3</div>
    <div class="step-label">Xác nhận thông tin</div>
</div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Step 1: Service Selection -->
            <div id="service-selection" style="{{ $step == 1 ? '' : 'display: none;' }}" class="mb-4">                @if(count($recommendedServices) > 0)
                <div class="recommended-services mb-4">
                    <h3 class="section-title">Dịch vụ đề xuất</h3>
                    <div class="row">
                        @foreach($recommendedServices as $service)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="service-card">
                                <div class="service-image">
                                    <img src="{{ $service->Image ? asset($service->Image) : asset('images/default-service.jpg') }}" alt="{{ $service->Tendichvu }}">
                                </div>
                                <div class="service-info">
                                    <h5 class="service-title">{{ $service->Tendichvu }}</h5>
                                    <div class="service-price">{{ $service->getFormattedPriceAttribute() }}</div>
                                    <div class="service-description">{{ Str::limit($service->MoTa, 100) }}</div>
                                    <div class="service-footer">
                                        <div class="service-duration">
                                            <i class="far fa-clock"></i> {{ $service->Thoigian }} phút
                                        </div>
                                        <form action="{{ route('customer.datlich.create') }}" method="GET">
                                            <input type="hidden" name="service_id" value="{{ $service->MaDV }}">
                                            <input type="hidden" name="step" value="2">
                                            <button type="submit" class="btn btn-sm btn-primary select-service">
                                                Chọn
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(count($userPreferredServices) > 0)
                <div class="user-preferred-services mb-4">
                    <h3 class="section-title">Dịch vụ bạn có thể thích</h3>
                    <div class="row">
                        @foreach($userPreferredServices as $service)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="service-card">
                                <div class="service-image">
                                    <img src="{{ $service->Image ? asset($service->Image) : asset('images/default-service.jpg') }}" alt="{{ $service->Tendichvu }}">
                                </div>
                                <div class="service-info">
                                    <h5 class="service-title">{{ $service->Tendichvu }}</h5>
                                    <div class="service-price">{{ $service->getFormattedPriceAttribute() }}</div>
                                    <div class="service-description">{{ Str::limit($service->MoTa, 100) }}</div>
                                    <div class="service-footer">
                                        <div class="service-duration">
                                            <i class="far fa-clock"></i> {{ $service->Thoigian }} phút
                                        </div>
                                        <form action="{{ route('customer.datlich.create') }}" method="GET">
    <input type="hidden" name="service_id" value="{{ $service->MaDV }}">
    <input type="hidden" name="step" value="2">
    <button type="submit" class="btn btn-sm btn-primary select-service">
        Chọn
    </button>
</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="all-services">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="section-title mb-0">Tất cả dịch vụ</h3>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sắp xếp
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Giá: Thấp đến cao</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Giá: Cao đến thấp</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}">Tên: A-Z</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}">Tên: Z-A</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">Phổ biến nhất</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                        </div>
                    </div>

                    <div class="collapse mb-4" id="filterCollapse">
                        <div class="filter-card">
                            <form action="{{ route('customer.datlich.create') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <!-- Placeholder for layout balance -->
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="price_min" class="form-label">Giá tối thiểu</label>
                                        <input type="number" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="price_max" class="form-label">Giá tối đa</label>
                                        <input type="number" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="search" class="form-label">Tìm kiếm</label>
                                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nhập tên dịch vụ...">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="date" class="form-label">Ngày</label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ request('date', $selectedDate) }}" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-12 text-end">
                                        <a href="{{ route('customer.datlich.create') }}" class="btn btn-outline-secondary me-2">Đặt lại</a>
                                        <button type="submit" class="btn btn-primary">Áp dụng</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($dichVus as $dichVu)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="service-card">
                                <div class="service-image">
                                    <img src="{{ $dichVu->Image ? asset($dichVu->Image) : asset('images/default-service.jpg') }}" alt="{{ $dichVu->Tendichvu }}">
                                </div>
                                <div class="service-info">
                                    <h5 class="service-title">{{ $dichVu->Tendichvu }}</h5>
                                    <div class="service-price">{{ $dichVu->getFormattedPriceAttribute() }}</div>
                                    <div class="service-description">{{ Str::limit($dichVu->MoTa, 100) }}</div>
                                    <div class="service-footer">
                                        <div class="service-duration">
                                            <i class="far fa-clock"></i> {{ $dichVu->Thoigian }} phút
                                        </div>
                                        <form action="{{ route('customer.datlich.create') }}" method="GET">
    <input type="hidden" name="service_id" value="{{ $dichVu->MaDV }}">
    <input type="hidden" name="step" value="2">
    <button type="submit" class="btn btn-sm btn-primary select-service">
        Chọn
    </button>
</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Không tìm thấy dịch vụ nào phù hợp với tiêu chí tìm kiếm.
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $dichVus->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            <!-- Step 2: Date & Time Selection -->
            <div id="datetime-selection" style="{{ $step == 2 ? '' : 'display: none;' }}" class="mb-4">                <h3 class="section-title">Chọn ngày và giờ</h3>
            <input type="hidden" id="booking_date" name="booking_date" value="{{ $selectedDate }}">
    <div class="mb-3">
        <strong>Ngày đã chọn:</strong> <span id="summary-date">{{ Carbon\Carbon::parse($selectedDate)->format('l, d/m/Y') }}</span>
    </div>
                <div class="date-selector mb-4">
                    @foreach($availableDates as $date)
                    <div class="date-item {{ $date['date'] == $selectedDate ? 'active' : '' }}" data-date="{{ $date['date'] }}">
                        <div class="date-day">{{ $date['day'] }}</div>
                        <div class="date-month">{{ $date['month'] }}/{{ $date['year'] }}</div>
                        <div class="date-weekday">{{ $date['day_short'] }}</div>
                    </div>
                    @endforeach
                </div>

                <div id="time-selection" class="mb-4">
                    <h5>Chọn giờ</h5>
                    <div class="time-slots" id="time-slots-container">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Đang tải các khung giờ có sẵn...</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-outline-secondary back-to-services">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                    <button class="btn btn-primary continue-to-confirm" disabled>
                        Tiếp tục <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div id="booking-confirmation" style="{{ $step == 3 ? '' : 'display: none;' }}">                <h3 class="section-title">Xác nhận thông tin đặt lịch</h3>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin khách hàng</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Họ tên:</strong> <span class="user-info-hoten">{{ Auth::user()->Hoten ?: 'Chưa cập nhật' }}</span></p>
                                <p class="mb-1"><strong>Email:</strong> <span class="user-info-email">{{ Auth::user()->Email ?: 'Chưa cập nhật' }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Số điện thoại:</strong> <span class="user-info-sdt">{{ Auth::user()->SDT ?: 'Chưa cập nhật' }}</span></p>
                                <p class="mb-1"><strong>Địa chỉ:</strong> <span class="user-info-diachi">{{ Auth::user()->DiaChi ?: 'Chưa cập nhật' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="booking-form" action="{{ route('customer.datlich.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="service_id" id="service_id">
                    <input type="hidden" name="booking_date" id="booking_date">
                    <input type="hidden" name="booking_time" id="booking_time">

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Ghi chú</h5>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Nhập ghi chú hoặc yêu cầu đặc biệt (nếu có)"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary back-to-datetime">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Xác nhận đặt lịch <i class="fas fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="booking-summary">
                <h4 class="mb-4">Thông tin đặt lịch</h4>
                <div id="empty-summary" class="text-center py-4">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <p>Vui lòng chọn dịch vụ để tiếp tục</p>
                </div>
                <div id="booking-summary-content" style="display: none;">
            <div class="summary-item">
                <div class="summary-label">Dịch vụ</div>
                <div class="summary-value" id="summary-service">-</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Thời gian</div>
                <div class="summary-value" id="summary-duration">-</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Ngày</div>
                <div class="summary-value" id="summary-date">-</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Giờ</div>
                <div class="summary-value" id="summary-time">-</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Giá</div>
                <div class="summary-value summary-total" id="summary-price">-</div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let selectedService = @json($selectedService ?? null);
        let selectedDate = "{{ $selectedDate }}";
        let selectedTime = null;
        let isLoading = false;

        // Initialize user info on page load for step 3
        if ("{{ $step }}" == 3) {
            console.log('Page loaded directly to step 3, loading user info...');
            loadUserInfo();
        }

        // Khởi tạo thông tin tóm tắt nếu đã chọn dịch vụ
        if (selectedService) {
            $('#summary-service').text(selectedService.Tendichvu);
            $('#summary-duration').text(selectedService.Thoigian + ' phút');
            $('#summary-price').text(selectedService.getFormattedPriceAttribute);
            $('#service_id').val(selectedService.MaDV);
            $('#empty-summary').hide();
            $('#booking-summary-content').show();
            
            // Hiển thị ngày được chọn trong phần tóm tắt
            updateDateDisplay(selectedDate);
            
            loadTimeSlots();
        }

        // Step 2: Date & Time Selection
        $('.date-item').click(function() {
            $('.date-item').removeClass('active');
            $(this).addClass('active');
            selectedDate = $(this).data('date');
            $('#booking_date').val(selectedDate);
            updateDateDisplay(selectedDate);
            selectedTime = null;
            $('#booking_time').val('');
            $('#summary-time').text('-');
            $('.continue-to-confirm').prop('disabled', true);
            loadTimeSlots();
        });

        // Back to service selection
        $('.back-to-services').click(function() {
            window.location.href = "{{ route('customer.datlich.create') }}";
        });

        // Continue to confirmation
        $('.continue-to-confirm').click(function() {
            if (!selectedService) {
                alert('Vui lòng chọn dịch vụ');
                return;
            }
            if (!selectedDate) {
                alert('Vui lòng chọn ngày');
                return;
            }
            if (!selectedTime) {
                alert('Vui lòng chọn giờ');
                return;
            }
            $('#datetime-selection').hide();
            $('#booking-confirmation').show();
            $('#step2').removeClass('active');
            $('#step3').addClass('active');
            
            // Ensure customer info is loaded properly
            loadUserInfo();
        });

        // Back to datetime selection
        $('.back-to-datetime').click(function() {
            $('#booking-confirmation').hide();
            $('#datetime-selection').show();
            $('#step3').removeClass('active');
            $('#step2').addClass('active');
        });

        // Load time slots
        function loadTimeSlots() {
    if (!selectedService || !selectedDate) {
        $('#time-slots-container').html('<div class="alert alert-warning">Vui lòng chọn dịch vụ và ngày.</div>');
        return;
    }

    $('#time-slots-container').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Đang tải các khung giờ có sẵn...</p>
        </div>
    `);

    $.ajax({
        url: "{{ route('customer.datlich.checkAvailability') }}",
        type: "GET",
        data: {
            service_id: selectedService.MaDV,
            date: selectedDate
        },
        success: function(response) {
            if (!response.available) {
                $('#time-slots-container').html(`
                    <div class="alert alert-warning">
                        ${response.message}
                    </div>
                `);
                return;
            }

            let html = '';
            if (response.timeSlots.length === 0) {
                html = `
                    <div class="alert alert-info">
                        Không có khung giờ nào khả dụng cho ngày này.
                    </div>
                `;
            } else {
                response.timeSlots.forEach(slot => {
                    html += `
                        <div class="time-slot ${!slot.available ? 'disabled' : ''}" 
                            data-time="${slot.time}" 
                            ${!slot.available ? 'disabled' : ''}>
                            ${slot.time}
                        </div>
                    `;
                });
            }

            $('#time-slots-container').html(html);

            $('.time-slot:not(.disabled)').click(function() {
                $('.time-slot').removeClass('active');
                $(this).addClass('active');
                selectedTime = $(this).data('time');
                $('#booking_time').val(selectedTime);
                $('#summary-time').text(selectedTime);
                $('.continue-to-confirm').prop('disabled', false);
            });
        },
        error: function(xhr, status, error) {
            $('#time-slots-container').html(`
                <div class="alert alert-danger">
                    Đã xảy ra lỗi khi tải khung giờ: ${error}. Vui lòng thử lại.
                </div>
            `);
        },
        complete: function() {
            isLoading = false;
        }
    });
}

        // Format date for display
        function formatDate(dateString) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('vi-VN', options);
        }
        
        // Function to update all date displays
        function updateDateDisplay(date) {
            if (date) {
                const formattedDate = formatDate(date);
                // Update date in the summary section and in the page header
                $('.summary-value#summary-date').text(formattedDate);
                $('span#summary-date').text(formattedDate);
            }
        }

        // Initialize with selected date
        $('#booking_date').val(selectedDate);
        
        // Khởi tạo hiển thị thông tin
        if (selectedDate) {
            updateDateDisplay(selectedDate);
        }
        
        // Hiển thị giá dịch vụ nếu có
        if (selectedService && selectedService.Gia) {
            $('#summary-price').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(selectedService.Gia));
        }

        // Function to load user information
        function loadUserInfo() {
            console.log('Loading user information...');
            // Get user data from server to ensure it's fresh
            $.ajax({
                url: "{{ route('customer.getUserInfo') }}",
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    console.log('User info response:', response);
                    if (response.success) {
                        updateUserInfoDisplay(response.user);
                    } else {
                        console.error('Error in user info response:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading user information:", error);
                    console.error("Response:", xhr.responseText);
                    // Fall back to using the existing DOM content
                    updateUserInfoDisplay();
                }
            });
        }
        
        // Update user info display
        function updateUserInfoDisplay(user) {
    console.log('Updating user info display with:', user);
    
    // Nếu không có dữ liệu từ API, sử dụng dữ liệu từ Blade
    if (!user) {
        user = {
            Hoten: "{{ Auth::user()->Hoten }}",
            Email: "{{ Auth::user()->Email }}",
            SDT: "{{ Auth::user()->SDT }}",
            DiaChi: "{{ Auth::user()->DiaChi }}"
        };
        console.log('Using fallback user data:', user);
    }
    
    // Cập nhật từng trường
    $('.user-info-hoten').text(user.Hoten && user.Hoten.trim() ? user.Hoten : 'Chưa cập nhật');
    $('.user-info-email').text(user.Email && user.Email.trim() ? user.Email : 'Chưa cập nhật');
    $('.user-info-sdt').text(user.SDT && user.SDT.trim() ? user.SDT : 'Chưa cập nhật');
    $('.user-info-diachi').text(user.DiaChi && user.DiaChi.trim() ? user.DiaChi : 'Chưa cập nhật');
}
    });
</script>
@endsection
