/**
 * Enhanced JavaScript for Customer Detail Page
 * 
 * Features:
 * - Interactive tabs with memory
 * - Spending charts with animations
 * - Points management
 * - Data filtering and sorting
 * - Modal interactions
 * - Activity timeline
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initDetailTabs();
    initSpendingChart();
    initPointsManagement();
    initFilterAndSort();
    initModals();
    initActivityTimeline();
    initTooltips();
});

/**
 * Initialize tabs with localStorage memory
 */
function initDetailTabs() {
    const tabLinks = document.querySelectorAll('#customerTabs .nav-link');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Store active tab in localStorage
            const tabId = this.getAttribute('href');
            localStorage.setItem('activeCustomerTab', tabId);
            
            // Activate the tab
            $(tabId).tab('show');
            
            // Animate content
            const tabContent = document.querySelector(tabId);
            if (tabContent) {
                tabContent.classList.add('fade-in');
                setTimeout(() => {
                    tabContent.classList.remove('fade-in');
                }, 500);
            }
        });
    });
    
    // Restore active tab from localStorage
    const activeTab = localStorage.getItem('activeCustomerTab');
    if (activeTab) {
        const tab = document.querySelector(`#customerTabs .nav-link[href="${activeTab}"]`);
        if (tab) {
            $(tab).tab('show');
        }
    }
    
    // Handle URL hash for direct tab access
    if (window.location.hash) {
        const hashTab = document.querySelector(`#customerTabs .nav-link[href="${window.location.hash}"]`);
        if (hashTab) {
            $(hashTab).tab('show');
        }
    }
}

/**
 * Initialize spending chart with enhanced visualizations
 */
function initSpendingChart() {
    const spendingChartCanvas = document.getElementById('spendingChart');
    
    if (spendingChartCanvas && typeof Chart !== 'undefined') {
        // Parse data from data attribute
        const chartData = JSON.parse(spendingChartCanvas.getAttribute('data-spending') || '[]');
        
        // Get average spending for comparison
        const amounts = chartData.map(item => item.amount);
        const avgAmount = amounts.reduce((sum, val) => sum + val, 0) / amounts.length || 0;
        
        // Create gradient background
        const ctx = spendingChartCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.8)');
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0.1)');
        
        // Create chart
        const chart = new Chart(spendingChartCanvas, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.month),
                datasets: [{
                    label: "Chi tiêu",
                    backgroundColor: gradient,
                    borderColor: "#4e73df",
                    borderWidth: 2,
                    data: chartData.map(item => item.amount),
                    hoverBackgroundColor: "#2e59d9",
                    pointBackgroundColor: "#4e73df",
                    pointBorderColor: "#fff",
                    pointRadius: 4
                }, {
                    label: "Trung bình",
                    type: 'line',
                    data: Array(chartData.length).fill(avgAmount),
                    borderColor: 'rgba(231, 74, 59, 0.7)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: 'rgba(231, 74, 59, 0.7)',
                    pointBorderColor: '#fff',
                    pointRadius: 0,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6,
                            padding: 10,
                            fontColor: "#858796"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                            fontColor: "#858796",
                            callback: function(value) {
                                return formatCurrency(value) + ' VNĐ';
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        fontColor: "#858796",
                        usePointStyle: true
                    }
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            const datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + formatCurrency(tooltipItem.yLabel) + ' VNĐ';
                        }
                    }
                }
            }
        });
        
        // Add time period selectors
        const timeFilters = document.querySelectorAll('.chart-time-filter');
        if (timeFilters.length > 0) {
            timeFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    // Remove active class from all filters
                    timeFilters.forEach(f => f.classList.remove('active'));
                    
                    // Add active class to clicked filter
                    this.classList.add('active');
                    
                    // Get time period
                    const period = this.getAttribute('data-period');
                    
                    // Update chart data based on time period
                    updateChartData(chart, period);
                });
            });
        }
    }
}

/**
 * Initialize points management system
 */
