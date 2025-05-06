/**
 * Spa-themed Customer Edit JavaScript
 * Enhances the customer edit page with animations and interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initAnimations();
    initFormEnhancements();
    initMembershipVisualizer();
    initValidation();
});

/**
 * Initialize animations for page elements
 */
function initAnimations() {
    // Animate form sections on page load
    const sections = document.querySelectorAll('.spa-card');
    sections.forEach((section, index) => {
        setTimeout(() => {
            section.classList.add('slide-up');
        }, index * 100);
    });
    
    // Enhance form input animations
    const formControls = document.querySelectorAll('.form-control, select, textarea');
    formControls.forEach(input => {
        // Add animation class
        input.classList.add('input-transition');
        
        // Add focus and blur effects
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('input-focused');
            
            // Find label and animate it
            const label = document.querySelector(`label[for="${this.id}"]`);
            if (label) {
                label.classList.add('label-focused');
            }
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('input-focused');
            
            // Find label and reset it if no value
            const label = document.querySelector(`label[for="${this.id}"]`);
            if (label && !this.value) {
                label.classList.remove('label-focused');
            }
        });
        
        // Add floating effect to the select element
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', function() {
                if (this.value) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
            
            // Trigger change event to set initial state
            input.dispatchEvent(new Event('change'));
        }
    });
}

/**
 * Initialize form enhancements
 */
function initFormEnhancements() {
    // Format phone number as user types
    const phoneInput = document.getElementById('SDT');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                // Format like: 0xxx xxx xxx
                if (value.length <= 4) {
                    value = value.replace(/^(\d{1,4})/, '$1');
                } else if (value.length <= 7) {
                    value = value.replace(/^(\d{4})(\d{1,3})/, '$1 $2');
                } else {
                    value = value.replace(/^(\d{4})(\d{3})(\d{1,3})/, '$1 $2 $3');
                }
            }
            e.target.value = value;
        });
    }
    
    // Add calendar icon functionality
    const birthDateInput = document.getElementById('Ngaysinh');
    const calendarIcon = document.querySelector('.input-group-spa i.fa-calendar-alt');
    if (birthDateInput && calendarIcon) {
        calendarIcon.addEventListener('click', function() {
            birthDateInput.showPicker();
        });
    }
    
    // Enhance radio buttons
    const radioInputs = document.querySelectorAll('.radio-spa input');
    radioInputs.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove active class from all radios
            document.querySelectorAll('.radio-spa').forEach(el => {
                el.classList.remove('active');
            });
            
            // Add active class to selected radio
            if (this.checked) {
                this.closest('.radio-spa').classList.add('active');
            }
        });
        
        // Set initial state
        if (radio.checked) {
            radio.closest('.radio-spa').classList.add('active');
        }
    });
    
    // Add smooth transitions when switching between form sections
    const formSections = document.querySelectorAll('.form-group');
    formSections.forEach((section, index) => {
        section.style.transition = 'all 0.3s ease';
        section.style.transitionDelay = `${index * 0.05}s`;
    });
}

/**
 * Initialize membership level visualizer
 */
function initMembershipVisualizer() {
    const membershipSelect = document.getElementById('membership_level');
    const membershipBadge = document.querySelector('.membership-display .spa-badge');
    
    if (membershipSelect && membershipBadge) {
        membershipSelect.addEventListener('change', function() {
            // Update badge class
            membershipBadge.className = 'spa-badge';
            
            // Get current selection
            const selectedLevel = this.value;
            
            // Apply appropriate class
            if (selectedLevel === 'VIP') {
                membershipBadge.classList.add('membership-vip');
            } else if (selectedLevel === 'Platinum') {
                membershipBadge.classList.add('membership-platinum');
            } else if (selectedLevel === 'Diamond') {
                membershipBadge.classList.add('membership-diamond');
            } else {
                membershipBadge.classList.add('membership-regular');
            }
            
            // Update content
            const hasIcon = selectedLevel !== 'Thường';
            membershipBadge.innerHTML = hasIcon 
                ? '<i class="fas fa-crown mr-1"></i>' + selectedLevel
                : selectedLevel;
                
            // Add animation
            membershipBadge.classList.add('badge-updated');
            setTimeout(() => {
                membershipBadge.classList.remove('badge-updated');
            }, 500);
            
            // Show benefits tooltip based on membership level
            showMembershipBenefits(selectedLevel);
        });
        
        // Create benefits modal/tooltip
        createMembershipBenefitsModal();
    }
}

/**
 * Show tooltip with membership benefits
 */
