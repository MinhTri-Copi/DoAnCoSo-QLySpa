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
            } else {
                // Add loading state to the submit button when form is valid
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang lưu...';
                    submitBtn.disabled = true;
                }
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

    // Preview payment method icon with enhanced animation
    const paymentMethodNameInput = document.getElementById('TenPT');
    const iconPreview = document.getElementById('iconPreview');
    const iconItems = document.querySelectorAll('.payment-method-item i');
    
    if (paymentMethodNameInput && iconPreview) {
        // Set initial icon
        const name = paymentMethodNameInput.value;
        updateIcon(name);
        
        // Update icon on input change with debounce for better performance
        let debounceTimer;
        paymentMethodNameInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            
            // Add transitioning class for smooth animation
            iconPreview.classList.add('transitioning');
            
            debounceTimer = setTimeout(() => {
                const name = this.value;
                updateIcon(name);
                
                // Remove transitioning class after animation completes
                setTimeout(() => {
                    iconPreview.classList.remove('transitioning');
                }, 300);
            }, 300);
        });
    }
    
    // Update all payment method icons in the page
    document.querySelectorAll('.payment-method-item').forEach(item => {
        const name = item.getAttribute('data-name');
        const iconElement = item.querySelector('.payment-method-icon i');
        
        if (iconElement && name) {
            const iconClass = window.getPaymentMethodIcon(name);
            
            // Remove existing font awesome classes
            iconElement.className = '';
            
            // Add new classes with pink theme
            iconElement.classList.add('fas', iconClass, 'fa-3x');
        }
    });
    
    /**
     * Update the icon preview with animation
     */
    function updateIcon(name) {
        const iconClass = window.getPaymentMethodIcon(name);
        
        // Remove all existing icon classes but keep the transitioning class if it's there
        const isTransitioning = iconPreview.classList.contains('transitioning');
        iconPreview.className = isTransitioning ? 'transitioning' : '';
        
        // Apply new icon classes
        iconPreview.classList.add('fas', iconClass, 'fa-3x');
        
        // Update other icon instances
        iconItems.forEach(icon => {
            icon.className = '';
            icon.classList.add('fas', iconClass, 'fa-3x');
        });
    }
    
    // Add animation to form fields on focus
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.form-group').classList.add('highlight-field');
        });
        
        input.addEventListener('blur', function() {
            this.closest('.form-group').classList.remove('highlight-field');
        });
    });
});