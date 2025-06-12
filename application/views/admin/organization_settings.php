<style>
    [type=radio]:not(:checked),
    [type=radio]:checked {
        position: static;
        opacity: 1;
    }


    #orgSettingsForm {
        max-width: 1000px;
    }

    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
        display: inline-block;
    }

    .toggle-switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 34px;
        transition: 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        border-radius: 50%;
        transition: 0.4s;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(30px);
    }

    .slider::after {
        content: 'OFF';
        color: white;
        font-size: 12px;
        position: absolute;
        right: 10px;
        top: 7px;
    }

    input:checked+.slider::after {
        content: 'ON';
        left: 10px;
        right: auto;
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .target-input:disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
    }

    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
        display: inline-block;
    }

    .toggle-switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #f44336;
        /* Red color for OFF state */
        border-radius: 34px;
        transition: 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        border-radius: 50%;
        transition: 0.4s;
    }

    input:checked+.slider {
        background-color: #4CAF50;
        /* Green color for ON state */
    }

    input:checked+.slider:before {
        transform: translateX(30px);
    }

    .slider::after {
        content: 'OFF';
        color: white;
        font-size: 12px;
        position: absolute;
        right: 10px;
        top: 7px;
    }

    input:checked+.slider::after {
        content: 'ON';
        left: 10px;
        right: auto;
    }

    .toast {
        padding: 10px;
        margin: 5px;
        border-radius: 4px;
        color: #fff;
        min-width: 200px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .toast-success {
        background-color: #28a745;
    }

    .toast-error {
        background-color: #e74c3c;
    }

    .is-invalid {
        border: 2px solid #e74c3c;
        background-color: #fcebea;
    }

    #toast-container {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
    }
          .content-wrapper{
    height: unset !important;
    min-height: unset !important;
}
</style>
<div id="toast-container" style="position: fixed;top: 0;"></div>
<?php $is_edit_mode = (isset($page_title) && $page_title == "Edit"); ?>

