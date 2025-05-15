@extends('backend.layouts.app')

@section('title', 'Quản Lý Phòng')

@section('styles')
<style>
    :root {
        --primary-pink: #ff6b95;
        --dark-pink: #e84a78;
        --light-pink: #ffdbe9;
        --light-pink-hover: #ffd0e1;
        --pink-gradient: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
        --secondary-color: #8e44ad;
        --text-primary: #2c3e50;
        --text-secondary: #7a8ca0;
        --green: #2ecc71;
        --yellow: #f1c40f;
        --red: #e74c3c;
        --white: #ffffff;
        --light-gray: #f7f9fc;
        --border-color: #e6e9ed;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --radius-xl: 30px;
        --transition: all 0.3s ease;
    }

    /* Dashboard Header Styling */
    .room-dashboard-header {
        background: var(--pink-gradient);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(255, 107, 149, 0.25);
        color: white;
    }

    .room-dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 1;
        animation: pulse 6s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.1); opacity: 0.8; }
    }

    .header-shimmer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.1) 20%, 
            rgba(255,255,255,0.2) 40%, 
            rgba(255,255,255,0.1) 60%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 5s infinite linear;
        z-index: 2;
        pointer-events: none;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .glitter-dot {
        position: absolute;
        background: white;
        border-radius: 50%;
        opacity: 0;
        z-index: 3;
        box-shadow: 0 0 10px 2px rgba(255,255,255,0.8);
        animation: glitter 8s infinite;
    }

    .glitter-dot:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 25%;
        left: 10%;
        animation-delay: 0s;
    }

    .glitter-dot:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 40%;
        left: 30%;
        animation-delay: 2s;
    }

    .glitter-dot:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 20%;
        right: 25%;
        animation-delay: 4s;
    }

    .glitter-dot:nth-child(4) {
        width: 5px;
        height: 5px;
        bottom: 30%;
        right: 15%;
        animation-delay: 6s;
    }

    @keyframes glitter {
        0% { transform: scale(0); opacity: 0; }
        10% { transform: scale(1); opacity: 0.8; }
        20% { transform: scale(0.2); opacity: 0.2; }
        30% { transform: scale(1.2); opacity: 0.7; }
        40% { transform: scale(0.5); opacity: 0.5; }
        50% { transform: scale(1); opacity: 0.9; }
        60% { transform: scale(0.3); opacity: 0.3; }
        100% { transform: scale(0); opacity: 0; }
    }

    .header-content {
        position: relative;
        z-index: 4;
    }

    .header-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.85;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        position: relative;
        z-index: 4;
    }

    /* Button Styles */
    .btn-room {
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-room i {
        font-size: 0.9rem;
    }

    .btn-primary-room {
        background: white;
        color: var(--primary-pink);
    }

    .btn-primary-room:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: var(--dark-pink);
    }

    .btn-secondary-room {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(5px);
    }

    .btn-secondary-room:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: white;
    }

    /* Room Grid Layout */
    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .room-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        overflow: hidden;
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .room-card-header {
        padding: 1.5rem;
        background: var(--light-pink);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-color);
    }

    .room-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .room-id {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-left: 0.5rem;
    }

    .room-status {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-available {
        background: rgba(46, 204, 113, 0.1);
        color: var(--green);
    }

    .status-occupied {
        background: rgba(231, 76, 60, 0.1);
        color: var(--red);
    }

    .status-maintenance {
        background: rgba(241, 196, 15, 0.1);
        color: var(--yellow);
    }

    .room-card-body {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .room-info-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }

    .room-info-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed var(--border-color);
    }

    .room-info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .room-info-label {
        width: 120px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .room-info-value {
        flex-grow: 1;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .room-card-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 0.8rem;
        justify-content: flex-end;
    }

    .room-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-gray);
        color: var(--text-secondary);
        border: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.85rem;
    }

    .room-action-btn.view {
        background: rgba(255, 107, 149, 0.1);
        color: var(--primary-pink);
    }

    .room-action-btn.edit {
        background: rgba(142, 68, 173, 0.1);
        color: var(--secondary-color);
    }

    .room-action-btn.delete {
        background: rgba(231, 76, 60, 0.1);
        color: var(--red);
    }

    .room-action-btn:hover {
        transform: translateY(-2px);
    }

    .room-action-btn.view:hover {
        background: rgba(255, 107, 149, 0.2);
    }

    .room-action-btn.edit:hover {
        background: rgba(142, 68, 173, 0.2);
    }

    .room-action-btn.delete:hover {
        background: rgba(231, 76, 60, 0.2);
    }

    /* Stats Section */
    .room-stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .room-stats-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .room-stats-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .room-stats-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .room-stats-title i {
        color: var(--primary-pink);
    }

    .room-stats-body {
        padding: 1.5rem;
    }

    .stats-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .stats-item {
        display: flex;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .stats-item:last-child {
        border-bottom: none;
    }

    .stats-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        background: var(--light-pink);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: var(--primary-pink);
    }

    .stats-info {
        flex-grow: 1;
    }

    .stats-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0.3rem;
    }

    .stats-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .stats-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: auto;
    }

    .badge-blue {
        background: rgba(255, 107, 149, 0.1);
        color: var(--primary-pink);
    }

    .progress-bar {
        height: 6px;
        background: var(--light-gray);
        border-radius: 10px;
        margin-top: 0.5rem;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: var(--primary-pink);
        border-radius: 10px;
    }

    /* Chart Section */
    .chart-container {
        padding: 1.5rem;
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-title i {
        color: var(--primary-pink);
    }

    .room-usage-chart {
        height: 300px;
        position: relative;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .chart-bar {
        position: absolute;
        bottom: 0;
        background: var(--pink-gradient);
        border-radius: 10px 10px 0 0;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        padding-bottom: 10px;
        box-shadow: 0 4px 15px rgba(255, 107, 149, 0.15);
    }

    .chart-bar:hover {
        opacity: 0.9;
    }

    .chart-value {
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .chart-label {
        position: absolute;
        bottom: -30px;
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-align: center;
        width: 100%;
    }

    .chart-grid {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        z-index: -1;
    }

    .grid-line {
        position: absolute;
        left: 0;
        right: 0;
        border-top: 1px dashed #e0e0e0;
    }

    /* Search and Filter Section */
    .room-search-filter {
        background: white;
        padding: 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .room-search-wrapper {
        position: relative;
        flex-grow: 1;
        min-width: 250px;
    }

    .room-search-input {
        width: 100%;
        padding: 0.8rem 1rem;
        padding-left: 2.8rem;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 0.95rem;
        transition: var(--transition);
        background: var(--light-gray);
    }

    .room-search-input:focus {
        outline: none;
        border-color: var(--primary-pink);
        background: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .room-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .filter-btn {
        padding: 0.8rem 1.5rem;
        background: var(--light-pink);
        color: var(--primary-pink);
        border: none;
        border-radius: 50px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-btn:hover {
        background: var(--light-pink-hover);
    }

    /* Responsive Media Queries */
    @media screen and (max-width: 992px) {
        .room-dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media screen and (max-width: 768px) {
        .room-search-filter {
            flex-direction: column;
            align-items: stretch;
        }

        .room-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Thêm style cho filter panel */
    .filter-panel {
        background: white;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
        display: none;
        border-top: 3px solid var(--primary-pink);
    }

    .filter-panel.active {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--primary-pink);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-group {
        margin-bottom: 1rem;
    }

    .filter-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background-color: var(--light-gray);
        font-size: 0.95rem;
        transition: var(--transition);
        color: var(--text-primary);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .filter-range {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-range-input {
        flex-grow: 1;
        padding: 0.7rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background-color: var(--light-gray);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .filter-range-input:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .filter-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
    }

    .filter-btn-apply {
        padding: 0.7rem 1.5rem;
        background: var(--primary-pink);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-btn-apply:hover {
        background: var(--dark-pink);
        transform: translateY(-2px);
    }

    .filter-btn-reset {
        padding: 0.7rem 1.5rem;
        background: var(--light-gray);
        color: var(--text-secondary);
        border: none;
        border-radius: 50px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-btn-reset:hover {
        background: #e6e9ed;
        color: var(--text-primary);
    }

    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .active-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        background: #e8f5e9;
        color: #2e7d32;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid #4caf50;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.15);
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .active-filter-tag i {
        cursor: pointer;
        font-size: 0.9rem;
        color: #4caf50;
        background: rgba(76, 175, 80, 0.1);
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .active-filter-tag i:hover {
        background: rgba(76, 175, 80, 0.2);
        transform: scale(1.1);
    }

    .highlight {
        background-color: rgba(255, 107, 149, 0.2);
        padding: 0 2px;
        border-radius: 3px;
    }

    /* Pulsing effect for found rooms */
    @keyframes pulseHighlight {
        0% { box-shadow: 0 0 0 0 rgba(255, 107, 149, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(255, 107, 149, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 107, 149, 0); }
    }

    .room-card-found {
        animation: pulseHighlight 1.5s ease-out;
        animation-iteration-count: 3;
        border: 2px solid var(--primary-pink) !important;
        transform: translateY(-5px);
        transition: all 0.5s ease;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Modern Dashboard Header -->
    <div class="room-dashboard-header">
        <div class="header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="header-content">
            <h1 class="header-title">Quản Lý Phòng</h1>
            <p class="header-subtitle">
                <i class="fas fa-door-open"></i> 
                Quản lý và theo dõi tình trạng các phòng
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.phong.create') }}" class="btn-room btn-primary-room">
                <i class="fas fa-plus"></i>
                Thêm Phòng Mới
            </a>
            <a href="{{ route('admin.trangthaiphong.index') }}" class="btn-room btn-secondary-room">
                <i class="fas fa-door-closed"></i>
                Quản Lý Trạng Thái Phòng
            </a>
        </div>
    </div>

    <!-- Notifications -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="room-search-filter">
        <div class="room-search-wrapper">
            <i class="fas fa-search room-search-icon"></i>
            <input type="text" class="room-search-input" id="roomSearchInput" placeholder="Tìm kiếm phòng...">
        </div>
        <button class="filter-btn" id="filterToggleBtn">
            <i class="fas fa-filter"></i>
            Bộ lọc
        </button>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel" id="filterPanel">
        <h3 class="filter-title"><i class="fas fa-sliders-h"></i> Tùy chọn lọc</h3>
        
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Trạng thái phòng</label>
                <select class="filter-select" id="statusFilter">
                    <option value="">Tất cả trạng thái</option>
                    @php
                        $statusTypes = [];
                        foreach($phongs as $p) {
                            if ($p->trangThaiPhong && !in_array($p->trangThaiPhong->Tentrangthai, $statusTypes)) {
                                $statusTypes[] = $p->trangThaiPhong->Tentrangthai;
                            }
                        }
                    @endphp
                    
                    @foreach($statusTypes as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Loại phòng</label>
                <select class="filter-select" id="typeFilter">
                    <option value="">Tất cả loại phòng</option>
                    @php
                        $roomTypes = [];
                        foreach($phongs as $p) {
                            if (!in_array($p->Loaiphong, $roomTypes)) {
                                $roomTypes[] = $p->Loaiphong;
                            }
                        }
                    @endphp
                    
                    @foreach($roomTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Số lần sử dụng</label>
                <div class="filter-range">
                    <input type="number" class="filter-range-input" id="usageMin" placeholder="Tối thiểu" min="0">
                    <span>đến</span>
                    <input type="number" class="filter-range-input" id="usageMax" placeholder="Tối đa" min="0">
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Doanh thu (VNĐ)</label>
                <div class="filter-range">
                    <input type="number" class="filter-range-input" id="revenueMin" placeholder="Tối thiểu" min="0" step="100000">
                    <span>đến</span>
                    <input type="number" class="filter-range-input" id="revenueMax" placeholder="Tối đa" min="0" step="100000">
                </div>
            </div>
        </div>
        
        <div class="filter-buttons">
            <button class="filter-btn-reset" id="resetFilterBtn">
                <i class="fas fa-undo"></i>
                Đặt lại
            </button>
            <button class="filter-btn-apply" id="applyFilterBtn">
                <i class="fas fa-check"></i>
                Áp dụng
            </button>
        </div>
        
        <div class="active-filters" id="activeFilters">
            <!-- Tags sẽ được thêm qua JavaScript -->
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="room-stats-container">
        <!-- Top 5 Rooms Card -->
        <div class="room-stats-card">
            <div class="room-stats-header">
                <h3 class="room-stats-title">
                    <i class="fas fa-chart-line"></i>
                    Top 5 Phòng Được Sử Dụng Nhiều Nhất
                </h3>
            </div>
            <div class="room-stats-body">
                @if(count($topPhongs) > 0)
                    <ul class="stats-list">
                        @foreach($phongs as $phong)
                            @if(isset($phongStats[$phong->Maphong]) && $loop->index < 5)
                                <li class="stats-item">
                                    <div class="stats-icon">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div class="stats-info">
                                        <div class="stats-label">{{ $phong->Tenphong }}</div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ min(100, ($phongStats[$phong->Maphong]->total_usage / $phongStats->max('total_usage')) * 100) }}%"></div>
                                        </div>
                                    </div>
                                    <span class="stats-badge badge-blue">{{ $phongStats[$phong->Maphong]->total_usage }} lần</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center my-4">Chưa có dữ liệu sử dụng phòng</p>
                @endif
            </div>
        </div>

        <!-- Room Statistics Summary -->
        <div class="room-stats-card">
            <div class="room-stats-header">
                <h3 class="room-stats-title">
                    <i class="fas fa-info-circle"></i>
                    Thống Kê Phòng
                </h3>
            </div>
            <div class="room-stats-body">
                <ul class="stats-list">
                    <li class="stats-item">
                        <div class="stats-icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="stats-info">
                            <div class="stats-label">Tổng số phòng</div>
                            <div class="stats-value">{{ count($phongs) }}</div>
                        </div>
                    </li>
                    
                    @php
                        // Tạo mảng để đếm số phòng theo từng trạng thái
                        $phongTheoTrangThai = [];
                        $mauSacTrangThai = [
                            'Khả dụng' => ['bg' => 'rgba(46, 204, 113, 0.1)', 'color' => '#2ecc71', 'icon' => 'fa-check-circle'],
                            'Đang sử dụng' => ['bg' => 'rgba(231, 76, 60, 0.1)', 'color' => '#e74c3c', 'icon' => 'fa-times-circle'],
                            'Bảo trì' => ['bg' => 'rgba(241, 196, 15, 0.1)', 'color' => '#f1c40f', 'icon' => 'fa-tools'],
                            'default' => ['bg' => 'rgba(52, 152, 219, 0.1)', 'color' => '#3498db', 'icon' => 'fa-info-circle']
                        ];
                        
                        foreach($phongs as $phong) {
                            if ($phong->trangThaiPhong) {
                                $tenTrangThai = $phong->trangThaiPhong->Tentrangthai;
                                if (!isset($phongTheoTrangThai[$tenTrangThai])) {
                                    $phongTheoTrangThai[$tenTrangThai] = 0;
                                }
                                $phongTheoTrangThai[$tenTrangThai]++;
                            }
                        }
                    @endphp
                    
                    @foreach($phongTheoTrangThai as $tenTrangThai => $soLuong)
                        @php
                            // Chọn màu sắc và biểu tượng dựa trên tên trạng thái
                            $style = $mauSacTrangThai['default'];
                            foreach($mauSacTrangThai as $key => $value) {
                                if (strpos(strtolower($tenTrangThai), strtolower($key)) !== false) {
                                    $style = $value;
                                    break;
                                }
                            }
                        @endphp
                        <li class="stats-item">
                            <div class="stats-icon" style="background: {{ $style['bg'] }}; color: {{ $style['color'] }};">
                                <i class="fas {{ $style['icon'] }}"></i>
                            </div>
                            <div class="stats-info">
                                <div class="stats-label">{{ $tenTrangThai }}</div>
                                <div class="stats-value">{{ $soLuong }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Room Usage Chart -->
    <div class="chart-container">
        <h3 class="chart-title">
            <i class="fas fa-chart-bar"></i>
            Biểu Đồ Sử Dụng Phòng
        </h3>
        <div class="room-usage-chart" id="roomUsageChart">
            <div class="chart-grid">
                @for($i = 1; $i <= 5; $i++)
                    <div class="grid-line" style="bottom: {{ $i * 20 }}%"></div>
                @endfor
            </div>
            @php
                $maxUsage = $phongStats->max('total_usage') > 0 ? $phongStats->max('total_usage') : 1;
                $chartWidth = 100 / (count($phongStats) > 0 ? count($phongStats) : 1);
                $chartWidth = min($chartWidth, 15); // Cap width at 15%
                $chartMargin = (100 - ($chartWidth * count($phongStats))) / (count($phongStats) > 1 ? (count($phongStats) * 2) : 2);
            @endphp
            
            @forelse($phongStats as $stat)
                @php
                    $phongInfo = $phongs->firstWhere('Maphong', $stat->Maphong);
                    $percentage = ($stat->total_usage / $maxUsage) * 80; // 80% max height for aesthetics
                    $position = $loop->index * ($chartWidth + $chartMargin * 2) + $chartMargin;
                @endphp
                <div class="chart-bar" style="height: {{ $percentage }}%; width: {{ $chartWidth }}%; left: {{ $position }}%">
                    <div class="chart-value">{{ $stat->total_usage }}</div>
                    <div class="chart-label">{{ $phongInfo->Tenphong ?? 'Phòng #'.$stat->Maphong }}</div>
                </div>
            @empty
                <p class="text-center text-muted">Không có dữ liệu để hiển thị biểu đồ</p>
            @endforelse
        </div>
    </div>

    <!-- Room List View -->
    <div class="room-grid" id="roomGrid">
        @forelse($phongs as $phong)
            <div class="room-card" data-name="{{ $phong->Tenphong }}" data-id="{{ $phong->Maphong }}" data-type="{{ $phong->Loaiphong }}">
                <div class="room-card-header">
                    <h3 class="room-card-title">
                        {{ $phong->Tenphong }}
                        <span class="room-id">#{{ $phong->Maphong }}</span>
                    </h3>
                    
                    @php
                        $statusClass = 'status-available';
                        $statusName = $phong->trangThaiPhong->Tentrangthai ?? 'N/A';
                        
                        if (stripos($statusName, 'đang sử dụng') !== false) {
                            $statusClass = 'status-occupied';
                        } elseif (stripos($statusName, 'bảo trì') !== false) {
                            $statusClass = 'status-maintenance';
                        }
                    @endphp
                    
                    <span class="room-status {{ $statusClass }}">
                        {{ $statusName }}
                    </span>
                </div>
                <div class="room-card-body">
                    <ul class="room-info-list">
                        <li class="room-info-item">
                            <div class="room-info-label">Loại phòng:</div>
                            <div class="room-info-value">{{ $phong->Loaiphong }}</div>
                        </li>
                        <li class="room-info-item">
                            <div class="room-info-label">Mã phòng:</div>
                            <div class="room-info-value">{{ $phong->Maphong }}</div>
                        </li>
                        <li class="room-info-item">
                            <div class="room-info-label">Số lần sử dụng:</div>
                            <div class="room-info-value">{{ $phongStats[$phong->Maphong]->total_usage ?? 0 }} lần</div>
                        </li>
                        <li class="room-info-item">
                            <div class="room-info-label">Doanh thu:</div>
                            <div class="room-info-value">{{ number_format($phongRevenue[$phong->Maphong]->total_revenue ?? 0, 0, ',', '.') }} VNĐ</div>
                        </li>
                    </ul>
                </div>
                <div class="room-card-footer">
                    <a href="{{ route('admin.phong.edit', $phong->Maphong) }}" class="room-action-btn edit" title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('admin.phong.confirm-destroy', $phong->Maphong) }}" class="room-action-btn delete" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h4>Chưa có phòng nào</h4>
                <p class="text-muted">Hãy thêm phòng mới để bắt đầu</p>
                <a href="{{ route('admin.phong.create') }}" class="btn-room btn-primary-room mt-3">
                    <i class="fas fa-plus"></i>
                    Thêm Phòng Mới
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Room elements
        const searchInput = document.getElementById('roomSearchInput');
        const roomGrid = document.getElementById('roomGrid');
        const roomCards = roomGrid.querySelectorAll('.room-card');
        
        // Thêm chức năng click vào card để xem chi tiết
        roomCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Nếu click vào các nút hành động, thì không xử lý ở đây
                if (e.target.closest('.room-action-btn') || e.target.closest('a') || e.target.tagName === 'A' || e.target.closest('button')) {
                    return;
                }
                
                // Lấy mã phòng từ data attribute
                const roomId = this.getAttribute('data-id');
                
                // Tạo URL đến trang chi tiết
                const detailLink = '{{ url("admin/phong") }}/' + roomId;
                
                // Chuyển hướng đến trang chi tiết
                window.location.href = detailLink;
            });
        });
        
        // Filter elements
        const filterToggleBtn = document.getElementById('filterToggleBtn');
        const filterPanel = document.getElementById('filterPanel');
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const usageMin = document.getElementById('usageMin');
        const usageMax = document.getElementById('usageMax');
        const revenueMin = document.getElementById('revenueMin');
        const revenueMax = document.getElementById('revenueMax');
        const applyFilterBtn = document.getElementById('applyFilterBtn');
        const resetFilterBtn = document.getElementById('resetFilterBtn');
        const activeFilters = document.getElementById('activeFilters');
        
        // Toggle filter panel
        filterToggleBtn.addEventListener('click', function() {
            filterPanel.classList.toggle('active');
        });
        
        // Function to scroll to a specific room card and highlight it
        function scrollToRoom(roomCard) {
            // Remove existing found class from all cards
            roomCards.forEach(card => card.classList.remove('room-card-found'));
            
            // Add found class to the matching card
            roomCard.classList.add('room-card-found');
            
            // Scroll to the card with smooth behavior
            roomCard.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            
            // Remove the highlight effect after animation completes
            setTimeout(() => {
                roomCard.classList.remove('room-card-found');
            }, 4500); // 3 iterations of 1.5s animation
        }
        
        // Add Enter key listener to search input
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm) {
                    // Find the first visible room card that matches the search
                    let foundCard = null;
                    
                    roomCards.forEach(card => {
                        if (foundCard) return; // Skip if we already found a match
                        
                        const roomName = card.getAttribute('data-name').toLowerCase();
                        const roomId = card.getAttribute('data-id').toLowerCase();
                        
                        if ((roomName.includes(searchTerm) || roomId.includes(searchTerm)) && 
                            card.style.display !== 'none') {
                            foundCard = card;
                        }
                    });
                    
                    if (foundCard) {
                        // If found, scroll to and highlight the room
                        scrollToRoom(foundCard);
                    } else {
                        console.log("Không tìm thấy phòng nào phù hợp với từ khóa:", searchTerm);
                        // Thông báo cho người dùng khi không tìm thấy kết quả
                        alert(`Không tìm thấy phòng nào phù hợp với từ khóa: "${searchTerm}"`);
                    }
                    
                    // Apply filters normally
                    applyFilters();
                }
            }
        });
        
        // Extract numeric value from string with better error handling
        function extractNumericValue(text, defaultValue = 0) {
            if (!text) return defaultValue;
            // Tìm tất cả các số trong chuỗi
            const matches = text.match(/\d[\d\s,.]*\d|\d+/);
            if (!matches || matches.length === 0) return defaultValue;
            
            // Loại bỏ tất cả ký tự không phải số (giữ lại dấu thập phân nếu có)
            return parseInt(matches[0].replace(/[^\d]/g, ''));
        }
        
        // Apply filters function
        function applyFilters() {
            console.log("Đang áp dụng bộ lọc...");
            
            // Clear active filters display
            activeFilters.innerHTML = '';
            
            // Add to the start of the function, add a normalize function for Vietnamese text
            function normalizeVietnameseText(text) {
                if (!text) return '';
                // Chuyển về chữ thường và loại bỏ khoảng trắng thừa
                return text.toLowerCase().trim()
                    .replace(/\s+/g, ' ');
            }
            
            // Get filter values and normalize them
            const status = normalizeVietnameseText(statusFilter.value);
            const type = normalizeVietnameseText(typeFilter.value);
            const minUsage = usageMin.value ? parseInt(usageMin.value) : 0;
            const maxUsage = usageMax.value ? parseInt(usageMax.value) : Infinity;
            const minRevenue = revenueMin.value ? parseInt(revenueMin.value) : 0;
            const maxRevenue = revenueMax.value ? parseInt(revenueMax.value) : Infinity;
            const searchTerm = normalizeVietnameseText(searchInput.value);
            
            console.log("Giá trị bộ lọc:", {
                trạngThái: status,
                loạiPhòng: type,
                lượtSửDụngMin: minUsage,
                lượtSửDụngMax: maxUsage,
                doanhThuMin: minRevenue,
                doanhThuMax: maxRevenue,
                từKhóa: searchTerm
            });
            
            // Add active filter tags
            if (statusFilter.value) {
                addFilterTag('Trạng thái: ' + statusFilter.value, function() {
                    statusFilter.value = '';
                    applyFilters();
                });
            }
            
            if (typeFilter.value) {
                addFilterTag('Loại phòng: ' + typeFilter.value, function() {
                    typeFilter.value = '';
                    applyFilters();
                });
            }
            
            if (usageMin.value || usageMax.value) {
                let usageText = 'Lượt sử dụng: ';
                if (usageMin.value && usageMax.value) {
                    usageText += usageMin.value + ' - ' + usageMax.value;
                } else if (usageMin.value) {
                    usageText += '≥ ' + usageMin.value;
                } else {
                    usageText += '≤ ' + usageMax.value;
                }
                
                addFilterTag(usageText, function() {
                    usageMin.value = '';
                    usageMax.value = '';
                    applyFilters();
                });
            }
            
            if (revenueMin.value || revenueMax.value) {
                let revenueText = 'Doanh thu: ';
                if (revenueMin.value && revenueMax.value) {
                    revenueText += formatCurrency(revenueMin.value) + ' - ' + formatCurrency(revenueMax.value);
                } else if (revenueMin.value) {
                    revenueText += '≥ ' + formatCurrency(revenueMin.value);
                } else {
                    revenueText += '≤ ' + formatCurrency(revenueMax.value);
                }
                
                addFilterTag(revenueText, function() {
                    revenueMin.value = '';
                    revenueMax.value = '';
                    applyFilters();
                });
            }
            
            if (searchTerm) {
                addFilterTag('Tìm kiếm: ' + searchTerm, function() {
                    searchInput.value = '';
                    applyFilters();
                });
            }
            
            let visibleCount = 0;
            
            // Apply filters to cards
            roomCards.forEach(card => {
                try {
                    // Get card data with better error handling
                    const roomName = normalizeVietnameseText(card.getAttribute('data-name'));
                    const roomId = normalizeVietnameseText(card.getAttribute('data-id'));
                    const roomType = normalizeVietnameseText(card.getAttribute('data-type'));
                    
                    // Get status with error handling
                    const statusElement = card.querySelector('.room-status');
                    const roomStatus = normalizeVietnameseText(statusElement ? statusElement.textContent : '');
                    
                    // Get info values with better selector specificity
                    const infoItems = card.querySelectorAll('.room-info-item');
                    let usageText = '0 lần';
                    let revenueText = '0 VNĐ';
                    
                    // Lấy giá trị sử dụng từ item thứ 3 (index 2)
                    if (infoItems.length >= 3) {
                        const usageValue = infoItems[2].querySelector('.room-info-value');
                        if (usageValue) usageText = usageValue.textContent.trim();
                    }
                    
                    // Lấy giá trị doanh thu từ item thứ 4 (index 3)
                    if (infoItems.length >= 4) {
                        const revenueValue = infoItems[3].querySelector('.room-info-value');
                        if (revenueValue) revenueText = revenueValue.textContent.trim();
                    }
                    
                    // Parse usage và revenue values với hàm trích xuất cải tiến
                    const usage = extractNumericValue(usageText);
                    const revenue = extractNumericValue(revenueText);
                    
                    // So sánh chính xác hơn đối với trạng thái - sau khi đã chuẩn hóa
                    let matchesStatus = true;
                    if (status !== '') {
                        matchesStatus = roomStatus === status || roomStatus.includes(status);
                        console.log(`So sánh trạng thái: '${roomStatus}' với '${status}' => ${matchesStatus}`);
                    }
                    
                    // So sánh chính xác hơn đối với loại phòng - sau khi đã chuẩn hóa
                    let matchesType = true;
                    if (type !== '') {
                        matchesType = roomType === type || roomType.includes(type);
                    }
                    
                    // Check against filters
                    const matchesSearch = searchTerm === '' || 
                                         roomName.includes(searchTerm) || 
                                         roomId.includes(searchTerm) || 
                                         roomType.includes(searchTerm);
                    
                    const matchesUsage = (usageMin.value === '' || usage >= minUsage) && 
                                        (usageMax.value === '' || usage <= maxUsage);
                    
                    const matchesRevenue = (revenueMin.value === '' || revenue >= minRevenue) && 
                                          (revenueMax.value === '' || revenue <= maxRevenue);
                    
                    // Debug info chi tiết hơn cho phòng này
                    console.log(`Phòng: ${roomName} (${roomStatus})`, {
                        "Trạng thái phòng (chuẩn hóa)": roomStatus,
                        "Trạng thái đang lọc (chuẩn hóa)": status,
                        "Khớp trạng thái": matchesStatus,
                        "Loại phòng (chuẩn hóa)": roomType,
                        "Loại đang lọc (chuẩn hóa)": type, 
                        "Khớp loại": matchesType,
                        "Lượt sử dụng": usage,
                        "Khớp lượt sử dụng": matchesUsage,
                        "Doanh thu": revenue,
                        "Khớp doanh thu": matchesRevenue,
                        "Khớp từ khóa": matchesSearch
                    });
                    
                    // Show/hide based on all filters
                    if (matchesSearch && matchesStatus && matchesType && matchesUsage && matchesRevenue) {
                        card.style.display = '';
                        visibleCount++;
                        
                        // Highlight search term if present
                        if (searchTerm) {
                            highlightSearchTerm(card, searchTerm);
                        } else {
                            // Remove any existing highlights
                            removeHighlights(card);
                        }
                    } else {
                        card.style.display = 'none';
                    }
                } catch (error) {
                    console.error("Lỗi khi xử lý phòng:", error, error.stack);
                    // Hiển thị phòng nếu có lỗi (để không bỏ sót phòng do lỗi)
                    card.style.display = '';
                    visibleCount++; // Đảm bảo phòng này vẫn được tính
                }
            });
            
            console.log(`Tổng cộng: ${visibleCount} phòng được hiển thị sau khi lọc`);
            
            // Thêm thông báo vào grid với màu xanh lá và dấu tick
            if (!document.getElementById('filterResultsMessage')) {
                const filterResults = document.createElement('div');
                filterResults.id = 'filterResultsMessage';
                filterResults.className = 'col-12 text-center py-5';
                filterResults.innerHTML = `
                    <div class="alert alert-success" role="alert" style="display: inline-block; background-color: #e8f5e9; border-color: #4caf50; color: #2e7d32; border-radius: 10px; box-shadow: 0 3px 10px rgba(76, 175, 80, 0.1);">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h4 class="text-success">Lọc áp dụng thành công</h4>
                        <p class="text-muted">Các phòng phù hợp đã được hiển thị</p>
                        <button class="btn mt-3" id="clearAllFilters" style="background-color: #4caf50; color: white; border-radius: 50px; padding: 8px 20px;">
                            <i class="fas fa-sync-alt"></i>
                            Đặt lại bộ lọc
                        </button>
                    </div>
                `;
                roomGrid.appendChild(filterResults);
                
                // Thêm sự kiện cho nút xóa tất cả bộ lọc
                document.getElementById('clearAllFilters').addEventListener('click', function() {
                    resetFilterBtn.click();
                });
                
                // Tự động ẩn thông báo sau 5 giây
                setTimeout(function() {
                    const message = document.getElementById('filterResultsMessage');
                    if (message) {
                        message.style.opacity = '0';
                        message.style.transition = 'opacity 1s ease';
                        setTimeout(function() {
                            if (message.parentNode) {
                                message.parentNode.removeChild(message);
                            }
                        }, 1000);
                    }
                }, 5000);
            }
            
            // Hiển thị kết quả tìm thấy 0 phòng
            if (visibleCount === 0) {
                if (!document.getElementById('noResultsMessage')) {
                    const noResults = document.createElement('div');
                    noResults.id = 'noResultsMessage';
                    noResults.className = 'col-12 text-center py-5';
                    noResults.innerHTML = `
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>Không tìm thấy phòng phù hợp</h4>
                        <p class="text-muted">Vui lòng thử lại với bộ lọc khác</p>
                        <button class="btn-room btn-primary-room mt-3" id="clearAllFiltersNoResults">
                            <i class="fas fa-times"></i>
                            Xóa tất cả bộ lọc
                        </button>
                    `;
                    roomGrid.appendChild(noResults);
                    
                    // Thêm sự kiện cho nút xóa tất cả bộ lọc
                    document.getElementById('clearAllFiltersNoResults').addEventListener('click', function() {
                        resetFilterBtn.click();
                    });
                }
            } else {
                // Xóa thông báo không tìm thấy kết quả nếu có
                const noResults = document.getElementById('noResultsMessage');
                if (noResults) {
                    noResults.remove();
                }
            }
        }
        
        // Highlight search term in card text
        function highlightSearchTerm(card, term) {
            // Remove any existing highlights first
            removeHighlights(card);
            
            // Find text nodes in the card and highlight the term
            const titleElement = card.querySelector('.room-card-title');
            if (!titleElement) return;
            
            const headerText = Array.from(titleElement.childNodes)
                .find(node => node.nodeType === Node.TEXT_NODE);
            
            if (headerText && headerText.nodeType === Node.TEXT_NODE) {
                const text = headerText.nodeValue;
                const lowerText = text.toLowerCase();
                const index = lowerText.indexOf(term);
                
                if (index >= 0) {
                    const before = text.substring(0, index);
                    const match = text.substring(index, index + term.length);
                    const after = text.substring(index + term.length);
                    
                    const fragment = document.createDocumentFragment();
                    fragment.appendChild(document.createTextNode(before));
                    
                    const highlight = document.createElement('span');
                    highlight.className = 'highlight';
                    highlight.appendChild(document.createTextNode(match));
                    fragment.appendChild(highlight);
                    
                    fragment.appendChild(document.createTextNode(after));
                    
                    titleElement.replaceChild(fragment, headerText);
                }
            }
        }
        
        // Remove highlights from card
        function removeHighlights(card) {
            const highlights = card.querySelectorAll('.highlight');
            highlights.forEach(highlight => {
                const parent = highlight.parentNode;
                const text = highlight.textContent;
                const textNode = document.createTextNode(text);
                parent.replaceChild(textNode, highlight);
                parent.normalize(); // Merge adjacent text nodes
            });
        }
        
        // Format currency
        function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                maximumFractionDigits: 0
            }).format(value);
        }
        
        // Add filter tag
        function addFilterTag(text, removeCallback) {
            const tag = document.createElement('div');
            tag.className = 'active-filter-tag';
            tag.innerHTML = `${text}`; // Bỏ icon check ở đầu
            
            // Thêm nút xóa riêng ở cuối
            const removeBtn = document.createElement('i');
            removeBtn.className = 'fas fa-times';
            removeBtn.style.marginLeft = 'auto';
            removeBtn.addEventListener('click', removeCallback);
            tag.appendChild(removeBtn);
            
            activeFilters.appendChild(tag);
        }
        
        // Event listeners
        searchInput.addEventListener('input', applyFilters);
        
        // Đảm bảo cập nhật ngay lập tức khi người dùng thay đổi các bộ lọc
        statusFilter.addEventListener('change', function() {
            console.log("Đã thay đổi bộ lọc trạng thái:", this.value);
        });
        
        typeFilter.addEventListener('change', function() {
            console.log("Đã thay đổi bộ lọc loại phòng:", this.value);
        });
        
        // Thêm sự kiện input cho các ô nhập số
        usageMin.addEventListener('input', function() {
            console.log("Đã thay đổi lượt sử dụng tối thiểu:", this.value);
            applyFilters();
        });
        
        usageMax.addEventListener('input', function() {
            console.log("Đã thay đổi lượt sử dụng tối đa:", this.value);
            applyFilters();
        });
        
        revenueMin.addEventListener('input', function() {
            console.log("Đã thay đổi doanh thu tối thiểu:", this.value);
            applyFilters();
        });
        
        revenueMax.addEventListener('input', function() {
            console.log("Đã thay đổi doanh thu tối đa:", this.value);
            applyFilters();
        });
        
        // Khi nhấn nút Áp dụng
        applyFilterBtn.addEventListener('click', function() {
            console.log("Đã nhấn nút áp dụng bộ lọc");
            applyFilters();
            
            // Tự động cuộn xuống danh sách phòng sau khi lọc
            setTimeout(() => {
                const roomGrid = document.getElementById('roomGrid');
                if (roomGrid) {
                    roomGrid.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start'
                    });
                    
                    // Thêm hiệu ứng highlight cho roomGrid
                    roomGrid.style.boxShadow = '0 0 20px rgba(76, 175, 80, 0.5)';
                    setTimeout(() => {
                        roomGrid.style.boxShadow = '';
                        roomGrid.style.transition = 'box-shadow 0.5s ease';
                    }, 1500);
                }
            }, 200); // Chờ một chút để đảm bảo bộ lọc đã được áp dụng
        });
        
        resetFilterBtn.addEventListener('click', function() {
            console.log("Đang đặt lại tất cả bộ lọc...");
            
            // Reset all filters
            statusFilter.value = '';
            typeFilter.value = '';
            usageMin.value = '';
            usageMax.value = '';
            revenueMin.value = '';
            revenueMax.value = '';
            searchInput.value = '';
            
            // Reset all cards visibility and remove highlights
            roomCards.forEach(card => {
                card.style.display = '';
                removeHighlights(card);
            });
            
            // Clear active filters
            activeFilters.innerHTML = '';
            
            // Xóa thông báo không tìm thấy kết quả nếu có
            const noResults = document.getElementById('noResultsMessage');
            if (noResults) {
                noResults.remove();
            }
            
            console.log("Đã đặt lại bộ lọc hoàn tất");
        });
        
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var closeBtn = alert.querySelector('.close');
                if (closeBtn) {
                    closeBtn.click();
                }
            });
        }, 5000);

        // Cập nhật select trạng thái dựa trên các trạng thái thực tế trong trang
        const statusSelect = document.getElementById('statusFilter');
        const statusOptions = new Set();
        
        // Xóa các option hiện tại (trừ "Tất cả")
        Array.from(statusSelect.options).forEach((option, index) => {
            if (index !== 0) { // Giữ lại option đầu tiên (Tất cả)
                statusSelect.removeChild(option);
            }
        });
        
        // Thu thập tất cả trạng thái từ trang
        document.querySelectorAll('.room-status').forEach(statusElem => {
            statusOptions.add(statusElem.textContent.trim());
        });
        
        // Thêm options mới
        statusOptions.forEach(status => {
            const option = document.createElement('option');
            option.value = status;
            option.textContent = status;
            statusSelect.appendChild(option);
        });
        
        // KHÔNG tự động áp dụng bộ lọc khi trang tải xong
    });
</script>
@endsection