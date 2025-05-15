/**
 * JavaScript for role edit page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const editRoleForm = document.getElementById('editRoleForm');
    if (editRoleForm) {
        editRoleForm.addEventListener('submit', function(e) {
            let isValid = true;
            
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
    const roleNameField = document.getElementById('Tenrole');
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