<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TimeRequest extends Home_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    // Show form + requests
    public function index()
    {
        $data['page_title'] = "Time Request";

        // Initialize requests in session if not already
 
        $data['userData'] = $this->get_timecards_by_employee();
        $data['main_content'] = $this->load->view('admin/employee/time_request', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    public function get_timecards_by_employee()
    {
        $user_id         = $this->session->userdata('employee_org_id');
        $employee_id     = $this->session->userdata('employee_id');

        if ($employee_id && $user_id) {
            $this->db->where('employee_id', $employee_id);
            $this->db->order_by('manual_id', 'DESC'); // Change column if needed
            $query = $this->db->get('timecards_manual');

            // return an array directly
            return ($query->num_rows() > 0)
                ? $query->result_array()
                : [];
        } else {
            return ['error' => 'Employee ID not found in session'];
        }
    }
    public function request_delete($manual_id)
    {
        $this->admin_model->delete_request($manual_id, 'timecards_manual');

        echo json_encode([
            'st'      => 1
        ]);
    }
    public function submit()
    {
        if ($this->input->post()) {
            $user_id     = $this->session->userdata('employee_org_id');
            $employee_id = $this->session->userdata('employee_id');
            $type        = $this->input->post('type');
            $requested_date = $this->input->post('requested_date');
            $start_time  = $this->input->post('time_start');
            $end_time    = $this->input->post('time_end');
            $manual_id   = $this->input->post('manual_id');

            // ✅ 1. Check if the same request already exists (ignore the current record if editing)
            $exists = $this->db->where('employee_id', $employee_id)
                ->where('date_added', $requested_date)
                ->where('timestamp_start', $start_time)
                ->where('timestamp_end', $end_time)
                ->where('approved !=', -1) // optional: skip invalid/declined if you like
                ->where($manual_id ? "manual_id !=" : '1=1', $manual_id ?: null) // skip same row when updating
                ->get('timecards_manual')
                ->num_rows();

            if ($exists > 0) {
                // send flash message and stop
                $this->session->set_flashdata('error', 'A request for the same date and time already exists.');
                redirect('employee/TimeRequest');
                return;
            }

            // ✅ 2. Continue with normal verification
            $verification_status = $this->verify_time_request(
                $employee_id,
                $requested_date,
                $start_time,
                $end_time,
                $type
            );

            $data = [
                'type'            => $type,
                'timestamp_start' => $start_time,
                'timestamp_end'   => $end_time,
                'user_id'         => $user_id,
                'employee_id'     => $employee_id,
                'approved'        => 0,
                'verification_status' => $verification_status,
                'reason'          => $this->input->post('reason'),
                'date_added'      => $requested_date,
                'updated_at'      => get_user_datetime_only($user_id)
            ];

            if (!empty($manual_id)) {
                $this->db->where('manual_id', $manual_id)
                    ->where('approved', 0)
                    ->update('timecards_manual', $data);
                $this->session->set_flashdata('msg', 'Request Updated.');
            } else {
                $data['created_at'] = get_user_datetime_only($user_id);
                $this->db->insert('timecards_manual', $data);
                $this->session->set_flashdata('msg', 'Request Added.');
            }

            redirect('employee/TimeRequest');
        }
    }


    public function verify_time_request($employee_id, $requested_date, $start_time, $end_time, $type = '')
    {
        // Define start and end of the requested day
        $start_of_day = $requested_date . ' ' . $start_time; // e.g., 2025-09-26 11:32:00
        $end_of_day   = $requested_date . ' ' . $end_time;

        // 1️⃣ Check activity in Employee_Activity
        $this->db->select('SUM(total_mouse_movement) as mouse, SUM(total_keystrokes) as keystrokes');
        $this->db->from('Employee_Activity');
        $this->db->where('employee_id', $employee_id);
        // Truncate seconds using DATE_FORMAT to match only up to minutes
        $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') >=", date('Y-m-d H:i', strtotime($start_of_day)));
        $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') <=", date('Y-m-d H:i', strtotime($end_of_day)));

        $activity = $this->db->get()->row();

        $total_mouse = $activity->mouse ?? 0;
        $total_keys  = $activity->keystrokes ?? 0;


        // 2️⃣ Check time_logs for the employee
        $this->db->select('log_id');
        $this->db->from('time_logs');
        $this->db->where('employee_id', $employee_id);
        $this->db->where("DATE(log_date)", $requested_date);
        $log = $this->db->get()->row();

        if (!empty($log)) {
            if ($total_mouse > 0 || $total_keys > 0) {
                return 0; // Needs review
            } else {
                return 1; // Valid
            }
        }else{
            return 0;
        }

        // 3️⃣ No activity and no logs → invalid
        return -1; // Invalid
    }




    public function create_timecard()
    {
        $user_id         = $this->session->userdata('employee_org_id');
        $employee_id     = $this->session->userdata('employee_id');
        $timestamp_start = $this->input->post('timestamp_start');
        $timestamp_end   = $this->input->post('timestamp_end');
        $reason          = $this->input->post('reason');
        // Get the current date for the new 'date_added' column
        $date_added      = date('Y-m-d'); // Format: YYYY-MM-DD

        if (!$user_id || !$employee_id || !$timestamp_start || !$timestamp_end || !$reason) {
            echo "All fields are required: employee_id, timestamp_start, timestamp_end, reason.";
            return;
        }

        $data = array(
            'timestamp_start' => $timestamp_start,
            'timestamp_end'   => $timestamp_end,
            'user_id'         => $user_id,
            'employee_id'     => $employee_id,
            'reason'          => $reason,
            'approved'        => 0,
            'approved_by'     => NULL,
            'date_added'      => get_user_datetime_only($user_id) // Add the new column here
        );

        $this->db->insert('timecards_manual', $data);

        echo ($this->db->affected_rows() > 0)
            ? "Timecard created successfully!"
            : "Failed to create timecard.";
    }
}
