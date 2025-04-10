
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
  jQuery('.datepicker').datepicker({
      format: 'yyyy-mm-dd'
  });
</script>


<form method="post" enctype="multipart/form-data" class="validate-form record_payment_form" action="<?php echo base_url('admin/invoice/record_payment/'.md5($invoice->id))?>" role="form" novalidate>
    <div class="modal-header">
        <h4 class="modal-title" id="vcenter"><?php echo trans('record-a-payment-for-this-invoice') ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        
        <?php $records = get_customer_advanced_record($invoice->customer) ?>
        <?php if (!empty($records) && $records->customer_id == $invoice->customer): ?>
            <?php if ($records->amount != 0): ?>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>You have reserve amount for this customer: <?php echo $records->amount.' '.$records->currency; ?></strong>
                </div>
            <?php endif ?>
        <?php endif ?>

        <div class="form-group m-t-30" style="display: none">
            <input type="checkbox" name="is_autoload_amount" value="1" <?php if($this->business->is_autoload_amount == 1){echo 'checked';} ?> data-toggle="toggle" data-onstyle="info" data-width="100"></span>

            <label> is autoload advance amount in your next invoice?</label>
        </div>


        <p class="text-muted"><?php echo trans('record-payment-info') ?></p><br>
        
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 text-right control-label col-form-label"><?php echo trans('payment-date') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control datepicker" placeholder="yyyy/mm/dd" name="payment_date" value="<?php echo date('Y-m-d') ?>">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-calender"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 text-right control-label col-form-label"><?php echo trans('due-date') ?></label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control datepicker" placeholder="<?php echo trans('enter-the-due-date-for-partial-payment') ?>" name="due_date" value="" autocomplete="off">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-calender"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 text-right control-label col-form-label"><?php echo trans('amount') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-8">
               <input type="text" class="form-control" name="amount" value="<?php echo html_escape($invoice->grand_total - get_total_invoice_payments($invoice->id, 0)) ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 text-right control-label col-form-label"><?php echo trans('payment-method') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <select class="form-control" id="tax" name="payment_method" required>
                    <option value=""><?php echo trans('select-payment-method') ?></option>
                    <?php $i=1; foreach (get_payment_methods() as $payment): ?>
                        <option value="<?php echo $i; ?>"><?php echo html_escape($payment); ?></option>
                    <?php $i++; endforeach ?>
                </select>  
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 text-right control-label col-form-label"><?php echo trans('memo-notes') ?></label>
            <div class="col-sm-8">
                <textarea class="form-control" name="note"> </textarea>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <input type="hidden" name="invoice_id" value="<?php echo html_escape(md5($invoice->id)) ?>">
        <!-- csrf token -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
        <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo trans('add-payment') ?></button>
    </div>

</form>