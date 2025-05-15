/**
 * Quản lý khách hàng - JavaScript
 */

// Khởi tạo khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo tooltips
    initTooltips();
    
    // Khởi tạo select2 nếu có
    initSelect2();
    
    // Thêm hiệu ứng cho các hàng khách hàng
    initCustomerRows();
    
    // Thêm hiệu ứng cho các form
    initFormAnimations();
    
    // Khởi tạo các tab nếu có
    initTabs();
    
    // Khởi tạo xác nhận xóa nếu có
    initDeleteConfirmation();
    
    // Khởi tạo form validation
    initFormValidation();
    
    // Khởi tạo các nút xuất dữ liệu
    initExportButtons();
});

/**
 * Khởi tạo tooltips
 */
function initTooltips() {
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
}

/**
 * Khởi tạo select2
 */
function initSelect2() {
    if (typeof $().select2 === 'function') {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Chọn --",
        });
    }
}

/**
 * Khởi tạo hiệu ứng cho các hàng khách hàng
 */
function initCustomerRows() {
    const customerRows = document.querySelectorAll('.customer-row');
    
    customerRows.forEach(row => {
        // Thêm hiệu ứng hover
        row.addEventListener('mouseenter', function() {
            this.classList.add('bg-light', 'transition-all', 'duration-300');
        });
        
        row.addEventListener('mouseleave', function() {
            this.classList.remove('bg-light');
        });
        
        // Thêm sự kiện click để chuyển đến trang chi tiết
        row.addEventListener('click', function() {
            const customerId = this.getAttribute('data-id');
            if (customerId) {
                window.location.href = `/admin/customers/${customerId}`;
            }
        });
    });
    
    // Thêm hiệu ứng khi trang tải
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('animate__animated', 'animate__fadeIn');
    });
}

/**
 * Khởi tạo hiệu ứng cho các form
 */
function initFormAnimations() {
    const formGroups = document.querySelectorAll('.form-group');
    
    formGroups.forEach((group, index) => {
        group.classList.add('animate__animated', 'animate__fadeInUp');
        group.style.animationDelay = (index * 0.1) + 's';
    });
}

/**
 * Khởi tạo các tab
 */
function initTabs() {
    // Đã được xử lý bởi Bootstrap, chỉ thêm hiệu ứng nếu cần
    document.querySelectorAll('.tab-pane').forEach(tab => {
        tab.addEventListener('show.bs.tab', function() {
            this.classList.add('animate__animated', 'animate__fadeIn');
        });
    });
}

/**
 * Khởi tạo xác nhận xóa
 */
function initDeleteConfirmation() {
    const confirmDeleteInput = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');
    
    if (confirmDeleteInput && deleteBtn) {
        confirmDeleteInput.addEventListener('input', function() {
            deleteBtn.disabled = this.value !== 'XÓA';
        });
        
        // Thêm hiệu ứng lắc cho biểu tượng cảnh báo
        const warningIcon = document.querySelector('.fa-exclamation-triangle');
        if (warningIcon) {
            setInterval(function() {
                warningIcon.classList.add('animate__animated', 'animate__headShake');
                
                setTimeout(function() {
                    warningIcon.classList.remove('animate__animated', 'animate__headShake');
                }, 1000);
            }, 3000);
        }
    }
}

/**
 * Khởi tạo form validation
 */
function initFormValidation() {
    const customerForm = document.getElementById('createCustomerForm') || document.getElementById('editCustomerForm');
    
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset validation
            customerForm.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            
            // Validate MaTK
            const maTkField = document.getElementById('MaTK');
            if (maTkField && !maTkField.value) {
                maTkField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate Hoten
            const hotenField = document.getElementById('Hoten');
            if (hotenField && !hotenField.value) {
                hotenField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate SDT
            const sdtField = document.getElementById('SDT');
            if (sdtField && !sdtField.value) {
                sdtField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate Email
            const emailField = document.getElementById('Email');
            if (emailField && (!emailField.value || !validateEmail(emailField.value))) {
                emailField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate DiaChi
            const diaChiField = document.getElementById('DiaChi');
            if (diaChiField && !diaChiField.value) {
                diaChiField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate Ngaysinh
            const ngaysinhField = document.getElementById('Ngaysinh');
            if (ngaysinhField && !ngaysinhField.value) {
                ngaysinhField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate Gioitinh
            const gioitinhChecked = document.querySelector('input[name="Gioitinh"]:checked');
            if (!gioitinhChecked) {
                document.querySelectorAll('input[name="Gioitinh"]').forEach(el => {
                    el.classList.add('is-invalid');
                });
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Hiển thị thông báo lỗi
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Vui lòng điền đầy đủ thông tin bắt buộc.',
                        confirmButtonColor: '#4e73df'
                    });
                } else {
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                }
                
                // Cuộn đến trường lỗi đầu tiên
                const firstInvalid = customerForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Hiển thị nút loading
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
                    submitBtn.disabled = true;
                }
            }
        });
    }
}

