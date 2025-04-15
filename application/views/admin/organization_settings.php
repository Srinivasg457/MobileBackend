<style>
    [type=radio]:not(:checked),
    [type=radio]:checked {
        position: static;
        opacity: 1;
    }
</style>
<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3">
    <!-- Toast will be dynamically added here -->
</div>
<div class="content-wrapper" style="min-height: 760.5px;">
    <section class="content">
        <div class="container mt-4">
            <h3>Organization Settings</h3>
            <form id="orgSettingsForm">
                <div class="row mt-5">
                    <!-- Screenshot Flag -->
                    <div class="col-md-6">
                        <label><strong>Screenshot Flag:</strong></label><br>
                        <label><input type="radio" name="screenshot_flag" value="1" checked> Enable</label>
                        <label class="ml-3"><input type="radio" name="screenshot_flag" value="0"> Disable</label>
                    </div>

                    <!-- Screenshot Interval -->
                    <div class="col-md-6">
                        <label><strong>Screenshot Interval (mins):</strong></label>
                        <select name="screenshot_time_interval" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option selected value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <!-- Webcam Flag -->
                    <div class="col-md-6">
                        <label><strong>Webcam Flag:</strong></label><br>
                        <label><input type="radio" name="webcam_flag" value="1" checked> Enable</label>
                        <label class="ml-3"><input type="radio" name="webcam_flag" value="0"> Disable</label>
                    </div>

                    <!-- Webcam Interval -->
                    <div class="col-md-6">
                        <label><strong>Webcam Interval (mins):</strong></label>
                        <select name="webcam_time_interval" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option selected value="5">5</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <!-- Mouse Move Flag -->
                    <div class="col-md-6">
                        <label><strong>Mouse Movement Flag:</strong></label><br>
                        <label><input type="radio" name="mouse_move_flag" value="1" checked> Enable</label>
                        <label class="ml-3"><input type="radio" name="mouse_move_flag" value="0"> Disable</label>
                    </div>

                    <!-- Mouse Move Threshold -->
                    <div class="col-md-6">
                        <label><strong>Mouse Move Threshold:</strong></label>
                        <input type="number" name="mouse_move_threshold" class="form-control" value="20" />
                    </div>

                    <!-- Key Stroke Flag -->
                    <div class="col-md-6">
                        <label><strong>Key Stroke Flag:</strong></label><br>
                        <label><input type="radio" name="key_stroke_flag" value="1" checked> Enable</label>
                        <label class="ml-3"><input type="radio" name="key_stroke_flag" value="0"> Disable</label>
                    </div>

                    <!-- Key Stroke Threshold -->
                    <div class="col-md-6">
                        <label><strong>Key Stroke Threshold:</strong></label>
                        <input type="number" name="key_stroke_threshold" class="form-control" value="40" />
                    </div>

                    <!-- Idle Time Flag -->
                    <div class="col-md-6">
                        <label><strong>Idle Time Flag:</strong></label><br>
                        <label><input type="radio" name="idle_time_flag" value="1" checked> Enable</label>
                        <label class="ml-3"><input type="radio" name="idle_time_flag" value="0"> Disable</label>
                    </div>

                    <!-- Timecards Interval -->
                    <div class="col-md-6">
                        <label><strong>Timecards Interval (mins):</strong></label>
                        <select name="timecards_time_interval" class="form-control">
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
    $("#orgSettingsForm").on("submit", function(e) {
        e.preventDefault();
        console.log($(this).serialize());

        $.ajax({
            url: `admin/Organization_settings/save_org_settings`,
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                alert(res);
            },
            error: function(res) {
                alert(res);
                console.log(res);

            }
        });
    });
</script>