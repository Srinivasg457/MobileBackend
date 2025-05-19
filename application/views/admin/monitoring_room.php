<style>
  :root {
    --primary: #4361ee;
    --primary-light: #e6e9ff;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --danger: #f72585;
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
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
  }

  .sort-select {
    padding: 0.625rem 1rem;
    border-radius: var(--border-radius);
    border: 1px solid #ced4da;
    font-size: 0.875rem;
    background-color: white;
    transition: var(--transition);
  }

  .sort-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
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
    0% { opacity: 1; }
    50% { opacity: 0.3; }
    100% { opacity: 1; }
  }

  .card-body {
    padding: 1.25rem;
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
    font-size: 0.875rem;
    color: var(--gray);
    margin-bottom: 1rem;
  }

  .activity-stats {
    display: flex;
    justify-content: space-between;
    padding-top: 1rem;
    margin-top: auto;
    border-top: 1px solid var(--light-gray);
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
    font-size: 0.75rem;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .active-stat .stat-value {
    color: var(--success);
  }

  .inactive-stat .stat-value {
    color: var(--danger);
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
    animation: modalFadeIn 0.3s ease-out;
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
</style>

<div class="content-wrapper">
  <section class="content">
<div class="monitoring-container">
  <div class="monitoring-header">
    <h1 class="monitoring-title">Employee Monitoring Dashboard</h1>
    <div class="monitoring-toolbar">
      <input type="text" class="search-input" placeholder="Search employees...">
      <select class="sort-select">
        <option value="">Sort by</option>
        <option value="name">Name (A-Z)</option>
        <option value="active">Active Hours</option>
        <option value="inactive">Inactive Hours</option>
      </select>
    </div>
  </div>

  <div class="employee-grid" id="employeeGrid">
    <!-- Employee cards will be dynamically inserted here -->
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
        <div class="modal-footer">
          <div class="connection-status">
            <span class="status-indicator"></span>
            <span>Connected</span>
          </div>
          <div class="timestamp" id="modalTimestamp"></div>
        </div>
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
                    <img id="screenshot-${employee.id}" src="" alt="Employee Screen" onerror="this.onerror=null;this.src='';this.parentNode.classList.add('blank-screen');this.parentNode.innerHTML='<span>No Screen Available</span>'">
                    <div class="live-badge">LIVE</div>
                  </div>
                  <div class="card-body">
                    <h3 class="employee-name">
                      <i class="bi bi-person-fill"></i> ${employee.name}
                    </h3>
                    <p class="employee-id">ID: ${employee.id}</p>
                    <div class="activity-stats">
                      <div class="stat-item active-stat">
                        <span class="stat-value" id="active-time-${employee.id}">00:00</span>
                        <span class="stat-label">Active</span>
                      </div>
                      <div class="stat-item inactive-stat">
                        <span class="stat-value" id="inactive-time-${employee.id}">00:00</span>
                        <span class="stat-label">Inactive</span>
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
        data: { name: searchQuery },
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
                    <img id="screenshot-${employee.id}" src="" alt="Employee Screen" onerror="this.onerror=null;this.src='';this.parentNode.classList.add('blank-screen');this.parentNode.innerHTML='<span>No Screen Available</span>'">
                    <div class="live-badge">LIVE</div>
                  </div>
                  <div class="card-body">
                    <h3 class="employee-name">
                      <i class="bi bi-person-fill"></i> ${employee.name}
                    </h3>
                    <p class="employee-id">ID: ${employee.id}</p>
                    <div class="activity-stats">
                      <div class="stat-item active-stat">
                        <span class="stat-value" id="active-time-${employee.id}">00:00</span>
                        <span class="stat-label">Active</span>
                      </div>
                      <div class="stat-item inactive-stat">
                        <span class="stat-value" id="inactive-time-${employee.id}">00:00</span>
                        <span class="stat-label">Inactive</span>
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
      data: { employee_id: currentEmployeeId },
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
      data: { employee_id: currentEmployeeId },
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

  // WebSocket functionality remains unchanged
  const ws = new WebSocket('wss://work-room.io:8090');
  const video = document.getElementById('modalScreen');

  ws.binaryType = 'arraybuffer';

  function playVideo(employeeId) {
    try {
      ws.send(JSON.stringify({
        type: 'viewer-join',
        employee_id: parseInt(employeeId)
      }));
    } catch (error) {
      alert("Unable to connect. Please start the server.");
      console.error("WebSocket Error:", error);
    }
  }

  ws.addEventListener('message', (event) => {
    if (typeof event.data !== 'string') {
      const blob = new Blob([event.data], { type: 'image/jpeg' });
      const url = URL.createObjectURL(blob);

      const img = document.getElementById('modalScreen');
      img.src = url;
      img.onload = () => {
        URL.revokeObjectURL(url);
      };
      
      // Update timestamp when new image arrives
      const now = new Date();
      document.getElementById("modalTimestamp").innerText = `Last updated: ${now.toLocaleTimeString()}`;
    }
  });
</script>