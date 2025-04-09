<div class="content-wrapper">

  
  <!-- Main content -->
  <section class="content container">
    <?php include APPPATH.'views/admin/include/breadcramb.php'; ?>
    <div class="text-right">
      <a href="<?php echo base_url('admin/file/download/'.strtolower($type)) ?>" class="text-right btn btn-dark btn-sm mt-15 mr-2 mb-3"><i class="fa fa-cloud-download"></i> <?php echo trans('') ?></a>
    </div>

    <div class="box list_area mt-3">
      <div class="box-header with-border">
          <h3 class="box-title">
            <?php if ($type=='products'): ?>
              <?php echo trans('import-productservice') ?>
            <?php else: ?>
              <?php echo trans('import') ?> <?php echo trans(strtolower($type)) ?>
            <?php endif; ?>
          </h3>

          <div class="box-tools pull-right">
           <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="text-right btn btn-secondary  btn-sm">< <?php echo trans('back') ?></a>
          </div>
      </div>

      <div class="box-body p-4">
        <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/file/import_file')?>" role="form" novalidate>

          <div class="form-group">
              <label><?php echo trans('upload-csv-file') ?></label>
              <input type="file" class="form-control" name="file" required>
          </div>

          <input type="hidden" name="type" value="<?php echo html_escape($type); ?>">
          <input type="hidden" name="product_type" value="<?php echo html_escape($product_type); ?>">
          <!-- csrf token -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

          <div class="row m-t-30">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-info pull-left"><i class="fa fa-check-circle"></i> <?php echo trans('upload') ?></button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </section>
</div>
