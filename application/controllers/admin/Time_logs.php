<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_logs extends Home_Controller { 

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function get_time_logs() {
        // Get inputs from GET request (query parameters)
    $employee_id = $this->session->userdata('employee_id')??$this->input->get('employee_id');
    $user_id = $this->session->userdata('employee_org_id')??$this->session->userdata('id'); // fallback for Postman or URL query params
        $date = $this->input->get('date');

        // If no date is provided, use today's date
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        // Validate inputs
        if (empty($employee_id) || empty($user_id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Missing employee_id or user_id'
                ]));
            return; // Important: Exit the function after sending the error response
        }

        // Check if the combination of user_id and employee_id exists in the time_logs table for the given date
        $this->db->select('log_id'); // Select a primary key to efficiently check existence
        $this->db->from('time_logs');
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date); // Filter by date

        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'No time logs found for the specified user_id, employee_id, and date.'
                ]));
            return; // Important: Exit the function
        }

        // Query the time_logs table
        $this->db->select('log_id, employee_id, user_id, log_date, start_time, end_time, total_active_time, total_idle_time, status, created_at, updated_at'); // Select the columns you need
        $this->db->from('time_logs');
        $this->db->where('user_id', $user_id);
        $this->db->where('employee_id', $employee_id);
        $this->db->where('DATE(log_date)', $date);
        $query = $this->db->get();
        $result = $query->result_array();

        // Return response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data' => $result
            ]));
    }

    public function checkExistingTimelog() 
{
    // Get identifiers from multiple sources (header > session > get/post)
    $employee_id = $this->input->get_request_header('employee_id', TRUE)
                  ?? $this->session->userdata('employee_id')
                  ?? $this->input->get_post('employee_id');
    
    $user_id = $this->input->get_request_header('user_id', TRUE)
               ?? $this->session->userdata('employee_org_id')
               ?? $this->session->userdata('id')
               ?? $this->input->get_post('user_id');
    
    $date = $this->input->get_request_header('date', TRUE)
            ?? $this->input->get_post('date');

    // Use today's date if none provided
    if (empty($date)) {
        $date = date('Y-m-d');
    }

    // Validate required parameters
    if (empty($employee_id) || empty($user_id)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status' => false,
                'message' => 'Missing required parameters',
                'missing' => [
                    'employee_id' => empty($employee_id),
                    'user_id' => empty($user_id)
                ],
                'request_sources' => [
                    'headers' => [
                        'employee_id' => $this->input->get_request_header('employee_id', TRUE) !== null,
                        'user_id' => $this->input->get_request_header('user_id', TRUE) !== null
                    ],
                    'session' => [
                        'employee_id' => $this->session->userdata('employee_id') !== null,
                        'user_id' => $this->session->userdata('employee_org_id') !== null
                    ],
                    'request' => [
                        'employee_id' => $this->input->get_post('employee_id') !== null,
                        'user_id' => $this->input->get_post('user_id') !== null
                    ]
                ]
            ]));
    }

    // Query for existing timelog
    $this->db->select('log_id, employee_id, user_id, log_date, start_time, 
                      end_time, total_active_time, total_idle_time, status, 
                      created_at, updated_at');
    $this->db->from('time_logs');
    $this->db->where('user_id', $user_id);
    $this->db->where('employee_id', $employee_id);
    $this->db->where('DATE(log_date)', $date);
    
    $query = $this->db->get();
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

    // Return successful response
    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'status' => true,
            'data' => $result,
            'request_source' => $this->getRequestSource([
                'employee_id' => $employee_id,
                'user_id' => $user_id,
                'date' => $date
            ])
        ]));
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
    


    public function store_timelog()
    {
        // Validate request method
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Only POST requests are allowed"
                ]));
        }
    
        // Get and validate headers
        $required_headers = [
            'user_id' => 'User ID',
            'employee_id' => 'Employee ID',
            'log_date' => 'Log Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'total_active_time' => 'Total Active Time',
            'total_idle_time' => 'Total Idle Time',
            'status' => 'Status'
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
    
        // Validate and format timestamps
        try {
            $start_datetime = (new DateTime($headers['start_time']))->format('Y-m-d H:i:s');
            $end_datetime = (new DateTime($headers['end_time']))->format('Y-m-d H:i:s');
            
            // Ensure end time is after start time
            if (strtotime($end_datetime) <= strtotime($start_datetime)) {
                throw new Exception("End time must be after start time");
            }
        } catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid time data: " . $e->getMessage()
                ]));
        }
    
        // Validate numerical values
        if (!is_numeric($headers['total_active_time']) || $headers['total_active_time'] < 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Total active time must be a positive number"
                ]));
        }
    
        if (!is_numeric($headers['total_idle_time']) || $headers['total_idle_time'] < 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Total idle time must be a positive number"
                ]));
        }
    
        // Check for duplicate entry
        $this->db->where('employee_id', $headers['employee_id']);
        $this->db->where('log_date', $headers['log_date']);
        $existing = $this->db->get('time_logs')->row();
        
        if ($existing) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(409)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Time log already exists for this employee and date",
                    "existing_log_id" => $existing->id
                ]));
        }
    
        // Prepare data with additional validation
        $current_time = date('Y-m-d H:i:s');
        $data = [
            'employee_id'       => $headers['employee_id'],
            'user_id'           => $headers['user_id'],
            'log_date'          => $headers['log_date'],
            'start_time'        => $start_datetime,
            'end_time'          => $end_datetime,
            'total_active_time' => (float)$headers['total_active_time'],
            'total_idle_time'   => (float)$headers['total_idle_time'],
            'status'            => $headers['status'],
            'created_at'        => $current_time,
            'updated_at'        => $current_time,
        ];
    
        // Start database transaction
        $this->db->trans_start();
        
        $inserted = $this->db->insert('time_logs', $data);
        $log_id = $this->db->insert_id();
        
        $this->db->trans_complete();
    
        if (!$this->db->trans_status() || !$inserted) {
            $error = $this->db->error();
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Failed to store time log",
                    "error" => $error['message'] ?? 'Unknown database error'
                ]));
        }
    
        // Log the successful operation (optional)
       // Log the successful operation (optional)
