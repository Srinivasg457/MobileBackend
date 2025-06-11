<div class="content-wrapper">
  <!-- Main content -->
    <section class="content">
      
      <div class="container">
        <div class="row">
          <div class="box add_area <?php if(isset($page_title) && $page_title == "Edit"){echo "d-block";}else{echo "hide";} ?>">
            <div class="box-header with-border">
              <?php if (isset($page_title) && $page_title == "Edit"): ?>
                <h3 class="box-title"><?php echo trans('edit') ?></h3>
              <?php else: ?>
                <h3 class="box-title"><?php echo trans('create-new') ?> </h3>
              <?php endif; ?>

              <div class="box-tools pull-right">
                <?php if (isset($page_title) && $page_title == "Edit"): ?>
                  <a href="<?php echo base_url('admin/users') ?>" class="pull-right btn btn-secondary btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
                <?php else: ?>
                  <a href="#" class="text-right btn btn-secondary cancel_btn btn-sm"><?php echo trans('back') ?></a>
                <?php endif; ?>
              </div>
            </div>

            <div class="box-body pl-0">
              <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/users/add')?>" role="form" novalidate>
                <div class="box-body">
                 
                    <div class="form-group">
                      <label> <?php echo trans('name') ?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" required name="name" value="<?php echo html_escape($user[0]['name']); ?>" >
                    </div>

                    <div class="form-group">
                      <label> <?php echo trans('email') ?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" required name="email" value="<?php echo html_escape($user[0]['email']); ?>" >
                    </div>

                    <div class="form-group">
                      <label><?php echo trans('password') ?> <span class="text-danger">*</span></label>
                      <input type="password" class="form-control" name="password" placeholder="<?php echo trans('set-or-reset-password') ?>" value="">
                    </div>

                    <div class="form-group mb-4">
                        <label><?php echo trans('plan') ?> <span class="text-danger">*</span></label>
                        <select class="form-control" name="package" required>
                            <option value=""><?php echo trans('select') ?></option>
                            <?php foreach ($packages as $package): ?>
                              <option <?php if($package->id == $payment->package_id){echo "selected";} ?> value="<?php echo html_escape($package->id) ?>"><?php echo html_escape($package->name) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label><?php echo trans('subscription-type') ?> <span class="text-danger">*</span></label>
                        <select class="form-control" name="billing_type" required>
                            <option value=""><?php echo trans('select') ?></option>
                            <option <?php if('monthly' == $payment->billing_type){echo "selected";} ?> value="monthly"><?php echo trans('monthly') ?></option>
                            <option <?php if('yearly' == $payment->billing_type){echo "selected";} ?> value="yearly"><?php echo trans('yearly') ?></option>
                            <?php if (settings()->enable_lifetime == 1): ?>
                              <option <?php if('lifetime' == $payment->billing_type){echo "selected";} ?> value="lifetime"><?php echo trans('lifetime') ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                   
                    <div class="form-group mb-4">
                        <label><?php echo trans('payment-status') ?></label>
                        <select class="form-control" name="payment_status" required>
                            <option value=""><?php echo trans('select') ?></option>
                            <option <?php if($payment->status == 'verified'){echo "selected";} ?> value="verified"><?php echo trans('verified') ?></option>
                            <option <?php if($payment->status == 'pending'){echo "selected";} ?> value="pending"><?php echo trans('pending') ?></option>
                        </select>
                    </div>

                    <?php if (isset($page_title) && $page_title != "Edit"): ?>
    <div class="form-group">
        <label><?php echo trans('country') ?></label>
        <select class="selectfield textfield--grey single_select col-sm-12" name="country" style="width: 100%">
            <option value=""><?php echo trans('select') ?></option>
            <?php foreach ($countries as $country): ?>
                <option value="<?php echo html_escape($country->id); ?>">
                    <?php echo html_escape($country->name); ?> (<?php echo html_escape($country->currency_code); ?>)
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        
            <label>Timezone:</label>
            <select id="timezoneSelect" class="form-control selectfield textfield--grey single_select" name="timezone" style="width: 100%">
                <option value="">Select Timezone</option>
                <?php 
                // Assuming $timezones is an array of timezone objects/arrays available in your context
                if (isset($timezones)): 
                    foreach ($timezones as $tz): ?>
                        <option value="<?php echo html_escape($tz['value'] ?? $tz->value); ?>">
                            <?php echo html_escape($tz['name'] ?? $tz->name); ?>
                        </option>
                    <?php endforeach; 
                endif; ?>
            </select>
        
    </div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajax({
        url: "<?= base_url('/admin/Organization_settings/get_timezone_list') ?>",

        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                var dropdown = $('#timezoneSelect');
                dropdown.empty();
                dropdown.append('<option value="">Select Timezone</option>');

                $.each(response.data, function(index, value) {
                    // Use full string as both value and label
                    dropdown.append('<option value="' + value + '">' + value + '</option>');
                });
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ', error);
            alert('Failed to load timezones.');
        }
    });
});
</script>
                    <div class="form-group clearfix">
                      <label><?php echo trans('status') ?></label><br>

                      <div class="icheck-primary radio radio-inline d-inline mr-4 mt-2">
                        <input type="radio" id="radioPrimary1" value="1" name="status" <?php if(isset($user[0]['status']) && $user[0]['status'] == 1){echo "checked";} ?>>
                        <label for="radioPrimary1"> <?php echo trans('active') ?>
                        </label>
                      </div>

                      <div class="icheck-primary radio radio-inline d-inline">
                        <input type="radio" id="radioPrimary2" value="2" name="status" <?php if(isset($user[0]['status']) && $user[0]['status'] == 2){echo "checked";} ?>>
                        <label for="radioPrimary2"> <?php echo trans('inactive') ?>
                        </label>
                      </div>
                    </div>

                </div>

           

                <div class="row mb-20 pl-20">
                  <div class="col-sm-12">
                    <input type="hidden" name="id" value="<?php echo html_escape($user['0']['id']); ?>">
                    <!-- csrf token -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                    <?php if (isset($page_title) && $page_title == "Edit"): ?>
                      <button type="submit" class="btn btn-info pull-left"><?php echo trans('save-changes') ?></button>
                    <?php else: ?>
                      <button type="submit" class="btn btn-info pull-left"> <?php echo trans('save') ?></button>
                    <?php endif; ?>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>





      <?php if (isset($page_title) && $page_title != "Edit"): ?>
        <div class="list_area container">

          <form class="user_sort_form" role="search" autocomplete="off" action="<?php echo base_url('admin/users') ?>" method="get">
            <div class="row">

              <div class="col-md-4 col-xs-12 mt-5 pl-15">
                <h3 class="box-title"><?php echo trans('all-users') ?> </h3>
              </div>
                    
              <div class="col-md-3 col-xs-12 mt-5 pl-0">
                <select class="form-control user_sort" name="package">
                    <option value=""><?php echo trans('all-package') ?></option>
                    <?php foreach ($packages as $package): ?>
                      <option value="<?php echo html_escape($package->id) ?>" <?php echo(isset($_GET['package']) && $_GET['package'] == $package->id) ? 'selected' : ''; ?>
                      ><?php echo html_escape($package->name) ?></option>
                    <?php endforeach ?>
                </select>
              </div>

              <div class="col-md-3 col-xs-12 mt-5 pl-0">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="<?php echo trans('search-by-name-email') ?> ..." name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" autocomplete="off">
                    <button type="submit" class="input-group-addon btn btn-secondary"><i class="fa fa-search"></i></button>
                </div>
              </div>

              <div class="col-md-2 col-xs-12 mt-5 pl-0">
                <a href="<?php echo base_url('admin/users/add') ?>" class="pull-right btn btn-info btn-squares add_btn"><i class="fa fa-plus"></i> <?php echo trans('create-new').' '.trans('user') ?></a>
              </div>

            </div>
          </form>
          
          <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
            <table class="table table-hover cushover">
              <thead>
                <tr>
                  <th>#</th>
                  <th><?php echo trans('avatar') ?></th>
                  <th><?php echo trans('name') ?></th>
                  <th><?php echo trans('email') ?></th>
                  <th><?php echo trans('business') ?></th>
                  <th><?php echo trans('package') ?></th>
                  <th><?php echo trans('payment-status') ?></th>
                  <th><?php echo trans('join') ?></th>
                  <th><?php echo trans('expire') ?></th>
                  <th><?php echo trans('action') ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($users as $user): ?>
                <tr id="row_<?php echo html_escape($user->id); ?>">

                  <td><?php echo $i; ?></td>
                  <td>
                    <?php if ($user->thumb == ''): ?>
                      <?php $avatar = 'assets/images/avatar.png'; ?> 
                    <?php else: ?>
                      <?php $avatar = $user->thumb; ?>
                    <?php endif ?>
                    <img width="40px" class="img-circle" src="<?php echo base_url($avatar); ?>">
                  </td>
                 
                  <td><?php echo html_escape($user->name); ?></td>
                  <td>
                    <?php if($user->email_verified == 1): ?>
                      <?php echo html_escape($user->email); ?><i class="fa fa-check-circle text-success ml-2"></i>
                    <?php else: ?>
                      <?php echo html_escape($user->email); ?>
                    <?php endif; ?>
                      
                    </td>

                  <td>
                    <?php if (count($user->business) == 0): ?>
                        <p class="mt-10"><?php echo trans('not-found') ?></p>
                      <?php else: ?>
                        <?php foreach ($user->business as $business): ?>
                            <p class="mb-0"><i class="fa fa-long-arrow-right"></i> <?php echo html_escape($business->name) ?></p>
                        <?php endforeach; ?>
                      <?php endif; ?>
                  </td>

                  <td>
                      <?php if ($user->user_type != 'trial'): ?>
                        <span class="label label-info">
                            <?php echo get_user_payment_details($user->id)->package_name; ?>
                        </span>
                      <?php endif ?>
                  </td>

                  <td>
                    <?php if ($user->user_type == 'trial'): ?>
                      <span class="label label-warning"><i class="flaticon-clock"></i> Trial</span>
                    <?php else: ?>
                      <?php $payment_status = get_user_payment($user->id) ?>
                      <?php $label = ''; ?>
                      <?php if ($payment_status == 'pending'){
                        $label = 'warning';
                        $text = '<i class="flaticon-time-is-money"></i> '.$payment_status;
                      }else if($payment_status == 'verified'){ 
                        $label = 'primary';
                        $text = '<i class="flaticon-checked"></i> '.$payment_status;
                      }else{ 
                        $label = 'danger';
                        $text = 'Expired';
                      }?>
                      <span class="label label-<?php echo html_escape($label) ?>">
                          <?php echo $text; ?>
                      </span>
                    <?php endif ?>
                  </td>

                  <td>
                    <?php echo get_time_ago($user->created_at) ?>
                  </td>

                  <td>
                    <?php if ($user->payment_status != 'expired'): ?>
                      <?php if ($user->user_type == 'trial'): ?>
                        <?php if ($user->expire_on < date('Y-m-d')): ?>
                            <span class="label label-danger"><b><?php echo get_time_ago($user->trial_expire) ?></span>
                        <?php else: ?>
                            <span class="label label-warning"><b><?php echo date_dif(date('Y-m-d'), $user->trial_expire) ?> <?php echo trans('days-left') ?></span>
                        <?php endif; ?>
                      <?php else: ?>
                      
                        <?php if ($user->expire_on < date('Y-m-d')): ?>
                            <span class="label label-danger"><b><?php echo get_time_ago($user->expire_on) ?></span>
                        <?php else: ?>
                            <span class="label label-warning"><b><?php echo date_dif(date('Y-m-d'), $user->expire_on) ?> <?php echo trans('days-left') ?></span>
                        <?php endif; ?>
                        
                      <?php endif ?>
                    <?php else: ?>
                      <?php if ($user->expire_on < date('Y-m-d')): ?>
                            <span class="label label-danger"><b><?php echo get_time_ago($user->expire_on) ?></span>
                        <?php else: ?>
                            <span class="label label-warning"><b><?php echo date_dif(date('Y-m-d'), $user->expire_on) ?> <?php echo trans('days-left') ?></span>
                        <?php endif; ?>
                    <?php endif ?>
                  </td>

                  <td class="actions">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default rounded btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <?php echo trans('action') ?>
                        </button>
                        <div class="dropdown-menu st" x-placement="bottom-start">
                           
                            <?php if ($user->status == 1): ?>
                              <li><a class="dropdown-item" href="<?php echo base_url('admin/users/status_action/2/'.$user->id) ?>"><i class="fa fa-times"></i> <?php echo trans('deactivate') ?></a></li>
                            <?php endif ?>
                            <?php if ($user->status == 2 || $user->status == 0): ?>
                              <li><a class="dropdown-item" href="<?php echo base_url('admin/users/status_action/1/'.$user->id) ?>"><i class="fa fa-check"></i> <?php echo trans('activate') ?></a></li>
                            <?php endif ?>

                            <li><a href="<?php echo base_url('admin/users/edit/'.$user->id) ?>" class="dropdown-item"><i class="fa fa-pencil"></i> <?php echo trans('edit') ?></a></li>
                    
                            <li><a class="dropdown-item delete_item" data-val="User" data-id="<?php echo html_escape($user->id); ?>" href="<?php echo base_url('admin/users/delete/'.$user->id);?>" class="on-defaults remove-row delete_item"><i class="fa fa-trash-o"></i> <?php echo trans('delete') ?></a></li>

                            <li><a class="dropdown-item reset_pass" href="<?php echo base_url('admin/users/reset_password/'.$user->id) ?>"><i class="fa fa-refresh"></i> <?php echo trans('reset-password') ?></a></li>
                        </div>
                    </div>
                  </td>

                </tr>

                <?php $i++; endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="col-md-12 text-center mt-50">
              <?php echo $this->pagination->create_links(); ?>
          </div>
        </div>
      <?php endif; ?>
    </section>
</div>


<?php foreach ($users as $user): ?>
  <div id="roleModal_<?php echo html_escape($user->id) ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <form method="post" action="<?php echo base_url('admin/users/change_account/'.html_escape($user->id))?>" role="form">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select account type</h4>
        </div>

        <div class="modal-body">
          <div class="form-group m-t-20">
              <div class="radio radio-info radio-inline">
                  <input <?php if($user->account_type == 'free'){echo "checked";} ?> type="radio" id="inlineRadio3" value="free" name="type">
                  <label for="inlineRadio3"> Free </label>
              </div>
             <div class="radio radio-info radio-inline">
                  <input <?php if($user->account_type == 'pro'){echo "checked";} ?> type="radio" id="inlineRadio4" value="pro" name="type">
                  <label for="inlineRadio4"> Pro </label>
              </div>
          </div>
          <!-- csrf token -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

      </form>

    </div>
  </div>
<?php endforeach ?>