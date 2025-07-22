<div class="content-wrapper org_exception_settings">
    <section class="content">
        <div class="container mt-4">
            <h3>Employee Settings</h3>
            <div class="box">
                <div class="box-body">
                    <div class="form-group mt-4 row"> <!-- Added 'row' class here -->
                        <div class="col-md-10">
                            <label class="control-label" for="employeeSelect">Employee</label>
                            <select id="employeeSelect" class="form-control single_select"></select>
                        </div>
                    </div>
                    <form id="orgExceptionForm">
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
                                <!-- <label class="switch">
                                    <input type="checkbox" name="screenshot_flag" checked>
                                    <span class="slider"></span>
                                </label> -->
                                <div>
                                    <label class="toggle-switch ">
                                        <input type="checkbox" class="toggle-flag" name="screenshot_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="col-md-6 form-group">
                                <label>Screenshot Interval (mins):</label>
                                <select name="screenshot_time_interval" class="form-control interval-field">

                                    <option value="1" disabled>1</option>
                                    <option value="2">2</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                </select>
                            </div> -->
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
                                <!-- <label class="switch" <?= is_plan_basic() ? 'data-toggle="tooltip" data-placement="top" title="Webcam feature not available in Basic plan"'  : '' ?>>
                                    <input type="checkbox" name="webcam_flag"
                                        <?= (!is_plan_basic()) ? 'checked' : 'disabled' ?>>
                                    <span class="slider"></span>
                                </label> -->
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
                                <!-- <label class="switch">
                                    <input type="checkbox" name="mouse_move_flag" checked>
                                    <span class="slider"></span>
                                </label> -->
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
                                <!-- <label class="switch">
                                    <input type="checkbox" name="key_stroke_flag" checked>
                                    <span class="slider"></span>
                                </label> -->
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
                                <!-- <label class="switch">
                                    <input type="checkbox" name="idle_time_flag" checked>
                                    <span class="slider"></span>
                                </label> -->
                                <div>
                                    <label class="toggle-switch ">
                                        <input type="checkbox" class="toggle-flag" name="idle_time_flag" value="1" checked data-toggle="toggle" data-onstyle="info" data-width="100">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-5 form-group">
                                <label>Timecards Interval (mins):</label>
                                <!-- <select name="timecards_time_interval" class="form-control interval-field">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select> -->
                                <input type="text" name="timecards_time_interval" class="form-control" value="1" readonly>
                            </div>

                            <div class="col-md-4 mb-5 form-group">
                                <label>Self Login:</label><br>
                                <!-- <label class="switch">
                                    <input type="checkbox" name="self_login" value="1" <?php echo ($existing_value['self_login'] == 1) ? 'checked' : ''; ?>>
                                    <span class="slider"></span>
                                </label> -->
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
                                        <span data-toggle="tooltip" data-placement="top" title="permission denied to manage employee settings" class="btn btn-default btn-sm m-5">save Settings</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>