log_message('info', "Time log created for employee {$headers['employee_id']} with ID $log_id");

return $this->output
    ->set_content_type('application/json')
    ->set_status_header(201)
    ->set_output(json_encode([
        "status" => "success",
        "message" => "Time log stored successfully",
        "log_id" => $log_id,
        "data" => $data
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


public function store_Employee_Activity()
{
    // Allow only POST
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Only POST requests are allowed"
            ]));
    }

    // Required headers
    $required_headers = [
        'user_id'             => 'user_id',
        'employee_id'         => 'employee_id',
        'total_mouse_movement'=> 'total_mouse_movement',
        'total_keystrokes'    => 'total_keystrokes'
    ];

    $headers = [];
    $missing_fields = [];

    // Validate headers (allow 0 as valid)
    foreach ($required_headers as $field => $label) {
        $value = $this->input->get_request_header($field, TRUE);

        if ($value === null) { // allows 0
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

    // Validate numeric values
    if (!is_numeric($headers['total_mouse_movement'])) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Total mouse movement must be a number"
            ]));
    }

    $mouse_movement = (int)$headers['total_mouse_movement'];
    if ($mouse_movement < 0 || $mouse_movement > 20) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Total mouse movement must be between 0 and 20"
            ]));
    }

    if (!is_numeric($headers['total_keystrokes'])) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Total keystrokes must be a number"
            ]));
    }

    $keystrokes = (int)$headers['total_keystrokes'];
    if ($keystrokes < 0 || $keystrokes > 40) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Total keystrokes must be between 0 and 40"
            ]));
    }

    $data = [
        'user_id'             => $headers['user_id'],
        'employee_id'         => $headers['employee_id'],
        'created_at'          => date('Y-m-d H:i:s'),
        'total_mouse_movement'=> $mouse_movement,
        'total_keystrokes'    => $keystrokes
    ];

    // Start database transaction
    $this->db->trans_start();

    $inserted = $this->db->insert('Employee_Activity', $data);
    $activity_id = $this->db->insert_id();

    $this->db->trans_complete();

    if (!$this->db->trans_status() || !$inserted) {
        $error = $this->db->error();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Failed to store activity log",
                "error" => $error['message'] ?? 'Unknown database error'
            ]));
    }

    log_message('info', "Employee activity created for employee {$headers['employee_id']} with ID $activity_id");

    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(201)
        ->set_output(json_encode([
            "status" => "success",
            "message" => "Employee activity stored successfully",
            "data" => $data
        ]));
}


// public function store_Time_Log()
// {
//     // Allow only POST
//     if ($this->input->server('REQUEST_METHOD') !== 'POST') {
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(405)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Only POST requests are allowed"
//             ]));
//     }

