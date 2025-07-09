    
<div class="content-wrapper activity_log">
    <div class="manual-entry-container">
        <h2>Activity Logs</h2>

        <div class="row mb-5 reprt-box">
            <div class="col-lg-4 mb-5"><label class="control-label">Employee </label>
                <select id="employeeSelect" class="form-control single_select"></select>
            </div>
            <div class="col-lg-4"></div>
            <div class="col-lg-4 mb-5"> 
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



        <div class="row box-payout-areas mt-30">
    <div class="col-md-3 col-sm-6 col-12 mb-1">
        <div class="info-box-pay border">
            <div class="info-box-content-pay">
                <span class="info-box-number-pay text-success" id="active-time"><strong>0 hr 0 min</strong></span>
                <span class="info-box-texts text-dark fs-13 text-capitalize">Active Time</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 col-12 mb-1">
        <div class="info-box-pay border">
            <div class="info-box-content-pay">
                <span class="info-box-number-pay text-success danger inactive"><strong>00 hrs 00 min</strong></span>
                <span class="info-box-texts text-dark fs-13 text-capitalize">Inactive Time</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12 mb-1">
        <div class="info-box-pay border">
            <div class="info-box-content-pay">
                <span class="info-box-number-pay text-success"><strong>0 hr 0 min</strong></span>
                <span class="info-box-texts text-dark fs-13 text-capitalize">Manual Time</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12 mb-1">
        <div class="info-box-pay border">
            <div class="info-box-content-pay">
                <span class="info-box-number-pay text-success"><strong>00:00</strong></span>
                <span class="info-box-texts text-dark fs-13 text-capitalize">Meeting Time</span>
            </div>
        </div>
    </div>
</div>

        <div class="mt-30" id="activity">

        <div class="activity-container">
    <h5>Activity Bar</h5>
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color bg-green"></div>
            <span>Active</span>
        </div>
        <div class="legend-item">
            <div class="legend-color bg-yellow"></div>
            <span>Moderate Active</span>
        </div>
        <div class="legend-item">
            <div class="legend-color box-danger"></div>
            <span>Inactive</span>
        </div>
    </div>
