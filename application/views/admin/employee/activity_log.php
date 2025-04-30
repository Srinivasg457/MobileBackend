<div id="toast-container" style="position: fixed;top: 0;"></div>
<style>
  .status-approved {
    color: #155724;
    /* background-color: #d4edda; */
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    text-align: center;
  }

  .status-pending {
    color: #856404;
    /* background-color: #fff3cd;  */
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    text-align: center;
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

  .form-row.full-width {
    width: 100%;
    display: flex;
    flex-direction: column;
  }

  .textarea-full {
    width: 100%;
    box-sizing: border-box;
    resize: vertical;
    /* optional */
  }


  .manual-entry-container {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    margin: auto;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
  }

  h2,
  h3 {
    margin-bottom: 20px;
    font-weight: 600;
    color: #2c3e50;
  }

  .entry-header,
  .status-boxes,
  .manual-entry-form .form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
  }

  label {
    flex: 15px;
    font-weight: 500;
    color: #2c3e50;
  }

  input,
  select,
  textarea {
    width: 100%;
    padding: 10px 14px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
  }

  .status-box {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    flex: 1 1 220px;
    text-align: center;
    box-shadow: inset 0 0 5px #ddd;
  }

  .status-box strong {
    display: block;
    margin-top: 6px;
    font-size: 16px;
    font-weight: 600;
  }

  .timeline-table,
  .log-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  th,
  td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
  }

  th {
    background: #f4f6f8;
    font-weight: 600;
  }

  tr:nth-child(even) {
    background-color: #fafafa;
  }

  .manual-button-wrap {
    text-align: right;
    margin-top: 10px;
  }

  .manual-add-btn {
    padding: 12px 24px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .manual-add-btn:hover {
    background: #2980b9;
  }

  /* Modal */
  .manual-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1000;
  }

  .manual-modal.show {
    display: flex;
  }

  .manual-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(2px);
  }

  .manual-modal-content {
    position: relative;
    margin: auto;
    z-index: 1;
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    width: 600px;
    max-width: 95%;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
    animation: fadeInUp 0.3s ease-out;
  }

  @keyframes fadeInUp {
    from {
      transform: translateY(40px);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
  }

  .save-btn,
  .cancel-btn {
    padding: 10px 20px;
    font-weight: 500;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
  }

  .save-btn {
    background: #2ecc71;
    color: #fff;
  }

  .cancel-btn {
    background: #e74c3c;
    color: #fff;
  }

  @media (max-width: 768px) {

    .entry-header,
    .status-boxes,
    .manual-entry-form .form-row {
      flex-direction: column;
    }

    .manual-modal-content {
      width: 90%;
    }
  }

  .truncated-reason {
    cursor: pointer;
    position: relative;
  }

  .truncated-reason:hover::after {
    content: attr(data-fulltext);
    position: absolute;
    left: 0;
    top: 100%;
    z-index: 1000;
    background-color: #000;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    width: 700px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }

  .container {
    background-color: white;
    border-radius: 4px;
    padding: 40px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
    width: auto;
    border: 1px solid #ddd;
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
  }

  .title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
  }

  .view-options {
    font-size: 14px;
    color: #666;
  }

  .legend {
    display: flex;
    gap: 15px;
    font-size: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .legend-item {
    display: flex;
    align-items: center;
    color: #555;
  }

  .legend-color {
    width: 12px;
    height: 12px;
    margin-right: 5px;
    border-radius: 2px;
  }

  .timeline-container {
    position: relative;
    height: 50px;
    border: 1px solid #ccc;
    margin-top: 20px;
  }



  .time-labels {
    margin-bottom: 5px;
    font-weight: bold;
  }

  #timeline-track {
    position: relative;
    height: 100%;
    background-color: #f5f5f5;
  }

  .time-marker {
    position: absolute;
    top: 0;
    height: 100%;
    width: 1px;
    background-color: #000;
    font-size: 12px;
    color: #333;
  }

  .time-marker::after {
    content: attr(data-time);
    position: absolute;
    top: 100%;
    left: -15px;
    margin-top: 6px;
    white-space: nowrap;
  }

  .timeline-yellow {
    background-color: #ffe066;
  }

  .timeline-lightgreen {
    background-color: #00FF00;
  }

  .timeline-red {
    background-color: red;
  }

  /* .timeline-yellow {
    background-color: orange;
}

.timeline-red {
    background-color: grey;
}

.timeline-lightgreen {
    background-color: greenyellow;
}

.timeline-darkgreen {
    background-color: green; 
} */


  /* .timeline-yellow {
    background-color: yellow;
}

.timeline-lightgreen {
    background-color: lightgreen;
}

.timeline-darkgreen {
    background-color: darkgreen;
}

.timeline-red {
    background-color: red;
} */
</style>

<div class="content-wrapper">
  <div class="manual-entry-container">
    <h2>Activity</h2>

    <div class="entry-header">
      <label class="hide">Employee
        <input type="text" placeholder="Enter employee name" />
      </label>
      <label>Date
        <input type="date" id="datePicker" value="">
      </label>
      <label>Timezone
        <select>
          <option>IST</option>
          <option>GMT</option>
          <option>PST</option>
        </select>
      </label>
    </div>

    <div class="status-boxes">
      <div class="status-box active">Active <strong id="active-time">00 hrs 00 min</strong></div>
      <div class="status-box inactive">Inactive <strong>0 hr 0 min</strong></div>
      <div class="status-box manual">Manual <strong>00:00</strong></div>
      <div class="status-box meeting">Meeting <strong>00:00</strong></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function() {

      });
    </script>



    <h3>Activity</h3>
    <div class="container">


      <div class="legend">
        <div class="legend-item">
          <div class="legend-color .timeline-lightgreen" style="background-color: #00FF00;"></div>
          <span>Active</span>
        </div>
        <div class="legend-item">
          <div class="legend-color .timeline-yellow" style="background-color: #ffe066;"></div>
          <span>Moderate Active</span>
        </div>
        <!-- <div class="legend-item">
            <div class="legend-color timeline-darkgreen"></div>
            <span>High Active</span>
        </div> -->
        <div class="legend-item">
          <div class="legend-color timeline-red"></div>
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


    <h3>Manual Entry Logs</h3>
    <table class="log-table">
      <thead>
        <tr>
          <th>S.No</th>
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
      url: '<?= base_url("employee/Timecards_manual/get_timecards_by_employee") ?>',
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
          $('.status-box.inactive strong').text(idleFormatted);
        } else {
          $('#active-time').text("00 hrs 00 min");
          $('.status-box.inactive strong').text("00 hrs 00 min");
        }
      },
      error: function() {
        alert('Failed to load time log data.');
      }
    });
  }


  function showToast(message, type) {
    const toast = $(`<div class="toast toast-${type}">${message}</div>`);
    $('#toast-container').append(toast);
    setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
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
      url: '<?= base_url("employee/Timecards_manual/create_timecard") ?>',
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
              blockColorClass = 'timeline-yellow';
            } else if (item.is_active == '2') {
              blockColorClass = 'timeline-lightgreen';
            } else if (item.is_active == '3') {
              blockColorClass = 'timeline-darkgreen';
            } else {
              blockColorClass = 'timeline-red';
            }

            const blockWidthPercent = (5 / totalMinutes) * 100;
            const leftPositionPercent = (totalTimeInMinutes / totalMinutes) * 100;

            const block = $('<div></div>')
              .addClass('activity-block')
              .addClass(blockColorClass)
              .css({
                'position': 'absolute',
                'left': leftPositionPercent + '%',
                'width': blockWidthPercent + '%',
                'height': '100%'
              });

            timelineTrack.append(block);
          });
        } else {
          showToast('No activity data found.', "error")
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
          $('.status-box.inactive strong').text(idleFormatted);
        } else {
          $('#active-time').text("00 hrs 00 min");
          $('.status-box.inactive strong').text("00 hrs 00 min");
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