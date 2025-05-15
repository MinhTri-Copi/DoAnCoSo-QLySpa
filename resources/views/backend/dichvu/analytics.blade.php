@section('content')
<div class="container-fluid page-analytics-dichvu" style="max-width: 1600px; margin: 0 auto; padding: 0 20px;">
    <!-- Analytics Dashboard -->
    <div class="analytics-dashboard animate__animated animate__fadeIn">
        <!-- Dashboard Header -->
        <div class="analytics-header">
            <div class="header-content">
                <h1 class="analytics-title">Phân Tích Dịch Vụ</h1>
                <p class="analytics-subtitle">
                    <i class="fas fa-chart-line"></i>
                    Thống kê và phân tích dữ liệu về dịch vụ
                </p>
            </div>
            
            <div class="header-actions">
                <a href="{{ route('admin.dichvu.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại danh sách</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 