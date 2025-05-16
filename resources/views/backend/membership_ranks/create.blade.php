@extends('backend.layouts.app')

@section('title', 'Thêm Hạng Thành Viên Mới')

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

    /* Welcome banner style */
    .welcome-banner {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        background: linear-gradient(145deg, #f58cba, #e83e8c);
        animation: softPulse 4s infinite alternate, floatAnimation 6s ease-in-out infinite;
        transition: all 0.5s ease;
        box-shadow: 0 5px 15px rgba(232, 62, 140, 0.3);
        transform-origin: center center;
        width: 100%;
        padding: 25px 30px;
        margin-bottom: 30px;
        color: white;
    }

    .welcome-banner:before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        z-index: -1;
        background: linear-gradient(45deg, 
            #ff7eb3, #ff758c, #ff7eb3, #ff8e8c, 
            #fdae9e, #ff7eb3, #ff758c, #ff7eb3);
        background-size: 400%;
        border-radius: 16px;
        animation: borderGlow 12s linear infinite;
        filter: blur(10px);
        opacity: 0.7;
    }

    .welcome-banner:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.1) 25%, 
            rgba(255,255,255,0.2) 50%, 
            rgba(255,255,255,0.1) 75%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 6s infinite;
        z-index: 1;
        pointer-events: none;
    }

    .welcome-banner h1, .welcome-banner p {
        position: relative;
        z-index: 2;
    }

    .welcome-banner h1 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 12px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .welcome-banner p {
        font-size: 1.05rem;
        opacity: 0.9;
        margin-bottom: 5px;
        position: relative;
        z-index: 1;
    }

    .shine-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.95);
        z-index: 2;
        animation: corner-to-corner 12s infinite cubic-bezier(0.25, 0.1, 0.25, 1);
        opacity: 0;
    }

    @keyframes corner-to-corner {
        0% {
            left: 0;
            top: 0;
            opacity: 0;
        }
        5% {
            opacity: 1;
        }
        45% {
            left: 100%;
            top: 100%;
            opacity: 1;
        }
        50% {
            opacity: 0;
        }
        100% {
            opacity: 0;
        }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes borderGlow {
        0% { background-position: 0% 0%; }
        100% { background-position: 100% 100%; }
    }

    @keyframes softPulse {
        0% {
            box-shadow: 0 5px 15px rgba(232, 62, 140, 0.3);
        }
        100% {
            box-shadow: 0 8px 25px rgba(232, 62, 140, 0.5);
        }
    }

    @keyframes floatAnimation {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }

    .header-container {
        background-color: var(--primary-color);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        color: var(--text-on-primary);
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

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .form-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23495057' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px 12px;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #6c757d;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 12px;
        color: var(--danger-color);
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    .rank-preview {
        margin-top: 30px;
        padding: 20px;
        border-radius: 8px;
        background-color: #f8f9fa;
        border: 1px dashed var(--border-color);
    }

    .rank-preview-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .rank-preview-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .rank-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 10px;
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

    .rank-description {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .rank-user {
        display: flex;
        align-items: center;
    }

    .rank-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: bold;
        margin-right: 10px;
    }

    .rank-user-info {
        font-size: 14px;
    }

    .rank-user-name {
        font-weight: 500;
        color: #343a40;
    }

    .rank-user-email {
        color: #6c757d;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .btn-container {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>

<div class="welcome-banner">
    <h1><i class="fas fa-plus-circle"></i> Thêm Hạng Thành Viên Mới</h1>
    <p>Tạo hạng thành viên mới để phân loại khách hàng</p>
    <div class="shine-line"></div>
</div>

<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-plus-circle"></i> Thông Tin Hạng Thành Viên
        </div>
    </div>
    
    @if($errors->any())
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <ul style="margin-bottom: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('admin.membership_ranks.store') }}" method="POST" id="rankForm">
        @csrf
        
        <div class="form-group">
            <label for="Mahang" class="form-label">Mã Hạng</label>
            <input type="number" class="form-control @error('Mahang') is-invalid @enderror" id="Mahang" name="Mahang" value="{{ old('Mahang', $suggestedMahang) }}" required>
            <small class="form-text">Mã hạng được gợi ý tự động, bạn có thể thay đổi nếu cần.</small>
            @error('Mahang')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Tenhang" class="form-label">Tên Hạng</label>
            <select class="form-select @error('Tenhang') is-invalid @enderror" id="Tenhang" name="Tenhang" required>
                <option value="">-- Chọn hạng thành viên --</option>
                @foreach($rankTypes as $rankType)
                    <option value="{{ $rankType }}" {{ old('Tenhang') == $rankType ? 'selected' : '' }}>{{ $rankType }}</option>
                @endforeach
            </select>
            @error('Tenhang')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="Manguoidung" class="form-label">Người Dùng</label>
            <select class="form-select @error('Manguoidung') is-invalid @enderror" id="Manguoidung" name="Manguoidung" required>
                <option value="">-- Chọn người dùng --</option>
                @foreach($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->Hoten }} ({{ $user->Email }})
                    </option>
                @endforeach
            </select>
            @error('Manguoidung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="rank-preview" id="rankPreview">
            <div class="rank-preview-title">
                <i class="fas fa-eye"></i> Xem Trước
            </div>
            <div id="previewContent">
                <p class="text-muted">Vui lòng chọn hạng thành viên và người dùng để xem trước.</p>
            </div>
        </div>
        
        <div class="btn-container">
            <a href="{{ route('admin.membership_ranks.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu Hạng Thành Viên</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tenhangSelect = document.getElementById('Tenhang');
    const manguoidungSelect = document.getElementById('Manguoidung');
    const previewContent = document.getElementById('previewContent');
    
    // Mô tả cho từng hạng
    const rankDescriptions = {
        'Thành viên Bạc': 'Hạng thành viên dành cho người mới',
        'Thành viên Vàng': 'Hạng thành viên dành cho người dùng tích cực',
        'Thành viên Bạch Kim': 'Hạng thành viên dành cho người dùng cao cấp',
        'Thành viên Kim Cương': 'Hạng thành viên cao nhất dành cho người dùng xuất sắc'
    };
    
    // Badge class cho từng hạng
    const rankBadgeClasses = {
        'Thành viên Bạc': 'rank-badge-silver',
        'Thành viên Vàng': 'rank-badge-gold',
        'Thành viên Bạch Kim': 'rank-badge-platinum',
        'Thành viên Kim Cương': 'rank-badge-diamond'
    };
    
    // Lưu thông tin người dùng
    const users = {};
    @foreach($users as $user)
        users['{{ $user->Manguoidung }}'] = {
            name: '{{ $user->Hoten }}',
            email: '{{ $user->Email }}',
            initial: '{{ substr($user->Hoten, 0, 1) }}'
        };
    @endforeach
    
    function updatePreview() {
        const selectedRank = tenhangSelect.value;
        const selectedUser = manguoidungSelect.value;
        
        if (!selectedRank || !selectedUser) {
            previewContent.innerHTML = '<p class="text-muted">Vui lòng chọn hạng thành viên và người dùng để xem trước.</p>';
            return;
        }
        
        const badgeClass = rankBadgeClasses[selectedRank] || 'rank-badge-silver';
        const description = rankDescriptions[selectedRank] || '';
        const user = users[selectedUser] || { name: 'Không xác định', email: 'N/A', initial: '?' };
        
        previewContent.innerHTML = `
            <span class="rank-badge ${badgeClass}">${selectedRank}</span>
            <p class="rank-description">${description}</p>
            <div class="rank-user">
                <div class="rank-user-avatar">${user.initial}</div>
                <div class="rank-user-info">
                    <div class="rank-user-name">${user.name}</div>
                    <div class="rank-user-email">${user.email}</div>
                </div>
            </div>
        `;
    }
    
    tenhangSelect.addEventListener('change', updatePreview);
    manguoidungSelect.addEventListener('change', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
@endsection