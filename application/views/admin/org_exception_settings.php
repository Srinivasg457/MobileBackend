<!-- Add to your <head> or <style> block -->
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 28px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e74c3c;
        transition: .4s;
        border-radius: 34px;
        text-align: center;
        line-height: 28px;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #28a745;
    }

    input:checked+.slider:before {
        transform: translateX(32px);
    }

    .switch input:not(:checked)+.slider::after {
        content: "OFF";
        position: absolute;
        right: 10px;
    }

    .switch input:checked+.slider::after {
        content: "ON";
        position: absolute;
        left: 10px;
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
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <h3>Employee Settings</h3>

            <div class="form-group">
                <label for="employeeSelect">Select Employee:</label>
                <select id="employeeSelect" class="form-control"></select>
            </div>

            <form id="orgExceptionForm">
                <div class="row" style="max-width: 1000px;">
                    <!-- Screenshot -->
                    <div class="col-md-6">
                        <label>Screenshot Flag:</label><br>
                        <label class="switch">
                            <input type="checkbox" name="screenshot_flag" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label>Screenshot Interval (mins):</label>
                        <select name="screenshot_time_interval" class="form-control interval-field">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <!-- Webcam -->
                    <div class="col-md-6">
                        <label>Webcam Flag:</label><br>
                        <label class="switch">
                            <input type="checkbox" name="webcam_flag" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label>Webcam Interval (mins):</label>
                        <select name="webcam_time_interval" class="form-control interval-field">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <!-- Mouse Move -->
                    <div class="col-md-6">
                        <label>Mouse Move Flag:</label><br>
                        <label class="switch">
                            <input type="checkbox" name="mouse_move_flag" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label>Mouse Move Threshold:</label>
                        <input type="number" name="mouse_move_threshold" class="form-control" value="20" />
                    </div>

                    <!-- Keystroke -->
                    <div class="col-md-6">
                        <label>Keystroke Flag:</label><br>
                        <label class="switch">
                            <input type="checkbox" name="key_stroke_flag" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label>Keystroke Threshold:</label>
                        <input type="number" name="key_stroke_threshold" class="form-control" value="40" />
                    </div>

                    <!-- Idle Time -->
                    <div class="col-md-6">
                        <label>Idle Time Flag:</label><br>
                        <label class="switch">
                            <input type="checkbox" name="idle_time_flag" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label>Timecards Interval (mins):</label>
                        <select name="timecards_time_interval" class="form-control interval-field">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </section>
</div>

<div id="toast-container" style="position: fixed;top: 0;"></div>

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
                if (target.is('select')) {
                    const firstValue = target.find('option:first').next().val();
                    target.val(firstValue);
                }
            } else {
                target.prop('disabled', true).val('');
            }
        }
    }

    function validateThresholds() {
        let isValid = true;
        const limits = {
            mouse_move_threshold: 20,
            key_stroke_threshold: 40
        };

        for (const [fieldName, max] of Object.entries(limits)) {
            const field = $(`[name="${fieldName}"]`);
            const value = parseInt(field.val(), 10);
            field.removeClass('is-invalid');

            if (!field.prop('disabled') && (isNaN(value) || value > max || value < 0)) {
                field.addClass('is-invalid');
                showToast(`Limit exceeded: ${fieldName.replace('_', ' ')} max is ${max}`, 'error');
                isValid = false;
            }
        }

        return isValid;
    }


    function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
    }

    $(document).ready(function() {
        // Load employees
        $.ajax({
            url: "<?= base_url('/admin/ScreenshotController/list_employees_by_user') ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                let employeeSelect = $('#employeeSelect');
                employeeSelect.empty().append(`<option value="">-- Select --</option>`);

                if (response.status === "success" && response.employees.length > 0) {
                    response.employees.forEach(emp => {
                        employeeSelect.append(`<option value="${emp.id}">${emp.name} (${emp.email})</option>`);
                    });
                } else {
                    showToast("No employees found for this user.", "error");
                }
            },
            error: function() {
                showToast("Failed to fetch employees.", "error");
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

            // If no employee selected, reset to default values
            if (!employeeId) {
                $('#orgExceptionForm')[0].reset();

                $('#orgExceptionForm input[type="checkbox"]').prop('checked', true).trigger('change');
                $('#orgExceptionForm select.interval-field').val('1').prop('disabled', false);
                $('[name="mouse_move_threshold"]').val(40).prop('disabled', false);
                $('[name="key_stroke_threshold"]').val(20).prop('disabled', false);

                return;
            }

            $.ajax({
                url: `<?= base_url('admin/organization_settings/get_org_exception_settings/') ?>${employeeId}`,
                method: 'GET',
                success: function(data) {
                    const settings = JSON.parse(data);

                    // Reset form
                    $('#orgExceptionForm')[0].reset();

                    // Set all checkboxes to true and enable all dependent fields
                    $('#orgExceptionForm input[type="checkbox"]').prop('checked', true).trigger('change');
                    $('#orgExceptionForm select.interval-field').val('1').prop('disabled', false);
                    $('[name="mouse_move_threshold"]').val(20).prop('disabled', false);
                    $('[name="key_stroke_threshold"]').val(40).prop('disabled', false);

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
                                        relatedField.prop('disabled', true).val('');
                                    }
                                }

                            } else {
                                if (!field.prop('disabled')) {
                                    field.val(value);
                                }
                            }
                        }
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
            dataObj['screenshot_time_interval'] = $('[name="screenshot_time_interval"]').val() || '5';
            dataObj['webcam_time_interval'] = $('[name="webcam_time_interval"]').val() || '5';
            dataObj['mouse_move_threshold'] = $('[name="mouse_move_threshold"]').val() || '20';
            dataObj['key_stroke_threshold'] = $('[name="key_stroke_threshold"]').val() || '40';
            dataObj['timecards_time_interval'] = $('[name="timecards_time_interval"]').val() || '5';

            console.log(currentEmployeeId);
            console.log(dataObj);

            $.ajax({
                url: `<?= base_url('admin/Organization_settings/save_org_exception_settings/') ?>${currentEmployeeId}`,
                method: 'POST',
                data: dataObj,
                success: function(res) {
                    showToast(res, 'success');
                },
                error: function(res) {
                    console.log(res);
                    showToast('Something went wrong while saving settings.', 'error');
                }
            });
        });


    });
</script>