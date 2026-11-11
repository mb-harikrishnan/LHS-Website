<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fee management module
 *  - extends fee_master (frequency, installments, fine, special fee ...)
 *  - extends student_fee_allocation (per-student amount)
 *  - fee_master_class    : class-wise amount of a fee
 *  - student_fee_due     : installment schedule of every allotted fee (dues)
 *  - fee_receipt(+detail): collections, one receipt can pay many dues (partial allowed)
 *  - menus               : Fees sub-menus renamed, Reports menu added
 */
class Migration_Fee_management extends CI_Migration
{
    public function up()
    {
        /* ---------- fee_master: new columns ---------- */
        $fmCols = array(
            // existing rows were simple one-amount fees, so they default to ANNUAL x 1
            'fmFrequency'     => "VARCHAR(15) NOT NULL DEFAULT 'ANNUAL'",
            'fmInstallments'  => "INT NOT NULL DEFAULT 1",
            'fmStartMonth'    => "TINYINT NOT NULL DEFAULT 6",
            'fmDueDay'        => "TINYINT NOT NULL DEFAULT 10",
            'fmIsSpecial'     => "TINYINT(1) NOT NULL DEFAULT 0",
            'fmAutoAssign'    => "TINYINT(1) NOT NULL DEFAULT 0",
            'fmFineType'      => "VARCHAR(10) NOT NULL DEFAULT 'NONE'",
            'fmFineAmount'    => "DECIMAL(10,2) NOT NULL DEFAULT 0",
            'fmFineGraceDays' => "INT NOT NULL DEFAULT 0",
            'fmFineMax'       => "DECIMAL(10,2) NOT NULL DEFAULT 0",
            'fmOrder'         => "INT NOT NULL DEFAULT 0",
        );
        foreach ($fmCols as $col => $def) {
            if (!$this->db->field_exists($col, 'fee_master')) {
                $this->db->query("ALTER TABLE fee_master ADD COLUMN `$col` $def");
            }
        }

        /* ---------- student_fee_allocation: amount per installment ---------- */
        if (!$this->db->field_exists('sfaAmount', 'student_fee_allocation')) {
            $this->db->query("ALTER TABLE student_fee_allocation ADD COLUMN sfaAmount DECIMAL(10,2) NULL DEFAULT NULL AFTER sfaAmId");
        }
        $this->db->query("UPDATE student_fee_allocation a
                          JOIN fee_master f ON f.fmId = a.sfaFmId
                          SET a.sfaAmount = f.fmAmount
                          WHERE a.sfaAmount IS NULL");

        /* ---------- class-wise amounts ---------- */
        $this->db->query("CREATE TABLE IF NOT EXISTS fee_master_class (
            fmcId     INT NOT NULL AUTO_INCREMENT,
            fmcFmId   INT NOT NULL,
            fmcCmId   INT NOT NULL,
            fmcAmount DECIMAL(10,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (fmcId),
            UNIQUE KEY uq_fee_class (fmcFmId, fmcCmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* ---------- dues (installment schedule) ---------- */
        $this->db->query("CREATE TABLE IF NOT EXISTS student_fee_due (
            sfdId      INT NOT NULL AUTO_INCREMENT,
            sfdSfaId   INT NOT NULL,
            sfdSmId    INT NOT NULL,
            sfdFmId    INT NOT NULL,
            sfdAmId    INT NOT NULL,
            sfdInstNo  INT NOT NULL,
            sfdPeriod  VARCHAR(40) NOT NULL,
            sfdDueDate DATE NOT NULL,
            sfdAmount  DECIMAL(10,2) NOT NULL DEFAULT 0,
            sfdTs      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (sfdId),
            UNIQUE KEY uq_sfa_inst (sfdSfaId, sfdInstNo),
            KEY ix_sfd_student (sfdSmId, sfdAmId),
            KEY ix_sfd_fee (sfdFmId),
            KEY ix_sfd_date (sfdDueDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* ---------- receipts ---------- */
        $this->db->query("CREATE TABLE IF NOT EXISTS fee_receipt (
            frId           INT NOT NULL AUTO_INCREMENT,
            frAmId         INT NOT NULL,
            frSeq          INT NOT NULL,
            frReceiptNo    VARCHAR(30) NOT NULL,
            frSmId         INT NOT NULL,
            frDate         DATE NOT NULL,
            frMode         VARCHAR(20) NOT NULL DEFAULT 'Cash',
            frReference    VARCHAR(100) NULL,
            frRemarks      VARCHAR(500) NULL,
            frAmount       DECIMAL(10,2) NOT NULL DEFAULT 0,
            frFine         DECIMAL(10,2) NOT NULL DEFAULT 0,
            frTotal        DECIMAL(10,2) NOT NULL DEFAULT 0,
            frCancelled    TINYINT(1) NOT NULL DEFAULT 0,
            frCancelReason VARCHAR(255) NULL,
            frCancelledBy  INT NULL,
            frCancelledAt  DATETIME NULL,
            frUser         INT NULL,
            frTs           TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (frId),
            UNIQUE KEY uq_fr_no (frReceiptNo),
            UNIQUE KEY uq_fr_seq (frAmId, frSeq),
            KEY ix_fr_student (frSmId),
            KEY ix_fr_date (frDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS fee_receipt_detail (
            frdId     INT NOT NULL AUTO_INCREMENT,
            frdFrId   INT NOT NULL,
            frdSfdId  INT NOT NULL,
            frdSmId   INT NOT NULL,
            frdFmId   INT NOT NULL,
            frdAmId   INT NOT NULL,
            frdAmount DECIMAL(10,2) NOT NULL DEFAULT 0,
            frdFine   DECIMAL(10,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (frdId),
            KEY ix_frd_receipt (frdFrId),
            KEY ix_frd_due (frdSfdId),
            KEY ix_frd_student (frdSmId, frdAmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* ---------- menus ---------- */
        $this->db->where('menu_name', 'FEE_COLLECTION')->update('menus', array('display_name' => 'Fee Register'));
        $this->db->where('menu_name', 'FEE_MANAGEMENT')->update('menus', array('display_name' => 'Fee Receipts'));

        $reportsId = $this->_ensure_menu('REPORTS', 'Reports', null, null, 10);
        $this->_ensure_menu('STUDENT_REPORTS', 'Student Reports', $reportsId, '/reports/students', 1);
        $this->_ensure_menu('FEE_REPORTS', 'Fee Reports', $reportsId, '/reports/fees', 2);

        /* ---------- build dues for allocations that already exist ---------- */
        $this->load->model('Fee_model');
        $this->Fee_model->sync_all_dues();
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS fee_receipt_detail");
        $this->db->query("DROP TABLE IF EXISTS fee_receipt");
        $this->db->query("DROP TABLE IF EXISTS student_fee_due");
        $this->db->query("DROP TABLE IF EXISTS fee_master_class");

        foreach (array('fmFrequency', 'fmInstallments', 'fmStartMonth', 'fmDueDay', 'fmIsSpecial', 'fmAutoAssign',
                       'fmFineType', 'fmFineAmount', 'fmFineGraceDays', 'fmFineMax', 'fmOrder') as $col) {
            if ($this->db->field_exists($col, 'fee_master')) {
                $this->dbforge->drop_column('fee_master', $col);
            }
        }
        if ($this->db->field_exists('sfaAmount', 'student_fee_allocation')) {
            $this->dbforge->drop_column('student_fee_allocation', 'sfaAmount');
        }

        $this->db->where_in('menu_name', array('STUDENT_REPORTS', 'FEE_REPORTS'))->delete('menus');
        $this->db->where('menu_name', 'REPORTS')->delete('menus');
        $this->db->where('menu_name', 'FEE_COLLECTION')->update('menus', array('display_name' => 'Fee Collection'));
        $this->db->where('menu_name', 'FEE_MANAGEMENT')->update('menus', array('display_name' => 'Fee Management'));
    }

    /* insert a menu row once (menu_name is unique); returns its id */
    private function _ensure_menu($name, $display, $parentId, $link, $order)
    {
        $row = $this->db->where('menu_name', $name)->get('menus')->row();
        if ($row) {
            return (int)$row->menu_id;
        }
        $this->db->insert('menus', array(
            'parent_menu_id' => $parentId,
            'menu_name'      => $name,
            'display_name'   => $display,
            'menu_link'      => $link,
            'display_order'  => $order,
            'status'         => 1,
            'group'          => 2,
        ));
        return (int)$this->db->insert_id();
    }
}
