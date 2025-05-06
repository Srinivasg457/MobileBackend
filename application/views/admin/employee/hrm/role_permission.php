<style>
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
</style>
<div id="toast-container" style="position: fixed;top: 0;"></div>
<div class="content-wrapper">
    <h2>Roles & Permissions</h2>
    <div class="">
        <div class="container mt-5" style="min-width: 600px;">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between">
                        <h5><i class="bi bi-person-plus"></i>Create Role </h5> <button type="button" class="btn btn-secondary mt-3 w-100%" id="cancelBtn">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </div>

                    <form id="createRoleForm">
                        <div class="mb-4">
                            <label for="role_name" class="form-label">Role Name:</label>
                            <select class="form-control" id="role_name" name="role_name" required>
                                <option value="">-- Select Role --</option>
                                <option value="TeamLead">Team Lead (TL)</option>
                                <option value="ProjectManager">Project Manager</option>
                                <option value="HR">HR Manager</option>
                                <option value="Employee">Employee</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description:</label>
                            <textarea class="form-control" id="description" name="description" maxlength="500" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100% my-5">
                            <i class="bi bi-person-plus"></i> Create Role
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container mt-5" style="min-width: 600px;">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-shield-plus"></i>Create Permission</h5>
                    <form id="createPermissionForm">
                        <div class="my-5">
                            <label for="user_id" class="form-label">Selected Role:</label><span id="selected-role"></span>
                        </div>

                        <div class="my-5">
                            <label for="description" class="form-label">Enter Permission:</label>
                            <input class="form-control" id="permission_name" name="permission_name" placeholder="Enter the Permission">
                        </div>
                        <div class="my-5">
                            <label for="role_permissions" class="form-label fw-bold">Permissions List:</label>
                            <div class="mb-5 hide" id="TeamLead">
                                <div class="d-flex flex-column">
                                    <div class="m-5 ">
                                        <label class="form-check-label" for="perm1">
                                            <i class="bi bi-check-lg text-success"></i> View and assign tasks to team members
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="perm2">
                                            <i class="bi bi-check-lg text-success"></i> Create/update project timelines
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="perm3">
                                            <i class="bi bi-check-lg text-success"></i> Approve or request changes in tasks/stories
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="perm4">
                                            <i class="bi bi-check-lg text-success"></i> View overall project status and reports
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 hide" id="HR">
                                <div class="d-flex flex-column">
                                    <div class="m-5">
                                        <label class="form-check-label" for="hr_perm1">
                                            <i class="bi bi-check-lg text-success"></i> View and manage employee profiles
                                        </label>
                                    </div>
                                    <div class="m-5">
                                        <label class="form-check-label" for="hr_perm3">
                                            <i class="bi bi-check-lg text-success"></i> Manage attendance and leave requests
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="hr_perm4">
                                            <i class="bi bi-check-lg text-success"></i> Monitor employee performance and reports
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 hide" id="Employee">
                                <div class="d-flex flex-column">
                                    <div class="m-5">
                                        <label class="form-check-label" for="emp_perm1">
                                            <i class="bi bi-check-lg text-success"></i> View Screenshots
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="emp_perm2">
                                            <i class="bi bi-check-lg text-success"></i> Submit work updates
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="emp_perm3">
                                            <i class="bi bi-check-lg text-success"></i> Apply for leave
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="emp_perm4">
                                            <i class="bi bi-check-lg text-success"></i> View Activity and Reports
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 hide" id="ProjectManager">
                                <div class="d-flex flex-column">
                                    <div class="m-5">
                                        <label class="form-check-label" for="pm_perm1">
                                            <i class="bi bi-check-lg text-success"></i> Create and manage projects
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="pm_perm2">
                                            <i class="bi bi-check-lg text-success"></i> Assign teams and define roles
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="pm_perm3">
                                            <i class="bi bi-check-lg text-success"></i> Monitor task progress and deadlines
                                        </label>
                                    </div>

                                    <div class="m-5">
                                        <label class="form-check-label" for="pm_perm4">
                                            <i class="bi bi-check-lg text-success"></i> Generate and review project reports
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="my-5">
                            <label for="description" class="form-label">Description:</label>
                            <textarea class="form-control" id="description" name="description" maxlength="500" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100% my-5">
                            <i class="bi bi-shield-plus"></i> Create Permission
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
    }

    let storedRoleData = null;

    $(document).ready(function() {
        // Initially disable permission form
        $('#createPermissionForm :input').prop('disabled', true);
        const userId = <?php echo json_encode($this->session->userdata('id')); ?>;

        // Step 1: Validate and store role data
        $('#createRoleForm').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            if (!form.checkValidity()) {
                showToast("select the role", "error")
                $(form).addClass('was-validated');
                return;
            }

            storedRoleData = {
                user_id: userId,
                role_name: $('#role_name').val().trim(),
                description: $('#createRoleForm #description').val().trim()
            };

            // Disable Role form
            $('#createRoleForm :input').prop('disabled', true);

            // Enable Permission form
            $('#createPermissionForm :input').prop('disabled', false);

            // Update UI
            $('#selected-role').text(storedRoleData.role_name);
            $(`#${storedRoleData.role_name.replace(/\s+/g, '')}`).removeClass('hide');
        });

        // Step 2: Submit Permission form
        $('#createPermissionForm').on('submit', function(e) {
            e.preventDefault();

            if (!storedRoleData) {
                showToast("Please create role first", "error");
                return;
            }

            // First call create_role API
            $.ajax({
                url: "<?php echo base_url('/employee/EmployeeRoles/create_role'); ?>",
                method: 'POST',
                data: storedRoleData,
                success: function(response) {
                    showToast(response.message, "success");

                    const permissionData = {
                        user_id: userId,
                        permission_name: $('#createPermissionForm #permission_name').val().trim(),
                        description: $('#createPermissionForm #description').val().trim()
                    };

                    // Call create_permission API
                    $.ajax({
                        url: "<?php echo base_url('employee/EmployeeRoles/create_permission'); ?>",
                        method: 'POST',
                        data: JSON.stringify(permissionData),
                        contentType: 'application/json',
                        success: function() {
                            showToast("Permission created successfully", "success");
                            $('#createRoleForm')[0].reset();
                            $('#createPermissionForm')[0].reset();
                            $('#createRoleForm :input').prop('disabled', false).removeClass('was-validated');
                            $('#createPermissionForm :input').prop('disabled', true);
                            $('#selected-role').text('');
                            $('#permission_name').val('');
                            $('.hide').addClass('hide');
                            storedRoleData = null;
                        },
                        error: function() {
                            showToast("Failed to create permission", "error");
                        }
                    });
                },
                error: function(xhr) {
                    let msg = 'Something went wrong';
                    showToast(msg, "error")
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showToast(msg, 'error');
                    $('#createRoleForm :input').prop('disabled', false);
                    $('#createPermissionForm :input').prop('disabled', true);
                    $('#permission_name').val('');
                }
            });
        });

        // Cancel button resets both forms
        $('#cancelBtn').on('click', function() {
            $('#createRoleForm')[0].reset();
            $('#createPermissionForm')[0].reset();
            $('#createRoleForm :input').prop('disabled', false).removeClass('was-validated');
            $('#createPermissionForm :input').prop('disabled', true);
            $('#selected-role').text('');
            $('.hide').addClass('hide');
            storedRoleData = null;
        });
    });
</script>