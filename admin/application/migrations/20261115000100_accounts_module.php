<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Accounts (double entry)
 *   acc_group    account groups (nature: ASSET, LIABILITY, EQUITY, INCOME, EXPENSE)
 *   acc_ledger   accounts; alSystemKey marks ledgers the automatic posting uses
 *   acc_voucher  voucher header; avSource/avSourceId = posted automatically from fees / store
 *   acc_entry    voucher lines (Dr / Cr); aeClearDate = bank reconciliation
 *   acc_setting  key / value settings
 */
class Migration_Accounts_module extends CI_Migration
{
    public function up()
    {
        $q = function ($sql) { $this->db->query($sql); };

        $q("CREATE TABLE IF NOT EXISTS acc_group (
            agId INT NOT NULL AUTO_INCREMENT,
            agName VARCHAR(100) NOT NULL,
            agNature VARCHAR(10) NOT NULL,
            agParentId INT NULL,
            agKey VARCHAR(30) NULL,
            agOrder INT NOT NULL DEFAULT 0,
            PRIMARY KEY (agId), UNIQUE KEY uq_ag_name (agName), UNIQUE KEY uq_ag_key (agKey)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS acc_ledger (
            alId INT NOT NULL AUTO_INCREMENT,
            alAgId INT NOT NULL,
            alCode VARCHAR(20) NULL,
            alName VARCHAR(150) NOT NULL,
            alType VARCHAR(10) NOT NULL DEFAULT 'GENERAL',
            alOpening DECIMAL(14,2) NOT NULL DEFAULT 0,
            alOpeningType CHAR(2) NOT NULL DEFAULT 'Dr',
            alBankName VARCHAR(100) NULL,
            alAccountNo VARCHAR(40) NULL,
            alIfsc VARCHAR(20) NULL,
            alPhone VARCHAR(20) NULL,
            alNotes VARCHAR(255) NULL,
            alSystemKey VARCHAR(60) NULL,
            alActive TINYINT(1) NOT NULL DEFAULT 1,
            alTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (alId), UNIQUE KEY uq_al_name (alName), UNIQUE KEY uq_al_key (alSystemKey), KEY ix_al_group (alAgId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS acc_voucher (
            avId INT NOT NULL AUTO_INCREMENT,
            avType VARCHAR(10) NOT NULL,
            avFy VARCHAR(7) NOT NULL,
            avSeq INT NOT NULL,
            avNo VARCHAR(30) NOT NULL,
            avDate DATE NOT NULL,
            avNarration VARCHAR(500) NULL,
            avRef VARCHAR(60) NULL,
            avAmount DECIMAL(14,2) NOT NULL DEFAULT 0,
            avSource VARCHAR(20) NULL,
            avSourceId INT NULL,
            avCancelled TINYINT(1) NOT NULL DEFAULT 0,
            avCancelReason VARCHAR(255) NULL,
            avUser INT NULL,
            avTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (avId), UNIQUE KEY uq_av_no (avNo), UNIQUE KEY uq_av_seq (avType, avFy, avSeq),
            UNIQUE KEY uq_av_source (avSource, avSourceId), KEY ix_av_date (avDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS acc_entry (
            aeId INT NOT NULL AUTO_INCREMENT,
            aeAvId INT NOT NULL,
            aeAlId INT NOT NULL,
            aeDr DECIMAL(14,2) NOT NULL DEFAULT 0,
            aeCr DECIMAL(14,2) NOT NULL DEFAULT 0,
            aeNarration VARCHAR(255) NULL,
            aeClearDate DATE NULL,
            PRIMARY KEY (aeId), KEY ix_ae_voucher (aeAvId), KEY ix_ae_ledger (aeAlId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS acc_setting (
            asKey VARCHAR(40) NOT NULL,
            asValue VARCHAR(255) NULL,
            PRIMARY KEY (asKey)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* ---------- default chart of accounts ---------- */
        // [key, name, nature, parent key]
        $groups = array(
            array('FIXED_ASSETS', 'Fixed Assets', 'ASSET', null),
            array('CURRENT_ASSETS', 'Current Assets', 'ASSET', null),
            array('CASH', 'Cash-in-Hand', 'ASSET', 'CURRENT_ASSETS'),
            array('BANK', 'Bank Accounts', 'ASSET', 'CURRENT_ASSETS'),
            array('DEBTORS', 'Sundry Debtors', 'ASSET', 'CURRENT_ASSETS'),
            array('STOCK', 'Stock-in-Hand', 'ASSET', 'CURRENT_ASSETS'),
            array('ADVANCES', 'Deposits & Advances', 'ASSET', 'CURRENT_ASSETS'),
            array('CAPITAL', 'Capital / Corpus Fund', 'EQUITY', null),
            array('RESERVES', 'Reserves & Surplus', 'EQUITY', null),
            array('LOANS', 'Loans (Liability)', 'LIABILITY', null),
            array('CURRENT_LIAB', 'Current Liabilities', 'LIABILITY', null),
            array('CREDITORS', 'Sundry Creditors', 'LIABILITY', 'CURRENT_LIAB'),
            array('TAXES', 'Duties & Taxes', 'LIABILITY', 'CURRENT_LIAB'),
            array('PROVISIONS', 'Provisions & Payables', 'LIABILITY', 'CURRENT_LIAB'),
            array('FEE_INCOME', 'Fee Income', 'INCOME', null),
            array('STORE_INCOME', 'Store Income', 'INCOME', null),
            array('OTHER_INCOME', 'Other Income', 'INCOME', null),
            array('SALARIES', 'Salaries & Staff Costs', 'EXPENSE', null),
            array('STORE_EXP', 'Store Costs', 'EXPENSE', null),
            array('ADMIN_EXP', 'Administrative Expenses', 'EXPENSE', null),
            array('MAINT_EXP', 'Repairs & Maintenance', 'EXPENSE', null),
            array('TRANSPORT_EXP', 'Transport Expenses', 'EXPENSE', null),
            array('ACADEMIC_EXP', 'Academic & Events', 'EXPENSE', null),
            array('OTHER_EXP', 'Other Expenses', 'EXPENSE', null),
        );
        $gid = array();
        foreach ($groups as $i => $g) {
            $row = $this->db->where('agKey', $g[0])->get('acc_group')->row();
            if (!$row) {
                $this->db->insert('acc_group', array('agKey' => $g[0], 'agName' => $g[1], 'agNature' => $g[2],
                    'agParentId' => $g[3] ? $gid[$g[3]] : null, 'agOrder' => $i + 1));
                $gid[$g[0]] = (int)$this->db->insert_id();
            } else {
                $gid[$g[0]] = (int)$row->agId;
            }
        }

        // [system key, name, group key, type]
        $ledgers = array(
            array('CASH', 'Cash', 'CASH', 'CASH'),
            array('BANK', 'Bank Account', 'BANK', 'BANK'),
            array('FEE_FINE', 'Late Fee / Fine', 'FEE_INCOME', 'GENERAL'),
            array('STORE_SALES', 'Store Sales', 'STORE_INCOME', 'GENERAL'),
            array('STORE_DEBTORS', 'Store Debtors', 'DEBTORS', 'GENERAL'),
            array('STOCK', 'Store Stock', 'STOCK', 'GENERAL'),
            array('COGS', 'Cost of Store Goods Sold', 'STORE_EXP', 'GENERAL'),
            array('STOCK_ADJ', 'Stock Adjustment (Loss / Gain)', 'STORE_EXP', 'GENERAL'),
            array('GST_OUT', 'GST Output', 'TAXES', 'GENERAL'),
            array('GST_IN', 'GST Input', 'TAXES', 'GENERAL'),
            array('OPENING_ADJ', 'Opening Balance Adjustment', 'RESERVES', 'GENERAL'),
            array(null, 'Corpus Fund', 'CAPITAL', 'GENERAL'),
            array(null, 'Donations Received', 'OTHER_INCOME', 'GENERAL'),
            array(null, 'Interest Received', 'OTHER_INCOME', 'GENERAL'),
            array(null, 'Teaching Staff Salary', 'SALARIES', 'GENERAL'),
            array(null, 'Non-Teaching Staff Salary', 'SALARIES', 'GENERAL'),
            array(null, 'PF & ESI Contribution', 'SALARIES', 'GENERAL'),
            array(null, 'Electricity Charges', 'ADMIN_EXP', 'GENERAL'),
            array(null, 'Water Charges', 'ADMIN_EXP', 'GENERAL'),
            array(null, 'Telephone & Internet', 'ADMIN_EXP', 'GENERAL'),
            array(null, 'Printing & Stationery', 'ADMIN_EXP', 'GENERAL'),
            array(null, 'Bank Charges', 'ADMIN_EXP', 'GENERAL'),
            array(null, 'Rent', 'ADMIN_EXP', 'GENERAL'),
            array(null, 'Building Maintenance', 'MAINT_EXP', 'GENERAL'),
            array(null, 'Furniture & Equipment Repairs', 'MAINT_EXP', 'GENERAL'),
            array(null, 'Vehicle Fuel', 'TRANSPORT_EXP', 'GENERAL'),
            array(null, 'Vehicle Maintenance', 'TRANSPORT_EXP', 'GENERAL'),
            array(null, 'Sports & Games', 'ACADEMIC_EXP', 'GENERAL'),
            array(null, 'Events & Celebrations', 'ACADEMIC_EXP', 'GENERAL'),
            array(null, 'Miscellaneous Expenses', 'OTHER_EXP', 'GENERAL'),
            array(null, 'Furniture & Fixtures', 'FIXED_ASSETS', 'GENERAL'),
            array(null, 'Computers & Equipment', 'FIXED_ASSETS', 'GENERAL'),
            array(null, 'School Vehicles', 'FIXED_ASSETS', 'GENERAL'),
        );
        foreach ($ledgers as $l) {
            if ($this->db->where('alName', $l[1])->count_all_results('acc_ledger')) continue;
            $this->db->insert('acc_ledger', array('alSystemKey' => $l[0], 'alName' => $l[1], 'alAgId' => $gid[$l[2]], 'alType' => $l[3]));
        }

        $cash = $this->db->where('alSystemKey', 'CASH')->get('acc_ledger')->row()->alId;
        $bank = $this->db->where('alSystemKey', 'BANK')->get('acc_ledger')->row()->alId;
        foreach (array('books_start' => date('Y') . '-04-01', 'cash_ledger' => $cash, 'bank_ledger' => $bank, 'auto_post' => '1') as $k => $v) {
            if (!$this->db->where('asKey', $k)->count_all_results('acc_setting')) $this->db->insert('acc_setting', array('asKey' => $k, 'asValue' => $v));
        }

        /* ---------- menus ---------- */
        $parent = $this->_menu('ACCOUNTS', 'Accounts', null, null, 12);
        $this->_menu('ACC_VOUCHERS', 'Vouchers', $parent, '/acc_vouchers', 1);
        $this->_menu('ACC_LEDGERS', 'Chart of Accounts', $parent, '/acc_ledgers', 2);
        $this->_menu('ACC_BANKING', 'Banking', $parent, '/acc_banking', 3);
        $this->_menu('ACC_SETTINGS', 'Account Settings', $parent, '/acc_settings', 4);
        $reports = $this->db->where('menu_name', 'REPORTS')->get('menus')->row();
        if ($reports) $this->_menu('ACC_REPORTS', 'Finance Reports', (int)$reports->menu_id, '/reports/accounts', 4);
    }

    public function down()
    {
        foreach (array('acc_entry', 'acc_voucher', 'acc_ledger', 'acc_group', 'acc_setting') as $t) $this->db->query("DROP TABLE IF EXISTS $t");
        $this->db->where_in('menu_name', array('ACC_VOUCHERS', 'ACC_LEDGERS', 'ACC_BANKING', 'ACC_SETTINGS', 'ACC_REPORTS'))->delete('menus');
        $this->db->where('menu_name', 'ACCOUNTS')->delete('menus');
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
