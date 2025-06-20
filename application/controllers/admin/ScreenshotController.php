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
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
    }
    public function webCam()
    {
        $data = array();
        $data['page_title'] = 'Webcam screenshots';
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/webcam_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription');
        }
        if(is_plan_basic()){
            redirect('/admin/subscription'); 
        }
    }
    public function EmployeewebCam()
    {
        $data = array();
        $data['page_title'] = 'Employee Webcam screenshots';
        $data['main_page'] = 'Analytics';
        $data['main_content'] = $this->load->view('admin/employee/webcam_screenshot', $data, TRUE);
        $this->load->view('admin/index', $data);
        // if (!is_subscribed()) {
        //     redirect('/admin/subscription');
        // }
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
            $formatted_timestamp = $timestamp_object->format('Ymd_His');
            $timestamp = $timestamp_object->getTimestamp();
        } catch (Exception $e) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Invalid timestamp format. Please use a format that DateTime can parse."
                ]));
        }
    
        // Define upload paths - original and compressed
        $original_upload_path = FCPATH . "uploads/screenshots/{$user_id}/{$employee_id}/";
        $compressed_upload_path = FCPATH . "uploads/screenshots_compressed/{$user_id}/{$employee_id}/";
    
        // Create the directories if they don't exist
      // Ensure original upload path exists and has 0777 permission
 // Ensure original upload path exists and has 0777 permission
