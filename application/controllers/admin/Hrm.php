<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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


    //  public function employee_add()
    // {   
    //     if($_POST)
    //     {   
    //         check_status();

    //         $id = $this->input->post('id', true);

               
    //             $data=array(
    //                 'user_id' => user()->id,
    //                 'business_id' => $this->business->uid,
    //                 'name' => $this->input->post('name', true),
    //                 'department_id' => $this->input->post('department', true),
    //                 'email' => $this->input->post('email', true),
    //                 'phone' => $this->input->post('phone', true),
    //                 'address' => $this->input->post('address', true),
    //                 'city' => $this->input->post('city', true),
    //                 'country' => $this->input->post('country', true),
    //                 'status' => $this->input->post('status', true),
    //                 'created_at' => my_date_now()
    //             );
    //             $data = $this->security->xss_clean($data);
                
    //             //if id available info will be edited
    //             if ($id != '') {
    //                 $this->admin_model->edit_option($data, $id, 'employees');
    //                 $this->session->set_flashdata('msg', trans('msg-updated')); 
    //             } else {
    //                 $id = $this->admin_model->insert($data, 'employees');
    //                 $this->session->set_flashdata('msg', trans('msg-inserted')); 
    //             }


    //             // insert photos
    //             if($_FILES['photo']['name'] != ''){
    //                 $up_load = $this->admin_model->upload_image('1200');
    //                 $data_img = array(
    //                     'image' => $up_load['images'],
    //                     'thumb' => $up_load['thumb']
    //                 );
    //                 $this->admin_model->edit_option($data_img, $id, 'employees');   
    //             }

    //             redirect(base_url('admin/hrm/employee'));

            
    //     }      
        
