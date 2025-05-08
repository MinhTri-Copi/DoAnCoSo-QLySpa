/**
 * JavaScript for role creation page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Generate random role ID
    const generateIdBtn = document.getElementById('generateId');
    const roleIdInput = document.getElementById('RoleID');
    
    if (generateIdBtn && roleIdInput) {
        generateIdBtn.addEventListener('click', function() {
            // Generate a random number between 1000 and 9999
            const randomId = Math.floor(Math.random() * 9000) + 1000;
            roleIdInput.value = randomId;
        });
    }

    // Form validation
    const createRoleForm = document.getElementById('createRoleForm');
    if (createRoleForm) {
        createRoleForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate Role ID
            const roleId = document.getElementById('RoleID').value.trim();
            if (!roleId) {
                document.getElementById('RoleIDFeedback').textContent = 'Vui lòng nhập mã vai trò';
                document.getElementById('RoleID').classList.add('is-invalid');
                isValid = false;
            } else if (isNaN(roleId) || parseInt(roleId) <= 0) {
                document.getElementById('RoleIDFeedback').textContent = 'Mã vai trò phải là số nguyên dương';
                document.getElementById('RoleID').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('RoleID').classList.remove('is-invalid');
            }
            
            // Validate Role Name
            const roleName = document.getElementById('Tenrole').value.trim();
            if (!roleName) {
                document.getElementById('TenroleFeedback').textContent = 'Vui lòng nhập tên vai trò';
                document.getElementById('Tenrole').classList.add('is-invalid');
                isValid = false;
            } else if (roleName.length > 50) {
                document.getElementById('TenroleFeedback').textContent = 'Tên vai trò không được vượt quá 50 ký tự';
                document.getElementById('Tenrole').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('Tenrole').classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Real-time validation
    const roleIdField = document.getElementById('RoleID');
    const roleNameField = document.getElementById('Tenrole');
    
    if (roleIdField) {
        roleIdField.addEventListener('input', function() {
            const value = this.value.trim();
            if (!value) {
                this.classList.add('is-invalid');
                document.getElementById('RoleIDFeedback').textContent = 'Vui lòng nhập mã vai trò';
            } else if (isNaN(value) || parseInt(value) <= 0) {
                this.classList.add('is-invalid');
                document.getElementById('RoleIDFeedback').textContent = 'Mã vai trò phải là số nguyên dương';
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    if (roleNameField) {
        roleNameField.addEventListener('input', function() {
            const value = this.value.trim();
            if (!value) {
                this.classList.add('is-invalid');
                document.getElementById('TenroleFeedback').textContent = 'Vui lòng nhập tên vai trò';
            } else if (value.length > 50) {
                this.classList.add('is-invalid');
                document.getElementById('TenroleFeedback').textContent = 'Tên vai trò không được vượt quá 50 ký tự';
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
});