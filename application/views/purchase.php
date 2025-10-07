<?php $settings = get_settings(); ?>

<section class="section">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="row">

                    <?php if (isset($success_msg) && $success_msg == 'Success'): ?>
                        <div class="col-6">
                            <div class="row justify-content-center">
                                <div class="row">
                                    <div class="col-md-9 mt-3 ms-8 border-0">
                                        <div class="card pricing-card text-center bg-primary text-light pb-3">
                                            <div class="pricing card-headerh  d-flex align-items-center flex-column">
                                                <p class="bold fs-40 text-light"><?php echo html_escape($current_features->package_name); ?></p>

                                            </div>

                                            <?php if (!empty($get_features) && is_array($get_features)): ?>
                                                <ul class="list-group list-group-flush monthly_row border-0 px-3">

                                                    <?php foreach ($get_features as $feature): ?>
                                                        <li class="d-flex justify-content-between py-2 text-light">
                                                            <span><?php echo html_escape($feature->name); ?></span>
                                                            <span class="bold"><?php echo html_escape($feature->pack_feature); ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p class="text-light">No features available for this plan.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 justify-content-center align-content-center"> <!-- ✅ Payment Success Header -->
                            <h1 class="text-success mb-3">
                                <i class="icon-check fs-1"></i><br><?php echo trans('done') ?>
                            </h1>
                            <h5 class="mb-4"><?php echo trans('payment-success-msg') ?></h5>
                            <div class="text-center mt-4">
                                <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>"
                                    class="btn btn-default px-4 fw-bold">
                                    Continue <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>

                        <!-- ✅ Single Feature Card -->

                    <?php elseif (isset($error_msg) && $error_msg == 'Error'): ?>
                        <!-- ❌ Payment Error Section -->
                        <h3 class="text-danger"><i class="icon-close"></i> <?php echo trans('error') ?></h3>
                        <h5 class="error mb-3"><?php echo trans('payment-error-msg') ?></h5>
                        <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>" class="btn btn-secondary btn-lg">
                            <?php echo trans('back') ?>
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>