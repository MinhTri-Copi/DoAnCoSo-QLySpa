/**
 * JavaScript for payment method edit page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const editPaymentMethodForm = document.getElementById('editPaymentMethodForm');
    if (editPaymentMethodForm) {
        editPaymentMethodForm.addEventListener('submit', function(e) {
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

    // Preview payment method icon
    const paymentMethodNameInput = document.getElementById('TenPT');
    const iconPreview = document.getElementById('iconPreview');
    
    if (paymentMethodNameInput && iconPreview) {
        // Set initial icon
        const name = paymentMethodNameInput.value;
        const iconClass = window.getPaymentMethodIcon(name);
        const colorClass = window.getPaymentMethodClass(name);
        
        // Remove all existing icon classes
        iconPreview.className = '';
        
        // Add new classes
        iconPreview.classList.add('fas', iconClass, colorClass, 'fa-3x', 'mb-3');
        
        // Update icon on input change
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