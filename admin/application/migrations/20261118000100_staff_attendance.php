<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Staff attendance & leave (staff = pay_employee, the payroll staff list)
 *   staff_leave_type    CL, SL, EL ... (paid / unpaid, yearly quota)
 *   staff_holiday       school holidays (weekly offs come from settings)
 *   staff_attendance    one row per staff per day: P present, A absent, HD half day, L leave, HL half-day leave, OD on duty
 *   staff_leave_app     leave applications (by the office or by the staff member from My Attendance)
 *   staff_leave_alloc   leave entitlement of a staff member for a leave year (overrides the type's quota)
 *   staff_att_setting   week_off, sat_rule, year_start, late_after
 */
class Migration_Staff_attendance extends CI_Migration
{
    public function up()
    {
        $q = function ($sql) { $this->db->query($sql); };

        $q("CREATE TABLE IF NOT EXISTS staff_leave_type (
            sltId INT NOT NULL AUTO_INCREMENT,
            sltName VARCHAR(60) NOT NULL,
            sltCode VARCHAR(6) NOT NULL,
            sltPaid TINYINT(1) NOT NULL DEFAULT 1,
            sltQuota DECIMAL(5,1) NULL,
            sltSelf TINYINT(1) NOT NULL DEFAULT 1,
            sltOrder INT NOT NULL DEFAULT 0,
            sltActive TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (sltId), UNIQUE KEY uq_slt_name (sltName), UNIQUE KEY uq_slt_code (sltCode)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS staff_holiday (
            shId INT NOT NULL AUTO_INCREMENT,
            shDate DATE NOT NULL,
            shName VARCHAR(100) NOT NULL,
            PRIMARY KEY (shId), UNIQUE KEY uq_sh_date (shDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS staff_attendance (
            satId INT NOT NULL AUTO_INCREMENT,
            satPeId INT NOT NULL,
            satDate DATE NOT NULL,
            satStatus VARCHAR(2) NOT NULL DEFAULT 'P',
            satSltId INT NULL,
            satLaId INT NULL,
            satIn TIME NULL,
            satOut TIME NULL,
            satRemark VARCHAR(100) NULL,
            satUser INT NULL,
            satTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (satId), UNIQUE KEY uq_sat_staff_day (satPeId, satDate), KEY ix_sat_date (satDate), KEY ix_sat_app (satLaId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS staff_leave_app (
            laId INT NOT NULL AUTO_INCREMENT,
            laPeId INT NOT NULL,
            laSltId INT NOT NULL,
            laFrom DATE NOT NULL,
            laTo DATE NOT NULL,
            laHalf TINYINT(1) NOT NULL DEFAULT 0,
            laDays DECIMAL(5,1) NOT NULL DEFAULT 0,
            laReason VARCHAR(255) NULL,
            laStatus VARCHAR(10) NOT NULL DEFAULT 'PENDING',
            laSelf TINYINT(1) NOT NULL DEFAULT 0,
            laUser INT NULL,
            laDecidedBy INT NULL,
            laDecidedAt DATETIME NULL,
            laNote VARCHAR(255) NULL,
            laTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (laId), KEY ix_la_staff (laPeId, laFrom), KEY ix_la_status (laStatus)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS staff_leave_alloc (
            slaPeId INT NOT NULL,
            slaSltId INT NOT NULL,
            slaYear DATE NOT NULL,
            slaDays DECIMAL(5,1) NOT NULL,
            PRIMARY KEY (slaPeId, slaSltId, slaYear)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS staff_att_setting (
            ssKey VARCHAR(30) NOT NULL,
            ssValue VARCHAR(100) NULL,
            PRIMARY KEY (ssKey)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // name, code, paid, yearly quota (null = no limit), staff can apply themselves
        $types = array(
            array('Casual Leave', 'CL', 1, 12, 1),
            array('Sick Leave', 'SL', 1, 10, 1),
            array('Earned Leave', 'EL', 1, 15, 1),
            array('Maternity Leave', 'MTL', 1, 182, 0),
            array('Loss of Pay', 'LOP', 0, null, 1),
        );
        foreach ($types as $i => $t) {
            if ($this->db->where('sltCode', $t[1])->count_all_results('staff_leave_type')) continue;
            $this->db->insert('staff_leave_type', array('sltName' => $t[0], 'sltCode' => $t[1], 'sltPaid' => $t[2], 'sltQuota' => $t[3],
                                                        'sltSelf' => $t[4], 'sltOrder' => $i + 1, 'sltActive' => 1));
        }
        foreach (array('week_off' => '0', 'sat_rule' => 'none', 'year_start' => '4', 'late_after' => '09:15') as $k => $v) {
            if (!$this->db->where('ssKey', $k)->count_all_results('staff_att_setting')) $this->db->insert('staff_att_setting', array('ssKey' => $k, 'ssValue' => $v));
        }

        /* ---------- menus ---------- */
        $parent = $this->_menu('STAFF_ATTENDANCE', 'Staff Attendance', null, null, 15);
        $this->_menu('STAFF_ATT_MARK', 'Mark Attendance', $parent, '/staff_attendance', 1);
        $this->_menu('STAFF_LEAVE', 'Leave Requests', $parent, '/staff_leave', 2);
        $this->_menu('STAFF_LEAVE_BAL', 'Leave Balances', $parent, '/staff_leave_balance', 3);
        $this->_menu('STAFF_ATT_SETTINGS', 'Holidays & Leave Types', $parent, '/staff_att_settings', 4);
        $mine = $this->_menu('MY_ATTENDANCE', 'My Attendance & Leave', $parent, '/my_attendance', 5);
        $reports = $this->db->where('menu_name', 'REPORTS')->get('menus')->row();
        if ($reports) $this->_menu('STAFF_ATT_REPORTS', 'Staff Attendance Reports', (int)$reports->menu_id, '/reports/staff_attendance', 6);

        // every staff role (not parents) may see their own attendance and apply for leave
        $roles = $this->db->query("SELECT role_id FROM user_roles WHERE role_id <> 1 AND LOWER(role_name) <> 'parent'")->result();
        foreach ($roles as $r) {
            foreach (array($parent => array(1, 0), $mine => array(1, 1)) as $menu => $p) {
                if ($this->db->where('role_id', $r->role_id)->where('menu_id', $menu)->count_all_results('user_roles_menu_permissions')) continue;
                $this->db->insert('user_roles_menu_permissions', array('role_id' => $r->role_id, 'menu_id' => $menu,
                    'can_view' => $p[0], 'can_add' => $p[1], 'can_edit' => 0, 'can_delete' => 0));
            }
        }
    }

    public function down()
    {
        foreach (array('staff_leave_type', 'staff_holiday', 'staff_attendance', 'staff_leave_app', 'staff_leave_alloc', 'staff_att_setting') as $t) {
            $this->db->query("DROP TABLE IF EXISTS $t");
        }
        $names = array('STAFF_ATT_MARK', 'STAFF_LEAVE', 'STAFF_LEAVE_BAL', 'STAFF_ATT_SETTINGS', 'MY_ATTENDANCE', 'STAFF_ATT_REPORTS', 'STAFF_ATTENDANCE');
        $ids = array();
        foreach ($this->db->where_in('menu_name', $names)->get('menus')->result() as $m) $ids[] = (int)$m->menu_id;
        if ($ids) $this->db->where_in('menu_id', $ids)->delete('user_roles_menu_permissions');
        $this->db->where_in('menu_name', array_slice($names, 0, 6))->delete('menus');
        $this->db->where('menu_name', 'STAFF_ATTENDANCE')->delete('menus');
    }

    private function _menu($name, $display, $parentId, $link, $order)
    {
        $row = $this->db->where('menu_name', $name)->get('menus')->row();
        if ($row) return (int)$row->menu_id;
        $this->db->insert('menus', array('parent_menu_id' => $parentId, 'menu_name' => $name, 'display_name' => $display,
            'menu_link' => $link, 'display_order' => $order, 'status' => 1, 'group' => 2));
        return (int)$this->db->insert_id();
    }
}
