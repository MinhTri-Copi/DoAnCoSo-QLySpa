// JavaScript dành cho bộ lọc thời gian
document.addEventListener('DOMContentLoaded', function() {
    console.log("Time filter script loaded");
    
    // Xử lý các nút bộ lọc thời gian
    const timeFilterButtons = document.querySelectorAll('.time-filter-btn:not(.custom-date-btn)');
    const customDateBtn = document.getElementById('custom-date-btn');
    const customDateMenu = document.getElementById('custom-date-menu');
    const applyCustomDateBtn = document.getElementById('apply-custom-date');
    const cancelCustomDateBtn = document.getElementById('cancel-custom-date');
    
    // Đảm bảo script này chỉ chạy sau khi các hàm showNotification và updateNotification đã được định nghĩa
    const waitForNotificationFunctions = setInterval(() => {
        if (typeof window.showNotification === 'function' && typeof window.updateNotification === 'function') {
            console.log("Notification functions detected, initializing time filter");
            clearInterval(waitForNotificationFunctions);
            
            // Khởi tạo bộ lọc thời gian sau khi các hàm thông báo đã sẵn sàng
            initializeTimeFilter();
        }
    }, 100);
    
    function initializeTimeFilter() {
        console.log("Custom date button:", customDateBtn);
        console.log("Custom date menu:", customDateMenu);
        
        // Thiết lập ngày mặc định
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        
        if (startDateInput && endDateInput) {
            const today = new Date();
            const oneMonthAgo = new Date();
            oneMonthAgo.setMonth(today.getMonth() - 1);
            
            startDateInput.value = formatDateForInput(oneMonthAgo);
            endDateInput.value = formatDateForInput(today);
            
            // Thêm sự kiện change để cập nhật input thời gian
            startDateInput.addEventListener('change', function() {
                console.log("Start date changed:", this.value);
            });
            
            endDateInput.addEventListener('change', function() {
                console.log("End date changed:", this.value);
            });
        }
        
        // Hiệu ứng ripple cho các nút
        const rippleButtons = document.querySelectorAll('.ripple');
        if (rippleButtons.length > 0) {
            rippleButtons.forEach(button => {
                button.addEventListener('click', createRipple);
            });
        }
        
        // Xử lý các nút filter bình thường (không phải custom date)
        timeFilterButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Loại bỏ class active từ tất cả các nút
                document.querySelectorAll('.time-filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Thêm class active cho nút được click
                this.classList.add('active');
                
                // Đóng custom date menu nếu đang mở
                if (customDateMenu) {
                    customDateMenu.style.display = 'none';
                }
                
                // Lấy period từ button
                const period = this.getAttribute('data-period');
                console.log("Selected period:", period);
                
                // Gọi API để lấy dữ liệu theo period - trực tiếp dùng hàm này thay vì qua các biến trung gian
                fetchFilteredStats(period, null, null);
                
                // Thêm hiệu ứng ripple
                addRippleEffect(e, this);
            });
        });
        
        // Xử lý nút tùy chỉnh
        if (customDateBtn) {
            customDateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log("Custom date button clicked");
                
                // Hiển thị dropdown bằng cách đặt trực tiếp style.display
                if (customDateMenu) {
                    if (customDateMenu.style.display === 'none' || customDateMenu.style.display === '') {
                        console.log("Showing dropdown");
                        customDateMenu.style.display = 'block';
                    } else {
                        console.log("Hiding dropdown");
                        customDateMenu.style.display = 'none';
                    }
                }
                
                // Thêm hiệu ứng ripple nếu cần
                if (typeof createRipple === 'function') {
                    createRipple.call(this, e);
                }
            });
        }
        
        // Xử lý click ra ngoài để đóng dropdown
        document.addEventListener('click', function(e) {
            if (customDateMenu && 
                customDateMenu.style.display === 'block' && 
                !customDateMenu.contains(e.target) && 
                e.target !== customDateBtn) {
                console.log("Clicking outside, hiding dropdown");
                customDateMenu.style.display = 'none';
            }
        });
        
        // Ngăn chặn sự kiện click trong dropdown lan ra document
        if (customDateMenu) {
            customDateMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Xử lý nút hủy
        if (cancelCustomDateBtn) {
            cancelCustomDateBtn.addEventListener('click', function() {
                console.log("Cancel button clicked");
                if (customDateMenu) {
                    customDateMenu.style.display = 'none';
                }
            });
        }
        
        // Xử lý nút áp dụng
        if (applyCustomDateBtn) {
            applyCustomDateBtn.addEventListener('click', function() {
                console.log("Apply button clicked");
                
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;
                
                if (!startDate || !endDate) {
                    alert('Vui lòng chọn ngày bắt đầu và ngày kết thúc');
                    return;
                }
                
                // Đóng dropdown
                if (customDateMenu) {
                    customDateMenu.style.display = 'none';
                }
                
                // Đánh dấu nút tùy chỉnh là active
                document.querySelectorAll('.time-filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                if (customDateBtn) {
                    customDateBtn.classList.add('active');
                }
                
                // Chuyển định dạng ngày
                const formattedStartDate = startDate.replace('T', ' ');
                const formattedEndDate = endDate.replace('T', ' ');
                
                // Gọi API để lấy dữ liệu - trực tiếp dùng hàm này thay vì qua các biến trung gian
                fetchFilteredStats('custom', formattedStartDate, formattedEndDate);
            });
        }
    }
    
    // Function tạo hiệu ứng ripple 
    function createRipple(e) {
        const button = this;
        const circle = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;
        
        // Lấy vị trí của button
        const rect = button.getBoundingClientRect();
        
        // Tính toán vị trí của ripple dựa trên vị trí của click và vị trí của button
        const x = e.clientX - rect.left - radius;
        const y = e.clientY - rect.top - radius;
        
        // Thiết lập style cho hiệu ứng
        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${x}px`;
        circle.style.top = `${y}px`;
        circle.classList.add('ripple-effect');
        
        // Xóa hiệu ứng cũ nếu có
        const ripple = button.querySelector('.ripple-effect');
        if (ripple) {
            ripple.remove();
        }
        
        // Thêm hiệu ứng mới
        button.appendChild(circle);
        
        // Xóa hiệu ứng sau khi hoàn thành animation
        setTimeout(() => {
            if (circle && circle.parentElement) {
                circle.remove();
            }
        }, 600);
    }
    
    // Hàm tạo hiệu ứng ripple
    function addRippleEffect(e, element) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple-effect');
        
        // Lấy kích thước của nút để tính bán kính
        const width = element.offsetWidth;
        const height = element.offsetHeight;
        const diameter = Math.max(width, height);
        const radius = diameter / 2;
        
        // Tính toán vị trí của ripple
        const rect = element.getBoundingClientRect();
        const left = e.clientX - rect.left - radius;
        const top = e.clientY - rect.top - radius;
        
        // Thiết lập vị trí và kích thước
        ripple.style.width = ripple.style.height = `${diameter}px`;
        ripple.style.left = `${left}px`;
        ripple.style.top = `${top}px`;
        
        // Thêm ripple vào nút
        element.appendChild(ripple);
        
        // Xóa ripple sau khi animation kết thúc
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // Hàm gọi API và cập nhật dashboard
    function fetchFilteredStats(period, startDate = null, endDate = null) {
        console.log(`Fetching data for period: ${period}, startDate: ${startDate}, endDate: ${endDate}`);
        
        // Kiểm tra xem hàm showNotification đã tồn tại chưa
        console.log('showNotification function exists:', typeof window.showNotification === 'function');
        console.log('isNotificationInProgress:', window.isNotificationInProgress);
        
        // Xóa tất cả các thông báo cũ trước khi tạo thông báo mới
        const container = document.getElementById('single-notification-container');
        if (container) {
            console.log('Container found, clearing previous notifications');
            container.innerHTML = '';
        } else {
            console.error('Notification container not found!');
        }
        
        // Đảm bảo thông báo không bị chặn bởi cờ
        if (window.isNotificationInProgress) {
            console.log('Notification is already in progress, resetting flag');
            window.isNotificationInProgress = false;
        }
        
        // Hiển thị thông báo đang tải - chỉ tạo một thông báo duy nhất
        let notification = null;
        if (typeof window.showNotification === 'function') {
            try {
                let periodText = 'Đang cập nhật thống kê';
                if (period === 'today') periodText += ' hôm nay';
                else if (period === 'yesterday') periodText += ' hôm qua';
                else if (period === 'last7days') periodText += ' 7 ngày qua';
                else if (period === 'last30days') periodText += ' 30 ngày qua';
                else if (period === 'thisMonth') periodText += ' tháng này';
                else if (period === 'lastMonth') periodText += ' tháng trước';
                else if (period === 'custom') periodText += ' theo khoảng thời gian tùy chỉnh';
                else periodText += '...';
                
                notification = window.showNotification('loading', 'Đang tải dữ liệu', periodText);
                console.log('Created notification with ID:', notification);
            } catch (error) {
                console.error('Error creating notification:', error);
            }
        } else {
            console.error('showNotification function is not available!');
        }
        
        const startTime = new Date().getTime();
        const minLoadingTime = 500; // Thời gian loading tối thiểu (ms)
        
        // Xây dựng URL API
        let apiUrl = `/admin/dashboard/filtered-stats?period=${period}`;
        if (startDate && endDate) {
            apiUrl += `&start_date=${startDate}&end_date=${endDate}`;
        }
        
        console.log('Fetching data from URL:', apiUrl);
        
        // Gọi API để lấy dữ liệu thống kê
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi khi lấy dữ liệu thống kê: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Filtered stats data received:', data);
                
                // Tính thời gian đã trôi qua
                const elapsedTime = new Date().getTime() - startTime;
                
                // Đảm bảo loading hiển thị ít nhất minLoadingTime
                const delayHide = Math.max(0, minLoadingTime - elapsedTime);
                
                // Cập nhật giao diện sau khi đã đảm bảo thời gian loading tối thiểu
                setTimeout(() => {
                    // Cập nhật các thẻ thống kê
                    updateDashboardStats(data);
                    
                    console.log('Stats updated, notification ID:', notification);
                    console.log('updateNotification function exists:', typeof window.updateNotification === 'function');
                    
                    // Hiển thị thông báo thành công
                    if (notification && typeof window.updateNotification === 'function') {
                        try {
                            window.updateNotification(
                                notification,
                                'success',
                                'Dữ liệu đã cập nhật',
                                'Dữ liệu thống kê đã được cập nhật thành công!'
                            );
                            console.log('Notification updated to success');
                        } catch (error) {
                            console.error('Error updating notification:', error);
                            // Tạo thông báo thành công mới nếu cần
                            if (typeof window.showNotification === 'function') {
                                window.showNotification('success', 'Dữ liệu đã cập nhật', 'Dữ liệu thống kê đã được cập nhật thành công!');
                            }
                        }
                    } else {
                        console.error('Cannot update notification: ID is null or updateNotification is not a function');
                        // Tạo thông báo thành công mới nếu cần
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('success', 'Dữ liệu đã cập nhật', 'Dữ liệu thống kê đã được cập nhật thành công!');
                        }
                    }
                }, delayHide);
            })
            .catch(error => {
                console.error('Error fetching filtered stats:', error);
                
                // Hiển thị thông báo lỗi
                if (notification && typeof window.updateNotification === 'function') {
                    try {
                        window.updateNotification(
                            notification,
                            'error',
                            'Lỗi cập nhật dữ liệu',
                            'Không thể cập nhật dữ liệu: ' + error.message
                        );
                        console.log('Notification updated to error');
                    } catch (error) {
                        console.error('Error updating notification to error state:', error);
                        // Tạo thông báo lỗi mới nếu cần
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('error', 'Lỗi cập nhật dữ liệu', 'Không thể cập nhật dữ liệu: ' + error.message);
                        }
                    }
                } else {
                    console.error('Cannot update notification to error state');
                    // Tạo thông báo lỗi mới nếu cần
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('error', 'Lỗi cập nhật dữ liệu', 'Không thể cập nhật dữ liệu: ' + error.message);
                    }
                }
            });
    }
    
    // Hàm cập nhật biểu đồ
    function updateRevenueChart(period) {
        console.log('Updating revenue chart for period:', period);
        const chartElement = document.getElementById('revenue-chart');
        if (!chartElement) {
            console.error('Chart element not found');
            return;
        }
        
        // Kiểm tra xem hàm showNotification đã tồn tại chưa
        console.log('showNotification function exists:', typeof window.showNotification === 'function');
        console.log('isNotificationInProgress:', window.isNotificationInProgress);
        
        // Xóa tất cả các thông báo cũ trước khi tạo thông báo mới
        const container = document.getElementById('single-notification-container');
        if (container) {
            console.log('Container found, clearing previous notifications for chart update');
            container.innerHTML = '';
        } else {
            console.error('Notification container not found!');
        }
        
        // Đảm bảo thông báo không bị chặn bởi cờ
        if (window.isNotificationInProgress) {
            console.log('Notification is already in progress, resetting flag for chart update');
            window.isNotificationInProgress = false;
        }
        
        // Lấy text hiển thị cho từng loại period
        const getPeriodText = (period) => {
            switch (period) {
                case 'daily': return 'ngày';
                case 'monthly': return 'tháng';
                case 'yearly': return 'năm';
                default: return 'khoảng thời gian';
            }
        };
        
        // Hiển thị thông báo đang tải
        let notification = null;
        if (typeof window.showNotification === 'function') {
            try {
                const periodText = getPeriodText(period);
                notification = window.showNotification('loading', 'Đang tải dữ liệu', `Đang cập nhật biểu đồ doanh thu theo ${periodText}`);
                console.log('Created chart notification with ID:', notification);
            } catch (error) {
                console.error('Error creating chart notification:', error);
            }
        } else {
            console.error('showNotification function is not available for chart!');
        }
        
        const apiUrl = `/admin/dashboard/revenue-data?period=${period}`;
        console.log('Fetching chart data from URL:', apiUrl);
        
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Lỗi HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Chart data received:', data);
                
                // Dữ liệu đã tải thành công, cập nhật biểu đồ
                if (window.ApexCharts) {
                    const chart = ApexCharts.getChartByID('revenue-chart');
                    if (chart) {
                        // Xử lý dữ liệu cho biểu đồ
                        const chartData = processChartData(data, period);
                        
                        // Cập nhật biểu đồ
                        chart.updateOptions({
                            xaxis: {
                                categories: chartData.categories
                            }
                        });
                        
                        chart.updateSeries([{
                            name: 'Doanh thu',
                            data: chartData.values
                        }]);
                        
                        console.log('Chart updated, notification ID:', notification);
                        console.log('updateNotification function exists:', typeof window.updateNotification === 'function');
                        
                        // Cập nhật thông báo
                        if (notification && typeof window.updateNotification === 'function') {
                            try {
                                const periodText = getPeriodText(period);
                                window.updateNotification(
                                    notification, 
                                    'success', 
                                    'Biểu đồ đã cập nhật', 
                                    `Dữ liệu biểu đồ doanh thu theo ${periodText} đã được cập nhật thành công`
                                );
                                console.log('Chart notification updated to success');
                            } catch (error) {
                                console.error('Error updating chart notification:', error);
                                // Tạo thông báo thành công mới nếu cần
                                if (typeof window.showNotification === 'function') {
                                    const periodText = getPeriodText(period);
                                    window.showNotification(
                                        'success', 
                                        'Biểu đồ đã cập nhật', 
                                        `Dữ liệu biểu đồ doanh thu theo ${periodText} đã được cập nhật thành công`
                                    );
                                }
                            }
                        } else {
                            console.error('Cannot update chart notification: ID is null or updateNotification is not a function');
                            // Tạo thông báo thành công mới nếu cần
                            if (typeof window.showNotification === 'function') {
                                const periodText = getPeriodText(period);
                                window.showNotification(
                                    'success', 
                                    'Biểu đồ đã cập nhật', 
                                    `Dữ liệu biểu đồ doanh thu theo ${periodText} đã được cập nhật thành công`
                                );
                            }
                        }
                    } else {
                        console.error('Chart not found');
                        if (notification && typeof window.updateNotification === 'function') {
                            try {
                                window.updateNotification(
                                    notification, 
                                    'error', 
                                    'Lỗi biểu đồ', 
                                    'Không tìm thấy biểu đồ doanh thu'
                                );
                            } catch (error) {
                                console.error('Error updating chart notification to error state:', error);
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification('error', 'Lỗi biểu đồ', 'Không tìm thấy biểu đồ doanh thu');
                                }
                            }
                        } else if (typeof window.showNotification === 'function') {
                            window.showNotification('error', 'Lỗi biểu đồ', 'Không tìm thấy biểu đồ doanh thu');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error updating chart:', error);
                if (notification && typeof window.updateNotification === 'function') {
                    try {
                        window.updateNotification(
                            notification, 
                            'error', 
                            'Lỗi biểu đồ', 
                            error.message || 'Không thể cập nhật biểu đồ doanh thu'
                        );
                        console.log('Chart notification updated to error');
                    } catch (error) {
                        console.error('Error updating chart notification to error state:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(
                                'error', 
                                'Lỗi biểu đồ', 
                                error.message || 'Không thể cập nhật biểu đồ doanh thu'
                            );
                        }
                    }
                } else {
                    console.error('Cannot update chart notification to error state');
                    // Tạo thông báo lỗi mới nếu cần
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(
                            'error', 
                            'Lỗi biểu đồ', 
                            error.message || 'Không thể cập nhật biểu đồ doanh thu'
                        );
                    }
                }
            });
    }
    
    // Hàm xử lý dữ liệu cho biểu đồ
    function processChartData(data, period) {
        let categories = [];
        let values = [];
        
        if (!data || data.length === 0) {
            return { categories: [], values: [] };
        }
        
        switch (period) {
            case 'daily':
                // Xử lý dữ liệu ngày
                data.forEach(item => {
                    if (item.date) {
                        const date = new Date(item.date);
                        categories.push(date.getDate() + '/' + (date.getMonth() + 1));
                        values.push(parseInt(item.total) || 0);
                    }
                });
                break;
            
            case 'yearly':
                // Xử lý dữ liệu năm
                data.forEach(item => {
                    if (item.year) {
                        categories.push(item.year.toString());
                        values.push(parseInt(item.total) || 0);
                    }
                });
                break;
            
            case 'custom':
                // Xử lý dữ liệu tùy chỉnh
                data.forEach(item => {
                    if (item.date) {
                        categories.push(item.label || formatReadableDate(item.date));
                        values.push(parseInt(item.total) || 0);
                    } else if (item.yearMonth) {
                        categories.push(item.label || item.yearMonth);
                        values.push(parseInt(item.total) || 0);
                    }
                });
                break;
            
            case 'monthly':
            default:
                // Xử lý dữ liệu tháng (mặc định)
                const monthNames = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
                data.forEach(item => {
                    if (item.month) {
                        const monthIndex = parseInt(item.month) - 1;
                        if (monthIndex >= 0 && monthIndex < 12) {
                            categories.push(monthNames[monthIndex]);
                            values.push(parseInt(item.total) || 0);
                        }
                    }
                });
                break;
        }
        
        return { categories, values };
    }
    
    // Hàm cập nhật các thẻ thống kê
    function updateDashboardStats(data) {
        try {
            console.log('Updating dashboard stats with data:', data);
            
            // Cập nhật tổng doanh thu - sử dụng dashboard_revenue thay vì total_revenue
            const revenueElement = document.querySelector('.col-lg-3:nth-child(1) .stat-value');
            if (revenueElement) {
                revenueElement.textContent = formatCurrency(data.dashboard_revenue || 0) + 'đ';
            }
            
            // Cập nhật badge của doanh thu
            const revenueBadge = document.getElementById('revenue-badge');
            if (revenueBadge) {
                revenueBadge.textContent = data.period_label || 'Hôm nay';
            }
            
            // Cập nhật tỷ lệ thay đổi doanh thu - sử dụng dashboard_revenue_change
            updateChangeRate('.col-lg-3:nth-child(1) .stat-change', data.dashboard_revenue_change || 0);
            
            // Cập nhật tổng đặt lịch - sử dụng dashboard_bookings thay vì total_bookings
            const bookingsElement = document.querySelector('.col-lg-3:nth-child(2) .stat-value');
            if (bookingsElement) {
                bookingsElement.textContent = formatCurrency(data.dashboard_bookings || 0);
            }
            
            // Cập nhật badge của đặt lịch
            const bookingsBadge = document.getElementById('bookings-badge');
            if (bookingsBadge) {
                bookingsBadge.textContent = data.period_label || 'Hôm nay';
            }
            
            // Cập nhật tỷ lệ thay đổi đặt lịch - sử dụng dashboard_bookings_change
            updateChangeRate('.col-lg-3:nth-child(2) .stat-change', data.dashboard_bookings_change || 0);
            
            // Cập nhật tổng khách hàng - giữ nguyên vì không thay đổi
            const customersElement = document.querySelector('.col-lg-3:nth-child(3) .stat-value');
            if (customersElement) {
                customersElement.textContent = formatCurrency(data.total_customers || 0);
            }
            
            // Cập nhật badge của khách hàng
            const customersBadge = document.getElementById('customers-badge');
            if (customersBadge) {
                customersBadge.textContent = data.period_label || 'Hôm nay';
            }
            
            // Cập nhật tỷ lệ thay đổi khách hàng - giữ nguyên
            updateChangeRate('.col-lg-3:nth-child(3) .stat-change', data.customers_change || 0);
            
            // Cập nhật tổng đánh giá - giữ nguyên
            const reviewsElement = document.querySelector('.col-lg-3:nth-child(4) .stat-value');
            if (reviewsElement) {
                reviewsElement.textContent = formatCurrency(data.total_reviews || 0);
            }
            
            // Cập nhật badge của đánh giá
            const reviewsBadge = document.getElementById('reviews-badge');
            if (reviewsBadge) {
                reviewsBadge.textContent = data.period_label || 'Hôm nay';
            }
            
            // Cập nhật tỷ lệ thay đổi đánh giá - giữ nguyên
            updateChangeRate('.col-lg-3:nth-child(4) .stat-change', data.reviews_change || 0);
            
            // Cập nhật text so sánh
            const comparisonTexts = document.querySelectorAll('.comparison-period-text');
            if (comparisonTexts.length > 0 && data.period_text) {
                comparisonTexts.forEach(el => {
                    el.textContent = data.period_text;
                });
            }
        } catch (error) {
            console.error('Error updating dashboard stats:', error);
            if (typeof window.showNotification === 'function') {
                window.showNotification('error', 'Lỗi cập nhật', 'Không thể cập nhật các số liệu thống kê');
            }
        }
    }
    
    // Hàm cập nhật chỉ số tỷ lệ thay đổi
    function updateChangeRate(selector, value) {
        const element = document.querySelector(selector);
        if (!element) return;
        
        // Reset classes
        element.classList.remove('positive', 'negative', 'neutral');
        
        // Cập nhật UI dựa trên giá trị
        if (value > 0) {
            element.classList.add('positive');
            element.style.backgroundColor = 'rgba(72, 187, 120, 0.1)';
            element.style.color = '#48bb78';
            element.innerHTML = `<i class="fas fa-arrow-up mr-1"></i> <span class="time-comparison-text">${value}%</span>`;
        } else if (value < 0) {
            element.classList.add('negative');
            element.style.backgroundColor = 'rgba(245, 101, 101, 0.1)';
            element.style.color = '#f56565';
            element.innerHTML = `<i class="fas fa-arrow-down mr-1"></i> <span class="time-comparison-text">${Math.abs(value)}%</span>`;
        } else {
            element.classList.add('neutral');
            element.style.backgroundColor = 'rgba(160, 174, 192, 0.1)';
            element.style.color = '#718096';
            element.innerHTML = `<i class="fas fa-minus mr-1"></i> <span class="time-comparison-text">0%</span>`;
        }
    }
    
    // Helper functions
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'decimal',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0
        }).format(value);
    }
    
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}T00:00`;
    }
    
    function formatReadableDate(date) {
        if (typeof date === 'string') {
            date = new Date(date);
        }
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        return `${day}/${month}`;
    }
}); 