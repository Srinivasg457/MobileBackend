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
        $data['page_title'] = 'Live Monitoring';
        $data['main_content'] = $this->load->view('admin/monitoring_room', $data, TRUE);
        $this->load->view('admin/index', $data);
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

}