function initPointsManagement() {
    const addPointsBtn = document.getElementById('addPointsBtn');
    const addPointsBtn2 = document.getElementById('addPointsBtn2');
    const addPointsBtn3 = document.getElementById('addPointsBtn3');
    const submitPointsBtn = document.getElementById('submitPoints');
    const pointTypeRadios = document.querySelectorAll('input[name="point_type"]');
    const pointsAmountInput = document.getElementById('pointsAmount');
    const pointsNoteInput = document.getElementById('pointsNote');
    
    // Show modal when any add points button is clicked
    [addPointsBtn, addPointsBtn2, addPointsBtn3].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function() {
                $('#addPointsModal').modal('show');
            });
        }
    });
    
    // Handle point type selection
    if (pointTypeRadios.length > 0) {
        pointTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const pointTypeLabel = document.getElementById('pointTypeLabel');
                if (this.value === 'Trừ') {
                    pointTypeLabel.textContent = 'Trừ Điểm';
                    pointTypeLabel.className = 'text-danger';
                } else {
                    pointTypeLabel.textContent = 'Cộng Điểm';
                    pointTypeLabel.className = 'text-success';
                }
            });
        });
    }
    
    // Show suggested reasons for points
    if (pointsNoteInput) {
        const suggestedReasons = [
            'Đặt dịch vụ mới',
            'Khuyến mãi đặc biệt',
            'Sinh nhật khách hàng',
            'Khách hàng thân thiết',
            'Đổi điểm lấy ưu đãi',
            'Hoàn điểm do hủy dịch vụ'
        ];
        
        const suggestionContainer = document.createElement('div');
        suggestionContainer.className = 'note-suggestions mt-2';
        pointsNoteInput.parentNode.appendChild(suggestionContainer);
        
        suggestedReasons.forEach(reason => {
            const pill = document.createElement('span');
            pill.className = 'badge badge-light mr-2 mb-2 p-2 suggestion-pill';
            pill.textContent = reason;
            pill.style.cursor = 'pointer';
            suggestionContainer.appendChild(pill);
            
            pill.addEventListener('click', function() {
                pointsNoteInput.value = this.textContent;
            });
        });
    }
    
    // Live validation for points form
    if (pointsAmountInput) {
        pointsAmountInput.addEventListener('input', validatePointsForm);
    }
    
    if (pointsNoteInput) {
        pointsNoteInput.addEventListener('input', validatePointsForm);
    }
    
    // Submit points form
    if (submitPointsBtn) {
        submitPointsBtn.addEventListener('click', function() {
            if (validatePointsForm()) {
                // Disable submit button and show spinner
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
                
                // Submit form
                document.getElementById('addPointsForm').submit();
            }
        });
    }
}

/**
 * Validate points form
 */
function validatePointsForm() {
    const pointsInput = document.getElementById('pointsAmount');
    const noteInput = document.getElementById('pointsNote');
    const submitBtn = document.getElementById('submitPoints');
    const pointsValue = pointsInput ? parseInt(pointsInput.value) : 0;
    const noteValue = noteInput ? noteInput.value.trim() : '';
    
    let isValid = true;
    
    // Validate points
    if (!pointsValue || pointsValue <= 0) {
        if (pointsInput) {
            pointsInput.classList.add('is-invalid');
            isValid = false;
            
            // Create or update feedback message
            let feedback = pointsInput.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                pointsInput.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Vui lòng nhập số điểm lớn hơn 0';
        }
    } else if (pointsInput) {
        pointsInput.classList.remove('is-invalid');
    }
    
    // Validate note
    if (!noteValue) {
        if (noteInput) {
            noteInput.classList.add('is-invalid');
            isValid = false;
            
            // Create or update feedback message
            let feedback = noteInput.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                noteInput.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Vui lòng nhập ghi chú';
        }
    } else if (noteInput) {
        noteInput.classList.remove('is-invalid');
    }
    
    // Enable/disable submit button
    if (submitBtn) {
        submitBtn.disabled = !isValid;
    }
    
    return isValid;
}

/**
 * Initialize filters and sorting
 */
