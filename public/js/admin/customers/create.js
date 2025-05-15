/**
 * JavaScript cho trang tạo mới khách hàng
 */

document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo form validation
    initCreateFormValidation();
    
    // Khởi tạo tự động sinh mã
    initAutoGenerateId();
});

/**
 * Khởi tạo form validation
 */
function initCreateFormValidation() {
    const createForm = document.getElementById('createCustomerForm');
    
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset validation
            createForm.querySelectorAll('.is-invalid').forEach(el => {
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
            } else if (sdtField && !validatePhoneNumber(sdtField.value)) {
                sdtField.classList.add('is-invalid');
                document.getElementById('SDTFeedback').textContent = 'Số điện thoại không hợp lệ.';
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
            } else if (ngaysinhField && !validateAge(ngaysinhField.value)) {
                ngaysinhField.classList.add('is-invalid');
                document.getElementById('NgaysinhFeedback').textContent = 'Khách hàng phải đủ 18 tuổi.';
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
                const firstInvalid = createForm.querySelector('.is-invalid');
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
 * Khởi tạo tự động sinh mã
 */
function initAutoGenerateId() {
    const generateIdBtn = document.getElementById('generateId');
    
    if (generateIdBtn) {
        generateIdBtn.addEventListener('click', function() {
            // Tạo mã ngẫu nhiên
            const randomId = Math.floor(Math.random() * 9000) + 1000;
            document.getElementById('Manguoidung').value = randomId;
        });
    }
}

/**
 * Kiểm tra số điện thoại hợp lệ
 */
function validatePhoneNumber(phone) {
    const phoneRegex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;
    return phoneRegex.test(phone);
}

/**
 * Kiểm tra tuổi hợp lệ (trên 18 tuổi)
 */
function validateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    return age >= 18;
}

/**
 * Kiểm tra email hợp lệ
 */
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}