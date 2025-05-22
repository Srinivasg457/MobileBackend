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
            /* Add these styles to your CSS */
            .delete-thumbnail {
    opacity: 1; /* Changed from 0 to 1 to make it always visible */
    transform: scale(1); /* Changed from 0.8 to 1 */
    transition: all 0.2s ease;
}



.delete-thumbnail:hover {
    transform: scale(1.1);
}


.thumbnail-item.deleting {
    transform: scale(0.8) translateY(20px);
    opacity: 0;
    transition: all 0.3s ease;
}
        </style>


        <!-- Popup Div -->
        <div class="popup" id="popupCard">
            <div class="d-flex justify-content-between align-items-baseline mb-5">
                <h5><strong>Name:</strong> <span id="popupName"></span></h5>
                <!-- <h5><strong>User ID:</strong> <span id="popupID"></span></h5> -->
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
        <div id="screenshot-modal" style="display: none; position: fixed; z-index: 1111; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); ">
    <span id="close-modal" style="position: fixed; top: 30px; right: 40px; font-size: 40px; color: white; cursor: pointer; z-index: 2; text-shadow: 0 0 5px rgba(0,0,0,0.5);">&times;</span>
    
    <div style="max-width: 1200px; margin: 80px auto; padding: 20px;">
        <!-- Main Image Container -->
        <div class="main-image-container" style="border-radius: 12px; padding: 15px;margin-bottom: 30px; position: relative;">
            <img id="modal-image" style="max-width: 100%; max-height: 70vh; display: block; margin: 0 auto; border-radius: 8px;">
            <div id="image-info" style="color: white; text-align: center; margin-top: 15px; font-size: 16px; opacity: 0.9;"></div>
        </div>
        
        <!-- Thumbnail Gallery -->
        <div class="thumbnail-gallery" style="
    border-radius: 12px;
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
">
    <div id="modal-additional-screenshots" style="
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    ">
     
        <!-- Repeat for additional images -->
    </div>
</div>

    </div>
</div>
<style>
    /* Add these styles to your CSS */
#screenshot-modal {
    transition: opacity 0.3s ease;
}

.thumbnail-item {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.thumbnail-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.active-thumbnail {
    box-shadow: 0 0 0 3px #4CAF50;
}

#modal-image {
    transition: opacity 0.3s ease;
}

/* Custom scrollbar for thumbnails */
#modal-additional-screenshots::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

#modal-additional-screenshots::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}

#modal-additional-screenshots::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 10px;
}

