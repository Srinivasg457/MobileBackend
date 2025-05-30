<style>
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
        margin: 20px 0;
    }

    .search-row input,
    .search-row select {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .card-container {
        width: 100%;
        display: flex;
        flex-direction: column;

        >p {
            text-align: center;
        }
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

    .screenshot-visible {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
        /* 6 equal columns */
    }

    .screenshot-card {
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
        height: 110px;
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

    /* Add these styles to your CSS */
    .delete-thumbnail {
        opacity: 1;
        /* Changed from 0 to 1 to make it always visible */
        transform: scale(1);
        /* Changed from 0.8 to 1 */
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

    #screenshot-modal {
        display: none;
        position: fixed;
        z-index: 1111;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        overflow-y: auto;

        >div {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;

            >#close-modal {
                display: block;
                font-size: 40px;
                color: white;
                cursor: pointer;
                text-align: end;
                text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
            }

            .main-image-container {
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 30px;
                position: relative;
                height: 70%;

                >#modal-image {
                    max-width: 100%;
                    max-height: 100%;
                    height: 100%;
                    display: block;
                    margin: 0 auto;
                    border-radius: 8px;
                    border: 2px solid white;
                }

                >#image-info {
                    color: white;
                    text-align: center;
                    margin-top: 15px;
                    font-size: 16px;
                    opacity: 0.9;
                }
            }

            >.thumbnail-gallery {
                border-radius: 12px;
                padding: 20px;
                max-width: auto;
                margin: 0 auto;
                text-align: center;

                >#modal-additional-screenshots {
                    display: grid;
                    grid-template-columns: repeat(6, auto);

                    .thumbnail-item {
                        margin: 0px 5px;
                    }
                }
            }
        }
    }

    /* Add these styles to your CSS */
    #screenshot-modal {
        transition: opacity 0.3s ease;
    }

    .thumbnail-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .thumbnail-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    #modal-additional-screenshots::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    #modal-additional-screenshots::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }


    @media (min-width: 1600px) {
        .container {
            width: auto !important;
            max-width: none !important;
        }
    }

    /* ---------- Mobile Responsive Styling ---------- */
    @media (max-width: 1024px) {
        #screenshot-modal {
            &>div {
                &>.thumbnail-gallery {
                    >#modal-additional-screenshots {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 10px;
                    }
                }
            }
        }


        .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-row {
            /* flex-direction: column; */
            align-items: stretch;
        }

        .screenshot-visible {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
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

    @media (max-width: 500px) {
        #screenshot-modal {
            &>div {
                &>.thumbnail-gallery {
                    >#modal-additional-screenshots {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 10px;
                    }
                }
            }
        }


        .screenshot-visible {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }
