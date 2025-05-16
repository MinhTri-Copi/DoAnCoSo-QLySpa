@extends('backend.layouts.app')

@section('title', 'Quản lý phiếu hỗ trợ')

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

    /* Dropdown styling */
    .filter-select {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #e6e9ed;
        border-radius: 10px;
        font-size: 0.95rem;
        color: var(--text-primary);
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        cursor: pointer;
        transition: all 0.3s ease;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ff6b95' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }

    .filter-select:focus,
    .filter-select:hover {
        outline: none;
        border-color: var(--primary-pink);
        box-shadow: 0 3px 12px rgba(255, 107, 149, 0.15);
        transform: translateY(-1px);
    }

    .filter-select:active {
        transform: translateY(0);
    }

    .filter-select option {
        padding: 12px;
        background-color: white;
        color: var(--text-primary);
        border-bottom: 1px solid #f5f5f5;
    }

    .filter-group {
        margin-bottom: 1.2rem;
        position: relative;
        z-index: 10;
    }

    .filter-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
        transition: all 0.2s ease;
    }

    .filter-group:hover .filter-label {
        color: var(--primary-pink);
        transform: translateX(3px);
    }

    /* Thêm dropdown animation */
    @keyframes dropdownFadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Tạo hiệu ứng nổi bật khi hover dropdown */
    .filter-group {
        position: relative;
        z-index: 50;
        transition: var(--transition);
    }

    .filter-group:hover .filter-label {
        color: var(--primary-pink);
    }

    .filter-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        transition: var(--transition);
    }

    /* Override any potential interference */
    .filter-group {
        position: relative;
        z-index: 100;
    }

    /* Dashboard Header Styling */
    .support-dashboard-header {
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

    .support-dashboard-header::before {
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
    .btn-support {
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

    .btn-support i {
        font-size: 0.9rem;
    }

    .btn-primary-support {
        background: white;
        color: var(--primary-pink);
    }

    .btn-primary-support:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: var(--dark-pink);
    }

    .btn-secondary-support {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        backdrop-filter: blur(5px);
    }

    .btn-secondary-support:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: white;
    }

    /* Support Grid Layout */
    .support-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        width: 100%;
    }
    
    .support-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
        border: 1px solid #f0f0f0;
    }
    
    .support-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(255, 107, 149, 0.15);
        border-color: rgba(255, 107, 149, 0.3);
    }

    .support-card.active-card {
        border-left: 3px solid var(--primary-pink);
        background-color: #fffbfd;
    }
    
    .urgent-indicator {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        background-color: #e74c3c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        z-index: 10;
        animation: pulse-red 1.5s infinite;
    }
    
    @keyframes pulse-red {
        0% {
            box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
            transform: scale(0.95);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(231, 76, 60, 0);
            transform: scale(1.1);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(231, 76, 60, 0);
            transform: scale(0.95);
        }
    }

    .support-card-header {
        padding: 1.5rem;
        background: var(--light-pink);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-color);
    }

    .support-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .support-id {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-left: 0.5rem;
    }

    .support-status {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background: rgba(241, 196, 15, 0.1);
        color: var(--yellow);
    }

    .status-processing {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .status-completed {
        background: rgba(46, 204, 113, 0.1);
        color: var(--green);
    }

    .status-cancelled {
        background: rgba(231, 76, 60, 0.1);
        color: var(--red);
    }

    .support-card-body {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .support-info-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }

    .support-info-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed var(--border-color);
    }

    .support-info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .support-info-label {
        width: 120px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .support-info-value {
        flex-grow: 1;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .support-card-footer {
        padding: 1.2rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 0.8rem;
        justify-content: flex-end;
        flex-wrap: wrap;
        align-items: center;
    }

    /* Search and Filter Section */
    .support-search-filter {
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

    .support-search-wrapper {
        position: relative;
        flex-grow: 1;
        min-width: 250px;
    }

    .support-search-input {
        width: 100%;
        padding: 0.8rem 1rem;
        padding-left: 2.8rem;
        border: 1px solid var(--border-color);
        border-radius: 50px;
        font-size: 0.95rem;
        transition: var(--transition);
        background: var(--light-gray);
    }

    .support-search-input:focus {
        outline: none;
        border-color: var(--primary-pink);
        background: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .support-search-icon {
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
        .support-dashboard-header {
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
        .support-search-filter {
            flex-direction: column;
            align-items: stretch;
        }

        .support-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Thêm CSS cho bộ lọc */
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

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-pink);
        background-color: white;
        box-shadow: 0 3px 10px rgba(255, 107, 149, 0.1);
    }

    .filter-date-input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background-color: var(--light-gray);
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .filter-date-input:focus {
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

    /* Status Colors */
    .filter-select#statusFilter option[value="chờ xử lý"] {
        color: var(--yellow);
        background-color: rgba(241, 196, 15, 0.05);
    }

    .filter-select#statusFilter option[value="đang xử lý"] {
        color: #3498db;
        background-color: rgba(52, 152, 219, 0.05);
    }

    .filter-select#statusFilter option[value="hoàn thành"] {
        color: var(--green);
        background-color: rgba(46, 204, 113, 0.05);
    }

    .filter-select#statusFilter option[value="đã hủy"] {
        color: var(--red);
        background-color: rgba(231, 76, 60, 0.05);
    }

    /* Selected Option Style */
    .filter-select#statusFilter,
    .filter-select#pthtFilter,
    .filter-select#userFilter {
        font-weight: 500;
        letter-spacing: 0.01em;
    }

    /* Add these new styles in the styles section */
    .support-action-btn.process {
        background: rgba(52, 152, 219, 0.3);
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.5);
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.1);
    }
    
    .support-action-btn.complete {
        background: rgba(46, 204, 113, 0.3);
        color: var(--green);
        border: 1px solid rgba(46, 204, 113, 0.5);
        box-shadow: 0 3px 10px rgba(46, 204, 113, 0.1);
        
    }
    
    .support-action-btn.process:hover {
        background: rgba(52, 152, 219, 0.5);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    }
    
    .support-action-btn.complete:hover {
        background: rgba(46, 204, 113, 0.5);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
    }
    
    .urgent-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 18px;
        height: 18px;
        background-color: var(--red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        animation: pulse-attention 1.5s infinite;
        box-shadow: 0 0 0 rgba(231, 76, 60, 0.4);
        z-index: 5;
    }
    
    @keyframes pulse-attention {
        0% {
            box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.6);
            transform: scale(0.95);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(231, 76, 60, 0);
            transform: scale(1.1);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(231, 76, 60, 0);
            transform: scale(0.95);
        }
    }

    /* Add a subtle pulse animation to make the buttons more noticeable */
    @keyframes button-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.5);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(52, 152, 219, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
        }
    }

    .support-action-btn.process {
        animation: button-pulse 2s infinite;
    }

    /* Remove the form wrapper and style the buttons directly */
    .status-update-form {
        display: contents; /* This makes the form's children appear as if they're direct children of the parent container */
    }

    /* Add CSS for disabled buttons */
    .support-action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Add a tooltip to help users understand the button functions */
    .action-tooltip {
        position: relative;
        display: inline-block;
    }

    .action-tooltip .tooltip-text {
        visibility: hidden;
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 10px;
        position: absolute;
        z-index: 100;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 12px;
        white-space: nowrap;
        pointer-events: none;
    }

    .action-tooltip .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
    }

    .action-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Modern Dashboard Header -->
    <div class="support-dashboard-header">
        <div class="header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="header-content">
            <h1 class="header-title">Quản Lý Phiếu Hỗ Trợ</h1>
            <p class="header-subtitle">
                <i class="fas fa-headset"></i> 
                Quản lý và theo dõi các phiếu hỗ trợ
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.phieuhotro.create') }}" class="btn-support btn-primary-support">
                <i class="fas fa-plus"></i>
                Tạo Phiếu Hỗ Trợ Mới
            </a>
            <a href="{{ route('admin.pthotro.index') }}" class="btn-support btn-secondary-support">
                <i class="fas fa-file-alt"></i>
                Quản Lý PTHoTro
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
    <div class="support-search-filter">
        <div class="support-search-wrapper">
            <i class="fas fa-search support-search-icon"></i>
            <input type="text" class="support-search-input" id="supportSearchInput" placeholder="Tìm kiếm phiếu hỗ trợ...">
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
                <label class="filter-label">Trạng thái</label>
                <select class="filter-select" id="statusFilter">
                    <option value="">Tất cả trạng thái</option>
                    <option value="chờ xử lý">Chờ xử lý</option>
                    <option value="đang xử lý">Đang xử lý</option>
                    <option value="hoàn thành">Hoàn thành</option>
                    <option value="đã hủy">Đã hủy</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Phương thức hỗ trợ</label>
                <select class="filter-select" id="pthtFilter">
                    <option value="">Tất cả phương thức</option>
                    @if(isset($ptHoTros) && count($ptHoTros) > 0)
                        @foreach($ptHoTros as $ptht)
                            <option value="{{ $ptht->MaPTHT }}">{{ $ptht->TenPT }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Người dùng</label>
                <select class="filter-select" id="userFilter">
                    <option value="">Tất cả người dùng</option>
                    @if(isset($users) && count($users) > 0)
                        @foreach($users as $user)
                            <option value="{{ $user->Manguoidung }}">{{ $user->Hoten }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Tìm kiếm nội dung</label>
                <input type="text" class="filter-date-input" id="contentFilter" placeholder="Nhập nội dung cần tìm">
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

    <!-- Support List View -->
    <div class="support-grid" id="supportGrid">
        @if(isset($phieuHoTros) && count($phieuHoTros) > 0)
            @php
                // Sort the tickets to prioritize 'pending' status ones
                $pendingTickets = $phieuHoTros->filter(function($ticket) {
                    $statusName = strtolower($ticket->trangThai->Tentrangthai ?? 'chờ xử lý');
                    return $statusName === 'chờ xử lý';
                });
                
                $otherTickets = $phieuHoTros->filter(function($ticket) {
                    $statusName = strtolower($ticket->trangThai->Tentrangthai ?? 'chờ xử lý');
                    return $statusName !== 'chờ xử lý';
                });
                
                // Merge the collections with pending tickets first
                $sortedTickets = $pendingTickets->merge($otherTickets);
            @endphp
            
            @foreach($sortedTickets as $phieuHoTro)
                <div class="support-card" 
                    data-id="{{ $phieuHoTro->MaphieuHT }}" 
                    data-status="{{ strtolower($phieuHoTro->trangThai->Tentrangthai ?? 'chờ xử lý') }}"
                    data-user="{{ $phieuHoTro->Manguoidung ?? '' }}"
                    data-ptht="{{ $phieuHoTro->MaPTHT ?? '' }}"
                    data-content="{{ $phieuHoTro->Noidungyeucau ?? '' }}"
                    onclick="window.location.href='{{ route('admin.phieuhotro.show', $phieuHoTro->MaphieuHT) }}'">
                    
                    @php
                        $statusName = strtolower($phieuHoTro->trangThai->Tentrangthai ?? 'chờ xử lý');
                        $isPending = $statusName === 'chờ xử lý';
                    @endphp
                    
                    @if($isPending)
                        <div class="urgent-indicator">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    @endif
                    
                    <div class="support-card-header">
                        <h3 class="support-card-title">
                            Phiếu Hỗ Trợ
                            <span class="support-id">#{{ $phieuHoTro->MaphieuHT }}</span>
                        </h3>
                        
                        @php
                            $statusClass = 'status-pending';
                            $statusName = $phieuHoTro->trangThai->Tentrangthai ?? 'Chờ xử lý';
                            
                            switch(strtolower($statusName)) {
                                case 'đang xử lý':
                                    $statusClass = 'status-processing';
                                    break;
                                case 'hoàn thành':
                                    $statusClass = 'status-completed';
                                    break;
                                case 'đã hủy':
                                    $statusClass = 'status-cancelled';
                                    break;
                            }
                        @endphp
                        
                        <span class="support-status {{ $statusClass }}">
                            {{ $statusName }}
                        </span>
                    </div>
                    <div class="support-card-body">
                        <ul class="support-info-list">
                            <li class="support-info-item">
                                <div class="support-info-label">Người dùng:</div>
                                <div class="support-info-value">{{ $phieuHoTro->user->Hoten ?? 'N/A' }}</div>
                            </li>
                            <li class="support-info-item">
                                <div class="support-info-label">Nội dung:</div>
                                <div class="support-info-value">{{ Str::limit($phieuHoTro->Noidungyeucau ?? '', 100) }}</div>
                            </li>
                            <li class="support-info-item">
                                <div class="support-info-label">Phương thức:</div>
                                <div class="support-info-value">{{ $phieuHoTro->ptHoTro->TenPT ?? 'N/A' }}</div>
                            </li>
                        </ul>
                    </div>
                    <div class="support-card-footer">
                        <!-- Phần "Xem chi tiết" đã được bỏ theo yêu cầu -->
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <i class="fas fa-headset fa-3x text-muted mb-3"></i>
                <h4>Chưa có phiếu hỗ trợ nào</h4>
                <p class="text-muted">Hãy tạo phiếu hỗ trợ mới để bắt đầu</p>
                <a href="{{ route('admin.phieuhotro.create') }}" class="btn-support btn-primary-support mt-3">
                    <i class="fas fa-plus"></i>
                    Tạo Phiếu Hỗ Trợ Mới
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Support elements
        const searchInput = document.getElementById('supportSearchInput');
        const supportGrid = document.getElementById('supportGrid');
        const supportCards = supportGrid.querySelectorAll('.support-card');
        
        // Filter elements
        const filterToggleBtn = document.getElementById('filterToggleBtn');
        const filterPanel = document.getElementById('filterPanel');
        const statusFilter = document.getElementById('statusFilter');
        const pthtFilter = document.getElementById('pthtFilter');
        const userFilter = document.getElementById('userFilter');
        const contentFilter = document.getElementById('contentFilter');
        const applyFilterBtn = document.getElementById('applyFilterBtn');
        const resetFilterBtn = document.getElementById('resetFilterBtn');
        const activeFilters = document.getElementById('activeFilters');
        
        // Toggle filter panel
        filterToggleBtn.addEventListener('click', function() {
            filterPanel.classList.toggle('active');
        });
        
        // Apply filters function
        function applyFilters() {
            // Clear active filters display
            activeFilters.innerHTML = '';
            
            // Get filter values
            const status = statusFilter.value.toLowerCase().trim();
            const ptht = pthtFilter.value;
            const userId = userFilter.value;
            const content = contentFilter.value.toLowerCase().trim();
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            // Add active filter tags
            if (status) {
                addFilterTag('Trạng thái: ' + status, function() {
                    statusFilter.value = '';
                    applyFilters();
                });
            }
            
            if (ptht) {
                const pthtText = pthtFilter.options[pthtFilter.selectedIndex].text;
                addFilterTag('Phương thức: ' + pthtText, function() {
                    pthtFilter.value = '';
                    applyFilters();
                });
            }
            
            if (userId) {
                const userText = userFilter.options[userFilter.selectedIndex].text;
                addFilterTag('Người dùng: ' + userText, function() {
                    userFilter.value = '';
                    applyFilters();
                });
            }
            
            if (content) {
                addFilterTag('Nội dung: ' + content, function() {
                    contentFilter.value = '';
                    applyFilters();
                });
            }
            
            if (searchTerm) {
                addFilterTag('Tìm kiếm: ' + searchTerm, function() {
                    searchInput.value = '';
                    applyFilters();
                });
            }
            
            // Filter cards
            let visibleCount = 0;
            
            supportCards.forEach(card => {
                const cardStatus = card.getAttribute('data-status').toLowerCase();
                const cardPtht = card.getAttribute('data-ptht');
                const cardUserId = card.getAttribute('data-user');
                const cardContent = card.getAttribute('data-content').toLowerCase();
                
                const matchesStatus = !status || cardStatus.includes(status);
                const matchesPtht = !ptht || cardPtht === ptht;
                const matchesUser = !userId || cardUserId === userId;
                const matchesContent = !content || cardContent.includes(content);
                const matchesSearch = !searchTerm || cardContent.includes(searchTerm);
                
                if (matchesStatus && matchesPtht && matchesUser && matchesContent && matchesSearch) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show no results message
            if (visibleCount === 0) {
                if (!document.getElementById('noResultsMessage')) {
                    const noResults = document.createElement('div');
                    noResults.id = 'noResultsMessage';
                    noResults.className = 'col-12 text-center py-5';
                    noResults.innerHTML = `
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>Không tìm thấy phiếu hỗ trợ phù hợp</h4>
                        <p class="text-muted">Vui lòng thử lại với bộ lọc khác</p>
                        <button class="btn-support btn-primary-support mt-3" id="clearAllFiltersNoResults">
                            <i class="fas fa-times"></i>
                            Xóa tất cả bộ lọc
                        </button>
                    `;
                    supportGrid.appendChild(noResults);
                    
                    document.getElementById('clearAllFiltersNoResults').addEventListener('click', function() {
                        resetFilterBtn.click();
                    });
                }
            } else {
                const noResults = document.getElementById('noResultsMessage');
                if (noResults) {
                    noResults.remove();
                }
            }
        }
        
        // Add filter tag
        function addFilterTag(text, removeCallback) {
            const tag = document.createElement('div');
            tag.className = 'active-filter-tag';
            tag.innerHTML = text;
            
            const removeBtn = document.createElement('i');
            removeBtn.className = 'fas fa-times';
            removeBtn.style.marginLeft = 'auto';
            removeBtn.addEventListener('click', removeCallback);
            tag.appendChild(removeBtn);
            
            activeFilters.appendChild(tag);
        }
        
        // Event listeners for search and filters
        searchInput.addEventListener('input', applyFilters);
        contentFilter.addEventListener('input', applyFilters);
        applyFilterBtn.addEventListener('click', applyFilters);
        
        resetFilterBtn.addEventListener('click', function() {
            statusFilter.value = '';
            pthtFilter.value = '';
            userFilter.value = '';
            contentFilter.value = '';
            searchInput.value = '';
            
            // Reset all cards visibility
            supportCards.forEach(card => {
                card.style.display = '';
            });
            
            // Clear active filters
            activeFilters.innerHTML = '';
            
            // Remove no results message if it exists
            const noResults = document.getElementById('noResultsMessage');
            if (noResults) {
                noResults.remove();
            }
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

        // Add support for status update forms
        const statusUpdateForms = document.querySelectorAll('.status-update-form');
        statusUpdateForms.forEach(form => {
            const buttons = form.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default to handle manually
                    
                    // Show loading state
                    const originalContent = this.innerHTML;
                    const buttonValue = this.value;
                    const buttonName = this.name;
                    
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.disabled = true;
                    
                    // Update other buttons in this form to be disabled
                    buttons.forEach(btn => {
                        if (btn !== this) {
                            btn.disabled = true;
                        }
                    });
                    
                    // Add a small delay for better UX
                    setTimeout(() => {
                        // Create a success notification
                        const card = this.closest('.support-card');
                        if (card) {
                            const statusBadge = card.querySelector('.support-status');
                            if (statusBadge) {
                                // Update the status badge visually for immediate feedback
                                if (buttonValue === "Đang xử lý") {
                                    statusBadge.textContent = "Đang xử lý";
                                    statusBadge.className = "support-status status-processing";
                                } else if (buttonValue === "Hoàn thành") {
                                    statusBadge.textContent = "Hoàn thành";
                                    statusBadge.className = "support-status status-completed";
                                }
                            }
                        }
                        
                        // Submit the form
                        form.submit();
                    }, 600);
                });
            });
        });
    });
</script>
@endsection