<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Home_Controller {

	public function __construct()
    {
        parent::__construct();
        //check auth
        if (!is_admin()) {
            redirect(base_url());
        }
    }

    public function index()
    {
        $this->all_users('all');
    }


    //all users list
    public function all_users($type)
    {
        $data = array();

        //initialize pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('admin/users/all_users/'.$type);
        $total_row = $this->admin_model->get_all_users(1 , 0, 0, $type);
        $config['total_rows'] = $total_row;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        
        $page = $this->security->xss_clean($this->input->get('page'));
        if (empty($page)) {
            $page = 0;
        }
        if ($page != 0) {
            $page = $page - 1;
        }

        $data['page_title'] = 'Users';
        $data['packages'] = $this->admin_model->select_asc('package');
        $data['countries'] = $this->admin_model->select_asc('country');
        $data['users'] = $this->admin_model->get_all_users(0 , $config['per_page'], $page * $config['per_page'], $type);
        $data["timezone"] = $this->admin_model->get_timezone_list();
        $data['main_content'] = $this->load->view('admin/users', $data, TRUE);
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
        $data['countries'] = $this->admin_model->select_asc('country');
        $data['main_content'] = $this->load->view('admin/users',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function add()
    {  
        if($_POST)
        {   
            $id = $this->input->post('id', true);
    
            //validate inputs
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
                    $new_password = $this->input->post('password');
                    $password = hash_password($this->input->post('password'));
                }
               $mail = $this->input->post('email', true);
                $email = $this->auth_model->check_email($mail);
                // Manually check email in employees table
                $this->db->where('LOWER(email)', $mail);
                $exists_in_employees = $this->db->get('employees')->row();
                
                if ($email || $exists_in_employees){
                    $this->session->set_flashdata('msg', trans('email-exist'));
                    redirect(base_url('admin/users'));
                }
                $package = $this->input->post('package', true);

                if ($package == 1) {
                    $user_type = 'trial';
                    $trial_expire = date('Y-m-d', strtotime('+' . $this->settings->trial_days . ' days'));
                } else {
                    $user_type = 'registered';
                    $trial_expire = date('Y-m-d');
                }
                
                $udata = array(
                    'name' => $this->input->post('name', true),
                    'user_name' => str_slug($this->input->post('name', true)),
                    'slug' => str_slug($this->input->post('name', true)),
                    'email' => $this->input->post('email', true),
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
                    'country'  => $this->input->post('country', true),
                    'timezone' => $this->input->post('time_zone', true),
                );
    
                if ($id != '') {
                    $this->admin_model->edit_option($udata, $id, 'users');
                    $this->session->set_flashdata('msg', trans('updated-successfully')); 
                } else {
                    $id = $this->admin_model->insert($udata, 'users');
                    $this->session->set_flashdata('msg', trans('inserted-successfully'));
    
                    $rand_uid = substr(random_string('numeric', 5).mt_rand(), 0, 8);
                    $uid = ltrim($rand_uid, '0');
    
                    $company_name = 'Company '.$uid;
    
                    $data=array(
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
    
                    // Add org_settings for trial users
                    if ($user_type == 'trial') {
                        $org_settings = array(
                            'user_id' => $id,
                            'screenshot_flag' => 1, // Enable screenshot by default for trial
                            'screenshot_time_interval' => 5, // Default 5 minutes interval
                            'webcam_flag' => 1, // Enable webcam by default for trial
                            'webcam_time_interval' => 5, // Default 5 minutes interval
                            'mouse_move_flag' => 1, // Enable mouse tracking
                            'mouse_move_threshold' => 20, // Default threshold
                            'key_stroke_flag' => 1, // Enable keystroke tracking
                            'key_stroke_threshold' => 40, // Default threshold
                            'idle_time_flag' => 1, // Enable idle time tracking
                            'timecards_time_interval' => 5, // Default 5 minutes interval
                            'created_at' => my_date_now(),
                            'updated_at' => my_date_now(),
                            'time_zone' => $this->input->post('time_zone', true)
                        );
                        $this->common_model->insert($org_settings, 'org_settings');
                        $this->admin_model->intial_department_storing($id, $uid);   // (user_id, business_uid)
                    }
                    
                }
               
                $payment = $this->admin_model->get_user_payment($id);   
    
                $plan = $this->input->post('package', true);
                $billing = $this->input->post('billing_type', true);
    
                $package = $this->common_model->get_by_id($plan, 'package');
                if ($billing == 'week') {
                    $price = $package->monthly_price;
                    $expire_on = date('Y-m-d', strtotime('+1 week'));
                } elseif ($billing == 'monthly') {
                    $price = $package->monthly_price;
                    $expire_on = date('Y-m-d', strtotime('+1 month'));
                } else {
                    // yearly
                    $price = $package->price;
                    $expire_on = date('Y-m-d', strtotime('+12 month'));
                }
    
                $pdata = array(
                    'puid' => random_string('numeric', 5),
                    'user_id' => $id,
                    'package' => $this->input->post('package', true),
                    'billing_type' => $this->input->post('billing_type', true),
                    'amount' => $price,
                    'status' => $this->input->post('payment_status', true),
                    'created_at' => my_date_now(),
                    'expire_on' => $expire_on
                );
                
                // Insert or update payment
                if (empty($payment)) {
                    $this->admin_model->insert($pdata, 'payment');
                } else {
                    $this->admin_model->update_payment($pdata, $id, 'payment');
                }
                $pkg  = (int) $this->input->post('package', true);
                $tz   = $this->security->xss_clean($this->input->post('time_zone', true)) ?: 'UTC';

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
                            'timecards_time_interval'  => 5,
                            'time_zone'                => $tz,
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
                            'timecards_time_interval'  => 5,
                            'time_zone'                => $tz,
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
                            'timecards_time_interval'  => 5,
                            'time_zone'                => $tz,
                        ],
                    ];

                    // Clean for security
                    $flags = $this->security->xss_clean($flagSets[$pkg]);

                    $this->db->trans_start();

                    $exists = $this->db->get_where('org_settings', ['user_id' => $id])->row();

                    if ($exists) {
                        $current_pkg = !empty($payment) ?  $payment->package_id : null;
                        $new_pkg     = $plan;
                        $skipOrgSave = ($current_pkg == 3 && $new_pkg == 4);
                         if(!$skipOrgSave){
                        $flags['updated_at'] = my_date_now();
                        $this->db->where('user_id', $id)->update('org_settings', $flags);
                         }
                    } else {
                        $flags['created_at'] = my_date_now();
                        $flags['updated_at'] = my_date_now();
                        $this->db->insert('org_settings', $flags);
                        // ensure default departments exist for non-trial sign-ups
                        $business_uid = $this->db
                            ->select('uid')
                            ->get_where('business', ['user_id' => $id])
                            ->row('uid');
                        $this->admin_model->intial_department_storing($id, $business_uid);
                    }

                    $this->db->trans_complete();

                    if (!$this->db->trans_status()) {
                        log_message('error', "Failed to save org_settings for user {$id} in package {$pkg}");
                    }
                }


                redirect(base_url('admin/users'));
            }
        }      
    }   



    //all pro users list
    public function pro($type = 'pro')
    {
        $data = array();
        $data['page_title'] = 'Users';
        $data['users'] = $this->admin_model->get_all_users($type);
        $data['main_content'] = $this->load->view('admin/users', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    //active or deactive post
    public function status_action($type, $id) 
    {
        check_status();

        $data = array(
            'status' => $type
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'users');

        if($type ==1):
            $this->session->set_flashdata('msg', trans('msg-activated')); 
        else:
            $this->session->set_flashdata('msg', trans('msg-deactivated')); 
        endif;
        redirect(base_url('admin/users'));
    }

    //change user role
    public function change_account($id) 
    {
        $data = array(
            'account_type' => $this->input->post('type', false)
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->edit_option($data, $id, 'users');
        $this->session->set_flashdata('msg', trans('msg-updated'));
        redirect(base_url('admin/users'));
    }

    public function edit__($id)
    {  
        check_status();

        if($_POST)
        {   
            $id = $this->input->post('id', true);
            $data=array(
                'name' => $this->input->post('name', true),
                'slug' => str_slug(trim($this->input->post('name', true))),
                'email' => $this->input->post('email', true),
                'phone' => $this->input->post('phone', true),
                'designation' => $this->input->post('designation', true),
                'address' => $this->input->post('address', true),
                'account_type' => $this->input->post('type', false)
            );
            $data = $this->security->xss_clean($data);
            $this->admin_model->edit_option($data, $id, 'users');
            $this->session->set_flashdata('msg', trans('msg-updated'));
            redirect(base_url('admin/users'));
        }

        $data = array();
        $data['page_title'] = 'Edit';   
        $data['user'] = $this->admin_model->get_by_id($id, 'users');
        $data['main_content'] = $this->load->view('admin/user_edit',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    
    public function active($id) 
    {
        $data = array(
            'status' => 1
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'users');
        $this->session->set_flashdata('msg', trans('msg-activated')); 
        redirect(base_url('admin/users'));
    }


    public function reset_password($id) 
    {
        $data = array(
            'password' => hash_password('1234')
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'users');
        $this->session->set_flashdata('msg', trans('msg-updated')); 
        echo json_encode(array('st' => 1));
    }


    public function delete($user_id)
    {
        check_status();
        
        $business = $this->admin_model->get_business_by_user($user_id);
        foreach ($business as $biz) {
            $this->admin_model->delete_payment_records($biz->uid,'payment_records'); 
        }
        $this->admin_model->delete_by_user($user_id,'payment'); 
        $this->admin_model->delete_by_user($user_id,'business');
        $this->admin_model->delete($user_id,'users'); 
        echo json_encode(array('st' => 1));
    }


}