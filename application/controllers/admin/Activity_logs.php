<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_logs extends Home_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        require_feature(1);
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Activity Log Admin';
        $data['can_edit'] = $this->auth_model->get_permission(1);
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/activityLog', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }
    public function get_employee_index()
    {
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        $data = array();
        $data['employee_id'] = $this->session->userdata('employee_id');
        $data['employee_org_id'] = $this->session->userdata('employee_org_id');
        $data['page_title'] = 'Activity Log';
        $data['main_content'] = $this->load->view('admin/employee/activity_log', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }
    // public function check_activity_status()
    // {
    //     $employee_id = $this->input->get('employee_id', true);
    //     $user_id = $this->session->userdata('user_id') ?? $this->input->get('user_id', true);
    
    //     if (empty($employee_id) || empty($user_id)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => false, 'message' => 'Employee ID and User ID are required']));
    //     }
    
    //     // Get employee data (note: 'id' is the employee_id column)
    //     $employee = $this->db->get_where('employees', ['id' => $employee_id])->row();
    //     if (!$employee) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(404)
    //             ->set_output(json_encode(['status' => false, 'message' => 'Employee not found']));
    //     }
    
    //     $settings = null;
    //     $interval = 1;
    //     $mouse_threshold = 20;
    //     $keystroke_threshold = 40;
    
    //     // Fetch settings based on settings_status
    //     if ($employee->settings_status == 2) {
    //         $this->db->where('employee_id', $employee_id);
    //         $this->db->where('user_id', $user_id);
    //         $settings = $this->db->get('organization_exception_settings')->row();
    //     } else if ($employee->settings_status == 1) {
    //         $this->db->where('user_id', $user_id);
    //         $settings = $this->db->get('org_settings')->row();
    //     }
    
    //     // Set interval and thresholds based on the correct column names
    //     if ($settings) {
    //         $interval = (int) ($settings->timecards_time_interval ?? 1); // 1,2,5,10
    //         $mouse_threshold = (int) ($settings->mouse_move_threshold ?? 20);
    //         $keystroke_threshold = (int) ($settings->key_stroke_threshold ?? 40);
    //     }
    
    //     // Calculate expected values
    //     $expected_mouse = $mouse_threshold * $interval;
    //     $expected_keys = $keystroke_threshold * $interval;
    
    //     $start_time = date('Y-m-d H:i:s', strtotime("-{$interval} minutes"));
    
    //     $this->db->select_sum('mouse_clicks');
    //     $this->db->select_sum('keystrokes');
    //     $this->db->where('employee_id', $employee_id);
    //     $this->db->where('timestamp >=', $start_time);
    //     $activity = $this->db->get('activity_logs')->row();
    
    //     $mouse = (int)($activity->mouse_clicks ?? 0);
    //     $keys = (int)($activity->keystrokes ?? 0);
    
    //     // Calculate flexible % of activity
    //     $mouse_pct = ($expected_mouse > 0) ? min(100, round(($mouse / $expected_mouse) * 100)) : 0;
    //     $keys_pct = ($expected_keys > 0) ? min(100, round(($keys / $expected_keys) * 100)) : 0;
    //     $overall_pct = round(($mouse_pct * 0.5) + ($keys_pct * 0.5));
    //     $is_active = $overall_pct >= 60;
    
    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode([
    //             'status' => true,
    //             'is_idle' => !$is_active,
    //             'mouse_clicks' => $mouse,
    //             'keystrokes' => $keys,
    //             'expected_mouse_clicks' => $expected_mouse,
    //             'expected_keystrokes' => $expected_keys,
    //             'mouse_activity_percent' => $mouse_pct,
    //             'keystroke_activity_percent' => $keys_pct,
    //             'overall_activity_percent' => $overall_pct,
    //             'interval_minutes' => $interval,
    //             'settings_source' => $employee->settings_status == 2 ? 'organization_exception_settings' : 'org_settings'
    //         ]));
    // }
    public function check_activity_status()
{
    $employee_id = $this->input->get('employee_id', true);
    $user_id = $this->session->userdata('user_id') ?? $this->input->get('user_id', true);

    if (empty($employee_id) || empty($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Employee ID and User ID are required']));
    }

    // Get employee data
    $employee = $this->db->get_where('employees', ['id' => $employee_id])->row();
    if (!$employee) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'Employee not found']));
    }

    // Defaults
    $interval = 1; // Default interval
    $mouse_threshold = 10;
    $keystroke_threshold = 25;

    // Fetch settings
    if ($employee->settings_status == 2) {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $user_id);
        $settings = $this->db->get('organization_exception_settings')->row();
    } else {
        $this->db->where('user_id', $user_id);
        $settings = $this->db->get('org_settings')->row();
    }

    if ($settings) {
        $dynamic_interval = (int)($settings->screenshot_time_interval ?? 1);
        $valid_intervals = [1, 2, 5, 10];
        $interval = $this->get_closest_interval($dynamic_interval, $valid_intervals);

        $mouse_threshold = (int)($settings->mouse_move_threshold ?? 10);
        $keystroke_threshold = (int)($settings->key_stroke_threshold ?? 25);
    }

    // Use 70% of expected values
    $expected_mouse = round($mouse_threshold * $interval * 0.7);
    $expected_keys = round($keystroke_threshold * $interval * 0.7);

    $this->db->select('timestamp, mouse_clicks, keystrokes');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('timestamp >=', date('Y-m-d H:i:s', strtotime("-{$interval} minutes")));
    $activity_logs = $this->db->get('activity_logs')->result();

    $total_mouse_clicks = 0;
    $total_keystrokes = 0;
    $interval_activity = [];

    foreach ($activity_logs as $log) {
        $interval_start = floor(strtotime($log->timestamp) / ($interval * 60)) * ($interval * 60);

        if (!isset($interval_activity[$interval_start])) {
            $interval_activity[$interval_start] = ['mouse_clicks' => 0, 'keystrokes' => 0];
        }

        $interval_activity[$interval_start]['mouse_clicks'] += $log->mouse_clicks;
        $interval_activity[$interval_start]['keystrokes'] += $log->keystrokes;

        $total_mouse_clicks += $log->mouse_clicks;
        $total_keystrokes += $log->keystrokes;
    }

    $total_pct_mouse = 0;
    $total_pct_keys = 0;
    $overall_pct = 0;
    $interval_count = count($interval_activity);

    foreach ($interval_activity as $activity) {
        $interval_mouse_pct = ($expected_mouse > 0) ? min(100, round(($activity['mouse_clicks'] / $expected_mouse) * 100)) : 0;
        $interval_keys_pct = ($expected_keys > 0) ? min(100, round(($activity['keystrokes'] / $expected_keys) * 100)) : 0;

        $total_pct_mouse += $interval_mouse_pct;
        $total_pct_keys += $interval_keys_pct;
    }

    if ($interval_count > 0) {
        $total_pct_mouse /= $interval_count;
        $total_pct_keys /= $interval_count;
        $overall_pct = round(($total_pct_mouse + $total_pct_keys) / 2);
    }

    $is_active = $overall_pct >= 60;

    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'status' => true,
            'is_idle' => !$is_active,
            'total_mouse_clicks' => $total_mouse_clicks,
            'total_keystrokes' => $total_keystrokes,
            'expected_mouse_clicks' => $expected_mouse,
            'expected_keystrokes' => $expected_keys,
            'mouse_activity_percent' => $total_pct_mouse,
            'keystroke_activity_percent' => $total_pct_keys,
            'overall_activity_percent' => $overall_pct,
            'interval_minutes' => $interval,
            'settings_source' => $employee->settings_status == 2 ? 'organization_exception_settings' : 'org_settings'
        ]));
}

