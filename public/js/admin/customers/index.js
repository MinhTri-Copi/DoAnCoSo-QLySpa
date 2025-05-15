/**
 * Spa-themed Customer Management JavaScript
 * Enhances the customer index page with animations and interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initAnimations();
    initSearch();
    initFilters();
    initTooltips();
    initCustomerActions();
});

/**
 * Initialize animations for page elements
 */
function initAnimations() {
    // Animate stat cards on page load
    const statCards = document.querySelectorAll('.stat-card-spa');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('slide-up');
        }, index * 100);
    });
    
    // Animate table rows on page load
    const tableRows = document.querySelectorAll('.customer-row');
    tableRows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            row.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 50);
        }, index * 50 + 300); // Start after stat cards animation
    });
    
    // Add hover effect to customer avatars
    const customerAvatars = document.querySelectorAll('.customer-avatar');
    customerAvatars.forEach(avatar => {
        avatar.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.3s ease';
            this.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.2)';
        });
        
        avatar.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
        });
    });
}

/**
 * Initialize search functionality
 */
function initSearch() {
    const searchInput = document.getElementById('customerSearch');
    if (!searchInput) return;
    
    // Clear search icon and animation
    const searchIcon = document.querySelector('.search-icon');
    const searchClearIcon = document.createElement('i');
    searchClearIcon.className = 'fas fa-times search-clear-icon';
    searchClearIcon.style.position = 'absolute';
    searchClearIcon.style.right = '15px';
    searchClearIcon.style.top = '50%';
    searchClearIcon.style.transform = 'translateY(-50%)';
    searchClearIcon.style.color = '#8d99ae';
    searchClearIcon.style.cursor = 'pointer';
    searchClearIcon.style.opacity = '0';
    searchClearIcon.style.transition = 'opacity 0.3s ease';
    
    searchInput.parentNode.appendChild(searchClearIcon);
    
    // Focus effect
    searchInput.addEventListener('focus', function() {
        searchIcon.style.color = '#006d77';
        this.parentNode.style.boxShadow = '0 0 0 0.2rem rgba(131, 197, 190, 0.25)';
    });
    
    searchInput.addEventListener('blur', function() {
        searchIcon.style.color = '';
        this.parentNode.style.boxShadow = '';
    });
    
    // Search functionality
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.customer-row');
        
        // Show/hide clear icon
        if (searchTerm.length > 0) {
            searchClearIcon.style.opacity = '1';
        } else {
            searchClearIcon.style.opacity = '0';
        }
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            const name = row.querySelector('.customer-name').textContent.toLowerCase();
            const contact = row.cells[1].textContent.toLowerCase();
            const membership = row.cells[3].textContent.toLowerCase();
            
            if (name.includes(searchTerm) || 
                contact.includes(searchTerm) ||
                membership.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
                
                // Highlight matching text (optional)
                if (searchTerm.length > 2) {
                    highlightText(row, searchTerm);
                } else {
                    removeHighlights(row);
                }
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update visible count
        updateVisibleCount(visibleCount);
    });
    
    // Clear search
    searchClearIcon.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.focus();
        searchInput.dispatchEvent(new Event('keyup'));
    });
}

/**
 * Highlight search term in row content
 */
function highlightText(row, term) {
    removeHighlights(row);
    
    const textNodes = getTextNodes(row);
    textNodes.forEach(node => {
        const text = node.nodeValue;
        const lowerText = text.toLowerCase();
        const index = lowerText.indexOf(term);
        
        if (index >= 0 && node.parentNode.tagName !== 'BUTTON' && 
            !node.parentNode.classList.contains('search-highlight')) {
            const span = document.createElement('span');
            const before = document.createTextNode(text.substring(0, index));
            const match = document.createTextNode(text.substring(index, index + term.length));
            const after = document.createTextNode(text.substring(index + term.length));
            
            const highlight = document.createElement('span');
            highlight.className = 'search-highlight';
            highlight.style.backgroundColor = 'rgba(131, 197, 190, 0.3)';
            highlight.style.borderRadius = '3px';
            highlight.style.padding = '0 2px';
            highlight.appendChild(match);
            
            span.appendChild(before);
            span.appendChild(highlight);
            span.appendChild(after);
            
            node.parentNode.replaceChild(span, node);
        }
    });
}

/**
 * Remove highlights
 */
function removeHighlights(element) {
    const highlights = element.querySelectorAll('.search-highlight');
    highlights.forEach(highlight => {
        const parent = highlight.parentNode;
        const text = parent.textContent;
        parent.parentNode.replaceChild(document.createTextNode(text), parent);
    });
}

/**
 * Get all text nodes within an element
 */
function getTextNodes(element) {
    const textNodes = [];
    const walker = document.createTreeWalker(
        element,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );
    
    let node;
    while (node = walker.nextNode()) {
        if (node.nodeValue.trim() !== '') {
            textNodes.push(node);
        }
    }
    
    return textNodes;
}

/**
 * Update visible customers count
 */
function updateVisibleCount(count) {
    const dataTableInfo = document.getElementById('dataTable_info');
    if (dataTableInfo) {
        const totalCount = document.querySelectorAll('.customer-row').length;
        dataTableInfo.textContent = `Hiển thị ${count} của ${totalCount} khách hàng`;
    }
}

