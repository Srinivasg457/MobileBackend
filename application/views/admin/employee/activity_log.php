<div class="content-wrapper activity_log">
  <section class="content">
    <div class="container-fluid">
      <h2>Activity Log</h2>
      <div class="entry-header mb-5 reprt-box">
        <label class="hide control-label">Employee
          <input type="text" class="form-control" placeholder="Enter employee name" />
        </label>
        <label class="control-label" for="datePicker">Date
          <input type="date" id="datePicker" value="" class="form-control">
        </label>
      </div>


      <div class="row box-payout-areas mt-20">
        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-success" id="active-time"><strong>00 hrs 00 min</strong></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize">Active Time</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border ">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-success danger inactive"><strong>00 hrs 00 min</strong></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize">Inactive Time</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border ">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay  text-success"><strong>00 hrs 00 min</strong></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize">Manual Time</span>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-success"><strong>00 hrs 00 min</strong></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize">Meeting Time</span>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-30" id="activity">

        <h5>Activity Bar</h5>
        <div class="legend">
          <div class="legend-item">
            <div class="legend-color bg-green"></div>
            <span>Active</span>
          </div>
          <div class="legend-item">
            <div class="legend-color bg-yellow"></div>
            <span>Moderate Active</span>
          </div>
          <div class="legend-item">
            <div class="legend-color box-danger"></div>
            <span>Inactive</span>
          </div>
        </div>


        <div class="timeline-container">
          <!-- <div class="time-row"> -->
          <div id="timeline-track">
            <!-- Dynamic blocks will be added here -->

            <!-- Static time markers (08:00 to 20:00) -->
            <!-- Each hour = 100% / 12 = 8.33% -->
            <div class="time-marker" style="left: 0%;" data-time="08:00"></div>
            <div class="time-marker" style="left: 8.33%;" data-time="09:00"></div>
            <div class="time-marker" style="left: 16.66%;" data-time="10:00"></div>
            <div class="time-marker" style="left: 25%;" data-time="11:00"></div>
            <div class="time-marker" style="left: 33.33%;" data-time="12:00"></div>
            <div class="time-marker" style="left: 41.66%;" data-time="13:00"></div>
            <div class="time-marker" style="left: 50%;" data-time="14:00"></div>
            <div class="time-marker" style="left: 58.33%;" data-time="15:00"></div>
            <div class="time-marker" style="left: 66.66%;" data-time="16:00"></div>
            <div class="time-marker" style="left: 75%;" data-time="17:00"></div>
            <div class="time-marker" style="left: 83.33%;" data-time="18:00"></div>
            <div class="time-marker" style="left: 91.66%;" data-time="19:00"></div>
            <div class="time-marker" style="left: 100%;" data-time="20:00"></div>
          </div>
          <!-- </div> -->
        </div>
      </div>

      <div id="dg_table">
        <div class="reprt-box mt-30">
          <h5>Manual Entry Logs</h5>

          <table class="log-table">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration (minutes)</th>
                <th>Reason</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody id="log-data">
              <!-- Data from database will go here -->
            </tbody>
          </table>

          <div class="manual-button-wrap">
            <button class="manual-add-btn" onclick="openModal()"><i class="fa fa-plus-circle" aria-hidden="true"></i>
              Add Manual Entry</button>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="manual-modal" id="manualModal">
        <div class="manual-modal-overlay" onclick="closeModal()"></div>
        <div class="manual-modal-content">
          <h3>Add Manual Entry</h3>
          <div class="manual-entry-form" id="manualEntryForm">
            <div class="form-row">
              <label>Start Time <input type="time" id="timestamp_start" required /></label>
              <label>End Time <input type="time" id="timestamp_end" required /></label>
            </div>
            <div class="form-row">
              <label>Project
                <input type="text" value="Lorem" readonly />
              </label>
              <label>Task
                <select disabled>
                  <option selected>Ipsum</option>
                  <option>Dolor</option>
                </select>
              </label>
            </div>
            <div class="form-row full-width">
              <label for="notes">Notes</label>
              <textarea id="reason" rows="2" placeholder="Enter notes" class="textarea-full" required></textarea>
            </div>
            <div class="form-actions">
              <button type="button" id="saveManualBtn" class="save-btn">Save</button>
              <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<script>
  function openModal() {
    document.getElementById('manualModal').classList.add('show');
  }

  function closeModal() {
    document.getElementById('manualModal').classList.remove('show');
  }

  $(document).ready(function() {
    fetchTimecards(); // Initial call
  });

  function fetchTimecards() {
    const employeeId = <?= $employee_id ?>;
    const employeeOrgId = <?= $employee_org_id ?>

    $.ajax({
      url: '<?= base_url("admin/Timecards_manual/get_timecards_by_employee") ?>',
      method: 'GET',
      data: {
        employee_id: employeeId,
        employee_org_id: employeeOrgId
      }, // Send it as query param
      dataType: 'json',
      success: function(response) {
        if (response.error) {
          $('#log-data').html('<tr><td colspan="6">' + response.error + '</td></tr>');
        } else {
          response.sort((a, b) => b.id - a.id);

          let html = '';
          let counter = 1;

          response.forEach(function(row) {
            let duration = 'N/A';

            if (row.timestamp_start && row.timestamp_end) {
              let start = new Date(`1970-01-01T${row.timestamp_start}`);
              let end = new Date(`1970-01-01T${row.timestamp_end}`);
              if (!isNaN(start.getTime()) && !isNaN(end.getTime())) {
                const diffMs = end - start;
                const diffMins = Math.floor(diffMs / 60000);
                duration = `${diffMins} minutes`;
              }
            }

            let reasonText = row.reason || '';
            let isTruncated = reasonText.length > 50;
            let truncatedReason = isTruncated ? reasonText.substring(0, 50) + '...' : reasonText;

            let tooltipAttr = isTruncated ?
              `class="truncated-reason" data-fulltext="${reasonText.replace(/"/g, '&quot;')}"` :
              '';

            html += `
            <tr>
              <td>${counter}</td>
              <td>${row.date_added}</td>
              <td>${row.timestamp_start}</td>
              <td>${row.timestamp_end}</td>
              <td>${duration}</td>
              <td ${tooltipAttr}>${truncatedReason}</td>
              <td class="${row.approved == 1 ? 'text-success' : 'text-warning'}">
                ${row.approved == 1 ? 'Approved' : 'Pending'}
              </td>
            </tr>`;

            counter++;
          });

          $('#log-data').html(html);
        }
      },
      error: function() {
        $('#log-data').html('<tr><td colspan="6">Error loading data</td></tr>');
      }
    });
    $.ajax({
      url: '<?= base_url("admin/Time_logs/get_time_logs") ?>',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.status && response.data.length > 0) {
          const data = response.data[0];

          // Active time
          const activeParts = data.total_active_time.split(':');
          const activeHours = parseInt(activeParts[0]);
          const activeMinutes = parseInt(activeParts[1]);
          const activeFormatted = `${activeHours.toString().padStart(2, '0')} hrs ${activeMinutes.toString().padStart(2, '0')} min`;
          $('#active-time').text(activeFormatted);

          // Inactive time
          const idleParts = data.total_idle_time.split(':');
          const idleHours = parseInt(idleParts[0]);
          const idleMinutes = parseInt(idleParts[1]);
          const idleFormatted = `${idleHours.toString().padStart(2, '0')} hrs ${idleMinutes.toString().padStart(2, '0')} min`;

          // Update the second .status-box strong tag (Inactive)
          $('.danger.inactive strong').text(idleFormatted);
        } else {
          $('#active-time').text("00 hrs 00 min");
          $('.danger.inactive strong').text("00 hrs 00 min");
        }
      },
      error: function() {
        alert('Failed to load time log data.');
      }
    });
  }




  $('#saveManualBtn').on('click', function() {
    const timestamp_start = $('#timestamp_start').val();
    const timestamp_end = $('#timestamp_end').val();
    const reason = $('#reason').val().trim();

    // Clear previous invalid styles
    $('#timestamp_start, #timestamp_end, #reason').removeClass('is-invalid');

    // Validation
    if (!timestamp_start) {
      $('#timestamp_start').addClass('is-invalid');
      showToast("Please fill the start time", "error");
      return;
    }

    if (!timestamp_end) {
      $('#timestamp_end').addClass('is-invalid');
      showToast("Please fill the end time", "error");
      return;
    }

    if (timestamp_start >= timestamp_end) {
      $('#timestamp_start, #timestamp_end').addClass('is-invalid');
      showToast('Start time must be earlier than end time', 'error');
      return;
    }

    if (!reason) {
      $('#reason').addClass('is-invalid');
      showToast("Please fill the Notes field", "error");
      return;
    }

    // Prepare form data
    const formData = new FormData();
    formData.append('timestamp_start', timestamp_start);
    formData.append('timestamp_end', timestamp_end);
    formData.append('reason', reason);

    // Send AJAX request
    $.ajax({
      url: '<?= base_url("admin/Timecards_manual/create_timecard") ?>',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        showToast(response, 'success');
        closeModal();
        fetchTimecards();
      },
      error: function(xhr, status, error) {
        console.error(error);
        showToast('Something went wrong. Try again.', 'error');
      }
    });
    $('#timestamp_start').val('');
    $('#timestamp_end').val('');
    $('#reason').val('');
  });

  function fetchActivity(date1) {
    const timelineTrack = $('#timeline-track');
    timelineTrack.find('.activity-block').remove(); // 🧹 Clear existing activity blocks

    $.ajax({
      url: "<?= base_url('/admin/Activity_logs/get_activity'); ?>",
      type: 'GET',
      dataType: 'json',
      data: {
        date: date1
      },
      success: function(response) {
        if (response.status && response.data.length > 0) {
          const startHour = 8; // 08:00 AM
          const endHour = 20; // 08:00 PM
          const totalMinutes = (endHour - startHour) * 60;

          response.data.forEach(function(item) {
            const createdAt = new Date(item.created_at);
            const hour = createdAt.getHours();
            const minutes = createdAt.getMinutes();
            const totalTimeInMinutes = (hour * 60 + minutes) - (startHour * 60);

            // Skip if the time is outside 08:00 - 20:00
            if (totalTimeInMinutes < 0 || totalTimeInMinutes > totalMinutes) return;


            let blockColorClass = '';
            if (item.is_active == '1') {
              blockColorClass = 'bg-yellow';
            } else if (item.is_active == '2') {
              blockColorClass = 'bg-green';
            } else if (item.is_active == '3') {
              blockColorClass = 'bg-green';
            } else {
              blockColorClass = 'bg-red';
            }

            const blockWidthPercent = (5 / totalMinutes) * 100;
            const leftPositionPercent = (totalTimeInMinutes / totalMinutes) * 100;

            // Calculate end time by adding 5 minutes
            const endAt = new Date(createdAt.getTime() + 5 * 60000);

            // Format time as HH:MM AM/PM
            const formatTime = date =>
              date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
              });

            const timeLabel = `${formatTime(createdAt)} to ${formatTime(endAt)}`;
            const tooltip = $('<div></div>')
              .addClass('custom-tooltip')
              .text(timeLabel)
              .hide(); // Initially hidden

            const block = $('<div></div>')
              .addClass('activity-block')
              .addClass(blockColorClass)
              .css({
                'position': 'absolute',
                'left': leftPositionPercent + '%',
                'width': blockWidthPercent + '%',
                'height': '100%'
              }).append(tooltip).hover(
                function() {
                  tooltip.show();
                },
                function() {
                  tooltip.hide();
                }
              );

            timelineTrack.append(block);
          });
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
        alert('Failed to fetch activity data.');
      }
    });
    $.ajax({
      url: '<?= base_url("admin/Time_logs/get_time_logs") ?>',
      type: 'GET',
      dataType: 'json',
      data: {
        date: date1
      },
      success: function(response) {
        console.log(response);

        if (response.status && response.data.length > 0) {
          const data = response.data[0];

          // Active time
          const activeParts = data.total_active_time.split(':');
          const activeHours = parseInt(activeParts[0]);
          const activeMinutes = parseInt(activeParts[1]);
          const activeFormatted = `${activeHours.toString().padStart(2, '0')} hrs ${activeMinutes.toString().padStart(2, '0')} min`;
          $('#active-time').text(activeFormatted);

          // Inactive time
          const idleParts = data.total_idle_time.split(':');
          const idleHours = parseInt(idleParts[0]);
          const idleMinutes = parseInt(idleParts[1]);
          const idleFormatted = `${idleHours.toString().padStart(2, '0')} hrs ${idleMinutes.toString().padStart(2, '0')} min`;

          // Update the second .status-box strong tag (Inactive)
          $('.danger.inactive strong').text(idleFormatted);
        } else {
          $('#active-time').text("00 hrs 00 min");
          $('.danger.inactive strong').text("00 hrs 00 min");
        }
      },
      error: function() {
        alert('Failed to load time log data.');
      }
    });
  }
  $(document).ready(function() {
    const today = new Date().toISOString().split('T')[0];
    $('#datePicker').val(today);
    fetchActivity();

  });

  function triggerFilter() {
    const date = $('#datePicker').val();
    fetchActivity(date)
  }
  $('#datePicker').on('change', triggerFilter);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {

  });
</script>