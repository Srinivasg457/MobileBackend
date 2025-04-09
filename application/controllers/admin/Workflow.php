<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow extends Home_Controller {

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
        $data = array();
        $data['page_title'] = 'Workflow';      
        $data['page'] = 'Workflow';   
        $data['workflow'] = FALSE;
        $data['workflows'] = $this->admin_model->select('workflows');
        $data['main_content'] = $this->load->view('admin/workflow',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function add()
    {	
        if($_POST)
        {   
            check_status();

            $id = $this->input->post('id', true);

            //validate inputs
            $this->form_validation->set_rules('title', "Title ", 'required');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error', validation_errors());
                redirect(base_url('admin/Workflow'));
            } else {
               
                $data=array(
                    'title' => $this->input->post('title', true),
                    'details' => $this->input->post('details'),
                    'status' => $this->input->post('status', true),
                    'created_at' => my_date_now()
                );
                $data = $this->security->xss_clean($data);
                
                //if id available info will be edited
                if ($id != '') {
                    $this->admin_model->edit_option($data, $id, 'workflows');
                    $this->session->set_flashdata('msg', trans('msg-updated')); 
                } else {
                    $id = $this->admin_model->insert($data, 'workflows');
                    $this->session->set_flashdata('msg', trans('msg-inserted')); 
                }

                // insert photos
                if($_FILES['photo']['name'] != ''){
                    $up_load = $this->admin_model->upload_image('1200');
                    $data_img = array(
                        'image' => $up_load['images'],
                        'thumb' => $up_load['thumb']
                    );
                    $this->admin_model->edit_option($data_img, $id, 'workflows');   
                }

                redirect(base_url('admin/workflow'));

            }
        }      
        
    }


    public function edit($id)
    {  
        //combine post tags
       
        
        $data = array();
        $data['page_title'] = 'Edit'; 
        $data['workflow'] = $this->admin_model->select_option($id, 'workflows');
        $data['main_content'] = $this->load->view('admin/workflow',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function delete($id)
    {
        $this->admin_model->delete($id,'workflows'); 
        echo json_encode(array('st' => 1));
    }

}
	

