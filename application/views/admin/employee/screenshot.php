<div class="content-wrapper">
    <section class="content" style="padding-top: 0;">
      <style>
    .popup {
        display: none;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .cancel-btn {
        background-color: black;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        padding: 6px 12px;
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

    .search-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
        flex-wrap: wrap;
    }

    .search-row input,
    .search-row select {
        padding: 8px;
        font-size: 14px;
        width: 100%;
        max-width: 300px;
    }

    .screenshot-row {
        /* display: flex;
        flex-wrap: wrap;
        gap: 10px; */
        border: 1px solid #eee;
        padding: 15px;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-top: 15px;
        overflow-y: auto;
    }

    .screenshot-card {
        /* flex: 1 1 calc(16.66% - 10px); */
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

    #screenshot-modal {
        display: none;
        position: fixed;
        z-index: 1111;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        text-align: center;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    #close-modal {
        position: absolute;
        top: 20px;
        right: 40px;
        font-size: 40px;
        color: white;
        cursor: pointer;
    }

    #modal-image {
        max-width: 90%;
        max-height: 90%;
        border: 5px solid white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
    }

    /* Responsive Breakpoints */
    @media (max-width: 992px) {
        .screenshot-card {
            flex: 1 1 calc(25% - 10px);
        }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-row input,
        .search-row select {
            width: 100%;
        }

        .screenshot-card {
            flex: 1 1 calc(33.33% - 10px);
        }
    }

    @media (max-width: 576px) {
        .screenshot-card {
            flex: 1 1 calc(50% - 10px);
        }

        .cancel-btn {
            font-size: 16px;
            padding: 5px 10px;
        }

        #close-modal {
            font-size: 30px;
            right: 20px;
        }
    }

    @media (max-width: 400px) {
        .screenshot-card {
            flex: 1 1 100%;
        }

        .search-row {
            flex-direction: column;
            gap: 5px;
        }
    }
    @media (min-width: 1600px) {
    .container {
        width: auto !important;
        max-width: 100% !important;
    }
}

