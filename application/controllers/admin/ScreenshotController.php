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
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        $data = array();
        $data['page_title'] = 'User Screenshots';
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/user_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function webCam()
    {
        $data = array();
        $data['page_title'] = 'Webcam screenshots';
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/webcam_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
    public function EmployeewebCam()
    {
        $data = array();
        $data['page_title'] = 'Employee Webcam screenshots';
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/employee/webcam_screenshot', $data, TRUE);
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
        $provided_timestamp = $this->input->get_request_header('timestamp', TRUE);

        if (empty($user_id) || empty($employee_id) || empty($provided_timestamp)) {
            $missing_fields = [];
            if (empty($user_id)) $missing_fields[] = 'user_id';
            if (empty($employee_id)) $missing_fields[] = 'employee_id';
            if (empty($provided_timestamp)) $missing_fields[] = 'timestamp';

            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing required fields in headers: " . implode(', ', $missing_fields)
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

        // Capture overall_activity_percent and is_active from POST data
        $overall_activity_percent = $this->input->get_request_header('overall_activity_percent', TRUE);
        if($overall_activity_percent>60){
            $is_active =2;
        }else if($overall_activity_percent>40 && $overall_activity_percent<=60){
            $is_active =1;
        }else{
            $is_active =0;
        }


        // Validate overall_activity_percent
        if (!is_numeric($overall_activity_percent) || $overall_activity_percent < 0 || $overall_activity_percent > 100) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid value for overall_activity_percent. It should be a number between 0 and 100."
                ]));
        }

        // Validate is_active
        if (!in_array($is_active, [0, 1, 2], true)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid value for is_active. It should be either 0, 1, or 2."
                ]));
        }

        // Validate the provided timestamp format
        try {
            $timestamp_object = new DateTime($provided_timestamp);
            $formatted_timestamp = $timestamp_object->format('Ymd_His'); // Change format here
        } catch (Exception $e) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid timestamp format. Please use a format that DateTime can parse."
                ]));
        }

        // Define upload path using user_id
        $upload_path = FCPATH . "uploads/screenshots/{$user_id}/";

        // Create the directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        // Get file extension and construct file name
        $file_extension = strtolower(pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION));
        $file_name = "screenshot_{$employee_id}_{$formatted_timestamp}." . $file_extension; // Use formatted timestamp

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
            'created_at' => date('Y-m-d H:i:s', strtotime($provided_timestamp)), // Use original timestamp value
            'overall_activity_percent' => $overall_activity_percent,
            'is_active' => $is_active
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
    public function store_webcam()
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
    $provided_timestamp = $this->input->get_request_header('timestamp', TRUE);

    if (empty($user_id) || empty($employee_id) || empty($provided_timestamp)) {
        $missing_fields = [];
        if (empty($user_id)) $missing_fields[] = 'user_id';
        if (empty($employee_id)) $missing_fields[] = 'employee_id';
        if (empty($provided_timestamp)) $missing_fields[] = 'timestamp';

        return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Missing required fields in headers: " . implode(', ', $missing_fields)
            ]));
    }

    if (empty($_FILES['webcam_image']['tmp_name'])) {
        return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "No file uploaded"
            ]));
    }

    // Validate timestamp format
    $timestamp = strtotime($provided_timestamp);
    if ($timestamp === false) {
        return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Invalid timestamp format"
            ]));
    }

    // Format timestamp for filename (yy-mm-dd hh-mm-ss)
    $formatted_timestamp = date('y-m-d H-i-s', $timestamp);
    
    // Define upload path using user_id and employee_id
    $upload_path = FCPATH . "uploads/webcam/{$user_id}/{$employee_id}/";

    // Create the directory if it doesn't exist
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }

    // Get file extension and construct file name
    $file_extension = strtolower(pathinfo($_FILES['webcam_image']['name'], PATHINFO_EXTENSION));
    $file_name = "webcam_{$formatted_timestamp}.{$file_extension}";

    $full_path = $upload_path . $file_name;
    $relative_path = "uploads/webcam/{$user_id}/{$employee_id}/{$file_name}";

    if (!move_uploaded_file($_FILES['webcam_image']['tmp_name'], $full_path)) {
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
        'status' => 1, // Assuming 1 means active/successful
        'created_at' => date('Y-m-d H:i:s', $timestamp),
        'is_active' => 1 // Assuming you want this to be active by default
    ];

    if (!$this->db->insert('webcam', $data)) {
        $error = $this->db->error();
        return $this->output->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "DB Insertion Failed",
                "error" => $error
            ]));
    }

    // Get the inserted webcam_id
    $webcam_id = $this->db->insert_id();

    return $this->output->set_content_type('application/json')
        ->set_status_header(201)
        ->set_output(json_encode([
            "status" => "success",
            "message" => "Webcam image stored successfully",
            "file_path" => $relative_path,
            "webcam_id" => $webcam_id
        ]));
}
    // public function store_webcam_screenshot()
    // {
    //     if ($this->input->server('REQUEST_METHOD') !== 'POST') {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(405)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Invalid request method"
    //             ]));
    //     }
    
    //     $user_id = $this->input->get_request_header('user_id', TRUE);
    //     $employee_id = $this->input->get_request_header('employee_id', TRUE);
    //     $provided_timestamp = $this->input->get_request_header('timestamp', TRUE);
    
    //     // Check required headers
    //     $missing_fields = [];
    //     if (empty($user_id)) $missing_fields[] = 'user_id';
    //     if (empty($employee_id)) $missing_fields[] = 'employee_id';
    //     if (empty($provided_timestamp)) $missing_fields[] = 'timestamp';
    
    //     if (!empty($missing_fields)) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Missing required headers: " . implode(', ', $missing_fields)
    //             ]));
    //     }
    
    //     // Check file upload
    //     if (empty($_FILES['webcam_image']['tmp_name'])) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "No webcam image uploaded"
    //             ]));
    //     }
    
    //     // Validate and parse timestamp
    //     try {
    //         $timestamp_object = new DateTime($provided_timestamp);
    //     } catch (Exception $e) {
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Invalid timestamp format"
    //             ]));
    //     }
    
    //     // Read binary file content
    //     $file_data = file_get_contents($_FILES['webcam_image']['tmp_name']);
    //     $file_type = $_FILES['webcam_image']['type'];
    
    //     // Prepare data for DB insert
    //     $insert_data = [
    //         'user_id' => $user_id,
    //         'employee_id' => $employee_id,
    //         'file_data' => $file_data,
    //         'file_type' => $file_type,
    //         'created_at' => $timestamp_object->format('Y-m-d H:i:s')
    //     ];
    
    //     // Insert into DB
    //     if (!$this->db->insert('webcam', $insert_data)) {
    //         $error = $this->db->error();
    //         return $this->output->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode([
    //                 "status" => "error",
    //                 "message" => "Database insertion failed",
    //                 "error" => $error
    //             ]));
    //     }
    
    //     return $this->output->set_content_type('application/json')
    //         ->set_status_header(201)
    //         ->set_output(json_encode([
    //             "status" => "success",
    //             "message" => "Webcam screenshot saved successfully"
    //         ]));
    // }
    

    
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
        $employee_id = $this->input->get('employee_id') ?? 4;
        $user_id = $this->input->get('user_id') ?? 3;
        $date = '2025-05-26'; // Optional
    
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
        $compressed_path = FCPATH . "uploads/screenshots/{$user_folder}/compressed/";
    
        if (!is_dir($upload_path)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "No screenshot folder found for user ID {$user_id}"
                ]));
        }
    
        // Create compressed directory if it doesn't exist
        if (!is_dir($compressed_path)) {
            mkdir($compressed_path, 0755, true);
        }
    
        // Fetch screenshot records from DB where status = 1
        $this->db->select('screenshot_id, file_path, created_at');
        $this->db->where('employee_id', $employee_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1);
        $this->db->like('created_at', $date);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('screenshots');
        $db_screenshots = $query->result_array();
    
        $screenshots = [];
        $total_original_size = 0;
        $total_compressed_size = 0;
        $compressed_count = 0;
        $failed_compression = 0;
    
        foreach ($db_screenshots as $row) {
            $filename = basename($row['file_path']);
            $full_path = $upload_path . $filename;
            $compressed_filename = 'compressed_' . $filename;
            $compressed_full_path = $compressed_path . $compressed_filename;
        
            if (file_exists($full_path)) {
                $original_size = filesize($full_path) / 1024; // Size in KB
                $total_original_size += $original_size;
                
                $compressed_size = 0;
                
                // Strictly compress to under 6KB
                if ($this->strict_compress_image($full_path, $compressed_full_path, 6)) {
                    $compressed_size = file_exists($compressed_full_path) ? filesize($compressed_full_path) / 1024 : 0;
                    
                    // Verify the size is strictly below 6KB
                    if ($compressed_size >= 10) {
                        // If still too large, delete the compressed version and mark as failed
                        @unlink($compressed_full_path);
                        $failed_compression++;
                        continue;
                    }
                    
                    $compressed_count++;
                    $total_compressed_size += $compressed_size;
                } else {
                    $failed_compression++;
                    continue;
                }
    
                $formatted_time = date('H:i:s', strtotime($row['created_at']));
        
                $screenshots[] = [
                    'id' => $row['screenshot_id'],
                    'file_name' => $compressed_filename,
                    'image_url' => base_url("uploads/screenshots/{$user_folder}/compressed/{$compressed_filename}"),
                    'created_at' => $formatted_time,
                    'display_text' => $formatted_time,
                    'original_size_kb' => round($original_size, 2),
                    'compressed_size_kb' => round($compressed_size, 2),
                    'compression_ratio' => $original_size > 0 ? round(($original_size - $compressed_size) / $original_size * 100, 2) : 0,
                    'compression_status' => $compressed_size < 10 ? 'success' : 'failed'
                ];
            }
        }
    
        $compression_message = '';
        if ($compressed_count > 0) {
            $compression_message = "Successfully compressed {$compressed_count} images to under 6KB. ";
        }
        if ($failed_compression > 0) {
            $compression_message .= "Failed to compress {$failed_compression} images to under 6KB. ";
        }
        
        $compression_message .= sprintf(
            "Total size reduced from %.2fKB to %.2fKB (%.2f%% reduction)",
            $total_original_size,
            $total_compressed_size,
            $total_original_size > 0 ? (($total_original_size - $total_compressed_size) / $total_original_size * 100) : 0
        );
    
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                "status" => "success",
                "screenshots" => $screenshots,
                "date" => $date,
                "employee_id" => $employee_id,
                "user_id" => $user_id,
                "compression_info" => [
                    "original_total_size_kb" => round($total_original_size, 2),
                    "compressed_total_size_kb" => round($total_compressed_size, 2),
                    "reduction_percentage" => $total_original_size > 0 ? round(($total_original_size - $total_compressed_size) / $total_original_size * 100, 2) : 0,
                    "compressed_images_count" => $compressed_count,
                    "failed_compressions" => $failed_compression,
                    "size_limit_kb" => 10
                ],
                "message" => empty($screenshots) ? 
                    "No screenshots found for employee ID {$employee_id} on {$date}" : 
                    $compression_message
            ]));
    }

