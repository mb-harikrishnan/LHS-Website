<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Parent portal + attendance
 *   parent_student      parent <-> student (smId stays the same across academic years)
 *   parent_notice       notices for parents (all / class / class + division)
 *   student_attendance  daily attendance (P present, A absent, H half day, L leave)
 *   parent_master       + login lockout columns
 *   exam_master         + emPublishParents (results visible in the portal)
 */
class Migration_Parent_portal extends CI_Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS parent_student (
            psId INT NOT NULL AUTO_INCREMENT,
            psPmId INT NOT NULL,
            psSmId INT NOT NULL,
            psTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (psId), UNIQUE KEY uq_parent_student (psPmId, psSmId), KEY ix_ps_student (psSmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS parent_notice (
            pnId INT NOT NULL AUTO_INCREMENT,
            pnTitle VARCHAR(200) NOT NULL,
            pnBody TEXT NOT NULL,
            pnDate DATE NOT NULL,
            pnExpiry DATE NULL,
            pnCmId INT NULL,
            pnDmId INT NULL,
            pnActive TINYINT(1) NOT NULL DEFAULT 1,
            pnUser INT NULL,
            pnTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (pnId), KEY ix_pn_date (pnDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS student_attendance (
            saId INT NOT NULL AUTO_INCREMENT,
            saSmId INT NOT NULL,
            saAmId INT NOT NULL,
            saCmId INT NOT NULL,
            saDmId INT NOT NULL,
            saDate DATE NOT NULL,
            saStatus CHAR(1) NOT NULL DEFAULT 'P',
            saRemark VARCHAR(100) NULL,
            saUser INT NULL,
            saTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (saId), UNIQUE KEY uq_sa_student_day (saSmId, saDate),
            KEY ix_sa_class_day (saCmId, saDmId, saDate), KEY ix_sa_year (saAmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        if (!$this->db->field_exists('pmFailCount', 'parent_master')) {
            $this->db->query("ALTER TABLE parent_master ADD COLUMN pmFailCount INT NOT NULL DEFAULT 0, ADD COLUMN pmLockUntil DATETIME NULL");
        }
        if (!$this->db->field_exists('emPublishParents', 'exam_master')) {
            $this->db->query("ALTER TABLE exam_master ADD COLUMN emPublishParents TINYINT(1) NOT NULL DEFAULT 0");
        }

        $parent = $this->_menu('PARENT_PORTAL', 'Parent Portal', null, null, 13);
        $this->_menu('PARENT_ACCOUNTS', 'Parent Accounts', $parent, '/parent_accounts', 1);
        $this->_menu('PARENT_NOTICES', 'Parent Notices', $parent, '/parent_notices', 2);
        $this->_menu('PARENT_PUBLISH', 'Publish Results', $parent, '/parent_publish', 3);
        $students = $this->db->where('menu_name', 'STUDENTS')->get('menus')->row();
        if ($students) $this->_menu('ATTENDANCE', 'Attendance', (int)$students->menu_id, '/attendance', 5);
    }

    public function down()
    {
        foreach (array('parent_student', 'parent_notice', 'student_attendance') as $t) $this->db->query("DROP TABLE IF EXISTS $t");
        if ($this->db->field_exists('pmFailCount', 'parent_master')) {
            $this->dbforge->drop_column('parent_master', 'pmFailCount');
            $this->dbforge->drop_column('parent_master', 'pmLockUntil');
        }
        if ($this->db->field_exists('emPublishParents', 'exam_master')) $this->dbforge->drop_column('exam_master', 'emPublishParents');
        $this->db->where_in('menu_name', array('PARENT_ACCOUNTS', 'PARENT_NOTICES', 'PARENT_PUBLISH', 'ATTENDANCE'))->delete('menus');
        $this->db->where('menu_name', 'PARENT_PORTAL')->delete('menus');
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
