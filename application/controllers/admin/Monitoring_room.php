<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_room extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load database library
        $this->load->database();
    }

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Live Monitoring';
        $data['main_content'] = $this->load->view('admin/monitoring_room', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
}