//     // Required headers based on the SQL query
//     $required_headers = [
//         'employee_id'        => 'employee_id',
//         'user_id'           => 'user_id',
//         'log_date'           => 'log_date',
//         'start_time'         => 'start_time',
//         'end_time'          => 'end_time',
//         'total_active_time' => 'total_active_time',
//         'total_idle_time'   => 'total_idle_time',
     
//     ];

//     $headers = [];
//     $missing_fields = [];

//     // Validate headers (allow 0 as valid)
//     foreach ($required_headers as $field => $label) {
//         $value = $this->input->get_request_header($field, TRUE);

//         if ($value === null) { // allows 0
//             $missing_fields[] = $label;
//         }

//         $headers[$field] = $value;
//     }

//     if (!empty($missing_fields)) {
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(400)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Missing required headers: " . implode(', ', $missing_fields)
//             ]));
//     }

//     // Validate numeric values for IDs
//     $numeric_fields = [
//         'employee_id' => ['min' => 1, 'max' => PHP_INT_MAX],
//         'user_id' => ['min' => 1, 'max' => PHP_INT_MAX]
//     ];

//     foreach ($numeric_fields as $field => $limits) {
//         if (!is_numeric($headers[$field])) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => ucfirst(str_replace('_', ' ', $field)) . " must be a number"
//                 ]));
//         }

//         $value = (int)$headers[$field];
//         if ($value < $limits['min'] || $value > $limits['max']) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => ucfirst(str_replace('_', ' ', $field)) . " must be between {$limits['min']} and {$limits['max']}"
//                 ]));
//         }
//     }

//     // Validate date format
//     if (!DateTime::createFromFormat('Y-m-d', $headers['log_date'])) {
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(400)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Invalid log date format. Expected YYYY-MM-DD"
//             ]));
//     }

//     // Validate time formats (start_time, end_time)
//     $time_fields = ['start_time', 'end_time'];
//     foreach ($time_fields as $field) {
//         if (!DateTime::createFromFormat('H:i:s', $headers[$field])) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => "Invalid $field format. Expected HH:MM:SS"
//                 ]));
//         }
//     }

//     // Validate duration formats (total_active_time, total_idle_time)
//     $duration_fields = ['total_active_time', 'total_idle_time'];
//     foreach ($duration_fields as $field) {
//         if (!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $headers[$field])) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => "Invalid $field format. Must be in HH:MM:SS format"
//                 ]));
//         }
        
//         // Additional check to ensure values are valid
//         $parts = explode(':', $headers[$field]);
//         $hours = (int)$parts[0];
//         $minutes = (int)$parts[1];
//         $seconds = (int)$parts[2];
        
//         if ($hours > 23 || $minutes > 59 || $seconds > 59) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => "Invalid $field value. Hours must be 0-23, minutes and seconds 0-59"
//                 ]));
//         }
//     }



//     $current_time = date('Y-m-d H:i:s');
//     $data = [
//         'employee_id'        => $headers['employee_id'],
//         'user_id'            => $headers['user_id'],
//         'log_date'           => $headers['log_date'],
//         'start_time'         => $headers['start_time'],
//         'end_time'           => $headers['end_time'],
//         'total_active_time' => $headers['total_active_time'], // Stored as HH:MM:SS
//         'total_idle_time'    => $headers['total_idle_time'], // Stored as HH:MM:SS
     
//         'created_at'         => $current_time,
//         'updated_at'        => $current_time
//     ];

//     // Start database transaction
//     $this->db->trans_start();

//     $inserted = $this->db->insert('time_logs', $data);
//     $log_id = $this->db->insert_id();

//     $this->db->trans_complete();

//     if (!$this->db->trans_status() || !$inserted) {
//         $error = $this->db->error();
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(500)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Failed to store time log",
//                 "error" => $error['message'] ?? 'Unknown database error'
//             ]));
//     }

//     log_message('info', "Time log created for employee {$headers['employee_id']} with ID $log_id");

//     return $this->output
//         ->set_content_type('application/json')
//         ->set_status_header(201)
//         ->set_output(json_encode([
//             "status" => "success",
//             "message" => "Time log stored successfully",
//             "data" => $data
//         ]));
// }

