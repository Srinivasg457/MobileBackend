<div class="content-wrapper">

  <?php include APPPATH.'views/admin/include/breadcramb.php'; ?>

    <section class="content">
        <div class="container">
        	<div class="row">
            	<div class="col-md-12">
            		<h2>Payments 
                    <?php if (check_payment_status() == TRUE || settings()->enable_paypal == 0 || user()->user_type == 'trial'): ?>

                        <a href="<?php echo base_url('admin/invoice/archives') ?>" class="pull-right btn btn-info btn-rounded btn-sm mx-5 batch_btn" style="display: none;">Archive</a>

                        <a href="<?php echo base_url('admin/invoice/deletes') ?>" class="pull-right btn btn-info btn-rounded btn-sm mx-5 batch_btn" style="display: none;">Delete</a>


                        <a href="<?php echo base_url('admin/invoice/approve') ?>" class="pull-right btn btn-info btn-rounded btn-sm mx-5 batch_btn" style="display: none;">Approve</a>
                        <a href="<?php echo base_url('admin/invoice/record_payment') ?>" class="pull-right btn btn-info btn-rounded btn-sm mx-5 batch_btn" style="display: none;">Record Payment</a>

                        <a href="<?php echo base_url('admin/payment/export_payment') ?>" class="btn btn-info btn-rounded pull-right d-none">Export</a>

                        <a href="<?php echo base_url('admin/invoice/payment_record/?payment=paid') ?>" class="btn btn-info btn-rounded pull-right mx-5"><i class="fa fa-plus"></i> New Payment</a>

                    <?php endif; ?>
                 </h2>

                    <form method="GET" class="sort_invoice_form" action="<?php echo base_url('admin/payment/payment_record/3') ?>">
                        <div class="row p-15 mt-20 mb-20">
                            <div class="col-md-4 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="customer">
                                    <option value="">All Customers</option>
                                    <?php foreach ($customers as $customer): ?>
                                      <option value="<?php echo html_escape($customer->id) ?>" <?php echo(isset($_GET['customer']) && $_GET['customer'] == $customer->id) ? 'selected' : ''; ?>
                                      ><?php echo html_escape($customer->name) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-3 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="payment_method">
                                   <option value="" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 0) ? 'selected' : ''; ?>>Payment Method</option>
                                   <option value="1" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 1) ? 'selected' : ''; ?>>Bank payment</option>
                                   <option value="2" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 2) ? 'selected' : ''; ?>>Cash</option>
                                   <option value="3" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 3) ? 'selected' : ''; ?>>Cheque</option>
                                   <option value="4" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 4) ? 'selected' : ''; ?>>Stripe</option>
                                   <option value="5" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 5) ? 'selected' : ''; ?>>Paypal</option>
                                   <option value="6" <?php echo(isset($_GET['payment_method']) && $_GET['payment_method'] == 6) ? 'selected' : ''; ?>>Others</option>
                                   
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
                        
                            <div class="col-md-1 col-xs-12 mt-5 pl-0">
                                <button type="submit" class="btn btn-info btn-report btn-block custom_search"><i class="flaticon-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="tab-content">
                        <!-- All -->
                        <div class="tab-pane active" id="messages2" role="tabpanel">

                            <?php if (!empty($payment_records)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover cushover dt_btn">
                                        <thead>
                                            <tr class="item-row">
                                                <th><?php echo trans('number') ?></th>
                                                <th>Company</th>
                                                <th>Contact</th>
                                                <th>Payment Date</th>
                                                <th>Payment Method</th>
                                                <th>Amount Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $i=1; foreach ($payment_records as $payment_record): ?>
                                                    <tr id="row_<?php echo html_escape($payment_record->id) ?>">

                                                       <td><?php echo html_escape($payment_record->invoice_number) ?></td>     
                                                       <td><?php echo html_escape($payment_record->customer_name) ?></td>     
                                                       <td><?php echo html_escape($payment_record->first) ?> <?php echo html_escape($payment_record->last) ?></td>     
                                                       <td><?php echo my_date_show($payment_record->payment_date) ?></td>     
                                                       <td>
                                                            <?php if ($payment_record->payment_method==1): ?>
                                                                Bank payment
                                                            <?php elseif ($payment_record->payment_method==2): ?>
                                                                Cash
                                                            <?php elseif ($payment_record->payment_method==3): ?>
                                                                Cheque
                                                            <?php elseif ($payment_record->payment_method==4): ?>
                                                                Stripe
                                                            <?php elseif ($payment_record->payment_method==5): ?>
                                                                Paypal
                                                            <?php else: ?>
                                                                Others    
                                                            <?php endif; ?>
                                                            
                                                            
                                                        </td>     
                                                       <td>
                                                            <span class="total-price"> <?php echo price_formatted_alt($payment_record->amount, $this->business->id, $currency_symbol) ?> </span><br>
                                                            <span class="conver-total"><?php echo price_formatted($payment_record->convert_amount, $this->business->id) ?></span>
                                                        </td>     
                                                    
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







<!-- Model Start -->
<div id="recordPaymentModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-zoom modal-md">
    <div class="modal-content modal-md" id="load_modal">
    

    </div>
  </div> 
</div>
<!-- Modal End-->


