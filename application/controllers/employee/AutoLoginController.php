<?php
// Prevent direct script access - only allow through CodeIgniter's bootstrap index.php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load Composer's autoloader to use external libraries (like Firebase JWT)
require_once APPPATH . '../vendor/autoload.php';

// Import the JWT classes we need from the Firebase library
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Controller class for handling automatic logins via JWT tokens
class AutoLoginController extends CI_Controller {

    // Secret key for JWT encoding/decoding
    private $jwt_key;

    // Constructor function runs when class is instantiated
    public function __construct() {
        parent::__construct(); // Call parent (CI_Controller) constructor
        $this->load->library('session'); // Load session library
        $this->load->database(); // Load database library
        $this->jwt_key = "limitscale_workroom"; // Set JWT secret key (should be more secure in production)
    }

    /**
     * Generate JWT token for automatic login
     * @param int $employee_id - Employee's unique ID
     * @param string $email - Employee's email address
     * @return string - Generated JWT token
     */
    public function generate_login_token($employee_id, $email) {
        $issuedAt = time(); // Current timestamp
        $expire = $issuedAt + (24 * 60 * 60); // Token expires in 24 hours (86400 seconds)
        
        // Create the JWT payload (data to be encoded)
        $payload = [
            'sub' => $employee_id, // Subject (user ID)
            'email' => $email, // User's email
            'iat' => $issuedAt, // Issued at timestamp
            'exp' => $expire // Expiration timestamp
        ];
        
        // Encode the payload and return the JWT token
        return JWT::encode($payload, $this->jwt_key, 'HS256');
    }

    /**
     * Automatic login endpoint - validates token and logs user in
     */
    public function auto_login() {
        // Get token from URL query parameter
        $token = $this->input->get('token')?? $this->input->get_request_header('user_id', TRUE);
        
        // If no token provided, show regular login page
        if (!$token) {
            $this->load->view('login_view');
            return; // Exit the function early
        }
        
        try {
            // Verify and decode the token using our secret key
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
            // Extract employee ID from token (subject claim)
            $employee_id = $decoded->sub ?? null;
            // Extract email from token
            $email = $decoded->email ?? '';
            
            // Validate we got an employee ID
            if (!$employee_id) {
                throw new Exception('Employee ID missing in token');
            }
            
            // Check database for matching employee
            $employee = $this->db->get_where('employees', [
                'id' => $employee_id, // Match employee ID
                'email' => $email, // Match email
                'is_registered' => 1 // Must be registered
            ])->row(); // Get single row result
            
            // If employee not found, throw exception
            if (!$employee) {
                throw new Exception('Employee not found or not registered');
            }
            
            // Set session data for authenticated user
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
            
            // Log successful login
            log_message('info', "AutoLogin: Successful login for employee ID: $employee->id");
            
            // Check if this is an API request (AJAX, JSON content type, etc.)
            $is_api_request = $this->input->is_ajax_request() || 
                              !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                              strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
            
            // Handle API response differently from web response
            if ($is_api_request) {
                // Return JSON response for API calls
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
            
        } catch (Exception $e) {
            // Log any errors that occur during the process
            log_message('error', 'AutoLogin failed: ' . $e->getMessage());
            
            // Again check if this is an API request
            $is_api_request = $this->input->is_ajax_request() || 
                              !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                              strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
            
            // Handle error response differently for API vs web
            if ($is_api_request) {
                // Return JSON error for API
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode([
                        'success' => false,
                        'message' => 'Login failed: ' . $e->getMessage()
                    ]));
            } else {
                // Set flash error message and redirect for web
                $this->session->set_flashdata('error_message', 'Invalid or expired login link');
                redirect('login');
            }
        }
    }
}