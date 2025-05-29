<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load database and form helper
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
    }

 

    public function send_notification()
    {
        $employee_id = $this->input->post('employee_id', true);
        $description = $this->input->post('description', true);
        $user_id = $this->session->userdata('user_id'); // user who is sending notification
    
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
    
        // Insert into notifications table
        $data = [
            'user_id'     => $user_id,
            'employee_id' => $employee_id,
            'description' => $description,
            'created_at'  => date('Y-m-d H:i:s')
        ];
    
        $this->db->insert('notifications', $data);
    
        if ($this->db->affected_rows() > 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'user_id'     => $user_id,
                    'employee_id' => $employee_id,
                    'description' => $description,
                    'created_at'  => date('Y-m-d H:i:s'),
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
    }
    

    // Optional: View all notifications for an employee
    public function view_by_employee($employee_id) {
        $query = $this->db->get_where('notifications', ['employee_id' => $employee_id]);
        echo json_encode($query->result());
    }
}
