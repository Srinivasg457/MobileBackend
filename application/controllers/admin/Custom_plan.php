<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_plan extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        //check auth
        if (!is_admin()) {
            redirect(base_url());
        }
    }

    public function index(): void
    {
        // if (!is_subscribed()) {
        //     redirect('/admin/subscription/upgrade_plan');
        // }

        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Custom Plan';
        $data['countries'] = $this->admin_model->select_asc('country');
        // $data['features'] = $this->admin_model->select_asc('package_features');
        $data['features'] = $this->admin_model->select_asc('package_features');
        $data['main_content'] = $this->load->view('admin/custom_plan', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['packages'] = $this->admin_model->select_asc('package');
        $data['user'] = $this->admin_model->select_option($id, 'users');
        $data['business'] = $this->admin_model->get_user_by_id($id, 'business');
        $data['payment'] = $this->admin_model->get_user_payment($id);
        $data['current_feature'] = $this->admin_model->get_feature_by_id($id, 'custom_plan_feature');
        $data['features'] = $this->admin_model->select_asc('package_features');
        $data['countries'] = $this->admin_model->select_asc('country');
        $data['main_content'] = $this->load->view('admin/custom_plan', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function add_custom_plan($id)
    {
        $data = array();
        $data['page_title'] = 'Add Custom Plan';
        $data['packages'] = $this->admin_model->select_asc('package');
        $data['user'] = $this->admin_model->select_option($id, 'users');
        $data['business'] = $this->admin_model->get_user_by_id($id, 'business');
        $data['payment'] = $this->admin_model->get_user_payment($id);
        $data['current_feature'] = $this->admin_model->get_feature_by_id($id, 'custom_plan_feature');
        $data['features'] = $this->admin_model->select_asc('package_features');
        $data['countries'] = $this->admin_model->select_asc('country');
        $data['main_content'] = $this->load->view('admin/custom_plan', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function add()
    {
        if ($_POST) {

            $id = $this->input->post('id', true);

            // Validate inputs
            $this->form_validation->set_rules('name', trans('name'), 'required|max_length[100]');
            $this->form_validation->set_rules('email', trans('email'), 'required|max_length[100]');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error', validation_errors());
                redirect(base_url('admin/users'));
            } else {
                if ($id != '') {
                    $new_password = $this->input->post('password');
                    if (empty($new_password)) {
                        $user = $this->admin_model->get_by_id($id, 'users');
                        $password = $user->password;
                    } else {
                        $password = hash_password($this->input->post('password'));
                    }
                } else {
                    $password = hash_password($this->input->post('password'));
                }

                $mail = strtolower($this->input->post('email', true));
                $email = $this->auth_model->check_email($mail);

                // Check if email exists in employees table
                $this->db->where('LOWER(email)', $mail);
                $exists_in_employees = $this->db->get('employees')->row();

                if ($email || $exists_in_employees) {
                    $this->session->set_flashdata('msg', trans('email-exist'));
                    redirect(base_url('admin/users'));
                }

                $package = 5; // Always Custom Plan
                $user_type = 'registered';
                $trial_expire = date('Y-m-d');

                // Create user record
                $udata = array(
                    'name' => $this->input->post('name', true),
                    'user_name' => str_slug($this->input->post('name', true)),
                    'slug' => str_slug($this->input->post('name', true)),
                    'email' => $mail,
                    'verify_code' => 0,
                    'thumb' => 'assets/front/img/avatar.png',
                    'password' => $password,
                    'role' => 'user',
                    'account_type' => 'pro',
                    'user_type' => $user_type,
                    'trial_expire' => $trial_expire,
                    'status' => 1,
                    'email_verified' => 1,
                    'referral_id' => substr(random_string('alnum', 5) . mt_rand(), 0, 10),
                    'created_at' => my_date_now(),
                    'country' => $this->input->post('country', true),
                    'timezone' => $this->input->post('time_zone', true),
                );

                $id = $this->admin_model->insert($udata, 'users');
                $this->session->set_flashdata('msg', trans('inserted-successfully'));

                // Create business record
                $rand_uid = substr(random_string('numeric', 5) . mt_rand(), 0, 8);
                $uid = ltrim($rand_uid, '0');
                $company_name = 'Company ' . $uid;

                $data = array(
                    'user_id' => $id,
                    'uid' => $uid,
                    'is_autoload_amount' => 0,
                    'enable_stock' => 0,
                    'name' => $company_name,
                    'slug' => str_slug($company_name),
                    'country' => $this->input->post('country', true),
                    'category' => $this->input->post('category', true),
                    'is_primary' => 1
                );
                $this->common_model->insert($data, 'business');

                // Payment setup
                $billing = $this->input->post('billing_type', true);
                $expire_on = ($billing == 'monthly')
                    ? date('Y-m-d', strtotime('+1 month'))
                    : date('Y-m-d', strtotime('+12 month'));

                $price = $this->input->post('amount', true);
                $pdata = array(
                    'puid' => random_string('numeric', 5),
                    'user_id' => $id,
                    'package' => $package,
                    'payment_type' => 'Manual',
                    'billing_type' => $billing,
                    'amount' => $price,
                    'status' => $this->input->post('payment_status', true),
                    'created_at' => my_date_now(),
                    'expire_on' => $expire_on
                );
                $this->admin_model->insert($pdata, 'payment');

                // Create custom plan user record
                $cdata = array(
                    'user_id' => $id,
                    'plan_name' => "Custom",
                    'start_date' => my_date_now(),
                    'end_date' => $expire_on,
                    'created_at' => my_date_now(),
                    'updated_at' => my_date_now(),
                );
                $this->admin_model->insert($cdata, 'custom_plan_user');

                // Save modal features data
                $features = $this->input->post('features');
                $this->save_custom_plan_features($id, $features);
                $this->add_org_settings($id, $features, $this->input->post('time_zone', true));

                redirect(base_url('admin/users'));
            }
        }
    }
    public function update()
    {
        if ($_POST) {

            $id = $this->input->post('id', true);

            // // Validate inputs
            // $this->form_validation->set_rules('name', trans('name'), 'required|max_length[100]');
            // // $this->form_validation->set_rules('email', trans('email'), 'required|max_length[100]');

            // if ($this->form_validation->run() === false) {
            //     $this->session->set_flashdata('error', validation_errors());
            //     redirect(base_url('admin/users'));
            // } 
            // else {
                // if ($id != '') {
                //     $new_password = $this->input->post('password');
                //     if (empty($new_password)) {
                //         $user = $this->admin_model->get_by_id($id, 'users');
                //         $password = $user->password;
                //     } else {
                //         $password = hash_password($this->input->post('password'));
                //     }
                // } else {
                //     $password = hash_password($this->input->post('password'));
                // }

                // $mail = strtolower($this->input->post('email', true));
                // $email = $this->auth_model->check_email($mail);

                // Check if email exists in employees table
                // $this->db->where('LOWER(email)', $mail);
                // $exists_in_employees = $this->db->get('employees')->row();

                // if ($email || $exists_in_employees) {
                //     $this->session->set_flashdata('msg', trans('email-exist'));
                //     redirect(base_url('admin/users'));
                // }

                $package = 5; // Always Custom Plan
                // $user_type = 'registered';
                // $trial_expire = date('Y-m-d');

                // Create user record
                $udata = array(
                    // 'name' => $this->input->post('name', true),
                    // 'user_name' => str_slug($this->input->post('name', true)),
                    // 'slug' => str_slug($this->input->post('name', true)),
                    // 'email' => $mail,
                    // 'referral_id' => substr(random_string('alnum', 5) . mt_rand(), 0, 10),
                    // 'status' => $this->input->post('status', true),
                    // 'country' => $this->input->post('country', true),
                    // 'timezone' => $this->input->post('time_zone', true),
                );
                    // $this->admin_model->edit_option($udata, $id, 'users');
                    $this->session->set_flashdata('msg', 'Updated Successfully.');


                // Payment setup
                $billing = $this->input->post('billing_type', true);
                $expire_on = ($billing == 'monthly')
                    ? date('Y-m-d', strtotime('+1 month'))
                    : date('Y-m-d', strtotime('+12 month'));

                $price = $this->input->post('amount', true);
                $pdata = array(
                    'puid' => random_string('numeric', 5),
                    'user_id' => $id,
                    'package' => $package,
                    'payment_type' => 'Manual',
                    'billing_type' => $billing,
                    'amount' => $price,
                    'status' => $this->input->post('payment_status', true),
                    'created_at' => my_date_now(),
                    'expire_on' => $expire_on
                );
                    $this->admin_model->update_payment($pdata, $id, 'payment');
                    $this->session->set_flashdata('msg', 'Updated Successfully.');


            // Create custom plan user record
            // Check if a custom plan already exists for this user
            $exists = $this->db->get_where('custom_plan_user', ['user_id' => $id])->row();

            $cdata = array(
                'user_id'    => $id,
                'plan_name'  => "Custom",
                'start_date' => my_date_now(),
                'end_date'   => $expire_on,
                'updated_at' => my_date_now(),
            );

            if ($exists) {
                // Update existing record
                $this->db->where('user_id', $id);
                $this->db->update('custom_plan_user', $cdata);
            } else {
                // Insert new record
                $this->db->insert('custom_plan_user', $cdata);
            }

            $this->session->set_flashdata('msg', 'Updated Successfully.');


            // Save modal features data
            $features = $this->input->post('features');
                $this->save_custom_plan_features($id, $features);
                $this->add_org_settings($id, $features, $this->input->post('time_zone', true));

                redirect(base_url('admin/users'));
            // }
        }
    }
    public function add_org_settings($user_id, $features, $tz)
    {
        // Default organization settings
        $flags = [
            'user_id'                  => $user_id,
            'screenshot_flag'          => 1,
            'screenshot_time_interval' => 10,
            'webcam_flag'              => 1,
            'webcam_time_interval'     => 10,
            'mouse_move_flag'          => 1,
            'mouse_move_threshold'     => 20,
            'key_stroke_flag'          => 1,
            'key_stroke_threshold'     => 40,
            'idle_time_flag'           => 1,
            'timecards_time_interval'  => 5,
            'time_zone'                => $tz,
        ];

        $flags = $this->security->xss_clean($flags);

        // Safely extract features from numeric keys
        $screenshot_feature = isset($features[6]) ? $features[6] : null; // Screenshots
        $webcam_feature     = isset($features[7]) ? $features[7] : null; // Webcam screenshots

        $this->db->trans_start();

        // Check if org_settings already exist
        $exists = $this->db->get_where('org_settings', ['user_id' => $user_id])->row();

        // Handle screenshot feature (ID = 6)

        // Always set timestamps
        $now = get_user_datetime_only($user_id);
        $flags['updated_at'] = $now;

        if ($exists) {
            // Handle Screenshot feature (ID = 6)
            if ($screenshot_feature) {
                $flag = isset($screenshot_feature['flag']) ? (int)$screenshot_feature['flag'] : 0;
                $option = isset($screenshot_feature['option']) ? strtolower($screenshot_feature['option']) : '';

                switch ($option) {
                    case 'basic':
                        // Always set to 10 for basic
                        $flags['screenshot_time_interval'] = 10;
                        break;

                    case 'standard':
                        // Only change if current interval is 2
                        if ($exists->screenshot_time_interval == 2) {
                            $flags['screenshot_time_interval'] = 5;
                        } else {
                            // Keep existing interval (no change)
                            unset($flags['screenshot_time_interval']);
                        }
                        break;

                    case 'premium':
                        // No changes for premium
                        unset($flags['screenshot_time_interval']);
                        break;

                    default:
                        break;
                }
            }

            if ($webcam_feature) {
                $option = strtolower($webcam_feature['option'] ?? '');
                switch ($option) {
                    case 'basic':
                        $flags['webcam_flag'] = 0;
                        $flags['webcam_time_interval'] = 10;
                        break;

                    case 'standard':
                        // Do not modify webcam settings (keep existing)
                        unset($flags['webcam_flag'], $flags['webcam_time_interval']);
                        break;

                    case 'premium':
                        $flags['webcam_flag'] = 1;
                        $flags['webcam_time_interval'] = 10;
                        break;
                }
            }
            unset($flags['idle_time_flag'], $flags['timecards_time_interval']);


            // Update existing settings
            $this->db->where('user_id', $user_id)->update('org_settings', $flags);
            log_message('info', "Org settings updated for user {$user_id}");
        } else {
            if ($screenshot_feature) {
                $flag = isset($screenshot_feature['flag']) ? (int)$screenshot_feature['flag'] : 0;
                $option = isset($screenshot_feature['option']) ? strtolower($screenshot_feature['option']) : '';

                switch ($option) {
                    case 'basic':
                        $flags['screenshot_flag'] = 1;
                        $flags['screenshot_time_interval'] = 10;
                        break;
                    case 'standard':
                        $flags['screenshot_flag'] = 1;
                        $flags['screenshot_time_interval'] = 10;
                        break;
                    case 'premium':
                        $flags['screenshot_flag'] = 1;
                        $flags['screenshot_time_interval'] = 10;
                        break;
                    default:
                        break;
                }
            }

            // Handle webcam feature (ID = 7)
            if ($webcam_feature) {
                $flag = isset($webcam_feature['flag']) ? (int)$webcam_feature['flag'] : 0;
                $option = isset($webcam_feature['option']) ? strtolower($webcam_feature['option']) : '';

                switch ($option) {
                    case 'basic':
                        $flags['webcam_flag'] = 0;
                        $flags['webcam_time_interval'] = 10;
                        break;
                    case 'standard':
                        $flags['webcam_flag'] = 0;
                        $flags['webcam_time_interval'] = 10;
                        break;
                    case 'premium':
                        $flags['webcam_flag'] = 1;
                        $flags['webcam_time_interval'] = 10;
                        break;
                    default:
                        break;
                }
            }
            // Insert new settings
            $flags['created_at'] = $now;
            $this->db->insert('org_settings', $flags);
            log_message('info', "Org settings inserted for user {$user_id}");
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            log_message('error', "Failed to save org_settings for user {$user_id}");
            return false;
        }

        return true;
    }


    private function save_custom_plan_features($user_id, $features)
    {
        if (empty($features) || !is_array($features)) return;

        // Initialize all columns with default values
        $data = [
            'customer_id' => $user_id,
            'created_at' => my_date_now(),
            'updated_at' => my_date_now(),
        ];

        // Map features based on feature name
        foreach ($features as $feature_id => $feature_data) {

            $flag = isset($feature_data['flag']) ? 1 : 0;
            $option = isset($feature_data['option']) ? $feature_data['option'] : '';

            switch (strtolower($feature_id)) {
                case 1:
                    $data['activity_log_flag'] = $flag;
                    $data['activity_log_feature'] = $option;
                    break;
                case 2:
                    $data['time_cards_flag'] = $flag;
                    $data['time_cards_feature'] = $option;
                    break;
                case 3:
                    $data['notification_flag'] = $flag;
                    $data['notification_feature'] = $option;
                    break;
                case 4:
                    $data['organization_settings_flag'] = $flag;
                    $data['organization_settings_feature'] = $option;
                    break;
                case 5:
                    $data['employee_settings_flag'] = $flag;
                    $data['employee_settings_feature'] = $option;
                    break;
                case 6:
                    $data['screenshots_flag'] = $flag;
                    $data['screenshots_feature'] = $option;
                    break;
                case 7:
                    $data['webcam_screenshots_flag'] = $flag;
                    $data['webcam_screenshots_feature'] = $option;
                    break;
                case 8:
                    $data['live_monitoring_flag'] = $flag;
                    $data['live_monitoring_feature'] = $option;
                    break;
                case 9:
                    $data['time_approval_flag'] = $flag;
                    $data['time_approval_feature'] = $option;
                    break;
                case 10:
                    $data['no_of_employees_flag'] = $flag;
                    $data['no_of_employees_feature'] = $option;
                    break;
                case 11:
                    $data['application_usage_flag'] = $flag;
                    $data['application_usage_feature'] = $option;
                   break;
                default:
                    // Add extra columns if new features are added later
                    break;
            }
        }
        // Save to DB
        $this->db->insert('custom_plan_feature', $data);
    }
}
