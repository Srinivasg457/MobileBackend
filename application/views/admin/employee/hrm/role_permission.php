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
</style>

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- Roles & Permissions Form -->
<div class="content-wrapper">
    <!-- <h2 class="mb-5">Roles & Permissions</h2> -->
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
            <h3 class="box-title"><i class="bi bi-shield-plus"></i><?php echo "Assign Permission to Role" ?>
                <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_bulk"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
                <a href="#" class="pull-right btn btn-info btn-sm rounded create_role mx-5">
                    <i class="fa fa-plus"></i> Create Role</a>
            </h3>
        </div>
        <form id="createPermissionForm">

            <div class="row">
                <!-- Permissions Form -->
                <div class="col-12">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <div class="row">
                                <div class="my-4 col-lg-6">
                                    <label for="role_name" class="form-label">Role Name</label>
                                    <select name="role_id" id="roleDropdown" class="form-control single_select" required>
                                        <option value="">-- Select Role --</option>
                                    </select>
                                </div>
                                <!-- <div class="my-4 col-lg-6">
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
                                </div> -->
                            </div>
                            <div class="my-4">
                                <label class="form-label">Features</label>
                                <div id="feature-access-list">
                                    <!-- Features table will be loaded here via JavaScript -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p>Loading features...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="my-4">
                                <label for="permission_description" class="form-label">Permission Description</label>
                                <textarea class="form-control" id="permission_description" name="permission_description" maxlength="500" rows="3"></textarea>
                            </div>
                            <div class="my-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="cancelBtn">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-success mx-2">
                                    <i class="bi bi-shield-plus"></i> Assign Permissions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- role and permission edit model -->
    <div id="edit_role_permssion_area" class="hide">
        <div class="box-header">
            <h3>
                Edit Roles & Permission
                <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_bulk"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
            </h3>
        </div>
        <form id="editPermissionForm">

            <div class="row">
                <!-- Permissions Form -->
                <div class="col-12">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <div class="row">
                                <div class="my-4 col-lg-6">
                                    <label for="role_name" class="form-label">Role Name</label>
                                    <input id="roleName" type="text" class="form-control" value="" readonly>
                                    <input type="hidden" id="editroleId">
                                </div>
                                <!-- <div class="my-4 col-lg-6">
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
                                </div> -->
                            </div>
                            <div class="my-4">
                                <label class="form-label">Features</label>
                                <div id="edit-feature-access-list">
                                    <!-- Features table will be loaded here via JavaScript -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p>Loading features...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="my-4">
                                <label for="permission_description" class="form-label">Permission Description</label>
                                <textarea class="form-control" id="permission_description" name="permission_description" maxlength="500" rows="3"></textarea>
                            </div>
                            <div class="my-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success mx-2">
                                    <i class="bi bi-arrow-clockwise"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- role creation model -->
    <div id="create_role" class="hide">
        <div class="box-header">
            <h3 class="box-title"><i class="bi bi-person-plus"></i> <?php echo " Create Role" ?>
                <a href="#" class="pull-right btn btn-default btn-sm rounded cancel_create_role"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
            </h3>
        </div>
        <form id="createRole">

            <div class="row">
                <!-- Role Form -->
                <div class="container">
                    <div class="card shadow-lg role">
                        <div class="card-body">
                            <h5 class="card-title"></h5>
                            <div class="mb-4">
                                <label for="role_name" class="form-label">Role Name</label>
                                <input class="form-control" type="text" id="role_name1" name="role_name" placeholder="Enter Role" required>
                                <!-- <select class="form-control" id="role_name" name="role_name" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="TeamLead">Team Lead (TL)</option>
                                    <option value="ProjectManager">Project Manager</option>
                                    <option value="HR">HR Manager</option>
                                    <option value="Employee">Employee</option>
                                    <option value="ter">Intern</option>

                                </select> -->
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Department</label>
                                <select class="form-control single_select" name="department1" required>
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
                            <div class="my-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mx-2" id="rolecancelBtn">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-success mx-2">
                                    <i class="fa fa-plus"></i> Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="list_area container">
        <h3 class="box-title"><?php echo "Roles & Permissions" ?> <a href="#" class="pull-right btn btn-info btn-sm rounded create_role_permssion mx-5">
                <i class="fa fa-plus"></i> Assign Permission to Role</a>
        </h3>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
            <table class="table table-hover cushover" id="user-role-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Features</th>
                        <th>View</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>


