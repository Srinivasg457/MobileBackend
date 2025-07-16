<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AutoLoginController extends CI_Controller {

    private $jwt_key;
    private $allowed_algs = ['HS256']; // Only allow secure algorithm

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        $this->jwt_key = getenv('JWT_SECRET_KEY'); // Move to environment variable
        $this->load->helper('url');
    }

    public function auto_login()
    {
        // Prefer header over URL parameter for token
        $token = $this->input->get('token') ?? $this->input->get_request_header('Authorization');
        
        // Clean token if it comes with 'Bearer ' prefix
        $token = str_replace('Bearer ', '', $token);

        if (!$token) {
            $this->log_and_redirect('AutoLogin: Token is missing.', 'Token is missing.');
            return;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
            
            // Additional validation
            if (empty($decoded->sub) || empty($decoded->email)) {
                throw new Exception('Required token claims missing');
            }
            
            // Validate token expiration manually (double-check)
            $now = time();
            if (isset($decoded->exp) && $decoded->exp < $now) {
                throw new Exception('Token expired');
            }
            
            // Validate token was issued at acceptable time
            if (isset($decoded->iat) && $decoded->iat > $now + 60) {
                throw new Exception('Token issued in future');
            }

        } catch (\Exception $e) {
            $this->log_and_redirect('AutoLogin: Token validation failed: ' . $e->getMessage(), 
                                  'Invalid or expired token.');
            return;
        }

        // Get employee with additional security checks
        $employee = $this->db->select('id, name, email, business_id, department_id, user_id')
                            ->from('employees')
                            ->where([
                                'id' => $decoded->sub,
                                'email' => $decoded->email,
                                'is_registered' => 1,
                                'is_active' => 1 // Additional check for active status
                            ])
                            ->limit(1)
                            ->get()
                            ->row();

        if (!$employee) {
            $this->log_and_redirect("AutoLogin: Employee not found or inactive. Employee ID: {$decoded->sub}", 
                                  'Invalid token: Employee not found or account disabled.');
            return;
        }

        // Regenerate session ID to prevent fixation
        $this->session->sess_regenerate(true);

        // Set minimal required session data
        $session_data = [
            'user_type' => 'employee_user',
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'employee_email' => $employee->email,
            'business_id' => $employee->business_id,
            'department_id' => $employee->department_id,
            'employee_org_id' => $employee->user_id,
            'employee_logged_in' => true,
            'is_employee' => true,
            'last_activity' => time(), // Track activity
            'ip_address' => $this->input->ip_address(), // Bind session to IP
            'user_agent' => $this->input->user_agent() // Bind session to browser
        ];

        try {
            $this->session->set_userdata($session_data);
            
            // Log successful login
            $this->db->insert('employee_login_logs', [
                'employee_id' => $employee->id,
                'login_time' => date('Y-m-d H:i:s'),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent(),
                'login_method' => 'auto_login'
            ]);
            
            log_message('info', "AutoLogin: Employee login successful. Employee ID: {$employee->id}");
            
            // Secure redirect with no caching
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
            $this->output->set_header('Pragma: no-cache');
            $this->output->set_header('Expires: 0');
            redirect('employee/dashboard', 'auto', 302);
            
        } catch (\Exception $e) {
            $this->log_and_redirect('AutoLogin: Session setting failed: ' . $e->getMessage(), 
                                  'Login error. Please try again.');
            return;
        }
    }

    /**
     * Helper method for consistent error handling
     */
    private function log_and_redirect($log_message, $flash_message) {
        log_message('error', $log_message);
        $this->session->set_flashdata('error_message', $flash_message);
        redirect('login', 'auto', 302);
    }
}