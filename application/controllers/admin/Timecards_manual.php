<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timecards_manual extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->database();
        $this->load->helper('url');
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
    // public function approve_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $timecards = null)
    // {
    //     $employee_name = urldecode($employee_name);
    //     $employee_email = urldecode($employee_email);
    //     $reason = urldecode($reason);
    //     $timecards = urldecode($timecards);

    //     $approved_by = $this->session->userdata("id");

    //     if (!$manual_id || !$approved_by) {
    //         echo json_encode(['status' => 'error', 'message' => 'Manual ID and session are required.']);
    //         return;
    //     }

    //     // Update approval in DB
    //     $this->db->where('manual_id', $manual_id);
    //     $this->db->where('user_id', $approved_by);
    //     $this->db->update('timecards_manual', [
    //         'approved'    => 1,
    //         'approved_by' => $approved_by
    //     ]);

    //     // Send approval email
    //     $time_range = substr($timecards['timestamp_start'], 0, 5) . " → " . substr($timecards['timestamp_end'], 0, 5);
    //     $this->send_alert_mail($employee_id, $employee_name, $employee_email, $reason, 1, $timecards); // For approval

    //     // $this->send_alert_mail($employee_id, $employee_name, $employee_email, $reason, 1);

    //     echo json_encode(['st' => 1]);
    // }
    // public function approve_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $start_time = "0", $end_time = "0")
    // {
    //     $employee_name = urldecode($employee_name);
    //     $employee_email = urldecode($employee_email);
    //     $reason = urldecode($reason);
    //     $start_time = urldecode($start_time);
    //     $end_time = urldecode($end_time);

    //     $approved_by = $this->session->userdata("id");

    //     if (!$manual_id || !$approved_by) {
    //         echo json_encode(['status' => 'error', 'message' => 'Manual ID and session are required.']);
    //         return;
    //     }

    //     // Update approval in DB
    //     $this->db->where('manual_id', $manual_id);
    //     $this->db->where('user_id', $approved_by);
    //     $this->db->update('timecards_manual', [
    //         'approved'    => 1,
    //         'approved_by' => $approved_by
    //     ]);

    //     // Fix: decode timecards string to array if it's JSON or build time range correctly
    //     // Assuming timecards is a string with start and end timestamps concatenated separated by comma or something
    //     // Since your link sends timestamp_start and timestamp_end as separate parameters, but your function takes only one timecards param,
    //     // adjust here accordingly:
    //     // If $timecards is like "start|end", explode it:
    //     $timecards_array = explode('|', $timecards);
    //     $time_range = isset($timecards_array[0], $timecards_array[1])
    //         ? substr($timecards_array[0], 0, 5) . " → " . substr($timecards_array[1], 0, 5)
    //         : $timecards; // fallback

    //     // Pass manual_id as first argument (fix mismatch)
    //     $this->send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, 1, $time_range);

    //     echo json_encode(['st' => 1]);
    // }
    public function approve_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $start_time = "0", $end_time = "0")
    {
        $employee_name = urldecode($employee_name);
        $employee_email = urldecode($employee_email);
        $reason = urldecode($reason);
        $start_time = urldecode($start_time);
        $end_time = urldecode($end_time);

        $approved_by = $this->session->userdata("id");

        if (!$manual_id || !$approved_by) {
            echo json_encode(['status' => 'error', 'message' => 'Manual ID and session are required.']);
            return;
        }

        // Update approval in DB
        $this->db->where('manual_id', $manual_id);
        $this->db->where('user_id', $approved_by);
        $this->db->update('timecards_manual', [
            'approved'    => 1,
            'approved_by' => $approved_by
        ]);

        // Create time range string using start_time and end_time
        $time_range = substr($start_time, 0, 5) . " → " . substr($end_time, 0, 5);

        // Pass manual_id as first argument (correct order)
        $this->send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, 1, $time_range);

        echo json_encode(['st' => 1]);
    }

    // public function decline_timecard($manual_id, $employee_id, $employee_name, $employee_email, $reason, $timecards)
    // {
    //     $employee_name = urldecode($employee_name);
    //     $employee_email = urldecode($employee_email);
    //     $reason = urldecode($reason);
    //     $timecards = urldecode($timecards);


    //     $declined_by = $this->input->post('declined_by') ?? $this->session->userdata('id');

    //     if (empty($manual_id) || empty($declined_by)) {
    //         echo json_encode([
    //             'status'  => 'error',
    //             'message' => 'Manual ID and Declined By are required.'
    //         ]);
    //         return;
    //     }

    //     // Check if timecard exists
    //     $timecard = $this->db->where('manual_id', $manual_id)
    //         ->get('timecards_manual')
    //         ->row_array();

    //     if (!$timecard) {
    //         echo json_encode([
    //             'status'  => 'error',
    //             'message' => 'No timecard found with the given Manual ID.'
    //         ]);
    //         return;
    //     }

    //     // Update to declined
    //     $this->db->where('manual_id', $manual_id)
    //         ->update('timecards_manual', [
    //             'approved'    => -1,
    //             'approved_by' => $declined_by
    //         ]);

    //     // Send declined email
    //     $time_range = substr($timecards['timestamp_start'], 0, 5) . " → " . substr($timecards['timestamp_end'], 0, 5);
    //     $this->send_alert_mail($employee_id, $employee_name, $employee_email, $reason, 0, $time_range); // For approval

    //     // $this->send_alert_mail($employee_id, $employee_name, $employee_email, $reason, 0);

    //     echo json_encode(['st' => 1]);
    // }
    // public function decline_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $start_time = "0", $end_time = "0")
    // {
    //     $employee_name = urldecode($employee_name);
    //     $employee_email = urldecode($employee_email);
    //     $reason = urldecode($reason);
    //     $start_time = urldecode($start_time);
    //     $end_time = urldecode($end_time);

    //     $declined_by = $this->input->post('declined_by') ?? $this->session->userdata('id');

    //     if (empty($manual_id) || empty($declined_by)) {
    //         echo json_encode([
    //             'status'  => 'error',
    //             'message' => 'Manual ID and Declined By are required.'
    //         ]);
    //         return;
    //     }

    //     // Check if timecard exists
    //     $timecard = $this->db->where('manual_id', $manual_id)
    //         ->get('timecards_manual')
    //         ->row_array();

    //     if (!$timecard) {
    //         echo json_encode([
    //             'status'  => 'error',
    //             'message' => 'No timecard found with the given Manual ID.'
    //         ]);
    //         return;
    //     }

    //     // Update to declined
    //     $this->db->where('manual_id', $manual_id)
    //         ->update('timecards_manual', [
    //             'approved'    => -1,
    //             'approved_by' => $declined_by
    //         ]);

    //     // Decode $timecards like approve_timecard
    //     $timecards_array = explode('|', $timecards);
    //     $time_range = isset($timecards_array[0], $timecards_array[1])
    //         ? substr($timecards_array[0], 0, 5) . " → " . substr($timecards_array[1], 0, 5)
    //         : $timecards; // fallback

    //     // Fix: Pass manual_id first
    //     $this->send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, 0, $time_range);

    //     echo json_encode(['st' => 1]);
    // }
    public function decline_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $start_time = "0", $end_time = "0")
    {
        $employee_name = urldecode($employee_name);
        $employee_email = urldecode($employee_email);
        $reason = urldecode($reason);
        $start_time = urldecode($start_time);
        $end_time = urldecode($end_time);

        $declined_by = $this->input->post('declined_by') ?? $this->session->userdata('id');

        if (empty($manual_id) || empty($declined_by)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Manual ID and Declined By are required.'
            ]);
            return;
        }

        // Check if timecard exists
        $timecard = $this->db->where('manual_id', $manual_id)
            ->get('timecards_manual')
            ->row_array();

        if (!$timecard) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No timecard found with the given Manual ID.'
            ]);
            return;
        }

        // Update to declined
        $this->db->where('manual_id', $manual_id)
            ->update('timecards_manual', [
                'approved'    => -1,
                'approved_by' => $declined_by
            ]);

        // Prepare time range string
        $time_range = substr($start_time, 0, 5) . " → " . substr($end_time, 0, 5);

        // Pass manual_id first (correct argument order)
        $this->send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, 0, $time_range);

        echo json_encode(['st' => 1]);
    }


    // public function send_alert_mail($employee_id, $employee_name, $employee_email, $reason, $approved)
    // {
    //     if (empty($employee_email)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode([
    //                 'status' => 'error',
    //                 'message' => 'Employee email is missing.'
    //             ]));
    //     }

    //     // Email subject and body
    //     $subject = '';
    //     $msg = "<p>Hi <strong>$employee_name</strong>,</p>";

    //     if ((int)$approved === 1) {
    //         // Approval email
    //         $subject .= "Approval Notification: Manual Time Request";
    //         $msg .= "<p>Your manual time request has been <strong style=\"color:green\">approved</strong> by the admin.</p>";
    //         $msg .= "<p><strong>Reason provided:</strong></p>";
    //         $msg .= "<blockquote style=\"font-style: italic; color: #555;\">$reason</blockquote>";
    //         $msg .= "<p>This approval confirms that your request aligns with project goals and scheduling availability.</p>";
    //     } else {
    //         // Decline email
    //         $subject .= "Request Declined: Manual Time Request";
    //         $msg .= "<p>Your manual time request has been <strong style=\"color:red\">declined</strong> by the admin.</p>";
    //         $msg .= "<p><strong>Reason provided:</strong></p>";
    //         $msg .= "<blockquote style=\"font-style: italic; color: #555;\">$reason</blockquote>";
    //         $msg .= "<p>If you believe this was an error or need further clarification, please reach out to your manager or admin.</p>";
    //     }

    //     $msg .= "<br><p>Regards,<br><strong>Admin Team</strong></p>";

    //     // Send email
    //     $this->email_model->send_email($employee_email, $subject, $msg);
    // }

    // public function send_alert_mail($employee_id, $employee_name, $employee_email, $reason, $approved, $time_range = null)
    // {
    //     if (empty($employee_email)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode([
    //                 'status' => 'error',
    //                 'message' => 'Employee email is missing.'
    //             ]));
    //     }


    //     $subject = '';
    //     $msg = "<p>Hi <strong>$employee_name</strong>,</p>";

    //     if ((int)$approved === 1) {
    //         $subject .= "Approval Notification: Manual Time Request";
    //         $msg .= "<p>Your manual time request has been <strong style=\"color:green\">approved</strong> by the admin.</p>";
    //     } else {
    //         $subject .= "Request Declined: Manual Time Request";
    //         $msg .= "<p>Your manual time request has been <strong style=\"color:red\">declined</strong> by the admin.</p>";
    //     }

    //     // Time & reason in table format
    //     $msg .= "
    //     <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; margin-top: 10px;'>
    //         <thead style='background-color: #f2f2f2;'>
    //             <tr>
    //                 <th style='text-align:left;'>Time Range</th>
    //                 <th style='text-align:left;'>Reason</th>
    //             </tr>
    //         </thead>
    //         <tbody>
    //             <tr>
    //                 <td>$time_range</td>
    //                 <td>$reason</td>
    //             </tr>
    //         </tbody>
    //     </table>
    // ";

    //     if ((int)$approved === 1) {
    //         $msg .= "<p>This approval confirms that your request aligns with project goals and scheduling availability.</p>";
    //     } else {
    //         $msg .= "<p>If you believe this was an error or need further clarification, please reach out to your manager or admin.</p>";
    //     }

    //     $msg .= "<br><p>Regards,<br><strong>Admin Team</strong></p>";

    //     // Send email
    //     $this->email_model->send_email($employee_email, $subject, $msg);
    // }
    // public function send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, $approved, $timecards)
    // {
    //     if (empty($employee_email)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode([
    //                 'status' => 'error',
    //                 'message' => 'Employee email is missing.'
    //             ]));
    //     }

    //     $timecards = $this->db->where('manual_id')
    //         ->get('timecards_manual')
    //         ->row_array();

    //     if (!$timecards) {
    //         echo json_encode([
    //             'status'  => 'error',
    //             'message' => 'No timecard found with the given Manual ID.'
    //         ]);
    //         return;
    //     }
    //     $subject = '';
    //     $msg = "<p>Hi <strong>$employee_name</strong>,</p>";

    //     if ((int)$approved === 1) {
    //         $subject = "Approval Notification: Manual Time Request";
    //         $msg .= "<p>Your manual time request has been <strong style=\"color:green\">approved</strong> by the admin.</p>";
    //     } else {
    //         $subject = "Request Declined: Manual Time Request";
    //         $msg .= "<p>Your manual time request has been <strong style=\"color:red\">declined</strong> by the admin.</p>";
    //     }

    //     // Time & reason in table format
    //     $msg .= "
    //     <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; margin-top: 10px;'>
    //         <thead style='background-color: #f2f2f2;'>
    //             <tr>
    //                 <th style='text-align:left;'>Time Range</th>
    //                 <th style='text-align:left;'>Reason</th>
    //             </tr>
    //         </thead>
    //         <tbody>
    //             <tr>
    //                 <td>$timecards</td>
    //                 <td>$reason</td>
    //             </tr>
    //         </tbody>
    //     </table>
    // ";

    //     if ((int)$approved === 1) {
    //         $msg .= "<p>This approval confirms that your request aligns with project goals and scheduling availability.</p>";
    //     } else {
    //         $msg .= "<p>If you believe this was an error or need further clarification, please reach out to your manager or admin.</p>";
    //     }

    //     $msg .= "<br><p>Regards,<br><strong>Admin Team</strong></p>";

    //     //Send email
    //     $this->email_model->send_email($employee_email, $subject, $msg);
    // }

    public function send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, $approved, $time_range)
    {
        if (empty($employee_email)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Employee email is missing.'
                ]));
        }

        // Fix missing manual_id condition here
        $timecard = $this->db->where('manual_id', $manual_id)
            ->get('timecards_manual')
            ->row_array();

        if (!$timecard) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No timecard found with the given Manual ID.'
            ]);
            return;
        }

        $subject = '';
        $msg = "<p>Hi <strong>$employee_name</strong>,</p>";

        if ((int)$approved === 1) {
            $subject = "Approval Notification: Manual Time Request";
            $msg .= "<p>Your manual time request has been <strong style=\"color:green\">approved</strong> by the admin.</p>";
        } else {
            $subject = "Request Declined: Manual Time Request";
            $msg .= "<p>Your manual time request has been <strong style=\"color:red\">declined</strong> by the admin.</p>";
        }

        // Use $time_range instead of $timecards to avoid confusion
        $msg .= "
    <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; margin-top: 10px;'>
        <thead style='background-color: #f2f2f2;'>
            <tr>
                <th style='text-align:left;'>Time Range</th>
                <th style='text-align:left;'>Reason</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$time_range</td>
                <td>$reason</td>
            </tr>
        </tbody>
    </table>
    ";

        if ((int)$approved === 1) {
            $msg .= "<p>This approval confirms that your request aligns with project goals and scheduling availability.</p>";
        } else {
            $msg .= "<p>If you believe this was an error or need further clarification, please reach out to your manager or admin.</p>";
        }

        $msg .= "<br><p>Regards,<br><strong>Admin Team</strong></p>";

        // Send email (ensure email_model is loaded properly)
        $this->email_model->send_email($employee_email, $subject, $msg);
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
