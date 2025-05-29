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
              /* display: flex; */
              align-items: center;
              /* gap: 10px; */
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
              .screenshot-visible {
                  display: grid;
                  grid-template-columns: repeat(2, 1fr);
                  gap: 10px;
              }
          }
      </style>
      <div class="content-wrapper">
          <section class="content" style="padding-top: 0;">
              <!-- Popup Div -->

              <!-- Screenshot Section -->
              <div class="container">
                  <div class="header">
                      <h3>Webcam Screenshots</h3>
                  </div>

                  <!-- Search Filters -->
                  <div class="search-row row">
                      <div class="col-lg-4 p-0"><input type="date" id="datePicker" class="form-control" value="">
                      </div>
                      <div class="col-lg-4"></div>
                      <div class="col-lg-4 p-0  ">
                          <select id="sortOrder" class="form-control">
                              <option value="">Sort By</option>
                              <option value="ascending">Ascending</option>
                              <option value="descending">Descending</option>
                          </select>
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
                                          output += `<div class="screenshot-group box" style="padding: 10px; border-radius: 8px; margin-bottom: 30px;">
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

                                                  output += `<div class="screenshot-card" style="box-sizing: border-box;">
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
                                                    style="cursor: pointer; transition: all 0.3s ease; border-radius: 8px; overflow: hidden; position: relative;"
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
                                      if (date === new Date().toISOString().split('T')[0]) {
                                          setTimeout(loadScreenshots, 60000);
                                      }

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
                      $('#close-modal').on('click', function() {
                          $('#screenshot-modal').hide();
                      });
                      $('#screenshot-modal').on('click', function(e) {
                          if (!$(e.target).closest('#screenshot-modal > div').length) {
                              $(this).hide();
                          }
                      });

                      $(document).ready(function() {
                          const today = new Date().toISOString().split('T')[0];
                          $('#datePicker').val(today);
                          loadScreenshots()

                      });
                      $('#sortOrder').on('change', function() {
                          currentSortOrder = $(this).val();
                          loadScreenshots();
                      });
                      $('#datePicker').on('change', function() {
                          loadScreenshots(); // No need to manually clear here
                      });
                  </script>