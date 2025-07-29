<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmployeeDashboard extends Home_Controller
{

    public function __construct()
    {
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
                    $from_date = date('Y-m-d', strtotime('monday -3 weeks'));
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
        
        $data['output'] = $this->get_this_week_work_time_data($from_date, $to_date); // 👈 Add this line
        $data['response_data'] = $this->get_last_week_total_active_hours(); // 👈 Add this line
        $data['yesterday_idle_alert'] = $this->get_yesterday_comparison_summary();
        $data['target_data'] = $this->get_this_week_target_time_data(); // 👈 Add this line   
        $data['inactive_data'] = $this->get_this_week_inactive_time_data(); // 👈 Add this line   
        $data['avarage_data'] = $this->get_this_week_avarage_time_data(); // 👈 Add this line    
        $data['active_time_comparison'] = $this->compare_weekly_average_active_time();
        // $data['weekly_reports'] = $this->get_weekly_report_data();
        $data['avarage_data'] = $this->get_this_week_avarage_time_data(); // 👈 Add this line    
        $data['employee_activity'] = $this->Employee_chart_Data($from_date, $to_date);
        $data['overall_productivity'] = $this->employee_overall_productivity($from_date, $to_date);
        $data['custom_date_chart_data'] = $this->get_custom_date_chart_data($from_date, $to_date); // 👈 Add this line   
        $data['date_range'] = $this->get_date_range($from_date, $to_date); // 👈 Add this line    
        // $data['weekly_report'] = $this->get_weekly_report_data();
        $data['first_record_date'] = $this->get_user_oldest_activity_date();
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    private function get_date_range($from_date, $to_date){
        $start = new DateTime($from_date);
        $end = new DateTime($to_date);
        $interval_days = $start->diff($end)->days + 1;
        $reportRange = "";

        if ($interval_days <= 14) {
            $reportRange = "Daily Report";
        } elseif ($interval_days <= 31) {
            $reportRange = "Weekly Report";
        } elseif ($interval_days <= 365) {
            $reportRange = "Monthly Report";
        } else {
            $reportRange = "Yearly Report";
        }

        return $reportRange;
    }

    private function get_custom_date_chart_data($from_date, $to_date)
    {
        // Default to today's date if empty
        if (empty($from_date) || empty($to_date)) {
            $from_date = date('Y-m-d', strtotime('monday this week'));
            $to_date = date('Y-m-d', strtotime('sunday this week'));
        }
        $employee_id = $this->session->userdata('employee_id');
        $organization_id = $this->session->userdata('employee_org_id');

        $start = new DateTime($from_date);
        $end = new DateTime($to_date);
        $interval_days = $start->diff($end)->days + 1;
        $result = [];

        if ($interval_days <= 14) {
            // Group by Day
            $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
            foreach ($period as $date) {
                $label = $date->format('D (M j)');
                $result[$label] = $this->get_total_active_time($employee_id, $organization_id, $date->format('Y-m-d'), $date->format('Y-m-d'));
            }
        } elseif ($interval_days <= 31) {
            // Group by Week
            $week_start = clone $start;
            $week_number = 1;

            while ($week_start <= $end) {
                $week_end = clone $week_start;
                $week_end->modify('+6 days');
                if ($week_end > $end) $week_end = $end;

                $label = "Week {$week_number} (" . $week_start->format('M j') . " - " . $week_end->format('M j') . ")";
                $result[$label] = $this->get_total_active_time($employee_id, $organization_id, $week_start->format('Y-m-d'), $week_end->format('Y-m-d'));

                $week_start->modify('+7 days');
                $week_number++;
            }
        } elseif ($interval_days <= 365) {
            // Group by Month
            // Group by Month (within custom date range)
            $current = clone $start;

            while ($current <= $end) {
                $month_start = new DateTime($current->format('Y-m-01'));
                $month_end = (clone $month_start)->modify('last day of this month');

                // Adjust based on user-specified date range
                $range_start = ($month_start < $start) ? clone $start : $month_start;
                $range_end = ($month_end > $end) ? clone $end : $month_end;

                $label = $range_start->format('M Y'); // Example: Jul 2025
                $result[$label] = $this->get_total_active_time(
                    $employee_id,
                    $organization_id,
                    $range_start->format('Y-m-d'),
                    $range_end->format('Y-m-d')
                );

                $current = $month_end->modify('+1 day');
            }
        } else {
            // Group by Year
            $current = clone $start;
            while ($current <= $end) {
                $year_start = new DateTime($current->format('Y-01-01'));
                $year_end = new DateTime($current->format('Y-12-31'));
                if ($year_end > $end) $year_end = $end;

                $label = $year_start->format('Y');
                $result[$label] = $this->get_total_active_time($employee_id, $organization_id, $year_start->format('Y-m-d'), $year_end->format('Y-m-d'));

                $current = $year_end->modify('+1 day');
            }
        }

        return $result;
    } 
    private function get_total_active_time($employee_id, $organization_id, $from_date, $to_date)
    {
        $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(log_date) >=", $from_date);
        $this->db->where("DATE(log_date) <=", $to_date);
        $row = $this->db->get()->row();
        $total_active = $row && $row->total_active ? $this->formatToHoursMins($row->total_active) : '0h 0m';

        return $total_active;
    }


  private function get_weekly_report_data()
{
    $employee_id = $this->session->userdata('employee_id');
    $organization_id = $this->session->userdata('employee_org_id');

    if (empty($employee_id) || empty($organization_id) || !is_numeric($employee_id) || !is_numeric($organization_id)) {
        return [];
    }

    $weekly_reports = [];
    $today = new DateTime();

    // Get current week's Monday
    $current_monday = clone $today;
    $current_monday->modify('monday this week');

    $week_start = clone $current_monday;
    $week_end = clone $week_start;
    $week_end->modify('sunday this week');

    $week_counter = 0;
    $weeks_to_include = [];

    while (true) {
        $from_date = $week_start->format('Y-m-d');
        $to_date = $week_end->format('Y-m-d');

        // Check if data exists in this week
        $this->db->select('COUNT(*) AS total');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(log_date) >=", $from_date);
        $this->db->where("DATE(log_date) <=", $to_date);
        $result = $this->db->get()->row();

        if (empty($result->total) || $result->total == 0) {
            break; // No more data found, stop the loop
        }

        $weeks_to_include[] = [
            'name_prefix' => ($week_counter == 0) ? 'Current Week' : 'Week ' . $week_counter,
            'start_date' => $from_date,
            'end_date' => $to_date
        ];

        // Move to previous week
        $week_end->modify('-1 week');
        $week_start->modify('-1 week');
        $week_counter++;
    }

    // Reverse for chronological order
    $weeks_to_include = array_reverse($weeks_to_include);

    foreach ($weeks_to_include as $week) {
        $from_date = $week['start_date'];
        $to_date = $week['end_date'];
        $week_name = $week['name_prefix'];

        // Weekly total
        $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(log_date) >=", $from_date);
        $this->db->where("DATE(log_date) <=", $to_date);
        $time_data = $this->db->get()->row();

        $daily_breakdown = [];

        $start = new DateTime($from_date);
        $end = new DateTime($to_date);

        while ($start <= $end) {
            $day_name = $start->format('l');
            $date_str = $start->format('Y-m-d');

            $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active');
            $this->db->from('time_logs');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('user_id', $organization_id);
            $this->db->where("DATE(log_date)", $date_str);
            $day_data = $this->db->get()->row();

            $daily_breakdown[$day_name] = $this->formatToHoursMins($day_data->total_active ?? '00:00:00');
            $start->modify('+1 day');
        }

        $weekly_reports[] = [
            'week_name' => $week_name,
            'date_range' => "$from_date to $to_date",
            'total_active' => $this->formatToHoursMins($time_data->total_active ?? '00:00:00'),
            'daily_breakdown' => $daily_breakdown
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
            $to_date = date('Y-m-d', strtotime('sunday this week'));
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
        $this->db->where("DATE(log_date) >=", $from_date);
        $this->db->where("DATE(log_date) <=", $to_date);
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
            $to_date = date('Y-m-d', strtotime('sunday this week'));
        }

        // Fetch total active, idle, and shift from time_logs
        $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(total_active_time))) AS total_active, SEC_TO_TIME(SUM(TIME_TO_SEC(total_idle_time))) AS total_idle, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time)))) AS total_duration');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $organization_id);
        $this->db->where("DATE(log_date) >=", $from_date);
        $this->db->where("DATE(log_date) <=", $to_date);
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
            return [
                'status' => 'error',
                'message' => 'Invalid employee or organization ID.',
            ];
        }

        $today = new DateTime(); // e.g., 21st July
        $monday = (clone $today)->modify('monday this week');
        $yesterday = $today; // ✅ include today in range

        $days_passed = max((int)$today->format('N'), 1); // Monday = 1, ..., Sunday = 7

        $this->db->select('log_date, total_idle_time, total_active_time')
            ->from('time_logs')
            ->where('employee_id', $employee_id)
            ->where('user_id', $user_id)
            ->where('log_date >=', $monday->format('Y-m-d'))
            ->where('log_date <=', $yesterday->format('Y-m-d')) // ✅ includes today
            ->order_by('log_date', 'ASC');

        $query = $this->db->get();
        $logs = $query->result_array();

        $total_idle_seconds = 0;
        $total_active_seconds = 0;
        $daily_breakdown = [];

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
        }

        $format_time = function ($seconds) {
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            $s = $seconds % 60;
            return sprintf('%02d:%02d:%02d', $h, $m, $s);
        };

        $average_idle = $days_passed > 0 ? gmdate('H:i', floor($total_idle_seconds / $days_passed)) : '00:00';
        $average_active = $days_passed > 0 ? gmdate('H:i', floor($total_active_seconds / $days_passed)) : '00:00';

        return [
            'status' => 'success',
            'message' => 'Average time data for the current week retrieved successfully.',
            'week_name' => 'Current Week',
            'date_range' => $monday->format('Y-m-d') . ' to ' . $today->format('Y-m-d'), // show up to today
            'days_count' => $days_passed,
            'total_idle_time' => $format_time($total_idle_seconds),
            'total_active_time' => $format_time($total_active_seconds),
            'average_idle_time' => $average_idle,
            'average_active_time' => $average_active,
            'daily_breakdown' => $daily_breakdown
        ];
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
                ->from('time_logs')
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

    public function get_this_week_target_time_data()
    {
        $employee_id = $this->session->userdata('employee_id');
        $user_id = $this->session->userdata('employee_org_id');

        if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
            return [];
        }

        $today = new DateTime(); // Current date
        $today_day = $today->format('l'); // Day name e.g. Thursday
        $monday = (clone $today)->modify('monday this week');
        $wednesday = (clone $monday)->modify('+2 days'); // Monday + 2 = Wednesday

        $this->db->select('log_date, total_idle_time, total_active_time')
            ->from('time_logs')
            ->where('employee_id', $employee_id)
            ->where('user_id', $user_id)
            ->where('log_date >=', $monday->format('Y-m-d'))
            ->where('log_date <=', $wednesday->format('Y-m-d')) // 👈 Only Mon–Wed
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

        $target_data = [
            'week_name' => 'Current Week',
            'date_range' => $monday->format('Y-m-d') . ' to ' . $wednesday->format('Y-m-d'),
            'days_count' => $days_count,
            'total_idle_time' => $format_time($total_idle_seconds),
            'total_active_time' => $format_time($total_active_seconds),
            'average_idle_time' => $days_count > 0 ? gmdate('H:i', floor($total_idle_seconds / $days_count)) : '00:00',
            'average_active_time' => $days_count > 0 ? gmdate('H:i', floor($total_active_seconds / $days_count)) : '00:00',
            'daily_breakdown' => $daily_breakdown
        ];

        // Show remaining time from Monday onwards
        $weekly_target_seconds = 47.5 * 3600;
        $remaining_seconds = max(0, $weekly_target_seconds - $total_active_seconds);
        $remaining_time = $format_time($remaining_seconds);

        $target_data['weekly_target'] = '47:30:00';
        $target_data['remaining_active_time'] = $remaining_time;

        // Calculate days remaining in the week (from today to Sunday)
        $sunday = (clone $monday)->modify('+6 days');
        $days_remaining = 0;
        if ($today <= $sunday) {
            $diff = $today->diff($sunday);
            $days_remaining = $diff->days + 1; // +1 to include today if needed
        }
        $target_data['days_remaining'] = $days_remaining;

        return $target_data;
    }

    private function get_yesterday_comparison_summary()
    {
        $employee_id = $this->session->userdata('employee_id');
        $user_id = $this->session->userdata('employee_org_id');

        if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
            return null;
        }

        $today = new DateTime();
        $yesterday = (clone $today)->modify('-1 day');

        // Don't show on Monday (no previous Sunday to compare)
        if ($yesterday->format('N') == 7) {
            return null;
        }

        $last_week_same_day = (clone $yesterday)->modify('-7 days');

        // Helper to get idle time in seconds
        $get_idle_seconds = function ($date) use ($employee_id, $user_id) {
            $this->db->select('total_idle_time')
                ->from('time_logs')
                ->where('employee_id', $employee_id)
                ->where('user_id', $user_id)
                ->where('log_date', $date->format('Y-m-d'));

            $query = $this->db->get();
            $row = $query->row_array();

            if (!empty($row['total_idle_time'])) {
                $parts = explode(':', $row['total_idle_time']);
                if (count($parts) === 3) {
                    return ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
                }
            }

            return 0;
        };

        $format_time = function ($seconds) {
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            return sprintf('%02d:%02d', $h, $m);
        };

        $this_week_idle = $get_idle_seconds($yesterday);
        $last_week_idle = $get_idle_seconds($last_week_same_day);
        $diff = $this_week_idle - $last_week_idle;

        // Only return data if idle time increased
        if ($diff <= 0) {
            return null;
        }

        $day_name = $yesterday->format('l'); // e.g., Tuesday

        return [
            'day' => $day_name,
            'this_week_idle' => $format_time($this_week_idle),
            'last_week_idle' => $format_time($last_week_idle),
            'idle_difference' => $format_time($diff),
            'status' => 'increased',
            'message' => "$day_name's idle time increased by " . $format_time($diff),
        ];
    }

    public function get_last_week_total_active_hours()
    {
        try {
            $employee_id = $this->session->userdata('employee_id');
            $user_id = $this->session->userdata('employee_org_id');

            if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
                return null;
            }
            // Calculate last week's Monday and Sunday
            $today = new DateTime();
            $last_monday = (clone $today)->modify('monday last week');
            $last_sunday = (clone $last_monday)->modify('+6 days');

            // Query active time from the database
            $this->db->select('total_active_time')
                ->from('time_logs')
                ->where('employee_id', $employee_id)
                ->where('user_id', $user_id)
                ->where('log_date >=', $last_monday->format('Y-m-d'))
                ->where('log_date <=', $last_sunday->format('Y-m-d'));

            $query = $this->db->get();
            $result = $query->result_array();

            $total_seconds = 0;

            // Sum all active time in seconds
            foreach ($result as $row) {
                $active_time = $row['total_active_time'];
                list($hours, $minutes, $seconds) = explode(':', $active_time);
                $total_seconds += ($hours * 3600) + ($minutes * 60) + $seconds;
            }

            // Convert total seconds to hours (decimal format)
            $total_hours = round($total_seconds / 3600, 2); // 3600 seconds = 1 hour

            // Store all values in variables
            $status = 'success';
            $message = 'Last week total active hours calculated successfully';
            $date_range = $last_monday->format('Y-m-d') . ' to ' . $last_sunday->format('Y-m-d');
            $response_data = [
                'status' => $status,
                'message' => $message,
                'date_range' => $date_range,
                'total_active_hours' => $total_hours
            ];



            return $response_data;
        } catch (Exception $e) {
            log_message('error', 'get_last_week_total_active_hours failed: ' . $e->getMessage());

            // Store error values in variables
            $error_status = 'error';
            $error_message = 'Failed to calculate last week active hours';
            $error_details = $e->getMessage();
            $error_response = [
                'status' => $error_status,
                'message' => $error_message,
                'error_details' => $error_details
            ];

            $error_output = $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode($error_response));

            return $error_output;
        }
    }

