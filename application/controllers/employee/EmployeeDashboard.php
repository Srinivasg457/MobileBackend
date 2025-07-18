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

    // public function index() {
    //     $data = array();
    //     $data['page_title'] = 'Employee Dashboard';
    //     $data['is_employee_admin'] = false;
    //     $data['details'] = $this->session->userdata('employee_id');
    //     $from_date = $this->input->post('fromDate', true);
    //     $to_date = $this->input->post('toDate', true);
    //     $data['employee_activity'] = $this->Employee_chart_Data($from_date, $to_date);
    //     $data['overall_productivity'] = $this->employee_overall_productivity($from_date,$to_date);
    //     $data['weekly_report'] = $this->get_weekly_report_data();
    //     $data['inactive_data'] = $this->get_this_week_inactive_time_data(); // 👈 Add this line   
    //     $data['avarage_data'] = $this->get_this_week_avarage_time_data(); // 👈 Add this line    
    //     $data['active_time_comparison'] = $this->compare_weekly_average_active_time();

    //     $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
    //     $this->load->view('admin/index', $data);
    // }

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Employee Dashboard';
        $data['is_employee_admin'] = false;
        $data['details'] = $this->session->userdata('employee_id');

        $time_period = $this->input->post('Time_period', true);
        $from_date = $this->input->post('fromDate', true);
        $to_date = $this->input->post('toDate', true);
        $data['time_period'] = $time_period;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        if (empty($time_period) && (empty($from_date) || empty($to_date))) {
            $data['time_period'] = "current_week";
        }

            // If dropdown is selected and from/to date are empty, calculate from dropdown
            if (!empty($time_period) && (empty($from_date) || empty($to_date))) {
            $today = date('Y-m-d');

            // switch ($time_period) {
            //     case 'current_week':
            //         $from_date = date('Y-m-d', strtotime('monday this week'));
            //         $to_date = $today;
            //         break;

            //     case 'last_7_days':
            //         $from_date = date('Y-m-d', strtotime('-6 days'));
            //         $to_date = $today;
            //         break;

            //     case 'last_14_days':
            //         $from_date = date('Y-m-d', strtotime('-13 days'));
            //         $to_date = $today;
            //         break;

            //     case 'this_month':
            //         $from_date = date('Y-m-01');
            //         $to_date = $today;
            //         break;

            //     case 'last_1_month':
            //         $from_date = date('Y-m-d', strtotime('-1 month'));
            //         $to_date = $today;
            //         break;

            //     case 'last_6_months':
            //         $from_date = date('Y-m-d', strtotime('-6 months'));
            //         $to_date = $today;
            //         break;

            //     case 'this_year':
            //         $from_date = date('Y-01-01');
            //         $to_date = $today;
            //         break;
            // }

            switch ($time_period) {
                case 'current_week':
                    $from_date = date('Y-m-d', strtotime('monday this week'));
                    $to_date = date('Y-m-d', strtotime('sunday this week'));
                    break;

                case 'last_week':
                    $from_date = date('Y-m-d', strtotime('monday last week'));
                    $to_date = date('Y-m-d', strtotime('sunday last week'));
                    break;

                case 'two_week':
                    $from_date = date('Y-m-d', strtotime('monday -2 weeks'));
                    $to_date = date('Y-m-d', strtotime('sunday last week'));
                    break;

                case 'this_month':
                    $from_date = date('Y-m-01');
                    $to_date = date('Y-m-t');
                    break;

                case 'last_month':
                    $from_date = date('Y-m-01', strtotime('first day of last month'));
                    $to_date = date('Y-m-t', strtotime('last day of last month'));
                    break;

                case 'last_6_months':
                    $from_date = date('Y-m-01', strtotime('-6 months'));
                    $to_date = date('Y-m-t');
                    break;

                case 'this_year':
                    $from_date = date('Y-01-01');
                    $to_date = date('Y-12-31');
                    break;

                default:
                    $from_date = $to_date = date('Y-m-d');
                    break;
            }
        }


        // Save values to pass to view
        $data['inactive_data'] = $this->get_this_week_inactive_time_data(); // 👈 Add this line   
        $data['avarage_data'] = $this->get_this_week_avarage_time_data(); // 👈 Add this line    
        $data['employee_activity'] = $this->Employee_chart_Data($from_date, $to_date);
        $data['overall_productivity'] = $this->employee_overall_productivity($from_date, $to_date);
        $data['weekly_report'] = $this->get_weekly_report_data();
        $data['first_record_date'] = $this->get_user_oldest_activity_date();
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    // private function get_weekly_report_data()
    // {
    //     $employee_id = $this->session->userdata('employee_id');
    //     $user_id = $this->session->userdata('employee_org_id');

    //     if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
    //         return []; // or throw exception if you want
    //     }

    //     $weekly_reports = [];
    //     $weeks_to_include_raw = [];

    //     $today = new DateTime();

    //     // Current week
    //     $current_week_start = clone $today;
    //     $current_week_start->modify('last sunday');
    //     $current_week_end = clone $today;

    //     $weeks_to_include_raw[] = [
    //         'name_prefix' => 'Current Week',
    //         'start_date' => $current_week_start->format('Y-m-d'),
    //         'end_date' => $current_week_end->format('Y-m-d')
    //     ];

    //     // Previous 4 weeks
    //     $week_counter = 1;
    //     $prev_week_cursor_end = clone $current_week_start;

    //     for ($i = 0; $i < 4; $i++) {
    //         $prev_week_end = clone $prev_week_cursor_end;
    //         $prev_week_end->modify('-1 day');

    //         $prev_week_start = clone $prev_week_end;
    //         $prev_week_start->modify('last sunday');

    //         if ($prev_week_start > $today) break;

    //         $weeks_to_include_raw[] = [
    //             'name_prefix' => 'Week ' . $week_counter,
    //             'start_date' => $prev_week_start->format('Y-m-d'),
    //             'end_date' => $prev_week_end->format('Y-m-d')
    //         ];

    //         $prev_week_cursor_end = clone $prev_week_start;
    //         $week_counter++;
    //     }

    //     $weeks_to_process = array_reverse($weeks_to_include_raw);

    //     foreach ($weeks_to_process as $week_data) {
    //         $week_name = $week_data['name_prefix'];

    //         $this->db->select('log_date, total_active_time')
    //             ->from('time_logs')
    //             ->where('employee_id', $employee_id)
    //             ->where('user_id', $user_id)
    //             ->where('log_date >=', $week_data['start_date'])
    //             ->where('log_date <=', $week_data['end_date'])
    //             ->order_by('log_date', 'ASC');

    //         $query = $this->db->get();
    //         $logs = $query->result_array();

    //         $total_seconds = 0;
    //         $daily_hours = [];
    //         $days_with_data = 0;

    //         foreach ($logs as $log) {
    //             if (!isset($log['total_active_time']) || empty($log['total_active_time'])) continue;

    //             $time_parts = explode(':', $log['total_active_time']);
    //             if (count($time_parts) !== 3) continue;

    //             $hours = (int)$time_parts[0];
    //             $minutes = (int)$time_parts[1];
    //             $seconds = (int)$time_parts[2];

    //             $daily_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
    //             $total_seconds += $daily_seconds;

    //             $daily_hours[$log['log_date']] = $log['total_active_time'];
    //             $days_with_data++;
    //         }

    //         $hours = floor($total_seconds / 3600);
    //         $remaining_seconds = $total_seconds % 3600;
    //         $minutes = floor($remaining_seconds / 60);
    //         $seconds = $remaining_seconds % 60;

    //         if ($days_with_data > 0) {
    //             $weekly_reports[] = [
    //                 'week_name' => $week_name,
    //                 'date_range' => $week_data['start_date'] . ' to ' . $week_data['end_date'],
    //                 'total_active_time' => sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds),
    //                 'days_with_data' => $days_with_data,
    //                 'total_days_in_week' => (new DateTime($week_data['end_date']))->diff(new DateTime($week_data['start_date']))->days + 1,
    //                 'daily_breakdown' => $daily_hours
    //             ];
    //         }
    //     }

    //     return $weekly_reports;
    // }

    private function get_weekly_report_data()
    {
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');

        if (empty($employee_id) || empty($organization_id) || !is_numeric($employee_id) || !is_numeric($organization_id)) {
            return [];
        }

        $weekly_reports = [];
        $today = new DateTime();

        // Current week
        $current_week_start = clone $today;
        $current_week_start->modify('last monday');
        $current_week_end = clone $today;

        $weeks_to_include = [];

        $weeks_to_include[] = [
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
            $prev_week_start->modify('last monday');

            if ($prev_week_start > $today) break;

            $weeks_to_include[] = [
                'name_prefix' => 'Week ' . $week_counter,
                'start_date' => $prev_week_start->format('Y-m-d'),
                'end_date' => $prev_week_end->format('Y-m-d')
            ];

            $prev_week_cursor_end = clone $prev_week_start;
            $week_counter++;
        }

        $weeks_to_include = array_reverse($weeks_to_include);

        foreach ($weeks_to_include as $week) {
            $from_date = $week['start_date'];
            $to_date = $week['end_date'];
            $week_name = $week['name_prefix'];

            // Fetch time logs summary
            $this->db->select('
            SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active,
        ');
            $this->db->from('time_logs');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('user_id', $organization_id);
            $this->db->where("DATE(created_at) >=", $from_date);
            $this->db->where("DATE(created_at) <=", $to_date);
            $time_data = $this->db->get()->row();


            $weekly_reports[] = [
                'week_name' => $week_name,
                'date_range' => "$from_date to $to_date",
                'total_active' => $this->formatToHoursMins($time_data->total_active ?? '00:00:00'),
            ];
        }

        return $weekly_reports;
    }



    private function employee_overall_productivity($from_date, $to_date)
    {
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');

        // Default to today's date if empty
        if (empty($from_date) || empty($to_date)) {
            $from_date = date('Y-m-d', strtotime('monday this week'));
            $to_date = date('Y-m-d');
        }

        // Fetch total active and idle time in seconds
        $this->db->select("
            SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active,
            SEC_TO_TIME(SUM(TIME_TO_SEC(total_idle_time))) AS total_idle,
            SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time)))) AS total_duration,
            ");
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(created_at) >=", $from_date);
        $this->db->where("DATE(created_at) <=", $to_date);
        $query = $this->db->get()->row();

        $this->db->select("
                SEC_TO_TIME(SUM(CASE WHEN is_meeting = 1 THEN TIME_TO_SEC(TIMEDIFF(timestamp_end, timestamp_start)) ELSE 0 END)) AS meeting_time,
                SEC_TO_TIME(SUM(CASE WHEN is_meeting = 0 THEN TIME_TO_SEC(TIMEDIFF(timestamp_end, timestamp_start)) ELSE 0 END)) AS manual_time
            ");
        $this->db->from('timecards_manual');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(date_added) >=", $from_date);
        $this->db->where("DATE(date_added) <=", $to_date);
        $query1 = $this->db->get()->row();


        $formatted_total_active_temp = $this->formatToHoursMins($query->total_active);
        $formatted_total_idle_temp = $this->formatToHoursMins($query->total_idle);
        $formatted_total_shift_time_temp = $this->formatToHoursMins($query->total_duration);
        $formatted_total_meeting_time_temp = $this->formatToHoursMins($query1->meeting_time);
        $formatted_total_manual_time_temp = $this->formatToHoursMins($query1->manual_time);


        $formatted_total_active = $this->convertTimeToMinutes($formatted_total_active_temp);
        $formatted_total_idle = $this->convertTimeToMinutes($formatted_total_idle_temp);
        $formatted_total_shift_time = $this->convertTimeToMinutes($formatted_total_shift_time_temp);
        $formatted_total_meeting_time = $this->convertTimeToMinutes($formatted_total_meeting_time_temp);
        $formatted_total_manual_time = $this->convertTimeToMinutes($formatted_total_manual_time_temp);



        // Calculate percentages
        $active_percent = 0;
        $idle_percent = 0;
        $meeting_percent = 0;
        $manual_percent = 0;

        if ($formatted_total_shift_time > 0) {
            $active_percent  = ($formatted_total_active > 0)  ? floor(($formatted_total_active / $formatted_total_shift_time) * 100)  : 0;
            $idle_percent    = ($formatted_total_idle > 0)    ? floor(($formatted_total_idle / $formatted_total_shift_time) * 100)    : 0;
            $meeting_percent = ($formatted_total_meeting_time > 0) ? floor(($formatted_total_meeting_time / $formatted_total_shift_time) * 100) : 0;
            $manual_percent  = ($formatted_total_manual_time > 0)  ? floor(($formatted_total_manual_time / $formatted_total_shift_time) * 100)  : 0;
        }


        return [
            'active_time' =>  $formatted_total_active_temp,
            'idle_time' =>  $formatted_total_idle_temp,
            'meeting_time' =>  $formatted_total_meeting_time_temp,
            'manual_time' =>  $formatted_total_manual_time_temp,
            'duration'  => $formatted_total_shift_time_temp,
            'active_percentage' => $active_percent,
            'idle_percentage' => $idle_percent,
            'meeting_percentage' => $meeting_percent,
            'manual_percentage' => $manual_percent

        ];
    }



    private function Employee_chart_Data($from_date, $to_date)
    {
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');

        // Set date range to today if empty
        if (empty($from_date) || empty($to_date)) {
            $from_date = date('Y-m-d', strtotime('monday this week'));
            $to_date = date('Y-m-d');
        }

        // Fetch total active, idle, and shift from time_logs
        $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active, SEC_TO_TIME(SUM(TIME_TO_SEC(total_idle_time))) AS total_idle, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time)))) AS total_duration');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(created_at) >=", $from_date);
        $this->db->where("DATE(created_at) <=", $to_date);
        $query = $this->db->get()->row();

        // Defaults
        $total_active = $query->total_active ?? "0h 0m";
        $total_idle = $query->total_idle ?? "0h 0m";
        $shift_time = $query->total_duration ?? "0h 0m";;


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
        $formatted_total_shift_time = $this->formatToHoursMins($shift_time);


        return [
            'total_active' => $formatted_total_active,
            'total_idle' => $formatted_total_idle,
            'shift_time' => $formatted_total_shift_time,
            'total_mouse_movements' => $total_mouse,
            'total_keystrokes' => $total_keys,
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


    private function convertTimeToMinutes($timeStr)
    {
        // Extract hours and minutes using regex
        preg_match('/(\d+)h\s*(\d+)m/', $timeStr, $matches);

        // Convert to total minutes
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;

        return ($hours * 60) + $minutes;
    }

    private function get_user_oldest_activity_date()
    {
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');
        $this->db->select_min('log_date'); // Change to your date column name
        $this->db->where('employee_id', $employee_id); // Change to your user column
        $this->db->where('user_id', $organization_id);
        $query = $this->db->get('time_logs'); // Change to your table name

        $result = $query->row();
        return $result ? $result->log_date : null;
    }

    private function get_this_week_inactive_time_data()
    {
        $employee_id = $this->session->userdata('employee_id');
        $user_id = $this->session->userdata('employee_org_id');
    
        if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
            return [];
        }
    
        $today = new DateTime();
        $monday = (clone $today)->modify('monday this week');
    
        $this->db->select('log_date, total_idle_time')
            ->from('time_logs')
            ->where('employee_id', $employee_id)
            ->where('user_id', $user_id)
            ->where('log_date >=', $monday->format('Y-m-d'))
            ->where('log_date <=', $today->format('Y-m-d'))
            ->order_by('log_date', 'ASC');
    
        $query = $this->db->get();
        $logs = $query->result_array();
    
        $total_seconds = 0;
        $daily_hours = [];
        $days_with_data = 0;
    
        foreach ($logs as $log) {
            if (!isset($log['total_idle_time']) || empty($log['total_idle_time'])) continue;
    
            $parts = explode(':', $log['total_idle_time']);
            if (count($parts) !== 3) continue;
    
            $hours = (int)$parts[0];
            $minutes = (int)$parts[1];
            $seconds = (int)$parts[2];
    
            $total_seconds += ($hours * 3600) + ($minutes * 60) + $seconds;
            $daily_hours[$log['log_date']] = $log['total_idle_time'];
            $days_with_data++;
        }
    
        $hours = floor($total_seconds / 3600);
        $minutes = floor(($total_seconds % 3600) / 60);
        $seconds = $total_seconds % 60;
    
        return [
            'week_name' => 'Current Week',
            'date_range' => $monday->format('Y-m-d') . ' to ' . $today->format('Y-m-d'),
            'total_idle_time' => sprintf('%02d:%02d', $hours, $minutes),
            'days_with_data' => $days_with_data,
            'total_days_in_week' => (new DateTime($today->format('Y-m-d')))->diff(new DateTime($monday->format('Y-m-d')))->days + 1,
            'daily_breakdown' => $daily_hours
        ];
    }
    

    private function get_this_week_avarage_time_data()
{
    $employee_id = $this->session->userdata('employee_id');
    $user_id = $this->session->userdata('employee_org_id');

    if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
        return [];
    }

    $today = new DateTime(); // Example: Thursday
    $yesterday = (clone $today)->modify('-1 day'); // Wednesday
    $monday = (clone $today)->modify('monday this week');

    $this->db->select('log_date, total_idle_time, total_active_time')
        ->from('time_logs')
        ->where('employee_id', $employee_id)
        ->where('user_id', $user_id)
        ->where('log_date >=', $monday->format('Y-m-d'))
        ->where('log_date <=', $yesterday->format('Y-m-d'))
        ->order_by('log_date', 'ASC');

    $query = $this->db->get();
    $logs = $query->result_array();

    $total_idle_seconds = 0;
    $total_active_seconds = 0;
    $daily_breakdown = [];
    $days_count = 0;

    foreach ($logs as $log) {
        $idle_time = $log['total_idle_time'];
        $active_time = $log['total_active_time'];

        $idle_sec = $active_sec = 0;

        if (!empty($idle_time)) {
            $parts = explode(':', $idle_time);
            if (count($parts) === 3) {
                $idle_sec = ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
            }
        }

        if (!empty($active_time)) {
            $parts = explode(':', $active_time);
            if (count($parts) === 3) {
                $active_sec = ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
            }
        }

        $total_idle_seconds += $idle_sec;
        $total_active_seconds += $active_sec;
        $daily_breakdown[$log['log_date']] = [
            'idle' => gmdate('H:i:s', $idle_sec),
            'active' => gmdate('H:i:s', $active_sec)
        ];

        $days_count++;
    }

    $format_time = function ($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    };

    // ✅ Return data as a variable
    $avarage_data = [
        'week_name' => 'Current Week',
        'date_range' => $monday->format('Y-m-d') . ' to ' . $yesterday->format('Y-m-d'),
        'days_count' => $days_count,
        'total_idle_time' => $format_time($total_idle_seconds),
        'total_active_time' => $format_time($total_active_seconds),
       'average_idle_time' => $days_count > 0 ? gmdate('H:i', floor($total_idle_seconds / $days_count)) : '00:00',
'average_active_time' => $days_count > 0 ? gmdate('H:i', floor($total_active_seconds / $days_count)) : '00:00',

        'daily_breakdown' => $daily_breakdown
    ];

    return $avarage_data;
}


