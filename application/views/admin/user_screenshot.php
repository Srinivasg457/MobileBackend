<div class="content-wrapper screenshot">
    <section class="content">
        <!-- Screenshot Section -->
        <div class="container-fluid">
            <h3><?php echo "View Screenshots" ?>
            </h3>
            <div class="row mb-5 reprt-box">
                <div class="form-group col-lg-4 my-3">
                    <label class="control-label">Employee</label>
                    <select id="employeeSelect" class="form-control single_select"></select>
                </div>

                <div class="form-group col-lg-4 my-3">
                    <label class="control-label">Date</label>
                    <div class="input-group">
                        <input type="text" id="datePicker" class="inv-dpick form-control datepicker" value="">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <div class="form-group col-lg-4 my-3">
                    <label class="control-label">Sort By</label>
                    <select id="sortOrder" class="form-control single_select">
                        <option value="ascending">Ascending</option>
                        <option value="descending">Descending</option>
                    </select>
                </div>
            </div>
            <div class="row mt-20">
                <div class="col-12">
                    <div class="box">
                        <div id="noEmployeeMessage" style="display:none;" class="box-header with-border text-center">
                            <h3 class="box-title">
                                <strong class="text-right">No Screenshots Available.</strong>
                            </h3>
                        </div>
                    </div>
                    <div class="card-container d-flex flex-column mt-4"></div>
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

        <script>
            let currentSortOrder = 'ascending';
            $(document).ready(function() {

                $('#datePicker').on('change', function() {
                    console.log("onchage");
                    let employeeId = $('#employeeSelect').val();
                    let currentEmployeeId = employeeId;
                    const date = $(this).val();

                    loadScreenshots(currentEmployeeId, date); // No need to manually clear here
                });
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
                            $('#noEmployeeMessage').hide(); // ✅ hide message
                            employeeSelect.empty();
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
                            $('#noEmployeeMessage').show(); // ✅ show message
                            $(".card-container").empty(); // clear screenshots
                        }
                    },


                    error: function() {
                        $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
                        $('#noEmployeeMessage').show(); // ✅ show message
                        $(".card-container").empty(); // clear screenshots
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
                    // Show loading message before request
                    beforeSend: function() {
                        $(".card-container").html(`
                            <div class="box-header with-border text-center">
                                <h4 class="box-title">
                                    loading...
                                </h4>
                            </div>
                    `);
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

                            // Sort hour ranges
                            let sortedHourRanges = Object.keys(groupedByHour);
                            sortedHourRanges.sort((a, b) => {
                                const startHourA = parseInt(a.split(":")[0], 10);
                                const startHourB = parseInt(b.split(":")[0], 10);
                                return currentSortOrder === 'ascending' ?
                                    startHourA - startHourB :
                                    startHourB - startHourA;
                            });

                            // Process each hour group
                            sortedHourRanges.forEach(hourRange => {
                                const screenshots = groupedByHour[hourRange];
                                output += `<div class="screenshot-group box" style="border-width: 1px; border-bottom-style: solid; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                        <div class="box-header" style="font-weight: bold; margin-bottom: 10px;">Time: ${hourRange}</div>
                        <div class="screenshot-visible" style="gap: 10px;">`;

                                // Group by 10-minute intervals
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

                                // One screenshot per interval
                                Object.keys(intervalScreenshots).forEach(intervalKey => {
                                    const screenshotsInInterval = intervalScreenshots[intervalKey];

                                    // Average activity
                                    let total = 0,
                                        count = 0;
                                    screenshotsInInterval.forEach(screenshot => {
                                        total += screenshot.percentage ? parseInt(screenshot.percentage) : 0;
                                        count++;
                                    });
                                    const averageActivity = count > 0 ? Math.round(total / count) : 0;

                                    // Closest to 5-minute mark
                                    let closestScreenshot = null,
                                        smallestDiff = Infinity;
                                    const targetMinute = parseInt(intervalKey.split(':')[1]) + 5;

                                    screenshotsInInterval.forEach(screenshot => {
                                        const minutes = parseInt(screenshot.display_text.split(':')[1]);
                                        const diff = Math.abs(minutes - targetMinute);
                                        if (diff < smallestDiff) {
                                            smallestDiff = diff;
                                            closestScreenshot = screenshot;
                                        }
                                    });

                                    // Build card
                                    if (closestScreenshot) {
                                        const timeWithoutSeconds = closestScreenshot.display_text.split(':').slice(0, 2).join(':');
                                        output += `<div class="screenshot-card box" style="box-sizing: border-box;">
                                <img src="${closestScreenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" 
                                    style="width: 100%; cursor: pointer;"
                                    data-interval="${intervalKey}"
                                    data-hour-range="${hourRange}"
                                    data-activity-percent="${averageActivity}">
                                <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                                    <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                                        <svg viewBox="0 0 36 36" width="40" height="40">
                                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e6e6e6" stroke-width="4"/>
                                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="green" stroke-width="4"
                                                stroke-dasharray="${averageActivity} ${100 - averageActivity}"
                                                stroke-dashoffset="25"
                                                transform="rotate(0 18 18)"
                                            />
                                        </svg>
                                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 10px; font-weight: bold; cursor: pointer;"
                                            data-toggle="tooltip" data-placement="top" title="${averageActivity}%">
                                            ${averageActivity}%
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

                            // Zoom modal handler
                            $(".see-zoomable-screenshot").on('click', function() {
                                const interval = $(this).data('interval');
                                const hourRange = $(this).data('hour-range');
                                const activity = $(this).data('activity-percent');
                                const clickedTime = $(this).closest('.screenshot-card').find('p').text();
                                const clickedImageUrl = $(this).attr('src');

                                // Find all screenshots in same interval
                                const allScreenshotsInInterval = groupedByHour[hourRange].filter(screenshot => {
                                    const [hours, minutes] = screenshot.display_text.split(':').map(Number);
                                    const screenshotInterval = Math.floor(minutes / 10) * 10;
                                    return `${hours}:${screenshotInterval}` === interval;
                                }).sort((a, b) => a.display_text.localeCompare(b.display_text));

                                // Update modal
                                $('#modal-image').attr('src', clickedImageUrl);
                                $('#image-info').html(`
                        <span style="display: inline-block; margin: 0 10px;">${clickedTime}</span>
                        <span style="display: inline-block; margin: 0 10px;">•</span>
                        <span style="display: inline-block; margin: 0 10px;">
                            Activity: <span style="color: ${getActivityColor(activity)}; font-weight: bold;">${activity}%</span>
                        </span>
                    `);

                                // Thumbnails
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
                                        <?php if ($can_edit): ?>
                                        <div data-id="${screenshot.id}" data-empID="${$('#employeeSelect').val()}" class="delete-thumbnail" style="width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                                            <img src="<?php echo base_url('assets/images/filled-trash.png') ?>" style="width: 100%; height: 100%; border-radius: 50%;" />
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        `);
                                });

                                $('#screenshot-modal').fadeIn();

                                // Auto-refresh
                                // setTimeout(function() {
                                //     let date = $('#datePicker').val();
                                //     let id = $('#employeeSelect').val();
                                //     if (date === new Date().toISOString().split('T')[0]) {
                                //         loadScreenshots(id, date);
                                //     }
                                // }, 6000);
                            });
                        } else {
                            $(".card-container").html(`
                    <div class="box">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title">
                                <strong class="text-right">No Screenshots Available.</strong>
                            </h3>
                        </div>
                    </div>
                `);
                        }
                    },
                    error: function(xhr, status, error) {
                        $(".card-container").html("<p>Error loading screenshots. Please try again.</p>");
                    },
                    complete: function() {

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
