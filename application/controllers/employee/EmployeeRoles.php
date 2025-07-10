<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeRoles extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('auth_model'); // Changed to auth_model
    }

    public function index() {
        require_feature(11);
        $data = array();
        $data['is_employee_admin'] = true;
        $data['can_edit'] = $this->auth_model->get_permission(11);
        $data['page_title'] = 'Create Roles & Permission';
        $data['departments'] = $this->admin_model->get_by_user('departments');
        $data['main_content'] = $this->load->view('admin/employee/hrm/role_permission', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
    }
    public function role()
    {
        require_feature(11);
        $data = array();
        $data['is_employee_admin'] = true;
        $data['can_edit'] = $this->auth_model->get_permission(11);
        $data['page_title'] = 'Roles & Permission';
        $data['departments'] = $this->admin_model->get_by_user_status('departments');
        $data['default_roles'] = $this->admin_model->select_asc('default_roles');
        $data['roles'] = $this->admin_model->get_role_by_user_status('employee_roles');
        $data['main_content'] = $this->load->view('admin/user/hrm/role', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
    }
    // public function create_role() {
    //     $input = $this->get_input_data();

    //     $this->form_validation->set_data($input);
    //     $this->form_validation->set_rules('user_id', 'Organization ID', 'required|integer');
    //     $this->form_validation->set_rules('department_id', 'Department ID', 'required|integer');
    //     $this->form_validation->set_rules('role_name', 'Role Name', 'required|max_length[100]');
    //     $this->form_validation->set_rules('description', 'Description', 'trim|max_length[500]');

    //     if ($this->form_validation->run() === FALSE) {
    //         return $this->json_response(400, 'Validation failed', [
    //             'errors' => $this->form_validation->error_array()
    //         ]);
    //     }

    //     $user_id = $input['user_id'];
    //     $department_id = $input['department_id'];
    //     $role_name = $input['role_name'];
    //     $description = $input['description'] ?? null;

    //     $existing = $this->db
    //         ->where([
    //             'user_id' => $user_id,
    //             'department_id' => $department_id,
    //             'role_name' => $role_name
    //         ])
    //         ->get('employee_roles')
    //         ->row();

    //     if ($existing) {
    //         return $this->json_response(409, 'Role already exists in this organization and department');
    //     }

    //     $data = [
    //         'user_id' => $user_id,
    //         'department_id' => $department_id,
    //         'role_name' => $role_name,
    //         'description' => $description,
    //         'created_at' => get_user_datetime_only($user_id), // Using helper function
    //         'updated_at' => get_user_datetime_only($user_id)  // Using helper function
    //     ];

    //     $this->db->trans_start();
    //     $this->db->insert('employee_roles', $data);
    //     $role_id = $this->db->insert_id();
    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === FALSE) {
    //         log_message('error', 'Database error: ' . $this->db->error()['message']);
    //         return $this->json_response(500, 'Database operation failed');
    //     }

    //     return $this->json_response(201, 'Role created successfully', [
    //         'role_id' => $role_id,
    //         'role_name' => $role_name
    //     ]);
    // }
    /**
     * Give freshly‑created roles their default permissions.
     *
     * • If the role definition is 1  → insert ALL features
     * • If the role definition is 4 or 5 → insert features 3,5,10,11,12
     *
     * Call this immediately after inserting into employee_roles.
     *
     * @param int $user_id  The user we just assigned the role to
     */
    /**
     * Give newly‑created roles their default permissions.
     *
     * • role_id 1 or 2 → all features
     * • role_id 3      → features 6,3,9,8,1
     * • role_id 4 or 5 → features 3,5,10,11,12
     *
     * Call right after inserting into employee_roles.
     *
     * @param int $user_id  The user we just assigned the role to
     */
    /**
     * Give every NEW “super” role (role_id 1‑5) its default permissions.
     * A role is “new” if it has no rows yet in role_feature_access.
     * Nothing is updated; existing roles are ignored.
     */
    // public function grant_super_role_access(int $user_id): void
    // {
    //     /* ------------------------------------------------------------
    //  * 1.  Find *new* employee_roles rows that need permissions
    //  * ------------------------------------------------------------ */
    //     $empRoles = $this->db->query(
    //         "SELECT er.id, er.role_id
    //        FROM employee_roles er
    //       WHERE er.user_id = ?
    //         AND er.role_id IN (1,2,3,4,5)
    //         AND er.status  = 1
    //         AND NOT EXISTS (
    //               SELECT 1
    //                 FROM role_feature_access r
    //                WHERE r.role_id = er.id
    //                  AND r.user_id = er.user_id
    //              )",
    //         [$user_id]
    //     )->result();

    //     if (empty($empRoles)) {
    //         return;                                // nothing new to insert
    //     }

    //     /* ------------------------------------------------------------
    //  * 2.  Prepare one big insert batch
    //  * ------------------------------------------------------------ */
    //     $now   = get_user_datetime_only($user_id);
    //     $batch = [];

    //     foreach ($empRoles as $er) {
    //         switch ((int) $er->role_id) {
    //             case 1:  // fall‑through
    //             case 2:  // full access
    //                 $ids = $this->db->select('id')->from('app_features')
    //                     ->get()->result_array();
    //                 $featureIds = array_column($ids, 'id');
    //                 break;

    //             case 3:
    //                 $featureIds = [6, 3, 9, 8, 1];
    //                 break;

    //             case 4:  // fall‑through
    //             case 5:
    //                 $featureIds = [3, 5, 10, 11, 12];
    //                 break;

    //             default:
    //                 continue 2;                    // skip unknown role
    //         }

    //         foreach ($featureIds as $fid) {
    //             $batch[] = [
    //                 'role_id'    => $er->id,       // PK in employee_roles
    //                 'user_id'    => $user_id,
    //                 'feature_id' => $fid,
    //                 'is_read'    => 1,
    //                 'is_write'   => 1,
    //                 'is_action'  => 1,
    //                 'is_delete'  => 1,
    //                 'status'     => 1,
    //                 'created_at' => $now,
    //                 'updated_at' => $now,
    //             ];
    //         }
    //     }

    //     /* ------------------------------------------------------------
    //  * 3.  Insert (duplicates impossible by design)
    //  * ------------------------------------------------------------ */
    //     if (!empty($batch)) {
    //         $this->db->insert_batch('role_feature_access', $batch);

    //         if ($this->db->error()['code']) {
    //             log_message(
    //                 'error',
    //                 'grant_super_role_access(): ' . json_encode($this->db->error())
    //             );
    //         }
    //     }
    // }

    /* --------------------------------------------------------------------------
 * 1.  Template: which features each *default* role should get on day‑one
 * -------------------------------------------------------------------------- */
    private function get_default_feature_ids(int $default_role_id): array
    {
        switch ($default_role_id) {
            /* Full access — pull every feature ID dynamically */
            case 1:     // Super Admin
            case 2:     // Org Admin
                $rows = $this->db->select('id')->from('app_features')->get()->result_array();
                return array_column($rows, 'id');

                /* CEO                           */
            case 3:
                return [6, 3, 9, 8, 1];

                /* Team Lead / Manager           */
            case 4:
            case 5:
                return [3, 5, 10, 11, 12];

            default:
                return [];   // unknown role ➜ no defaults
        }
    }

    /* --------------------------------------------------------------------------
 * 2.  Seed one employee_role row once (idempotent)
 * -------------------------------------------------------------------------- */
    private function seed_role_permissions(
        int $emp_role_pk,
        int $default_role_id,
        int $user_id
    ): void {
        $already = $this->db->where([
            'role_id' => $emp_role_pk,
            'user_id' => $user_id
        ])->count_all_results('role_feature_access');

        if ($already) return;                          // permissions already exist

        $feature_ids = $this->get_default_feature_ids($default_role_id);
        if (empty($feature_ids)) return;               // nothing to seed

        $now   = get_user_datetime_only($user_id);
        $batch = [];

        foreach ($feature_ids as $fid) {
            $batch[] = [
                'role_id'    => $emp_role_pk,
                'user_id'    => $user_id,
                'feature_id' => $fid,
                'is_read'    => 1,
                'is_write'   => 1,
                'is_action'  => 1,
                'is_delete'  => 1,
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        $this->db->insert_batch('role_feature_access', $batch);
    }

    /* --------------------------------------------------------------------------
 * 3.  Main: create_role()
 * -------------------------------------------------------------------------- */
    public function create_role()
    {
        /* Guard clause */
        if (!$this->input->post()) {
            redirect(base_url('admin/roles_permissions'));
        }

        /* POST arrays (all same length) */
        $role_names       = $this->input->post('role_name',        true);
        $department_ids   = $this->input->post('department_id',    true);
        $default_role_ids = $this->input->post('default_role_id',  true);
        $statuses         = $this->input->post('status',           true);

        $user_id = user()->id;
        $now     = get_user_datetime_only($user_id);
        $errors  = [];

        /* One transaction for everything */
        $this->db->trans_start();

        if (is_array($role_names) && count($role_names)) {
            foreach ($role_names as $idx => $role_name) {

                $department_id   = $department_ids[$idx]   ?? null;
                $default_role_id = $default_role_ids[$idx] ?? null;
                $status          = (int) ($statuses[$idx]  ?? 0);

                if (!$role_name || !$department_id || !$default_role_id) {
                    continue;      // skip incomplete rows
                }

                /* Does this employee already have that default_role in that dept? */
                $row = $this->db->get_where('employee_roles', [
                    'user_id'       => $user_id,
                    'department_id' => $department_id,
                    'role_id'       => $default_role_id
                ])->row();

                /* -------------------------------------------------------- *
             * A) Row exists ➜ update or delete
             * -------------------------------------------------------- */
                if ($row) {
                    if ($status === 0) {                // deactivate  ➜ delete row
                        $assigned = $this->db->where('role_id', $row->id)
                            ->count_all_results('employees');
                        if ($assigned) {
                            $errors[] = "Cannot deactivate <strong>{$role_name}</strong> – role in use.";
                            continue;
                        }
                        // remove role + its permissions
                        $this->db->delete('employee_roles',      ['id' => $row->id]);
                        $this->db->delete('role_feature_access', [
                            'role_id' => $row->id,
                            'user_id' => $user_id
                        ]);
                    } else {                           // activate/update name
                        $this->db->update('employee_roles', [
                            'role_name'  => $role_name,
                            'status'     => 1,
                            'updated_at' => $now
                        ], ['id' => $row->id]);

                        // ensure permissions exist (idempotent)
                        $this->seed_role_permissions($row->id, $default_role_id, $user_id);
                    }

                    continue;   // done with existing row
                }

                /* -------------------------------------------------------- *
             * B) Brand‑new row ➜ insert + seed defaults
             * -------------------------------------------------------- */
                if ($status === 1) {      // insert only if flagged "Active"
                    $this->db->insert('employee_roles', [
                        'user_id'       => $user_id,
                        'department_id' => $department_id,
                        'role_id'       => $default_role_id,
                        'role_name'     => $role_name,
                        'status'        => 1,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);

                    $new_pk = $this->db->insert_id();
                    $this->seed_role_permissions($new_pk, $default_role_id, $user_id);
                }
            }
        }

        $this->db->trans_complete();

        /* Flash + redirect */
        if ($this->db->trans_status() === FALSE) {
            $errors[] = 'Database error. Please try again.';
        }

        if ($errors) {
            $this->session->set_flashdata('error', implode('<br><br>', $errors));
        } else {
            $this->session->set_flashdata('msg', 'Roles updated successfully.');
        }

        redirect(base_url('admin/roles_permissions'));
    }





    private function get_input_data() {
        $content_type = $this->input->server('CONTENT_TYPE');
        if (strpos($content_type, 'application/json') !== false) {
            return json_decode(trim(file_get_contents('php://input')), true) ?? [];
        }
        return $this->input->post() ?: [];
    }

    public function get_app_features() {
        try {
            $this->db->select('id, name');
            $this->db->order_by('name', 'asc');
            $query = $this->db->get('app_features');

            if ($query === FALSE) {
                log_message('error', 'Database error: ' . $this->db->error()['message']);
                throw new Exception('Database query failed', 500);
            }
    
            $features = $query->result();
            
            if (empty($features)) {
                log_message('error', 'No features found in the database');
                return $this->json_response(404, 'No features found');
            }
    
            return $this->json_response(200, 'Features fetched successfully', [
                'features' => $features
            ]);
    
        } catch (Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return $this->json_response($e->getCode(), $e->getMessage());
        }
    }

    public function get_roles_dropdown() {
        $input = $this->get_input_data();
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');
        $this->form_validation->set_rules('department_id', 'Department ID', 'required|integer');
    
        if ($this->form_validation->run() === FALSE) {
            return $this->json_response(400, 'Validation failed', [
                'errors' => $this->form_validation->error_array()
            ]);
        }
    
        $user_id = $input['user_id'];
        $department_id = $input['department_id'];
    
        try {
            $this->db->select('id, role_name');
            $this->db->where([
                'user_id' => $user_id,
                'department_id' => $department_id
            ]);
            $this->db->order_by('role_name', 'asc');
            $query = $this->db->get('employee_roles');
    
            if ($query === FALSE) {
                log_message('error', 'Database error: ' . $this->db->error()['message']);
                throw new Exception('Database query failed', 500);
            }
    
            $roles = $query->result();
    
            if (empty($roles)) {
                return $this->json_response(404, 'No roles found');
            }
    
            return $this->json_response(200, 'Roles fetched successfully', [
                'roles' => $roles
            ]);
    
        } catch (Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return $this->json_response($e->getCode(), $e->getMessage());
        }
    }

    public function get_roles_by_user() {
        $user_id = $this->session->userdata('id');

        if (empty($user_id) || !is_numeric($user_id)) {
            return $this->json_response(400, 'Invalid or missing user ID');
        }

        $this->db->select('id, role_name, department_id');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('role_name', 'asc');
        $query = $this->db->get('employee_roles');

        if ($query === FALSE) {
            log_message('error', 'Database error: ' . $this->db->error()['message']);
            return $this->json_response(500, 'Database query failed');
        }

        $roles = $query->result_array();

        if (empty($roles)) {
            return $this->json_response(404, 'No roles found for this user');
        }

        return $this->json_response(200, 'Roles fetched successfully', ['roles' => $roles]);
    }

    public function get_user_roles() {
        $user_id = $this->session->userdata('user_id');
        if (empty($user_id) || !is_numeric($user_id)) {
            echo json_encode(['status' => 400, 'message' => 'Invalid or missing user ID']);
            return;
        }

        $this->db->select('id, role_name');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('role_name', 'asc');
        $query = $this->db->get('employee_roles');

        if ($query === FALSE) {
            echo json_encode(['status' => 500, 'message' => 'Database query failed']);
            return;
        }

        $roles = $query->result_array();

        if (empty($roles)) {
            echo json_encode(['status' => 404, 'message' => 'No roles found for this user']);
            return;
        }

        echo json_encode(['status' => 200, 'message' => 'Roles found', 'data' => $roles]);
    }

    public function store_role_feature_access() {
        try {
            $request_body = $this->input->raw_input_stream;
            $input = json_decode($request_body, TRUE);
    
            if ($input === NULL || !is_array($input)) {
                log_message('error', 'Invalid JSON format in request body: ' . $request_body);
                return $this->json_response(400, 'Invalid JSON format. Please ensure the request body is correctly formatted.');
            }
    
            $this->form_validation->set_data([
                'role_id' => $input['role_id'] ?? null,
                'user_id' => $input['user_id'] ?? null
            ]);
            $this->form_validation->set_rules('role_id', 'Role ID', 'required|integer');
            $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');
    
            if ($this->form_validation->run() === FALSE) {
                $errors = $this->form_validation->error_array();
                log_message('error', 'Validation failed: ' . json_encode($errors));
                return $this->json_response(400, 'Validation failed. Please ensure all required fields are provided correctly.', ['errors' => $errors]);
            }
    
            if (!isset($input['features']) || !is_array($input['features']) || empty($input['features'])) {
                log_message('error', 'The Features field is required and must be a non-empty array.');
                return $this->json_response(400, 'The Features field is required and must be a non-empty array.');
            }
    
            $role_id = $input['role_id'];
            $user_id = $input['user_id'];
            $features = $input['features'];
            $errors = [];
    
            if (!$this->db->where('id', $role_id)->get('employee_roles')->row()) {
                return $this->json_response(404, 'Role does not exist. Please verify the Role ID.');
            }
    
            if (!$this->db->where('id', $user_id)->get('users')->row()) {
                return $this->json_response(404, 'User does not exist. Please verify the User ID.');
            }
    
            $this->db->trans_start();
            $date = get_user_datetime_only($user_id);
            $this->db->where('role_id', $role_id)
                ->where('user_id', $user_id)
                ->update('role_feature_access', [
                    'status' => 0,
                    'updated_at' => $date // Using helper function
                ]);

            foreach ($features as $key => $feature) {
                $this->form_validation->set_data($feature);
                $this->form_validation->set_rules('feature_id', 'Feature ID', 'required|integer');
                $this->form_validation->set_rules('is_read', 'Read Permission', 'required|integer');
                $this->form_validation->set_rules('is_write', 'Write Permission', 'required|integer');
                $this->form_validation->set_rules('is_action', 'Action Permission', 'required|integer');
                $this->form_validation->set_rules('is_delete', 'Delete Permission', 'required|integer');
    
                if ($this->form_validation->run() === FALSE) {
                    $errors['features'][$key] = $this->form_validation->error_array();
                    continue;
                }
    
                $feature_id = $feature['feature_id'];

                if (!$this->db->where('id', $feature_id)->get('app_features')->row()) {
                    $errors['features'][$key]['feature_id'] = 'Feature ID ' . $feature_id . ' does not exist';
                    continue;
                }
    
                $data = [
                    'role_id' => $role_id,
                    'user_id' => $user_id,
                    'feature_id' => $feature_id,
                    'is_read' => $feature['is_read'],
                    'is_write' => $feature['is_write'],
                    'is_action' => $feature['is_action'],
                    'is_delete' => $feature['is_delete'],
                    'status' => (
                        $feature['is_read'] || 
                        $feature['is_write'] || 
                        $feature['is_action'] || 
                        $feature['is_delete']
                    ) ? 1 : 0,
                    'created_at' => get_user_datetime_only($user_id), // Using helper function
                    'updated_at' => get_user_datetime_only($user_id)  // Using helper function
                ];
    
                $existing = $this->db->get_where('role_feature_access', [
                    'role_id' => $role_id,
                    'user_id' => $user_id,
                    'feature_id' => $feature_id
                ])->row();
    
                if ($existing) {
                    $date = get_user_datetime_only($user_id);
                    $this->db->where('id', $existing->id)
                             ->update('role_feature_access', [
                                 'is_read' => $feature['is_read'],
                                 'is_write' => $feature['is_write'],
                                 'is_action' => $feature['is_action'],
                                 'is_delete' => $feature['is_delete'],
                                 'status' => $data['status'],
                                  'updated_at' => $date 
                             ]);
                } else {
                    $this->db->insert('role_feature_access', $data);
                }
            }
    
            $this->db->trans_complete();
    
            if ($this->db->trans_status() === FALSE) {
                $db_error = $this->db->error();
                log_message('error', 'Database error: ' . json_encode($db_error));
                return $this->json_response(500, 'An internal server error occurred.', [
                    'error' => $db_error['message'],
                    'code' => $db_error['code']
                ]);
            }
    
            if (!empty($errors)) {
                return $this->json_response(400, 'Validation failed for some features.', ['errors' => $errors]);
            }
    
            return $this->json_response(201, 'Role feature access stored successfully.');
        } catch (Exception $e) {
            log_message('error', 'Unexpected error: ' . $e->getMessage());
            return $this->json_response(500, 'An unexpected error occurred.', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }

    // public function get_user_role_feature_permissions() {
    //     $user_id = $this->input->get('user_id');

    //     if (!$user_id) {
    //         return $this->json_response(400, 'Missing user_id');
    //     }

    //     $user_exists = $this->db->where('id', $user_id)->get('users')->row();
    //     if (!$user_exists) {
    //         return $this->json_response(404, 'User not found');
    //     }

    //     $roles = $this->db->select('id, role_name, department_id')
    //                       ->from('employee_roles')
    //                       ->where('user_id', $user_id)
    //                       ->get()
    //                       ->result();

    //     if (empty($roles)) {
    //         return $this->json_response(404, 'No roles found for the given user');
    //     }

    //     $result = [];

    //     foreach ($roles as $role) {
    //         $access_entries = $this->db
    //             ->select('rfa.feature_id, af.name, rfa.is_read, rfa.is_write, rfa.is_action, rfa.is_delete')
    //             ->from('role_feature_access as rfa')
    //             ->join('app_features as af', 'af.id = rfa.feature_id')
    //             ->where('rfa.role_id', $role->id)
    //             ->where('rfa.user_id', $user_id)
    //             ->where('rfa.status', 1)
    //             ->get()
    //             ->result();

    //         $features = [];
    //         foreach ($access_entries as $entry) {
    //             $features[] = [
    //                 'feature_id' => $entry->feature_id,
    //                 'feature_name' => $entry->name,
    //                 'is_read' => $entry->is_read,
    //                 'is_write' => $entry->is_write,
    //                 'is_action' => $entry->is_action,
    //                 'is_delete' => $entry->is_delete
    //             ];
    //         }

    //         $result[] = [
    //             'role_id' => $role->id,
    //             'role_name' => $role->role_name,
    //             'department_id' => $role->department_id,
    //             'features' => $features
    //         ];
    //     }

    //     return $this->json_response(200, 'Data fetched successfully', $result);
    // }
    public function get_user_role_feature_permissions()
    {
        $user_id = $this->input->get('user_id');

        if (!$user_id) {
            $user_id =  $this->session->userdata('employee_org_id');
        }
        if (!$user_id) {
            return $this->json_response(400, 'Missing user_id');
        }
        $user_exists = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user_exists) {
            return $this->json_response(404, 'User not found');
        }

        // Join departments to get department name
        $roles = $this->db->select('er.id, er.role_name, er.department_id, d.name as department_name')
            ->from('employee_roles as er')
            ->join('departments as d', 'er.department_id = d.id', 'left')
            ->where('er.user_id', $user_id)
            ->get()
            ->result();

        if (empty($roles)) {
            return $this->json_response(404, 'No roles found for the given user');
        }

        $result = [];

        foreach ($roles as $role) {
            $access_entries = $this->db
                ->select('rfa.feature_id, af.name, rfa.is_read, rfa.is_write, rfa.is_action, rfa.is_delete')
                ->from('role_feature_access as rfa')
                ->join('app_features as af', 'af.id = rfa.feature_id')
                ->where('rfa.role_id', $role->id)
                ->where('rfa.user_id', $user_id)
                ->where('rfa.status', 1)
                ->get()
                ->result();

            $features = [];
            foreach ($access_entries as $entry) {
                $features[] = [
                    'feature_id'   => $entry->feature_id,
                    'feature_name' => $entry->name,
                    'is_read'      => $entry->is_read,
                    'is_write'     => $entry->is_write,
                    'is_action'    => $entry->is_action,
                    'is_delete'    => $entry->is_delete
                ];
            }

            $result[] = [
                'role_id'         => $role->id,
                'role_name'       => $role->role_name,
                'department_id'   => $role->department_id,
                'department_name' => $role->department_name,  // Added
                'features'        => $features
            ];
        }

        return $this->json_response(200, 'Data fetched successfully', $result);
    }


    public function get_feature_access_by_user_and_role() {
        $user_id = $this->input->get('user_id');
        $role_id = $this->input->get('role_id');
    
        if (!$user_id || !$role_id) {
            return $this->json_response(400, 'Missing user_id or role_id');
        }
    
        $user_exists = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user_exists) {
            return $this->json_response(404, 'User not found');
        }
    
        $role = $this->db->where('id', $role_id)
            ->where('user_id', $user_id)
            ->get('employee_roles')
            ->row();
        if (!$role) {
            return $this->json_response(404, 'Role not found for the given user');
        }

        $access_entries = $this->db
            ->select('rfa.feature_id, af.name, rfa.is_read, rfa.is_write, rfa.is_action, rfa.is_delete')
            ->from('role_feature_access as rfa')
            ->join('app_features as af', 'af.id = rfa.feature_id')
            ->where('rfa.role_id', $role_id)
            ->where('rfa.user_id', $user_id)
            ->where('rfa.status', 1)
            ->get()
            ->result();
    
        if (empty($access_entries)) {
            return $this->json_response(404, 'No active feature access entries found');
        }
    
        $features = [];
        foreach ($access_entries as $entry) {
            $features[] = [
                'feature_id' => $entry->feature_id,
                'feature_name' => $entry->name,
                'is_read' => $entry->is_read,
                'is_write' => $entry->is_write,
                'is_action' => $entry->is_action,
                'is_delete' => $entry->is_delete
            ];
        }
    
        $response = [
            'role_id' => $role->id,
            'role_name' => $role->role_name,
            'department_id' => $role->department_id,
            'features' => $features
        ];
    
        return $this->json_response(200, 'Feature access data fetched successfully', $response);
    }

    public function delete_role() {
        header('Content-Type: application/json');

        $employee_user_id = $this->input->post('user_id');
        $department_id = $this->input->post('department_id');
        $role_name = $this->input->post('role_name');

        if (!$employee_user_id || !$department_id || !$role_name) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'message' => 'Missing required parameters.'
            ]);
            return;
        }

        $role = $this->db->where('user_id', $employee_user_id)
                         ->where('department_id', $department_id)
                         ->where('role_name', $role_name)
                         ->get('employee_roles')
                         ->row();

        if (!$role) {
            http_response_code(404);
            echo json_encode([
                'status' => false,
                'message' => 'Role not found or access denied.'
            ]);
            return;
        }

        $assigned = $this->db->where('role_id', $role->id)->limit(1)->get('employees')->row();
        if ($assigned) {
            http_response_code(409);
            echo json_encode([
                'status' => false,
                'message' => 'Cannot delete: Role is assigned to one or more employees.'
            ]);
            return;
        }

        // $this->db->where('id', $role->id)
        //          ->update('employee_roles', [
        //              'deleted_at' => get_user_datetime_only($employee_user_id) // Using helper function
        //          ]);
        $this->db->delete('employee_roles', ['id' => $role->id]);

        if ($this->db->affected_rows() > 0) {
            echo json_encode([
                'status' => true,
                'message' => 'Role deleted successfully.'
            ]);
        } else {
            http_response_code(500);
            $db_error = $this->db->error();
            echo json_encode([
                'status' => false,
                'message' => 'Failed to delete role. Database error.',
                'error' => $db_error['message'] ?? 'Unknown error'
            ]);
        }
    }
}