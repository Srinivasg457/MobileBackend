<style>
    .custom-legend {
        display: flex;
        flex-direction: column;
        /* 👈 vertical stacking */
        align-items: flex-start;
        gap: 10px;
        margin-top: 20px;
        font-weight: bold;
    }

    .custom-legend-item {
        display: flex;
        align-items: center;
    }

    .custom-legend-box {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        border-radius: 3px;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-top: 20px;
    }
    @media (max-width: 1200px) {
        .card-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 768px) {
        .card-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .card-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="d-flex justify-content-between align-items-baseline mb-3">
            <h3 class="mb-0">Dashboard</h3>
            <div class="form-group col-lg-6">
                <form id="dateRangeForm" class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('employee/dashboard') ?>" method="post">
                    <div class="row">

                        <div class="col-lg-6 form-group">
                            <label class="control-label">From</label>

                            <div class="input-group">
                                <input type="text" class="inv-dpick form-control datepicker" name="fromDate" value="<?php echo $employee_activity['from_date'] ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="control-label">To</label>

                            <div class="input-group">
                                <input type="text" class="inv-dpick form-control datepicker" name="toDate" value="<?php echo $employee_activity['to_date'] ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <button type="submit" class="input-group-addon btn btn-secondary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- <label class="control-label">Date</label> -->

                </form>
            </div>
        </div>
        <div class=" mt-20">
            <div class="card-grid">
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Productive Hours" ?></h4>
                                <h2 class="m-b-0"><?php echo $employee_activity['total_active'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Unproductive Hours" ?></h4>
                                <h2 class="m-b-0"><?php echo $employee_activity['total_idle'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Shift Time" ?></h4>
                                <h2 class="m-b-0"><?php echo $employee_activity['shift_time'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Key Stroke" ?></h4>
                                <h2 class="m-b-0"><?php echo $employee_activity['total_keystrokes'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Mouse Activity" ?></h4>
                                <h2 class="m-b-0"><?php echo $employee_activity['total_mouse_movements'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-sm-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Overall Productivity" ?></h3>
                    </div>
                    <div class="box-body">
                        <div style="height:260px;text-align: center;justify-content: center;display: flex;">
                            <canvas id="ProductivityReportChart" style="height: 250px; width: 100%;"></canvas>
                        </div>
                        <div id="doughnutLegend" class="custom-legend"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Last 4 Weeks Report"  ?></h3>
                    </div>
                    <div class="box-body">
                        <canvas id="weeklyReportChart" style="height: 400px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Focus or Action" ?></h3>
                    </div>
                    <div class="box-body">
                        <?php print_r($overall_productivity) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Insights (AI Generated)" ?></h3>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
$('#dateRangeForm').on('submit', function(e) {
    const fromDate = $('input[name="fromDate"]').val();
    const toDate = $('input[name="toDate"]').val();

    // Simple validation
    if (!fromDate || !toDate) {
        e.preventDefault();
        showToast('Both From and To dates are required.', 'error');
        return;
    }

    const from = new Date(fromDate);
    const to = new Date(toDate);

    if (from > to) {
        e.preventDefault();
        showToast('"From Date" should not be after "To Date".', 'error');
        return;
    }
});

// Submit form on date change
$(document).on('change', "#datePicker", function() {
    $('.user_filter_form').submit();
});

// ✅ Doughnut Chart Data
const doughnutData = {
    labels: ['Manual Time', 'Meeting Time', 'Inactive Time', 'Active Time'],
    datasets: [{
        label: 'Time Breakdown',
        data: [
            <?php echo $overall_productivity["manual_percentage"]; ?>,
            <?php echo $overall_productivity["meeting_percentage"]; ?>,
            <?php echo $overall_productivity["idle_percentage"]; ?>,
            <?php echo $overall_productivity["active_percentage"]; ?>
        ],
        backgroundColor: [
            'rgb(75, 192, 192)', // Manual
            'rgb(255, 99, 132)', // Meeting
            'rgb(255, 205, 86)', // Inactive
            'rgb(55, 175, 255)' // Active
        ],
        hoverOffset: 4
    }]
};

// ✅ Doughnut Chart Config (No recalculation!)
const doughnutConfig = {
    type: 'doughnut',
    data: doughnutData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false // hide default legend
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        return `${context.label}: ${value}%`;
                    }
                }
            }
        }
    }
};

// ✅ Render Chart
const doughnutCtx = document.getElementById('ProductivityReportChart').getContext('2d');
const doughnutChart = new Chart(doughnutCtx, doughnutConfig);

// ✅ Custom Legend Renderer (use raw value directly)
function renderCustomLegend(chart, legendId) {
    const container = document.getElementById(legendId);
    container.innerHTML = '';

    const labels = chart.data.labels;
    const data = chart.data.datasets[0].data;
    const colors = chart.data.datasets[0].backgroundColor;

    labels.forEach((label, i) => {
        const value = data[i];

        const item = document.createElement('div');
        item.classList.add('custom-legend-item');

        const box = document.createElement('div');
        box.classList.add('custom-legend-box');
        box.style.backgroundColor = colors[i];

        const text = document.createElement('span');
        text.innerText = `${label}: ${value}%`;

        item.appendChild(box);
        item.appendChild(text);
        container.appendChild(item);
    });
}

renderCustomLegend(doughnutChart, 'doughnutLegend');

// ✅ Weekly Chart (Bar + Line)
const weeklyReportsData = <?php echo json_encode($weekly_report); ?>;
const weeklyCtx = document.getElementById('weeklyReportChart').getContext('2d');


        const labels = [];
        const barData = [];
        const lineData = [];
        let maxBarValue = 0;
        weeklyReportsData.sort((a, b) => {
            const dateA = new Date(a.date_range.split(' to ')[0]);
            const dateB = new Date(b.date_range.split(' to ')[0]);
            return dateA - dateB;
        });
        weeklyReportsData.forEach(report => {
            const timeParts = report.total_active_time.split(':');
            const totalHours = parseInt(timeParts[0]) +
parseInt(timeParts[1]) / 60 +
parseInt(timeParts[2]) / 3600;

labels.push(report.week_name);
barData.push(totalHours);
lineData.push((totalHours * 1.05).toFixed(2));

            if (totalHours > maxBarValue) {
                maxBarValue = totalHours;
            }
        });

        const suggestedMaxY = Math.ceil((maxBarValue + 5) / 10) * 10;

        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Total Active Time (hr)',
                        data: barData,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        order: 2,
                        barPercentage: 0.5,
                        categoryPercentage: 0.7
                    },
                    {
                        label: 'Trend (hr)',
                        data: lineData,
                        type: 'line',
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: false,
                        tension: 0.3,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: suggestedMaxY,
                        title: {
                            display: true,
                            text: 'Hours (hr)',
                            font: {
                                size: 14,
                                weight: 600,
                                color: '#444'
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 14,
                                weight: 600,
                                color: '#444'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week',
                            font: {
                                size: 14,
                                weight: 600,
                                color: '#444'
                            }
                        },
                        ticks: {
                            font: {
                                size: 14,
                                weight: 600,
                                color: '#444'
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' hr';
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 600,
                                color: '#444'

                            },
                            padding: 20
                        }
                    }
                }
            }
        });
    });
</script>
