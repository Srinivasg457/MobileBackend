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
        $from_date = $this->input->post('fromDate', true);
        $to_date = $this->input->post('toDate', true);
        $data['employee_activity'] = $this->Employee_chart_Data($from_date, $to_date);
        $data['overall_productivity'] = $this->employee_overall_productivity($from_date,$to_date);
        $data['weekly_report'] = $this->get_weekly_report_data();
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

private function get_weekly_report_data()
{
    $employee_id = $this->session->userdata('employee_id');
    $user_id = $this->session->userdata('employee_org_id');

    if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
        return []; // or throw exception if you want
    }

    $weekly_reports = [];
    $weeks_to_include_raw = [];

    $today = new DateTime();

    // Current week
    $current_week_start = clone $today;
    $current_week_start->modify('last sunday');
    $current_week_end = clone $today;

    $weeks_to_include_raw[] = [
        'name_prefix' => 'Current Week',
        'start_date' => $current_week_start->format('Y-m-d'),
        'end_date' => $current_week_end->format('Y-m-d')
    ];

    // Previous 4 weeks
    $week_counter = 1;
    $prev_week_cursor_end = clone $current_week_start;

    for ($i = 0; $i < 4; $i++) {
        $prev_week_end = clone $prev_week_cursor_end;
        $prev_week_end->modify('-1 day');

        $prev_week_start = clone $prev_week_end;
        $prev_week_start->modify('last sunday');

        if ($prev_week_start > $today) break;

        $weeks_to_include_raw[] = [
            'name_prefix' => 'Week ' . $week_counter,
            'start_date' => $prev_week_start->format('Y-m-d'),
            'end_date' => $prev_week_end->format('Y-m-d')
        ];

        $prev_week_cursor_end = clone $prev_week_start;
        $week_counter++;
    }

    $weeks_to_process = array_reverse($weeks_to_include_raw);

    foreach ($weeks_to_process as $week_data) {
        $week_name = $week_data['name_prefix'];

        $this->db->select('log_date, total_active_time')
            ->from('time_logs')
            ->where('employee_id', $employee_id)
            ->where('user_id', $user_id)
            ->where('log_date >=', $week_data['start_date'])
            ->where('log_date <=', $week_data['end_date'])
            ->order_by('log_date', 'ASC');

        $query = $this->db->get();
        $logs = $query->result_array();

        $total_seconds = 0;
        $daily_hours = [];
        $days_with_data = 0;

        foreach ($logs as $log) {
            if (!isset($log['total_active_time']) || empty($log['total_active_time'])) continue;

            $time_parts = explode(':', $log['total_active_time']);
            if (count($time_parts) !== 3) continue;

            $hours = (int)$time_parts[0];
            $minutes = (int)$time_parts[1];
            $seconds = (int)$time_parts[2];

            $daily_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
            $total_seconds += $daily_seconds;

            $daily_hours[$log['log_date']] = $log['total_active_time'];
            $days_with_data++;
        }

        $hours = floor($total_seconds / 3600);
        $remaining_seconds = $total_seconds % 3600;
        $minutes = floor($remaining_seconds / 60);
        $seconds = $remaining_seconds % 60;

        if ($days_with_data > 0) {
            $weekly_reports[] = [
                'week_name' => $week_name,
                'date_range' => $week_data['start_date'] . ' to ' . $week_data['end_date'],
                'total_active_time' => sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds),
                'days_with_data' => $days_with_data,
                'total_days_in_week' => (new DateTime($week_data['end_date']))->diff(new DateTime($week_data['start_date']))->days + 1,
                'daily_breakdown' => $daily_hours
            ];
        }
    }

    return $weekly_reports;
}


    private function employee_overall_productivity($from_date, $to_date)
    {
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');

        // Set default date
        if (empty($from_date) || empty($to_date)) {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        }

        // Fetch time log data
        $this->db->select('
        SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active, 
        SEC_TO_TIME(SUM(TIME_TO_SEC(total_idle_time))) AS total_idle, 
        MIN(start_time) AS start_time, 
        MAX(end_time) AS end_time
    ');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(created_at) >=", $from_date);
        $this->db->where("DATE(created_at) <=", $to_date);
        $query = $this->db->get()->row();

        // Convert times to seconds
        $active_seconds = strtotime("1970-01-01 " . $query->total_active) - strtotime("1970-01-01 00:00:00");
        $idle_seconds   = strtotime("1970-01-01 " . $query->total_idle) - strtotime("1970-01-01 00:00:00");

        $shift_seconds = 0;
        if (!empty($query->start_time) && !empty($query->end_time)) {
            $shift_seconds = strtotime($query->end_time) - strtotime($query->start_time);
        }

        $active_percentage = $shift_seconds > 0 ? round(($active_seconds / $shift_seconds) * 100, 1) : 0;
        $idle_percentage   = $shift_seconds > 0 ? round(($idle_seconds / $shift_seconds) * 100, 1) : 0;

        return [
            'active_time' => $query->total_active,
            'idle_time' => $query->total_idle,
            'shift_duration' => gmdate("H:i:s", $shift_seconds),
            'active_percentage' => $active_percentage,
            'idle_percentage' => $idle_percentage
        ];
    }


    private function Employee_chart_Data($from_date, $to_date)
    {
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');

        // Set date range to today if empty
        if (empty($from_date) || empty($to_date)) {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        }

        // Fetch total active, idle, and shift from time_logs
        $this->db->select('SUM(total_active_time) AS total_active, SUM(total_idle_time) AS total_idle, MIN(start_time) AS start_time, MAX(end_time) AS end_time');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(created_at) >=", $from_date);
        $this->db->where("DATE(created_at) <=", $to_date);
        $query = $this->db->get()->row();

        // Defaults
        $total_active = $query->total_active ?? "0h 0m";
        $total_idle = $query->total_idle ?? "0h 0m";
        $shift_time = "0h 0m";

        if (!empty($query->start_time) && !empty($query->end_time)) {
            $start_time = strtotime($query->start_time);
            $end_time = strtotime($query->end_time);
            if ($end_time > $start_time) {
                $shift_seconds = $end_time - $start_time;
                $hours = floor($shift_seconds / 3600);
                $minutes = floor(($shift_seconds % 3600) / 60);
                $shift_time = "{$hours}h {$minutes}m";
            }
        }

        // Fetch total mouse and keystrokes
        $this->db->select('SUM(total_mouse_movement) AS total_mouse, SUM(total_keystrokes) AS total_keys');
        $this->db->from('Employee_Activity');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(created_at) >=", $from_date);
        $this->db->where("DATE(created_at) <=", $to_date);
        $activity = $this->db->get()->row();

        // Format data
        $total_mouse = number_format($activity->total_mouse ?? 0);
        $total_keys = number_format($activity->total_keys ?? 0);

        $formatted_total_active = $this->formatToHoursMins($total_active);
        $formatted_total_idle = $this->formatToHoursMins($total_idle);

        return [
            'total_active' => $formatted_total_active,
            'total_idle' => $formatted_total_idle,
            'shift_time' => $shift_time,
            'total_mouse_movements' => $total_mouse,
            'total_keystrokes' => $total_keys,
            'from_date' => $from_date,
            'to_date' => $to_date
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