    <?php
    // Function: Convert seconds to "Hh Mm"
    function formatTime($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        return ($h > 0 ? "{$h}h " : "") . "{$m}m";
    }
    ?>

    <div class="content-wrapper application_logs">
        <section class="content">
            <h3>Application Usage Tracker</h3>
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

            <?php if (!empty($response['data']['applications'])): ?>
                <div class="box mb-4">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-6">
                                <h4>Application Usage Summary</h4>
                                <p class="text-muted">Total time tracked: <?= $response['data']['total_usage_time'] ?></p>
                            </div>
                            <div class="col-6 text-end d-flex justify-content-end align-items-center">
                                <span class="total-time-badge" style="height: max-content;">
                                    <?= $response['data']['total_usage_time'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php foreach ($response['data']['applications'] as $appName => $appData): ?>
                            <div class="py-4" style="border-bottom: 1px solid #dee2e6;">
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
                                    <button type="button" class="btn text-info show-windows-modal" 
                                            data-app="<?= htmlspecialchars($appName) ?>"
                                            data-windows='<?= json_encode($appData['windows']) ?>'>
                                        Show Windows
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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Window Title</th>
                                    <th>Formatted Time</th>
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
            
            // Set modal title
            $('#windowDetailsModalLabel').text('Window Details - ' + appName);
            
            // Clear previous content
            $('#windowDetailsBody').empty();
            
            // Add window details to table
            windows.forEach(function(window) {
                $('#windowDetailsBody').append(`
                    <tr>
                        <td>${window.window_title}</td>
                        <td>${window.formatted_time}</td>
                    </tr>
                `);
            });
            
            // Show the modal
            $('#windowDetailsModal').modal('show');
        });
    });
    </script>

    <style>
    .app-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .progress-container {
        height: 10px;
        background-color: #f0f0f0;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    .progress-bar {
        height: 100%;
        background-color: #4e73df;
        border-radius: 5px;
    }

    .total-time-badge {
        background-color: #4e73df;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
    }

    .window-details {
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 10px;
    }

    .modal-dialog-zoom {
        max-width: 800px;
    }
    </style>