<script>
    let storedRoleId = null;
    // let allFeatures = [];

    $(document).ready(function() {
        const userId = <?= json_encode($this->session->userdata('id')); ?>;
        //  for loading the roles
        function loadRolesForCurrentUser() {
            $.ajax({
                url: "<?= base_url('employee/EmployeeRoles/get_roles_by_user'); ?>",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    let dropdown = $('#roleDropdown');
                    dropdown.empty().append('<option value="">-- Select Role --</option>');

                    res.data.roles.forEach(function(role) {
                        dropdown.append(`<option value="${role.id}">${role.role_name}</option>`);
                    });
                },
                error: function(xhr) {
                    let err = xhr.responseJSON?.message || "Failed to load roles";
                    // swal("Error", err, "error");
                }
            });
        }

        // for role list 
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
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content" style="margin-top: 10% !important">
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
                                 <a href="#" data-role-name ="${role.role_name}" data-role-id="${role.role_id}"  class="edit-row  edit_row_button" data-placement="top" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                               <a href="#" 
                                    class="remove-row  delete-role-btn" 
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
                                <a href="#" data-role-name ="${role.role_name}" data-role-id="${role.role_id}"  class="edit_row_button" data-placement="top" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a href="#" 
                                    class="remove-row delete-role-btn" 
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
        // Initialize the feature table
        function initializeFeatureTable(features, tableName, roleId) {
            const container = $(tableName);
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
            $(document).on('change', '#selectAllFeatures', function() {
                const isChecked = $(this).prop('checked');
                $('.feature-selector').prop('checked', isChecked).trigger('change');
            });

            // Enable/disable permission checkboxes when feature is selected/deselected
            $(document).on('change', '.feature-selector', function() {
                const featureId = $(this).data('feature-id');
                const isChecked = $(this).is(':checked');

                // Enable/disable the permission checkboxes
                $(`input[name^="access[${featureId}]"]`).prop('disabled', !isChecked);

                if (isChecked) {
                    // Automatically check is_read when feature is selected
                    $(`input[name="access[${featureId}][is_read]"]`).prop('checked', true);
                } else {
                    // Uncheck all permission checkboxes when deselected
                    $(`input[name^="access[${featureId}]"]`).prop('checked', false);
                }
            });

            // Select all checkboxes for each permission type
            $(document).on('change', '#selectAllFeatures', function() {
                const isChecked = $(this).prop('checked');
                $('.feature-selector').prop('checked', isChecked).trigger('change');
            });

            $(document).on('change', '#selectAllRead', function() {
                const isChecked = $(this).prop('checked');
                $('.read-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            $(document).on('change', '#selectAllWrite', function() {
                const isChecked = $(this).prop('checked');
                $('.write-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            $(document).on('change', '#selectAllAction', function() {
                const isChecked = $(this).prop('checked');
                $('.action-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            $(document).on('change', '#selectAllDelete', function() {
                const isChecked = $(this).prop('checked');
                $('.delete-checkbox:not(:disabled)').prop('checked', isChecked);
            });

            if (tableName == '#edit-feature-access-list') {
                console.log(roleId);

                $.ajax({
                    url: "<?= base_url('employee/EmployeeRoles/get_feature_access_by_user_and_role') ?>",
                    method: "GET",
                    dataType: "json",
                    data: {
                        role_id: roleId,
                        user_id: userId
                    },
                    success: function(response) {
                        console.log(response);

                        const features = response.data.features;

                        features.forEach((feature) => {
                            const featureId = feature.feature_id;

                            // Read checkbox
                            $(`input[name="access[${featureId}][is_read]"]`)
                                .prop('checked', feature.is_read === '1')
                                .prop('disabled', false);

                            // Write checkbox
                            $(`input[name="access[${featureId}][is_write]"]`)
                                .prop('checked', feature.is_write === '1')
                                .prop('disabled', false);

                            // Action checkbox
                            $(`input[name="access[${featureId}][is_action]"]`)
                                .prop('checked', feature.is_action === '1')
                                .prop('disabled', false);

                            // Delete checkbox (only if present in your response)
                            if (feature.hasOwnProperty('is_delete')) {
                                $(`input[name="access[${featureId}][is_delete]"]`)
                                    .prop('checked', feature.is_delete === '1')
                                    .prop('disabled', false);
                            }

                            // Also check the feature-selector checkbox
                            $(`.feature-selector[data-feature-id="${featureId}"]`).prop('checked', true);
                        });

                    },
                    error: function() {
                        console.log("Failed to fetch features.", "error");
                        // $(tableName).html('<div class="alert alert-danger">Failed to load features</div>');
                    }
                });
                const editTable = $('#edit_role_permssion_area');
                // console.log(editTable.html());
            }
        }

        function loadFeatures(tableName, roleId) {
            $.ajax({
                url: "<?= base_url('employee/EmployeeRoles/get_app_features') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        allFeatures = response.data.features;
                        initializeFeatureTable(allFeatures, tableName, roleId);
                    } else {
                        showToast("No features found.", "error");
                        $(tableName).html('<div class="alert alert-info">No features available</div>');
                    }
                },
                error: function() {
                    showToast("Failed to fetch features.", "error");
                    $(tableName).html('<div class="alert alert-danger">Failed to load features</div>');
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

            e.preventDefault();
            $('#edit_role_permssion_area').show();
            $('.list_area').hide();
            const roleName = $(this).data('role-name');
            const roleid = $(this).data('role-id');
            $('#roleName').val(roleName); // Show role name
            $('#editroleId').val(roleid);
            console.log(roleName, roleid);
            loadFeatures("#edit-feature-access-list", roleid);
        });

        //creating permission
        $('#createPermissionForm').on('submit', function(e) {
            e.preventDefault();

            const form = $('#createPermissionForm'); // form context

            const roleId = form.find('#roleDropdown').val(); // scoped to the form
            const userId = <?= $this->session->userdata('id') ?>;
            const permissionDescription = form.find('#permission_description').val();

            const features = [];

            // Get all feature checkboxes inside this form
            form.find('.feature-selector').each(function() {
                const feature_id = $(this).data('feature-id');
                const isChecked = $(this).is(':checked');

                const permissions = {
                    feature_id: parseInt(feature_id),
                    is_read: 0,
                    is_write: 0,
                    is_action: 0,
                    is_delete: 0
                };

                // Only if checked, collect permission inputs
                if (isChecked) {
                    form.find(`input[name^="access[${feature_id}]"]:checked`).each(function() {
                        const name = $(this).attr('name');
                        const match = name.match(/\[(\w+)\]$/);
                        if (match && match[1]) {
                            permissions[match[1]] = 1;
                        }
                    });
                }

                features.push(permissions);
            });

            const payload = {
                role_id: parseInt(roleId),
                user_id: parseInt(userId),
                features: features,
                permission_description: permissionDescription
            };

            $.ajax({
                url: "<?= base_url('employee/EmployeeRoles/store_role_feature_access'); ?>",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(payload),
                success: function(res) {
                    console.log(res);
                    swal("Success!", res.message, "success");
                    loadUserRolePermissions(userId);
                    form[0].reset();
                    $('#create_role_permssion_area').hide();
                    $('#edit_role_permssion_area').hide();
                    $('.list_area').show();
                },
                error: function(xhr) {
                    console.error(xhr);
                    const errorMsg = xhr.responseJSON?.message || "Something went wrong.";
                    swal("Error!", errorMsg, "error");
                }
            });
        });


        // Update permission from edit form
        $('#editPermissionForm').on('submit', function(e) {
            e.preventDefault();

            const form1 = $('#editPermissionForm'); // Always reference within the form
            const roleId1 = form1.find('#editroleId').val();
            const userId1 = <?= $this->session->userdata('id') ?>;
            const permissionDescription1 = form1.find('#permission_description').val();

            const selectedFeatures1 = form1.find('.feature-selector:checked');
            const features1 = [];

            if (selectedFeatures1.length === 0) {
                showToast('Please select at least one feature.', 'error');
                return;
            }

            console.log(selectedFeatures1);

            selectedFeatures1.each(function() {
                const featureId1 = $(this).data('feature-id');
                const permissions1 = {
                    feature_id: parseInt(featureId1),
                    is_read: 0,
                    is_write: 0,
                    is_action: 0,
                    is_delete: 0
                };

                // Only get permissions within the form context
                form1.find(`input[name^="access[${featureId1}]"]:checked`).each(function() {
                    const name1 = $(this).attr('name'); // access[1][is_read]
                    const match1 = name1.match(/\[(\w+)\]$/); // get is_read, etc.
                    if (match1 && match1[1]) {
                        permissions1[match1[1]] = 1;
                    }
                });

                features1.push(permissions1);
            });

            const payload1 = {
                role_id: parseInt(roleId1),
                user_id: parseInt(userId1),
                features: features1,
                permission_description: permissionDescription1
            };

            console.log(payload1);

            $.ajax({
                url: "<?= base_url('employee/EmployeeRoles/store_role_feature_access'); ?>",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(payload1),
                success: function(res) {
                    console.log(res);
                    swal("Success!", "Updated Successfully", "success");
                    loadUserRolePermissions(userId1);
                    $('#createPermissionForm')[0].reset();
                    $('#editPermissionForm')[0].reset();
                    $('#create_role_permssion_area').hide();
                    $('#edit_role_permssion_area').hide();
                    $('.list_area').show();
                    // Optional reset/hide logic here
                },
                error: function(xhr) {
                    console.error(xhr);
                    const errorMsg = xhr.responseJSON?.message || "Something went wrong.";
                    swal("Error!", errorMsg, "error");
                }
            });
        });


        $('#cancelBtn').on('click', function() {
            $('#createPermissionForm')[0].reset();
            $('#editscreatePermissionForm')[0].reset();
            storedRoleId = null;
            initializeFeatureTable(allFeatures);
        });
        $('.create_role_permssion').on('click', function(e) {
            e.preventDefault();
            loadFeatures('#feature-access-list');
            $('#create_role_permssion_area').show();
            $('.list_area').hide();
        });
        $('.create_role').on('click', function(e) {
            e.preventDefault();
            $('#create_role').show();
            $('#create_role_permssion_area').hide();
            $('.list_area').hide();
        });

        $('.cancel_bulk').on('click', function(e) {
            e.preventDefault();
            $('#createPermissionForm')[0].reset();
            $('#editPermissionForm')[0].reset();
            $('#create_role_permssion_area  ').hide();
            $('#edit_role_permssion_area  ').hide();
            $('.list_area').show();
        });

        $('.cancel_create_role').on('click', function(e) {
            e.preventDefault();
            $('#create_role').hide();
            $('#create_role_permssion_area  ').show();
            // $('.list_area').show();
        });
        $('#rolecancelBtn').on('click', function() {
            $('#createRole')[0].reset();
        });


        // creating role
        $('#createRole').on('submit', function(e) {
            e.preventDefault();

            const role_name = $('#role_name1').val().trim();
            const department_id = $('select[name="department1"]').val();
            const role_description = $('#role_description').val().trim();
            const userId = "<?= $this->session->userdata('id'); ?>"; // or however you're retrieving user ID
            console.log(role_name);
            console.log(department_id);

            if (!role_name || !department_id) {
                alert("Please fill all required fields.");
                return;
            }

            $.ajax({
                url: "<?= base_url('/employee/EmployeeRoles/create_role'); ?>",
                method: "POST",
                dataType: "json",
                data: {
                    user_id: userId,
                    department_id: department_id,
                    role_name: role_name,
                    description: role_description,
                    csrf_test_name: "<?= $this->security->get_csrf_hash(); ?>"
                },
                success: function(response) {
                    console.log(response);
                    swal("Success!", "Role created successfully.", "success");
                    $('#createRole')[0].reset(); // Clear the form
                    loadUserRolePermissions(userId)
                    loadRolesForCurrentUser();
                    $('#create_role').hide();
                    $('#create_role_permssion_area  ').show();
                },
                error: function(res) {
                    console.log(res); // Optional for debugging

                    const errorMsg = res.responseJSON?.message || "Something went wrong.";
                    swal("Error!", errorMsg, "error");
                }
            });
        });
        loadUserRolePermissions(userId);
        loadRolesForCurrentUser();
    });
</script>