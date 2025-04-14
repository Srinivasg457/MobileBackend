<div class="content-wrapper" style="overflow:hidden">

    <section class="content">
        <div class="container mt-4">
            <h3>Organization Settings</h3>
            <form id="orgSettingsForm">
                <div class="row">
                    <div class="col-md-4">
                        <label>Screenshot Flag:</label>
                        <select name="screenshot_flag" class="form-control">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Screenshot Interval (mins):</label>
                        <input type="number" name="screenshot_time_interval" class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <label>Webcam Flag:</label>
                        <select name="webcam_flag" class="form-control">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Webcam Interval (mins):</label>
                        <input type="number" name="webcam_time_interval" class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <label>Mouse Movement Flag:</label>
                        <select name="mouse_move_flag" class="form-control">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Mouse Move Threshold:</label>
                        <input type="number" name="mouse_move_threshold" class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <label>Key Stroke Flag:</label>
                        <select name="key_stroke_flag" class="form-control">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Key Stroke Threshold:</label>
                        <input type="number" name="key_stroke_threshold" class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <label>Idle Time Flag:</label>
                        <select name="idle_time_flag" class="form-control">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Timecards Interval (mins):</label>
                        <input type="number" name="timecards_time_interval" class="form-control" />
                    </div>
                </div>

                <div class="mt-3">
                    <input type="hidden" name="user_id" id="user_id" value="1" />
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>

            <hr class="mt-5 mb-4">
            <h3>Load Settings</h3>
            <button class="btn btn-info" onclick="loadSettings()">Load Organization Settings</button>
        </div>

    </section>
</div>
<script>
    $("#orgSettingsForm").on("submit", function(e) {
        e.preventDefault();

        const userId = $("#user_id").val();

        $.ajax({
            url: `<?= base_url('admin/organization_settings/save_org_settings/') ?>${userId}`,
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                alert(res);
            },
            error: function() {
                alert("Something went wrong while saving settings.");
            }
        });
    });

    function loadSettings() {
        const userId = $("#user_id").val();

        $.get(`<?= base_url('admin/organization_settings/get_org_settings/') ?>${userId}`, function(data) {
            const settings = JSON.parse(data);
            if (settings.error) {
                alert(settings.error);
            } else {
                $.each(settings, function(key, value) {
                    $(`[name="${key}"]`).val(value);
                });
            }
        });
    }
</script>