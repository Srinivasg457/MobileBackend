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
 
    h2 {
      margin-bottom: 25px;
      color: var(--dark-color);
      font-weight: 600;
      font-size: 28px;
    }
 
    .toolbar {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
      justify-content: space-between;
    }
 
    .toolbar input[type="text"],
    .toolbar select {
      padding: 10px 15px;
      border-radius: var(--border-radius);
      border: 1px solid #ddd;
      font-size: 14px;
      background-color: white;
      transition: var(--transition);
    width:30%}
 
    .toolbar input[type="text"]:focus,
    .toolbar select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }
 
    .card-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 3 columns */
  gap: 25px;
}
 
 
    .card {
      background-color: #fff;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: var(--transition);
    }
 
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
 
    .card .image-container {
      position: relative;
      cursor: pointer;
    }
 
    .card img.thumbnail {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
 
    .card .video-call-icon {
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
 
    .card .video-call-icon:hover {
      transform: scale(1.1);
    }
 
    .card-content {
      padding: 5px;
    }
 
    .card-content h3 {
      margin: 0 0 5px;
      font-size: 18px;
      font-weight: 600;
      color: var(--dark-color);
    }
 
    .card-content p {
      margin: 0 0 10px;
      color: var(--text-light);
      font-size: 14px;
    }
 
    .status {
      display: flex;
      justify-content: space-between;
      margin: 15px 0 5px;
      padding-top: 10px;
      border-top: 1px solid #eee;
    }
 
    .status span {
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
 
    .status-icon {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      display: inline-block;
    }
 
    .active {
      color: var(--success-color);
      font-weight: 500;
    }
 
    .inactive {
      color: var(--danger-color);
      font-weight: 500;
    }
 
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
      background-color: #fefefe;
      margin: auto;
      width: 80%;
      max-width: 900px;
      border-radius: var(--border-radius);
      position: relative;
      top: 50%;
      transform: translateY(-50%);
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      animation: modalFadeIn 0.3s ease-out;
    }
 
    @keyframes modalFadeIn {
      from {opacity: 0; transform: translateY(-60%);}
      to {opacity: 1; transform: translateY(-50%);}
    }
 
    .modal-content video {
      width: 100%;
      height: auto;
      border-radius: var(--border-radius) var(--border-radius) 0 0;
    }
 
    .modal-info {
      padding: 15px;
      background-color: white;
      border-radius: 0 0 var(--border-radius) var(--border-radius);
    }
 
    .modal-info h3 {
      margin: 0 0 5px;
      color: var(--dark-color);
    }
 
    .modal-info p {
      margin: 0;
      color: var(--text-light);
      font-size: 14px;
    }
 
    .close {
      color: white;
      position: absolute;
      top: -40px;
      right: 0;
      font-size: 30px;
      font-weight: bold;
      cursor: pointer;
      z-index: 10000;
      opacity: 0.8;
      transition: var(--transition);
    }
 
    .close:hover {
      opacity: 1;
      transform: scale(1.1);
    }
 
    @media(max-width: 768px) {
      .modal-content {
        width: 95%;
      }
 
      .card-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }
    }
 
    @media(max-width: 480px) {
      .toolbar {
        flex-direction: column;
        gap: 10px;
      }
 
      .toolbar input[type="text"],
      .toolbar select {
        width: 100%;
      }
    }
    video { width: 100%; max-width: 100%; border: 1px solid #ccc; }
    button { margin: 10px 0; padding: 10px 20px; }
</style>


<div class="content-wrapper">
  <section class="content">
 
  <h2>Live Monitoring Dashboard</h2>
 
  <div class="toolbar">
    <input type="text" placeholder="Search employees by name or ID...">
    <select>
      <option value="">Sort by</option>
      <option value="name">Name (A-Z)</option>
      <option value="active">Active Hours (High-Low)</option>
      <option value="inactive">Inactive Hours (High-Low)</option>
    </select>
  </div>
 
  <div class="card-grid" id="employee-container">
  <!-- Employee cards will be inserted here -->
</div>
<script>
$(document).ready(function () {
  fetchEmployees();
});

function fetchEmployees() {
  $.ajax({
    url: "<?= base_url('/admin/Monitoring_room/list_employees_by_user') ?>",    type: 'GET',
    dataType: 'json',
    success: function (response) {
      if (response.status === 'success') {
        const container = $('#employee-container');
        container.empty(); // Clear previous content

        $.each(response.employees, function (index, employee) {
          const card = `
            <div class="card">
              <div class="image-container" onclick="openVideoModal('https://www.w3schools.com/html/mov_bbb.mp4', '${employee.name}', 'ID: ${employee.id}')">
                <img src="https://cdn.mos.cms.futurecdn.net/f5kTB9Cb3HSGjfPiiTcobK.jpg" class="thumbnail" alt="Video Feed">
                <img src="https://img.icons8.com/ios-filled/50/000000/video-call.png" alt="Video Call" class="video-call-icon">
              </div>
              <div class="card-content">
                <h3>${employee.name}</h3>
                <p>ID: ${employee.id}</p>
                <div class="status">
                  <span class="active"><span class="status-icon" style="background: var(--success-color);"></span> 06:00 hrs active</span>
                  <span class="inactive"><span class="status-icon" style="background: var(--danger-color);"></span> 01:00 hrs inactive</span>
                </div>
              </div>
            </div>
          `;
          container.append(card);
        });
      } else {
        alert('Failed to load employees: ' + response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX Error:', error);
    }
  });
}
</script>

 
 
  <!-- Modal -->
  <div id="videoModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeVideoModal()">&times;</span>
      <img id="screen" autoplay playsinline>
      <div class="modal-info">
        <h3 id="modalName"></h3>
        <p id="modalId"></p>
      </div>
    </div>
  </div>
 
  </section>
</div>


<script>
    function openVideoModal(videoUrl, name, id) {
      document.getElementById("modalName").innerText = name;
      document.getElementById("modalId").innerText = id;
      document.getElementById("videoModal").style.display = "block";
      playVideo(employeeId) 
    }
 
    function closeVideoModal() {
      const modal = document.getElementById("videoModal");
      modal.style.display = "none";
    }
 
    window.onclick = function(event) {
      const modal = document.getElementById("videoModal");
      if (event.target === modal) {
        closeVideoModal();
      }
    }
  </script>


<body>

 <h2>Live Screen Viewer</h2>



<script>
  
const ws = new WebSocket('wss://work-room.io:8090');

// const ws = new WebSocket('ws://localhost:8090'); 



ws.binaryType = 'arraybuffer';
function playVideo(employeeId) {

  ws.send(JSON.stringify({
    type: 'viewer-join',
    employee_id: employeeId, 
  }));
}

ws.addEventListener('message', (event) => {
  if (typeof event.data !== 'string') {
    const blob = new Blob([event.data], { type: 'image/jpeg' });
    const url = URL.createObjectURL(blob);

    const img = document.getElementById('screen');
    img.src = url;
    img.onload = () => {
      URL.revokeObjectURL(url);
    };
  }
});

</script>





