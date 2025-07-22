<header class="page header text-contrast bg-light" style="">
    <div class="container text-center">
        <h1 class="bold fs-heading display-md-4 text-dark mb-4"><?php echo trans('explore-our-features') ?></h1>
    </div>
</header>

<section class="section">
    <div class="container">
        <?php $i=1; foreach ($features as $feature): ?>
        <div class="row <?php if($i % 2 == 0){echo "flex-lg-row-reverse";} ?> mb-5">
            <div class="col-md-6">
                <figure><img src="<?php echo base_url($feature->image)?>" class="img-responsive w-80" alt=""></figure>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <div class="mb-5">
                    <h4 class="text-primary"><?php echo html_escape($feature->short_name); ?></h4>
                    <h2 class="bold text-capitalize"><?php echo html_escape($feature->name); ?></h2>
                    <p class="c-555"><?php echo strip_tags($feature->details); ?></p>
                </div>
            </div>
        </div>
        <?php $i++; endforeach; ?>
    </div>
</section>