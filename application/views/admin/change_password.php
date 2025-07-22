<style>
  .input-group-append {
    border: 1px solid #ddd !important;
    width: 40px;
    align-items: center;
    justify-content: center;
    display: flex;
    font-size: large;
  }

  .input-group-append:hover{
background-color: whitesmoke;
  }
</style>
<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">


    <div class="row">

      <div class="col-md-8">
        <div class="box">

          <div class="box-header with-border">
            <h3 class="box-title"><?php echo trans('change-password') ?></h3>
          </div>

          <!-- <?php if (auth('role') == 'admin' || auth('role') == 'user'): ?>
            <form method="post" id="cahage_pass_form" action="<?php echo base_url('admin/dashboard/change') ?>">
            <?php else: ?>
              <form method="post" id="cahage_pass_form" action="<?php echo base_url('admin/profile/change') ?>">
              <?php endif ?> -->
          <form method="post" id="cahage_pass_form" action="<?php echo base_url('admin/dashboard/change') ?>">

            <div class="col-md-12 mt-20">
              <div class="row">

                <!-- Old Password -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <label><?php echo trans('old-password') ?></label>
                    <div class="input-group">
                      <input type="password" class="form-control" name="old_pass" id="old_pass" />
                      <div class="input-group-append">
                        <span class="input-group-text toggle-password" data-target="old_pass" style="cursor:pointer;">
                          <i class="bi bi-eye-slash-fill"></i> </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- New Password -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <label><?php echo trans('new-password') ?></label>
                    <div class="input-group">
                      <input type="password" class="form-control" name="new_pass" id="new_pass" />
                      <div class="input-group-append">
                        <span class="input-group-text toggle-password" data-target="new_pass" style="cursor:pointer;">
                          <i class="bi bi-eye-slash-fill"></i> </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <label><?php echo trans('confirm-new-password') ?></label>
                    <div class="input-group">
                      <input type="password" class="form-control" name="confirm_pass" id="confirm_pass" />
                      <div class="input-group-append">
                        <span class="input-group-text toggle-password" data-target="confirm_pass" style="cursor:pointer;">
                          <i class="bi bi-eye-slash-fill"></i> </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- CSRF token -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="col-sm-12">
                  <div class="form-group">
                    <button type="submit" class="btn btn-info"><?php echo trans('change') ?></button>
                  </div>
                </div>

              </div>
            </div>

          </form>


        </div>
      </div>

    </div>


  </section>

</div>