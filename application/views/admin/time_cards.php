<div class="content-wrapper timecards">
   <div class="manual-entry-container">
         <h3>Time Cards</h3>
     <div class="filter-controls" id="filterControls">
        <div class="row">
            <!-- Employee Select -->
            <div class="col-lg-3">
                <label for="employeeSelect" class="control-label" >Employee</label>
                <select id="employeeSelect" class="form-control single_select">
                </select>
            </div>

     
                <div class="col-lg-3"> 
    <label class="control-label">Date</label>
    <?php
    $today = date('Y-m-d');
    $min_date = '';
    $help_text = '';
    
    if (is_pack_trial()) {
        $min_date = date('Y-m-d', strtotime('-1 day'));
        $help_text = 'Trial plan only allows selecting today or yesterday\'s date.';
    } elseif (is_plan_basic()) {
        $min_date = date('Y-m-d', strtotime('-7 days'));
        $help_text = 'Basic Package allows selecting dates from the last 7 days only.';
    } elseif (is_plan_standard()) {
        $min_date = date('Y-m-d', strtotime('-1 month'));
        $help_text = 'Standard plan allows selecting dates from the last one month only.';
    }
    ?>
    
    <input type="date" id="datePicker" class="form-control" 
           value="<?= !empty($min_date) ? $today : '' ?>"
           <?= !empty($min_date) ? "min='$min_date' max='$today'" : '' ?>
           onfocus="this.showPicker()">
           
    <?php if (!empty($help_text)): ?>
        <small class="text-muted"><?= $help_text ?></small>
    <?php endif; ?>
