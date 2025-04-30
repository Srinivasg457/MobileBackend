<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('has_permission')) {
    /**
     * Check if an employee has a given permission
     *
     * @param CI_DB $db          CodeIgniter DB instance
     * @param int $employee_id   ID of the employee
     * @param string $permission_name  Name of the permission
     * @param int $user_id       Organization user_id
     * @return bool
     */
    function has_permission($db, $employee_id, $permission_name, $user_id) {
        $db->select('erp.is_granted')
            ->from('employees e')
            ->join('employee_roles er', 'e.role_id = er.id')
            ->join('employee_role_permission erp', 'er.id = erp.role_id')
            ->join('employee_permissions ep', 'erp.permission_id = ep.id')
            ->where([
                'e.id' => $employee_id,
                'e.user_id' => $user_id,
                'ep.permission_name' => $permission_name,
                'ep.user_id' => $user_id
            ])
            ->limit(1);

        $result = $db->get()->row();
        return ($result && (int)$result->is_granted === 1);
    }
}
