 <?php
    $dailyBreakdown = $response_data['data']['daily_breakdown'] ?? [];
    // Function: Convert "HH:MM:SS" to seconds
    function timeToSeconds($timeStr)
    {
        list($h, $m, $s) = explode(':', $timeStr);
        return ($h * 3600) + ($m * 60) + $s;
    }
    // Function: Format seconds as "Hh Mm"
    function formatTime($seconds)
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        return ($h > 0 ? "{$h}h " : "") . "{$m}m";
    }
    ?>
 <div class="content-wrapper application_logs">
     <section class="content">
         <h3>Application Tracker</h3>
         <form id="dateRangeForm" class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('admin/application_tracker') ?>" method="post">
             <div class="row mb-5 reprt-box">
                 <div class="form-group col-lg-4 my-3"><label class="control-label">Employee </label>
                     <select name="employee_id" id="employee_search" class="form-control single_select">
                         <option value="">-- Select Employee --</option>
                         <?php foreach ($employees as $emp): ?>
                             <option value="<?= $emp['id'] ?>" <?= ($emp['id'] == $employee_id) ? 'selected' : '' ?>>
                                 <?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['email']) ?>)
                             </option>
                         <?php endforeach; ?>
                     </select>
                 </div>
                 <div class="form-group col-lg-4 my-3">
                     <label class="control-label">Date</label>
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
                        ?>

                     <input type="date" id="datePicker" class="form-control" name="date"
                         value="<?= $date ?>"
                         <?= !empty($min_date) ? "min='$min_date' max='$today'" : '' ?>
                         onfocus="this.showPicker()">

                     <?php if (!empty($help_text)): ?>
                         <small class="text-muted"><?= $help_text ?></small>
                     <?php endif; ?>
                 </div>
                 <div class="form-group col-lg-4 my-3">
                     <label class="control-label">Sort By</label>
                     <select name="order" id="sortOrder" class="form-control single_select">
                         <option value="ascending" <?= ($order == "ascending") ? 'selected' : '' ?>>Ascending</option>
                         <option value="descending" <?= ($order == "descending") ? 'selected' : '' ?>>Descending</option>
                     </select>
                 </div>
             </div>
         </form>


         <?php if (!empty($dailyBreakdown)): ?>
             <?php foreach ($dailyBreakdown as $date => $apps): ?>
                 <?php
                    // Calculate total seconds for the date
                    $totalTimeSeconds = 0;
                    foreach ($apps as $timeStr) {
                        $totalTimeSeconds += timeToSeconds($timeStr);
                    }

                    // Get the last app name to detect the last iteration
                    $lastAppName = array_key_last($apps);
                    ?>
                 <div class="box mb-4">
                     <div class="box-header with-border">
                         <div class="row">
                             <div class="col-6">
                                 <h4>Top Applications</h4>
                                 <p class="text-muted"><?= htmlspecialchars($date); ?> — By total time</p>
                             </div>
                             <div class="col-6 text-end d-flex justify-content-end align-items-center">
                                 <span class="total-time-badge" style="height: max-content;"><?= gmdate("H:i:s", $totalTimeSeconds); ?></span>
                             </div>
                         </div>
                     </div>
                     <div class="box-body">
                         <?php foreach ($apps as $appName => $timeStr): ?>
                             <?php
                                $appSeconds = timeToSeconds($timeStr);
                                $percentage = $totalTimeSeconds > 0 ? ($appSeconds / $totalTimeSeconds) * 100 : 0;
                                $isLast = ($appName === $lastAppName);
                                ?>
                             <div class="py-4" style="<?= !$isLast ? 'border-bottom: 1px solid #dee2e6;' : '' ?>">
                                 <div class="app-details">
                                     <div class="app-name">
                                         <div class="form-group my-0">
                                             <label class="control-label"><?= htmlspecialchars($appName); ?></label>
                                         </div>
                                     </div>
                                     <div class="app-time"><?= formatTime($appSeconds); ?></div>
                                 </div>
                                 <div class="progress-container" data-toggle="tooltip" data-placement="top" title="Usage Time: <?= formatTime($appSeconds); ?>" style="cursor:pointer;">
                                     <div class="progress-bar" style="width: <?= number_format($percentage, 1); ?>%;"></div>
                                 </div>
                                 <div class="text-muted"><?= number_format($percentage, 1); ?>% of total</div>
                             </div>
                         <?php endforeach; ?>
                     </div>
                 </div>
             <?php endforeach; ?>
 </div>
 <?php else: ?>
     <div class="box">
         <div class="box-header with-border text-center">
             <h3 class="box-title">
                 <strong class="text-right"> No application log found.</strong>
             </h3>
         </div>
     </div>
 <?php endif; ?>
 </section>
 </div>
 <script>
     $(document).ready(function() {

         $(document).on('change', '#employee_search, #datePicker, #sortOrder', function(e) {
             $('.user_filter_form').submit();
         });

         // // First, immediately disable the date picker while we check payment status
         //  const datePicker = $('#datePicker');
         //  datePicker.prop('disabled', true);

         //  // // Fetch user's payment details
         //  $.ajax({
         //      url: "<?= base_url('/admin/ScreenshotController/getPaymentDetails'); ?>",
         //      method: 'GET',
         //      success: function(response) {
         //          if (response.billing_type === 'week') {
         //              setupWeeklyBillingRestrictions();
         //          } else {
         //              // Enable normally for other billing types
         //              datePicker.prop('disabled', false);
         //          }
         //      },
         //      error: function() {
         //          console.log('Error fetching payment details');
         //          datePicker.prop('disabled', false);
         //      }
         //  });

         //  function setupWeeklyBillingRestrictions() {
         //      const today = new Date();
         //      const yesterday = new Date(today);
         //      yesterday.setDate(yesterday.getDate() - 1);

         //      // Format dates as YYYY-MM-DD
         //      const todayStr = formatDate(today);
         //      const yesterdayStr = formatDate(yesterday);

         //      // Set the value to today
         //      datePicker.val(todayStr);

         //      // Create a custom dropdown with only two options
         //      datePicker.after(`
         //         <div class="weekly-date-options" style="margin-top: 5px;">
         //             <button class="btn btn-sm btn-outline-primary date-option ${datePicker.val() === todayStr ? 'active' : ''}" 
         //                     data-date="${todayStr}">Today (${formatDisplayDate(today)})</button>
         //             <button class="btn btn-sm btn-outline-primary date-option ${datePicker.val() === yesterdayStr ? 'active' : ''}" 
         //                     data-date="${yesterdayStr}">Yesterday (${formatDisplayDate(yesterday)})</button>
         //         </div>
         //     `);

         //      // Hide the original date picker
         //      datePicker.hide();

         //      // Handle custom button clicks
         //      $('.date-option').click(function() {
         //          const selectedDate = $(this).data('date');
         //          datePicker.val(selectedDate);
         //          $('.date-option').removeClass('active');
         //          $(this).addClass('active');
         //          // Trigger any date change events you might have
         //          datePicker.trigger('change');
         //      });
         //  }

         function formatDate(date) {
             return date.toISOString().split('T')[0];
         }

         function formatDisplayDate(date) {
             const day = date.getDate();
             const month = date.getMonth() + 1;
             return `${day}/${month}/${date.getFullYear()}`;
         }


         // // Get the user's date from the helper function
         // const userDate = "<?= get_user_datetime_only($this->session->userdata('id')) ?>";
         // const today = userDate.split(' ')[0]; // This splits date and time and takes the date part
         // $('#datePicker').val(today);
         // $.ajax({
         //     url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
         //     method: "GET",
         //     dataType: "json",
         //     success: function(response) {
         //         let employeeSelect = $('#employeeSelect');
         //         if (response.status === "success" && response.employees.length > 0) {
         //             response.employees.forEach(emp => {
         //                 employeeSelect.append(`<option value="${emp.id}">${emp.name} (${emp.email})</option>`);
         //             });

         //             const randomIndex = Math.floor(Math.random() * response.employees.length);
         //             const randomEmployee = response.employees[randomIndex];
         //             employeeSelect.val(randomEmployee.id);
         //             $('#employeeName').text(`${randomEmployee.name} (${randomEmployee.email})`); // ✅ Set name on auto-load
         //             let currentDate = $('#datePicker').val();
         //             fetchActivity(randomEmployee.id, currentDate);
         //         } else {
         //             employeeSelect.empty().append(`<option value="">-- No employees found --</option>`);
         //         }
         //     },
         //     error: function() {
         //         $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
         //     }
         // });

         // function triggerFilter() {
         //     const employeeId = $('#employeeSelect').val();
         //     const date = $('#datePicker').val();
         //     if (!employee) {
         //         showToast("Please select an employee.", "error");
         //         $('#employeeSelect').focus(); // Optional: focus on the select box
         //         return;
         //     }
         //     fetchActivity(employeeId, date);
         // }
         // $('#datePicker, #employeeSelect').on('change', triggerFilter);

     });
 </script>