<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_logs extends Home_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index(){
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        $data = array();
        $data['page_title'] = 'Activity Log';
        $data['main_content'] = $this->load->view('admin/employee/activity_log', $data, TRUE);
        $this->load->view('admin/index', $data);
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


    
}