</style>


        <!-- Popup Div -->
        <div class="popup" id="popupCard">
            <div class="d-flex justify-content-between mb-5">
                <h5><strong>Name:</strong> <span id="popupName"></span></h5>
                <h5><strong>User ID:</strong> <span id="popupID"></span></h5>
                <button class="cancel-btn" onclick="closePopup()">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="container" style="display: none;">
                <div class="header">
                    <h3>Screenshots</h3>
                </div>
            </div>
            <div class="row screenshot-container"></div>
        </div>

        <!-- Screenshot Section -->
        <div class="container">
            <div class="header">
                <h3>Screenshots</h3>
            </div>

            <!-- Filter only by date -->
            <div class="search-row">
                <input type="date" id="datePicker" value="">
            </div>

            <!-- Main Screenshot Row for Logged-in User -->
            <div class="screenshot-row" id="userScreenshotRow"></div>
        </div>

        <!-- Modal for Screenshot Preview -->
        <div id="screenshot-modal">
            <span id="close-modal">&times;</span>
            <img id="modal-image">
        </div>

        <script>
            function closePopup() {
                $('#popupCard').fadeOut();
                $('.container').fadeIn();
            }

            $(document).ready(function() {
                let lastFetchedTime = null;

                function fetchUserScreenshots(date = '') {
                    $.ajax({
                        url: "<?= base_url('/admin/ScreenshotController/get_user_screenshots'); ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            date: date
                        },
success: function (response) {
    const container = $(".screenshot-row");
    console.log(response);

    if (response.status === "success" && response.screenshots.length > 0) {
        let output_screen = "";

        const screenshotsPerGroup = 12;

        // Group by 1 hour intervals dynamically
        const groupedScreenshots = {};
        response.screenshots.forEach((screenshot) => {
            const time = screenshot.display_text;
            const hour = time.split(":")[0];
            const groupLabel = `${hour}:00:00-${String(Number(hour) + 1).padStart(2, '0')}:00:00`;

            if (!groupedScreenshots[groupLabel]) {
                groupedScreenshots[groupLabel] = [];
            }

                                        groupedScreenshots[groupLabel].push(screenshot);
                                    });

        $.each(groupedScreenshots, function (timeRange, groupScreenshots) {
            const groupId = `group-${timeRange.replace(/[^a-zA-Z0-9]/g, "")}`;
            output_screen += `
                <div class="screenshot-group" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-bottom: 30px;">
                    <div style="font-weight: bold; margin-bottom: 10px;">Time: ${timeRange}</div>
                    <div class="screenshot-visible" style="display: flex; flex-wrap: wrap; gap: 10px;">
            `;

            groupScreenshots.slice(0, screenshotsPerGroup).forEach((screenshot) => {
                output_screen += `
                    <div class="screenshot-card" style="width: calc(100% / 6 - 10px); box-sizing: border-box;">
                        <img src="${screenshot.image_url}" class="zoomable-screenshot" alt="Screenshot" style="width: 100%; cursor: pointer;">
                        <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
                            <span>${response.date}</span> 
                            <span>${screenshot.display_text}</span>
                        </div>
                    </div>
                `;
            });

            output_screen += `
                    </div> <!-- end visible -->
                    <div class="screenshot-hidden" id="${groupId}-extra" style="display: none; flex-direction: row; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
            `;

            groupScreenshots.slice(screenshotsPerGroup).forEach((screenshot) => {
                output_screen += `
                    <div class="screenshot-card" style="width: calc(100% / 6 - 10px); box-sizing: border-box;">
                        <img src="${screenshot.image_url}" class="zoomable-screenshot" alt="Screenshot" style="width: 100%; cursor: pointer;">
                        <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
                            <span>${response.date}</span> 
                            <span>${screenshot.display_text}</span>
                        </div>
                    </div>
                `;
            });

            output_screen += `
                    </div> <!-- end hidden -->
                    ${groupScreenshots.length > screenshotsPerGroup ? `
    <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
        <button class="toggle-button" data-target="${groupId}-extra" style="padding: 6px 12px; font-size: 13px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">See More</button>
    </div>
` : ''}
                </div> <!-- end screenshot group -->
            `;
        });

        container.html(output_screen);
        $('#datePicker').val(response.date);

        // Modal functionality
        $('.zoomable-screenshot').on('click', function () {
            $('#modal-image').attr('src', $(this).attr('src'));
            $('#screenshot-modal').fadeIn();
        });

        $('#close-modal').on('click', function () {
            $('#screenshot-modal').fadeOut();
        });

        // Toggle See More / See Less
        $('.toggle-button').on('click', function () {
            const targetId = $(this).data('target');
            const target = $(`#${targetId}`);
            const isVisible = target.is(':visible');

            if (isVisible) {
                target.slideUp();
                $(this).text('See More');
            } else {
                target.css('display', 'flex').hide().slideDown(); // Ensures flex layout is applied
                $(this).text('See Less');
            }
        });

        lastFetchedTime = response.screenshots[response.screenshots.length - 1].display_text;
    } else {
        container.html("<p>No screenshots available.</p>");
        $('#datePicker').val(response.date);
    }

    // Auto-refresh after 5.5 minutes
    setTimeout(() => fetchUserScreenshots($('#datePicker').val()), 60000);
}



,
                        error: function(response) {
                            console.log(response);
                            setTimeout(() => fetchUserScreenshots($('#datePicker').val()), 60000);
                        }
                    });
                }

                $('#datePicker').on('change', function() {
                    fetchUserScreenshots($(this).val());
                });


                // Load screenshots when date changes
                $('#datePicker').on('change', function() {
                    fetchUserScreenshots($(this).val());
                });

                // Initial load on page ready
                fetchUserScreenshots();
            });
        </script>
    </section>
</div>