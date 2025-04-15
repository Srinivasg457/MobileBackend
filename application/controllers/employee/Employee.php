<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->library('form_validation');
    }
     public function index(){
        if (!$this->session->userdata('employee_logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Employee Dashboard';
        $data['details'] = $this->session->userdata('employee_id');
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function screenshot(){
        if (!$this->session->userdata('employee_logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Screenshots';
        $data['user_id'] = $this->session->userdata('employee_id');
        $data['main_content'] = $this->load->view('admin/employee/screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    // Step 1: Handle invitation link
    public function accept_invitation()
    {
        $token = $this->input->get('token', true);
        $employee = $this->_get_employee_by_token($token);
        $employee_name = $employee->name;
        $employee_email = $employee-> email;

        if (!$employee) {
            redirect(base_url('login?registered=1'));
        }

        $data['employee'] = $employee_name;
        $data['token'] = $token;
        $data['email'] = $employee_email;
        $data["page"] = "Auth";
        $data['page_title'] = 'Employee Register';
        $data['main_content'] = $this->load->view('employee_register', $data, TRUE);
        $this->load->view('index', $data);
    }

    public function complete_registration()
    {
        $token = $this->input->post('token', true);

        // Re-fetch employee data using token
        $employee = $this->_get_employee_by_token($token);

        if (!$employee) {
            show_404(); // Invalid token
        }

        // Continue without validation
        $password = $this->input->post('password', true);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $this->_update_employee_password($employee->id, $hashed_password);

        // Redirect to login with success flag
        redirect(base_url('login?registered=1'));
    }


    // =====================
    // Model Logic Inline
    // =====================

    private function _get_employee_by_token($token)
    {
        return $this->db->get_where('employees', ['invitation_token' => $token])->row();
    }

    private function _update_employee_password($id, $hashed_password)
    {
        $data = [
            'password' => $hashed_password,
            'is_registered' => 1,
            'invitation_token' => null
        ];

        $this->db->where('id', $id);
        return $this->db->update('employees', $data);
    }
}
