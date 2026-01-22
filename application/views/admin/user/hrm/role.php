<div class="content-wrapper role_management">
    <section class="content ">
        <div class="col-md-6 m-auto box add_area mt-50" style="display: <?php if ($page_title == "Edit") {
                                                                            echo "block";
                                                                        } else {
                                                                            echo "none";
                                                                        } ?>">

            <div class="box-header">
                <?php if (isset($page_title) && $page_title == "Edit"): ?>
                    <h3><?php echo "Edit Role" ?> <a href="<?php echo base_url('admin/roles_permissions/role_management') ?>" class="pull-right btn btn-default rounded btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
                <?php else: ?>
                    <h3><?php echo 'Add New Role'; ?> <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_btn"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
                <?php endif; ?>
            </div>

            <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form mt-20 p-30" action="<?php echo base_url('admin/hrm/role_add') ?>" role="form" novalidate>

                <div class="form-group">
                    <label><?php echo 'Departments' ?> <span class="text-danger">*</span></label>
                    <select class="form-control single_select" name="department">
                        <option value=""><?php echo trans('select') ?></option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?php echo html_escape($department->id); ?>"
                                <?php if (isset($department) && $role[0]['department_id'] == $department->id) echo "selected"; ?>>
                                <?php echo html_escape($department->name); ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo 'Role Name' ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" required name="name" value="<?php echo html_escape($role[0]['role_name']); ?>">
                </div>

                <div class="form-group">
                    <div class="icheck-primary radio radio-inline d-inline mr-4 mt-2">
                        <input type="radio" id="radioPrimary1" value="1" name="status" <?php if (!empty($role) && $role[0]['status'] == 1) {
                                                                                            echo "checked";
                                                                                        } ?>>
                        <label for="radioPrimary1"> <?php echo trans('show') ?>
                        </label>
                    </div>

                    <div class="icheck-primary radio radio-inline d-inline">
                        <input type="radio" id="radioPrimary2" value="0" name="status" <?php if (!empty($role) && $role[0]['status'] == 0) {
                                                                                            echo "checked";
                                                                                        } ?>>
                        <label for="radioPrimary2"> <?php echo trans('hide') ?>
                        </label>
                    </div>
                </div>


                <input type="hidden" name="id" value="<?php echo html_escape($role['0']['id']); ?>">
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

                <h3>
                    <h3 class="box-title"><?php echo "Role Management" ?>
                        <a href="<?php echo base_url('admin/roles_permissions') ?>" class="pull-right btn btn-default btn-sm rounded">
                            <i class="fa fa-angle-left"></i> <?php echo trans('back') ?>
                        </a>
                        <a href="#" class="pull-right btn btn-info btn-sm rounded add_btn mx-5"><i class="fa fa-plus"></i> <?php echo 'Add New Role' ?></a>
                    </h3>
                </h3>
                <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
                    <table class="table table-hover cushover <?php if (count($roles) > 10) {
                                                                    echo "datatable";
                                                                } ?>" id="dg_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo trans('department') ?></th>
                                <th><?php echo trans('name') ?></th>
                                <th><?php echo trans('status') ?></th>
                                <th><?php echo trans('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($roles as $role): ?>
                                <tr id="row_<?php echo html_escape($role->id); ?>">

                                    <td><?php echo $i; ?></td>
                                    <td><?php echo get_department($role->department_id)->name; ?></td>
                                    <td><?php echo html_escape($role->role_name); ?></td>
                                    <td>
                                        <?php if ($role->status == 1): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-danger">Dective</span>
                                        <?php endif ?>
                                    </td>

                                    <td class="actions" width="15%">
                                        <a href="<?php echo base_url('employee/EmployeeRoles/role_edit/' . html_escape($role->id)); ?>" class="on-default edit-row" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;

                                        <a data-val="role" data-id="<?php echo html_escape($role->id); ?>" href="<?php echo base_url('admin/hrm/role_delete/' . html_escape($role->id)); ?>" class="on-default remove-row delete_item" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
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

<!-- JS to update hidden status[] from radio buttons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('shown.bs.collapse', e => {
        e.target.previousElementSibling
            .querySelector('.rotate-icon')
            .classList.replace('fa-angle-down', 'fa-angle-up');
    });
    document.addEventListener('hidden.bs.collapse', e => {
        e.target.previousElementSibling
            .querySelector('.rotate-icon')
            .classList.replace('fa-angle-up', 'fa-angle-down');
    });
</script>