<div class="content-wrapper" style="min-height: 760.5px;">
    <section class="content">
        <div class="container mt-4">
            <?php if ($is_edit_mode): ?>
                <h3 class="box-title">Edit Organization Settings
                    <a href="<?= base_url('organization') ?>" class="pull-right btn btn-default  rounded btn-sm">
                        <i class="fa fa-angle-left"></i> <?= trans('back') ?>
                    </a>
                </h3>
            <?php else: ?>
                <h3 class="box-title">Organization Settings
                    <a href="<?= base_url('organization/edit') ?>" class="pull-right btn btn-info btn-sm rounded">
                        <i class="fa fa-edit"></i> Edit Settings
                    </a>
                </h3>
            <?php endif; ?>

            <div class="box mt-20">
                <div class="box-body">
                    <form id="orgSettingsForm" class="validate-form <?= $is_edit_mode ? '' : 'readonly-form' ?>" role="form">
                                                    <!-- Timezone Dropdown -->
                        <div class="col-md-6 form-group">
                            <label class="form-label">Select Timezone:</label>
                                <select name="time_zone" id="timezone" class="form-control single_select">
                                    <option value="">-- Select Timezone --</option>
                                    <?php foreach ($timezone as $tz): print_r($tz); ?>
                                        <option value="<?= htmlspecialchars($tz) ?>"><?= htmlspecialchars($tz) ?></option>
                                    <?php endforeach; ?>
                                </select>
                        </div>

               
                        <div class="row mt-5">

                            <!-- Screenshot Flag -->
                            <div class="col-md-6 form-group">

                                <label class="form-label">Screenshot Flag:</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="screenshot_flag" value="1" class="toggle-flag" data-target="screenshot_time_interval"
                                        <?= isset($settings['screenshot_flag']) && $settings['screenshot_flag'] ? 'checked' : '' ?>
                                        <?= $is_edit_mode ? '' : 'disabled' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>



                            <!-- Screenshot Interval -->
                            <div class="col-md-6 form-group">
                                <label class="form-label">Screenshot Interval (mins):</label>

                                <?php if ($is_edit_mode): ?>
                                    <select name="screenshot_time_interval" class="form-control target-input" id="screenshot_time_interval">
                                        <?php foreach ([1, 2, 5, 10] as $val): ?>
                                            <option value="<?= $val ?>" <?= (isset($settings['screenshot_time_interval']) && $settings['screenshot_time_interval'] == $val) ? 'selected' : '' ?>>
                                                <?= $val ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="text" class="form-control" value="<?= isset($settings['screenshot_time_interval']) ? $settings['screenshot_time_interval'] : '' ?>" readonly>
                                <?php endif; ?>
                            </div>


                            <!-- Webcam Flag -->
                            <div class="col-md-6 form-group">
                                <label class="form-label">Webcam Flag:</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="webcam_flag" value="1" class="toggle-flag" data-target="webcam_time_interval"
                                        <?= isset($settings['webcam_flag']) && $settings['webcam_flag'] ? 'checked' : '' ?>
                                        <?= $is_edit_mode ? '' : 'disabled' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <!-- Webcam Interval -->
                            <div class="col-md-6 form-group">
                                <label class="form-label">Webcam Interval (mins):</label>

                                <?php if ($is_edit_mode): ?>
                                    <select name="webcam_time_interval" class="form-control" id="webcam_time_interval">
                                        <?php foreach ([1, 2, 5, 10] as $val): ?>
                                            <option value="<?= $val ?>" <?= (isset($settings['webcam_time_interval']) && $settings['webcam_time_interval'] == $val) ? 'selected' : '' ?>>
                                                <?= $val ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="text" class="form-control" value="<?= isset($settings['webcam_time_interval']) ? $settings['webcam_time_interval'] : '' ?>" readonly>
                                <?php endif; ?>
                            </div>


                            <!-- Mouse Movement Flag -->
                            <div class="col-md-6 form-group">
                                <label class="form-label">Mouse Movement Flag:</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="mouse_move_flag" value="1" class="toggle-flag" data-target="mouse_move_threshold"
                                        <?= isset($settings['mouse_move_flag']) && $settings['mouse_move_flag'] ? 'checked' : '' ?>
                                        <?= $is_edit_mode ? '' : 'disabled' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <!-- Mouse Movement Threshold -->
                            <div class="col-md-6">
                                <label class="form-label">Mouse Movement Threshold:</label>
                                <input type="number" name="mouse_move_threshold" class="form-control" id="mouse_move_threshold"
                                    value="<?= isset($settings['mouse_move_threshold']) ? $settings['mouse_move_threshold'] : '' ?>"
                                    readonly />
                            </div>

                            <!-- Keystroke Flag -->
                            <div class="col-md-6 form-group">
                                <label class="form-label">Keystroke Flag:</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="key_stroke_flag" value="1" class="toggle-flag" data-target="key_stroke_threshold"
                                        <?= isset($settings['key_stroke_flag']) && $settings['key_stroke_flag'] ? 'checked' : '' ?>
                                        <?= $is_edit_mode ? '' : 'disabled' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <!-- Keystroke Threshold -->
                            <div class="col-md-6">
                                <label class="form-label">Keystroke Threshold:</label>
                                <input type="number" name="key_stroke_threshold" class="form-control" id="key_stroke_threshold"
                                    value="<?= isset($settings['key_stroke_threshold']) ? $settings['key_stroke_threshold'] : '' ?>"
                                    readonly/>
                            </div>

                            <!-- Idle Time Flag -->
                            <div class="col-md-6 form-group">
                                <label class="form-label">Idle Time Flag:</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="idle_time_flag" value="1" class="toggle-flag" data-target="timecards_time_interval"
                                        <?= isset($settings['idle_time_flag']) && $settings['idle_time_flag'] ? 'checked' : '' ?>
                                        <?= $is_edit_mode ? '' : 'disabled' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <!-- Timecards Interval -->
                            <div class="col-md-6">
                                <label class="form-label">Timecards Interval (mins):</label>
                                <input type="text" name="timecards_time_interval" class="form-control" id="timecards_time_interval"
                                    value="<?= isset($settings['timecards_time_interval']) ? $settings['timecards_time_interval'] : '' ?>"
                                    <?= $is_edit_mode ? '' : 'readonly' ?>>
                            </div>
                        </div>

                        <?php if ($is_edit_mode): ?>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-info">Save Settings</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Optional CSS for graying out form -->
