<style>
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
</style>

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- Roles & Permissions Form -->
<div class="content-wrapper">
    <h2>Roles & Permissions</h2>
    <!-- Feature Details Modal -->
    <div class="modal fade" id="featureDetailsModal" tabindex="-1" aria-labelledby="featureDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="featureDetailsModalLabel">Feature Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="feature-details-list">
                        <!-- Feature items will be injected here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- role and permission creating model -->
    <div id="create_role_permssion_area" class="hide">
        <div class="box-header">
            <h3>
                Create Roles & Permission
                <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_bulk"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
            </h3>
        </div>
        <form id="createPermissionForm">
            <div class="row rolesandpermission">
                <!-- Role Form -->
                <div class="col-lg-6">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-person-plus"></i> Select Role</h5>
                            <div class="mb-4">
                                <label for="role_name" class="form-label">Role Name</label>
                                <select class="form-control" id="role_name" name="role_name" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="TeamLead">Team Lead (TL)</option>
                                    <option value="ProjectManager">Project Manager</option>
                                    <option value="HR">HR Manager</option>
                                    <option value="Employee">Employee</option>
                                    <option value="ter">Intern</option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Department</label>
                                <select class="form-control" name="department" required>
                                    <option value="">-- Select Department --</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?= html_escape($department->id); ?>"
                                            <?php if (!empty($employee) && $employee[0]['department_id'] == $department->id) echo 'selected'; ?>>
                                            <?= html_escape($department->name); ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="role_description" class="form-label">Role Description</label>
                                <textarea class="form-control" id="role_description" name="role_description" maxlength="500" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Form -->
                <div class="col-lg-6">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-shield-plus"></i> Assign Permission to Role</h5>
                            <div class="mb-4">
                                <div id="feature-access-list" class="mt-4">
                                    <!-- Features table will be loaded here via JavaScript -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p>Loading features...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 hide">
                                <label for="permission_description" class="form-label">Permission Description</label>
                                <textarea class="form-control" id="permission_description" name="permission_description" maxlength="500" rows="3"></textarea>
                            </div>
                            <div class="my-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="cancelBtn">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-success mx-2">
                                    <i class="bi bi-shield-plus"></i> Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- role and permission creating model -->
    <div id="edit_role_permssion_area" class="hide">
        <div class="box-header">
            <h3>
                Edit Roles & Permission
                <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_bulk"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
            </h3>
        </div>
        <form id="createPermissionForm">
            <div class="row rolesandpermission">
                <!-- Role Form -->
                <div class="col-lg-6">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-person-plus"></i> Select Role</h5>
                            <div class="mb-4">
                                <label for="role_name" class="form-label">Role Name</label>
                                <select class="form-control" id="role_name" name="role_name" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="TeamLead">Team Lead (TL)</option>
                                    <option value="ProjectManager">Project Manager</option>
                                    <option value="HR">HR Manager</option>
                                    <option value="Employee">Employee</option>
                                    <option value="ter">Intern</option>

                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Department</label>
                                <select class="form-control" name="department" required>
                                    <option value="">-- Select Department --</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?= html_escape($department->id); ?>"
                                            <?php if (!empty($employee) && $employee[0]['department_id'] == $department->id) echo 'selected'; ?>>
                                            <?= html_escape($department->name); ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="role_description" class="form-label">Role Description</label>
                                <textarea class="form-control" id="role_description" name="role_description" maxlength="500" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Form -->
                <div class="col-lg-6">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-shield-plus"></i> Assign Permission to Role</h5>
                            <div class="mb-4">
                                <div id="feature-access-list" class="mt-4">
                                    <!-- Features table will be loaded here via JavaScript -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p>Loading features...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 hide">
                                <label for="permission_description" class="form-label">Permission Description</label>
                                <textarea class="form-control" id="permission_description" name="permission_description" maxlength="500" rows="3"></textarea>
                            </div>
                            <div class="my-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="cancelBtn">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-success mx-2">
                                    <i class="fa fa-check"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="list_area container">
        <h3 class="box-title"><?php echo "roles" ?> <a href="#" class="pull-right btn btn-info btn-sm rounded create_role_permssion mx-5">
                <i class="fa fa-plus"></i> Create Role & Permission</a>
        </h3>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
            <table class="table table-hover cushover" id="user-role-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Feature Name</th>
                        <th>View</th>
                        <!-- <th>Write</th> -->
                        <th>Action</th>
                        <!-- <th>Delete</th>
                        <th>Edit & Delete</th> -->
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>


