    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="list_area container">
                <div class="row">
                    <?php if (is_pack_trial()): ?>
                        <div class="col-10  m-auto mt-20">
                            <div class="box add_area">
                                <div class="box-header flex-between">
                                    <div>
                                        <h3 class="box-title"><?php echo "Current Plan" ?></h3>
                                    </div>
                                    <div>
                                        <a target="_blank" href="<?php echo base_url('admin/payment/lists') ?>" class="pull-right btn btn-default btn-xs  brd-30"><i class="fa fa-file-text-o"></i> <?php echo trans('view-invoice') ?></a>
                                        <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>" class="pull-right btn btn-info btn-xs  mr-4"><i class="fa fa-diamond" aria-hidden="true"></i> <?php echo trans('upgrade-plan') ?></a>
                                    </div>
                                </div>
                                <div class="box-body p-0">
                                    <div style="padding: 20px 30px;">
                                        <p><?php echo trans('your-subscription') ?>: <strong><?php echo trans('free-trial-of') ?> <?php echo settings()->trial_days . ' ' . trans('days') ?></strong></p>
                                        <p><?php echo trans('billing-frequency') ?> : <strong><?php echo settings()->trial_days . ' ' . trans('days') ?></strong></p>
                                        <p><?php echo trans('created') ?> : <strong><?php echo my_date_show(user()->created_at) ?></strong>
                                            <?php if (($days_left = date_dif(date('Y-m-d'), user()->trial_expire)) != -1): ?>
                                                <strong class="text-danger">(<?php echo $days_left ?> <?php echo trans('days-left') ?>)</strong>
                                            <?php else: ?>
                                                <strong class="text-danger">(<?php echo 'expired' ?>)</strong>
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($days_left == -1): ?>
                                            <p class="text-danger" style="font-weight: bold; margin-top: 10px; padding: 8px; background-color: #ffeeee; border-left: 3px solid red;">
                                                You've reached the end of your free trial. Select a plan to keep your experience going.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-10  m-auto mt-20">
                            <div class="box add_area">
                                <div class="box-header flex-between">
                                    <div>
                                        <h3 class="box-title"><?php echo "Current Plan" ?></h3>
                                    </div>

                                    <div>
                                        <a target="_blank" href="<?php echo base_url('admin/payment/lists') ?>" class="pull-right btn btn-default btn-xs mt-1 brd-30"><i class="fa fa-file-text-o"></i> <?php echo trans('view-invoice') ?></a>
                                        <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>" class="pull-right btn btn-info btn-xs mt-1 mr-4"><i class="fa fa-diamond" aria-hidden="true"></i> <?php echo trans('upgrade-plan') ?></a>
                                    </div>

                                </div>



                                <?php if ($user->package_name != 'Trial'): ?>
                                    <div class="box-body" style="padding: 20px 30px;">
                                        <p><?php echo trans('your-subscription') ?>: <strong><?php echo html_escape($user->package_name) ?> <?php echo trans('plan') ?></strong></p>
                                        <p><?php echo trans('price') ?>: <strong><?php echo price_formatted($user->amount, 'site') ?> </strong></p>
                                        <p><?php echo trans('billing-frequency') ?> : <strong><?php echo ucfirst(html_escape($user->billing_type)) ?></strong> </p>
                                        <p><?php echo trans('last-billing') ?> : <strong><?php echo my_date_show($user->created_at) ?></strong> </p>
                                        <p><?php echo trans('next-billing') ?> : <strong><?php echo my_date_show($user->expire_on); ?></strong>
                                            <strong class="text-danger">(<?php echo date_dif(date('Y-m-d'), $user->expire_on) ?> <?php echo trans('days-left') ?>)</strong>
                                        </p>
                                    </div>
                                    <div class="box-footer text-center soft-<?php echo ($user->status == 'verified') ? "success" : "danger"; ?>">
                                        <?php echo trans('payment-status') ?>: &emsp;
                                        <i class="fa fa-<?php echo ($user->status == 'verified') ? "check" : "times"; ?>"></i>
                                        <?php echo ucfirst(html_escape($user->status)) ?>

                                        <?php if ($user->status != 'verified'): ?>
                                            <?php if (is_custom_plan_user()): ?>
                                                <div class="mt-3">
                                                    <span style="color:red">Your request has been sent. Please wait for the admin to take further action.</span>
                                                </div>
                                            <?php else: ?>
                                                <?php $billing_type = $user->billing_type == 'monthly' ? "monthly" : "yearly" ?>
                                                <div class="mt-3">
                                                    <a href="<?php echo base_url('admin/subscription/upgrade/' . $user->slug . '/' . $billing_type) ?>"
                                                        class="btn btn-default btn-sm">
                                                        <i class="fa fa-credit-card"></i> <?php echo 'Complete Payment' ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- <div class="box-footer text-center soft-<?php if ($user->status == 'verified') {
                                                                                        echo "success";
                                                                                    } else {
                                                                                        echo "danger";
                                                                                    } ?>">
                                        <?php echo trans('payment-status') ?>: &emsp; <i class="fa fa-<?php if ($user->status == 'verified') {
                                                                                                            echo "check";
                                                                                                        } else {
                                                                                                            echo "times";
                                                                                                        } ?>"></i> <?php echo ucfirst(html_escape($user->status)) ?>
                                    </div> -->
                                <?php else: ?>
                                    <div class="box-body">
                                        <p><?php echo trans('your-subscription') ?>: <strong><?php echo trans('free-trial-of') ?> <?php echo settings()->trial_days . ' ' . trans('days') ?></strong></p>
                                        <p><?php echo trans('billing-frequency') ?> : <strong><?php echo settings()->trial_days . ' ' . trans('days') ?></strong> </p>
                                        <p><?php echo trans('created') ?> : <strong><?php echo my_date_show(user()->created_at) ?></strong>
                                            <strong class="text-danger">(<?php echo date_dif(date('Y-m-d'), user()->trial_expire) ?> <?php echo trans('days-left') ?>)</strong>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>