<div class="content-wrapper role_management">
    <section class="content ">
        <div class="list_area container">
            <div class="col-md-10 m-auto">

                <h3>
                    Role Management
                    <a href="<?php echo base_url('admin/roles_permissions') ?>" class="pull-right btn btn-default btn-sm rounded">
                        <i class="fa fa-angle-left"></i> <?php echo trans('back') ?>
                    </a>
                </h3>


                <?php
                // Step 1: Create a map of role_id => status from organization roles
                $role_status_map = [];
                foreach ($roles as $role) {
                    $role_status_map[$role->role_id] = $role->status;
                }
                ?>

                <?php if (empty($departments)): ?>
                    <!-- No department message -->
                    <div class="box-header  mt-20">
                        <h5 class="text-danger text-center">No active departments found!</h5>
                    </div>
                    <div class="box-body text-center">
                        <p>Please add a department to manage roles.</p>
                        <a href="<?= base_url('admin/hrm/department') ?>" class="btn btn-info">
                            <i class="fa fa-plus"></i> Add Department
                        </a>
                    </div>
                <?php else: ?>
                    <?php $restricted_departments = ['Executive', 'Manager', 'Team Lead', 'Human Resource']; ?>

                    <!-- ✅ Start single form for all departments -->
                    <form action="<?= base_url('/employee/EmployeeRoles/create_role') ?>" method="post" class=" mt-20">


                        <?php foreach ($departments as $dept): ?>
                            <?php if (!in_array($dept->name, $restricted_departments) || $this->auth_model->is_access_for_all_role()): ?>
                                <div class="mb-5 box">
                                    <div class="cursor-pointer department-header"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#dept-body-<?= $dept->id ?>"
                                        aria-expanded="false"
                                        aria-controls="dept-body-<?= $dept->id ?>">
                                        <div class=" box-header d-flex justify-content-between align-items-center mt-4 mb-4">
                                            <h5 class="mb-0">Department: <?= html_escape($dept->name); ?></h5>

                                            <!-- rotate‑me icon -->
                                            <i class="fa fa-angle-down rotate-icon"></i>
                                        </div>
                                    </div>

                                    <div id="dept-body-<?= $dept->id ?>" class="box-body collapse" style="padding:0px 2rem;">
                                        <p class="mb-2 text-muted with-border">Roles</p>

                                        <?php foreach ($default_roles as $index => $drole): ?>
                                            <?php if ($drole->department_id == $dept->department_id): ?>
                                                <?php
                                                $role_id   = $drole->id;
                                                $role_name = trim($drole->role_name);
                                                $dept_id   = $dept->id;
                                                $status    = isset($role_status_map[$role_id]) ? 1 : 0;
                                                ?>
                                                <div class="form-group row align-items-center my-4 pl-25 pr-25">
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
            </div>
            <!-- ✅ Single Save Button -->
            <div class="col-md-10 m-auto text-end my-4" style="text-align: end;">
                <?php if ($can_edit): ?>
                    <button type="submit" class="btn btn-info waves-effect rounded w-md waves-light">Save All Roles</button>
                <?php else: ?>
                    <span data-toggle="tooltip" data-placement="top" title="permission denied to manage role " class="btn btn-default btn-sm m-5">Save All Roles</span>
                <?php endif; ?>
            </div>

            </form> <!-- ✅ End of single form -->

        <?php endif; ?>
        </div>
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