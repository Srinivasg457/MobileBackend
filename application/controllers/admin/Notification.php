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
    // Set the default timezone to Indian Standard Time (UTC+5:30)
    date_default_timezone_set('Asia/Kolkata');

    // Get user_id and employee_id from session
    $employee_id = $this->session->userdata('employee_id') ?? $this->input->get('employee_id');
    $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

    // Validate both user_id and employee_id
    if (empty($user_id) || empty($employee_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'User ID and Employee ID are required from session.'
            ]));
    }

    // Fetch notifications from database with employee name using JOIN
    // Where description is either "User is inactive for a while" or "User is active now"
    $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.name as employee_name');
    $this->db->from('notifications n');
    $this->db->join('employees e', 'n.employee_id = e.id', 'left');
    $this->db->where('n.user_id', $user_id);
    $this->db->where('n.employee_id', $employee_id);
    $this->db->where_in('n.description', ['User is inactive for a while', 'User is active now','User sign off']);
    $this->db->order_by('n.created_at', 'DESC');
    $this->db->limit(1);  // This will return only the latest record

    $query = $this->db->get();
    $notifications = $query->result_array();

    // Convert MySQL timestamps to Indian time (if needed)
    foreach ($notifications as &$notification) {
        if (isset($notification['created_at'])) {
            $datetime = new DateTime($notification['created_at'], new DateTimeZone('UTC'));
            $datetime->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $notification['created_at'] = $datetime->format('Y-m-d H:i:s');
        }
    }

    // Return response
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
    // Set the default timezone to Indian Standard Time (UTC+5:30)
    date_default_timezone_set('Asia/Kolkata');

    // Get user_id and employee_id from session
    $employee_id = $this->session->userdata('employee_id') ?? $this->input->get('employee_id');
    $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

    // Validate both user_id and employee_id
    if (empty($user_id) || empty($employee_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'User ID and Employee ID are required from session.'
            ]));
    }

    // Fetch notifications from database with employee name using JOIN
    $this->db->select('n.notification_id, n.user_id, n.employee_id, n.description, n.created_at, n.status, e.name as employee_name');
    $this->db->from('notifications n');
    $this->db->join('employees e', 'n.employee_id = e.id', 'left');
    $this->db->where('n.user_id', $user_id);
    $this->db->where('n.employee_id', $employee_id);
    $this->db->where('n.description !=', 'User is inactive for a while'); // Exclude inactive notifications
    $this->db->order_by('n.created_at', 'DESC');
    $this->db->limit(1);  // This will return only the latest record

    $query = $this->db->get();
    $notifications = $query->result_array();

    // Convert MySQL timestamps to Indian time (if needed)
    foreach ($notifications as &$notification) {
        if (isset($notification['created_at'])) {
            $datetime = new DateTime($notification['created_at'], new DateTimeZone('UTC'));
            $datetime->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $notification['created_at'] = $datetime->format('Y-m-d H:i:s');
        }
    }

    // Return response
    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'status' => 'success',
            'data' => $notifications
        ]));
} // Optional: View all notifications for an employee
    public function view_by_employee($employee_id) {
        $query = $this->db->get_where('notifications', ['employee_id' => $employee_id]);
        echo json_encode($query->result());
    }
}
