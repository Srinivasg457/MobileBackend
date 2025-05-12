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
    public function get_roles_by_user()
    {
        $user_id = $this->session->userdata('id');

        if (empty($user_id) || !is_numeric($user_id)) {
            return $this->json_response(400, 'Invalid or missing user ID');
        }

        $this->db->select('id, role_name, department_id'); // fixed comma issue
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
public function get_user_roles()
{
    $user_id = $this->session->userdata('user_id');
    // Check if user ID is valid
    if (empty($user_id) || !is_numeric($user_id)) {
        echo json_encode(['status' => 400, 'message' => 'Invalid or missing user ID']);
        return;
    }

    // Fetch roles for the user from the database
    $this->db->select('id, role_name');
    $this->db->where('user_id', $user_id);
    $this->db->order_by('role_name', 'asc');
    $query = $this->db->get('employee_roles');

    // Handle any database errors
    if ($query === FALSE) {
        echo json_encode(['status' => 500, 'message' => 'Database query failed']);
        return;
    }

    $roles = $query->result_array();

    // If no roles found, return a JSON response
    if (empty($roles)) {
        echo json_encode(['status' => 404, 'message' => 'No roles found for this user']);
        return;
    }

    // Return roles as a JSON response
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
    
            // Validate role_id and user_id
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
    
            // Validate existence of role and user
            if (!$this->db->where('id', $role_id)->get('employee_roles')->row()) {
                return $this->json_response(404, 'Role does not exist. Please verify the Role ID.');
            }
    
            if (!$this->db->where('id', $user_id)->get('users')->row()) {
                return $this->json_response(404, 'User does not exist. Please verify the User ID.');
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
                    'is_delete' => $feature['is_delete']
                ];
    
                $existing = $this->db->get_where('role_feature_access', [
                    'role_id' => $role_id,
                    'user_id' => $user_id,
                    'feature_id' => $feature_id
                ])->row();
    
                if ($existing) {
                    $this->db->where('id', $existing->id)->update('role_feature_access', $data);
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

    // Get all roles associated with this user (including department_id)
    $roles = $this->db->select('id, role_name, department_id')
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
            'department_id' => $role->department_id,
            'features' => $features
        ];
    }

    // Return the structured response
    return $this->json_response(200, 'Data fetched successfully', $result);
}
public function get_feature_access_by_user_and_role()
    {
        $user_id = 3;
        $role_id = 45;
 
        // Validate input
        if (!$user_id || !$role_id) {
            return $this->json_response(400, 'Missing user_id or role_id');
        }
 
        // Check if the user exists
        $user_exists = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user_exists) {
            return $this->json_response(404, 'User not found');
        }
 
        // Check if the role exists for this user
        $role = $this->db->where('id', $role_id)
            ->where('user_id', $user_id)
            ->get('employee_roles')
            ->row();
        if (!$role) {
            return $this->json_response(404, 'Role not found for the given user');
        }
 
        // Get feature access data
        $access_entries = $this->db
            ->select('rfa.feature_id, af.feature_name, rfa.is_read, rfa.is_write, rfa.is_action, rfa.is_delete')
            ->from('role_feature_access as rfa')
            ->join('app_features as af', 'af.id = rfa.feature_id')
            ->where('rfa.role_id', $role_id)
            ->where('rfa.user_id', $user_id)
            ->get()
            ->result();
 
        if (empty($access_entries)) {
            return $this->json_response(404, 'No feature access entries found');
        }
 
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
 
        $response = [
            'role_id' => $role->id,
            'role_name' => $role->role_name,
            'department_id' => $role->department_id,
            'features' => $features
        ];
 
        return $this->json_response(200, 'Feature access data fetched successfully', $response);
    }

public function delete_role()
{
    header('Content-Type: application/json');

    // Get parameters from POST
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

    // Get the role to delete
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

    // Check if role is assigned to any employee (if needed)
    $assigned = $this->db->where('role_id', $role->id)->limit(1)->get('employees')->row();
    if ($assigned) {
        http_response_code(409);
        echo json_encode([
            'status' => false,
            'message' => 'Cannot delete: Role is assigned to one or more employees.'
        ]);
        return;
    }

    // Proceed to delete
    if ($this->db->delete('employee_roles', ['id' => $role->id])) {
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