public function employee_add()
{   
    if ($_POST) {   
        check_status();
        $this->load->database();

        $id = $this->input->post('id', true);
        $email = $this->input->post('email', true);

        $data = array(
            'user_id' => user()->id,
            'business_id' => $this->business->uid,
            'name' => $this->input->post('name', true),
            'department_id' => $this->input->post('department', true),
            'email' => $email,
            'phone' => $this->input->post('phone', true),
            'address' => $this->input->post('address', true),
            'city' => $this->input->post('city', true),
            'country' => $this->input->post('country', true),
            'status' => $this->input->post('status', true),
            'created_at' => my_date_now()
        );

        $data = $this->security->xss_clean($data);

        if ($id != '') {
            $this->db->where('id', $id)->update('employees', $data);
            $this->session->set_flashdata('msg', trans('msg-updated'));
        } else {
            // Case-insensitive email check
            $this->db->where('LOWER(email)', strtolower($email));
            $exists = $this->db->get('employees')->row();

            if ($exists) {
                $this->session->set_flashdata('error', 'Email address already exists.');
                redirect(base_url('admin/hrm/employee'));
                return;
            }

            // Insert new employee
            try {
                $this->db->insert('employees', $data);
                $id = $this->db->insert_id();
                $this->session->set_flashdata('msg', trans('msg-inserted'));
            } catch (Exception $e) {
                log_message('error', 'Insert failed: ' . $e->getMessage());
                $this->session->set_flashdata('error', 'Failed to add employee. Email may already exist.');
                redirect(base_url('admin/hrm/employee'));
                return;
            }

            // Generate and save invitation token
            $token = uniqid();
            $this->db->where('id', $id)->update('employees', ['invitation_token' => $token]);

            // Send invitation email
            $config = array(
                'protocol'    => 'smtp',
                'smtp_host'   => 'smtp.gmail.com',
                'smtp_port'   => 587,
                'smtp_user'   => 'sabeer2002ahmed@gmail.com',
                'smtp_pass'   => 'vivxkwqkkygmelzp',
                'smtp_crypto' => 'tls',
                'mailtype'    => 'html',
                'charset'     => 'utf-8',
                'newline'     => "\r\n",
                'crlf'        => "\r\n"
            );

            $this->load->library('email');
            $this->email->initialize($config);

            $subject = 'You are invited to join Time Tracker';
            $message = '<p>Hello ' . $data['name'] . ',</p>';
            $message .= '<p>You have been invited to register for Time Tracker. Click below to register:</p>';
            $message .= '<p><a href="' . base_url('accept-invitation?token=' . $token) . '">Accept Invitation</a></p>';
            $message .= '<p>If you did not expect this, you can ignore this email.</p>';
            $message .= '<p>Regards,<br>Time Tracker Team</p>';

            $this->email->to($email);
            $this->email->from('sabeer2002ahmed@gmail.com', 'Time Tracker');
            $this->email->subject($subject);
            $this->email->message($message);

            if ($this->email->send()) {
                $this->db->where('id', $id)->update('employees', ['invitation_sent_at' => my_date_now()]);
                $this->session->set_flashdata('msg', 'Employee added and invitation email sent.');
            } else {
                log_message('error', 'Email error: ' . $this->email->print_debugger());
                $this->session->set_flashdata('error', 'Employee added but failed to send email.');
            }
        }

        // Upload photo if available
        if (!empty($_FILES['photo']['name'])) {
            $up_load = $this->admin_model->upload_image('1200'); // Optional: replace with inline logic if preferred
            $data_img = array(
                'image' => $up_load['images'],
                'thumb' => $up_load['thumb']
            );
            $this->db->where('id', $id)->update('employees', $data_img);
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
    public function employee_import()
{
    check_status();
    $this->load->database();

    if (!empty($_FILES['import_file']['name'])) {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv|xls|xlsx';
        $config['max_size'] = 10000;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('import_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect(base_url('admin/hrm/employee'));
            return;
        }

        $fileData = $this->upload->data();
        $filePath = './uploads/' . $fileData['file_name'];

        // Determine file type and load using PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $header = array_map('strtolower', array_map('trim', $rows[0])); // First row is header
        unset($rows[0]); // Remove header from data rows

        $duplicateEmails = [];

        foreach ($rows as $row) {
            $employeeData = array_combine($header, $row);
            $email = strtolower(trim($employeeData['email']));

            // Check for duplicates
            $this->db->where('LOWER(email)', $email);
            if ($this->db->get('employees')->row()) {
                $duplicateEmails[] = $email;
                continue;
            }

            $data = array(
                'user_id' => user()->id,
                'business_id' => $this->business->uid,
                'name' => $employeeData['name'],
                'department_id' => $employeeData['department_id'],
                'email' => $email,
                'phone' => $employeeData['phone'],
                'address' => $employeeData['address'],
                'city' => $employeeData['city'],
                'country' => $employeeData['country'],
                'status' => 1,
                'created_at' => my_date_now()
            );

            $this->db->insert('employees', $data);
            $id = $this->db->insert_id();

            $token = uniqid();
            $this->db->where('id', $id)->update('employees', ['invitation_token' => $token]);

            // Send invitation email
            $this->_send_invitation_email($data['name'], $email, $token);
        }

        unlink($filePath); // Clean up

        if (!empty($duplicateEmails)) {
            $this->session->set_flashdata('error', 'These emails already exist and were skipped: ' . implode(', ', $duplicateEmails));
        } else {
            $this->session->set_flashdata('msg', 'Employees imported successfully.');
        }

        redirect(base_url('admin/hrm/employee'));
    }
}


public function download_sample_excel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headings
    $sheet->setCellValue('A1', 'name');
    $sheet->setCellValue('B1', 'department_id');
    $sheet->setCellValue('C1', 'email');
    $sheet->setCellValue('D1', 'phone');
    $sheet->setCellValue('E1', 'address');
    $sheet->setCellValue('F1', 'city');
    $sheet->setCellValue('G1', 'country');

    // Add sample row
    $sheet->setCellValue('A2', 'John Doe');
    $sheet->setCellValue('B2', '1');
    $sheet->setCellValue('C2', 'john@example.com');
    $sheet->setCellValue('D2', '1234567890');
    $sheet->setCellValue('E2', '123 Main St');
    $sheet->setCellValue('F2', 'New York');
    $sheet->setCellValue('G2', 'USA');

    // Send file to browser
    $filename = 'employee_sample.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Reuse email logic as a helper method
private function _send_invitation_email($name, $email, $token)
{
    $config = array(
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_user'   => 'sabeer2002ahmed@gmail.com',
        'smtp_pass'   => 'vivxkwqkkygmelzp',
        'smtp_crypto' => 'tls',
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'crlf'        => "\r\n"
    );

    $this->load->library('email');
    $this->email->initialize($config);

    $subject = 'You are invited to join Time Tracker';
    $message = '<p>Hello ' . $name . ',</p>';
    $message .= '<p>You have been invited to register for Time Tracker. Click below to register:</p>';
    $message .= '<p><a href="' . base_url('accept-invitation?token=' . $token) . '">Accept Invitation</a></p>';
    $message .= '<p>If you did not expect this, you can ignore this email.</p>';
    $message .= '<p>Regards,<br>Time Tracker Team</p>';

    $this->email->to($email);
    $this->email->from('sabeer2002ahmed@gmail.com', 'Time Tracker');
    $this->email->subject($subject);
    $this->email->message($message);

    if ($this->email->send()) {
        $this->db->where('email', $email)->update('employees', ['invitation_sent_at' => my_date_now()]);
    } else {
        log_message('error', 'Email failed: ' . $this->email->print_debugger());
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
	

