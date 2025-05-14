<div class="content-wrapper">
    <section class="content" style="padding-top: 0;">
        <style>
            /* .main-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    z-index: 1000;
    overflow-y: auto;
    } */


            .popup {
                display: none;
                padding: 10px;
            }

            .cancel-btn {
                background-color: black;
                color: white;
                border: none;
                border-radius: 10px;
                font-size: 20px;
            }

            .cancel-btn:hover {
                background-color: #0b3d1a;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #ccc;
                padding-bottom: 10px;
                flex-wrap: wrap;
            }

            .breadcrumbs {
                font-size: 14px;
                color: #555;
            }

            .search-row {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 20px 0;
            }

            .search-row input,
            .search-row select {
                padding: 10px;
                font-size: 15px;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                border: 1px solid #ccc;
                border-radius: 8px;
            }

            .card-container {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .user-card {
                /* border: 1px solid #ccc; */
                padding: 15px;
                border-radius: 6px;
                box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
                background-color: white;
            }

            .user-info-line {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                font-size: 14px;
                margin-bottom: 10px;
            }

            .user-info-line span {
                font-weight: bold;
            }

            .timestamp-boxes {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                overflow: hidden;
            }

            .see-more-button {
                padding: 6px 12px;
                font-size: 14px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .see-more-button:hover {
                background-color: #0056b3;
            }

            .screenshot-row {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                border: 1px solid #eee;
                padding: 15px;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                width: 100%;
            }

            .screenshot-card {
                width: calc(16.66% - 10px);
                background-color: #F4F6F9;
                border-radius: 6px;
                padding: 8px;
                text-align: center;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                transition: transform 0.2s;
                border: 0.1px solid #a9a7a7;
            }

            .screenshot-card:hover {
                transform: scale(1.02);
            }

            .screenshot-card img {
                width: 100%;
                height: 90px;
                object-fit: cover;
                border-radius: 4px;
                margin-bottom: 6px;
            }

            .screenshot-card p {
                font-size: 12px;
                color: #333;
                margin: 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            #search-btn {
                background-color: transparent;
                border: none;
                cursor: pointer;
                font-size: 18px;
                color: #333;
            }

            @media (min-width: 1600px) {
                .container {
                    width: auto !important;
                    max-width: none !important;
                }
            }

            /* ---------- Mobile Responsive Styling ---------- */
            @media (max-width: 768px) {
                .header {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .search-row {
                    flex-direction: column;
                    align-items: stretch;
                }

                .screenshot-card {
                    width: 48%;
                }

                .user-info-line {
                    flex-direction: column;
                }

                .cancel-btn {
                    font-size: 16px;
                    padding: 8px 12px;
                }

                .breadcrumbs {
                    font-size: 12px;
                }

                .see-more-button {
                    font-size: 12px;
                }

                .screenshot-card img {
                    height: 70px;
                }

                .screenshot-card p {
                    font-size: 11px;
                }

                .toast {
                    font-size: 13px;
                    min-width: 180px;
                }

                .screenshot-row,
                .screenshot-visible,
                .screenshot-hidden {
                    justify-content: center;
                }

            }

            .screenshot-card {
  width: calc(100% / 6 - 10px);
  box-sizing: border-box;
}

/* For mobile responsiveness (adjust max-width as needed) */
@media (max-width: 768px) {
  .screenshot-card {
    width: 200px;
  }
}

        </style>


        <!-- Popup Div -->
       <div class="popup" id="popupCard">
            <div class="d-flex justify-content-between align-items-baseline mb-5">
                <h5><strong>Name:</strong> <span id="popupName"></span></h5>
                <h5><strong>User ID:</strong> <span id="popupID"></span></h5>
                <button class="cancel-btn" onclick="closePopup()">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>

            <div class="container box" style="display: none;">
                <div class="header">
                    <h3>Screenshots</h3>
                </div>
            </div>

            <div class="screenshot-container"></div>
        </div>

        <!-- Screenshot Section -->
        <div class="container">
            <div class="header">
                <h3>Screenshots</h3>
            </div>

            <!-- Search Filters -->
            <div class="search-row">
                Employee: <input type="text" id="search-name" placeholder="Search Users / Department">
                <!-- <button id="search-btn"> -->
                <!-- <i class="fa fa-search"></i> -->
                </button>
                Date: <input type="date" id="datePicker" value="">
                <select>
                    <option>Sort By</option>
                </select>
            </div>

            <div class="card-container"></div>
        </div>

        <!-- Modal for Screenshot Preview -->
        <div id="screenshot-modal" style="display: none; position: fixed; z-index: 1111; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); text-align: center; justify-content: center; align-items: center; flex-direction: column;">
            <span id="close-modal" style="position: absolute; top: 20px; right: 40px; font-size: 40px; color: white; cursor: pointer;">&times;</span>
            <img id="modal-image" style="position: relative; top: 75px;max-width: 95%; max-height: 90%; border: 5px solid white; border-radius: 10px; box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);">
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 10px;">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="deleteConfirmLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this screenshot?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const today = new Date().toISOString().split('T')[0];
            $('#datePicker').val(today);


            function closePopup() {
                $('#popupCard').hide();
                $('.container').show();
            }
            //    let activityDataArray = [];

            function fetchOverallActivityPercentage(employeeId, date1 = '') {
                let activityDataArray = [];
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: "<?= base_url('admin/Activity_logs/get_activity'); ?>",
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            date: date1,
                            employee_id: employeeId
                        },
                        success: function(response) {
                            console.log(response.data);

                            resolve(response.data); // save the array globally
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            async function fetchUserScreenshots(employeeId, date = '') {
                try {
                    const activityDataArray1 = await fetchOverallActivityPercentage(employeeId, date);
                    // console.log('Activity data:', activityDataArray1);
                    // proceed with rendering

                    // let activityDataArray1 = await fetchOverallActivityPercentage(employeeId, date);
                    $.ajax({
                        url: "<?= base_url('admin/ScreenshotController/get_screenshots'); ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            employee_id: employeeId,
                            date: date
                        },
                        success: function(response) {
                            console.log(response);
                            $('#datePicker').val(response.date);
                            const container = $(`#user-${employeeId} .screenshot-row`);
                            if (response.status === "success" && response.screenshots.length > 0) {
                                let output_screen = "";

                                $.each(response.screenshots.slice(0, 6), function(index, screenshot) {
                                    const matchingActivity = activityDataArray1.find(item => item.screenshot_id == screenshot.id);
                                    const overallActivityRaw = matchingActivity ? (matchingActivity.overall_activity_percent ?? '0') : '0';
                                    const overallActivity = isNaN(parseFloat(overallActivityRaw)) ? 0 : Math.min(100, parseFloat(overallActivityRaw));
                                    let timeWithoutSeconds = screenshot.display_text.split(':').slice(0, 2).join(':');

                                    output_screen += `

    <div class="screenshot-card" style="width: 200px; margin: 5px;">
        <img src="${screenshot.image_url}" class="zoomable-screenshot" alt="Screenshot" width="100%" style="cursor: pointer;">
        <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
            <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                  <svg viewBox="0 0 36 36" width="40" height="40">
                    <!-- Background circle -->
                    <circle
                    cx="18"
                    cy="18"
                    r="15.9155"
                    fill="none"
                    stroke="#e6e6e6"
                    stroke-width="4"
                    />
                    
                    <!-- Progress circle -->
                    <circle
                    cx="18"
                    cy="18"
                    r="15.9155"
                    fill="none"
                    stroke="green"
                    stroke-width="4"
                    stroke-dasharray="${overallActivity} ${100 - overallActivity}"
                    stroke-dashoffset="25"  <!-- makes it start from top -->
                    transform="rotate(-90 18 18)"  <!-- rotates start point to top -->
                    />
                </svg>
                <div style="position: absolute; top: 50%; left: 50%; 
                            transform: translate(-50%, -50%);
                            font-size: 10px; font-weight: bold;cursor: pointer;" 
                            data-toggle="tooltip" data-placement="top" title="${overallActivityRaw}%">
                    ${overallActivityRaw}%
                </div>
            </div>
            <span>${timeWithoutSeconds}</span>
            <img 
                src="https://img.icons8.com/?size=50&id=4887&format=png" 
                class="delete-screenshot" 
                data-id="${screenshot.id}" 
                alt="Delete" 
                style="cursor: pointer; width: 20px; height: 20px;"
                data-toggle="tooltip" data-placement="top" title="Delete"
            />
        </div>
    </div>`;

                                });



                                container.html(output_screen);

                                //  Show/Hide "See More" based on count
                                if (response.screenshots.length > 6) {
                                    $(`#user-${employeeId} .see-more-button`).show();
                                } else {
                                    $(`#user-${employeeId} .see-more-button`).hide();
                                }
                                if (date === new Date().toISOString().split('T')[0]) {
                                    setTimeout(() => fetchUserScreenshots(employeeId, date), 1000);
                                }

                                container.find('.zoomable-screenshot').on('click', function() {
                                    $('#modal-image').attr('src', $(this).attr('src'));
                                    $('#screenshot-modal').fadeIn();
                                });

                                $('#close-modal').on('click', function() {
                                    $('#screenshot-modal').fadeOut();
                                });

                                container.find('.delete-screenshot').on('click', function() {
                                    const screenshotId = $(this).data('id');

                                     const message = `Are you sure you want to delete the Screenshot?`;
                                showConfirmationAlert(message, "warning", function() {
                                        $.ajax({
                                            url: "/admin/ScreenshotController/soft_delete_screenshot",
                                            type: "POST",
                                            data: {
                                                screenshot_id: screenshotId,
                                                employee_id: employeeId
                                            },
                                            success: function() {
                                                   if (response.status == 1) {
                                                       swal("Failed!", response.message || "Could not delete Screenshot.", "error");
                                                        loadUserRolePermissions(userId);      
                                                    } else {
                                                        swal("Deleted!", "Screenshot deleted successfully.", "success");
                                                    }
                                                // alert("Screenshot deleted successfully.");
                                                fetchUserScreenshots(employeeId, $('#datePicker').val());
                                            },
                                            error: function(xhr) {
                                                // alert("Error deleting screenshot: " + xhr.responseText);
                                                swal("Error!", "Something went wrong.", "error");
                                            }
                                        });
                                      });
                                });

                            } else {
                                container.html("<p>No screenshots available.</p>");
                                $(`#user-${employeeId} .see-more-button`).hide(); //  Also hide button if no screenshots
                            }
                        },
                        error: function(xhr, error) {
                            console.error("Error fetching screenshots:", error);
                        }
                    });
                } catch (error) {
                    // console.error("Error fetching screenshots:", error);

                    showToast(error, "error");
                }
            }

            // Popup Screenshot Loader
            $(document).on("click", ".see-more-button", function() {
                $(".container").hide();

                const name = $(this).data("name");
                const id = $(this).data("id");
                const date = $('#datePicker').val();

                $("#popupName").text(name);
                $("#popupID").text(id);
                $("#popupCard").fadeIn();

                async function loadScreenshots() {
                    try {
                        let activityDataArray1 = await fetchOverallActivityPercentage(id, date);
                        $.ajax({
                            url: "<?= base_url('admin/ScreenshotController/get_screenshots'); ?>",
                            type: "GET",
                            dataType: "json",
                            data: {
                                employee_id: id,
                                date: date
                            },
                            success: function(response) {
                                console.log(response);

                                if (response.status === "success" && response.screenshots.length > 0) {
                                    const screenshotsPerGroup = 12;
                                    let output = "";
                                    const groupedScreenshots = {};

                                    // Group screenshots by hour range
                                    response.screenshots.forEach((screenshot) => {
                                        const time = screenshot.display_text;
                                        const hour = time.split(":")[0];
                                        const groupLabel = `${hour}:00:00-${String(Number(hour) + 1).padStart(2, '0')}:00:00`;

                                        if (!groupedScreenshots[groupLabel]) {
                                            groupedScreenshots[groupLabel] = [];
                                        }

                                        groupedScreenshots[groupLabel].push(screenshot);
                                    });

                                    $.each(groupedScreenshots, function(timeRange, groupScreenshots) {
                                        const groupId = `group-${timeRange.replace(/[^a-zA-Z0-9]/g, "")}`;
                                        output += `
                        <div class="screenshot-group box" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                            <div class="box-header" style="font-weight: bold; margin-bottom: 10px;">Time: ${timeRange}</div>
                            <div class="screenshot-visible" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    `;

                                        groupScreenshots.slice(0, screenshotsPerGroup).forEach((screenshot) => {
                                            const matchingActivity = activityDataArray1.find(item => item.screenshot_id == screenshot.id);
                                            const overallActivity = matchingActivity ? (matchingActivity.overall_activity_percent ?? '0') : '0';
                                            let timeWithoutSeconds = screenshot.display_text.split(':').slice(0, 2).join(':');

                                            output += `
                            <div class="screenshot-card"  box-sizing: border-box;">
                                <img src="${screenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" style="width: 100%; cursor: pointer;">
                                <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                                    <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                     <svg viewBox="0 0 36 36" width="40" height="40">
                    <!-- Background circle -->
                    <circle
                    cx="18"
                    cy="18"
                    r="15.9155"
                    fill="none"
                    stroke="#e6e6e6"
                    stroke-width="4"
                    />
                    
                    <!-- Progress circle -->
                    <circle
                    cx="18"
                    cy="18"
                    r="15.9155"
                    fill="none"
                    stroke="green"
                    stroke-width="4"
                    stroke-dasharray="${overallActivity} ${100 - overallActivity}"
                    stroke-dashoffset="25"  <!-- makes it start from top -->
                    transform="rotate(-90 18 18)"  <!-- rotates start point to top -->
                />
            </svg>
            <div style="position: absolute; top: 50%; left: 50%; 
                        transform: translate(-50%, -50%);
                        font-size: 10px; font-weight: bold;cursor: pointer;"
                        data-toggle="tooltip" data-placement="top" title="${overallActivity}%">
                ${overallActivity}%
                                </div>
                            </div> <p>${timeWithoutSeconds}</p>
                                    <img 
                                        src="https://img.icons8.com/?size=50&id=4887&format=png" 
                                        class="delete-screenshot" 
                                        data-id="${screenshot.id}" 
                                        alt="Delete" 
                                        style="cursor: pointer; width: 20px; height: 20px;"
                                        data-toggle="tooltip" data-placement="top" title="Delete"
                                    />
                                </div>
                            </div>
                        `;
                                        });


                                        output += `
                            </div> <!-- end visible -->
                            <div class="screenshot-hidden" id="${groupId}-extra" style="display: none; flex-direction: row; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                    `;

                                        groupScreenshots.slice(screenshotsPerGroup).forEach((screenshot) => {
                                            const matchingActivity = activityDataArray1.find(item => item.screenshot_id == screenshot.id);
                                            const overallActivity = matchingActivity ? (matchingActivity.overall_activity_percent ?? 'N/A') : 'N/A';
                                            let timeWithoutSeconds = screenshot.display_text.split(':').slice(0, 2).join(':');

                                            output += `
                            <div class="screenshot-card" box-sizing: border-box;">
                                <img src="${screenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" style="width: 100%; cursor: pointer;">
                                <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                                      <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                     <svg viewBox="0 0 36 36" width="40" height="40">
                    <!-- Background circle -->
                    <circle
                    cx="18"
                    cy="18"
                    r="15.9155"
                    fill="none"
                    stroke="#e6e6e6"
                    stroke-width="4"
                    />
                    
                    <!-- Progress circle -->
                    <circle
                    cx="18"
                    cy="18"
                    r="15.9155"
                    fill="none"
                    stroke="green"
                    stroke-width="4"
                    stroke-dasharray="${overallActivity} ${100 - overallActivity}"
                    stroke-dashoffset="25"  <!-- makes it start from top -->
                    transform="rotate(-90 18 18)"  <!-- rotates start point to top -->
                />
            </svg>
            <div style="position: absolute; top: 50%; left: 50%; 
                        transform: translate(-50%, -50%);
                        font-size: 10px; font-weight: bold;cursor: pointer;"
                        data-toggle="tooltip" data-placement="top" title="${overallActivity}%">
                ${overallActivity}%
                                </div>
                            </div> <p>${timeWithoutSeconds}</p>
                                    <img 
                                        src="https://img.icons8.com/?size=50&id=4887&format=png" 
                                        class="delete-screenshot" 
                                        data-id="${screenshot.id}" 
                                        alt="Delete" 
                                        style="cursor: pointer; width: 20px; height: 20px;"
                                        data-toggle="tooltip" data-placement="top" title="Delete"
                                    />
                                </div>
                            </div>
                        `;
                                        });

                                        output += `
                            </div> <!-- end hidden -->
                    `;

                                        if (groupScreenshots.length > screenshotsPerGroup) {
                                            output += `
                            <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                                <button class="toggle-button" data-target="${groupId}-extra" style="padding: 6px 12px; font-size: 13px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">See More</button>
                            </div>
                        `;
                                        }

                                        output += `</div> <!-- end screenshot group -->`;
                                    });

                                    $(".screenshot-container").html(output);
                                    if (date === new Date().toISOString().split('T')[0]) {
                                        setTimeout(() => loadScreenshots(), 60000);
                                    }

                                    // Zoom modal
                                    $(".see-zoomable-screenshot").on('click', function() {
                                        $('#modal-image').attr('src', $(this).attr('src'));
                                        $('#screenshot-modal').fadeIn();
                                    });

                                    $('#close-modal').on('click', function() {
                                        $('#screenshot-modal').fadeOut();
                                    });

                                    // Toggle See More / See Less
                                    $('.toggle-button').on('click', function() {
                                        const targetId = $(this).data('target');
                                        const target = $(`#${targetId}`);
                                        const isVisible = target.is(':visible');

                                        if (isVisible) {
                                            target.slideUp();
                                            $(this).text('See More');
                                        } else {
                                            target.css('display', 'flex').hide().slideDown();
                                            $(this).text('See Less');
                                        }
                                    });

                                    // Delete screenshot
                                    $(".delete-screenshot").on('click', function() {
                                        const screenshotId = $(this).data("id");
                                     const message = `Are you sure you want to delete the Screenshot?`;
                                showConfirmationAlert(message, "warning", function() {
                                            $.ajax({
                                                url: "/admin/ScreenshotController/soft_delete_screenshot",
                                                type: "POST",
                                                data: {
                                                    screenshot_id: screenshotId,
                                                    employee_id: id
                                                },
                                                success: function() {
                                                    if (response.status == 1) {
                                                        swal("Failed!", response.message || "Could not delete Screenshot.", "error");
                                                        loadUserRolePermissions(userId);
                                                    } else {
                                                        swal("Deleted!", "Screenshot deleted successfully.", "success");
                                                    }
                                                    loadScreenshots();
                                                    fetchUserScreenshots(id, date);
                                                },
                                                error: function(xhr) {
                                                swal("Error!", "Something went wrong.", "error");
                                                }
                                            });
                                        }); 
                                    });

                                    // Auto-refresh after 5 minutes from the latest timestamp
                                    // let latestTime = response.screenshots[response.screenshots.length - 1].timestamp;
                                    // if (latestTime) {
                                    //     if (nextFetchTimeout) clearTimeout(nextFetchTimeout);

                                    //     const latestDate = new Date(latestTime);
                                    //     const now = new Date();
                                    //     const timeDiff = 100;
                                    //      console.log("hii");

                                    //     nextFetchTimeout = setTimeout(() => {
                                    //         const currentDate = latestDate.toISOString().split("T")[0];
                                    //         $('#datePicker').val(currentDate);
                                    //         loadScreenshots(); // Auto-refresh
                                    //     }, timeDiff);
                                    // }

                                } else {
                                    $(".screenshot-container").html("<p>No screenshots available.</p>");
                                }
                            },
                            error: function(status, error) {
                                console.error("AJAX Error: " + error);
                            }

                        });
                    } catch (error) {
                        // console.error("Error fetching screenshots:", error);

                        showToast(error, "error");
                    }

                }




                loadScreenshots();
            });
            // let activityDataArray = []; // store activity data globally


            // Main fetch function with date and search
            $(document).ready(function() {
                function fetchUsers(search = '', date = '') {
                    let endpoint = search ? '/admin/ScreenshotController/search_employees_by_name_by_user' : '/admin/ScreenshotController/list_employees_by_user';

                    $.ajax({
                        url: endpoint,
                        type: 'GET',
                        data: {
                            search: search,
                            date: date
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            const employees = response.employees || [];

                            if (response.status === 'success' && employees.length > 0) {
                                $.each(employees, function(index, employee) {
                                    output += `
                                <div class="user-card box" id="user-${employee.id}">
                                    <div class="user-info-line box-header">
                                        <div><i class="bi bi-hash"></i> <span> Id:</span> ${employee.id}</div>
                                        <div><i class="bi bi-person-fill"></i> <span> Name:</span> ${employee.name || 'N/A'}</div>
                                        <div><i class="bi bi-briefcase-fill"></i> <span> Designation:</span> ${employee.designation || 'N/A'}</div>
                                        <div><i class="bi bi-bar-chart-fill"></i> <span> Productivity Level:</span> ${employee.productivity || 'N/A'}%</div>
                                    </div>
                                    <div class="timestamp-boxes">
                                        <div class="screenshot-row"></div>
                                    </div>
                                    <div style="text-align: right;">
                                        <button class="see-more-button" data-name="${employee.name}" data-id="${employee.id}">See More</button>
                                    </div>
                                </div>`;
                                });

                                $(".card-container").html(output);

                                employees.forEach(function(employee) {
                                    fetchUserScreenshots(employee.id, date);
                                });
                            } else {
                                $(".card-container").html("<p>No employees found.</p>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                        }
                    });
                }

                // Initial fetch
                fetchUsers();

                // Search & Date filtering
                function triggerFilter() {
                    const keyword = $('#search-name').val().trim();
                    const date = $('#datePicker').val();
                    fetchUsers(keyword, date);
                }

                $('#search-name').on('input', triggerFilter);
                $('#datePicker').on('change', triggerFilter);
                $('#search-btn').on('click', triggerFilter);

            });
        </script>