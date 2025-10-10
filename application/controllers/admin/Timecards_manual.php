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

    public function Time_Approval()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        require_feature(9);
        $data['page_title'] = 'Time_Approval';
        $data['can_edit'] = $this->auth_model->get_permission(9);
        $data['is_employee_admin'] = true;
        $data['is_request_page'] = true;
        $data['time_cards'] = $this->get_timecards2();
        $data['main_content'] = $this->load->view('admin/Time_  approval', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }

    public function Time_Approval_History()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
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
    // public function approve_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $start_time = null, $end_time = null)
    // {
    //     $employee_name = urldecode($employee_name);
    //     $employee_email = urldecode($employee_email);
    //     $reason = urldecode($reason);
    //     $start_time = urldecode($start_time);
    //     $end_time = urldecode($end_time);

    //     $approved_by = $this->session->userdata("id");

    //     if (!$manual_id || !$approved_by || !$employee_email || !$employee_id || !$start_time || !$end_time) {
    //         echo json_encode(['status' => 'error', 'message' => 'ID and session are required.']);
    //         return;
    //     }

    //     // Update approval in DB
    //     $this->db->where('manual_id', $manual_id);
    //     $this->db->where('user_id', $approved_by);
    //     $this->db->update('timecards_manual', [
    //         'approved'    => 1,
    //         'approved_by' => $approved_by
    //     ]);
    //     $this->add_active_time_from_request($manual_id);

    //     // Create time range string using start_time and end_time
    //     $time_range = substr($start_time, 0, 5) . " → " . substr($end_time, 0, 5);

    //     // Pass manual_id as first argument (correct order)
    //     $this->send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, 1, $time_range);

    //     echo json_encode(['st' => 1]);
    // }
    public function approve_timecard($manual_id = null, $employee_id = null, $employee_name = null, $employee_email = null, $reason = null, $start_time = null, $end_time = null, $requested_date = null)
    {
        $employee_id = urldecode($employee_id);
        $employee_name = urldecode($employee_name);
        $employee_email = urldecode($employee_email);
        $reason = urldecode($reason);
        $start_time = urldecode($start_time);
        $end_time = urldecode($end_time);
        $requested_date = urldecode($requested_date);

        $approved_by = $this->session->userdata("id");

        if (!$manual_id || !$approved_by || !$employee_email || !$employee_id || !$start_time || !$end_time) {
            echo json_encode(['status' => 'error', 'message' => 'ID and session are required.', 'ws_payload' => null]);
            return;
        }

        // Update approval in DB
        $this->db->where('manual_id', $manual_id);
        $this->db->where('user_id', $approved_by);
        $this->db->update('timecards_manual', [
            'approved'    => 1,
            'approved_by' => $approved_by
        ]);

        // Update active time and get the log data
        $timeLog = $this->add_active_time_from_request($manual_id, true); // return log data

        // Create time range string using start_time and end_time
        $time_range = substr($start_time, 0, 5) . " → " . substr($end_time, 0, 5);

        // Send alert email
        $this->send_alert_mail($manual_id, $employee_id, $employee_name, $employee_email, $reason, 1, $time_range);

        // Prepare JSON payload for WebSocket
        $payload = null;
        // ✅ Check if requested date is today
        $requested_date = $requested_date;
        $today = date('Y-m-d');

        if ($timeLog && $requested_date === $today) {
            $payload = [
                'type'       => 'timecard-approved',
                'employee_id' => $employee_id,
                'user_id'    => $approved_by,
                'data'       => [
                    'total_active_time' => $timeLog['total_active_time'],
                    'total_idle_time'   => $timeLog['total_idle_time'],
                    'total_time'        => $timeLog['total_time']
                ]
            ];
        }

        echo json_encode(['st' => 1, 'ws_payload' => $payload]);
    }
    /**
     * Add the approved manual request as active time into time_logs.
     * If a log exists for the same employee and day, update active/idle/total times.
     *
     * Rules:
     *  - If idle >= requested  → move requested from idle → active
     *  - If requested > idle   → move all idle to active and add the remainder to active + total
     *
     * @param int $manual_id  The ID of the approved manual request
     * @return bool
     */
    // private function add_active_time_from_request($manual_id)
    // {
    //     // Fetch approved request
    //     $req = $this->db
    //         ->where('manual_id', $manual_id)
    //         ->where('approved', 1)
    //         ->get('timecards_manual')
    //         ->row();

    //     if (!$req) {
    //         return false;
    //     }

    //     // Combine the request date with the stored start/end times
    //     // date_added is e.g. "2025-09-26"
    //     $log_date = date('Y-m-d', strtotime($req->date_added));
    //     $startDT  = new DateTime($log_date . ' ' . $req->timestamp_start);
    //     $endDT    = new DateTime($log_date . ' ' . $req->timestamp_end);

    //     $requested_seconds = $endDT->getTimestamp() - $startDT->getTimestamp();

    //     // Look for an existing daily log
    //     $log = $this->db
    //         ->where('employee_id', $req->employee_id)
    //         ->where('user_id', $req->user_id)
    //         ->where('log_date', $log_date)
    //         ->get('time_logs')
    //         ->row();

    //     // Helpers
    //     $toSec  = fn($t) => $t && strpos($t, ':') !== false
    //         ? array_sum(array_map(
    //             fn($v, $i) => $v * pow(60, 2 - $i),
    //             array_map('intval', explode(':', $t)),
    //             [0, 1, 2]
    //         ))
    //         : 0;
    //     $toTime = fn($s) => gmdate('H:i:s', max(0, $s));

    //     if ($log) {
    //         $idle   = $toSec($log->total_idle_time);
    //         $active = $toSec($log->total_active_time);
    //         $total  = $toSec($log->total_time);

    //         if ($idle >= $requested_seconds) {
    //             $idle   -= $requested_seconds;
    //             $active += $requested_seconds;
    //             // total stays the same
    //         } else {
    //             $active += $idle;
    //             $requested_seconds -= $idle;
    //             $idle = 0;
    //             $active += $requested_seconds;
    //             $total  += $requested_seconds; // grow total by the remainder
    //         }

    //         return $this->db
    //             ->where('log_id', $log->log_id)
    //             ->update('time_logs', [
    //                 'total_active_time' => $toTime($active),
    //                 'total_idle_time'   => $toTime($idle),
    //                 'total_time'        => $toTime($active + $idle), // keep consistent
    //                 'updated_at'        => date('Y-m-d H:i:s')
    //             ]);
    //     }

    //     // No existing log: insert new
    //     $duration = gmdate('H:i:s', $requested_seconds);

    //     return $this->db->insert('time_logs', [
    //         'session_id'        => null,
    //         'employee_id'       => $req->employee_id,
    //         'user_id'           => $req->user_id,
    //         'log_date'          => $log_date,
    //         'start_time'        => $startDT->format('Y-m-d H:i:s'),
    //         'end_time'          => $endDT->format('Y-m-d H:i:s'),
    //         'total_active_time' => $duration,
    //         'total_idle_time'   => '00:00:00',
    //         'total_time'        => $duration,
    //         'created_at'        => date('Y-m-d H:i:s'),
    //         'updated_at'        => date('Y-m-d H:i:s')
    //     ]);
    // }
    private function add_active_time_from_request($manual_id, $returnLog = false)
    {
        $req = $this->db
            ->where('manual_id', $manual_id)
            ->where('approved', 1)
            ->get('timecards_manual')
            ->row();

        if (!$req) return false;

        $log_date = date('Y-m-d', strtotime($req->date_added));
        $startDT  = new DateTime($log_date . ' ' . $req->timestamp_start);
        $endDT    = new DateTime($log_date . ' ' . $req->timestamp_end);

        $requested_seconds = $endDT->getTimestamp() - $startDT->getTimestamp();

        $log = $this->db
            ->where('employee_id', $req->employee_id)
            ->where('user_id', $req->user_id)
            ->where('log_date', $log_date)
            ->get('time_logs')
            ->row();

        $toSec  = fn($t) => $t && strpos($t, ':') !== false
            ? array_sum(array_map(fn($v, $i) => $v * pow(60, 2 - $i), array_map('intval', explode(':', $t)), [0, 1, 2]))
            : 0;
        $toTime = fn($s) => gmdate('H:i:s', max(0, $s));

        if ($log) {
            $idle   = $toSec($log->total_idle_time);
            $active = $toSec($log->total_active_time);
            $total  = $toSec($log->total_time);

            if ($idle >= $requested_seconds) {
                $idle   -= $requested_seconds;
                $active += $requested_seconds;
            } else {
                $active += $idle;
                $requested_seconds -= $idle;
                $idle = 0;
                $active += $requested_seconds;
                $total  += $requested_seconds;
            }

            $this->db
                ->where('log_id', $log->log_id)
                ->update('time_logs', [
                    'total_active_time' => $toTime($active),
                    'total_idle_time'   => $toTime($idle),
                    'total_time'        => $toTime($active + $idle),
                    'updated_at'        => date('Y-m-d H:i:s')
                ]);

            return $returnLog ? [
                'total_active_time' => $toTime($active),
                'total_idle_time'   => $toTime($idle),
                'total_time'        => $toTime($active + $idle)
            ] : false;
        }

        $duration = gmdate('H:i:s', $requested_seconds);

        $this->db->insert('time_logs', [
            'session_id'        => null,
            'employee_id'       => $req->employee_id,
            'user_id'           => $req->user_id,
            'log_date'          => $log_date,
            'start_time'        => $startDT->format('Y-m-d H:i:s'),
            'end_time'          => $endDT->format('Y-m-d H:i:s'),
            'total_active_time' => $duration,
            'total_idle_time'   => '00:00:00',
            'total_time'        => $duration,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        return $returnLog ? [
            'total_active_time' => $duration,
            'total_idle_time'   => '00:00:00',
            'total_time'        => $duration
        ] : false;
    }



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
        $this->db->select('t.manual_id, t.type, t.timestamp_start, t.timestamp_end, t.user_id, t.employee_id, t.date_added, t.reason, t.approved, t.approved_by, t.verification_status, t.created_at, e.name as employee_name, e.email, e.thumb');
        $this->db->from('timecards_manual t');
        $this->db->join('employees e', 't.employee_id = e.id', 'left');
        $this->db->where('t.user_id', $user_id);
        $this->db->order_by('t.manual_id', 'DESC'); // Change column if needed

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
        $this->db->select('t.manual_id, t.type, t.timestamp_start, t.timestamp_end, t.user_id, t.employee_id, t.date_added, t.reason, t.approved, t.approved_by, t.verification_status, t.created_at, e.name as employee_name, e.email, e.thumb');
        $this->db->from('timecards_manual t');
        $this->db->join('employees e', 't.employee_id = e.id', 'left');
        $this->db->where('t.user_id', $user_id);
        $this->db->where('t.approved', 0);

        // Only last 7 days based on created_at
        $this->db->where('t.created_at >=', date('Y-m-d 00:00:00', strtotime('-6 days')));
        $this->db->where('t.created_at <=', date('Y-m-d 23:59:59'));

        $this->db->order_by('t.manual_id', 'DESC');

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
