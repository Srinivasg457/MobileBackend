<div class="content-wrapper screenshot">
    <section class="content">
        <!-- Screenshot Section -->
        <div class="container">
            <h3><?php echo "View Screenshots" ?>
            </h3>
            <div class="row mb-5 reprt-box">
                <div class="form-group col-lg-4 my-3">
                    <label class="control-label">Date</label>
                    <div class="input-group">
                        <input type="text" id="datePicker" class="inv-dpick form-control datepicker" value="">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group col-lg-4 my-3"></div>

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
            let currentSortOrder = 'ascending';
            const today = new Date().toISOString().split('T')[0];

            $('#datePicker').val(today);
            loadScreenshots();
            $(document).ready(function() {
                $('#sortOrder').on('change', function() {
                    currentSortOrder = $(this).val();
                    loadScreenshots();
                });


                $('#datePicker').on('change', function() {
                    loadScreenshots();
                });

                // Close modal when clicking outside content
                function closeScreenshotModal() {
                    $('#screenshot-modal').fadeOut();
                    loadScreenshots();
                }

                // Close on close button click
                $('#close-modal').on('click', closeScreenshotModal);

                // Close when clicking outside modal content
                $('#screenshot-modal').on('click', function(e) {
                    if (!$(e.target).closest('#screenshot-modal > div').length) {
                        closeScreenshotModal();
                    }
                });

            });


            function getActivityColor(percentage) {
                if (percentage >= 70) return '#4CAF50'; // Green
                if (percentage >= 40) return '#FFC107'; // Amber
                return '#F44336'; // Red
            }

            function loadScreenshots() {
                let date = $("#datePicker").val();
                $.ajax({
                    url: "<?= base_url('admin/ScreenshotController/get_screenshots'); ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
                        date
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
                                output += `<div class="screenshot-group box" style="border-width: 1px;
                             border-bottom-style: solid; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                            <div class="box-header" style="font-weight: bold; margin-bottom: 10px;">Time: ${hourRange}</div>
                            <div class="screenshot-visible">`;

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

                                    // Step 1: Compute average activity
                                    let total = 0;
                                    let count = 0;

                                    screenshotsInInterval.forEach(screenshot => {
                                        const value = screenshot.percentage ? parseInt(screenshot.percentage) : 0;
                                        total += value;
                                        count++;
                                    });

                                    const averageActivity = count > 0 ? Math.round(total / count) : 0;

                                    // Step 2: Find screenshot closest to 5-minute mark
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

                                    // Step 3: Generate HTML using average activity
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
                                    <div class="thumbnail-item ${isActive ? 'active-thumbnail' : ''}" 
                                        style="border:2px solid white;cursor: pointer; transition: all 0.3s ease; border-radius: 8px; overflow: hidden; position: relative;"
                                        data-src="${screenshot.image_url}"
                                        data-time="${timeWithoutSeconds}"
                                        data-activity="${overallActivity}"
                                        data-id="${screenshot.id}">

                                        <img src="${screenshot.image_url}" 
                                            style="width: 100%; height: 100px; object-fit: cover; display: block; filter: transition; transition: filter 0.3s;">

                                        <div class="thumbnail-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 8px; font-size: 12px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                                <span style="color: ${getActivityColor(overallActivity)}; font-weight: bold;">${overallActivity}%</span>
                                                <span>${timeWithoutSeconds}</span>
                                            </div>
                                        </div>
                                    </div>
                                `);
                                });

                                // Show modal
                                $('#screenshot-modal').fadeIn();

                                // Auto-refresh if current date
                                setTimeout(function() {
                                    if (date === new Date().toISOString().split('T')[0]) {
                                        loadScreenshots()
                                    }
                                }, 60000);
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
                        console.error("AJAX Error:", status, error);
                        $(".card-container").html("<p>Error loading screenshots. Please try again.</p>");
                    }
                });

            }
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
        </script>