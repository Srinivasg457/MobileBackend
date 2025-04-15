<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScreenshotController extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
    }
    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $data = array();
        $data['page_title'] = 'User Screenshots';
        $data['main_content'] = $this->load->view('admin/user_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

     public function store_screenshot() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid request method"
                ]));
        }

        $user_id = $this->input->get_request_header('user_id', TRUE);      // From users table
        $employee_id = $this->input->get_request_header('employee_id', TRUE);  // From employees table

        if (empty($user_id) || empty($employee_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing user_id or employee_id in headers"
                ]));
        }

        if (empty($_FILES['screenshot']['tmp_name'])) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "No file uploaded"
                ]));
        }

        $file_name = $_FILES['screenshot']['name'];
        $file_tmp = $_FILES['screenshot']['tmp_name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_extension, $allowed_extensions)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid file type"
                ]));
        }

        // Read binary data
        $binary_data = file_get_contents($file_tmp);
        if (!$binary_data) {
            $handle = fopen($file_tmp, "rb");
            $binary_data = fread($handle, filesize($file_tmp));
            fclose($handle);
        }

        $data = [
            'user_id' => $user_id,               // users.id
            'employee_id' => $employee_id,         // employees.id
            'file_data' => $binary_data,
            'file_type' => $file_extension,      // Make sure this column exists in DB
            'created_at' => date('Y-m-d H:i:s')
        ];

        if (!$this->db->insert('screenshots', $data)) {
            $error = $this->db->error();
            return $this->output->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "DB Insertion Failed",
                    "error" => $error
                ]));
        }

        return $this->output->set_content_type('application/json')
            ->set_status_header(201)
            ->set_output(json_encode([
                "status" => "success",
                "message" => "Screenshot stored successfully"
            ]));
    }

    
    public function get_screenshots() {
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->session->userdata('id');
        $date = $this->input->get('date'); // Optional date in 'YYYY-MM-DD'

        if (empty($user_id) && empty($employee_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing user_id or employee_id"
                ]));
        }

        // Default to today’s date if none is provided
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $this->db->select('screenshot_id, file_data, file_type, created_at');
        $this->db->from('screenshots');

        if (!empty($user_id)) {
            $this->db->where('user_id', $user_id);
        }

        if (!empty($employee_id)) {
            $this->db->where('employee_id', $employee_id);
        }

        $this->db->where('status', 1);
        $this->db->where('DATE(created_at)', $date); // 📅 Filter by date
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $message = "No screenshots found";
            if (!empty($date)) {
                $message .= " for date " . $date;
            }
            if (!empty($user_id)) {
                $message .= " for user ID " . $user_id;
            }
            if (!empty($employee_id)) {
                $message .= " for employee ID " . $employee_id;
            }
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => $message
                ]));
        }
        if ($query->num_rows() === 0) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status" => "success",
                    "screenshots" => [],
                    "message" => "No screenshots found for employee ID {$employee_id} on {$date}",
                    "date" => $date,
                    "employee_id" => $employee_id
                ]));
        }

        $screenshots = [];
        foreach ($query->result() as $row) {
            $formatted_date = date('H:i:s', strtotime($row->created_at));
            $base64_image = base64_encode($row->file_data);
            $image_data_url = 'data:image/' . $row->file_type . ';base64,' . $base64_image;

            $screenshots[] = [
                'id' => $row->screenshot_id,
                'image_data' => $image_data_url,
                'created_at' => $formatted_date,
                'display_text' => $formatted_date
            ];
        }

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success",
                "screenshots" => $screenshots,
                "date" => $date,
                "user_id" => $user_id,
                "employee_id" => $employee_id
            ]));
    }

    public function get_user_screenshots()
    {
        $employee_id = $this->session->userdata('employee_id');
        $date = $this->input->get('date'); // Optional date in 'YYYY-MM-DD'

        if (empty($employee_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing employee_id"
                ]));
        }

        // Default to today’s date if not provided
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $this->db->select('screenshot_id, file_data, file_type, created_at');
        $this->db->from('screenshots');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('status', 1);
        $this->db->where('DATE(created_at)', $date); // Filter by date
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() === 0) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status" => "success",
                    "screenshots" => [],
                    "message" => "No screenshots found for employee ID {$employee_id} on {$date}",
                    "date" => $date,
                    "employee_id" => $employee_id
                ]));
        }

        $screenshots = [];
        foreach ($query->result() as $row) {
            $formatted_date = date('H:i:s', strtotime($row->created_at));
            $base64_image = base64_encode($row->file_data);
            $image_data_url = 'data:image/' . $row->file_type . ';base64,' . $base64_image;

            $screenshots[] = [
                'id' => $row->screenshot_id,
                'image_data' => $image_data_url,
                'created_at' => $formatted_date,
                'display_text' => $formatted_date
            ];
        }

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success",
                "screenshots" => $screenshots,
                "date" => $date,
                "employee_id" => $employee_id
            ]));
    }

    //hard delete
    public function hard_delete_screenshot($screenshot_id) {
        $user_id = $this->input->get_request_header('user_id', TRUE);
        $employee_id = $this->input->get_request_header('employee_id', TRUE);

        if (empty($user_id) && empty($employee_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(["status" => "error", "message" => "Missing user_id or employee_id in headers"]));
        }

        // Check if screenshot exists
        $this->db->where('screenshot_id', $screenshot_id);
        if (!empty($user_id)) {
            $this->db->where('user_id', $user_id);
        }
        if (!empty($employee_id)) {
            $this->db->where('employee_id', $employee_id);
        }
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

    public function soft_delete_screenshot()
    {
        $screenshot_id = $this->input->post('screenshot_id', true);
        $employee_id = $this->input->post('employee_id', true);
        $user_id = $this->session->userdata('user_id'); // or null if not logged in

        // Validate inputs
        if (empty($screenshot_id) || (empty($user_id) && empty($employee_id))) {
            return $this->output
                ->set_content_type('text/plain')
                ->set_status_header(400)
                ->set_output("Error: screenshot_id and either user_id or employee_id are required.");
        }

        // Build query
        $this->db->where('screenshot_id', $screenshot_id);
        if (!empty($user_id)) {
            $this->db->where('user_id', $user_id);
        }
        if (!empty($employee_id)) {
            $this->db->where('employee_id', $employee_id);
        }

        $this->db->update('screenshots', ['status' => -1]);

        // Response
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

    //list of employees based on user ID
    public function list_employees_by_user() {

        $user_id = $this->session->userdata('id');

        // 1. Validate user ID
        if (empty($user_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid user ID required'
                ]));
        }
        // $data['user'] = $this->User_model->get_user_by_id($user_id, "users");
        // 2. Get employees from the employees table matching the provided user_id
        $employees = $this->db
            ->select('id, name, email') // Select the employee details you need
            ->where('user_id', $user_id)
            ->get('employees')
            ->result_array();

        // 3. Return response
        if ($employees) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => 'success',
                    'user_id' => (int)$user_id,
                    'employees' => $employees,
                ]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'No employees found for the given user ID'
                ]));
        }
    }
   

    //search employees by name based on user ID
    public function search_employees_by_name_by_user() {
        $user_id = $this->session->userdata('id');
        // 1. Validate user ID
        if (empty($user_id) || !is_numeric($user_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Valid user ID required'
                ]));
        }

        // 2. Get optional search keyword from query param
        $search = $this->input->get('search');

        // 3. Build query
        $this->db->select('id, name, email') // Select the employee details you need
            ->from('employees')
            ->where('user_id', $user_id);

        if (!empty($search)) {
            // Add case-insensitive name search
            $this->db->like('LOWER(name)', strtolower($search));
        }

        $this->db->order_by('name', 'ASC');
        $employees = $this->db->get()->result_array();

        // 4. Return response
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => 'success',
                'user_id' => (int)$user_id,
                'search' => $search,
                'employees' => $employees
            ]));
    }
    
    
}
