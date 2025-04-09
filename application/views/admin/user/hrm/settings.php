<div class="content-wrapper">
  <section class="content container">
    
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/hrm/update_hrm_settings') ?>" role="form" class="form-horizontal">
        <div class="nav-tabs-custom">

            <div class="row m-5 mt-20">
              <div class="col-md-6 box">
                
                <div class="box-header">
                    <h3 class="box-title"><?php echo trans('hrm-settings') ?></h3>
                </div>

                <div class="box-body p-10">

                    <div class="form-group m-t-20">
                        <label class="col-sm-4 control-label" for="example-input-normal"><?php echo trans('default-check-in') ?></label>
                        <div class="col-sm-12">
                            <input type="text" name="default_check_in" value="<?php echo html_escape($this->business->default_check_in) ?>" class="form-control timepicker" >
                        </div>
                    </div>

                    <div class="form-group m-t-20">
                        <label class="col-sm-4 control-label" for="example-input-normal"><?php echo trans('default-check-out') ?></label>
                        <div class="col-sm-12">
                            <input type="text" name="default_check_out" value="<?php echo html_escape($this->business->default_check_out) ?>" class="form-control timepicker" >
                        </div>
                    </div>


                </div>

                <div class="box-footer">
                    <!-- csrf token -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <button type="submit" class="btn btn-info waves-effect rounded w-md waves-light"><i class="fa fa-check"></i> <?php echo trans('save-changes') ?></button>
                </div>

              </div>
            </div>
        </div>
    </form>
  </section>
</div>