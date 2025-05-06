<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeRoles extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper('url'); // Load the URL helper
    }

    public function role_permission() {
        $data = array();
        $data['page_title'] = 'Create Roles & Permission';
        $data['main_content'] = $this->load->view('admin/employee/hrm/role_permission', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    /**
     * Creates a new role for a specific organization and department.
     */
    public function create_role() {
        // Use the common input retrieval
        $input = $this->get_input_data();

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
        $description = isset($input['description']) ? $input['description'] : null;

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
            log_message('error', 'Database error: ' . $this->db->error()['message']); // Log the error
            return $this->json_response(500, 'Database operation failed');
        }

        return $this->json_response(201, 'Role created successfully', [
            'role_id' => $role_id,
            'role_name' => $role_name
        ]);
    }


    /**
     * Creates a new permission for a specific organization and department.
     */
    public function create_permission() {
        // Use the common input retrieval
        $input = $this->get_input_data();

        $this->form_validation->set_rules('user_id', 'Organization ID', 'required|integer');
        $this->form_validation->set_rules('department_id', 'Department ID', 'required|integer');
        $this->form_validation->set_rules('permission_name', 'Permission Name', 'required|max_length[100]');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[500]');

        if ($this->form_validation->run() === FALSE) {
            return $this->json_response(400, 'Validation failed', ['errors' => $this->form_validation->error_array()]);
        }

        $user_id = $input['user_id'];
        $department_id = $input['department_id'];
        $permission_name = $input['permission_name'];
        $description = isset($input['description']) ? $input['description'] : null;

        $existing = $this->db
            ->where(['user_id' => $user_id, 'department_id' => $department_id, 'permission_name' => $permission_name])
            ->get('employee_permissions')
            ->row();

        if ($existing) {
            return $this->json_response(409, 'Permission already exists in this organization and department');
        }

        $data = [
            'user_id' => $user_id,
            'department_id' => $department_id,
            'permission_name' => $permission_name,
            'description' => $description
        ];

        $this->db->trans_start();
        $this->db->insert('employee_permissions', $data);
        $permission_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
             log_message('error', 'Database error: ' . $this->db->error()['message']); // Log the error
            return $this->json_response(500, 'Database operation failed');
        }

        return $this->json_response(201, 'Permission created successfully', [
            'permission_id' => $permission_id,
            'permission_name' => $data['permission_name']
        ]);
    }

    /**
     * Assigns a permission to a specific role.
     */
    public function assign_permission_to_role() {
        // Use the common input retrieval
        $input = $this->get_input_data();

        $this->form_validation->set_rules('role_id', 'Role ID', 'required|integer');
        $this->form_validation->set_rules('permission_id', 'Permission ID', 'required|integer');
        $this->form_validation->set_rules('is_granted', 'Is Granted', 'required|in_list[0,1]');

        if ($this->form_validation->run() === FALSE) {
            return $this->json_response(400, 'Validation failed', ['errors' => $this->form_validation->error_array()]);
        }

        $role_id = $input['role_id'];
        $permission_id = $input['permission_id'];
        $is_granted = $input['is_granted'];

        $role = $this->db->get_where('employee_roles', ['id' => $role_id])->row();
        $permission = $this->db->get_where('employee_permissions', ['id' => $permission_id])->row();

        if (!$role || !$permission) {
            return $this->json_response(404, 'Role or permission not found');
        }

        // Ensure that the role and permission belong to the same organization and department
        if ($role->user_id !== $permission->user_id || $role->department_id !== $permission->department_id) {
            return $this->json_response(400, 'Role and permission belong to different organizations or departments');
        }

        $existing = $this->db
            ->where(['role_id' => $role_id, 'permission_id' => $permission_id])
            ->get('employee_role_permission')
            ->row();

        $this->db->trans_start();

        if ($existing) {
            if ($existing->is_granted != $is_granted) {
                $this->db->where('id', $existing->id)
                    ->update('employee_role_permission', ['is_granted' => $is_granted]);
                $message = 'Permission assignment updated';
            } else {
                $message = 'Permission already assigned with same value';
            }
        } else {
            $this->db->insert('employee_role_permission', [
                'role_id' => $role_id,
                'permission_id' => $permission_id,
                'is_granted' => $is_granted
            ]);
            $message = 'Permission assigned to role';
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
             log_message('error', 'Database error: ' . $this->db->error()['message']); // Log the error
            return $this->json_response(500, 'Database operation failed');
        }

        return $this->json_response(200, $message, [
            'role_id' => $role_id,
            'permission_id' => $permission_id,
            'is_granted' => (bool)$is_granted
        ]);
    }

    /**
     * Gets all roles with their assigned permissions for a specific organization and department.
     */
    public function get_roles_with_permissions() {
        $user_id = $this->input->get('user_id');
        $department_id = $this->input->get('department_id');
        if (!$user_id || !$department_id) {
            return $this->json_response(400, 'user_id and department_id are required');
        }

        $query = $this->db
            ->select([
                'er.id as role_id',
                'er.role_name',
                'er.description as role_description',
                'ep.id as permission_id',
                'ep.permission_name',
                'ep.description as permission_description',
                'erp.is_granted'
            ])
            ->from('employee_roles er')
            ->join('employee_role_permission erp', 'er.id = erp.role_id', 'left')
            ->join('employee_permissions ep', 'erp.permission_id = ep.id', 'left')
            ->where('er.user_id', $user_id)
            ->where('er.department_id', $department_id)
            ->order_by('er.role_name, ep.permission_name')
            ->get();

        $roles = [];
        foreach ($query->result_array() as $row) {
            $role_id = $row['role_id'];
            if (!isset($roles[$role_id])) {
                $roles[$role_id] = [
                    'role_id' => $row['role_id'],
                    'role_name' => $row['role_name'],
                    'role_description' => $row['role_description'],
                    'permissions' => []
                ];
            }

            if ($row['permission_id']) {
                $roles[$role_id]['permissions'][] = [
                    'permission_id' => $row['permission_id'],
                    'permission_name' => $row['permission_name'],
                    'permission_description' => $row['permission_description'],
                    'is_granted' => (bool)$row['is_granted']
                ];
            }
        }

        return $this->json_response(200, 'Roles with permissions fetched for organization and department', array_values($roles));
    }

    /**
     * Common method to get input data regardless of content type
     * @return array
     */
   

}
