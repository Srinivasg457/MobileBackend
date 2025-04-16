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
            }

            .search-row {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 20px 0;
            }

            .search-row input,
            .search-row select {
                padding: 8px;
                font-size: 14px;
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
                margin-top: 15px;
                max-height: 500px;
                /* Adjust as you wish */
                overflow-y: auto;
            }

            .screenshot-card {
                width: calc(16.66% - 10px);
                background-color: #F4F6F9;
                border-radius: 6px;
                padding: 8px;
                text-align: center;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                transition: transform 0.2s;
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
                        success: function(response) {
                            const container = $(".screenshot-row");
                            console.log(response);

                            if (response.status === "success" && response.screenshots.length > 0) {
                                let output_screen = "";
                                $.each(response.screenshots, function(index, screenshot) {
                                    output_screen += `
                            <div class="screenshot-card">
                                <img src="${screenshot.image_url}" class="zoomable-screenshot" alt="Screenshot" style="cursor: pointer;">
                                <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                                   <span>${response.date}</span> <span>${screenshot.display_text}</span>
                                </div>
                            </div>`;
                                });

                                container.html(output_screen);
                                $('#datePicker').val(response.date);

                                // Modal handlers
                                $('.zoomable-screenshot').on('click', function() {
                                    $('#modal-image').attr('src', $(this).attr('src'));
                                    $('#screenshot-modal').fadeIn();
                                });

                                $('#close-modal').on('click', function() {
                                    $('#screenshot-modal').fadeOut();
                                });

                                // Store latest time
                                lastFetchedTime = response.screenshots[response.screenshots.length - 1].display_text;
                            } else {
                                container.html("<p>No screenshots available.</p>");
                                $('#datePicker').val(response.date);
                            }

                            // Schedule next fetch after 5 seconds                            
                            setTimeout(() => fetchUserScreenshots($('#datePicker').val()), 330000);
                        },
                        error: function(response) {
                            console.log(response);
                            setTimeout(() => fetchUserScreenshots($('#datePicker').val()), 330000);
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