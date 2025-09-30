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
    width: 96.5%;
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

  .chart-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
  }

  .pie-chart-wrapper {
    height: 300px;
    margin-bottom: 40px;
  }

  .custom-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    position: relative;
  }

  .legend-item {
    display: flex;
    align-items: center;
    padding: 4px 8px;
    background: #f5f5f5;
    border-radius: 4px;
    font-size: 12px;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .color-box {
    width: 12px;
    height: 12px;
    margin-right: 6px;
    border-radius: 2px;
    flex-shrink: 0;
  }

  .ellipsis-item {
    display: flex;
    align-items: center;
    padding: 4px 8px;
    background: #f5f5f5;
    border-radius: 4px;
    font-size: 12px;
    cursor: default;
    position: relative;
  }

  .ellipsis-item:hover .hidden-items-tooltip {
    display: block;
  }

  .hidden-items-tooltip {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;
    z-index: 100;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    min-width: 150px;
  }

  .hidden-legend-item {
    display: flex;
    align-items: center;
    padding: 4px 0;
    font-size: 12px;
    white-space: nowrap;
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
                <?php
                $disabled_attr = 'disabled title="Upgrade to unlock this option"'; // common attribute

                if (is_pack_trial()) {
                  $extra_option = '<option value="last_1_days" ' . (($time_period == 'last_1_days') ? 'selected' : '') . '>Last 1 Days</option>';
                } elseif (is_plan_basic()) {
                  $extra_option = '<option value="last_7_days" ' . (($time_period == 'last_7_days') ? 'selected' : '') . '>Last 7 Days</option>';
                } else {
                  $extra_option = '';
                }
                ?>

                <select name="Time_period" id="period_search" class="form-control single_select">
                  <option value="" <?= ($time_period == " ") ? 'selected' : ''; ?>>Select</option>

                  <?= $extra_option; ?>

                  <option value="current_week" <?= ($time_period == 'current_week') ? 'selected' : ''; ?> <?= !is_pack_trial() ? '' : $disabled_attr; ?>>This Week</option>
                  <option value="last_week" <?= ($time_period == 'last_week') ? 'selected' : ''; ?> <?= !is_pack_trial() && !is_plan_basic() ? '' : $disabled_attr; ?>>Last Week</option>
                  <option value="two_week" <?= ($time_period == 'two_week') ? 'selected' : ''; ?> <?= !is_pack_trial() && !is_plan_basic() ? '' : $disabled_attr; ?>>Last Two Week</option>
                  <option value="this_month" <?= ($time_period == 'this_month') ? 'selected' : ''; ?> <?= !is_pack_trial() && !is_plan_basic() ? '' : $disabled_attr; ?>>This Month</option>
                  <?php if (is_plan_standard()): ?>
                    <option value="last_1_month" <?= ($time_period == 'last_1_month') ? 'selected' : ''; ?>>Last 1 Month</option>
                  <?php endif; ?>
                  <option value="last_month" <?= ($time_period == 'last_month') ? 'selected' : ''; ?> <?= !is_pack_trial() && !is_plan_basic() && !is_plan_standard() ? '' : $disabled_attr; ?>>Last Month</option>
                  <option value="last_6_months" <?= ($time_period == 'last_6_months') ? 'selected' : ''; ?> <?= !is_pack_trial() && !is_plan_basic() && !is_plan_standard() ? '' : $disabled_attr; ?>>Last 6 Months</option>
                  <option value="this_year" <?= ($time_period == 'this_year') ? 'selected' : ''; ?> <?= !is_pack_trial() && !is_plan_basic() && !is_plan_standard() ? '' : $disabled_attr; ?>>This Year</option>
                  <option value="manual" <?= ($time_period == 'manual') ? 'selected' : ''; ?>>Pick Dates</option>
                </select>



                <!-- <span id="searchManually" class="input-group-addon btn btn-secondary align-content-center mx-5"><i class="fa fa-search"></i> Pick Dates</span> -->

              </div>
            </div>
          </div>

          <div class="row position-relative" id="manual_filter_row" style="<?= $is_manual ? '' : 'display: none;' ?>">
            <?php
            $today = date('Y-m-d');
            $min_date = '';
            $help_text = '';

            if (is_pack_trial()) {
              $min_date = date('Y-m-d', strtotime('-1 day'));
              $help_text = 'Trial plan only allows selecting today or yesterday\'s date.';
            } elseif (is_plan_basic()) {
              $min_date = date('Y-m-d', strtotime('-7 days'));
              $help_text = 'Basic Package allows selecting dates from the last 7 days only.';
            } elseif (is_plan_standard()) {
              $min_date = date('Y-m-d', strtotime('-1 month'));
              $help_text = 'Standard plan allows selecting dates from the last one month only.';
            }

            $from_date = !empty($from_date) ? $from_date : $today;
            $to_date   = !empty($to_date) ? $to_date   : $today;
            ?>

            <div class="col-12 text-right">
              <small class="text-muted"><?= $help_text ?></small>

              <button type="button" id="cancelManualFilter" class="btn btn-secondary btn-sm">
                <i class="fa fa-times"></i>
              </button>
            </div>

            <div class="col-lg-6 form-group">
              <label class="control-label">From</label>
              <input type="date"
                class="inv-dpick form-control"
                name="fromDate"
                value="<?= $from_date ?>"
                <?= !empty($min_date) ? "min='$min_date' max='$today'" : '' ?>
                onfocus="this.showPicker()">
            </div>

            <div class="col-lg-6 form-group">
              <label class="control-label">To</label>
              <div class="d-flex">
                <input type="date"
                  class="inv-dpick form-control"
                  name="toDate"
                  value="<?= $to_date ?>"
                  <?= !empty($min_date) ? "min='$min_date' max='$today'" : '' ?>
                  onfocus="this.showPicker()">
                <span class="input-group-addon btn btn-secondary align-content-center"
                  style="height: 40px;"
                  id="search_date">
                  <i class="fa fa-search"></i>
                </span>
              </div>
              <span class="text-danger small pl-5" id="error"></span>
            </div>

            <?php if (!empty($help_text)): ?>
              <div class="col-12">
              </div>
            <?php endif; ?>

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
                <h4 class="text-muteds m-b-0"><?php echo "Signout Hours" ?></h4>
                <h2 class="m-b-0" data-toggle="tooltip" data-placement="bottom" title="<?php echo $employee_activity['sign_out'] ?>"><?php echo $employee_activity['sign_out'] ?></h2>
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
            <!-- <h4 class="text-muted mb-3">Activity</h4> -->

            <?php
            $keystroke_percentage = $output['keystroke_percentage'];
            $mouse_percentage     = $output['mouse_activity_percentage'];

            function getColor($value)
            {
              if ($value >= 70) return "bg-success";
              if ($value >= 50) return "bg-warning";
              return "bg-danger";
            }
            ?>

            <!-- Keystrokes -->
            <div class="mb-0">
              <div class="d-flex justify-content-between align-items-center mb-1">

                <span>Keystrokes</span>
                <span><?php echo $keystroke_percentage; ?>%</span>
              </div>
              <div class="progress" style="height: 12px;margin-bottom: 2px">
                <div class="progress-bar <?php echo getColor($keystroke_percentage); ?>"
                  role="progressbar"
                  style="width: <?php echo $keystroke_percentage; ?>%;"
                  aria-valuenow="<?php echo $keystroke_percentage; ?>"
                  aria-valuemin="0"
                  aria-valuemax="100">
                </div>
              </div>
            </div>

            <!-- Mouse -->
            <div>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span>Mouse</span>
                <span><?php echo $mouse_percentage; ?>%</span>
              </div>
              <div class="progress" style="height: 12px;margin-bottom: 2px">
                <div class="progress-bar <?php echo getColor($mouse_percentage); ?>"
                  role="progressbar"
                  style="width: <?php echo $mouse_percentage; ?>%;"
                  aria-valuenow="<?php echo $mouse_percentage; ?>"
                  aria-valuemin="0"
                  aria-valuemax="100">
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

          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <!-- <div class="box-body">
                <div id="appUsageLegend" class="custom-legend"></div>
                <canvas id="appUsageChart" style="max-height: 330px; width: 100%;"></canvas>
            </div> -->
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo "Overall Productivity" ?></h3>
          </div>
          <div class="box-body">
            <div id="appUsageLegend" class="custom-legend"></div>
          </div>

          <div class="pie-chart-wrapper">
            <canvas id="appUsageChart"></canvas>
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

    render_app_usage_chart();
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
</script>
<script>
  function render_app_usage_chart() {
    // Get PHP data
    const usageData = <?= $usage_json ?>;

    let labels = [];
    let values = [];

    // Helper: Convert minutes to HH:MM
    function minutesToHHMM(minutes) {
      const hrs = Math.floor(minutes / 60);
      const mins = Math.round(minutes % 60);
      return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
    }

    // Check if data is aggregated (range mode) or daily (day-wise)
    if (Array.isArray(usageData)) {
      // Range mode
      labels = usageData.map(item => item.application_name);
      values = usageData.map(item => parseFloat(item.total_minutes)); // keep as minutes
    } else {
      // Day-wise mode
      const dates = Object.keys(usageData);
      const latestDate = dates[dates.length - 1];
      const latestData = usageData[latestDate] || [];
      labels = latestData.map(item => item.application_name);
      values = latestData.map(item => parseFloat(item.total_minutes)); // keep as minutes
    }

    // Handle no data or all zero data
    const isAllZero = values.length === 0 || values.every(val => val === 0);
    if (isAllZero) {
      labels = ['No Data'];
      values = [0.0001];
      const chartWrapper = document.querySelector('.pie-chart-wrapper');
      if (chartWrapper) {
        chartWrapper.style.height = '335px';
        chartWrapper.style.marginBottom = '40px';
      }
    }

    // Define colors
    const backgroundColors = isAllZero ? ['#d3d3d3'] : [
      "#B9D9EB", "#FBCEB1", "#CCCCFF", "#00B4D8", "#E9967A",
      "#F0E68C", "#9ACD32", "#FF0000", "#260effff", "#006400", "#663399",
      "#000000", "#8B4513", "#2E8B57", "#E63946", "#8B0000", '#6A4C93',
      '#FF9F1C', '#2EC4B6', '#E71D36', '#8AC926', '#1982C4',
      '#FFCA3A', '#6A8D73', '#fffc41ff', '#FF6B6B'
    ].slice(0, labels.length);

    // Chart.js config
    const pieData = {
      labels: labels,
      datasets: [{
        data: values, // still in minutes for pie proportions
        backgroundColor: backgroundColors,
        hoverOffset: 4,
        borderWidth: 1,
        borderColor: '#fff'
      }]
    };

    const pieConfig = {
      type: 'pie',
      data: pieData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return isAllZero ?
                  'No Data Available' :
                  `${context.label}: ${minutesToHHMM(context.raw)}`;
              }
            }
          }
        }
      }
    };

    // Render chart
    const pieCtx = document.getElementById('appUsageChart').getContext('2d');
    new Chart(pieCtx, pieConfig);

    // Custom legend with ellipsis
    function renderCustomLegend() {
      const container = document.getElementById('appUsageLegend');
      container.innerHTML = '';

      const maxVisibleItems = 7;
      const hasOverflow = labels.length > maxVisibleItems;
      const visibleItemsCount = hasOverflow ? maxVisibleItems - 1 : labels.length;

      // Add visible items
      for (let i = 0; i < visibleItemsCount; i++) {
        const item = document.createElement('div');
        item.className = 'legend-item';
        item.title = `${labels[i]} (${minutesToHHMM(values[i])})`;
        item.innerHTML = `
                <span class="color-box" style="background:${backgroundColors[i]}"></span>
                <span class="label-text">${labels[i]}</span>
            `;
        container.appendChild(item);
      }

      // Add ellipsis for hidden items
      if (hasOverflow) {
        const ellipsisItem = document.createElement('div');
        ellipsisItem.className = 'ellipsis-item';
        ellipsisItem.innerHTML = `
                <span class="color-box" style="background:#cccccc"></span>
                <span class="label-text">...</span>
                <div class="hidden-items-tooltip">
                    ${labels.slice(maxVisibleItems - 1).map((label, i) => `
                        <div class="hidden-legend-item">
                            <span class="color-box" style="background:${backgroundColors[i + maxVisibleItems - 1]}"></span>
                            <span>${label} (${minutesToHHMM(values[i + maxVisibleItems - 1])})</span>
                        </div>
                    `).join('')}
                </div>
            `;
        container.appendChild(ellipsisItem);
      }
    }

    renderCustomLegend();
  }
</script>