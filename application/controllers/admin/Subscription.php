<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();

        // if (!is_user()) {
        //     redirect(base_url());
        // }
    }

    public function index()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        $data = array();
        $this->upgrade_plans();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'Subscription';
        $data['main_page'] = 'plan&pack';
        $data['user'] = $this->common_model->get_my_package();


        if (is_custom_plan_user()) {

            $data['page_title'] = 'Custom_plan';
            $data['custom_plan'] = $this->get_custom_plan_feature();
            $data['main_content'] = $this->load->view('include/no_employees_view.php', $data, TRUE);
            $this->load->view('admin/index', $data);
        } else {

            if (user()->user_type == 'trial') {
                $data['packages'] = $this->admin_model->get_active_packages('package');
                $data['features'] = $this->admin_model->select_asc('package_features');
                $page_load = 'trial_subscription';
            } else {
                $data['packages'] = $this->admin_model->get_active_packages_for_subscription('package');
                $data['features'] = $this->admin_model->get_features_for_subscription('package_features');
                $page_load = 'subscription';
            }
            if (empty($data['packages'])) {
                $data['no_active_package'] = 1;
                $data['main_content'] = $this->load->view('include/no_employees_view.php', $data, TRUE);
                $this->load->view('admin/index', $data);
            } else {
                $data['main_content'] = $this->load->view('admin/user/' . $page_load, $data, TRUE);
                $this->load->view('admin/index', $data);
            }
        }
    }

    // custom_plan

    public function custom_plan_payment()
    {
        // Generate unique payment UID
        $puid = random_string('numeric', 10);

        // Get user ID from session
        $user_id = user()->id; // or $this->session->userdata('id');

        // Get the user’s latest payment info (if needed)
        $payment = $this->admin_model->get_user_payment($user_id);

        // Get billing type from previous payment (or default)
        $billing_type = isset($payment->billing_type) ? $payment->billing_type : 'monthly';

        // Get the selected package details
        $package = 5; // custom plan package ID
        if (empty($package)) {
            $this->session->set_flashdata('error', 'Package not found.');
            redirect(base_url('admin/subscription'));
            return;
        }

        // Calculate amount and expiry date
        if ($billing_type === 'monthly') {
            $amount = (settings()->enable_discount == 1)
                ? get_discount($package->monthly_price, $package->dis_month)
                : round($package->monthly_price);
            $expire_on = date('Y-m-d', strtotime('+1 month'));
        } else {
            $amount = (settings()->enable_discount == 1)
                ? get_discount($package->yearly_price, $package->dis_year)
                : round($package->yearly_price);
            $expire_on = date('Y-m-d', strtotime('+12 months'));
        }

        // Expire previous active payments
        $payments = $this->admin_model->get_previous_payments($user_id);
        if (!empty($payments)) {
            foreach ($payments as $pay) {
                $this->common_model->edit_option(['status' => 'expired'], $pay->id, 'payment');
            }
        }

        // Save new payment record
        $pay_data = [
            'user_id'      => $user_id,
            'puid'         => $puid,
            'package'      => $package,
            'billing_type' => $billing_type,
            'payment_type' => 'Manual',
            'status'       => 'pending',
            'created_at'   => my_date_now(),
            'expire_on' => $expire_on
        ];

        $this->common_model->insert($pay_data, 'payment');

        // Flash message and redirect
        $this->session->set_flashdata('msg', 'Your request has been sent. Please wait for the admin to take further action.');
        redirect(base_url('admin/subscription/current_plan'));
    }

    // get_custom_plan_feature

    public function get_custom_plan_feature()
    {
        $user_id = $this->session->userdata('id'); // Assuming 'id' is the correct session key for user_id

        if ($user_id) {
            $this->db->select('user_id');
            $this->db->from('custom_plan_user');
            $this->db->where('user_id', $user_id); // Ensure the user_id exists in custom_plan_user
            $query = $this->db->get();

            if ($query->num_rows() >= 1) {
                $this->db->select('*');
                $this->db->from('custom_plan_feature');
                $this->db->where('customer_id', $user_id); // Match customer_id with user_id
                $this->db->order_by('id', 'DESC'); // Order by id in descending order
                $this->db->limit(1); // Limit to 1 result to get the first feature based on id DESC
                $feature_query = $this->db->get();

                if ($feature_query->num_rows() == 1) {
                    return $this->map_flags_to_features($feature_query->row());  // Return a single row (not an array of rows)
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    // Map flags to their corresponding feature levels (basic, standard, premium)
    private function map_flags_to_features($feature)
    {
        // Initialize an array to store the mapped features
        $mapped_features = [];

        // Map each flag to the corresponding feature
        if ($feature->activity_log_flag) {
            $mapped_features['activity_log'] = $this->fetch_package_feature_values($feature->activity_log_flag, 1, $feature->activity_log_feature);
        }
        if ($feature->time_cards_flag) {
            $mapped_features['time_cards'] = $this->fetch_package_feature_values($feature->time_cards_flag, 2, $feature->time_cards_feature);
        }
        if ($feature->notification_flag) {
            $mapped_features['notification'] = $this->fetch_package_feature_values($feature->notification_flag, 3, $feature->notification_feature);
        }
        if ($feature->organization_settings_flag) {
            $mapped_features['organization_settings'] = $this->fetch_package_feature_values($feature->organization_settings_flag, 4, $feature->organization_settings_feature);
        }
        if ($feature->employee_settings_flag) {
            $mapped_features['employee_settings'] = $this->fetch_package_feature_values($feature->employee_settings_flag, 5, $feature->employee_settings_feature);
        }
        if ($feature->screenshots_flag) {
            $mapped_features['screenshots'] = $this->fetch_package_feature_values($feature->screenshots_flag, 6, $feature->screenshots_feature);
        }
        if ($feature->webcam_screenshots_flag) {
            $mapped_features['webcam_screenshots'] = $this->fetch_package_feature_values($feature->webcam_screenshots_flag, 7, $feature->webcam_screenshots_feature);
        }
        if ($feature->live_monitoring_flag) {
            $mapped_features['live_monitoring'] = $this->fetch_package_feature_values($feature->live_monitoring_flag, 8, $feature->live_monitoring_feature);
        }
        if ($feature->time_approval_flag) {
            $mapped_features['time_approval'] = $this->fetch_package_feature_values($feature->time_approval_flag, 9, $feature->time_approval_feature);
        }
        if ($feature->no_of_employees_flag) {
            $mapped_features['no_of_employees'] = $this->fetch_package_feature_values($feature->no_of_employees_flag, 10, $feature->no_of_employees_feature);
        }
        if ($feature->application_usage_flag) {
            $mapped_features['application_usage'] = $this->fetch_package_feature_values($feature->application_usage_flag, 11, $feature->application_usage_feature);
        }

        // Return the mapped features
        return $mapped_features;
    }

    // Helper function to fetch package feature values based on the feature_id and feature column (basic, standard, premium)
    private function fetch_package_feature_values($flag, $feature_id, $feature_column)
    {
        $features_with_values = null;

        if ($flag) {
            // Query the package_features table to fetch the feature value (basic, standard, premium) using the feature column
            $this->db->select('name, ' . $feature_column . ' as feature'); // Select the appropriate feature value column
            $this->db->from('package_features');
            $this->db->where('id', $feature_id); // Match the feature ID

            // Execute the query
            $query = $this->db->get();

            // Check if the package feature is found and return the value
            if ($query->num_rows() == 1) {
                $package_feature = $query->row();
                $features_with_values = $package_feature->feature; // Get the feature value (basic, standard, premium)
            }
        }

        // If the flag is not set (0 or null), return null
        return $features_with_values;
    }




    public function currentPlan()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        $data = array();
        $this->upgrade_plans();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'CurrentPlan';
        $data['main_page'] = 'plan&pack';
        $data['user'] = $this->common_model->get_my_package();
        $data['packages'] = $this->admin_model->get_active_packages('package');
        $data['features'] = $this->admin_model->select_asc('package_features');
        $data['main_content'] = $this->load->view('admin/user/current_plan', $data, true);
        $this->load->view('admin/index', $data);
    }


    public function upgrade($slug = '', $billing_type = '', $status = 1)
    {
        if ($status == 0) {
            $data = array();
            $data['slug'] = $slug;
            $data['billing_type'] = $billing_type;

            $data['main_content'] = $this->load->view('admin/user/payment_confirm', $data, TRUE);
            $this->load->view('admin/index', $data);
        } else {


            $data = array();
            $data['page_title'] = 'Upgrade';
            $data['page'] = 'Payment';
            $payment = $this->common_model->get_user_payment();
            $uid = random_string('numeric', 5);
            $data['payment_id'] = (user()->user_type == 'trial' ? $uid : $payment->puid);
            $data['billing_type'] = $billing_type;
            $data['package'] = $this->common_model->get_package_by_slug($slug);
            $payments = $this->admin_model->get_previous_payments(user()->id);
            $package = $data['package'];
            $uid = random_string('numeric', 5);



            if ($billing_type == 'monthly'):
                $amount = $package->monthly_price;
                $expire_on = date('Y-m-d', strtotime('+1 month'));
            else:
                $amount = $package->yearly_price;
                $expire_on = date('Y-m-d', strtotime('+12 month'));
            endif;

            if (number_format($amount, 0) == 0):
                $status = 'verified';
            else:
                $status = 'pending';
            endif;


            //create payment
            $pay_data = array(
                'user_id' => user()->id,
                'puid' => $uid,
                'package' => $package->id,
                'amount' => $amount,
                'billing_type' => $billing_type,
                'status' => $status,
                'created_at' => my_date_now(),
                'expire_on' => $expire_on
            );
            $pay_data = $this->security->xss_clean($pay_data);

            if ($this->settings->enable_paypal == 0 || number_format($amount, 0) == 0) {
                foreach ($payments as $pay) {
                    $pays_data = array(
                        'status' => 'expired'
                    );
                    $this->common_model->edit_option($pays_data, $pay->id, 'payment');
                }

                $this->common_model->insert($pay_data, 'payment');

                if (user()->user_type == 'trial') {
                    //update user type
                    $user_data = array(
                        'user_type' => 'registered',
                        'trial_expire' => date('Y-m-d')
                    );
                    $this->common_model->edit_option($user_data, user()->id, 'users');
                }
                // ✅ APPLY ORG SETTINGS FLAGS HERE
                // $this->apply_org_settings_flags(user()->id, $package->id);
            }

            if (number_format($amount, 0) == 0) {
                redirect(base_url('admin/subscription/upgrade_plan'));
            } else {
                if ($this->settings->enable_paypal == 1) {
                    $data['main_content'] = $this->load->view('admin/user/payment', $data, TRUE);
                    $this->load->view('admin/index', $data);
                } else {
                    redirect(base_url('admin/subscription/upgrade_plan'));
                }
            }
        }
    }


    //payment success
    public function payment_success($billing_type, $package_id, $payment_id)
    {

        $payments = $this->admin_model->get_previous_payments(user()->id);
        foreach ($payments as $pay) {
            $pays_data = array(
                'status' => 'expired'
            );
            $this->common_model->edit_option($pays_data, $pay->id, 'payment');
        }


        $package = $this->common_model->get_package_by_id($package_id);
        $payment = $this->common_model->get_payment($payment_id);
        $uid = random_string('numeric', 5);

        if ($billing_type == 'monthly'):
            $amount = $package->monthly_price;
            $expire_on = date('Y-m-d', strtotime('+1 month'));
        else:
            $amount = $package->yearly_price;
            $expire_on = date('Y-m-d', strtotime('+12 month'));
        endif;

        $data = array();
        $pay_data = array(
            'user_id' => user()->id,
            'package' => $package->id,
            'puid' => $payment_id,
            'status' => 'verified',
            'billing_type' => $billing_type,
            'amount' => $amount,
            'expire_on' => $expire_on,
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
        $this->common_model->insert($pay_data, 'payment');

        if (user()->user_type == 'trial') {
            //update user type
            $user_data = array(
                'user_type' => 'registered',
                'trial_expire' => '0000-00-00'
            );
            $this->common_model->edit_option($user_data, user()->id, 'users');
        }

        //affiliate code
        $referral_settings = $this->admin_model->get_referral_settings();

        if ($referral_settings->is_enable == 1) {
            $register_user = $this->admin_model->get_by_referral_user(user()->id);

            $commision = $referral_settings->commision_rate;
            $commision_amount = ($commision * $amount) / 100;

            $ref_data = array(
                'status' => 1,
                'amount' => $amount,
                'commision' => $commision,
                'commision_amount' => $commision_amount
            );
            $this->admin_model->edit_option($ref_data, $register_user->id, 'referrals');



            $user = $this->admin_model->get_by_referral_id($register_user->referrar_id);

            if (!empty($register_user)) {
                $user_id = $user->id;
                $ref_earn = $user->referral_earn;
                $update_balance = $ref_earn + $register_user->commision_amount;

                $earn_data = array(
                    'referral_earn' => $update_balance,
                );

                $earn_data = $this->security->xss_clean($earn_data);
                $this->admin_model->edit_option($earn_data, $user_id, 'users');
            }
        }
        //affiliate code

        $this->add_org_settings(user()->id, $package_id);
        // $view_data['success_msg'] = 'Success';
        // $view_data['main_content'] = $this->load->view('purchase', $view_data, TRUE);
        // $this->load->view('index', $view_data);
        // $data['success_msg'] = 'Success';
        // $data['main_content'] = $this->load->view('admin/user/payment_msg', $data, TRUE);
        // $this->load->view('admin/index', $data);
        $data['success_msg'] = 'Success';
        $data['main_content'] = $this->load->view('purchase', $data, TRUE);
        $this->load->view('index', $data);
    }

    public function add_org_settings($user_id, $pkg)
    {
        $package = (int) $pkg;

        // Get user timezone
        $user = $this->db->select('timezone')->get_where('users', ['id' => $user_id])->row();
        $tz = !empty($user->timezone) ? $user->timezone : 'UTC';

        // Only proceed for valid packages
        if (!in_array($package, [2, 3, 4], true)) {
            log_message('error', "Invalid package {$package} for user {$user_id}");
            return false;
        }

        // Define package-specific settings
        $flagSets = [
            2 => [ // Silver plan
                'user_id'                  => $user_id,
                'screenshot_flag'          => 1,
                'screenshot_time_interval' => 10,
                'webcam_flag'              => 0,  // Webcam off for silver
                'webcam_time_interval'     => 5,
                'mouse_move_flag'          => 1,
                'mouse_move_threshold'     => 20,
                'key_stroke_flag'          => 1,
                'key_stroke_threshold'     => 40,
                'idle_time_flag'           => 1,
                'timecards_time_interval'  => 5,
                'time_zone'                => $tz,
            ],
            3 => [ // Gold plan
                'user_id'                  => $user_id,
                'screenshot_flag'          => 1,
                'screenshot_time_interval' => 10,
                'webcam_flag'              => 1,  // Webcam on
                'webcam_time_interval'     => 5,
                'mouse_move_flag'          => 1,
                'mouse_move_threshold'     => 20,
                'key_stroke_flag'          => 1,
                'key_stroke_threshold'     => 40,
                'idle_time_flag'           => 1,
                'timecards_time_interval'  => 5,
                'time_zone'                => $tz,
            ],
            4 => [ // Platinum plan
                'user_id'                  => $user_id,
                'screenshot_flag'          => 1,
                'screenshot_time_interval' => 10,
                'webcam_flag'              => 1,
                'webcam_time_interval'     => 5,
                'mouse_move_flag'          => 1,
                'mouse_move_threshold'     => 20,
                'key_stroke_flag'          => 1,
                'key_stroke_threshold'     => 40,
                'idle_time_flag'           => 1,
                'timecards_time_interval'  => 5,
                'time_zone'                => $tz,
            ],
        ];

        $flags = $this->security->xss_clean($flagSets[$package]);

        $this->db->trans_start();

        // Check if org_settings already exist
        $exists = $this->db->get_where('org_settings', ['user_id' => $user_id])->row();

        if ($exists) {
            // Fetch last 2 packages from payment table
            // $payments = $this->db
            //     ->select('package')
            //     ->from('payment')
            //     ->where('user_id', $user_id)
            //     ->order_by('id', 'DESC')
            //     ->limit(2)
            //     ->get()
            //     ->result();

            // if (count($payments) >= 2) {
            //     $previous_package = (int) $payments[1]->package; // second latest

            if ($pkg == 2) {
                // if (isset($flags['webcam_flag'])) {
                //     $flags['webcam_flag'] = 0; // Reset or set webcam to 0
                // }
                // // Update settings only if previous package was 1
                // $flags['updated_at'] = my_date_now();
                // $this->db->where('user_id', $user_id)->update('org_settings', $flags);
                $this->db->where('user_id', $user_id)->update('org_settings', [
                    'webcam_flag' => 0, // Reset or set webcam to 0
                    'updated_at'  => my_date_now(), // Update timestamp
                ]);
                log_message('info', "Org settings updated for user {$user_id}, package {$package}");
            }
            // else {
            //     log_message('info', "User {$user_id} previous package was {$previous_package}, skipping update");
            // }

            else {
                log_message('error', "Not enough payment history for user {$user_id}");
            }
        } else {
            // Insert new settings (first time setup, no payment check needed)
            $flags['created_at'] = my_date_now();
            $flags['updated_at'] = my_date_now();
            $this->db->insert('org_settings', $flags);
            log_message('info', "Org settings inserted for user {$user_id}, package {$package}");
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            log_message('error', "Failed to save org_settings for user {$user_id} in package {$package}");
            return false;
        }

        return true;
    }

    public function upgrade_plans()
    {
        if (!empty(settings()->sid) && settings()->sid == '2020-02-02') {
            return true;
        } else {
            if (settings()->sid < '2021-12-14') {
                return true;
            } else {
                if (settings()->site_info == 2) {
                    return true;
                } else {
                    $user_data = array(
                        'enable_paypal' => '0'
                    );
                    $this->common_model->edit_option($user_data, 1, 'settings');
                }
            }
        }
    }


    //payment cancel
    public function payment_cancel($billing_type, $package_id, $payment_id)
    {
        $data = array();
        $data['error_msg'] = 'Error';
        $data['main_content'] = $this->load->view('admin/user/payment_msg', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    protected function apply_org_settings_flags(int $id, int $pkg): void
    {
        // --- original content begins ---
        if (in_array($pkg, [2, 3, 4], true)) {

            // Define full flags for each package
            $flagSets = [
                2 => [
                    'user_id'                  => $id,
                    'screenshot_flag'          => 1,
                    'screenshot_time_interval' => 10,
                    'webcam_flag'              => 0,
                    'webcam_time_interval'     => 5,
                    'mouse_move_flag'          => 1,
                    'mouse_move_threshold'     => 20,
                    'key_stroke_flag'          => 1,
                    'key_stroke_threshold'     => 40,
                    'idle_time_flag'           => 1,
                    'timecards_time_interval'  => 5
                ],
                3 => [
                    'user_id'                  => $id,
                    'screenshot_flag'          => 1,
                    'screenshot_time_interval' => 10,
                    'webcam_flag'              => 1,
                    'webcam_time_interval'     => 5,
                    'mouse_move_flag'          => 1,
                    'mouse_move_threshold'     => 20,
                    'key_stroke_flag'          => 1,
                    'key_stroke_threshold'     => 40,
                    'idle_time_flag'           => 1,
                    'timecards_time_interval'  => 5
                ],
                4 => [
                    'user_id'                  => $id,
                    'screenshot_flag'          => 1,
                    'screenshot_time_interval' => 10,
                    'webcam_flag'              => 1,
                    'webcam_time_interval'     => 5,
                    'mouse_move_flag'          => 1,
                    'mouse_move_threshold'     => 20,
                    'key_stroke_flag'          => 1,
                    'key_stroke_threshold'     => 40,
                    'idle_time_flag'           => 1,
                    'timecards_time_interval'  => 5
                ],
            ];

            // Clean for security
            $flags = $this->security->xss_clean($flagSets[$pkg]);

            $this->db->trans_start();

            $flags['updated_at'] = my_date_now();
            $this->db->where('user_id', $id)->update('org_settings', $flags);

            $this->db->trans_complete();

            if (!$this->db->trans_status()) {
                log_message('error', "Failed to save org_settings for user {$id} in package {$pkg}");
            }
        }
        // --- original content ends ---
    }
}
