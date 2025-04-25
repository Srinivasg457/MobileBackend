<style>
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

    .log-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;

    }

    .tableDiv {
        background-color: white;
        min-height: 100vh;
        padding: 10px;
    }

    th,
    td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    th {
        background: #fafafa;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .btn-group {
        display: flex;
        gap: 30px;
        justify-content: end;
    }
</style>
<div id="toast-container" style="position: fixed;top: 0;"></div>
<div class="content-wrapper">
    <!-- Employee Dropdown and Filter Buttons -->
    <div class="row" style="margin: 25px auto;">
        <div class="col-lg-6">
            <select id="employee-select" class="form-control">
                <option value="">Select Employee</option>
            </select>
        </div>
        <!-- Filter Buttons -->
        <div class="col-lg-6">
            <div class="btn-group">
                <span class="m-10">Filters:</span>
                <button id="approved-btn" class="btn btn-success btn-sm m-5">Approved</button>
                <button id="unapproved-btn" class="btn btn-warning btn-sm m-5">Unapproved</button>
                <button id="cancel-btn" class="btn btn-secondary btn-sm m-5">Cancel Filter</button>
            </div>
        </div>
    </div>

    <hr>
    <!-- Timecard Table -->
    <div class="tableDiv box">
        <table class="table table-bordered log-table">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="log-data">
            </tbody>
        </table>
    </div>
</div>

<script>
    function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
    }
    // Approve or Decline a timecard
    function updateApproval(manualId, status, employeeId, userId) {
        $.ajax({
            url: '<?= base_url("employee/Timecards_manual/approve_timecard") ?>',
            method: 'POST',
            data: {
                manual_id: manualId,
                status: status
            },
            success: function(response) {
                showToast(response, "success");
                // After approval, reload that employee's timecard data
                loadTimecards(employeeId, userId);
            },
            error: function() {
                showToast("Failed to update timecard approval.", "error");
            }
        });
    }

    // Function to load employee list
    function loadEmployees() {
        $.ajax({
            url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                let employeeSelect = $('#employee-select');
                employeeSelect.empty().append(`<option value="">-- Select --</option>`);

                if (response.status === "success" && response.employees.length > 0) {
                    response.employees.forEach(emp => {
                        employeeSelect.append(`
                            <option value="${emp.id}" data-user-id="${response.user_id}">
                                ${emp.name} (${emp.email})
                            </option>
                        `);
                    });
                } else {
                    showToast("No employees found.", "error");
                }
            },
            error: function() {
                console.error("Failed to fetch employees.");
            }
        });
    }

    // Function to load timecards for selected employee
    // Function to load timecards for selected employee
    function loadTimecards(empId, userId, statusFilter = '') {
        if (!empId || !userId) return;

        $.ajax({
            url: '<?= base_url("employee/Timecards_manual/get_timecards") ?>',
            method: 'GET',
            data: {
                employee_id: empId,
                employee_org_id: userId,
                approved: statusFilter
            },
            dataType: 'json',
            success: function(timecards) {
                let html = '';
                if (timecards.length === 0) {
                    // No timecards found for the employee
                    html = `
                    <tr>
                        <td colspan="7">No requests found</td>
                    </tr>
                `;
                } else {
                    timecards.forEach((row, i) => {
                        const duration = (row.timestamp_start && row.timestamp_end) ?
                            Math.floor((new Date(`1970-01-01T${row.timestamp_end}`) - new Date(`1970-01-01T${row.timestamp_start}`)) / 60000) + ' mins' :
                            'N/A';

                        let actionBtns = '';
                        if (row.approved != 1) {
                            actionBtns = `
                            <button class="btn btn-success btn-sm" onclick="updateApproval(${row.manual_id}, 'approved', ${empId}, ${userId})">Approve</button>
                            <button class="btn btn-danger btn-sm" onclick="updateApproval(${row.manual_id}, 'unapproved', ${empId}, ${userId})">Decline</button>
                        `;
                        }

                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${row.timestamp_start}</td>
                            <td>${row.timestamp_end}</td>
                            <td>${duration}</td>
                            <td>${row.reason || ''}</td>
                            <td class="${row.approved == 1 ? 'text-success' : 'text-warning'}">
                                ${row.approved == 1 ? 'Approved' : 'Pending'}
                            </td>
                            <td>${actionBtns}</td>
                        </tr>
                    `;
                    });
                }
                $('#log-data').html(html);
            },
            error: function() {
                console.error("Failed to fetch timecards.");
            }
        });
    }


    // On employee selection change
    $(document).ready(function() {
        loadEmployees();

        $('#employee-select').on('change', function() {
            const empId = $(this).val();
            const userId = $(this).find('option:selected').data('user-id');
            loadTimecards(empId, userId);
        });

        // Filter Approved
        $('#approved-btn').on('click', function() {
            const empId = $('#employee-select').val();
            const userId = $('#employee-select').find('option:selected').data('user-id');
            loadTimecards(empId, userId, 'approved');
        });

        // Filter Unapproved
        $('#unapproved-btn').on('click', function() {
            const empId = $('#employee-select').val();
            const userId = $('#employee-select').find('option:selected').data('user-id');
            loadTimecards(empId, userId, 'unapproved');
        });

        // Cancel Filter
        $('#cancel-btn').on('click', function() {
            const empId = $('#employee-select').val();
            const userId = $('#employee-select').find('option:selected').data('user-id');
            loadTimecards(empId, userId); // Reset to load all timecards without filter
        });
    });
</script>