<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AutoLoginController extends CI_Controller {

    private $jwt_key; //  moved to class property

      public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        $this->jwt_key = "your_secret_key_here";  // Initialize in constructor.  IMPORTANT
    }

    public function auto_login()
    {
        $token = $this->input->get('token');

        if (!$token) {
            // Token missing
             echo "Token is missing.  Redirecting to login.<br>"; //Keep for Debugging
            redirect('login');
            return;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
        } catch (\Exception $e) {
            // Token invalid or expired
            echo "Token is invalid: " . $e->getMessage() . ". Redirecting to login.<br>"; // Keep for Debugging
            redirect('login');
            return;
        }

        // Validate employee ID
        $employee_id = $decoded->sub ?? null;
        if (!$employee_id) {
            echo "Employee ID is missing from token. Redirecting to login.<br>"; // Keep for Debugging.
            redirect('login');
            return;
        }

        $employee = $this->db->get_where('employees', [
            'id' => $employee_id,
            'email' => $decoded->email ?? '', // optional extra check
            'is_registered' => 1
        ])->row();

        if (!$employee) {
            echo "Employee not found in database. Redirecting to login.<br>";  // Keep for Debugging.
            redirect('login');
            return;
        }

        // Set session
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

        echo "Login successful.  Redirecting to dashboard.<br>"; //Keep for Debugging
        // Redirect to employee dashboard (or reports)
        redirect('employeedashboard'); // Customize this route.  Make SURE this route is correct.
    }
}
