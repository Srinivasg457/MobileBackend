<div id="toast-container" style="position: fixed;top: 0;"></div>
<style>
    .status-approved {
        color: #155724;
        /* background-color: #d4edda; */
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        text-align: center;
    }

    .status-pending {
        color: #856404;
        /* background-color: #fff3cd;  */
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        text-align: center;
    }

    .toast {
        padding: 10px;
        margin: 5px;
        border-radius: 4px;
        color: #fff;
        min-width: 200px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .toast-success {
        background-color: #28a745;
    }

    .toast-error {
        background-color: #e74c3c;
    }

    .is-invalid {
        border: 2px solid #e74c3c;
        background-color: #fcebea;
    }

    #toast-container {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
    }

    .form-row.full-width {
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .textarea-full {
        width: 100%;
        box-sizing: border-box;
        resize: vertical;
        /* optional */
    }


    .manual-entry-container {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        margin: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    }

    h2,
    h3 {
        margin-bottom: 20px;
        font-weight: 600;
        color: #2c3e50;
    }

    .entry-header,
    .status-boxes,
    .manual-entry-form .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }

    label {
        flex: 15px;
        font-weight: 500;
        color: #2c3e50;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px 14px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
    }

    .status-box {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        flex: 1 1 220px;
        text-align: center;
        box-shadow: inset 0 0 5px #ddd;
    }

    .status-box strong {
        display: block;
        margin-top: 6px;
        font-size: 16px;
        font-weight: 600;
    }

    .timeline-table,
    .log-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    th {
        background: #f4f6f8;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .manual-button-wrap {
        text-align: right;
        margin-top: 10px;
    }

    .manual-add-btn {
        padding: 12px 24px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .manual-add-btn:hover {
        background: #2980b9;
    }

    /* Modal */
    .manual-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1000;
    }

    .manual-modal.show {
        display: flex;
    }

    .manual-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(2px);
    }

    .manual-modal-content {
        position: relative;
        margin: auto;
        z-index: 1;
        background: #fff;
        padding: 30px;
        border-radius: 14px;
        width: 600px;
        max-width: 95%;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
        animation: fadeInUp 0.3s ease-out;
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(40px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }

    .save-btn,
    .cancel-btn {
        padding: 10px 20px;
        font-weight: 500;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
    }

    .save-btn {
        background: #2ecc71;
        color: #fff;
    }

    .cancel-btn {
        background: #e74c3c;
        color: #fff;
    }

    @media (max-width: 768px) {

        .entry-header,
        .status-boxes,
        .manual-entry-form .form-row {
            flex-direction: column;
        }

        .manual-modal-content {
            width: 90%;
        }
    }

    .truncated-reason {
        cursor: pointer;
        position: relative;
    }

    .truncated-reason:hover::after {
        content: attr(data-fulltext);
        position: absolute;
        left: 0;
        top: 100%;
        z-index: 1000;
        background-color: #000;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        width: 700px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* .container {
        background-color: white;
        border-radius: 4px;
        padding: 40px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        width: auto;
        border: 1px solid #ddd;
    } */

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .view-options {
        font-size: 14px;
        color: #666;
    }

    .legend {
        display: flex;
        gap: 15px;
        font-size: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        color: #555;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        margin-right: 5px;
        border-radius: 2px;
    }

    .timeline-container {
        position: relative;
        height: 50px;
        border: 1px solid #ccc;
        margin-top: 20px;
    }



    .time-labels {
        margin-bottom: 5px;
        font-weight: bold;
    }

    #timeline-track {
        position: relative;
        height: 100%;
        background-color: #f5f5f5;
    }

    .time-marker {
        position: absolute;
        top: 0;
        height: 100%;
        width: 1px;
        background-color: #000;
        font-size: 12px;
        color: #333;
    }

    .time-marker::after {
        content: attr(data-time);
        position: absolute;
        top: 100%;
        left: -15px;
        margin-top: 6px;
        white-space: nowrap;
    }



    .timeline-yellow {
        background-color: #ffe066;
    }

    .timeline-lightgreen {
        background-color: #00FF00;
    }

    .timeline-red {
        background-color: red;
    }

    .activity-container {
        background-color: white;
        border-radius: 4px;
        padding: 40px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        width: auto;
        border: 1px solid #ddd;
    }

    .custom-tooltip {
        position: absolute;
        top: -40px;
        z-index: 9999;
        width: max-content;
        background-color: white;
        /* color: white; */
        padding: 5px;
        /* box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px; */
        border-radius: 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
            rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }


</style>

<style>
    
    /* Mobile Responsive Additions Only */
