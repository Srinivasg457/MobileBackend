<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->library('form_validation');
    }
    public function index()
    {
        if (!$this->session->userdata('employee_logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'Report';
        $data['main_content'] = $this->load->view('admin/employee/report', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


}
