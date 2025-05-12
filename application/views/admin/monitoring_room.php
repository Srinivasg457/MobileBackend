<!-- Add this right after the opening <body> tag -->
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
/* Updated CSS for the card layout */
.card-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 20px;
}

.card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: 1px solid #e0e0e0;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
}

.image-container {
  position: relative;
  height: 180px;
  overflow: hidden;
  cursor: pointer;
}

.image-container img.thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.card:hover .image-container img.thumbnail {
  transform: scale(1.05);
}

.video-call-icon {
  position: absolute;
  bottom: 15px;
  right: 15px;
  width: 40px;
  height: 40px;
  background: rgba(0, 0, 0, 0.6);
  border-radius: 50%;
  padding: 8px;
  transition: all 0.3s ease;
}

.card:hover .video-call-icon {
  background: rgba(0, 120, 255, 0.8);
  transform: scale(1.1);
}

.card-content {
  padding: 16px;
}

.card-content h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  color: #333;
  font-weight: 600;
}

.card-content p {
  margin: 0 0 12px 0;
  color: #666;
  font-size: 14px;
}

.status {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 12px;
}

.status span {
  display: flex;
  align-items: center;
  font-size: 13px;
  color: #555;
}

.status-icon {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-right: 8px;
}

.active .status-icon {
  background: #4CAF50;
}

.inactive .status-icon {
  background: #F44336;
}

.loading {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px;
  color: #666;
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
}
.modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.8);
}

.modal-content {
  position: relative;
  margin: 5% auto;
  padding: 20px;
  background: #fff;
  width: 80%;
  max-width: 800px;
  border-radius: 8px;
  box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

.modal-content img#remoteVideo {
  width: 100%;
  height: auto;
  max-height: 400px;
  object-fit: contain;
  border-radius: 6px;
}

.modal-info {
  margin-top: 15px;
  text-align: center;
}

.close {
  position: absolute;
  top: 10px; right: 15px;
  font-size: 28px;
  font-weight: bold;
  color: #aaa;
  cursor: pointer;
}

.close:hover {
  color: #000;
}
.screen-label {
  background-color: #ccc;
  height: 150px;
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

  // Render employee cards with different design
  function renderEmployeeCards(employees) {
  if (employees.length === 0) {
    $('#employeeCards').html('<div class="loading">No employees found</div>');
    return;
  }

  let cardsHTML = '';

  employees.forEach((employee, index) => {
    const activeHours = (Math.random() * 3 + 4).toFixed(1);
    const inactiveHours = (Math.random() * 2).toFixed(1);
    const productivityScore = Math.floor(Math.random() * 30) + 70;

    cardsHTML += `
      <div class="card" data-name="${employee.name.toLowerCase()}" data-id="${employee.id}">
        <div class="image-container screen-label" onclick="openVideoModal('https://www.w3schools.com/html/mov_bbb.mp4', '${employee.name}', 'ID: ${employee.id}')">
          <div class="screen-text">Screen-${index + 1}</div>
        </div>
        <div class="card-content">
          <h3>${employee.name}</h3>
          <p>ID: ${employee.id}</p>
          <div class="status">
            <span class="active"><span class="status-icon"></span> Active: ${activeHours} hrs</span>
            <span class="inactive"><span class="status-icon"></span> Idle: ${inactiveHours} hrs</span>
           
          </div>
        </div>
      </div>
    `;
  });

  $('#employeeCards').html(cardsHTML);
}

  function getProgressColor(score) {
    if (score >= 85) return '#4CAF50';
    if (score >= 70) return '#8BC34A';
    if (score >= 50) return '#FFC107';
    return '#F44336';
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
        const hoursA = parseFloat($(a).find('.active').text().split(' ')[1]);
        const hoursB = parseFloat($(b).find('.active').text().split(' ')[1]);
        return hoursB - hoursA;
      } else if(sortBy === 'inactive') {
        const hoursA = parseFloat($(a).find('.inactive').text().split(' ')[1]);
        const hoursB = parseFloat($(b).find('.inactive').text().split(' ')[1]);
        return hoursB - hoursA;
      }
      return 0;
    });
    
    $('#employeeCards').empty().append($cards);
  });

  // Initialize
  fetchEmployees();
});

// Video modal functions
function openVideoModal(videoUrl, name, id) {
  $('#modalName').text(name);
  $('#modalId').text(id);
  $('#videoModal').show();
  playVideo(id);
}

function closeVideoModal() {
  $('#videoModal').hide();
}

$(window).click(function(event) {
  if(event.target === $('#videoModal')[0]) {
    closeVideoModal();
  }
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
}

function closeVideoModal() {
  const modal = document.getElementById('videoModal');
  const remoteVideo = document.getElementById('remoteVideo');

  // Stop video playback
  remoteVideo.src = '';

  // Hide modal
  modal.style.display = 'none';
}

</script>