<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_logs extends Home_Controller { 

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function get_time_logs() {
        // Get inputs from session or GET request
        $employee_id = $this->input->get('employee_id') ?? $this->session->userdata('employee_id');
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
        $date = $this->input->get('date') ?: date('Y-m-d'); // Default to today if not provided
    
        // Validate inputs
        if (empty($employee_id) || empty($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Missing employee_id or user_id'
                ]));
        }
    
        // Check if logs exist for the given date
        $this->db->select('log_id');
        $this->db->from('time_logs');
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date);

        $exists = $this->db->get();
    
        if ($exists->num_rows() == 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'No time logs found for the specified user_id, employee_id, and date.'
                ]));
        }
    
        // Fetch time log details
        $this->db->select('log_id, employee_id, user_id, log_date, start_time, end_time, total_active_time, total_idle_time, created_at, updated_at');
        $this->db->from('time_logs');
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date);
        $query = $this->db->get();
    
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data' => $query->result_array()
            ]));
    }
    

    public function checkExistingTimelog() 
    {
        try {
            // Strictly get identifiers from headers only
            $employee_id = $this->input->get_request_header('employee_id', TRUE);
            $user_id = $this->input->get_request_header('user_id', TRUE);
            $date = $this->input->get_request_header('date', TRUE);
    
            // Validate required headers
            if (empty($employee_id) || empty($user_id)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Missing required headers',
                        'required_headers' => [
                            'employee_id' => true,
                            'user_id' => true
                        ],
                        'provided_headers' => [
                            'employee_id' => $employee_id,
                            'user_id' => $user_id,
                            'date' => $date
                        ]
                    ]));
            }
    
            // Set default date if not provided
            $date = empty($date) ? date('Y-m-d') : $date;
    
            // Build query without status field
            $this->db->select('log_id, employee_id, user_id, log_date, start_time, 
                             end_time, total_active_time, total_idle_time, 
                             created_at, updated_at');
            $this->db->from('time_logs');
            $this->db->where('user_id', $user_id);
            $this->db->where('employee_id', $employee_id);
            $this->db->where('DATE(log_date)', $date);
            
            $query = $this->db->get();
            
            if (!$query) {
                throw new Exception('Database query failed: ' . $this->db->error()['message']);
            }
            
            $result = $query->row_array();
    
            if (empty($result)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'No time log found',
                        'search_parameters' => [
                            'employee_id' => $employee_id,
                            'user_id' => $user_id,
                            'date' => $date
                        ]
                    ]));
            }
    
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'data' => $result
                ]));
    
        } catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Internal Server Error',
                    'error' => $e->getMessage()
                ]));
        }
    }

/**
 * Helper method to determine request parameter sources
 */
