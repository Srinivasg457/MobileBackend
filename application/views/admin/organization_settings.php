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
<div class="content-wrapper" style="min-height: 760.5px;">
    <section class="content">
        <div class="container mt-4">
            <h3>Organization Settings</h3>
            <div class="box mt-5">
                <div class="box-body">
            <form id="orgSettingsForm">
                <div class="row mt-5">
                    <!-- Screenshot Flag -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Screenshot Flag:</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="screenshot_flag" value="1" class="toggle-flag" data-target="screenshot_time_interval" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Screenshot Interval -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Screenshot Interval (mins):</label>
                        <select name="screenshot_time_interval" class="form-control target-input single_select" id="screenshot_time_interval">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option selected value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <!-- Webcam Flag -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Webcam Flag:</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="webcam_flag" value="1" class="toggle-flag" data-target="webcam_time_interval" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Webcam Interval -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Webcam Interval (mins):</label>
                        <select name="webcam_time_interval" class="form-control single_select" id="webcam_time_interval">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option selected value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <!-- Mouse Movement Flag -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Mouse Movement Flag:</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="mouse_move_flag" value="1" class="toggle-flag" data-target="mouse_move_threshold" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Mouse Movement Threshold -->
                    <div class="col-md-6">
                        <label class="form-label">Mouse Movement Threshold:</label>
                        <input type="number" name="mouse_move_threshold" class="form-control" id="mouse_move_threshold" value="20" placeholder="" />
                    </div>

                    <!-- Keystroke Flag -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Keystroke Flag:</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="key_stroke_flag" value="1" class="toggle-flag" data-target="key_stroke_threshold" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Keystroke Threshold -->
                    <div class="col-md-6">
                        <label class="form-label">Keystroke Threshold:</label>
                        <input type="number" name="key_stroke_threshold" class="form-control" id="key_stroke_threshold" value="40" placeholder="" />
                    </div>

                    <!-- Idle Time Flag -->
                    <div class="col-md-6 form-group">
                        <label class="form-label">Idle Time Flag:</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="idle_time_flag" value="1" class="toggle-flag" data-target="timecards_time_interval" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Timecards Interval -->
                    <div class="col-md-6">
                        <label>Timecards Interval (mins):</label>
                        <!-- <select name="timecards_time_interval" class="form-control interval-field">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                        </select> -->
                        <input type="text" name="timecards_time_interval" class="form-control" value="1" readonly>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-info">Save Settings</button>
                </div>
            </form>
        </div>
            </div>
        </div>
    </section>
</div>

<script>
    function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
    }

    $(document).ready(function() {
        // Initialize toggle flags
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
            const isChecked = $(this).is(':checked');
            targetInput.prop('disabled', !isChecked);
        });

        initializeToggleStates();

        $('#orgSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let isValid = true;
            $('.is-invalid').removeClass('is-invalid'); // Reset errors

            // Validate required fields
            $(this).find(':input').each(function() {
                const $input = $(this);
                if ($input.attr('name') && $input.val() === '') {
                    $input.addClass('is-invalid');
                    isValid = false;
                }
            });

            // Validate Keystroke
            const keystrokeVal = parseInt($('#key_stroke_threshold').val());
            if (keystrokeVal > 40 || 0 >= keystrokeVal) {
                $('#key_stroke_threshold').addClass('is-invalid');
                showToast("Keystroke threshold must be between 1 and 40", "error");
                isValid = false;
            }

            // Validate Mouse Movement
            const mouseVal = parseInt($('#mouse_move_threshold').val());
            if (mouseVal > 20 || 0 >= mouseVal) {
                $('#mouse_move_threshold').addClass('is-invalid');
                showToast("Mouse movement threshold must be between 1 and 20", "error");
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Prepare data for submission
            const formData = {};
            $(this).find(':input').each(function() {
                if (this.name) {
                    if ($(this).hasClass('toggle-flag')) {
                        formData[this.name] = $(this).is(':checked') ? 1 : 0;
                    } else {
                        formData[this.name] = $(this).val();
                    }
                }
            });

            $.ajax({
                url: "admin/Organization_settings/save_org_settings",
                method: "POST",
                data: formData,
                success: function(response) {
                    console.log(response);
                    swal("Success!", "Employee Settings saved successfully.", "success");
                },
                error: function(res) {
                    const errorMsg = res.responseJSON?.message || "Something went wrong.";
                    swal("Error!", errorMsg, "error");
                }
            });
        });
    });
</script>