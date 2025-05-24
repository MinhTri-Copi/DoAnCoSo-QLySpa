@extends('backend.layouts.app')

@section('title', 'Quản Lý Dịch Vụ')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* ===== MODERN SPA DESIGN ===== */
    :root {
        --primary-pink: #ff6b95;
        --light-pink: #ffdbe9;
        --dark-pink: #e84a78;
        --text-primary: #2c3e50;
        --text-secondary: #7a8ca0;
        --info: #3498db;
        --primary: #8e44ad;
        --danger: #e74c3c;
        --light-gray: #f7f9fc;
        --white: #ffffff;
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 5px 25px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.09);
        --shadow-pink: 0 8px 25px rgba(255, 107, 149, 0.14);
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
    }

    /* ===== HEADER ===== */
    .spa-dashboard-header {
        background: linear-gradient(135deg, var(--primary-pink) 0%, #ff92b6 100%);
        border-radius: var(--radius-lg);
        padding: 2.2rem 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-pink);
        max-height: 160px;
    }

    .spa-dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
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

    .spa-header-content {
        position: relative;
        z-index: 4;
    }

    .spa-header-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.4rem;
        letter-spacing: 0.5px;
    }

    .spa-header-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        display: flex;
        align-items: center;
    }

    .spa-header-subtitle i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }

    .spa-header-action {
        position: relative;
        z-index: 4;
    }

    .spa-btn-add {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-pink);
        border: none;
        font-size: 1.05rem;
        font-weight: 600;
        padding: 0.8rem 1.7rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: var(--transition-fast);
        text-decoration: none;
    }

    .spa-btn-add i {
        font-size: 0.8rem;
        background: rgba(255, 107, 149, 0.15);
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: var(--transition-fast);
    }

    .spa-btn-add:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .spa-btn-add:hover i {
        background: rgba(255, 107, 149, 0.25);
    }

    /* Main container styles */
    .page-dichvu {
        background-color: #f8f9fa;
    }

    /* Stats cards */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .stat-title {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #343a40;
    }

    .bg-pink-gradient {
        background: linear-gradient(120deg, #f78ca0 0%, #f9748f 100%);
        color: white;
    }

    .bg-blue-gradient {
        background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
        color: white;
    }

    .bg-green-gradient {
        background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
        color: white;
    }

    .bg-orange-gradient {
        background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
        color: white;
    }

    /* Filters section */
    .filters-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 35px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .filter-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #343a40;
    }

    .filter-group label {
        font-size: 0.95rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 8px;
        display: block;
    }

    .filters-section input,
    .filters-section select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 12px 18px;
        width: 100%;
        transition: border 0.3s ease;
    }

    .filters-section input:focus,
    .filters-section select:focus {
        border-color: #ff9a9e;
        box-shadow: 0 0 0 3px rgba(255, 154, 158, 0.2);
        outline: none;
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        gap: 15px;
    }

    /* Services cards */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .service-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
        transform: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        border: 2px solid transparent;
        will-change: transform, box-shadow;
        cursor: pointer;
    }

    /* Completely reset any animations when card is loading */
    .service-card.animate__animated {
        animation: none !important;
    }

    /* Hiệu ứng hover cho các dịch vụ thường - chỉ nổi lên, không phóng to */
    .service-card:hover:not(.featured) {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    /* Hiệu ứng hover cho dịch vụ nổi bật - chỉ nổi lên, không phóng to */
    .service-card.featured:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .service-card-header {
        padding: 25px;
        background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .service-card-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .service-id {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-left: 5px;
    }

    .service-status {
        font-size: 0.7rem;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .status-pending {
        background-color: #FFC107;
        color: #333;
    }

    .status-processing {
        background-color: #3498DB;
        color: white;
    }

    .status-completed {
        background-color: #2ECC71;
        color: white;
    }

    .status-cancelled {
        background-color: #E74C3C;
        color: white;
    }

    .service-card-body {
        padding: 25px;
        flex-grow: 1;
    }

    .service-card-body p {
        margin: 15px 0;
        font-size: 1.05rem;
        color: #555;
    }

    .service-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        margin-bottom: 20px;
        border-radius: 10px;
    }

    .service-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #e83e8c;
        margin: 18px 0;
    }

    .service-time {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background-color: #e9ecef;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #495057;
        margin-top: 10px;
    }

    .service-time i {
        margin-right: 5px;
    }

    .service-card-footer {
        padding: 18px 25px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fa;
    }

    /* Action buttons */
    .service-action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        color: white;
    }

    .service-action-btn.view {
        background-color: #3498DB;
    }

    .service-action-btn.edit {
        background-color: #F39C12;
    }

    .service-action-btn.delete {
        background-color: #E74C3C;
    }

    .service-action-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
    }

    .service-action-btn.view:hover {
        background-color: #2980B9;
    }

    .service-action-btn.edit:hover {
        background-color: #D35400;
    }

    .service-action-btn.delete:hover {
        background-color: #C0392B;
    }

    /* Status update buttons */
    .service-action-btn.process {
        background-color: #3498DB;
        animation: button-pulse 2s infinite;
    }

    .service-action-btn.complete {
        background-color: #2ECC71;
    }

    .service-action-btn.process:hover {
        background-color: #2980B9;
    }

    .service-action-btn.complete:hover {
        background-color: #27AE60;
    }

    /* Add a subtle pulse animation */
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

    .status-update-form {
        display: contents;
    }

    .service-action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

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

    /* Featured badge */
    .featured-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(90deg, #ffd86f, #fc6262);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 3px 8px rgba(252, 98, 98, 0.3);
        animation: badge-pulse 2s infinite alternate;
    }

    @keyframes badge-pulse {
        0% {
            box-shadow: 0 0 5px rgba(252, 98, 98, 0.3);
        }
        100% {
            box-shadow: 0 0 15px rgba(252, 98, 98, 0.6);
        }
    }

    .featured-badge i {
        margin-right: 4px;
        animation: star-rotate 3s infinite linear;
    }

    @keyframes star-rotate {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(10deg); }
        50% { transform: rotate(0deg); }
        75% { transform: rotate(-10deg); }
        100% { transform: rotate(0deg); }
    }

    /* Thêm hiệu ứng viền cho dịch vụ nổi bật */
    .service-card.featured {
        border: 2px solid transparent;
        background-image: linear-gradient(white, white), 
                         linear-gradient(90deg, #ff9a9e, #fad0c4, #ffd86f);
        background-origin: border-box;
        background-clip: content-box, border-box;
        animation: border-animate 3s infinite alternate;
    }

    @keyframes border-animate {
        0% {
            background-image: linear-gradient(white, white), 
                             linear-gradient(90deg, #ff9a9e, #fad0c4, #ffd86f);
        }
        50% {
            background-image: linear-gradient(white, white), 
                             linear-gradient(90deg, #ffd86f, #ff9a9e, #fad0c4);
        }
        100% {
            background-image: linear-gradient(white, white), 
                             linear-gradient(90deg, #fad0c4, #ffd86f, #ff9a9e);
        }
    }

    /* Buttons */
    .btn-action {
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 500;
        transition: var(--transition-fast);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-outline {
        background: white;
        color: var(--text-secondary);
        border: none;
        box-shadow: var(--shadow-sm);
    }

    .btn-outline:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        color: var(--primary-pink);
    }

    .toolbar-actions {
        display: flex;
        gap: 20px;
    }

    .toolbar-actions .btn-action {
        background-color: #ffffff;
        box-shadow: var(--shadow-sm);
        padding: 10px 20px;
        border-radius: 50px;
        min-width: 120px;
        justify-content: center;
        font-size: 0.9rem;
    }

    /* Loại bỏ các quy tắc không còn phù hợp */
    .toolbar-actions .btn-action:first-child {
        border-radius: 50px;
    }

    .toolbar-actions .btn-action:last-child {
        border-radius: 50px;
    }

    .toolbar-actions .btn-action:not(:last-child)::after {
        display: none;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .pagination .page-item .page-link {
        border-radius: 8px;
        margin: 0 5px;
        color: #ff9a9e;
        border: 1px solid #f1f1f1;
        transition: all 0.3s;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 100%);
        border-color: transparent;
        color: white;
    }

    .pagination .page-item .page-link:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    /* Alert styling */
    .alert-custom {
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 25px;
        border: none;
        animation: fadeInDown 0.5s;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 5px solid #28a745;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 5px solid #dc3545;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border-left: 5px solid #ffc107;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border-left: 5px solid #17a2b8;
    }

    .top-toolbar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 20px;
        gap: 30px;
    }

    .search-container {
        margin-bottom: 0;
        max-width: 320px;
        width: 100%;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #adb5bd;
        margin-bottom: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .dashboard-header {
            padding: 20px;
        }

        .dashboard-title {
            font-size: 1.8rem;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .filters-form {
            grid-template-columns: 1fr;
        }

        .card-container {
            grid-template-columns: 1fr;
        }

        .stats-cards {
            grid-template-columns: 1fr;
        }

        .top-toolbar {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .toolbar-actions {
            width: 100%;
            justify-content: space-between;
        }

        .btn-action {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
    }

    .filters-form {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
        transition: var(--transition-fast);
    }

    .search-input-wrapper:hover {
        transform: translateY(-2px);
    }

    .search-input-wrapper input {
        width: 100%;
        padding: 12px 18px 12px 42px;
        border: none;
        border-radius: 50px;
        background-color: #ffffff;
        color: var(--text-primary);
        font-size: 0.95rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-fast);
    }

    .search-input-wrapper:hover input {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .search-input-wrapper input:focus {
        outline: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .search-input-wrapper input::placeholder {
        color: #aab2c8;
    }

    .search-input-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.9rem;
        color: #aab2c8;
        pointer-events: none;
    }

    .btn-pink {
        background: linear-gradient(120deg, #ff9a9e 0%, #fecfef 100%);
        color: white;
        border: none;
    }

    .btn-pink:hover {
        box-shadow: 0 5px 15px rgba(255, 154, 158, 0.3);
        transform: translateY(-2px);
    }

    .toolbar-actions .btn-action:hover {
        background-color: rgba(247, 248, 252, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        color: var(--primary-pink);
    }
</style>
@endsection

@section('content')
<div class="container-fluid page-dichvu" style="max-width: 1600px; margin: 0 auto; padding: 0 20px;">
    <!-- Dashboard Header -->
    <div class="spa-dashboard-header animate__animated animate__fadeIn">
        <div class="header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        
        <div class="spa-header-content">
            <h1 class="spa-header-title">Quản Lý Dịch Vụ</h1>
            <p class="spa-header-subtitle">
                <i class="fas fa-spa"></i>
                Thêm, sửa, xóa và theo dõi trạng thái các dịch vụ của spa
            </p>
        </div>
        
        <div class="spa-header-action">
            <a href="{{ route('admin.dichvu.create') }}" class="spa-btn-add">
                <i class="fas fa-plus"></i>
                <span>Thêm dịch vụ</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-custom alert-success animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-custom alert-danger animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-cards animate__animated animate__fadeInUp">
        <div class="stat-card">
            <div class="stat-icon bg-pink-gradient">
                <i class="fas fa-spa"></i>
            </div>
            <div class="stat-title">Tổng Dịch Vụ</div>
            <div class="stat-value">{{ $statistics['total'] }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-green-gradient">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-title">Giá Trung Bình</div>
            <div class="stat-value">{{ number_format($statistics['avg_price'], 0, ',', '.') }} VNĐ</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-orange-gradient">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-title">Dịch Vụ Đặt Nhiều Nhất</div>
            <div class="stat-value">{{ $statistics['most_booked'] ? $statistics['most_booked']->Tendichvu : 'N/A' }}</div>
        </div>
    </div>

    <!-- Top Toolbar -->
    <div class="top-toolbar">
        <div class="search-container">
            <form action="{{ route('admin.dichvu.index') }}" method="GET">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Tìm kiếm dịch vụ..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
        
        <div class="toolbar-actions">
            <a href="{{ route('admin.dichvu.export') }}" class="btn btn-action btn-outline">
                <i class="fas fa-file-export"></i> CSV
            </a>
            
            <a href="{{ route('admin.dichvu.analytics') }}" class="btn btn-action btn-outline">
                <i class="fas fa-chart-line"></i> Thống kê
            </a>
            
            <a href="{{ route('admin.dichvu.auto-featured') }}" class="btn btn-action btn-outline-warning">
                <i class="fas fa-star"></i> Tự động nổi bật
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section animate__animated animate__fadeIn">
        <h3 class="filter-title">Bộ Lọc</h3>
        <form action="{{ route('admin.dichvu.index') }}" method="GET" class="filters-form">
            <div class="filter-group">
                <label for="price_min">Giá tối thiểu</label>
                <input type="number" id="price_min" name="price_min" value="{{ request('price_min') }}" min="0" placeholder="VNĐ">
            </div>
            
            <div class="filter-group">
                <label for="price_max">Giá tối đa</label>
                <input type="number" id="price_max" name="price_max" value="{{ request('price_max') }}" min="0" placeholder="VNĐ">
            </div>
            
            <div class="filter-group">
                <label for="sort">Sắp xếp theo</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá (Thấp-Cao)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá (Cao-Thấp)</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="reset" class="btn btn-outline">Đặt lại</button>
                <button type="submit" class="btn btn-pink">Áp dụng</button>
            </div>
        </form>
    </div>

    <!-- Services Cards -->
    @if(count($dichVus) > 0)
        <div class="card-container animate__animated animate__fadeIn">
            @foreach($dichVus as $dichVu)
                <div class="service-card" data-id="{{ $dichVu->MaDV }}">
                    @if($dichVu->featured ?? false)
                        <div class="featured-badge">
                            <i class="fas fa-star"></i>Nổi bật
                        </div>
                    @endif
                    
                    <div class="service-card-header">
                        <h3 class="service-card-title">
                            {{ $dichVu->Tendichvu }}
                            <span class="service-id">#{{ $dichVu->MaDV }}</span>
                        </h3>
                    </div>
                    
                    <div class="service-card-body">
                        @if($dichVu->Image)
                            <img src="{{ asset($dichVu->Image) }}" alt="{{ $dichVu->Tendichvu }}" class="service-image">
                        @else
                            <div class="service-image" style="display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                <i class="fas fa-spa fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="service-price">{{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ</div>
                        
                        <p><strong>Mô Tả:</strong> {{ $dichVu->MoTa ?? 'Chưa có mô tả' }}</p>
                        
                        <div class="service-time">
                            <i class="far fa-clock"></i> 
                            {{ $dichVu->Thoigian ? $dichVu->Thoigian . ' phút' : '-' }}                        </div>
                    </div>
                    
                    <div class="service-card-footer">
                        <!-- Featured toggle with tooltip -->
                        <div class="action-tooltip">
                            <form action="{{ route('admin.dichvu.toggle-featured', $dichVu->MaDV) }}" method="POST">
                                @csrf
                                <button type="submit" class="service-action-btn {{ ($dichVu->featured ?? false) ? 'bg-warning' : 'bg-secondary' }}">
                                    <i class="fas fa-star"></i>
                                </button>
                                <span class="tooltip-text">
                                    {{ ($dichVu->featured ?? false) ? 'Bỏ nổi bật' : 'Đánh dấu nổi bật' }}
                                </span>
                            </form>
                        </div>
                        
                        <!-- Standard action buttons -->
                        <div class="standard-actions">
                            <a href="{{ route('admin.dichvu.edit', $dichVu->MaDV) }}" class="service-action-btn edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.dichvu.confirm-destroy', $dichVu->MaDV) }}" class="service-action-btn delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state animate__animated animate__fadeIn">
            <i class="fas fa-spa"></i>
            <h3>Chưa có dịch vụ nào</h3>
            <p>Bắt đầu bằng cách thêm dịch vụ đầu tiên của bạn</p>
            <a href="{{ route('admin.dichvu.create') }}" class="btn btn-action btn-pink">
                <i class="fas fa-plus-circle me-1"></i> Thêm Dịch Vụ
            </a>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            });
        }, 5000);

        // Reset button action
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = "{{ route('admin.dichvu.index') }}";
        });

        // Add support for status update forms
        const statusUpdateForms = document.querySelectorAll('.status-update-form');
        statusUpdateForms.forEach(form => {
            const buttons = form.querySelectorAll('button[name="Matrangthai"]');
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
                        const card = this.closest('.service-card');
                        if (card) {
                            const statusBadge = card.querySelector('.service-status');
                            if (statusBadge) {
                                // Update the status badge visually for immediate feedback
                                if (buttonValue === "{{ $processingStatus ?? '' }}") {
                                    statusBadge.textContent = "Đang xử lý";
                                    statusBadge.className = "service-status status-processing";
                                } else if (buttonValue === "{{ $completedStatus ?? '' }}") {
                                    statusBadge.textContent = "Hoàn thành";
                                    statusBadge.className = "service-status status-completed";
                                }
                            }
                        }
                        
                        // Submit the form
                        form.submit();
                    }, 600);
                });
            });
        });

        // Thêm class để style các dịch vụ nổi bật
        document.querySelectorAll('.featured-badge').forEach(badge => {
            badge.closest('.service-card').classList.add('featured');
        });

        // Xử lý sự kiện click cho thẻ dịch vụ
        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Kiểm tra nếu click vào các nút hành động thì không chuyển hướng
                if (e.target.closest('.service-action-btn') || 
                    e.target.closest('.action-tooltip') || 
                    e.target.closest('form')) {
                    return;
                }
                
                // Lấy ID dịch vụ từ thuộc tính data-id
                const dichVuId = this.getAttribute('data-id');
                if (dichVuId) {
                    // Tạo URL bằng cách thay thế trực tiếp vào đường dẫn
                    window.location.href = '/admin/dichvu/' + dichVuId;
                }
            });
        });
    });
</script>
@endsection