public function get_webcam_screenshots()
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

    // Fetch webcam screenshot records from DB where status is 1
    $this->db->select('webcam_id, file_path, created_at, status, user_id, employee_id');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 1); // Only active screenshots
    $this->db->like('created_at', $date);
    $this->db->order_by('created_at', 'DESC');
    $query = $this->db->get('webcam');
    $db_screenshots = $query->result_array();

    $screenshots = [];

    foreach ($db_screenshots as $row) {
        $filename = basename($row['file_path']);
        $created_at = new DateTime($row['created_at']);
        
        $screenshots[] = [
            'id' => (string)$row['webcam_id'],
            'image_url' => base_url( $row['file_path']),
            'file_type' => pathinfo($filename, PATHINFO_EXTENSION), // Changed from 'Message' to 'file_type'
            'status' => (int)$row['status'],
            'is_active' => 1,
            'display_text' => $created_at->format('H:i:'), // Changed from 'Time' to 'captured_at'
            'user_id' => (string)$row['user_id'],
            'employee_id' => (string)$row['employee_id']
        ];
    }

    return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            "status" => "success",
            "screenshots" => $screenshots,
            "metadata" => [
                "date" => $date,
                "total_records" => count($screenshots),
                "server_time" => date('Y-m-d H:i:s')
            ],
            "message" => empty($screenshots) ? "No webcam screenshots found for user ID {$user_id} on {$date}" : null
        ]));
}

