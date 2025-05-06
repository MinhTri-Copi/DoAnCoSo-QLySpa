@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/customers.css') }}" rel="stylesheet">
<style>
    /* Spa-themed styles for edit page */
    .page-heading {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border-radius: 10px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-heading::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background-image: url('/img/zen-pattern.png');
        background-size: cover;
        opacity: 0.1;
    }
    
    .form-control-spa {
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }
    
    .form-control-spa:focus {
        border-color: var(--spa-primary);
        box-shadow: 0 0 0 0.2rem rgba(131, 197, 190, 0.25);
    }
    
    .btn-spa {
        border-radius: 50px;
        padding: 0.7rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .btn-spa-primary {
        background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark));
        border: none;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 109, 119, 0.2);
    }
    
    .btn-spa-secondary {
        background: #f8f9fa;
        border: 1px solid #e2e8f0;
        color: var(--spa-dark);
    }
    
    .btn-spa:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 109, 119, 0.3);
    }
    
    .form-group label {
        font-weight: 600;
        color: var(--spa-dark);
        margin-bottom: 0.5rem;
    }
    
    .form-group small {
        color: var(--spa-gray);
    }
    
    .radio-spa {
        margin-right: 1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    
    .radio-spa input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .radio-spa-checkmark {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 8px;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .radio-spa input:checked ~ .radio-spa-checkmark {
        border-color: var(--spa-primary);
    }
    
    .radio-spa-checkmark:after {
        content: "";
        position: absolute;
        display: none;
        top: 3px;
        left: 3px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--spa-primary);
    }
    
    .radio-spa input:checked ~ .radio-spa-checkmark:after {
        display: block;
    }
    
    .membership-select {
        background-color: white;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 0.75rem 1.5rem;
    }
    
    .membership-select option {
        padding: 10px;
    }
    
    .timeline-spa {
        position: relative;
        padding-left: 1.5rem;
        margin: 0 0 0 1rem;
    }
    
    .timeline-spa::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 2px;
        background: var(--spa-primary-light);
    }
    
    .timeline-item-spa {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-marker-spa {
        position: absolute;
        left: -1.5rem;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--spa-primary);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--spa-primary-light);
    }
    
    .timeline-date-spa {
        font-size: 0.8rem;
        color: var(--spa-gray);
        margin-bottom: 0.5rem;
    }
    
    .timeline-content-spa {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
    }
    
    .input-group-spa {
        position: relative;
    }
    
    .input-group-spa i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--spa-gray);
        z-index: 10;
    }
    
    .input-group-spa .form-control {
        padding-left: 40px;
        border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-heading mb-4">
        <div class="row">
            <div class="col-md-6">
                <h1 class="h3 mb-2 text-white font-weight-bold">Chỉnh Sửa Khách Hàng</h1>
                <p class="mb-0 text-white opacity-75">
                    <i class="fas fa-user-edit mr-1"></i> Cập nhật và quản lý thông tin khách hàng của bạn
                </p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.customers.show', $customer->Manguoidung) }}" class="btn btn-spa btn-spa-secondary mr-2">
                    <i class="fas fa-eye mr-1"></i> Xem Chi Tiết
                </a>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-spa btn-spa-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Edit Form -->
        <div class="col-lg-8">
            <div class="spa-card mb-4 slide-up">
                <div class="spa-card-header d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                        <i class="fas fa-user-circle mr-2"></i>Thông Tin Khách Hàng
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="btn btn-sm btn-action" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: var(--spa-light);">
                            <i class="fas fa-ellipsis-v text-muted"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy Chọn:</div>
                            <a class="dropdown-item" href="{{ route('admin.customers.show', $customer->Manguoidung) }}">
                                <i class="fas fa-eye fa-sm fa-fw mr-2 text-muted"></i>
                                Xem Chi Tiết
                            </a>
                            <a class="dropdown-item text-danger" href="{{ route('admin.customers.confirmDestroy', $customer->Manguoidung) }}">
                                <i class="fas fa-trash fa-sm fa-fw mr-2"></i>
                                Xóa Khách Hàng
                            </a>
                        </div>
                    </div>
                </div>
                <div class="spa-card-body">
                    <form action="{{ route('admin.customers.update', $customer->Manguoidung) }}" method="POST" id="editCustomerForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Manguoidung">Mã Khách Hàng</label>
                                    <input type="text" class="form-control form-control-spa" id="Manguoidung" value="{{ $customer->Manguoidung }}" readonly>
                                    <small class="form-text text-muted">Mã khách hàng không thể thay đổi</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="MaTK">Tài Khoản <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-spa @error('MaTK') is-invalid @enderror" id="MaTK" name="MaTK">
                                        <option value="">-- Chọn Tài Khoản --</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->MaTK }}" {{ $customer->MaTK == $account->MaTK ? 'selected' : '' }}>
                                                {{ $account->Tendangnhap }} ({{ $account->MaTK }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('MaTK')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="Hoten">Họ Tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-spa @error('Hoten') is-invalid @enderror" 
                                        id="Hoten" name="Hoten" value="{{ old('Hoten', $customer->Hoten) }}">
                                    @error('Hoten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="SDT">Số Điện Thoại <span class="text-danger">*</span></label>
                                    <div class="input-group-spa">
                                        <i class="fas fa-phone"></i>
                                        <input type="text" class="form-control @error('SDT') is-invalid @enderror" 
                                            id="SDT" name="SDT" value="{{ old('SDT', $customer->SDT) }}">
                                    </div>
                                    @error('SDT')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Email">Email <span class="text-danger">*</span></label>
                                    <div class="input-group-spa">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" class="form-control @error('Email') is-invalid @enderror" 
                                            id="Email" name="Email" value="{{ old('Email', $customer->Email) }}">
                                    </div>
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="DiaChi">Địa Chỉ <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-spa @error('DiaChi') is-invalid @enderror" 
                                        id="DiaChi" name="DiaChi" rows="3" style="border-radius: 20px;">{{ old('DiaChi', $customer->DiaChi) }}</textarea>
                                    @error('DiaChi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="Ngaysinh">Ngày Sinh <span class="text-danger">*</span></label>
                                    <div class="input-group-spa">
                                        <i class="fas fa-calendar-alt"></i>
                                        <input type="date" class="form-control @error('Ngaysinh') is-invalid @enderror" 
                                            id="Ngaysinh" name="Ngaysinh" value="{{ old('Ngaysinh', $customer->Ngaysinh) }}">
                                    </div>
                                    @error('Ngaysinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>Giới Tính <span class="text-danger">*</span></label>
                                    <div style="margin-top: 8px;">
                                        <label class="radio-spa">
                                            <input type="radio" name="Gioitinh" value="Nam" 
                                                {{ old('Gioitinh', $customer->Gioitinh) == 'Nam' ? 'checked' : '' }}>
                                            <span class="radio-spa-checkmark"></span>
                                            Nam
                                        </label>
                                        <label class="radio-spa">
                                            <input type="radio" name="Gioitinh" value="Nữ"
                                                {{ old('Gioitinh', $customer->Gioitinh) == 'Nữ' ? 'checked' : '' }}>
                                            <span class="radio-spa-checkmark"></span>
                                            Nữ
                                        </label>
                                    </div>
                                    @error('Gioitinh')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr class="mt-0 mb-4" style="opacity: 0.1;">
                        
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="membership_level">Hạng Thành Viên</label>
                                    <select class="form-control membership-select" id="membership_level" name="membership_level">
                                        <option value="Thường" {{ $customer->hangThanhVien->first() && $customer->hangThanhVien->first()->Tenhang == 'Thường' ? 'selected' : '' }}>Thường</option>
                                        <option value="VIP" {{ $customer->hangThanhVien->first() && $customer->hangThanhVien->first()->Tenhang == 'VIP' ? 'selected' : '' }}>VIP</option>
                                        <option value="Platinum" {{ $customer->hangThanhVien->first() && $customer->hangThanhVien->first()->Tenhang == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                        <option value="Diamond" {{ $customer->hangThanhVien->first() && $customer->hangThanhVien->first()->Tenhang == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                                    </select>
                                    <small class="form-text text-muted mt-2"><i class="fas fa-info-circle mr-1"></i> Nâng cấp hạng thành viên sẽ tự động thêm điểm thưởng</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="membership-display text-center">
                                    @php
                                        $hangName = $customer->hangThanhVien->first() ? $customer->hangThanhVien->first()->Tenhang : 'Thường';
                                        $badgeClass = 'membership-regular';
                                        
                                        if($hangName == 'VIP') {
                                            $badgeClass = 'membership-vip';
                                        } elseif($hangName == 'Platinum') {
                                            $badgeClass = 'membership-platinum';
                                        } elseif($hangName == 'Diamond') {
                                            $badgeClass = 'membership-diamond';
                                        }
                                    @endphp
                                    <div class="spa-badge {{ $badgeClass }}" style="margin: 0 auto; display: inline-flex; transform: scale(1.2);">
                                        @if($hangName != 'Thường')
                                            <i class="fas fa-crown mr-1"></i>
                                        @endif
                                        {{ $hangName }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-spa btn-spa-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-spa btn-spa-primary" id="submitBtn">
                                    <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Customer Activity Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Profile Card -->
            <div class="spa-card mb-4 slide-right">
                <div class="spa-card-header">
                    <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                        <i class="fas fa-id-card mr-2"></i>Thông Tin Tóm Tắt
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="customer-profile-spa">
                        <img src="{{ asset('img/undraw_profile.svg') }}" alt="{{ $customer->Hoten }}" class="customer-profile-avatar-spa">
                        <h4 class="customer-profile-name-spa">{{ $customer->Hoten }}</h4>
                        @php
                            $hangTV = $customer->hangThanhVien->first();
                            $hangName = $hangTV ? $hangTV->Tenhang : 'Thường';
                            $badgeClass = 'membership-regular';
                            
                            if($hangName == 'VIP') {
                                $badgeClass = 'membership-vip';
                            } elseif($hangName == 'Platinum') {
                                $badgeClass = 'membership-platinum';
                            } elseif($hangName == 'Diamond') {
                                $badgeClass = 'membership-diamond';
                            }
                        @endphp
                        <div class="spa-badge {{ $badgeClass }}" style="margin-bottom: 1.5rem;">
                            @if($hangName != 'Thường')
                                <i class="fas fa-crown mr-1"></i>
                            @endif
                            {{ $hangName }}
                        </div>
                        
                        <div class="customer-stats" style="width: 100%; display: flex; justify-content: space-around; margin-top: 0.5rem;">
                            <div class="stat-item text-center">
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--spa-dark);">
                                    {{ $customer->hoaDon->count() }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--spa-gray);">Đơn hàng</div>
                            </div>
                            <div class="stat-item text-center">
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--spa-dark);">
                                    {{ $customer->datLich->count() }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--spa-gray);">Lịch hẹn</div>
                            </div>
                            <div class="stat-item text-center">
                                <div style="font-size: 1.5rem; font-weight: 700; color: var(--spa-dark);">
                                    @php
                                        $pointsEarned = $customer->lsDiemThuong->where('Loai', 'Cộng')->sum('Diem');
                                        $pointsSpent = $customer->lsDiemThuong->where('Loai', 'Trừ')->sum('Diem');
                                        $currentPoints = $pointsEarned - $pointsSpent;
                                    @endphp
                                    {{ $currentPoints }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--spa-gray);">Điểm</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="spa-card mb-4 slide-right" style="animation-delay: 0.2s;">
                <div class="spa-card-header">
                    <h6 class="m-0 font-weight-bold" style="color: var(--spa-dark);">
                        <i class="fas fa-history mr-2"></i>Hoạt Động Gần Đây
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline-spa">
                        @php
                            $recentActivities = collect();
                            
                            // Add orders
                            foreach($customer->hoaDon->take(3) as $order) {
                                $recentActivities->push([
                                    'date' => $order->Ngaytao,
                                    'type' => 'order',
                                    'icon' => 'shopping-cart',
                                    'color' => '#4e73df',
                                    'title' => 'Đặt đơn hàng #' . $order->MaHD,
                                    'content' => 'Tổng giá trị: ' . number_format($order->Tongtien, 0, ',', '.') . ' VNĐ',
                                    'status' => $order->trangThai ? $order->trangThai->Tentrangthai : 'N/A',
                                    'id' => $order->MaHD
                                ]);
                            }
                            
                            // Add appointments
                            foreach($customer->datLich->take(3) as $appointment) {
                                $recentActivities->push([
                                    'date' => $appointment->Ngaydat ?? $appointment->Thoigiandatlich,
                                    'type' => 'appointment',
                                    'icon' => 'calendar-check',
                                    'color' => '#36b9cc',
                                    'title' => 'Đặt lịch hẹn #' . $appointment->MaDL,
                                    'content' => 'Dịch vụ: ' . ($appointment->dichVu ? $appointment->dichVu->Tendichvu : 'N/A'),
                                    'status' => $appointment->Trangthai_ ?? 'N/A',
                                    'id' => $appointment->MaDL
                                ]);
                            }
                            
                            // Add points history
                            foreach($customer->lsDiemThuong->take(3) as $points) {
                                $color = $points->Loai == 'Cộng' ? '#1cc88a' : '#e74a3b';
                                $icon = $points->Loai == 'Cộng' ? 'plus-circle' : 'minus-circle';
                                
                                $recentActivities->push([
                                    'date' => $points->Ngay,
                                    'type' => 'points',
                                    'icon' => 'coins',
                                    'color' => $color,
                                    'title' => $points->Loai . ' ' . $points->Diem . ' điểm',
                                    'content' => $points->Ghichu,
                                    'status' => '',
                                    'id' => $points->MaLS
                                ]);
                            }
                            
                            // Sort by date
                            $recentActivities = $recentActivities->sortByDesc('date')->take(5);
                        @endphp
                        
                        @forelse($recentActivities as $activity)
                            <div class="timeline-item-spa">
                                <div class="timeline-marker-spa" style="background-color: {{ $activity['color'] }};"></div>
                                <div class="timeline-date-spa">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y H:i') }}
                                </div>
                                <div class="timeline-content-spa">
                                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                        <div style="width: 28px; height: 28px; background-color: {{ $activity['color'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                            <i class="fas fa-{{ $activity['icon'] }}" style="color: white; font-size: 0.8rem;"></i>
                                        </div>
                                        <div style="font-weight: 600; color: var(--spa-dark);">{{ $activity['title'] }}</div>
                                    </div>
                                    <div style="color: var(--spa-text); font-size: 0.9rem; margin-left: 42px;">
                                        {{ $activity['content'] }}
                                    </div>
                                    @if(!empty($activity['status']))
                                        <div style="margin-top: 0.5rem; margin-left: 42px;">
                                            @php
                                                $statusColor = '#858796';
                                                if ($activity['status'] == 'Hoàn thành') {
                                                    $statusColor = '#1cc88a';
                                                } elseif ($activity['status'] == 'Đang xử lý' || $activity['status'] == 'Chờ xác nhận') {
                                                    $statusColor = '#f6c23e';
                                                } elseif ($activity['status'] == 'Đã hủy') {
                                                    $statusColor = '#e74a3b';
                                                }
                                            @endphp
                                            <span class="spa-badge" style="background-color: rgba({{ hexToRgb($statusColor) }}, 0.1); color: {{ $statusColor }};">
                                                {{ $activity['status'] }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                                <p class="mb-0" style="color: var(--spa-gray);">Không có hoạt động gần đây</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
function hexToRgb($hex) {
    // Remove the hash
    $hex = ltrim($hex, '#');
    
    // Parse the hex code
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    return "$r, $g, $b";
}
@endphp
@endsection

@section('scripts')
<script src="{{ asset('js/admin/customers/edit.js') }}"></script>
@endsection