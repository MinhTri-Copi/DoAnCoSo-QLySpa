/**
 * JavaScript for role destroy confirmation page
 */
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteInput = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');
    const deleteRoleForm = document.getElementById('deleteRoleForm');
    
    if (confirmDeleteInput && deleteBtn) {
        confirmDeleteInput.addEventListener('input', function() {
            if (this.value === 'XÓA') {
                deleteBtn.removeAttribute('disabled');
            } else {
                deleteBtn.setAttribute('disabled', 'disabled');
            }
        });
    }
    
    if (deleteRoleForm) {
        deleteRoleForm.addEventListener('submit', function(e) {
            const confirmValue = confirmDeleteInput.value;
            
            if (confirmValue !== 'XÓA') {
                e.preventDefault();
                Swal.fire({
                    title: 'Lỗi',
                    text: 'Vui lòng nhập "XÓA" để xác nhận',
                    icon: 'error',
                    confirmButtonColor: '#4e73df'
                });
            }
        });
    }
});