<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_logs extends CI_Controller { // Change Your_controller to your actual controller name

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function get_time_logs() {
        // Get inputs from GET request (query parameters)
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->input->get('user_id');
        $date = $this->input->get('date');

        // If no date is provided, use today's date
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        // Validate inputs
        if (empty($employee_id) || empty($user_id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Missing employee_id or user_id'
                ]));
            return; // Important: Exit the function after sending the error response
        }

        // Check if the combination of user_id and employee_id exists in the time_logs table for the given date
        $this->db->select('log_id'); // Select a primary key to efficiently check existence
        $this->db->from('time_logs');
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date); // Filter by date

        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'No time logs found for the specified user_id, employee_id, and date.'
                ]));
            return; // Important: Exit the function
        }

        // Query the time_logs table
        $this->db->select('log_id, employee_id, user_id, log_date, start_time, end_time, total_active_time, total_idle_time, status, created_at, updated_at'); // Select the columns you need
        $this->db->from('time_logs');
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date);
        $query = $this->db->get();
        $result = $query->result_array();

        // Return response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data' => $result
            ]));
    }
}
?>
