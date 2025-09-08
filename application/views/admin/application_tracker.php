    <div class="content-wrapper application_logs">
        <section class="content">
            <h3>Application Usage Tracker</h3>
                <form id="dateRangeForm" class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('admin/application_tracker') ?>" method="post">
                <div class="row mb-5 reprt-box">
                    <div class="form-group col-lg-4 my-3"><label class="control-label">Employee </label>
                        <select name="employee_id" id="employee_search" class="form-control single_select">
                            <?php if (!empty($employees)): ?>
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($employees as $emp): ?>
                                    <option value="<?= $emp['id'] ?>" <?= ($emp['id'] == $employee_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">-- No employees found --</option>
                            <?php endif; ?>
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

            <?php if (!empty($response['data']['applications'])): ?>
                <div class="box mb-4">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-6">
                                <h4>Usage Stats</h4>
                                <p class="text-muted"><i class="bi bi-clock mr-5"></i>Total time tracked - <?= $response['data']['total_usage_time'] ?></p>
                            </div>
                            <div class="col-6 text-end d-flex justify-content-end align-items-center">
                                <!-- <span class="total-time-badge" data-toggle="tooltip" data-placement="top"
                                    title="Total Time: <?= $response['data']['total_usage_time']  ?>" style="cursor:pointer; height: max-content;">
                                    <?= $response['data']['total_usage_time'] ?>
                                </span> -->
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php
                        $applications = $response['data']['applications'];
                        $lastKey = array_key_last($applications);

                        foreach ($applications as $appName => $appData):
                            $isLast = ($appName === $lastKey);
                        ?>
                            <div class="py-4" style="<?= $isLast ? '' : 'border-bottom: 1px solid #dee2e6;' ?>">
                                <div class="app-details">
                                    <div class="app-name">
                                        <div class="form-group my-0">
                                            <label class="control-label"><?= htmlspecialchars($appName); ?></label>
                                            <small class="text-muted d-block"><?= count($appData['windows']) ?> window(s)</small>
                                        </div>
                                    </div>
                                    <div class="app-time"><?= $appData['formatted_time'] ?></div>
                                </div>
                                <div class="progress-container" data-toggle="tooltip" data-placement="top"
                                    title="Usage Time: <?= $appData['formatted_time'] ?>" style="cursor:pointer;">
                                    <div class="progress-bar" style="width: <?= ($appData['total_seconds'] / $response['data']['raw_total_usage_seconds']) * 100 ?>%;"></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px; justify-content: space-between;">
                                    <div class="text-muted">
                                        <?= number_format(($appData['total_seconds'] / $response['data']['raw_total_usage_seconds']) * 100, 1); ?>% of total
                                    </div>
                                    <button type="button" class="btn btn-default show-windows-modal"
                                        data-app="<?= htmlspecialchars($appName) ?>"
                                        data-windows='<?= json_encode($appData['windows']) ?>'>
                                        <i class="bi bi-window-stack mr-5"></i>
                                        Windows (<?= count($appData['windows']) ?>)
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="box">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            <strong class="text-right">No application usage data found.</strong>
                        </h3>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Window Details Modal -->
    <div class="modal fade" id="windowDetailsModal" tabindex="-1" role="dialog" aria-labelledby="windowDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-zoom" role="document">
            <div class="modal-content" style="margin-top: 10% !important">
                <div class="modal-header">
                    <h5 class="modal-title" id="windowDetailsModalLabel">Window Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0 ">
                        <table class="table table-hover cushover mt-0 " id="dg_table">
                            <thead>
                                <tr>
                                    <th>Tab/Screen</th>
                                    <th> Usage </th>
                                </tr>
                            </thead>
                            <tbody id="windowDetailsBody">
                                <!-- Window details will be inserted here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Handle employee dropdown change
        $(document).on('change', '#employee_search', function(e) {
            $('.user_filter_form').submit();
        });
           $(document).on('change', '#employee_search, #datePicker, #sortOrder', function(e) {
             $('.user_filter_form').submit();
         });

            // Show window details in modal
            $(document).on('click', '.show-windows-modal', function() {
                const appName = $(this).data('app');
                const windows = $(this).data('windows');

                // Merge windows with the same title
                const merged = {};
                windows.forEach(function(window) {
                    if (!merged[window.window_title]) {
                        merged[window.window_title] = 0;
                    }
                    merged[window.window_title] += window.total_seconds; // Use raw seconds for sum
                });

                // Clear previous content
                $('#windowDetailsBody').empty();

                // Add merged window details to table
                Object.entries(merged).forEach(function([title, totalSeconds]) {
                    const hours = Math.floor(totalSeconds / 3600);
                    const minutes = Math.floor((totalSeconds % 3600) / 60);
                    const seconds = totalSeconds % 60;

                    // Format as h m s
                    let timeStr = '';
                    if (hours > 0) timeStr += hours + 'h ';
                    if (minutes > 0) timeStr += minutes + 'min ';
                    if (seconds > 0) timeStr += seconds + 's';

                    // Shorten title to 20 characters + ellipsis if too long
                    let displayTitle = title;
                    if (title.length > 85) {
                        displayTitle = title.substring(0, 85) + '...';
                    }

                    $('#windowDetailsBody').append(`
            <tr>
                <td title="${title}">${displayTitle}</td>
                <td>${timeStr.trim()}</td>
            </tr>
        `);
                });

                // Initialize Bootstrap tooltips
                $('#windowDetailsBody [title]').tooltip();

                // Show the modal
                $('#windowDetailsModal').modal('show');
            });


        });
    </script>