<style>
    .readonly-form input[readonly],
    .readonly-form select:disabled,
    .readonly-form input:disabled {
        background-color: #f9f9f9;
        pointer-events: none;
        opacity: 0.8;
    }
</style>
<script>
    $(document).ready(function() {
        <?php if ($is_edit_mode): ?>
            $.ajax({
                url: "<?php echo base_url('admin/Organization_settings/get_all_timezones_list_for_dropdown') ?>",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Full response:', response); // Debug the full response

                    if (response.status === 'success' && response.data && response.data.length > 0) {
                        const timezoneSelect = $('#timezone');
                        const currentTimezone = "<?= isset($settings['timezone']) ? $settings['timezone'] : '' ?>";

                        // Clear any existing options except the first one
                        timezoneSelect.find('option:not(:first)').remove();

                        response.data.forEach(function(item) {
                            // Check the actual property names in your response
                            const timezoneValue = item.time_zone || item.value || item.id || item;
                            const timezoneLabel = item.time_zone || item.label || item.name || item;

                            const selected = timezoneValue === currentTimezone ? 'selected' : '';
                            timezoneSelect.append(`<option value="${timezoneValue}" ${selected}>${timezoneLabel}</option>`);
                        });
                    } else {
                        console.log('No data or error:', response.message);
                    }
                },
                error: function(xhr) {
                    console.error("Error fetching timezones:", xhr.responseText);
                }
            });
        <?php endif; ?>
    });
</script>


<script>
    $(document).ready(function() {
        function initializeToggleStates() {
            $('.toggle-flag').each(function() {
                const targetId = $(this).data('target');
                const targetInput = $('#' + targetId);
                const isChecked = $(this).is(':checked');
                targetInput.prop('disabled', !isChecked);
            });
        }

        $('.toggle-flag').change(function() {
            const targetId = $(this).data('target');
            const targetInput = $('#' + targetId);
            targetInput.prop('disabled', !$(this).is(':checked'));
        });

        initializeToggleStates();

        $('#orgSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let isValid = true;
            $('.is-invalid').removeClass('is-invalid');

            // Validate required fields
            $(this).find(':input').each(function() {
                if ($(this).attr('name') && $(this).val() === '') {
                    $(this).addClass('is-invalid');
                    isValid = false;
                }
            });

            // Validate Keystroke
            const keystrokeVal = parseInt($('#key_stroke_threshold').val());
            if (keystrokeVal > 40 || keystrokeVal <= 0) {
                $('#key_stroke_threshold').addClass('is-invalid');
                showToast("Keystroke threshold must be between 1 and 40", "error");
                isValid = false;
            }

            // Validate Mouse Movement
            const mouseVal = parseInt($('#mouse_move_threshold').val());
            if (mouseVal > 20 || mouseVal <= 0) {
                $('#mouse_move_threshold').addClass('is-invalid');
                showToast("Mouse movement threshold must be between 1 and 20", "error");
                isValid = false;
            }

            if (!isValid) return;

            const formData = {};
            $(this).find(':input').each(function() {
                if (this.name) {
                    formData[this.name] = $(this).hasClass('toggle-flag') ? ($(this).is(':checked') ? 1 : 0) : $(this).val();
                }
            });

            $.ajax({
                url: "<?php echo base_url('admin/Organization_settings/save_org_settings') ?>",
                method: "POST",
                data: formData,
                success: function(response) {
                    swal("Success!", response, "success");
                    window.location.href = "<?= base_url('organization') ?>";
                },
                error: function(res) {
                    const errorMsg = res.responseJSON?.message || "Something went wrong.";
                    swal("Error!", errorMsg, "error");
                }
            });
        });
    });
</script>