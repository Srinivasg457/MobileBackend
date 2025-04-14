<?php
class Hrm_model extends CI_Model {
    
    function get_employees()
    {
        $this->db->select('e.*,d.name as department_name , c.name as country_name');
        $this->db->from('employees as e');
        $this->db->where('e.business_id', $this->business->uid);
        $this->db->where('e.user_id', $this->session->userdata('id'));
        $this->db->order_by('e.id','DESC');
        $this->db->join('departments as d','e.department_id=d.id','LEFT');
        $this->db->join('country as c','e.country=c.id','LEFT');
        $query = $this->db->get();
        $query = $query->result();  
        return $query;
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