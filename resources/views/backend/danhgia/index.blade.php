@extends('backend.layouts.app')

@section('title', 'Quản Lý Đánh Giá')

@section('styles')
<style>
    body {
        background-color: #ffebf3 !important;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #e83e8c, #fd7e97);
        color: white;
        border-radius: 10px;
        padding: 20px 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.3);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-in-out;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .welcome-banner p {
        font-size: 1rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    
    .shine-line {
        position: absolute;
        top: 0;
        left: -150%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 3s infinite;
        transform: skewX(-25deg);
    }
    
    @keyframes shine {
        0% { left: -150%; }
        100% { left: 150%; }
    }

    .custom-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    .bg-pink-500 {
        background-color: #e83e8c !important;
    }
    .bg-pink-100 {
        background-color: #ffe0e9 !important;
    }
    .text-pink-500 {
        color: #e83e8c !important;
    }
    .bg-blue-100 {
        background-color: #e1f5fe !important;
    }
    .text-blue-500 {
        color: #03a9f4 !important;
    }
    .bg-green-100 {
        background-color: #e8f5e9 !important;
    }
    .text-green-500 {
        color: #4caf50 !important;
    }
    .btn-pink {
        background-color: #e83e8c;
        border-color: #e83e8c;
        color: white;
        transition: all 0.3s;
    }
    .btn-pink:hover {
        background-color: #d33077;
        border-color: #d33077;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(232, 62, 140, 0.3);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e83e8c;
    }
    .table-hover tbody tr:hover {
        background-color: #fff9fb;
    }
    .badge-rating {
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        background-color: #e83e8c;
        color: white;
    }
    
    .btn-export {
        background-color: white;
        color: #e83e8c;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .btn-export:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h1><i class="fas fa-spa"></i> Quản Lý Đánh Giá</h1>
        <p>Tối ưu trải nghiệm và phục vụ khách hàng tốt nhất</p>
        <div class="shine-line"></div>
        
        <div class="position-absolute" style="top: 20px; right: 20px;">
            <a href="{{ route('admin.danhgia.export') }}" class="btn-export">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thống kê tổng số đánh giá -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-white mb-4 custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-pink-100 p-3 me-3">
                            <i class="fas fa-comments text-pink-500 fa-2x"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $totalReviews }}</h2>
                            <div class="text-muted">Tổng Đánh Giá</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê đánh giá tháng này -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-white mb-4 custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-blue-100 p-3 me-3">
                            <i class="fas fa-calendar-alt text-blue-500 fa-2x"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $currentMonthReviews }}</h2>
                            <div class="text-muted">Đánh Giá Mới (Tháng Này)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê phần trăm đánh giá tốt -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-white mb-4 custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-green-100 p-3 me-3">
                            <i class="fas fa-thumbs-up text-green-500 fa-2x"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $goodReviewPercentage }}%</h2>
                            <div class="text-muted">Tỷ Lệ Đánh Giá Tốt</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1 text-pink-500"></i>
                <span class="fw-bold">Danh Sách Đánh Giá</span>
            </div>
            <a href="{{ route('admin.danhgia.create') }}" class="btn btn-pink">
                <i class="fas fa-plus me-1"></i> Thêm đánh giá
            </a>
        </div>
        <div class="card-body">
            <!-- Bộ lọc -->
            <div class="mb-4 p-3 bg-light rounded">
                <form action="{{ route('admin.danhgia.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="star_rating" class="form-label fw-bold text-secondary">
                            <i class="fas fa-star text-warning me-1"></i> Đánh giá sao
                        </label>
                        <select name="star_rating" id="star_rating" class="form-select">
                            <option value="">Tất cả</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('star_rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} sao
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="room_id" class="form-label fw-bold text-secondary">
                            <i class="fas fa-door-open me-1"></i> Phòng
                        </label>
                        <select name="room_id" id="room_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->Maphong }}" {{ request('room_id') == $room->Maphong ? 'selected' : '' }}>
                                    {{ $room->Tenphong }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="from_date" class="form-label fw-bold text-secondary">
                            <i class="fas fa-calendar me-1"></i> Từ ngày
                        </label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="to_date" class="form-label fw-bold text-secondary">
                            <i class="fas fa-calendar-check me-1"></i> Đến ngày
                        </label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-pink me-2">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo-alt me-1"></i> Đặt lại
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bảng đánh giá -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="80">Mã ĐG</th>
                            <th>Khách hàng</th>
                            <th>Liên hệ</th>
                            <th class="text-center">Đánh giá</th>
                            <th>Nhận xét</th>
                            <th>Phòng</th>
                            <th>Ngày đánh giá</th>
                            <th class="text-center" width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($danhGias as $danhGia)
                            <tr>
                                <td class="text-center fw-bold">{{ $danhGia->MaDG }}</td>
                                <td>
                                    @if ($danhGia->user)
                                        <span class="fw-bold">{{ $danhGia->user->Hoten ?? 'N/A' }}</span>
                                    @else
                                        <span class="text-muted">Không có thông tin</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($danhGia->user)
                                        <div><i class="fas fa-phone-alt me-1 text-secondary"></i> {{ $danhGia->user->Sodienthoai ?? 'N/A' }}</div>
                                        <div><i class="fas fa-envelope me-1 text-secondary"></i> {{ $danhGia->user->Email ?? 'N/A' }}</div>
                                    @else
                                        <span class="text-muted">Không có thông tin</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="badge-rating">
                                        {{ $danhGia->Danhgiasao }}/5
                                        <i class="fas fa-star ms-1"></i>
                                    </div>
                                </td>
                                <td>{{ Str::limit($danhGia->Nhanxet, 50) }}</td>
                                <td>
                                    @if ($danhGia->hoaDon && $danhGia->hoaDon->phong)
                                        <span class="fw-bold">{{ $danhGia->hoaDon->phong->Tenphong ?? 'N/A' }}</span>
                                    @else
                                        <span class="text-muted">Không có thông tin</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($danhGia->Ngaydanhgia)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.danhgia.show', $danhGia->MaDG) }}" class="btn btn-info btn-sm me-1" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.danhgia.edit', $danhGia->MaDG) }}" class="btn btn-primary btn-sm me-1" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.danhgia.confirmDestroy', $danhGia->MaDG) }}" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <img src="{{ asset('admin/images/no-data.svg') }}" alt="No Data" width="120" onerror="this.src='https://cdn-icons-png.flaticon.com/512/7486/7486754.png'">
                                    <p class="mt-3 text-muted">Không có dữ liệu đánh giá</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-end mt-3">
                {{ $danhGias->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Thêm các script JavaScript nếu cần
    });
</script>
@endsection