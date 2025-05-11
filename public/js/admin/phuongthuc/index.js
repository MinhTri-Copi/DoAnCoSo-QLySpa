/**
 * JavaScript for payment methods index page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment method card click to show details
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't navigate if clicking on a button or link inside the card
            if (e.target.closest('a, button')) {
                return;
            }
            
            const paymentMethodId = this.getAttribute('data-payment-method-id');
            window.location.href = `/admin/phuongthuc/${paymentMethodId}`;
        });
    });

    // Handle bulk actions
    const bulkActionSelect = document.getElementById('bulkAction');
    const bulkActionBtn = document.getElementById('applyBulkAction');
    
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener('click', function() {
            const selectedAction = bulkActionSelect.value;
            if (!selectedAction) return;
            
            const selectedPaymentMethods = document.querySelectorAll('input[name="selectedPaymentMethods[]"]:checked');
            if (selectedPaymentMethods.length === 0) {
                Swal.fire({
                    title: 'Lỗi',
                    text: 'Vui lòng chọn ít nhất một phương thức thanh toán',
                    icon: 'error',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }
            
            const paymentMethodIds = Array.from(selectedPaymentMethods).map(checkbox => checkbox.value);
            
            if (selectedAction === 'delete') {
                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: `Bạn có chắc chắn muốn xóa ${paymentMethodIds.length} phương thức thanh toán đã chọn?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form for bulk delete
                        document.getElementById('bulkActionForm').submit();
                    }
                });
            } else {
                // For other actions, just submit the form
                document.getElementById('bulkActionForm').submit();
            }
        });
    }

    // Toggle all checkboxes
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selectedPaymentMethods[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#paymentMethodsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tất cả"]]
        });
    }
});