@media (max-width: 768px) {
    .manual-entry-container {
        padding: 15px;
    }

    .entry-header {
        gap: 12px;
        margin-bottom: 20px;
    }

    .status-boxes {
        gap: 12px;
    }

    .status-box {
        flex: 1 1 calc(50% - 12px);
        padding: 15px;
    }

    .activity-container,
    .container {
        padding: 20px;
    }

    .timeline-container {
        height: 40px;
    }

    .time-marker::after {
        font-size: 10px;
    }

    .legend {
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .manual-entry-container {
        padding: 12px;
    }

    .status-box {
        flex: 1 1 100%;
    }

    .timeline-container {
        height: 35px;
    }

    .time-marker:nth-child(odd)::after {
        content: '';
    }

    .custom-tooltip {
        font-size: 12px;
        max-width: 150px;
        white-space: normal;
    }

    /* Ensure dropdowns are touch-friendly */
    select {
        padding: 12px 14px;
    }
}
      .content-wrapper{
    height: unset !important;
    min-height: unset !important;
}

/* Timeline bar mobile optimization */
@media (max-width: 768px) {
    .activity-block {
        min-width: 2px !important;
    }

    #timeline-track {
        height: 100%;
    }
}
</style>
<div class="content-wrapper">
    <div class="manual-entry-container">
        <h2>Activity</h2>

        <div class="entry-header">
            <label>Employee
                <select id="employeeSelect" class="form-control"></select>
            </label>
            <label>Date
                <input type="date" id="datePicker" class="form-control" value="">
            </label>

        </div>

        <div class="status-boxes">
            <div class="status-box active">Active <strong id="active-time">00 hrs 00 min</strong></div>
            <div class="status-box inactive">Inactive <strong>00 hrs 00 min</strong></div>
            <div class="status-box manual">Manual <strong>0 hr 0 min</strong></div>
            <div class="status-box meeting">Meeting <strong>00:00</strong></div>
        </div>

        <h3>Activity</h3>
        <div class="activity-container">


            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color .timeline-lightgreen" style="background-color: #00FF00;"></div>
                    <span>Active</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color .timeline-yellow" style="background-color: #ffe066;"></div>
                    <span>Moderate Active</span>
                </div>
                <!-- <div class="legend-item">
            <div class="legend-color timeline-darkgreen"></div>
            <span>High Active</span>
        </div> -->
                <div class="legend-item">
                    <div class="legend-color timeline-red"></div>
                    <span>Inactive</span>
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
        function fetchActivity(currentEmployeeId, date) {
            const timelineTrack = $('#timeline-track');
            timelineTrack.find('.activity-block').remove(); // 🧹 Clear existing activity blocks

            $.ajax({
                url: "<?= base_url('/admin/Activity_logs/get_activity'); ?>",
                type: 'GET',
                dataType: 'json',
                data: {
                    employee_id: currentEmployeeId,
                    date
                },
                success: function(response) {
                    if (response.status && response.data.length > 0) {
                        const startHour = 8; // 08:00 AM
                        const endHour = 20; // 08:00 PM
                        const totalMinutes = (endHour - startHour) * 60;

                        response.data.forEach(function(item) {
                            const createdAt = new Date(item.created_at);
                            const hour = createdAt.getHours();
                            const minutes = createdAt.getMinutes();
                            const totalTimeInMinutes = (hour * 60 + minutes) - (startHour * 60);

                            // Skip if the time is outside 08:00 - 20:00
                            if (totalTimeInMinutes < 0 || totalTimeInMinutes > totalMinutes) return;

                            let blockColorClass = '';
                            if (item.is_active == '1') {
                                blockColorClass = 'timeline-yellow';
                            } else if (item.is_active == '2') {
                                blockColorClass = 'timeline-lightgreen';
                            } else if (item.is_active == '3') {
                                blockColorClass = 'timeline-darkgreen';
                            } else {
                                blockColorClass = 'timeline-red';
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
                                .hide(); // Initially hidden

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
                    } else {
                        showToast('No activity data found.', "error")
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('Failed to fetch activity data.');
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

                        // Update the second .status-box strong tag (Inactive)
                        $('.status-box.inactive strong').text(idleFormatted);
                    } else {
                        $('#active-time').text("00 hrs 00 min");
                        $('.status-box.inactive strong').text("00 hrs 00 min");
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
            const today = new Date().toISOString().split('T')[0];
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

        });
        $('#employeeSelect').on('change', function() {
            const employeeId = $(this).val();
            currentEmployeeId = employeeId;
            const date = $('#datePicker').val();
            fetchActivity(currentEmployeeId, date); // No need to manually clear here
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
        $('#datePicker').on('change', triggerFilter);
    </script>