// public function store_Time_Log()
// {
//     // Allow only POST
//     if ($this->input->server('REQUEST_METHOD') !== 'POST') {
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(405)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Only POST requests are allowed"
//             ]));
//     }

//     // Required headers based on the SQL query
//     $required_headers = [
//         'employee_id'        => 'employee_id',
//         'user_id'           => 'user_id',
//         'log_date'           => 'log_date',
//         'start_time'         => 'start_time',
//         'end_time'          => 'end_time',
//         'total_active_time' => 'total_active_time',
//         'total_idle_time'   => 'total_idle_time',
//         'status'            => 'status'
//     ];

//     $headers = [];
//     $missing_fields = [];

//     // Validate headers (allow 0 as valid)
//     foreach ($required_headers as $field => $label) {
//         $value = $this->input->get_request_header($field, TRUE);

//         if ($value === null) { // allows 0
//             $missing_fields[] = $label;
//         }

//         $headers[$field] = $value;
//     }

//     if (!empty($missing_fields)) {
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(400)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Missing required headers: " . implode(', ', $missing_fields)
//             ]));
//     }

//     // Validate numeric values for IDs and status
//     $numeric_fields = [
//         'employee_id' => ['min' => 1, 'max' => PHP_INT_MAX],
//         'user_id' => ['min' => 1, 'max' => PHP_INT_MAX],
//         'status' => ['min' => 0, 'max' => 1] // Assuming status is 0 or 1 (or adjust as needed)
//     ];

//     foreach ($numeric_fields as $field => $limits) {
//         if (!is_numeric($headers[$field])) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => ucfirst(str_replace('_', ' ', $field)) . " must be a number"
//                 ]));
//         }

//         $value = (int)$headers[$field];
//         if ($value < $limits['min'] || $value > $limits['max']) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => ucfirst(str_replace('_', ' ', $field)) . " must be between {$limits['min']} and {$limits['max']}"
//                 ]));
//         }
//     }

//     // Validate date format
//     if (!DateTime::createFromFormat('Y-m-d', $headers['log_date'])) {
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(400)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Invalid log date format. Expected YYYY-MM-DD"
//             ]));
//     }

//     // Validate time formats (start_time, end_time)
//     $time_fields = ['start_time', 'end_time'];
//     foreach ($time_fields as $field) {
//         if (!DateTime::createFromFormat('H:i:s', $headers[$field])) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => "Invalid $field format. Expected HH:MM:SS"
//                 ]));
//         }
//     }

//     // Validate duration formats (total_active_time, total_idle_time)
//     $duration_fields = ['total_active_time', 'total_idle_time'];
//     foreach ($duration_fields as $field) {
//         if (!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $headers[$field])) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => "Invalid $field format. Must be in HH:MM:SS format"
//                 ]));
//         }
        
//         // Additional check to ensure values are valid
//         $parts = explode(':', $headers[$field]);
//         $hours = (int)$parts[0];
//         $minutes = (int)$parts[1];
//         $seconds = (int)$parts[2];
        
//         if ($hours > 23 || $minutes > 59 || $seconds > 59) {
//             return $this->output
//                 ->set_content_type('application/json')
//                 ->set_status_header(400)
//                 ->set_output(json_encode([
//                     "status" => "error",
//                     "message" => "Invalid $field value. Hours must be 0-23, minutes and seconds 0-59"
//                 ]));
//         }
//     }

//     $current_time = date('Y-m-d H:i:s');
//     $data = [
//         'employee_id'        => $headers['employee_id'],
//         'user_id'            => $headers['user_id'],
//         'log_date'           => $headers['log_date'],
//         'start_time'         => $headers['start_time'],
//         'end_time'           => $headers['end_time'],
//         'total_active_time'  => $headers['total_active_time'], // Stored as HH:MM:SS
//         'total_idle_time'    => $headers['total_idle_time'], // Stored as HH:MM:SS
//         'status'             => (int)$headers['status'],
//         'created_at'         => $current_time,
//         'updated_at'        => $current_time
//     ];

//     // Start database transaction
//     $this->db->trans_start();

//     $inserted = $this->db->insert('time_logs', $data);
//     $log_id = $this->db->insert_id();

//     $this->db->trans_complete();

//     if (!$this->db->trans_status() || !$inserted) {
//         $error = $this->db->error();
//         return $this->output
//             ->set_content_type('application/json')
//             ->set_status_header(500)
//             ->set_output(json_encode([
//                 "status" => "error",
//                 "message" => "Failed to store time log",
//                 "error" => $error['message'] ?? 'Unknown database error'
//             ]));
//     }

