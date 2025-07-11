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
        require_feature(3);
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Notification';
        $data['can_edit'] = $this->auth_model->get_permission(3);
        $data['main_content'] = $this->load->view('admin/notification', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }
    public function send_notification()
    {
        try {
            // Get all data from headers instead of POST
            $employee_id = $this->input->get_request_header('employee_id', TRUE);
            $description = $this->input->get_request_header('description', TRUE);
            $status = $this->input->get_request_header('status', TRUE);
            $user_id = $this->input->get_request_header('user_id', TRUE);
    
            // Basic validation (similar to file1 style)
            if(empty($employee_id) || empty($user_id) || empty($description)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Missing required data',
                        'required_fields' => [
                            'employee_id (got: '.$employee_id.')',
                            'user_id (got: '.$user_id.')',
                            'description (got: '.$description.')'
                        ]
                    ]));
            }
    
            // Validate numeric IDs
            if(!is_numeric($employee_id) || !is_numeric($user_id)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Invalid ID format',
                        'invalid_fields' => [
                            'employee_id (must be numeric, got: '.$employee_id.')',
                            'user_id (must be numeric, got: '.$user_id.')'
                        ]
                    ]));
            }
    
            // Validate status (must be between 0 and 9 now)
            $status = intval($status);
            if($status < 0 || $status > 9) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Invalid status value',
                        'details' => 'Status must be between 0 and 9 (got: '.$status.')'
                    ]));
            }
    
            // Validate status text matches status code
            $status_messages = [
                0 => 'Webcam permission denied by system, but the user is online',
                1 => 'Webcam permission denied by system and the user is offline',
                2 => 'Webcam is closed, but the user is online',
                3 => 'Webcam is closed and the user is offline',
                4 => 'Webcam is live and the user is online',
                5 => 'Webcam is live, but the user is offline',
                6 => 'User sign off',
                7 => 'User is inactive for a while',
                8 => 'User is active now'
            ];
    
            // Special case: Status 5 ("User is active now") is now removed as we have more specific webcam states
            // Status 6 from previous version is now status 9
    
            if (!isset($status_messages[$status]) || stripos($description, $status_messages[$status]) === false) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Status code and description mismatch',
                        'details' => [
                            'status_code' => $status,
                            'expected_description_contains' => $status_messages[$status],
                            'received_description' => $description
                        ]
                    ]));
            }
    
            // Get current datetime in user's timezone
            $current_datetime = get_user_datetime_only($user_id);
    
            // Prepare data
            $data = [
                'user_id'     => $user_id,
                'employee_id' => $employee_id,
                'description' => $description,
                'status'      => $status,
                'created_at'  => $current_datetime
            ];
    
            // Insert into DB
            $this->db->insert('notifications', $data);
    
            if($this->db->affected_rows() > 0) {
                $notification_id = $this->db->insert_id();
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(201)
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Notification sent successfully',
                        'notification_id' => $notification_id,
                        'data' => $data,
                        'user_timezone' => 'Based on user_id: '.$user_id
                    ]));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Failed to send notification',
                        'database_error' => $this->db->error()
                    ]));
            }
        } catch (Exception $e) {
            log_message('error', 'Notification Error: ' . $e->getMessage());
    
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred',
                    'error_details' => $e->getMessage()
                ]));
        }
    }

    // public function desktop_notifications()
    // {
    //     $employee_id = $this->input->get('employee_id');
    //     $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

    //     if (empty($user_id)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 'status' => 'error',
    //                 'message' => 'User ID is required from session.'
    //             ]));
    //     }

    //     $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.name as employee_name');
    //     $this->db->from('notifications n');
    //     $this->db->join('employees e', 'n.employee_id = e.id', 'left');
    //     $this->db->where('n.user_id', $user_id);

    //     if (!empty($employee_id)) {
    //         $this->db->where('n.employee_id', $employee_id);
    //     }

    //     $this->db->where_in('n.description', ['User is inactive for a while', 'User is active now', 'User sign off']);
    //     $this->db->order_by('n.created_at', 'DESC');
    //     $this->db->limit(1);

    //     $query = $this->db->get();
    //     $notifications = $query->result_array();

    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode([
    //             'status' => 'success',
    //             'data' => $notifications
    //         ]));
    // }
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

        if (!empty($employee_id)) {
            // If employee_id given, get latest notification for that employee only
            $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status,e.email, e.name as employee_name');
            $this->db->from('notifications n');
            $this->db->join('employees e', 'n.employee_id = e.id', 'left');
            $this->db->where('n.user_id', $user_id);
            $this->db->where('n.employee_id', $employee_id);
            $this->db->order_by('n.created_at', 'DESC');
            $this->db->limit(1);

            $query = $this->db->get();
            $notifications = $query->result_array();
        } else {
            // If no employee_id, get latest notification per employee

            // Subquery to get latest created_at per employee
            $subquery = $this->db->select('MAX(created_at) as latest_time, employee_id')
                ->from('notifications')
                ->where('user_id', $user_id)
                ->group_by('employee_id')
                ->get_compiled_select();

            // Main query joins on latest notification per employee
            $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status,e.email, e.name as employee_name');
            $this->db->from('notifications n');
            $this->db->join("($subquery) as latest", 'n.employee_id = latest.employee_id AND n.created_at = latest.latest_time', 'inner');
            $this->db->join('employees e', 'n.employee_id = e.id', 'left');
            $this->db->where('n.user_id', $user_id);

            // Sort by status (0 first), then by created_at desc
            $this->db->order_by('n.status', 'ASC');
            $this->db->order_by('n.created_at', 'ASC');

            $query = $this->db->get();
            $notifications = $query->result_array();
        }

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

        if (!empty($employee_id)) {
            // If employee_id given, get latest notification for that employee only
            $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status,e.email, e.name as employee_name');
            $this->db->from('notifications n');
            $this->db->join('employees e', 'n.employee_id = e.id', 'left');
            $this->db->where('n.user_id', $user_id);
            $this->db->where('n.employee_id', $employee_id);
            $this->db->order_by('n.created_at', 'DESC');
            $this->db->limit(1);

            $query = $this->db->get();
            $notifications = $query->result_array();
        } else {
            // If no employee_id, get latest notification per employee

            // Subquery to get latest created_at per employee
            $subquery = $this->db->select('MAX(created_at) as latest_time, employee_id')
                ->from('notifications')
                ->where('user_id', $user_id)
                ->group_by('employee_id')
                ->get_compiled_select();

            // Main query joins on latest notification per employee
            $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status,e.email, e.name as employee_name');
            $this->db->from('notifications n');
            $this->db->join("($subquery) as latest", 'n.employee_id = latest.employee_id AND n.created_at = latest.latest_time', 'inner');
            $this->db->join('employees e', 'n.employee_id = e.id', 'left');
            $this->db->where('n.user_id', $user_id);

            // Sort by status (0 first), then by created_at desc
            $this->db->order_by('n.status', 'ASC');
            $this->db->order_by('n.created_at', 'ASC');

            $query = $this->db->get();
            $notifications = $query->result_array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $notifications
            ]));
    }






    // public function get_notifications()
    // {
    //     $employee_id = 5;
    //     $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

    //     if (empty($user_id)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 'status' => 'error',
    //                 'message' => 'User ID is required from session.'
    //             ]));
    //     }

    //     $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.name as employee_name');
    //     $this->db->from('notifications n');
    //     $this->db->join('employees e', 'n.employee_id = e.id', 'left');
    //     $this->db->where('n.user_id', $user_id);

    //     if (!empty($employee_id)) {
    //         $this->db->where('n.employee_id', $employee_id);
    //     }

    //     $this->db->order_by('n.created_at', 'DESC');
    //     $this->db->limit(1);

    //     $query = $this->db->get();
    //     $notifications = $query->result_array();

    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode([
    //             'status' => 'success',
    //             'data' => $notifications
    //         ]));
    // }
  



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
    public function send_alert_mail()
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
        $subject = "Attention Required: Notification for $employeeName";

        // $logo = '<img width="100" src="' . base_url('uploads/thumbnail/2_thumb-100x100.png') . '" alt="Workroom" style="display:block; margin:0 auto;width: 150px;">';

        // $msg = $logo;

        // $msg .= '<br><br>';
        // $msg .= "<p><strong>To: $employeeName</strong></p>";
        // $msg .= '<hr style="border: 1px solid #eee; margin: 15px 0;">';
        $msg .= "<p>Hi $employeeName,</p>";
        $msg .= "<p>We have detected the following issue:</p>";
        $msg .= '<p style="font-weight: 700">"' . $message . '"</p>';
        $msg .= "<p>It has come to my attention that your desktop application has been <b> closed </b> or <b>logged out</b>. To ensure uninterrupted workflow and access to necessary tools, kindly <b> log in </b> again at your earliest convenience.</p>";
        $msg .= "<p>If you encounter any issues or require assistance, please don't hesitate to reach out to the IT support team.</p>";
        $msg .= "<p>Regards,<br>Admin</p>";

        // $msg = 'Hello '.$user['name'].'<br> We have reset your password, Please use this <b>'.$user['password'].'</b> code to login your account';
        $this->email_model->send_email($employeeEmail, $subject, $msg);
    }
}
