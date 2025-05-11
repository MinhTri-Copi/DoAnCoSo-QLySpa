/**
 * Common JavaScript functions for payment method management
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for all select elements with the select2 class
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    }

    // Initialize tooltips
    if (typeof $().tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Initialize popovers
    if (typeof $().popover !== 'undefined') {
        $('[data-toggle="popover"]').popover();
    }

    // Handle success and error messages with SweetAlert2
    const successMessage = document.querySelector('meta[name="success-message"]');
    const errorMessage = document.querySelector('meta[name="error-message"]');

    if (successMessage && successMessage.content) {
        Swal.fire({
            title: 'Thành công!',
            text: successMessage.content,
            icon: 'success',
            confirmButtonColor: '#4e73df'
        });
    }

    if (errorMessage && errorMessage.content) {
        Swal.fire({
            title: 'Lỗi!',
            text: errorMessage.content,
            icon: 'error',
            confirmButtonColor: '#4e73df'
        });
    }

    // Confirm delete with SweetAlert2
    const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Search functionality for tables
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                let found = false;
                const cells = row.querySelectorAll('td');
                
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        found = true;
                    }
                });
                
                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Get payment method icon based on name
    window.getPaymentMethodIcon = function(name) {
        name = name.toLowerCase();
        
        if (name.includes('tiền mặt') || name.includes('cash')) {
            return 'fa-money-bill-wave';
        } else if (name.includes('thẻ') || name.includes('card')) {
            return 'fa-credit-card';
        } else if (name.includes('chuyển khoản') || name.includes('transfer')) {
            return 'fa-exchange-alt';
        } else if (name.includes('ví điện tử') || name.includes('momo') || name.includes('zalopay') || name.includes('ewallet')) {
            return 'fa-wallet';
        } else {
            return 'fa-money-check-alt';
        }
    };

    // Get payment method class based on name
    window.getPaymentMethodClass = function(name) {
        name = name.toLowerCase();
        
        if (name.includes('tiền mặt') || name.includes('cash')) {
            return 'payment-method-cash';
        } else if (name.includes('thẻ') || name.includes('card')) {
            return 'payment-method-card';
        } else if (name.includes('chuyển khoản') || name.includes('transfer')) {
            return 'payment-method-transfer';
        } else if (name.includes('ví điện tử') || name.includes('momo') || name.includes('zalopay') || name.includes('ewallet')) {
            return 'payment-method-ewallet';
        } else {
            return 'payment-method-other';
        }
    };

    // Apply payment method icons and classes
    const paymentMethodItems = document.querySelectorAll('.payment-method-item');
    paymentMethodItems.forEach(item => {
        const name = item.getAttribute('data-name');
        const iconElement = item.querySelector('.payment-method-icon i');
        
        if (iconElement) {
            const iconClass = getPaymentMethodIcon(name);
            iconElement.classList.add(iconClass);
            
            const colorClass = getPaymentMethodClass(name);
            iconElement.classList.add(colorClass);
        }
    });
});