<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization_settings extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load database library
        $this->load->database();
    }

    public function index(): void
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Organization settings';
        $data["settings"] = $this->PreLoading_get_org_settings();
        $data['main_content'] = $this->load->view('admin/organization_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function Organization_settings_edit()
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data["settings"] = $this->PreLoading_get_org_settings();
        $data['main_content'] = $this->load->view('admin/organization_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function org_exception_settings(): void
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Ex Organization settings';
        $data['main_content'] = $this->load->view('admin/org_exception_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    // Method to insert or update org settings for a user
    public function save_org_settings()
    {
        date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

        $user_id = $this->session->userdata('id');
        $now = date('Y-m-d H:i:s');
    
        $data = [
            'user_id'                  => $user_id,
            'screenshot_flag'          => $this->input->post('screenshot_flag', TRUE),
            'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
            'webcam_flag'              => $this->input->post('webcam_flag', TRUE),
            'webcam_time_interval'     => $this->input->post('webcam_time_interval', TRUE),
            'mouse_move_flag'          => $this->input->post('mouse_move_flag', TRUE),
            'mouse_move_threshold'     => $this->input->post('mouse_move_threshold', TRUE),
            'key_stroke_flag'          => $this->input->post('key_stroke_flag', TRUE),
            'key_stroke_threshold'     => $this->input->post('key_stroke_threshold', TRUE),
            'idle_time_flag'           => $this->input->post('idle_time_flag', TRUE),
            'timecards_time_interval'  => 5,
            'updated_at'               => $now
        ];
    
        // Check if settings exist for this user
        $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);
    
        if ($query->num_rows() > 0) {
            // Update existing org settings
            $this->db->where('user_id', $user_id);
            $this->db->update('org_settings', $data);
        } else {
            // Include created_at for insert
            $data['created_at'] = $now;
            $this->db->insert('org_settings', $data);
        }
    
        // Check for errors
        if ($this->db->affected_rows() > 0) {
            echo "Settings saved successfully!";
        } else {
            echo "No changes in the saved settings.";
        }
    }
    

    // // Method to insert or update organization exception settings for a specific employee
    // public function save_org_exception_settings($employee_id)
    // {

    //     $user_id = $this->session->userdata('id');
    //     // Get data from POST request (replace with actual form data)
    //     $data = [
    //         'user_id'               => $user_id,
    //         'employee_id'           => $employee_id,
    //         'screenshot_flag'       => $this->input->post('screenshot_flag', TRUE),
    //         'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
    //         'webcam_flag'           => $this->input->post('webcam_flag', TRUE),
    //         'webcam_time_interval'  => $this->input->post('webcam_time_interval', TRUE),
    //         'mouse_move_flag'       => $this->input->post('mouse_move_flag', TRUE),
    //         'mouse_move_threshold'  => $this->input->post('mouse_move_threshold', TRUE),
    //         'key_stroke_flag'       => $this->input->post('key_stroke_flag', TRUE),
    //         'key_stroke_threshold'  => $this->input->post('key_stroke_threshold', TRUE),
    //         'idle_time_flag'        => $this->input->post('idle_time_flag', TRUE),
    //         'timecards_time_interval' => 1
    //     ];

    //     // Check if exception settings exist for this user and employee
    //     $query = $this->db->get_where('organization_exception_setting', [
    //         'user_id' => $user_id,
    //         'employee_id' => $employee_id
    //     ]);

    //     if ($query->num_rows() > 0) {
    //         //   ✅ Update settings_status in employees table to 2, based on user_id and employee_id
    //         $this->db->where('id', $employee_id);
    //         $this->db->where('user_id', $user_id);
    //         $this->db->update('employees', ['settings_status' => 2]);
    //         // Update existing exception settings
    //         $this->db->where('user_id', $user_id);
    //         $this->db->where('employee_id', $employee_id);
    //         $this->db->update('organization_exception_setting', $data);
    //     } else {
    //         // Insert new exception settings
    //         $this->db->insert('organization_exception_setting', $data);
    //     }
              
    //     // Check for errors
    //     if ($this->db->affected_rows() > 0) {
    //         echo "Employee settings saved successfully!";
    //     } else {
    //         echo "No changes in the saved employee settings.";
    //     }
    // }

    public function save_org_exception_settings($employee_id)
    {
        date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST
        $user_id = $this->session->userdata('id');
        $self_login = $this->input->post('self_login') ? 1 : 0;
        $now = date('Y-m-d H:i:s');
    
        $data = [
            'user_id'                  => $user_id,
            'employee_id'              => $employee_id,
            'screenshot_flag'          => $this->input->post('screenshot_flag', TRUE) ? 1 : 0,
            'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
            'webcam_flag'              => $this->input->post('webcam_flag', TRUE) ? 1 : 0,
            'webcam_time_interval'     => $this->input->post('webcam_time_interval', TRUE),
            'mouse_move_flag'          => $this->input->post('mouse_move_flag', TRUE) ? 1 : 0,
            'mouse_move_threshold'     => $this->input->post('mouse_move_threshold', TRUE),
            'key_stroke_flag'          => $this->input->post('key_stroke_flag', TRUE) ? 1 : 0,
            'key_stroke_threshold'     => $this->input->post('key_stroke_threshold', TRUE),
            'idle_time_flag'           => $this->input->post('idle_time_flag', TRUE) ? 1 : 0,
            'timecards_time_interval'  => 5,
            'updated_at'               => $now
        ];
    
        $employee_data = [
            'settings_status' => 2,
            'self_login' => $self_login
        ];
    
        // Check if exception settings exist
        $query = $this->db->get_where('organization_exception_setting', [
            'user_id' => $user_id,
            'employee_id' => $employee_id
        ]);
    
        if ($query->num_rows() > 0) {
            // Update
            $this->db->where('employee_id', $employee_id);
            $this->db->where('user_id', $user_id);
            $this->db->update('organization_exception_setting', $data);
        } else {
            // Insert
            $data['created_at'] = $now;
            $this->db->insert('organization_exception_setting', $data);
        }
    
        // Update employee record
        $this->db->where('id', $employee_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('employees', $employee_data);
    
        if ($this->db->affected_rows() > 0) {
            echo "Employee settings saved successfully!";
        } else {
            echo "No changes in the saved employee settings.";
        }
    }
    
    


   
    public function get_org_settings()
{
    $user_id = $this->input->get('id');

    if (!$user_id) {
        echo json_encode(['error' => 'Missing user_id parameter.']);
        return;
    }

    $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

    if ($query->num_rows() > 0) {
        echo json_encode($query->row_array());
    } else {
        echo json_encode(['error' => 'No settings found for this user.']);
    }
}
    public function PreLoading_get_org_settings()
    {
        $user_id = $this->session->userdata('id');

        if (!$user_id) {
            return ['error' => 'Missing user_id parameter.'];
        }

        $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return ['error' => 'No settings found for this user.'];
        }
    }


    public function get_org_exception_settings($employee_id)
{
        $user_id = $this->session->userdata('id');
    // $employee_id = $this->input->get('employee_id');

    if (!$user_id || !$employee_id) {
        echo json_encode(['error' => 'Missing user_id or employee_id parameter.']);
        return;
    }

    $query = $this->db->get_where('organization_exception_setting', [
        'user_id' => $user_id,
        'employee_id' => $employee_id
    ]);

    if ($query->num_rows() > 0) {
        echo json_encode($query->row_array());
    } else {
        echo json_encode(['error' => 'No exception settings found for this user and employee.']);
    }
}



public function get_organization_settings()
{
    $user_id = $this->input->get('user_id')?? $this->session->userdata('user_id');
    
    $status = $this->input->get('status'); // Fetch status from the request

    if (!$user_id || !$status) {
        echo json_encode(['error' => 'Missing user_id or status parameter.']);
        return;
    }

    // Determine the table to query based on status
    if ($status == 1) {
        $table = 'org_settings';
    } else if ($status == 2) {
        $table = 'organization_exception_setting';
    } else {
        echo json_encode(['error' => 'Invalid status value.']);
        return;
    }

    // Execute query
    $query = $this->db->get_where($table, ['user_id' => $user_id]);

    if ($query->num_rows() > 0) {
        echo json_encode($query->row_array());
    } else {
        echo json_encode(['error' => 'No settings found for this user.']);
    }
}

}
?>
