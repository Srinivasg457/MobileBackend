<div class="content-wrapper">
  <section class="content">
    <h2>Live Monitoring Dashboard</h2>
 
    <div class="toolbar">
      <input type="text" id="searchInput" placeholder="Search employees by name or ID...">
      <select id="sortSelect">
        <option value="">Sort by</option>
        <option value="name">Name (A-Z)</option>
        <option value="active">Active Hours (High-Low)</option>
        <option value="inactive">Inactive Hours (High-Low)</option>
      </select>
    </div>
 
    <div class="card-container" id="employeeCards">
      <div class="loading">Loading employees...</div>
    </div>
 
    <!-- Modal -->
    <div id="videoModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeVideoModal()">&times;</span>
        <img id="remoteVideo" autoplay playsinline>
        <div class="modal-info">
          <h3 id="modalName"></h3>
          <p id="modalId"></p>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
:root {
  --primary-color: #3498db;
  --secondary-color: #2980b9;
  --success-color: #2ecc71;
  --danger-color: #e74c3c;
  --light-color: #ecf0f1;
  --dark-color: #2c3e50;
  --text-color: #333;
  --text-light: #7f8c8d;
  --border-radius: 8px;
  --box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  --transition: all 0.3s ease;
}

/* Updated CSS for the card layout */
.card-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 25px;
  margin-top: 20px;
}

.card {
  background: #fff;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  overflow: hidden;
  transition: var(--transition);
  border: 1px solid #e0e0e0;
  display: flex;
  flex-direction: column;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
}

.image-container {
  position: relative;
  height: 200px;
  overflow: hidden;
  cursor: pointer;
}

.image-container img.thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: var(--transition);
}

.card:hover .image-container img.thumbnail {
  transform: scale(1.05);
}

.video-call-icon {
  position: absolute;
  top: 15px;
  right: 15px;
  width: 36px;
  height: 36px;
  background-color: white;
  padding: 7px;
  border-radius: 50%;
  box-shadow: 0 3px 10px rgba(0,0,0,0.2);
  transition: var(--transition);
}

.card:hover .video-call-icon {
  background: var(--primary-color);
  transform: scale(1.1);
}

.card-content {
  padding: 16px;
}

.card-content h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  color: var(--dark-color);
  font-weight: 600;
}

.card-content p {
  margin: 0 0 12px 0;
  color: var(--text-light);
  font-size: 14px;
}

.status {
  display: flex;
  justify-content: space-between;
  margin-top: 12px;
  padding-top: 10px;
  border-top: 1px solid #eee;
}

.status span {
  display: flex;
  align-items: center;
  font-size: 14px;
  gap: 5px;
}

.status-icon {
  display: inline-block;
  width: 12px;
  height: 12px;
  border-radius: 50%;
}

.active {
  color: var(--success-color);
  font-weight: 500;
}

.inactive {
  color: var(--danger-color);
  font-weight: 500;
}

.loading {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px;
  color: #666;
}

/* Modal styles */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.8);
  backdrop-filter: blur(5px);
}

.modal-content {
  position: relative;
  margin: auto;
  width: 80%;
  max-width: 900px;
  background: #fff;
  border-radius: var(--border-radius);
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  top: 50%;
  transform: translateY(-50%);
  animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
  from {opacity: 0; transform: translateY(-60%);}
  to {opacity: 1; transform: translateY(-50%);}
}

.modal-content img#remoteVideo {
  width: 100%;
  height: auto;
  max-height: 500px;
  object-fit: contain;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.modal-info {
  padding: 15px;
  text-align: center;
}

.close {
  position: absolute;
  top: -40px;
  right: 0;
  color: white;
  font-size: 30px;
  font-weight: bold;
  cursor: pointer;
  opacity: 0.8;
  transition: var(--transition);
}

.close:hover {
  opacity: 1;
  transform: scale(1.1);
}

.screen-label {
  background-color: #ccc;
  height: 200px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
  font-weight: bold;
  color: #333;
  cursor: pointer;
  position: relative;
}

.screen-text {
  text-align: center;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .card-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .card-container {
    grid-template-columns: 1fr;
  }
  
  .modal-content {
    width: 95%;
  }
}

@media (max-width: 480px) {
  .toolbar {
    flex-direction: column;
    gap: 10px;
  }
  
  .toolbar input[type="text"],
  .toolbar select {
    width: 100%;
  }
}
</style>

