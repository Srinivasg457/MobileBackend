<main>
    <div class="container-fluid" id="login-area">
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-7 mx-auto">

                <?php if (settings()->type == 'demo'): ?>
                    <div class="alert alert-light border-1 w-lg-50 mx-auto" role="alert">
                        <div class="row paddingx-m">
                            <div class="col-md-6">
                                <h4 class="alert-heading text-primary">Admin Access</h4>
                                <p class="mb-0">admin / 1234</p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="alert-heading text-primary">User Access</h4>
                                <p class="mb-0">user / 1234</p>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <div class="login-form w-lg-50 mx-auto paddingx-m">
                    <p class="mb-3"><a class="site-logo" href="<?php echo base_url() ?>">
                            <img class="img-fluid" width="30%" src="<?php echo base_url($settings->logo) ?>" alt="demo" /></a></p>

                    <h1 class="text-darker bold"><?php echo "Employee register "?></h1>
                    <p class="text-secondary mt-0 mb-5"><?php echo trans('dont-have-an-account-yet') ?> <a href="<?php echo base_url('register'); ?><?php if (settings()->trial_days != 0) {
                                                                                                                                                        echo '?trial=start';
                                                                                                                                                    } ?>" class="text-primary"><?php echo trans('sign-up') ?></a></p>

                    <div class="mb-4 mt-4">
                        <div class="success text-success"></div>
                        <div class="error text-danger bg-danger-soft rounded-1 py-2 px-3" style="display: none;"></div>
                        <div class="warning text-warning"></div>
                    </div>

                    <form class="form cozy" id="login-form" action="<?php echo base_url('auth/log'); ?>" data-validate-on="submit" novalidate>
                        <label class="form-label"><?php echo trans('email') ?></label>
                        <div class="form-group has-icon">
                            <input type="text" id="login_username" name="user_name" class="form-control br-5" required> <i class="icon bi bi-person-fill text-muted"></i>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label"><?php echo trans('password') ?></label>
                            <div class="form-group has-icon mb-0">
                                <input type="password" id="login_password" name="password" class="form-control br-5" required> <i class="icon bi bi-lock-fill text-muted"></i>
                            </div>
                            <div class="d-flex justify-content-end mt-1">
                                <span><a href="#" class="text-dark small forgot_pass link text-muted"><?php echo trans('forgot-password') ?></a></span>
                            </div>
                        </div>


                        <div class="form-group d-flex align-items-center justify-content-between ">
                            <!-- csrf token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-primary br-5 signin_btn"><?php echo trans('sign-in') ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-5 col-lg-5 hide-m fullscreen-md d-flex justify-content-center align-items-center overlay overlay-primarys alpha-4 image-background cover" style="background-image:url(<?php echo base_url('assets/images/default.jpg') ?>)">
                <div class="content">
                    <h2 class="display-4 display-md-3 display-lg-2 text-contrast mt-5 mt-md-0"><?php echo trans('welcome-to') ?><span class="bold d-block"><?php echo settings()->site_name ?></span></h2>
                    <p class="lead text-contrast"><?php echo trans('log-in-to-your-account') ?></p>
                </div>
            </div>
        </div>
    </div>





    <div class="container-fluid hide" id="forgot-area">
        <div class="row align-items-center">

            <div class="col-md-7 col-lg-7 mx-auto">
                <div class="w-lg-50 mx-auto paddingx-m">
                    <div class="row mb-3">
                        <div class="col-md-12 text-left">
                            <a class="site-logo" href="<?php echo base_url() ?>">
                                <img class="img-fluid" width="30%" src="<?php echo base_url($settings->logo) ?>" alt="demo" />
                            </a>
                        </div>
                    </div>

                    <div class="forgot-form mt-5 mt-md-0">
                        <h1 class="text-darker bold"><?php echo trans('forgot-password') ?></h1>
                        <p class="text-secondary mt-0 mb-4 mb-md-6"><?php echo trans('enter-your-email-bellow-to-retrieve-your-account-or') ?> <a href="#" class="text-primary bold back_login"><?php echo trans('login') ?></a></p>
                        <form class="cozy" id="lost-form" action="<?php echo base_url('auth/forgot_password'); ?>" data-validate-on="submit" novalidate>
                            <div class="form-group has-icon">
                                <input type="text" id="register_email" name="email" class="form-control br-5" placeholder="<?php echo trans('your-registered-email') ?>" required> <i class="icon bi bi-envelope"></i>
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-between">
                                <!-- csrf token -->
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                                <button type="submit" class="btn btn-primary br-5 ms-auto"><?php echo trans('submit') ?> <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="hide-m col-md-5 col-lg-5 fullscreen-md d-flex justify-content-center align-items-center overlay overlay-primary alpha-8 image-background cover" style="background-image:url(<?php echo base_url('assets/images/default.jpg') ?>)">
                <div class="content">
                    <h2 class="display-4 display-md-3 display-lg-2 text-contrast mt-4 mt-md-0"><?php echo trans('forgot') ?> <span class="bold d-block"><?php echo trans('password') ?>?</span></h2>
                    <p class="lead text-contrast"><?php echo trans('weve-got-you-covered') ?></p>
                    <!-- <hr class="mt-md-6 w-25"> -->
                </div>
            </div>


        </div>
    </div>
</main>