 <style>
     /* ===================== Focus Text Styling ===================== */
     .focus-text {
         padding: 12px 16px;
         font-size: 14px;
         background: #ffffff;
         margin-bottom: 10px;
         border-radius: 6px;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
         font-weight: 600;
         color: #444;
         font-family: 'Segoe UI', sans-serif;
         transition: background 0.3s;
     }

     .focus-text:hover {
         background: #fff3cd;
     }

     .box-body:hover .focus-scroller {
         animation-play-state: paused;
     }

     /* ===================== Insight Auto-Scroll Styling ===================== */
     .insight-scroller {
         position: absolute;
         bottom: 0;
         animation: scroll-up 10s linear infinite;
         width: 100%;
     }

     .insight-text {
         padding: 12px 16px;
         font-size: 14px;
         font-weight: 600;
         color: #444;
         background: #ffffff;
         margin-bottom: 10px;
         border-radius: 6px;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
         font-family: 'Segoe UI', sans-serif;
         transition: background 0.3s;
     }

     .insight-text:hover {
         background: #d0f0ff;
     }

     .box-body:hover .insight-scroller {
         animation-play-state: paused;
     }

     @keyframes scroll-up {
         0% {
             transform: translateY(100%);
         }

         100% {
             transform: translateY(-100%);
         }
     }

     /* ===================== Custom Legend ===================== */
     .custom-legend {
         display: flex;
         flex-direction: column;
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

     /* ===================== Responsive Card Grid ===================== */
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


 <div class="content-wrapper employee_dashboard">
     <section class="content">
         <?php
            $is_manual = !empty($from_date) && !empty($to_date);
            ?>
         <form id="dateRangeForm" class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('employee/dashboard') ?>" method="post">
             <div class="row my-0">
                 <div class="col-lg-6 form-group my-0 mt-20">
                     <h3 class="mb-0">Dashboard</h3>
                 </div>
                 <div class="col-lg-6 form-group my-0 mt-20">
                     <div class="row" id="predefined_filter_row" style="<?= $is_manual ? 'display: none;' : '' ?>">

                         <div class="col-lg-6"></div>
                         <div class="col-lg-6">
                             <input type="hidden" id="first_record_date" value="<?php echo $first_record_date; ?>">
                             <!-- <label class="control-label" for="period_search">Time Period</label> -->
                             <div class="input-group">
                                 <select name="Time_period" id="period_search" class="form-control">
                                     <option value="" <?= ($time_period == " ") ? 'selected' : ''; ?>>Select</option>
                                     <option value="current_week" <?= ($time_period == 'current_week') ? 'selected' : ''; ?>>Current Week</option>
                                     <option value="last_week" <?= ($time_period == 'last_week') ? 'selected' : ''; ?>>Last Week</option>
                                     <option value="two_week" <?= ($time_period == 'two_week') ? 'selected' : ''; ?>>Two Week</option>
                                     <option value="this_month" <?= ($time_period == 'this_month') ? 'selected' : ''; ?>>This Month</option>
                                     <option value="last_month" <?= ($time_period == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
                                     <option value="last_6_months" <?= ($time_period == 'last_6_months') ? 'selected' : ''; ?>>Last 6 Months</option>
                                     <option value="this_year" <?= ($time_period == 'this_year') ? 'selected' : ''; ?>>This Year</option>
                                 </select>


                                 <span id="searchManually" class="input-group-addon btn btn-secondary align-content-center mx-5"><i class="fa fa-search"></i> Pick Dates</span>

                             </div>
                         </div>
                     </div>

                     <div class="row position-relative" id="manual_filter_row" style="<?= $is_manual ? '' : 'display: none;' ?>">
                         <div class="col-12 text-right">
                             <button type="button" id="cancelManualFilter" class="btn btn-secondary btn-sm">
                                 <i class="fa fa-times"></i>
                             </button>
                         </div>
                         <?php
                            $today = date('Y-m-d');
                            $from_date = !empty($from_date) ? $from_date : $today;
                            $to_date = !empty($to_date) ? $to_date : $today;
                            ?>
                         <div class="col-lg-6 form-group">
                             <label class="control-label">From</label>
                             <div class="input-group">
                                 <input type="text" class="inv-dpick form-control datepicker" name="fromDate" value="<?php echo $from_date ?>">
                                 <!-- <span class="input-group-addon"><i class="fa fa-calendar"></i></span> -->
                             </div>
                         </div>

                         <div class="col-lg-6 form-group">
                             <label class="control-label">To</label>
                             <div class="input-group">
                                 <input type="text" class="inv-dpick form-control datepicker" name="toDate" value="<?php echo $to_date ?>">
                                 <!-- <span class="input-group-addon"><i class="fa fa-calendar"></i></span> -->
                                 <span class="input-group-addon btn btn-secondary align-content-center" id="search_date"><i class="fa fa-search"></i></span>
                             </div>
                         </div>
                     </div>


                 </div>

             </div>
         </form>

         <div class="mt-20">
             <div class="card-grid">
                 <div class="card counts">
                     <div class="card-body">
                         <div class="d-flex flex-row">
                             <div class="ml-20 align-self-center">
                                 <h4 class="text-muteds m-b-0"><?php echo "Active Hours" ?></h4>
                                 <h2 class="m-b-0"><?php echo $employee_activity['total_active'] ?></h2>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="card counts">
                     <div class="card-body">
                         <div class="d-flex flex-row">
                             <div class="ml-20 align-self-center">
                                 <h4 class="text-muteds m-b-0"><?php echo "Inactive  Hours" ?></h4>
                                 <h2 class="m-b-0"><?php echo $employee_activity['total_idle'] ?></h2>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="card counts">
                     <div class="card-body">
                         <div class="d-flex flex-row">
                             <div class="ml-20 align-self-center">
                                 <h4 class="text-muteds m-b-0"><?php echo "Total Hours" ?></h4>
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
                         <h3 class="box-title"><?php echo "Focus"; ?></h3>
                     </div>
                     <div class="box-body" style="height: 220px; overflow: hidden; position: relative; padding: 10px; background: linear-gradient(to top, #fff8e1, #ffffff); border-radius: 8px;">
                         <div class="focus-scroller">
                             <div class="focus-text">⏳ Only <span style="color: green; font-weight: bold;"><?= $target_data['remaining_active_time']; ?> </span> hours left to reach your 47.5-hour goal. Focus on high-priority tasks!</div>
                             <div class="focus-text">📵 Long inactive periods detected. Set reminders to stay engaged.</div>
                             <?php if (!empty($yesterday_idle_alert)): ?>
                                 <div class="focus-text">
                                     <?php
                                        $message = $yesterday_idle_alert['message'];
                                        // Split the message to highlight just "increased"
                                        $parts = explode('increased', $message);
                                        ?>
                                     <?= $parts[0]; ?>
                                     <span style="color: red; font-weight: bold;">increased</span>
                                     <?= $parts[1] ?? ''; ?>
                                     .Try using mini-breaks intentionally, and avoid passive screen time.
                                     <br>

                                 </div>
                             <?php endif; ?>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="col-sm-6">
                 <div class="box">
                     <div class="box-header with-border">
                         <h3 class="box-title"><?php echo "Insights"; ?></h3>
                     </div>





                     <div class="box-body" style="height: 220px; overflow: hidden; position: relative; padding: 10px; background: linear-gradient(to top, #e0f7fa, #ffffff); border-radius: 8px;">

                         <div class="insight-scroller">




                             <div class="insight-text">🔔 You were inactive for <?= $inactive_data['total_idle_time']; ?> hours this week. Try taking short breaks to stay fresh!</div>

                             <div class="insight-text">🌞 Your most productive time is [morning/afternoon]. Schedule important work then!</div>

                             <div class="insight-text">🏆




                                 <?php if ($response_data['total_active_hours'] >= 47.5): ?>

                                     🏆 Last week, you exceeded your target! <?= $response_data['total_active_hours']; ?> hours. Keep up the momentum.

                                 <?php else: ?>

                                     Last week you have only managed <span style="color: red;"><?= $response_data['total_active_hours']; ?> </span> hours. So, Focus on Your work <?php endif; ?>

                             </div>

                             <div class="insight-text">⏱️ You were active for <?= $avarage_data['average_active_time']; ?> hours/day this week, with <?= $avarage_data['average_active_time']; ?> hours/day of inactivity.</div>

                             <div class="insight-text">📉 Your productivity <span style="color: <?= $active_time_comparison['status'] == 'increased by' ? 'green' : 'red' ?>"> <?= ucfirst($active_time_comparison['status']) ?> </span><?= $active_time_comparison['change_percentage'] ?>% compared to last week</div>


                         </div>

                     </div>



                 </div>
             </div>
     </section>
 </div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script>
     $(document).ready(function() {

         $('#searchManually').on('click', function() {
             $('#predefined_filter_row').hide();
             $('#manual_filter_row').css('display', 'flex');
         });

         $('#cancelManualFilter').on('click', function() {
             $('#manual_filter_row').hide();
             $('#predefined_filter_row').css('display', 'flex');
         });

         // Initialize datepickers if needed
         $('.datepicker').datepicker({
             format: 'yyyy-mm-dd',
             autoclose: true
         });
         $('#search_date').on('click', function(e) {
             const fromDate = $('input[name="fromDate"]').val();
             const toDate = $('input[name="toDate"]').val();
             const firstRecordDate = $('#first_record_date').val();

             // Simple validation
             if (!fromDate || !toDate) {
                 e.preventDefault();
                 showToast('Both From and To dates are required.', 'error');
                 return;
             }

             const from = fromDate;
             const to = toDate;
             const first = firstRecordDate;
             console.log(first);
             console.log(from);
             if (from > to) {
                 e.preventDefault();
                 showToast('"From Date" should not be after "To Date".', 'error');
                 return;
             }

             //   if (from < first) {
             //       e.preventDefault();
             //       showToast(`Data available only from ${first}.`, 'error');
             //       return;
             //   }


             // Disable dropdown and submit form
             $('#period_search').prop('disabled', true);
             $('.user_filter_form').submit();
         });



         // Submit form on date change
         let previousPeriodValue = $('#period_search').val(); // Set initial value

         $(document).on('focus', "#period_search", function() {
             // Store old value on focus
             previousPeriodValue = $(this).val();
         });

         $(document).on('change', "#period_search", function(e) {
             const firstRecordDate = $('#first_record_date').val(); // Already in 'YYYY-MM-DD'
             const first = new Date(firstRecordDate);
             first.setHours(0, 0, 0, 0);

             const today = new Date();
             today.setHours(0, 0, 0, 0);

             const selectedValue = $(this).val();
             let calculatedFromDate;

             switch (selectedValue) {
                 case 'today':
                     calculatedFromDate = new Date(today);
                     break;
                 case 'yesterday':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setDate(today.getDate() - 1);
                     break;
                 case 'last_7_days':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setDate(today.getDate() - 6);
                     break;
                 case 'last_14_days':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setDate(today.getDate() - 13);
                     break;
                 case 'last_21_days':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setDate(today.getDate() - 20);
                     break;
                 case 'this_month':
                     calculatedFromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                     break;
                 case 'last_1_month':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setMonth(today.getMonth() - 1);
                     break;
                 case 'last_2_months':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setMonth(today.getMonth() - 2);
                     break;
                 case 'last_6_months':
                     calculatedFromDate = new Date(today);
                     calculatedFromDate.setMonth(today.getMonth() - 6);
                     break;
                 case 'this_year':
                     calculatedFromDate = new Date(today.getFullYear(), 0, 1);
                     break;
                 default:
                     calculatedFromDate = new Date(today);
             }

             calculatedFromDate.setHours(0, 0, 0, 0); // normalize

             // Validate range
             //   if (calculatedFromDate < first) {
             //       e.preventDefault();
             //       showToast(`Data available only from ${firstRecordDate}.`, 'error');
             //       $(this).val(previousPeriodValue); // restore previous selection
             //       return;
             //   } 

             // Clear manual dates
             $('input[name="fromDate"]').val('');
             $('input[name="toDate"]').val('');

             $('.user_filter_form').submit();
         });
         render_employee_productivity_chart();
         render_fourWeek_report();


     });

     //   function render_fourWeek_report() {
     //       const weeklyReportsData = <?php echo json_encode($weekly_report); ?>;
     //       const weeklyCtx = document.getElementById('weeklyReportChart').getContext('2d');

     //       const labels = [];
     //       const barData = [];
     //       const lineData = [];
     //       let maxBarValue = 0;

     //       // Sort by week start date (from date_range)
     //       weeklyReportsData.sort((a, b) => {
     //           const dateA = new Date(a.date_range.split(' to ')[0]);
     //           const dateB = new Date(b.date_range.split(' to ')[0]);
     //           return dateA - dateB;
     //       });

     //       weeklyReportsData.forEach(report => {
     //           // Parse "44h 48m" to hours
     //           const activeParts = report.total_active.match(/(\d+)h\s+(\d+)m/);
     //           const hours = parseInt(activeParts[1] || 0);
     //           const minutes = parseInt(activeParts[2] || 0);
     //           const totalHours = hours + (minutes / 60);

     //           const [startDateStr, endDateStr] = report.date_range.split(' to ');
     //           const options = {
     //               day: '2-digit',
     //               month: 'short'
     //           };
     //           const startFormatted = new Date(startDateStr).toLocaleDateString('en-US', options);
     //           const endFormatted = new Date(endDateStr).toLocaleDateString('en-US', options);
     //           labels.push([report.week_name, `(${startFormatted} to ${endFormatted})`]);
     //           barData.push(parseFloat(totalHours.toFixed(2)));
     //           lineData.push((totalHours * 1.05).toFixed(2));

     //           if (totalHours > maxBarValue) {
     //               maxBarValue = totalHours;
     //           }
     //       });

     //       const suggestedMaxY = Math.ceil((maxBarValue + 5) / 10) * 10;

     //       new Chart(weeklyCtx, {
     //           type: 'bar',
     //           data: {
     //               labels: labels,
     //               datasets: [{
     //                       label: 'Total Active Time (hr)',
     //                       data: barData,
     //                       backgroundColor: 'rgba(54, 162, 235, 0.8)',
     //                       borderColor: 'rgba(54, 162, 235, 1)',
     //                       borderWidth: 1,
     //                       order: 2,
     //                       barPercentage: 0.5,
     //                       categoryPercentage: 0.7
     //                   },
     //                   {
     //                       label: 'Trend (hr)',
     //                       data: lineData,
     //                       type: 'line',
     //                       borderColor: 'rgb(75, 192, 192)',
     //                       backgroundColor: 'rgba(75, 192, 192, 0.2)',
     //                       fill: false,
     //                       tension: 0.3,
     //                       order: 1
     //                   }
     //               ]
     //           },
     //           options: {
     //               responsive: true,
     //               maintainAspectRatio: false,
     //               scales: {
     //                   y: {
     //                       beginAtZero: true,
     //                       max: suggestedMaxY,
     //                       title: {
     //                           display: true,
     //                           text: 'Hours (hr)',
     //                           font: {
     //                               size: 14,
     //                               weight: 600,
     //                               color: '#444'
     //                           }
     //                       },
     //                       ticks: {
     //                           stepSize: 1,
     //                           font: {
     //                               size: 14,
     //                               weight: 600,
     //                               color: '#444'
     //                           }
     //                       }
     //                   },
     //                   x: {
     //                       title: {
     //                           display: false,
     //                           text: 'Week',
     //                           font: {
     //                               size: 14,
     //                               weight: 600,
     //                               color: '#444'
     //                           }
     //                       },
     //                       ticks: {
     //                           font: {
     //                               size: 14,
     //                               weight: 600,
     //                               color: '#444'
     //                           }
     //                       }
     //                   }
     //               },
     //               plugins: {
     //                   tooltip: {
     //                       callbacks: {
     //                           label: function(context) {
     //                               const value = context.parsed.y;
     //                               const hrs = Math.floor(value);
     //                               const mins = Math.round((value - hrs) * 60);
     //                               return `${context.dataset.label}: ${hrs}h ${mins}m`;
     //                           }
     //                       }
     //                   },
     //                   legend: {
     //                       position: 'top',
     //                       labels: {
     //                           font: {
     //                               size: 14,
     //                               weight: 600,
     //                               color: '#444'
     //                           },
     //                           padding: 20
     //                       }
     //                   }
     //               }
     //           }
     //       });

     //   }

     function render_fourWeek_report() {
         const weeklyReportsData = <?php echo json_encode($weekly_report); ?>;
         const weeklyCtx = document.getElementById('weeklyReportChart').getContext('2d');

         const labels = [];
         const barData = [];
         const lineData = [];
         let maxBarValue = 0;

         // Sort by week start date (from date_range)
         weeklyReportsData.sort((a, b) => {
             const dateA = new Date(a.date_range.split(' to ')[0]);
             const dateB = new Date(b.date_range.split(' to ')[0]);
             return dateA - dateB;
         });

         weeklyReportsData.forEach(report => {
             // Parse "44h 48m" to total hours
             const activeParts = report.total_active.match(/(\d+)h\s+(\d+)m/);
             const hours = parseInt(activeParts[1] || 0);
             const minutes = parseInt(activeParts[2] || 0);
             const totalHours = hours + (minutes / 60);

             // Format label as: Week 4 (Mon to Sun)
             const [startDateStr, endDateStr] = report.date_range.split(' to ');
             const dayOptions = {
                 weekday: 'short'
             }; // "Mon", "Sun"
             const startDay = new Date(startDateStr).toLocaleDateString('en-US', dayOptions);
             const endDay = new Date(endDateStr).toLocaleDateString('en-US', dayOptions);

             labels.push(`${report.week_name}\n(${startDay} to ${endDay})`);

             barData.push(parseFloat(totalHours.toFixed(2)));
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
                 layout: {
                     padding: {
                         left: 10,
                         right: 10,
                         top: 10,
                         bottom: 10
                     }
                 },
                 scales: {
                     y: {
                         beginAtZero: true,
                         max: suggestedMaxY,
                         title: {
                             display: true,
                             text: 'Hours (hr)',
                             font: {
                                 size: window.innerWidth < 600 ? 10 : 12,
                                 weight: 'bold'
                             }
                         },
                         ticks: {
                             stepSize: 1,
                             font: {
                                 size: window.innerWidth < 600 ? 10 : 12
                             }
                         }
                     },
                     x: {
                         title: {
                             display: false
                         },
                         ticks: {
                             font: {
                                 size: window.innerWidth < 600 ? 5 : 12
                             },
                             callback: function(val, index) {
                                 const label = this.getLabelForValue(index);
                                 return label.split('\n'); // wraps label for smaller screens
                             }
                         }
                     }
                 },
                 plugins: {
                     tooltip: {
                         callbacks: {
                             label: function(context) {
                                 const value = context.parsed.y;
                                 const hrs = Math.floor(value);
                                 const mins = Math.round((value - hrs) * 60);
                                 return `${context.dataset.label}: ${hrs}h ${mins}m`;
                             }
                         }
                     },
                     legend: {
                         position: 'top',
                         labels: {
                             font: {
                                 size: 12
                             },
                             padding: 15
                         }
                     }
                 }
             }
         });
     }


     function render_employee_productivity_chart() {

         // Original PHP values
         let manual = <?php echo $overall_productivity["manual_percentage"]; ?>;
         let meeting = <?php echo $overall_productivity["meeting_percentage"]; ?>;
         let idle = <?php echo $overall_productivity["idle_percentage"]; ?>;
         let active = <?php echo $overall_productivity["active_percentage"]; ?>;

         let chartData = [manual, meeting, idle, active];
         const isAllZero = chartData.every(val => val === 0);

         // Force small non-zero values to render the chart
         if (isAllZero) {
             chartData = [0.0001, 0.0001, 0.0001, 0.0001];
         }

         const prod_labels = ['Manual Time', 'Meeting Time', 'Inactive Time', 'Active Time'];

         const doughnutData = {
             labels: prod_labels,
             datasets: [{
                 label: 'Time Breakdown',
                 data: chartData,
                 backgroundColor: isAllZero ? ['#d3d3d3', '#d3d3d3', '#d3d3d3', '#d3d3d3'] : [
                     'rgb(75, 192, 192)',
                     'rgb(255, 99, 132)',
                     'rgb(255, 205, 86)',
                     'rgb(55, 175, 255)'
                 ],
                 hoverOffset: 4
             }]
         };

         const doughnutConfig = {
             type: 'doughnut',
             data: doughnutData,
             options: {
                 responsive: true,
                 plugins: {
                     legend: {
                         display: false
                     },
                     tooltip: {
                         callbacks: {
                             label: function(context) {
                                 const value = context.raw ?? 0;
                                 return `${context.label}: ${isAllZero ? 0 : value}%`;
                             }
                         }
                     }
                 }
             }
         };

         const doughnutCtx = document.getElementById('ProductivityReportChart').getContext('2d');
         const doughnutChart = new Chart(doughnutCtx, doughnutConfig);

         // Custom Legend
         function renderCustomLegend(chart, legendId) {
             const container = document.getElementById(legendId);
             container.innerHTML = '';

             const labels = chart.data.labels;
             const data = chart.data.datasets[0].data;
             const colors = chart.data.datasets[0].backgroundColor;

             labels.forEach((label, i) => {
                 const item = document.createElement('div');
                 item.classList.add('custom-legend-item');

                 const box = document.createElement('div');
                 box.classList.add('custom-legend-box');
                 box.style.backgroundColor = colors[i];

                 const text = document.createElement('span');
                 const value = isAllZero ? 0 : data[i];
                 text.innerText = `${label}: ${value}%`;

                 item.appendChild(box);
                 item.appendChild(text);
                 container.appendChild(item);
             });
         }

         renderCustomLegend(doughnutChart, 'doughnutLegend');

     }
 </script>