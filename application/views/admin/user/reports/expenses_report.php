<div class="content-wrapper">

  <?php include APPPATH.'views/admin/include/breadcramb.php'; ?>


  <!-- Main content -->
  <section class="content">

      <div class="col-md-8 m-auto box add_area mt-50" style="display: <?php if($page_title == "Edit"){echo "block";}else{echo "none";} ?>">
          
          <div class="box-header with-border">
              <?php if (isset($page_title) && $page_title == "Edit"): ?>
                <h3 class="box-title"><?php echo trans('edit-expense') ?> </h3>
              <?php else: ?>
                <h3 class="box-title">New Expense</h3>
              <?php endif; ?>

            <div class="box-tools pull-right">
              <?php if (isset($page_title) && $page_title == "Edit"): ?>
                <a href="<?php echo base_url('admin/expense') ?>" class="btn btn-default rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
              <?php else: ?>
                <a href="#" class="btn btn-default btn-sm rounded cancel_btn"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
              <?php endif; ?>
            </div>
          </div>

          <form method="post" enctype="multipart/form-data" class="validate-form mt-20 p-30" action="<?php echo base_url('admin/expense/add')?>" role="form" novalidate>

            <div class="form-group">
              <label>Subtotal Amount<span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="amount" value="<?php echo html_escape($expense[0]['amount']); ?>" >
            </div>

            <div class="form-group">
              <label><?php echo trans('tax') ?> %</label>
              <input type="number" class="form-control" name="tax" value="<?php echo html_escape($expense[0]['tax']); ?>" >
            </div>

            <div class="form-group">
                <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo trans('vendors') ?> </label>
                <select class="form-control" name="vendor">
                    <option value=""><?php echo trans('select') ?></option>
                    <?php foreach ($vendors as $vendor): ?>
                        <option value="<?php echo html_escape($vendor->id); ?>" 
                          <?php echo ($expense[0]['vendor'] == $vendor->id) ? 'selected' : ''; ?>>
                          <?php echo html_escape($vendor->name); ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo trans('expense-category') ?><span class="text-danger">*</span></label>
                <select class="form-control" name="category" required>
                    <option value=""><?php echo trans('select') ?></option>
                    <?php foreach ($expense_category as $category): ?>
                        <option value="<?php echo html_escape($category->id); ?>" 
                          <?php echo ($expense[0]['category'] == $category->id) ? 'selected' : ''; ?>>
                          <?php echo html_escape($category->name); ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-12 control-label p-0"><?php echo trans('date') ?> <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control datepicker" required placeholder="yyyy/mm/dd" name="date" value="<?php echo date('Y-m-d') ?>">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-calender"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
              <label><?php echo trans('notes') ?></label>
              <textarea class="form-control" name="notes"><?php echo html_escape($expense[0]['notes']); ?></textarea>
            </div>

            <div class="form-group">
              <label>Upload Image</label>
              <?php if (!empty($expense[0]['file'])): ?>
                <p><label class="label label-info"><?php echo $expense[0]['file'] ?></label></p>
              <?php endif ?>
              <label><?php echo trans('upload') ?></label>
              <input class="form-control" type="file" name="file">
            </div>
            

            <input type="hidden" name="id" value="<?php echo html_escape($expense['0']['id']); ?>">
            <!-- csrf token -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">


            <div class="row m-t-30">
              <div class="col-sm-12">
                <?php if (isset($page_title) && $page_title == "Edit"): ?>
                  <button type="submit" class="btn btn-info btn-rounded pull-left"><i class="fa fa-check"></i> <?php echo trans('save-changes') ?></button>
                <?php else: ?>
                  <button type="submit" class="btn btn-info btn-rounded pull-left"><i class="fa fa-check"></i> <?php echo trans('save') ?></button>
                <?php endif; ?>
              </div>
            </div>

          </form>
      </div>

      <?php if (isset($page_title) && $page_title != "Edit"): ?>
        <div class="list_area container">
          
          <?php if (isset($page_title) && $page_title == "Edit"): ?>
            <h3 class="box-title"><?php echo trans('edit-expense') ?> <a href="<?php echo base_url('admin/expense') ?>" class="pull-right btn btn-primary rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
          <?php endif; ?>

          <form method="GET" class="sort_invoice_form" action="<?php echo base_url('admin/reports/expenses') ?>">
                        <div class="row p-15 mt-20 mb-20">
                            <div class="col-md-4 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="vendor">
                                    <option value="">All Vendors</option>
                                    <?php foreach ($vendors as $vendor): ?>
                                      <option value="<?php echo html_escape($vendor->id) ?>" <?php echo(isset($_GET['vendor']) && $_GET['vendor'] == $vendor->id) ? 'selected' : ''; ?>
                                      ><?php echo html_escape($vendor->name) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-3 col-xs-12 mt-5 pl-0">
                                <div class="input-group">
                                    <input type="text" class="inv-dpick form-control datepicker" placeholder="<?php echo trans('from') ?>" name="start_date" value="<?php if(isset($_GET['start_date'])){echo $_GET['start_date'];} ?>" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-12 mt-5 pl-0">
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

          <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
              <table class="table table-hover cushover <?php if(count($expenses) > 10){echo "datatable";} ?>" id="dg_table">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th><?php echo trans('date') ?></th>
                          <th><?php echo trans('amount') ?></th>
                          <th><?php echo trans('tax') ?></th>
                          <th><?php echo trans('client') ?></th>
                          <th><?php echo trans('category') ?></th>
                          <th><?php echo trans('notes') ?></th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php $tax=0; $netamount=0; ?>
                    <?php $i=1; foreach ($expenses as $expense): ?>
                       <?php $tax+= ($expense->net_amount)-($expense->amount); ?>
                       <?php $netamount+= ($expense->net_amount); ?>
                      <tr id="row_<?php echo html_escape($expense->id); ?>">
                          
                          
                          <td><?php echo $i; ?></td>
                          <td><?php echo my_date_show($expense->date); ?></td>
                          <td><?php echo price_formatted($expense->net_amount, $this->business->id); ?></td>
                          <td><?php echo price_formatted($expense->net_amount-$expense->amount, $this->business->id); ?> (<?php echo $expense->tax  ?>%)</td>
                          <td><?php echo html_escape($expense->vendor_name); ?></td>
                          <td><?php echo html_escape($expense->category_name); ?></td>
                          <td><?php echo html_escape($expense->notes); ?></td>
                      </tr>
                      
                    <?php $i++; endforeach; ?>
                  <?php echo $sum ?>
                  </tbody>
                  <tbody>
                    <tr>
                      <td></td>
                      <td style="font-size:20px;">Total</td>
                      <td style="font-size:20px;"><b><?php echo price_formatted($netamount,$this->business->id) ?></b></td>
                      <td style="font-size:20px;"><b><?php echo price_formatted($tax,$this->business->id) ?></b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
              </table>
          </div>

        </div>
      <?php endif; ?>

  </section>
</div>
