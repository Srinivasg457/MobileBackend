<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <!-- Main content -->
  <div class="content">
    <div class="container">
        <div class="row">
          <div class="col-md-12">

            <?php if (isset($page_title) && $page_title != "Edit"): ?>
              <div class="list_area">
                    <h3 class="box-title pt-2"><?php echo trans('referrals') ?></h3>
                </div>

                <div class="card-body table-responsive p-0">

                    <table class="table table-hover text-nowrap <?php if(count($referrals) > 5){echo "datatable";} ?>">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo trans('referrar-id') ?></th>
                                <th><?php echo trans('order-id') ?></th>
                                <th><?php echo trans('amount') ?></th>
                                <th><?php echo trans('commision') ?>(%)</th>
                                <th><?php echo trans('commision-amount') ?></th>
                                <th><?php echo trans('date') ?></th>
                                <th><?php echo trans('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php $i=1; foreach ($referrals as $referral): ?>
                            <tr id="row_<?php echo html_escape($referral->id); ?>">
                                
                                <td><?= $i; ?></td>
                                <td><span class="badge badge-info"><?php echo html_escape($referral->referrar_id) ?></span></td>
                                <td><?php echo html_escape($referral->order_id) ?></td>
                                <td>
                                  <?php echo price_formatted($referral->amount, 'site') ?>
                                </td>
                                <td><?php echo html_escape($referral->commision) ?></td>
                                <td>
                                  <?php echo price_formatted($referral->commision_amount, 'site') ?>  
                                </td> 
                                <td><?php echo html_escape($referral->created_at) ?></td> 
                                <td class="actions">
                                    <a data-val="Category" data-id="<?php echo html_escape($referral->id); ?>" href="<?php echo base_url('admin/referral/referral_delete/'.html_escape($referral->id));?>" class="on-default remove-row delete_item"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            
                          <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                  
                </div>

              </div>
            <?php endif; ?>

          </div>
      </div>
    </div>
  </div>
</div>