private function get_this_week_work_time_data($from_date, $to_date)
{
    $employee_id = $this->session->userdata('employee_id');
    $user_id = $this->session->userdata('employee_org_id');

    if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
        return [];
    }

        // Set date range to today if empty
        if (empty($from_date) || empty($to_date)) {
            $from_date = date('Y-m-d', strtotime('monday this week'));
            $to_date = date('Y-m-d', strtotime('sunday this week'));
        }

    // Get time logs for the week
    $this->db->select('log_date, start_time, end_time')
        ->from('time_logs')
        ->where('employee_id', $employee_id)
        ->where('user_id', $user_id)
        ->where('log_date >=', $from_date)
        ->where('log_date <=', $to_date)
        ->order_by('log_date', 'ASC');

    $query = $this->db->get();
    $logs = $query->result_array();

    $total_seconds = 0;
    $daily_hours = [];
    $days_with_data = 0;

    foreach ($logs as $log) {
        if (!isset($log['start_time'], $log['end_time']) || empty($log['start_time']) || empty($log['end_time'])) {
            continue;
        }

        $start = new DateTime($log['start_time']);
        $end = new DateTime($log['end_time']);

        $interval = $start->diff($end);
        $seconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

        $total_seconds += $seconds;
        $daily_hours[$log['log_date']] = sprintf('%02d:%02d', floor($seconds / 3600), floor(($seconds % 3600) / 60));
        $days_with_data++;
    }

    // Total work time in hours and minutes
    $hours = floor($total_seconds / 3600);
    $minutes = floor(($total_seconds % 3600) / 60);

    // Convert total work time to minutes
    $total_minutes = floor($total_seconds / 60);

    // Estimated keystrokes and mouse activity
    $estimated_keystrokes = $total_minutes * 40;
    $estimated_mouse_activity = $total_minutes * 20;

    // Fetch actual keystroke and mouse movement from employee_activity
    $this->db->select('SUM(total_keystrokes) AS sum_keystrokes, SUM(total_mouse_movement) AS sum_mouse')
        ->from('Employee_Activity')
        ->where('employee_id', $employee_id)
        ->where('user_id', $user_id)
        ->where('DATE(created_at) >=', $from_date)
        ->where('DATE(created_at) <=', $to_date);

    $activity_result = $this->db->get()->row_array();

    $actual_keystrokes = isset($activity_result['sum_keystrokes']) ? (int)$activity_result['sum_keystrokes'] : 0;
    $actual_mouse_activity = isset($activity_result['sum_mouse']) ? (int)$activity_result['sum_mouse'] : 0;

    // Percentage calculations
    $keystroke_percentage = $estimated_keystrokes > 0 ? round(($actual_keystrokes / $estimated_keystrokes) * 100, 2) : 0;
    $mouse_activity_percentage = $estimated_mouse_activity > 0 ? round(($actual_mouse_activity / $estimated_mouse_activity) * 100, 2) : 0;

    $output = [
        'date_range' => $from_date . ' to ' . $to_date,
        'total_work_time' => sprintf('%02d:%02d', $hours, $minutes),

        // Estimated values based on time
        'estimated_keystrokes_in_the_period' => $estimated_keystrokes,
        'estimated_mouse_activity_in_the_period' => $estimated_mouse_activity,

        // Actual values from employee_activity table
        'actual_keystrokes_in_the_period' => $actual_keystrokes,
        'actual_mouse_activity_in_the_period' => $actual_mouse_activity,

        // Percentages
        'keystroke_percentage' => $keystroke_percentage, // %
        'mouse_activity_percentage' => $mouse_activity_percentage, // %

        'days_with_data' => $days_with_data,
        'total_days_in_week' => (new DateTime($to_date))->diff(new DateTime($from_date))->days + 1,
        'daily_breakdown' => $daily_hours
    ];

    return $output;
}




}
