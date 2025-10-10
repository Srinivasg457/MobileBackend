<div class="content-wrapper">
    <style>
        #customPlanBtn {
            font-weight: 600;
        }

        .custom_plan_div:hover {
            background-color: #0FB783;
            color: white;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <!-- <div class="box-header with-border">
            <h3 class="box-title"><?php echo trans('') ?></h3>
        </div> -->


        <div class="container">
            <div class="row">
                <div class="box add_area d-block">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo trans('create-new') ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('admin/users') ?>" class="text-right btn btn-secondary  btn-sm"><i class="fa fa-angle-left"></i><?php echo trans('back') ?></a>
                        </div>
                    </div>

                    <div class="box-body pl-0">
                        <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/users/add') ?>" role="form">
                            <div class="box-body">

                                <div class="form-group">
                                    <label><?php echo trans('name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required name="name" value="<?php echo html_escape($user[0]['name']); ?>">
                                </div>

                                <div class="form-group">
                                    <label><?php echo trans('email') ?> <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" required name="email" value="<?php echo html_escape($user[0]['email']); ?>">
                                </div>

                                <div class="form-group">
                                    <label><?php echo trans('password') ?> <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" placeholder="<?php echo trans('set-or-reset-password') ?>" value="">
                                </div>

                                <div class="form-group mb-4">
                                    <label><?php echo trans('plan') ?> <span class="text-danger">*</span></label>
                                    <input type="hidden" name="package" value="1">
                                    <!-- <input type="text" class="form-control" name="package_name" value="Customized Plan" readonly> -->

                                    <div class="input-group">
                                        <input type="text" name="package_name" value="Customized Plan" class=" form-control" readonly style="cursor:pointer;">
                                        <div class="input-group-addon custom_plan_div" style="cursor:pointer;">
                                            <span type="button" class="" id="customPlanBtn">
                                                <i class="fa fa-puzzle-piece"></i> <?php echo 'Customize Features' ?>
                                            </span>
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

                        </form>
                    </div>
                </div>
            </div>
        </div>



    </section>
</div>