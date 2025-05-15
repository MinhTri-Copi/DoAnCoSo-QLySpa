@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/customers.css') }}" rel="stylesheet">
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
        --spa-primary: #ff6b95;
        --spa-primary-dark: #e84a78;
        --spa-secondary: #ffdbe9;
        --spa-accent: #ff4778;
        --spa-light: #fff0f5;
        --spa-dark: #d23964;
        --spa-text: #2c3e50;
        --spa-card-shadow: 0 8px 20px rgba(255, 107, 149, 0.15);
        --spa-gradient: linear-gradient(135deg, var(--primary-pink) 0%, var(--dark-pink) 100%);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    /* Card styling */
    .card.customer-card {
        border: none;
        border-radius: var(--radius-md);
        box-shadow: var(--spa-card-shadow);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card.customer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(255, 107, 149, 0.2);
    }

    /* Header styling */
    .card-header-gradient {
        background: var(--pink-gradient);
        color: white;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 100%);
        transform: skewX(-30deg);
    }

    /* Customer profile styling */
    .customer-profile {
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .customer-profile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, var(--light-pink) 0%, rgba(255,255,255,0) 100%);
        opacity: 0.3;
        z-index: 0;
    }

    .customer-profile-avatar {
        display: none;
    }

    .customer-profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: var(--spa-dark);
        position: relative;
        z-index: 1;
    }

    /* Membership badge styling */
    .customer-membership-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .membership-regular {
        background-color: var(--light-pink);
        color: var(--spa-dark);
    }

    .membership-vip {
        background: linear-gradient(120deg, #ffd700, #daa520);
        color: white;
        box-shadow: 0 3px 10px rgba(218, 165, 32, 0.3);
    }

    .membership-platinum {
        background: linear-gradient(120deg, #e0e0e0, #a9a9a9);
        color: white;
        box-shadow: 0 3px 10px rgba(169, 169, 169, 0.3);
    }

    .membership-diamond {
        background: linear-gradient(120deg, #b3e5fc, #4fc3f7);
        color: white;
        box-shadow: 0 3px 10px rgba(79, 195, 247, 0.3);
    }

    /* Customer info styling */
    .customer-info-list {
        margin-top: 1.5rem;
        text-align: left;
        position: relative;
        z-index: 1;
    }

    .customer-info-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed var(--light-pink);
    }

    .customer-info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .customer-info-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--light-pink);
        color: var(--spa-primary-dark);
        border-radius: 50%;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .customer-info-content {
        flex-grow: 1;
    }

    .customer-info-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .customer-info-value {
        font-weight: 500;
        color: var(--text-primary);
    }

    /* Stats card styling */
    .stat-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background-color: white;
        border-radius: var(--radius-md);
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255, 107, 149, 0.15);
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1rem;
        color: white;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #4fc3f7, #0288d1);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffb74d, #f57c00);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #81c784, #388e3c);
    }

    .stat-card-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-card-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20px;
        height: 100%;
        width: 2px;
        background-color: var(--light-pink);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        top: 0;
        left: -1.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-top: 5px;
    }

    .timeline-content {
        padding-left: 1rem;
    }

    .timeline-date {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .timeline-body {
        font-size: 0.9rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    /* Tabs styling */
    .custom-tabs {
        border-bottom: none;
        padding: 0 1rem;
        background-color: #f8f9fc;
    }

    .custom-tabs .nav-item {
        margin-bottom: 0;
    }

    .custom-tabs .nav-link {
        border: none;
        color: var(--text-secondary);
        padding: 1rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
    }

    .custom-tabs .nav-link:hover {
        color: var(--spa-primary);
        background-color: rgba(255, 107, 149, 0.05);
    }

    .custom-tabs .nav-link.active {
        color: var(--spa-primary-dark);
        background-color: white;
        border-bottom: 2px solid var(--spa-primary);
    }

    .custom-tabs .nav-link i {
        margin-right: 0.5rem;
    }

    /* Table styling */
    .customer-table {
        width: 100%;
        margin-bottom: 0;
    }

    .customer-table thead th {
        background-color: var(--light-gray);
        border: none;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        text-transform: uppercase;
    }

    .customer-table tbody tr {
        transition: background-color 0.3s ease;
        border-bottom: 1px solid var(--border-color);
    }

    .customer-table tbody tr:hover {
        background-color: rgba(255, 219, 233, 0.1);
    }

    .customer-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    /* Badge status styling */
    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-status-success {
        background-color: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .badge-status-warning {
        background-color: rgba(241, 196, 15, 0.1);
        color: #f1c40f;
    }

    .badge-status-danger {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .badge-status-secondary {
        background-color: rgba(189, 195, 199, 0.1);
        color: #7f8c8d;
    }

    /* Button styling */
    .btn-custom {
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
    }

    /* Pink-themed buttons */
    .btn-primary {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
    }

    .btn-primary:hover {
        background-color: var(--spa-primary-dark);
        border-color: var(--spa-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 107, 149, 0.3);
    }

    .btn-outline-primary {
        color: var(--spa-primary);
        border-color: var(--spa-primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
        color: white;
    }

    .btn-outline-primary.active {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
        color: white;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    /* Form styling */
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--spa-primary);
        border-color: var(--spa-primary);
    }

    .custom-control-label.text-success {
        color: var(--spa-primary) !important;
    }

    /* Animation for the page */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .customer-card {
        animation: fadeIn 0.5s ease-out forwards;
    }

    .customer-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .customer-card:nth-child(3) {
        animation-delay: 0.2s;
    }

    /* Customer Header Styling */
    .customer-header {
        background: var(--pink-gradient);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: var(--spa-card-shadow);
        display: flex;
        align-items: center;
        min-height: 120px;
    }

    .customer-header-background {
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background-image: radial-gradient(circle, rgba(255,255,255,0.15) 10%, transparent 70%);
        z-index: 1;
    }

    .customer-header-shimmer {
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

    .customer-header .glitter-dot {
        position: absolute;
        background: white;
        border-radius: 50%;
        opacity: 0;
        z-index: 3;
        box-shadow: 0 0 10px 2px rgba(255,255,255,0.8);
        animation: glitter 8s infinite;
    }

    .customer-header .glitter-dot:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 25%;
        left: 10%;
        animation-delay: 0s;
    }

    .customer-header .glitter-dot:nth-child(2) {
        width: 6px;
        height: 6px;
        top: 40%;
        left: 30%;
        animation-delay: 2s;
    }

    .customer-header .glitter-dot:nth-child(3) {
        width: 3px;
        height: 3px;
        top: 20%;
        right: 25%;
        animation-delay: 4s;
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

    .customer-header-content {
        position: relative;
        z-index: 5;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .customer-header-info {
        display: flex;
        align-items: center;
    }

    .customer-header-avatar {
        display: none;
    }

    .customer-header-details {
        flex: 1;
    }

    .customer-header-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }

    .customer-header-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .customer-header-meta-item {
        margin-right: 1.5rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        color: rgba(255,255,255,0.85);
    }

    .customer-header-meta-item i {
        margin-right: 0.5rem;
        opacity: 0.9;
    }

    .customer-header-meta-item strong {
        font-weight: 700;
        color: white;
    }

    .customer-header-actions {
        display: flex;
        align-items: center;
    }

    .btn-light {
        background-color: white;
        color: var(--spa-primary-dark);
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        margin-right: 1rem;
    }

    .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        background-color: white;
        color: var(--spa-primary);
    }

    .btn-light-outline {
        background-color: rgba(255,255,255,0.15);
        color: white;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
    }

    .btn-light-outline:hover {
        background-color: rgba(255,255,255,0.25);
        border-color: rgba(255,255,255,0.4);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        color: white;
    }

    .btn-icon-inner {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
    }

    .btn-text {
        font-weight: 600;
    }

    /* Responsive adjustments for customer header */
    @media (max-width: 767px) {
        .customer-header {
            padding: 1.5rem;
        }
        
        .customer-header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .customer-header-actions {
            margin-top: 1rem;
            align-self: flex-end;
        }
        
        .customer-header-avatar {
            width: 60px;
            height: 60px;
        }
        
        .customer-header-title {
            font-size: 1.5rem;
        }
        
        .customer-header-meta {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .customer-header-meta-item {
            margin-right: 0;
            margin-bottom: 0.5rem;
        }
    }

    /* Empty state styling */
    .empty-state-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem 2rem;
        text-align: center;
    }

    .empty-state {
        max-width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        color: #e0e0e0;
        background-color: #f9f9f9;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--spa-primary-dark);
    }

    .empty-state-text {
        color: #757575;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    /* Filter buttons */
    .filter-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        border-radius: 20px;
        font-size: 0.8rem;
        padding: 0.3rem 1rem;
        border: 1px solid #dee2e6;
        background-color: white;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        background-color: #f8f9fa;
    }

    .filter-btn.active {
        background-color: var(--spa-primary);
        color: white;
        border-color: var(--spa-primary);
    }

    .filter-btn.filter-success {
        border-color: #28a745;
        color: #28a745;
    }

    .filter-btn.filter-success:hover,
    .filter-btn.filter-success.active {
        background-color: #28a745;
        color: white;
    }

    .filter-btn.filter-warning {
        border-color: #ffc107;
        color: #ffc107;
    }

    .filter-btn.filter-warning:hover,
    .filter-btn.filter-warning.active {
        background-color: #ffc107;
        color: white;
    }

    .filter-btn.filter-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .filter-btn.filter-danger:hover,
    .filter-btn.filter-danger.active {
        background-color: #dc3545;
        color: white;
    }

    /* Reviews styling */
    .reviews-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .review-card {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .review-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .rating {
        display: flex;
        align-items: center;
    }

    .review-date {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .review-service {
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .service-label {
        color: var(--text-secondary);
        margin-right: 0.5rem;
    }

    .service-name {
        color: var(--spa-primary-dark);
        font-weight: 500;
    }

    .review-content {
        color: var(--text-primary);
        line-height: 1.5;
    }

    .review-content p {
        margin-bottom: 0;
    }

    /* Customer tabs styling */
    .customer-tabs {
        display: flex;
        overflow-x: auto;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e0e0e0;
    }

    .customer-tab {
        padding: 1rem 1.5rem;
        color: #6c757d;
        text-decoration: none;
        white-space: nowrap;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        border-bottom: 2px solid transparent;
    }

    .customer-tab:hover {
        color: var(--spa-primary);
        text-decoration: none;
        background-color: rgba(255, 107, 149, 0.05);
    }

    .customer-tab.active {
        color: var(--spa-primary);
        background-color: white;
        border-bottom: 2px solid var(--spa-primary);
    }

    .customer-tab i {
        margin-right: 0.5rem;
        font-size: 0.9em;
    }

    /* Pink-themed tabs */
    .customer-tab.active {
        color: var(--spa-primary);
    }

    /* Hide scrollbar for tabs */
    .customer-tabs::-webkit-scrollbar {
        display: none;
    }

    .customer-tabs {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="customer-header mb-4">
        <div class="customer-header-background"></div>
        <div class="customer-header-shimmer"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        
        <div class="customer-header-content">
            <div class="customer-header-info">
                <div class="customer-header-details">
                    <h1 class="customer-header-title">
                        <i class="fas fa-user-circle mr-2"></i>Chi Tiết Khách Hàng
                    </h1>
                    <div class="customer-header-meta">
                        <div class="customer-header-meta-item">
                            <i class="fas fa-id-card"></i> Mã KH: <strong>{{ $customer->Manguoidung }}</strong>
                        </div>
                        <div class="customer-header-meta-item">
                            <i class="fas fa-clock"></i> Tham gia: <strong>{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') : 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="customer-header-actions">
                <a href="{{ route('admin.customers.edit', $customer->Manguoidung) }}" class="btn btn-light btn-icon">
                    <span class="btn-icon-inner">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="btn-text">Chỉnh Sửa</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Customer Profile Column -->
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-id-card mr-2"></i>Thông Tin Khách Hàng
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="customer-profile">
                        <h4 class="customer-profile-name">{{ $customer->Hoten }}</h4>
                        
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
                        
                        <div class="customer-membership-badge {{ $badgeClass }}">
                            @if($hangName == 'VIP' || $hangName == 'Platinum' || $hangName == 'Diamond')
                                <i class="fas fa-crown mr-1"></i>
                            @endif
                            {{ $hangName }}
                        </div>
                        
                        <div class="customer-info-list">
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Email</div>
                                    <div class="customer-info-value">{{ $customer->Email }}</div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Số Điện Thoại</div>
                                    <div class="customer-info-value">{{ $customer->SDT }}</div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Địa Chỉ</div>
                                    <div class="customer-info-value">{{ $customer->DiaChi }}</div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Ngày Sinh</div>
                                    <div class="customer-info-value">
                                        {{ \Carbon\Carbon::parse($customer->Ngaysinh)->format('d/m/Y') }}
                                        <small class="text-muted ml-2">
                                            ({{ \Carbon\Carbon::parse($customer->Ngaysinh)->age }} tuổi)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-venus-mars"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Giới Tính</div>
                                    <div class="customer-info-value">
                                        {{ $customer->Gioitinh }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="customer-info-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="customer-info-content">
                                    <div class="customer-info-label">Tài Khoản</div>
                                    <div class="customer-info-value">
                                        {{ $customer->account ? $customer->account->Tendangnhap : 'N/A' }}
                                        @if($customer->account && $customer->account->role)
                                            <span class="badge badge-secondary ml-2">{{ $customer->account->role->Tenquyen }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Statistics Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-pie mr-2"></i>Thống Kê Khách Hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="stat-card-value">{{ $customer->hoaDon->count() }}</div>
                                <div class="stat-card-label">Đơn Hàng</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-info">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-card-value">{{ $customer->datLich->count() }}</div>
                                <div class="stat-card-label">Lịch Hẹn</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-warning">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-card-value">{{ $customer->danhGia->count() }}</div>
                                <div class="stat-card-label">Đánh Giá</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12 col-xl-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-card-icon bg-gradient-success">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-card-value">
                                    @php
                                        $totalPoints = 0;
                                        foreach($customer->lsDiemThuong as $point) {
                                            if($point->Loai == 'Cộng') {
                                                $totalPoints += $point->Diem;
                                            } else {
                                                $totalPoints -= $point->Diem;
                                            }
                                        }
                                    @endphp
                                    {{ $totalPoints }}
                                </div>
                                <div class="stat-card-label">Điểm Thưởng</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button class="btn btn-primary btn-custom btn-icon" id="addPointsBtn2">
                            <span class="btn-icon"><i class="fas fa-plus-circle"></i></span>
                            Thêm Điểm Thưởng
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-history mr-2"></i>Hoạt Động Gần Đây
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="timeline p-3">
                        @php
                            $recentActivities = collect();
                            
                            // Add orders
                            foreach($customer->hoaDon->take(2) as $order) {
                                $recentActivities->push([
                                    'date' => $order->Ngaytao,
                                    'type' => 'order',
                                    'icon' => 'fa-shopping-cart',
                                    'color' => 'primary',
                                    'title' => 'Đặt đơn hàng #' . $order->MaHD,
                                    'content' => 'Tổng giá trị: ' . number_format($order->Tongtien, 0, ',', '.') . ' VNĐ',
                                    'link' => '#',
                                    'link_text' => 'Xem chi tiết'
                                ]);
                            }
                            
                            // Add appointments
                            foreach($customer->datLich->take(2) as $appointment) {
                                $recentActivities->push([
                                    'date' => $appointment->Thoigiandatlich,
                                    'type' => 'appointment',
                                    'icon' => 'fa-calendar-check',
                                    'color' => 'info',
                                    'title' => 'Đặt lịch hẹn dịch vụ',
                                    'content' => ($appointment->dichVu ? $appointment->dichVu->Tendichvu : 'N/A'),
                                    'link' => '#',
                                    'link_text' => 'Xem chi tiết'
                                ]);
                            }
                            
                            // Add points
                            foreach($customer->lsDiemThuong->take(2) as $point) {
                                $recentActivities->push([
                                    'date' => $point->Ngay,
                                    'type' => 'points',
                                    'icon' => 'fa-coins',
                                    'color' => $point->Loai == 'Cộng' ? 'success' : 'danger',
                                    'title' => $point->Loai . ' ' . $point->Diem . ' điểm',
                                    'content' => $point->Ghichu,
                                    'link' => '#',
                                    'link_text' => 'Lịch sử điểm'
                                ]);
                            }
                            
                            // Sort by date (newest first)
                            $recentActivities = $recentActivities->sortByDesc('date')->take(5);
                        @endphp
                        
                        @forelse($recentActivities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $activity['color'] }}"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas {{ $activity['icon'] }} mr-1 text-{{ $activity['color'] }}"></i>
                                        {{ $activity['title'] }}
                                    </div>
                                    <div class="timeline-body">
                                        {{ $activity['content'] }}
                                    </div>
                                    <a href="{{ $activity['link'] }}" class="btn btn-sm btn-link px-0">
                                        {{ $activity['link_text'] }} <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                                <p class="mb-0 text-gray-500">Không có hoạt động gần đây</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Detail Tabs Column -->
        <div class="col-xl-8 col-lg-7">
            <!-- Tabs Card -->
            <div class="card customer-card mb-4">
                <div class="card-header-gradient py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-folder-open mr-2"></i>Chi Tiết Khách Hàng
                    </h6>
                </div>
                <div class="card-body p-0">
                    <!-- Tabs Nav -->
                    <div class="customer-tabs">
                        <a href="#orders" class="customer-tab active" data-toggle="tab">
                            <i class="fas fa-shopping-cart mr-2"></i>Đơn Hàng
                        </a>
                        <a href="#appointments" class="customer-tab" data-toggle="tab">
                            <i class="fas fa-calendar-check mr-2"></i>Lịch Hẹn
                        </a>
                        <a href="#points" class="customer-tab" data-toggle="tab">
                            <i class="fas fa-coins mr-2"></i>Điểm Thưởng
                        </a>
                        <a href="#reviews" class="customer-tab" data-toggle="tab">
                            <i class="fas fa-star mr-2"></i>Đánh Giá
                        </a>
                    </div>
                    
                    <!-- Tabs Content -->
                    <div class="tab-content p-4" id="customerTabsContent">
                        <!-- Orders Tab -->
                        <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-cart text-primary mr-2"></i>Lịch Sử Đơn Hàng
                                    <span class="badge badge-primary ml-2" id="visibleOrdersCount">{{ $customer->hoaDon->count() }}</span>
                                </h5>
                                <div class="filter-buttons">
                                    <button class="btn btn-sm filter-btn active" data-filter="all">Tất cả</button>
                                    <button class="btn btn-sm filter-btn filter-success" data-filter="Hoàn thành">Hoàn Thành</button>
                                    <button class="btn btn-sm filter-btn filter-warning" data-filter="Đang xử lý">Đang Xử Lý</button>
                                    <button class="btn btn-sm filter-btn filter-danger" data-filter="Đã hủy">Đã Hủy</button>
                                </div>
                            </div>
                            
                            @if($customer->hoaDon->count() > 0)
                                <div class="table-responsive">
                                    <table class="table customer-table">
                                        <thead>
                                            <tr>
                                                <th>Mã ĐH</th>
                                                <th>Ngày Đặt</th>
                                                <th>Dịch Vụ</th>
                                                <th>Tổng Tiền</th>
                                                <th>Trạng Thái</th>
                                                <th class="text-right">Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->hoaDon as $order)
                                                @php
                                                    $statusClass = 'secondary';
                                                    $status = $order->trangThai ? $order->trangThai->Tentrangthai : 'N/A';
                                                    
                                                    if($status == 'Hoàn thành') {
                                                        $statusClass = 'success';
                                                    } elseif($status == 'Đang xử lý') {
                                                        $statusClass = 'warning';
                                                    } elseif($status == 'Đã hủy') {
                                                        $statusClass = 'danger';
                                                    }
                                                @endphp
                                                
                                                <tr class="order-row" 
                                                    data-id="{{ $order->MaHD }}" 
                                                    data-status="{{ $status }}"
                                                    data-date="{{ \Carbon\Carbon::parse($order->Ngaytao)->format('d/m/Y') }}"
                                                    data-amount="{{ number_format($order->Tongtien, 0, ',', '.') }} VNĐ"
                                                    data-method="{{ $order->phuongThuc ? $order->phuongThuc->Tenphuongthuc : 'N/A' }}">
                                                    <td>
                                                        <span class="font-weight-bold">HD{{ $order->MaHD }}</span>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($order->Ngaytao)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($order->datLich && $order->datLich->dichVu)
                                                            {{ $order->datLich->dichVu->Tendichvu }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($order->Tongtien, 0, ',', '.') }} VNĐ</td>
                                                    <td>
                                                        <span class="badge-status badge-status-{{ $statusClass }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <button class="btn btn-sm btn-primary view-order" data-id="{{ $order->MaHD }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state-container">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <h4 class="empty-state-text">Khách hàng chưa có đơn hàng nào</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Appointments Tab -->
                        <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-check text-primary mr-2"></i>Lịch Hẹn
                                    <span class="badge badge-primary ml-2" id="visibleAppointmentsCount">{{ $customer->datLich->count() }}</span>
                                </h5>
                                <div class="filter-buttons">
                                    <button class="btn btn-sm filter-btn active" data-filter="all">Tất cả</button>
                                    <button class="btn btn-sm filter-btn filter-success" data-filter="Hoàn thành">Hoàn Thành</button>
                                    <button class="btn btn-sm filter-btn filter-warning" data-filter="Đã xác nhận">Đã Xác Nhận</button>
                                    <button class="btn btn-sm filter-btn filter-danger" data-filter="Đã hủy">Đã Hủy</button>
                                </div>
                            </div>
                            
                            @if($customer->datLich->count() > 0)
                                <div class="table-responsive">
                                    <table class="table customer-table">
                                        <thead>
                                            <tr>
                                                <th>Mã ĐL</th>
                                                <th>Thời Gian</th>
                                                <th>Dịch Vụ</th>
                                                <th>Trạng Thái</th>
                                                <th class="text-right">Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->datLich as $appointment)
                                                @php
                                                    $statusClass = 'secondary';
                                                    $trangthai = $appointment->Trangthai_;
                                                    if($trangthai) {
                                                        if($trangthai == 'Hoàn thành') {
                                                            $statusClass = 'success';
                                                        } elseif($trangthai == 'Đã xác nhận' || $trangthai == 'Đang chờ' || $trangthai == 'Chờ xác nhận') {
                                                            $statusClass = 'warning';
                                                        } elseif($trangthai == 'Đã hủy') {
                                                            $statusClass = 'danger';
                                                        }
                                                    }
                                                @endphp
                                                
                                                <tr class="appointment-row" 
                                                    data-id="{{ $appointment->MaDL }}" 
                                                    data-status="{{ $trangthai }}"
                                                    data-date="{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('d/m/Y') }}"
                                                    data-time="{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('H:i') }}"
                                                    data-service="{{ $appointment->dichVu ? $appointment->dichVu->Tendichvu : 'N/A' }}">
                                                    <td>
                                                        <span class="font-weight-bold">DL{{ $appointment->MaDL }}</span>
                                                    </td>
                                                    <td>
                                                        <div>{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('d/m/Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->Thoigiandatlich)->format('H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($appointment->dichVu)
                                                            {{ $appointment->dichVu->Tendichvu }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge-status badge-status-{{ $statusClass }}">
                                                            {{ $trangthai }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <button class="btn btn-sm btn-primary view-appointment" data-id="{{ $appointment->MaDL }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state-container">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <h4 class="empty-state-text">Khách hàng chưa có lịch hẹn nào</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Points Tab -->
                        <div class="tab-pane fade" id="points" role="tabpanel" aria-labelledby="points-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-coins text-primary mr-2"></i>Lịch Sử Điểm Thưởng
                                    <span class="badge badge-primary ml-2" id="visiblePointsCount">{{ $customer->lsDiemThuong->count() }}</span>
                                </h5>
                                <button class="btn btn-sm btn-primary" id="addPointsBtn3">
                                    <i class="fas fa-plus-circle mr-1"></i> Thêm Điểm Thưởng
                                </button>
                            </div>
                            
                            @if($customer->lsDiemThuong->count() > 0)
                                <div class="table-responsive">
                                    <table class="table customer-table">
                                        <thead>
                                            <tr>
                                                <th>Ngày</th>
                                                <th>Loại</th>
                                                <th>Điểm</th>
                                                <th>Ghi Chú</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->lsDiemThuong->sortByDesc('Ngay') as $point)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($point->Ngay)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($point->Loai == 'Cộng')
                                                            <span class="badge-status badge-status-success">Cộng điểm</span>
                                                        @else
                                                            <span class="badge-status badge-status-danger">Trừ điểm</span>
                                                        @endif
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        @if($point->Loai == 'Cộng')
                                                            <span class="text-success">+{{ $point->Diem }}</span>
                                                        @else
                                                            <span class="text-danger">-{{ $point->Diem }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $point->Ghichu }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state-container">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <h4 class="empty-state-text">Khách hàng chưa có điểm thưởng nào</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-star text-primary mr-2"></i>Đánh Giá Của Khách Hàng
                                    <span class="badge badge-primary ml-2" id="visibleReviewsCount">{{ $customer->danhGia->count() }}</span>
                                </h5>
                            </div>
                            
                            @if($customer->danhGia->count() > 0)
                                <div class="reviews-container">
                                    @foreach($customer->danhGia->sortByDesc('Ngaydanhgia') as $review)
                                        <div class="review-card">
                                            <div class="review-header">
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->Sosao)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-2 font-weight-bold">{{ $review->Sosao }}/5</span>
                                                </div>
                                                <div class="review-date">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="review-service">
                                                <span class="service-label">Dịch vụ:</span>
                                                <span class="service-name">
                                                    @if($review->dichVu)
                                                        {{ $review->dichVu->Tendichvu }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="review-content">
                                                <p>{{ $review->Noidung ?: 'Không có nội dung đánh giá' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state-container">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h4 class="empty-state-text">Khách hàng chưa có đánh giá nào</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Points Modal -->
    <div class="modal fade" id="addPointsModal" tabindex="-1" role="dialog" aria-labelledby="addPointsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header card-header-gradient">
                    <h5 class="modal-title text-white" id="addPointsModalLabel">
                        <i class="fas fa-coins mr-2"></i>Thêm Điểm Thưởng
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.customers.addPoints', $customer->Manguoidung) }}" method="POST" id="addPointsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="point_type" class="form-label">Loại Giao Dịch <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="pointTypeAdd" name="point_type" value="Cộng" class="custom-control-input" checked>
                                    <label class="custom-control-label text-success" for="pointTypeAdd">Cộng Điểm</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="pointTypeSubtract" name="point_type" value="Trừ" class="custom-control-input">
                                    <label class="custom-control-label text-danger" for="pointTypeSubtract">Trừ Điểm</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pointsAmount" class="form-label">
                                <span id="pointTypeLabel" class="text-success">Cộng Điểm</span> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                </div>
                                <input type="number" class="form-control" id="pointsAmount" name="points" min="1" required>
                            </div>
                            <small class="form-text text-muted">Nhập số điểm cần thêm/trừ</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="pointsNote" class="form-label">Ghi Chú <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="pointsNote" name="note" rows="3" required placeholder="Nhập lý do thêm/trừ điểm"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary" id="submitPoints">
                            <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Point handling
        const addPointsBtn = document.getElementById('addPointsBtn');
        const addPointsBtn2 = document.getElementById('addPointsBtn2');
        const addPointsBtn3 = document.getElementById('addPointsBtn3');
        const addPointsModal = document.getElementById('addPointsModal');
        const pointTypeAdd = document.getElementById('pointTypeAdd');
        const pointTypeSubtract = document.getElementById('pointTypeSubtract');
        const pointTypeLabel = document.getElementById('pointTypeLabel');
        
        if (addPointsBtn) {
            addPointsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                $('#addPointsModal').modal('show');
            });
        }
        
        if (addPointsBtn2) {
            addPointsBtn2.addEventListener('click', function(e) {
                e.preventDefault();
                $('#addPointsModal').modal('show');
            });
        }
        
        if (addPointsBtn3) {
            addPointsBtn3.addEventListener('click', function(e) {
                e.preventDefault();
                $('#addPointsModal').modal('show');
            });
        }
        
        if (pointTypeAdd && pointTypeSubtract && pointTypeLabel) {
            pointTypeAdd.addEventListener('change', function() {
                if (this.checked) {
                    pointTypeLabel.textContent = 'Cộng Điểm';
                    pointTypeLabel.className = 'text-success';
                }
            });
            
            pointTypeSubtract.addEventListener('change', function() {
                if (this.checked) {
                    pointTypeLabel.textContent = 'Trừ Điểm';
                    pointTypeLabel.className = 'text-danger';
                }
            });
        }
        
        if (document.getElementById('submitPoints')) {
            document.getElementById('submitPoints').addEventListener('click', function() {
                document.getElementById('addPointsForm').submit();
            });
        }
        
        // Order tab filtering
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Get filter value and parent container
                const filterValue = this.getAttribute('data-filter');
                const tabPane = this.closest('.tab-pane');
                
                // Update active state for buttons within the same tab
                const tabFilterBtns = tabPane.querySelectorAll('.filter-btn');
                tabFilterBtns.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Get the appropriate rows to filter
                let rows;
                if (tabPane.id === 'orders') {
                    rows = tabPane.querySelectorAll('.order-row');
                } else if (tabPane.id === 'appointments') {
                    rows = tabPane.querySelectorAll('.appointment-row');
                }
                
                // Apply filter
                if (rows) {
                    let visibleCount = 0;
                    
                    rows.forEach(row => {
                        const status = row.getAttribute('data-status');
                        
                        if (filterValue === 'all' || status === filterValue) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    
                    // Update visible count
                    const countElement = tabPane.querySelector('[id$="Count"]');
                    if (countElement) {
                        countElement.textContent = visibleCount;
                    }
                }
            });
        });
        
        // Add click events for view buttons
        const viewOrderBtns = document.querySelectorAll('.view-order');
        viewOrderBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');
                // Here we would typically navigate to order details
                // For now, just show an alert
                alert('Xem chi tiết đơn hàng #' + orderId);
            });
        });
        
        const viewAppointmentBtns = document.querySelectorAll('.view-appointment');
        viewAppointmentBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const appointmentId = this.getAttribute('data-id');
                // Here we would typically navigate to appointment details
                // For now, just show an alert
                alert('Xem chi tiết lịch hẹn #' + appointmentId);
            });
        });

        // Handle tab switching
        const customerTabs = document.querySelectorAll('.customer-tab');
        
        customerTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                customerTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab panes
                const tabPanes = document.querySelectorAll('.tab-pane');
                tabPanes.forEach(pane => {
                    pane.classList.remove('show');
                    pane.classList.remove('active');
                });
                
                // Show the selected tab pane
                const targetId = this.getAttribute('href');
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('show');
                    targetPane.classList.add('active');
                }
            });
        });
    });
</script>
@endsection