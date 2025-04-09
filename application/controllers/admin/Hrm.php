<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hrm extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        //check auth
        if (!is_user()) {
            redirect(base_url());
        }
        $this->load->model('hrm_model');
    }

    public function department(){
        $data = array();
        $data['page_title'] = 'Department';      
        $data['page'] = 'Hrm';   
        $data['main_page'] = 'Hrm';   
        $data['department'] = FALSE;
        $data['departments'] = $this->admin_model->get_by_user('departments');
        $data['main_content'] = $this->load->view('admin/user/hrm/department',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function department_add()
    {   
        if($_POST)
        {   
            $id = $this->input->post('id', true);
               
                $data=array(
                    'user_id' => user()->id,
                    'business_id' => $this->business->uid,
                    'name' => $this->input->post('name', true),
                    'status' => $this->input->post('status', true),
                    'created_at' => my_date_now()
                );
                $data = $this->security->xss_clean($data);
                
                //if id available info will be edited
                if ($id != '') {
                    $this->admin_model->edit_option($data, $id, 'departments');
                    $this->session->set_flashdata('msg', trans('msg-updated')); 
                } else {
                    $id = $this->admin_model->insert($data, 'departments');
                    $this->session->set_flashdata('msg', trans('msg-inserted')); 
                }
                redirect(base_url('admin/hrm/department'));

        }
        
    }

    public function department_edit($id)
    {  
        $data = array();
        $data['page_title'] = 'Edit';   
        $data['department'] = $this->admin_model->select_option($id, 'departments');
        $data['main_content'] = $this->load->view('admin/user/hrm/department',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function department_delete($id)
    {
        $this->admin_model->delete($id,'departments'); 
        echo json_encode(array('st' => 1));
    }


    public function employee(){
        $data = array();
        $data['page_title'] = 'Employee';      
        $data['page'] = 'Hrm';   
        $data['main_page'] = 'Hrm';   
        $data['employee'] = FALSE;
        $data['departments'] = $this->admin_model->get_by_user_status('departments');
        $data['countries'] = $this->hrm_model->get_countries();
        $data['employees'] = $this->hrm_model->get_employees();
        $data['main_content'] = $this->load->view('admin/user/hrm/employee',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


     public function employee_add()
    {   
        if($_POST)
        {   
            check_status();

            $id = $this->input->post('id', true);

               
                $data=array(
                    'user_id' => user()->id,
                    'business_id' => $this->business->uid,
                    'name' => $this->input->post('name', true),
                    'department_id' => $this->input->post('department', true),
                    'email' => $this->input->post('email', true),
                    'phone' => $this->input->post('phone', true),
                    'address' => $this->input->post('address', true),
                    'city' => $this->input->post('city', true),
                    'country' => $this->input->post('country', true),
                    'status' => $this->input->post('status', true),
                    'created_at' => my_date_now()
                );
                $data = $this->security->xss_clean($data);
                
                //if id available info will be edited
                if ($id != '') {
                    $this->admin_model->edit_option($data, $id, 'employees');
                    $this->session->set_flashdata('msg', trans('msg-updated')); 
                } else {
                    $id = $this->admin_model->insert($data, 'employees');
                    $this->session->set_flashdata('msg', trans('msg-inserted')); 
                }


                // insert photos
                if($_FILES['photo']['name'] != ''){
                    $up_load = $this->admin_model->upload_image('1200');
                    $data_img = array(
                        'image' => $up_load['images'],
                        'thumb' => $up_load['thumb']
                    );
                    $this->admin_model->edit_option($data_img, $id, 'employees');   
                }

                redirect(base_url('admin/hrm/employee'));

            
        }      
        
    }

    public function employee_edit($id)
    {  
        $data = array();
        $data['page_title'] = 'Edit';
        $data['departments'] = $this->admin_model->get_by_user('departments');
        $data['employee'] = $this->admin_model->select_option($id, 'employees');
        $data['countries'] = $this->hrm_model->get_countries();
        //echo "<pre>"; print_r($data['employee']); exit();
        $data['main_content'] = $this->load->view('admin/user/hrm/employee',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function employee_delete($id)
    {
        $this->admin_model->delete($id,'employees'); 
        echo json_encode(array('st' => 1));
    }


    public function attendance(){
        //echo "string"; exit();
        $data = array();
        $data['page_title'] = 'Attendance';      
        $data['page'] = 'Hrm';   
        $data['main_page'] = 'Hrm';   
        $data['department'] = FALSE;
        $data['employees'] = $this->hrm_model->get_employees();
        $data['attendances'] = $this->hrm_model->get_attendances();
        //echo "<pre>"; print_r($data['attendances']); exit();
        $data['main_content'] = $this->load->view('admin/user/hrm/attendance',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function attendance_add()
    {   
        if($_POST)
        {   
            $id = $this->input->post('id', true);
               
                $data=array(
                    'user_id' => user()->id,
                    'business_id' => $this->business->uid,
                    'employee_id' => $this->input->post('employee', true),
                    'date' => $this->input->post('date', true),
                    'check_in' => $this->input->post('check_in', true),
                    'check_out' => $this->input->post('check_out', true),
                    'note' => $this->input->post('note', true),
                    'created_at' => my_date_now()
                );
                $data = $this->security->xss_clean($data);
                
                //if id available info will be edited
                if ($id != '') {
                    $this->admin_model->edit_option($data, $id, 'attendence');
                    $this->session->set_flashdata('msg', trans('msg-updated')); 
                } else {
                    $id = $this->admin_model->insert($data, 'attendence');
                    $this->session->set_flashdata('msg', trans('msg-inserted')); 
                }
                redirect(base_url('admin/hrm/attendance'));

        }
        
    }

    public function attendance_edit($id)
    {  
        $data = array();
        $data['page_title'] = 'Edit';
        $data['departments'] = $this->admin_model->get_by_user('departments');
        $data['employees'] = $this->admin_model->get_by_user('employees');
        $data['attendence'] = $this->admin_model->select_option($id, 'attendence');
        //echo "<pre>"; print_r($data['employee']); exit();
        $data['main_content'] = $this->load->view('admin/user/hrm/attendance',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function attendance_delete($id)
    {
        $this->admin_model->delete($id,'employees'); 
        echo json_encode(array('st' => 1));
    }


    public function salary(){
        //echo "string"; exit();
        $data = array();
        $data['page_title'] = 'Salary';      
        $data['page'] = 'Hrm';   
        $data['main_page'] = 'Hrm';   
        $data['department'] = FALSE;
        $data['employees'] = $this->hrm_model->get_employees();
        $data['salaries'] = $this->hrm_model->get_salaries();
        //echo "<pre>"; print_r($data['employees']); exit();
        $data['main_content'] = $this->load->view('admin/user/hrm/salary',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function salary_add()
    {   
        if($_POST)
        {   
            $id = $this->input->post('id', true);
               
                $data=array(
                    'user_id' => user()->id,
                    'business_id' => $this->business->uid,
                    'employee_id' => $this->input->post('employee', true),
                    'department_id' => $this->input->post('department', true),
                    'amount' => $this->input->post('amount', true),
                    'acount' => $this->input->post('acount', true),
                    'method' => $this->input->post('method', true),
                    'note' => $this->input->post('note', true),
                    'created_at' => my_date_now()
                );
                $data = $this->security->xss_clean($data);
                
                //if id available info will be edited
                if ($id != '') {
                    $this->admin_model->edit_option($data, $id, 'salary');
                    $this->session->set_flashdata('msg', trans('msg-updated')); 
                } else {
                    $id = $this->admin_model->insert($data, 'salary');
                    $this->session->set_flashdata('msg', trans('msg-inserted')); 
                }
                redirect(base_url('admin/hrm/salary'));

        }
        
    }

    public function salary_edit($id)
    {  
        $data = array();
        $data['page_title'] = 'Edit';
        $data['departments'] = $this->admin_model->get_by_user('departments');
        $data['employees'] = $this->admin_model->get_by_user('employees');
        $data['salary'] = $this->admin_model->select_option($id, 'salary');
        //echo "<pre>"; print_r($data['employee']); exit();
        $data['main_content'] = $this->load->view('admin/user/hrm/salary',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function salary_delete($id)
    {
        $this->admin_model->delete($id,'salary'); 
        echo json_encode(array('st' => 1));
    }


    public function hrm_settings(){
        $data = array();
        $data['page_title'] = 'Hrm settings';      
        $data['page'] = 'Hrm';   
        $data['main_page'] = 'Hrm';   
        $data['department'] = FALSE;
        $data['main_content'] = $this->load->view('admin/user/hrm/settings',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function update_hrm_settings()
    { 
        if($_POST)
        {   
           
            $data=array(
                'default_check_in' => $this->input->post('default_check_in', true),
                'default_check_out' => $this->input->post('default_check_out', true),
            );
            $data = $this->security->xss_clean($data);

                $this->admin_model->edit_option($data, $this->business->id , 'business');
                $this->session->set_flashdata('msg', trans('msg-updated'));

            redirect(base_url('admin/hrm/hrm_settings'));

        }
        
    }


}
	

