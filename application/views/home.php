<main class="position-relative overflow-hidden">
<header class="header alter2-header section parallax image-background center-bottom cover overlays  alpha-8s text-contrast" style="background-image: url('<?php //echo base_url('assets/front_new/') ?>img/bg/grid.jpg')">
    <div class="divider-shape">
        <!-- waves divider --> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" class="shape-waves" style="left: 0; transform: rotate3d(0,1,0,180deg);">
            <path class="shape-fill shape-fill-contrast" d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z" /></svg>
    </div>

    <div class="container-fluid" style="background: #0d333f;">
    <div class="container overflow-hidden py-0">
        <div class="row align-items-center">
            <div class="col-md-6 text-dark">
                <p class="text-gray" data-aos="zoom-in-up"><?php echo trans('small-business-accounting-software') ?></p>
                <h1 data-aos="fade-up" data-aos-delay="150" class="text-white fs-heading bold w-90"><?php echo html_escape(settings()->site_title) ?></h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-light lead fs-20 fw-500"><?php echo html_escape(settings()->description) ?></p>
                <nav class="nav mt-5">
                    <a data-aos="zoom-in" data-aos-delay="350" href="<?php echo base_url('pricing') ?>" class="me-3 btn btn btn-primary"><?php echo trans('pricing-plans') ?></a>

                    <?php if(settings()->trial_days != 0): ?>
                        <a data-aos="zoom-in" data-aos-delay="350" href="<?php echo base_url('register') ?><?php if(settings()->trial_days != 0){echo '?trial=start';}?>" class="btn btn-outline-light btn-sm-contrast"><?php echo trans('try-it-free') ?></a>
                    <?php endif ?>

                </nav>
            </div>
            <div class="col-md-6 text-right d-flex justify-content-end align-items-center hide-m">
                <img class="hero-imgi ml-5 w-80 pull-right" src="<?php echo base_url(settings()->hero_img) ?>" alt="Hero Image" data-aos="zoom-in"/>
            </div>
        </div>
    </div>
    </div>
</header><!-- ./Perspective mockups -->


<section class="zindex-low mt-5 mb-0 d-none">
    <div class="container z0">
        <div class="w-md-80 w-lg-50 text-center mx-auto mb-8">
            <span class="badge badge-secondary-soft badge-square mb-3"><?php echo trans('workflow') ?></span>
            <h1 class="text-dark mx-auto mb-1 bold"><?php echo trans('how-app-works') ?></h1>
        </div>

        <div class="row mt-5">
            <?php $w=1; foreach ($workflows as $workflow): ?>
                <div class="col-md-4 mb-md-0">
                    <div class="text-center m-3 py-5 px-4" data-aos="fade-up">
                        <div class="mb-5 workflow-img"><img class="display-5" src="<?php echo base_url($workflow->image) ?>" alt="Image"></div>
                       
                        <h3 class="title bold"><?php echo html_escape($workflow->title) ?></h3>
                        <p class="c-555"><?php echo strip_tags($workflow->details) ?></p>
                    </div>
                </div>
            <?php $w++; endforeach ?>

        </div>
    </div>
</section>


<section>
    <div class="container">
        <div class="w-md-80 w-lg-50 text-center mx-auto mb-5">
            <span class="badge badge-secondary-soft badge-square mb-3"><?php echo trans('workflow') ?></span>
            <h2 class="text-dark font-weight-bold mx-auto mb-1 display-5"><?php echo trans('how-app-works') ?></h2>
        </div>

        <div class="row">
            <?php $w=1; foreach ($workflows as $workflow): ?>
                <div class="col-md-4 mb-7 mb-md-0">
                    <div class="text-center m-2 py-5 px-4 <?php if($w==2){echo "shadow-workflow border-1";} ?>">
                        <div class="mb-5 workflow-img border-0"><img class="display-5" src="<?php echo base_url($workflow->image) ?>" alt="Image"></div>

                        <h4 class="mb-2 mx-auto text-dark"><?php echo ($workflow->title) ?></h4>
                        <p class="text-muted"><?php echo ($workflow->details) ?></p>
                    </div>
                </div>
            <?php $w++; endforeach ?>
        </div>
    </div>
</section>



