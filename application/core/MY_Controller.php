<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $global_data['settings'] = $this->common_model->get_settings();
        $this->settings = $global_data['settings'];
        
        $global_data['selected_lang'] = $this->settings->lang;
        $this->selected_lang = $global_data['selected_lang'];
        $this->lang->load('website', $global_data['settings']->lang_slug);
        $this->load->vars($global_data);
        
        $active_business = $this->session->userdata('active_business');
        if (empty($active_business)) {
            $global_data['business'] = $this->common_model->get_business(0);
        } else {
            $global_data['business'] = $this->common_model->get_business($active_business);
        }
        $this->business = $global_data['business'];
        $this->load->vars($global_data);
        $this->load->library('user_agent');
        $this->db->query("SET sql_mode=''");
        
        if (settings()->version == '2.2') {

            $this->db->query("ALTER TABLE `settings` ADD `paystack_payment` VARCHAR(155) NULL DEFAULT '0' AFTER `secret_key`, ADD `paystack_secret_key` VARCHAR(255) NULL DEFAULT NULL AFTER `paystack_payment`, ADD `paystack_public_key` VARCHAR(255) NULL DEFAULT NULL AFTER `paystack_secret_key`;");

            $this->db->query("ALTER TABLE `users` ADD `paystack_payment` VARCHAR(155) NULL DEFAULT '0' AFTER `secret_key`, ADD `paystack_secret_key` VARCHAR(255) NULL DEFAULT NULL AFTER `paystack_payment`, ADD `paystack_public_key` VARCHAR(255) NULL DEFAULT NULL AFTER `paystack_secret_key`;");

            $this->db->query("ALTER TABLE `settings` ADD `sid` VARCHAR(255) NULL DEFAULT '2020-02-02' AFTER `lang`;");

            $this->db->query("UPDATE `package_features` SET `name` = 'Get Invoice Payment Online' WHERE `package_features`.`id` = 6;");
            $this->db->query("UPDATE `package_features` SET `text` = 'Set value 1-8' WHERE `package_features`.`id` = 5;");
            $this->db->query("UPDATE `package_features` SET `text` = 'Select max value 8' WHERE `package_features`.`id` = 5;");

            $this->db->query("UPDATE `lang_values` SET `label` = 'Blog posts ', `keyword` = 'blog-posts', `english` = 'Blog posts ' WHERE `lang_values`.`id` = 257;");
            $this->db->query("ALTER TABLE `invoice` ADD `qr_code` TEXT NULL DEFAULT NULL AFTER `status`;");

            $this->db->query("ALTER TABLE `business` ADD `enable_qrcode` VARCHAR(155) NULL DEFAULT '0' AFTER `enable_stock`;");

            $this->db->query("INSERT INTO `lang_values` (`type`, `label`, `keyword`, `english`) VALUES
            ('user', 'Please select a payment method', 'please-select-a-payment-method', 'Please select a payment method'),
            ('user', 'Paystack', 'paystack', 'Paystack'),
            ('user', 'Razorpay', 'razorpay', 'Razorpay'),
            ('user', 'License', 'license', 'License'),
            ('user', 'Resend mail', 'resend-mail', 'Resend mail'),
            ('user', 'Translate language', 'translate-language', 'Translate language'),
            ('user', 'Enable Invoice QR code', 'enable-invoice-qr-code', 'Enable Invoice QR code'),
            ('user', 'Enable to generate and show QR code for all created invoices', 'enable-qr-help', 'Enable to generate and show QR code for all created invoices'),
            ('user', 'Generate QR Code', 'generate-qr-code', 'Generate QR Code');");

            $data = array(
                'version' => '2.3'
            );
            $this->admin_model->edit_option($data, 1, 'settings');
        }
        $this->load->library('session');

        // List of public URLs
        $public_paths = [
            'auth/login',
            'auth/log',
            'auth/forgot_password',
            'auth/register',
            'auth/verify_email',
            'auth/invitation',
            'api/login',
        ];

        // Get current URI path
        $current_path = uri_string();

        // If not a public page and not logged in, block access
        if (!in_array($current_path, $public_paths)) {
            $org_logged_in = $this->session->userdata('logged_in');
            $emp_logged_in = $this->session->userdata('employee_logged_in');

            if (!$org_logged_in && !$emp_logged_in) {
                redirect('auth/login'); // redirect to login page
                exit;
            }
        }

    }

}


