@extends('backend.layouts.app')

@section('title', 'Chi Tiết Hạng Thành Viên')

@section('content')
<style>
    :root {
        --primary-color: #ff6b8b;
        --primary-light: #ffd0d9;
        --primary-dark: #e84e6f;
        --text-on-primary: #ffffff;
        --secondary-color: #f8f9fa;
        --border-color: #e9ecef;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
    }

    .header-container {
        background-color: var(--primary-color);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        color: var(--text-on-primary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        font-size: 24px;
        font-weight: bold;
    }

    .header-subtitle {
        font-size: 14px;
        margin-top: 5px;
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-white {
        background-color: white;
        color: var(--primary-color);
    }

    .btn-white:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-outline {
        background-color: transparent;
        color: white;
        border: 1px solid white;
    }

    .btn-outline:hover {
        background-color: rgba(255,255,255,0.1);
        transform: translateY(-2px);
    }

    .content-card {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--primary-color);
        margin-right: 10px;
    }

    .rank-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 15px;
    }

    .rank-badge-silver {
        background-color: #e9ecef;
        color: #495057;
    }

    .rank-badge-gold {
        background-color: #ffc107;
        color: #212529;
    }

    .rank-badge-platinum {
        background-color: #6c757d;
        color: white;
    }

    .rank-badge-diamond {
        background-color: #17a2b8;
        color: white;
    }

    .info-section {
        margin-bottom: 30px;
    }

    .info-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .info-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .info-row {
        display: flex;
        margin-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
    }

    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        width: 200px;
        font-weight: 500;
        color: #495057;
    }

    .info-value {
        flex: 1;
        color: #212529;
    }

    .user-card {
        display: flex;
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 24px;
        font-weight: bold;
        margin-right: 20px;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-size: 18px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 5px;
    }

    .user-email {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .user-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .user-detail {
        display: flex;
        align-items: center;
    }

    .user-detail i {
        color: var(--primary-color);
        margin-right: 8px;
        width: 16px;
    }

    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .benefit-item:last-child {
        border-bottom: none;
    }

    .benefit-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        margin-right: 15px;
    }

    .benefit-content {
        flex: 1;
    }

    .benefit-title {
        font-weight: 500;
        color: #343a40;
        margin-bottom: 3px;
    }

    .benefit-description {
        font-size: 14px;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-actions {
            margin-top: 15px;
            width: 100%;
        }
        
        .btn {
            flex: 1;
        }
        
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .user-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .user-avatar {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .user-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="header-container">
    <div>
        <div class="header-title">Chi Tiết Hạng Thành Viên</div>
        <div class="header-subtitle">Xem thông tin chi tiết về hạng thành viên</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.membership_ranks.edit', $rank->Mahang) }}" class="btn btn-white">
            <i class="fas fa-edit"></i> Chỉnh Sửa
        </a>
        <a href="{{ route('admin.membership_ranks.confirm-destroy', $rank->Mahang) }}" class="btn btn-outline">
            <i class="fas fa-trash"></i> Xóa
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-info-circle"></i> Thông Tin Hạng Thành Viên
        </div>
    </div>
    
    <div class="info-section">
        @php
            $badgeClass = 'rank-badge-silver';
            if($rank->Tenhang == 'Thành viên Vàng') {
                $badgeClass = 'rank-badge-gold';
            } elseif($rank->Tenhang == 'Thành viên Bạch Kim') {
                $badgeClass = 'rank-badge-platinum';
            } elseif($rank->Tenhang == 'Thành viên Kim Cương') {
                $badgeClass = 'rank-badge-diamond';
            }
        @endphp
        
        <span class="rank-badge {{ $badgeClass }}">{{ $rank->Tenhang }}</span>
        
        <div class="info-row">
            <div class="info-label">Mã Hạng</div>
            <div class="info-value">{{ $rank->Mahang }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Tên Hạng</div>
            <div class="info-value">{{ $rank->Tenhang }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Mô Tả</div>
            <div class="info-value">{{ $rank->Mota }}</div>
        </div>
    </div>
    
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-user"></i> Thông Tin Người Dùng
        </div>
        
        @if($rank->user)
        <div class="user-card">
            <div class="user-avatar">{{ substr($rank->user->Hoten, 0, 1) }}</div>
            <div class="user-info">
                <div class="user-name">{{ $rank->user->Hoten }}</div>
                <div class="user-email">{{ $rank->user->Email }}</div>
                <div class="user-details">
                    <div class="user-detail">
                        <i class="fas fa-phone"></i>
                        <span>{{ $rank->user->SDT ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-venus-mars"></i>
                        <span>{{ $rank->user->Gioitinh ? ($rank->user->Gioitinh == 1 ? 'Nam' : 'Nữ') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-birthday-cake"></i>
                        <span>{{ $rank->user->Ngaysinh ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="user-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $rank->user->DiaChi ?? 'Chưa cập nhật' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 5px;">
            Không tìm thấy thông tin người dùng.
        </div>
        @endif
    </div>
    
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-gift"></i> Quyền Lợi Hạng Thành Viên
        </div>
        
        <ul class="benefits-list">
            @php
                $benefits = [];
                if($rank->Tenhang == 'Thành viên Bạc') {
                    $benefits = [
                        ['icon' => 'fas fa-tag', 'title' => 'Giảm giá 5%', 'description' => 'Áp dụng cho tất cả các dịch vụ'],
                        ['icon' => 'fas fa-birthday-cake', 'title' => 'Ưu đãi sinh nhật', 'description' => 'Giảm 10% dịch vụ vào tháng sinh nhật'],
                        ['icon' => 'fas fa-star', 'title' => 'Tích điểm cơ bản', 'description' => 'Tích 1 điểm cho mỗi 100.000đ chi tiêu']
                    ];
                } elseif($rank->Tenhang == 'Thành viên Vàng') {
                    $benefits = [
                        ['icon' => 'fas fa-tag', 'title' => 'Giảm giá 10%', 'description' => 'Áp dụng cho tất cả các dịch vụ'],
                        ['icon' => 'fas fa-birthday-cake', 'title' => 'Ưu đãi sinh nhật', 'description' => 'Giảm 15% dịch vụ vào tháng sinh nhật'],
                        ['icon' => 'fas fa-star', 'title' => 'Tích điểm nâng cao', 'description' => 'Tích 1.5 điểm cho mỗi 100.000đ chi tiêu'],
                        ['icon' => 'fas fa-clock', 'title' => 'Ưu tiên đặt lịch', 'description' => 'Được ưu tiên khi đặt lịch dịch vụ']
                    ];
                } elseif($rank->Tenhang == 'Thành viên Bạch Kim') {
                    $benefits = [
                        ['icon' => 'fas fa-tag', 'title' => 'Giảm giá 15%', 'description' => 'Áp dụng cho tất cả các dịch vụ'],
                        ['icon' => 'fas fa-birthday-cake', 'title' => 'Ưu đãi sinh nhật', 'description' => 'Giảm 20% dịch vụ vào tháng sinh nhật'],
                        ['icon' => 'fas fa-star', 'title' => 'Tích điểm cao cấp', 'description' => 'Tích 2 điểm cho mỗi 100.000đ chi tiêu'],
                        ['icon' => 'fas fa-clock', 'title' => 'Ưu tiên đặt lịch', 'description' => 'Được ưu tiên cao khi đặt lịch dịch vụ'],
                        ['icon' => 'fas fa-gift', 'title' => 'Quà tặng định kỳ', 'description' => 'Nhận quà tặng mỗi quý']
                    ];
                } elseif($rank->Tenhang == 'Thành viên Kim Cương') {
                    $benefits = [
                        ['icon' => 'fas fa-tag', 'title' => 'Giảm giá 20%', 'description' => 'Áp dụng cho tất cả các dịch vụ'],
                        ['icon' => 'fas fa-birthday-cake', 'title' => 'Ưu đãi sinh nhật', 'description' => 'Giảm 25% dịch vụ vào tháng sinh nhật'],
                        ['icon' => 'fas fa-star', 'title' => 'Tích điểm đặc biệt', 'description' => 'Tích 3 điểm cho mỗi 100.000đ chi tiêu'],
                        ['icon' => 'fas fa-clock', 'title' => 'Ưu tiên đặt lịch tuyệt đối', 'description' => 'Được ưu tiên cao nhất khi đặt lịch dịch vụ'],
                        ['icon' => 'fas fa-gift', 'title' => 'Quà tặng hàng tháng', 'description' => 'Nhận quà tặng mỗi tháng'],
                        ['icon' => 'fas fa-user-tie', 'title' => 'Tư vấn viên riêng', 'description' => 'Được phục vụ bởi tư vấn viên cá nhân'],
                        ['icon' => 'fas fa-glass-cheers', 'title' => 'Sự kiện đặc biệt', 'description' => 'Được mời tham gia các sự kiện đặc biệt']
                    ];
                }
            @endphp
            
            @foreach($benefits as $benefit)
            <li class="benefit-item">
                <div class="benefit-icon">
                    <i class="{{ $benefit['icon'] }}"></i>
                </div>
                <div class="benefit-content">
                    <div class="benefit-title">{{ $benefit['title'] }}</div>
                    <div class="benefit-description">{{ $benefit['description'] }}</div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="btn-container" style="display: flex; justify-content: center; margin-top: 20px;">
    <a href="{{ route('admin.membership_ranks.index') }}" class="btn" style="background-color: var(--primary-color); color: white;">
        <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
    </a>
</div>
@endsection