private function get_closest_interval($dynamic_interval, $valid_intervals)
{
    sort($valid_intervals);
    $closest = $valid_intervals[0];
    foreach ($valid_intervals as $valid_interval) {
        if (abs($dynamic_interval - $valid_interval) < abs($dynamic_interval - $closest)) {
            $closest = $valid_interval;
        }
    }
    return $closest;
}

public function get_activity()
{
        // Get inputs from GET request (query parameters)
    $employee_id = $this->input->get('employee_id') ?? $this->session->userdata('employee_id');
    $user_id = $this->session->userdata('employee_org_id')??$this->session->userdata('id'); // fallback for Postman or URL query params
    $date = $this->input->get('date'); // Get the date from the query parameter (if provided)

    // If no date is provided, use today's date
    if (empty($date)) {
        $date = date('Y-m-d'); // Default to today
    }

    // Validate inputs
    if (empty($employee_id) || empty($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Missing employee_id or user_id'
            ]));
    }

    // Check if the combination of user_id and employee_id exists in the screenshots table with the given date and non-null screenshot_id
    $this->db->select('screenshot_id'); // Selecting screenshot_id to check if the record exists
    $this->db->from('screenshots');
    $this->db->where('user_id', $user_id); // Matching user_id
    $this->db->where('employee_id', $employee_id); // Matching employee_id
    $this->db->where('DATE(created_at)', $date); // Ensure the date is the provided or default date
    $this->db->where('screenshot_id IS NOT NULL'); // Check for non-null screenshot_id
    $query = $this->db->get();

    // If no record exists for this user_id and employee_id combination, return an error
    if ($query->num_rows() == 0) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Invalid user_id or employee_id, or no data available for the selected date'
            ]));
    }

    // Query the screenshots table using user_id and employee_id to get data with non-null screenshot_id
    $this->db->select('user_id, employee_id, overall_activity_percent, is_active, screenshot_id,created_at');
    $this->db->from('screenshots');
    $this->db->where('user_id', $user_id);
    $this->db->where('employee_id', $employee_id);
    $this->db->where('DATE(created_at)', $date); // Ensure the date is the provided or default date
    $this->db->where('screenshot_id IS NOT NULL'); // Ensure screenshot_id is not null
    $query = $this->db->get();
    $result = $query->result_array();

        $filtered_result = [];
        $previous_status = null;

        foreach ($result as $row) {
            if ($row['is_active'] !== $previous_status) {
                $filtered_result[] = $row;
                $previous_status = $row['is_active'];
            }
        }

        // Return response
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data' => $filtered_result
            ]));
    }