private function compare_weekly_average_active_time()
{
    $employee_id = $this->session->userdata('employee_id');
    $user_id = $this->session->userdata('employee_org_id');

    if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
        return [];
    }

    $today = new DateTime(); // Today
    $this_monday = (clone $today)->modify('monday this week');
    $yesterday = (clone $today)->modify('-1 day');

    $last_week_monday = (clone $this_monday)->modify('-7 days');
    $last_week_sunday = (clone $this_monday)->modify('-1 day');

    // Helper function to calculate average active time (in seconds) for a date range
    $get_average_active_seconds = function ($from_date, $to_date) use ($employee_id, $user_id) {
        $this->db->select('total_active_time')
            ->from('time_logs')
            ->where('employee_id', $employee_id)
            ->where('user_id', $user_id)
            ->where('log_date >=', $from_date->format('Y-m-d'))
            ->where('log_date <=', $to_date->format('Y-m-d'));

        $query = $this->db->get();
        $logs = $query->result_array();

        $total_active_seconds = 0;
        $days_count = 0;

        foreach ($logs as $log) {
            if (!empty($log['total_active_time'])) {
                $parts = explode(':', $log['total_active_time']);
                if (count($parts) === 3) {
                    $active_sec = ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
                    $total_active_seconds += $active_sec;
                    $days_count++;
                }
            }
        }

        return $days_count > 0 ? floor($total_active_seconds / $days_count) : 0;
    };

    $current_week_avg_sec = $get_average_active_seconds($this_monday, $yesterday);
    $last_week_avg_sec = $get_average_active_seconds($last_week_monday, $last_week_sunday);

    $format_time = function ($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $h, $m);
    };

    $percentage_change = 0;
    $status = 'no change';

    if ($last_week_avg_sec > 0) {
        $percentage_change = (($current_week_avg_sec - $last_week_avg_sec) / $last_week_avg_sec) * 100;
        $status = $percentage_change > 0 ? 'increased by' : 'decreased by';
    }

    return [
        'current_week_avg_active_time' => $format_time($current_week_avg_sec),
        'last_week_avg_active_time' => $format_time($last_week_avg_sec),
        'change_percentage' => round(abs($percentage_change), 2),
        'status' => $status,
    ];
}


