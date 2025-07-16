<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('security');
        $this->load->library('form_validation');
      //  Session check for logged-in employee
        if (!$this->session->userdata('employee_logged_in')) {
            redirect('login'); // Redirect to the login page if not logged in as an employee
        }
        // $this->load->model('employee_model'); // Create a new model for employee-specific data
    }

    public function index() {
        $data = array();
        $data['page_title'] = 'Employee Dashboard';
        $data['is_employee_admin'] = false;
        $data['details'] = $this->session->userdata('employee_id');
        $data['chart_data'] = $this->Employee_chart_Data();

        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    private function Employee_chart_Data()
    {
        $date = $this->input->post('date', true);

        // Load session data
        $employee_id = "21"; // Replace with session if needed
        $organization_id = $this->session->userdata('employee_org_id');

        // Set date range (today's date)
        if (empty($date)) {
            $date = date('Y-m-d');
            // $date = '2025-07-14';   
        }
 

        // Fetch activity data from time_logs for today
        $this->db->select('total_active_time AS total_active, total_idle_time AS total_idle, start_time, end_time');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->like('created_at', $date);
        $query = $this->db->get()->row();

        // Default values
        $total_active = $query->total_active ?? "0h 0m";
        $total_idle   = $query->total_idle ?? "0h 0m";
        $shift_time   = "0h 0m";

        if (!empty($query->start_time) && !empty($query->end_time)) {
            $start_time = strtotime($query->start_time);
            $end_time   = strtotime($query->end_time);

            if ($end_time > $start_time) {
                $shift_seconds = $end_time - $start_time;
                $hours = floor($shift_seconds / 3600);
                $minutes = floor(($shift_seconds % 3600) / 60);
                $shift_time = "{$hours}h {$minutes}m";
            }
        }

        // ✅ Fetch total mouse movement and keystroke from Employee_Activity
        $this->db->select('SUM(total_mouse_movement) AS total_mouse, SUM(total_keystrokes) AS total_keys');
        $this->db->from('Employee_Activity');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->like('created_at', $date);
        $activity = $this->db->get()->row();

        // Format with commas
        $total_mouse = number_format($activity->total_mouse ?? 0);
        $total_keys  = number_format($activity->total_keys ?? 0);


        $formatted_total_active = $this->formatToHoursMins($total_active);
        $formatted_total_idle   = $this->formatToHoursMins($total_idle);

        return [
            'total_active'     => $formatted_total_active,
            'total_idle'       => $formatted_total_idle,
            'shift_time'       => $shift_time,
            'total_mouse_movements'  => $total_mouse,
            'total_keystrokes'       => $total_keys,
            'date'                   => $date
        ];
    }


    private function formatToHoursMins($time)
    {
        if (!$time) return "0h 0m";

        // If $time is a string like "02:30:00", convert to seconds
        if (strpos($time, ':') !== false) {
            list($h, $m, $s) = explode(':', $time);
            $time = ($h * 3600) + ($m * 60) + $s;
        }

        $hours = floor($time / 3600);
        $minutes = floor(($time % 3600) / 60);

        return "{$hours}h {$minutes}m";
    }





    // Add other methods for specific employee dashboard sections or functionalities
    // e.g., public function tasks(), public function timesheets(), etc.
}