<div class="content-wrapper">
  <section class="content container">
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/profile/update_twilio_settings') ?>" role="form" class="form-horizontal">

        <div class="nav-tabs-custom">
          
            <?php include"include/profile_menu.php"; ?>

            <div class="row m-5 mt-20">
              <div class="col-md-12 box">
                
                <div class="box-header">
                    <h3 class="box-title"><?php echo trans('twilio-sms-settings') ?></h3>
                </div>

                <div class="box-body">
                    
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label><?php echo trans('account-sid') ?></label>
                            <input type="text" name="twillo_account_sid" value="<?php echo html_escape(user()->twillo_account_sid); ?>" class="form-control" >
                        </div>

                        <div class="form-group">
                          <label><?php echo trans('auth-token') ?></label>
                            <input type="text" name="twillo_auth_token" value="<?php echo html_escape(user()->twillo_auth_token); ?>" class="form-control" >
                        </div>

                        <div class="form-group">
                          <label><?php echo trans('sender-number-tw') ?></label>
                            <input type="text" name="twillo_number" value="<?php echo html_escape(user()->twillo_number); ?>" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-6">


                      <div class="form-group flex-parent-between mb-5 pl-25 pr-25">
                        <label class="mt-3"><?php echo trans('enable') ?>
                          <br><small class="fs-13 text-muted"> <?php echo trans('enable-booking-con-titlee') ?></small>
                        </label>
                        <div class="switch">
                          <input type="checkbox" name="enable_sms_notify" value="1" <?php if(user()->enable_sms_notify == 1){echo 'checked';} ?> data-toggle="toggle" data-onstyle="info" data-width="100">
                        </div>
                      </div>


                      <div class="form-group flex-parent-between mb-5 pl-25 pr-25 d-none">
                        <label class="mt-3"><?php echo trans('enable-booking-reminder-alerts') ?>
                          <br><small class="fs-13 text-muted"> <?php echo trans('enable-booking-alert-titles') ?></small>
                        </label>
                        <div class="switch">
                          <input type="checkbox" name="enable_sms_alert" value="1" <?php if(user()->enable_sms_alert == 1){echo 'checked';} ?> data-toggle="toggle" data-onstyle="info" data-width="100">
                        </div>
                      </div>
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