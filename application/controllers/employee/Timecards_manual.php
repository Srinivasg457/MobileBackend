<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timecards_manual extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /*
     * Create a manual timecard for an employee (by admin/org user)
     */
    public function create_timecard() {
        $user_id         = $this->session->userdata('id'); // From session
        $employee_id     = $this->input->post('employee_id');
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
        $approved_by = $this->session->userdata('id'); // From session

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
        $user_id         = $this->session->userdata('id'); // Must always come from session
        $employee_id     = $this->input->get('employee_id');
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
}
