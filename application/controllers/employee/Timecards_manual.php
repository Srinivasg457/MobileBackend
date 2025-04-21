<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timecards_manual extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();  // Load the database
    }

    /**
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

    /**
     * (Optional) Fetch manual timecards for an employee
     */
    public function get_timecards() {
        $employee_id = $this->input->get('employee_id');

        if (!$employee_id) {
            echo "Employee ID is required.";
            return;
        }

        $this->db->where('employee_id', $employee_id);
        $query = $this->db->get('timecards_manual');
        $results = $query->result();

        echo json_encode($results);
    }
}
