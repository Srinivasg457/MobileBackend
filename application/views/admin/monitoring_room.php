<div class="content-wrapper monitoring_room">
  <section class="content">
    <div class="container-fluid">
      <h3><?php echo "Live Monitoring" ?>
      </h3>
      <div class="row mb-5 reprt-box">
        <div class="form-group col-lg-4 my-3">
          <label class="control-label">Employee</label>
          <div class="input-group">
            <input type="text" class="search-input form-control" placeholder="Search employees...">
          </div>
        </div>
        <div class="form-group col-lg-4 my-3"></div>

        <div class="form-group col-lg-4 my-3">
          <label class="control-label">Sort By</label>
          <div class="input-group">
            <select class="form-control single_select" id="sortSelect">
              <option value="employeeName">Employee Name</option>
              <option value="active">Active Hours</option>
              <option value="inactive">Inactive Hours</option>
            </select>
            <!-- Sorting icon -->
            <div class="input-group-addon border-0">
              <span id="sortIcon" style="cursor: pointer;">
                <i class="bi bi-arrow-down-up"></i>
              </span>
            </div>
          </div>

        </div>
      </div>

      <div class="employee-grid mt-20" id="employeeGrid">
        <!-- Employee list will appear here -->
      </div>
      <!-- Monitoring Modal -->
      <div id="monitoringModal" class="monitoring-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <div>
                <label class="modal-title textwhite my-2" id="modalEmployeeName"></label>
                <p class="modal-subtitle" id="modalEmployeeId"></p>
              </div>
              <button type="button" class="btn" onclick="closeVideoModal()" style="background-color: white; border: none;">
                <i class="fa fa-times" style="color: black;"></i>
              </button>
            </div>
            <div class="modal-body">
              <img id="modalScreen" class="modal-screen" autoplay playsinline>
            </div>
            <!-- <div class="modal-footer">
                <div class="connection-status">
                  <span class="status-indicator"></span>
                  <span>Connected</span>
                </div>
                <div class="timestamp" id="modalTimestamp"></div>
              </div> -->
          </div>
        </div>
      </div>
  </section>
</div>

