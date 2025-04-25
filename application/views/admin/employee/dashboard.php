<div class="content-wrapper">

    <section class="content">


        <style>
            .export-panel {
                display: flex;
                align-items: flex-end;
                gap: 100px;
                padding: 20px;
                background: #fff;
                border: 1px solid #b1aaaa;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            .field,
            .export-button {
                flex: 1;
                /* Equal width for all */
                display: flex;
                flex-direction: row;
            }

            label {
                font-weight: bold;
                font-size: 14px;
                margin-bottom: 5px;
            }

            input[type="text"],
            select,
            input[type="date"] {
                padding: 8px 10px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 5px;
                width: 100%;
            }

            .time-range {
                display: flex;
                gap: 10px;
            }

            .time-range input {
                flex: 1;
                /* Equal width inside time range */
            }

            .export-button {
                background-color: #1e1e1e;
                color: white;
                padding: 10px 20px;
                font-size: 14px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                align-items: center;
                justify-content: center;
                gap: 8px;
                height: 100%;
            }

            .export-button:hover {
                background-color: #333;
            }
        </style>
        <h3>Reports</h3>
        <div class="export-panel">
            <div class="field">
                <label>Employee</label>
                <input type="text" value="Veena Ramamoorthy">
            </div>
            <div class="field">
                <label>Date</label>
                <input type="date">
            </div>
            <div class="field">
                <label>Log Period</label>
                <select>
                    <option>Select Log Period</option>
                </select>
            </div>

            <div class="field">
                <label>Date Range</label>
                <div class="time-range">
                    <input type="date" placeholder="From">
                    <input type="date" placeholder="To">
                </div>
            </div>

            <button class="export-button">
                Export <i class="fa fa-download" aria-hidden="true"></i>

            </button>
        </div>



        <h3>Employee Log</h3>
        <div class="employee-log-wrapper">
            <table class="employee-log-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Active</th>
                        <th>Time Out</th>
                        <th class="orange-text">Manual</th>
                        <th class="green-text">Total</th>
                        <th class="green-text">Time Worked</th>
                        <th class="purple-text">Meeting Mode</th>
                        <th>Per. Act.Sec.</th>
                        <th>First Activity</th>
                        <th>Last Activity</th>
                        <th>Time stopped duration between FA and LA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Veena</td>
                        <td>06:56</td>
                        <td>00:00</td>
                        <td class="orange-text">00:00</td>
                        <td class="green-text">06:56</td>
                        <td class="green-text">06:56</td>
                        <td class="purple-text">00:00</td>
                        <td>54</td>
                        <td>08:32</td>
                        <td>18:18</td>
                        <td>02:49</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <span>&larr; Prev</span>
                <span>0</span>
                <span>Next &rarr;</span>
            </div>
        </div>

        <style>
            .employee-log-wrapper {
                margin: 20px 0;
                background: #fff;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                overflow-x: auto;
                border: 1px solid #b1aaaa;
            }

            .employee-log-table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
                font-size: 14px;
            }

            .employee-log-table thead th {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                background: #f9f9f9;
            }

            .employee-log-table tbody td {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }

            .orange-text {
                color: orange;
                font-weight: bold;
            }

            .green-text {
                color: #00b894;
                font-weight: bold;
            }

            .purple-text {
                color: #a29bfe;
                font-weight: bold;
            }

            .pagination {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-top: 10px;
                font-size: 14px;
                color: #666;
            }
        </style>
        <h3>Employee Summary Of The Last 7 Days</h3>

        <div class="summary-card">
            <div class="field">

            </div>
            <div style="display: flex; flex-direction: row; justify-content: space-between; height: 280px;">

                <div class="bars-section" style="display: flex
;
    flex-direction: column;
    justify-content: center;
    gap: 40px;">
                    <div class="bar-row">
                        <div class="bar full-time"></div>
                        <span class="bar-text teal">45 h</span>
                    </div>
                    <div class="bar-row">
                        <div class="bar manual-time"></div>
                        <span class="bar-text gold">4 h</span>
                    </div>
                    <div class="bar-row">
                        <div class="bar meeting-time"></div>
                        <span class="bar-text purple">1 h</span>
                    </div>
                </div>

                <!-- Donut Chart -->
                <div class="donut-chart-container">
                    <svg width="250" height="300" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background Circle -->
                        <circle cx="60" cy="60" r="50" stroke="#e6e6e6" stroke-width="10" fill="none" />
                        <!-- Green Progress Circle (50%) -->
                        <circle cx="60" cy="60" r="50" stroke="#00c9a7" stroke-width="10" fill="none" stroke-dasharray="314, 314" stroke-dashoffset="0" transform="rotate(-90 60 60)" />
                        <!-- Grey Remaining Circle (50%) -->
                        <circle cx="60" cy="60" r="50" stroke="#b1b1b1" stroke-width="10" fill="none" stroke-dasharray="314, 314" stroke-dashoffset="157" transform="rotate(-90 60 60)" />

                        <!-- Text Inside Donut -->
                        <text x="50%" y="50%" text-anchor="middle" dy=".1em" font-size="12px">Total</text>
                        <text x="50%" y="64%" text-anchor="middle" font-size="10px" fill="#333">45:00 hrs</text>
                    </svg>
                </div>



                <div class="tags">
                    <span class="tag teal">45:00<br><small>Total</small></span>
                    <span class="tag gold">04:00<br><small>Manual</small></span>
                    <span class="tag purple">01:00<br><small>Meeting</small></span>
                </div>
            </div>
        </div>

        <style>
            .tags .tag {
                display: inline-block;
                width: 120px;
                /* Increase this value as needed */
                padding: 10px;
                text-align: center;
                border-radius: 8px;
                font-size: 16px;
                line-height: 1.2;
            }

            .tag.teal {
                background-color: #008080;
                color: white;
            }

            .tag.gold {
                background-color: #FFD700;
                color: black;
            }

            .tag.purple {
                background-color: #800080;
                color: white;
            }

            .summary-card {
                background: #fafafa;
                padding: 30px;
                border-radius: 10px;
                font-family: 'Arial', sans-serif;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #b1aaaa;
            }

            .name-input {
                width: 250px;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 6px;
                margin-bottom: 20px;
                font-size: 14px;
            }

            .bars-section {
                margin-bottom: 20px;
            }

            .bar-row {
                display: flex;
                align-items: center;
                margin-bottom: 15px;

            }

            .bar {
                height: 16px;
                border-radius: 10px;
                margin-right: 10px;
            }

            .full-time {
                width: 300px;
                background-color: #00c9a7;
            }

            .manual-time {
                width: 250px;
                background-color: #e0b253;
            }

            .meeting-time {
                width: 200px;
                background-color: #9966ff;
            }

            .bar-text {
                font-weight: bold;
                font-size: 18px;
            }

            .bar-text.teal {
                color: #00c9a7;
            }

            .bar-text.gold {
                color: #e0b253;
            }

            .bar-text.purple {
                color: #9966ff;
            }

            .tags {
                display: flex;
                gap: 50px;
                height: fit-content;
                margin-top: 100px;
            }

            .tag {
                padding: 10px 16px;
                border-radius: 12px;
                color: #fff;
                font-weight: bold;
                font-size: 14px;
                text-align: center;
                min-width: 80px;
            }

            .tag.teal {
                background-color: #a6f3e5;
                color: #00c9a7;
            }

            .tag.gold {
                background-color: #f9e4c5;
                color: #e0b253;
            }

            .tag.purple {
                background-color: #e1d5ff;
                color: #9966ff;
            }

            .donut-chart-container {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 10px;
            }
        </style>


    </section>
</div>