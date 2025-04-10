<div class="content-wrapper">

  <?php include APPPATH.'views/admin/include/breadcramb.php'; ?>

    <section class="content">
        <div class="container">
        	<div class="row">
            	<div class="col-md-12">
            		<h2><?php echo trans('invoices') ?> Report</h2>

                    <form method="GET" class="sort_invoice_form" action="<?php echo base_url('admin/Reports/invoice_details') ?>">
                        <div class="row p-15 mt-20 mb-20">
                            <div class="col-md-3 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="customer">
                                    <option value="">All Customers</option>
                                    <?php foreach ($customers as $customer): ?>
                                      <option value="<?php echo html_escape($customer->id) ?>" <?php echo(isset($_GET['customer']) && $_GET['customer'] == $customer->id) ? 'selected' : ''; ?>
                                      ><?php echo html_escape($customer->name) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-3 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="product">
                                    <option value="">All Items</option>
                                    <?php foreach ($products as $product): ?>
                                      <option value="<?php echo html_escape($product->id) ?>" <?php echo(isset($_GET['product']) && $_GET['product'] == $product->id) ? 'selected' : ''; ?>
                                      ><?php echo html_escape($product->name) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-3 col-xs-12 mt-5 pl-0 d-none">
                                <select class="form-control single_select sort" name="status">
                                   <option value="" <?php echo(isset($_GET['status']) && $_GET['status'] == 0) ? 'selected' : ''; ?>>All Status</option>
                                   <option value="5" <?php echo(isset($_GET['status']) && $_GET['status'] == 5) ? 'selected' : ''; ?> >Archived</option>
                                   <option value="4" <?php echo(isset($_GET['status']) && $_GET['status'] == 4) ? 'selected' : ''; ?> ><?php echo trans('draft') ?></option>
                                   <option value="2" <?php echo(isset($_GET['status']) && $_GET['status'] == 2) ? 'selected' : ''; ?> ><?php echo trans('paid') ?></option>

                                   <option value="2" <?php echo(isset($_GET['status']) && $_GET['status'] == 2) ? 'selected' : ''; ?> >Partial</option>
                                   <!-- <option value="2" <?php //echo(isset($_GET['status']) && $_GET['status'] == 2) ? 'selected' : ''; ?> >partial</option> -->
                                   <option value="3" <?php echo(isset($_GET['status']) && $_GET['status'] == 3) ? 'selected' : ''; ?> ><?php echo trans('sent') ?></option>
                                   <option value="1" <?php echo(isset($_GET['status']) && $_GET['status'] == 1) ? 'selected' : ''; ?> ><?php echo trans('unpaid') ?></option>
                                   
                                   
                                </select>
                            </div>

                            <div class="col-md-2 col-xs-12 mt-5 pl-0">
                                <div class="input-group">
                                    <input type="text" class="inv-dpick form-control datepicker" placeholder="<?php echo trans('from') ?>" name="start_date" value="<?php if(isset($_GET['start_date'])){echo $_GET['start_date'];} ?>" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-2 col-xs-12 mt-5 pl-0">
                                <div class="input-group">
                                    <input type="text" class="inv-dpick form-control datepicker" placeholder="<?php echo trans('to') ?>" name="end_date" value="<?php if(isset($_GET['end_date'])){echo $_GET['end_date'];} ?>" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        
                            <div class="col-md-2 col-xs-12 mt-5 pl-0">
                                <button type="submit" class="btn btn-info btn-report btn-block custom_search"><i class="flaticon-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>


                    <div class="tab-content">
                        <!-- All -->
                        <div class="tab-pane active" id="messages2" role="tabpanel">

                            <?php if (!empty($invoice_details)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover cushover">
                                        <thead>
                                            <tr class="item-row">
                                                <th><?php echo trans('number') ?></th>
                                                <th><?php echo trans('status') ?></th>
                                                <th>Company</th>
                                                <th>Contact</th>
                                                <th>Issued Date</th>
                                                <th><?php echo trans('total') ?></th>
                                                <th><?php echo trans('amount-due') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $i=1; foreach ($invoice_details as $invoice): ?>
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
                                    </table>
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









