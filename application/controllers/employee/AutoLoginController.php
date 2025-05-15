<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AutoLoginController extends CI_Controller {

    private $jwt_key = "your_secret_key_here"; // Same as in DesktopLoginController

    public function auto_login()
    {
        $token = $this->input->get('token');

        if (!$token) {
            // Token missing
            redirect('login');
            return;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
        } catch (\Exception $e) {
            // Token invalid or expired
            redirect('login');
            return;
        }

        // Validate employee ID
        $employee_id = $decoded->sub ?? null;
        if (!$employee_id) {
            redirect('login');
            return;
        }

        $employee = $this->db->get_where('employees', [
            'id' => $employee_id,
            'email' => $decoded->email ?? '', // optional extra check
            'is_registered' => 1
        ])->row();

        if (!$employee) {
            redirect('login');
            return;
        }

        // Set session
        $this->session->set_userdata([
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'employee_email' => $employee->email,
            'is_employee' => true,
            'logged_in' => true
        ]);

        // Redirect to employee dashboard (or reports)
        redirect('employeedashboard'); // Customize this route
    }
}
