<div id="toast-container"></div>

<div class="content-wrapper Time_Approval">
<div class="manual-entry-container">

    <h3>Time Approval</h3>
    <div class="row reprt-box" style="margin: 25px auto;">
    <div class="col-lg-4">
        Employee List
        <select id="employee-select" class="form-control single_select">
            <option value="">Select Employee</option>
        </select>
    </div>
<div class="col-lg-4 "></div>
    <div class="form-group col-lg-4">
    <label class="control-label">Sort By</label>
    <div class="input-group">
        <select class="form-control single_select" id="sortSelect">
            <option value="Filters">Filters</option>
            <option value="Approved">Approved</option>
            <option value="Unapproved">UnApproved</option>
        </select>

        <!-- Sort Icon -->
        <div class="input-group-addon border-0">
            <span id="sortIcon" style="cursor: pointer;">
                <i class="bi bi-arrow-down-up"></i>
            </span>
        </div>

        
    </div>
</div>

    </div>

    <hr>

    <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
        <table class="table table-hover cushover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Name</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="log-data"></tbody>
        </table>
    </div>
</div>
</div>
<script>
    let globalUserId = null;
    let employeeData = {}; // Store employee data for matching names

    function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
    }

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
                const empId = $('#employee-select').val();
                const selectedUserId = $('#employee-select option:selected').data('user-id') || globalUserId;
                loadTimecards(empId, selectedUserId);
            },
            error: function() {
                showToast("Failed to update timecard approval.", "error");
            }
        });
    }

    function loadEmployees() {
        $.ajax({
            url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                let employeeSelect = $('#employee-select');
                employeeSelect.empty().append(`<option value="">Select employee</option>`);

                if (response.status === "success" && response.employees.length > 0) {
                    globalUserId = response.user_id;

                    response.employees.forEach(emp => {
                        employeeData[emp.id] = emp.name; // Store employee names for matching
                        employeeSelect.append(`
                            <option value="${emp.id}" data-user-id="${response.user_id}">
                                ${emp.name} (${emp.email})
                            </option>
                        `);
                    });

                    // Load all timecards by default
                    loadTimecards('', response.user_id);
                } else {
                    showToast("No employees found.", "error");
                }
            },
            error: function() {
                console.error("Failed to fetch employees.");
            }
        });
    }

    function loadTimecards(empId = '', userId = globalUserId, statusFilter = '') {
        if (!userId) return;

        let requestData = {
            employee_org_id: userId
        };

        if (statusFilter) {
            requestData.approved = statusFilter;
        }

        if (empId) {
            requestData.employee_id = empId;
        }

        $.ajax({
            url: '<?= base_url("employee/Timecards_manual/get_timecards") ?>',
            method: 'GET',
            data: requestData,
            dataType: 'json',
            success: function(timecards) {
                let html = '';
                if (timecards.length === 0) {
                    html = `<tr><td colspan="8">No requests found</td></tr>`;
                } else {
                    timecards.forEach((row, i) => {
                        const startTime = row.timestamp_start ? row.timestamp_start.slice(0, 5) : '';
                        const endTime = row.timestamp_end ? row.timestamp_end.slice(0, 5) : '';

                        const duration = (row.timestamp_start && row.timestamp_end) ?
                            Math.floor((new Date(`1970-01-01T${row.timestamp_end}`) - new Date(`1970-01-01T${row.timestamp_start}`)) / 60000) + ' mins' :
                            'N/A';

                        let actionBtns = '';
                        if (row.approved != 1) {
                            actionBtns = `
                            <?php if ($can_edit): ?>
                                <button class="btn btn-success btn-sm" onclick="updateApproval(${row.manual_id}, 'approved', ${row.employee_id}, ${userId})">Approve</button>
                                <button class="btn btn-danger btn-sm disabled" onclick="">Decline</button>
                                 <?php else: ?>
                                <button data-toggle="tooltip" data-placement="top" title="permission denied to approve" class="btn btn-default btn-sm m-5">Approve</button>
                                <button data-toggle="tooltip" data-placement="top" title="permission denied to decline" class="btn btn-default btn-sm m-5">Decline</button>
                                <?php endif; ?>
                            `;
                        }

                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${employeeData[row.employee_id] || 'Unknown'}</td>
                                <td>${startTime}</td>
                                <td>${endTime}</td>
                                <td>${duration}</td>
                                <td>${row.reason || ''}</td>
                                <td class="${row.approved == 1 ? 'text-success' : 'text-warning'}">
                                    ${row.approved == 1 ? 'Approved' : 'Pending'}
                                </td>
                                <td>${actionBtns}</td>
                            </tr>`;
                    });
                }
                $('#log-data').html(html);
                $('[data-toggle="tooltip"]').tooltip(); // Re-initialize tooltips

            },
            error: function() {
                console.error("Failed to fetch timecards.");
            }
        });
    }

    $(document).ready(function() {
        loadEmployees();

        $('#employee-select').on('change', function() {
            const empId = $(this).val();
            const userId = $(this).find('option:selected').data('user-id') || globalUserId;
            loadTimecards(empId, userId);
        });

        $('#sortSelect').on('change', function () {
    const selectedValue = $(this).val();
    const empId = $('#employee-select').val();
    const userId = $('#employee-select option:selected').data('user-id') || globalUserId;

    if (selectedValue === 'Approved') {
        loadTimecards(empId, userId, 'approved');
    } else if (selectedValue === 'Unapproved') {
        loadTimecards(empId, userId, 'unapproved');
    } else {
        loadTimecards(empId, userId); // Filters or default
    }
});

        // $('#cancel-btn').on('click', function() {
        //     loadTimecards('', globalUserId);
        // });
    });
</script>