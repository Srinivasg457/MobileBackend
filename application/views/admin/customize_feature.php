<!-- Custom Plan Modal -->
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="box add_area">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Customize Feature" ?> </h3>


                            <div class="box-tools pull-right">
                                <a href="<?php echo base_url('admin/users') ?>" class="pull-right btn btn-secondary btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
                            </div>
                        </div>

                        <div class="box-body my-20 pl-20">
                            <form id="customPlanForm" method="post" action="<?= base_url('admin/Custom_plan/save_customize_features') ?>">

                                <div class="row mb-20 pl-20">
                                    <!-- <div class="col-sm-8">
                                        <div class="row"> -->

                                            <?php
                                            // Map feature names to DB keys
                                            $feature_map = [
                                                'Activity Log' => 'activity_log',
                                                'Time Cards' => 'time_cards',
                                                'Notification' => 'notification',
                                                'Organization Settings' => 'organization_settings',
                                                'Employee Settings' => 'employee_settings',
                                                'Screenshots' => 'screenshots',
                                                'Webcam Screenshots' => 'webcam_screenshots',
                                                'Live Monitoring' => 'live_monitoring',
                                                'Time Approval' => 'time_approval',
                                                'No of Employees' => 'no_of_employees',
                                                'Application Usage' => 'application_usage',
                                            ];

                                            foreach ($features as $feature):
                                                // get mapped key name
                                                $key = isset($feature_map[$feature->name]) ? $feature_map[$feature->name] : null;
                                                if (!$key) continue; // skip if not found in map

                                                // use key to build field names
                                                $flag_key = $key . '_flag';
                                                $feature_key = $key . '_feature';

                                                // fetch from $current_feature (the org settings object)
                                                $flag_value = isset($current_feature->$flag_key) ? $current_feature->$flag_key : 0;
                                                $feature_value = isset($current_feature->$feature_key) ? $current_feature->$feature_key : 'basic';
                                            ?>
                                                <div class="col-md-4 form-group">
                                                    <label class="control-label"><?= $feature->name ?>:</label>
                                                    <div>
                                                        <label class="toggle-switch">
                                                            <input type="checkbox"
                                                                class="toggle-flag"
                                                                name="features[<?= $feature->id ?>][flag]"
                                                                value="1"
                                                                data-toggle="toggle"
                                                                data-onstyle="info"
                                                                data-width="100"
                                                                <?php if (!empty($current_feature) && isset($page_title) && $page_title == "Customize Feature"): ?>
                                                                <?= $flag_value == 1 ? 'checked' : ''; ?>
                                                                <?php else: ?>
                                                                checked
                                                                <?php endif; ?>>
                                                        </label>

                                                    </div>
                                                </div>

                                                <div class="col-md-8 mb-5 form-group">
                                                    <label class="control-label">Select:</label>
                                                    <select name="features[<?= $feature->id ?>][option]" class="form-control single_select"
                                                        <?php if (!empty($current_feature) && isset($page_title) && $page_title == "Customize Feature"): ?>
                                                        <?= $flag_value == 0 ? 'disabled' : ''; ?>
                                                        <?php else: ?>
                                                        <?php endif; ?>>
                                                        <option value="basic" <?= $feature_value == 'basic' ? 'selected' : '' ?>><?= $feature->basic ?></option>
                                                        <option value="standard" <?= $feature_value == 'standard' ? 'selected' : '' ?>><?= $feature->standard ?></option>
                                                        <option value="premium" <?= $feature_value == 'premium' ? 'selected' : '' ?>><?= $feature->premium ?></option>
                                                    </select>
                                                </div>
                                            <?php endforeach; ?>
                                        <!-- </div>
                                    </div> -->
                                <!-- </div> -->
                                <!-- <div class="row mb-20 pl-20"> -->
                                    <div class="col-sm-12">
                                        <input type="hidden" name="id" value="<?php echo html_escape($user['0']['id']); ?>">
                                        <!-- csrf token -->
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                        <button type="submit" class="btn btn-info pull-left"><?php echo trans('save-changes') ?></button>
                                    </div>
                                <!-- </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
            </div>
    </section>
</div>

<script>
    $(document).on('change', '.toggle-flag', function() {
        $(this).closest('.row').find('select').prop('disabled', !this.checked);
    });
</script>