<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('employee_model'); // You'll create this model
        $this->load->helper('security');
        $this->load->library('form_validation');
    }

    // Step 1: Handle invitation link
    public function accept_invitation() {
        $token = $this->input->get('token', true);
        $employee = $this->employee_model->get_by_token($token);

        if (!$employee) {
            show_404(); // Invalid token
        }

        $data['employee'] = $employee;
        $data['token'] = $token;
        $this->load->view('employee/register', $data); // Create this view
    }

    // Step 2: Complete registration (submit password form)
    public function complete_registration() {
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $token = $this->input->post('token', true);

        if ($this->form_validation->run() == false) {
            $employee = $this->employee_model->get_by_token($token);
            $data['employee'] = $employee;
            $data['token'] = $token;
            return $this->load->view('employee/register', $data);
        }

        $employee = $this->employee_model->get_by_token($token);
        if (!$employee) {
            show_404();
        }

        $password = $this->input->post('password', true);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $this->employee_model->update_password($employee->id, $hashed_password);

        // Redirect to employee dashboard or login
        redirect(base_url('employee/login?registered=1'));
    }
}
