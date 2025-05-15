<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include Composer autoload
require_once APPPATH . '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class DesktopLoginController extends CI_Controller {

    // Your secret key (keep this secure, ideally in an .env file or config)
    private $jwt_key;

    public function __construct() {
        parent::__construct();
        // Load necessary libraries and models
        $this->load->library('session');
        $this->load->database();
        $this->jwt_key = "your_secret_key_here"; //  Move to config
    }

    public function login()
    {
        header('Content-Type: application/json');

        try {
            // Only allow POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['status' => 0, 'message' => 'Method not allowed']);
                return;
            }

            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Validate input
            if (empty($email) || empty($password)) {
                http_response_code(400);
                echo json_encode(['status' => 0, 'message' => 'Email and password are required']);
                return;
            }

            // Fetch employee
            $this->db->where('email', $email);
            $this->db->where('is_registered', 1);
            $query = $this->db->get('employees');
            $employee = $query->row();

            if (!$employee) {
                http_response_code(401);
                echo json_encode(['status' => 0, 'message' => 'Invalid credentials']);
                return;
            }

            // Verify password
            if (!password_verify($password, $employee->password)) {
                http_response_code(401);
                echo json_encode(['status' => 0, 'message' => 'Invalid credentials']);
                return;
            }

            // Generate JWT
            $payload = [
                'iss' => 'szigony-time-tracker',
                'sub' => $employee->id,
                'email' => $employee->email,
                'iat' => time(),
                'exp' => time() + (3600 * 24 * 7) // 7 days
            ];

            $token = JWT::encode($payload, $this->jwt_key, 'HS256');

            echo json_encode([
                'status' => 1,
                'message' => 'Login successful',
                'token' => $token,
                'employee_id' => $employee->id,
                'name' => $employee->name
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 0,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }
}