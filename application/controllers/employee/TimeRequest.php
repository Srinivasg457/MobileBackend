<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TimeRequest extends Home_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    // Show form + requests
    public function index()
    {
        $data['page_title'] = "Time Request";

        // Initialize requests in session if not already
 
        $data['userData'] = $this->get_timecards_by_employee();
        $data['main_content'] = $this->load->view('admin/employee/time_request', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    public function get_timecards_by_employee()
    {
        $user_id     = 4;
        $employee_id = 5;

        if ($employee_id && $user_id) {
            $this->db->where('employee_id', $employee_id);
            $this->db->order_by('manual_id', 'DESC'); // Change column if needed
            $query = $this->db->get('timecards_manual');

            // return an array directly
            return ($query->num_rows() > 0)
                ? $query->result_array()
                : [];
        } else {
            return ['error' => 'Employee ID not found in session'];
        }
    }

    // Handle form submit and add new request
    public function submit()
    {
        if ($this->input->post()) {
            $user_id         = $this->session->userdata('employee_org_id');
            $employee_id     = $this->session->userdata('employee_id');
            $data = array(
                'is_meeting'     => $this->input->post('type'),
                'timestamp_start' => $this->input->post('time_start'),
                'timestamp_end'   => $this->input->post('time_end'),
                'user_id'         => $user_id,
                'employee_id'     => $employee_id,
                'reason'          => $this->input->post('reason'),
                'approved'        => 0,
                'approved_by'     => NULL,
                'date_added'      => get_user_datetime_only($user_id) // Add the new column here
            );
        }
            // Append to session
            $this->db->insert('timecards_manual', $data);

            echo ($this->db->affected_rows() > 0)
                ? "Timecard created successfully!"
                : "Failed to create timecard.";          

        redirect('employee/TimeRequest');
    }

    public function create_timecard()
    {
        $user_id         = $this->session->userdata('employee_org_id');
        $employee_id     = $this->session->userdata('employee_id');
        $timestamp_start = $this->input->post('timestamp_start');
        $timestamp_end   = $this->input->post('timestamp_end');
        $reason          = $this->input->post('reason');
        // Get the current date for the new 'date_added' column
        $date_added      = date('Y-m-d'); // Format: YYYY-MM-DD

        if (!$user_id || !$employee_id || !$timestamp_start || !$timestamp_end || !$reason) {
            echo "All fields are required: employee_id, timestamp_start, timestamp_end, reason.";
            return;
        }

        $data = array(
            'timestamp_start' => $timestamp_start,
            'timestamp_end'   => $timestamp_end,
            'user_id'         => $user_id,
            'employee_id'     => $employee_id,
            'reason'          => $reason,
            'approved'        => 0,
            'approved_by'     => NULL,
            'date_added'      => get_user_datetime_only($user_id) // Add the new column here
        );

        $this->db->insert('timecards_manual', $data);

        echo ($this->db->affected_rows() > 0)
            ? "Timecard created successfully!"
            : "Failed to create timecard.";
    }
}
