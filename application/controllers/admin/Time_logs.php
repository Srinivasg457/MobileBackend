<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Time_logs extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function get_time_logs()
    {
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
    public function get_time_logs_for_tool()
    {
        // Get inputs from session or GET request
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->input->get('user_id');
        $date =  date('Y-m-d'); // Default to today if not provided

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
        // Fetch time log details
        $this->db->select('
        log_id, 
        employee_id, 
        user_id, 
        log_date, 
        start_time, 
        end_time, 
        ROUND(TIME_TO_SEC(total_active_time) / 60, 0) AS total_active_time, 
        ROUND(TIME_TO_SEC(total_idle_time) / 60, 0) AS total_idle_time, 
        created_at, 
        updated_at
        ');
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
            } elseif (
                $this->session->userdata($key) !== null ||
                ($key === 'user_id' && ($this->session->userdata('employee_org_id') !== null ||
                    $this->session->userdata('id') !== null))
            ) {
                $sources[$key] = 'session';
            } elseif ($this->input->get_post($key) !== null) {
                $sources[$key] = 'request';
            } else {
                $sources[$key] = 'default';
            }
        }

        return $sources;
    }


    // public function update_timelog()
    // {
    //     // Validate request method
    //     if ($this->input->server('REQUEST_METHOD') !== 'PUT') {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(405)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Only PUT requests are allowed"
    //             ]));
    //     }

    //     // Get and validate headers
    //     $required_headers = [
    //         'user_id' => 'user_id',
    //         'employee_id' => 'employee_id',
    //         'log_date' => 'log_date',
    //         'end_time' => 'end_time',
    //         'total_active_time' => 'total_active_time',
    //         'total_idle_time' => 'total_idle_time'
    //     ];

    //     $headers = [];
    //     $missing_fields = [];

    //     foreach ($required_headers as $field => $label) {
    //         $value = $this->input->get_request_header($field, TRUE);
    //         if (empty($value)) {
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

    //     // Validate and format timestamp
    //     try {
    //         $end_datetime = (new DateTime($headers['end_time']))->format('Y-m-d H:i:s');

    //         // Get existing log to validate end time is after start time
    //         $this->db->where('employee_id', $headers['employee_id']);
    //         $this->db->where('user_id', $headers['user_id']);
    //         $this->db->where('log_date', $headers['log_date']);
    //         $existing = $this->db->get('time_logs')->row();

    //         if (!$existing) {
    //             return $this->output
    //                 ->set_content_type('application/json')
    //                 ->set_status_header(404)
    //                 ->set_output(json_encode([
    //                     "status" => "error",
    //                     "message" => "Time log not found for update"
    //                 ]));
    //         }

    //         if (strtotime($end_datetime) < strtotime($existing->start_time)) {
    //             throw new Exception("End time must be after start time");
    //         }

    //         // Function to convert various time formats to HH:MM:SS
    //         function convertToHHMMSS($time)
    //         {
    //             if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
    //                 return $time;
    //             }

    //             if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $time)) {
    //                 return str_replace('-', ':', $time);
    //             }

    //             if (is_numeric($time)) {
    //                 $hours = (float)$time;
    //                 $total_seconds = (int)($hours * 3600);

    //                 $hours = floor($total_seconds / 3600);
    //                 $minutes = floor(($total_seconds % 3600) / 60);
    //                 $seconds = $total_seconds % 60;

    //                 return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    //             }

    //             throw new Exception("Invalid time format");
    //         }

    //         $active_time = convertToHHMMSS($headers['total_active_time']);
    //         $idle_time = convertToHHMMSS($headers['total_idle_time']);
    //     } catch (Exception $e) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Invalid time data: " . $e->getMessage()
    //             ]));
    //     }

    //     // // Prepare update data
    //     $current_time =  get_user_datetime_only($headers['user_id']);
    //     $data = [
    //         'end_time' => $end_datetime,
    //         'total_active_time' => $active_time,
    //         'total_idle_time' => $idle_time,
    //         'updated_at' => $current_time
    //     ];
    //     // $current_time = get_user_datetime_only($headers['user_id']);

    //     // $data = [
    //     //     'end_time' => $end_datetime,
    //     //     'updated_at' => $current_time
    //     // ];

    //     // Only include active and idle time if they are not both '00:00:00'
    //     // if ($active_time !== '00:00:00' || $idle_time !== '00:00:00') {
    //     //     $data['total_active_time'] = $active_time;
    //     //     $data['total_idle_time'] = $idle_time;
    //     // }


    //     // Start database transaction
    //     $this->db->trans_start();

    //     $this->db->where('user_id', $headers['user_id']);
    //     $this->db->where('employee_id', $headers['employee_id']);
    //     $this->db->where('log_date', $headers['log_date']);
    //     $updated = $this->db->update('time_logs', $data);

    //     $this->db->trans_complete();

    //     if (!$this->db->trans_status() || !$updated) {
    //         $error = $this->db->error();
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Failed to update time log",
    //                 "error" => $error['message'] ?? 'Unknown database error'
    //             ]));
    //     }

    //     // Get the updated record
    //     $this->db->where('employee_id', $headers['employee_id']);
    //     $this->db->where('user_id', $headers['user_id']);
    //     $this->db->where('log_date', $headers['log_date']);
    //     $updated_record = $this->db->get('time_logs')->row();

    //     // Log success
    //     log_message('info', "Time log updated for employee {$headers['employee_id']} on date {$headers['log_date']}");

    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode([
    //             "status" => "success",
    //             "message" => "Time log updated successfully",
    //             "data" => [
    //                 "original_data" => $existing,
    //                 "updated_data" => $updated_record,
    //                 "changes_applied" => $data
    //             ]
    //         ]));
    // }

    // for calculating the signout time
    // public function update_timelog()
    // {
    //     // Validate request method
    //     if ($this->input->server('REQUEST_METHOD') !== 'PUT') {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(405)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Only PUT requests are allowed"
    //             ]));
    //     }

    //     // Get and validate headers
    //     $required_headers = [
    //         'user_id' => 'user_id',
    //         'employee_id' => 'employee_id',
    //         'log_date' => 'log_date',
    //         'end_time' => 'end_time',
    //         'total_active_time' => 'total_active_time',
    //         'total_idle_time' => 'total_idle_time'
    //     ];

    //     $headers = [];
    //     $missing_fields = [];

    //     foreach ($required_headers as $field => $label) {
    //         $value = $this->input->get_request_header($field, TRUE);
    //         if (empty($value)) {
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

    //     // Validate and format timestamp
    //     try {
    //         $end_datetime = (new DateTime($headers['end_time']))->format('Y-m-d H:i:s');

    //         // Get existing log to validate end time is after start time
    //         $this->db->where('employee_id', $headers['employee_id']);
    //         $this->db->where('user_id', $headers['user_id']);
    //         $this->db->where('log_date', $headers['log_date']);
    //         $existing = $this->db->get('time_logs')->row();

    //         if (!$existing) {
    //             return $this->output
    //                 ->set_content_type('application/json')
    //                 ->set_status_header(404)
    //                 ->set_output(json_encode([
    //                     "status" => "error",
    //                     "message" => "Time log not found for update"
    //                 ]));
    //         }

    //         if (strtotime($end_datetime) < strtotime($existing->start_time)) {
    //             throw new Exception("End time must be after start time");
    //         }

    //         // Function to convert various time formats to HH:MM:SS
    //         function convertToHHMMSS($time)
    //         {
    //             if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
    //                 return $time;
    //             }

    //             if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $time)) {
    //                 return str_replace('-', ':', $time);
    //             }

    //             if (is_numeric($time)) {
    //                 $total_seconds = (int)($time * 3600);
    //                 return sprintf(
    //                     "%02d:%02d:%02d",
    //                     floor($total_seconds / 3600),
    //                     floor(($total_seconds % 3600) / 60),
    //                     $total_seconds % 60
    //                 );
    //             }
    //             throw new Exception("Invalid time format");
    //         }

    //         $active_time = convertToHHMMSS($headers['total_active_time']);
    //         $idle_time   = convertToHHMMSS($headers['total_idle_time']);

    //         // -------------------- NEW LOGIC --------------------
    //         if (!empty($existing->signout_time)) {
    //             $signout_ts = strtotime($existing->signout_time);
    //             $end_ts     = strtotime($end_datetime);

    //             if ($end_ts > $signout_ts) {
    //                 $diff_seconds = $end_ts - $signout_ts;

    //                 // Convert existing DB idle time to seconds
    //                 list($ih, $im, $is) = explode(':', $existing->total_idle_time);
    //                 $existing_idle_seconds = ($ih * 3600) + ($im * 60) + $is;

    //                 // Add extra idle seconds
    //                 $new_idle_seconds = $existing_idle_seconds + $diff_seconds;

    //                 // Convert back to HH:MM:SS
    //                 $idle_time = sprintf(
    //                     "%02d:%02d:%02d",
    //                     floor($new_idle_seconds / 3600),
    //                     floor(($new_idle_seconds % 3600) / 60),
    //                     $new_idle_seconds % 60
    //                 );
    //             }

    //             // ✅ Reset signout_time so it won't be used again
    //             $this->db->where('user_id', $headers['user_id']);
    //             $this->db->where('employee_id', $headers['employee_id']);
    //             $this->db->where('log_date', $headers['log_date']);
    //             $this->db->update('time_logs', ['signout_time' => null]);
    //         }
    //         // -------------------- END NEW LOGIC --------------------

    //     } catch (Exception $e) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Invalid time data: " . $e->getMessage()
    //             ]));
    //     }

    //     // // Prepare update data
    //     $current_time =  get_user_datetime_only($headers['user_id']);
    //     $data = [
    //         'end_time' => $end_datetime,
    //         'total_active_time' => $active_time,
    //         'total_idle_time' => $idle_time,
    //         'updated_at' => $current_time
    //     ];

    //     $this->db->trans_start();
    //     $this->db->where('user_id', $headers['user_id']);
    //     $this->db->where('employee_id', $headers['employee_id']);
    //     $this->db->where('log_date', $headers['log_date']);
    //     $updated = $this->db->update('time_logs', $data);
    //     $this->db->trans_complete();

    //     if (!$this->db->trans_status() || !$updated) {
    //         $error = $this->db->error();
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Failed to update time log",
    //                 "error" => $error['message'] ?? 'Unknown database error'
    //             ]));
    //     }

    //     // Get updated record
    //     $this->db->where('employee_id', $headers['employee_id']);
    //     $this->db->where('user_id', $headers['user_id']);
    //     $this->db->where('log_date', $headers['log_date']);
    //     $updated_record = $this->db->get('time_logs')->row();

    //     log_message('info', "Time log updated for employee {$headers['employee_id']} on date {$headers['log_date']}");

    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode([
    //             "status" => "success",
    //             "message" => "Time log updated successfully",
    //             "data" => [
    //                 "original_data" => $existing,
    //                 "updated_data" => $updated_record,
    //                 "changes_applied" => $data
    //             ]
    //         ]));
    // }

    public function update_timelog()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'PUT') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Only PUT requests are allowed"
                ]));
        }

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

        try {
            $end_datetime = (new DateTime($headers['end_time']))->format('Y-m-d H:i:s');

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

            if (strtotime($end_datetime) < strtotime($existing->start_time)) {
                throw new Exception("End time must be after start time");
            }

            function convertToHHMMSS($time)
            {
                if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
                    return $time;
                }
                if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $time)) {
                    return str_replace('-', ':', $time);
                }
                if (is_numeric($time)) {
                    $total_seconds = (int)($time * 3600);
                    return sprintf(
                        "%02d:%02d:%02d",
                        floor($total_seconds / 3600),
                        floor(($total_seconds % 3600) / 60),
                        $total_seconds % 60
                    );
                }
                throw new Exception("Invalid time format");
            }

            $active_time = convertToHHMMSS($headers['total_active_time']);
            $idle_time   = convertToHHMMSS($headers['total_idle_time']);

            // --- NEW LOGIC: Check gap between last update and new end_time ---
            if (!empty($existing->end_time)) {
                $prev_update_ts = strtotime($existing->end_time);
                $end_ts         = strtotime($end_datetime);

                $diff_seconds = $end_ts - $prev_update_ts;

                if ($diff_seconds > 60) { // more than 1 minute
                    list($ih, $im, $is) = explode(':', $idle_time);
                    $existing_idle_seconds = ($ih * 3600) + ($im * 60) + $is;

                    $new_idle_seconds = $existing_idle_seconds + $diff_seconds;

                    $idle_time = sprintf(
                        "%02d:%02d:%02d",
                        floor($new_idle_seconds / 3600),
                        floor(($new_idle_seconds % 3600) / 60),
                        $new_idle_seconds % 60
                    );
                }
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

        $current_time = get_user_datetime_only($headers['user_id']);
        $data = [
            'end_time' => $end_datetime,
            'total_active_time' => $active_time,
            'total_idle_time' => $idle_time,
            'updated_at' => $current_time
        ];

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

        $this->db->where('employee_id', $headers['employee_id']);
        $this->db->where('user_id', $headers['user_id']);
        $this->db->where('log_date', $headers['log_date']);
        $updated_record = $this->db->get('time_logs')->row();

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




    public function insert_singout_time()
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

        // Required headers
        $required_headers = [
            'user_id'        => 'user_id',
            'employee_id'    => 'employee_id',
            'log_date'       => 'log_date',
            'end_time'       => 'end_time',
            'total_active_time' => 'total_active_time',
            'total_idle_time'   => 'total_idle_time',
            'signout_time'   => 'signout_time' // new
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

        try {
            // Format times
            $end_datetime    = (new DateTime($headers['end_time']))->format('Y-m-d H:i:s');
            $signout_datetime = (new DateTime($headers['signout_time']))->format('Y-m-d H:i:s'); // new

            // Get existing log
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

            if (strtotime($end_datetime) < strtotime($existing->start_time)) {
                throw new Exception("End time must be after start time");
            }

            // Convert to HH:MM:SS
            $active_time = $this->convertToHHMMSS($headers['total_active_time']);
            $idle_time   = $this->convertToHHMMSS($headers['total_idle_time']);
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
        $current_time = get_user_datetime_only($headers['user_id']);
        $data = [
            'end_time'         => $end_datetime,
            'total_active_time' => $active_time,
            'total_idle_time'  => $idle_time,
            'signout_time'     => $signout_datetime, // new
            'updated_at'       => $current_time
        ];

        // DB Transaction
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

    /**
     * Helper: Convert various time formats to HH:MM:SS
     */
    private function convertToHHMMSS($time)
    {
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
        if (empty($user_id) || empty($employee_id) || !is_numeric($mouse) || !is_numeric($keys)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Missing or invalid data',
                    'required_fields' => [
                        'user_id (got: ' . $user_id . ')',
                        'employee_id (got: ' . $employee_id . ')',
                        'total_mouse_movement (numeric, got: ' . $mouse . ')',
                        'total_keystrokes (numeric, got: ' . $keys . ')'
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
        if ($this->db->affected_rows() > 0) {
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

        // Basic validation
        if (
            empty($employee_id) || empty($user_id) || empty($log_date) ||
            empty($start_time) || empty($end_time) ||
            empty($total_active_time) || empty($total_idle_time)
        ) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Missing required data',
                    'required_fields' => [
                        'employee_id (got: ' . $employee_id . ')',
                        'user_id (got: ' . $user_id . ')',
                        'log_date (got: ' . $log_date . ')',
                        'start_time (got: ' . $start_time . ')',
                        'end_time (got: ' . $end_time . ')',
                        'total_active_time (got: ' . $total_active_time . ')',
                        'total_idle_time (got: ' . $total_idle_time . ')'
                    ]
                ]));
        }

        // Validate numeric IDs
        if (!is_numeric($employee_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid ID format',
                    'invalid_fields' => [
                        'employee_id (must be numeric, got: ' . $employee_id . ')',
                        'user_id (must be numeric, got: ' . $user_id . ')'
                    ]
                ]));
        }

        // Get current user datetime
        $current_datetime = get_user_datetime_only($user_id);
        $current_date = date('Y-m-d', strtotime($current_datetime));
        $current_time = date('H:i:s', strtotime($current_datetime));
        $expected_start_datetime = $current_date . ' ' . $current_time;

        // Check if logging for today
        if ($log_date == $current_date) {
            // For today's log, check if times are within a 3-minute window
            $current_timestamp = strtotime($current_datetime);

            // Create timestamps for -1 minute, current minute, and +1 minute
            $prev_minute = date('Y-m-d H:i', $current_timestamp - 60);
            $current_minute = date('Y-m-d H:i', $current_timestamp);
            $next_minute = date('Y-m-d H:i', $current_timestamp + 60);

            // Format received time (handle both time-only and datetime formats)
            $received_time = (strpos($start_time, ' ') !== false) ? $start_time : $log_date . ' ' . $start_time;
            $received_minute = date('Y-m-d H:i', strtotime($received_time));

            // Check if received time is within the 3-minute window
            $is_valid = in_array($received_minute, [$prev_minute, $current_minute, $next_minute]);

            if (!$is_valid) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Invalid start time for today - must be within a 3-minute window around current time',
                        'details' => [
                            'valid_window' => [
                                'from' => $prev_minute . ':00',
                                'to' => $next_minute . ':00'
                            ],
                            'expected_current_time' => $expected_start_datetime,
                            'received_start_time' => $received_time,
                            'user_timezone' => 'Based on user_id: ' . $user_id
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
        if ($this->db->affected_rows() > 0) {
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
public function store_application_usage_log()
{
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input) || empty($input)) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid or missing JSON body'
            ]));
    }

    $success_count = 0;
    $failed_entries = [];
    $inserted_data = [];

    foreach ($input as $i => $log) {
        if (
            empty($log['user_id']) ||
            empty($log['employee_id']) ||
            empty($log['log_date']) ||
            empty($log['start_time']) ||
            empty($log['end_time']) ||
            empty($log['application_name'])
        ) {
            $failed_entries[] = [
                'index' => $i,
                'reason' => 'Missing required fields'
            ];
            continue;
        }

      $data = [
    'employee_id'       => $log['employee_id'],
    'user_id'           => $log['user_id'],
    'log_date'          => $log['log_date'],
    'start_time'        => $log['start_time'],
    'end_time'          => $log['end_time'],
    'duration_seconds'  => isset($log['duration_seconds']) ? $log['duration_seconds'] : 0,
    'application_name'  => $log['application_name'],
    'window_title'      => isset($log['window_title']) ? $log['window_title'] : null,
    'website_url'       => isset($log['website_url']) ? $log['website_url'] : null,
    'created_at'        => date('Y-m-d H:i:s'),
    'updated_at'        => date('Y-m-d H:i:s')
];

        $this->db->insert('application_usage_logs', $data);

        if ($this->db->affected_rows() > 0) {
            $success_count++;
            $inserted_data[] = $data;
        } else {
            $failed_entries[] = [
                'index' => $i,
                'reason' => 'Database insert failed',
                'db_error' => $this->db->error()
            ];
        }
    }

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => 'success',
            'message' => 'Logs processed successfully',
            'data' => [
                'success_count' => $success_count,
                'failed_entries' => $failed_entries,
                'inserted_data' => $inserted_data
            ]
        ]));
}



    public function get_weekly_reports()
    {
        // Get required headers
        $employee_id = $this->input->get_request_header('employee_id', TRUE);
        $user_id = $this->input->get_request_header('user_id', TRUE);

        // Basic validation
        if (empty($employee_id) || empty($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Missing required headers',
                    'required_headers' => [
                        'employee_id',
                        'user_id'
                    ]
                ]));
        }

        // Validate numeric IDs
        if (!is_numeric($employee_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid ID format',
                    'invalid_fields' => [
                        'employee_id (must be numeric)',
                        'user_id (must be numeric)'
                    ]
                ]));
        }

        try {
            // Initialize response array
            $weekly_reports = [];
            $weeks_to_include_raw = []; // Use a temporary array to build weeks before filtering

            // Get current date
            $today = new DateTime();

            // Add Current Week
            $current_week_start = clone $today;
            $current_week_start->modify('last sunday'); // Start of the current week (Sunday)
            $current_week_end = clone $today; // End of the current week (today's date)

            $weeks_to_include_raw[] = [
                'name_prefix' => 'Current Week',
                'start_date' => $current_week_start->format('Y-m-d'),
                'end_date' => $current_week_end->format('Y-m-d')
            ];

            // Add up to 4 previous weeks
            $week_counter = 1;
            $prev_week_cursor_end = clone $current_week_start; // Start from current week's Sunday

            for ($i = 0; $i < 4; $i++) {
                $prev_week_end = clone $prev_week_cursor_end;
                $prev_week_end->modify('-1 day'); // End of this previous week (Saturday)

                $prev_week_start = clone $prev_week_end;
                $prev_week_start->modify('last sunday'); // Start of this previous week (Sunday)

                // Ensure we don't go too far back if current week hasn't started yet
                if ($prev_week_start > $today) {
                    break; // Avoid adding future weeks if today is before the calculated start
                }

                $weeks_to_include_raw[] = [
                    'name_prefix' => 'Week ' . $week_counter,
                    'start_date' => $prev_week_start->format('Y-m-d'),
                    'end_date' => $prev_week_end->format('Y-m-d')
                ];

                // Move cursor for the next iteration (next previous week)
                $prev_week_cursor_end = clone $prev_week_start;
                $week_counter++;
            }

            // We want the weeks to be ordered from oldest to newest for the graph display.
            // The loop adds them in reverse chronological order (Current, Week 1, Week 2, ...).
            // So, we need to reverse the array to get them in the desired order.
            $weeks_to_process = array_reverse($weeks_to_include_raw);

            foreach ($weeks_to_process as $week_data) {
                $week_name = $week_data['name_prefix'];

                $this->db->select('log_date, total_active_time')
                    ->from('worksmart.time_logs')
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
                    if (!isset($log['total_active_time']) || empty($log['total_active_time'])) {
                        continue;
                    }

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

                // Convert total seconds to HH:MM:SS
                $hours = floor($total_seconds / 3600);
                $remaining_seconds = $total_seconds % 3600;
                $minutes = floor($remaining_seconds / 60);
                $seconds = $remaining_seconds % 60;

                // Only add the report if there is data for the week
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

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'data' => $weekly_reports,
                    'meta' => [
                        'employee_id' => (int)$employee_id,
                        'user_id' => (int)$user_id,
                        'report_date' => date('Y-m-d'),
                        'weeks_included' => count($weekly_reports)
                    ]
                ]));
        } catch (Exception $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Server error occurred',
                    'error_details' => $e->getMessage()
                ]));
        }
    }
    public function generate_last_two_months_logs()
    {
        $timezone = new DateTimeZone('UTC');
        $user_id = $this->session->userdata('employee_org_id');
        $id = $this->session->userdata('employee_id');

        // Date range: last 2 months
        $endDate = new DateTime('now', $timezone);
        $startDate = (clone $endDate)->sub(new DateInterval('P2M'));

        $data = [];
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        foreach ($dateRange as $date) {
            $logDate = $date->format('Y-m-d');

            // Random start and end time
            $startHour = rand(8, 9);
            $startMin = rand(0, 59);
            $startSec = rand(0, 59);
            $endHour = rand(16, 18);
            $endMin = rand(0, 59);
            $endSec = rand(0, 59);

            $startTime = (clone $date)->setTime($startHour, $startMin, $startSec);
            $endTime = (clone $date)->setTime($endHour, $endMin, $endSec);

            // Duration in seconds
            $totalSeconds = $endTime->getTimestamp() - $startTime->getTimestamp();

            // Active and idle split
            $activeSeconds = rand((int)($totalSeconds * 0.6), (int)($totalSeconds * 0.9));
            $idleSeconds = $totalSeconds - $activeSeconds;

            $totalActiveTime = gmdate('H:i:s', $activeSeconds);
            $totalIdleTime = gmdate('H:i:s', $idleSeconds);

            // Estimate keystrokes and mouse movements from active seconds
            $activeMinutes = floor($activeSeconds / 60);
            $estimated_keystrokes = $activeMinutes * 40;
            $estimated_mouse = $activeMinutes * 20;

            // Insert into time_logs
            $log = [
                'employee_id' => $id,
                'user_id' => $user_id,
                'log_date' => $logDate,
                'start_time' => $startTime->format('Y-m-d H:i:s'),
                'end_time' => $endTime->format('Y-m-d H:i:s'),
                'total_active_time' => $totalActiveTime,
                'total_idle_time' => $totalIdleTime,
                'created_at' => $startTime->format('Y-m-d H:i:s'),
                'updated_at' => $startTime->format('Y-m-d H:i:s')
            ];
            $this->db->insert('time_logs', $log);

            // Insert into Employee_Activity
            $activity = [
                'employee_id' => $id,
                'user_id' => $user_id,
                'total_mouse_movement' => $estimated_mouse,
                'total_keystrokes' => $estimated_keystrokes,
                'created_at' => $startTime->format('Y-m-d H:i:s')
            ];
            $this->db->insert('Employee_Activity', $activity);

            // For return/debug
            $data[] = [
                'log' => $log,
                'activity' => $activity
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'count' => count($data),
                'data' => $data
            ]));
    }
}
