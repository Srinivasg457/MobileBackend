<style>
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
            <div class="form-group col-lg-3">
                <form class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('employee/dashboard') ?>" method="post">

                    <!-- <label class="control-label">Date</label> -->
                    <input type="date" class="form-control" name="date" id="datePicker" value="<?php echo $employee_activity['date'] ?>">
                    <!-- <div class="input-group">
                        <input type="text" id="datePicker" class="inv-dpick form-control datepicker" value="<?php echo $employee_activity['date'] ?>">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div> -->
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
<script>
    $(document).ready(function() {
        $(document).on('change', "#datePicker", function() {
            $('.user_filter_form').submit();
        });


        // document.addEventListener('DOMContentLoaded', function() {
        //     fetchWeeklyReports();
        // });

        // async function fetchWeeklyReports() {
        //     try {
        //         const url = "<?= base_url('admin/Time_logs/get_weekly_reports'); ?>";

        //         const employeeId = 20; // Example employee ID
        //         const userId = 6;      // Example user ID

        //         const response = await fetch(url, {
        //             method: 'GET',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'employee_id': employeeId,
        //                 'user_id': userId
        //             }
        //         });

        //         if (!response.ok) {
        //             const errorData = await response.json();
        //             throw new Error(errorData.message || 'Failed to fetch data');
        //         }

        //         const data = await response.json();
        //         console.log('API Response:', data);

        //         if (data.status === 'success' && data.data) {
        //             renderChart(data.data);
        //         } else {
        //             console.error('API returned an error or no data:', data.message);
        //             alert('Error: ' + (data.message || 'Could not retrieve data.'));
        //         }

        //     } catch (error) {
        //         console.error('Error fetching weekly reports:', error);
        //         alert('Failed to load chart data: ' + error.message);
        //     }
        // }

        // function renderChart(weeklyReportsData) {
        const weeklyReportsData = <?php echo json_encode($weekly_report); ?>;
        const ctx = document.getElementById('weeklyReportChart').getContext('2d');

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

            labels.push(report.week_name.replace('Week of ', 'Week '));
            barData.push(totalHours);
            lineData.push(totalHours * 1.05); // Adjust line data to be slightly above bar data

            if (totalHours > maxBarValue) {
                maxBarValue = totalHours;
            }
        });

        // Calculate dynamic max for y-axis
        const suggestedMaxY = Math.ceil((maxBarValue + 5) / 10) * 10;
        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: labels,
            datasets: [
                {
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
                        title: {
                            display: true,
                            text: 'Hours (hr)',
                            font: {
                                size: 14,
                                weight: 600 ,      // Added from file 2
                                color: '#444'       // Added from file 2
                            }
                            
                        },
                        ticks: {
                            callback: function(value) {
                                return value;
                            },
                            stepSize: 10,
                            font: {
                                size: 14,
                                weight: 600 ,      // Added from file 2
                                color: '#444'       // Added from file 2
                            }
                        },
                        // Set dynamic max for y-axis
                        max: suggestedMaxY
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week',
                            font: {
                                size: 14,
                                weight: 600 ,      // Added from file 2
                                color: '#444'       // Added from file 2
                            }
                        },
                        ticks: {
                                font: {
                                size: 14,
                                weight: 600 ,      // Added from file 2
                                color: '#444'       // Added from file 2
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + 'hr';
                            }
                        },
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14,
                                weight: 600 ,      // Added from file 2
                                color: '#444' // Added from file 2
                            },
                            padding: 20
                        }
                    },
                }
            }
        });
    });
    // }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>