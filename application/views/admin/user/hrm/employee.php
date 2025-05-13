<div class="content-wrapper">

  <!-- Main content -->
  <section class="content ">

    <div class="col-md-8 m-auto box add_area mt-50" style="display: <?php if ($page_title == "Edit") {
                                                                      echo "block";
                                                                    } else {
                                                                      echo "none";
                                                                    } ?>">

      <div class="box-header">
        <?php if (isset($page_title) && $page_title == "Edit"): ?>
          <h3><?php echo trans('edit-employee') ?> <a href="<?php echo base_url('admin/hrm/employee') ?>" class="pull-right btn btn-default rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
        <?php else: ?>
          <h3><?php echo trans('add-new-employee') ?> <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_btn"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
        <?php endif; ?>
      </div>

      <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form mt-20 p-30" action="<?php echo base_url('admin/hrm/employee_add') ?>" role="form" novalidate>

        <div class="form-group">
          <?php if (isset($page_title) && $page_title == "Edit"): ?>
            <img src="<?php echo base_url($employee[0]['thumb']) ?>"> <br><br>
          <?php endif ?>
          <label><?php echo trans('upload-image') ?></label><br>
          <input type="file" id="imgInp" name="photo">
        </div>


        <div class="form-group">
          <div class="form-group d-flex" style="gap: 20px;">
  
  <!-- Department Field -->
                  <div style="flex: 1;">
                    <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo trans('department') ?> </label>
                    <select class="form-control" name="department">
                      <option value=""><?php echo trans('select') ?></option>
                      <?php foreach ($departments as $department): ?>
                        <option value="<?php echo html_escape($department->id); ?>"
                          <?php if (!empty($employee) && $employee[0]['department_id'] == $department->id) echo 'selected'; ?>>
                          <?php echo html_escape($department->name); ?>
                        </option>
                      <?php endforeach ?>
                    </select>
                  </div>

                  <!-- Role Field -->
                  <div style="flex: 1;">
                  <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo trans('department') ?> </label>
                  <select class="form-control" name="role">
                  <option value=""><?php echo trans('select'); ?></option>
                  <?php foreach ($roles as $role): ?>
                    <option value="<?php echo html_escape($role->id); ?>"
                      <?php if (!empty($employee) && $employee[0]['role_id'] == $role->id) echo 'selected'; ?>>
                      <?php echo html_escape($role->name); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                  </div>
                </div>
          <label><?php echo trans('employee-name') ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" required name="name" value="<?php echo html_escape($employee[0]['name']); ?>">
        </div>

        <div class="form-group">
          <label><?php echo trans('email') ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" required name="email" value="<?php echo html_escape($employee[0]['email']); ?>">
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><?php echo trans('phone') ?></label>
              <input type="text" class="form-control" name="phone" value="<?php echo html_escape($employee[0]['phone']); ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label><?php echo trans('address') ?></span></label>
              <input type="text" class="form-control" name="address" value="<?php echo html_escape($employee[0]['address']); ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label><?php echo trans('city') ?> </label>
              <input type="text" class="form-control" name="city" value="<?php echo html_escape($employee[0]['city']); ?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo trans('country') ?> </label>
              <select class="form-control" name="country">
                <option value=""><?php echo trans('select') ?></option>
                <?php foreach ($countries as $country): ?>
                  <option value="<?php echo html_escape($country->id); ?>"
                    <?php if (!empty($employee) && $employee[0]['country'] == $country->id) echo 'selected'; ?>>
                    <?php echo html_escape($country->name); ?>
                  </option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="icheck-primary radio radio-inline d-inline mr-4 mt-2">
            <input type="radio" id="radioPrimary1" value="1" name="status" <?php if (!empty($employee) && $employee[0]['status'] == 1) {
                                                                              echo "checked";
                                                                            } ?>>
            <label for="radioPrimary1"> <?php echo trans('show') ?>
            </label>
          </div>

          <div class="icheck-primary radio radio-inline d-inline">
            <input type="radio" id="radioPrimary2" value="0" name="status" <?php if (!empty($employee) && $employee[0]['status'] == 0) {
                                                                              echo "checked";
                                                                            } ?>>
            <label for="radioPrimary2"> <?php echo trans('hide') ?>
            </label>
          </div>
        </div>


        <input type="hidden" name="id" value="<?php echo html_escape($employee['0']['id']); ?>">
        <!-- csrf token -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

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
    <!-- Bulk Upload Section -->
    <div class="col-md-8 m-auto box mt-50" id="bulk_upload_area" style="display: none;">
      <div class="box-header">
        <h3>
          bulk-upload-employee
          <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_bulk"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
        </h3>
      </div>

      <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/hrm/employee_import') ?>" class="validate-form mt-20 p-30">

        <div class="form-group">
          <label>upload-excel-file  (.xlsx or .csv)</label>
          <input type="file" class="form-control" name="import_file" accept=".csv, .xls, .xlsx" required>
        </div>

        <!-- CSRF Token -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

        <button type="submit" class="btn btn-success btn-rounded"><i class="fa fa-upload"></i> import</button>

        <!-- Download Sample Excel -->
        <a href="<?php echo base_url('admin/hrm/download_sample_excel') ?>" class="btn btn-primary btn-rounded ml-2">
          <i class="fa fa-download"></i> download-sample
        </a>
      </form>
    </div>

    <?php if (isset($page_title) && $page_title != "Edit"): ?>
      <div class="list_area container">

        <?php if (isset($page_title) && $page_title == "Edit"): ?>
          <h3 class="box-title"><?php echo trans('edit-employee') ?> <a href="<?php echo base_url('admin/hrm/employee') ?>" class="pull-right btn btn-primary rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
        <?php else: ?>
          <h3 class="box-title"><?php echo trans('employees') ?> <a href="#" class="pull-right btn btn-info btn-sm rounded upload mx-5">
              <i class="fa fa-plus"></i> Bulk upload</a>
            <a href="#" class="pull-right btn btn-info btn-sm rounded add_btn"><i class="fa fa-plus"></i> <?php echo trans('add-new-employee') ?></a>
          </h3>
        <?php endif; ?>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
          <table class="table table-hover cushover <?php if (count($employees) > 10) {
                                                      echo "datatable";
                                                    } ?>" id="dg_table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo trans('image') ?></th>
                <th><?php echo trans('name') ?></th>
                <th><?php echo trans('department') ?></th>
                <th><?php echo trans('address') ?></th>
                <th><?php echo trans('status') ?></th>
                <th><?php echo trans('action') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($employees as $employee): ?>
                <tr id="row_<?php echo html_escape($employee->id); ?>">

                  <td><?php echo $i; ?></td>
                  <td><img src="<?php echo base_url($employee->thumb) ?>"></td>
                  <td>
                    <p class="mb-0"><?php echo html_escape($employee->name); ?></p>
                    <p class="mb-0"><?php echo html_escape($employee->email); ?></p>
                    <p class="mb-0"><?php echo html_escape($employee->phone); ?></p>
                  </td>
                  <td><?php echo html_escape($employee->department_name) ?></td>
                  <td>
                    <p class="mb-0"><?php echo html_escape($employee->address); ?></p>
                    <p class="mb-0"><?php echo html_escape($employee->city); ?></p>
                    <p class="mb-0"><?php echo html_escape($employee->country_name); ?></p>
                  </td>
                  <td>
                    <?php if ($employee->status == 1): ?>
                      <span class="label label-success">Active</span>
                    <?php else: ?>
                      <span class="label label-danger">Dective</span>
                    <?php endif ?>
                  </td>

                  <td class="actions" width="15%">
                    <a href="<?php echo base_url('admin/hrm/employee_edit/' . html_escape($employee->id)); ?>" class="on-default edit-row" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>

                    <a data-val="employee" data-id="<?php echo html_escape($employee->id); ?>" href="<?php echo base_url('admin/hrm/employee_delete/' . html_escape($employee->id)); ?>" class="on-default remove-row delete_item" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                  </td>
                </tr>

              <?php $i++;
              endforeach; ?>
            </tbody>
          </table>
        </div>

      </div>
    <?php endif; ?>

  </section>
</div>
<script>
  $(document).ready(function() {
    $('.upload').on('click', function(e) {
      e.preventDefault();
      $('#bulk_upload_area').show();
      $('.list_area').hide();
    });

    $('.cancel_bulk').on('click', function(e) {
      e.preventDefault();
      $('#bulk_upload_area').hide();
      $('.list_area').show();
    });
  });
</script>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Make AJAX request to get user roles
    $.ajax({
        url: "<?= base_url('/employee/EmployeeRoles/get_user_roles'); ?>",
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 200) {
                // If roles are fetched successfully, populate the dropdown
                var roles = response.data;
                var $roleSelect = $('select[name="role"]');
                $roleSelect.empty(); // Clear any existing options
                $roleSelect.append('<option value=""><?php echo trans('select'); ?></option>'); // Default select option

                // Loop through roles and append them to the dropdown
                $.each(roles, function(index, role) {
                    $roleSelect.append('<option value="' + role.id + '">' + role.role_name + '</option>');
                });
            } else {
                console.log('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + error);
        }
    });
});

</script>