<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScreenshotController extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
    }

    public function index(){
        $data = array();
        $data['page_title'] = 'User Screenshots';
        $data['main_content'] = $this->load->view('admin/user_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    //store the screenshots
    public function store_screenshot() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(["status" => "error", "message" => "Invalid request method"]));
        }
    
        $org_id = $this->input->get_request_header('org_id', TRUE);
        $user_id = $this->input->get_request_header('user_id', TRUE);
    
        if (empty($org_id) || empty($user_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(["status" => "error", "message" => "Missing org_id or user_id in headers"]));
        }
    
        if (empty($_FILES['screenshot']['tmp_name'])) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(["status" => "error", "message" => "No file uploaded"]));
        }
    
        $file_name = $_FILES['screenshot']['name'];
        $file_tmp = $_FILES['screenshot']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_extension, $allowed_extensions)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(["status" => "error", "message" => "Invalid file type"]));
        }
    
        // Read binary data
        $binary_data = file_get_contents($file_tmp);
        if (!$binary_data) {
            $handle = fopen($file_tmp, "rb");
            $binary_data = fread($handle, filesize($file_tmp));
            fclose($handle);
        }
    
        $created_at = date('Y-m-d H:i:s');
    
        $data = [
            'org_id' => $org_id,
            'user_id' => $user_id,
            'file_data' => $binary_data,
            'file_type' => $file_extension, // Updated column name
            'created_at' => $created_at
        ];
    
        if (!$this->db->insert('screenshots', $data)) {
            $error = $this->db->error();
            return $this->output->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(["status" => "error", "message" => "DB Insertion Failed", "error" => $error]));
        }
    
        return $this->output->set_content_type('application/json')
            ->set_status_header(201)
            ->set_output(json_encode(["status" => "success", "message" => "Screenshot stored successfully"]));
    }

    //Get the list users screenshots based on org_id and user_id 
    // public function get_screenshots() {
    //     $org_id = $this->input->get_request_header('org_id', TRUE);
    //     $user_id = $this->input->get_request_header('user_id', TRUE);
    
    //     if (empty($org_id) || empty($user_id)) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(["status" => "error", "message" => "Missing org_id or user_id in headers"]));
    //     }
    
    //     $this->db->select('screenshot_id, file_type, created_at');
    //     $this->db->from('screenshots');
    //     $this->db->where('org_id', $org_id);
    //     $this->db->where('user_id', $user_id);
    //     $this->db->where('status', 1);  // Only show visible screenshots
    //     $this->db->order_by('created_at', 'DESC');
    //     $query = $this->db->get();
    
    //     if ($query->num_rows() == 0) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(404)
    //             ->set_output(json_encode(["status" => "error", "message" => "No screenshots found"]));
    //     }
    
    //     $screenshots = [];
    //     foreach ($query->result() as $row) {
    //         $screenshots[] = [
    //             'id' => $row->screenshot_id,
    //             'image_url' => base_url("screenshot/get_image/".$row->screenshot_id),
    //             'created_at' => $row->created_at
    //         ];
    //     }
    
    //     return $this->output->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode(["status" => "success", "screenshots" => $screenshots]));
    // }
    public function get_screenshots() {
        $org_id = $this->input->get('org_id');   // Changed from get_request_header
        $user_id = $this->input->get('user_id'); // Changed from get_request_header

        if (empty($org_id) || empty($user_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error", 
                    "message" => "Missing org_id or user_id in headers"
                ]));
        }
    
        $this->db->select('screenshot_id, file_data, file_type, created_at');
        $this->db->from('screenshots');
        $this->db->where('org_id', $org_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
    
        if ($query->num_rows() == 0) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    "status" => "error", 
                    "message" => "No screenshots found"
                ]));
        }
    
        $screenshots = [];
        foreach ($query->result() as $row) {
            $formatted_date = date('H:i:s', strtotime($row->created_at));
            
            // Encode binary data to base64
            $base64_image = base64_encode($row->file_data);
            $image_data_url = 'data:image/' . $row->file_type . ';base64,' . $base64_image;
    
            $screenshots[] = [
                'id' => $row->screenshot_id,
                'image_data' => $image_data_url, // This can be used directly in <img src="">
                'created_at' => $formatted_date,
                'display_text' => $formatted_date
            ];
        }
    
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success", 
                "screenshots" => $screenshots
            ]));
    }
    

    //hard delete
    public function hard_delete_screenshot($screenshot_id) {
        $org_id = $this->input->get_request_header('org_id', TRUE);
        $user_id = $this->input->get_request_header('user_id', TRUE);
    
        if (empty($org_id) || empty($user_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(["status" => "error", "message" => "Missing org_id or user_id in headers"]));
        }
    
        // Check if screenshot exists
        $this->db->where('screenshot_id', $screenshot_id);
        $this->db->where('org_id', $org_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('screenshots');
    
        if ($query->num_rows() == 0) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(["status" => "error", "message" => "Screenshot not found or unauthorized"]));
        }
    
        // Delete the screenshot
        $this->db->where('screenshot_id', $screenshot_id);
        $this->db->delete('screenshots');
    
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => "success", "message" => "Screenshot deleted successfully"]));
    }

    //soft delete
  public function soft_delete_screenshot() {
    $screenshot_id = $this->input->post('screenshot_id');
    $org_id = $this->input->get_request_header('org_id', TRUE);
    $user_id = $this->input->get_request_header('user_id', TRUE);

    // Validate inputs
    if (empty($screenshot_id) || empty($org_id) || empty($user_id)) {
        return $this->output
            ->set_content_type('text/plain')
            ->set_status_header(400)
            ->set_output("Error: screenshot_id, org_id, and user_id are required.");
    }

    // Soft delete
    $this->db->where('screenshot_id', $screenshot_id);
    $this->db->where('org_id', $org_id);
    $this->db->where('user_id', $user_id);
    $this->db->update('screenshots', ['status' => -1]);

    if ($this->db->affected_rows() > 0) {
        return $this->output
            ->set_content_type('text/plain')
            ->set_status_header(200)
            ->set_output("Soft deleted successfully.");
    } else {
        return $this->output
            ->set_content_type('text/plain')
            ->set_status_header(404)
            ->set_output("Screenshot not found or already deleted.");
    }
}

    
     //list of users
     public function list_organization_users($org_id) {
        // 1. Validate organization ID
        if (empty($org_id) || !is_numeric($org_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid organization ID required'
                ]));
        }
    
        // 2. Get users (only id and name) from specified organization
        $users = $this->db
            ->select('id, name')
            ->where('org_id', $org_id)
            ->where('user_type !=', 'deleted')
            ->order_by('name', 'ASC')
            ->get('users')
            ->result_array();
    
        // 3. Return response
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'org_id' => (int)$org_id,
                'users' => $users
            ]));
    }
    
    //search filter for users
    public function search_filter_by_names($org_id) {
        // 1. Validate organization ID
        if (empty($org_id) || !is_numeric($org_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid organization ID required'
                ]));
        }
    
        // 2. Get optional search keyword from query param
        $search = $this->input->get('search');
    
        // 3. Build query
        $this->db->select('id, name')
            ->from('users')
            ->where('org_id', $org_id)
            ->where('user_type !=', 'deleted');
    
        if (!empty($search)) {
            // Add global name search (case-insensitive)
            $this->db->like('LOWER(name)', strtolower($search));
        }
    
        $this->db->order_by('name', 'ASC');
        $users = $this->db->get()->result_array();
    
        // 4. Return response
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'org_id' => (int)$org_id,
                'search' => $search,
                'users' => $users
            ]));
    }
    
    
    
}
