<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization_settings extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load database library
        $this->load->database();
    }

    public function index(): void
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Organization settings';
        $data["settings"] = $this->PreLoading_get_org_settings();
        $data["timezone"] = $this->admin_model->get_timezone_list();
        $data['main_content'] = $this->load->view('admin/organization_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function Organization_settings_edit()
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data["settings"] = $this->PreLoading_get_org_settings();
        $data['main_content'] = $this->load->view('admin/organization_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function org_exception_settings(): void
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Ex Organization settings';
        $data["timezone"] = $this->admin_model->get_timezone_list();
        $data['main_content'] = $this->load->view('admin/org_exception_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    // // Method to insert or update org settings for a user
    // public function save_org_settings()
    // {
    //     $user_id = $this->session->userdata('id');
    //     // Get data from POST request (replace with actual form data)
    //     $data = [
    //         'user_id'               => $user_id,
    //         'screenshot_flag'       => $this->input->post('screenshot_flag', TRUE),
    //         'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
    //         'webcam_flag'           => $this->input->post('webcam_flag', TRUE),
    //         'webcam_time_interval'  => $this->input->post('webcam_time_interval', TRUE),
    //         'mouse_move_flag'       => $this->input->post('mouse_move_flag', TRUE),
    //         'mouse_move_threshold'  => $this->input->post('mouse_move_threshold', TRUE),
    //         'key_stroke_flag'       => $this->input->post('key_stroke_flag', TRUE),
    //         'key_stroke_threshold'  => $this->input->post('key_stroke_threshold', TRUE),
    //         'idle_time_flag'        => $this->input->post('idle_time_flag', TRUE),
    //         'timecards_time_interval' => 5
    //     ];

    //     // Check if settings exist for this user
    //     $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

    //     if ($query->num_rows() > 0) {
    //         // Update existing org settings
    //         $this->db->where('user_id', $user_id);
    //         $this->db->update('org_settings', $data);
    //     } else {
    //         // Insert new org settings
    //         $this->db->insert('org_settings', $data);
    //     }

    //     // Check for errors
    //     if ($this->db->affected_rows() > 0) {
    //         echo "Settings saved successfully!";
    //     } else {
    //         echo "Failed to save settings.";
    //     }
    // }
    public function save_org_settings()
{
    // Check if this is an AJAX request
    if (!$this->input->is_ajax_request()) {
        show_404();
    }

    $user_id = $this->session->userdata('id');
    
    // Validate required fields
    if (empty($this->input->post('time_zone_selected'))) {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'success' => false,
                'message' => 'Timezone is required'
            ]));
        return;
    }

        // Prepare data from POST request
        $data = [
            'user_id'                  => $user_id,
            'screenshot_flag'          => $this->input->post('screenshot_flag', TRUE) ? 1 : 0,
            'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
            'webcam_flag'              => $this->input->post('webcam_flag', TRUE) ? 1 : 0,
            'webcam_time_interval'     => $this->input->post('webcam_time_interval', TRUE),
            'mouse_move_flag'          => $this->input->post('mouse_move_flag', TRUE) ? 1 : 0,
            'mouse_move_threshold'     => $this->input->post('mouse_move_threshold', TRUE),
            'key_stroke_flag'          => $this->input->post('key_stroke_flag', TRUE) ? 1 : 0,
            'key_stroke_threshold'     => $this->input->post('key_stroke_threshold', TRUE),
            'idle_time_flag'           => $this->input->post('idle_time_flag', TRUE) ? 1 : 0,
            'timecards_time_interval'  => $this->input->post('timecards_time_interval', TRUE),
            'time_zone'                => $this->input->post('time_zone_selected', TRUE)
            ];

        // Clean data for XSS prevention
        $data = $this->security->xss_clean($data);

        // Check if settings exist for this user
        $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

        $this->db->trans_start(); // Start transaction

        if ($query->num_rows() > 0) {
        // Update existing org settings
        $this->db->where('user_id', $user_id);
        $this->db->update('org_settings', $data);
        } else {
        // Insert new org settings
        $this->db->insert('org_settings', $data);
        }

        $this->db->trans_complete(); // Complete transaction

        if ($this->db->trans_status() === FALSE) {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode([
                'success' => false,
                'message' => 'Database error occurred'
            ]));
        } else {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Organization settings saved successfully!'
            ]));
        }
    }



   

    // public function save_org_exception_settings($employee_id)
    // {
    //     $user_id = $this->session->userdata('id');
    //     $self_login = $this->input->post('self_login') ? 1 : 0;
    
    //     $data = [
    //         'user_id'                  => $user_id,
    //         'employee_id'              => $employee_id,
    //         'screenshot_flag'          => $this->input->post('screenshot_flag', TRUE) ? 1 : 0,
    //         'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
    //         'webcam_flag'              => $this->input->post('webcam_flag', TRUE) ? 1 : 0,
    //         'webcam_time_interval'     => $this->input->post('webcam_time_interval', TRUE),
    //         'mouse_move_flag'          => $this->input->post('mouse_move_flag', TRUE) ? 1 : 0,
    //         'mouse_move_threshold'     => $this->input->post('mouse_move_threshold', TRUE),
    //         'key_stroke_flag'          => $this->input->post('key_stroke_flag', TRUE) ? 1 : 0,
    //         'key_stroke_threshold'     => $this->input->post('key_stroke_threshold', TRUE),
    //         'idle_time_flag'           => $this->input->post('idle_time_flag', TRUE) ? 1 : 0,
    //         'timecards_time_interval'  => 5
    //     ];
    
    //     $employee_data = [
    //         'settings_status' => 2,
    //         'self_login' => $self_login
    //     ];
    
    //     // Check if exception settings exist
    //     $query = $this->db->get_where('organization_exception_setting', [
    //         'user_id' => $user_id,
    //         'employee_id' => $employee_id
    //     ]);
    
    //     if ($query->num_rows() > 0) {
    //         $this->db->where('employee_id', $employee_id);
    //         $this->db->where('user_id', $user_id);
    //         $this->db->update('organization_exception_setting', $data);
    //     } else {
    //         $this->db->insert('organization_exception_setting', $data);
    //     }
    
    //     // Update self_login and settings_status
    //     $this->db->where('id', $employee_id);
    //     $this->db->where('user_id', $user_id);
    //     $this->db->update('employees', $employee_data);
    
    //     if ($this->db->affected_rows() > 0) {
    //         echo "Employee settings saved successfully!";
    //     } else {
    //         echo "No changes in the saved employee settings.";
    //     }
    // }


    public function save_org_exception_settings($employee_id)
    {
        $user_id = $this->session->userdata('id');
        $self_login = $this->input->post('self_login') ? 1 : 0;
    
        // Prepare data for organization_exception_setting table
        $data = [
            'user_id'                  => $user_id,
            'employee_id'              => $employee_id,
            'screenshot_flag'          => $this->input->post('screenshot_flag', TRUE) ? 1 : 0,
            'screenshot_time_interval' => $this->input->post('screenshot_time_interval', TRUE),
            'webcam_flag'              => $this->input->post('webcam_flag', TRUE) ? 1 : 0,
            'webcam_time_interval'     => $this->input->post('webcam_time_interval', TRUE),
            'mouse_move_flag'          => $this->input->post('mouse_move_flag', TRUE) ? 1 : 0,
            'mouse_move_threshold'     => $this->input->post('mouse_move_threshold', TRUE),
            'key_stroke_flag'          => $this->input->post('key_stroke_flag', TRUE) ? 1 : 0,
            'key_stroke_threshold'     => $this->input->post('key_stroke_threshold', TRUE),
            'idle_time_flag'           => $this->input->post('idle_time_flag', TRUE) ? 1 : 0,
            'timecards_time_interval'  => 5,
            'time_zone'                => $this->input->post('time_zone_selected', TRUE) // <-- Added this line for timezone
        ];
    
        // Clean data for XSS prevention
        $data = $this->security->xss_clean($data);

        // Prepare data for employees table update
        $employee_data = [
            'settings_status' => 2,
            'self_login'      => $self_login
        ];
    
        // Check if exception settings exist for this employee and user
        $query = $this->db->get_where('organization_exception_setting', [
            'user_id' => $user_id,
            'employee_id' => $employee_id
        ]);
    
        if ($query->num_rows() > 0) {
            // Update existing exception settings
            $this->db->where('employee_id', $employee_id);
            $this->db->where('user_id', $user_id);
            $this->db->update('organization_exception_setting', $data);
        } else {
            // Insert new exception settings
            $this->db->insert('organization_exception_setting', $data);
        }
    
        // Update self_login and settings_status in the 'employees' table
        $this->db->where('id', $employee_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('employees', $employee_data);
    
        // Check for errors and provide feedback
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('msg', 'Employee exception settings saved successfully!');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', 'Failed to save employee exception settings or no changes made.');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
     
    public function get_org_settings()
{
    $user_id = $this->input->get('id');

    if (!$user_id) {
        echo json_encode(['error' => 'Missing user_id parameter.']);
        return;
    }

    $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

    if ($query->num_rows() > 0) {
        echo json_encode($query->row_array());
    } else {
        echo json_encode(['error' => 'No settings found for this user.']);
    }
}
    public function PreLoading_get_org_settings()
    {
        $user_id = $this->session->userdata('id');

        if (!$user_id) {
            return ['error' => 'Missing user_id parameter.'];
        }

        $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return ['error' => 'No settings found for this user.'];
        }
    }


    public function get_org_exception_settings($employee_id)
{
        $user_id = $this->session->userdata('id');
    // $employee_id = $this->input->get('employee_id');

    if (!$user_id || !$employee_id) {
        echo json_encode(['error' => 'Missing user_id or employee_id parameter.']);
        return;
    }

    $query = $this->db->get_where('organization_exception_setting', [
        'user_id' => $user_id,
        'employee_id' => $employee_id
    ]);

    if ($query->num_rows() > 0) {
        echo json_encode($query->row_array());
    } else {
        echo json_encode(['error' => 'No exception settings found for this user and employee.']);
    }
}



public function get_organization_settings()
{
    $user_id = $this->input->get('user_id')?? $this->session->userdata('user_id');
    
    $status = $this->input->get('status'); // Fetch status from the request

    if (!$user_id || !$status) {
        echo json_encode(['error' => 'Missing user_id or status parameter.']);
        return;
    }

    // Determine the table to query based on status
    if ($status == 1) {
        $table = 'org_settings';
    } else if ($status == 2) {
        $table = 'organization_exception_setting';
    } else {
        echo json_encode(['error' => 'Invalid status value.']);
        return;
    }

    // Execute query
    $query = $this->db->get_where($table, ['user_id' => $user_id]);

    if ($query->num_rows() > 0) {
        echo json_encode($query->row_array());
    } else {
        echo json_encode(['error' => 'No settings found for this user.']);
    }
}



    public function get_all_timezones_list_for_dropdown()
{
    $this->db->select('id, name'); // Select the ID and timezone name columns
    $this->db->order_by('name', 'ASC'); // Order alphabetically by timezone name
    $query = $this->db->get('time_zone'); // Changed from 'country' to 'time_zone'
    $timezones = $query->result_array();

    if ($timezones === null) {
        $response = [
            'status'  => 'error',
            'message' => 'An unexpected error occurred while fetching timezones.'
        ];
        $this->output->set_status_header(500); // Internal Server Error
    } elseif (empty($timezones)) {
        $response = [
            'status'  => 'success',
            'data'    => [],
            'message' => 'No timezones found in the database.'
        ];
        $this->output->set_status_header(200); // OK
    } else {
        $response = [
            'status'  => 'success',
            'data'    => $timezones,
            'message' => 'Timezones retrieved successfully in alphabetical order.'
        ];
        $this->output->set_status_header(200); // OK
    }

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($response));
}

