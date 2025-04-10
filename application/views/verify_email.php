
<section class="section p-0 mt-100">
    <div class="container">
        
        <div class="spacer py-6"></div>

        <div class="row minh-500">
            <?php if (empty($code)): ?>
                <div class="col-md-12 text-center">
                    <img src="<?php echo base_url(settings()->logo) ?>" width="200px">
                    <h3 class="mt-5 text-dark"><?php echo trans('verify-account') ?></h3>
                    <p class="mt-2"><?php echo trans('verify-acc-msg') ?></p>

                    <a class="btn btn-primary btn-rounded resend_mail" href="<?php echo base_url('auth/resend_mail') ?>"><i class="fa fa-paper-plane"></i> <?php echo trans('resend-mail') ?></a>
                </div>
            <?php else: ?>
                <?php if ($code == 'invalid'): ?>
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url(settings()->logo) ?>" width="200px">
                        <h3 class="mt-5 text-danger"><?php echo trans('error') ?></h3>
                        <p class="mt-2"><?php echo trans('verify-failed') ?></p>
                        <a class="btn btn-default btn-rounded" href="<?php echo base_url() ?>"><?php echo trans('back-home') ?></a>
                    </div>
                <?php else: ?>
                    <div class="col-md-12 text-center">
                        <img src="<?php echo base_url(settings()->logo) ?>" width="200px">
                        <h3 class="mb-1 mt-5">Congratulations</h3>
                        <p class="mt-2">Your account was successfully verified.</p>
                        <a class="btn btn-primary mt-4 btn-rounded" href="<?php echo base_url('admin/dashboard/business') ?>"><?php echo trans('continue') ?></a>
                    </div>
                <?php endif ?>
            <?php endif ?>
        </div>

        <div class="spacer py-4"></div>

    </div>
</section>