//     log_message('info', "Time log created for employee {$headers['employee_id']} with ID $log_id");

//     return $this->output
//         ->set_content_type('application/json')
//         ->set_status_header(201)
//         ->set_output(json_encode([
//             "status" => "success",
//             "message" => "Time log stored successfully",
//             "data" => $data
//         ]));
// }






public function store_Time_Log()
{
    // Allow only POST
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Only POST requests are allowed"
            ]));
    }

    // Required headers based on the SQL query
    $required_headers = [
        'employee_id'        => 'employee_id',
        'user_id'           => 'user_id',
        'log_date'           => 'log_date',
        'start_time'         => 'start_time',
        'end_time'          => 'end_time',
        'total_active_time' => 'total_active_time',
        'total_idle_time'   => 'total_idle_time',
        'status'            => 'status'
    ];

    $headers = [];
    $missing_fields = [];

    // Validate headers (allow 0 as valid)
    foreach ($required_headers as $field => $label) {
        $value = $this->input->get_request_header($field, TRUE);

        if ($value === null) { // allows 0
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

    // Validate numeric values for IDs
    $numeric_fields = [
        'employee_id' => ['min' => 1, 'max' => PHP_INT_MAX],
        'user_id' => ['min' => 1, 'max' => PHP_INT_MAX]
    ];

    foreach ($numeric_fields as $field => $limits) {
        if (!is_numeric($headers[$field])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => ucfirst(str_replace('_', ' ', $field)) . " must be a number"
                ]));
        }

        $value = (int)$headers[$field];
        if ($value < $limits['min'] || $value > $limits['max']) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => ucfirst(str_replace('_', ' ', $field)) . " must be between {$limits['min']} and {$limits['max']}"
                ]));
        }
    }

    // Validate status is an integer (any value)
    if (!is_numeric($headers['status'])) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Status must be an integer value"
            ]));
    }

    // Validate date format
    if (!DateTime::createFromFormat('Y-m-d', $headers['log_date'])) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Invalid log date format. Expected YYYY-MM-DD"
            ]));
    }

    // Validate time formats (start_time, end_time)
    $time_fields = ['start_time', 'end_time'];
    foreach ($time_fields as $field) {
        if (!DateTime::createFromFormat('H:i:s', $headers[$field])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid $field format. Expected HH:MM:SS"
                ]));
        }
    }

    // Validate duration formats (total_active_time, total_idle_time)
    $duration_fields = ['total_active_time', 'total_idle_time'];
    foreach ($duration_fields as $field) {
        if (!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $headers[$field])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid $field format. Must be in HH:MM:SS format"
                ]));
        }
        
        // Additional check to ensure values are valid
        $parts = explode(':', $headers[$field]);
        $hours = (int)$parts[0];
        $minutes = (int)$parts[1];
        $seconds = (int)$parts[2];
        
        if ($hours > 23 || $minutes > 59 || $seconds > 59) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid $field value. Hours must be 0-23, minutes and seconds 0-59"
                ]));
        }
    }

    $current_time = date('Y-m-d H:i:s');
    $data = [
        'employee_id'        => $headers['employee_id'],
        'user_id'            => $headers['user_id'],
        'log_date'           => $headers['log_date'],
        'start_time'         => $headers['start_time'],
        'end_time'           => $headers['end_time'],
        'total_active_time' => $headers['total_active_time'], // Stored as HH:MM:SS
        'total_idle_time'    => $headers['total_idle_time'], // Stored as HH:MM:SS
        'status'             => (int)$headers['status'],
        'created_at'         => $current_time,
        'updated_at'        => $current_time
    ];

    // Start database transaction
    $this->db->trans_start();

    $inserted = $this->db->insert('time_logs', $data);
    $log_id = $this->db->insert_id();

    $this->db->trans_complete();

    if (!$this->db->trans_status() || !$inserted) {
        $error = $this->db->error();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Failed to store time log",
                "error" => $error['message'] ?? 'Unknown database error'
            ]));
    }

    log_message('info', "Time log created for employee {$headers['employee_id']} with ID $log_id");

    return $this->output
        ->set_content_type('application/json')
        ->set_status_header(201)
        ->set_output(json_encode([
            "status" => "success",
            "message" => "Time log stored successfully",
            "data" => $data
        ]));
}
}
?>
