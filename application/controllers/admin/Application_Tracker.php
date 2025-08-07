<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_Tracker extends Home_Controller {

       public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
         $this->load->library('session');
    }
     public function index()
    {
        require_feature(13);
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'application_tracker';
        $employee_id =  $this->input->post('employee_id', true) ?? $this->get_random_employee_id();
        $date =  $this->input->post('date', true) ?? date('Y-m-d');
        $data['employees'] = $this->list_employees_by_user();
        $data['employee_id'] = $employee_id;
        $data['date'] = $date;
        $data['can_edit'] = $this->auth_model->get_permission(2);
        $response_data = $this->get_application_usage_logs($employee_id, $date);
        $data['response_data'] = $response_data;
        $data['main_content'] = $this->load->view('admin/application_tracker', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }


   public function get_application_usage_logs($employee_id, $date)
    {
        // --- Get inputs (POST or GET) ---
        $this->$employee_id = $employee_id;
        $this->$date = $date;
        $user_id     = $this->input->get_post('user_id')?? $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');

        $start_date_raw  = $this->input->get_post('start_date');
        $end_date_raw    = $this->input->get_post('end_date');
        $application_name= $this->input->get_post('application_name');

        $limit  = (int) $this->input->get_post('limit', TRUE) ?: 2000;
        $offset = (int) $this->input->get_post('offset', TRUE) ?: 0;

        $debug = (int) $this->input->get_post('debug', TRUE);

        // --- Basic validation for employee/user ---
        if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid or missing employee_id or user_id'
                ]));
        }

        // --- Normalize / parse date filters ---
        $start_date = $this->normalize_date_filter($start_date_raw, 'start');
        $end_date   = $this->normalize_date_filter($end_date_raw, 'end');

        // If only one boundary present ensure comparators are proper
        // Build main query using Query Builder
        $this->db->start_cache();
        $this->db->select('log_id, employee_id, user_id, log_date, start_time, end_time, duration_seconds, application_name, window_title, website_url, created_at, updated_at');
        $this->db->from('application_usage_logs');
        $this->db->where('employee_id', (int)$employee_id);
        $this->db->where('user_id', (int)$user_id);
        // $this->db->where('log_date', (int)$date);

        // Apply date filter
        if (!empty($date)) {
            $this->db->where('log_date', $date);
        } else {
            if ($start_date !== null) {
                $this->db->where('log_date >=', $start_date);
            }
            if ($end_date !== null) {
                $this->db->where('log_date <=', $end_date);
            }
        }

        if (!empty($application_name)) {
            // Partial match, case-insensitive depending on DB collation; optionally force lower
            $this->db->like('application_name', $application_name);
        }
        $this->db->stop_cache();

        // Debug - show compiled SQL and exit if requested
        if ($debug === 1) {
            $sql = $this->db->get_compiled_select();
            $this->db->flush_cache();
            echo $sql; exit;
        }

        // Get total matching rows (without limit) for pagination metadata
        $count_query = $this->db->select('COUNT(*) AS total')->get();
        $total_rows = (int) $count_query->row()->total;

        // Add ordering, limit, offset for actual data fetch
        $this->db->order_by('start_time', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        $logs = $query->result_array();

        // Clear cached where/selects
        $this->db->flush_cache();

        // If duration_seconds missing for some rows, compute from start_time and end_time
        foreach ($logs as &$r) {
            if ((int)$r['duration_seconds'] <= 0) {
                $r['duration_seconds'] = $this->compute_duration_seconds($r['start_time'], $r['end_time']);
            }
        }
        unset($r);

        // Aggregate calculations
        $total_usage_seconds = 0;
        $app_breakdown = [];
        $daily_breakdown = [];

        foreach ($logs as $log) {
            $duration = (int)$log['duration_seconds'];
            $app_name = isset($log['application_name']) ? trim($log['application_name']) : 'Unknown';
            $log_date = isset($log['log_date']) ? $log['log_date'] : null;

            // total
            $total_usage_seconds += $duration;

            // per-app (case-normalized to avoid Chrome vs chrome splits — tweak as needed)
            $key = $app_name; // or strtolower($app_name) if you want normalized keys
            if (!isset($app_breakdown[$key])) $app_breakdown[$key] = 0;
            $app_breakdown[$key] += $duration;

            // daily breakdown
            if ($log_date) {
                if (!isset($daily_breakdown[$log_date])) $daily_breakdown[$log_date] = [];
                if (!isset($daily_breakdown[$log_date][$key])) $daily_breakdown[$log_date][$key] = 0;
                $daily_breakdown[$log_date][$key] += $duration;
            }
        }

        // Format output
        $response = [
            'status' => 'success',
            'meta' => [
                'total_rows' => $total_rows,
                'limit' => $limit,
                'offset' => $offset,
            ],
            'data' => [
                'total_logs_returned' => count($logs),
                'total_usage_time' => $this->seconds_to_time($total_usage_seconds),
                'raw_total_usage_seconds' => $total_usage_seconds,
                'top_applications' => $this->sort_and_format_apps($app_breakdown),
                'daily_breakdown' => $this->format_daily_breakdown($daily_breakdown),
                'raw_logs' => $logs
            ]
        ];

       return $response; // return array to caller

    }

    /**
     * Normalize incoming date filters: accepts date or datetime string.
     * Returns 'YYYY-MM-DD' (for comparing to log_date) or null.
     */
    private function normalize_date_filter($input, $which = 'start')
    {
        if (empty($input)) return null;

        // Try strtotime; supports 'YYYY-MM-DD' and 'YYYY-MM-DD HH:MM:SS'
        $ts = strtotime($input);
        if ($ts === false) {
            return null; // invalid date — caller treats as no filter
        }
        // For log_date compare, return only date part
        return date('Y-m-d', $ts);
    }

    /**
     * Compute seconds difference between start and end datetimes.
     * If invalid values, return 0.
     */
    private function compute_duration_seconds($start_time, $end_time)
    {
        if (empty($start_time) || empty($end_time)) return 0;
        $s = strtotime($start_time);
        $e = strtotime($end_time);
        if ($s === false || $e === false || $e < $s) return 0;
        return $e - $s;
    }

    /**
     * Convert seconds to H:i:s string.
     */
    private function seconds_to_time($seconds)
    {
        $seconds = max(0, (int)$seconds);
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    /**
     * Sort apps by usage (desc) and format durations as H:i:s.
     * Returns keyed array: [ 'AppName' => 'HH:MM:SS', ... ]
     */
    private function sort_and_format_apps($app_breakdown)
    {
        arsort($app_breakdown);
        $formatted = [];
        foreach ($app_breakdown as $app => $seconds) {
            $formatted[$app] = $this->seconds_to_time($seconds);
        }
        return $formatted;
    }

    /**
     * Format daily breakdown durations to H:i:s (keeps structure)
     */
    private function format_daily_breakdown($daily_breakdown)
    {
        $out = [];
        foreach ($daily_breakdown as $date => $apps) {
            $out[$date] = [];
            foreach ($apps as $app => $seconds) {
                $out[$date][$app] = $this->seconds_to_time($seconds);
            }
        }
        return $out;
    }


    public function list_employees_by_user()
    {
        // Get user_id from session first
        $user_id = $this->session->userdata('id');

        // If not found in session, try to get from header
        if (empty($user_id)) {
            $user_id = $this->input->get_request_header('user_id', TRUE);
        }

        // Validate user ID
        if (empty($user_id) || !is_numeric($user_id)) {
            return []; // Return empty array on invalid user_id
        }

        // Get employees from the employees table matching the provided user_id
        $employees = $this->db
            ->select('id, name, email, country, role_id')
            ->where('user_id', $user_id)
            ->get('employees')
            ->result_array();

        // Filter out CEOs using your helper
        $filtered = array_filter($employees, function ($emp) {
            return !is_CEO($emp['role_id']);
        });

        // Reindex the array
        $filtered = array_values($filtered);

        return $filtered;
    }
    public function get_random_employee_id()
    {
        $employees = $this->list_employees_by_user();

        if (empty($employees)) {
            return null; // No employee found
        }

        // Get a random employee
        $random_employee = $employees[array_rand($employees)];

        return $random_employee['id'];
    }

}