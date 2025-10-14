<div class="content-wrapper">
    <style>
        #customPlanBtn {
            font-weight: 600;
        }

        .custom_plan_div:hover {
            background-color: #0FB783;
            color: white;
        }

        .toggle-switch {
            >.toggle {
                width: 90px !important;
                height: 35px !important;
            }
        }
    </style>
    <!-- Main content -->
    <section class="content">

        <div class="container">
            <div class="row">
                <div class="box add_area d-block">
                    <div class="box-header with-border">
                        <?php
                        if (isset($page_title) && $page_title == "Verify Payment"): ?>
                            <h3 class="box-title"><?php echo "Verify Payment" ?> </h3>
                        <?php else: ?>
                            <h3 class="box-title"><?php echo trans('create-new') ?> </h3>
                        <?php endif; ?>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('admin/users') ?>" class="text-right btn btn-secondary  btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
                        </div>
                    </div>

                    <div class="box-body pl-0">
                        <?php if (isset($page_title) && $page_title == "Verify Payment"): ?>
                            <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/custom_plan/update') ?>" role="form">
                            <?php else: ?>
                                <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/custom_plan/add') ?>" role="form">
                                <?php endif; ?>
                                <div class="box-body">

                                    <div class="form-group">
                                        <label><?php echo trans('name') ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required name="name" value="<?php echo html_escape($user[0]['name']); ?>"
                                            <?php if (isset($page_title) && $page_title == "Verify Payment"): ?>Disabled<?php endif; ?>>
                                    </div>

                                    <div class="form-group">
                                        <label><?php echo trans('email') ?> <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" required name="email" value="<?php echo html_escape($user[0]['email']); ?>"
                                            <?php if (isset($page_title) && $page_title == "Verify Payment"): ?>Disabled<?php endif; ?>>
                                    </div>
                                    <?php if (isset($page_title) && $page_title == "Verify Payment"): ?>
                                    <?php else: ?>
                                        <div class="form-group">
                                            <label><?php echo trans('password') ?> <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" value="<?php echo html_escape($user[0]['password']); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label><?php echo "Plan Price" ?> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="amount" value="<?php echo html_escape($payment->amount); ?>">
                                    </div>

                                    <div class="form-group mb-4">
                                        <label><?php echo trans('plan') ?> <span class="text-danger">*</span></label>
                                        <input type="hidden" name="package" value="5">
                                        <!-- <input type="text" class="form-control" name="package_name" value="Customized Plan" readonly> -->

                                        <div class="input-group">
                                            <input type="text" name="package_name" value="Customized Plan" class=" form-control" readonly style="cursor:pointer;">
                                            <div class="input-group-addon custom_plan_div" style="cursor:pointer;" data-toggle="modal" data-target="#customPlanModal"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Customize Features">
                                                <span type="button" class="" id="customPlanBtn">
                                                    <i class="fa fa-puzzle-piece"></i> <?php echo 'Customize Features' ?>
                                                </span>
                                                <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#customPlanModal">
                                                <i class="fa fa-puzzle-piece"></i> Customize Features
                                            </button> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Custom Plan Button -->
                                    <!-- <div class="form-group mb-4 text-right">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="customPlanBtn">
                                        <i class="fa fa-puzzle-piece"></i> <?php echo 'Customize Features' ?>
                                    </button>
                                </div> -->
                                    <div class="form-group mb-4">
                                        <label><?php echo trans('subscription-type') ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="billing_type" required>
                                            <option value=""><?php echo trans('select') ?></option>
                                            <option <?php if ('monthly' == $payment->billing_type) echo "selected"; ?> value="monthly"><?php echo trans('monthly') ?></option>
                                            <option <?php if ('yearly' == $payment->billing_type) echo "selected"; ?> value="yearly"><?php echo trans('yearly') ?></option>
                                            <?php if (settings()->enable_lifetime == 1): ?>
                                                <option <?php if ('lifetime' == $payment->billing_type) echo "selected"; ?> value="lifetime"><?php echo trans('lifetime') ?></option>
                                            <?php endif ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label><?php echo trans('payment-status') ?></label>
                                        <select class="form-control" name="payment_status" required>
                                            <option value=""><?php echo trans('select') ?></option>
                                            <option <?php if ($payment->status == 'verified') echo "selected"; ?> value="verified"><?php echo trans('verified') ?></option>
                                            <option <?php if ($payment->status == 'pending') echo "selected"; ?> value="pending"><?php echo trans('pending') ?></option>
                                        </select>
                                    </div>
                                    <?php if (isset($page_title) && $page_title == "Verify Payment"): ?>
                                    <?php else: ?>
                                        <div class="form-group">
                                            <label><?php echo trans('country') ?></label>
                                            <select class="selectfield textfield--grey single_select col-sm-12" required name="country" id="country" style="width: 100%">
                                                <option value=""><?php echo trans('select') ?></option>
                                                <?php foreach ($countries as $country): ?>
                                                    <option value="<?php echo html_escape($country->id); ?>" <?php if (isset($user[0]['country']) && $user[0]['country'] == $country->id) echo "selected"; ?>>
                                                        <?php echo html_escape($country->name); ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Select Timezone:</label>
                                            <select name="time_zone" id="timezone_select" required class="selectfield textfield--grey single_select col-sm-12 wd-100">
                                                <option value="<?php echo isset($user[0]['timezone']) ? $user[0]['timezone'] : ''; ?>">
                                                    <?php echo isset($user[0]['timezone']) ? $user[0]['timezone'] : 'Select'; ?>
                                                </option>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group clearfix">
                                        <label><?php echo trans('status') ?></label><br>
                                        <div class="icheck-primary radio radio-inline d-inline mr-4 mt-2">
                                            <input type="radio" id="radioPrimary1" value="1" name="status" <?php if (isset($user[0]['status']) && $user[0]['status'] == 1) echo "checked"; ?>>
                                            <label for="radioPrimary1"><?php echo trans('active') ?></label>
                                        </div>

                                        <div class="icheck-primary radio radio-inline d-inline">
                                            <input type="radio" id="radioPrimary2" value="2" name="status" <?php if (isset($user[0]['status']) && $user[0]['status'] == 2) echo "checked"; ?>>
                                            <label for="radioPrimary2"><?php echo trans('inactive') ?></label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-20 pl-20">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="id" value="<?php echo html_escape($user['0']['id']); ?>">
                                        <!-- csrf token -->
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-info pull-left"><?php echo trans('save') ?></button>
                                    </div>
                                </div>
                                <!-- Custom Plan Modal -->
                                <div class="modal fade" id="customPlanModal" tabindex="-1" role="dialog" aria-labelledby="customPlanModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content" style="margin-top: 10% !important">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="customPlanModalLabel">Customize Features & Options</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
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
                                                                        <?php if (isset($page_title) && $page_title == "Verify Payment"): ?>
                                                                        <?= $flag_value == 1 ? 'checked' : ''; ?>
                                                                        <?php else: ?>
                                                                        checked
                                                                        <?php endif; ?>>
                                                                </label>

                                                            </div>
                                                        </div>

                                                        <div class="col-md-8 mb-5 form-group">
                                                            <label class="control-label">Select:</label>
                                                            <select name="features[<?= $feature->id ?>][option]" class="form-control single_select" <?= $flag_value == 1 ? '' : 'disabled'; ?>>
                                                                <option value="basic" <?= $feature_value == 'basic' ? 'selected' : '' ?>><?= $feature->basic ?></option>
                                                                <option value="standard" <?= $feature_value == 'standard' ? 'selected' : '' ?>><?= $feature->standard ?></option>
                                                                <option value="premium" <?= $feature_value == 'premium' ? 'selected' : '' ?>><?= $feature->premium ?></option>
                                                            </select>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>


                                            <div class="modal-footer">
                                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Save Features</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {

        // Initialize toggle selects
        // $('.toggle-flag').each(function() {
        //     var $toggle = $(this);
        //     var $select = $toggle.closest('.col-md-4').next('.col-md-8').find('select');

        //     // Set initial state
        //     if (!$toggle.is(':checked')) {
        //         $select.prop('disabled', true).val('');
        //         $toggle.data('value', 0);
        //     } else {
        //         $toggle.data('value', 1);
        //     }
        // });

        // On toggle change
        $('.toggle-flag').change(function() {
            var $toggle = $(this);
            var $select = $toggle.closest('.col-md-4').next('.col-md-8').find('select');

            if ($toggle.is(':checked')) {
                $select.prop('disabled', false);
                $toggle.data('value', 1);
            } else {
                $select.prop('disabled', true).val('');
                $toggle.data('value', 0);
            }
        });

        // On form submit, replace toggles with hidden inputs
        // $('#customPlanForm').submit(function(e) {
        //     $('.toggle-flag').each(function() {
        //         var $toggle = $(this);
        //         var name = $toggle.attr('name');
        //         var value = $toggle.data('value');

        //         // Remove toggle from form and replace with hidden input
        //         $toggle.removeAttr('name');
        //         $('<input>').attr({
        //             type: 'hidden',
        //             name: name,
        //             value: value
        //         }).appendTo('#customPlanForm');
        //     });
        // });

    });
</script>