<div class="content-wrapper">

    <section class="content" style="padding-top: 0;">

        <style>
            .popup {
                display: none;
                /* top: 0px;
                left: 100%; */
                padding: 10px;
                /* width: 100%;
                height: 100vh;
                overflow-y: auto; */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            .cancel-btn {
                background-color: black;
                color: white;
                border: none;
                /* padding: 5px 15px; */
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

            .breadcrumbs {
                font-size: 14px;
                color: #555;
            }

            .search-row {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin: 20px 0;
            }

            .search-row input,
            .search-row select {
                padding: 8px;
                font-size: 14px;
            }

            .card-container {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .user-card {
                border: 1px solid #ccc;
                padding: 15px;
                border-radius: 6px;
                box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
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
                flex-wrap: nowrap;
                gap: 25px;
                margin-bottom: 15px;
                justify-content: flex-start;
            }

            .timestamp-box {
                width: 228px;
                height: 170px;
                border: 1px solid #ddd;
                padding: 10px;
                background-color: #f9f9f9;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                border-radius: 6px;
            }

            .screenshot-container img {
                height: 120px;
                object-fit: cover;
                margin-bottom: 5px;
            }

            .screenshot-container img {
                transition: transform 0.2s ease-in-out;
            }

            .screenshot-container img:hover {
                transform: scale(1.05);
            }

            .screenshot-container {
                display: flex;
                flex-direction: column;
                background-color: #F4F6F9;
                /* Light gray background */
                min-height: 100vh;
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
            }

            .screenshot-card {
                width: calc(16.66% - 10px);
                /* 6 items per row */
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

            .search-row {
                display: flex;
                align-items: center;
                gap: 10px;
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
                    /* or 100%, or your desired width */
                    max-width: none !important;
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

            <!-- The container to show -->
            <div class="container" style="display: none;">
                <div class="header">
                    <div>
                        <h3>Screenshots</h3>
                    </div>
                </div>
            </div>
            <script>
                function closePopup() {
                    $('#popupCard').fadeOut(); // Hides popup
                    $('.container').fadeIn(); // Shows the screenshots container
                }
            </script>

            <div class="row screenshot-container"></div>
        </div>

        <div class="container">
            <!-- Header -->
            <div class="header">
                <div>
                    <h3>Screenshots</h3>
                </div>
            </div>

            <!-- Search Filters -->
            <div class="search-row">
                <input type="text" id="search-name" placeholder="Search Users / Department">

                <button id="search-btn">
                    <i class="fa fa-search"></i>
                </button>


                <input type="date" value="2025-01-18">
                <select>
                    <option>Sort By</option>
                </select>
            </div>


            <div class="card-container"></div>

            <!-- Screenshot Cards -->
        </div>
        <div id="screenshot-modal" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    flex-direction: column;
">



            <span id="close-modal" style="
        position: absolute;
        top: 20px;
        right: 40px;
        font-size: 30px;
        color: white;
        cursor: pointer;
    ">&times;</span>
            <img id="modal-image" src="" style="
        max-width: 90%;
        max-height: 90%;
        border: 5px solid white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
        margin-left:250px;
        margin-top:50px
    ">
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

        <!-- Modal Structure -->
        <div id="screenshot-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); text-align: center;">
            <span id="close-modal" style="position: absolute; top: 20px; right: 40px; font-size: 40px; color: white; cursor: pointer;">&times;</span>
            <img id="modal-image" style="max-width: 90%; max-height: 90%; margin-top: 5%;">
        </div>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            //             function fetchFilteredUserNames(orgId, searchKeyword = '') {
            //     $.ajax({
            //         url: "<?= base_url('ScreenshotController/search_filter_by_names'); ?>/" + orgId,
            //         type: "GET",
            //         dataType: "json",
            //         data: {
            //             search: searchKeyword
            //         },
            //         success: function(response) {
            //             if (response.status === 'success') {
            //                 console.log("Users:", response.users);
            //                 // You can loop through response.users and display them as needed
            //             } else {
            //                 console.error("Error:", response.message);
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("AJAX Error:", error);
            //         }
            //     });
            // }

            // Function to fetch screenshots for each user (for inline preview)
            function fetchUserScreenshots(userId, orgId) {
                $.ajax({
                    url: "<?= base_url('ScreenshotController/get_screenshots'); ?>",
                    type: "GET",
                    dataType: "json",
                    data: {
                        user_id: userId,
                        org_id: orgId
                    },
                    success: function(response) {
                        const container = $(`#user-${userId} .screenshot-row`);
                        if (response.status === "success" && response.screenshots.length > 0) {
                            let output_screen = "";
                            $.each(response.screenshots.slice(0, 6), function(index, screenshot) {
                                output_screen += `
                        <div class="timestamp-box">
                            <img src="${screenshot.image_data}" class="zoomable-screenshot" alt="Screenshot" width="200" style="cursor: pointer;">
                            <div style="margin-top:10px; display: flex; align-items: center; gap: 120px;">
                                <span>${screenshot.display_text}</span>
                                <img 
                                    src="https://img.icons8.com/?size=50&id=4887&format=png" 
                                    class="delete-screenshot" 
                                    data-id="${screenshot.id}" 
                                    alt="Delete" 
                                    style="cursor: pointer; width: 20px; height: 20px;"
                                />
                            </div>
                        </div>`;
                            });

                            container.html(output_screen);

                            // Zoom on click
                            container.find('.zoomable-screenshot').on('click', function() {
                                const src = $(this).attr('src');
                                $('#modal-image').attr('src', src);
                                $('#screenshot-modal').fadeIn();
                            });

                            // Close modal
                            $('#close-modal').on('click', function() {
                                $('#screenshot-modal').fadeOut();
                            });

                            // Delete screenshot on icon click (with confirmation)
                            container.find('.delete-screenshot').on('click', function() {
                                const screenshotId = $(this).data('id');

                                if (confirm("Are you sure you want to delete this screenshot?")) {
                                    $.ajax({
                                        url: "<?= base_url('ScreenshotController/soft_delete_screenshot'); ?>",
                                        type: "POST",
                                        headers: {
                                            'org_id': orgId,
                                            'user_id': userId
                                        },
                                        data: {
                                            screenshot_id: screenshotId
                                        },
                                        success: function(response) {
                                            alert("Screenshot deleted successfully.");
                                            fetchUserScreenshots(userId, orgId); // Refresh the list
                                        },
                                        error: function(xhr) {
                                            alert("Error deleting screenshot: " + xhr.responseText);
                                        }
                                    });
                                }
                            });

                        } else {
                            container.html("<p>No screenshots available.</p>");
                        }
                    },
                    error: function(xhr, error) {
                        console.error("Error fetching screenshots:", error);
                    }
                });
            }



            $(document).on("click", ".see-more-button", function() {
                $(".container").hide(); // ✅ Hide file 1 container

                const name = $(this).data("name");
                const id = $(this).data("id");
                const orgId = 1;

                $("#popupName").text(name);
                $("#popupID").text(id);
                $("#popupCard").fadeIn();

                function loadScreenshots() {
                    $.ajax({
                        url: "<?= base_url('ScreenshotController/get_screenshots'); ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            user_id: id,
                            org_id: orgId
                        },
                        success: function(response) {
                            if (response.status === "success" && response.screenshots.length > 0) {
                                let output = "";
                                let group = "";

                                $.each(response.screenshots, function(index, screenshot) {
                                    group += `
                            <div class="screenshot-card">
                                <img src="${screenshot.image_data}" class="zoomable-screenshot" alt="Screenshot" style="cursor: pointer;">
                                <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                                    <p>${screenshot.display_text}</p>
                                    <img 
                                        src="https://img.icons8.com/?size=50&id=4887&format=png" 
                                        class="delete-screenshot" 
                                        data-id="${screenshot.id}" 
                                        alt="Delete" 
                                        style="cursor: pointer; width: 20px; height: 20px;"
                                    />
                                </div>
                            </div>
                        `;

                                    if ((index + 1) % 12 === 0 || index === response.screenshots.length - 1) {
                                        output += `<div class="screenshot-row">${group}</div>`;
                                        group = "";
                                    }
                                });

                                $(".screenshot-container").html(output);

                                // Zoom modal
                                $(".screenshot-container").find('.zoomable-screenshot').on('click', function() {
                                    const src = $(this).attr('src');
                                    $('#modal-image').attr('src', src);
                                    $('#screenshot-modal').fadeIn();
                                });

                                $('#close-modal').on('click', function() {
                                    $('#screenshot-modal').fadeOut();
                                    $(".container").show(); // ✅ Show file 1 container back
                                });

                                // Delete functionality
                                $(".screenshot-container").find('.delete-screenshot').on('click', function() {
                                    const screenshotId = $(this).data("id");

                                    if (confirm("Are you sure you want to delete this screenshot?")) {
                                        $.ajax({
                                            url: "<?= base_url('ScreenshotController/soft_delete_screenshot'); ?>",
                                            type: "POST",
                                            headers: {
                                                'org_id': orgId,
                                                'user_id': id
                                            },
                                            data: {
                                                screenshot_id: screenshotId
                                            },
                                            success: function(res) {
                                                alert("Screenshot deleted successfully.");
                                                loadScreenshots(); // Refresh list
                                            },
                                            error: function(xhr) {
                                                alert("Error deleting screenshot: " + xhr.responseText);
                                            }
                                        });
                                    }
                                });

                            } else {
                                $(".screenshot-container").html("<p>No screenshots available.</p>");
                            }
                        },
                        error: function(status, error) {
                            console.error("AJAX Error: " + error);
                        }
                    });
                }

                loadScreenshots(); // Initial load
            });
        </script>



        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                const orgId = 1;

                function fetchUsers(orgId, search = '') {
                    $.ajax({
                        url: `/ScreenshotController/${search ? 'search_filter_by_names' : 'list_organization_users'}/${orgId}`,
                        type: 'GET',
                        data: {
                            search: search
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            const users = response.users || [];

                            if (response.status === 'success' && users.length > 0) {
                                $.each(users, function(index, user) {
                                    output += `
                            <div class="user-card" id="user-${user.id}">
                                <div class="user-info-line">
                                    <div><span>Id:</span> ${user.id}</div>
                                    <div><span>Name:</span> ${user.name || 'N/A'}</div>
                                    <div><span>Designation:</span> ${user.designation || 'N/A'}</div>
                                    <div><span>Productivity Level:</span> ${user.productivity || 'N/A'}%</div>
                                </div>
                                <div class="timestamp-boxes">
                                    <div class="screenshot-row"></div>
                                </div>
                                <div style="text-align: right;">
                                    <button class="see-more-button" data-name="${user.name}" data-id="${user.id}">See More</button>
                                </div>
                            </div>`;
                                });

                                $(".card-container").html(output);

                                // Fetch screenshots for each user
                                users.forEach(function(user) {
                                    fetchUserScreenshots(user.id, orgId);
                                });
                            } else {
                                $(".card-container").html("<p>No users found.</p>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                        }
                    });
                }

                // Initial load
                fetchUsers(orgId);

                // Live search as you type
                $('#search-name').on('input', function() {
                    const keyword = $(this).val().trim();
                    fetchUsers(orgId, keyword);
                });

                // You can keep this button click if you still want that option
                $('#search-btn').on('click', function() {
                    const keyword = $('#search-name').val().trim();
                    fetchUsers(orgId, keyword);
                });
            });
        </script>




    </section>
</div>