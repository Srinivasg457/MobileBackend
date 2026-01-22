    <div class="content-wrapper application_logs">
        <section class="content">
            <h3>Application Usage Tracker</h3>
            <form id="dateRangeForm" class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('employee/application_tracker') ?>" method="post">
                <div class="row mb-5 reprt-box">
                    <!-- <div class="form-group col-lg-4 my-3">
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
                    </div> -->
                    <div class="form-group col-lg-4 my-3">
                        <label class="control-label">Start Date</label>
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

                        <!-- ✅ Start Date Input -->
                        <input type="date"
                            id="startdatepicker"
                            class="form-control"
                            name="start_date"
                            value="<?= isset($start_date) ? $start_date : $today ?>"
                            <?= !empty($min_date) ? "min='$min_date' max='$today'" : '' ?>
                            onfocus="this.showPicker()">

                        <?php if (!empty($help_text)): ?>
                            <small class="text-muted"><?= $help_text ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- ✅ End Date Input -->
                    <div class="form-group col-lg-4 my-3">
                        <label class="control-label">End Date</label>
                        <input type="date"
                            id="enddatepicker"
                            class="form-control"
                            name="end_date"
                            value="<?= isset($end_date) ? $end_date : $today ?>"
                            <?= !empty($min_date) ? "min='$min_date' max='$today'" : '' ?>
                            onfocus="this.showPicker()">
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

            <?php if (!empty($overall_usage['data']['total_applications']) && $overall_usage['data']['total_applications'] > 0): ?>
                <div id="overall-status">
                    <div class="box mb-4">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-6">
                                    <h4>Usage Status</h4>
                                    <p class="text-muted"><i class="bi bi-clock mr-5"></i>Total time tracked - <?= $overall_usage['data']['total_usage_time'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="py-4" style="<?= $isLast ? '' : 'border-bottom: 1px solid #dee2e6;' ?>">
                                <div class="app-details">
                                    <div class="app-name">
                                        <div class="form-group my-0">
                                            <h4>Productive Usage<small> - <?= $productive_usage['data']['total_usage_time'] ?></small></h4>
                                            <!-- <p class="text-muted"><i class="bi bi-clock mr-5"></i>Total time tracked</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-container" data-toggle="tooltip" data-placement="top"
                                    title="Usage Time: <?= $productive_usage['data']['total_usage_time'] ?>" style="cursor:pointer;">
                                    <div class="progress-bar" style="width: <?= ($productive_usage['data']['raw_total_usage_seconds'] / $overall_usage['data']['raw_total_usage_seconds']) * 100 ?>%;"></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px; justify-content: space-between;">
                                    <div class="text-muted">
                                        <?= number_format(($productive_usage['data']['raw_total_usage_seconds'] / $overall_usage['data']['raw_total_usage_seconds']) * 100, 1); ?>% of total
                                    </div>
                                    <button type="button" class="btn btn-default show-windows-modal" data-id="productive">
                                        <i class="bi bi-window-stack mr-5"></i>
                                        Usage History (<?= $productive_usage['data']['total_applications'] ?>)
                                    </button>
                                </div>
                            </div>

                            <div class="py-4" style="<?= $isLast ? '' : 'border-bottom: 1px solid #dee2e6;' ?>">
                                <div class="app-details">
                                    <div class="app-name">
                                        <div class="form-group my-0">
                                            <h4>Unproductive Usage<small> - <?= $unproductive_usage['data']['total_usage_time'] ?></small></h4>
                                            <!-- <p class="text-muted"><i class="bi bi-clock mr-5"></i>Total time tracked</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-container" data-toggle="tooltip" data-placement="top"
                                    title="Usage Time: <?= $unproductive_usage['data']['total_usage_time'] ?>" style="cursor:pointer;">
                                    <div class="progress-bar-red" style="width: <?= ($unproductive_usage['data']['raw_total_usage_seconds'] / $overall_usage['data']['raw_total_usage_seconds']) * 100 ?>%;"></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px; justify-content: space-between;">
                                    <div class="text-muted">
                                        <?= number_format(($unproductive_usage['data']['raw_total_usage_seconds'] / $overall_usage['data']['raw_total_usage_seconds']) * 100, 1); ?>% of total
                                    </div>
                                    <button type="button" class="btn btn-default show-windows-modal" data-id="unproductive" data-windows='<?= htmlspecialchars(json_encode($appData['windows']), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="bi bi-window-stack mr-5"></i>
                                        Usage History (<?= $unproductive_usage['data']['total_applications'] ?>)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productive Section (Initially Hidden) -->
                <div id="productive-status" class="d-none box">

                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-6">
                                <h4>Productive Status</h4>
                                <p class="text-muted"><i class="bi bi-clock mr-5"></i>Total time tracked - <?= $productive_usage['data']['total_usage_time'] ?></p>
                            </div>
                            <div class="col-6">
                                <button type="button" class="pull-right btn btn-default btn-sm rounded mt-4" id="back-btn-productive"><i class="fa fa-angle-left"></i> Back</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" id="productive">
                        <?php
                        $applications = $productive_usage['data']['applications'];
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
                                    <div class="progress-bar" style="width: <?= ($appData['total_seconds'] / $productive_usage['data']['raw_total_usage_seconds']) * 100 ?>%;"></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px; justify-content: space-between;">
                                    <div class="text-muted">
                                        <?= number_format(($appData['total_seconds'] / $productive_usage['data']['raw_total_usage_seconds']) * 100, 1); ?>% of total
                                    </div>
                                    <button type="button" class="btn btn-default show-windows-modal"
                                        data-app="<?= htmlspecialchars($appName) ?>"
                                        data-windows='<?= htmlspecialchars(json_encode($appData['windows']), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="bi bi-window-stack mr-5"></i>
                                        Windows (<?= count($appData['windows']) ?>)
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Unproductive Section (Initially Hidden) -->
                <div id="unproductive-status" class="d-none box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-6">
                                <h4>Unproductive Status</h4>
                                <p class="text-muted"><i class="bi bi-clock mr-5"></i>Total time tracked - <?= $unproductive_usage['data']['total_usage_time'] ?></p>
                            </div>
                            <div class="col-6">
                                <button type="button" class="pull-right btn btn-default btn-sm rounded mt-4" id="back-btn-unproductive"><i class="fa fa-angle-left"></i> Back</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" id="unproductive">
                        <?php
                        $applications = $unproductive_usage['data']['applications'];
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
                                    <div class="app-time-unpro"><?= $appData['formatted_time'] ?></div>
                                </div>
                                <div class="progress-container" data-toggle="tooltip" data-placement="top"
                                    title="Usage Time: <?= $appData['formatted_time'] ?>" style="cursor:pointer;">
                                    <div class="progress-bar-red" style="width: <?= ($appData['total_seconds'] / $unproductive_usage['data']['raw_total_usage_seconds']) * 100 ?>%;"></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px; justify-content: space-between;">
                                    <div class="text-muted">
                                        <?= number_format(($appData['total_seconds'] / $unproductive_usage['data']['raw_total_usage_seconds']) * 100, 1); ?>% of total
                                    </div>
                                    <button type="button" class="btn btn-default show-windows-modal"
                                        data-app="<?= htmlspecialchars($appName) ?>"
                                        data-windows='<?= htmlspecialchars(json_encode($appData['windows']), ENT_QUOTES, 'UTF-8') ?>'>
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
                            <strong class="text-right">No Application Usage Found.</strong>
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
            // 🔹 Auto-submit form when filters change
            $(document).on('change', '#employee_search, #startdatepicker,#enddatepicker, #sortOrder', function() {
                $('.user_filter_form').submit();
            });

            // 🔹 Show modal with merged window details
            $(document).on('click', '.show-windows-modal[data-app]', function() {
                const appName = $(this).data('app');
                const windows = $(this).data('windows');

                if (!windows || !Array.isArray(windows)) return;

                // Merge windows with same title
                const merged = {};
                windows.forEach(window => {
                    if (!merged[window.window_title]) merged[window.window_title] = 0;
                    merged[window.window_title] += window.total_seconds;
                });

                // Clear old data
                $('#windowDetailsBody').empty();

                // Add rows for each merged window
                Object.entries(merged).forEach(([title, totalSeconds]) => {
                    const hours = Math.floor(totalSeconds / 3600);
                    const minutes = Math.floor((totalSeconds % 3600) / 60);
                    const seconds = totalSeconds % 60;

                    // Format time
                    let timeStr = '';
                    if (hours > 0) timeStr += hours + 'h ';
                    if (minutes > 0) timeStr += minutes + 'min ';
                    if (seconds > 0) timeStr += seconds + 's';

                    // Trim long titles
                    let displayTitle = title.length > 85 ? title.substring(0, 85) + '...' : title;

                    $('#windowDetailsBody').append(`
                <tr>
                    <td title="${title}">${displayTitle}</td>
                    <td>${timeStr.trim()}</td>
                </tr>
            `);
                });

                // Enable tooltips and show modal
                $('#windowDetailsBody [title]').tooltip();
                $('#windowDetailsModal').modal('show');
            });

            // 🔹 Switch to Productive View
            $(document).on('click', '.show-windows-modal[data-id="productive"]', function() {
                $('#overall-status').hide();
                $('#productive-status').removeClass('d-none').show();
                $('#unproductive-status').hide();
            });

            // 🔹 Switch to Unproductive View
            $(document).on('click', '.show-windows-modal[data-id="unproductive"]', function() {
                $('#overall-status').hide();
                $('#unproductive-status').removeClass('d-none').show();
                $('#productive-status').hide();
            });

            // 🔹 Back Buttons
            $('#back-btn-productive').on('click', function() {
                $('#productive-status').hide();
                $('#overall-status').show();
            });

            $('#back-btn-unproductive').on('click', function() {
                $('#unproductive-status').hide();
                $('#overall-status').show();
            });
        });
    </script>