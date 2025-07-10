<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timecards_manual extends Home_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();

    }
     public function index(){
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        $data = array();
        $data['employee_id'] = $this->session->userdata('employee_id');
        $data['employee_org_id'] = $this->session->userdata('employee_org_id');
        $data['page_title'] = 'Activity Log';
        $data['main_content'] = $this->load->view('admin/employee/activity_log', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }

    /*
     * Create a manual timecard for an employee (by admin/org user)
     */
    public function create_timecard() {
        $user_id     = $this->session->userdata('employee_org_id');
        $employee_id = $this->session->userdata('employee_id');
        $timestamp_start = $this->input->post('timestamp_start');
        $timestamp_end   = $this->input->post('timestamp_end');
        $reason          = $this->input->post('reason');

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
            'approved_by'     => NULL
        );

        $this->db->insert('timecards_manual', $data);

        echo ($this->db->affected_rows() > 0) 
            ? "Timecard created successfully!" 
            : "Failed to create timecard.";
    }

    /**
     * Approve a manual timecard
     */
    public function approve_timecard() {
        $manual_id   = $this->input->post('manual_id');
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

        echo ($this->db->affected_rows() > 0)
            ? "Timecard approved successfully!"
            : "Failed to approve timecard.";
    }

    /**
     * Get or update timecards manually
     */
    public function get_timecards() {
        $user_id     = $this->input->get('employee_org_id');
        $employee_id = $this->input->get('employee_id');
        $approval_status = $this->input->get('approved'); // approved/unapproved
        $mode            = $this->input->get('mode'); // 'update' or null
        $manual_id       = $this->input->get('manual_id');

        if (!$user_id) {
            echo "Unauthorized access: user not logged in.";
            return;
        }

        // ---- UPDATE ----
        if ($mode === 'update') {
            if (!$manual_id || !$approval_status) {
                echo "manual_id and approval_status required for update mode.";
                return;
            }

            $approved_value = ($approval_status === 'approved') ? 1 : 0;

            $this->db->where('manual_id', $manual_id);
            $this->db->where('user_id', $user_id); // Ensure security
            $this->db->update('timecards_manual', [
                'approved'    => $approved_value,
                'approved_by' => $user_id
            ]);

            echo ($this->db->affected_rows() > 0)
                ? "Timecard status updated successfully."
                : "No update performed.";
            return;
        }

        // ---- FETCH ----
        $this->db->where('user_id', $user_id);

        if ($employee_id) {
            $this->db->where('employee_id', $employee_id);
        }

        if ($approval_status === 'approved') {
            $this->db->where('approved', 1);
        } elseif ($approval_status === 'unapproved') {
            $this->db->where('approved', 0);
        }

        $query = $this->db->get('timecards_manual');
        echo json_encode($query->result());
    }

     public function Time_Approval(){
        require_feature(9);
        $data['page_title'] = 'Time_Approval';
        $data['can_edit'] = $this->auth_model->get_permission(9);
        $data['is_employee_admin'] = true;
        $data['main_content'] = $this->load->view('admin/Time_approval', $data, TRUE);
    $this->load->view('admin/index', $data);
    if (!is_subscribed()) {
        redirect('/admin/subscription/upgrade_plan');
    }
    }
       public function get_timecards_by_employee() {
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
