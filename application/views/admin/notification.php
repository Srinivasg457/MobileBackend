<div class="content-wrapper">
    <section class="content">


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



        <h3>Notifications</h3>
        <div class="row" style="margin: 25px auto;">
            <div class="col-lg-6">
                <select id="employeeSelect" class="form-control single_select"></select>

            </div>
            <!-- <div class="col-lg-6 align-content-center justify-content-center mt-3">
          <div class="btn-group">
              <button id="approved-btn" class="btn btn-success btn-sm m-5">Approved</button>
              <button id="unapproved-btn" class="btn btn-warning btn-sm m-5">Unapproved</button>
          </div>
      </div> -->
        </div>

        <hr>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
            <table class="table table-hover cushover">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>Notification Id</th>
                        <th>Employee Id</th>
                        <th>Posted At</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody id="log-data">
                    <!-- Rows will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <script>
            $(document).ready(function() {
                $('#employeeSelect').on('change', function() {
                    const employeeNameText = $(this).find('option:selected').text().split(' (')[0];
                    console.log("onchage");
                    let employeeId = $(this).val();
                    let currentEmployeeId = employeeId;
                    fetchNotifications(currentEmployeeId);
                    // No need to manually clear here
                });
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
                            fetchNotifications(randomEmployee.id);

                        } else {
                            employeeSelect.empty().append(`<option value="">-- No employees found --</option>`);
                        }
                    },


                    error: function() {
                        $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
                    }
                });
                // Function to fetch and display notifications
                function fetchNotifications(employeeId) {
                    $.ajax({
                        url: "<?= base_url('admin/Notification/get_notifications'); ?>",
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            employee_id: employeeId
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#log-data').empty();

                                if (response.data.length === 0) {
                                    $('#log-data').html('<tr><td colspan="6" class="text-center">No notifications found</td></tr>');
                                    return;
                                }

                                $.each(response.data, function(index, notification) {
                                    var postedAt = new Date(notification.created_at);
                                    var formattedDate = postedAt.toLocaleString();
                                    var statusBadge = notification.status == 1 ?
                                        '<span class="badge badge-success">read</span>' :
                                        '<span class="badge badge-warning">unread</span>';

                                    var row = `
                                <tr class="notification-row" data-id="${notification.notification_id}" data-status="${notification.status}">
                                    <td><input type="checkbox" class="notification-checkbox" data-id="${notification.notification_id}"></td>
                                    <td>${notification.notification_id}</td>
                                    <td>${notification.employee_id}</td>
                                    <td>${formattedDate}</td>
                                    <td>${notification.description}</td>
                                </tr>
                            `;

                                    $('#log-data').append(row);
                                });

                                // Add click event to rows to mark as read
                                $('.notification-row').click(function() {
                                    var notificationId = $(this).data('id');
                                    var currentStatus = $(this).data('status');

                                    if (currentStatus == 0) { // Only update if unread
                                        updateNotificationStatus(notificationId, 1);
                                    }
                                });
                            } else {
                                $('#log-data').html('<tr><td colspan="6" class="text-center">' + response.message + '</td></tr>');
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#log-data').html('<tr><td colspan="6" class="text-center">Error loading notifications: ' + error + '</td></tr>');
                        }
                    });
                }

                // Function to update notification status


                // Select all functionality
                $('#select-all').change(function() {
                    var isChecked = $(this).is(':checked');
                    $('.notification-checkbox').prop('checked', isChecked).trigger('change');
                });
            });
        </script>

    </section>

</div>