#modal-additional-screenshots::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}
</style>
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
                            data-toggle="tooltip" data-placement="top" title="${Math.round(overallActivity)}%">
                    ${Math.round(overallActivity)}%
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
                        let output = '';
                        const groupedByHour = {};

                        // First group by hour
                        response.screenshots.forEach((screenshot) => {
                            const time = screenshot.display_text;
                            const hour = time.split(":")[0].padStart(2, '0');
                            const hourLabel = `${hour}:00 - ${String(Number(hour) + 1).padStart(2, '0')}:00`;

                            if (!groupedByHour[hourLabel]) {
                                groupedByHour[hourLabel] = [];
                            }
                            groupedByHour[hourLabel].push(screenshot);
                        });

                        // Process each hour
                        $.each(groupedByHour, function(hourRange, screenshots) {
                            const groupId = `group-${hourRange.replace(/[^a-zA-Z0-9]/g, "")}`;
                            output += `<div class="screenshot-group box" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                                <div class="box-header" style="font-weight: bold; margin-bottom: 10px;">Time: ${hourRange}</div>
                                <div class="screenshot-visible" style="display: flex; flex-wrap: wrap; gap: 10px;">`;

                            // Group by 10-minute intervals and find closest to 5-minute mark
                            const intervalScreenshots = {};
                            screenshots.forEach(screenshot => {
                                const time = screenshot.display_text;
                                const [hours, minutes] = time.split(':').map(Number);
                                const interval = Math.floor(minutes / 10) * 10;
                                const intervalKey = `${hours}:${interval}`;

                                if (!intervalScreenshots[intervalKey]) {
                                    intervalScreenshots[intervalKey] = [];
                                }
                                intervalScreenshots[intervalKey].push(screenshot);
                            });

                            // Get one screenshot per interval (closest to 5-minute mark)
                            Object.keys(intervalScreenshots).forEach(intervalKey => {
                                const screenshotsInInterval = intervalScreenshots[intervalKey];
                                let closestScreenshot = null;
                                let smallestDiff = Infinity;
                                const targetMinute = parseInt(intervalKey.split(':')[1]) + 5;

                                screenshotsInInterval.forEach(screenshot => {
                                    const minutes = parseInt(screenshot.display_text.split(':')[1]);
                                    const diff = Math.abs(minutes - targetMinute);
                                    if (diff < smallestDiff) {
                                        smallestDiff = diff;
                                        closestScreenshot = screenshot;
                                    }
                                });

                                if (closestScreenshot) {
                                    const matchingActivity = activityDataArray1.find(item => item.screenshot_id == closestScreenshot.id);
                                    const overallActivity = matchingActivity ? (matchingActivity.overall_activity_percent ?? '0') : '0';
                                    let timeWithoutSeconds = closestScreenshot.display_text.split(':').slice(0, 2).join(':');

                                    output += `<div class="screenshot-card" style="box-sizing: border-box; width: calc(16.666% - 10px);">
                                        <img src="${closestScreenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" 
                                            style="width: 100%; cursor: pointer;"
                                            data-interval="${intervalKey}"
                                            data-hour-range="${hourRange}">
                                        <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                                            <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                                                <svg viewBox="0 0 36 36" width="40" height="40">
                                                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e6e6e6" stroke-width="4"/>
                                                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="green" stroke-width="4"
                                                        stroke-dasharray="${overallActivity} ${100 - overallActivity}"
                                                        stroke-dashoffset="25"
                                                        transform="rotate(-90 18 18)"
                                                    />
                                                </svg>
                                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 10px; font-weight: bold; cursor: pointer;"
                                                    data-toggle="tooltip" data-placement="top" title="${Math.round(overallActivity)}%">
                                                    ${Math.round(overallActivity)}%
                                                </div>
                                            </div>
                                            <p style="margin: 0; font-size: 12px;">${timeWithoutSeconds}</p>
                                            <img src="https://img.icons8.com/?size=50&id=4887&format=png" 
                                                class="delete-screenshot" 
                                                data-id="${closestScreenshot.id}" 
                                                alt="Delete" 
                                                style="cursor: pointer; width: 20px; height: 20px;"
                                                data-toggle="tooltip" data-placement="top" title="Delete"
                                            />
                                        </div>
                                        <div class="additional-screenshots" style="display: none; width: 100%; margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 10px;"></div>
                                    </div>`;
                                }
                            });

                            output += `</div></div>`;
                        });

                        $(".screenshot-container").html(output);

                        // Initialize tooltips
                        $('[data-toggle="tooltip"]').tooltip();

                        // Zoom modal
                        $(".see-zoomable-screenshot").on('click', function() {
    const interval = $(this).data('interval');
    const hourRange = $(this).data('hour-range');
    const clickedTime = $(this).closest('.screenshot-card').find('p').text();
    
    // Find all screenshots in this interval
    const allScreenshotsInInterval = groupedByHour[hourRange].filter(screenshot => {
        const [hours, minutes] = screenshot.display_text.split(':').map(Number);
        const screenshotInterval = Math.floor(minutes / 10) * 10;
        return `${hours}:${screenshotInterval}` === interval;
    }).sort((a, b) => a.display_text.localeCompare(b.display_text));
    
    // Set the clicked image as main image
    const clickedImageUrl = $(this).attr('src');
    $('#modal-image').attr('src', clickedImageUrl);
    
    // Set image info
    const matchingActivity = activityDataArray1.find(item => item.screenshot_id == $(this).closest('.screenshot-card').data('id'));
    const overallActivity = matchingActivity ? Math.round(matchingActivity.overall_activity_percent) || 0 : 0;
    $('#image-info').html(`
        <span style="display: inline-block; margin: 0 10px;">${clickedTime}</span>
        <span style="display: inline-block; margin: 0 10px;">•</span>
        <span style="display: inline-block; margin: 0 10px;">
            Activity: <span style="color: ${getActivityColor(overallActivity)}; font-weight: bold;">${overallActivity}%</span>
        </span>
    `);
    
    // Clear and rebuild thumbnails
    $('#modal-additional-screenshots').empty();
    
    allScreenshotsInInterval.forEach(screenshot => {
        const matchingActivity = activityDataArray1.find(item => item.screenshot_id == screenshot.id);
        const overallActivity = matchingActivity ? Math.round(matchingActivity.overall_activity_percent) || 0 : 0;
        const timeWithoutSeconds = screenshot.display_text.split(':').slice(0, 2).join(':');
        const isActive = screenshot.image_url === clickedImageUrl;
        
        $('#modal-additional-screenshots').append(`
    <div class="thumbnail-item ${isActive ? 'active-thumbnail' : ''}" 
         style="cursor: pointer; transition: all 0.3s ease; border-radius: 8px; overflow: hidden; position: relative;"
         data-src="${screenshot.image_url}"
         data-time="${timeWithoutSeconds}"
         data-activity="${overallActivity}"
         data-id="${screenshot.id}">
         
        <img src="${screenshot.image_url}" 
             style="width: 100%; height: 100px; object-fit: cover; display: block; filter: ${isActive ? 'none' : 'brightness(0.7)'}; transition: filter 0.3s;">
        
        <div class="thumbnail-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 8px; font-size: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <span style="color: ${getActivityColor(overallActivity)}; font-weight: bold;">${overallActivity}%</span>
   
            <span>${timeWithoutSeconds}</span>
                <div class="delete-thumbnail" style="width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                    <img src="<?php echo base_url('assets/images/filled-trash.png') ?>" style="width: 100%; height: 100%; border-radius: 50%;" />
                </div>
            </div>
        </div>

        ${isActive ? '<div class="active-indicator" style="position: absolute; top: 5px; right: 5px; width: 12px; height: 12px; background: #4CAF50; border-radius: 50%; border: 2px solid white;"></div>' : ''}
    </div>
`);


    });
// Handle delete button clicks
$('.delete-thumbnail').on('click', function(e) {
    e.stopPropagation();
    const thumbnailItem = $(this).closest('.thumbnail-item');
    const screenshotId = thumbnailItem.data('id');
    const isActive = thumbnailItem.hasClass('active-thumbnail');
    
    showConfirmationAlert(
        "Are you sure you want to delete this screenshot?", 
        "warning", 
        function() {
            $.ajax({
                url: "/admin/ScreenshotController/soft_delete_screenshot",
                type: "POST",
                data: {
                    screenshot_id: screenshotId,
                    employee_id: id
                },
                success: function(response) {
                    if (response.status == 1) {
                        swal("Failed!", response.message || "Could not delete screenshot.", "error");
                    } else {
                        // Visual feedback
                        thumbnailItem.addClass('deleting');
                        
                        // If deleting the active thumbnail, select a new one
                        if (isActive) {
                            const nextThumbnail = $('.thumbnail-item').not(thumbnailItem).first();
                            if (nextThumbnail.length) {
                                nextThumbnail.click();
                            } else {
                                $('#screenshot-modal').fadeOut();
                            }
                        }
                        
                        // Remove after animation
                        setTimeout(() => {
                            thumbnailItem.remove();
                            swal({
                                title: "Deleted!",
                                text: "Screenshot deleted successfully.",
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            // If no more thumbnails, close modal
                            if ($('.thumbnail-item').length === 0) {
                                $('#screenshot-modal').fadeOut();
                            }
                        }, 300);
                        
                        // Refresh the main view
                        setTimeout(loadScreenshots, 500);
                    }
                },
                error: function(xhr) {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        }
    );
});
    // Show the modal
    $('#screenshot-modal').fadeIn();
    
    // Handle thumbnail clicks
    $('.thumbnail-item').on('click', function() {
        const newSrc = $(this).data('src');
        const newTime = $(this).data('time');
        const newActivity = $(this).data('activity');
        
        // Update main image
        $('#modal-image').attr('src', newSrc);
        $('#image-info').html(`
            <span style="display: inline-block; margin: 0 10px;">${newTime}</span>
            <span style="display: inline-block; margin: 0 10px;">•</span>
            <span style="display: inline-block; margin: 0 10px;">
                Activity: <span style="color: ${getActivityColor(newActivity)}; font-weight: bold;">${newActivity}%</span>
            </span>
        `);
        
        // Update active state
        $('.thumbnail-item').removeClass('active-thumbnail')
            .find('img').css('filter', 'brightness(0.7)');
        $(this).addClass('active-thumbnail')
            .find('img').css('filter', 'none');
        
        // Move to center if possible
        this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    });
});

// Helper function for activity colors
function getActivityColor(percentage) {
    if (percentage >= 70) return '#4CAF50'; // Green
    if (percentage >= 40) return '#FFC107'; // Amber
    return '#F44336'; // Red
}
                        // Delete screenshot
                        $(".delete-screenshot").on('click', function(e) {
                            e.stopPropagation();
                            const screenshotId = $(this).data("id");
                            const message = "Are you sure you want to delete this screenshot?";
                            showConfirmationAlert(message, "warning", function() {
                                $.ajax({
                                    url: "/admin/ScreenshotController/soft_delete_screenshot",
                                    type: "POST",
                                    data: {
                                        screenshot_id: screenshotId,
                                        employee_id: id
                                    },
                                    success: function(response) {
                                        if (response.status == 1) {
                                            swal("Failed!", response.message || "Could not delete screenshot.", "error");
                                        } else {
                                            swal("Deleted!", "Screenshot deleted successfully.", "success");
                                            loadScreenshots();
                                        }
                                    },
                                    error: function(xhr) {
                                        swal("Error!", "Something went wrong.", "error");
                                    }
                                });
                            });
                        }); 

                        // Auto-refresh if current date
                        if (date === new Date().toISOString().split('T')[0]) {
                            setTimeout(loadScreenshots, 60000);
                        }

                    } else {
                        $(".screenshot-container").html("<p>No screenshots available for this date.</p>");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $(".screenshot-container").html("<p>Error loading screenshots. Please try again.</p>");
                }
            });
        } catch (error) {
            console.error("Error in loadScreenshots:", error);
            showToast("Error loading screenshots: " + error.message, "error");
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