<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends Home_Controller {

    public function __construct() {
        parent::__construct();
        // Load database and form helper
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
    }

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Notification';
        $data['main_content'] = $this->load->view('admin/notification', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function send_notification()
    {
        try {
            // Load input and session helpers
            $this->load->helper('security');
    
            // Fetch inputs securely
            $employee_id = $this->input->post('employee_id', true);
            $description = $this->input->post('description', true);
            $status = $this->input->post('status', true); // New status field (1 or 0)
    
            // Get user_id from session or POST (for Postman/local testing)
            $user_id = $this->session->userdata('user_id');
            if (empty($user_id)) {
                $user_id = $this->input->post('user_id', true); // fallback
            }
    
            // Validate inputs
            if (empty($user_id) || empty($employee_id) || empty($description)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'User ID, Employee ID, and Description are required.'
                    ]));
            }
    
            // Validate status (must be 0 or 1)
            $status = ($status === '1' || $status === 1) ? 1 : 0;
    
            // Set timezone to Indian Standard Time
            date_default_timezone_set('Asia/Kolkata');
            $created_at = date('Y-m-d H:i:s');
    
            // Prepare data
            $data = [
                'user_id'     => $user_id,
                'employee_id' => $employee_id,
                'description' => $description,
                'status'      => $status,
                'created_at'  => $created_at
            ];
    
            // Insert into DB
            $this->db->insert('notifications', $data);
    
            if ($this->db->affected_rows() > 0) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'status' => 'success',
                        'user_id' => $user_id,
                        'employee_id' => $employee_id,
                        'description' => $description,
                        'notification_status' => $status,
                        'created_at' => $created_at,
                        'timezone' => 'IST (Asia/Kolkata)', // Indicate the timezone
                        'message' => 'Notification sent successfully.'
                    ]));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Failed to send notification.'
                    ]));
            }
        } catch (Exception $e) {
            log_message('error', 'Notification Error: ' . $e->getMessage());
    
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred.'
                ]));
        }
    }

    public function desktop_notifications()
    {
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
    
        if (empty($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'User ID is required from session.'
                ]));
        }
    
        $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.name as employee_name');
        $this->db->from('notifications n');
        $this->db->join('employees e', 'n.employee_id = e.id', 'left');
        $this->db->where('n.user_id', $user_id);
        
        if (!empty($employee_id)) {
            $this->db->where('n.employee_id', $employee_id);
        }
        
        $this->db->where_in('n.description', ['User is inactive for a while', 'User is active now', 'User sign off']);
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit(1);
    
        $query = $this->db->get();
        $notifications = $query->result_array();
    
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $notifications
            ]));
    }
    
    public function get_notifications()
    {
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
    
        if (empty($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'User ID is required from session.'
                ]));
        }
    
        $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.name as employee_name');
        $this->db->from('notifications n');
        $this->db->join('employees e', 'n.employee_id = e.id', 'left');
        $this->db->where('n.user_id', $user_id);
        
        if (!empty($employee_id)) {
            $this->db->where('n.employee_id', $employee_id);
        }
        
        $this->db->order_by('n.created_at', 'DESC');
        $this->db->limit(1);
    
        $query = $this->db->get();
        $notifications = $query->result_array();
    
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $notifications
            ]));
    }
    public function list_employees_by_user()
    {
        $user_id = 3; // Or get from session: $this->session->userdata('id');
    
        if (empty($user_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid user ID required'
                ]));
        }
    
        $employees = $this->db
            ->select('id, name, email')
            ->where('user_id', $user_id)
            ->get('employees')
            ->result_array();
    
        if ($employees) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'user_id' => (int)$user_id,
                    'employees' => $employees,
                ]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'No employees found for the given user ID'
                ]));
        }
    }
    //send reset code to user email
    public function send__alter_mail()
    {
        // Get POST data
        $employeeId = $this->input->post('employee_id');
        $employeeName = $this->input->post('employee_name');
        $employeeEmail = $this->input->post('employee_email');
        $message = $this->input->post('message');

        // Basic validation
        if (empty($employeeEmail) || empty($message)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Missing required fields.'
                ]));
        }
        // Compose email
        $subject = "Attention Required: Notification for $employeeName";  
        $logo = '<img width="100" src="' . base_url('uploads/thumbnail/2_thumb-100x100.png') . '" alt="Workroom" style="display:block; margin:0 auto;width: 150px;">';
        $msg = $logo;
        $msg .= '<br><br>';
        $msg .= "Hi $employeeName,\n\nWe have detected the following issue:\n\n\"$message\"\n\nPlease take necessary action.\n\nRegards,\nAdmin";

        // $msg = 'Hello '.$user['name'].'<br> We have reset your password, Please use this <b>'.$user['password'].'</b> code to login your account';
        $this->email_model->send_email($employeeEmail, $subject, $msg);
    }
}
