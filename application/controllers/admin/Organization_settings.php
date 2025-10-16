<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organization_settings extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load database library
        $this->load->database();
        $this->load->model('hrm_model');
    }

    public function index(): void
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }

        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Organization settings';
        $data["settings"] = $this->PreLoading_get_org_settings();
        $data['main_content'] = $this->load->view('admin/organization_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function Organization_settings_edit()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
        $data = array();
        $data['plan'] = get_plan_by_feature(4);
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Edit';
        $data['can_edit'] = $this->auth_model->get_permission(4);
        $data["settings"] = $this->PreLoading_get_org_settings();
        $data['countries'] = $this->admin_model->select('country');
        // $data["timezone"] = $this->admin_model->get_timezone_list();
        $data['main_content'] = $this->load->view('admin/organization_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function org_exception_settings()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
        $data = array();
        $data['plan'] = get_plan_by_feature(5);
        $data['is_employee_admin'] = true;
        $data['navbar'] = 'own_settings';
        $data['page_title'] = 'Ex Organization settings';
        $data['can_edit'] = $this->auth_model->get_permission(5);
        $data['countries'] = $this->admin_model->select('country');
        $data['employees_settings'] = $this->get_all_org_employee_settings();
        // $data["timezone"] = $this->admin_model->get_timezone_list();
        $data['main_content'] = $this->load->view('admin/org_exception_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function no_org_exception_settings()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
        $data = array();
        $data['plan'] = get_plan_by_feature(5);
        $data['is_employee_admin'] = true;
        $data['navbar'] = 'no_own_settings';
        $data['page_title'] = 'Ex Organization settings';
        $data['can_edit'] = $this->auth_model->get_permission(5);
        $data['countries'] = $this->admin_model->select('country');
        $data['employees'] = $this->get_employees_without_settings();
        // $data["timezone"] = $this->admin_model->get_timezone_list();
        $data['main_content'] = $this->load->view('admin/org_exception_settings', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function index111()
    {
        $data = array();
        $data['plan'] = get_plan_by_feature(3);
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Notification';
        $data['navbar'] = 'webcam';
        $data['can_edit'] = $this->auth_model->get_permission(3);
        $data['notifications'] = $this->web_notifications();
        $data['main_content'] = $this->load->view('admin/notification', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }
    public function settings_delete($id)
    {
        $this->admin_model->delete_settings($id, 'organization_exception_setting');
        $settings = $this->get_organization_settings_for_deletion(1, $id);

        echo json_encode([
            'st'      => 1,
            'payload' => $settings   // ✅ correct key name
        ]);
    }
    public function get_settings_status()
    {
        // $employee_id = 5;
        // $user_id = 4;
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->input->get('user_id');

        if (!$employee_id || !$user_id) {
            echo json_encode(['error' => 'Missing employee_id or user_id parameter.']);
            return;
        }

        // Query the database selecting only settings_status
        $query = $this->db
            ->select('settings_status')
            ->get_where('employees', [
                'id' => $employee_id,
                'user_id' => $user_id
            ]);

        if ($query->num_rows() > 0) {
            echo json_encode([
                'status' => true,
                'message' => 'Employee found.',
                'settings_status' => $query->row()->settings_status // Return only this field
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Employee is not there.'
            ]);
        }
    }






    public function save_org_settings()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('id');

        if (empty($this->input->post('time_zone'))) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Timezone is required'
                ]));
        }

        $data = [
            'user_id'                  => $user_id,
            'screenshot_flag'          => $this->input->post('screenshot_flag', TRUE) ? 1 : 0,
            'screenshot_time_interval' => (int) $this->input->post('screenshot_time_interval', TRUE),
            'webcam_flag'              => $this->input->post('webcam_flag', TRUE) ? 1 : 0,
            'webcam_time_interval'     => (int) $this->input->post('webcam_time_interval', TRUE),
            'mouse_move_flag'          => $this->input->post('mouse_move_flag', TRUE) ? 1 : 0,
            'mouse_move_threshold'     => (int) $this->input->post('mouse_move_threshold', TRUE),
            'key_stroke_flag'          => $this->input->post('key_stroke_flag', TRUE) ? 1 : 0,
            'key_stroke_threshold'     => (int) $this->input->post('key_stroke_threshold', TRUE),
            'idle_time_flag'           => $this->input->post('idle_time_flag', TRUE) ? 1 : 0,
            'timecards_time_interval'  => (int) $this->input->post('timecards_time_interval', TRUE),
            'time_zone'                => $this->input->post('time_zone', TRUE),
            'created_at'               => get_user_datetime_only($user_id),
            'updated_at'               => get_user_datetime_only($user_id)
        ];

        $data = $this->security->xss_clean($data);

        $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

        $this->db->trans_start();
        if ($query->num_rows() > 0) {
            $this->db->where('user_id', $user_id);
            $this->db->update('org_settings', $data);
        } else {
            $this->db->insert('org_settings', $data);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Database error occurred'
                ]));
        }

        // --- Build payload like in save_org_exception_settings ---
        $payload = [
            'userId'   => (int) $user_id,
            'employeeId' => null,
            'settingsRemoved' => false,
            'settings' => [
                'screenshot_flag'          => $data['screenshot_flag'],
                'screenshot_time_interval' => $data['screenshot_time_interval'],
                'webcam_flag'              => $data['webcam_flag'],
                'webcam_time_interval'     => $data['webcam_time_interval'],
                'mouse_move_flag'          => $data['mouse_move_flag'],
                'mouse_move_threshold'     => $data['mouse_move_threshold'],
                'key_stroke_flag'          => $data['key_stroke_flag'],
                'key_stroke_threshold'     => $data['key_stroke_threshold'],
                'idle_time_flag'           => $data['idle_time_flag'],
                'timecards_time_interval'  => $data['timecards_time_interval'],
                'time_zone'                => $data['time_zone'],
            ],
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Organization settings saved successfully!',
                'payload' => $payload
            ]));
    }


    public function save_org_exception_settings($employee_id)
    {
        // ------------------------------------------------------------------
        // 1.  Gather & sanitise input
        // ------------------------------------------------------------------
        $user_id    = (int) $this->session->userdata('id');
        if (!$user_id) {
            show_error('Session expired', 401);
        }

        $self_login = $this->input->post('self_login') ? 1 : 0;

        $data = [
            'user_id'                  => $user_id,
            'employee_id'              => (int) $employee_id,
            'screenshot_flag'          => $this->input->post('screenshot_flag',  TRUE) ? 1 : 0,
            'screenshot_time_interval' => (int) $this->input->post('screenshot_time_interval', TRUE),
            'webcam_flag'              => $this->input->post('webcam_flag',      TRUE) ? 1 : 0,
            'webcam_time_interval'     => (int) $this->input->post('webcam_time_interval', TRUE),
            'mouse_move_flag'          => $this->input->post('mouse_move_flag',  TRUE) ? 1 : 0,
            'mouse_move_threshold'     => (int) $this->input->post('mouse_move_threshold', TRUE),
            'key_stroke_flag'          => $this->input->post('key_stroke_flag',  TRUE) ? 1 : 0,
            'key_stroke_threshold'     => (int) $this->input->post('key_stroke_threshold', TRUE),
            'idle_time_flag'           => $this->input->post('idle_time_flag',   TRUE) ? 1 : 0,
            'self_login'               => $self_login,
            'timecards_time_interval'  => (int) $this->input->post('timecards_time_interval', TRUE),
            'time_zone'                => $this->input->post('time_zone',        TRUE),
            'created_at'               => get_user_datetime_only($user_id),
            'updated_at'               => get_user_datetime_only($user_id),
        ];

        // XSS clean
        $data = $this->security->xss_clean($data);

        // ------------------------------------------------------------------
        // 2.  DB transaction: upsert into exception table + update employees
        // ------------------------------------------------------------------
        $this->db->trans_start();

        $exists = $this->db->where([
            'user_id'    => $user_id,
            'employee_id' => $employee_id
        ])->count_all_results('organization_exception_setting');

        if ($exists) {
            $this->db->update(
                'organization_exception_setting',
                $data,
                ['user_id' => $user_id, 'employee_id' => $employee_id]
            );
        } else {
            $this->db->insert('organization_exception_setting', $data);
        }

        $this->db->update(
            'employees',
            ['settings_status' => 2, 'self_login' => $self_login],
            ['id' => $employee_id, 'user_id' => $user_id]
        );

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // --- failure ---------------------------------------------------
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['success' => false, 'msg' => 'DB error']));
            }

            $this->session->set_flashdata('error', 'Failed to save settings.');
            return redirect($_SERVER['HTTP_REFERER']);
        }

        // ------------------------------------------------------------------
        // 3.  Build payload for the WebSocket message
        // ------------------------------------------------------------------
        $payload = [
            'employeeId' => (int) $employee_id,
            'userId'     => (int) $user_id,
            'settingsRemoved' => false,
            'settings'   => [
                'screenshot_flag'          => $data['screenshot_flag'],
                'screenshot_time_interval' => $data['screenshot_time_interval'],
                'webcam_flag'              => $data['webcam_flag'],
                'webcam_time_interval'     => $data['webcam_time_interval'],
                'mouse_move_flag'          => $data['mouse_move_flag'],
                'mouse_move_threshold'     => $data['mouse_move_threshold'],
                'key_stroke_flag'          => $data['key_stroke_flag'],
                'key_stroke_threshold'     => $data['key_stroke_threshold'],
                'idle_time_flag'           => $data['idle_time_flag'],
                'timecards_time_interval'  => $data['timecards_time_interval'],
                'self_login'               => $data['self_login'],
                'time_zone'                => $data['time_zone'],
            ],
        ];

        // ------------------------------------------------------------------
        // 4a. AJAX request → respond immediately with JSON
        // ------------------------------------------------------------------
        if ($this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'payload' => $payload]));
        }

        // ------------------------------------------------------------------
        // 4b. Normal POST → store payload & redirect (no headers‑sent issue)
        // ------------------------------------------------------------------
        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode([
                    'success' => true,
                    'msg'     => 'Employee exception settings saved successfully!',
                    'payload' => $payload
                ])
            );
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
    // public function PreLoading_get_org_settings()
    // {
    //     $user_id = $this->session->userdata('id');

    //     if (!$user_id) {
    //         return ['error' => 'Missing user_id parameter.'];
    //     }

    //     // Fetch org_settings
    //     $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

    //     if ($query->num_rows() > 0) {
    //         $settings = $query->row_array();

    //         // Fetch user's timezone from users table
    //         $user = $this->db
    //             ->select('timezone',)
    //             ->get_where('users', ['id' => $user_id])
    //             ->row_array();

    //         // Add timezone to settings array
    //         if ($user && isset($user['timezone'])) {
    //             $settings['timezone'] = $user['timezone'];
    //         } else {
    //             $settings['timezone'] = null; // or default value
    //         }

    //         return $settings;
    //     } else {
    //         return ['error' => 'No settings found for this user.'];
    //     }
    // }
    public function PreLoading_get_org_settings()
    {
        $user_id = $this->session->userdata('id');

        if (!$user_id) {
            return ['error' => 'Missing user_id parameter.'];
        }

        // Fetch org_settings
        $query = $this->db->get_where('org_settings', ['user_id' => $user_id]);

        if ($query->num_rows() > 0) {
            $settings = $query->row_array();

            // Fetch user's timezone and country code from users table
            $user = $this->db
                ->select('timezone, country')
                ->get_where('users', ['id' => $user_id])
                ->row_array();

            // Add timezone to settings
            $settings['timezone'] = $user['timezone'] ?? null;

            // Fetch country name from country table using country_code
            if (!empty($user['country'])) {
                $country = $this->db
                    ->select('name')
                    ->get_where('country', ['id' => $user['country']])
                    ->row_array();

                $settings['country_name'] = $country['name'] ?? null;
            } else {
                $settings['country_name'] = null;
            }

            return $settings;
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
    public function get_all_org_employee_settings()
    {
        $user_id = $this->session->userdata('id');
        if (!$user_id) {
            return ['error' => 'Missing user_id parameter.'];
        }

        $employeeDetails = $this->get_employees_with_settings();

        $query = $this->db->get_where('organization_exception_setting', ['user_id' => $user_id]);
        $settings = $query->num_rows() > 0 ? $query->result_array() : [];

        // Index settings by employee_id
        $settingsByEmp = [];
        foreach ($settings as $s) {
            $settingsByEmp[$s['employee_id']] = $s;
        }

        $final = [];
        foreach ($employeeDetails as $emp) {
            $s = $settingsByEmp[$emp->id] ?? [];

            $final[] = [
                'id'         => $emp->id,
                'user_id'    => $emp->user_id,
                'name'       => $emp->name,
                'email'      => $emp->email,
                'department' => $emp->department_name ?? '',
                'role'       => $emp->role_name ?? '',
                'settings'   => [
                    'screenshot' => [
                        'flag'     => $s['screenshot_flag'],
                        'interval' => $s['screenshot_time_interval'],
                    ],
                    'webcam' => [
                        'flag'     => $s['webcam_flag'],
                        'interval' => $s['webcam_time_interval'],
                    ],
                    'mouse_movement' => [
                        'flag'     => $s['mouse_move_flag'],
                        'interval' => $s['mouse_move_threshold'],
                    ],
                    'keystrokes' => [
                        'flag'     => $s['key_stroke_flag'],
                        'interval' => $s['key_stroke_threshold'],
                    ],
                    'idle_time' => [
                        'flag'     => $s['idle_time_flag'],
                        'interval' => $s['timecards_time_interval'],
                    ],
                    'time_zone' => $s['time_zone'],
                    'self_login' => $s['self_login']
                ],
            ];
        }

        return $final;
    }




    // public function get_all_org_employee_no_settings()
    // {
    //     $user_id = $this->session->userdata('id');

    //     if (!$user_id) {
    //         return ['error' => 'Missing user_id parameter.'];
    //     }

    //     $this->db->select('id, user_id, name, email');
    //     $this->db->where([
    //         'user_id' => $user_id,
    //         'settings_status' => 1
    //     ]);
    //     $query = $this->db->get('employees');


    //     if ($query->num_rows() > 0) {
    //         return $query->result_array(); // ✅ return array directly
    //     } else {
    //         return ['error' => 'No exception settings found for this user.'];
    //     }
    // }

    public function get_employees_with_settings()
    {
        $user_id = $this->session->userdata('id') ?: $this->session->userdata('employee_org_id');
        if (!$user_id) {
            return [];
        }

        $business = $this->db->select('uid')
            ->from('business')
            ->where('user_id', $user_id)
            ->limit(1)
            ->get()
            ->row();

        if (!$business) {
            return [];
        }

        $business_uid = $business->uid;

        $this->db->select('
        e.id,
        e.user_id,
        e.name,
        e.email,
        d.name AS department_name,
        r.role_name
    ');
        $this->db->from('employees AS e');
        $this->db->where('e.business_id', $business_uid);
        $this->db->where('e.settings_status', 2); // Only employees with active settings
        $this->db->join('departments AS d', 'e.department_id = d.id', 'LEFT');
        $this->db->join('employee_roles AS r', 'e.role_id = r.id', 'LEFT');
        $this->db->order_by('e.id', 'DESC');

        return $this->db->get()->result();
    }
    public function get_employees_without_settings()
    {
        $user_id = $this->session->userdata('id') ?: $this->session->userdata('employee_org_id');
        if (!$user_id) {
            return [];
        }

        $business = $this->db->select('uid')
            ->from('business')
            ->where('user_id', $user_id)
            ->limit(1)
            ->get()
            ->row();

        if (!$business) {
            return [];
        }

        $business_uid = $business->uid;

        $this->db->select('
        e.id,
        e.user_id,
        e.name,
        e.email,
        d.name AS department,
        r.role_name As role
     ');
        $this->db->from('employees AS e');
        $this->db->where('e.business_id', $business_uid);
        $this->db->where('e.settings_status', 1); // Only employees without active settings
        $this->db->join('departments AS d', 'e.department_id = d.id', 'LEFT');
        $this->db->join('employee_roles AS r', 'e.role_id = r.id', 'LEFT');
        $this->db->order_by('e.id', 'DESC');

        return $this->db->get()->result_array(); // ✅ associative array
    }



    public function get_organization_settings()
    {
        $status = $this->input->get('status');

        if (!$status) {
            echo json_encode(['error' => 'Missing status parameter.']);
            return;
        }

        // Determine input parameter and table based on status
        if ($status == 1) {
            $user_id = $this->input->get('user_id') ?? $this->session->userdata('user_id');
            if (!$user_id) {
                echo json_encode(['error' => 'Missing user_id parameter.']);
                return;
            }
            $table = 'org_settings';
            $column = 'user_id';
            $value = $user_id;
        } else if ($status == 2) {
            $employee_id = $this->input->get('employee_id');
            if (!$employee_id) {
                echo json_encode(['error' => 'Missing employee_id parameter.']);
                return;
            }
            $table = 'organization_exception_setting';
            $column = 'employee_id';
            $value = $employee_id;
        } else {
            echo json_encode(['error' => 'Invalid status value.']);
            return;
        }

        // Query the database
        $query = $this->db->get_where($table, [$column => $value]);

        if ($query->num_rows() > 0) {
            echo json_encode($query->row_array());
        } else {
            echo json_encode(['error' => 'No settings found.']);
        }
    }
    public function get_organization_settings_for_deletion($status, $employee_id)
    {
        if (!$status) {
            return ['error' => 'Missing status parameter.'];
        }

        if ($status == 1) {
            $user_id = $this->session->userdata('id') ?? $this->session->userdata('user_id');
            if (!$user_id) {
                return ['error' => 'Missing user_id parameter.'];
            }

            $table  = 'org_settings';
            $column = 'user_id';
            $value  = $user_id;
        } else {
            return ['error' => 'Invalid status value.'];
        }

        $query = $this->db->get_where($table, [$column => $value]);

        if ($query->num_rows() > 0) {
            $data = $query->row_array();

            return [
                'employeeId'      => (int) $employee_id,
                'userId'          => (int) $user_id,
                'settingsRemoved' => true,
                'settings'        => [
                    'screenshot_flag'          => $data['screenshot_flag'],
                    'screenshot_time_interval' => $data['screenshot_time_interval'],
                    'webcam_flag'              => $data['webcam_flag'],
                    'webcam_time_interval'     => $data['webcam_time_interval'],
                    'mouse_move_flag'          => $data['mouse_move_flag'],
                    'mouse_move_threshold'     => $data['mouse_move_threshold'],
                    'key_stroke_flag'          => $data['key_stroke_flag'],
                    'key_stroke_threshold'     => $data['key_stroke_threshold'],
                    'idle_time_flag'           => $data['idle_time_flag'],
                    'timecards_time_interval'  => $data['timecards_time_interval'],
                    'self_login'               => $data['self_login'],
                    'time_zone'                => $data['time_zone'],
                ],
            ];
        }

        return ['error' => 'No settings found.'];
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



    function getTimezoneLabel($timezoneName)
    {
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

    public function get_user_timestamp()
    {
        header('Content-Type: application/json');

        // Get user_id from session
        $user_id = $this->session->userdata('id');

        // Optional: allow override via GET/POST for testing
        $param_user_id = $this->input->get_post('user_id');
        if (!empty($param_user_id)) {
            $user_id = $param_user_id;
        }

        // Validate
        if (empty($user_id) || !is_numeric($user_id)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'message' => 'User ID not found in session or params.'
            ]);
            return;
        }

        // Load DB if not autoloaded
        $this->load->database();

        // Fetch user timezone
        $user = $this->db->get_where('users', ['id' => $user_id])->row();

        if (!$user || empty($user->timezone)) {
            http_response_code(404);
            echo json_encode([
                'status' => false,
                'message' => 'User not found or timezone not set.'
            ]);
            return;
        }

        try {
            $tz = new DateTimeZone($user->timezone);
            $now = new DateTime('now', $tz);

            echo json_encode([
                'status'    => true,
                'user_id'   => $user_id,
                'timezone'  => $user->timezone,
                'datetime'  => $now->format('Y-m-d H:i:s'),
                'offset'    => $now->format('P') // e.g., +05:30
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
