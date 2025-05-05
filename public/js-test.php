<?php
// Disable browser caching for this file
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaScript Testing - Rosa Spa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .test-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .test-result {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .failure {
            background-color: #f8d7da;
            color: #721c24;
        }
        .log-container {
            height: 150px;
            overflow-y: auto;
            background-color: #f8f9fa;
            padding: 10px;
            font-family: monospace;
            font-size: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1, h2 {
            color: #f58cba;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Rosa Spa - JavaScript Testing</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="test-container">
                    <h2>1. ApexCharts Availability Test</h2>
                    <p>Checking if ApexCharts library is loading properly...</p>
                    <div id="apexcharts-test-result" class="test-result">Running test...</div>
                    <button id="reload-apexcharts" class="btn btn-primary mt-2">Reload ApexCharts</button>
                </div>
                
                <div class="test-container">
                    <h2>2. DOM Element Test</h2>
                    <p>Checking if chart container is accessible...</p>
                    <div id="chart-container" style="height: 200px;"></div>
                    <div id="dom-test-result" class="test-result">Running test...</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="test-container">
                    <h2>3. Full Chart Creation Test</h2>
                    <p>Attempting to create and render a chart...</p>
                    <div id="test-chart" style="height: 200px;"></div>
                    <div id="chart-test-result" class="test-result">Running test...</div>
                    <div class="btn-group mt-2">
                        <button id="reload-chart" class="btn btn-primary">Reload Chart</button>
                        <button id="update-chart" class="btn btn-secondary">Update Data</button>
                    </div>
                </div>
                
                <div class="test-container">
                    <h2>4. Console Log</h2>
                    <div id="log-container" class="log-container"></div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h2>Browser Information:</h2>
            <div id="browser-info"></div>
        </div>
        
        <div class="mt-4">
            <a href="test-chart.html" class="btn btn-outline-primary">Go to Standalone Test Page</a>
            <a href="<?php echo url('/dashboard'); ?>" class="btn btn-outline-secondary">Go to Dashboard</a>
        </div>
    </div>
    
    <!-- First try loading from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js"></script>
    
    <script>
        // Helper function to log messages
        function log(message) {
            const logContainer = document.getElementById('log-container');
            const logItem = document.createElement('div');
            logItem.innerHTML = `<span style="color:#888;">${new Date().toLocaleTimeString()}:</span> ${message}`;
            logContainer.appendChild(logItem);
            logContainer.scrollTop = logContainer.scrollHeight;
            console.log(message);
        }
        
        // Display browser information
        function showBrowserInfo() {
            const browserInfo = document.getElementById('browser-info');
            browserInfo.innerHTML = `
                <ul>
                    <li><strong>User Agent:</strong> ${navigator.userAgent}</li>
                    <li><strong>Browser:</strong> ${navigator.appName}</li>
                    <li><strong>Browser Version:</strong> ${navigator.appVersion}</li>
                    <li><strong>Cookies Enabled:</strong> ${navigator.cookieEnabled}</li>
                    <li><strong>Platform:</strong> ${navigator.platform}</li>
                    <li><strong>Screen Resolution:</strong> ${window.screen.width} x ${window.screen.height}</li>
                    <li><strong>Viewport:</strong> ${window.innerWidth} x ${window.innerHeight}</li>
                </ul>
            `;
        }
        
        // Test 1: ApexCharts Availability
        function testApexChartsAvailability() {
            const resultElement = document.getElementById('apexcharts-test-result');
            
            try {
                if (typeof ApexCharts === 'undefined') {
                    resultElement.className = 'test-result failure';
                    resultElement.innerHTML = 'FAILED: ApexCharts is not defined. Library may not be loaded.';
                    log('❌ FAILED: ApexCharts not defined');
                    return false;
                } else {
                    resultElement.className = 'test-result success';
                    resultElement.innerHTML = 'SUCCESS: ApexCharts is loaded (version: ' + ApexCharts.version + ')';
                    log('✅ SUCCESS: ApexCharts loaded - version ' + ApexCharts.version);
                    return true;
                }
            } catch (error) {
                resultElement.className = 'test-result failure';
                resultElement.innerHTML = 'ERROR: ' + error.message;
                log('❌ ERROR testing ApexCharts: ' + error.message);
                return false;
            }
        }
        
        // Test 2: DOM Element Test
        function testDOMElement() {
            const resultElement = document.getElementById('dom-test-result');
            const chartContainer = document.getElementById('chart-container');
            
            try {
                if (!chartContainer) {
                    resultElement.className = 'test-result failure';
                    resultElement.innerHTML = 'FAILED: Chart container not found';
                    log('❌ FAILED: Chart container not found');
                    return false;
                } else {
                    resultElement.className = 'test-result success';
                    resultElement.innerHTML = 'SUCCESS: Chart container found and accessible';
                    log('✅ SUCCESS: Chart container accessible');
                    return true;
                }
            } catch (error) {
                resultElement.className = 'test-result failure';
                resultElement.innerHTML = 'ERROR: ' + error.message;
                log('❌ ERROR testing DOM: ' + error.message);
                return false;
            }
        }
        
        // Test 3: Full Chart Creation
        function testChartCreation() {
            const resultElement = document.getElementById('chart-test-result');
            const chartElement = document.getElementById('test-chart');
            
            try {
                // First check prerequisites
                if (typeof ApexCharts === 'undefined') {
                    resultElement.className = 'test-result failure';
                    resultElement.innerHTML = 'FAILED: ApexCharts not available';
                    log('❌ FAILED: Cannot create chart - ApexCharts not loaded');
                    return false;
                }
                
                if (!chartElement) {
                    resultElement.className = 'test-result failure';
                    resultElement.innerHTML = 'FAILED: Chart container not found';
                    log('❌ FAILED: Cannot create chart - container not found');
                    return false;
                }
                
                // Create a simple chart
                const options = {
                    series: [{
                        name: 'Test Data',
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                    }],
                    chart: {
                        height: 200,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ['#f58cba'],
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
                    }
                };
                
                log('Attempting to create test chart...');
                window.testChart = new ApexCharts(chartElement, options);
                window.testChart.render();
                
                resultElement.className = 'test-result success';
                resultElement.innerHTML = 'SUCCESS: Chart created and rendered';
                log('✅ SUCCESS: Test chart created and rendered');
                return true;
                
            } catch (error) {
                resultElement.className = 'test-result failure';
                resultElement.innerHTML = 'ERROR: ' + error.message;
                log('❌ ERROR creating chart: ' + error.message);
                if (error.stack) {
                    log('Stack: ' + error.stack);
                }
                return false;
            }
        }
        
        // Run all tests
        function runAllTests() {
            log('Running all tests...');
            const test1 = testApexChartsAvailability();
            const test2 = testDOMElement();
            
            if (test1 && test2) {
                testChartCreation();
            }
        }
        
        // Update the test chart with new data
        function updateTestChart() {
            if (window.testChart) {
                try {
                    log('Updating test chart with new data...');
                    window.testChart.updateOptions({
                        series: [{
                            name: 'Test Data',
                            data: Array.from({length: 9}, () => Math.floor(Math.random() * 100) + 10)
                        }]
                    });
                    log('✅ Chart updated successfully');
                } catch (error) {
                    log('❌ ERROR updating chart: ' + error.message);
                }
            } else {
                log('Cannot update chart - no chart instance found');
            }
        }
        
        // Reload ApexCharts
        function reloadApexCharts() {
            log('Attempting to reload ApexCharts...');
            
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js';
            script.onload = function() {
                log('✅ ApexCharts reloaded successfully');
                testApexChartsAvailability();
            };
            script.onerror = function() {
                log('❌ Failed to reload ApexCharts');
            };
            
            document.head.appendChild(script);
        }
        
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            log('Page loaded, running tests...');
            showBrowserInfo();
            runAllTests();
            
            // Setup event listeners
            document.getElementById('reload-apexcharts').addEventListener('click', reloadApexCharts);
            document.getElementById('reload-chart').addEventListener('click', function() {
                if (window.testChart) {
                    window.testChart.destroy();
                }
                testChartCreation();
            });
            document.getElementById('update-chart').addEventListener('click', updateTestChart);
        });
    </script>
</body>
</html> 