<section>
    <div class="container">
        <div class="w-md-80 w-lg-60 text-center mx-auto mb-5">
            <h1 class="text-dark font-weight-bold mx-auto mb-1 display-5"><?php echo trans('home-feature-one-place') ?>.</h1>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2 mb-md-0">
                <div class="text-left bg-warning-soft px-5 py-5 fitem-lg round-1 mb-4">
                    <span class="fitem-icon"><i class="bi bi-receipt"></i></span>
                    <div class="fitem-body">
                        <h4 class="mb-2 mx-auto text-dark display-7"><?php echo trans('invoicing') ?></h4>
                        <p class="text-dark mb-0 w-lg-60"><?php echo trans('invoicing-title') ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2 mb-md-0">
                <div class="text-left bg-blue px-5 py-5 fitem-lg round-1 mb-4">
                    <span class="fitem-icon"><i class="bi bi-coin"></i></span>
                    <div class="fitem-body">
                        <h4 class="mb-2 mx-auto text-white display-7"><?php echo trans('billing-and-payments') ?></h4>
                        <p class="text-light mb-0 w-lg-50"><?php echo trans('billing-and-payments-title') ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3 mb-md-0">
                <div class="text-left bg-blue-soft py-5 px-5 fitem-lg round-1">
                    <span class="fitem-icon"><i class="bi bi-cash-stack"></i></span>
                    <div class="fitem-body">
                        <h4 class="mb-2 mx-auto text-dark display-7"><?php echo trans('expenses') ?></h4>
                        <p class="text-dark mb-0 w-lg-70"><?php echo trans('expenses-title') ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3 mb-md-0">
                <div class="text-left bg-navy py-5 px-5 fitem-lg round-1">
                    <span class="fitem-icon"><i class="bi bi-bar-chart-fill"></i></span>
                    <div class="fitem-body">
                        <h4 class="mb-2 mx-auto text-white display-7"><?php echo trans('reports') ?></h4>
                        <p class="text-light mb-0 w-lg-70"><?php echo trans('reports-title') ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3 mb-md-0">
                <div class="text-left bg-warning py-5 px-5 fitem-lg round-1">
                    <span class="fitem-icon"><i class="bi bi-patch-check"></i></span>
                    <div class="fitem-body">
                        <h4 class="mb-2 mx-auto text-dark display-7"><?php echo trans('more-features') ?></h4>
                        <div class="d-flex justify-content-between">
                            <div class="">
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('products') ?> </a></p>
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('tax') ?> </a></p>
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('hrm') ?> </a></p>
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('affiliate') ?> </a></p>
                            </div>
                            <div class="">
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('estimates') ?> </a></p>
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('recurring-invoice') ?> </a></p>
                                <p><a class="fs-14 link-grey" href="<?php echo base_url('login') ?>"><i class="bi bi-check-circle"></i> <?php echo trans('role-permissions') ?> </a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php if(!empty($testimonials)): ?>
