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
           width: 100%;
           max-width: 100%;
           box-sizing: border-box;
       }

       .card-container {
           width: 100%;
           display: flex;
           flex-direction: column;
           gap: 20px;

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

           >div {
               max-width: 1200px;
               margin: auto;
               padding: 20px;
               height: 100%;

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
                   background-color: whitesmoke;

                   >#modal-image {
                       max-width: 100%;
                       max-height: 100%;
                       display: block;
                       margin: 0 auto;
                       border-radius: 10px;
                       border: 1px solid black;
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
                       display: flex;
                       flex-wrap: wrap;
                       justify-content: center;
                       gap: 15px;
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
   </style>
   <div class="content-wrapper">
       <section class="content" style="padding-top: 0;">
           <!-- Screenshot Section -->
           <div class="container">
               <div class="header">
                   <h3>Screenshots</h3>
               </div>

               <!-- Search Filters -->
               <div class="search-row">
                   Employee: <select id="employeeSelect" class="form-control single_select"></select>
                   <!-- <button id="search-btn"> -->
                   <!-- <i class="fa fa-search"></i> -->
                   </button>
                   Date: <input type="date" id="datePicker" class="form-control" value="">
                   <select class="form-control">
                       <option>Sort By</option>
                   </select>
               </div>

               <div class="box">
                   <div class="box-header with-border">
                       <div class="row">
                           <div class="col-lg-6">
                               <h3 class="box-title"><strong class="text-right">Employee Name: <span id="employeeName"></span> </strong></h3>
                           </div>
                           <div class="col-lg-6">
                               <h3 class="box-title"><strong class="text-right">Role: <span id="employRole"></span>#</strong></h3>
                           </div>
                       </div>
                   </div>
                   <div class="box-body">
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
               const today = new Date().toISOString().split('T')[0];
               $('#datePicker').val(today);

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


               async function loadScreenshots(id, date) {
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
                                            data-hour-range="${hourRange}"
                                            data-activity-percent="${Math.round(overallActivity)}">
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

                                   $(".card-container").html(output);

                                   // Initialize tooltips
                                   $('[data-toggle="tooltip"]').tooltip();

                                   // Zoom modal
                                   $(".see-zoomable-screenshot").on('click', function() {
                                       const interval = $(this).data('interval');
                                       const hourRange = $(this).data('hour-range');
                                       const activity = $(this).data('activity-percent');
                                       console.log(activity);

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
                                                    Activity: <span style="color: ${getActivityColor(activity)}; font-weight: bold;">${activity}%</span>
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
                                                               //    setTimeout(loadScreenshots, 500);
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
                                                       //    loadScreenshots();
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
                                       //    setTimeout(loadScreenshots, 60000);
                                   }

                               } else {
                                   $(".card-container").html("<p>No screenshots available.</p>");
                               }
                           },
                           error: function(xhr, status, error) {
                               console.error("AJAX Error:", status, error);
                               $(".card-container").html("<p>Error loading screenshots. Please try again.</p>");
                           }
                       });
                   } catch (error) {
                       console.error("Error in loadScreenshots:", error);
                       showToast("Error loading screenshots: " + error.message, "error");
                   }
               }
               $('#close-modal').on('click', function() {
                   $('#screenshot-modal').fadeOut();
               });
               // Close when clicking outside the modal content
               $('#screenshot-modal').on('click', function(e) {
                   // Close only if clicked outside the inner <div>
                   if (!$(e.target).closest('#screenshot-modal > div').length) {
                       $(this).fadeOut();
                   }
               });

               $(document).ready(function() {
                   const today = new Date().toISOString().split('T')[0];
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
                               showToast("No employees found for this user.", "error");
                           }
                       },


                       error: function() {
                           showToast("Failed to fetch employees.", "error");
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

               });
               $('#datePicker').on('change', function() {
                   console.log("onchage");
                   let employeeId = $('#employeeSelect').val();
                   let currentEmployeeId = employeeId;
                   const date = $(this).val();

                   loadScreenshots(currentEmployeeId, date); // No need to manually clear here
               });
           </script>