<script>
  // All your existing JavaScript functionality remains unchanged
  // Only the UI elements have been updated
  function loadAllEmployees() {
    $.ajax({
      url: "<?= base_url('/admin/Monitoring_room/list_employees_by_user') ?>",
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        const employeeGrid = $('#employeeGrid');
        employeeGrid.empty();

        if (response.status === 'success' && response.employees.length > 0) {
          const employees = response.employees;

          // Process employees in chunks of 3 for each row
          for (let i = 0; i < employees.length; i += 3) {
            const rowEmployees = employees.slice(i, i + 3);

            // Create a row container (not needed since we're using CSS grid)
            $.each(rowEmployees, function(index, employee) {
              const card = `
                <div class="employee-card" id="employee-card-${employee.id}">
                  <div class="card-thumbnail" onclick="openVideoModal('${employee.id}', '${employee.name}', '${employee.id}')">
                    <img id="screenshot-${employee.id}" src="" alt="Employee Screen">
                    <div class="live-badge">ONLINE</div>
                  </div>
                  <div class="card-body">
                                      <p class="employee-id">ID: ${employee.id}</p>

                    <h3 class="employee-name">
                      <i class="bi bi-person-fill"></i> ${employee.name}
                    </h3>
                    <div class="activity-stats">
                      <div class="stat-item active-stat">
                        <strong class="stat-label">Active: </strong>
                        <span class="stat-value" id="active-time-${employee.id}">00:00</span>
                      </div>
                      <div class="stat-item inactive-stat">
                       <strong class="stat-label">Inactive :</strong>
                        <span class="stat-value" id="inactive-time-${employee.id}">00:00</span>
                      </div>
                    </div>
                  </div>
                </div>
              `;

              employeeGrid.append(card);
              getActivity(employee.id);
              getLatestScreenshot(employee.id);
            });
          }
        } else {
          employeeGrid.html('<p class="text-center py-4" style="grid-column: 1 / -1">No employees found</p>');
        }
      },
      error: function() {
        $('#employeeGrid').html('<p class="text-center py-4" style="grid-column: 1 / -1">Error loading employee data</p>');
      }
    });
  }

  $(document).ready(function() {
    loadAllEmployees();

    // Search functionality
    $('.search-input').on('keyup', function() {
      const searchQuery = $(this).val().trim();

      if (searchQuery === "") {
        loadAllEmployees();
        return;
      }

      $.ajax({
        url: "<?= base_url('/admin/Monitoring_room/list_employees_by_name') ?>",
        method: 'GET',
        data: {
          name: searchQuery
        },
        dataType: 'json',
        success: function(response) {
          const employeeGrid = $('#employeeGrid');
          employeeGrid.empty();

          if (response.status === 'success' && response.employees.length > 0) {
            const employees = response.employees;

            $.each(employees, function(index, employee) {
              const card = `
                <div class="employee-card" id="employee-card-${employee.id}">
                  <div class="card-thumbnail" onclick="openVideoModal('${employee.id}', '${employee.name}', '${employee.id}')">
                    <img id="screenshot-${employee.id}" src="" alt="Employee Screen">
                    <div class="live-badge">ONLINE</div>
                  </div>
                  <div class="card-body">
                                      <p class="employee-id">ID: ${employee.id}</p>

                    <h3 class="employee-name">
                      <i class="bi bi-person-fill"></i> ${employee.name}
                    </h3>
                    <div class="activity-stats">
                      <div class="stat-item active-stat">
                        <strong class="stat-label">Active: </strong>
                        <span class="stat-value" id="active-time-${employee.id}">00:00</span>
                      </div>
                      <div class="stat-item inactive-stat">
                       <strong class="stat-label">Inactive :</strong>
                        <span class="stat-value" id="inactive-time-${employee.id}">00:00</span>
                      </div>
                    </div>
                  </div>
                </div>
              `;

              employeeGrid.append(card);
              getActivity(employee.id);
              getLatestScreenshot(employee.id);
            });
          } else {
            employeeGrid.html('<p class="text-center py-4" style="grid-column: 1 / -1">No matching employees found</p>');
          }
        },
        error: function() {
          $('#employeeGrid').html('<p class="text-center py-4" style="grid-column: 1 / -1">Error searching employees</p>');
        }
      });
    });
  });

  function openVideoModal(employeeId, name, id) {
    const img = document.getElementById('modalScreen');
    img.src = "";
    document.getElementById("modalEmployeeName").innerText = name;
    document.getElementById("modalEmployeeId").innerText = `Employee ID: ${id}`;
    document.getElementById("monitoringModal").style.display = "block";
    document.body.style.overflow = "hidden";
    playVideo(employeeId);

    // Update timestamp
    const now = new Date();
    document.getElementById("modalTimestamp").innerText = `Last updated: ${now.toLocaleTimeString()}`;
  }

  function closeVideoModal() {
    const modal = document.getElementById("monitoringModal");
    modal.style.display = "none";
    document.body.style.overflow = "auto";
    const img = document.getElementById('modalScreen');
    img.src = "";
  }

  window.onclick = function(event) {
    const modal = document.getElementById("monitoringModal");
    if (event.target === modal) {
      closeVideoModal();
    }
  }

  function getActivity(currentEmployeeId) {
    $.ajax({
      url: '<?= base_url("admin/Time_logs/get_time_logs") ?>',
      type: 'GET',
      dataType: 'json',
      data: {
        employee_id: currentEmployeeId
      },
      success: function(response) {
        if (response.status && response.data.length > 0) {
          const data = response.data[0];

          // Format Active time
          const activeParts = data.total_active_time.split(':');
          const activeHours = activeParts[0].padStart(2, '0');
          const activeMinutes = activeParts[1].padStart(2, '0');
          const activeFormatted = `${activeHours}:${activeMinutes}`;
          $(`#active-time-${currentEmployeeId}`).text(activeFormatted);

          // Format Inactive time
          const idleParts = data.total_idle_time.split(':');
          const idleHours = idleParts[0].padStart(2, '0');
          const idleMinutes = idleParts[1].padStart(2, '0');
          const idleFormatted = `${idleHours}:${idleMinutes}`;
          $(`#inactive-time-${currentEmployeeId}`).text(idleFormatted);
        } else {
          $(`#active-time-${currentEmployeeId}`).text("00:00");
          $(`#inactive-time-${currentEmployeeId}`).text("00:00");
        }
      },
      error: function() {
        console.log('Failed to load time log data');
      }
    });
  }

  function getLatestScreenshot(currentEmployeeId) {
    $.ajax({
      url: "<?= base_url('/admin/ScreenshotController/get_last_screenshot') ?>",
      method: "GET",
      dataType: "json",
      data: {
        employee_id: currentEmployeeId
      },
      success: function(response) {
        const thumbnail = $(`#screenshot-${currentEmployeeId}`);
        const container = thumbnail.parent();

        if (response.status === 'success' && response.screenshot.image_url && !response.user_offline) {
          thumbnail.attr('src', response.screenshot.image_url)
            .on('error', function() {
              container.addClass('blank-screen')
                .removeAttr('onclick') // Disable click
                .html('<span>No Screen Available</span><div class="offline-badge">OFFLINE</div>')
                .css({
                  'pointer-events': 'not-allowed',
                  'opacity': 0.6,
                  'cursor': 'not-allowed'
                });
            });
          container.removeClass('blank-screen')
            .css({
              'pointer-events': '',
              'opacity': '',
              'cursor': ''
            }); // Re-enable click
        } else {
          // Either no screenshot or user is offline
          container.addClass('blank-screen')
            .removeAttr('onclick') // Disable click
            .html('<span>No Screen Available</span><div class="offline-badge">OFFLINE</div>')
            .css({
              'pointer-events': 'not-allowed',
              'opacity': 0.6,
              'cursor': 'not-allowed'
            });
        }
      },
      error: function() {
        const container = $(`#screenshot-${currentEmployeeId}`).parent();
        container.addClass('blank-screen')
          .removeAttr('onclick') // Disable click
          .html('<span>No Screen Available</span><div class="offline-badge">OFFLINE</div>')
          .css({
            'pointer-events': 'not-allowed',
            'opacity': 0.6,
            'cursor': 'not-allowed'
          });
      }

    });
  }


  $(document).ready(function() {
    let currentOrder = 'asc'; // Default sorting order
    let currentSort = ''; // Track current selected sort

    function fetchSortedEmployees(order) {
      $.ajax({
        url: "<?= base_url('/admin/Monitoring_room/list_employees_ordered') ?>",
        method: 'GET',
        data: {
          order: order
        },
        dataType: 'json',
        success: function(response) {
          const employeeGrid = $('#employeeGrid');
          employeeGrid.empty();

          if (response.status === 'success' && response.employees.length > 0) {
            $.each(response.employees, function(index, employee) {
              const card = `
<div class="employee-card" id="employee-card-${employee.id}">
  <div class="card-thumbnail" onclick="openVideoModal('${employee.id}', '${employee.name}', '${employee.id}')">
    <img id="screenshot-${employee.id}" src="" alt="Employee Screen">
    <div class="live-badge">ONLINE</div>
  </div>
  <div class="card-body">
    <p class="employee-id">ID: ${employee.id}</p>
    <h3 class="employee-name">
      <i class="bi bi-person-fill"></i> ${employee.name}
    </h3>
    <div class="activity-stats">
      <div class="stat-item active-stat">
        <strong class="stat-label">Active: </strong>
        <span class="stat-value" id="active-time-${employee.id}">00:00</span>
      </div>
      <div class="stat-item inactive-stat">
        <strong class="stat-label">Inactive :</strong>
        <span class="stat-value" id="inactive-time-${employee.id}">00:00</span>
      </div>
    </div>
  </div>
</div>
          `;
              employeeGrid.append(card);
              getActivity(employee.id);
              getLatestScreenshot(employee.id);
            });
          } else {
            employeeGrid.html('<p class="text-center py-4" style="grid-column: 1 / -1">No matching employees found</p>');
          }
        },
        error: function() {
          $('#employeeGrid').html('<p class="text-center py-4" style="grid-column: 1 / -1">Error searching employees</p>');
        }
      });
    }

    // Sort icon click behavior
    $('#sortIcon').on('click', function() {
      const selectedSort = $('#sortSelect').val();

      if (selectedSort === 'employeeName') {
        fetchSortedEmployees(currentOrder);

        // Toggle the order
        if (currentOrder === 'asc') {
          currentOrder = 'desc';
          $('#sortIcon i').removeClass().addClass('bi bi-arrow-down');
        } else {
          currentOrder = 'asc';
          $('#sortIcon i').removeClass().addClass('bi bi-arrow-up');
        }
      }
    });

    // On dropdown change
    $('#sortSelect').on('change', function() {
      currentSort = $(this).val();

      if (currentSort === 'employeeName') {
        // Reset icon and default order
        currentOrder = 'asc';
        $('#sortIcon i').removeClass().addClass('bi bi-arrow-up');
        fetchSortedEmployees(currentOrder);
      } else {
        $('#sortIcon i').removeClass().addClass('bi bi-arrow-down-up');
        fetchSortedEmployees(currentSort);
      }
    });
  });

  let sortOrder = 'desc'; // default sort order

  // Listen for change in dropdown
  $('#sortSelect').on('change', function() {
    const selectedValue = $(this).val();

    if (selectedValue === 'active') {
      fetchSortedEmployees('active', sortOrder);
    } else if (selectedValue === 'employeeName') {
      fetchSortedEmployees('employeeName', sortOrder);
    } else if (selectedValue === 'inactive') {
      fetchSortedEmployees('inactive', sortOrder);
    }
  });

  // Optional: toggle sorting order when clicking icon
  $('#sortIcon').on('click', function() {
    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
    const selectedValue = $('#sortSelect').val();

    if (selectedValue) {
      fetchSortedEmployees(selectedValue, sortOrder);
    }
  });

  // Function to fetch and reload employees
  function fetchSortedEmployees(type, order) {
    let orderParam = 'desc'; // fallback

    if (type === 'active') {
      orderParam = order;
    }

    $.ajax({
      url: "<?= base_url('/admin/Monitoring_room/get_active_hours_by_latest_date') ?>",

      type: 'GET',
      dataType: 'json',
      data: {
        order: orderParam
      },
      success: function(response) {
        const employeeGrid = $('#employeeGrid');
        employeeGrid.empty();

        if (response.status === true && response.active_hours.length > 0) {
          const employees = response.active_hours;

          $.each(employees, function(index, employee) {
            const card = `
<div class="employee-card" id="employee-card-${employee.employee_id}">
  <div class="card-thumbnail" onclick="openVideoModal('${employee.employee_id}', '${employee.name}', '${employee.employee_id}')">
    <img id="screenshot-${employee.employee_id}" src="" alt="Employee Screen">
    <div class="live-badge">ONLINE</div>
  </div>
  <div class="card-body">
    <p class="employee-id">ID: ${employee.employee_id}</p>
    <h3 class="employee-name">
      <i class="bi bi-person-fill"></i> ${employee.name}
    </h3>
    <div class="activity-stats">
      <div class="stat-item active-stat">
        <strong class="stat-label">Active: </strong>
        <span class="stat-value" id="active-time-${employee.employee_id}">${employee.total_active_time}</span>
      </div>
      <div class="stat-item inactive-stat">
        <strong class="stat-label">Inactive :</strong>
        <span class="stat-value" id="inactive-time-${employee.employee_id}">00:00</span>
      </div>
    </div>
  </div>
</div>
        `;

            employeeGrid.append(card);
            getActivity(employee.employee_id);
            getLatestScreenshot(employee.employee_id);
          });
        } else {
          employeeGrid.html('<p class="text-center py-4" style="grid-column: 1 / -1">No matching employees found</p>');
        }
      },
      error: function() {
        $('#employeeGrid').html('<p class="text-center py-4" style="grid-column: 1 / -1">Error loading employees</p>');
      }
    });
  }

  // Add this to your sort select change handler
  $('#sortSelect').on('change', function() {
    const selectedValue = $(this).val();

    if (selectedValue === 'inactive') {
      fetchInactiveEmployees();
    }
    // ... other sort options
  });

  function fetchInactiveEmployees(order = 'desc') {
    $.ajax({
      url: "<?= base_url('/admin/Monitoring_room/get_inactive_hours_by_latest_date') ?>",
      type: 'GET',
      dataType: 'json',
      data: {
        order: order
      },
      success: function(response) {
        const employeeGrid = $('#employeeGrid');
        employeeGrid.empty();

        if (response.status === true && response.inactive_hours.length > 0) {
          const employees = response.inactive_hours;

          $.each(employees, function(index, employee) {
            // Format the inactive time (remove seconds if present)
            let inactiveTime = employee.total_idle_time;
            if (inactiveTime && inactiveTime.includes(':')) {
              const parts = inactiveTime.split(':');
              if (parts.length === 3) {
                inactiveTime = `${parts[0]}:${parts[1]}`; // HH:MM
              }
            }

            const card = `
<div class="employee-card" id="employee-card-${employee.employee_id}">
  <div class="card-thumbnail" onclick="openVideoModal('${employee.employee_id}', '${employee.name}', '${employee.employee_id}')">
    <img id="screenshot-${employee.employee_id}" src="" alt="Employee Screen">
    <div class="live-badge">ONLINE</div>
  </div>
  <div class="card-body">
    <p class="employee-id">ID: ${employee.employee_id}</p>
    <h3 class="employee-name">
      <i class="bi bi-person-fill"></i> ${employee.name}
    </h3>
    <div class="activity-stats">
      <div class="stat-item active-stat">
        <strong class="stat-label">Active: </strong>
        <span class="stat-value" id="active-time-${employee.employee_id}">00:00</span>
      </div>
      <div class="stat-item inactive-stat">
        <strong class="stat-label">Inactive: </strong>
        <span class="stat-value" id="inactive-time-${employee.employee_id}">${inactiveTime}</span>
      </div>
    </div>
  </div>
</div>
`;

            employeeGrid.append(card);
            getActivity(employee.employee_id);
            getLatestScreenshot(employee.employee_id);
          });
        } else {
          employeeGrid.html('<p class="text-center py-4" style="grid-column: 1 / -1">No employees with inactive hours found</p>');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching inactive hours:', error);
        $('#employeeGrid').html('<p class="text-center py-4" style="grid-column: 1 / -1">Error loading inactive hours data</p>');
      }
    });
  }

  // Optional: Add toggle functionality for sort order
  $('#sortIcon').on('click', function() {
    if ($('#sortSelect').val() === 'inactive') {
      const currentOrder = $(this).data('order') || 'desc';
      const newOrder = currentOrder === 'desc' ? 'asc' : 'desc';
      $(this).data('order', newOrder);

      // Update icon to show sort direction
      $(this).find('i').removeClass('bi-arrow-down bi-arrow-up')
        .addClass(newOrder === 'desc' ? 'bi-arrow-down' : 'bi-arrow-up');

      fetchInactiveEmployees(newOrder);
    }
  });
</script>

<script src="<?= base_url('ws-client.js'); ?>"></script>