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
        if (!$this->session->has_userdata('requests')) {
            $this->session->set_userdata('requests', [
                (object)[
                    'type' => 'Meeting',
                    'requested_date' => '2025-09-20',
                    'time_start' => '10:00',
                    'time_end' => '11:00',
                    'reason' => 'Project discussion',
                    'status' => 'Pending',
                    'admin_note' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'attachment' => null
                ],
                (object)[
                    'type' => 'Manual Time',
                    'requested_date' => '2025-09-18',
                    'time_start' => '14:00',
                    'time_end' => '15:30',
                    'reason' => 'Client follow-up',
                    'status' => 'Approved',
                    'admin_note' => 'Okay',
                    'created_at' => date('Y-m-d H:i:s'),
                    'attachment' => null
                ]
            ]);
        }

        // Pass session requests to view
        $data['requests'] = $this->session->userdata('requests');

        $data['main_content'] = $this->load->view('admin/employee/time_request', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    // Handle form submit and add new request
    public function submit()
    {
        if ($this->input->post()) {
            $new_request = (object)[
                'type' => $this->input->post('type'),
                'requested_date' => $this->input->post('requested_date'),
                'time_start' => $this->input->post('time_start'),
                'time_end' => $this->input->post('time_end'),
                'reason' => $this->input->post('reason'),
                'status' => 'Approved',
                'admin_note' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'attachment' => null
            ];

            // Append to session
            $requests = $this->session->userdata('requests');
            $requests[] = $new_request;
            $this->session->set_userdata('requests', $requests);

            $this->session->set_flashdata('success', 'Request submitted successfully!');
        }

        redirect('employee/TimeRequest');
    }
}
