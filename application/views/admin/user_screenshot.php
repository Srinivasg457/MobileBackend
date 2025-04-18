<div class="content-wrapper">
    <section class="content" style="padding-top: 0;">
        <style>
            .popup {
                display: none;
                padding: 10px;
                /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); */
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

            .toast {
                padding: 10px;
                margin: 5px;
                border-radius: 4px;
                color: #fff;
                min-width: 200px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            .toast-success {
                background-color: #28a745;
            }

            .toast-error {
                background-color: #e74c3c;
            }

            .is-invalid {
                border: 2px solid #e74c3c;
                background-color: #fcebea;
            }

            #toast-container {
                position: fixed;
                top: 10px;
                right: 10px;
                z-index: 9999;
            }
        </style>

        <!-- Popup Div -->
        <div id="toast-container" style="position: fixed;top: 0;"></div>
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

            <!-- Search Filters -->
            <div class="search-row">
                <input type="text" id="search-name" placeholder="Search Users / Department">
                <button id="search-btn">
                    <i class="fa fa-search"></i>
                </button>
                <input type="date" id="datePicker" value="">
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
            function showToast(message, type) {
                const toast = $(`<div class="toast toast-${type}">${message}</div>`);
                $('#toast-container').append(toast);
                setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
            }

            function closePopup() {
                $('#popupCard').fadeOut();
                $('.container').fadeIn();
            }

            function fetchUserScreenshots(employeeId, date = '') {
                console.log(employeeId);

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
                                output_screen += `
                            <div class="screenshot-card">
                                <img src="${screenshot.image_url}" class="zoomable-screenshot" alt="Screenshot" width="200" style="cursor: pointer;">
                                <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
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
                            container.find('.zoomable-screenshot').on('click', function() {
                                $('#modal-image').attr('src', $(this).attr('src'));
                                $('#screenshot-modal').fadeIn();
                            });

                            $('#close-modal').on('click', function() {
                                $('#screenshot-modal').fadeOut();
                            });

                            container.find('.delete-screenshot').on('click', function() {
                                const screenshotId = $(this).data('id');

                                if (confirm("Are you sure you want to delete this screenshot?")) {
                                    $.ajax({
                                        url: "/admin/ScreenshotController/soft_delete_screenshot",
                                        type: "POST",
                                        data: {
                                            screenshot_id: screenshotId,
                                            employee_id: employeeId
                                        },
                                        success: function() {
                                            showToast(`Screenshot deleted successfully.`, 'success');
                                            // alert("Screenshot deleted successfully.");
                                            fetchUserScreenshots(employeeId, $('#datePicker').val());
                                        },
                                        error: function(xhr) {
                                            // alert("Error deleting screenshot: " + xhr.responseText);
                                            showToast(`Error while deleting screenshot.`, 'error');

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

            // Popup Screenshot Loader
            $(document).on("click", ".see-more-button", function() {
                $(".container").hide();

                const name = $(this).data("name");
                const id = $(this).data("id");
                const date = $('#datePicker').val();

                $("#popupName").text(name);
                $("#popupID").text(id);
                $("#popupCard").fadeIn();

                function loadScreenshots() {
                    $.ajax({
                        url: "<?= base_url('admin/ScreenshotController/get_screenshots'); ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            employee_id: id,
                            date: date
                        },
                        success: function(response) {
                            if (response.status === "success" && response.screenshots.length > 0) {
                                let output = "",
                                    group = "",
                                    latestTime = null;

                                $.each(response.screenshots, function(index, screenshot) {
                                    group += `
                    <div class="screenshot-card">
                        <img src="${screenshot.image_url}" class="see-zoomable-screenshot" alt="Screenshot" style="cursor: pointer;">
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
                    </div>`;

                                    // Get the latest timestamp
                                    if (!latestTime || new Date(screenshot.timestamp) > new Date(latestTime)) {
                                        latestTime = screenshot.timestamp;
                                    }

                                    if ((index + 1) % 12 === 0 || index === response.screenshots.length - 1) {
                                        output += `<div class="screenshot-row">${group}</div>`;
                                        group = "";
                                    }
                                });

                                $(".screenshot-container").html(output);

                                $(".see-zoomable-screenshot").on('click', function() {
                                    $('#modal-image').attr('src', $(this).attr('src'));
                                    $('#screenshot-modal').fadeIn();
                                });

                                $('#close-modal').on('click', function() {
                                    $('#screenshot-modal').fadeOut();
                                });

                                $(".delete-screenshot").on('click', function() {
                                    const screenshotId = $(this).data("id");

                                    if (confirm("Are you sure you want to delete this screenshot?")) {
                                        console.log(screenshotId, id);

                                        $.ajax({
                                            url: "/admin/ScreenshotController/soft_delete_screenshot",
                                            type: "POST",
                                            data: {
                                                screenshot_id: screenshotId,
                                                employee_id: id
                                            },
                                            success: function() {
                                                showToast(`Screenshot deleted successfully.`, 'success');
                                                loadScreenshots(); // reload
                                                fetchUserScreenshots(id, date);
                                            },
                                            error: function(xhr) {
                                                showToast(`Error while deleting screenshot.`, 'error');
                                                // alert("Error deleting screenshot: " + xhr.responseText);
                                            }
                                        });
                                    }
                                });

                                // Schedule next refresh from latest screenshot
                                if (latestTime) {
                                    if (nextFetchTimeout) clearTimeout(nextFetchTimeout);

                                    const latestDate = new Date(latestTime);
                                    const now = new Date();
                                    const timeDiff = Math.max(0, (latestDate.getTime() + 5 * 60 * 1000) - now.getTime());

                                    nextFetchTimeout = setTimeout(() => {
                                        const currentDate = latestDate.toISOString().split("T")[0];
                                        $('#datePicker').val(currentDate);
                                        loadScreenshots(); // Auto-refresh
                                    }, timeDiff);
                                }

                            } else {
                                $(".screenshot-container").html("<p>No screenshots available.</p>");
                            }
                        },
                        error: function(status, error) {
                            console.error("AJAX Error: " + error);
                        }
                    });
                }


                loadScreenshots();
            });

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
                                <div class="user-card" id="user-${employee.id}">
                                    <div class="user-info-line">
                                        <div><span>Id:</span> ${employee.id}</div>
                                        <div><span>Name:</span> ${employee.name || 'N/A'}</div>
                                        <div><span>Designation:</span> ${employee.designation || 'N/A'}</div>
                                        <div><span>Productivity Level:</span> ${employee.productivity || 'N/A'}%</div>
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