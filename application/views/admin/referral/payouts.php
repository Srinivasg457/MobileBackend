<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  

  <!-- Main content -->
  <div class="content pt-4">
      <div class="container">

        <div class="row ">
          <div class="col-md-12">

            <div class="box add_area <?php if(isset($_GET['error']) && $_GET['error'] == "Invalid"){echo "d-block";}else{echo "hide";} ?>">
              <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('send-payout-request') ?></h3>

                <div class="box-tools pull-right">
                    <a href="#" class="text-right btn btn-dark cancel_btn btn-sm"><?php echo trans('payouts') ?></a>
                </div>
              </div>


              <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/referral/add_payouts')?>" role="form" novalidate>
                <div class="box-body">
                   
                    <div class="form-group">
                      <label><?php echo trans('amount') ?></label>
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo currency_to_symbol(settings()->currency) ?></span>
                        <input type="number" class="form-control" name="amount" value="" placeholder="" required aria-invalid="false">
                      </div>
                      <p class="small mt-1"><i class="fa fa-info-circle text-muted"></i> <?php echo trans('balance') ?> <?php echo '<b>'.price_formatted(user()->referral_earn,'site').'</b>' ?></p>
                    </div>


                    <div class="form-group mt-4">
                      <label> <?php echo trans('withdrawal-method') ?><span class="text-danger">*</span></label>
                      <select class="form-control show_method" required name="payment_method">
                        <option class="" value=""><?php echo trans('select-your-payment-method') ?></option>
                        <option class="" value="Paypal"><?php echo trans('paypal') ?></option>
                        <option class="" value="Bank"><?php echo trans('bank') ?></option>
                      </select>
                    </div>

                    <div class="form-group purple-border method_details " style="display: none;">
                      <label for="exampleFormControlTextarea4"><?php echo trans('method-details') ?></label>
                      <textarea class="form-control" id="exampleFormControlTextarea4" name="method_details" rows="3"></textarea>
                    </div>
                </div>

                <div class="box-footers mt-4">
                    <!-- csrf token -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="id" value="<?php echo user()->referral_id?>">
                    <button type="submit" class="btn btn-info pull-left"><?php echo trans('submit') ?></button>
                </div>

              </form>

            </div>

            <div class="boxs list_area <?php if(isset($_GET['error']) && $_GET['error'] == "Invalid"){echo "hide";} ?>">
              <div class="box-header with-border pl-0">
                  <h3 class="box-title pt-2 pl-0"><?php echo trans('payouts') ?></h3>
                  <div class="box-tools pull-right">

                    <?php if (user()->referral_earn >= $settings->minimum_payout): ?>
                      <a href="#" class="pull-right btn btn-sm btn-info add_btn"><?php echo trans('send-payout-request') ?></a>
                    <?php endif ?>
                  </div>
              </div>

              <div class="card-body table-responsives p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo trans('transaction-id') ?></th>
                                <th><?php echo trans('amount') ?></th>
                                <th><?php echo trans('payment-method') ?></th>
                                <th><?php echo trans('status') ?></th>
                                <th><?php echo trans('date') ?></th>
                                <th><?php echo trans('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php $i=1; foreach ($payouts as $payout): ?>
                            <tr id="row_<?php echo html_escape($payout->id); ?>">
                                <td><?= $i; ?></td>
                                <td>
                                  <?php echo html_escape($payout->transaction_id); ?>
                                </td>
                                <td>
                                  <p class="mb-0"><?php echo price_formatted($payout->amount, 'site'); ?></p>
                                </td>
                                <td>
                                  <span class="badge badge-info"><?php echo html_escape($payout->payout_method); ?></span>
                                </td>
                                <td>
                                  <?php if ($payout->status == 1): ?>
                                    <span class="badge badge-success"><i class="fa fa-check-circle"></i> <?php echo trans('completed') ?></span>
                                  <?php else: ?>
                                    <span class="badge badge-warning"><i class="fa fa-clock"></i> <?php echo trans('pending') ?></span>
                                  <?php endif ?>
                                </td>

                                <td>
                                  <p class="mb-0 fs-14"><?php echo my_date_show_time($payout->created_at) ?></p>
                                </td>

                                <td class="actions">
                                    
                                    <a data-val="Payout" data-id="<?php echo html_escape($payout->id); ?>" href="<?php echo base_url('admin/referral/payout_delete/'.html_escape($payout->id));?>" class="on-default remove-row delete_item"><i class="fa fa-trash-o"></i></a>
                                      
                                </td>
                            </tr>
                            
                          <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
              </div>

              <div class="mt-4">
                  <?php echo $this->pagination->create_links(); ?>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>



</div>
