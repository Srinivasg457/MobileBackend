<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AutoLoginController extends CI_Controller {

    private $jwt_key;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        $this->jwt_key = "limitscale_workroom";
    }

    public function auto_login()
    {
        $token = $this->input->get('token');

        if (!$token) {
            log_message('error', 'AutoLogin: Token is missing.');
            $this->session->set_flashdata('error_message', 'Token is missing.');
            redirect('login');
            return;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
        } catch (\Exception $e) {
            log_message('error', 'AutoLogin: Token is invalid: ' . $e->getMessage());
            $this->session->set_flashdata('error_message', 'Invalid or expired token.');
            redirect('login');
            return;
        }

        $employee_id = $decoded->userId ?? null;
        if (!$employee_id) {
            log_message('error', 'AutoLogin: Employee ID is missing from token.');
            $this->session->set_flashdata('error_message', 'Invalid token: Employee ID missing.');
            redirect('login');
            return;
        }

        $employee = $this->db->get_where('employees', [
            'id' => $employee_id,
            'email' => $decoded->email ?? '',
            'is_registered' => 1
        ])->row();

        if (!$employee) {
            log_message('error', "AutoLogin: Employee not found in database. Employee ID: $employee_id");
            $this->session->set_flashdata('error_message', 'Invalid token: Employee not found.');
            redirect('login');
            return;
        }

        try {
            $this->session->set_userdata([
                'user_type' => 'employee_user',
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'employee_email' => $employee->email,
                'business_id' => $employee->business_id,
                'department_id' => $employee->department_id,
                'employee_org_id' => $employee->user_id,
                'employee_logged_in' => true,
                'is_employee' => true
            ]);
             log_message('info', "AutoLogin: Employee login successful. Employee ID: $employee->id");
            redirect('employee/dashboard');
        } catch (\Exception $e) {
            log_message('error', 'AutoLogin: Session setting failed: ' . $e->getMessage());
             $this->session->set_flashdata('error_message', 'Login error. Please try again.');
            redirect('login');
            return;
        }
    }
}