<script>
    function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 2000);
    }




    let storedRoleId = null;
    let allFeatures = [];

    $(document).ready(function() {
        const userId = <?= json_encode($this->session->userdata('id')); ?>;

    function loadUserRolePermissions(userId) {
        $.ajax({
            url: "<?= base_url('/employee/EmployeeRoles/get_user_role_feature_permissions'); ?>",
            method: 'GET',
            dataType: "json",
            data: {
                user_id: userId
            },
            success: function(response) {
                console.log(response);

                    if (response.status === 200 || response.status === 'success') {
                        var tbody = $('#user-role-table tbody');
                        tbody.empty(); // Clear old rows

                    // Create modal div if it doesn't exist
                    if ($('#permissionsModal').length === 0) {
                        $('body').append(`
                        <div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="permissionsModalLabel">Role Permissions</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="permissionsContent"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    }

                    let index = 1;

                    response.data.forEach(function(role) {
                        let featureNames = [];
                        let featureDetails = [];

                        if (role.features.length > 0) {
                            role.features.forEach(function(feature) {
                                featureNames.push(feature.feature_name);
                                featureDetails.push({
                                    name: feature.feature_name,
                                    read: feature.is_read,
                                    write: feature.is_write,
                                    action: feature.is_action,
                                    delete: feature.is_delete
                                });
                            });

                            const row = `
                        <tr>
                            <td>${index++}</td>
                            <td>${role.role_name}</td>
                            <td>
                                <div>${featureNames.join(', ')}</div>
                            </td>
                            <td>
                                <a href="#" class="view-permissions" data-role="${role.role_name}" data-features='${JSON.stringify(featureDetails)}' title="View Permissions">
                                    <i class="bi bi-eye-fill text-primary" style="font-size: 1.5rem;"></i>
                                </a>
                            </td>
                             <td class="actions" width="15%">
                                 <a href="#" class="on-default edit-row  edit_row_button" data-placement="top" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                               <a href="#" 
                                    class="on-default remove-row  delete-role-btn" 
                                    data-val="employee" 
                                    data-id="${userId}" 
                                    data-department-id="${role.department_id}" 
                                    data-role="${role.role_name}" 
                                    title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                    </a>

                            </td>
                        </tr>`;
                            tbody.append(row);
                        } else {
                            const row = `
                        <tr>
                            <td>${index++}</td>
                            <td>${role.role_name}</td>
                            <td><i class="bi bi-pencil-square text-muted" title="No features to update" style="cursor: not-allowed;">No feature to update</i></td>
                            <td>
                                <a href="#" class="view-permissions  text-muted" title="No Permissions">
                                    <i class="bi bi-eye-slash" style="font-size: 1.5rem;"></i>
                                </a>
                            </td>
                           <td class="actions" width="15%">
                                <a href="#" class="on-default edit_row_button" data-placement="top" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="#" 
                                    class="on-default remove-row delete-role-btn" 
                                    data-val="employee" 
                                    data-id="${userId}" 
                                    data-department-id="${role.department_id}" 
                                    data-role="${role.role_name}" 
                                    title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                    </a>

                            </td>
                        </tr>`;
                            tbody.append(row);
                        }
                    });

                    // Add click event for view icons
                    $(document).off('click', '.view-permissions').on('click', '.view-permissions', function(e) {
                        e.preventDefault();
                        const role = $(this).data('role');
                        const features = $(this).data('features');

                        if (!features || features.length === 0) {
                            $('#permissionsContent').html('<p>No permissions found for this role.</p>');
                        } else {
                            let tableHtml = `
                            <h6><i class="bi bi-person-gear" title="Role"></i> Role: ${role}</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Feature</th>
                                            <th>Read</th>
                                            <th>Write</th>
                                            <th>Action</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            features.forEach(feature => {
                                tableHtml += `
                                <tr>
                                    <td>${feature.name}</td>
                                    <td>${feature.read == 1 ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>'}</td>
                                    <td>${feature.write == 1 ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>'}</td>
                                    <td>${feature.action == 1 ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>'}</td>
                                    <td>${feature.delete == 1 ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>'}</td>
                                </tr>`;
                            });

                            tableHtml += `</tbody></table></div>`;
                            $('#permissionsContent').html(tableHtml);
                        }

                        $('#permissionsModal').modal('show');
                    });

                } else {
                    alert(response.message || 'No data found.');
                }
            },
            error: function(err) {
                    var tbody = $('#user-role-table tbody');
                    tbody.empty(); // Clear old rows
                    // alert(response.message || 'No data found.');
                    // console.error('Error:', err);
                    // alert('Something went wrong while fetching data.');
                    console.log(err);

            }
        });
    }
        loadFeatures();
        loadUserRolePermissions(userId);
        // Initialize the feature table
        function initializeFeatureTable(features) {
            const container = $('#feature-access-list');
            container.empty();

            if (features.length === 0) {
                container.html('<div class="alert alert-info">No features available</div>');
                return;
            }

            let table = `
                <table class="table table-bordered permission-table">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="selectAllFeatures">
                            </th>
                            <th>Feature</th>
                            <th class="text-center">
                                <input type="checkbox" id="selectAllRead"> Read
                            </th>
                            <th class="text-center">
                                <input type="checkbox" id="selectAllWrite"> Write
                            </th>
                            <th class="text-center">
                                <input type="checkbox" id="selectAllAction"> Action
                            </th>
                            <th class="text-center">
                                <input type="checkbox" id="selectAllDelete"> Delete
                            </th>
                        </tr>
                    </thead>
                    <tbody>`;

            features.forEach((feature) => {
                table += `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="feature-selector" data-feature-id="${feature.id}">
                        </td>
                        <td>
                            ${feature.feature_name}
                            <input type="hidden" name="feature_id[]" value="${feature.id}" />
                        </td>
                        <td class="text-center">
                            <input class="form-check-input read-checkbox" type="checkbox" 
                                   name="access[${feature.id}][is_read]" value="1" disabled>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input write-checkbox" type="checkbox" 
                                   name="access[${feature.id}][is_write]" value="1" disabled>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input action-checkbox" type="checkbox" 
                                   name="access[${feature.id}][is_action]" value="1" disabled>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input delete-checkbox" type="checkbox" 
                                   name="access[${feature.id}][is_delete]" value="1" disabled>
                        </td>
                    </tr>`;
            });

            table += `</tbody></table>`;
            container.append(table);

            // Select all features checkbox
            $('#selectAllFeatures').change(function() {
                const isChecked = $(this).prop('checked');
                $('.feature-selector').prop('checked', isChecked).trigger('change');
            });

            // Enable/disable permission checkboxes when feature is selected/deselected
            $(document).on('change', '.feature-selector', function() {
                const featureId = $(this).data('feature-id');
                const isChecked = $(this).is(':checked');

                $(`input[name^="access[${featureId}]"]`)
                    .prop('disabled', !isChecked)
                    .prop('checked', isChecked ? $(`input[name^="access[${featureId}]"]`).prop('checked') : false);
            });

            // Select all checkboxes for each permission type
            $('#selectAllRead').change(function() {
                const isChecked = $(this).prop('checked');
                $('.read-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            $('#selectAllWrite').change(function() {
                const isChecked = $(this).prop('checked');
                $('.write-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            $('#selectAllAction').change(function() {
                const isChecked = $(this).prop('checked');
                $('.action-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            $('#selectAllDelete').change(function() {
                const isChecked = $(this).prop('checked');
                $('.delete-checkbox:not(:disabled)').prop('checked', isChecked);
            });
        }

        function loadFeatures() {
            $.ajax({
                url: "<?= base_url('employee/EmployeeRoles/get_app_features') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        allFeatures = response.data.features;
                        initializeFeatureTable(allFeatures);
                    } else {
                        showToast("No features found.", "error");
                        $('#feature-access-list').html('<div class="alert alert-info">No features available</div>');
                    }
                },
                error: function() {
                    showToast("Failed to fetch features.", "error");
                    $('#feature-access-list').html('<div class="alert alert-danger">Failed to load features</div>');
                }
            });
        }
        $(document).on('click', '.delete-role-btn', function(e) {
            e.preventDefault();

            const userId = $(this).data('id');
            const departmentId = $(this).data('department-id');
            const role = $(this).data('role');

            const message = `Are you sure you want to delete the role "${role}" for this user?`;

            showConfirmationAlert(message, "warning", function() {
                // Perform the delete action here
                $.ajax({
                    url: "<?= base_url('/employee/EmployeeRoles/delete_role'); ?>",
                    method: "POST",
                    dataType: "json",
                    data: {
                        user_id: userId,
                        department_id: departmentId,
                        role_name: role,
                        csrf_test_name: "<?= $this->security->get_csrf_hash(); ?>"
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            swal("Deleted!", "Role deleted successfully.", "success");
                            loadUserRolePermissions(userId);
                        } else {
                            swal("Failed!", response.message || "Could not delete role.", "error");
                        }
                    },
                    error: function() {
                        swal("Error!", "Something went wrong.", "error");
                    }
                });
            });
        });

        $(document).off('click', '.edit_row_button').on('click', '.edit_row_button', function(e) {
            console.log("hii");
            e.preventDefault();
            $('#edit_role_permssion_area').show();
            $('.list_area').hide();
        });

        $('#createPermissionForm').on('submit', function(e) {
            e.preventDefault();

            const role_name = $('#role_name').val();
            const department_id = $('select[name="department"]').val();
            const role_description = $('#role_description').val();

            if (!role_name || !department_id) {
                showToast('Please fill out all required fields in Role form.', 'error');
                return;
            }

            $.ajax({
                url: "<?= base_url('/employee/EmployeeRoles/create_role'); ?>",
                method: "POST",
                data: {
                    user_id: userId,
                    department_id: department_id,
                    role_name: role_name,
                    description: role_description
                },
                success: function(response) {
                    if (response.status === 'success' || response.status === 201) {
                        // showToast(response.message, 'success');
                        storedRoleId = response.role_id || (response.data?.role_id ?? null);
                        console.log("Created Role ID:", storedRoleId);
                        submitPermissions(storedRoleId);
                    } else {
                        showToast('Role already Exists.', 'error');
                    }
                },
                error: function() {
                    // showToast('Server error while creating role.', 'error');
                    showToast('Role already Exists.', 'error');
                }
            });
        });

        function submitPermissions(roleId) {
            const selectedFeatures = $('.feature-selector:checked');
            const features = [];

            if (selectedFeatures.length === 0) {
                showToast('Please select at least one feature.', 'error');
                return;
            }

            selectedFeatures.each(function() {
                const feature_id = $(this).data('feature-id');
                const permissions = {
                    feature_id: parseInt(feature_id),
                    is_read: 0,
                    is_write: 0,
                    is_action: 0,
                    is_delete: 0
                };

                // Collect all permissions for this feature
                $(`input[name^="access[${feature_id}]"]:checked`).each(function() {
                    const name = $(this).attr('name'); // access[1][is_read]
                    const match = name.match(/\[(\w+)\]$/); // get is_read
                    if (match && match[1]) {
                        permissions[match[1]] = 1;
                    }
                });

                features.push(permissions);
            });
            console.log("Role ID:", roleId);
            console.log("User ID:", userId);
            console.log("Submitting permissions:", features);


            if (!roleId) {
                showToast('Role not created properly.', 'error');
                return;
            }

            $.ajax({
                url: "<?= base_url('employee/EmployeeRoles/store_role_feature_access'); ?>",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    role_id: roleId,
                    user_id: userId,
                    features: features
                }),
                success: function(response) {
                    if (response.status === 'success' || response.status === 201) {
                        showToast("Role and Permission Created Successfully", 'success');
                        $('#createPermissionForm')[0].reset();
                        storedRoleId = null;
                        initializeFeatureTable(allFeatures); // Reload table if needed
                        loadUserRolePermissions(userId);
                    } else {
                        showToast("Failed to Create Role and Permission", 'error');
                    }
                },
                error: function(re) {
                    console.log(re);

                    showToast('Server error while assigning permission.', 'error');
                }
            });
        }

        $('#cancelBtn').on('click', function() {
            $('#createPermissionForm')[0].reset();
            storedRoleId = null;
            initializeFeatureTable(allFeatures);
        });
        $('.create_role_permssion').on('click', function(e) {
            e.preventDefault();
            $('#create_role_permssion_area').show();
            $('.list_area').hide();
        });

        $('.cancel_bulk').on('click', function(e) {
            e.preventDefault();
            $('#create_role_permssion_area  ').hide();
            $('#edit_role_permssion_area  ').hide();
            $('.list_area').show();
        });

    });
</script>