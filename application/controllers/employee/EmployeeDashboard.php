<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        Session check for logged-in employee
        if (!$this->session->userdata('employee_logged_in')) {
            redirect('login'); // Redirect to the login page if not logged in as an employee
        }
        $this->load->model('employee_model'); // Create a new model for employee-specific data
    }

    public function index() {
        $employee_id = $this->session->userdata('employee_id');
        $data['title'] = 'Employee Dashboard';
        $data['employee_name'] = $this->session->userdata('employee_name');
        $data['employee_email'] = $this->session->userdata('employee_email');
        $data['business_name'] = $this->employee_model->get_business_name($this->session->userdata('business_id'));
        $data['department_name'] = $this->employee_model->get_department_name($this->session->userdata('department_id'));

        // Example: Fetch tasks assigned to the employee
        $data['assigned_tasks'] = $this->employee_model->get_assigned_tasks($employee_id);

        // Example: Fetch recent time entries for the employee
        $data['recent_time_entries'] = $this->employee_model->get_recent_time_entries($employee_id, 5); // Limit to 5 entries

        $this->load->view('templates/header', $data);
        $this->load->view('employee/employee_dashboard_view', $data); // Create a new view for employees
        $this->load->view('templates/footer');
    }

    // Add other methods for specific employee dashboard sections or functionalities
    // e.g., public function tasks(), public function timesheets(), etc.
}