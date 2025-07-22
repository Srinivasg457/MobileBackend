<header class="page header text-contrast bg-light" style="">
    <div class="container text-center">
        <h1 class="bold display-md-4 text-dark mb-2"><?php echo trans('blogs') ?></h1>
    </div>
</header>

<section class="section">
    <div class="container pt-5">
        <div class="row gap-y">
        	<?php foreach ($posts as $post): ?>
                <?php include'include/blog_item.php'; ?>
            <?php endforeach ?>
        </div>
        <div class="row">
            <div class="col-md-12 mx-auto" style="width: auto !important;">
                <div class="mt-5" >
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
		</div>
    </div>
</section>