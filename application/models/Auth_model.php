<?php
class Auth_model extends CI_Model {

    public function edit_option_md5($action, $id, $table)
    {
        $this->db->where('md5(id)',$id);
        $this->db->update($table,$action);
        return;
    }


    //is logged in
    public function is_logged_in()
    {
        //check if user logged in
        if ($this->session->userdata('logged_in') == TRUE && !empty($this->get_user($this->session->userdata('id')))) {
            return true;
        } else {
            return false;
        }
    }


    //function get user
    public function get_logged_user()
    {
        if ($this->is_logged_in()) {
            $this->db->select('u.*, c.name as country, c.currency_name, c.currency_code, c.currency_symbol');
            $this->db->from('users as u');
            $this->db->join('country as c', 'c.id = u.country', 'LEFT');
            $this->db->where('u.id', $this->session->userdata('id'));
            $query = $this->db->get();
            return $query->row();
        }
    }

    //get user by id
    public function get_user($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }
    public function get_user_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

   public function require_feature(int $featureId): void
    {
        // for security purpose
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('employee_logged_in')) {
            redirect('login');
        }
        
        if (!$this->session->userdata('is_org_admin') && !$this->session->userdata('is_org_ceo')) {
 
        $CI = &get_instance();
        $allowed = get_allowed_feature_ids();   // <- your existing helper

        if (!in_array($featureId, $allowed, true)) {
            // Either redirect...
            redirect('error-404');

            // ...or show a 403:
            // show_error('Forbidden', 403, 'Access denied');
            exit;
        }
    }
    }
    //is admin
    public function is_admin()
    {
        get_header_info();
        //check logged in
        if (!$this->is_logged_in()) {
            return false;
        }

        //check role
        if (user()->role == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    //is user
    public function is_user()
    {   
        get_header_info();
        //check logged in
        if (!$this->is_logged_in()) {
            return false;
        }

        //check role
        if (user()->role == 'user') {
            return true;
        } else {
            return false;
        }
    }


    //is pro user
    public function is_pro_user()
    {
        //check logged in
        if (!$this->is_logged_in()) {
            return false;
        }

        //check role
        if (user()->role == 'user' && user()->account_type == 'pro') {
            return true;
        } else {
            return false;
        }
    }



    //logout
    public function logout()
    {
        //unset user data
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('admin_logged_in');
        $this->session->unset_userdata('app_key');
    }


    // check post email
    public function check_roles_email($email)
    {
        $this->db->select('*');
        $this->db->from('users_role');
        $this->db->where('email', $email); 
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 1) {                 
            return $query->result();
        }else{
            $result = $this->check_email($email);
            return $result;
        }
    }


    // check post email
    public function check_email($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email); 
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 1) {                 
            return $query->result();
        }else{
            return false;
        }
    }
    // check post email
    public function check_emloyee_email($email)
    {
        $this->db->select('*');
        $this->db->from('employees');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }


    // check valid user by id
    public function validate_id($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('md5(id)', $id); 
        $this->db->limit(1);
        $query = $this->db->get();
        if($query -> num_rows() == 1)
        {                 
            return $query->row();
        }
        else{
            return false;
        }
    }

    // check post email
    public function validate_code($code)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('verify_code', $code); 
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 1) {                 
            return true;
        }else{
            return false;
        }
    }



    // check valid user
    function validate_user()
    {            
        
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $this->input->post('user_name'));
        $this->db->or_where('user_name', $this->input->post('user_name'));
        $this->db->limit(1);
        $query = $this->db->get();   
        if($query->num_rows() == 1){                 
           return $query->row();
        }else{
            $result = $this->validate_role();
            return $result;
        }
    }
   // check valid employee
    function validate_employee(){
        $this->db->select('*');
        $this->db->from('employees');
        $this->db->where('email', $this->input->post('user_name'));
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    // check valid staff
    function validate_role()
    {   
        $this->db->select('*');
        $this->db->from('users_role');
        $this->db->where('email', $this->input->post('user_name'));
        $this->db->limit(1);
        $query = $this->db->get();   
        if($query->num_rows() > 0)
        {                 
           return $query->row();
        }
        else{
            return FALSE;
        }
    }


    public function send_email($to, $subject, $message)
    {
        $this->load->library('email');

        $settings = get_settings();

        if ($settings->mail_protocol == "mail") {
            $config = Array(
                'protocol' => 'mail',
                'smtp_host' => $settings->mail_host,
                'smtp_port' => $settings->mail_port,
                'smtp_user' => $settings->mail_username,
                'smtp_pass' => $settings->mail_password,
                'smtp_timeout' => 100,
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'wordwrap' => TRUE
            );
        } else {
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => $settings->mail_host,
                'smtp_port' => $settings->mail_port,
                'smtp_user' => $settings->mail_username,
                'smtp_pass' => $settings->mail_password,
                'smtp_timeout' => 100,
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'wordwrap' => TRUE
            );
        }


        //initialize
        $this->email->initialize($config);

        //send email
        $this->email->from($settings->mail_username, $settings->application_name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->set_newline("\r\n");

        return $this->email->send();
    }
    // public function is_subscribed()
    // {
    //         $user = user(); // assuming this helper exists and returns the logged-in user
    //         if (!$user) return false;
    //         $user_id = $user->id;

    //     // Get user data
    //     $user = $this->db->get_where('users', ['id' => $user_id])->row();
    //     if (!$user) {
    //         return false;
    //     }

    //     // Calculate days left in trial
    //     $days_left = date_dif(date('Y-m-d'), $user->trial_expire);

    //     // Check if payment is verified for monthly/yearly
    //     $payment = $this->db->select('billing_type, status')
    //         ->where('user_id', $user_id)
    //         ->order_by('created_at', 'DESC')
    //         ->get('payment')
    //         ->row();

    //     $isSubscribed = ($days_left >= 0) || (
    //         $payment &&
    //         in_array($payment->billing_type, ['monthly', 'yearly']) &&
    //         $payment->status === 'verified'
    //     );

    //     return $isSubscribed;
    // }

    public function is_organization_subscribed($user_id)
    {
        if (!$user_id) return false;


        // Fetch full user data from DB
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        if (!$user) return false;

        // Case 1: Trial user - check trial expiry
        if ($user->user_type === 'trial' && !empty($user->trial_expire)) {
            $today  = new DateTime('today');
            $expire = new DateTime($user->trial_expire);

            if ($expire <= $today) {
                return false; // trial is still valid
            }
        }

        // Case 2: Registered user - check payment
        if ($user->user_type === 'registered') {
            $payment = $this->db->select('billing_type, status')
                ->where('user_id', $user_id)
                ->order_by('created_at', 'DESC')
                ->limit(1)
                ->get('payment')
                ->row();

            if ($payment->status != 'verified') {
                return false; // no valid subscription
            }
        }

        // Default fallback
        return true;
    }
    public function is_subscribed()
    {
        $user = user(); // assuming this helper returns the logged-in user
        $user_id = $user->id;

        if (!$user_id) {
            $user_id = $this->session->userdata('employee_org_id');
        }

        if (!$user_id) {
            return false;
        }
        // Fetch full user data from DB
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        if (!$user) return false;

        // Case 1: Trial user - check trial expiry
        if ($user->user_type === 'trial' && !empty($user->trial_expire)) {
            $today  = new DateTime('today');
            $expire = new DateTime($user->trial_expire);

            if ($expire <= $today) {
                return false; // trial is still valid
            }
        }

        // Case 2: Registered user - check payment
        if ($user->user_type === 'registered') {
            $payment = $this->db->select('billing_type, status')
                ->where('user_id', $user_id)
                ->order_by('created_at', 'DESC')
                ->limit(1)
                ->get('payment')
                ->row();

            if ($payment->status != 'verified') {
                return false; // no valid subscription
            }
        }

        // Default fallback
        return true;
    }

    public function is_employee()
    {

        if ($this->session->userdata('employee_id') && $this->session->userdata('is_employee')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function check_role_department(){

    }
    
    // public function is_pack_trial()
    // {

    //     $user_id = user()->id;
    //     $this->db->where('id', $user_id);
    //     $user = $this->db->get('users')->row();

    //     if ($user && strtolower($user->user_type) === 'trial') {
    //         return true;
    //     }
    //     return false;
    // }

    public function is_pack_trial()
{
    $user_id = user()->id;
    $this->db->where('id', $user_id);
    $user = $this->db->get('users')->row();

    if ($user && strtolower($user->user_type) === 'trial') {
        // Check if trial has expired
        if (isset($user->trial_expire)) {
            $today = date('Y-m-d');
            if ($user->trial_expire >= $today) {
                return true; // Still in trial period
            }
        }
        return false; // Either no expiration date set or trial has expired
    }
    return false;
}
    function has_verified_package($packageIds)
    {
        // $CI = &get_instance();          // CodeIgniter super‑object
        $user = user();                 // Your helper that returns the logged‑in user

        if (!$user) {
            return false;               // not logged in
        }

         $CI= $this->db->select('*')
            ->from('payment')
            ->where('user_id', $user->id)
            ->where('package', $packageIds)
            ->order_by('id', 'DESC')   // newest first
            ->limit(1);

        if($CI->status == "pending"){
            return false;
        }  
        return true;    // TRUE if a row exists
    }

    /* -------------------------------------------------
 |  Thin wrappers for readability
 |  (package IDs: Basic=2, Standard=3, Premium=4, Custom=5)
 -------------------------------------------------*/
 public function is_plan_basic()
{
    $user_id = user()->id;
    
    $this->db->where('user_id', $user_id);
    $this->db->where('package', 2);
    $payment = $this->db->get('payment')->row();

    if ($payment) {
        // Check if package hasn't expired (assuming there's an expire_date column)
        if (isset($payment->expire_date)) {
            $today = date('Y-m-d');
            if ($payment->expire_date >= $today) {
                return true; // Package 2 is active
            }
        } else {
            // If no expiration date, assume it's active
            return true;
        }
    }
    
    return false;
}
public function is_plan_standard()
{
    $user_id = user()->id;
    
    $this->db->where('user_id', $user_id);
    $this->db->where('package', 3);
    $payment = $this->db->get('payment')->row();

    if ($payment) {
        // Check if package hasn't expired (assuming there's an expire_date column)
        if (isset($payment->expire_date)) {
            $today = date('Y-m-d');
            if ($payment->expire_date >= $today) {
                return true; // Package 3 is active
            }
        } else {
            // If no expiration date, assume it's active
            return true;
        }
    }
    
    return false;
}
    function is_pack_premium()
    {
        return $this-> has_verified_package(4);
    }
    function is_pack_customization()
    {
        return $this->has_verified_package(5);
    }


    public function is_payment_pending()
    {
        $user = user(); // Get the logged-in user

        if (!$user) {
            return false;
        }

        // Get the most recent payment record for the user
        $payment = $this->db->select('*')
            ->from('payment')
            ->where('user_id', $user->id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
        // Check if the payment status is "pending"
        if ($payment && $payment->status == 'pending') {
            return true;
        }

        return false;
    }




    function get_user_time_value($user_id)
    {

        if (!$user_id) {
            $user_id = $ci->session->userdata('id');
        }        

        // Validate
        if (empty($user_id) || !is_numeric($user_id)) {
            return null;
        }

        // Fetch user record
        $user = $this->db->get_where('users', ['id' => $user_id])->row();

        if (! $user || empty($user->timezone)) {
            return null;
        }

        try {
            $tz  = new DateTimeZone($user->timezone);
            $now = new DateTime('now', $tz);
            return $now->format('H:i:s');   // HH:MM:SS
        } catch (Exception $e) {
            return null;
        }
    }
    function get_user_datetime_only($user_id)
    {

        $ci = &get_instance();

        // If no user_id passed, get from session
        if(!$user_id){
            $user_id = $ci->session->userdata('id');
        }
        
        if (empty($user_id)) {
            return null;
        }

        $user = $ci->db->get_where('users', ['id' => $user_id])->row();

        if (!$user || empty($user->timezone)) {
            return null;
        }

        try {
            $tz = new DateTimeZone($user->timezone);
            $now = new DateTime('now', $tz);
            return $now->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }

    public function check_department()
    {
        // Grab department rows (objects) from the two helpers
        $dept_from_session = $this->get_department();          // may be null
        $dept_from_role    = $this->get_department_by_role();  // may be null

        // If either lookup failed, treat it as a mismatch
        if (!$dept_from_session || !$dept_from_role) {
            return false;
        }

        // Compare their primary‑key IDs
        $dept_id = $dept_from_session->department_id;

        if (
            ($dept_id == $dept_from_role->department_id) &&
            in_array($dept_id, ['1', '2', '3', '4'])
        ) {
            $role_id = (int) $this->session->userdata('role_id');
            if (!$role_id) {
                return false;                                      // no role in session
            }

            $has_feature_access = $this->db
                ->where('role_id', $role_id)
                ->limit(1)
                ->count_all_results('role_feature_access') > 0;

            return $has_feature_access;
        }

        return false;
    }


    public function get_department()
    {
        // Get department_id from session
        $department_id = $this->session->userdata('department_id');

        // Check if it's available
        if (!$department_id) {
            return null;
        }

        // Fetch department row from the departments table
        $query = $this->db->get_where('departments', ['id' => $department_id], 1);
        

        // Return the result (as object)
        return $query->row();  // Use ->row_array() if you prefer an array
    }
    public function get_department_by_role()
    {
        $role_id = $this->session->userdata('role_id');
        if (!$role_id) {
            return null;
        }

        // first query – find the department_id
        $dept = $this->db->select('department_id')
            ->from('employee_roles')
            ->where('id', $role_id)
            ->limit(1)
            ->get()
            ->row();

        if (!$dept) {
            return null;
        }

        // second query – fetch the actual department record
        return $this->db->get_where(
            'departments',
            ['id' => $dept->department_id],
            1
        )->row();
    }

    public function is_CEO($role_id)
    {
        if (!$role_id) {
            // 1️⃣  Get role_id from session
            $role_id = (int) $this->session->userdata('role_id');        }
    
        if (!$role_id) {
            return false;                       // no role in session
        }

        // 2️⃣  Fetch that role from employee_roles
        $role = $this->db->select('role_id')       // or 'slug' if that’s your column
            ->from('employee_roles')
            ->where('id', $role_id)
            ->limit(1)
            ->get()
            ->row();

        if (!$role) {
            return false;                       // role not found
        }

        // 3️⃣  Compare name to “CEO” (case‑insensitive)
        return $role->role_id == 1;
    }
    public function is_user_ceo()
    {
            // 1️⃣  Get from session
            $is_ceo = (int) $this->session->userdata('is_org_ceo');
        

        if (!$is_ceo) {
            return false;                       // no role in session
        }
       return true;
    }
    public function get_allowed_feature_ids()
    {
        $role_id = (int) $this->session->userdata('role_id');
        $rows = $this->db->select('feature_id')
            ->from('role_feature_access')
            ->where('role_id', $role_id)
            ->where('status', 1)
            ->get()
            ->result_array();          // [['feature_id' => 1], …]

        // Flatten to a simple int array
        return array_map('intval', array_column($rows, 'feature_id'));
    }

    public function is_access_for_all_role()
    {
        $is_ceo = (int) $this->session->userdata('is_org_ceo');
        $is_org_admin = (int) $this->session->userdata('is_org_admin');

        return ($is_ceo === 1 || $is_org_admin === 1);
    }
}