</style>
<div class="content-wrapper">
    <section class="content" style="padding-top: 0;">
        <!-- Screenshot Section -->
        <div class="container">
            <div class="header">
                <h3>Screenshots</h3>
            </div>

            <!-- Search Filters -->
            <div class="search-row row">
                <div class="col-lg-4  my-3">
                    <select id="employeeSelect" class="form-control single_select"></select>
                </div>
                <div class="col-lg-4 my-3">
                    <input type="date" id="datePicker" class="form-control" value="">
                </div>
                <div class="col-lg-4 my-3">
                    <select id="sortOrder" class="form-control">
                        <option value="">Sort By</option>
                        <option value="ascending">Ascending</option>
                        <option value="descending">Descending</option>
                    </select>
                </div>
            </div>

            <div class="box" style="background-color: #F4F6F9; box-shadow: none !important">
                <div class="box-header with-border">
                    <!-- <div class="row">
                           <div class="col-lg-6">
                               <h3 class="box-title"><strong class="text-right">Employee Name: <span id="employeeName"></span> </strong></h3>
                           </div>
                           <div class="col-lg-6">
                               <h3 class="box-title"><strong class="text-right">Role: <span id="employRole"></span>#</strong></h3>
                           </div>
                       </div> -->
                </div>
                <div class="box-body" style="padding: inherit !important;">
                    <div class="card-container"></div>
                </div>
            </div>
        </div>

        <!-- Modal for Screenshot Preview -->
        <div id="screenshot-modal">

            <div>
                <!-- Main Image Container -->
                <span id="close-modal">&times;</span>

                <div class="main-image-container">
                    <img id="modal-image">
                    <div id="image-info"></div>
                </div>

                <!-- Thumbnail Gallery -->
                <div class="thumbnail-gallery">
                    <div id="modal-additional-screenshots">

                        <!-- Repeat for additional images -->
                    </div>
                </div>

            </div>
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
            let currentSortOrder = 'ascending';
            $(document).ready(function() {
                const today = new Date().toISOString().split('T')[0];
                $('#sortOrder').on('change', function() {
                    currentSortOrder = $(this).val();
                    let currentEmployeeId = $('#employeeSelect').val();
                    let date = $('#datePicker').val();
                    loadScreenshots(currentEmployeeId, date);
                });
                $('#datePicker').val(today);
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
                            const date = $('#datePicker').val();
                            loadScreenshots(randomEmployee.id, date);
                        } else {
                            employeeSelect.empty().append(`<option value="">-- No employees found --</option>`);
                        }
                    },


                    error: function() {
                        $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
                    }
                });
                $('#employeeSelect').on('change', function() {
                    const employeeNameText = $(this).find('option:selected').text().split(' (')[0];
                    console.log("onchage");
                    let employeeId = $(this).val();
                    let currentEmployeeId = employeeId;
                    date = $('#datePicker').val();

                    loadScreenshots(currentEmployeeId, date); // No need to manually clear here
                });

            });
            const today = new Date().toISOString().split('T')[0];
            $('#datePicker').val(today);
            // Helper function for color coding
            function getActivityColor(percentage) {
                if (percentage >= 70) return '#4CAF50'; // Green
                if (percentage >= 40) return '#FFC107'; // Amber
                return '#F44336'; // Red
            }

            function loadScreenshots(id, date) {
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

                            // Group by hour
                            response.screenshots.forEach((screenshot) => {
                                const time = screenshot.display_text;
                                const hour = time.split(":")[0].padStart(2, '0');
                                const hourLabel = `${hour}:00 - ${String(Number(hour) + 1).padStart(2, '0')}:00`;

                                if (!groupedByHour[hourLabel]) {
                                    groupedByHour[hourLabel] = [];
                                }
                                groupedByHour[hourLabel].push(screenshot);
                            });

                            // Get all hourRange keys and sort based on currentSortOrder
                            let sortedHourRanges = Object.keys(groupedByHour);

                            sortedHourRanges.sort((a, b) => {
                                const startHourA = parseInt(a.split(":")[0], 10);
                                const startHourB = parseInt(b.split(":")[0], 10);

                                if (currentSortOrder === 'ascending') {
                                    return startHourA - startHourB;
                                } else {
                                    return startHourB - startHourA;
                                }
                            });

                            // Process each hour group in sorted order
                            sortedHourRanges.forEach(hourRange => {
                                const screenshots = groupedByHour[hourRange];
                                const groupId = `group-${hourRange.replace(/[^a-zA-Z0-9]/g, "")}`;
                                output += `<div class="screenshot-group box" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                            <div class="box-header" style="font-weight: bold; margin-bottom: 10px;">Time: ${hourRange}</div>
                            <div class="screenshot-visible" style="gap: 10px;">`;

                                // Group by 10-minute intervals within this hour
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

                                // One screenshot per interval (closest to 5-minute mark)
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
                                        const overallActivity = closestScreenshot.percentage ? parseInt(closestScreenshot.percentage) : 0;
                                        const timeWithoutSeconds = closestScreenshot.display_text.split(':').slice(0, 2).join(':');

                                        output += `<div class="screenshot-card" style="box-sizing: border-box;">
                        <img src="${closestScreenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" 
                            style="width: 100%; cursor: pointer;"
                            data-interval="${intervalKey}"
                            data-hour-range="${hourRange}"
                            data-activity-percent="${overallActivity}">
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
                                    data-toggle="tooltip" data-placement="top" title="${overallActivity}%">
                                    ${overallActivity}%
                                </div>
                            </div>
                            <p style="margin: 0; font-size: 12px;">${timeWithoutSeconds}</p>
                        </div>
                        <div class="additional-screenshots" style="display: none; width: 100%; margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 10px;"></div>
                                 </div>`;
                                    }
                                });

                                output += `</div></div>`;
                            });

                            $(".card-container").html(output);

                            // Initialize tooltips
                            $('[data-toggle="tooltip"]').tooltip();

                            // Zoom modal click handler
                            $(".see-zoomable-screenshot").on('click', function() {
                                const interval = $(this).data('interval');
                                const hourRange = $(this).data('hour-range');
                                const activity = $(this).data('activity-percent');
                                const clickedTime = $(this).closest('.screenshot-card').find('p').text();
                                const clickedImageUrl = $(this).attr('src');

                                // Find all screenshots in the same interval
                                const allScreenshotsInInterval = groupedByHour[hourRange].filter(screenshot => {
                                    const [hours, minutes] = screenshot.display_text.split(':').map(Number);
                                    const screenshotInterval = Math.floor(minutes / 10) * 10;
                                    return `${hours}:${screenshotInterval}` === interval;
                                }).sort((a, b) => a.display_text.localeCompare(b.display_text));

                                // Set modal main image and info
                                $('#modal-image').attr('src', clickedImageUrl);
                                $('#image-info').html(`
                            <span style="display: inline-block; margin: 0 10px;">${clickedTime}</span>
                            <span style="display: inline-block; margin: 0 10px;">•</span>
                            <span style="display: inline-block; margin: 0 10px;">
                                Activity: <span style="color: ${getActivityColor(activity)}; font-weight: bold;">${activity}%</span>
                            </span>
                        `);

                                // Clear and add thumbnails
                                $('#modal-additional-screenshots').empty();

                                allScreenshotsInInterval.forEach(screenshot => {
                                    const overallActivity = screenshot.percentage ? parseInt(screenshot.percentage) : 0;
                                    const timeWithoutSeconds = screenshot.display_text.split(':').slice(0, 2).join(':');
                                    const isActive = screenshot.image_url === clickedImageUrl;

                                    $('#modal-additional-screenshots').append(`
                                        <div id="screenshot-${screenshot.id}" class="thumbnail-item ${isActive ? 'active-thumbnail' : ''}" 
                                            style="border:2px solid white;cursor: pointer; transition: all 0.3s ease; border-radius: 8px; overflow: hidden; position: relative;"
                                            data-src="${screenshot.image_url}"
                                            data-time="${timeWithoutSeconds}"
                                            data-activity="${overallActivity}"
                                            data-id="${screenshot.id}">

                                            <img id="main-image" src="${screenshot.image_url}" 
                                                style="width: 100%; height: 100px; object-fit: cover; display: block; filter: transition; transition: filter 0.3s;">

                                            <div class="thumbnail-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 8px; font-size: 12px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                                    <span style="color: ${getActivityColor(overallActivity)}; font-weight: bold;">${overallActivity}%</span>
                                                    <span>${timeWithoutSeconds}</span>
                                                    <div data-id="${screenshot.id}" data-empID="${$('#employeeSelect').val()}" class="delete-thumbnail" style="width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                                                        <img src="<?php echo base_url('assets/images/filled-trash.png') ?>" style="width: 100%; height: 100%; border-radius: 50%;" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        `);
                                });

                                // Show modal
                                $('#screenshot-modal').fadeIn();
                            });
                        } else {
                            $(".card-container").html(
                                `<div class="box">
                            <div class="box-header with-border text-center">
                                <h3 class="box-title">
                                    <strong class="text-right">No screenshots available.</strong>
                                </h3>
                            </div>
                        </div>`
                            );
                        }
                    },

                    error: function(xhr, status, error) {
                        $(".card-container").html("<p>Error loading screenshots. Please try again.</p>");
                    }
                });
            }

            function closeScreenshotModal() {
                $('#screenshot-modal').fadeOut();
                const date = $('#datePicker').val();
                const id = $('#employeeSelect').val();
                loadScreenshots(id, date);
            }

            // Close on close button click
            $('#close-modal').on('click', closeScreenshotModal);

            // Close when clicking outside modal content
            $('#screenshot-modal').on('click', function(e) {
                if (!$(e.target).closest('#screenshot-modal > div').length) {
                    closeScreenshotModal();
                }
            });



            $('#datePicker').on('change', function() {
                console.log("onchage");
                let employeeId = $('#employeeSelect').val();
                let currentEmployeeId = employeeId;
                const date = $(this).val();

                loadScreenshots(currentEmployeeId, date); // No need to manually clear here
            });
            // Delegate click handler on thumbnails inside modal additional screenshots container
            $('#modal-additional-screenshots').on('click', '.thumbnail-item', function() {
                const newSrc = $(this).data('src');
                const newTime = $(this).data('time');
                const newActivity = $(this).data('activity');

                // Update main image src
                $('#modal-image').attr('src', newSrc);

                // Update image info
                $('#image-info').html(`
                        <span style="display: inline-block; margin: 0 10px;">${newTime}</span>
                        <span style="display: inline-block; margin: 0 10px;">•</span>
                        <span style="display: inline-block; margin: 0 10px;">
                            Activity: <span style="color: ${getActivityColor(newActivity)}; font-weight: bold;">${newActivity}%</span>
                        </span>
                    `);

                // Update active thumbnail highlight
                $('.thumbnail-item').removeClass('active-thumbnail');
                $(this).addClass('active-thumbnail');
            });
            // Close modal when clicking outside content
            $('#screenshot-modal').on('click', function(e) {
                // Check if the clicked target is the modal background
                if ($(e.target).is('#screenshot-modal')) {
                    $('#screenshot-modal').fadeOut();
                }
            });
            $(document).on('click', '.delete-thumbnail', function() {
                const screenshotId = $(this).data('id');
                const empId = $(this).data('empid');
                const $thumbnail = $(this); // store reference for removing on success
                console.log(screenshotId, empId);


                showConfirmationAlert("Are you sure you want to delete this screenshot?", "warning", function() {
                    $.ajax({
                        url: "<?= base_url('admin/ScreenshotController/soft_delete_screenshot'); ?>",
                        method: 'POST',
                        data: {
                            screenshot_id: screenshotId,
                            employee_id: empId
                        },
                        success: function(response) {

                            if (response === "Soft deleted successfully.") {
                                swal("Deleted!", "Screenshot has been deleted.", "success");

                                const $deletedThumb = $(`#screenshot-${screenshotId}`);

                                // Try to get the next or previous sibling BEFORE removing the element
                                let $nextThumb = $deletedThumb.next('.thumbnail-item');
                                if ($nextThumb.length === 0) {
                                    $nextThumb = $deletedThumb.prev('.thumbnail-item');
                                }

                                // Fade out and remove the deleted thumbnail
                                $deletedThumb.fadeOut(300, function() {
                                    $(this).remove();

                                    const remainingItems = $('.thumbnail-item');
                                    console.log(remainingItems.length);

                                    if (remainingItems.length === 0) {
                                        closeScreenshotModal();
                                        $('#screenshot-modal').fadeOut();
                                    } else if ($nextThumb.length > 0) {
                                        // Add active class to the nearest thumb
                                        $nextThumb.addClass('active-thumbnail');

                                        // Simulate click to reload the modal with the selected thumbnail
                                        console.log($nextThumb.find('img'));
                                        $nextThumb.find('#main-image').trigger('click');
                                    }
                                });
                            } else {
                                swal("Error", response.message || "Something went wrong!", "error");
                            }
                        },
                        error: function(error) {
                            swal("Error", "Failed to communicate with the server.", "error");
                        }
                    });
                });
            });
        </script>