if (!is_dir($original_upload_path)) {
    mkdir($original_upload_path, 0777, true);
    chmod($original_upload_path, 0777); // Explicitly set permissions
}
if (!is_dir($compressed_upload_path)) {
    mkdir($compressed_upload_path, 0777, true);
    chmod($compressed_upload_path, 0777); // Explicitly set permissions
}

        // Get file extension and construct file names
        $file_extension = strtolower(pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION));
        $file_name = "screenshot_{$employee_id}_{$formatted_timestamp}.{$file_extension}";
    
        // Original file paths
        $original_full_path = $original_upload_path . $file_name;
        $original_relative_path = "uploads/screenshots/{$user_id}/{$employee_id}/{$file_name}";
    
        // Compressed file paths
        $compressed_file_name = "screenshot_{$employee_id}_{$formatted_timestamp}.jpg"; // Always save compressed as JPG
        $compressed_full_path = $compressed_upload_path . $compressed_file_name;
        $compressed_relative_path = "uploads/screenshots_compressed/{$user_id}/{$employee_id}/{$compressed_file_name}";
    
        // Move uploaded file to original location
        if (!move_uploaded_file($_FILES['screenshot']['tmp_name'], $original_full_path)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    "status" => "error",
                    "message" => "Failed to move uploaded file"
                ]));
        }
        // Compress the image (target size 100KB)
        $compression_success = $this->compressScreenshot($original_full_path, $compressed_full_path, 100);
    
        if (!$compression_success) {
            // If compression fails, use original path for both
            $compressed_relative_path = $original_relative_path;
        }
        // Insert record into DB
        $data = [
            'user_id' => $user_id,
            'employee_id' => $employee_id,
            'file_path' => $original_relative_path,
            'compressed_path' => $compressed_relative_path,
            'file_type' => $file_extension,
            'created_at' => date('Y-m-d H:i:s', $timestamp),
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
            $screenshot_id = $this->db->insert_id();
    
        return $this->output->set_content_type('application/json')
            ->set_status_header(201)
            ->set_output(json_encode([
                "status" => "success",
                "message" => "Screenshot stored successfully",
                "original_path" => $original_relative_path,
                "compressed_path" => $compressed_relative_path,
                "screenshot_id" => $screenshot_id
            ]));
    }
    
    
    private function compressScreenshot($source, $destination, $target_size_kb) {
        // Check if GD is installed
        if (!extension_loaded('gd')) {
            error_log("GD library not available");
            return false;
        }
    
        // Get image info
        $info = getimagesize($source);
        if (!$info) {
            error_log("Invalid image file");
            return false;
        }
    
        // Allow JPEG/PNG/WebP
        if (!in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'])) {
            error_log("Unsupported image type");
            return false;
        }
    
        // Load image
        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                // Convert PNG to JPEG with white background
                $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                imagealphablending($bg, TRUE);
                imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                imagedestroy($image);
                $image = $bg;
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source);
                break;
            default:
                return false;
        }
    
        if (!$image) {
            error_log("Failed to load image");
            return false;
        }
    
        // Resize settings - more aggressive for 5KB target
        $max_width = 650; // Reduced from 800
        $width = imagesx($image);
        $height = imagesy($image);
    
        // Resize if needed
        if ($width > $max_width) {
            $new_height = (int)($height * $max_width / $width);
            $resized = imagescale($image, $max_width, $new_height);
            imagedestroy($image);
            $image = $resized;
        }
    
        // Compression attempt with more aggressive settings
        $quality = 50; // Start lower
        $min_quality = 5; // Go much lower
        $success = false;
    
        do {
            // Save as JPEG
            imagejpeg($image, $destination, $quality);
            
            // Check size
            $current_size = filesize($destination) / 1024; // KB
    
            if ($current_size <= $target_size_kb) {
                $success = true;
                break;
            }
    
            $quality -= 5;
        } while ($quality >= $min_quality);
    
        // Clean up
        imagedestroy($image);
    
        return $success;
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
    
    // Define upload paths - original and compressed
    $original_upload_path = FCPATH . "uploads/webcam/{$user_id}/{$employee_id}/";
    $compressed_upload_path = FCPATH . "uploads/webcam_compressed/{$user_id}/{$employee_id}/";

    // Create the directories if they don't exist
    if (!is_dir($original_upload_path)) {
        mkdir($original_upload_path, 0755, true);
    }
    if (!is_dir($compressed_upload_path)) {
        mkdir($compressed_upload_path, 0755, true);
    }

    // Get file extension and construct file names
    $file_extension = strtolower(pathinfo($_FILES['webcam_image']['name'], PATHINFO_EXTENSION));
    $file_name = "webcam_{$formatted_timestamp}.{$file_extension}";

    // Original file paths
    $original_full_path = $original_upload_path . $file_name;
    $original_relative_path = "uploads/webcam/{$user_id}/{$employee_id}/{$file_name}";

    // Compressed file paths
    $compressed_file_name = "webcam_{$formatted_timestamp}.jpg"; // Always save compressed as JPG
    $compressed_full_path = $compressed_upload_path . $compressed_file_name;
    $compressed_relative_path = "uploads/webcam_compressed/{$user_id}/{$employee_id}/{$compressed_file_name}";

    // Move uploaded file to original location
    if (!move_uploaded_file($_FILES['webcam_image']['tmp_name'], $original_full_path)) {
        return $this->output->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode([
                "status" => "error",
                "message" => "Failed to move uploaded file"
            ]));
    }

    // Compress the image (target size 100KB)
    $compression_success = $this->compressImage($original_full_path, $compressed_full_path, 100);

    if (!$compression_success) {
        // If compression fails, use original path for both
        $compressed_relative_path = $original_relative_path;
    }

    $data = [
        'user_id' => $user_id,
        'employee_id' => $employee_id,
        'file_path' => $original_relative_path, // original path
        'compressed_path' => $compressed_relative_path, // compressed path
        'file_type' => $file_extension,
        'status' => 1,
        'created_at' => date('Y-m-d H:i:s', $timestamp),
        'is_active' => 1
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
            "original_path" => $original_relative_path,
            "compressed_path" => $compressed_relative_path,
            "webcam_id" => $webcam_id
        ]));
}

