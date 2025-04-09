<div class="content-wrapper">

  <?php include APPPATH.'views/admin/include/breadcramb.php'; ?>

    <section class="content">
        <div class="container">
        	<div class="row">
            	<div class="col-md-12">
            		<h2><?php echo trans('invoices') ?> 
                    
                 </h2>

                    <form method="GET" class="sort_invoice_form" action="<?php echo base_url('admin/Reports/account_report_invoice') ?>">
                        <div class="row p-15 mt-20 mb-20">

                            <div class="col-md-8 col-xs-12 mt-5 pl-0 ">
                                <select class="form-control single_select sort" name="due_time">
                                   <option value="" <?php echo(isset($_GET['due_time']) && $_GET['due_time'] == 0) ? 'selected' : ''; ?>>Select</option>
                                   <option value="30" <?php echo(isset($_GET['due_time']) && $_GET['due_time'] == 30) ? 'selected' : ''; ?> >0-30 Days</option>
                                   <option value="59" <?php echo(isset($_GET['due_time']) && $_GET['due_time'] == 59) ? 'selected' : ''; ?> >31-59 Days</option>
                                   <option value="89" <?php echo(isset($_GET['due_time']) && $_GET['due_time'] == 89) ? 'selected' : ''; ?> >60-89 Days</option>
                                   <option value="90" <?php echo(isset($_GET['due_time']) && $_GET['due_time'] == 90) ? 'selected' : ''; ?> >90+ Days </option>
                                   
                                   
                                </select>
                            </div>
                        
                            <div class="col-md-4 col-xs-12 mt-5 pl-0">
                                <button type="submit" class="btn btn-info btn-report btn-block custom_search"><i class="flaticon-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>


                    <div class="tab-content">
                        <!-- All -->
                        <div class="tab-pane active" id="messages2" role="tabpanel">

                            <?php if (!empty($invoice_accounts)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover cushover">
                                        <thead>
                                            <tr class="item-row">
                                                <th><?php echo trans('number') ?></th>
                                                <th><?php echo trans('status') ?></th>
                                                <th>Company</th>
                                                <th>Contact</th>
                                                <th>Issued Date</th>
                                                <th>Due Date</th>
                                                <th><?php echo trans('total') ?></th>
                                                <th><?php echo trans('amount-due') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $total=0; $amountdue=0; ?>

                                            <?php $i=1; foreach ($invoice_accounts as $invoice): ?>
                                                    <tr id="row_<?php echo html_escape($invoice->id) ?>">
                                                        <?php $total+= ($invoice->convert_total); ?>
                                                        <?php $amountdue+= ($invoice->grand_total - get_total_invoice_payments($invoice->id, 0)); ?>
                                                        
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
                                                        <td><?php echo my_date_show($invoice->payment_due); ?></td>

                                                    <?php if($invoice->status == 2): ?>
                                                        <td>
                                                            <span class="total-price"> <?php echo price_formatted_alt($invoice->grand_total, $this->business->id, $currency_symbol) ?> </span><br>
                                                            <span class="conver-total"><?php echo price_formatted($invoice->convert_total, $this->business->id) ?></span>
                                                        </td>
                                                        <td>
                                                            <span class="total-price"><?php echo price_formatted_alt(0, $this->business->id, $currency_symbol) ?> </span>
                                                            <br>
                                                            <span class="conver-total"><?php echo price_formatted('0', $this->business->id) ?></span>
                                                        </td>
                                                    <?php else: ?>
                                                        <td>
                                                            <span class="total-price"><?php echo price_formatted_alt($invoice->grand_total, $this->business->id, $currency_symbol) ?> </span><br>
                                                            <span class="conver-total"><?php echo price_formatted($invoice->convert_total, $this->business->id) ?></span>
                                                        </td>
                                                        
                                                        <td class="text-danger">
                                                            <span class="total-price">
                                                                <?php $due_total = $invoice->grand_total - get_total_invoice_payments($invoice->id, 0); ?>
                                                                <?php echo price_formatted_alt($due_total, $this->business->id, $currency_symbol) ?> 
                                                            </span><br>
                                                            <?php if ($invoice->status != 1): ?>
                                                                
                                                                <span class="conver-total"><?php echo price_formatted($invoice->convert_total, $this->business->id) ?></span>
                                                            <?php endif ?>
                                                        </td>
                                                    <?php endif ?>
                                                </tr>
                                            <?php $i++; endforeach ?>

                                        </tbody>
                                        <tbody>
                                            <tr>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td style="font-size:20px;">Total</td>
                                              <td style="font-size:20px;"><b><?php echo price_formatted($total,$this->business->id) ?></b></td>
                                              <td style="font-size:20px;"><b><?php echo price_formatted($amountdue,$this->business->id) ?></b></td>
                                            </tr>
                                          </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="pt-30"><strong>No Invoice Found</strong></div>
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