public function get_country_list()
{
    try {
        $countries = $this->db->select('name, code')->get('country')->result_array();

        if (empty($countries)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'No countries found']));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'data' => $countries]));
    } catch (Exception $e) {
        return $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Server error', 'error' => $e->getMessage()]));
    }
}
    // public function get_timezones_by_country_code()
    // {
    //     // Accept from GET or POST param
    //     $country_code = $this->input->get('country_code', true) ?: $this->input->post('country_code', true);
    //     $country_code = strtoupper(trim($country_code));

    //     if (!$country_code) {
    //         return $this->output
    //             ->set_status_header(400)
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['status' => false, 'message' => 'Country code is required']));
    //     }

    //     try {
    //         $timezones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);

    //         if (empty($timezones)) {
    //             return $this->output
    //                 ->set_status_header(404)
    //                 ->set_content_type('application/json')
    //                 ->set_output(json_encode(['status' => false, 'message' => 'No timezones found for this country code']));
    //         }

    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['status' => true, 'data' => $timezones]));
    //     } catch (Exception $e) {
    //         return $this->output
    //             ->set_status_header(500)
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['status' => false, 'message' => 'Server error', 'error' => $e->getMessage()]));
    //     }
    // }
    public function get_timezones_by_country_id()
    {
        // Accept from GET or POST param
        $country_id = $this->input->get('country_id', true) ?: $this->input->post('country_id', true);

        if (!$country_id) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Country ID is required']));
        }

        // Fetch country code from database
        $country = $this->db->select('code')->from('country')->where('id', $country_id)->get()->row();

        if (!$country || empty($country->code)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Invalid country ID or country not found']));
        }

        $country_code = strtoupper(trim($country->code));

        try {
            $timezones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);

            if (empty($timezones)) {
                return $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'No timezones found for this country']));
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'data' => $timezones]));
        } catch (Exception $e) {
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Server error', 'error' => $e->getMessage()]));
        }
    }


    // public function get_timezone_list()
    // {
    //     try {
    //         $timezones = $this->db
    //             ->select('utc_offset, time_zone_names')
    //             ->order_by('utc_offset', 'ASC')
    //             ->get('world_time_zones')
    //             ->result_array();

    //         if (empty($timezones)) {
    //             return $this->output
    //                 ->set_status_header(404)
    //                 ->set_content_type('application/json')
    //                 ->set_output(json_encode([
    //                     'status' => false,
    //                     'message' => 'No time zones found'
    //                 ]));
    //         }

    //         $formatted = [];
    //         foreach ($timezones as $tz) {
    //             $formatted[] = "{$tz['utc_offset']} / {$tz['time_zone_names']}";
    //         }

    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode([
    //                 'status' => true,
    //                 'data' => $formatted
    //             ]));

    //     } catch (Exception $e) {
    //         return $this->output
    //             ->set_status_header(500)
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode([
    //                 'status' => false,
    //                 'message' => 'Server error',
    //                 'error' => $e->getMessage()
    //             ]));
    //     }
    // }



    function getTimezoneLabel($timezoneName) {
    try {
        $tz = new DateTimeZone($timezoneName);
        $now = new DateTime("now", $tz);

        // Get UTC offset in ±HH:MM format
        $offsetFormatted = $now->format('P');
        $utcLabel = "UTC" . $offsetFormatted;

        // Try to get abbreviation (like CET, WAT)
        $abbreviation = $now->format('T');  // timezone abbreviation

        return "/ $utcLabel / " . timezone_name_from_abbr($abbreviation) . " ($abbreviation)";
    } catch (Exception $e) {
        return "/ Invalid timezone /";
    }
}

public function get_timezone_info()
{
    header('Content-Type: application/json');

    $timezone = $this->input->get('timezone'); // For CI3
    // $timezone = $this->request->getGet('timezone'); // Uncomment for CI4

    if (empty($timezone)) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Missing timezone parameter.'
        ]);
        return;
    }

    try {
        $tz = new DateTimeZone($timezone);
        $now = new DateTime('now', $tz);

        echo json_encode([
            'status'    => true,
            'timezone'  => $timezone,
            'timestamp' => $now->format('Y-m-d H:i:s'), // Changed from getTimestamp()
            'offset'    => $now->format('P')             // +05:30 style offset
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status'  => false,
            'message' => 'Invalid timezone.',
            'error'   => $e->getMessage()
        ]);
    }
}




}
?>
