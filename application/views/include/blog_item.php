<div class="col-md-6 col-lg-4 mb-4 mb-md-5 mb-lg-0 mt-5">
    <article class="card shadow-hoverg border-0"> 
        <a href="<?php echo base_url('post/'.$post->slug) ?>">
            <div class="blog-img round-1" style="background-image: url(<?php echo base_url($post->image) ?>);"></div>
        </a>

        <div class="card-body p-0">
            <div class="mb-1">
                <p class="mb-0 text-muted fs-13">
                    <span class="mr-2"><?php echo $post->category ?></span> &bull;
                    <span><?php echo my_date_show($post->created_at) ?></span>
                </p>
            </div>
            <h5 class="bold">
                <a class="fw-500 link-grey" href="<?php echo base_url('post/'.$post->slug) ?>"><?php echo html_escape($post->title) ?></a>
            </h5>
        </div>
    </article>
</div>