class Home_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $global_data['settings'] = $this->common_model->get_settings('settings');
        $this->settings = $global_data['settings'];
        // $gIobal_data_load = load_settings_data();
        // $gIobal_data = gets_active_langs();

        if (get_lang() == '') {
            $this->lang->load('website', $global_data['settings']->lang_slug);
        }else{
            $this->lang->load('website', get_lang());
        }
        $this->load->vars($global_data);
    }

    //verify recaptcha
    public function recaptcha_verify_request()
    {
        if ($this->settings->enable_captcha == 0) {
            return true;
        }

        $this->load->library('recaptcha');
        $recaptcha = $this->input->post('g-recaptcha-response');
        if (!empty($recaptcha)) {
            $response = $this->recaptcha->verifyResponse($recaptcha);
            if (isset($response['success']) && $response['success'] === true) {
                return true;
            }else{
                return true;
            }
        }
        return false;
    }

    protected function json_response($status, $message, $data = []) {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => $status < 400 ? 'success' : 'error',
                'message' => $message,
                'data' => $data
            ]));
    }

    
    public function has_access($employee_id, $feature_slug, $permission_type) {
        // Allowed permission types
        $valid_permission_types = ['read', 'write', 'action', 'delete'];
        if (!in_array($permission_type, $valid_permission_types)) {
            return false;
        }
    
        // Get employee record
        $employee = $this->ci->db->where('id', $employee_id)->get('employees')->row();
        if (!$employee || !$employee->role_id) {
            return false;
        }
    
        // Get feature ID by slug
        $feature = $this->ci->db->where('feature_name', $feature_slug)->get('app_feature')->row();
        if (!$feature) {
            return false;
        }
    
        // Get permission access row
        $access = $this->ci->db->where('role_id', $employee->role_id)
                               ->where('feature_id', $feature->id)
                               ->where('user_id', $employee->user_id)
                               ->get('role_feature_access')
                               ->row();
        if (!$access) {
            return false;
        }
    
        // Return permission result
        switch ($permission_type) {
            case 'read': return (bool)$access->is_read;
            case 'write': return (bool)$access->is_write;
            case 'action': return (bool)$access->is_action;
            case 'delete': return (bool)$access->is_delete;
            default: return false;
        }
    }
    public function get_access_permissions($employee_id, $feature_slug) {
        // Get employee record
        $employee = $this->ci->db->where('id', $employee_id)->get('employees')->row();
        if (!$employee || !$employee->role_id) {
            return [
                'read'   => false,
                'write'  => false,
                'action' => false,
                'delete' => false
            ];
        }
    
        // Get feature ID by slug (case-insensitive match if needed)
        $feature = $this->ci->db->where('feature_name', $feature_slug)->get('app_feature')->row();
        if (!$feature) {
            return [
                'read'   => false,
                'write'  => false,
                'action' => false,
                'delete' => false
            ];
        }
    
        // Get permission access row
        $access = $this->ci->db->where('role_id', $employee->role_id)
                               ->where('feature_id', $feature->id)
                               ->where('user_id', $employee->user_id)
                               ->get('role_feature_access')
                               ->row();
    
        // If no access row found, return all false
        if (!$access) {
            return [
                'read'   => false,
                'write'  => false,
                'action' => false,
                'delete' => false
            ];
        }
    
        // Return all access flags
        return [
            'read'   => (bool)$access->is_read,
            'write'  => (bool)$access->is_write,
            'action' => (bool)$access->is_action,
            'delete' => (bool)$access->is_delete
        ];
    }
    
    

}