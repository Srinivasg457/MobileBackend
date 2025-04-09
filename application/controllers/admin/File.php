<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends Home_Controller 
{

    public function __construct()
    {
        parent::__construct();
    }


    public function import($type, $product_type='')
    {
        $data = array();
        $data['page_title'] = 'Import';      
        $data['page'] = 'File';
        $data['type'] = $type;
        $data['product_type'] = $product_type;
        $data['main_content'] = $this->load->view('admin/files/import',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function download($type)
    {
        $this->load->helper('download');
        $path = base_url('uploads/files/'.$type.'_csv_template.csv');
        $data = file_get_contents($path);
        $pathinfo = pathinfo($path);
        $file_name = $type.'_csv_template.csv';
        force_download($file_name, $data);
    }


    //import csv file
    public function import_file(){
       
        $data = array();
        
        // If import request is submitted
        if($_POST){

            
            $table = $this->input->post('type');
            $product_type = $this->input->post('product_type');


            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    
                    // Insert/update CSV data into database
                    if(!empty($csvData)){
                        
                        $last_id = $this->common_model->get_last_id($table);

                        $insertCount = $last_id+1;
                        foreach($csvData as $row){ 
                            
                            // Prepare data for DB insertion
                            

                            if ($product_type=='sell') {
                                $is_sell=1 ;
                                $is_buy=0 ;
                            }
                            if($product_type=='buy'){
                                $is_buy=1 ;
                                $is_sell=0 ;
                            }
                            

                            $csvchecker = file($_FILES['file']['tmp_name']);
                            $tblcolumns = explode(",", $csvchecker[0]);

                            if($table=='customers'){

                                if (trim($tblcolumns[0]) == 'Name'  && trim($tblcolumns[1]) == 'Email') {
                                    
                                }else{
                                    
                                    $this->session->set_flashdata('error', 'The CSV uploaded was the incorrect format, please use the CSV Template provided.');
                                    redirect($_SERVER['HTTP_REFERER']);
                                    exit();
                                    
                                }
                            
                                //     $country =$row['country'];
                                //     $currency =$this->admin_model->get_by_id($row['country'],'country');
                                //     $currency_code =$currency->currency_code;
                            

                                $country = $this->business->country_id;
                                $currency_code =$this->business->currency_code;

                                $importData = array(
                                    'user_id' => user()->id,
                                    'business_id' => $this->business->uid,
                                    'name' => $row['Name'],
                                    'email' => $row['Email'],
                                    'country' => $country ,
                                    'currency' => $currency_code ,
                                    'status' => 1

                                );
                            }
                            if($table=='products'){

                                if (trim($tblcolumns[0]) == 'Name' && trim($tblcolumns[1]) == 'Price' && trim($tblcolumns[2]) == 'Quantity') {
                                    
                                }else{
                                    $this->session->set_flashdata('error', 'The CSV uploaded was the incorrect format, please use the CSV Template provided.');
                                    redirect($_SERVER['HTTP_REFERER']);
                                    exit();
                                }

                                if ($this->business->enable_stock == 1){
                                    $quantity = $row['Quantity'];
                                }else{
                                    $quantity = '';
                                }
                                if ($this->business->enable_unit == 1){
                                    $unit = 'pc';
                                }else{
                                    $unit =  '';
                                }


                                $importData = array(
                                    'user_id' => user()->id,
                                    'business_id' => $this->business->uid,
                                    'name' => $row['Name'],
                                    'price' => $row['Price'],
                                    'quantity' => $quantity ,
                                    'is_sell' => $is_sell,
                                    'is_buy' => $is_buy,
                                    'unit' => $unit,
                                );
                            }

                            if($table=='expenses'){

                                if (trim($tblcolumns[0]) == 'Amount' && trim($tblcolumns[1]) == 'Category' && trim($tblcolumns[2]) == 'Date' && trim($tblcolumns[3]) == 'Vendor' && trim($tblcolumns[4]) == 'Note') {
                                    
                                }else{
                                    $this->session->set_flashdata('error', 'The CSV uploaded was the incorrect format, please use the CSV Template provided.');
                                    redirect($_SERVER['HTTP_REFERER']);
                                    exit();
                                }

                                $category_check=$this->admin_model->check_expenses_category(str_slug(trim($row['Category'])));
                                //echo "<pre>"; print_r($category_check); exit();

                                if(empty($category_check)){

                                    $categoryData=array(
                                        'user_id' => user()->id,
                                        'business_id' => $this->business->uid,
                                        'name' => $row['Category'],
                                        'slug' => str_slug(trim($row['Category'])),
                                        'type' => 2
                                    );
                                    $category_id=$this->common_model->insert($categoryData, 'categories');
                                }else{
                                    $category_id=$category_check->id;
                                }


                                $vendorData=array(
                                    'user_id' => user()->id,
                                    'business_id' => $this->business->uid,
                                    'name' => $row['Vendor'],
                                    'created_at' => my_date_now()
                                );
                                $vendor_id=$this->common_model->insert($vendorData, 'vendors');

                                


                                $importData = array(
                                    'user_id' => user()->id,
                                    'business_id' => $this->business->uid,
                                    'amount' => $row['Amount'],
                                    'net_amount' => $row['Amount'],
                                    'date' => $row['Date'],
                                    'category' => $category_id,
                                    'vendor' => $vendor_id,
                                    'notes' => $row['Note'],
                                    'created_at' => my_date_now(),
                                    'status' => 1,
                                );
                            }
                            
                            // Insert member data
                            $insert = $this->common_model->insert($importData, $table);
                            
                            if($insert){
                                $insertCount++;
                            }
                        }
                        $this->session->set_flashdata('msg', 'Data Imported Successfully');
                    
                    }
                }else{
                    $this->session->set_flashdata('error', 'Error on file upload, please try again.');
                }
            }else{
            	$this->session->set_flashdata('error', 'Invalid file, please select only CSV file');
            }
        }

        //redirect(base_url('admin/file/import/'.$table));
        if($table=='customers'){
            redirect(base_url('admin/customer'));
        }elseif($table=='products'){
            redirect(base_url('admin/product/all/'.$product_type));
        }elseif($table=='expenses'){
            redirect(base_url('admin/expense'));
        }
    }

    //check file
    public function file_check($str){
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }


}