private function compressImage($source, $destination, $target_size_kb) {
// Check if GD is installed
if (!extension_loaded('gd')) {
    error_log("GD library not available");
    return false;
}

// Get image info
$info = getimagesize($source);
if (!$info) {
    error_log("Invalid image file");
    return false;
}

// Allow JPEG/PNG/WebP
if (!in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'])) {
    error_log("Unsupported image type");
    return false;
}

// Load image
switch ($info['mime']) {
    case 'image/jpeg':
        $image = imagecreatefromjpeg($source);
        break;
    case 'image/png':
        $image = imagecreatefrompng($source);
        // Convert PNG to JPEG with white background
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $image = $bg;
        break;
    case 'image/webp':
        $image = imagecreatefromwebp($source);
        break;
    default:
        return false;
}

if (!$image) {
    error_log("Failed to load image");
    return false;
}

    // Resize settings - more aggressive for 5KB target
    $max_width = 100; // Reduced from 800
    $width = imagesx($image);
    $height = imagesy($image);

    // Resize if needed
    if ($width > $max_width) {
        $new_height = (int)($height * $max_width / $width);
        $resized = imagescale($image, $max_width, $new_height);
        imagedestroy($image);
        $image = $resized;
    }

    // Compression attempt with more aggressive settings
    $quality = 50; // Start lower
    $min_quality = 5; // Go much lower
    $success = false;

    do {
        // Save as JPEG
        imagejpeg($image, $destination, $quality);
        
        // Check size
        $current_size = filesize($destination) / 1024; // KB

        if ($current_size <= $target_size_kb) {
            $success = true;
            break;
        }

        $quality -= 5;
    } while ($quality >= $min_quality);

    // Clean up
    imagedestroy($image);

    return $success;
}



    
public function get_screenshots()
{
        $employee_id = $this->session->userdata('employee_id') ?? $this->input->get('employee_id');
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
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

    // Fetch screenshot records from DB where status is 1
    $this->db->select('screenshot_id, compressed_path, overall_activity_percent, created_at');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 1); // Only active screenshots
    $this->db->like('created_at', $date);
    $this->db->order_by('created_at', 'DESC');
    $query = $this->db->get('screenshots');
    $db_screenshots = $query->result_array();

    $screenshots = [];

    foreach ($db_screenshots as $row) {
        // Get the filename from compressed_path
        $filename = basename($row['compressed_path']);        
        // Check if the compressed file exists
        $compressed_full_path = FCPATH . $row['compressed_path'];        
        if (file_exists($compressed_full_path)) {
            // Use created_at directly without timezone conversion
            $formatted_time = date('H:i:s', strtotime($row['created_at']));

            $screenshots[] = [
                'id' => $row['screenshot_id'],
                'file_name' => $filename,
                'image_url' => base_url($row['compressed_path']),
                'created_at' => $formatted_time,
                'display_text' => $formatted_time,
                'percentage' => $row['overall_activity_percent'],
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

public function get_webcam_screenshots()
{
        $employee_id = $this->session->userdata('employee_id') ?? $this->input->get('employee_id');
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
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

    // Fetch records from database
    $this->db->select('webcam_id, compressed_path, created_at, status, user_id, employee_id');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 1);
    $this->db->where('DATE(created_at)', $date); // More precise than LIKE
    $this->db->order_by('created_at', 'DESC');
    $query = $this->db->get('webcam');
    $db_screenshots = $query->result_array();

    $screenshots = [];
    foreach ($db_screenshots as $row) {
        // Use the compressed_path from database
        $file_path = !empty($row['compressed_path']) ? $row['compressed_path'] : $row['file_path'];
        $filename = basename($file_path);
        
        $screenshots[] = [
            'id' => (string)$row['webcam_id'],
            'image_url' => base_url($file_path),
            'file_type' => pathinfo($filename, PATHINFO_EXTENSION),
            'status' => (int)$row['status'],
            'is_active' => 1,
            'display_text' => date('H:i:', strtotime($row['created_at'])),
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

        // Get the most recent screenshot of today with compressed path
    $this->db->select('screenshot_id, compressed_path, created_at');
    $this->db->where('employee_id', $employee_id);
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 1); // Only active screenshots
    $this->db->like('created_at', $date); // Filter by today's date
    $this->db->order_by('created_at', 'DESC'); // Latest first
    $this->db->limit(1);
    $query = $this->db->get('screenshots');
    $row = $query->row_array();

    if ($row) {
        // Get the filename from compressed_path
        $filename = basename($row['compressed_path']);
        // Check if the compressed file exists
        $compressed_full_path = FCPATH . $row['compressed_path'];
        if (file_exists($compressed_full_path)) {
            $filename = basename($row['compressed_path']);
            $formatted_time = date('H:i:s', strtotime($row['created_at']));

            $screenshot = [
                'id' => $row['screenshot_id'],
                'file_name' => $filename,
                'image_url' => base_url($row['compressed_path']),
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
        // Get user_id from session first
        $user_id = $this->session->userdata('id');
        
        // If not found in session, try to get from header
        if (empty($user_id)) {
            $user_id = $this->input->get_request_header('user_id', TRUE);
        }
    
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
    
        // 2. Get employees from the employees table matching the provided user_id
        $employees = $this->db
            ->select('id, name, email, country') // Select the employee details you need
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