private function getRequestSource($params)
{
    $sources = [];
    
    foreach ($params as $key => $value) {
        if ($this->input->get_request_header($key, TRUE) !== null) {
            $sources[$key] = 'header';
        } elseif ($this->session->userdata($key) !== null || 
                 ($key === 'user_id' && ($this->session->userdata('employee_org_id') !== null || 
                                        $this->session->userdata('id') !== null))) {
            $sources[$key] = 'session';
        } elseif ($this->input->get_post($key) !== null) {
            $sources[$key] = 'request';
        } else {
            $sources[$key] = 'default';
        }
    }
    
    return $sources;
}
    
    
    public function updateTimelog() {
        $employee_id = $this->session->userdata('employee_id') ?? $this->input->post('employee_id');
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id') ;
        $date = $this->input->post('date') ?? date('Y-m-d');
    
        // New values to be updated
        $end_time = $this->input->post('end_time') ;
        $total_active_time = $this->input->post('total_active_time');
        $total_idle_time = $this->input->post('total_idle_time');
        $status = $this->input->post('status');
        $updated_at = date('Y-m-d H:i:s');
    
        // Validate required fields
        if (empty($employee_id) || empty($user_id) || empty($end_time)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Missing required parameters (employee_id, user_id, end_time)'
                ]));
        }
    
        // Check if timelog exists for this user, employee, and date
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date);
        $exists = $this->db->get('time_logs')->num_rows();
    
        if ($exists == 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'No existing timelog found for this user on the given date.'
                ]));
        }
    
        // Update the record
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date);
        $this->db->update('time_logs', [
            'end_time' => $end_time,
            'total_active_time' => $total_active_time,
            'total_idle_time' => $total_idle_time,
            'status' => $status,
            'updated_at' => $updated_at
        ]);
    
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'message' => 'Timelog updated successfully.'
            ]));
    }
    


   
    public function update_timelog()
    {
        // Validate request method
        if ($this->input->server('REQUEST_METHOD') !== 'PUT') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Only PUT requests are allowed"
                ]));
        }
    
        // Get and validate headers
        $required_headers = [
            'user_id' => 'user_id',
            'employee_id' => 'employee_id',
            'log_date' => 'log_date',
            'end_time' => 'end_time',
            'total_active_time' => 'total_active_time',
            'total_idle_time' => 'total_idle_time'
        ];
    
        $headers = [];
        $missing_fields = [];
    
        foreach ($required_headers as $field => $label) {
            $value = $this->input->get_request_header($field, TRUE);
            if (empty($value)) {
                $missing_fields[] = $label;
            }
            $headers[$field] = $value;
        }
    
        if (!empty($missing_fields)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing required headers: " . implode(', ', $missing_fields)
                ]));
        }
    
        // Validate and format timestamp
        try {
            $end_datetime = (new DateTime($headers['end_time']))->format('Y-m-d H:i:s');
            
            // Get existing log to validate end time is after start time
            $this->db->where('employee_id', $headers['employee_id']);
            $this->db->where('user_id', $headers['user_id']);
            $this->db->where('log_date', $headers['log_date']);
            $existing = $this->db->get('time_logs')->row();
            
            if (!$existing) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode([
                        "status" => "error",
                        "message" => "Time log not found for update"
                    ]));
            }
            
            if (strtotime($end_datetime) <= strtotime($existing->start_time)) {
                throw new Exception("End time must be after start time");
            }
    
            // Function to convert various time formats to HH:MM:SS
            function convertToHHMMSS($time) {
                if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
                    return $time;
                }
    
                if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $time)) {
                    return str_replace('-', ':', $time);
                }
    
                if (is_numeric($time)) {
                    $hours = (float)$time;
                    $total_seconds = (int)($hours * 3600);
                    
                    $hours = floor($total_seconds / 3600);
                    $minutes = floor(($total_seconds % 3600) / 60);
                    $seconds = $total_seconds % 60;
    
                    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                }
    
                throw new Exception("Invalid time format");
            }
    
            $active_time = convertToHHMMSS($headers['total_active_time']);
            $idle_time = convertToHHMMSS($headers['total_idle_time']);
    
        } catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid time data: " . $e->getMessage()
                ]));
        }
    
        // Prepare update data
        $current_time = date('Y-m-d H:i:s');
        $data = [
            'end_time' => $end_datetime,
            'total_active_time' => $active_time,
            'total_idle_time' => $idle_time,
            'updated_at' => $current_time
        ];
    
        // Start database transaction
        $this->db->trans_start();
        
        $this->db->where('user_id', $headers['user_id']);
        $this->db->where('employee_id', $headers['employee_id']);
        $this->db->where('log_date', $headers['log_date']);
        $updated = $this->db->update('time_logs', $data);
        
        $this->db->trans_complete();
    
        if (!$this->db->trans_status() || !$updated) {
            $error = $this->db->error();
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Failed to update time log",
                    "error" => $error['message'] ?? 'Unknown database error'
                ]));
        }
    
        // Get the updated record
        $this->db->where('employee_id', $headers['employee_id']);
        $this->db->where('user_id', $headers['user_id']);
        $this->db->where('log_date', $headers['log_date']);
        $updated_record = $this->db->get('time_logs')->row();
    
        // Log success
        log_message('info', "Time log updated for employee {$headers['employee_id']} on date {$headers['log_date']}");
    
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success",
                "message" => "Time log updated successfully",
                "data" => [
                    "original_data" => $existing,
                    "updated_data" => $updated_record,
                    "changes_applied" => $data
                ]
            ]));
    }
    


    public function get_employee_by_email()
    {
        // Get email from multiple possible sources (in order of priority):
        // 1. Request headers
        // 2. Session data
        // 3. POST data
        $email = $this->input->get_request_header('email', TRUE) 
                 ?? $this->session->userdata('email') 
                 ?? $this->input->post('email');
        
        // 1. Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid email address required',
                    'request_sources_checked' => [
                        'header' => $this->input->get_request_header('email', TRUE) !== null,
                        'session' => $this->session->userdata('email') !== null,
                        'post' => $this->input->post('email') !== null
                    ]
                ]));
        }
    
        // 2. Get employee details by email
        $employee = $this->db
            ->select('*') // Select all fields or specify the ones you need
            ->where('email', $email)
            ->get('employees')
            ->row_array();
    
        // 3. Return response
        if ($employee) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'data' => [
                        'employee' => $employee,
                        'request_source' => $this->get_email_request_source()
                    ]
                ]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'No employee found with the given email',
                    'searched_email' => $email,
                    'request_source' => $this->get_email_request_source()
                ]));
        }
    }
    
    /**
     * Helper method to determine where the email came from
     */
    private function get_email_request_source()
    {
        if ($this->input->get_request_header('email', TRUE) !== null) {
            return 'header';
        }
        if ($this->session->userdata('email') !== null) {
            return 'session';
        }
        if ($this->input->post('email') !== null) {
            return 'post';
        }
        return 'unknown';
    }

    public function get_server_time()
{
    // Set default timezone (if not already set in config)
    date_default_timezone_set('UTC'); // or your preferred timezone
    
    // Get current server time
    $server_time = date('Y-m-d H:i:s');
    $timestamp = time();
    $timezone = date_default_timezone_get();
    
    // Prepare response data
    $response = [
        'status' => 'success',
        'data' => [
            'datetime' => $server_time,
            'timestamp' => $timestamp,
            'timezone' => $timezone,
            'iso8601' => date('c'), // ISO 8601 format
            'rfc2822' => date('r'), // RFC 2822 format
            'milliseconds' => round(microtime(true) * 1000)
        ],
        'server_info' => [
            'software' => $_SERVER['SERVER_SOFTWARE'] ?? null,
            'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? null
        ]
    ];
    
    // Return JSON response
    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($response));
}


