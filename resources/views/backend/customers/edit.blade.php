@extends('backend.layouts.app')

@section('styles')
<link href="{{ asset('css/admin/customers.css') }}" rel="stylesheet">
<style>
    :root {
        --primary-pink: #ff6b95;
        --dark-pink: #e84a78;
        --light-pink: #ffdbe9;
        --light-pink-hover: #ffd0e1;
        --pink-gradient: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
        --text-color: #455a64;
        --border-color: #e0e0e0;
        --bg-light: #f9f9f9;
    }
    
    /* Additional fix for select boxes */
    select.form-control, 
    select.form-control-spa,
    #MaTK {
        text-indent: 0 !important;
        padding-left: 15px !important;
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
    }
    
    /* Ensure the text in options is fully visible */
    select option {
        padding: 10px !important;
        text-overflow: unset !important;
        white-space: normal !important;
        overflow: visible !important;
        width: 100% !important;
    }
    
    /* Spa-themed styles for edit page */
    .customer-dashboard-header {
        background: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(255, 107, 149, 0.25);
        color: white;
    }

    .customer-dashboard-header::before {
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
        gap: 1.5rem;
        position: relative;
        z-index: 4;
    }
    
    .page-heading {
        background: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
        border-radius: 10px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(255, 107, 149, 0.25);
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
    
    .form-group label {
        font-weight: 600;
        color: var(--primary-pink);
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }
    
    .text-danger {
        color: var(--primary-pink) !important;
    }
    
    .form-group small {
        color: #8a94a6;
    }
    
    .form-control-spa, .form-control {
        border-radius: 50px;
        padding: 0.85rem 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        height: calc(2.5rem + 2px);
        font-size: 1rem;
    }
    
    textarea.form-control-spa, textarea.form-control {
        height: auto;
        min-height: 100px;
        padding-left: 1.5rem;
    }
    
    .input-group-spa {
        margin-bottom: 0.5rem;
        position: relative;
    }
    
    .input-group-spa .form-control {
        padding-left: 1.5rem;
        height: calc(2.5rem + 2px);
        border-radius: 50px;
    }
    
    .row {
        margin-bottom: 1.5rem;
    }
    
    hr {
        margin: 2rem 0;
        border-color: rgba(255, 107, 149, 0.1);
        opacity: 0.5;
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
    
    .input-group-spa i {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-pink);
        z-index: 10;
        font-size: 1rem;
    }
    
    .radio-spa {
        margin-right: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .radio-spa input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .radio-spa-checkmark {
        position: relative;
        display: inline-block;
        width: 22px;
        height: 22px;
        margin-right: 8px;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .radio-spa input:checked ~ .radio-spa-checkmark {
        border-color: var(--primary-pink);
    }
    
    .radio-spa-checkmark:after {
        content: "";
        position: absolute;
        display: none;
        top: 3px;
        left: 3px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-pink);
    }
    
    .radio-spa input:checked ~ .radio-spa-checkmark:after {
        display: block;
    }
    
    .membership-display {
        padding: 1.5rem;
        background: rgba(255, 107, 149, 0.05);
        border-radius: 16px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
    
    .spa-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }
    
    .membership-regular {
        background-color: var(--light-pink);
        color: var(--dark-pink);
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

    .spa-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        background-color: #fff;
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .spa-card-header {
        padding: 1.5rem 2rem;
        background: rgba(255, 107, 149, 0.05);
        border-bottom: 1px solid rgba(255, 107, 149, 0.1);
    }

    .spa-card-body {
        padding: 2.5rem;
    }

    /* Form styling */
    .form-section-title {
        color: var(--primary-pink);
        font-weight: 600;
        margin-bottom: 1.5rem;
        border-bottom: 1px dashed rgba(255, 107, 149, 0.2);
        padding-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.8rem;
        position: relative;
    }

    /* Đảm bảo select box đủ rộng để hiển thị nội dung */
    .form-group select {
        width: 100%;
        display: block;
        overflow: visible !important;
        text-overflow: unset !important;
    }

    /* Loại bỏ cơ chế truncate text */
    select option {
        white-space: normal;
        overflow: visible;
        width: auto;
        padding: 10px;
    }
    
    /* Fix cho #MaTK dropdown */
    #MaTK {
        padding-left: 1.5rem !important;
        text-overflow: unset !important;
        overflow: visible !important;
        border-radius: 50px;
        background-position: right 1rem center !important;
    }

    /* Đảm bảo select box hiển thị full text khi được chọn */
    select:focus option:checked {
        font-weight: bold;
        display: block;
    }

    /* Đặc biệt cho #MaTK */
    #MaTK {
        width: 100% !important;
        padding-right: 2.5rem !important;
        overflow: visible !important;
        white-space: normal !important;
        text-overflow: unset !important;
        height: auto !important;
        min-height: calc(2.5rem + 2px);
        word-break: normal !important;
        text-indent: 0 !important;
        font-size: 0.95rem !important;
        letter-spacing: -0.2px;
    }

    /* Larger padding for dropdown to fit text */
    .form-group select#MaTK option {
        padding: 10px 15px !important;
        white-space: normal !important;
        font-size: 0.95rem !important;
    }
    
    /* Adjust text rendering in form controls */
    .form-control, .form-control-spa {
        text-rendering: optimizeLegibility;
    }

    .form-control-spa:focus, .form-control:focus {
        border-color: var(--primary-pink);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 149, 0.25);
    }

    .input-group-spa {
        margin-bottom: 0.5rem;
        position: relative;
    }

    .input-group-spa i {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-pink);
        z-index: 10;
        font-size: 1rem;
    }

    .row {
        margin-bottom: 1.5rem;
    }

    hr {
        margin: 2rem 0;
        border-color: rgba(255, 107, 149, 0.1);
        opacity: 0.5;
    }

    /* Gender selector */
    .gender-selector {
        display: flex;
        gap: 2rem;
        margin-top: 0.5rem;
    }
    
    .radio-spa {
        margin-right: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .radio-spa input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .radio-spa-checkmark {
        position: relative;
        display: inline-block;
        width: 22px;
        height: 22px;
        margin-right: 8px;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .radio-spa input:checked ~ .radio-spa-checkmark {
        border-color: var(--primary-pink);
    }
    
    .radio-spa-checkmark:after {
        content: "";
        position: absolute;
        display: none;
        top: 3px;
        left: 3px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-pink);
    }
    
    .radio-spa input:checked ~ .radio-spa-checkmark:after {
        display: block;
    }
    
    /* Buttons */
    .header-actions {
        display: flex;
        gap: 1.5rem;
        position: relative;
        z-index: 4;
    }

    .btn-light {
        background-color: white;
        color: var(--primary-pink);
        border-radius: 50px;
        padding: 0.8rem 1.8rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
    }

    .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        background-color: white;
        color: var(--dark-pink);
    }

    .btn-icon-inner {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
    }

    .btn-text {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .btn-cancel {
        background-color: white;
        color: var(--primary-pink);
        border-radius: 50px;
        padding: 0.8rem 1.8rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(255,107,149,0.3);
        display: inline-flex;
        align-items: center;
        font-size: 1rem;
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        background-color: #FFF0F5;
        color: var(--dark-pink);
        border-color: rgba(255,107,149,0.5);
    }

    .btn-save-changes {
        background: linear-gradient(135deg, #ff6b95 0%, #ff4778 100%);
        color: white;
        border-radius: 50px;
        padding: 0.8rem 1.8rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(255,107,149,0.3);
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        margin-left: 1rem;
        font-size: 1rem;
    }

    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255,107,149,0.4);
        background: linear-gradient(135deg, #ff6b95 10%, #e84a78 100%);
    }

    /* Timeline styling with pink color */
    .timeline-spa {
        padding-left: 2rem;
        margin: 1rem 0 0 1.5rem;
        position: relative;
    }
    
    .timeline-spa::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 2px;
        background: var(--light-pink);
    }
    
    .timeline-item-spa {
        padding-bottom: 2rem;
        position: relative;
    }
    
    .timeline-marker-spa {
        position: absolute;
        left: -1.5rem;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--primary-pink);
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--light-pink);
    }
    
    .timeline-date-spa {
        font-size: 0.8rem;
        color: var(--spa-gray);
        margin-bottom: 0.5rem;
    }
    
    .timeline-content-spa {
        padding: 1.2rem;
        margin-bottom: 1.2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .invalid-feedback {
        color: var(--dark-pink);
        margin-top: 0.5rem;
        margin-left: 1rem;
    }

    .form-buttons {
        margin-top: 3rem;
        text-align: right;
    }

    body {
        color: var(--text-color);
    }

    /* Select and dropdown styling */
    select.form-control, 
    select.form-control-spa,
    .membership-select {
        background-color: white;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 0.85rem 1.5rem;
        height: calc(2.5rem + 2px);
        font-size: 1rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ff6b95'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 1rem center !important;
        background-size: 1.2rem !important;
        padding-right: 2.5rem !important; /* Ensure space for dropdown arrow */
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* Fix specifically for the MaTK select dropdown */
    #MaTK {
        width: 100%;
        padding-right: 2.5rem !important;
        overflow: visible !important;
        white-space: normal !important;
        text-overflow: unset !important;
        height: auto !important;
        min-height: calc(2.5rem + 2px);
        word-break: normal !important;
    }

    /* Ensure dropdown width is enough for content */
    select.form-control option, 
    select.form-control-spa option,
    .membership-select option {
        padding: 10px;
        font-size: 1rem;
        white-space: normal;
        width: auto;
    }

    /* Fix for select boxes to prevent text truncation */
    .form-control, 
    .form-control-spa, 
    select.membership-select,
    select.form-control {
        padding-right: 2.5rem !important;
        text-overflow: unset !important;
        overflow: visible !important;
        white-space: normal !important;
    }
    
    /* Fix dropdown arrow positioning */
    select.form-control, 
    select.form-control-spa,
    .membership-select {
        background-position: right 0.75rem center !important;
        padding-right: 2rem !important;
    }
    
    /* Fix for select dropdown options */
    select option {
        padding: 10px !important;
        white-space: normal !important;
    }
    
    /* Enhanced styling for membership select */
    .membership-select {
        width: 100% !important;
        overflow: visible !important;
        border-radius: 50px !important;
    }

    /* Adjust text clipping in all selects */
    .form-group select,
    .form-group .form-control,
    .form-group .form-control-spa,
    .form-group .membership-select {
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        width: 100% !important;
        display: block !important;
        padding-right: 2.5rem !important;
    }
    
    /* Fix specifically for the dropdown appearance when clicked */
    .form-group select:focus,
    .form-group select:active {
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: clip !important;
    }
    
    /* Fix dropdown option display */
    .form-group select option {
        white-space: normal !important;
        overflow: visible !important;
        padding: 8px 12px !important;
    }

    /* Reset any overflow or text clipping for dropdowns */
    #membership_level, #MaTK {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
        padding-left: 20px !important;
        padding-right: 32px !important;
        appearance: auto !important;
        -webkit-appearance: auto !important;
        -moz-appearance: auto !important;
    }
    
    /* Ensure text is not cut off */
    .form-control option, 
    #membership_level option, 
    #MaTK option {
        padding: 8px !important;
        white-space: normal !important;
    }
    
    /* Clean up conflicting styles */
    .form-group select {
        background-image: none !important;
    }

    /* Complete reset for select elements */
    #membership_level {
        box-sizing: border-box !important;
        width: 100% !important;
        min-width: 100% !important;
        padding: 10px 40px 10px 20px !important;
        font-size: 16px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 50px !important;
        background-color: white !important;
        text-align: left !important;
        /* Remove all text clipping properties */
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
        /* Override appearance */
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        /* Custom dropdown arrow */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23ff6b95'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E") !important;
        background-position: right 15px center !important;
        background-repeat: no-repeat !important;
        background-size: 16px !important;
    }
    
    /* Make sure dropdown options are normal */
    #membership_level option {
        font-size: 16px !important;
        padding: 10px !important;
        white-space: normal !important;
    }
    
    /* Fix dropdown containers */
    .position-relative {
        position: relative !important;
        width: 100% !important;
    }

    /* Custom Pink Dropdown Styling */
    .custom-pink-dropdown {
        position: relative;
        width: 100%;
        margin-bottom: 10px;
    }

    .custom-pink-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background-color: white;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .custom-pink-dropdown-toggle:hover {
        border-color: var(--primary-pink);
    }

    .custom-pink-dropdown-text {
        font-size: 16px;
        color: var(--text-color);
    }

    .custom-pink-dropdown-arrow {
        color: var(--primary-pink);
        font-size: 14px;
        transition: transform 0.2s ease;
    }

    .custom-pink-dropdown-toggle.active .custom-pink-dropdown-arrow {
        transform: rotate(180deg);
    }

    .custom-pink-dropdown-menu {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        width: 100%;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        overflow: visible !important;
        display: none;
        border: 1px solid rgba(255, 107, 149, 0.2);
    }

    .custom-pink-dropdown-menu.show {
        display: block;
    }

    .custom-pink-dropdown-item {
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-pink-dropdown-item:hover {
        background-color: var(--light-pink-hover);
    }
    
    .custom-pink-dropdown-item.selected {
        background-color: white;
        color: var(--text-color);
        font-weight: 400;
    }

    .row, .col-md-6, .form-group {
        overflow: visible !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="customer-dashboard-header">
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="glitter-dot"></div>
        <div class="header-shimmer"></div>
        
        <div class="header-content">
            <h1 class="header-title">Chỉnh Sửa Khách Hàng</h1>
            <p class="header-subtitle">
                    <i class="fas fa-user-edit mr-1"></i> Cập nhật và quản lý thông tin khách hàng của bạn
                </p>
            </div>
        
        <div class="header-actions">
            <a href="{{ route('admin.customers.show', $customer->Manguoidung) }}" class="btn btn-light" style="margin-right: 10px;">
                <span class="btn-icon-inner">
                    <i class="fas fa-eye"></i>
                </span>
                <span class="btn-text">XEM CHI TIẾT</span>
            </a>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
                <span class="btn-icon-inner">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="btn-text">QUAY LẠI</span>
            </a>
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
                                    <label for="Manguoidung">
                                        <i class="fas fa-fingerprint"></i> Mã Khách Hàng
                                    </label>
                                    <input type="text" class="form-control form-control-spa" id="Manguoidung" value="{{ $customer->Manguoidung }}" readonly>
                                    <small class="form-text text-muted">Mã khách hàng không thể thay đổi</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="MaTK">
                                        <i class="fas fa-user-lock"></i> Tài Khoản <span class="text-danger">*</span>
                                    </label>
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
                                    <label for="Hoten">
                                        <i class="fas fa-user"></i> Họ Tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-spa @error('Hoten') is-invalid @enderror" 
                                        id="Hoten" name="Hoten" value="{{ old('Hoten', $customer->Hoten) }}">
                                    @error('Hoten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="SDT">
                                        <i class="fas fa-phone-alt"></i> Số Điện Thoại <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group-spa">
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
                                    <label for="Email">
                                        <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group-spa">
                                        <input type="email" class="form-control @error('Email') is-invalid @enderror" 
                                            id="Email" name="Email" value="{{ old('Email', $customer->Email) }}">
                                    </div>
                                    @error('Email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="DiaChi">
                                        <i class="fas fa-map-marker-alt"></i> Địa Chỉ <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control form-control-spa @error('DiaChi') is-invalid @enderror" 
                                        id="DiaChi" name="DiaChi" rows="3" style="border-radius: 20px;">{{ old('DiaChi', $customer->DiaChi) }}</textarea>
                                    @error('DiaChi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="Ngaysinh">
                                        <i class="fas fa-birthday-cake"></i> Ngày Sinh <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group-spa">
                                        <input type="date" class="form-control @error('Ngaysinh') is-invalid @enderror" 
                                            id="Ngaysinh" name="Ngaysinh" value="{{ old('Ngaysinh', $customer->Ngaysinh) }}">
                                    </div>
                                    @error('Ngaysinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <i class="fas fa-venus-mars"></i> Giới Tính <span class="text-danger">*</span>
                                    </label>
                                    <div class="gender-selector">
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
                        
                        <div class="form-buttons">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-cancel">
                                    <i class="fas fa-times mr-1"></i> Hủy
                                </a>
                            <button type="submit" class="btn btn-save-changes" id="submitBtn">
                                    <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                                </button>
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
                            $hangName = $hangTV ? $hangTV->Tenhang : 'Thành viên Bạc';
                            $badgeClass = 'membership-regular';
                            
                            if($hangName == 'Thành viên Vàng') {
                                $badgeClass = 'membership-vip';
                            } elseif($hangName == 'Thành viên Bạch Kim') {
                                $badgeClass = 'membership-platinum';
                            } elseif($hangName == 'Thành viên Kim Cương') {
                                $badgeClass = 'membership-diamond';
                            }
                        @endphp
                        <div class="spa-badge {{ $badgeClass }}" style="margin-bottom: 1.5rem;">
                            @if($hangName != 'Thành viên Bạc')
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
<script>
    // Custom dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize membership level dropdown
        const membershipToggle = document.getElementById('membership_level_toggle');
        const membershipMenu = document.getElementById('membership_level_menu');
        const membershipItems = membershipMenu.querySelectorAll('.custom-pink-dropdown-item');
        const membershipInput = document.getElementById('membership_level_hidden');
        const membershipText = membershipToggle.querySelector('.custom-pink-dropdown-text');
        
        // Mark current selection as selected
        const currentValue = membershipInput.value;
        membershipItems.forEach(item => {
            if(item.dataset.value === currentValue) {
                item.classList.add('selected');
            }
        });
        
        // Toggle dropdown on click
        membershipToggle.addEventListener('click', function() {
            membershipToggle.classList.toggle('active');
            membershipMenu.classList.toggle('show');
        });
        
        // Handle item selection
        membershipItems.forEach(item => {
            item.addEventListener('click', function() {
                const value = item.dataset.value;
                
                // Update hidden input
                membershipInput.value = value;
                
                // Update displayed text
                membershipText.textContent = value;
                
                // Update selected item
                membershipItems.forEach(i => i.classList.remove('selected'));
                item.classList.add('selected');
                
                // Close dropdown
                membershipToggle.classList.remove('active');
                membershipMenu.classList.remove('show');
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!membershipToggle.contains(event.target) && !membershipMenu.contains(event.target)) {
                membershipToggle.classList.remove('active');
                membershipMenu.classList.remove('show');
            }
        });
    });
</script>
@endsection