</div>

                <!-- From Time -->
                <div class="col-lg-3">
                    <label for="fromTime" class="control-label">From</label>
                    <select id="fromTime" class="form-control single_select">
                        <option value="00:00">00:00</option>
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
                        <option value="21:00">21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                    </select>
                </div>

                <!-- To Time -->
                <div class="col-lg-3">
                    <label for="toTime" class="control-label">To</label>
                    <select id="toTime" class="form-control single_select">
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
                        <option value="21:00">21:00</option>
                        <option value="22:00">22:00</option>
                        <option value="23:00">23:00</option>
                        <option value="23:59">23:59</option>
                    </select>
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

    <div class="btn-group mb-3 hide" role="group" aria-label="Hour Range Buttons" id="hourButtons">
        <button type="button" class="btn btn-primary m-5" data-hours="8">Last 8 Hours</button>
        <button type="button" class="btn btn-primary m-5" data-hours="6">Last 6 Hours</button>
        <button type="button" class="btn btn-primary m-5" data-hours="4">Last 4 Hours</button>
        <button type="button" class="btn btn-primary m-5" data-hours="2">Last 2 Hours</button>
        <button type="button" class="btn btn-primary m-5 active" data-hours="1">Last 1 Hour</button>
    </div>

    <div class="chart-container">
        <div id="activityChart"></div>
        <div id="noDataMessage">
            <i class="fas fa-chart-line" style="font-size: 24px; margin-bottom: 10px;"></i>
            <p>No activity data available for the selected time range.</p>
            <p>Please try a different time range.</p>
        </div>
    </div>
    </div>
    <script>
        let chart;
        let debounceTimer;

        $(document).ready(function() {
            $('#fromTime').val('');
            $('#toTime').val('');

            const today = new Date().toISOString().split('T')[0];
            $('#datePicker').val(today);

            $.ajax({
                url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    let employeeSelect = $('#employeeSelect');
                    if (response.status === "success" && response.employees.length > 0) {
                        response.employees.forEach(emp => {
                            employeeSelect.append(`<option value="${emp.id}">${emp.name} (${emp.email})</option>`);
                        });

                        // Select a random employee
                        const randomIndex = Math.floor(Math.random() * response.employees.length);
                        const randomEmployee = response.employees[randomIndex];
                        employeeSelect.val(randomEmployee.id);
                        $('#employeeName').text(`${randomEmployee.name} (${randomEmployee.email})`);
                        fetchActivityData(null, null, randomEmployee.id); // ✅ Pass random employee to fetch data
                    } else {
                        employeeSelect.empty().append(`<option value="">-- No employees found --</option>`);
                    }
                },
                error: function() {
                    $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
                }
            });
        
        $('#employeeSelect, #datePicker').change(function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchActivityData();
            }, 300);

            if (chart) {
                chart.destroy();
                chart = null;
            }
        });

        $('#fromTime, #toTime').change(function() {
            const fromTime = $('#fromTime').val();
            const toTime = $('#toTime').val();

            // Remove active class from hour buttons when custom time is used
            $('#hourButtons button').removeClass('active');

            if (fromTime && toTime) {
                fetchActivityData(fromTime, toTime);
            }
        });

        $('#hourButtons button').on('click', function() {
            $('#hourButtons button').removeClass('active');
            $(this).addClass('active');

            $('#fromTime').val('');
            $('#toTime').val('');

            const hours = parseInt($(this).data('hours'));
            const now = new Date();
            const from = new Date(now.getTime() - (hours * 60 * 60 * 1000));

            const formatTime = (dateObj) => {
                return dateObj.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
            };

            const fromTime = formatTime(from);
            const toTime = formatTime(now);

            if (chart) {
                chart.destroy();
                chart = null;
            }

            fetchActivityData(fromTime, toTime);
        });

        function fetchActivityData(fromTime = null, toTime = null, employeeId = null) {
    const employee = employeeId || $('#employeeSelect').val();
    const date = $('#datePicker').val();

    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);

    const formatTime = (dateObj) => {
        return dateObj.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    };

    // Handle full day vs hourly range
    if (selectedDate < today) {
        if (!fromTime || !toTime) {
            fromTime = "09:00";
            toTime = "13:00";
        }
        $('#hourButtons').hide();
    } else {
        $('#hourButtons').show();
        if (!fromTime || !toTime) {
            const now = new Date();
            const defaultFrom = new Date(now.getTime() - (60 * 60 * 1000));
            fromTime = formatTime(defaultFrom);
            toTime = formatTime(now);
        }
    }

    console.log(`Fetching from ${fromTime} to ${toTime}`);

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

                // Create a complete time series for the selected range
                const [fromHours, fromMinutes] = fromTime.split(':').map(Number);
                const [toHours, toMinutes] = toTime.split(':').map(Number);
                
                const startDate = new Date(selectedDate);
                startDate.setHours(fromHours, fromMinutes, 0, 0);
                
                const endDate = new Date(selectedDate);
                endDate.setHours(toHours, toMinutes, 0, 0);
                
                // Generate all possible time points (every minute)
                const allTimePoints = [];
                let currentTime = new Date(startDate);
                
                while (currentTime <= endDate) {
                    allTimePoints.push(new Date(currentTime));
                    currentTime.setMinutes(currentTime.getMinutes() + 1);
                }
                
                // Create a map of existing data points for quick lookup
                const dataMap = {};
                response.data.forEach(item => {
                    if (item.created_at) {
                        const dateObj = new Date(item.created_at);
                        const timeKey = dateObj.toLocaleTimeString([], {
                            hour: '2-digit', 
                            minute: '2-digit',
                            hour12: false
                        });
                        dataMap[timeKey] = {
                            mouse: parseInt(item.total_mouse_movement || 0),
                            keys: parseInt(item.total_keystrokes || 0),
                            timestamp: dateObj.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: false
                            })
                        };
                    }
                });
                
                // Build complete datasets with 0 values for missing times
                const labels = [];
                const mouseMovements = [];
                const keystrokes = [];
                const timestamps = [];
                
                allTimePoints.forEach(time => {
                    const timeKey = time.toLocaleTimeString([], {
                        hour: '2-digit', 
                        minute: '2-digit',
                        hour12: false
                    });
                    
                    labels.push(timeKey);
                    
                    if (dataMap[timeKey]) {
                        mouseMovements.push(dataMap[timeKey].mouse);
                        keystrokes.push(dataMap[timeKey].keys);
                        timestamps.push(dataMap[timeKey].timestamp);
                    } else {
                        mouseMovements.push(0);
                        keystrokes.push(0);
                        timestamps.push(timeKey + ':00'); // Add seconds for consistency
                    }
                });

                if (labels.length > 0) {
                    renderAreaChart(labels, mouseMovements, keystrokes, timestamps);
                } else {
                    showNoDataMessage();
                }
            } else {
                showNoDataMessage();
                $('#totalKeystrokes').text('0');
                $('#totalMouseMovement').text('0');
            }
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            showNoDataMessage();
            $('#hourButtons').hide();
            console.error('Error fetching employee activity data:', error);
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

    if (chart) {
        chart.destroy();
    }

    const date = $('#datePicker').val();
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
            type: 'area', // Changed back to area chart
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
            },
            stacked: false
        },
        colors: ['#4e73df', '#1cc88a'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight', // Keep straight lines for clear gaps
            width: 2
        },
        fill: {
            type: 'gradient', // Restore gradient fill
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
        },
        markers: {
            size: 0, // Remove markers completely
            hover: {
                size: 0
            }
        }
    };

    chart = new ApexCharts(document.querySelector("#activityChart"), options);
    chart.render();
}
        });
    </script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</div>