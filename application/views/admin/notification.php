<div class="content-wrapper">
    <section class="content">

        <style>
            .notification-container {
                margin: auto;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
                padding: 20px;
                max-width: 1200px;
                /* border: 1px solid black; */
            }

            .notification {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                border-bottom: 1px solid #eee;
                padding: 16px 0;
            }

            .notification:last-child {
                border-bottom: none;
            }

            .profile {
                display: flex;
                align-items: center;
            }

            .profile img {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                margin-right: 12px;
                object-fit: cover;
            }

            .details {
                display: flex;
                flex-direction: column;
            }

            .name {
                font-weight: bold;
                margin-bottom: 2px;
            }

            .desc {
                color: #888;
                font-size: 14px;
            }

            .right {
                text-align: right;
            }

            .status {
                display: inline-block;
                margin-top: 8px;
                padding: 4px 12px;
                font-size: 13px;
                border-radius: 12px;
                width: max-content;
            }

            .right {
                text-align: right;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 4px;
                /* Adds small space between status and time */
            }

            .status {
                margin-top: 0;
                /* Remove the previous margin */
            }

            .time {
                color: #aaa;
                font-size: 12px;
                /* Slightly smaller for better hierarchy */
            }

            .online {
                background-color: #dcfce7;
                color: #166534;
                border: 2px solid #166534;
            }

            .offline {
                background-color: #fee2e2;
                color: #991b1b;
                border: 2px solid #991b1b;
            }

            .time {
                color: #aaa;
                font-size: 16px;
            }

            .loading {
                text-align: center;
                padding: 20px;
                color: #888;
            }

            .error-message {
                color: #cc4c4c;
                text-align: center;
                padding: 20px;
            }
        </style>
        <style>
            .container {
                display: flex;
                gap: 20px;
                justify-content: center;
                margin-bottom: 20px;
            }

            .box {
                width: 250px;
                height: 50px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 16px;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .desktop {
                color: #2c3e50;
                background-color: #ecf0f1;
                border: 1px solid #2c3e50;
            }

            .webcam {
                color: #2c3e50;
                background-color: #ecf0f1;
                border: 1px solid #2c3e50;
            }

            .box.active {
                background-color: #3498db;
                color: white;
                border-color: #3498db;
                font-weight: bold;
            }
        </style>
        <h2 style="text-align: center;">Notifications</h2>
        <div class="container">
            <div class="box desktop">Desktop</div>
            <div class="box webcam active">Webcam</div>
        </div>

        <div class="notification-container" id="notifications-list">
            <!-- <div class="loading">Loading notifications...</div> -->
        </div>

        <script>
    // Main function to load notifications
    function loadNotifications(application) {
        // Set active tab
        if (application === "web") {
            $('.box.webcam').addClass('active');
            $('.box.desktop').removeClass('active');
        } else {
            $('.box.desktop').addClass('active');
            $('.box.webcam').removeClass('active');
        }

        // Clear existing notifications
        $('#notifications-list').html('');

        $.ajax({
            url: "<?= base_url('/admin/Monitoring_room/list_employees_by_user') ?>",
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.employees.length > 0) {
                    // First show all employees as signed off
                    const initialNotifications = response.employees.map(function(employee) {
                        return {
                            employeeId: employee.id,
                            employeeName: employee.name,
                            notification: {
                                status: 0,
                                description: 'User sign off',
                                created_at: new Date().toISOString()
                            }
                        };
                    });
                    
                    // Display initial notifications sorted (offline first)
                    displaySortedNotifications(application, initialNotifications);

                    // Then fetch actual status from server
                    response.employees.forEach(function(employee) {
                        if (application === "web") {
                            fetchWebcamNotifications(employee.id, employee.name);
                        } else {
                            fetchDesktopNotifications(employee.id, employee.name);
                        }
                    });
                } else {
                    $('#notifications-list').html(
                        '<div class="notification">No employees found.</div>'
                    );
                }
            },
            error: function() {
                $('#notifications-list').html(
                    '<div class="error-message">Error loading employees.</div>'
                );
            }
        });
    }

    // Helper function to display notifications in sorted order (offline first)
    function displaySortedNotifications(application, notificationsData) {
        // Sort notifications - offline first (status 0), then online (status 1)
        notificationsData.sort((a, b) => a.notification.status - b.notification.status);
        
        // Clear existing notifications
        $('#notifications-list').html('');
        
        // Display each notification in sorted order
        notificationsData.forEach(data => {
            if (application === "web") {
                displayWebcamNotification(data.employeeId, data.employeeName, data.notification);
            } else {
                displayDesktopNotification(data.employeeId, data.employeeName, data.notification);
            }
        });
    }

    // Webcam Notifications
    function fetchWebcamNotifications(employeeId, employeeName) {
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
                    // Get all current notifications
                    const currentNotifications = [];
                    $('.notification').each(function() {
                        const name = $(this).find('.name').text().replace('Emp Name: ', '');
                        const isOnline = $(this).find('.status').hasClass('online');
                        currentNotifications.push({
                            employeeName: name,
                            notification: {
                                status: isOnline ? 1 : 0,
                                description: $(this).find('.desc').text().replace('Message: ', '') || '',
                                created_at: $(this).find('.time').text() || new Date().toISOString()
                            }
                        });
                    });
                    
                    // Update the notification for this employee
                    const updatedNotifications = currentNotifications.map(notif => {
                        if (notif.employeeName === employeeName) {
                            return {
                                employeeName: employeeName,
                                notification: response.data[0]
                            };
                        }
                        return notif;
                    });
                    
                    // Display sorted notifications
                    displaySortedNotifications("web", updatedNotifications);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading webcam notifications for employee ID ' + employeeId, error);
            }
        });
    }

    function displayWebcamNotification(employeeId, employeeName, notification) {
        const timeAgo = formatTimeAgo(notification.created_at);
        const isOnline = notification.status == 1;
        const statusHtml = isOnline ?
            '<span class="status online">ONLINE</span>' :
            '<span class="status offline">OFFLINE</span>';

        const showDescription = !isOnline || 
            (notification.description && notification.description.includes("webcam is closed, but the user is online"));
        
        const descriptionHtml = showDescription ? 
            '<span class="desc">Message: ' + notification.description + '</span>' : '';
            
        const timeHtml = isOnline ? '' : 
            '<div class="time">' + timeAgo + '</div>';

        const html = 
            '<div class="notification">' +
                '<div class="profile">' +
                    '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTknqZMo9wWXmrjrwgdRD29sKWtvzxb-MWkVNnCgYujtPDxdK57cMM2vgaGnFdqhqcxCY8&usqp=CAU" alt="Profile">' +
                    '<div class="details">' +
                        '<span class="name">Emp Name: ' + employeeName + '</span>' +
                        descriptionHtml +
                    '</div>' +
                '</div>' +
                '<div class="right">' +
                    statusHtml +
                    timeHtml +
                '</div>' +
            '</div>';

        $('#notifications-list').append(html);
    }

    // Desktop Notifications
    function fetchDesktopNotifications(employeeId, employeeName) {
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
                    const currentNotifications = [];
                    $('.notification').each(function() {
                        const name = $(this).find('.name').text().replace('Emp Name: ', '');
                        const isOnline = $(this).find('.status').hasClass('online');
                        currentNotifications.push({
                            employeeName: name,
                            notification: {
                                status: isOnline ? 1 : 0,
                                description: $(this).find('.desc').text().replace('Message: ', '') || '',
                                created_at: $(this).find('.time').text() || new Date().toISOString()
                            }
                        });
                    });
                    
                    // Update the notification for this employee
                    const updatedNotifications = currentNotifications.map(notif => {
                        if (notif.employeeName === employeeName) {
                            return {
                                employeeName: employeeName,
                                notification: response.data[0]
                            };
                        }
                        return notif;
                    });
                    
                    // Display sorted notifications
                    displaySortedNotifications("desk", updatedNotifications);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading desktop notifications', error);
            }
        });
    }

    function displayDesktopNotification(employeeId, employeeName, notification) {
        const timeAgo = formatTimeAgo(notification.created_at);
        const isOnline = notification.status == 1;

        const html = 
            '<div class="notification">' +
                '<div class="profile">' +
                    '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTknqZMo9wWXmrjrwgdRD29sKWtvzxb-MWkVNnCgYujtPDxdK57cMM2vgaGnFdqhqcxCY8&usqp=CAU" alt="Profile">' +
                    '<div class="details">' +
                        '<span class="name">Emp Name: ' + employeeName + '</span>' +
                        (isOnline ? '' : '<span class="desc">Message: ' + notification.description + '</span>') +
                    '</div>' +
                '</div>' +
                '<div class="right">' +
                    (isOnline ? '<span class="status online">ONLINE</span>' : '<span class="status offline">OFFLINE</span>') +
                    (isOnline ? '' : '<div class="time">' + timeAgo + '</div>') +
                '</div>' +
            '</div>';

        $('#notifications-list').append(html);
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
</script>