function showMembershipBenefits(level) {
    const benefits = {
        'Thường': [
            'Tích lũy điểm thưởng cơ bản (1 điểm/10,000đ)',
            'Đặt lịch online',
            'Ưu đãi sinh nhật'
        ],
        'VIP': [
            'Tích lũy 1.5x điểm thưởng',
            'Giảm 5% dịch vụ spa',
            'Quà tặng VIP sinh nhật',
            'Đặt lịch ưu tiên'
        ],
        'Platinum': [
            'Tích lũy 2x điểm thưởng',
            'Giảm 10% dịch vụ spa',
            'Đặt lịch ưu tiên cao cấp',
            'Quà tặng Platinum sinh nhật',
            'Tư vấn spa miễn phí'
        ],
        'Diamond': [
            'Tích lũy 3x điểm thưởng',
            'Giảm 15% dịch vụ spa',
            'Phục vụ VIP, ưu tiên tuyệt đối',
            'Quà tặng Diamond sinh nhật',
            'Tư vấn spa cao cấp miễn phí',
            'Chăm sóc da chuyên sâu miễn phí 1 lần/tháng'
        ]
    };
    
    // Get benefits for this level
    const levelBenefits = benefits[level] || [];
    
    // Show tooltip or modal with benefits
    const benefitsHTML = `
        <div class="benefits-heading">
            <i class="${level !== 'Thường' ? 'fas' : 'far'} fa-gem mr-2"></i>
            Quyền lợi hạng ${level}
        </div>
        <ul class="benefits-list">
            ${levelBenefits.map(benefit => `<li>${benefit}</li>`).join('')}
        </ul>
    `;
    
    // Update modal/tooltip content
    const benefitsContent = document.querySelector('.membership-benefits-content');
    if (benefitsContent) {
        benefitsContent.innerHTML = benefitsHTML;
        
        // Show the modal/tooltip
        const benefitsModal = document.getElementById('membershipBenefitsModal');
        if (benefitsModal) {
            $(benefitsModal).modal('show');
        }
    }
}

/**
 * Create membership benefits modal
 */
function createMembershipBenefitsModal() {
    // Create modal if it doesn't exist
    if (!document.getElementById('membershipBenefitsModal')) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'membershipBenefitsModal';
        modal.tabIndex = '-1';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-labelledby', 'membershipBenefitsModalLabel');
        modal.setAttribute('aria-hidden', 'true');
        
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(120deg, var(--spa-primary), var(--spa-primary-dark)); color: white;">
                        <h5 class="modal-title" id="membershipBenefitsModalLabel">Quyền Lợi Thành Viên</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body membership-benefits-content">
                        <!-- Content will be inserted here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-spa btn-spa-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add modal styles
        const modalStyle = document.createElement('style');
        modalStyle.textContent = `
            .benefits-heading {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--spa-dark);
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid rgba(0,0,0,0.1);
            }
            .benefits-list {
                padding-left: 1.5rem;
            }
            .benefits-list li {
                margin-bottom: 0.5rem;
                position: relative;
            }
            .benefits-list li:before {
                content: "✓";
                color: var(--spa-primary);
                position: absolute;
                left: -1.2rem;
            }
            .badge-updated {
                animation: pulse 0.5s ease;
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(modalStyle);
    }
}

/**
 * Initialize form validation
 */
function initValidation() {
    const form = document.getElementById('editCustomerForm');
    if (!form) return;
    
    // Add custom validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Clear previous errors
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Required fields
        const requiredFields = ['Hoten', 'SDT', 'Email', 'DiaChi', 'Ngaysinh', 'MaTK'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input) return;
            
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
                
                // If there's no error message yet, add one
                let feedback = input.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Trường này không được để trống';
                    input.parentNode.appendChild(feedback);
                }
            }
        });
        
        // Email validation
        const emailInput = document.getElementById('Email');
        if (emailInput && emailInput.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value.trim())) {
                emailInput.classList.add('is-invalid');
                isValid = false;
                
                let feedback = emailInput.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Email không hợp lệ';
                    emailInput.parentNode.appendChild(feedback);
                } else {
                    feedback.textContent = 'Email không hợp lệ';
                }
            }
        }
        
        // Phone validation
        const phoneInput = document.getElementById('SDT');
        if (phoneInput && phoneInput.value.trim()) {
            const phoneRegex = /^[0-9\s]+$/;
            if (!phoneRegex.test(phoneInput.value.trim())) {
                phoneInput.classList.add('is-invalid');
                isValid = false;
                
                let feedback = phoneInput.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Số điện thoại chỉ được chứa số';
                    phoneInput.parentNode.appendChild(feedback);
                } else {
                    feedback.textContent = 'Số điện thoại chỉ được chứa số';
                }
            }
        }
        
        // Date validation
        const birthDateInput = document.getElementById('Ngaysinh');
        if (birthDateInput && birthDateInput.value.trim()) {
            const today = new Date();
            const birthDate = new Date(birthDateInput.value);
            
            if (birthDate > today) {
                birthDateInput.classList.add('is-invalid');
                isValid = false;
                
                let feedback = birthDateInput.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Ngày sinh không thể ở tương lai';
                    birthDateInput.parentNode.appendChild(feedback);
                } else {
                    feedback.textContent = 'Ngày sinh không thể ở tương lai';
                }
            }
        }
        
        // Gender validation
        const maleRadio = document.getElementById('genderMale');
        const femaleRadio = document.getElementById('genderFemale');
        if (maleRadio && femaleRadio && !maleRadio.checked && !femaleRadio.checked) {
            const genderContainer = document.querySelector('.radio-spa').parentNode;
            
            let feedback = genderContainer.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block';
                feedback.textContent = 'Vui lòng chọn giới tính';
                genderContainer.appendChild(feedback);
            } else {
                feedback.style.display = 'block';
            }
            
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            
            // Focus first invalid field
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                
                // Scroll to first invalid field with smooth animation
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
} 