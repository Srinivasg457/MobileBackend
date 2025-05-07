<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeRoles extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Create Roles & Permission';
        $data['departments'] = $this->admin_model->get_by_user('departments');
        $data['main_content'] = $this->load->view('admin/employee/hrm/role_permission', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    /**
     * Creates a new role for a specific organization and department.
     */
    public function create_role() {
        $input = $this->get_input_data();
    
        // 🔧 Set input data for validation
        $this->form_validation->set_data($input);
    
        // Validation rules
        $this->form_validation->set_rules('user_id', 'Organization ID', 'required|integer');
        $this->form_validation->set_rules('department_id', 'Department ID', 'required|integer');
        $this->form_validation->set_rules('role_name', 'Role Name', 'required|max_length[100]');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[500]');
    
        if ($this->form_validation->run() === FALSE) {
            return $this->json_response(400, 'Validation failed', [
                'errors' => $this->form_validation->error_array()
            ]);
        }

        $user_id = $input['user_id'];
        $department_id = $input['department_id'];
        $role_name = $input['role_name'];
        $description = $input['description'] ?? null;

        // Check for existing role
        $existing = $this->db
            ->where([
                'user_id' => $user_id,
                'department_id' => $department_id,
                'role_name' => $role_name
            ])
            ->get('employee_roles')
            ->row();

        if ($existing) {
            return $this->json_response(409, 'Role already exists in this organization and department');
        }

        $data = [
            'user_id' => $user_id,
            'department_id' => $department_id,
            'role_name' => $role_name,
            'description' => $description
        ];

        $this->db->trans_start();
        $this->db->insert('employee_roles', $data);
        $role_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Database error: ' . $this->db->error()['message']);
            return $this->json_response(500, 'Database operation failed');
        }

        return $this->json_response(201, 'Role created successfully', [
            'role_id' => $role_id,
            'role_name' => $role_name
        ]);
    }

    /**
     * Common method to get input data regardless of content type.
     */
    private function get_input_data() {
        $content_type = $this->input->server('CONTENT_TYPE');
        if (strpos($content_type, 'application/json') !== false) {
            return json_decode(trim(file_get_contents('php://input')), true) ?? [];
        }
        return $this->input->post() ?: [];
    }

    public function get_app_features() {
        try {
            // Get the features from the app_features table
            $this->db->select('id, feature_name');
            $this->db->order_by('feature_name', 'asc');
            $query = $this->db->get('app_features');
            
            // Check for database errors
            if ($query === FALSE) {
                // Log the specific database error
                log_message('error', 'Database error: ' . $this->db->error()['message']);
                throw new Exception('Database query failed', 500); // Throwing exception for DB failure
            }
    
            $features = $query->result();
            
            // Check if no features were found
            if (empty($features)) {
                log_message('error', 'No features found in the database');
                return $this->json_response(404, 'No features found');
            }
    
            // Return the features in JSON format
            return $this->json_response(200, 'Features fetched successfully', [
                'features' => $features
            ]);
    
        } catch (Exception $e) {
            // Handle exceptions thrown during database queries or logic
            log_message('error', 'Exception: ' . $e->getMessage());
            return $this->json_response($e->getCode(), $e->getMessage());
        }
    }
    public function get_roles_dropdown() {
        $input = $this->get_input_data();
        $this->form_validation->set_data($input);

        // Validate user_id and department_id
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
            // Fetch roles filtered by user_id and department_id
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
    
    // public function store_role_feature_access() {
    //     // Get the raw input stream
    //     $request_body = $this->input->raw_input_stream;
    
    //     // Decode the JSON data
    //     $input = json_decode($request_body, TRUE);
    
    //     // Check if decoding was successful and if $input is an array
    //     if ($input === NULL || !is_array($input)) {
    //         return $this->json_response(400, 'Invalid JSON format in request body');
    //     }
    
    //     // Validate role_id only with form_validation
    //     $this->form_validation->set_data(['role_id' => $input['role_id'] ?? null]);
    //     $this->form_validation->set_rules('role_id', 'Role ID', 'required|integer');
    
    //     if ($this->form_validation->run() === FALSE) {
    //         return $this->json_response(400, 'Validation failed', [
    //             'errors' => $this->form_validation->error_array()
    //         ]);
    //     }
    
    //     // Validate features manually
    //     if (!isset($input['features']) || !is_array($input['features']) || empty($input['features'])) {
    //         return $this->json_response(400, 'Validation failed', [
    //             'errors' => ['features' => 'The Features field is required and must be a non-empty array.']
    //         ]);
    //     }
    
    //     $role_id = $input['role_id'];
    //     $features = $input['features'];
    //     $insert_data = [];
    //     $errors = [];
    
    //     // Check if the role exists
    //     $role_exists = $this->db->where('id', $role_id)->get('employee_roles')->row();
    //     if (!$role_exists) {
    //         return $this->json_response(404, 'Role ID does not exist');
    //     }
    
    //     // Validate each feature
    //     foreach ($features as $key => $feature) {
    //         $this->form_validation->set_data($feature);
    //         $this->form_validation->set_rules('feature_id', 'Feature ID', 'required|integer');
    //         $this->form_validation->set_rules('is_read', 'Read Permission', 'required|integer');
    //         $this->form_validation->set_rules('is_write', 'Write Permission', 'required|integer');
    //         $this->form_validation->set_rules('is_action', 'Action Permission', 'required|integer');
    //         $this->form_validation->set_rules('is_delete', 'Delete Permission', 'required|integer');
    
    //         if ($this->form_validation->run() === FALSE) {
    //             $errors['features'][$key] = $this->form_validation->error_array();
    //             continue;
    //         }
    
    //         // Check if feature exists
    //         $feature_exists = $this->db->where('id', $feature['feature_id'])->get('app_features')->row();
    //         if (!$feature_exists) {
    //             $errors['features'][$key]['feature_id'] = 'Feature ID ' . $feature['feature_id'] . ' does not exist';
    //             continue;
    //         }
    
    //         $insert_data[] = [
    //             'role_id' => $role_id,
    //             'feature_id' => $feature['feature_id'],
    //             'is_read' => $feature['is_read'],
    //             'is_write' => $feature['is_write'],
    //             'is_action' => $feature['is_action'],
    //             'is_delete' => $feature['is_delete']
    //         ];
    //     }
    
    //     // If there are validation errors
    //     if (!empty($errors)) {
    //         return $this->json_response(400, 'Validation failed for one or more features', ['errors' => $errors]);
    //     }
    
    //     // Start DB transaction
    //     $this->db->trans_start();
    //     $this->db->where('role_id', $role_id)->delete('role_feature_access');
    
    //     if (!empty($insert_data)) {
    //         $this->db->insert_batch('role_feature_access', $insert_data);
    //     }
    
    //     $this->db->trans_complete();
    
    //     if ($this->db->trans_status() === FALSE) {
    //         log_message('error', 'Database error: ' . json_encode($this->db->error()));
    //         return $this->json_response(500, 'Failed to store role feature access');
    //     }
    
    //     return $this->json_response(201, 'Role feature access stored successfully for multiple features');
    // }
   

   


    //for update and insert same logic
    
    public function store_role_feature_access() {
        $request_body = $this->input->raw_input_stream;
        $input = json_decode($request_body, TRUE);
    
        if ($input === NULL || !is_array($input)) {
            return $this->json_response(400, 'Invalid JSON format in request body');
        }
    
        // Validate role_id and user_id
        $this->form_validation->set_data([
            'role_id' => $input['role_id'] ?? null,
            'user_id' => $input['user_id'] ?? null
        ]);
        $this->form_validation->set_rules('role_id', 'Role ID', 'required|integer');
        $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');
    
        if ($this->form_validation->run() === FALSE) {
            return $this->json_response(400, 'Validation failed', [
                'errors' => $this->form_validation->error_array()
            ]);
        }
    
        if (!isset($input['features']) || !is_array($input['features']) || empty($input['features'])) {
            return $this->json_response(400, 'Validation failed', [
                'errors' => ['features' => 'The Features field is required and must be a non-empty array.']
            ]);
        }
    
        $role_id = $input['role_id'];
        $user_id = $input['user_id'];
        $features = $input['features'];
        $errors = [];
    
        $role_exists = $this->db->where('id', $role_id)->get('employee_roles')->row();
        if (!$role_exists) {
            return $this->json_response(404, 'Role ID does not exist');
        }
    
        $user_exists = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user_exists) {
            return $this->json_response(404, 'User ID does not exist');
        }
    
        $this->db->trans_start();
    
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
    
            $feature_exists = $this->db->where('id', $feature_id)->get('app_features')->row();
            if (!$feature_exists) {
                $errors['features'][$key]['feature_id'] = 'Feature ID ' . $feature_id . ' does not exist';
                continue;
            }
    
            // Data to insert or update
            $data = [
                'is_read' => $feature['is_read'],
                'is_write' => $feature['is_write'],
                'is_action' => $feature['is_action'],
                'is_delete' => $feature['is_delete']
            ];
    
            // Check if the entry exists
            $existing = $this->db->get_where('role_feature_access', [
                'role_id' => $role_id,
                'user_id' => $user_id,
                'feature_id' => $feature_id
            ])->row();
    
            if ($existing) {
                // Update existing record
                $this->db->where('id', $existing->id)->update('role_feature_access', $data);
            } else {
                // Insert new record
                $data['role_id'] = $role_id;
                $data['user_id'] = $user_id;
                $data['feature_id'] = $feature_id;
                $this->db->insert('role_feature_access', $data);
            }
        }
    
        $this->db->trans_complete();
    
        if (!empty($errors)) {
            return $this->json_response(400, 'Validation failed for one or more features', ['errors' => $errors]);
        }
    
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Database error: ' . json_encode($this->db->error()));
            return $this->json_response(500, 'Failed to store role feature access');
        }
    
        return $this->json_response(201, 'Role feature access stored successfully for multiple features');
    }


   public function get_user_role_feature_permissions() {
    // Retrieve user_id from the request
    $user_id = $this->input->get('user_id');

    // Validate if user_id is provided
    if (!$user_id) {
        return $this->json_response(400, 'Missing user_id');
    }

    // Check if the user exists
    $user_exists = $this->db->where('id', $user_id)->get('users')->row();
    if (!$user_exists) {
        return $this->json_response(404, 'User not found');
    }

    // Get all roles associated with this user
    $roles = $this->db->select('id, role_name')
                      ->from('employee_roles')
                      ->where('user_id', $user_id)
                      ->get()
                      ->result();

    // If no roles found for the user
    if (empty($roles)) {
        return $this->json_response(404, 'No roles found for the given user');
    }

    $result = [];

    foreach ($roles as $role) {
        // Fetch feature access entries for each role
        $access_entries = $this->db
            ->select('rfa.feature_id, af.feature_name, rfa.is_read, rfa.is_write, rfa.is_action, rfa.is_delete')
            ->from('role_feature_access as rfa')
            ->join('app_features as af', 'af.id = rfa.feature_id')
            ->where('rfa.role_id', $role->id)
            ->where('rfa.user_id', $user_id)
            ->get()
            ->result();

        // Prepare features data for each role
        $features = [];
        foreach ($access_entries as $entry) {
            $features[] = [
                'feature_id' => $entry->feature_id,
                'feature_name' => $entry->feature_name,
                'is_read' => $entry->is_read,
                'is_write' => $entry->is_write,
                'is_action' => $entry->is_action,
                'is_delete' => $entry->is_delete
            ];
        }

        // Add role and its features to the result array
        $result[] = [
            'role_id' => $role->id,
            'role_name' => $role->role_name,
            'features' => $features
        ];
    }

    // Return the structured response
    return $this->json_response(200, 'Data fetched successfully', $result);
}


    

}
