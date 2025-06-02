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
                border: 1px solid black;
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
            }

            .box {
                width: 250px;
                height: 50px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 16px;
                border-radius: 8px;
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
        </style>
        <h2 style="text-align: center;">Notifications</h2>
        <div class="container">
            <div class="box desktop">Desktop</div>
            <div class="box webcam">Webcam</div>
        </div>

        <div class="notification-container" id="notifications-list">
            <!-- <div class="loading">Loading notifications...</div> -->
        </div>

        <script>
    // Function to fetch notifications for all employees
    function loadAllEmployees() {
        $.ajax({
            url: "<?= base_url('/admin/Monitoring_room/list_employees_by_user') ?>",
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.employees.length > 0) {
                    response.employees.forEach(function(employee) {
                        fetchNotifications(employee.id, employee.name); // Pass both ID and name
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

    // Function to fetch notifications for a specific employee
    function fetchNotifications(employeeId, employeeName) {
        $.ajax({
            url: "<?= base_url('admin/Notification/get_notifications') ?>",
            type: 'GET',
            data: {
                employee_id: employeeId,
                employee_name: employeeName
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    const sortedNotifications = response.data.sort((a, b) => {
                        return (a.status === 0) ? -1 : (b.status === 0 ? 1 : 0);
                    });
                    displayNotifications(employeeId, employeeName, sortedNotifications); // Pass name here
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading notifications for employee ID ' + employeeId, error);
            }
        });
    }

    // Function to display notifications for a single employee
 function displayNotifications(employeeId, employeeName, notifications) {
    if (notifications.length === 0) return;

    let html = '';

    notifications.forEach(function(notification) {
        const timeAgo = formatTimeAgo(notification.created_at);

        const isOnline = notification.status == 1;
        const statusHtml = isOnline ?
            `<span class="status online">ONLINE</span>` :
            `<span class="status offline">OFFLINE</span>`;

        // Hide description and time if online
        const descriptionHtml = isOnline ? '' : 
            `<span class="desc">Message :${notification.description}</span>`;
        const timeHtml = isOnline ? '' : 
            `<div class="time">${timeAgo}</div>`;

        html += `
            <div class="notification">
                <div class="profile">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTknqZMo9wWXmrjrwgdRD29sKWtvzxb-MWkVNnCgYujtPDxdK57cMM2vgaGnFdqhqcxCY8&usqp=CAU" alt="Profile">
                    <div class="details">
                        <span class="name">Emp Name :${employeeName}</span>
                        ${descriptionHtml}
                    </div>
                </div>
                <div class="right">
                    ${statusHtml}
                    ${timeHtml}
                </div>
            </div>
        `;
    });

    // Append to list (not replace) to show all employees' messages
    $('#notifications-list').append(html);
}
    // Function to format time ago from created_at
    function formatTimeAgo(createdAt) {
        const createdDate = new Date(createdAt);
        const now = new Date();
        const diffInSeconds = Math.floor((now - createdDate) / 1000);

        if (diffInSeconds < 60) return 'just now';
        if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' mins ago';
        if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
        return Math.floor(diffInSeconds / 86400) + ' days ago';
    }

    // On document ready
    $(document).ready(function() {
        loadAllEmployees();
    });
</script>



    </section>
</div>