public function get_this_week_inactive_time()
{
    try {
        $employee_id = $this->input->get_request_header('employee_id', TRUE);
        $user_id = $this->input->get_request_header('user_id', TRUE);

        // Calculate this week's Monday and today's date
        $today = new DateTime();
        $monday = (clone $today)->modify('monday this week');

        // Query time_logs from Monday to today
        $this->db->select('log_date, total_idle_time')
            ->from('worksmart.time_logs')
            ->where('employee_id', $employee_id)
            ->where('user_id', $user_id)
            ->where('log_date >=', $monday->format('Y-m-d'))
            ->where('log_date <=', $today->format('Y-m-d'));

        $query = $this->db->get();
        $result = $query->result_array();

        // Initialize total and daily breakdown
        $total_seconds = 0;
        $daily_breakdown = [];

        foreach ($result as $row) {
            $log_date = $row['log_date'];
            $idle_time = $row['total_idle_time'];

            // Convert HH:MM:SS to seconds
            list($hours, $minutes, $seconds) = explode(':', $idle_time);
            $seconds_today = ($hours * 3600) + ($minutes * 60) + $seconds;

            $total_seconds += $seconds_today;

            $daily_breakdown[] = [
                'date' => $log_date,
                'idle_time' => $idle_time
            ];
        }

        // Convert total seconds to HH:MM:SS
        $total_idle_time = gmdate('H:i:s', $total_seconds);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'date_range' => $monday->format('Y-m-d') . ' to ' . $today->format('Y-m-d'),
                'total_idle_time' => $total_idle_time,
                'daily_breakdown' => $daily_breakdown
            ]));

    } catch (Exception $e) {
        log_message('error', 'get_this_week_inactive_time failed: ' . $e->getMessage());

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error_details' => $e->getMessage()
            ]));
    }
}
    // Add other methods for specific employee dashboard sections or functionalities
    // e.g., public function tasks(), public function timesheets(), etc.
}