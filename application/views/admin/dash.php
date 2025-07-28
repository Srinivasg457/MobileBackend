<!-- <div class="content-wrapper">

  <section class="content"> 

    <div class="row">

      <div class="col-md-6">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><strong class="text-right"><?php echo trans('last-12-months-income') ?> </strong></h3>
          </div>
          <div class="box-body">
            <div id="incomeChart"></div>
          </div>
        </div>

        <div class="box mt-10">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo trans('net-income') ?></h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th><?php echo trans('fiscal-year') ?> <i class="fa fa-info-circle" data-toggle="tooltip" data-title="Fiscal year start is January 01"></i></th>
                    <?php foreach ($net_income as $netincome): ?>
                      <th><?php echo show_year($netincome->payment_date) ?></th>
                    <?php endforeach ?>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo trans('income') ?></td>
                    <?php foreach ($net_income as $netincome): ?>
                      <td><?php echo price_formatted($netincome->total, $this->business->id) ?></td>
                    <?php endforeach ?>
                  </tr>
                  <tr>
                    <td><?php echo trans('expense') ?></td> 
                    <?php foreach ($net_income as $netincome): ?>
                      <td><?php echo price_formatted(get_expense_by_year(show_year($netincome->payment_date)), $this->business->id) ?></td>
                    <?php endforeach ?>
                  </tr>
                  <tr>
                    <td><?php echo trans('net-income') ?> </td>
                    <?php foreach ($net_income as $netincome): ?>
                      <td><strong><?php echo price_formatted($netincome->total - get_expense_by_year(show_year($netincome->payment_date)), $this->business->id) ?></strong></td>
                    <?php endforeach ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

      <div class="col-md-6">


          <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('upcomming-recurring-payments') ?></h3>
              </div>

              <div class="box-body">
                <?php if (empty($upcoming_payments)): ?>
                  <p><?php echo trans('no-data-founds') ?></p>
                <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                        <tr>
                         <th><?php echo trans('customer') ?></th>
                         <th><?php echo trans('total') ?></th>
                         <th><?php echo trans('amount-due') ?></th>
                         <th><?php echo trans('next-payment') ?></th>
                         <th><?php echo trans('status') ?></th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($upcoming_payments as $payment): ?>
                          
                            <tr>
                              <td><?php echo html_escape($payment->customer_name) ?></td>
                              <td><?php echo price_formatted($payment->grand_total, $this->business->id) ?></td>
                              <td><?php echo price_formatted($payment->grand_total - get_total_invoice_payments($payment->id, $payment->parent_id), $this->business->id) ?></td>
                              <td><?php echo my_date_show($payment->next_payment) ?></td>
                              <td>
                                <span class="custom-label-lg label-light-info"><?php echo trans('upcomming') ?></span>
                              </td>
                            </tr>
                          <?php endforeach ?>
                      </tbody>
                    </table>
                </div>
                <?php endif ?>
              </div>

              <?php if (!empty($upcoming_payments) && check_permissions(auth('role'), 'invoices') == TRUE): ?>
                <div class="text-center bt-1 border-light p-10">
                  <a class="d-block font-size-14" href="<?php echo base_url('admin/invoice/type/3?recurring=1') ?>"><?php echo trans('all-invoices') ?>  <i class="fa fa-long-arrow-right"></i></a>
                </div>
              <?php endif ?>

          </div>


          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo trans('overdue-invoices') ?></h3>
            </div>

            <div class="box-body">
              <?php if (empty($overdues)): ?>
                <p><?php echo trans('no-data-founds') ?></p>
              <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th><?php echo trans('customer') ?></th>
                      <th><?php echo trans('amount') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php foreach ($overdues as $due): ?>
                          <tr>
                            <td><?php echo html_escape($due->customer_name) ?></td>
                            <td><?php echo price_formatted($due->grand_total, $this->business->id) ?> </td>
                          </tr>
                        <?php endforeach ?>
                  </tbody>
                  </table>
                </div>
                <?php endif ?>
              </div>

              <?php if (!empty($overdues) && check_permissions(auth('role'), 'invoices') == TRUE): ?>
                <div class="text-center bt-1 border-light p-10">
                  <a class="d-block font-size-14" href="<?php echo base_url('admin/invoice/type/1') ?>"><?php echo trans('see-all-overdue-invoices') ?>  <i class="fa fa-long-arrow-right"></i></a>
                </div>
              <?php endif ?>
          </div>



          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo trans('pending-invoices') ?></h3>
            </div>

            <div class="box-body">
              <?php if (empty($pending)): ?>
                  <p><?php echo trans('no-data-founds') ?></p>
              <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th><?php echo trans('customer') ?></th>
                      <th><?php echo trans('amount') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php foreach ($pending as $pending): ?>
                          <tr>
                            <td><?php echo html_escape($pending->customer_name) ?></td>
                            <td><?php echo price_formatted($pending->grand_total, $this->business->id) ?></td>
                          </tr>
                        <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
                <?php endif ?>
              </div>

              <?php if (!empty($overdues)): ?>
                <div class="text-center bt-1 border-light p-10">
                  <a class="d-block font-size-14" href="<?php echo base_url('admin/invoice/type/1') ?>"><?php echo trans('all-invoices') ?>  <i class="fa fa-long-arrow-right"></i></a>
                </div>
              <?php endif ?>
          </div>

          <div class="box hide">
              <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('recently-paid-invoices') ?></h3>
              </div>

              <div class="box-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                       <th><?php echo trans('customer') ?></th>
                       <th><?php echo trans('amount') ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($paids)): ?>
                        <p><?php echo trans('no-data-founds') ?></p>
                        <?php else: ?>
                          <?php foreach ($paids as $paid): ?>
                            <tr>
                              <td><?php echo html_escape($paid->customer_name) ?></td>
                              <td><?php echo price_formatted($paid->grand_total, $this->business->id) ?></td>
                            </tr>
                          <?php endforeach ?>
                        <?php endif ?>
                      </tbody>
                    </table>
                </div>
              </div>

                <?php if (!empty($paids)): ?>
                  <div class="text-center bt-1 border-light p-10">
                    <a class="d-block font-size-14" href="<?php echo base_url('admin/invoice/type/3') ?>"><?php echo trans('see-all-paid-invoices') ?>  <i class="fa fa-long-arrow-right"></i></a>
                  </div>
                <?php endif ?>

          </div>


        </div>

        </div>

      </section>

    </div> -->


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
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin: 37px 0px;
  }

  .custom-legend-item {
    display: flex;
    align-items: center;
  }

  .custom-legend-box {
    width: 12px;
    height: 12px;
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
    <form id="dateRangeForm" class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('admin/dashboard/business') ?>" method="post">
      <div class="row my-0">
        <div class="col-lg-6 form-group my-0 mt-20">
          <h3 class="mb-0">Dashboard</h3>
        </div>
        <div class="col-lg-6 form-group my-0 mt-20">
          <div class="row" id="predefined_filter_row" style="<?= $is_manual ? 'display: none;' : '' ?>">

            <div class="col-lg-6">
              <select name="employee_id" id="employee_search" class="form-control single_select">
                <option value="">-- Select Employee --</option>
                <?php foreach ($employees as $emp): ?>
                  <option value="<?= $emp['id'] ?>" <?= ($emp['id'] == $employee_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['email']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-lg-6">
              <input type="hidden" id="first_record_date" value="<?php echo $first_record_date; ?>">
              <!-- <label class="control-label" for="period_search">Time Period</label> -->
              <div class="input-group">
                <select name="Time_period" id="period_search" class="form-control single_select">
                  <option value="" <?= ($time_period == " ") ? 'selected' : ''; ?>>Select</option>
                  <option value="current_week" <?= ($time_period == 'current_week') ? 'selected' : ''; ?>>This Week</option>
                  <option value="last_week" <?= ($time_period == 'last_week') ? 'selected' : ''; ?>>Last Week</option>
                  <option value="two_week" <?= ($time_period == 'two_week') ? 'selected' : ''; ?>>Last Two Week</option>
                  <option value="this_month" <?= ($time_period == 'this_month') ? 'selected' : ''; ?>>This Month</option>
                  <option value="last_month" <?= ($time_period == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
                  <option value="last_6_months" <?= ($time_period == 'last_6_months') ? 'selected' : ''; ?>>Last 6 Months</option>
                  <option value="this_year" <?= ($time_period == 'this_year') ? 'selected' : ''; ?>>This Year</option>
                  <option value="manual" <?= ($time_period == 'manual') ? 'selected' : ''; ?>>Pick Dates</option>
                  </option>
                </select>


                <!-- <span id="searchManually" class="input-group-addon btn btn-secondary align-content-center mx-5"><i class="fa fa-search"></i> Pick Dates</span> -->

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
              <span class="text-danger small pl-5" id="error"></span>
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
                <h2 class="m-b-0" data-toggle="tooltip" data-placement="bottom" title="<?php echo $employee_activity['total_active'] ?>"><?php echo $employee_activity['total_active'] ?></h2>
              </div>
            </div>
          </div>
        </div>
        <div class="card counts">
          <div class="card-body">
            <div class="d-flex flex-row">
              <div class="ml-20 align-self-center">
                <h4 class="text-muteds m-b-0"><?php echo "Inactive  Hours" ?></h4>
                <h2 class="m-b-0" data-toggle="tooltip" data-placement="bottom" title="<?php echo $employee_activity['total_idle'] ?>"><?php echo $employee_activity['total_idle'] ?></h2>
              </div>
            </div>
          </div>
        </div>
        <div class="card counts">
          <div class="card-body">
            <div class="d-flex flex-row">
              <div class="ml-20 align-self-center">
                <h4 class="text-muteds m-b-0"><?php echo "Total Hours" ?></h4>
                <h2 class="m-b-0" data-toggle="tooltip" data-placement="bottom" title="<?php echo $employee_activity['shift_time'] ?>"><?php echo $employee_activity['shift_time'] ?></h2>
              </div>
            </div>
          </div>
        </div>
        <div class="card counts">
          <div class="card-body">
            <div class="ml-20 align-self-center">
              <h4 class="text-muteds m-b-0"><?php echo "Key Stroke" ?></h4>
              <?php
              $keystroke_percentage = $output['keystroke_percentage'];

              // Determine color based on percentage
              if ($keystroke_percentage >= 70) {
                $stroke_color = "green";
              } elseif ($keystroke_percentage >= 50) {
                $stroke_color = "rgb(255, 205, 86)";
              } else {
                $stroke_color = "red";
              }
              ?>
              <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                <h2 class="m-b-0" data-toggle="tooltip" data-placement="bottom"><?php echo $keystroke_percentage . "%"; ?></h2>

                <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                  <svg viewBox="0 0 36 36" width="40" height="40">
                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e6e6e6" stroke-width="4" />
                    <circle
                      cx="18" cy="18" r="15.9155" fill="none"
                      stroke="<?php echo $stroke_color; ?>" stroke-width="4"
                      stroke-dasharray="<?php echo $keystroke_percentage . ' ' . (100 - $keystroke_percentage); ?>"
                      stroke-dashoffset="25"
                      transform="rotate(0 18 18)" />
                  </svg>
                  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 10px; font-weight: bold; cursor: pointer;"
                    data-toggle="tooltip" data-placement="bottom" title="<?php echo $keystroke_percentage; ?>">
                    <?php echo $keystroke_percentage . "%"; ?>
                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="card counts">
          <div class="card-body">
            <div class="ml-20 align-self-center">
              <h4 class="text-muteds m-b-0"><?php echo "Mouse Activity" ?></h4>
              <?php
              $mouse_movement = $output['mouse_activity_percentage'];

              // Determine color based on percentage
              if ($mouse_movement >= 60) {
                $stroke_color = "green";
              } elseif ($mouse_movement >= 30) {
                $stroke_color = "rgb(255, 205, 86)";
              } else {
                $stroke_color = "red";
              }
              ?>
              <div style="margin-top:10px; display: flex; align-items: center; justify-content: space-between;">
                <h2 class="m-b-0" data-toggle="tooltip" data-placement="bottom"><?php echo $mouse_movement . "%"; ?></h2>

                <div class="donut-chart" style="position: relative; width: 40px; height: 40px;">
                  <svg viewBox="0 0 36 36" width="40" height="40">
                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e6e6e6" stroke-width="4" />
                    <circle
                      cx="18" cy="18" r="15.9155" fill="none"
                      stroke="<?php echo $stroke_color; ?>" stroke-width="4"
                      stroke-dasharray="<?php echo $mouse_movement . ' ' . (100 - $mouse_movement); ?>"
                      stroke-dashoffset="25"
                      transform="rotate(0 18 18)" />
                  </svg>
                  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 10px; font-weight: bold; cursor: pointer;"
                    data-toggle="tooltip" data-placement="bottom" title="<?php echo $mouse_movement; ?>">
                    <?php echo $mouse_movement . "%"; ?>
                  </div>
                </div>
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
            <div id="doughnutLegend" class="custom-legend"></div>

            <div style="height:260px;text-align: center;justify-content: center;display: flex;">
              <canvas id="ProductivityReportChart" style="max-height: 250px; width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo  $date_range ?></h3>
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


              <?= $weekly_reports['total_active']; ?>

              <div class="insight-text">🔔 You were inactive for <span style="color: red;"><?= $inactive_data['total_idle_time']; ?> </span> this week. Try taking short breaks to stay fresh!</div>

              <div class="insight-text">🌞 Your most productive time is morning. Schedule important work then!</div>

              <div class="insight-text">🏆




                <?php if ($response_data['total_active_hours'] >= 47.5): ?>

                  🏆 Last week, you exceeded your target! <?= $response_data['total_active_hours']; ?> hours. Keep up the momentum.

                <?php else: ?>

                  Last week you have only managed <span style="color: red;"><?= $response_data['total_active_hours']; ?> </span> hours. So, Focus on Your work <?php endif; ?>

              </div>
              <div class="insight-text">⏱️ You were active for <span style="color: green;"><?= $avarage_data['average_active_time']; ?> </span>day this week, with <span style="color: green;"><?= $avarage_data['average_idle_time']; ?> </span>day of inactivity.</div>

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

    $('input[name="fromDate"], input[name="toDate"]').on('focus click', function() {
      $('#error').text(''); // Clears the error message
      //  $('#error').fadeOut();

    });

    $('#searchManually').on('click', function() {
      $('#predefined_filter_row').hide();
      $('#manual_filter_row').css('display', 'flex');

      // Set the first option (index 0) as selected
      $('#period_search').val('');
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
      const error_msg = $('#error');

      // Simple validation
      if (!fromDate || !toDate) {
        e.preventDefault();
        error_msg.text("Both From and To dates are required.");
        return;
      }

      const from = fromDate;
      const to = toDate;
      const first = firstRecordDate;
      console.log(first);
      console.log(from);
      if (from > to) {
        e.preventDefault();
        //  showToast('"From Date" should not be after "To Date".', 'error');
        error_msg.text("Invalid range: From Date is after To Date.");
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
    $(document).on('change', "#employee_search", function(e) {
      // Hide manual filter if any other option is selected
      $('#manual_filter_row').hide();
      $('#predefined_filter_row').show();

      // Clear manual dates
      $('input[name="fromDate"]').val('');
      $('input[name="toDate"]').val('');

      $('.user_filter_form').submit();
    });

    $(document).on('change', "#period_search", function(e) {
      const selectedValue = $(this).val();

      if (selectedValue === 'manual') {
        // Set the first option (index 0) as selected
        $('#period_search').prop('selectedIndex', 0);
        // Show manual date filter row
        $('#manual_filter_row').css('display', 'flex');
        $('#predefined_filter_row').hide();
        return; // Do not submit form
      }

      // Hide manual filter if any other option is selected
      $('#manual_filter_row').hide();
      $('#predefined_filter_row').show();

      // Clear manual dates
      $('input[name="fromDate"]').val('');
      $('input[name="toDate"]').val('');

      $('.user_filter_form').submit();
    });

    render_employee_productivity_chart();
    render_custom_date_report();
  });

  function render_custom_date_report() {
    const customData = <?php echo json_encode($custom_date_chart_data); ?>; // Example: ["Jul 2025" => "12h 30m", ...]
    console.log(customData);
    const ctx = document.getElementById('weeklyReportChart').getContext('2d');

    const labels = [];
    const barData = [];
    const lineData = [];
    let maxBarValue = 0;
    let hasNonZeroData = false;

    Object.entries(customData).forEach(([label, time]) => {
      const match = time.match(/(\d+)h\s*(\d+)m/);
      const hours = parseInt(match?.[1] || 0);
      const minutes = parseInt(match?.[2] || 0);
      const totalHours = hours + (minutes / 60);

      labels.push(label);
      barData.push(parseFloat(totalHours.toFixed(2)));
      lineData.push((totalHours * 1.05).toFixed(2));

      if (totalHours > maxBarValue) {
        maxBarValue = totalHours;
      }

      if (totalHours > 0) {
        hasNonZeroData = true;
      }
    });

    // ✅ If all data is zero (e.g., all "0h 0m"), make sure line chart still shows
    if (!hasNonZeroData) {
      // keep labels/barData/lineData as 0, just ensure chart renders with default maxY
      maxBarValue = 2;
    }



    const suggestedMaxY = Math.ceil((maxBarValue * 1.25) / 10) * 10;

    if (window.weeklyChartInstance) {
      window.weeklyChartInstance.destroy();
    }

    window.weeklyChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Active Time (hr)',
          data: barData,
          backgroundColor: 'rgba(54, 162, 235, 0.8)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          barPercentage: 0.4,
          categoryPercentage: 0.7,
          order: 2
        }, {
          label: 'Trend (hr)',
          data: lineData,
          type: 'line',
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: false,
          tension: 0.3,
          order: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
          padding: {
            top: 10,
            bottom: 10,
            left: 10,
            right: 10
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
                size: 12,
                weight: 'bold'
              }
            },
            ticks: {
              stepSize: 2,
              font: {
                size: 12
              }
            }
          },
          x: {
            title: {
              display: true,
              text: 'Date Group',
              font: {
                size: window.innerWidth < 600 ? 10 : 12,
                weight: 'bold'
              }
            },
            ticks: {
              font: {
                size: 12
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



  // function render_fourWeek_report() {
  //   const weeklyReportsData = <?php echo json_encode($weekly_reports); ?>;
  //   console.log(weeklyReportsData);
  //   const labels = [];
  //   const barData = [];
  //   const lineData = [];
  //   let maxBarValue = 0;

  //   let filteredData = [];
  //   let showDailyBreakdown = false;
  //   let weekToShow = null;

  //   const selectedPeriod = $('#period_search').val();
  //   const weeklyCtx = document.getElementById('weeklyReportChart').getContext('2d');
  //   const isNoData = weeklyReportsData.every(item => {
  //     const breakdown = item.daily_breakdown;
  //     return Object.values(breakdown).every(val => val.trim() === '0h 0m');
  //   });
  //   if (isNoData || weeklyReportsData.length === 0) {
  //     labels.length = 0;
  //     barData.length = 0;
  //     lineData.length = 0;

  //     if (selectedPeriod === 'two_week' || showDailyBreakdown) {
  //       const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
  //       daysOfWeek.forEach(day => {
  //         labels.push(day);
  //         barData.push(0);
  //       });
  //     } else if (selectedPeriod === 'this_year') {
  //       const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
  //         "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
  //       ];
  //       months.forEach(month => {
  //         labels.push(month);
  //         barData.push(0);
  //       });
  //     } else if (selectedPeriod === 'last_6_months') {
  //       const now = new Date();
  //       for (let i = 5; i >= 0; i--) {
  //         const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
  //         const label = date.toLocaleString('default', {
  //           month: 'short',
  //           year: 'numeric'
  //         });
  //         labels.push(label);
  //         barData.push(0);
  //       }
  //     } else {
  //       for (let i = 1; i <= 4; i++) {
  //         labels.push(`Week ${i}`);
  //         barData.push(0);
  //       }
  //     }

  //     // ✅ Key Fix Here: Ensure lineData matches barData length
  //     lineData.length = 0;
  //     for (let i = 0; i < barData.length; i++) {
  //       lineData.push(0.1); // soft baseline
  //     }

  //     maxBarValue = 2;
  //   } else {
  //     switch (selectedPeriod) {
  //       case 'current_week':
  //         filteredData = weeklyReportsData.filter(item => item.week_name === 'Current Week');
  //         showDailyBreakdown = true;
  //         weekToShow = 'Current Week';
  //         break;
  //       case 'last_week':
  //         filteredData = weeklyReportsData.filter(item => item.week_name === 'Week 1');
  //         showDailyBreakdown = true;
  //         weekToShow = 'Week 1';
  //         break;
  //       case 'two_week':
  //         const weeksToCompare = ['Week 2', 'Week 1']; // Show Week 2 first
  //         showDailyBreakdown = false;

  //         filteredData = weeklyReportsData.filter(item =>
  //           weeksToCompare.includes(item.week_name)
  //         );

  //         const weekMap = {};
  //         filteredData.forEach(item => {
  //           const startDateStr = item.date_range?.split(' to ')[0];
  //           item.startDate = new Date(startDateStr);
  //           weekMap[item.week_name] = item;
  //         });

  //         const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

  //         weeksToCompare.forEach(week => {
  //           const weekData = weekMap[week];
  //           if (!weekData) return;

  //           daysOfWeek.forEach((day, index) => {
  //             const currentDate = new Date(weekData.startDate);
  //             currentDate.setDate(currentDate.getDate() + index); // Calculate day offset

  //             const formattedDate = currentDate.toLocaleDateString('en-US', {
  //               month: 'short',
  //               day: 'numeric'
  //             }); // Example: Jul 2

  //             const label = `${day} (${formattedDate})`;

  //             const dayTime = weekData.daily_breakdown?.[day] || "0h 0m";
  //             const activeParts = dayTime.match(/(\d+)h\s+(\d+)m/);
  //             const hours = parseInt(activeParts?.[1] || 0);
  //             const minutes = parseInt(activeParts?.[2] || 0);
  //             const totalHours = hours + (minutes / 60);

  //             labels.push(label);
  //             barData.push(parseFloat(totalHours.toFixed(2)));
  //             lineData.push((totalHours * 1.05).toFixed(2));
  //             if (totalHours > maxBarValue) {
  //               maxBarValue = totalHours;
  //             }
  //           });
  //         });
  //         break;
  //       case 'this_month': {
  //         const now = new Date();
  //         const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
  //         const endOfMonth = now;

  //         labels.length = 0;
  //         barData.length = 0;
  //         lineData.length = 0;

  //         const daysInWeek = 7;
  //         const weekMap = {};

  //         // Iterate through all weeks in the report
  //         weeklyReportsData.forEach(weekData => {
  //           const startDateStr = weekData.date_range?.split(' to ')[0];
  //           const weekStartDate = new Date(startDateStr);

  //           let weeklyTotal = 0;
  //           let weekStartLabel = null;
  //           let weekEndLabel = null;

  //           for (let i = 0; i < 7; i++) {
  //             const currentDate = new Date(weekStartDate);
  //             currentDate.setDate(currentDate.getDate() + i);

  //             if (currentDate >= startOfMonth && currentDate <= endOfMonth) {
  //               const dayOfWeek = currentDate.toLocaleString('en-US', {
  //                 weekday: 'long'
  //               });
  //               const dayTime = weekData.daily_breakdown?.[dayOfWeek] || "0h 0m";
  //               const activeParts = dayTime.match(/(\d+)h\s+(\d+)m/);
  //               const hours = parseInt(activeParts?.[1] || 0);
  //               const minutes = parseInt(activeParts?.[2] || 0);
  //               const totalHours = hours + (minutes / 60);

  //               weeklyTotal += totalHours;

  //               // Build week label
  //               const formattedDate = currentDate.toLocaleDateString('en-US', {
  //                 month: 'short',
  //                 day: 'numeric'
  //               });

  //               if (!weekStartLabel) weekStartLabel = formattedDate;
  //               weekEndLabel = formattedDate;
  //             }
  //           }

  //           if (weeklyTotal > 0) {
  //             const label = `${weekData.week_name} (${weekStartLabel}–${weekEndLabel})`;
  //             labels.push(label);
  //             const roundedTotal = parseFloat(weeklyTotal.toFixed(2));
  //             barData.push(roundedTotal);
  //             lineData.push((roundedTotal * 1.05).toFixed(2));
  //             if (roundedTotal > maxBarValue) {
  //               maxBarValue = roundedTotal;
  //             }
  //           }
  //         });

  //         break;
  //       }
  //       case 'last_month': {
  //         const now = new Date();
  //         const startOfLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
  //         const endOfLastMonth = new Date(now.getFullYear(), now.getMonth(), 0); // last day of previous month

  //         labels.length = 0;
  //         barData.length = 0;
  //         lineData.length = 0;

  //         const weekMap = {};

  //         weeklyReportsData.forEach(weekData => {
  //           const startDateStr = weekData.date_range?.split(' to ')[0];
  //           const weekStartDate = new Date(startDateStr);

  //           let weeklyTotal = 0;
  //           let weekStartLabel = null;
  //           let weekEndLabel = null;

  //           for (let i = 0; i < 7; i++) {
  //             const currentDate = new Date(weekStartDate);
  //             currentDate.setDate(currentDate.getDate() + i);

  //             if (currentDate >= startOfLastMonth && currentDate <= endOfLastMonth) {
  //               const dayOfWeek = currentDate.toLocaleString('en-US', {
  //                 weekday: 'long'
  //               });
  //               const dayTime = weekData.daily_breakdown?.[dayOfWeek] || "0h 0m";
  //               const activeParts = dayTime.match(/(\d+)h\s+(\d+)m/);
  //               const hours = parseInt(activeParts?.[1] || 0);
  //               const minutes = parseInt(activeParts?.[2] || 0);
  //               const totalHours = hours + (minutes / 60);

  //               weeklyTotal += totalHours;

  //               const formattedDate = currentDate.toLocaleDateString('en-US', {
  //                 month: 'short',
  //                 day: 'numeric'
  //               });

  //               if (!weekStartLabel) weekStartLabel = formattedDate;
  //               weekEndLabel = formattedDate;
  //             }
  //           }

  //           if (weeklyTotal > 0) {
  //             const label = `${weekData.week_name} (${weekStartLabel}–${weekEndLabel})`;
  //             labels.push(label);
  //             const roundedTotal = parseFloat(weeklyTotal.toFixed(2));
  //             barData.push(roundedTotal);
  //             lineData.push((roundedTotal * 1.05).toFixed(2));
  //             if (roundedTotal > maxBarValue) {
  //               maxBarValue = roundedTotal;
  //             }
  //           }
  //         });
  //         break;
  //       }
  //       case 'last_6_months': {
  //         const now = new Date();
  //         const startDate = new Date(now.getFullYear(), now.getMonth() - 5, 1); // 5 months ago
  //         const endDate = now;

  //         const monthGroups = {};
  //         const monthKeys = [];

  //         // Initialize past 6 months with 0 hours
  //         for (let i = 5; i >= 0; i--) {
  //           const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
  //           const key = date.toLocaleString('default', {
  //             month: 'short',
  //             year: 'numeric'
  //           }); // e.g. "Mar 2025"
  //           monthGroups[key] = 0;
  //           monthKeys.push(key);
  //         }

  //         // Aggregate data
  //         weeklyReportsData.forEach(item => {
  //           const startDateStr = item.date_range?.split(' to ')[0];
  //           const reportDate = new Date(startDateStr);

  //           if (reportDate >= startDate && reportDate <= endDate) {
  //             const key = reportDate.toLocaleString('default', {
  //               month: 'short',
  //               year: 'numeric'
  //             });
  //             if (!monthGroups[key]) {
  //               monthGroups[key] = 0;
  //             }

  //             const activeParts = item.total_active.match(/(\d+)h\s+(\d+)m/);
  //             const hours = parseInt(activeParts?.[1] || 0);
  //             const minutes = parseInt(activeParts?.[2] || 0);
  //             const totalHours = hours + (minutes / 60);
  //             monthGroups[key] += totalHours;
  //           }
  //         });

  //         // Render labels in chronological order
  //         monthKeys.forEach(monthKey => {
  //           const totalHours = monthGroups[monthKey];
  //           labels.push(monthKey);
  //           barData.push(parseFloat(totalHours.toFixed(2)));
  //           lineData.push((totalHours * 1.05).toFixed(2));
  //           if (totalHours > maxBarValue) {
  //             maxBarValue = totalHours;
  //           }
  //         });

  //         break;
  //       }


  //       case 'this_year': {
  //         const now = new Date();
  //         const currentYear = now.getFullYear();

  //         const monthOrder = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  //         const monthGroups = {};

  //         // Step 1: Initialize all months with 0
  //         monthOrder.forEach(month => {
  //           monthGroups[month] = 0;
  //         });

  //         // Step 2: Fill in available data
  //         weeklyReportsData.forEach(item => {
  //           const date = new Date(item.date_range.split(' to ')[0]);
  //           const year = date.getFullYear();

  //           if (year === currentYear) {
  //             const monthKey = date.toLocaleString('default', {
  //               month: 'short'
  //             });
  //             if (!monthGroups[monthKey]) {
  //               monthGroups[monthKey] = 0;
  //             }

  //             const activeParts = item.total_active.match(/(\d+)h\s+(\d+)m/);
  //             const hours = parseInt(activeParts?.[1] || 0);
  //             const minutes = parseInt(activeParts?.[2] || 0);
  //             const totalHours = hours + (minutes / 60);

  //             monthGroups[monthKey] += totalHours;
  //           }
  //         });

  //         // Step 3: Render in correct month order
  //         monthOrder.forEach(month => {
  //           const totalHours = monthGroups[month];
  //           labels.push(month);
  //           barData.push(parseFloat(totalHours.toFixed(2)));
  //           lineData.push((totalHours * 1.05).toFixed(2));
  //           if (totalHours > maxBarValue) {
  //             maxBarValue = totalHours;
  //           }
  //         });

  //         break;
  //       }
  //       case '':
  //       default:
  //         filteredData = weeklyReportsData;
  //     }

  //     if (!showDailyBreakdown && selectedPeriod !== 'this_year' && selectedPeriod !== 'two_week') {
  //       filteredData.sort((a, b) => {
  //         const dateA = new Date(a.date_range.split(' to ')[0]);
  //         const dateB = new Date(b.date_range.split(' to ')[0]);
  //         return dateA - dateB;
  //       });

  //       filteredData.forEach(report => {
  //         const activeParts = report.total_active.match(/(\d+)h\s+(\d+)m/);
  //         const hours = parseInt(activeParts[1] || 0);
  //         const minutes = parseInt(activeParts[2] || 0);
  //         const totalHours = hours + (minutes / 60);

  //         labels.push(`${report.week_name} (Mon to Sun)`);
  //         barData.push(parseFloat(totalHours.toFixed(2)));
  //         lineData.push((totalHours * 1.05).toFixed(2));
  //         if (totalHours > maxBarValue) {
  //           maxBarValue = totalHours;
  //         }
  //       });
  //     }

  //     if (showDailyBreakdown && weekToShow) {
  //       const weekData = weeklyReportsData.find(item => item.week_name === weekToShow);
  //       if (weekData) {
  //         const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

  //         daysOfWeek.forEach(day => {
  //           const dayTime = weekData.daily_breakdown[day] || "0h 0m";
  //           const activeParts = dayTime.match(/(\d+)h\s+(\d+)m/);
  //           const hours = parseInt(activeParts[1] || 0);
  //           const minutes = parseInt(activeParts[2] || 0);
  //           const totalHours = hours + (minutes / 60);

  //           labels.push(day);
  //           barData.push(parseFloat(totalHours.toFixed(2)));
  //           lineData.push((totalHours * 1.05).toFixed(2));
  //           if (totalHours > maxBarValue) {
  //             maxBarValue = totalHours;
  //           }
  //         });
  //       }
  //     }
  //   }
  //   const suggestedMaxY = Math.ceil((maxBarValue * 1.25) / 10) * 10;




  //   if (window.weeklyChartInstance) {
  //     window.weeklyChartInstance.destroy();
  //   }

  //   window.weeklyChartInstance = new Chart(weeklyCtx, {
  //     type: 'bar',
  //     data: {
  //       labels: labels,
  //       datasets: [{
  //         label: (showDailyBreakdown || selectedPeriod === 'two_week') ? 'Daily Active Time (hr)' : selectedPeriod === 'this_year' ? 'Monthly Active Time (hr)' : 'Total Active Time (hr)',
  //         data: barData,
  //         backgroundColor: 'rgba(54, 162, 235, 0.8)',
  //         borderColor: 'rgba(54, 162, 235, 1)',
  //         borderWidth: 1,
  //         order: 2,
  //         barPercentage: 0.4,
  //         categoryPercentage: 0.7
  //       }, {
  //         label: 'Trend (hr)',
  //         data: lineData,
  //         type: 'line',
  //         borderColor: 'rgb(75, 192, 192)',
  //         backgroundColor: 'rgba(75, 192, 192, 0.2)',
  //         fill: false,
  //         tension: 0.3,
  //         order: 1
  //       }]
  //     },
  //     options: {
  //       responsive: true,
  //       maintainAspectRatio: false,
  //       layout: {
  //         padding: {
  //           left: 10,
  //           right: 10,
  //           top: 10,
  //           bottom: 10
  //         }
  //       },
  //       scales: {
  //         y: {
  //           beginAtZero: true,
  //           max: suggestedMaxY,
  //           title: {
  //             display: true,
  //             text: 'Hours (hr)',
  //             font: {
  //               size: window.innerWidth < 600 ? 10 : 12,
  //               weight: 'bold'
  //             }
  //           },
  //           ticks: {
  //             stepSize: showDailyBreakdown || selectedPeriod === 'two_week' ? 1 : 2,
  //             font: {
  //               size: window.innerWidth < 600 ? 10 : 12
  //             }
  //           }
  //         },
  //         x: {
  //           title: {
  //             display: showDailyBreakdown || selectedPeriod === 'two_week',
  //             text: (showDailyBreakdown || selectedPeriod === 'two_week') ? 'Days of Week' : '',
  //             font: {
  //               size: window.innerWidth < 600 ? 10 : 12,
  //               weight: 'bold'
  //             }
  //           },
  //           ticks: {
  //             font: {
  //               size: window.innerWidth < 600 ? 8 : 12
  //             }
  //           }
  //         }
  //       },
  //       plugins: {
  //         tooltip: {
  //           callbacks: {
  //             label: function(context) {
  //               const value = context.parsed.y;
  //               const hrs = Math.floor(value);
  //               const mins = Math.round((value - hrs) * 60);
  //               return `${context.dataset.label}: ${hrs}h ${mins}m`;
  //             }
  //           }
  //         },
  //         legend: {
  //           position: 'top',
  //           labels: {
  //             font: {
  //               size: 12
  //             },
  //             padding: 15
  //           }
  //         },

  //       }
  //     }
  //   });
  // }


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