/**
 * Common JavaScript functions for role management
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
});