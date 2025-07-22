<div class="content-wrapper screenshot">
    <section class="content">
        <!-- Screenshot Section -->
        <div class="container">
            <h3><?php echo "Webcam  Screenshots" ?>
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


            function closePopup() {
                $('#popupCard').hide();
                $('.container').show();
            }

            function loadScreenshots() {
                let date = $('#datePicker').val();
                $.ajax({
                    url: "<?= base_url('admin/ScreenshotController/get_webcam_screenshots'); ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
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
                                                <div class="thumbnail-item ${isActive ? 'active-thumbnail' : ''}" 
                                                    style="border:2px solid white;cursor: pointer; transition: all 0.3s ease; border-radius: 8px; overflow: hidden; position: relative;"
                                                    data-src="${screenshot.image_url}"
                                                    data-time="${timeWithoutSeconds}"
                                                    data-id="${screenshot.id}">
                                                    
                                                    <img src="${screenshot.image_url}" 
                                                        style="width: 100%; height: 100px; object-fit: cover; display: block; filter:transition: filter 0.3s;">
                                                    
                                                    <div class="thumbnail-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 8px; font-size: 12px;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                            
                                                        <span>${timeWithoutSeconds}</span>
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
                                if (date === new Date().toISOString().split('T')[0]) {
                                    loadScreenshots()
                                }
                            }, 60000);

                        } else {
                            console.log("no screenshot");

                            $(".card-container").html(`
                            <div class="box">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">
                                        <strong class="text-right">No Webcam Images available.</strong>
                                    </h3>
                                </div>
                            </div>
                        `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }
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

            $(document).ready(function() {
                const today = new Date().toISOString().split('T')[0];
                $('#datePicker').val(today);
                loadScreenshots()
                $('#sortOrder').on('change', function() {
                    currentSortOrder = $(this).val();
                    loadScreenshots();
                });
                $('#datePicker').on('change', function() {
                    loadScreenshots(); // No need to manually clear here
                });
            });

            // Close modal when clicking outside content
            $('#screenshot-modal').on('click', function(e) {
                // Check if the clicked target is the modal background
                if ($(e.target).is('#screenshot-modal')) {
                    $('#screenshot-modal').fadeOut();
                }
            });
        </script>