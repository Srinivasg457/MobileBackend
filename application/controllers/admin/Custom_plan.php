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
        $data['main_content'] = $this->load->view('admin/custom_plan', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
}