public function get_employee_activity()
{
        // Get parameters from request
    $employee_id = $this->input->get('employee_id') ?? $this->session->userdata('employee_id');
    $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id'); // fallback for Postman or URL query params
    $date = $this->input->get('date') ?? date('Y-m-d');
    $from_time = $this->input->get('from_time') ?? '00:00:00';
    $to_time = $this->input->get('to_time') ?? '23:59:59';

    // Convert to datetime format
    $start_datetime = $date . ' ' . $from_time;
    $end_datetime = $date . ' ' . $to_time;

    // Query to get sum of keystrokes and mouse movements for the period
    $this->db->select('
        SUM(total_keystrokes) as total_keystrokes,
        SUM(total_mouse_movement) as total_mouse_movement,
        DATE_FORMAT(created_at, "%H:%i") as time,
        created_at
    ');
    $this->db->from('Employee_Activity');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('created_at >=', $start_datetime);
    $this->db->where('created_at <=', $end_datetime);
    $this->db->group_by('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'); // Group by minute
    $this->db->order_by('created_at');
    
    $query = $this->db->get();
    $result = $query->result_array();

    // Calculate totals for the entire period
    $total_keystrokes = 0;
    $total_mouse_movement = 0;
    foreach ($result as $row) {
        $total_keystrokes += $row['total_keystrokes'];
        $total_mouse_movement += $row['total_mouse_movement'];
    }

    // Return the data
    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => !empty($result),
            'data' => $result,
            'totals' => [
                'keystrokes' => $total_keystrokes,
                'mouse_movement' => $total_mouse_movement
            ],
            'message' => empty($result) ? 'No activity data available for the selected time range.' : ''
        ]));
}
public function Time_Cards()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        require_feature(2);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'employee_activity';
        $data['can_edit'] = $this->auth_model->get_permission(2);
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/time_cards', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

}