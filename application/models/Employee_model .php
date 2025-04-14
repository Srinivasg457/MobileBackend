<?php
class Employee_model extends CI_Model {

    // Step 1: Get employee by invitation token
    public function get_by_token($token) {
        return $this->db->get_where('employees', ['invitation_token' => $token])->row();
    }

    // Step 2: Update password for the employee
    public function update_password($id, $hashed_password) {
        $data = [
            'password' => $hashed_password,
            'is_registered' => 1, // Optional: mark as registered
            'invitation_token' => null // Invalidate token after use
        ];

        $this->db->where('id', $id);
        return $this->db->update('employees', $data);
    }
}
