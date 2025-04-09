<div class="content-wrapper">

  <?php include APPPATH.'views/admin/include/breadcramb.php'; ?>

    <section class="content">
        <div class="container">
        	<div class="row">
            	<div class="col-md-12">
            		<h2>Record Payments
                        <div class="box-tools pull-right">
                           <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="text-right btn btn-secondary  btn-sm">< Back</a>
                        </div>
                    </h2>
                    
                    <div class="tab-content">
                        <!-- All -->
                        <div class="tab-pane active" id="messages2" role="tabpanel">

                            <?php if (!empty($selected_invoices)): ?>
                                <div class="table-responsive">
                                    <form method="POST" action="<?php echo base_url('admin/invoice/multiple_payment_record') ?>">
                                        <table class="table table-hover cushover">
                                            <thead>
                                                <tr class="item-row">
                                                    <th><?php echo trans('number') ?></th>
                                                    <th><?php echo trans('status') ?></th>
                                                    <th>Company</th>
                                                    <th>Contact</th>
                                                    <th>Issued Date</th>
                                                    <th><?php echo trans('total') ?></th>
                                                    <th>Payment Amount</th>
                                                    <th>Payment Method</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $i=1; foreach ($selected_invoices as $invoice): ?>
                                                        <tr id="row_<?php echo html_escape($invoice->id) ?>">
                                                            <td>
                                                                <a class="invoice_number_link" href="<?php echo base_url('admin/invoice/details/'.md5($invoice->id)) ?>"  >
                                                                    <p class="mb-0"> <?php echo html_escape($invoice->number) ?> </p>
                                                                </a>
                                                                <?php if ($invoice->recurring == 1): ?>
                                                                    <strong><?php echo trans('recurring') ?></strong>
                                                                <?php endif ?>
                                                            </td>
                                                            
                                                            <td>
                                                                <?php if ($invoice->status == 0): ?>
                                                                    <span data-toggle="tooltip" data-placement="right" title="<?php echo trans('draft-tooltip') ?>" class="custom-label-sm label-light-default"><?php echo trans('draft') ?></span>
                                                                <?php elseif($invoice->status == 2): ?>
                                                                    <?php if ($invoice->type == 4): ?>
                                                                        <span data-toggle="tooltip" data-placement="right" title="" class="custom-label-sm label-light-warning" style="width: 90px"><?php echo trans('credit-note') ?></span>
                                                                    <?php else: ?>
                                                                        <span data-toggle="tooltip" data-placement="right" title="<?php echo trans('paid-tooltip') ?>" class="custom-label-sm label-light-success"><?php echo trans('paid') ?></span>
                                                                    <?php endif ?>
                                                                <?php elseif($invoice->status == 1): ?>
                                                                    <?php if (check_paid_status($invoice->id) == 1): ?>
                                                                        <span data-toggle="tooltip" data-placement="right" title="<?php echo trans('partial-payment') ?>" class="custom-label-sm label-light-info"><?php echo trans('partial') ?></span>
                                                                    <?php else: ?>
                                                                        <span data-toggle="tooltip" data-placement="right" title="<?php echo trans('unpaid-tooltip') ?>" class="custom-label-sm label-light-danger"><?php echo trans('unpaid') ?></span>
                                                                    <?php endif ?>

                                                                <?php elseif($invoice->status == 5): ?>
                                                                    
                                                                        <span data-toggle="tooltip" data-placement="right" title="<?php echo trans('partial-payment') ?>" class="custom-label-sm label-light-info">Archive</span>
                                                                    
                                                                <?php endif ?>

                                                                <?php if ($invoice->recurring == 1): ?>
                                                                    <?php if ($invoice->is_completed == 0): ?>
                                                                        <span class="custom-label-sm label-light-success mt-5"><?php echo trans('active') ?></span>
                                                                    <?php elseif($invoice->is_completed == 1): ?>
                                                                        <span data-toggle="tooltip" data-placement="right" title="<?php echo trans('complete-tooltip') ?>" class="custom-label-sm label-light-danger mt-5"><?php echo trans('completed') ?></span>
                                                                    <?php endif ?>
                                                                <?php endif ?>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty(helper_get_customer($invoice->customer))): ?>
                                                                    <?php echo helper_get_customer($invoice->customer)->name ?>
                                                                    <?php 
                                                                        $currency_symbol = helper_get_customer($invoice->customer)->currency_symbol;
                                                                        if (isset($currency_symbol)) {
                                                                            $currency_symbol = $currency_symbol;
                                                                        } else {
                                                                            $currency_symbol = $this->business->currency_symbol;
                                                                        }
                                                                    ?>
                                                                    <?php $currency_code = helper_get_customer($invoice->customer)->currency_code ?>
                                                                <?php endif ?>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty(helper_get_customer($invoice->customer))): ?>
                                                                    <?php echo helper_get_customer($invoice->customer)->first_name ?> <?php echo helper_get_customer($invoice->customer)->last_name ?>
                                                                <?php endif ?>
                                                            </td>
                                                            
                                                            <td><?php echo my_date_show($invoice->date); ?></td>

                                                            
                                                            <td>
                                                                <span class="total-price"><?php echo price_formatted_alt($invoice->grand_total, $this->business->id, $currency_symbol) ?> </span><br>
                                                                <span class="conver-total"><?php echo price_formatted($invoice->convert_total, $this->business->id) ?></span>
                                                            </td>
                                                            
                                                            <td class="text-danger">
                                                                <input type="text" name="amount[]" class="form-control" value="<?php echo html_escape($invoice->grand_total - get_total_invoice_payments($invoice->id, 0)) ?>">
                                                            </td>
                                                            <td>
                                                                <select class="form-control" id="payment_method" name="payment_method[]" required>
                                                                    <option value=""><?php echo trans('select-payment-method') ?></option>
                                                                    <?php $i=1; foreach (get_payment_methods() as $payment): ?>
                                                                        <option value="<?php echo $i; ?>"><?php echo html_escape($payment); ?></option>
                                                                    <?php $i++; endforeach ?>
                                                                </select>
                                                            </td>

                                                    </tr>
                                                    <input type="hidden" name="id[]" value="<?php echo $invoice->id ?>">
                                                    <input type="hidden" name="customer_id[]" value="<?php echo $invoice->customer_id ?>">
                                                <?php $i++; endforeach ?>

                                            </tbody>
                                        </table>

                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                        <button type="submit" class="btn btn-info pull-right">Record Payment</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="pt-30"><strong>No Invoices Found</strong></div>
                            <?php endif ?>
                        </div>

                    </div>
                </div>

                <div class="col-md-12 text-center mt-50">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </section>
</div>


<?php foreach ($invoices as $invoice): ?>
    <?php include"include/send_invoice_modal.php"; ?>
<?php endforeach; ?>




<!-- Model Start -->
<div id="recordPaymentModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-zoom modal-md">
    <div class="modal-content modal-md" id="load_modal">
    

    </div>
  </div> 
</div>
<!-- Modal End-->


