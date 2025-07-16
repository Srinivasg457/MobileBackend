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

    public function auto_login() {
        $token = $this->input->get('token');

        // Check if this is an API request (like from Postman)
        $is_api_request = $this->input->is_ajax_request() || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if (!$token) {
            $error_message = 'Token is missing.';
            log_message('error', 'AutoLogin: ' . $error_message);
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['success' => false, 'message' => $error_message]));
                return;
            } else {
                $this->session->set_flashdata('error_message', $error_message);
                redirect('login');
                return;
            }
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
        } catch (\Exception $e) {
            $error_message = 'Invalid or expired token: ' . $e->getMessage();
            log_message('error', 'AutoLogin: ' . $error_message);
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode(['success' => false, 'message' => $error_message]));
                return;
            } else {
                $this->session->set_flashdata('error_message', $error_message);
                redirect('login');
                return;
            }
        }

        $employee_id = $decoded->sub ?? null;
        if (!$employee_id) {
            $error_message = 'Employee ID is missing from token.';
            log_message('error', 'AutoLogin: ' . $error_message);
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['success' => false, 'message' => $error_message]));
                return;
            } else {
                $this->session->set_flashdata('error_message', $error_message);
                redirect('login');
                return;
            }
        }

        $employee = $this->db->get_where('employees', [
            'id' => $employee_id,
            'email' => $decoded->email ?? '',
            'is_registered' => 1
        ])->row();

        if (!$employee) {
            $error_message = "Employee not found in database. Employee ID: $employee_id";
            log_message('error', 'AutoLogin: ' . $error_message);
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode(['success' => false, 'message' => 'Invalid token: Employee not found.']));
                return;
            } else {
                $this->session->set_flashdata('error_message', 'Invalid token: Employee not found.');
                redirect('login');
                return;
            }
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
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'success' => true,
                        'message' => 'Login successful',
                        'employee' => [
                            'id' => $employee->id,
                            'name' => $employee->name,
                            'email' => $employee->email,
                            'business_id' => $employee->business_id,
                            'department_id' => $employee->department_id
                        ]
                    ]));
                return;
            } else {
                redirect('employee/dashboard');
                return;
            }
        } catch (\Exception $e) {
            $error_message = 'Session setting failed: ' . $e->getMessage();
            log_message('error', 'AutoLogin: ' . $error_message);
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['success' => false, 'message' => 'Login error. Please try again.']));
                return;
            } else {
                $this->session->set_flashdata('error_message', 'Login error. Please try again.');
                redirect('login');
                return;
            }
        }
    }
}