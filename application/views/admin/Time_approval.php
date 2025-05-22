<style>
  

    .btn-group {
        display: flex;
        gap: 25px;
        justify-content: end;
    }


    /* New mobile-responsive styles */
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
            margin: 15px auto !important;
        }

        .col-lg-6 {
            width: 100%;
            margin-bottom: 15px;
        }

        #employee-select {
            width: 100%;
        }

        .btn-group {
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-start;
        }

        .btn-group button {
            flex: 1 0 100px;
            margin: 5px 0 !important;
        }

        .content-wrapper h3 {
            font-size: 1.5rem;
            text-align: center;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group button {
            width: 100%;
        }
        
        #toast-container {
            left: 10px;
            right: 10px;
            top: 10px;
        }
        
        .toast {
            min-width: auto;
            width: calc(100% - 20px);
        }
    }
</style>

<div id="toast-container"></div>

<div class="content-wrapper">
    <h3>Time Approval</h3>
    <div class="row" style="margin: 25px auto;">
        <div class="col-lg-6">
         Employee List:   <select id="employee-select" class="form-control single_select">
                <option value="">Select Employee</option>
            </select>
        </div>
        <div class="col-lg-6 align-content-center justify-content-center mt-3">
            <div class="btn-group">
                <span class="m-10">Filters:</span>
                <button id="approved-btn" class="btn btn-success btn-sm m-5">Approved</button>
                <button id="unapproved-btn" class="btn btn-warning btn-sm m-5">Unapproved</button>
                <a href="<?php echo base_url('employee/Timecards_manual/approve') ?>" class="btn btn-default btn-sm m-5 "><i class="flaticon-reload"></i> <?php echo trans('reset-filter') ?></a>
                <!-- <button id="cancel-btn" class="btn btn-secondary btn-sm m-5">Cancel Filter</button> -->
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
                                <button class="btn btn-success btn-sm" onclick="updateApproval(${row.manual_id}, 'approved', ${row.employee_id}, ${userId})">Approve</button>
                                <button class="btn btn-danger btn-sm disabled" onclick="">Decline</button>
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

        $('#approved-btn').on('click', function() {
            loadTimecards($('#employee-select').val(), globalUserId, 'approved');
        });

        $('#unapproved-btn').on('click', function() {
            loadTimecards($('#employee-select').val(), globalUserId, 'unapproved');
        });

        // $('#cancel-btn').on('click', function() {
        //     loadTimecards('', globalUserId);
        // });
    });
</script>