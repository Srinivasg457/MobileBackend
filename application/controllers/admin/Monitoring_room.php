<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_room extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load database library
        $this->load->database();
    }

    public function index()
    {
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Live Monitoring';
        $data['main_content'] = $this->load->view('admin/monitoring_room', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
        if(is_plan_basic()){
            redirect('/admin/subscription'); 
        }
    }
    public function list_employees_by_user()
{
    // Get user_id from GET or POST (based on your request method)
    $user_id = $this->session->userdata('id');

    // 1. Validate user ID
    if (empty($user_id) || !is_numeric($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Valid user ID required'
            ]));
    }

    // 2. Get employees from the employees table matching the provided user_id
    $employees = $this->db
        ->select('id, name, email')
        ->where('user_id', $user_id)
        ->get('employees')
        ->result_array();

    // 3. Return response
    if (!empty($employees)) {
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
    public function list_employees_by_name()
    {
        $user_id = $this->session->userdata('id');

        // 1. Validate user ID
        if (empty($user_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid user ID required'
                ]));
        }

        // 2. Get optional name filter from query
        $name = $this->input->get('name');

        // 3. Build query
        $this->db->select('id, name, email')
            ->from('employees')
            ->where('user_id', $user_id);

        if (!empty($name)) {
            $this->db->like('LOWER(name)', strtolower($name));
        }

        $this->db->order_by('name', 'ASC');
        $employees = $this->db->get()->result_array();

        // 4. Return response
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'user_id' => (int)$user_id,
                'employees' => $employees
            ]));
    }
    public function list_employees_ordered()
{
    $user_id = $this->session->userdata('id') ?? $this->input->get('user_id');
    // 1. Validate user ID
    if (empty($user_id) || !is_numeric($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Valid user ID required'
            ]));
    }

    // 2. Get optional filters from query
    $name = $this->input->get('name');
    $order = strtolower($this->input->get('order'));
    if ($order !== 'asc' && $order !== 'desc') {
        $order = 'asc'; // default ordering
    }

    // 3. Build query
    $this->db->select('id, name, email')
        ->from('employees')
        ->where('user_id', $user_id);

    if (!empty($name)) {
        $this->db->like('LOWER(name)', strtolower($name));
    }

    $this->db->order_by('name', $order);
    $employees = $this->db->get()->result_array();

    // 4. Return response
    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'status' => 'success',
            'user_id' => (int)$user_id,
            'order' => $order,
            'employees' => $employees
        ]));
}


public function get_active_hours_by_latest_date() {
    // 1. Get user_id and order from session or GET parameters
    $user_id = $this->session->userdata('employee_org_id') 
        ?? $this->session->userdata('id') 
        ?? $this->input->get('user_id');
    $order = strtolower($this->input->get('order'));

    // 2. Validate user_id
    if (empty($user_id) || !is_numeric($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Valid user_id is required'
            ]));
    }

    // 3. Default order
    if ($order !== 'asc' && $order !== 'desc') {
        $order = 'desc';
    }

    // 4. Get latest log_date for the user
    $this->db->select_max('DATE(log_date)', 'latest_date');
    $this->db->where('user_id', $user_id);
    $latest_date_result = $this->db->get('time_logs')->row_array();
    $latest_date = $latest_date_result['latest_date'] ?? null;

    if (!$latest_date) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'No time logs found for the user.'
            ]));
    }

    // 5. Fetch all employees under the user/org and LEFT JOIN their logs for the latest date
    $this->db->select('e.id AS employee_id, e.name, e.email, COALESCE(t.total_active_time, "00:00:00") AS total_active_time');
    $this->db->from('employees e');
    $this->db->where('e.user_id', $user_id);
    $this->db->join('time_logs t', 't.employee_id = e.id AND DATE(t.log_date) = "'.$latest_date.'"', 'left');
    $this->db->order_by('total_active_time', $order);

    $active_hours = $this->db->get()->result_array();

    // 6. Return response
    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'status' => true,
            'user_id' => (int)$user_id,
            'latest_date' => $latest_date,
            'order' => $order,
            'active_hours' => $active_hours
        ]));
}

public function get_inactive_hours_by_latest_date() {
    // 1. Get user_id and order from session or GET parameters
    $user_id = $this->session->userdata('employee_org_id') 
        ?? $this->session->userdata('id') 
        ?? $this->input->get('user_id');
    $order = strtolower($this->input->get('order'));

    // 2. Validate user_id
    if (empty($user_id) || !is_numeric($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Valid user_id is required'
            ]));
    }

    // 3. Default order
    if ($order !== 'asc' && $order !== 'desc') {
        $order = 'desc';
    }

    // 4. Get latest log_date for the user
    $this->db->select_max('DATE(log_date)', 'latest_date');
    $this->db->where('user_id', $user_id);
    $latest_date_result = $this->db->get('time_logs')->row_array();
    $latest_date = $latest_date_result['latest_date'] ?? null;

    if (!$latest_date) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'No time logs found for the user.'
            ]));
    }

    // 5. Fetch all employees under the user/org and LEFT JOIN their logs for the latest date
    $this->db->select('e.id AS employee_id, e.name, e.email, COALESCE(t.total_idle_time, "00:00:00") AS total_idle_time');
    $this->db->from('employees e');
    $this->db->where('e.user_id', $user_id);
    $this->db->join('time_logs t', 't.employee_id = e.id AND DATE(t.log_date) = "'.$latest_date.'"', 'left');
    $this->db->order_by('total_idle_time', $order);

    $inactive_hours = $this->db->get()->result_array();

    // 6. Return response
    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'status' => true,
            'user_id' => (int)$user_id,
            'latest_date' => $latest_date,
            'order' => $order,
            'inactive_hours' => $inactive_hours
        ]));
}


}
