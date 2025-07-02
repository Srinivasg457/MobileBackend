<style>
    /* .content-wrapper,
    body,
    .wrapper {
        height: unset !important;
        min-height: unset !important;
        overflow-x: unset !important;
        overflow-y: unset !important;
    } */

    .actions {
        display: flex;
        gap: 10px;

        >a {
            color: black;

            >i {
                transition: color 0.3s;
                font-size: 20px;
                margin-top: 5px;
            }
        }

        >a:hover>i {
            color: green;
        }

        >a:nth-child(2):hover>i {
            color: red;
        }
    }

    [type=checkbox]:checked,
    [type=checkbox]:not(:checked) {
        position: static;
        left: none;
        opacity: 9999;
    }

    .toast {
        padding: 10px;
        margin: 5px;
        border-radius: 4px;
        color: #fff;
        min-width: 200px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .toast-success {
        background-color: #28a745;
    }

    .toast-error {
        background-color: #e74c3c;
    }

    .is-invalid {
        border: 2px solid #e74c3c;
        background-color: #fcebea;
    }

    #toast-container {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
    }

    .rolesandpermission .role {
        height: 475px;
        overflow-y: auto;
    }

    .permission-table {
        width: 100%;
    }

    .permission-table th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }

    .select-all-container {
        margin-bottom: 15px;
    }

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


<!-- Roles & Permissions Form -->

<!-- Roles & Permissions Form -->
<div class="content-wrapper">
    <div class="box-header">
        <h3>
            Add new role
            <a href="<?php echo base_url('employee/EmployeeRoles') ?>" class="pull-right btn btn-default btn-sm rounded">
                <i class="fa fa-angle-left"></i> <?php echo trans('back') ?>
            </a>
        </h3>
    </div>

    <?php
    // Step 1: Create a map of role_id => status from organization roles
    $role_status_map = [];
    foreach ($roles as $role) {
        $role_status_map[$role->role_id] = $role->status;
    }
    ?>

    <?php if (empty($departments)): ?>
        <!-- No department message -->
        <div class="col-md-10 m-auto card my-5">
            <div class="card-header">
                <h5 class="text-danger text-center">No active departments found!</h5>
            </div>
            <div class="card-body text-center">
                <p>Please add a department to manage roles.</p>
                <a href="<?= base_url('admin/hrm/department') ?>" class="btn btn-info">
                    <i class="fa fa-plus"></i> Add Department
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php $restricted_departments = ['Executive', 'Manager', 'Team Lead', 'Human Resource']; ?>

        <!-- ✅ Start single form for all departments -->
        <form action="<?= base_url('/employee/EmployeeRoles/create_role') ?>" method="post">


            <?php foreach ($departments as $dept): ?>
                <?php if (!in_array($dept->name, $restricted_departments) || $this->auth_model->is_access_for_all_role()): ?>
                    <div class="col-md-12 m-auto card mb-5">
                        <div class="card-header">
                            <h5>Department: <?= html_escape($dept->name); ?></h5>
                        </div>
                        <div class="card-body">
                            <h6 class=""><i class="fa fa-user"></i> Roles</h6>

                            <?php foreach ($default_roles as $index => $drole): ?>
                                <?php if ($drole->department_id == $dept->department_id): ?>
                                    <?php
                                    $role_id   = $drole->id;
                                    $role_name = trim($drole->role_name);
                                    $dept_id   = $dept->id;
                                    $status    = isset($role_status_map[$role_id]) ? 1 : 0;
                                    ?>
                                    <div class="form-group row align-items-center my-4">
                                        <div class="col-sm-7">
                                            <label class="font-weight-bold"><?= html_escape($role_name) ?></label>
                                            <input type="hidden" name="role_name[]" value="<?= html_escape($role_name) ?>">
                                            <input type="hidden" name="department_id[]" value="<?= $dept_id ?>">
                                            <input type="hidden" name="default_role_id[]" value="<?= $role_id ?>">
                                            <input type="hidden" name="status[]" id="role-status-<?= $index ?>" value="<?= $status ?>">
                                        </div>
                                        <div class="col-sm-5 d-flex justify-content-start gap-5">
                                            <div class="icheck-primary radio radio-inline mx-5">
                                                <input type="radio" name="status_<?= $index ?>" id="role_active_<?= $index ?>" value="1" <?= $status == 1 ? 'checked' : '' ?>>
                                                <label for="role_active_<?= $index ?>">Active</label>
                                            </div>
                                            <div class="icheck-danger radio radio-inline">
                                                <input type="radio" name="status_<?= $index ?>" id="role_inactive_<?= $index ?>" value="0" <?= $status == 0 ? 'checked' : '' ?>>
                                                <label for="role_inactive_<?= $index ?>">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <hr>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- ✅ Single Save Button -->
            <div class="text-end my-4" style="
    text-align: end;
">
                <button type="submit" class="btn btn-success btn-sm">Save All Roles</button>
            </div>

        </form> <!-- ✅ End of single form -->

    <?php endif; ?>
</div>

<!-- JS to update hidden status[] from radio buttons -->
<script>
    $(document).ready(function() {
        $('input[type=radio][name^=status_]').on('change', function() {
            var index = $(this).attr('name').split('_')[1];
            $('#role-status-' + index).val($(this).val());
        });
    });
</script>