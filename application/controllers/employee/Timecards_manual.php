<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timecards_manual extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();  // Load the database
    }

    /*
     * Create a manual timecard for an employee (by admin/org user)
     */
    public function create_timecard() {
        $employee_id     = $this->input->get('employee_id');               // From GET param
        $user_id         = $this->session->userdata('id');                 // Logged-in admin/org
        $timestamp_start = $this->input->post('timestamp_start');
        $timestamp_end   = $this->input->post('timestamp_end');
        $reason          = $this->input->post('reason');

        // Validate required fields
        if (!$employee_id || !$user_id || !$timestamp_start || !$timestamp_end || !$reason) {
            echo "All fields are required: employee_id, timestamp_start, timestamp_end, reason.";
            return;
        }

        // Prepare data
        $data = array(
            'timestamp_start' => $timestamp_start,
            'timestamp_end'   => $timestamp_end,
            'user_id'         => $user_id,
            'employee_id'     => $employee_id,
            'reason'          => $reason,
            'approved'        => 0, // Pending approval
            'approved_by'     => NULL
        );

        // Insert
        $this->db->insert('timecards_manual', $data);

        if ($this->db->affected_rows() > 0) {
            echo "Timecard created successfully!";
        } else {
            echo "Failed to create timecard.";
        }
    }

    /**
     * Approve a manual timecard
     */
    public function approve_timecard() {
        $manual_id   = $this->input->post('manual_id');
        $approved_by = $this->session->userdata('id'); // Admin approving

        if (!$manual_id || !$approved_by) {
            echo "Manual ID is required.";
            return;
        }

        $data = array(
            'approved'    => 1,
            'approved_by' => $approved_by
        );

        $this->db->where('manual_id', $manual_id);
        $this->db->update('timecards_manual', $data);

        if ($this->db->affected_rows() > 0) {
            echo "Timecard approved successfully!";
        } else {
            echo "Failed to approve timecard.";
        }
    }

    public function get_timecards() {
        $employee_id     = $this->input->get('employee_id');
        $approval_status = $this->input->get('approved'); // 'approved' or 'unapproved'
        $user_id         = $this->session->userdata('id'); // Logged-in org/admin user
        $mode            = $this->input->get('mode'); // 'update' or null
        $manual_id       = $this->input->get('manual_id'); // required if mode is update
    
        if (!$user_id) {
            echo "Unauthorized access: user not logged in.";
            return;
        }
    
        // --- Handle update ---
        if ($mode === 'update') {
            if (!$manual_id || !$approval_status) {
                echo "manual_id and approved status required for update.";
                return;
            }
    
            $approved_value = ($approval_status === 'approved') ? 1 : 0;
    
            $this->db->where('manual_id', $manual_id);
            $this->db->where('user_id', $user_id); // Extra check for security
            $this->db->update('timecards_manual', [
                'approved'    => $approved_value,
                'approved_by' => $user_id
            ]);
    
            if ($this->db->affected_rows() > 0) {
                echo "Timecard status updated successfully.";
            } else {
                echo "No update performed (maybe already updated or invalid ID).";
            }
            return;
        }
    
        // --- Handle fetch ---
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
        $results = $query->result();
    
        echo json_encode($results);
    }
    
    
}
