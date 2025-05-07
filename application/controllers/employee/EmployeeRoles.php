<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeRoles extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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
    
    
    public function store_role_feature_access() {
        // Get input data
        $input = $this->get_input_data();
        //$this->form_validation->set_data($input);
    
        // Validation rules
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('role_id', 'Role ID', 'required|integer');
        $this->form_validation->set_rules('feature_id', 'Feature ID', 'required|integer');
        $this->form_validation->set_rules('is_read', 'Read Permission', 'required|integer');
        $this->form_validation->set_rules('is_write', 'Write Permission', 'required|integer');
        $this->form_validation->set_rules('is_action', 'Action Permission', 'required|integer');
        $this->form_validation->set_rules('is_delete', 'Delete Permission', 'required|integer');
    
        // Check if validation fails
        if ($this->form_validation->run() === FALSE) {
            return $this->json_response(400, 'Validation failed', [
                'errors' => $this->form_validation->error_array()
            ]);
        }
    
        // Prepare data for insertion (no need for explicit casting)
        $data = [
            'role_id' => $input['role_id'],
            'feature_id' => $input['feature_id'],
            'is_read' => $input['is_read'], // Assuming they are already integers
            'is_write' => $input['is_write'], // Assuming they are already integers
            'is_action' => $input['is_action'], // Assuming they are already integers
            'is_delete' => $input['is_delete'] // Assuming they are already integers
        ];
    
        // Check if role and feature exist
        $role_exists = $this->db->where('id', $input['role_id'])->get('employee_roles')->row();
        $feature_exists = $this->db->where('id', $input['feature_id'])->get('app_features')->row();
    
        if (!$role_exists) {
            return $this->json_response(404, 'Role ID does not exist');
        }
    
        if (!$feature_exists) {
            return $this->json_response(404, 'Feature ID does not exist');
        }
    
        // Start database transaction
        $this->db->trans_start();
        $this->db->insert('role_feature_access', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
    
        // Check if the transaction was successful
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Database error: ' . $this->db->error()['message']);
            return $this->json_response(500, 'Failed to store role feature access');
        }
    
        // Return success response
        return $this->json_response(201, 'Role feature access stored successfully', [
            'id' => $insert_id
        ]);
    }
    
    
    
    

}