<section class="bg-lgreen">
    <div class="container">
        <div class="row">
            <div class="w-md-80 w-lg-50 text-center mx-auto mb-6">
                <span class="badge badge-secondary-soft badge-square mb-3"><?php echo trans('testimonial') ?></span>
                <h1 class="text-dark mx-auto mb-1 title bold display-5"><?php echo trans('clients-say') ?> <b class="text-primary"><?php echo settings()->site_name ?></b></h1>
            </div>

            <div class="col-md-12d">
                <div class="testimonial testimonial-carousel owl-carousel owl-theme navTopRight">
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="col-6s item mb-5">
                            <div class="card border-2s h-100 mr-2 round-1">
                                <div class="card-body testimonial-box">
                                    <div class="text-center mb-3">
                                        <div class="text-center pt-3">
                                            <div class="avatar-sm mx-auto" style="background-image: url(<?php echo base_url($testimonial->image) ?>);"></div>
                                            
                                            <div class="mt-3">
                                                <h6 class="mb-0 text-dark"><?php echo html_escape($testimonial->name) ?>, <?php echo html_escape($testimonial->designation) ?></h6>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($testimonial->feedback)): ?>
                                            <div class="px-3 pt-0">
                                                <p class="ttext"><?php echo html_escape($testimonial->feedback) ?></p>
                                            </div>
                                        <?php endif ?>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($features)): ?>
    <section class="section">
        <div class="container">
            <div class="w-md-80 w-lg-50 text-center mx-auto mb-5">
                <span class="badge badge-secondary-soft badge-square mb-3"><?php echo trans('features') ?></span>
                <h1 class="text-dark mx-auto mb-1 title bold display-5 w-80"><?php echo trans('home-feature-title') ?></h1>
            </div>

            <?php $i=1; foreach ($features as $feature): ?>
                <div class="row <?php if($i % 2 == 0){echo "flex-lg-row-reverse";} ?> align-items-md-center" data-aos="fade-<?php if($i % 2 == 0){echo "right";}else{echo "left";} ?>">
                    <div class="col-12 col-lg-6 mt-5">
                        <div class="w-80">
                            <div class="section-headings">
                                <h1 class="title bold"><?php echo html_escape($feature->name); ?></h1>
                            </div>

                            <div class="">
                                <p class="c-555">
                                    <?php echo strip_tags($feature->details); ?>
                                </p>
                            </div>
                        </div>
                        
                    </div>

                    <div class="spacer py-4 d-lg-none"></div>

                    <div class="col-12 col-lg-6 mt-5 text-center text-lg-<?php if($i % 2 == 0){echo "left";}else{echo "right";} ?>">
                        <img class="img-fluid" width="520" height="507" src="<?php echo base_url($feature->image) ?>" alt="demo" />
                    </div>
                </div>
            <?php $i++; endforeach; ?>
        </div>
    </section>
<?php endif ?>



<?php if(!empty($faqs)): ?>
<section class="section bottom-right">
    <div class="container">
        <div class="row">
            <div class="col-md-8 px-5 mx-auto">
                <h1 class="text-dark text-center mb-5 bold display-5"><?php echo trans('frequently-asked-questions') ?></h1>

                <div class="accordion accordion-clean" id="faqs-accordion">
                    <?php $i=1; foreach ($faqs as $faq): ?>
                    <div class="card mb-4 bg-soft1">
                        <div class="card-header fs-14 bg-soft1"><a href="#" class="card-title btn" data-bs-toggle="collapse" data-bs-target="#v1-q1<?= $i; ?>" ><i class="fas fa-angle-down angle"></i> <?php echo html_escape($faq->title) ?></a></div>
                        <div id="v1-q1<?= $i; ?>" class="collapse <?php if($i==1){echo 'show';} ?>" data-bs-parent="#faqs-accordion">
                            <div class="card-body text-muted"><?php echo html_escape($faq->details) ?></div>
                        </div>
                    </div>
                    <?php $i++; endforeach ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if (!empty($posts)): ?>
<section class="bg-lights pt-0">
    <div class="container">

        <div class="w-md-80 w-lg-50 text-center mx-auto mb-5">
            <span class="badge badge-secondary-soft badge-square mb-3"><?php echo trans('blogs') ?></span>
            <h1 class="text-dark mx-auto mb-1 bold display-5"><?php echo trans('learn-more-build-skills-empower-yourself') ?></h1>
        </div>

        <div class="row">
            <?php $b=1; foreach ($posts as $post): ?>
                <?php include'include/blog_item.php'; ?>
            <?php $b++; endforeach ?>
        </div>
    </div>
</section>
<?php endif ?>



<?php if(settings()->trial_days != 0): ?>
    <section class="section bb-1 bg-soft">
        <div class="container">
            <div class="text-center">
                <div class="text-center mb-4">
                    <p class="light mb-3 text-dark btn-outline badge-pill px-3 fs-16"><?php echo trans('ready-to-get-started') ?></p>
                    <h2 class="mt-1 display-5 text-primary"><?php echo trans('try') ?> <?php echo settings()->site_name ?> <?php echo trans('free-for-') ?> <?php echo settings()->trial_days ?> <?php echo trans('days') ?></h2>
                </div>
                <a href="<?php echo base_url('register') ?><?php if(settings()->trial_days != 0){echo '?trial=start';}?>" class="btn btn-primary mt-3 mt-md-0 ms-md-auto"><?php echo trans('try-it-free') ?> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

</main>