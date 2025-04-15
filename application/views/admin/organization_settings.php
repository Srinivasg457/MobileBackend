<style>
    [type=radio]:not(:checked),
    [type=radio]:checked {
        position: static;
        opacity: 1;
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

    input:checked + .slider {
        background-color: #4CAF50;
    }

    input:checked + .slider:before {
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

    input:checked + .slider::after {
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
    background-color: #f44336; /* Red color for OFF state */
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

input:checked + .slider {
    background-color: #4CAF50; /* Green color for ON state */
}

input:checked + .slider:before {
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

input:checked + .slider::after {
    content: 'ON';
    left: 10px;
    right: auto;
}
</style>

<div class="content-wrapper" style="min-height: 760.5px;">
    <section class="content">
        <div class="container mt-4">
            <h3>Organization Settings</h3>
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
                        <select name="screenshot_time_interval" class="form-control target-input" id="screenshot_time_interval">
                            <option value=""></option>
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
                        <select name="webcam_time_interval" class="form-control target-input" id="webcam_time_interval">
                            <option value=""></option>
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
                    <div class="col-md-6 form-group">
                        <label class="form-label">Mouse Movement Threshold:</label>
                        <input type="number" name="mouse_move_threshold" class="form-control target-input" id="mouse_move_threshold" value="20" placeholder="" />
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
                    <div class="col-md-6 form-group">
                        <label class="form-label">Keystroke Threshold:</label>
                        <input type="number" name="key_stroke_threshold" class="form-control target-input" id="key_stroke_threshold" value="40" placeholder="" />
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
                    <div class="col-md-6 form-group">
                        <label class="form-label">Timecards Interval (mins):</label>
                        <select name="timecards_time_interval" class="form-control target-input" id="timecards_time_interval">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option selected value="5">5</option>
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

<script>
    $(document).ready(function() {
        // Initialize all toggle states
        function initializeToggleStates() {
            $('.toggle-flag').each(function() {
                const targetId = $(this).data('target');
                const targetInput = $('#' + targetId);
                
                if (!$(this).is(':checked')) {
                    // Store current value and clear input
                    targetInput.data('previous-value', targetInput.val());
                    
                    if (targetInput.is('select')) {
                        targetInput.val('');
                    } else {
                        targetInput.val('');
                    }
                    targetInput.prop('disabled', true);
                }
            });
        }

        // Handle toggle changes
        $('.toggle-flag').change(function() {
            const targetId = $(this).data('target');
            const targetInput = $('#' + targetId);
            const isChecked = $(this).is(':checked');

            targetInput.prop('disabled', !isChecked);

            if (isChecked) {
                // Restore previous value if it exists
                if (targetInput.data('previous-value')) {
                    targetInput.val(targetInput.data('previous-value'));
                }
            } else {
                // Store current value and clear input
                targetInput.data('previous-value', targetInput.val());
                targetInput.val('');
            }
        });

        // Initialize on page load
        initializeToggleStates();

        // Form submission
        $('#orgSettingsForm').on('submit', function(e) {
            e.preventDefault();
            
            // Prepare data - exclude disabled fields and empty values
            const formData = {};
            $(this).find(':input').not(':disabled').each(function() {
                if (this.name && $(this).val() !== '') {
                    formData[this.name] = $(this).val();
                }
            });

            $.ajax({
                url: "admin/Organization_settings/save_org_settings",
                method: "POST",
                data: formData,
                success: function(response) {
                    alert('Settings saved successfully');
                },
                error: function(xhr) {
                    alert('Error saving settings: ' + xhr.responseText);
                }
            });
        });
    });
</script>