public function save_activity()
{
    $user_id = $this->input->get_request_header('user_id', TRUE);
    $employee_id = $this->input->get_request_header('employee_id', TRUE);
    $mouse = $this->input->get_request_header('total_mouse_movement', TRUE);
    $keys = $this->input->get_request_header('total_keystrokes', TRUE);

    // Basic validation
    if(empty($user_id) || empty($employee_id) || !is_numeric($mouse) || !is_numeric($keys)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Missing or invalid data',
                'required_fields' => [
                    'user_id (got: '.$user_id.')',
                    'employee_id (got: '.$employee_id.')',
                    'total_mouse_movement (numeric, got: '.$mouse.')',
                    'total_keystrokes (numeric, got: '.$keys.')'
                ]
            ]));
    }

    // Prepare data
    $data = [
        'user_id' => $user_id,
        'employee_id' => $employee_id,
        'total_mouse_movement' => $mouse,
        'total_keystrokes' => $keys,
        'created_at' => get_user_datetime_only($user_id)
    ];

    // Insert to database
    $this->db->insert('Employee_Activity', $data);

    // Check if inserted
    if($this->db->affected_rows() > 0) {
        $activity_id = $this->db->insert_id();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Activity saved successfully',
                'activity_id' => $activity_id,
                'data' => $data
            ]));
    } else {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to save activity',
                'database_error' => $this->db->error()
            ]));
    }
}






public function store_Time_Log()
{
    // Get all headers
    $employee_id = $this->input->get_request_header('employee_id', TRUE);
    $user_id = $this->input->get_request_header('user_id', TRUE);
    $log_date = $this->input->get_request_header('log_date', TRUE);
    $start_time = $this->input->get_request_header('start_time', TRUE);
    $end_time = $this->input->get_request_header('end_time', TRUE);
    $total_active_time = $this->input->get_request_header('total_active_time', TRUE);
    $total_idle_time = $this->input->get_request_header('total_idle_time', TRUE);

    // Basic validation (similar to file1 style)
    if(empty($employee_id) || empty($user_id) || empty($log_date) || 
       empty($start_time) || empty($end_time) || 
       empty($total_active_time) || empty($total_idle_time)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Missing required data',
                'required_fields' => [
                    'employee_id (got: '.$employee_id.')',
                    'user_id (got: '.$user_id.')',
                    'log_date (got: '.$log_date.')',
                    'start_time (got: '.$start_time.')',
                    'end_time (got: '.$end_time.')',
                    'total_active_time (got: '.$total_active_time.')',
                    'total_idle_time (got: '.$total_idle_time.')'
                ]
            ]));
    }

    // Validate numeric IDs
    if(!is_numeric($employee_id) || !is_numeric($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid ID format',
                'invalid_fields' => [
                    'employee_id (must be numeric, got: '.$employee_id.')',
                    'user_id (must be numeric, got: '.$user_id.')'
                ]
            ]));
    }

    // Get current user datetime
    $current_datetime = get_user_datetime_only($user_id);
    $current_date = date('Y-m-d', strtotime($current_datetime));
    $current_time = date('H:i:s', strtotime($current_datetime));

    // Check if logging for today
    if ($log_date == $current_date) {
        // For today's log, start_time must match current user time
        if ($start_time != $current_time) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid start time for today',
                    'details' => [
                        'expected_start_time' => $current_time,
                        'received_start_time' => $start_time,
                        'user_timezone' => 'Based on user_id: '.$user_id
                    ]
                ]));
        }
    }

    // Prepare data
    $data = [
        'employee_id' => $employee_id,
        'user_id' => $user_id,
        'log_date' => $log_date,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'total_active_time' => $total_active_time,
        'total_idle_time' => $total_idle_time,
        'created_at' => $current_datetime,
        'updated_at' => $current_datetime
    ];

    // Insert to database
    $this->db->insert('time_logs', $data);

    // Check if inserted
    if($this->db->affected_rows() > 0) {
        $log_id = $this->db->insert_id();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Time log stored successfully',
                'log_id' => $log_id,
                'data' => $data
            ]));
    } else {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to store time log',
                'database_error' => $this->db->error()
            ]));
    }
}
}
?>
