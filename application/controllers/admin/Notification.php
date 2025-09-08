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
        $data['navbar'] = 'webcam';
        $data['can_edit'] = $this->auth_model->get_permission(3);
        $data['notifications'] = $this->web_notifications();
        $data['main_content'] = $this->load->view('admin/notification', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }

    public function desktop()
    {
        require_feature(3);
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Notification';
        $data['navbar'] = 'desktop';
        $data['can_edit'] = $this->auth_model->get_permission(3);
        $data['notifications'] = $this->desktop_notifications();
        $data['main_content'] = $this->load->view('admin/notification', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }

    // public function desktop_notifications()
    // {
    //     $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

    //     // Subquery: only get the latest notification per employee with status 6–8
    //     $subquery = $this->db->select('MAX(created_at) as latest_time, employee_id')
    //         ->from('notifications')
    //         ->where('user_id', $user_id)
    //         ->where_in('status', [6, 7, 8]) // ✅ Only consider valid statuses here
    //         ->group_by('employee_id')
    //         ->get_compiled_select();

    //     // Main query: join on filtered latest notifications
    //     $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.email, e.name as employee_name');
    //     $this->db->from('notifications n');
    //     $this->db->join("($subquery) as latest", 'n.employee_id = latest.employee_id AND n.created_at = latest.latest_time', 'inner');
    //     $this->db->join('employees e', 'n.employee_id = e.id', 'left');
    //     $this->db->where('n.user_id', $user_id);
    //     $this->db->where_in('n.status', [6, 7, 8]); // ✅ Matches subquery filter
    //     $this->db->where('DATE(n.created_at)', date('Y-m-d'));


    //     // Order: status first, then time
    //     $this->db->order_by('n.status', 'ASC');
    //     $this->db->order_by('n.created_at', 'ASC');

    //     $query = $this->db->get();
    //     return $query->result_array();
    // }
    public function desktop_notifications()
    {
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

        // Step 1: Subquery - latest desktop notifications (status 6–8)
        $subquery = $this->db->select('MAX(created_at) as latest_time, employee_id,MAX(notification_id) as latest_id')
            ->from('notifications')
            ->where('user_id', $user_id)
            ->where_in('status', [6, 7, 8])
            ->group_by('employee_id')
            ->get_compiled_select();

        // Step 2: Fetch existing notifications
        $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.email, e.name as employee_name');
        $this->db->from('notifications n');
        $this->db->join("($subquery) as latest", 'n.employee_id = latest.employee_id AND n.created_at = latest.latest_time AND n.notification_id = latest.latest_id', 'inner');
        $this->db->join('employees e', 'n.employee_id = e.id', 'left');
        $this->db->where('n.user_id', $user_id);
        $this->db->where_in('n.status', [6, 7, 8]);
        $this->db->order_by('n.created_at', 'ASC');

        $query = $this->db->get();
        $notifications = $query->result_array();

        // Step 3: Get employees without desktop notifications
        $notified_employee_ids = array_column($notifications, 'employee_id');

        $this->db->select('id, name, email, created_at');
        $this->db->from('employees');
        $this->db->where('user_id', $user_id);
        if (!empty($notified_employee_ids)) {
            $this->db->where_not_in('id', $notified_employee_ids);
        }
        $remaining_employees = $this->db->get()->result_array();

        // Step 4: Add fallback rows
        foreach ($remaining_employees as $emp) {
            $notifications[] = [
                'notification_id' => null,
                'user_id'         => $user_id,
                'employee_id'     => $emp['id'],
                'description'     => 'User not yet logged into Workroom Application',
                'created_at'      => $emp['created_at'],
                'status'          => 9,
                'email'           => $emp['email'],
                'employee_name'   => $emp['name'],
            ];
        }
        // 6. Sort all notifications by created_at ascending
        usort($notifications, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });
        return $notifications;
    }




    // public function web_notifications()
    // {
    //     $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

    //     // Subquery: only consider notifications with status 0–5
    //     $subquery = $this->db->select('MAX(created_at) as latest_time, employee_id')
    //         ->from('notifications')
    //         ->where('user_id', $user_id)
    //         ->where_in('status', [0, 1, 2, 3, 4, 5]) // <-- status condition here
    //         ->group_by('employee_id')
    //         ->get_compiled_select();

    //     // Main query: join on subquery
    //     $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.email, e.name as employee_name');
    //     $this->db->from('notifications n');
    //     $this->db->join("($subquery) as latest", 'n.employee_id = latest.employee_id AND n.created_at = latest.latest_time', 'inner');
    //     $this->db->join('employees e', 'n.employee_id = e.id', 'left');
    //     $this->db->where('n.user_id', $user_id);
    //     $this->db->where_in('n.status', [0, 1, 2, 3, 4, 5]);
    //     $this->db->where('DATE(n.created_at)', date('Y-m-d'));


    //     // Order by status, then time
    //     $this->db->order_by('n.status', 'ASC');
    //     $this->db->order_by('n.created_at', 'ASC');

    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

    public function web_notifications()
    {
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

        // 1. Get subquery for latest notification per employee
        $subquery = $this->db->select('MAX(created_at) as latest_time, employee_id, MAX(notification_id) as latest_id')
            ->from('notifications')
            ->where('user_id', $user_id)
            ->where_in('status', [0, 1, 2, 3, 4, 5, 6])
            ->group_by('employee_id')
            ->get_compiled_select();

        // 2. Main query: fetch existing notifications
        $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.email, e.name as employee_name');
        $this->db->from('notifications n');
        $this->db->join("($subquery) as latest", 'n.employee_id = latest.employee_id AND n.created_at = latest.latest_time AND n.notification_id = latest.latest_id', 'inner');
        $this->db->join('employees e', 'n.employee_id = e.id', 'left');
        $this->db->where('n.user_id', $user_id);
        $this->db->where_in('n.status', [0, 1, 2, 3, 4, 5, 6]);
        $this->db->order_by('n.created_at', 'ASC');

        $query = $this->db->get();
        $notifications = $query->result_array();

        // 3. Track employee_ids with notifications
        $notified_employee_ids = array_column($notifications, 'employee_id');

        // 4. Get all employees under this user/org
        $this->db->select('id, name, email, created_at');
        $this->db->from('employees');
        $this->db->where('user_id', $user_id);
        if (!empty($notified_employee_ids)) {
            $this->db->where_not_in('id', $notified_employee_ids);
        }
        $remaining_employees = $this->db->get()->result_array();

        // 5. Add fallback notification rows for employees without notifications
        foreach ($remaining_employees as $emp) {
            $notifications[] = [
                'notification_id' => null,
                'user_id'         => $user_id,
                'employee_id'     => $emp['id'],
                'description'     => 'User not yet logged into Workroom Application',
                'created_at'      => $emp['created_at'],
                'status'          => 9,
                'email'           => $emp['email'],
                'employee_name'   => $emp['name'],
            ];
        }
        // 6. Sort all notifications by created_at ascending
        usort($notifications, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        return $notifications;
    }




    public function send_notification()
    {
        try {
            // Get all data from headers instead of POST
            $employee_id = $this->input->get_request_header('employee_id', TRUE);
            $description = $this->input->get_request_header('description', TRUE);
            $status = $this->input->get_request_header('status', TRUE);
            $user_id = $this->input->get_request_header('user_id', TRUE)??$this->session->userdata('id')??$this->session->userdata('user_id')??$this->input->post('user_id', TRUE);
    
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
