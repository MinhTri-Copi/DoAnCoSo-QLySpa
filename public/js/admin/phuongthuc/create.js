/**
 * JavaScript for payment method creation page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const createPaymentMethodForm = document.getElementById('createPaymentMethodForm');
    if (createPaymentMethodForm) {
        createPaymentMethodForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate Payment Method Name
            const paymentMethodName = document.getElementById('TenPT').value.trim();
            if (!paymentMethodName) {
                document.getElementById('TenPTFeedback').textContent = 'Vui lòng nhập tên phương thức thanh toán';
                document.getElementById('TenPT').classList.add('is-invalid');
                isValid = false;
            } else if (paymentMethodName.length > 255) {
                document.getElementById('TenPTFeedback').textContent = 'Tên phương thức thanh toán không được vượt quá 255 ký tự';
                document.getElementById('TenPT').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('TenPT').classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Real-time validation
    const paymentMethodNameField = document.getElementById('TenPT');
    if (paymentMethodNameField) {
        paymentMethodNameField.addEventListener('input', function() {
            const value = this.value.trim();
            if (!value) {
                this.classList.add('is-invalid');
                document.getElementById('TenPTFeedback').textContent = 'Vui lòng nhập tên phương thức thanh toán';
            } else if (value.length > 255) {
                this.classList.add('is-invalid');
                document.getElementById('TenPTFeedback').textContent = 'Tên phương thức thanh toán không được vượt quá 255 ký tự';
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }

    // Payment method template selection
    const paymentMethodTemplates = document.querySelectorAll('input[name="paymentMethodTemplate"]');
    if (paymentMethodTemplates.length > 0) {
        paymentMethodTemplates.forEach(template => {
            template.addEventListener('change', function() {
                if (this.checked) {
                    const value = this.value;
                    const nameField = document.getElementById('TenPT');
                    const descField = document.getElementById('Mota');
                    
                    switch (value) {
                        case 'cash':
                            nameField.value = 'Tiền mặt';
                            descField.value = 'Thanh toán bằng tiền mặt trực tiếp tại quầy.';
                            break;
                        case 'card':
                            nameField.value = 'Thẻ tín dụng/ghi nợ';
                            descField.value = 'Thanh toán bằng thẻ tín dụng hoặc thẻ ghi nợ.';
                            break;
                        case 'transfer':
                            nameField.value = 'Chuyển khoản ngân hàng';
                            descField.value = 'Thanh toán bằng chuyển khoản qua tài khoản ngân hàng.';
                            break;
                        case 'momo':
                            nameField.value = 'Ví MoMo';
                            descField.value = 'Thanh toán qua ví điện tử MoMo.';
                            break;
                        case 'zalopay':
                            nameField.value = 'ZaloPay';
                            descField.value = 'Thanh toán qua ví điện tử ZaloPay.';
                            break;
                    }
                }
            });
        });
    }

    // Preview payment method icon
    const paymentMethodNameInput = document.getElementById('TenPT');
    const iconPreview = document.getElementById('iconPreview');
    
    if (paymentMethodNameInput && iconPreview) {
        paymentMethodNameInput.addEventListener('input', function() {
            const name = this.value;
            const iconClass = window.getPaymentMethodIcon(name);
            const colorClass = window.getPaymentMethodClass(name);
            
            // Remove all existing icon classes
            iconPreview.className = '';
            
            // Add new classes
            iconPreview.classList.add('fas', iconClass, colorClass, 'fa-3x', 'mb-3');
        });
    }
});