<div class="content-wrapper org_exception_settings">
    <section class="content">
        <div class="container mt-4">
            <h3>Employee Settings</h3>

            <div class="container mt-50 employee_settings_table">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs admin">
                                <li>
                                    <a class="<?php if (isset($navbar) && $navbar == 'own_settings') echo 'active'; ?>"
                                        href="<?php echo base_url('admin/employee_settings/assigned') ?>">
                                        <i class="fa fa-cogs"></i>
                                        <span class="hidden-xs"><?php echo "Assigned" ?></span>
                                    </a>
                                </li>

                                <li>
                                    <a class="<?php if (isset($navbar) && $navbar == 'no_own_settings') echo 'active'; ?>"
                                        href="<?php echo base_url('admin/employee_settings/unassigned') ?>">
                                        <i class="fa fa-ban"></i>
                                        <span class="hidden-xs"><?php echo "Unassigned" ?></span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

                <!-- table format for no settings employees -->
                <?php if (isset($employees)): ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0">
                        <table class="table table-hover cushover mt-0 <?php if (count($employees) > 10) {
                                                                            echo "datatable";
                                                                        } ?>" id="dg_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo 'Name'; ?></th>
                                    <th><?php echo 'Email'; ?></th>
                                    <th><?php echo 'Department'; ?></th>
                                    <th><?php echo 'Role'; ?></th>
                                    <th class="text-center"><?php echo trans('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($employees)): ?>
                                    <!-- <tr>
                                <td>notification not found</td>
                            </tr> -->
                                <?php else: ?>
                                    <?php $i = 1;
                                    foreach ($employees as $employee): ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $employee['name']; ?></td>
                                            <td><?php echo $employee['email']; ?></td>
                                            <td><?php echo $employee['department']; ?></td>
                                            <td><?php echo $employee['role']; ?></td>

                                            <td class="text-center">
                                                <?php if ($can_edit): ?>
                                                    <button class="btn btn-default rounded add-settings-btn" data-toggle="tooltip"
                                                        data-id="<?php echo $employee['id']; ?>" data-user-id="<?php echo $employee['user_id']; ?>"
                                                        data-placement="top" style="margin-top:5px;">
                                                        <i class="fa fa-plus"></i> Add Settings
                                                    </button>
                                                <?php else: ?>
                                                    <span class="btn btn-default rounded" data-toggle="tooltip"
                                                        data-placement="top" style="margin-top:5px;" title="permission denied to manage employee settings">
                                                        <i class="fa fa-plus"></i> Add Settings
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php $i++;
                                    endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?php if (isset($employees_settings)): ?> <!-- employee_setting form or content goes here -->
                    <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0">
                        <table class="table table-hover cushover mt-0 <?php if (count($employees_settings) > 10) {
                                                                            echo "datatable";
                                                                        } ?>" id="dg_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo 'Name'; ?></th>
                                    <th><?php echo 'Email'; ?></th>
                                    <th><?php echo 'Department'; ?></th>
                                    <th><?php echo 'Role'; ?></th>
                                    <th><?php echo 'View Settings'; ?></th>
                                    <th><?php echo trans('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($employees_settings)): ?>
                                    <!-- <tr>
                                <td>notification not found</td>
                            </tr> -->
                                <?php else: ?>
                                    <?php $i = 1;
                                    foreach ($employees_settings as $employees_setting): ?>
                                        <tr id="row_<?php echo html_escape($employees_setting['id']); ?>">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $employees_setting['name']; ?></td>
                                            <td><?php echo $employees_setting['email']; ?></td>
                                            <td><?php echo $employees_setting['department']; ?></td>
                                            <td><?php echo $employees_setting['role']; ?></td>
                                            <td class="text-center mr-5">
                                                <a href="#" class="view-permissions"
                                                    data-role="${role.role_name}"
                                                    data-features='<?php echo json_encode($employees_setting); ?>'
                                                    title="View settings">
                                                    <i class="bi bi-eye-fill text-primary" style="font-size: 1.5rem;"></i>
                                                </a>
                                            </td>

                                            <td class="actions view-settings text-center" width="15%">
                                                <?php if ($can_edit): ?>
                                                    <a href="#"
                                                        class="on-default edit-row"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        data-id="<?php echo $employees_setting['id']; ?>"
                                                        data-user-id="<?php echo $employees_setting['user_id']; ?>"
                                                        title="Edit"
                                                        data-original-title="Edit"
                                                        data-employee='<?php echo json_encode($employees_setting); ?>'>
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    <a
                                                        data-val="department"
                                                        data-id="<?php echo $employees_setting['id']; ?>"
                                                        data-user-id="<?php echo $employees_setting['user_id']; ?>"
                                                        href="<?php echo base_url('admin/Organization_settings/settings_delete/'  . html_escape($employees_setting['id'])) ?>"
                                                        class="on-default remove-row delete_Assigned_Settings_item"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Delete"
                                                        data-original-title="Delete">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="#"
                                                        class="on-default edit-row"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Permission denied"
                                                        data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    <a href="#"
                                                        class="on-default"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Permission denied"
                                                        data-original-title="Delete">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php $i++;
                                    endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hidden form, initially hidden with inline style -->
            <div class="container mt-50 settings-form" style="display: none;">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php if (isset($navbar) && $navbar == 'own_settings'):
                                echo 'Edit Settings';
                            else :
                                echo 'Add Settings';
                            endif; ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href=" <?php if (isset($navbar) && $navbar == 'own_settings'):
                                            echo base_url('admin/employee_settings/assigned');
                                        else :
                                            echo base_url('admin/employee_settings/unassigned');
                                        endif; ?>" class="pull-right btn btn-default btn-sm back-btn"><i class="fa fa-angle-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- <div class="form-group mt-4 row">
                            <div class="col-md-10">
                                <label class="control-label" for="employeeSelect">Employee</label>
                                <select id="employeeSelect" class="form-control single_select"></select>
                            </div>
                        </div> -->
                        <form id="orgExceptionForm">
                            <input type="hidden" name="employee_id" id="employee_id">
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="row  mt-4">
                                <!-- timezone -->
                                <div class="col-md-4 mb-5 form-group">
                                </div>

                                <div class="col-md-6 mb-5 form-group">
                                    <label class="form-label">Timezone:</label>
                                    <div class="input-group" id="editTimezoneBtn" style="cursor:pointer;">
                                        <input type="text" name="time_zone" value="" id="timezone_input" class="form-control" readonly style="cursor:pointer;">
                                        <div class="input-group-addon" style="cursor:pointer;">
                                            <span class="">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Screenshot -->
                                <div class="col-md-4 mb-5 form-group">
                                    <label class="control-label">Screenshot Flag:</label><br>
                                    <div>
                                        <label class="toggle-switch ">
                                            <input type="checkbox" class="toggle-flag" name="screenshot_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-5 form-group">
                                    <label>Screenshot Interval (mins):</label>
                                    <select name="screenshot_time_interval" class="form-control interval-field">
                                        <?php if (is_plan_basic()): ?>
                                            <option value="10">10</option>
                                        <?php elseif (is_plan_standard()): ?>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                        <?php else: ?>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Webcam -->
                                <div class="col-md-4 mb-5 form-group">
                                    <label>Webcam Flag:</label><br>
                                    <div>
                                        <label class="toggle-switch ">
                                            <input type="checkbox" class="toggle-flag" name="webcam_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5 form-group">
                                    <label>Webcam Interval (mins):</label>
                                    <select name="webcam_time_interval" class="form-control interval-field"
                                        <?php echo is_plan_basic() ? 'disabled' : ''; ?>>
                                        <?php if (is_plan_standard()): ?>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                        <?php else: ?>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                        <?php endif; ?>
                                    </select>

                                    <?php if (is_plan_basic()): ?>
                                        <small class="text-danger">Webcam feature not available in Basic plan</small>
                                    <?php endif; ?>
                                </div>


                                <!-- Mouse Move -->
                                <div class="col-md-4 mb-5 form-group">
                                    <label>Mouse Move Flag:</label><br>
                                    <div>
                                        <label class="toggle-switch ">
                                            <input type="checkbox" class="toggle-flag" name="mouse_move_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5 form-group">
                                    <label>Mouse Move Threshold:</label>
                                    <input type="number" name="mouse_move_threshold" class="form-control" value="20" readonly />
                                </div>

                                <!-- Keystroke -->
                                <div class="col-md-4 mb-5 form-group">
                                    <label>Keystroke Flag:</label><br>
                                    <div>
                                        <label class="toggle-switch ">
                                            <input type="checkbox" class="toggle-flag" name="key_stroke_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5 form-group">
                                    <label>Keystroke Threshold:</label>
                                    <input type="number" name="key_stroke_threshold" class="form-control" value="40" readonly />
                                </div>

                                <!-- Idle Time -->
                                <div class="col-md-4 mb-5 form-group">
                                    <label>Idle Time Flag:</label><br>
                                    <div>
                                        <label class="toggle-switch ">
                                            <input type="checkbox" class="toggle-flag" name="idle_time_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5 form-group">
                                    <label>Timecards Interval (mins):</label>
                                    <select name="timecards_time_interval" class="form-control interval-field">
                                        <?php if (is_plan_basic()): ?>
                                            <option value="10">10</option>
                                        <?php elseif (is_plan_standard()): ?>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                        <?php else: ?>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-5 form-group">
                                    <label>Self Login:</label><br>
                                    <div>
                                        <label class="toggle-switch ">
                                            <input type="checkbox" class="toggle-flag" name="self_login" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                        </label>
                                    </div>
                                </div>


                                <div class="col-12 form-group">
                                    <?php if (is_pack_trial()): ?>
                                        <span class="text-danger pull-left " data-toggle="tooltip" data-placement="bottom" title="Upgrade your plan to enable editing.">
                                            <i class="fa fa-lock"></i> Editing is disabled in Trial plan.
                                        </span>
                                    <?php else: ?>
                                        <?php if ($can_edit): ?>
                                            <button type="submit" class="btn btn-info">Save Settings</button>
                                        <?php else: ?>
                                            <span data-toggle="tooltip" data-placement="right" title="permission denied to manage employee settings" class="btn btn-default btn-sm m-5">Save Settings</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap settings  Modal -->
        <div class="modal" id="view_settings" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-zoom" role="document">
                <div class="modal-content" style="margin-top: 10% !important">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Settings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Step Instructions -->
                        <div class="row mb-5 form-group">
                            <div class="col-sm-6">
                                <label class="form-label" for="">Time zone :</label><span class="" id="timeZoneValue"></span>
                            </div>
                            <div id="selfLoginStatus" class="mb-3">
                                <label class="form-label">Self Login :</label>
                                <span id="selfLoginValue"></span>
                            </div>

                        </div>


                        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo 'Name'; ?></th>
                                        <th><?php echo 'Status'; ?></th>
                                        <th><?php echo 'Interval'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<!-- Bootstrap Timezone Modal with Steps -->
<div class="modal fade" id="timezoneModal" tabindex="-1" role="dialog" aria-labelledby="timezoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-zoom" role="document">
        <div class="modal-content" style="margin-top: 10% !important">
            <div class="modal-header">
                <h5 class="modal-title" id="timezoneModalLabel">Edit Timezone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Step Instructions -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-secondary mr-2">1</span>
                        <strong>Select a Country</strong>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="country" id="country_select">
                            <option value=""><?php echo trans('select') ?></option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo html_escape($country->id); ?>">
                                    <?php echo html_escape($country->name); ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="d-flex align-items-center mb-3 mt-4">
                        <span class="badge badge-secondary  mr-2">2</span>
                        <strong>Select a Timezone</strong>
                    </div>
                    <div class="form-group">
                        <select name="time_zone" id="timezone_select" class="form-control" disabled>
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="saveTimezoneBtn" class="btn btn-info">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>




<!-- jQuery script to show form on Add Settings button click -->
<script>
    let currentEmployeeId = null;

    function toggleIntervalField(checkbox, enabled) {
        const name = checkbox.attr('name');
        let target = null;

        if (name === 'screenshot_flag') target = $('[name="screenshot_time_interval"]');
        else if (name === 'webcam_flag') target = $('[name="webcam_time_interval"]');
        else if (name === 'idle_time_flag') target = $('[name="timecards_time_interval"]');
        else if (name === 'mouse_move_flag') target = $('[name="mouse_move_threshold"]');
        else if (name === 'key_stroke_flag') target = $('[name="key_stroke_threshold"]');

        if (target) {
            if (enabled) {
                target.prop('disabled', false);
                // if (target.is('select')) {
                //     const firstValue = target.find('option:first').next().val();
                //     target.val(firstValue);
                // }
            } else {
                target.prop('disabled', true);
            }
        }
    }

    function validateThresholds() {
        let isValid = true;
        const timeZoneField = $('[name="time_zone"]');
        const timeZoneValue = timeZoneField.val();

        if (!timeZoneValue) {
            // time_zone is present and has a non-empty value
            showToast("Please select timezone", 'error');
            return flase;
        }
        const limits = {
            mouse_move_threshold: 20,
            key_stroke_threshold: 40
        };

        for (const [fieldName, max] of Object.entries(limits)) {
            const field = $(`[name="${fieldName}"]`);
            const value = parseInt(field.val(), 10);

            if (!field.prop('disabled') && (isNaN(value) || value < 1 || value > max)) {
                const readableName = fieldName.replace(/_/g, ' ');
                showToast(`${readableName} must be between 1 and ${max}`, 'error');
                isValid = false;
            }
        }

        return isValid;
    }


    $(document).ready(function() {
        $('.add-settings-btn').click(function() {
            let employeeId = $(this).data('id');
            let userId = $(this).data('user-id');

            // Store in hidden fields
            $('#employee_id').val(employeeId);
            $('#user_id').val(userId);

            // Also store in global variable (if needed)
            currentEmployeeId = employeeId;

            // Hide the employee table and show the form
            $('.employee_settings_table').hide();
            $('.settings-form').show();
        });
        $('.edit-row').on('click', function() {
            let employeeId = $(this).data('id');
            let userId = $(this).data('user-id');
            let employee = $(this).data('employee');
            let settings = employee.settings;
            // Show the form

            // Populate employee select
            $('#employee_id').val(employeeId);
            $('#user_id').val(userId);

            // Also store in global variable (if needed)
            currentEmployeeId = employeeId;
            // Populate checkboxes and intervals

            $('input[name="time_zone"]').val(settings.time_zone);

            $('input[name="screenshot_flag"]').prop('checked', settings.screenshot.flag == 1).change();
            $('select[name="screenshot_time_interval"]').val(settings.screenshot.interval);

            $('input[name="webcam_flag"]').prop('checked', settings.webcam.flag == 1).change();
            $('select[name="webcam_time_interval"]').val(settings.webcam.interval).change();

            $('input[name="mouse_move_flag"]').prop('checked', settings.mouse_movement.flag == 1).change();
            // Set threshold if needed, e.g.
            // $('input[name="mouse_move_threshold"]').val(settings.mouse_movement.threshold || 20);

            $('input[name="key_stroke_flag"]').prop('checked', settings.keystrokes.flag == 1).change();
            // $('input[name="key_stroke_threshold"]').val(settings.keystrokes.threshold || 40);

            // Idle Time and Self Login might not be inside settings, set defaults or adapt as needed
            $('input[name="idle_time_flag"]').prop('checked', settings.idle_time.flag == 1).change();
            $('select[name="timecards_time_interval"]').val(settings.idle_time.interval);
            $('input[name="self_login"]').prop('checked', settings.self_login == 1).change();
            $('.employee_settings_table').hide();
            $('.settings-form').show();
            console.log(settings.self_login);
        });
        // Show modal
        $('#editTimezoneBtn').on('click', function() {
            $('#timezoneModal').modal('show');
        });
        $('.view-permissions').on('click', function() {
            const employeeData = $(this).data('features');
            const settings = employeeData.settings;

            // Clear old rows
            const tbody = $('#view_settings tbody');
            tbody.empty();

            // Set timezone
            $('#timeZoneValue').text(settings.time_zone || 'Not Set');

            // self login
            const selfLoginStatus = settings.self_login == 1 ? 'On' : 'Off';
            const selfLoginColor = settings.self_login == 1 ? 'label label-success' : 'label label-danger';

            console.log('Evaluated Status:', selfLoginStatus);
            console.log('Evaluated Class:', selfLoginColor);

            $('#selfLoginValue')
                .removeClass()
                .addClass(selfLoginColor)
                .text(selfLoginStatus);

            // (rest of your code to build the table etc.)

            $('#view_settings').modal('show');

            // Prepare mapping (so labels look good)
            const settingMap = [{
                    key: 'screenshot',
                    label: 'Screenshot'
                },
                {
                    key: 'webcam',
                    label: 'Webcam'
                },
                {
                    key: 'mouse_movement',
                    label: 'Mouse Movement'
                },
                {
                    key: 'keystrokes',
                    label: 'Keystrokes'
                },
                {
                    key: 'idle_time',
                    label: 'Idle Time'
                }
            ];

            let index = 1;
            settingMap.forEach(item => {
                if (settings[item.key]) {
                    const flag = settings[item.key].flag == 1 ? 'on' : 'off';
                    const colorStatus = settings[item.key].flag == 1 ? 'label-success' : 'label-danger';
                    const interval = item.noInterval ?
                        '-' :
                        (settings[item.key].interval || settings[item.key].threshold || '-');

                    tbody.append(`
                <tr>
                    <td>${index}</td>
                    <td>${item.label}</td>
                    <td> <span class="label ${colorStatus}" >${flag}</span></td>
                    <td>${interval}</td>
                </tr>
            `);

                    index++;
                }
            });

            // Show modal
            $('#view_settings').modal('show');
        });

        // Save and close modal
        $('#saveTimezoneBtn').on('click', function() {

            const selectedTimezone = $('#timezone_select').val();
            if (selectedTimezone != "") {
                $('#timezone_input').val(selectedTimezone);
                $('#timezoneModal').modal('hide');
            } else {
                showToast("Please select timezone", 'error');
            }
        });

        $('#country_select').on('change', function() {
            var country_id = $(this).val();


            // Clear existing timezones
            $('#timezone').html('<option value="">Loading...</option>');

            if (country_id) {
                $.ajax({
                    url: '<?= base_url('admin/organization_settings/get_timezones_by_country_id') ?>',
                    type: 'GET',
                    data: {
                        country_id: country_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            let options = '<option value="">Select</option>';
                            $.each(response.data, function(index, value) {
                                options += '<option value="' + value + '">' + value + '</option>';
                            });
                            $('#timezone_select').html(options);
                        } else {
                            $('#timezone_select').html('<option value="">No timezones found</option>');
                        }
                        $('#timezone_select').prop('disabled', false);
                    },
                    error: function(xhr) {
                        $('#timezone_select').html('<option value="">Error fetching timezones</option>');
                    }
                });
            } else {
                $('#timezone_select').html(' < option value = "" > Select < /option>');
            }
        });
        // Checkbox toggle
        $('#orgExceptionForm').on('change', 'input[type="checkbox"]', function() {
            toggleIntervalField($(this), $(this).is(':checked'));
        });
        // Save form
        $('#orgExceptionForm').on('submit', function(e) {
            e.preventDefault();
            // if (!currentEmployeeId) {
            //     showToast('Please select an employee first.', 'error');
            //     return;
            // }
            if (!$('#employee_id').val() || !$('#user_id').val()) {
                showToast('Missing employee details.', 'error');
                return;
            }

            if (!validateThresholds()) return;

            const dataObj = {};

            // Include checkbox states and related fields manually
            $('#orgExceptionForm input[type="checkbox"]').each(function() {
                const name = $(this).attr('name');
                const isChecked = $(this).is(':checked');
                dataObj[name] = isChecked ? 1 : 0;
            });

            // Always fetch the values (even if the related flag is off)
            dataObj['screenshot_time_interval'] = $('[name="screenshot_time_interval"]').val();
            dataObj['webcam_time_interval'] = $('[name="webcam_time_interval"]').val();
            dataObj['mouse_move_threshold'] = $('[name="mouse_move_threshold"]').val();
            dataObj['key_stroke_threshold'] = $('[name="key_stroke_threshold"]').val();
            dataObj['timecards_time_interval'] = $('[name="timecards_time_interval"]').val();
            // Add timezone value to the data object with the correct name
            dataObj['time_zone'] = $('#timezone_input').val();

            src = "<?= base_url('ws-client.js'); ?>" >
                $.ajax({
                    url: '<?= base_url('admin/Organization_settings/save_org_exception_settings/') ?>' + currentEmployeeId,
                    type: 'POST',
                    data: dataObj,
                    dataType: 'json', // ← tell jQuery to parse JSON
                    success: function(res) {
                        if (res.success) {
                            /* fire the WebSocket */
                            const p = res.payload;
                            changeOrganizationSetting(p.employeeId, p);

                            /* UI feedback */
                            swal('Success!', res.msg, 'success');
                        } else {
                            swal('Error!', 'Save failed.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.msg || 'Something went wrong.';
                        swal('Error!', msg, 'error');
                    }
                });
        });
    });
</script>