


<header class="page header text-contrast bg-light" style="">
    <div class="container text-center">
        <h1 class="bold fs-heading display-md-4 text-dark mb-2"><?php echo $post->title ?></h1>
    </div>
</header>

<section class="section blog single">
    <div class="container">
        <div class="row gap-y">
            <div class="col-lg-8 ">
                <div class="blog-post post-content pb-5">
                    <figure class="shadow rounded mb-2"><img class="img-responsive rounded" src="<?php echo base_url($post->image) ?>" alt=""></figure>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="author d-flex align-items-center">
                            
                            <a href="<?php echo base_url('category/'.$post->category_slug) ?>" class="d-flex align-items-center text-secondary blog-action blog-bookmark"><span class="small bold image_down"><?php echo html_escape($post->category) ?></span></a>
                    		
                        </div>
                        <nav class="nav">
                        	
                        	<p class="bold small text-secondary mt-0 image_down"><small><?php echo my_date_show($post->created_at) ?></small></p>
                        </nav>
                    </div>

                    <h1 class="post-details-title"><?php echo html_escape($post->title) ?></h1>
                    <p><?php echo $post->details ?></p>
                </div>


                <div class="post-details  ">
                    <ul class="list-unstyled d-flex flex-wrap flex-row align-items-center">
                        <li class="me-4"><i class="fas fa-tag text-secondary"></i></li>
                    	<?php foreach ($tags as $tag): ?>

                        <li><a href="#" class="badge rounded-pill badge-outline-secondary me-2"><?php echo html_escape($tag->tag) ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <div class="post-author py-5  b-2x">
                	<?php foreach ($comments as $comment): ?>
                    <div class="d-flex align-items-center mt-3"><img class="d-flex me-3 rounded-circle shadow" src="<?php echo base_url('assets/front_new/img/avatar/user.png') ?>" alt="...">
                        <div class="flex-fill mt-2">
                            <h5 class="my-0 bold"><?php echo html_escape($comment->name) ?></h5><span class="text-secondary"><?php echo $comment->email ?></span>
                            <p class="small text-secondary mb-0 mt-1"><?php echo $comment->message ?></p>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
                <div class="post-comments  p-4">
                    <form action="<?php echo base_url('home/send_comment/'.html_escape($post->id)); ?>" method="post" >
                        <h3 class="mb-4 semi-bold">Leave a comment</h3>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6"><input class="form-control" type="text" placeholder="Name" name="name"></div>
                            <div class="mb-3 col-12 col-md-6"><input class="form-control" type="text" placeholder="Email" name="email"></div>
                        </div>
                        <div class="mb-3">
                        	<textarea class="form-control" name="message" placeholder="Your comment" rows="4"></textarea>
                        </div>
                        <!-- csrf token -->
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                        <button class="btn btn-primary" type="submit">Post your comment</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4" style="padding-left: 35px; ">
                <h4 class=" mb-3 bold">Related Posts</h4>
                <ul class="list-unstyled">
                	<?php foreach ($related_posts as $post): ?>
                    <li class="d-flex align-items-center"><a href="<?php echo base_url('post/'.$post->slug) ?>" class="d-flex me-3 py-2"><img class="rounded-circle icon-xl shadow" src="<?php echo base_url($post->image) ?>" alt="..."></a>
                        <div class="flex-fill">
                            <span class="text-muted fs-12"><i class="far fa-calendar-alt"></i> <?php echo my_date_show($post->created_at) ?></span>
                            <h6 class="semi-bold mt-1 mb-2"><a href="<?php echo base_url('post/'.$post->slug) ?>" class="text-dark"><?php echo html_escape($post->title) ?></a></h6>

                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>

               
            </div>
        </div>
    </div>
</section>