<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payroll (employee salary)
 *   pay_component          earnings / deductions / employer contributions (HRA, DA, PF, ESI, PT, TDS ...)
 *   pay_employee           staff on payroll (linked to employee_master when they have a login) + salary structure
 *   pay_employee_component components of each staff member's structure (value overrides the default)
 *   pay_run                one payroll per month (DRAFT -> APPROVED)
 *   pay_approval           each approval of a run; posted to accounts (a reopen cancels it)
 *   pay_slip / _line       payslips with a snapshot of the staff details and every amount
 *   pay_advance            salary advances, recovered in monthly instalments through payroll
 *   pay_batch / _slip      salary payments (one batch = one payment voucher)
 */
class Migration_Payroll_module extends CI_Migration
{
    public function up()
    {
        $q = function ($sql) { $this->db->query($sql); };

        $q("CREATE TABLE IF NOT EXISTS pay_component (
            pcId INT NOT NULL AUTO_INCREMENT,
            pcName VARCHAR(80) NOT NULL,
            pcShort VARCHAR(12) NOT NULL,
            pcType VARCHAR(10) NOT NULL,
            pcCalc VARCHAR(6) NOT NULL DEFAULT 'FIXED',
            pcValue DECIMAL(12,2) NOT NULL DEFAULT 0,
            pcCeiling DECIMAL(12,2) NULL,
            pcGrossLimit DECIMAL(12,2) NULL,
            pcProrate TINYINT(1) NOT NULL DEFAULT 1,
            pcWage TINYINT(1) NOT NULL DEFAULT 0,
            pcDefault TINYINT(1) NOT NULL DEFAULT 0,
            pcKey VARCHAR(10) NULL,
            pcOrder INT NOT NULL DEFAULT 0,
            pcActive TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (pcId), UNIQUE KEY uq_pc_name (pcName)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_employee (
            peId INT NOT NULL AUTO_INCREMENT,
            peEmId INT NULL,
            peCode VARCHAR(20) NULL,
            peName VARCHAR(100) NOT NULL,
            pePhone VARCHAR(15) NULL,
            peCategory VARCHAR(12) NOT NULL DEFAULT 'TEACHING',
            peDesignation VARCHAR(80) NULL,
            peJoinDate DATE NULL,
            peLeaveDate DATE NULL,
            peBasic DECIMAL(12,2) NOT NULL DEFAULT 0,
            pePayMode VARCHAR(10) NOT NULL DEFAULT 'Bank',
            peBankName VARCHAR(100) NULL,
            peAccountNo VARCHAR(30) NULL,
            peIfsc VARCHAR(15) NULL,
            pePan VARCHAR(10) NULL,
            peUan VARCHAR(20) NULL,
            peEsiNo VARCHAR(20) NULL,
            peActive TINYINT(1) NOT NULL DEFAULT 1,
            peTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (peId), UNIQUE KEY uq_pe_em (peEmId), UNIQUE KEY uq_pe_code (peCode)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_employee_component (
            pecPeId INT NOT NULL,
            pecPcId INT NOT NULL,
            pecValue DECIMAL(12,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (pecPeId, pecPcId), KEY ix_pec_pc (pecPcId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_run (
            prId INT NOT NULL AUTO_INCREMENT,
            prMonth DATE NOT NULL,
            prDays INT NOT NULL,
            prStatus VARCHAR(10) NOT NULL DEFAULT 'DRAFT',
            prApprovedAt DATETIME NULL,
            prApprovedBy INT NULL,
            prRemarks VARCHAR(255) NULL,
            prUser INT NULL,
            prTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (prId), UNIQUE KEY uq_pr_month (prMonth)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_approval (
            paId INT NOT NULL AUTO_INCREMENT,
            paPrId INT NOT NULL,
            paDate DATE NOT NULL,
            paCancelled TINYINT(1) NOT NULL DEFAULT 0,
            paUser INT NULL,
            paTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (paId), KEY ix_pa_run (paPrId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_slip (
            psId INT NOT NULL AUTO_INCREMENT,
            psPrId INT NOT NULL,
            psPeId INT NOT NULL,
            psName VARCHAR(100) NOT NULL,
            psCode VARCHAR(20) NULL,
            psDesignation VARCHAR(80) NULL,
            psCategory VARCHAR(12) NOT NULL,
            psPayMode VARCHAR(10) NOT NULL,
            psBankName VARCHAR(100) NULL,
            psAccountNo VARCHAR(30) NULL,
            psIfsc VARCHAR(15) NULL,
            psPan VARCHAR(10) NULL,
            psUan VARCHAR(20) NULL,
            psEsiNo VARCHAR(20) NULL,
            psDays INT NOT NULL,
            psLop DECIMAL(5,1) NOT NULL DEFAULT 0,
            psPaidDays DECIMAL(5,1) NOT NULL DEFAULT 0,
            psBasicRate DECIMAL(12,2) NOT NULL DEFAULT 0,
            psExtraEarn DECIMAL(12,2) NOT NULL DEFAULT 0,
            psExtraEarnNote VARCHAR(100) NULL,
            psExtraDed DECIMAL(12,2) NOT NULL DEFAULT 0,
            psExtraDedNote VARCHAR(100) NULL,
            psGross DECIMAL(12,2) NOT NULL DEFAULT 0,
            psDeduction DECIMAL(12,2) NOT NULL DEFAULT 0,
            psEmployer DECIMAL(12,2) NOT NULL DEFAULT 0,
            psNet DECIMAL(12,2) NOT NULL DEFAULT 0,
            psPaid DECIMAL(12,2) NOT NULL DEFAULT 0,
            psRemarks VARCHAR(255) NULL,
            PRIMARY KEY (psId), UNIQUE KEY uq_ps_run_emp (psPrId, psPeId), KEY ix_ps_emp (psPeId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_slip_line (
            pslId INT NOT NULL AUTO_INCREMENT,
            pslPsId INT NOT NULL,
            pslKind VARCHAR(6) NOT NULL,
            pslPcId INT NULL,
            pslPadId INT NULL,
            pslName VARCHAR(80) NOT NULL,
            pslType VARCHAR(10) NOT NULL,
            pslKey VARCHAR(10) NULL,
            pslBase DECIMAL(12,2) NULL,
            pslAmount DECIMAL(12,2) NOT NULL DEFAULT 0,
            pslOrder INT NOT NULL DEFAULT 0,
            PRIMARY KEY (pslId), KEY ix_psl_slip (pslPsId), KEY ix_psl_pc (pslPcId), KEY ix_psl_adv (pslPadId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_advance (
            padId INT NOT NULL AUTO_INCREMENT,
            padPeId INT NOT NULL,
            padDate DATE NOT NULL,
            padAmount DECIMAL(12,2) NOT NULL,
            padInstallment DECIMAL(12,2) NOT NULL,
            padFromMonth DATE NOT NULL,
            padMode VARCHAR(20) NOT NULL DEFAULT 'Cash',
            padRef VARCHAR(60) NULL,
            padRemarks VARCHAR(255) NULL,
            padCancelled TINYINT(1) NOT NULL DEFAULT 0,
            padCancelReason VARCHAR(255) NULL,
            padUser INT NULL,
            padTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (padId), KEY ix_pad_emp (padPeId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_batch (
            pbId INT NOT NULL AUTO_INCREMENT,
            pbPrId INT NOT NULL,
            pbDate DATE NOT NULL,
            pbMode VARCHAR(20) NOT NULL,
            pbRef VARCHAR(60) NULL,
            pbAmount DECIMAL(14,2) NOT NULL DEFAULT 0,
            pbRemarks VARCHAR(255) NULL,
            pbCancelled TINYINT(1) NOT NULL DEFAULT 0,
            pbCancelReason VARCHAR(255) NULL,
            pbUser INT NULL,
            pbTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (pbId), KEY ix_pb_run (pbPrId), KEY ix_pb_date (pbDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS pay_batch_slip (
            pbsId INT NOT NULL AUTO_INCREMENT,
            pbsPbId INT NOT NULL,
            pbsPsId INT NOT NULL,
            pbsAmount DECIMAL(12,2) NOT NULL,
            PRIMARY KEY (pbsId), KEY ix_pbs_batch (pbsPbId), KEY ix_pbs_slip (pbsPsId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* ---------- default components (usual Indian school payroll; all editable) ---------- */
        // name, short, type, calc, value, ceiling, gross limit, prorate, wage, default, key
        $c = array(
            array('Dearness Allowance', 'DA', 'EARNING', 'BASIC', 0, null, null, 1, 1, 0, null),
            array('House Rent Allowance', 'HRA', 'EARNING', 'BASIC', 10, null, null, 1, 0, 0, null),
            array('Conveyance Allowance', 'CONV', 'EARNING', 'FIXED', 0, null, null, 1, 0, 0, null),
            array('Special Allowance', 'SPL', 'EARNING', 'FIXED', 0, null, null, 1, 0, 0, null),
            array('Provident Fund (Employee)', 'PF', 'DEDUCTION', 'WAGE', 12, 15000, null, 1, 0, 0, 'PF'),
            array('ESI (Employee)', 'ESI', 'DEDUCTION', 'GROSS', 0.75, null, 21000, 1, 0, 0, 'ESI'),
            array('Professional Tax', 'PT', 'DEDUCTION', 'FIXED', 200, null, null, 0, 0, 0, 'PT'),
            array('Income Tax (TDS)', 'TDS', 'DEDUCTION', 'FIXED', 0, null, null, 0, 0, 0, 'TDS'),
            array('Provident Fund (Employer)', 'PF-ER', 'EMPLOYER', 'WAGE', 12, 15000, null, 1, 0, 0, 'PF_ER'),
            array('ESI (Employer)', 'ESI-ER', 'EMPLOYER', 'GROSS', 3.25, null, 21000, 1, 0, 0, 'ESI_ER'),
        );
        foreach ($c as $i => $r) {
            if ($this->db->where('pcName', $r[0])->count_all_results('pay_component')) continue;
            $this->db->insert('pay_component', array('pcName' => $r[0], 'pcShort' => $r[1], 'pcType' => $r[2], 'pcCalc' => $r[3],
                'pcValue' => $r[4], 'pcCeiling' => $r[5], 'pcGrossLimit' => $r[6], 'pcProrate' => $r[7], 'pcWage' => $r[8],
                'pcDefault' => $r[9], 'pcKey' => $r[10], 'pcOrder' => $i + 1, 'pcActive' => 1));
        }

        /* ---------- menus ---------- */
        $parent = $this->_menu('PAYROLL', 'Payroll', null, null, 14);
        $this->_menu('PAY_RUNS', 'Monthly Payroll', $parent, '/payroll', 1);
        $this->_menu('PAY_STAFF', 'Salary Structure', $parent, '/pay_staff', 2);
        $this->_menu('PAY_ADVANCES', 'Salary Advances', $parent, '/pay_advances', 3);
        $this->_menu('PAY_COMPONENTS', 'Salary Components', $parent, '/pay_components', 4);
        $reports = $this->db->where('menu_name', 'REPORTS')->get('menus')->row();
        if ($reports) $this->_menu('PAY_REPORTS', 'Payroll Reports', (int)$reports->menu_id, '/reports/payroll', 5);
    }

    public function down()
    {
        foreach (array('pay_batch_slip', 'pay_batch', 'pay_advance', 'pay_slip_line', 'pay_slip', 'pay_approval', 'pay_run',
                       'pay_employee_component', 'pay_employee', 'pay_component') as $t) {
            $this->db->query("DROP TABLE IF EXISTS $t");
        }
        $this->db->where_in('menu_name', array('PAY_RUNS', 'PAY_STAFF', 'PAY_ADVANCES', 'PAY_COMPONENTS', 'PAY_REPORTS'))->delete('menus');
        $this->db->where('menu_name', 'PAYROLL')->delete('menus');
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
