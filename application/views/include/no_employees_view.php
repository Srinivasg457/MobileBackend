<div class="content-wrapper">
    <section class="content">
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-10 col-md-6">
                <!-- Custom Plan -->
                <?php if (isset($custom_plan) && $custom_plan !== null): ?>
                    <div class="box add_area">
                        <div class="box-header flex-between">
                            <div>
                                <h3 class="box-title"><?php echo "Custom Plan" ?></h3>
                            </div>
                            <div>
                                <a target="_blank" href="<?php echo base_url('admin/payment/lists') ?>" class="pull-right btn btn-default btn-xs  brd-30"><i class="fa fa-file-text-o"></i> <?php echo trans('view-invoice') ?></a>
                            </div>
                        </div>
                        <div class="box-body p-0">
                            <div style="padding: 20px 30px;">
                                <?php if (!empty($custom_plan) && is_array($custom_plan)): ?>
                                    <ul class="border-0 px-3">
                                        <?php foreach ($custom_plan as $feature => $value): ?>
                                            <li class="d-flex justify-content-between py-2 text-light">
                                                <span><?php echo ucfirst(str_replace('_', ' ', $feature)); ?></span>
                                                <span class="bold"><?php echo html_escape($value); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="d-flex justify-content-center">
                                        <a href="<?php echo base_url('admin/subscription/custom_plan_payment'); ?>"
                                            class="btn  btn-default btn-sm">
                                            Upgrade
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-light">No features available for this plan.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php elseif (isset($no_active_package) && $no_active_package == true): ?>
                    <div class="card shadow-lg border-0 rounded-4">

                        <!-- No Active Package -->
                        <div class="card-body text-center p-5 m-5">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="fa fa-lock text-secondary" style="font-size: 50px;"></i>
                            </div>

                            <!-- Message -->
                            <h4 class="mb-4">No Upgrade Plan</h4>
                            <p class="text-muted">
                                Upgrade plan is currently disabled. Please contact your administrator for details.
                            </p>

                            <!-- CTA Button -->
                            <a href="<?php echo base_url('admin/subscription/current_plan') ?>" class="btn btn-default btn-sm rounded">
                                <i class="fa fa-file-text-o me-3"></i> View Plan
                            </a>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="card shadow-lg border-0 rounded-4">

                        <!-- No Employees Added -->
                        <div class="card-body text-center p-5 m-5">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="fa fa-users text-secondary" style="font-size: 50px;"></i>
                            </div>

                            <!-- Message -->
                            <h4 class="mb-4">No Employees Added Yet</h4>
                            <p class="text-muted">
                                Your organization hasn’t added any employees. Start by adding your first employee to begin tracking.
                            </p>

                            <!-- CTA Button -->
                            <a href="<?php echo base_url('admin/hrm/employees') ?>" class="btn btn-info mt-3 px-4">
                                <i class="fa fa-user-plus me-2"></i> Add Employee
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>
</section>
</div>