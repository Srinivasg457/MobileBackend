<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timecards_manual extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    // public function index()
    // {
    //     // if (!$this->session->userdata('logged_in')) {
    //     //     redirect('login');
    //     // }
    //     $data = array();
    //     $data['employee_id'] = $this->session->userdata('employee_id');
    //     $data['employee_org_id'] = $this->session->userdata('employee_org_id');
    //     $data['page_title'] = 'Activity Log';
    //     $data['main_content'] = $this->load->view('admin/employee/activity_log', $data, TRUE);
    //     $data['time_cards'] = "asd";
    //     $this->load->view('admin/index', $data);
    //     if (!is_subscribed()) {
    //         redirect('/admin/subscription/upgrade_plan');
    //     }
    // }
    public function Time_Approval()
    {
        require_feature(9);
        $data['page_title'] = 'Time_Approval';
        $data['can_edit'] = $this->auth_model->get_permission(9);
        $data['is_employee_admin'] = true;
        $data['is_request_page'] = true;
        $data['time_cards'] = $this->get_timecards2();
        $data['main_content'] = $this->load->view('admin/Time_approval', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }

    public function Time_Approval_History()
    {
        require_feature(9);
        $data['page_title'] = 'Time_Approval';
        $data['can_edit'] = $this->auth_model->get_permission(9);
        $data['is_employee_admin'] = true;
        $data['is_request_page'] = false;
        $data['time_cards'] = $this->get_timecards();
        $data['main_content'] = $this->load->view('admin/Time_approval', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }

    /*
     * Create a manual timecard for an employee (by admin/org user)
     */
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
    /**
     * Approve a manual timecard
     */
    // public function approve_timecard()
    // {
    //     $manual_id   = $this->input->post('manual_id');
    //     $approved_by = $this->session->userdata("id"); // From session

    //     if (!$manual_id || !$approved_by) {
    //         echo "Manual ID and session are required.";
    //         return;
    //     }

    //     $this->db->where('manual_id', $manual_id);
    //     $this->db->where('user_id', $approved_by); // Ensures admin is updating only their own records
    //     $this->db->update('timecards_manual', [
    //         'approved'    => 1,
    //         'approved_by' => $approved_by
    //     ]);

    //     echo ($this->db->affected_rows() > 0)
    //         ? "Timecard approved successfully!"
    //         : "Failed to approve timecard.";
    // }

    public function approve_timecard($manual_id)
    {

        $approved_by = $this->session->userdata("id"); // From session

        if (!$manual_id || !$approved_by) {
            echo "Manual ID and session are required.";
            return;
        }

        $this->db->where('manual_id', $manual_id);
        $this->db->where('user_id', $approved_by); // Ensures admin is updating only their own records
        $this->db->update('timecards_manual', [
            'approved'    => 1,
            'approved_by' => $approved_by
        ]);
        echo json_encode([
            'st'      => 1
        ]);
    }
    //decline_timecard

    public function decline_timecard($manual_id)
    {
        // 1. Get inputs from POST
        $declined_by = $this->input->post('declined_by') ?? $this->session->userdata('id');

        // 2. Validate input
        if (empty($manual_id) || empty($declined_by)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Manual ID and Declined By are required'
            ]);
            return;
        }

        // 3. Check if timecard exists
        $timecard = $this->db->where('manual_id', $manual_id)
            ->get('timecards_manual')
            ->row_array();

        if (!$timecard) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No timecard found with the given Manual ID'
            ]);
            return;
        }

        // 4. Update the timecard to declined
        $this->db->where('manual_id', $manual_id)
            ->update('timecards_manual', [
                'approved'    => -1,
                'approved_by' => $declined_by
            ]);

        // 5. Return success response
        echo json_encode([
            'st'      => 1
        ]);
    }


    /**
     * Get or update timecards manually
     */
    public function get_timecards()
    {
        // Get user_id from session
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
        if (!$user_id) {
            return []; // Unauthorized
        }

        // Join with employee details
        $this->db->select('t.manual_id, t.is_meeting, t.timestamp_start, t.timestamp_end, t.user_id, t.employee_id, t.date_added, t.reason, t.approved, t.approved_by, e.name as employee_name, e.email, e.thumb');
        $this->db->from('timecards_manual t');
        $this->db->join('employees e', 't.employee_id = e.id', 'left');
        $this->db->where('t.user_id', $user_id);
        // $this->db->where('t.approved', );

        $query = $this->db->get();
        return $query->result_array(); // Return as normal PHP array
    }
    public function get_timecards2()
    {
        // Get user_id from session
        $user_id = $this->session->userdata('employee_org_id') ?? $this->session->userdata('id');
        if (!$user_id) {
            return []; // Unauthorized
        }

        // Join with employee details
        $this->db->select('t.manual_id, t.is_meeting, t.timestamp_start, t.timestamp_end, t.user_id, t.employee_id, t.date_added, t.reason, t.approved, t.approved_by, e.name as employee_name, e.email, e.thumb');
        $this->db->from('timecards_manual t');
        $this->db->join('employees e', 't.employee_id = e.id', 'left');
        $this->db->where('t.user_id', $user_id);
        $this->db->where('t.approved', 0);

        $query = $this->db->get();
        return $query->result_array(); // Return as normal PHP array
    }

    public function get_timecards_by_employee()
    {
        $employee_id = $this->session->userdata('employee_id');

        if ($employee_id) {
            $this->db->where('employee_id', $employee_id);
            $this->db->order_by('manual_id', 'DESC'); // Change 'id' to your preferred column
            $query = $this->db->get('timecards_manual');
            echo json_encode($query->result());
        } else {
            echo json_encode(['error' => 'Employee ID not found in session']);
        }
    }
}