/**
 * Khởi tạo các nút xuất dữ liệu
 */
function initExportButtons() {
    // Xuất CSV
    const exportCsvBtn = document.getElementById('exportCSV');
    if (exportCsvBtn) {
        exportCsvBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Đang xử lý',
                    text: 'Xuất file CSV đang được xử lý...',
                    confirmButtonColor: '#4e73df'
                });
            } else {
                alert('Xuất file CSV đang được xử lý...');
            }
            
            // Thực hiện xuất CSV thực tế ở đây
        });
    }
    
    // In danh sách
    const printListBtn = document.getElementById('printList');
    if (printListBtn) {
        printListBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
    
    // Làm mới danh sách
    const refreshListBtn = document.getElementById('refreshList');
    if (refreshListBtn) {
        refreshListBtn.addEventListener('click', function(e) {
            e.preventDefault();
            location.reload();
        });
    }
}

/**
 * Hàm kiểm tra email hợp lệ
 */
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Xử lý phiếu hỗ trợ
 */
function initSupportTicket() {
    const createTicketBtns = document.querySelectorAll('#createSupportTicket, #createSupportTicketEmpty');
    const submitTicketBtn = document.getElementById('submitTicket');
    
    createTicketBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            $('#supportTicketModal').modal('show');
        });
    });
    
    if (submitTicketBtn) {
        submitTicketBtn.addEventListener('click', function() {
            const titleField = document.getElementById('ticketTitle');
            const contentField = document.getElementById('ticketContent');
            
            if (!titleField.value || !contentField.value) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Vui lòng điền đầy đủ thông tin bắt buộc.',
                        confirmButtonColor: '#4e73df'
                    });
                } else {
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                }
                return;
            }
            
            $('#supportTicketModal').modal('hide');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Phiếu hỗ trợ đã được tạo thành công.',
                    confirmButtonColor: '#4e73df'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                alert('Phiếu hỗ trợ đã được tạo thành công.');
                location.reload();
            }
        });
    }
}

/**
 * Xử lý xem chi tiết đơn hàng, lịch hẹn, phiếu hỗ trợ
 */
function initViewDetails() {
    // Xem chi tiết đơn hàng
    document.querySelectorAll('.view-order').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.getAttribute('data-id');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Chi Tiết Đơn Hàng',
                    text: 'Đang tải thông tin đơn hàng #' + orderId,
                    confirmButtonColor: '#4e73df'
                });
            } else {
                alert('Đang tải thông tin đơn hàng #' + orderId);
            }
        });
    });
    
    // Xem chi tiết lịch hẹn
    document.querySelectorAll('.view-appointment').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.getAttribute('data-id');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Chi Tiết Lịch Hẹn',
                    text: 'Đang tải thông tin lịch hẹn #' + appointmentId,
                    confirmButtonColor: '#4e73df'
                });
            } else {
                alert('Đang tải thông tin lịch hẹn #' + appointmentId);
            }
        });
    });
    
    // Xem chi tiết phiếu hỗ trợ
    document.querySelectorAll('.view-ticket').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const ticketId = this.getAttribute('data-id');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Chi Tiết Phiếu Hỗ Trợ',
                    text: 'Đang tải thông tin phiếu hỗ trợ #' + ticketId,
                    confirmButtonColor: '#4e73df'
                });
            } else {
                alert('Đang tải thông tin phiếu hỗ trợ #' + ticketId);
            }
        });
    });
}

// Gọi hàm khởi tạo phiếu hỗ trợ và xem chi tiết
document.addEventListener('DOMContentLoaded', function() {
    initSupportTicket();
    initViewDetails();
});