/**
 * Initialize filters
 */
function initFilters() {
    const membershipFilter = document.querySelector('select');
    if (!membershipFilter) return;
    
    membershipFilter.addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.customer-row');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            const membershipCell = row.cells[3];
            const membershipText = membershipCell.textContent.toLowerCase().trim();
            
            if (filterValue === '' || membershipText.includes(filterValue)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update visible count
        updateVisibleCount(visibleCount);
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    // Initialize Bootstrap tooltips
    const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    if (tooltips.length > 0 && typeof $ !== 'undefined') {
        $(tooltips).tooltip();
    }
}

/**
 * Initialize customer action buttons
 */
function initCustomerActions() {
    // Export to CSV
    const exportCsvBtn = document.getElementById('exportCSV');
    if (exportCsvBtn) {
        exportCsvBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Simple export functionality (could be replaced with actual AJAX call)
            exportTableToCSV('customers_export.csv');
            
            // Show success message
            showToast('Danh sách khách hàng đã được xuất thành công!', 'success');
        });
    }
    
    // Print list
    const printListBtn = document.getElementById('printList');
    if (printListBtn) {
        printListBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
    
    // Refresh list
    const refreshListBtn = document.getElementById('refreshList');
    if (refreshListBtn) {
        refreshListBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading state
            const rows = document.querySelectorAll('.customer-row');
            rows.forEach(row => {
                row.style.opacity = '0.5';
            });
            
            // Simulate refresh delay
            setTimeout(() => {
                rows.forEach((row, index) => {
                    setTimeout(() => {
                        row.style.opacity = '1';
                    }, index * 50);
                });
                
                showToast('Danh sách khách hàng đã được làm mới!', 'info');
            }, 500);
        });
    }
}

/**
 * Export table data to CSV
 */
function exportTableToCSV(filename) {
    const rows = document.querySelectorAll('.customer-row');
    let csv = [];
    
    // Add header row
    const headers = ['Mã KH', 'Họ Tên', 'Số Điện Thoại', 'Email', 'Hạng Thành Viên'];
    csv.push(headers.join(','));
    
    // Add data rows
    rows.forEach(row => {
        const customerNameDiv = row.querySelector('.customer-name');
        const customerName = customerNameDiv.querySelector('div').textContent.trim();
        
        const customerIdText = customerNameDiv.querySelector('small').textContent;
        const customerId = customerIdText.replace('Mã KH:', '').trim();
        
        const contactDiv = row.cells[1];
        const contactLines = contactDiv.innerHTML.split('<div>');
        const phone = contactLines[1] ? contactLines[1].replace(/<[^>]*>/g, '').trim() : '';
        const email = contactLines[2] ? contactLines[2].replace(/<[^>]*>/g, '').trim() : '';
        
        const membershipText = row.cells[3].textContent.trim();
        
        const rowData = [customerId, customerName, phone, email, membershipText];
        csv.push(rowData.join(','));
    });
    
    // Create download link
    const csvData = csv.join('\n');
    const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
    
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, filename);
    } else {
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Check if toast container exists, if not create it
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.backgroundColor = 'white';
    toast.style.borderRadius = '8px';
    toast.style.padding = '1rem 1.5rem';
    toast.style.marginBottom = '10px';
    toast.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.minWidth = '300px';
    toast.style.transform = 'translateX(400px)';
    toast.style.opacity = '0';
    toast.style.transition = 'all 0.3s ease';
    
    // Set icon based on type
    let iconClass = 'fas fa-info-circle';
    let iconColor = '#4cc9f0';
    
    if (type === 'success') {
        iconClass = 'fas fa-check-circle';
        iconColor = '#57cc99';
    } else if (type === 'warning') {
        iconClass = 'fas fa-exclamation-triangle';
        iconColor = '#ffcb77';
    } else if (type === 'error') {
        iconClass = 'fas fa-times-circle';
        iconColor = '#e56b6f';
    }
    
    // Create toast content
    toast.innerHTML = `
        <i class="${iconClass}" style="color: ${iconColor}; font-size: 1.5rem; margin-right: 0.75rem;"></i>
        <div style="flex-grow: 1;">${message}</div>
        <button style="background: none; border: none; cursor: pointer; color: #8d99ae; font-size: 1.25rem; margin-left: 0.75rem;">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add toast to container
    toastContainer.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 50);
    
    // Add close functionality
    const closeButton = toast.querySelector('button');
    closeButton.addEventListener('click', () => {
        toast.style.transform = 'translateX(400px)';
        toast.style.opacity = '0';
        
        setTimeout(() => {
            toastContainer.removeChild(toast);
        }, 300);
    });
    
    // Auto-close after 5 seconds
    setTimeout(() => {
        if (toast.parentNode === toastContainer) {
            toast.style.transform = 'translateX(400px)';
            toast.style.opacity = '0';
            
            setTimeout(() => {
                if (toast.parentNode === toastContainer) {
                    toastContainer.removeChild(toast);
                }
            }, 300);
        }
    }, 5000);
}