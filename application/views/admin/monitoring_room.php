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
    --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
    width: 30%
  }

  .toolbar input[type="text"]:focus,
  .toolbar select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
  }

  .card-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    /* 3 columns */
    gap: 95px;
  }


  .card {
    background-color: #fff;
    border-radius: var(--border-radius);
    /* box-shadow: var(--box-shadow); */
    box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
    margin-bottom: 0;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .card .image-container {
    position: relative;
    cursor: pointer;
  }

  .card img.thumbnail {
    width: 100%;
    height: 200px;
    object-fit: cover;
    padding: 15px;
  }

  .card .video-call-icon {
    position: absolute;
    top: 25px;
    right: 25px;
    width: 35px;
    height: 35px;
    background-color: white;
    padding: 7px;
    border-radius: 50%;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    transition: var(--transition);
  }

  .card .video-call-icon:hover {
    transform: scale(1.1);
  }

  .card-content {
    /* padding: 5px; */
    padding: 0px 15px 15px 15px;
  }

  .card-content h3 {
    /* margin: 0 0 5px; */
    margin: 0 0 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-color);
  }

  .card-content p {
    line-height: 30px;
    margin: 0 0 0;
    color: var(--text-light);
    font-size: 14px;
  }

  .status {
    /*       display: flex; */
    justify-content: space-between;
    margin: 15px 0 0;
    /* padding-top: 10px; */
    border-top: 1px solid #eee;
  }

  .thumbnail-image-container {
    padding: 15px;
    height: 170px;
    overflow: hidden;
    display: flex;
    justify-content: center;

    >img {
      width: -webkit-fill-available;
      height: initial;
      border-radius: 5px;
    }
  }


  .status>.row>div>span {
    padding: 5px 15px;
    border-radius: 15px;
    width: fit-content;
    font-size: 12px;
    font-weight: bolder;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .status>.row>div>.active {
    background: #DEFAEC;
  }

  .status>.row>div>.inactive {
    background: #FEF1F1;
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
    background-color: rgba(0, 0, 0, 0.8);
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
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: modalFadeIn 0.3s ease-out;
  }

  @keyframes modalFadeIn {
    from {
      opacity: 0;
      transform: translateY(-60%);
    }

    to {
      opacity: 1;
      transform: translateY(-50%);
    }
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

  @media(max-width: 1204px) {
    .mobileAligment {
      text-align: left !important;
      justify-content: left !important;
      margin-top: 5px;
    }

    .modal-content {
      width: 95%;
    }

    .card-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 50px;
    }

    .status-icon {
      height: 10px;
    }
  }

  @media(max-width: 480px) {
    .toolbar {
      flex-direction: column;
      gap: 10px;
    }

    .mobileAligment {
      text-align: left !important;
      justify-content: left !important;
      margin-top: 5px;
    }

    .toolbar input[type="text"],
    .toolbar select {
      width: 100%;
    }

    .card-grid {
      grid-template-columns: repeat(1, 1fr);
      /* gap: 50px; */
    }
  }

  video {
    width: 100%;
    max-width: 100%;
    border: 1px solid #ccc;
  }

  button {
    margin: 10px 0;
    padding: 10px 20px;
  }
</style>


<div class="content-wrapper">
  <section class="content">

    <h2>Live Monitoring</h2>

    <div class="toolbar">
      <input type="text" placeholder="Search employees by name or ID...">
      <select>
        <option value="">Sort by</option>
        <option value="name">Name (A-Z)</option>
        <option value="active">Active Hours (High-Low)</option>
        <option value="inactive">Inactive Hours (High-Low)</option>
      </select>
    </div>

    <div class="card-grid">
      <!-- Cards will be dynamically inserted here -->
    </div>


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
  function loadAllEmployees() {
    $.ajax({
      url: "<?= base_url('/admin/Monitoring_room/list_employees_by_user') ?>",
      method: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          const employees = response.employees;
          const cardGrid = $('.card-grid');
          cardGrid.empty();

          $.each(employees, function(index, employee) {
            const card = `
            <div class="card" id="employee-card-${employee.id}">
              <div class="thumbnail-image-container" id="thumb-container-${employee.id}" onclick="openVideoModal('https://www.w3schools.com/html/mov_bbb.mp4', '${employee.name}', '${employee.id}')">
                <img id="screenshot-${employee.id}" src="https://t4.ftcdn.net/jpg/10/78/87/79/240_F_1078877919_BuOhReO2s7w5Yu6ReT39b4bsoTTomARa.jpg" alt="Live Screen">
              </div>
              <div class="card-content">
                <div class="status">
                  <div class="row mt-4">
                    <div class="col-md-12"><h3><i class="bi bi-person-fill"></i> ${employee.name}</h3></div>
                    <div class="col-md-12 mobileAligment"><p>ID: ${employee.id}</p></div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-lg-6">
                      <span class="active">
                        <span class="status-icon" style="background: var(--success-color);"></span> 
                        <span id="active-time-${employee.id}">00:00 hrs Active</span>
                      </span>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-end mobileAligment">
                      <span class="inactive">
                        <span class="status-icon" style="background: var(--danger-color);"></span> 
                        <span id="inactive-time-${employee.id}">00:00 hrs Inactive</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;

            cardGrid.append(card);
            getActivity(employee.id);
            getLatestScreenshot(employee.id);
          });
        } else {
          $('.card-grid').html('<p>No employees found</p>');
        }
      },
      error: function() {
        alert('There was an error fetching employee data');
      }
    });
  }
  $(document).ready(function() {
    // Make AJAX GET request to fetch employee data
    loadAllEmployees();
  });
</script>

<script>
  $('input[placeholder="Search employees by name or ID..."]').on('keyup', function() {
    const searchQuery = $(this).val().trim();

    // If input is empty, load the full employee list
    if (searchQuery === "") {
      loadAllEmployees(); // <-- Call original employee loader
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
        if (response.status === 'success' && response.employees.length > 0) {
          const employees = response.employees;
          const cardGrid = $('.card-grid');
          cardGrid.empty();

          $.each(employees, function(index, employee) {
            const card = `
            <div class="card" id="employee-card-${employee.id}">
              <div class="thumbnail-image-container" id="thumb-container-${employee.id}" onclick="openVideoModal('https://www.w3schools.com/html/mov_bbb.mp4', '${employee.name}', '${employee.id}')">
                <img id="screenshot-${employee.id}" src="https://t4.ftcdn.net/jpg/10/78/87/79/240_F_1078877919_BuOhReO2s7w5Yu6ReT39b4bsoTTomARa.jpg" alt="Live Screen">
              </div>
              <div class="card-content">
                <div class="status">
                  <div class="row mt-4">
                    <div class="col-md-12"><h3><i class="bi bi-person-fill"></i> ${employee.name}</h3></div>
                    <div class="col-md-12 mobileAligment"><p>ID: ${employee.id}</p></div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-md-6">
                      <span class="active">
                        <span class="status-icon" style="background: var(--success-color);"></span> 
                        <span id="active-time-${employee.id}">00:00 hrs Active</span>
                      </span>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end mobileAligment">
                      <span class="inactive">
                        <span class="status-icon" style="background: var(--danger-color);"></span> 
                        <span id="inactive-time-${employee.id}">00:00 hrs Inactive</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;

            cardGrid.append(card);
            getActivity(employee.id);
            getLatestScreenshot(employee.id);
          });
        } else {
          const cardGrid = $('.card-grid');
          cardGrid.empty();
          $('.card-grid').html('<p>No employees found</p>');
        }
      },
      error: function() {
        alert('Failed to fetch employee list');
      }
    });
  });


  function openVideoModal(videoUrl, name, id) {
    const img = document.getElementById('screen');
    img.src = "";
    document.getElementById("modalName").innerText = name;
    document.getElementById("modalId").innerText = id;
    document.getElementById("videoModal").style.display = "block";
    playVideo(id)
  }

  function closeVideoModal() {
    const modal = document.getElementById("videoModal");
    modal.style.display = "none";
    const img = document.getElementById('screen');
    img.src = "";

  }

  window.onclick = function(event) {
    const modal = document.getElementById("videoModal");
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
        employee_id: currentEmployeeId,
      },
      success: function(response) {


        if (response.status && response.data.length > 0) {
          const data = response.data[0];

          // Format Active time
          const activeParts = data.total_active_time.split(':');
          const activeHours = activeParts[0].padStart(2, '0');
          const activeMinutes = activeParts[1].padStart(2, '0');
          const activeFormatted = `${activeHours}:${activeMinutes} hrs`;
          $(`#active-time-${currentEmployeeId}`).text(activeFormatted + " Active");

          // Format Inactive time
          const idleParts = data.total_idle_time.split(':');
          const idleHours = idleParts[0].padStart(2, '0');
          const idleMinutes = idleParts[1].padStart(2, '0');
          const idleFormatted = `${idleHours}:${idleMinutes} hrs`;
          $(`#inactive-time-${currentEmployeeId}`).text(idleFormatted + " Inactive");
        } else {
          $(`#active-time-${currentEmployeeId}`).text("00:00 hrs Active");
          $(`#inactive-time-${currentEmployeeId}`).text("00:00 hrs Inactive");
        }
      },
      error: function() {
        console.log('Failed to load time log data');
      }
    });
  }

  function getLatestScreenshot(currentEmployeeId) {
    // ✅ After appending the card, call AJAX to fetch screenshot
    $.ajax({
      url: "<?= base_url('/admin/ScreenshotController/get_last_screenshot') ?>",
      method: "GET",
      dataType: "json",
      data: {
        employee_id: currentEmployeeId,
      },
      success: function(response) {

        if (response.status === 'success') {
          const imgSrc = response.screenshot.image_url;
          $(`#screenshot-${currentEmployeeId}`).attr('src', imgSrc);
        } else {
          $(`#screenshot-${currentEmployeeId}`).attr('src', 'https://t4.ftcdn.net/jpg/10/78/87/79/240_F_1078877919_BuOhReO2s7w5Yu6ReT39b4bsoTTomARa.jpg');
        }
      },
      error: function(re) {
        $(`#screenshot-${currentEmployeeId}`).attr('src', 'https://t4.ftcdn.net/jpg/10/78/87/79/240_F_1078877919_BuOhReO2s7w5Yu6ReT39b4bsoTTomARa.jpg');
      }
    });
  }
</script>






<script>
  const ws = new WebSocket('wss://work-room.io:8090');

  // const ws = new WebSocket('ws://localhost:8090'); 

  const video = document.getElementById('screen');

  let mediaSource = new MediaSource();
  video.src = URL.createObjectURL(mediaSource);

  let sourceBuffer;

  mediaSource.addEventListener('sourceopen', () => {
    sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');
  });

  ws.binaryType = 'arraybuffer';

  function playVideo(employeeId) {

    try {
      ws.send(JSON.stringify({
        type: 'viewer-join',
        employee_id: parseInt(employeeId) // Or use: parseInt(employeeId)
      }));
    } catch (error) {
      alert("Unable to connect. Please start the server.");
      console.error("WebSocket Error:", error);
    }
  }

  ws.addEventListener('message', (event) => {
    if (typeof event.data !== 'string') {
      const blob = new Blob([event.data], {
        type: 'image/jpeg'
      });
      const url = URL.createObjectURL(blob);

      const img = document.getElementById('screen');
      img.src = url;
      img.onload = () => {
        URL.revokeObjectURL(url);
      };
    }
  });
</script>