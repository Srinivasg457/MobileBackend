<div class="content-wrapper">

  <!-- Main content -->
  <section class="content ">

      <div class="col-md-6 m-auto box add_area mt-50" style="display: <?php if($page_title == "Edit"){echo "block";}else{echo "none";} ?>">

          <div class="box-header">
            <?php if (isset($page_title) && $page_title == "Edit"): ?>
              <h3><?php echo trans('edit-attendence') ?> <a href="<?php echo base_url('admin/hrm/attendance') ?>" class="pull-right btn btn-default rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
            <?php else: ?>
              <h3><?php echo trans('add-new-attendence') ?> <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_btn"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
            <?php endif; ?>
          </div>
  
          <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form mt-20 p-30" action="<?php echo base_url('admin/hrm/attendance_add')?>" role="form" novalidate>

            <div class="form-group">
                <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo trans('employee') ?> </label>
                <select class="form-control" name="employee">
                    <option value=""><?php echo trans('select') ?></option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo html_escape($employee->id); ?>" 
                          <?php if(!empty($attendence) && $attendence[0]['employee_id'] == $employee->id) echo 'selected'; ?>>
                          <?php echo html_escape($employee->name); ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
              <label><?php echo trans('date') ?></label>
              <div class="input-group mb-3">
                <input type="text" class="inv-dpick form-control datepicker"  name="date" value="<?php echo html_escape($attendence[0]['date']); ?>" autocomplete="off">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>


            

            <div class="form-group">
              <label><?php echo trans('check-in') ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control timepicker2" required name="check_in" value="<?php echo html_escape($attendence[0]['check_in']); ?>" >
            </div>

            <div class="form-group">
              <label><?php echo trans('check-out') ?></label>
              <input type="text" class="form-control timepicker2"  name="check_out" value="<?php echo html_escape($attendence[0]['check_out']); ?>" >
            </div>

            <div class="form-group">
              <label><?php echo trans('note') ?></span></label>
              <textarea class="form-control"  name="note"><?php echo html_escape($attendence[0]['note']); ?></textarea>
            </div>
            

            <input type="hidden" name="id" value="<?php echo html_escape($attendence['0']['id']); ?>">
            <!-- csrf token -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

            <hr>

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
            <h3 class="box-title"><?php echo trans('edit') ?> <a href="<?php echo base_url('admin/hrm/attendance') ?>" class="pull-right btn btn-primary rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
          <?php else: ?>
            <h3 class="box-title"><?php echo trans('attendence') ?> <a href="#" class="pull-right btn btn-info btn-sm rounded add_btn"><i class="fa fa-plus"></i> <?php echo trans('add-new-attendence') ?></a></h3>
          <?php endif; ?>

          <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
              <table class="table table-hover cushover <?php if(count($attendances) > 10){echo "datatable";} ?>" id="dg_table">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th><?php echo trans('name') ?></th>
                          <th><?php echo trans('department') ?></th>
                          <th><?php echo trans('date') ?></th>
                          <th><?php echo trans('check-in') ?></th>
                          <th><?php echo trans('check-out') ?></th>
                          <th><?php echo trans('note') ?></th>
                          <th><?php echo trans('status') ?></th>
                          <th><?php echo trans('action') ?></th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php $i=1; foreach ($attendances as $attendance): ?>
                      <tr id="row_<?php echo html_escape($attendance->id); ?>">
                          
                          <td><?php echo $i; ?></td>
                          <td>
                            <?php echo html_escape($attendance->employee_name); ?>
                          </td>
                          <td><?php echo html_escape($attendance->department_name) ?></td>

                          <td>
                            <?php echo html_escape($attendance->date); ?>
                          </td>

                          <td>
                            <?php echo html_escape($attendance->check_in); ?>
                          </td>

                          <td>
                            <?php echo html_escape($attendance->check_out); ?>
                          </td>

                          <td>
                            <?php echo html_escape($attendance->note); ?>
                          </td>

                          <td>
                            <?php if ($attendance->check_in == $this->business->default_check_in): ?>
                              <span class="label label-success">Present</span>
                            <?php else: ?>
                              <span class="label label-danger">Late</span>
                            <?php endif ?>
                          </td>

                          <td class="actions" width="15%">
                            <a href="<?php echo base_url('admin/hrm/attendance_edit/'.html_escape($attendance->id));?>" class="on-default edit-row" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>  

                            <a data-val="attendance" data-id="<?php echo html_escape($attendance->id); ?>" href="<?php echo base_url('admin/hrm/attendance_delete/'.html_escape($attendance->id));?>" class="on-default remove-row delete_item" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                          </td>
                      </tr>
                      
                    <?php $i++; endforeach; ?>
                  </tbody>
              </table>
          </div>

        </div>
      <?php endif; ?>

  </section>
</div>
