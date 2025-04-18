<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ScreenshotController extends Home_Controller
{

    public function __construct()
    {
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

    //  public function store_screenshot() {
    //     if ($this->input->server('REQUEST_METHOD') !== 'POST') {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(405)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Invalid request method"
    //             ]));
    //     }

    //     $user_id = $this->input->get_request_header('user_id', TRUE);      // From users table
    //     $employee_id = $this->input->get_request_header('employee_id', TRUE);  // From employees table

    //     if (empty($user_id) || empty($employee_id)) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Missing user_id or employee_id in headers"
    //             ]));
    //     }

    //     if (empty($_FILES['screenshot']['tmp_name'])) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "No file uploaded"
    //             ]));
    //     }

    //     $file_name = $_FILES['screenshot']['name'];
    //     $file_tmp = $_FILES['screenshot']['tmp_name'];
    //     $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    //     $allowed_extensions = ['jpg', 'jpeg', 'png'];
    //     if (!in_array($file_extension, $allowed_extensions)) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Invalid file type"
    //             ]));
    //     }

    //     // Read binary data
    //     $binary_data = file_get_contents($file_tmp);
    //     if (!$binary_data) {
    //         $handle = fopen($file_tmp, "rb");
    //         $binary_data = fread($handle, filesize($file_tmp));
    //         fclose($handle);
    //     }

    //     $data = [
    //         'user_id' => $user_id,               // users.id
    //         'employee_id' => $employee_id,         // employees.id
    //         'file_data' => $binary_data,
    //         'file_type' => $file_extension,      // Make sure this column exists in DB
    //         'created_at' => date('Y-m-d H:i:s')
    //     ];

    //     if (!$this->db->insert('screenshots', $data)) {
    //         $error = $this->db->error();
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "DB Insertion Failed",
    //                 "error" => $error
    //             ]));
    //     }

    //     return $this->output->set_content_type('application/json')
    //         ->set_status_header(201)
    //         ->set_output(json_encode([
    //             "status" => "success",
    //             "message" => "Screenshot stored successfully"
    //         ]));
    // }
    public function store_screenshot()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid request method"
                ]));
        }

        $user_id = $this->input->get_request_header('user_id', TRUE);
        $employee_id = $this->input->get_request_header('employee_id', TRUE);

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

        // Define upload path using user_id
        $upload_path = FCPATH . "uploads/screenshots/{$user_id}/";

        // Create the directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        // Get file extension and construct file name with timestamp
        $file_extension = strtolower(pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION));
        $timestamp = date('Ymd_His');
        $file_name = "screenshot_{$employee_id}_{$timestamp}." . $file_extension;

        $full_path = $upload_path . $file_name;
        $relative_path = "uploads/screenshots/{$user_id}/{$file_name}";

        if (!move_uploaded_file($_FILES['screenshot']['tmp_name'], $full_path)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Failed to move uploaded file"
                ]));
        }

        // Insert record into DB
        $data = [
            'user_id' => $user_id,
            'employee_id' => $employee_id,
            'file_path' => $relative_path,
            'file_type' => $file_extension,
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
                "message" => "Screenshot stored successfully",
                "file_path" => $relative_path
            ]));
    }
    //     public function get_screenshots() {
    //          $employee_id = $this->input->get('employee_id');
    //         // $user_id = $this->input->get('user_id');
    //         $user_id = $this->session->userdata('id');
    //         $date = $this->input->get('date');

    //         if (empty($user_id) && empty($employee_id)) {
    //             return $this->output->set_content_type('application/json')
    //                 ->set_status_header(400)
    //                 ->set_output(json_encode([
    //                     "status" => "error",
    //                     "message" => "Missing user_id or employee_id"
    //                 ]));
    //         }

    //         if (empty($date)) {
    //             $date = date('Y-m-d');
    //         }

    //         // Use user_id as folder name directly
    //         $user_folder = $user_id;
    //         $upload_path = FCPATH . "uploads/screenshots/{$user_folder}/";

    //         if (!is_dir($upload_path)) {
    //             return $this->output->set_content_type('application/json')
    //                 ->set_status_header(404)
    //                 ->set_output(json_encode([
    //                     "status" => "error",
    //                     "message" => "No folder found for user ID {$user_id}"
    //                 ]));
    //         }

    //         $screenshots = [];
    //         $files = scandir($upload_path);

    //         foreach ($files as $file) {
    //             if (strpos($file, "screenshot_{$employee_id}") === 0 && strpos($file, str_replace('-', '', $date)) !== false) {
    //                 preg_match('/screenshot_(\d+)_(\d{8})_(\d{6})\.(\w{3,4})/', $file, $matches);

    //                 if (isset($matches[2]) && isset($matches[3])) {
    //                     $file_date = $matches[2];
    //                     $file_time = $matches[3];
    //                     $file_extension = $matches[4];

    //                     if ($file_date == str_replace('-', '', $date)) {
    //                         $formatted_time = date('H:i:s', strtotime($file_time));
    //                         $file_path = base_url("uploads/screenshots/{$user_folder}/{$file}");

    //                         $screenshots[] = [
    //                             'file_name' => $file,
    //                             'image_url' => $file_path,
    //                             'created_at' => $formatted_time,
    //                             'display_text' => $formatted_time
    //                         ];
    //                     }
    //                 }
    //             }
    //         }

    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode([
    //                 "status" => "success",
    //                 "screenshots" => $screenshots,
    //                 "date" => $date,
    //                 "user_id" => $user_id,
    //                 "employee_id" => $employee_id,
    //                 "message" => empty($screenshots) ? "No screenshots found for user ID {$user_id} on {$date}" : null
    //             ]));
    //     }

    public function get_screenshots()
    {
        $employee_id = $this->input->get('employee_id');
        $user_id = $this->session->userdata('id');
        $date = $this->input->get('date');

        if (empty($user_id) && empty($employee_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing user_id or employee_id"
                ]));
        }

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $user_folder = $user_id;
        $upload_path = FCPATH . "uploads/screenshots/{$user_folder}/";

        if (!is_dir($upload_path)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "No folder found for user ID {$user_id}"
                ]));
        }

        // Fetch screenshot records from DB where status is 1
        $this->db->select('screenshot_id, file_path, created_at');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1); // Only active screenshots
        $this->db->like('created_at', $date);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('screenshots');
        $db_screenshots = $query->result_array();


        $screenshots = [];

        foreach ($db_screenshots as $row) {
            $filename = basename($row['file_path']);
            $full_path = $upload_path . $filename;

            if (file_exists($full_path)) {
                // Extract time from filename (or fallback to created_at)
                preg_match('/screenshot_(\d+)_(\d{8})_(\d{6})\.(\w{3,4})/', $filename, $matches);
                // $formatted_time = isset($matches[3]) ? date('H:i:s', strtotime($matches[3])) : date('H:i:s', strtotime($row['created_at']));
                $formatted_time = isset($matches[3])
                    ? (new DateTime(substr($matches[3], 0, 2) . ':' . substr($matches[3], 2, 2) . ':' . substr($matches[3], 4, 2), new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata'))
                    ->format('H:i:s')
                    : (new DateTime($row['created_at'], new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata'))
                    ->format('H:i:s');

                $screenshots[] = [
                    'id' => $row['screenshot_id'],
                    'file_name' => $filename,
                    'image_url' => base_url("uploads/screenshots/{$user_folder}/{$filename}"),
                    'created_at' => $formatted_time,
                    'display_text' => $formatted_time
                ];
            }
        }

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success",
                "screenshots" => $screenshots,
                "date" => $date,
                "user_id" => $user_id,
                "employee_id" => $employee_id,
                "message" => empty($screenshots) ? "No screenshots found for user ID {$user_id} on {$date}" : null
            ]));
    }






    public function get_user_screenshots()
    {
        $employee_id = $this->session->userdata('employee_id');
        $employee_org_id = $this->session->userdata('employee_org_id');
        $date = $this->input->get('date'); // Optional

        if (empty($employee_id)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing employee_id"
                ]));
        }

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $user_folder = $employee_org_id;
        $upload_path = FCPATH . "uploads/screenshots/{$user_folder}/";

        if (!is_dir($upload_path)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "No screenshot folder found for employee ID {$employee_id}"
                ]));
        }

        // Fetch screenshot records from DB where status = 1
        $this->db->select('screenshot_id, file_path, created_at');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $employee_org_id); // Assuming user_id = org_id
        $this->db->where('status', 1);
        $this->db->like('created_at', $date);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('screenshots');
        $db_screenshots = $query->result_array();

        $screenshots = [];

        foreach ($db_screenshots as $row) {
            $filename = basename($row['file_path']);
            $full_path = $upload_path . $filename;

            if (file_exists($full_path)) {
                preg_match('/screenshot_(\d+)_(\d{8})_(\d{6})\.(\w{3,4})/', $filename, $matches);
                // $formatted_time = isset($matches[3]) ? date('H:i:s', strtotime($matches[3])) : date('H:i:s', strtotime($row['created_at']));
                $formatted_time = isset($matches[3])
                    ? (new DateTime(substr($matches[3], 0, 2) . ':' . substr($matches[3], 2, 2) . ':' . substr($matches[3], 4, 2), new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata'))
                    ->format('H:i:s')
                    : (new DateTime($row['created_at'], new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Kolkata'))
                    ->format('H:i:s');
                $screenshots[] = [
                    'id' => $row['screenshot_id'],
                    'file_name' => $filename,
                    'image_url' => base_url("uploads/screenshots/{$user_folder}/{$filename}"),
                    'created_at' => $formatted_time,
                    'display_text' => $formatted_time
                ];
            }
        }

        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success",
                "screenshots" => $screenshots,
                "date" => $date,
                "employee_id" => $employee_id,
                "message" => empty($screenshots) ? "No screenshots found for employee ID {$employee_id} on {$date}" : null
            ]));
    }



    //hard delete
    public function hard_delete_screenshot($screenshot_id)
    {
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
    public function list_employees_by_user()
    {

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
    public function search_employees_by_name_by_user()
    {
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
