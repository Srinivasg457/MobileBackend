
<header class="page header text-contrast bg-light" style="">
    <div class="container text-center">
        <h1 class="bold display-md-4 text-dark mb-2"><?php echo trans('frequently-asked-questions') ?></h1>
    </div>
</header>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="accordion accordion-clean" id="faqs-accordion">
                    <?php $i=1; foreach ($faqs as $row): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="#" class="card-title btn collapsed text-dark fs-15 fw-600" data-bs-toggle="collapse" data-bs-target="#v1<?= $i ?>"><i class="fas fa-angle-down angle"></i><?php echo html_escape($row->title); ?></a></div>
                            <div id="v1<?= $i ?>" class="collapse" data-bs-parent="#faqs-accordion">
                                <div class="card-body"><?= $row->details; ?></div>
                            </div>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
