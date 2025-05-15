/**
 * JavaScript for role show page
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize account list table with DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#accountsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tất cả"]]
        });
    }

    // Account distribution chart
    const accountChartCanvas = document.getElementById('accountDistributionChart');
    if (accountChartCanvas && typeof Chart !== 'undefined') {
        const ctx = accountChartCanvas.getContext('2d');
        
        // Get data from the data attribute
        const roleData = JSON.parse(accountChartCanvas.getAttribute('data-roles'));
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: roleData.map(item => item.role),
                datasets: [{
                    data: roleData.map(item => item.count),
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                        '#5a5c69', '#858796', '#6f42c1', '#20c9a6', '#f8f9fc'
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                        '#3a3b45', '#60616f', '#5d36a4', '#169c82', '#d8dbe2'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                cutoutPercentage: 70,
            },
        });
    }
});