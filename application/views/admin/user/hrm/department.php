<style>
  .icheck-primary {
    padding: 3px 10px 0px 5px;
  }

  .icheck-primary:hover {
    background-color: #16D17F;
    border-radius: 50px;
  }

  .icheck-danger {
    padding: 3px 10px 0px 5px;
  }

  .icheck-danger:hover {
    background-color: #FE5152;
    border-radius: 50px;
  }
</style>
<div class="content-wrapper">
  <?php
  $restricted_departments = ['Executive', 'Manager', 'Team Lead', 'Human Resource'];
  $can_view_all_departments = $this->auth_model->is_access_for_all_role();
  ?>
  <!-- Main content -->
  <section class="content ">

    <div class="col-md-6 m-auto box add_area mt-50" style="display: <?php if ($page_title == "Edit") {
                                                                      echo "block";
                                                                    } else {
                                                                      echo "none";
                                                                    } ?>">

      <div class="box-header">
        <?php if (isset($page_title) && $page_title == "Edit"): ?>
          <h3><?php echo trans('edit-department') ?> <a href="<?php echo base_url('admin/hrm/department') ?>" class="pull-right btn btn-default rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
        <?php else: ?>
          <h3><?php echo "Department Management" ?> <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_btn"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
        <?php endif; ?>
      </div>

      <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form mt-20 p-30" action="<?php echo base_url('admin/hrm/department_add') ?>" role="form" novalidate>

        <?php
        // Map existing department names and their status (e.g., from 'departments' table)
        $dept_status_map = [];
        foreach ($departments as $dept) {
          $dept_status_map[trim($dept->department_id)] = $dept->status;
        }
        ?>

        <?php foreach ($default_departments as $index => $default_dept): ?>
          <?php
          $dept_name = trim($default_dept->name);
          if (!$can_view_all_departments && in_array($dept_name, $restricted_departments)) {
            continue; // Skip restricted departments
          }
          ?> <?php
              $dept_id   = $default_dept->id;
              $dept_name = trim($default_dept->name);
              $status    = isset($dept_status_map[$dept_id]) ? 1 : 0;
              ?>
          <div class="form-group row align-items-center my-4">
            <div class="col-sm-7 ps-5">
              <label class="mb-1 font-weight-bold"><?= $dept_name ?></label>
              <input type="hidden" name="name[]" value="<?= $dept_name ?>">
              <input type="hidden" name="department_id[]" value="<?= $dept_id ?>">
              <input type="hidden" name="status[]" id="dept-status-<?= $index ?>" value="<?= $status ?>">
            </div>

            <div class="col-sm-5 d-flex align-items-center justify-content-between">
              <div class="icheck-primary radio radio-inline mr-3">
                <input type="radio" name="status_<?= $index ?>" id="show_<?= $index ?>" value="1" <?= ($status == 1) ? 'checked' : '' ?>>
                <label for="show_<?= $index ?>">Active</label>
              </div>
              <div class="icheck-danger radio radio-inline">
                <input type="radio" name="status_<?= $index ?>" id="hide_<?= $index ?>" value="0" <?= ($status == 0) ? 'checked' : '' ?>>
                <label for="hide_<?= $index ?>">Inactive</label>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <input type="hidden" name="id" value="<?php echo html_escape($department['0']['id']); ?>">
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


    <?php if (isset($page_title) && $page_title != "Edit"): ?>
      <div class="list_area container">

        <?php if (isset($page_title) && $page_title == "Edit"): ?>
          <h3 class="box-title"><?php echo trans('edit-department') ?> <a href="<?php echo base_url('admin/hrm/department') ?>" class="pull-right btn btn-primary rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
        <?php else: ?>
          <h3 class="box-title"><?php echo ('Department') ?>
            <?php if ($can_edit): ?>
              <a href="#" class="pull-right btn btn-info btn-sm rounded add_btn"><i class="fa fa-plus"></i> <?php echo "Manage Departments" ?></a>
          </h3>
        <?php else: ?>
          <button data-toggle="tooltip" data-placement="top" title="permission denied to manage Departments" class=" pull-right btn btn-default btn-sm m-5"><i class="fa fa-plus"></i> <?php echo "Manage Departments" ?></button>
        <?php endif; ?>

      <?php endif; ?>

      <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
        <table class="table table-hover cushover <?php if (count($departments) > 10) {
                                                    echo "datatable";
                                                  } ?>" id="dg_table">
          <thead>
            <tr>
              <th>#</th>
              <th><?php echo trans('name') ?></th>
              <th><?php echo trans('status') ?></th>
              <th><?php echo trans('action') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            foreach ($departments as $department): ?>
              <?php
              if (!$can_view_all_departments && in_array(trim($department->name), $restricted_departments)) {
                continue; // Hide restricted department from list
              }
              ?>
              <tr id="row_<?php echo html_escape($department->id); ?>">

                <td><?php echo $i; ?></td>
                <td><?php echo html_escape($department->name); ?></td>
                <td>
                  <?php if ($department->status == 1): ?>
                    <span class="label label-success">Active</span>
                  <?php else: ?>
                    <span class="label label-danger">Dective</span>
                  <?php endif ?>
                </td>

                <td class="actions" width="15%">
                  <a href="<?php echo base_url('admin/hrm/department_edit/' . html_escape($department->id)); ?>" class="on-default edit-row hide" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;
                  <?php if ($can_edit): ?>
                    <a data-val="department" data-id="<?php echo html_escape($department->id); ?>" href="<?php echo base_url('admin/hrm/department_delete/' . html_escape($department->id)); ?>" class="on-default remove-row delete_item" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                  <?php else: ?>
                    <a class="on-default" data-toggle="tooltip" data-placement="top" title="permission deined to delete department"><i class="fa fa-trash-o"></i></a>
                  <?php endif; ?>
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
    $('input[type=radio]').on('change', function() {
      var index = $(this).attr('name').split('_')[1];
      var value = $(this).val();
      $('#dept-status-' + index).val(value);
    });
  });
</script>