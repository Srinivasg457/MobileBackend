<div class="content-wrapper">
    <section class="content">
        <!-- Screenshot Section -->
        <div class="container">
            <h3><?php echo "Webcam Screenshots" ?>
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
            const today = new Date().toISOString().split('T')[0];
            $('#datePicker').val(today);


            function closePopup() {
                $('#popupCard').hide();
                $('.container').show();
            }

            function loadScreenshots(id, date) {

                $.ajax({
                    url: "<?= base_url('admin/ScreenshotController/get_webcam_screenshots'); ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
                        employee_id: id,
                        date: date
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.status === "success" && response.message === null) {
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
                            Object.keys(groupedByHour).sort((a, b) => {
                                const hourA = parseInt(a.split(':')[0]);
                                const hourB = parseInt(b.split(':')[0]);
                                return currentSortOrder === 'descending' ? hourB - hourA : hourA - hourB;
                            }).forEach(hourRange => {
                                const screenshots = groupedByHour[hourRange];

                                const groupId = `group-${hourRange.replace(/[^a-zA-Z0-9]/g, "")}`;
                                output += `<div class="screenshot-group box" style="border-width: 1px;
                                 border-bottom-style: solid; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                                <div class="box-header" style="font-weight: bold; margin-bottom: 10px;">Time: ${hourRange}</div>
                                <div class="screenshot-visible">`;

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
                                        let timeWithoutSeconds = closestScreenshot.display_text.split(':').slice(0, 2).join(':');

                                        output += `<div class="screenshot-card box" style="box-sizing: border-box;">
                                        <img src="${closestScreenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" 
                                            style="width: 100%; cursor: pointer;"
                                            data-interval="${intervalKey}"
                                            data-hour-range="${hourRange}">
                                        <div style="margin-top:10px;">
                                          
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

                                $('#image-info').html(`
                                        <span style="display: inline-block; margin: 0 10px;">${clickedTime}</span>
                                    `);

                                // Clear and rebuild thumbnails
                                $('#modal-additional-screenshots').empty();

                                allScreenshotsInInterval.forEach(screenshot => {
                                    const timeWithoutSeconds = screenshot.display_text.split(':').slice(0, 2).join(':');
                                    const isActive = screenshot.image_url === clickedImageUrl;

                                    $('#modal-additional-screenshots').append(`
                                                <div id="screenshot-${screenshot.id}" class="thumbnail-item ${isActive ? 'active-thumbnail' : ''}" 
                                                    style="border:2px solid white;cursor: pointer; transition: all 0.3s ease; border-radius: 8px; overflow: hidden; position: relative;"
                                                    data-src="${screenshot.image_url}"
                                                    data-time="${timeWithoutSeconds}"
                                                    data-id="${screenshot.id}">
                                                    
                                                    <img id="main-image"  src="${screenshot.image_url}" 
                                                            style="width: 100%; height: 100px; object-fit: cover; display: block; filter: transition; transition: filter 0.3s;">
                                                    
                                                    <div class="thumbnail-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 8px; font-size: 12px;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                            
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

                                // Show the modal
                                $('#screenshot-modal').show();

                                // Handle thumbnail clicks
                                $('.thumbnail-item').on('click', function() {
                                    const newSrc = $(this).data('src');
                                    const newTime = $(this).data('time');
                                    const newActivity = $(this).data('activity');

                                    // Update main image
                                    $('#modal-image').attr('src', newSrc);
                                    $('#image-info').html(`
                                    <span style="display: inline-block; margin: 0 10px;">${newTime}</span>
                                `);

                                    // Update active state
                                    $('.thumbnail-item').removeClass('active-thumbnail')
                                        .find('img').css('filter', 'brightness(0.7)');
                                    $(this).addClass('active-thumbnail')
                                        .find('img').css('filter', 'none');

                                    // Move to center if possible
                                    this.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'nearest',
                                        inline: 'center'
                                    });
                                });
                            });

                            // Helper function for activity colors
                            function getActivityColor(percentage) {
                                if (percentage >= 70) return '#4CAF50'; // Green
                                if (percentage >= 40) return '#FFC107'; // Amber
                                return '#F44336'; // Red
                            }

                            // Auto-refresh if current date
                            setTimeout(function() {
                                // $('#screenshot-modal').fadeOut();
                                let date = $('#datePicker').val();
                                let id = $('#employeeSelect').val();
                                if (date === new Date().toISOString().split('T')[0]) {
                                    loadScreenshots(id, date)
                                }
                            }, 60000);

                        } else {
                            $(".card-container").html(`
                            <div class="box">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">
                                        <strong class="text-right">No Webcam Screenshots Available.</strong>
                                    </h3>
                                </div>
                            </div>
                        `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        $(".card-container").html("<p>Error loading Webcam Images. Please try again.</p>");
                    }
                });
            }


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
                        employeeSelect.empty().append(`<option value="">-- Select Employee --</option>`);

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
                    const date = $('#datePicker').val();

                    loadScreenshots(currentEmployeeId, date); // No need to manually clear here
                });
                $('#datePicker').on('change', function() {
                    console.log("onchage");
                    let employeeId = $('#employeeSelect').val();
                    let currentEmployeeId = employeeId;
                    const date = $(this).val();

                    loadScreenshots(currentEmployeeId, date); // No need to manually clear here
                });

            });

            // Close modal when clicking outside content
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
            $(document).on('click', '.delete-thumbnail', function() {
                const screenshotId = $(this).data('id');
                const empId = $(this).data('empid');
                const $thumbnail = $(this); // store reference for removing on success
                console.log(screenshotId, empId);


                showConfirmationAlert("Are you sure you want to delete this webcam screenshot?", "warning", function() {
                    $.ajax({
                        url: "<?= base_url('admin/ScreenshotController/delete_webcam_screenshot'); ?>",
                        method: 'POST',
                        data: {
                            webcam_id: screenshotId,
                            employee_id: empId
                        },
                        success: function(response) {

                            if (response.message === "Webcam screenshot marked as deleted.") {
                                swal("Deleted!", "Webcam screenshot has been deleted.", "success");

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