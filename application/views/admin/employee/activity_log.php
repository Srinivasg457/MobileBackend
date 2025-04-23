<div class="content-wrapper">
  <div class="manual-entry-container">
    <h2>Manual Entry</h2>

    <div class="entry-header">
      <label>Employee
        <input type="text" placeholder="Enter employee name" />
      </label>
      <label>Date
        <input type="date" />
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
      <div class="status-box active">Active <strong>06 hrs 30 min</strong></div>
      <div class="status-box inactive">Inactive <strong>1 hr 0 min</strong></div>
      <div class="status-box manual">Manual <strong>1 hr 0 min</strong></div>
      <div class="status-box meeting">Meeting <strong>00:00</strong></div>
    </div>

    <h3>Timeline</h3>
    <table class="timeline-table">
      <thead>
        <tr>
          <th>Time</th>
          <th>Project</th>
          <th>Task</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>12:00 - 12:30</td>
          <td>Lorem</td>
          <td>Ipsum</td>
          <td>Active</td>
        </tr>
        <tr>
          <td>12:30 - 12:32</td>
          <td>Lorem</td>
          <td>Ipsum</td>
          <td>Idle</td>
        </tr>
        <tr>
          <td>12:32 - 12:45</td>
          <td>Lorem</td>
          <td>Ipsum</td>
          <td>Manual</td>
        </tr>
      </tbody>
    </table>

    <h3>Logs</h3>
    <table class="log-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Period</th>
          <th>Duration</th>
          <th>Task</th>
          <th>Note</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>18/05/2025</td>
          <td>12:00 - 12:30</td>
          <td>30 min</td>
          <td>Lorem</td>
          <td>Lorem</td>
          <td>Requested</td>
        </tr>
      </tbody>
    </table>

    <div class="manual-button-wrap">
      <button class="manual-add-btn" onclick="openModal()">➕ Add Manual Entry</button>
    </div>
  </div>

  <!-- Modal -->
  <div class="manual-modal" id="manualModal">
    <div class="manual-modal-overlay" onclick="closeModal()"></div>
    <div class="manual-modal-content">
      <h3>Add Manual Entry</h3>
      <div class="manual-entry-form">
        <div class="form-row">
          <label>Start Time <input type="time" /></label>
          <label>End Time <input type="time" /></label>
        </div>
        <div class="form-row">
          <label>Project <input type="text" placeholder="Enter project name" /></label>
          <label>Task 
            <select>
              <option>Ipsum</option>
              <option>Dolor</option>
            </select>
          </label>
        </div>
      <div class="form-row full-width">
  <label for="notes">Notes</label>
  <textarea id="notes" rows="2" placeholder="Enter notes" class="textarea-full"></textarea>
</div>

        <div class="form-actions">
          <button class="save-btn">💾 Save</button>
          <button class="cancel-btn" onclick="closeModal()">❌ Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .form-row.full-width {
  width: 100%;
  display: flex;
  flex-direction: column;
}

.textarea-full {
  width: 100%;
  box-sizing: border-box;
  resize: vertical; /* optional */
}

  
    .manual-entry-container {
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  margin: auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
}

h2, h3 {
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
  flex:15px;
  font-weight: 500;
  color: #2c3e50;
}

input, select, textarea {
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

.timeline-table, .log-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

th, td {
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

.save-btn, .cancel-btn {
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

</style>

<script>
    function openModal() {
  document.getElementById('manualModal').classList.add('show');
}

function closeModal() {
  document.getElementById('manualModal').classList.remove('show');
}

</script>