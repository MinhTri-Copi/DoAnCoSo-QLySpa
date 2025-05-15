@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Hóa Đơn')

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

    .confirm-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .confirm-header {
        background-color: var(--danger-color);
        padding: 20px;
        color: white;
        text-align: center;
    }

    .confirm-icon {
        font-size: 48px;
        margin-bottom: 10px;
    }

    .confirm-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .confirm-subtitle {
        font-size: 14px;
        opacity: 0.9;
    }

    .confirm-body {
        padding: 30px;
    }

    .confirm-message {
        text-align: center;
        margin-bottom: 30px;
        color: #495057;
        font-size: 16px;
        line-height: 1.6;
    }

    .confirm-item {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .confirm-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .confirm-item-title {
        font-weight: 600;
        color: #343a40;
        font-size: 18px;
    }

    .confirm-item-badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-confirmed {
        background-color: #28a745;
        color: white;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: white;
    }

    .badge-completed {
        background-color: #17a2b8;
        color: white;
    }

    .confirm-item-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .confirm-item-detail {
        display: flex;
        align-items: center;
    }

    .confirm-item-detail i {
        color: var(--primary-color);
        margin-right: 8px;
        width: 16px;
    }

    .confirm-actions {
        display: flex;
        gap: 15px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 
    }