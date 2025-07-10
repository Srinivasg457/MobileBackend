
<div class="content-wrapper notificaion_style">
    <section class="content">
        <div class="list_area container">
            <h3 class="box-title"><?php echo 'Notification' ?>
                <div class="pull-right rounded  mx-2 box desktop"><?php echo "Desktop" ?></div>
                <div class="pull-right  rounded  box webcam active"><?php echo "Webcam" ?></div>
            </h3>



            <div class="container my-5"></div>
            <!-- <div class="row mt-20"> -->
            <div class="notification-container col-md-10 col-sm-12 col-xs-10  bg-white rounded m-auto border-bottom" style="border-bottom: 1px solid whitesmoke;">
                <!-- <div class="loading">Loading notifications...</div> -->
                <div class="notification form-group mb-0">
                    <div class="text-center">
                        <label for="control-label fw-bolder">User</label>
                    </div>
                    <div class="right" style="display: grid; grid-template-columns: 1fr 1fr; align-items: flex-end; gap: 25px;">
                        <div> <label for="control-label ">Status</label>
                        </div>
                        <div> <label for="control-label">Send mail</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="notification-container col-md-10 col-sm-12 col-xs-10  bg-white rounded m-auto" id="notifications-list">

            </div>
            <!-- </div> -->
    </section>
