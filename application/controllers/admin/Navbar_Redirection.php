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

    public function index()
    {
        //cron_recurring_payments();
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'User Screenshots';
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/user_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
    }


   public function  employee_nav(){
        $data = array();
        $data['is_employee_admin'] = false;
        $data['page_title'] = 'Employee Dashboard';
        $data['details'] = $this->session->userdata('employee_id');
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
        // if (!is_subscribed()) {
        //     redirect('/admin/subscription');
        // }
    }
    }