function initFilterAndSort() {
    // Order history filter
    const orderFilters = document.querySelectorAll('.order-filter');
    const orderRows = document.querySelectorAll('.order-row');
    
    if (orderFilters.length > 0 && orderRows.length > 0) {
        orderFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                orderFilters.forEach(f => f.classList.remove('active'));
                
                // Add active class to clicked filter
                this.classList.add('active');
                
                // Get filter value
                const status = this.getAttribute('data-filter');
                
                // Filter rows
                orderRows.forEach(row => {
                    if (status === 'all' || row.getAttribute('data-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update counter
                const visibleRows = [...orderRows].filter(row => row.style.display !== 'none');
                document.getElementById('visibleOrdersCount').textContent = visibleRows.length;
            });
        });
    }
    
    // Appointment history filter
    const appointmentFilters = document.querySelectorAll('.appointment-filter');
    const appointmentRows = document.querySelectorAll('.appointment-row');
    
    if (appointmentFilters.length > 0 && appointmentRows.length > 0) {
        appointmentFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                appointmentFilters.forEach(f => f.classList.remove('active'));
                
                // Add active class to clicked filter
                this.classList.add('active');
                
                // Get filter value
                const status = this.getAttribute('data-filter');
                
                // Filter rows
                appointmentRows.forEach(row => {
                    if (status === 'all' || row.getAttribute('data-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update counter
                const visibleRows = [...appointmentRows].filter(row => row.style.display !== 'none');
                document.getElementById('visibleAppointmentsCount').textContent = visibleRows.length;
            });
        });
    }
    
    // Search input for reviews
    const reviewSearch = document.getElementById('reviewSearch');
    const reviewItems = document.querySelectorAll('.review-item');
    
    if (reviewSearch && reviewItems.length > 0) {
        reviewSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            reviewItems.forEach(item => {
                const reviewText = item.querySelector('.review-text').textContent.toLowerCase();
                
                if (reviewText.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

/**
 * Initialize modals
 */
function initModals() {
    // View appointment details modal
    const viewAppointmentBtns = document.querySelectorAll('.view-appointment');
    
    viewAppointmentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-id');
            
            // For now, just find the row with this ID and show data in modal
            const row = document.querySelector(`.appointment-row[data-id="${appointmentId}"]`);
            
            if (row) {
                const date = row.getAttribute('data-date');
                const time = row.getAttribute('data-time');
                const service = row.getAttribute('data-service');
                const status = row.getAttribute('data-status');
                
                // Fill modal with data
                document.getElementById('appointmentModalTitle').textContent = `Chi tiết lịch hẹn #${appointmentId}`;
                document.getElementById('appointmentDate').textContent = date;
                document.getElementById('appointmentTime').textContent = time;
                document.getElementById('appointmentService').textContent = service;
                document.getElementById('appointmentStatus').textContent = status;
                
                // Show modal
                $('#appointmentDetailModal').modal('show');
            }
        });
    });
    
    // View order details modal
    const viewOrderBtns = document.querySelectorAll('.view-order');
    
    viewOrderBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            
            // For now, just find the row with this ID and show data in modal
            const row = document.querySelector(`.order-row[data-id="${orderId}"]`);
            
            if (row) {
                const date = row.getAttribute('data-date');
                const amount = row.getAttribute('data-amount');
                const method = row.getAttribute('data-method');
                const status = row.getAttribute('data-status');
                
                // Fill modal with data
                document.getElementById('orderModalTitle').textContent = `Chi tiết đơn hàng #${orderId}`;
                document.getElementById('orderDate').textContent = date;
                document.getElementById('orderAmount').textContent = amount;
                document.getElementById('orderMethod').textContent = method;
                document.getElementById('orderStatus').textContent = status;
                
                // Show modal
                $('#orderDetailModal').modal('show');
            }
        });
    });
}

/**
 * Initialize activity timeline
 */
function initActivityTimeline() {
    // Make each timeline item show more details on click
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    timelineItems.forEach(item => {
        item.addEventListener('click', function() {
            // Toggle expanded class
            this.classList.toggle('timeline-item-expanded');
            
            // Get details element
            const details = this.querySelector('.timeline-details');
            
            if (details) {
                // Toggle details visibility
                if (details.style.maxHeight) {
                    details.style.maxHeight = null;
                } else {
                    details.style.maxHeight = details.scrollHeight + 'px';
                }
            }
        });
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    // Initialize Bootstrap tooltips
    const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    
    if (tooltips.length > 0) {
        $(tooltips).tooltip();
    }
}

/**
 * Update chart data based on time period
 */
function updateChartData(chart, period) {
    // Placeholder for API call to get data for the selected period
    // For now, just show a message that this would update the chart
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Updating Chart',
            text: `This would update the chart with data for the ${period} period.`,
            timer: 2000,
            showConfirmButton: false
        });
    }
}

/**
 * Format currency with thousands separator
 */
function formatCurrency(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Format date to DD/MM/YYYY format
 */
function formatDate(date) {
    if (!date) return '';
    
    const d = new Date(date);
    const day = d.getDate().toString().padStart(2, '0');
    const month = (d.getMonth() + 1).toString().padStart(2, '0');
    const year = d.getFullYear();
    
    return `${day}/${month}/${year}`;
}

/**
 * Format date and time to DD/MM/YYYY HH:MM format
 */
function formatDateTime(date) {
    if (!date) return '';
    
    const d = new Date(date);
    const day = d.getDate().toString().padStart(2, '0');
    const month = (d.getMonth() + 1).toString().padStart(2, '0');
    const year = d.getFullYear();
    const hours = d.getHours().toString().padStart(2, '0');
    const minutes = d.getMinutes().toString().padStart(2, '0');
    
    return `${day}/${month}/${year} ${hours}:${minutes}`;
}
    