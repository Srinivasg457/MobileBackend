<div class="content-wrapper">


  <!-- Main content -->
  <div class="content pt-4 mb-4">
      <div class="container-fluid">
        
        <div class="row mt-5">
          <div class="col-md-12 hide" >
            <form action="<?php echo base_url('admin/referral/payout_request') ?>" class="validate-form"style="float: right;" method="get">

              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Transaction Id" name="transaction_id" value="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button type="submit" class="btn-primary btn" style="border-radius: 0px;"><?php echo trans('search') ?></button>
                </div>
              </div>
            </form>
          </div>


          <div class="col-md-12">

            <div class="boxs list_area">
                <h3 style="padding-left: 5px;"><?php echo trans('payout-request') ?></h3>
              <div class="card-body table-responsive p-0">
                  <?php if (!empty($payouts)): ?>
                  <table class="table table-hover text-nowrap">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th><?php echo trans('user') ?></th>
                              <th><?php echo trans('withdrawal-method') ?></th>
                              <th><?php echo trans('withdrawal-amount') ?></th>
                              <th><?php echo trans('balance') ?></th>
                              <th><?php echo trans('transaction-id') ?></th>
                              <th><?php echo trans('status') ?></th>
                              <th><?php echo trans('request-sent') ?></th>
                              <th><?php echo trans('action') ?></th>
                          </tr>
                      </thead>
                      <tbody>
                        
                        <?php $i=1; foreach ($payouts as $row): ?>
                        <?php $user = $this->admin_model->get_by_id($row->user_id, 'users'); ?>
                          <tr id="row_<?php echo html_escape($row->id); ?>">
                              <td><?= $i; ?></td>
                              
                              <td class="pl-2">
                                <div class="d-flex align-items-center">
                                  
                                  <div class="d-flexs flex-columns">
                                      <div>
                                          <p class="leads font-weight-bold mb-0"><?php echo html_escape($user->name); ?></p>
                                      </div>
                                  </div>
                                </div>
                              </td>

                              <td>
                                <span class="badge badge-primary"><?php echo ucfirst($row->payout_method); ?></span>
                                <a data-toggle="modal" href="#payoutModal_<?php echo html_escape($i) ?>" >
                                  <span class="badge badge-secondary"><i class="fa fa-eye"></i> <?php echo trans('view-details') ?></span>
                                </a>
                              </td>
                              <td>
                                <p class="mb-0 text-success"><?php echo price_formatted($row->amount, 'site') ?></p>
                              </td>
                              <td><p class="mb-0 text-success"><?php echo price_formatted($user->referral_earn, 'site') ?></p></td>
                              <td>
                                <p class="mb-0"><?php echo html_escape($row->transaction_id); ?></p>
                              </td>
                              <td>
                                <?php if ($row->status == 1): ?>
                                  <span class="badge badge-success"><i class="fa fa-check-circle"></i> <?php echo trans('completed') ?></span>
                                <?php else: ?>
                                  <span class="badge badge-warning"><i class="fa fa-clock"></i> <?php echo trans('pending') ?></span>
                                <?php endif ?>
                              </td>
                              <td>
                                <p class="mb-0 fs-14"><?php echo get_time_ago($row->created_at) ?></p>
                              </td>
                              <td class="actions">
                                  <div class="btn-groups">
                                    <button type="button" class="btn btn-light" data-toggle="dropdown" aria-expanded="false">
                                      <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu" >
                                      <?php if ($row->status == 0): ?>
                                        <a href="<?php echo base_url('admin/referral/payout_complete/'.md5($row->id));?>" class="dropdown-item <?php //if($user_balance < $row->amount){echo "hide";} ?>"><i class="fa fa-check-circle"></i><?php echo trans('completed') ?></a>
                                      <?php endif ?>
                                        <a data-val="Category" data-id="" href="<?php echo base_url('admin/referral/payout_delete/'.html_escape($row->id));?>" class="dropdown-item delete_item"><i class="lni lni-trash-can"></i><?php echo trans('delete') ?></a>
                                    </div>
                                </div>
                              </td>

                          </tr>
                          
                        <?php $i++; endforeach; ?>
                      </tbody>
                  </table>
                  <?php else: ?>
                    <p class="p-4"><?php echo trans('no-data-founds') ?></p>
                  <?php endif; ?>
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

<?php $b=1; foreach ($payouts as $payout): ?>
<div class="modal fade" id="payoutModal_<?php echo html_escape($b) ?>">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header justify-content-between">
        <h5 class="modal-title font-weight-bold"><?php echo trans('withdrawal-method') ?> (<?php echo ucfirst($payout->payout_method) ?>)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="fs-14"><i class="lnib lni-close"></i></span>
        </button>
      </div>

      <div class="modal-body p-0">

        <?php if ($payout->payout_method == 'Paypal'): ?>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
            <?php echo trans('paypal-email') ?>
            <span class="text-dark"><?php echo html_escape($payout->method_details) ?></span>
          </li>
        </ul>
        <?php endif ?>

        <?php if ($payout->payout_method == 'Bank'): ?>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo trans('bank-details') ?>
            <span class="text-dark"><?php echo html_escape($payout->method_details) ?></span>
          </li>
        </ul>
        <?php endif ?>

      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php $b++; endforeach; ?>

