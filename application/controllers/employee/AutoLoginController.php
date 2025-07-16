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
        $this->jwt_key = "limitscale_workroom"; // Should be in config and more secure
    }

    /**
     * Generate JWT token for automatic login
     */
    public function generate_login_token($employee_id, $email) {
        $issuedAt = time();
        $expire = $issuedAt + (24 * 60 * 60); // Token valid for 24 hours
        
        $payload = [
            'sub' => $employee_id,
            'email' => $email,
            'iat' => $issuedAt,
            'exp' => $expire
        ];
        
        return JWT::encode($payload, $this->jwt_key, 'HS256');
    }

    /**
     * Automatic login endpoint
     */
    public function auto_login() {
        $token = $this->input->get('token');
        
        // If no token provided, show regular login page
        if (!$token) {
            $this->load->view('login_view');
            return;
        }
        
        try {
            // Verify and decode the token
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
            $employee_id = $decoded->sub ?? null;
            $email = $decoded->email ?? '';
            
            if (!$employee_id) {
                throw new Exception('Employee ID missing in token');
            }
            
            // Verify employee exists and is registered
            $employee = $this->db->get_where('employees', [
                'id' => $employee_id,
                'email' => $email,
                'is_registered' => 1
            ])->row();
            
            if (!$employee) {
                throw new Exception('Employee not found or not registered');
            }
            
            // Set session data
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
            
            log_message('info', "AutoLogin: Successful login for employee ID: $employee->id");
            
            // Check if this is an API request
            $is_api_request = $this->input->is_ajax_request() || 
                              !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                              strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
            
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
                            'email' => $employee->email
                        ]
                    ]));
            } else {
                // Redirect to dashboard for regular web requests
                redirect('employee/dashboard');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'AutoLogin failed: ' . $e->getMessage());
            
            $is_api_request = $this->input->is_ajax_request() || 
                              !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                              strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
            
            if ($is_api_request) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode([
                        'success' => false,
                        'message' => 'Login failed: ' . $e->getMessage()
                    ]));
            } else {
                $this->session->set_flashdata('error_message', 'Invalid or expired login link');
                redirect('login');
            }
        }
    }
}