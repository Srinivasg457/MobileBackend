<style>
    [type=radio]:not(:checked),
    [type=radio]:checked {
        position: static;
        opacity: 1;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <h3><i class="bi bi-gear"></i> Organization Exception Settings</h3>

            <div class="form-group">
                <label for="employeeSelect">Select Employee:</label>
                <select id="employeeSelect" class="form-control">
                    <option value="">-- Select --</option>
                    <!-- Dynamically fill this with employee ID and name -->
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>"><?= $emp['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <form id="orgExceptionForm">
                <div class="row">
                    <!-- Screenshot Flag -->
                    <div class="col-md-6">
                        <label>Screenshot Flag:</label><br>
                        <label><input type="radio" name="screenshot_flag" value="1"> Enable</label>
                        <label class="ml-3"><input type="radio" name="screenshot_flag" value="0"> Disable</label>
                    </div>

                    <!-- Screenshot Interval -->
                    <div class="col-md-6">
                        <label>Screenshot Interval (mins):</label>
                        <input type="number" name="screenshot_time_interval" class="form-control" />
                    </div>

                    <!-- Webcam Flag -->
                    <div class="col-md-6">
                        <label>Webcam Flag:</label><br>
                        <label><input type="radio" name="webcam_flag" value="1"> Enable</label>
                        <label class="ml-3"><input type="radio" name="webcam_flag" value="0"> Disable</label>
                    </div>

                    <!-- Webcam Interval -->
                    <div class="col-md-6">
                        <label>Webcam Interval (mins):</label>
                        <input type="number" name="webcam_time_interval" class="form-control" />
                    </div>

                    <!-- Mouse Movement -->
                    <div class="col-md-6">
                        <label>Mouse Move Flag:</label><br>
                        <label><input type="radio" name="mouse_move_flag" value="1"> Enable</label>
                        <label class="ml-3"><input type="radio" name="mouse_move_flag" value="0"> Disable</label>
                    </div>

                    <div class="col-md-6">
                        <label>Mouse Move Threshold:</label>
                        <input type="number" name="mouse_move_threshold" class="form-control" />
                    </div>

                    <!-- Keystroke -->
                    <div class="col-md-6">
                        <label>Keystroke Flag:</label><br>
                        <label><input type="radio" name="key_stroke_flag" value="1"> Enable</label>
                        <label class="ml-3"><input type="radio" name="key_stroke_flag" value="0"> Disable</label>
                    </div>

                    <div class="col-md-6">
                        <label>Keystroke Threshold:</label>
                        <input type="number" name="key_stroke_threshold" class="form-control" />
                    </div>

                    <!-- Idle Time -->
                    <div class="col-md-6">
                        <label>Idle Time Flag:</label><br>
                        <label><input type="radio" name="idle_time_flag" value="1"> Enable</label>
                        <label class="ml-3"><input type="radio" name="idle_time_flag" value="0"> Disable</label>
                    </div>

                    <div class="col-md-6">
                        <label>Timecards Interval (mins):</label>
                        <input type="number" name="timecards_time_interval" class="form-control" />
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
    let currentEmployeeId = null;

    $('#employeeSelect').on('change', function() {
        const employeeId = $(this).val();
        currentEmployeeId = employeeId;

        if (!employeeId) return;

        $.ajax({
            url: `<?= base_url('admin/organization_settings/get_org_exception_settings/') ?>${employeeId}`,
            method: 'GET',
            success: function(data) {
                const settings = JSON.parse(data);
                if (settings.error) {
                    $('#orgExceptionForm')[0].reset();
                } else {
                    for (let key in settings) {
                        const field = $(`[name="${key}"]`);
                        if (field.attr('type') === 'radio') {
                            $(`[name="${key}"][value="${settings[key]}"]`).prop('checked', true);
                        } else {
                            field.val(settings[key]);
                        }
                    }
                }
            }
        });
    });

    $('#orgExceptionForm').on('submit', function(e) {
        e.preventDefault();

        if (!currentEmployeeId) {
            alert('Please select an employee first.');
            return;
        }

        $.ajax({
            url: `<?= base_url('admin/organization_settings/save_org_exception_settings/') ?>${currentEmployeeId}`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                alert(res);
            },
            error: function() {
                alert('Something went wrong while saving settings.');
            }
        });
    });
</script>