<style>
  :root {
    --primary: #4361ee;
    --primary-light: #e6e9ff;
    --secondary: #3f37c9;
    --success: #5cb85c;
    --danger: #ff0505;
    --warning: #f8961e;
    --info: #4895ef;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
    --light-gray: #e9ecef;
    --border-radius: 0.375rem;
    --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  }

  .monitoring-container {
    padding: 2rem;
    background-color: #f5f7fb;
    min-height: 100vh;
  }

  .monitoring-header {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .monitoring-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
  }

  .monitoring-toolbar {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }

  .search-input {
    padding: 0.625rem 1rem;
    border-radius: var(--border-radius);
    border: 1px solid #ced4da;
    font-size: 0.875rem;
    width: 240px;
    transition: var(--transition);
  }

  .search-input:focus {
    /* outline: none; */
    /* border-color: var(--primary); */
    /* box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25); */
  }

  .sort-select {
    padding: 0.625rem 1rem !important;
    border-radius: var(--border-radius);
    border: 1px solid #ced4da;
    font-size: 0.875rem;
    background-color: white;
    transition: var(--transition);
    width: 200px;
  }

  .sort-select:focus {
    /* outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25); */
  }

  .employee-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
  }

  .employee-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: var(--transition);
    border: 1px solid var(--light-gray);
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .employee-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--box-shadow);
    border-color: var(--primary-light);
  }

  .card-thumbnail {
    position: relative;
    height: 180px;
    overflow: hidden;
    background-color: #f0f2f5;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .card-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    background-color: #e9ecef;
  }

  .blank-screen {
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray);
    font-size: 0.875rem;
  }

  .employee-card:hover .card-thumbnail img {
    transform: scale(1.03);
  }

  .live-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background-color: var(--danger);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 2;
  }

  .live-badge::before {
    content: "";
    display: block;
    width: 0.5rem;
    height: 0.5rem;
    background-color: white;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0% {
      opacity: 1;
    }

    50% {
      opacity: 0.3;
    }

    100% {
      opacity: 1;
    }
  }

  .card-body {
    padding: 0.55rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  .employee-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .employee-id {
    font-size: 1.0rem;
    color: var(--gray);
    margin-bottom: 0.5rem;
  }

  .activity-stats {
    display: flex;
    justify-content: space-between;
    padding-top: 1rem;
    margin-top: auto;
    border-top: 1px solid var(--light-gray);

    >div {
      border-radius: 5px;
      padding: 3px 10px;
    }
  }

  .stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 0.5rem;
  }

  .stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
  }

  .stat-label {
    font-size: 0.875rem;
    /* color: var(--gray); */
    /* text-transform: uppercase; */
    letter-spacing: 0.5px;
  }

  .active-stat {
    color: var(--success);
    display: inline;
    background-color: #DEF9EC;
  }

  .inactive-stat {
    color: var(--danger);
    display: inline;
    background-color: #FFF2F1;
  }

  /* Modal Styles */
  .monitoring-modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
  }

  .modal-dialog {
    position: absolute;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 1000px;
  }

  .modal-content {
    background-color: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    /* animation: modalFadeIn 0.1s ease-out; */
  }

  @keyframes modalFadeIn {
    from {
      opacity: 0;
      transform: translate(-50%, -55%);
    }

    to {
      opacity: 1;
      transform: translate(-50%, -50%);
    }
  }

  .modal-header {
    padding: 1rem 1.5rem;
    background-color: var(--primary);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
  }

  .modal-subtitle {
    font-size: 0.875rem;
    opacity: 0.9;
  }

  .modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.8;
    transition: var(--transition);
  }

  .modal-close:hover {
    opacity: 1;
  }

  .modal-body {
    padding: 0;
    text-align: center;
  }

  .modal-screen {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
    background-color: black;
  }

  .modal-footer {
    padding: 1rem 1.5rem;
    background-color: var(--light);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .connection-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
  }

  .status-indicator {
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: var(--success);
    animation: pulse 1.5s infinite;
  }

  /* Responsive Adjustments */
  @media (max-width: 992px) {
    .employee-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .monitoring-container {
      padding: 1.5rem;
    }

    .monitoring-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .monitoring-toolbar {
      width: 100%;
    }

    .search-input {
      width: 100%;
    }

    .modal-dialog {
      width: 95%;
    }
  }

  @media (max-width: 576px) {
    .employee-grid {
      grid-template-columns: 1fr;
    }

    .activity-stats {
      flex-direction: column;
      gap: 0.75rem;
    }

    .stat-item {
      align-items: flex-start;
    }
  }

  #sortIcon {
    vertical-align: middle;
    font-size: 18px;
  }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


<div class="content-wrapper">
  <section class="content">
    <div class="monitoring-container">
      <div class="monitoring-header">
        <h1 class="monitoring-title">Employee Monitoring Dashboard</h1>
        <div class="monitoring-toolbar">
          <input type="text" class="search-input form-control" placeholder="Search employees...">
          <select class="sort-select form-control" id="sortSelect">
            <option value="">Sort by</option>
            <option value="employeeName">Employee Name</option>
            <option value="active">Active Hours</option>
            <option value="inactive">Inactive Hours</option>
          </select>
          <!-- Sorting icon -->
          <span id="sortIcon" style="cursor: pointer; display: inline-block; margin-left: 10px;">
            <i class="bi bi-arrow-down-up"></i>
          </span>
        </div>
      </div>
      <div class="employee-grid" id="employeeGrid">
        <!-- Employee list will appear here -->
      </div>
      <!-- Monitoring Modal -->
      <div id="monitoringModal" class="monitoring-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <div>
                <h3 class="modal-title" id="modalEmployeeName"></h3>
                <p class="modal-subtitle" id="modalEmployeeId"></p>
              </div>
              <button class="modal-close" onclick="closeVideoModal()">&times;</button>
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
                    <div class="live-badge">LIVE</div>
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
                    <div class="live-badge">LIVE</div>
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
      url: "<?= base_url('/admin/ScreenshotController/get_last_monitoring_screenshot') ?>",
      method: "GET",
      dataType: "json",
      data: {
        employee_id: currentEmployeeId
      },
      success: function(response) {
        const thumbnail = $(`#screenshot-${currentEmployeeId}`);
        const container = thumbnail.parent();

        if (response.status === 'success' && response.screenshot.image_url) {
          thumbnail.attr('src', response.screenshot.image_url)
            .on('error', function() {
              container.addClass('blank-screen').html('<span>No Screen Available</span>');
            });
          container.removeClass('blank-screen');
        } else {
          container.addClass('blank-screen').html('<span>No Screen Available</span>');
        }
      },
      error: function() {
        $(`#screenshot-${currentEmployeeId}`).parent()
          .addClass('blank-screen')
          .html('<span>No Screen Available</span>');
      }
    });
  }

  <script src = "<?= base_url('ws-client.js'); ?>"></script>

$(document).ready(function () {
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
    <div class="live-badge">LIVE</div>
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
$('#sortIcon').on('click', function () {
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
$('#sortSelect').on('change', function () {
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
$('#sortSelect').on('change', function () {
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
$('#sortIcon').on('click', function () {
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
    <div class="live-badge">LIVE</div>
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
    <div class="live-badge">LIVE</div>
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