</div>
<script>
    // Main function to load notifications
    function loadNotifications(application) {
        if (application === "web") {
            $('.box.webcam').addClass('active');
            $('.box.desktop').removeClass('active');
        } else {
            $('.box.desktop').addClass('active');
            $('.box.webcam').removeClass('active');
        }

        $('#notifications-list').html('');

        // Choose API endpoint based on application
        const url = application === "web" ?
            "<?= base_url('admin/Notification/get_notifications') ?>" :
            "<?= base_url('admin/Notification/desktop_notifications') ?>";

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.data.length > 0) {
                    console.log(response);

                    const formattedData = response.data.map(item => ({
                        employeeId: item.employee_id,
                        employeeName: item.employee_name,
                        employeeEmail: item.email, // If you want email, return it in the API
                        notification: {
                            status: parseInt(item.status),
                            description: item.description,
                            created_at: item.created_at
                        }
                    }));

                    // Display all notifications in one go
                    displaySortedNotifications(application, formattedData);
                } else {
                    $('#notifications-list').html(
                        '<div class="notification">No notifications found.</div>'
                    );
                }
            },
            error: function() {
                $('#notifications-list').html(
                    '<div class="error-message">Error loading notifications.</div>'
                );
            }
        });
    }


    // Helper function to display notifications in sorted order (offline first)
    function displaySortedNotifications(application, notificationsData) {

        // notificationsData.sort((a, b) => a.notification.status - b.notification.status);
        // $('#notifications-list').html('');
        notificationsData.forEach(data => {
            if (application === "web") {
                displayWebcamNotification(data.employeeId, data.employeeName, data.employeeEmail, data.notification);
            } else {
                displayDesktopNotification(data.employeeId, data.employeeName, data.employeeEmail, data.notification);
            }
        });
    }



    // Webcam Notifications
    function fetchWebcamNotifications(employeeId, employeeName, employeeEmail) {
        $.ajax({
            url: "<?= base_url('admin/Notification/get_notifications') ?>",
            type: 'GET',
            data: {
                employee_id: employeeId,
                employee_name: employeeName
            },
            dataType: 'json',
            success: function(response) {

                if (response.status === 'success' && response.data.length > 0) {
                    // Get all current notifications from DOM
                    // const currentNotifications = [];
                    // $('.notification').each(function() {
                    //     const empId = $(this).data('emp-id');
                    //     const empName = $(this).find('.name').text().replace('Emp Name: ', '');
                    //     const isOnline = $(this).find('.status').hasClass('online');

                    //     currentNotifications.push({
                    //         employeeId: empId,
                    //         employeeName: empName,
                    //         notification: {
                    //             status: isOnline ? 1 : 0,
                    //             description: $(this).find('.desc').text().replace('Message: ', '') || '',
                    //             created_at: $(this).find('.time').text() || new Date().toISOString()
                    //         }
                    //     });
                    // });

                    // Update the matched employee's notification by ID
                    // const updatedNotifications = currentNotifications.map(notif => {
                    //     if (parseInt(notif.employeeId) === parseInt(employeeId)) {
                    //         return {
                    //             employeeId: employeeId,
                    //             employeeName: employeeName,
                    //             notification: response.data[0]
                    //         };
                    //     }
                    //     return notif;
                    // });

                    const formattedData = [{
                        employeeId: employeeId,
                        employeeName: employeeName,
                        employeeEmail: employeeEmail,
                        notification: {
                            status: parseInt(response.data[0].status), // ensure it's number
                            description: response.data[0].description,
                            created_at: response.data[0].created_at
                        }
                    }];

                    displaySortedNotifications("web", formattedData);
                }

            },
            error: function(xhr, status, error) {
                console.error('Error loading webcam notifications for employee ID ' + employeeId, error);
            }
        });
    }


    function displayWebcamNotification(employeeId, employeeName, employeeEmail, notification) {
        const timeAgo = formatTimeAgo(notification.created_at);
        const isOnline = notification.status == 1;

        const statusHtml = isOnline ?
            '<span class="status online">ONLINE</span>' :
            '<span class="status offline">OFFLINE</span>';

        const showDescription = !isOnline ||
            (notification.description &&
                (notification.description.includes("webcam is closed, but the user is online") ||
                    notification.description.includes("Webcam permission denied by system, but the user is online")));

        const descriptionHtml = showDescription ?
            '<span class="desc">Message: ' + notification.description + '</span>' :
            '';

        const timeHtml = isOnline ? '' : '<div class="time">' + timeAgo + '</div>';


        const sendButtonHtml = showDescription ?
            `
                    <?php if ($can_edit): ?>
                        <button class="send-email" data-id="${employeeId}" data-name="${employeeName}" data-email="${employeeEmail}" data-description="${notification.description}" style="margin-top:5px;">Send Email</button>
                        <?php else: ?>
                        <button class="btn btn-default rounded" data-toggle="tooltip" data-placement="top" title="permission denied to send mail" style="margin-top:5px;">Send Email</button>

                        <?php endif; ?>` :
            '<button class="send-email" style="margin-top:5px; visibility: hidden;"> Send Email </button>';



        const html =
            `<div class="notification">
                        <div class="profile">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTknqZMo9wWXmrjrwgdRD29sKWtvzxb-MWkVNnCgYujtPDxdK57cMM2vgaGnFdqhqcxCY8&usqp=CAU" alt="Profile">
                            <div class="details">
                                <span class="name">Emp Name: ${employeeName}</span>
                                ${descriptionHtml}
                            </div>
                        </div>
                      <div class="right" style="display: grid; grid-template-columns: 1fr 1fr; align-items: flex-end; gap: 25px;">
                        <div>
                            <div class="status-wrapper">${statusHtml}</div>
                            <div class="time-wrapper">${timeHtml}</div>
                        </div>
                        <div class="button-wrapper">
                            ${sendButtonHtml}
                        </div>
                    </div>

                    </div>`;

        $('#notifications-list').append(html);
        $('[data-toggle="tooltip"]').tooltip(); // Re-initialize tooltips
    }


    // Desktop Notifications
    function fetchDesktopNotifications(employeeId, employeeName, employeeEmail) {
        $.ajax({
            url: "<?= base_url('admin/Notification/desktop_notifications') ?>",
            type: 'GET',
            data: {
                employee_id: employeeId,
                employee_name: employeeName
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.data.length > 0) {
                    // Get all current notifications
                    // const currentNotifications = [];
                    // $('.notification').each(function() {
                    //     const name = $(this).find('.name').text().replace('Emp Name: ', '');
                    //     const isOnline = $(this).find('.status').hasClass('online');
                    //     currentNotifications.push({
                    //         employeeName: name,
                    //         notification: {
                    //             status: isOnline ? 1 : 0,
                    //             description: $(this).find('.desc').text().replace('Message: ', '') || '',
                    //             created_at: $(this).find('.time').text() || new Date().toISOString()
                    //         }
                    //     });
                    // });

                    // // Update the notification for this employee
                    // const updatedNotifications = currentNotifications.map(notif => {
                    //     if (notif.employeeName === employeeName) {
                    //         return {
                    //             employeeName: employeeName,
                    //             notification: response.data[0]
                    //         };
                    //     }
                    //     return notif;
                    // });
                    const formattedData = [{
                        employeeId: employeeId,
                        employeeName: employeeName,
                        employeeEmail: employeeEmail,
                        notification: {
                            status: parseInt(response.data[0].status), // ensure it's number
                            description: response.data[0].description,
                            created_at: response.data[0].created_at
                        }
                    }];

                    displaySortedNotifications("desk", formattedData);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading desktop notifications', error);
            }
        });
    }

    function displayDesktopNotification(employeeId, employeeName, employeeEmail, notification) {
        const timeAgo = formatTimeAgo(notification.created_at);
        const isOnline = notification.status == 1;

        const sendButtonHtml = (!isOnline) ?
            `
                    <?php if ($can_edit): ?>
                    <button class="send-email" data-id="${employeeId}" data-name="${employeeName}" data-email="${employeeEmail}" data-description="${notification.description}" style="margin-top:5px;">Send Email</button>
                    <?php else: ?>
                        <button class="btn" data-toggle="tooltip" data-placement="top" title="permission denied to send mail" style="margin-top:5px;">Send Email</button>
                        <?php endif; ?>` :
            '<button class="send-email" style="margin-top:5px; visibility: hidden;"> Send Email </button>';

        const html =
            `<div class="notification">
                        <div class="profile">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTknqZMo9wWXmrjrwgdRD29sKWtvzxb-MWkVNnCgYujtPDxdK57cMM2vgaGnFdqhqcxCY8&usqp=CAU" alt="Profile">
                            <div class="details">
                                <span class="name">Emp Name: ${employeeName}</span>
                                ${isOnline ? '' : `<span class="desc">Message: ${notification.description}</span>`}
                            </div>
                        </div>
                        <div class="right" style="display: grid; grid-template-columns: 1fr 1fr; align-items: flex-end; gap: 25px;">
                        <div>
                            <div class="status-wrapper">
                                ${isOnline ? '<span class="status online">ONLINE</span>' : '<span class="status offline">OFFLINE</span>'}
                            </div>
                            ${isOnline ? '' : `
                                <div class="time-wrapper">
                                    <div class="time">${timeAgo}</div>
                                </div>
                            
                            `}
                            </div>
                            <div class="button-wrapper">
                                    ${sendButtonHtml}
                                </div>
                        </div>
                        </div>`;

        $('#notifications-list').append(html);
        $('[data-toggle="tooltip"]').tooltip(); // Re-initialize tooltips
    }



    function formatTimeAgo(createdAt) {
        const createdDate = new Date(createdAt);
        const now = new Date();
        const diffInSeconds = Math.floor((now - createdDate) / 1000);

        if (diffInSeconds < 60) return 'just now';
        if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' mins ago';
        if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
        return Math.floor(diffInSeconds / 86400) + ' days ago';
    }

    $(document).ready(function() {

        // Initially load webcam notifications
        loadNotifications("web");

        // Tab click handlers
        $('.box.webcam').on('click', function() {
            loadNotifications("web");
        });

        $('.box.desktop').on('click', function() {
            loadNotifications("desk");
        });

    });
    $(document).on('click', '.send-email', function() {
        const $btn = $(this);
        const employeeId = $btn.data('id');
        const employeeName = $btn.data('name');
        const employeeEmail = $btn.data('email');
        const description = $btn.data('description');
        console.log(employeeEmail);


        // Disable button and add loader
        $btn.prop('disabled', true);
        const originalText = $btn.html();
        $btn.html('Sending... <span class="button-loader"></span>');

        $.ajax({
            url: "<?= base_url('admin/Notification/send_alert_mail') ?>",
            type: "POST",
            data: {
                employee_id: employeeId,
                employee_name: employeeName,
                employee_email: employeeEmail,
                message: description
            },
            success: function(response) {
                swal("Success", "Alert email sent successfully!", "success");
            },
            error: function(xhr, status, error) {
                swal("Error", "Failed to send alert email.", "error");
            },
            complete: function() {
                // Restore original button state
                $btn.html(originalText);
                $btn.prop('disabled', false);
            }
        });
    });
</script>