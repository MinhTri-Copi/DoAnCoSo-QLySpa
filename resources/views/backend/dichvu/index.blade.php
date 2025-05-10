@extends('backend.layouts.app')

@section('title', 'Quản Lý Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản Lý Dịch Vụ</h2>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('admin.dichvu.create') }}" class="btn btn-primary mb-3">Thêm Dịch Vụ</a>

    <div class="card-container">
        @foreach ($dichVus as $dichVu)
            <div class="service-card" data-id="{{ $dichVu->MaDV }}">
                <div class="service-card-header">
                    <h3 class="service-card-title">
                        {{ $dichVu->Tendichvu }}
                        <span class="service-id">#{{ $dichVu->MaDV }}</span>
                    </h3>
                    
                    @php
                        $statusClass = 'status-pending';
                        $statusName = $dichVu->trangThai->Tentrangthai ?? 'Chờ xử lý';
                        
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
                    
                    <span class="service-status {{ $statusClass }}">
                        {{ $statusName }}
                    </span>
                </div>
                
                <div class="service-card-body">
                    <p><strong>Giá:</strong> {{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ</p>
                    <p><strong>Mô Tả:</strong> {{ $dichVu->MoTa ?? 'N/A' }}</p>
                    <p><strong>Thời Gian:</strong> {{ $dichVu->Thoigian ? $dichVu->Thoigian->format('H:i') : 'N/A' }}</p>
                </div>
                
                <div class="service-card-footer">
                    <!-- Status update buttons -->
                    <form action="{{ route('admin.dichvu.update-status', $dichVu->MaDV) }}" method="POST" class="status-update-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="MaDV" value="{{ $dichVu->MaDV }}">
                        
                        @php
                            // Find status IDs for processing and completion
                            $processingStatus = null;
                            $completedStatus = null;
                            
                            foreach ($trangThais as $trangThai) {
                                if (strtolower($trangThai->Tentrangthai) == 'đang xử lý') {
                                    $processingStatus = $trangThai->Matrangthai;
                                }
                                elseif (strtolower($trangThai->Tentrangthai) == 'hoàn thành') {
                                    $completedStatus = $trangThai->Matrangthai;
                                }
                            }

                            $currentStatus = strtolower($statusName);
                            $processingDisabled = ($currentStatus == 'đang xử lý');
                            $completedDisabled = ($currentStatus == 'hoàn thành');
                        @endphp
                        
                        <!-- Process button with tooltip -->
                        <div class="action-tooltip">
                            <button type="submit" name="Matrangthai" value="{{ $processingStatus }}" 
                                class="service-action-btn process" 
                                title="Chuyển sang Đang xử lý"
                                {{ $processingDisabled ? 'disabled' : '' }}>
                                <i class="fas fa-hourglass-half"></i>
                            </button>
                            <span class="tooltip-text">Đánh dấu là "Đang xử lý"</span>
                        </div>
                        
                        <!-- Complete button with tooltip -->
                        <div class="action-tooltip">
                            <button type="submit" name="Matrangthai" value="{{ $completedStatus }}" 
                                class="service-action-btn complete" 
                                title="Chuyển sang Hoàn thành"
                                {{ $completedDisabled ? 'disabled' : '' }}>
                                <i class="fas fa-check-circle"></i>
                            </button>
                            <span class="tooltip-text">Đánh dấu là "Hoàn thành"</span>
                        </div>
                    </form>
                    
                    <!-- Standard action buttons -->
                    <div class="standard-actions">
                        <a href="{{ route('admin.dichvu.show', $dichVu->MaDV) }}" class="service-action-btn view" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
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
</div>

<style>
    /* Card container */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    /* Service card styling */
    .service-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        background-color: #fff;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .service-card-header {
        padding: 15px;
        background: linear-gradient(135deg, #FF9A9E 0%, #F6416C 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .service-card-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .service-id {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-left: 5px;
    }
    
    .service-status {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 12px;
        font-weight: 500;
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
        padding: 15px;
    }
    
    .service-card-body p {
        margin: 8px 0;
        font-size: 0.9rem;
    }
    
    .service-card-footer {
        padding: 15px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Action buttons */
    .service-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
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
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
    
    /* Remove the form wrapper and style the buttons directly */
    .status-update-form {
        display: contents; /* This makes the form's children appear as if they're direct children of the parent container */
    }
    
    /* Add CSS for disabled buttons */
    .service-action-btn:disabled {
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
    
    /* For better mobile responsiveness */
    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        const card = this.closest('.service-card');
                        if (card) {
                            const statusBadge = card.querySelector('.service-status');
                            if (statusBadge) {
                                // Update the status badge visually for immediate feedback
                                if (buttonValue === "{{ $processingStatus }}") {
                                    statusBadge.textContent = "Đang xử lý";
                                    statusBadge.className = "service-status status-processing";
                                } else if (buttonValue === "{{ $completedStatus }}") {
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
    });
</script>
@endsection