public function get_last_screenshot()
{
    $employee_id = $this->input->get('employee_id');
    $user_id = $this->session->userdata('id');
    $date = date('Y-m-d'); // Always use today's date

    if (empty($user_id) || empty($employee_id)) {
        return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Missing user_id or employee_id"
            ]));
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

    // Get the most recent screenshot of today
    $this->db->select('screenshot_id, file_path, created_at');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 1); // Only active screenshots
    $this->db->like('created_at', $date); // Filter by today's date
    $this->db->order_by('created_at', 'DESC'); // Latest first
    $this->db->limit(1);
    $query = $this->db->get('screenshots');
    $row = $query->row_array();

    if ($row) {
        $filename = basename($row['file_path']);
        $full_path = $upload_path . $filename;

        if (file_exists($full_path)) {
            $formatted_time = date('H:i:s', strtotime($row['created_at']));

            $screenshot = [
                'id' => $row['screenshot_id'],
                'file_name' => $filename,
                'image_url' => base_url("uploads/screenshots/{$user_folder}/{$filename}"),
                'created_at' => $formatted_time,
                'display_text' => $formatted_time
            ];

            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status" => "success",
                    "screenshot" => $screenshot,
                    "date" => $date,
                    "user_id" => $user_id,
                    "employee_id" => $employee_id
                ]));
        }
    }

    return $this->output->set_content_type('application/json')
        ->set_status_header(404)
        ->set_output(json_encode([
            "status" => "error",
            "message" => "No recent screenshot found for user ID {$user_id} on {$date}"
        ]));
}








   public function get_user_screenshots()
{
    $employee_id = $this->session->userdata('employee_id')?? 2;
    $user_id = $this->session->userdata('user_id')?? 3;
    $date = $this->input->get('date'); // Optional

    if (empty($employee_id)) {
        return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Missing employee_id"
            ]));
    }

    if (empty($user_id)) {
        return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Missing user_id"
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
                "message" => "No screenshot folder found for user ID {$user_id}"
            ]));
    }

    // Fetch screenshot records from DB where status = 1
    $this->db->select('screenshot_id, file_path, created_at');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
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
            // Use created_at time directly
            $formatted_time = date('H:i:s', strtotime($row['created_at']));
    
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

    private function strict_compress_image($source_path, $destination_path, $max_kb)
    {
        $info = getimagesize($source_path);
        $mime = $info['mime'];

        // Check if WEBP format
        if ($mime === 'image/webp') {
            return $this->compress_webp_image($source_path, $destination_path, $max_kb);
        }

        // For other formats, convert to WEBP for better compression
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                case 'image/gif':
                // For PNG and GIF, convert to JPEG first for better compression
                $image = imagecreatetruecolor($info[0], $info[1]);
                $white = imagecolorallocate($image, 255, 255, 255);
                imagefill($image, 0, 0, $white);
                
                if ($mime === 'image/png') {
                    $original = imagecreatefrompng($source_path);
                } else {
                    $original = imagecreatefromgif($source_path);
                }
                
                imagecopy($image, $original, 0, 0, 0, 0, $info[0], $info[1]);
                imagedestroy($original);
                break;
            default:
                return false;
        }

        // Get original dimensions
        $original_width = imagesx($image);
        $original_height = imagesy($image);
        
        // Calculate new dimensions - start with more aggressive reduction
        $max_dimension = 800; // Maximum width or height
        $ratio = min($max_dimension/$original_width, $max_dimension/$original_height);
        $new_width = (int)($original_width * $ratio);
        $new_height = (int)($original_height * $ratio);
        
        // Create a new image with reduced dimensions
        $resized_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
        imagedestroy($image);
        $image = $resized_image;

        // Now compress with aggressive quality settings
        $quality = 70; // Start with low quality
        $min_quality = 15;
        $max_iterations = 20;
        $iteration = 0;
        $best_quality = $quality;
        $best_size = null;
        
        do {
            // Output image to buffer
            ob_start();
            imagewebp($image, null, $quality);
            $image_data = ob_get_clean();
            $current_size = strlen($image_data) / 1024; // Size in KB
            
            // Track best quality that meets our size requirement
            if ($current_size <= $max_kb) {
                if ($best_size === null || $current_size < $best_size) {
                    $best_quality = $quality;
                    $best_size = $current_size;
                }
                
                // Try to find the best quality within limit
                if ($current_size < $max_kb * 0.8) { // If we're well under, try higher quality
                    $quality += 5;
                } else {
                    $quality += 1;
                }
            } else {
                $quality -= 5; // Reduce quality more aggressively
            }
            
            $iteration++;
        } while ($iteration < $max_iterations && $quality >= $min_quality && $quality <= 100);

        // Use the best quality we found
        if ($best_size !== null) {
            $quality = $best_quality;
        } else {
            // If we didn't find a suitable quality, use minimum quality
            $quality = $min_quality;
            ob_start();
            imagewebp($image, null, $quality);
            $image_data = ob_get_clean();
            $best_size = strlen($image_data) / 1024;
        }

        // Save final compressed image as WEBP
        $result = imagewebp($image, $destination_path, $quality);
        imagedestroy($image);
        
        // Verify the final size is strictly below max_kb
        if (file_exists($destination_path)) {
            $final_size = filesize($destination_path) / 1024;
            if ($final_size >= $max_kb) {
                // If still too large, try more aggressive compression
                @unlink($destination_path);
                return $this->aggressive_compress($source_path, $destination_path, $max_kb);
            }
        }
        
        return $result;
    }

    private function compress_webp_image($source_path, $destination_path, $max_kb)
    {
        // First try to read as WEBP
        if (function_exists('imagecreatefromwebp')) {
            $image = @imagecreatefromwebp($source_path);
            if ($image === false) {
                return false;
            }
        } else {
            return false;
        }

        // Get original dimensions
        $original_width = imagesx($image);
        $original_height = imagesy($image);
        
        // Calculate new dimensions - more aggressive reduction for WEBP
        $max_dimension = 600; // Maximum width or height for WEBP
        $ratio = min($max_dimension/$original_width, $max_dimension/$original_height);
        $new_width = (int)($original_width * $ratio);
        $new_height = (int)($original_height * $ratio);
        
        // Create a new image with reduced dimensions
        $resized_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
        imagedestroy($image);
        $image = $resized_image;

        // Now compress with very aggressive quality settings for WEBP
        $quality = 70; // Start with very low quality for WEBP
        $min_quality = 15;
        $max_iterations = 25;
        $iteration = 0;
        $best_quality = $quality;
        $best_size = null;
        
        do {
            // Output image to buffer
            ob_start();
            imagewebp($image, null, $quality);
            $image_data = ob_get_clean();
            $current_size = strlen($image_data) / 1024; // Size in KB
            
            // Track best quality that meets our size requirement
            if ($current_size <= $max_kb) {
                if ($best_size === null || $current_size < $best_size) {
                    $best_quality = $quality;
                    $best_size = $current_size;
                }
                
                // Try to find the best quality within limit
                if ($current_size < $max_kb * 0.7) { // If we're well under, try higher quality
                    $quality += 3;
                } else {
                    $quality += 1;
                }
            } else {
                $quality -= 5; // Reduce quality more aggressively
            }
            
            $iteration++;
        } while ($iteration < $max_iterations && $quality >= $min_quality && $quality <= 100);

        // Use the best quality we found
        if ($best_size !== null) {
            $quality = $best_quality;
        } else {
            // If we didn't find a suitable quality, use minimum quality
            $quality = $min_quality;
            ob_start();
            imagewebp($image, null, $quality);
            $image_data = ob_get_clean();
            $best_size = strlen($image_data) / 1024;
        }

        // Save final compressed image
        $result = imagewebp($image, $destination_path, $quality);
        imagedestroy($image);
        
        // Verify the final size is strictly below max_kb
        if (file_exists($destination_path)) {
            $final_size = filesize($destination_path) / 1024;
            if ($final_size >= $max_kb) {
                // If still too large, try more aggressive compression
                @unlink($destination_path);
                return $this->aggressive_compress($source_path, $destination_path, $max_kb);
            }
        }
        
        return $result;
    }

    private function aggressive_compress($source_path, $destination_path, $max_kb)
    {
        $info = getimagesize($source_path);
        $mime = $info['mime'];

        // Always convert to WEBP for maximum compression
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
            case 'image/gif':
                // For PNG and GIF, convert to JPEG first
                $image = imagecreatetruecolor($info[0], $info[1]);
                $white = imagecolorallocate($image, 255, 255, 255);
                imagefill($image, 0, 0, $white);
                
                if ($mime === 'image/png') {
                    $original = imagecreatefrompng($source_path);
                } else {
                    $original = imagecreatefromgif($source_path);
                }
                
                imagecopy($image, $original, 0, 0, 0, 0, $info[0], $info[1]);
                imagedestroy($original);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source_path);
                break;
            default:
                return false;
        }

        // Get original dimensions
        $original_width = imagesx($image);
        $original_height = imagesy($image);
        
        // Calculate very small dimensions (fixed small size)
        $max_dimension = 400; // Maximum width or height
        $ratio = min($max_dimension/$original_width, $max_dimension/$original_height);
        $new_width = (int)($original_width * $ratio);
        $new_height = (int)($original_height * $ratio);
        
        // Create a new image with very small dimensions
        $resized_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
        imagedestroy($image);
        $image = $resized_image;

        // Use extremely low quality settings
        $quality = 70; // Start with minimum reasonable quality
        $min_quality = 15;
        $max_iterations = 30;
        $iteration = 0;
        $best_size = null;
        
        do {
            // Output image to buffer
            ob_start();
            imagewebp($image, null, $quality);
            $image_data = ob_get_clean();
            $current_size = strlen($image_data) / 1024; // Size in KB
            
            // Track if we meet our target
            if ($current_size <= $max_kb) {
                $best_size = $current_size;
                break;
            }
            
            // Reduce quality aggressively
            $quality -= 2;
            $iteration++;
        } while ($iteration < $max_iterations && $quality >= $min_quality);

        // If we didn't meet the target, use the smallest we could get
        if ($best_size === null || $best_size >= $max_kb) {
            // Try with absolute minimum quality and even smaller dimensions
            $max_dimension = 500;
            $ratio = min($max_dimension/$original_width, $max_dimension/$original_height);
            $new_width = (int)($original_width * $ratio);
            $new_height = (int)($original_height * $ratio);
            
            $resized_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            imagedestroy($image);
            $image = $resized_image;
            
            ob_start();
            imagewebp($image, null, $min_quality);
            $image_data = ob_get_clean();
            $best_size = strlen($image_data) / 1024;
            $quality = $min_quality;
        }

        // Save final compressed image
        $result = imagewebp($image, $destination_path, $quality);
        imagedestroy($image);
        
        // Final verification
        if (file_exists($destination_path)) {
            $final_size = filesize($destination_path) / 1024;
            if ($final_size >= $max_kb) {
                @unlink($destination_path);
                return false;
            }
        }
        
        return $result;
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
    public function delete_webcam_screenshot()
    {
        $webcam_id = $this->input->post('webcam_id');
        $employee_id = $this->input->post('employee_id');

        if (empty($webcam_id) || empty($employee_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Missing webcam_id or employee_id"
                ]));
        }

        // Update status to 0 (soft delete) with additional employee_id check
        $this->db->where('webcam_id', $webcam_id);
        $this->db->where('employee_id', $employee_id);
        $updated = $this->db->update('webcam', ['status' => 0]);

        if ($updated && $this->db->affected_rows() > 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    "status" => "success",
                    "message" => "Webcam screenshot marked as deleted."
                ]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Screenshot not found or already deleted."
                ]));
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
