/**
 * JavaScript for roles index page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle role card click to show details
    const roleCards = document.querySelectorAll('.role-card');
    roleCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't navigate if clicking on a button or link inside the card
            if (e.target.closest('a, button')) {
                return;
            }
            
            const roleId = this.getAttribute('data-role-id');
            window.location.href = `/admin/roles/${roleId}`;
        });
    });

    // Handle bulk actions
    const bulkActionSelect = document.getElementById('bulkAction');
    const bulkActionBtn = document.getElementById('applyBulkAction');
    
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener('click', function() {
            const selectedAction = bulkActionSelect.value;
            if (!selectedAction) return;
            
            const selectedRoles = document.querySelectorAll('input[name="selectedRoles[]"]:checked');
            if (selectedRoles.length === 0) {
                Swal.fire({
                    title: 'Lỗi',
                    text: 'Vui lòng chọn ít nhất một vai trò',
                    icon: 'error',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }
            
            const roleIds = Array.from(selectedRoles).map(checkbox => checkbox.value);
            
            if (selectedAction === 'delete') {
                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: `Bạn có chắc chắn muốn xóa ${roleIds.length} vai trò đã chọn?`,
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
            const checkboxes = document.querySelectorAll('input[name="selectedRoles[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});