</div>


            <div class="timeline-container">
                <!-- <div class="time-row"> -->
                <div id="timeline-track">
                    <!-- Dynamic blocks will be added here -->

                    <!-- Static time markers (08:00 to 20:00) -->
                    <!-- Each hour = 100% / 12 = 8.33% -->
                    <div class="time-marker" style="left: 0%;" data-time="08:00"></div>
                    <div class="time-marker" style="left: 8.33%;" data-time="09:00"></div>
                    <div class="time-marker" style="left: 16.66%;" data-time="10:00"></div>
                    <div class="time-marker" style="left: 25%;" data-time="11:00"></div>
                    <div class="time-marker" style="left: 33.33%;" data-time="12:00"></div>
                    <div class="time-marker" style="left: 41.66%;" data-time="13:00"></div>
                    <div class="time-marker" style="left: 50%;" data-time="14:00"></div>
                    <div class="time-marker" style="left: 58.33%;" data-time="15:00"></div>
                    <div class="time-marker" style="left: 66.66%;" data-time="16:00"></div>
                    <div class="time-marker" style="left: 75%;" data-time="17:00"></div>
                    <div class="time-marker" style="left: 83.33%;" data-time="18:00"></div>
                    <div class="time-marker" style="left: 91.66%;" data-time="19:00"></div>
                    <div class="time-marker" style="left: 100%;" data-time="20:00"></div>
                </div>
                <!-- </div> -->
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
        function fetchActivity(currentEmployeeId, date) {
            const timelineTrack = $('#timeline-track');
timelineTrack.find('.activity-block, .time-marker').remove(); // Clear existing blocks and markers

$.ajax({
    url: "<?= base_url('/admin/Activity_logs/get_activity'); ?>",
    type: 'GET',
    dataType: 'json',
    data: {
        employee_id: currentEmployeeId,
        date
    },
    success: function(response) {
        // Default time range (8AM to 8PM)
        let startHour = 8;
        let endHour = 20;
        let hasActivities = false;

        if (response.status && response.data.length > 0) {
            // Sort activities by time to find first and last
            const sortedActivities = [...response.data].sort((a, b) => 
                new Date(a.created_at) - new Date(b.created_at)
            );
            
            const firstActivityTime = new Date(sortedActivities[0].created_at);
            const lastActivityTime = new Date(sortedActivities[sortedActivities.length - 1].created_at);
            
            // Calculate dynamic start and end times (with 1 hour buffer before first and 2 hours after last)
            startHour = Math.max(0, Math.min(23, firstActivityTime.getHours() - 1));
            endHour = Math.max(0, Math.min(23, lastActivityTime.getHours() + 2));
            
            hasActivities = true;
        }

        const totalHours = endHour - startHour;
        const totalMinutes = totalHours * 60;
        
        // Generate time markers
        for (let i = 0; i <= totalHours; i++) {
            const hour = startHour + i;
            const timeString = `${hour.toString().padStart(2, '0')}:00`;
            const positionPercent = (i / totalHours) * 100;
            
            $('<div></div>')
                .addClass('time-marker')
                .attr('data-time', timeString)
                .css('left', positionPercent + '%')
                .appendTo(timelineTrack);
        }
        
        if (hasActivities) {
            // Process each activity
            response.data.forEach(function(item) {
                const createdAt = new Date(item.created_at);
                const hour = createdAt.getHours();
                const minutes = createdAt.getMinutes();
                const totalTimeInMinutes = (hour * 60 + minutes) - (startHour * 60);

                // Skip if outside our dynamic range
                if (totalTimeInMinutes < 0 || totalTimeInMinutes > totalMinutes) return;

                let blockColorClass = '';
                if (item.is_active == '1') {
                    blockColorClass = 'bg-yellow';
                } else if (item.is_active == '2') {
                    blockColorClass = 'bg-green';
                } else if (item.is_active == '3') {
                    blockColorClass = 'bg-green';
                } else {
                    blockColorClass = 'bg-red';
                }

                const blockWidthPercent = (5 / totalMinutes) * 100;
                const leftPositionPercent = (totalTimeInMinutes / totalMinutes) * 100;

                // Calculate end time by adding 5 minutes
                const endAt = new Date(createdAt.getTime() + 5 * 60000);

                // Format time as HH:MM AM/PM
                const formatTime = date =>
                    date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                const timeLabel = `${formatTime(createdAt)} to ${formatTime(endAt)}`;
                const tooltip = $('<div></div>')
                    .addClass('custom-tooltip')
                    .text(timeLabel)
                    .hide();

                const block = $('<div></div>')
                    .addClass('activity-block')
                    .addClass(blockColorClass)
                    .css({
                        'position': 'absolute',
                        'left': leftPositionPercent + '%',
                        'width': blockWidthPercent + '%',
                        'height': '100%'
                    }).append(tooltip).hover(
                        function() {
                            tooltip.show();
                        },
                        function() {
                            tooltip.hide();
                        }
                    );

                timelineTrack.append(block);
            });
        }
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
        showToast('Failed to fetch activity data. Showing default time range.', "error");
        
        // Show default time range on error too
        const startHour = 8;
        const endHour = 20;
        const totalHours = endHour - startHour;
        
        for (let i = 0; i <= totalHours; i++) {
            const hour = startHour + i;
            const timeString = `${hour.toString().padStart(2, '0')}:00`;
            const positionPercent = (i / totalHours) * 100;
            
            $('<div></div>')
                .addClass('time-marker')
                .attr('data-time', timeString)
                .css('left', positionPercent + '%')
                .appendTo(timelineTrack);
        }
    }
});
            $.ajax({
                url: '<?= base_url("admin/Time_logs/get_time_logs") ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    employee_id: currentEmployeeId,
                    date
                },
                success: function(response) {
                    if (response.status && response.data.length > 0) {
                        const data = response.data[0];

                        // Active time
                        const activeParts = data.total_active_time.split(':');
                        const activeHours = parseInt(activeParts[0]);
                        const activeMinutes = parseInt(activeParts[1]);
                        const activeFormatted = `${activeHours.toString().padStart(2, '0')} hrs ${activeMinutes.toString().padStart(2, '0')} min`;
                        $('#active-time').text(activeFormatted);

                        // Inactive time
                        const idleParts = data.total_idle_time.split(':');
                        const idleHours = parseInt(idleParts[0]);
                        const idleMinutes = parseInt(idleParts[1]);
                        const idleFormatted = `${idleHours.toString().padStart(2, '0')} hrs ${idleMinutes.toString().padStart(2, '0')} min`;

                        $('.danger.inactive strong').text(idleFormatted);
                    } else {
                        $('#active-time').text("00 hrs 00 min");
                        $('.danger.inactive strong').text("00 hrs 00 min");
                    }
                },
                error: function() {
                    alert('Failed to load time log data.');
                }
            });
        }




        function showToast(message, type) {
            const toast = $(`<div class="toast toast-${type}">${message}</div>`);
            $('#toast-container').append(toast);
            setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
        }

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