<!-- Bootstrap Timezone Modal with Steps -->
<div class="modal fade" id="timezoneModal" tabindex="-1" role="dialog" aria-labelledby="timezoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
<script src="<?= base_url('ws-client.js'); ?>"></script>
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
            console.log(`${field}: ${value}`);

            if (!field.prop('disabled') && (isNaN(value) || value < 1 || value > max)) {
                const readableName = fieldName.replace(/_/g, ' ');
                showToast(`${readableName} must be between 1 and ${max}`, 'error');
                isValid = false;
            }
        }

        return isValid;
    }

    $(document).ready(function() {
        // Show modal
        $('#editTimezoneBtn').on('click', function() {
            $('#timezoneModal').modal('show');
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
            console.log(country_id);


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
        // Load employees
        $.ajax({
            url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response);

                let employeeSelect = $('#employeeSelect');
                let countrySelect = $('#country_select');

                employeeSelect.empty().append(`<option value="">Select</option>`);

                if (response.status === "success" && response.employees.length > 0) {
                    response.employees.forEach(emp => {
                        employeeSelect.append(`<option value="${emp.id}" data-country="${emp.country}">${emp.name} (${emp.email})</option>`);
                    });


                } else {
                    employeeSelect.empty().append(`<option value="">-- No employees found --</option>`);
                }
            },
            error: function() {
                $('#employeeSelect').empty().append(`<option value="">-- No employees found --</option>`);
            }
        });


        // Checkbox toggle
        $('#orgExceptionForm').on('change', 'input[type="checkbox"]', function() {
            toggleIntervalField($(this), $(this).is(':checked'));
        });

        // On employee select
        $('#employeeSelect').on('change', function() {
            const employeeId = $(this).val();
            currentEmployeeId = employeeId;
            // ✅ FIXED: Get selected option's data-country
            let selectedCountryId = $(this).find(':selected').data('country');
            console.log(selectedCountryId);

            if (selectedCountryId) {
                $('#country_select').val(selectedCountryId).trigger('change');
            } else {
                $('#country_select').val('');
            }


            // If no employee selected, reset to default values
            if (!employeeId) {
                $('#orgExceptionForm')[0].reset();

                $('#orgExceptionForm input[type="checkbox"]').prop('checked', true).trigger('change');
                $('#orgExceptionForm select.interval-field').val('1').prop('disabled', false);
                $('[name="mouse_move_threshold"]').val(20).prop('disabled', false);
                $('[name="key_stroke_threshold"]').val(40).prop('disabled', false);

                return;
            }

            $.ajax({
                url: `<?= base_url('admin/organization_settings/get_org_exception_settings/') ?>${employeeId}`,
                method: 'GET',
                success: function(data) {
                    console.log(data);

                    const settings = JSON.parse(data);

                    // Reset form
                    $('#orgExceptionForm')[0].reset();

                    // Set all checkboxes to true and enable all dependent fields


                    // Set the timezone input field if it's part of the employee data
                    // if (settings['time_zone']) {
                    //     $('#timezone').val(settings['time_zone']); // Show timezone as plain text
                    // } else {
                    //     $('#timezone').val('');
                    // }


                    if (!settings.error) {
                        for (let key in settings) {
                            const field = $(`[name="${key}"]`);
                            const value = settings[key];

                            if (field.attr('type') === 'checkbox') {
                                const isChecked = value == 1;
                                field.prop('checked', isChecked).trigger('change');

                                // Related input field disabling/enabling
                                let relatedField = null;

                                if (key === 'screenshot_flag') relatedField = $('[name="screenshot_time_interval"]');
                                if (key === 'webcam_flag') relatedField = $('[name="webcam_time_interval"]');
                                if (key === 'mouse_move_flag') relatedField = $('[name="mouse_move_threshold"]');
                                if (key === 'key_stroke_flag') relatedField = $('[name="key_stroke_threshold"]');
                                if (key === 'idle_time_flag') relatedField = $('[name="timecards_time_interval"]');

                                if (relatedField) {
                                    if (isChecked) {
                                        relatedField.prop('disabled', false).val(settings[relatedField.attr('name')] || '');
                                    } else {
                                        relatedField.prop('disabled', true);
                                    }
                                }

                            } else {
                                if (!field.prop('disabled')) {
                                    field.val(value);
                                } else {
                                    field.val(value);
                                }
                            }
                        }
                        showToast('Data fetched successfully.', 'success');
                    } else {
                        showToast('No settings found. Please insert the data.', 'success');
                    }
                },
                error: function() {
                    showToast('Something went wrong while fetching settings.', 'error');
                }
            });
        });



        // Save form
        // Save form
        $('#orgExceptionForm').on('submit', function(e) {
            e.preventDefault();
            if (!currentEmployeeId) {
                showToast('Please select an employee first.', 'error');
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

            console.log(currentEmployeeId);
            console.log(dataObj);
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
                            changeOrganizationSetting(p.employeeId, p.settings);

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