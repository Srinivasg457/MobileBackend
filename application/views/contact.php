

<header class="page header text-contrast bg-light" style="">
    <div class="container text-center">
        <h1 class="bold fs-heading display-md-4 text-dark mb-2"><?php echo trans('get-in-touch') ?></h1>
    </div>
</header>



<section class="section mt-5">
    <div class="container bring-to-front pt-0">
        <div class="row align-items-center gap-y ">
            <div class="col-md-7 mx-auto">
                <div class="shadow border-1 bg-contrast p-5 rounded">
                    <form action="<?php echo base_url('send-message'); ?>" method="post" class="form form-contact" name="form-contact" data-response-message-animation="slide-in-up">

                        <div class="mb-4">
                            <label for="contact_email" class="text-dark bold mb-1"><?php echo trans('name') ?></label>
                            <input type="text" name="name" id="contact_subject" class="form-control bg-contrast" placeholder="<?php echo trans('name') ?>" required>
                         </div>

                        <div class="mb-4">
                            <label for="contact_email" class="text-dark bold mb-1"><?php echo trans('email') ?></label>
                            <input type="email" name="email" id="contact_email" class="form-control bg-contrast" placeholder="<?php echo trans('email') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="contact_email" class="text-dark bold mb-1"><?php echo trans('message') ?></label>
                            <textarea name="message" id="contact_message" class="form-control bg-contrast" placeholder="<?php echo trans('write-your-message') ?>" rows="4" required></textarea>
                        </div>

                        <div class="mb-4">
                            <?php if (settings()->enable_captcha == 1 && settings()->captcha_site_key != ''): ?>
                                <div class="g-recaptcha pull-left" data-sitekey="<?php echo html_escape($settings->captcha_site_key); ?>"></div>
                            <?php endif ?>
                        </div>

                        <div class="d-grids gap-2">
                            <!-- csrf token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <button id="contact-submit" data-loading-text="Sending..." name="submit" type="submit" class="btn btn-primary ">
                                <?php echo trans('submit') ?>
                                    
                            </button>
                        </div>
                    </form>
                    <div class="response-message">
                        <div class="section-heading"><i class="fas fa-check font-lg"></i>
                            <p class="font-md m-0"><?php echo trans('thank-you') ?></p>
                            <p class="response"><?php echo trans('your-message-has-been-send-we-will-contact-you-as-soon-as-possible') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- ./Other contact channels -->