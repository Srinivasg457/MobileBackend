<?php
class Hrm_model extends CI_Model {

    // function get_employees()
    // {
    //     $this->db->select('e.*,d.name as department_name , c.name as country_name');
    //     $this->db->from('employees as e');
    //     $this->db->where('e.business_id', $this->business->uid);
    //     $this->db->where('e.user_id', $this->session->userdata('id'));
    //     $this->db->order_by('e.id','DESC');
    //     $this->db->join('departments as d','e.department_id=d.id','LEFT');
    //     $this->db->join('country as c','e.country=c.id','LEFT');
    //     $query = $this->db->get();
    //     $query = $query->result();  
    //     return $query;
    // }
    // public function get_employees()
    // {
    //     $user_id = $this->session->userdata('id') ?: $this->session->userdata('employee_org_id');
    //     $this->db->select('e.*, d.name as department_name, c.name as country_name, r.role_name as role_name');
    //     $this->db->from('employees as e');
    //     $this->db->where('e.business_id', $this->business->uid);
    //     $this->db->where('e.user_id', $user_id);
    //     $this->db->order_by('e.id', 'DESC');

    //     $this->db->join('departments as d', 'e.department_id = d.id', 'LEFT');
    //     $this->db->join('country as c', 'e.country = c.id', 'LEFT');
    //     $this->db->join('employee_roles as r', 'e.role_id = r.id', 'LEFT'); // ✅ join for role name

    //     $query = $this->db->get();
    //     return $query->result();
    // }
    /**
     * Return all employees that belong to the business owned by the
     * user currently in session (admin  ➜  id,  employee CEO ➜ employee_org_id).
     *
     * @return array  List of employee rows (empty array if business not found)
     */
    public function get_employees()
    {
        // 1️⃣  Figure out who’s logged in
        $user_id = $this->session->userdata('id') ?: $this->session->userdata('employee_org_id');
        if (!$user_id) {
            return [];                              // no user in session
        }

        // 2️⃣  Look up the business UID tied to that user
        $business = $this->db->select('uid')
            ->from('business')    // ← adjust table name if needed
            ->where('user_id', $user_id)
            ->limit(1)
            ->get()
            ->row();

        if (!$business) {
            return [];                              // user has no business
        }

        $business_uid = $business->uid;

        // 3️⃣  Fetch employees for that business
        $this->db->select('
        e.*,
        d.name AS department_name,
        c.name AS country_name,
        r.role_name
    ');
        $this->db->from('employees AS e');
        $this->db->where('e.business_id', $business_uid);
        $this->db->order_by('e.id', 'DESC');

        // joins for related names
        $this->db->join('departments    AS d', 'e.department_id = d.id', 'LEFT');
        $this->db->join('country        AS c', 'e.country       = c.id', 'LEFT');
        $this->db->join('employee_roles AS r', 'e.role_id       = r.id', 'LEFT');

        return $this->db->get()->result();
    }

    function get_attendances()
    {
        $this->db->select('a.* , d.name as department_name , e.name as employee_name');
        $this->db->from('attendence as a');
        $this->db->where('a.business_id', $this->business->uid);
        $this->db->where('a.user_id', $this->session->userdata('id'));
        $this->db->order_by('a.id','DESC');
        $this->db->join('employees as e','a.employee_id=e.id','LEFT');
        $this->db->join('departments as d','e.department_id=d.id','LEFT');
        $query = $this->db->get();
        $query = $query->result();  
        return $query;
    }
    
    function get_salaries()
    {
        $this->db->select('s.* , d.name as department_name , e.name as employee_name');
        $this->db->from('salary as s');
        $this->db->where('s.business_id', $this->business->uid);
        $this->db->where('s.user_id', $this->session->userdata('id'));
        $this->db->order_by('s.id','DESC');
        $this->db->join('employees as e','s.employee_id=e.id','LEFT');
        $this->db->join('departments as d','e.department_id=d.id','LEFT');
        $query = $this->db->get();
        $query = $query->result();  
        return $query;
    }
    
    function get_countries()
    {
        $this->db->select('*');
        $this->db->from('country');
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        $query = $query->result();  
        return $query;
    }
    

}