<div class="content-wrapper">
<div>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5d5dff;
            --secondary-color: #66ccff;
            --dark-color: #1a237e;
            --text-dark: #333333;
            --text-muted: #6c757d;
            --background-color: #f0f2f5;
            --card-background: #ffffff;
            --border-color: #e9ecef;
        }

     
        .top-apps-card {
            background-color: var(--card-background);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .header-left h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .header-left p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        .total-time-badge {
            background-color: var(--dark-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
        }

        .app-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .app-item:last-child {
            border-bottom: none;
        }

        .app-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .app-name {
            font-weight: 500;
            font-size: 1.1rem;
        }

        .app-time {
            font-weight: 600;
            color: var(--primary-color);
        }

        .progress-container {
            height: 8px;
            background-color: var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-bar {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .app-percentage {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
    </style>

 <?php
$dailyBreakdown = $response_data['data']['daily_breakdown'] ?? [];

// Function: Convert "HH:MM:SS" to seconds
function timeToSeconds($timeStr) {
    list($h, $m, $s) = explode(':', $timeStr);
    return ($h * 3600) + ($m * 60) + $s;
}

// Function: Format seconds as "Hh Mm"
function formatTime($seconds) {
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    return ($h > 0 ? "{$h}h " : "") . "{$m}m";
}
?>

<div class="container">

    <?php foreach ($dailyBreakdown as $date => $apps): ?>
        <?php
        // Calculate total seconds for the date
        $totalTimeSeconds = 0;
        foreach ($apps as $timeStr) {
            $totalTimeSeconds += timeToSeconds($timeStr);
        }
        ?>
           <div class="row mb-5 reprt-box">
                    <div class="form-group col-lg-4 my-3"><label class="control-label">Employee </label>
                        <select id="employeeSelect" class="form-control single_select"></select>
                    </div>
                    <div class="form-group col-lg-4 my-3"></div>
                    <div class="form-group col-lg-4 my-3">
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
                </div>

        <div class="top-apps-card mb-4">
           
            <div class="header">
                <div class="header-left">
                    <h4>Top Applications</h4>
                    <p class="text-muted"><?= htmlspecialchars($date); ?> — By total time</p>
                </div>
                <div class="total-time-badge">
                    <?= gmdate("H:i:s", $totalTimeSeconds); ?>
                </div>
            </div>

            <?php foreach ($apps as $appName => $timeStr): ?>
                <?php
                $appSeconds = timeToSeconds($timeStr);
                $percentage = $totalTimeSeconds > 0 ? ($appSeconds / $totalTimeSeconds) * 100 : 0;
                ?>
                <div class="app-item">
                    <div class="app-details">
                        <div class="app-name"><?= htmlspecialchars($appName); ?></div>
                        <div class="app-time"><?= formatTime($appSeconds); ?></div>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?= number_format($percentage, 1); ?>%;"></div>
                    </div>
                    <div class="app-percentage"><?= number_format($percentage, 1); ?>% of total</div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

</div>
</div>
   <script>
        $(document).ready(function() {
            // First, immediately disable the date picker while we check payment status
            const datePicker = $('#datePicker');
            datePicker.prop('disabled', true);

            // Fetch user's payment details
            $.ajax({
                url: "<?= base_url('/admin/ScreenshotController/getPaymentDetails'); ?>",
                method: 'GET',
                success: function(response) {
                    if (response.billing_type === 'week') {
                        setupWeeklyBillingRestrictions();
                    } else {
                        // Enable normally for other billing types
                        datePicker.prop('disabled', false);
                    }
                },
                error: function() {
                    console.log('Error fetching payment details');
                    datePicker.prop('disabled', false);
                }
            });

            function setupWeeklyBillingRestrictions() {
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);

                // Format dates as YYYY-MM-DD
                const todayStr = formatDate(today);
                const yesterdayStr = formatDate(yesterday);

                // Set the value to today
                datePicker.val(todayStr);

                // Create a custom dropdown with only two options
                datePicker.after(`
            <div class="weekly-date-options" style="margin-top: 5px;">
                <button class="btn btn-sm btn-outline-primary date-option ${datePicker.val() === todayStr ? 'active' : ''}" 
                        data-date="${todayStr}">Today (${formatDisplayDate(today)})</button>
                <button class="btn btn-sm btn-outline-primary date-option ${datePicker.val() === yesterdayStr ? 'active' : ''}" 
                        data-date="${yesterdayStr}">Yesterday (${formatDisplayDate(yesterday)})</button>
            </div>
        `);

                // Hide the original date picker
                datePicker.hide();

                // Handle custom button clicks
                $('.date-option').click(function() {
                    const selectedDate = $(this).data('date');
                    datePicker.val(selectedDate);
                    $('.date-option').removeClass('active');
                    $(this).addClass('active');
                    // Trigger any date change events you might have
                    datePicker.trigger('change');
                });
            }

            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }

            function formatDisplayDate(date) {
                const day = date.getDate();
                const month = date.getMonth() + 1;
                return `${day}/${month}/${date.getFullYear()}`;
            }
        });
    </script>
<script>
        $(document).ready(function() {
            // Get the user's date from the helper function
            const userDate = "<?= get_user_datetime_only($this->session->userdata('id')) ?>";
            const today = userDate.split(' ')[0]; // This splits date and time and takes the date part
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

                        const randomIndex = Math.floor(Math.random() * response.employees.length);
                        const randomEmployee = response.employees[randomIndex];
                        employeeSelect.val(randomEmployee.id);
                        $('#employeeName').text(`${randomEmployee.name} (${randomEmployee.email})`); // ✅ Set name on auto-load
                        let currentDate = $('#datePicker').val();
                        fetchActivity(randomEmployee.id, currentDate);
                    } else {
                        employeeSelect.empty().append(`<option value="">-- No employees found --</option>`);
                    }
                },
                error: function() {
                    $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
                }
            });

            function triggerFilter() {
                const employee = $('#employeeSelect').val();
                const date = $('#datePicker').val();
                if (!employee) {
                    showToast("Please select an employee.", "error");
                    $('#employeeSelect').focus(); // Optional: focus on the select box
                    return;
                }
                fetchActivity(employee, date)
            }
            $('#datePicker, #employeeSelect').on('change', triggerFilter);

        });
</script>