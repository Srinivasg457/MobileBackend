<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Navbar_Redirection extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
    }

    // public function index()
    // {
    //     //cron_recurring_payments();
    //     $data = array();
    //     $data['is_employee_admin'] = true;
    //     $data['page_title'] = 'User Screenshots';
    //     $data['main_page'] = 'Analytics';
    //     $data['main_content'] = $this->load->view('admin/user_screenshot', $data, TRUE);
    //     $this->load->view('admin/index', $data);
    //     if (!is_subscribed()) {
    //         redirect('/admin/subscription');
    //     }
    // }
    public function index()
    {
        if(is_CEO("")){
            redirect('admin/dashboard/business');  
        }
        $allowed = get_allowed_feature_ids();

        // Check features in navbar order
        if (in_array(6, $allowed)) {
            redirect('admin/ScreenshotController');
        } elseif (in_array(7, $allowed)) {
            redirect('admin/ScreenshotController/webcam');
        } elseif (in_array(1, $allowed)) {
            redirect('admin/Activity_logs');
        } elseif (in_array(2, $allowed)) {
            redirect('admin/Activity_logs/get_index');
        } elseif (in_array(8, $allowed)) {
            redirect('admin/Monitoring_room');
        } elseif (in_array(3, $allowed)) {
            redirect('admin/Notification');
        } elseif (in_array(9, $allowed)) {
            redirect('employee/Timecards_manual/approve');
        } elseif (in_array(4, $allowed)) {
            redirect('organization/edit');
        } elseif (in_array(5, $allowed)) {
            redirect('organization/org_exception');
        } elseif (in_array(12, $allowed)) {
            redirect('admin/hrm/department');
        } elseif (in_array(10, $allowed)) {
            redirect('admin/hrm/employee');
        } elseif (in_array(11, $allowed)) {
            redirect('employee/EmployeeRoles');
        } else {
            // Fallback to dashboard if no permissions
            redirect('admin/dashboard');
        }
    }


   public function  employee_nav(){
        $data = array();
        $data['is_employee_admin'] = false;
        $data['page_title'] = 'Employee Dashboard';
        $data['details'] = $this->session->userdata('employee_id');
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
    }
    }
