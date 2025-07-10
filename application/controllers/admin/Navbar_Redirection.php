<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Navbar_Redirection extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
    }

    // public function index()
    // {
    //     //cron_recurring_payments();
    //     $data = array();
    //     $data['is_employee_admin'] = true;
    //     $data['page_title'] = 'User Screenshots';
    //     $data['main_page'] = 'Analytics';
    //     $data['main_content'] = $this->load->view('admin/user_screenshot', $data, TRUE);
    //     $this->load->view('admin/index', $data);
    //     if (!is_subscribed()) {
    //         redirect('/admin/subscription');
    //     }
    // }
    /**
     * Attach organisation‑owner block to an employee session
     * using the *same* key names & values that CEOs receive.
     *
     * Runs once per session.  Safe to call at the top of index().
     */
    private function _attach_org_block_to_employee_session(): void
    {
        // 1. Skip non‑employees and people already switched to org_user
        if (
            !$this->session->userdata('is_employee') ||
            $this->session->userdata('user_type') === 'org_user'
        ) {
            return;
        }

        // 2. Look up the owner row (“parent” user in the users table)
        $ownerId    = (int) $this->session->userdata('employee_org_id');
        if (!$ownerId) {
            return;                                   // should never happen
        }

        $orgUser = $this->auth_model->get_user_by_id($ownerId);
        if (!$orgUser) {
            return;                                   // defensive guard
        }

        // 3. Build the org‑user block (same fields CEO gets)
        $orgBlock = [
            'id'        => $orgUser->id,
            'user_type' => 'org_user',
            'name'      => $this->session->userdata('employee_name'),
            'slug'      => $orgUser->slug  ?? '',
            'thumb'     => $orgUser->thumb ?? '',
            'email'     => $orgUser->email ?? $this->session->userdata('employee_email'),
            'role'      => 'user',          // treat as regular org user
            'parent'    => $orgUser->id,
            'logged_in' => true
        ];

        // 4. Merge into the session (keeps employee_* keys)
        $this->session->set_userdata($this->security->xss_clean($orgBlock));
    }
    private function _detach_org_block_from_employee_session(): void
    {
        if ($this->session->userdata('user_type') !== 'org_user') {
            return;                    // nothing to clean up
        }

        $this->session->unset_userdata([
            'id',
            'user_type',
            'name',
            'slug',
            'thumb',
            'email',
            'role',
            'parent',
            'logged_in'
        ]);
    }
    public function index()
    {
        $this->_attach_org_block_to_employee_session();

        $allowed = get_allowed_feature_ids();

        // Check features in navbar order
        if (in_array(6, $allowed)) {
            redirect('admin/view_screenshots');
        } elseif (in_array(7, $allowed)) {
            redirect('admin/webcam_screenshots');
        } elseif (in_array(1, $allowed)) {
            redirect('admin/activity_logs');
        } elseif (in_array(2, $allowed)) {
            redirect('admin/time_cards');
        } elseif (in_array(8, $allowed)) {
            redirect('admin/live_monitoring');
        } elseif (in_array(3, $allowed)) {
            redirect('admin/notification');
        } elseif (in_array(9, $allowed)) {
            redirect('employee/Timecards_manual/Time_Approval');
        } elseif (in_array(4, $allowed)) {
            redirect('admin/organization_settings');
        } elseif (in_array(5, $allowed)) {
            redirect('admin/employee_settings');
        } elseif (in_array(12, $allowed)) {
            redirect('admin/hrm/department');
        } elseif (in_array(10, $allowed)) {
            redirect('admin/hrm/employees');
        } elseif (in_array(11, $allowed)) {
            redirect('admin/roles_permissions');
        } else {
            // Fallback to dashboard if no permissions
            redirect('admin/dashboard');
        }
    }


   public function  employee_nav()
   {
        $this->_detach_org_block_from_employee_session();
        $data = array();
        $data['is_employee_admin'] = false;
        $data['page_title'] = 'Employee Dashboard';
        $data['details'] = $this->session->userdata('employee_id');
        $data['main_content'] = $this->load->view('admin/employee/dashboard', $data, TRUE);
        $this->load->view('admin/index', $data);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
    }
    }
