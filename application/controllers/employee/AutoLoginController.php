<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AutoLoginController extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    public function auto_login()
    {
        $token = $this->input->get('token');

        if (!$token) {
            exit('Token not provided');
        }

        // Step 2: Decode token payload (without verifying signature)
        $tokenParts = explode('.', $token);
        if (count($tokenParts) != 3) {
            exit('Invalid token format');
        }

        $payload = json_decode(base64_decode($tokenParts[1]), true);
        $userId = $payload['userId'] ?? null;

        if (!$userId) {
            exit('User ID not found in token');
        }

        // Step 3: Fetch employee secret key from DB
        $employee = $this->db->get_where('employees', [
            'id' => $userId,
            'is_registered' => 1
        ])->row();

        if (!$employee) {
            exit('Employee not found');
        }

        $secret_key = $employee->secret_key ?? null;
        if (!$secret_key) {
            exit('Secret key not found for this user');
        }

        // Step 4: Verify token using employee's secret key
        try {
            $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        } catch (Exception $e) {
            exit('Invalid token: ' . $secret_key);
        }

        $employee_id = $decoded->userId ?? null;
        $email = $decoded->email ?? null;

        if (!$employee_id || !$email) {
            log_message('error', 'AutoLogin: Token data missing (userId/email).');
            $this->session->set_flashdata('error_message', 'Token data incomplete.');
            redirect('login');
            return;
        }

        $employee = $this->db->get_where('employees', [
            'id' => $employee_id,
            'email' => $email,
            'is_registered' => 1,
        ])->row();

        if (!$employee) {
            log_message('error', "AutoLogin: Employee not found. ID: $employee_id");
            $this->session->set_flashdata('error_message', 'Employee not found.');
            redirect('login');
            return;
        }

        try {
            $this->session->set_userdata([
                'employee_id'        => $employee->id,
                'user_type'          => 'employee_user',
                'employee_name'      => $employee->name,
                'employee_email'     => $employee->email,
                'business_id'        => $employee->business_id,
                'department_id'      => $employee->department_id,
                'role_id'            => $employee->role_id,
                'employee_org_id'    => $employee->user_id,
                'employee_logged_in' => TRUE,
                'is_employee'        => TRUE
            ]);

            log_message('info', "AutoLogin: Successful login for employee ID: $employee->id");
            redirect('employee/dashboard');
        } catch (\Exception $e) {
            log_message('error', 'AutoLogin: Session error: ' . $e->getMessage());
            $this->session->set_flashdata('error_message', 'Login failed. Please try again.');
            redirect('login');
        }
    }
}
