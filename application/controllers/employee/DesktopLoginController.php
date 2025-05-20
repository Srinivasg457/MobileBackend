<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include Composer autoload
require_once APPPATH . '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class DesktopLoginController extends CI_Controller {

    // Keep the JWT key here (still advisable to eventually move to an env/config)
    private $jwt_key = 'your_secure_random_secret_here';

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    public function login() {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->respond(405, 'Method Not Allowed');
            }

            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->security->xss_clean($this->input->post('password'));

            if (empty($email) || empty($password)) {
                return $this->respond(400, 'Email and password are required');
            }

            $employee = $this->getEmployeeByEmail($email);

            if (!$employee || !password_verify($password, $employee->password)) {
                return $this->respond(401, 'Invalid credentials');
            }

            $token = $this->generateJwtToken($employee);

            return $this->respond(200, 'Login successful', [
                'token' => $token,
                'employee_id' => $employee->id,
                'name' => $employee->name
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'Login Error: ' . $e->getMessage());
            return $this->respond(500, 'Internal Server Error', ['error' => $e->getMessage()]);
        }
    }

    private function getEmployeeByEmail($email) {
        try {
            $this->db->where('email', $email);
            $this->db->where('is_registered', 1);
            $query = $this->db->get('employees');
            return $query->row();
        } catch (\Throwable $e) {
            log_message('error', 'DB Error: ' . $e->getMessage());
            throw new Exception('Database error while fetching employee.');
        }
    }

    private function generateJwtToken($employee) {
        try {
            $payload = [
                'iss' => 'szigony-time-tracker',
                'sub' => $employee->id,
                'email' => $employee->email,
                'iat' => time(),
                'exp' => time() + (3600 * 24 * 7) // 7 days
            ];

            return JWT::encode($payload, $this->jwt_key, 'HS256');
        } catch (\Throwable $e) {
            log_message('error', 'JWT Error: ' . $e->getMessage());
            throw new Exception('Error generating authentication token.');
        }
    }

    private function respond($status_code, $message, $data = []) {
        http_response_code($status_code);
        echo json_encode(array_merge(['status' => $status_code < 300 ? 1 : 0, 'message' => $message], $data));
        exit;
    }
}
