<div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        #activityChart {
            max-width: 100%;
            height: 400px;
            margin-top: 20px;
        }

        .chart-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            gap: 10px;
        }

        .chart-actions button {
            padding: 6px 12px;
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .chart-actions button:hover {
            background-color: #2e59d9;
        }

        .filter-controls {
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 120px;
            background: white;
        }

        #noDataMessage {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            display: none;
            font-size: 16px;
            border: 1px dashed #ddd;
            border-radius: 5px;
            margin-top: 20px;
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #ddd;
        }

        .loading-indicator {
            display: none;
            text-align: center;
            padding: 10px;
            color: #4e73df;
        }

        .apexcharts-tooltip-custom {
            padding: 5px 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .apexcharts-tooltip-custom div {
            margin: 3px 0;
        }

        .totals-display {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #ddd;
        }

        .total-box {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .total-box h3 {
            margin-top: 0;
            color: #5a5c69;
            font-size: 16px;
        }

        .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #1cc88a;
        }

        .total-box:nth-child(2) .total-value {
            color: #4e73df;
        }
    </style>

    <h2>Employee Activity Report</h2>


    <div class="filter-controls" style="font-family: 'Segoe UI', Arial, sans-serif; margin: 20px auto; padding: 20px; border-radius: 8px; background: #ffffff; box-shadow: 0 1px 5px rgba(0,0,0,0.1);  width: 100%;">
        <div style="display: flex; flex-wrap: wrap; gap: 130px; align-items: center; justify-content: space-between;">
            <!-- Employee Select -->
            <div class="filter-group" style="flex: 1; min-width: 250px; max-width: 300px;">
                <label for="employeeSelect" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Employee</label>
                <select id="employeeSelect" style="width: 130%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: white; font-size: 14px; color: #2d3748; transition: all 0.3s; outline: none; border:1px solid lightgrey" onfocus="this.style.borderColor='#4299e1'">
                    <option value="all" selected>All Employees</option>
                    <option value="emp1">John Smith</option>
                    <option value="emp2">Sarah Johnson</option>
                    <option value="emp3">Michael Brown</option>
                    <option value="emp4">Emily Davis</option>
                    <option value="emp5">David Wilson</option>
                </select>
            </div>

            <!-- Date and Time Filters -->
            <div style="display: flex; gap: 20px; flex: 2; flex-wrap: wrap; justify-content: space-between; min-width: 300px;">
                <!-- Date Picker -->
                <div class="filter-group" style="flex: 1; min-width: 180px;">
                    <label for="datePicker" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Date</label>
                    <input type="date" id="datePicker" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: white; font-size: 14px; color: #2d3748; transition: all 0.3s; outline: none;border:1px solid lightgrey" onfocus="this.style.borderColor='#4299e1'">
                </div>

                <!-- From Time -->
                <div class="filter-group" style="flex: 0.8; min-width: 120px;">
                    <label for="fromTime" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">From</label>
                    <select id="fromTime" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: white; font-size: 14px; color: #2d3748; transition: all 0.3s; outline: none;border:1px solid lightgrey" onfocus="this.style.borderColor='#4299e1'">
                        <option value="00:00">00:00</option>
                        <option value="01:00">01:00</option>
                        <option value="02:00">02:00</option>
                        <option value="03:00">03:00</option>
                        <option value="04:00">04:00</option>
                        <option value="05:00">05:00</option>
                        <option value="06:00">06:00</option>
                        <option value="07:00">07:00</option>
                        <option value="08:00" selected>08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00">21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                    </select>
                </div>

                <!-- To Time -->
                <div class="filter-group" style="flex: 0.8; min-width: 120px;">
                    <label for="toTime" style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">To</label>
                    <select id="toTime" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: white; font-size: 14px; color: #2d3748; transition: all 0.3s; outline: none;border:1px solid lightgrey" onfocus="this.style.borderColor='#4299e1'">
                        <option value="01:00">01:00</option>
                        <option value="02:00">02:00</option>
                        <option value="03:00">03:00</option>
                        <option value="04:00">04:00</option>
                        <option value="05:00">05:00</option>
                        <option value="06:00">06:00</option>
                        <option value="07:00">07:00</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                        <option value="21:00" selected>21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                        <option value="23:59">23:59</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="totals-display">

        <div class="total-box">
            <h3>Total Mouse Movement</h3>
            <div id="totalMouseMovement" class="total-value">0</div>
        </div>
        <div class="total-box">
            <h3>Total Keystrokes</h3>
            <div id="totalKeystrokes" class="total-value">0</div>
        </div>
    </div>

    <div class="loading-indicator" id="loadingIndicator">
        <i class="fas fa-spinner fa-spin"></i> Loading data...
    </div>

    <div class="chart-container">
        <div id="activityChart"></div>
        <div id="noDataMessage">
            <i class="fas fa-chart-line" style="font-size: 24px; margin-bottom: 10px;"></i>
            <p>No activity data available for the selected time range.</p>
            <p>Please try a different time range.</p>
        </div>
    </div>

    <script>
    let chart;
    let debounceTimer;

    $(document).ready(function() {
            // Set default date to today            const today = new Date().toISOString().split('T')[0];
            $('#datePicker').val(today);

            $.ajax({
                url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    let employeeSelect = $('#employeeSelect');
                    employeeSelect.empty().append(`<option value="">-- Select Employee --</option>`);

                    if (response.status === "success" && response.employees.length > 0) {
                        response.employees.forEach(emp => {
                            employeeSelect.append(`<option value="${emp.id}">${emp.name} (${emp.email})</option>`);
                        });
                    } else {
                        showToast("No employees found for this user.", "error");
                    }
                },
                error: function() {
                    showToast("Failed to fetch employees.", "error");
                }
            });

            fetchActivityData();

            $('#employeeSelect, #datePicker, #fromTime, #toTime').change(function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchActivityData, 300);
                if (chart) {
                    chart.destroy();
                    chart = null;
                }
            });
        });

        function fetchActivityData() {
            const employee = $('#employeeSelect').val();
            const date = $('#datePicker').val();
            const fromTime = $('#fromTime').val();
            const toTime = $('#toTime').val();

            $('#activityChart').hide();
            $('#noDataMessage').hide();
            $('#loadingIndicator').show();

            $.ajax({
                url: "<?= base_url('admin/Activity_logs/get_employee_activity'); ?>",
                method: 'GET',
                dataType: 'json',
                data: {
                    date: date,
                    from_time: fromTime,
                    to_time: toTime,
                    employee_id: employee
                },
                success: function(response) {
                    $('#loadingIndicator').hide();

                    if (response.status && response.data && response.data.length > 0) {
                        $('#noDataMessage').hide();

                        $('#totalKeystrokes').text(response.totals.keystrokes);
                        $('#totalMouseMovement').text(response.totals.mouse_movement);

                        const labels = [];
                        const mouseMovements = [];
                        const keystrokes = [];
                        const timestamps = [];

                        response.data.forEach(item => {
                            try {
                                if (!item.created_at) return;

                                const dateObj = new Date(item.created_at);
                                const time = dateObj.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });

                                if (time) {
                                    labels.push(time);
                                    mouseMovements.push(parseInt(item.total_mouse_movement || 0));
                                    keystrokes.push(parseInt(item.total_keystrokes || 0));
                                    timestamps.push(dateObj.toLocaleTimeString([], {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        hour12: false
                                    }));
                                }
                            } catch (e) {
                                console.error('Error processing data item:', e, item);
                            }
                        });


                        if (labels.length > 0) {
                            renderAreaChart(labels, mouseMovements, keystrokes, timestamps);
                        } else {
                            showNoDataMessage();
                        }
                    } else {
                        showNoDataMessage();
                        // Reset totals when no data
                        $('#totalKeystrokes').text('0');
                        $('#totalMouseMovement').text('0');
                    }
                },
                error: function(xhr, status, error) {
                    $('#loadingIndicator').hide();
                    showNoDataMessage();
                    console.error('Error fetching employee activity data:', error);
                    // Reset totals on error
                    $('#totalKeystrokes').text('0');
                    $('#totalMouseMovement').text('0');
                }
            });
        }

        function showNoDataMessage() {
            $('#activityChart').hide();
            $('#noDataMessage').show();
            if (chart) {
                chart.destroy();
                chart = null;
            }
        }

        function renderAreaChart(labels, mouseData, keyData, timestamps) {
            $('#activityChart').show();

            // Destroy existing chart if it exists
            if (chart) {
                chart.destroy();
            }

            const date = $('#datePicker').val();

            // Format date for display
            const displayDate = new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            var options = {
                series: [{
                    name: 'Mouse Movement',
                    data: mouseData
                }, {
                    name: 'Keystrokes',
                    data: keyData
                }],
                chart: {
                    height: 400,
                    type: 'area',
                    toolbar: {
                        show: true,
                        tools: {
                            download: false
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                colors: ['#4e73df', '#1cc88a'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 100]
                    }
                },
                legend: {
                    position: 'top'
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        formatter: function(value) {
                            // Return the time value as is (already formatted)
                            return value;
                        },
                        style: {
                            colors: '#6c757d'
                        },
                        rotate: 0,
                        hideOverlappingLabels: true
                    },
                    title: {
                        text: 'Time (24-hour format)'
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                yaxis: {
                    min: 0,
                    title: {
                        text: 'Activity Count'
                    },
                    labels: {
                        style: {
                            colors: '#6c757d'
                        }
                    }
                },
                title: {
                    text: `Employee Activity (${displayDate} ${labels[0]} - ${labels[labels.length-1]})`,
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        color: '#5a5c69'
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const timestamp = timestamps[dataPointIndex];
                        return `<div class="apexcharts-tooltip-custom">
                    <div><strong>Time:</strong> ${timestamp}</div>
                    <div><strong style="color:#4E73DF">Mouse Movement:</strong> ${series[0][dataPointIndex]}</div>
                    <div><strong style="color:#1CC88A">Keystrokes:</strong> ${series[1][dataPointIndex]}</div>
                </div>`;
                    }
                },
                grid: {
                    borderColor: '#e3e6f0',
                    strokeDashArray: 3
                }
            };

            chart = new ApexCharts(document.querySelector("#activityChart"), options);
            chart.render();
        }
    </script>
</div>