<script>
$(document).ready(function() {
  // Fetch employees via AJAX
  function fetchEmployees() {
    $.ajax({
      url: "<?= base_url('admin/Monitoring_room/list_employees_by_user'); ?>",
      type: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $('#employeeCards').html('<div class="loading">Loading employees...</div>');
      },
      success: function(response) {
        if(response.status === 'success') {
          renderEmployeeCards(response.employees);
        } else {
          $('#employeeCards').html('<div class="loading">Error: ' + response.message + '</div>');
        }
      },
      error: function(xhr, status, error) {
        $('#employeeCards').html('<div class="loading">Error loading employees. Please try again.</div>');
        console.error('AJAX Error:', error);
      }
    });
  }

  // Render employee cards with improved design
  function renderEmployeeCards(employees) {
    if (employees.length === 0) {
      $('#employeeCards').html('<div class="loading">No employees found</div>');
      return;
    }

    let cardsHTML = '';

    employees.forEach((employee, index) => {
      const activeHours = (Math.random() * 3 + 4).toFixed(1);
      const inactiveHours = (Math.random() * 2).toFixed(1);

      cardsHTML += `
        <div class="card" data-name="${employee.name.toLowerCase()}" data-id="${employee.id}">
          <div class="image-container" onclick="openVideoModal('https://www.w3schools.com/html/mov_bbb.mp4', '${employee.name}', 'ID: ${employee.id}')">
            <div class="screen-label">
              <div class="screen-text">Screen-${index + 1}</div>
            </div>
            <img src="https://img.icons8.com/ios-filled/50/000000/video-call.png" alt="Video Call" class="video-call-icon">
          </div>
          <div class="card-content">
            <h3>${employee.name}</h3>
            <p>ID: ${employee.id}</p>
            <div class="status">
              <span class="active"><span class="status-icon"></span> ${activeHours} hrs active</span>
              <span class="inactive"><span class="status-icon"></span> ${inactiveHours} hrs idle</span>
            </div>
          </div>
        </div>
      `;
    });

    $('#employeeCards').html(cardsHTML);
  }

  // Search functionality
  $('#searchInput').on('input', function() {
    const searchTerm = $(this).val().toLowerCase();
    $('.card').each(function() {
      const name = $(this).data('name');
      const id = $(this).data('id').toString();
      if(name.includes(searchTerm) || id.includes(searchTerm)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });

  // Sort functionality
  $('#sortSelect').change(function() {
    const sortBy = $(this).val();
    if(!sortBy) return;
    
    const $cards = $('.card').get();
    
    $cards.sort((a, b) => {
      if(sortBy === 'name') {
        return $(a).data('name').localeCompare($(b).data('name'));
      } else if(sortBy === 'active') {
        const hoursA = parseFloat($(a).find('.active').text().split(' ')[0]);
        const hoursB = parseFloat($(b).find('.active').text().split(' ')[0]);
        return hoursB - hoursA;
      } else if(sortBy === 'inactive') {
        const hoursA = parseFloat($(a).find('.inactive').text().split(' ')[0]);
        const hoursB = parseFloat($(b).find('.inactive').text().split(' ')[0]);
        return hoursB - hoursA;
      }
      return 0;
    });
    
    $('#employeeCards').empty().append($cards);
  });

  // Initialize
  fetchEmployees();
});

// WebSocket for live video
const socket = new WebSocket('wss://work-room.io:8090');
socket.binaryType = 'blob'; 
const img = document.getElementById('remoteVideo');

socket.onopen = function() {
  console.log('WebSocket connection established');
};

socket.onclose = function() {
  console.log('WebSocket connection closed');
};

socket.onerror = function(error) {
  console.error('WebSocket error:', error);
};

function playVideo(id) {
  socket.send(JSON.stringify({ action: 'start_stream', employee_id: id }));
}

socket.onmessage = function(event) {
  const blob = event.data;
  const fixedBlob = new Blob([blob], { type: 'image/jpeg' });
  const url = URL.createObjectURL(fixedBlob);
  img.src = url;
  img.onload = () => URL.revokeObjectURL(url);
};

// Video modal functions
function openVideoModal(videoUrl, name, id) {
  const modal = document.getElementById('videoModal');
  const remoteVideo = document.getElementById('remoteVideo');
  const modalName = document.getElementById('modalName');
  const modalId = document.getElementById('modalId');

  // Set video source
  remoteVideo.src = videoUrl;

  // Set employee info
  modalName.textContent = name;
  modalId.textContent = id;

  // Display modal
  modal.style.display = 'block';
  
  // Start WebSocket stream
  playVideo(id.split(': ')[1]);
}

function closeVideoModal() {
  const modal = document.getElementById('videoModal');
  const remoteVideo = document.getElementById('remoteVideo');

  // Stop video playback
  remoteVideo.src = '';

  // Hide modal
  modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
  const modal = document.getElementById('videoModal');
  if (event.target === modal) {
    closeVideoModal();
  }
};
</script>