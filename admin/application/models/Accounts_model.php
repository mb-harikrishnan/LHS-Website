<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Accounts — double entry.
 *
 * Amount convention: Dr positive, Cr negative ("signed" balance).
 * Natures: ASSET, EXPENSE are normally Dr;  LIABILITY, EQUITY, INCOME normally Cr.
 * Cancelled vouchers are ignored everywhere.
 *
 * Automatic posting (sync_sources) creates vouchers for:
 *   FEE_RECEIPT    fee receipts            Dr Cash/Bank   Cr <fee> income, Late Fee
 *   STORE_INVOICE  store invoices          Dr Store Debtors  Cr Store Sales, GST Output;  Dr COGS  Cr Store Stock
 *   STORE_PAYMENT  store invoice payments  Dr Cash/Bank   Cr Store Debtors
 *   STORE_PURCHASE store purchases         Dr Store Stock, GST Input   Cr <supplier>
 *   STOCK_MOVE     opening stock / stock adjustments
 *   PAY_RUN        approved payroll        Dr Teaching / Non-Teaching Staff Salary, PF & ESI Contribution
 *                                          Cr Salary Payable, PF / ESI / PT / TDS payable, Staff Salary Advances
 *   PAY_PAYMENT    salary payments         Dr Salary Payable   Cr Cash/Bank
 *   PAY_ADVANCE    salary advances given   Dr Staff Salary Advances   Cr Cash/Bank
 * A cancelled receipt / invoice / purchase cancels its voucher.
 */
class Accounts_model extends CI_Model
{
    public static $types = array(
        'RECEIPT'  => array('label' => 'Receipt',  'prefix' => 'RV'),
        'PAYMENT'  => array('label' => 'Payment',  'prefix' => 'PV'),
        'CONTRA'   => array('label' => 'Contra',   'prefix' => 'CV'),
        'JOURNAL'  => array('label' => 'Journal',  'prefix' => 'JV'),
        'SALES'    => array('label' => 'Sales',    'prefix' => 'SV'),
        'PURCHASE' => array('label' => 'Purchase', 'prefix' => 'PU'),
    );
    public static $natures = array('ASSET' => 'Assets', 'LIABILITY' => 'Liabilities', 'EQUITY' => 'Capital & Reserves',
                                   'INCOME' => 'Income', 'EXPENSE' => 'Expenses');
    public static $sources = array('FEE_RECEIPT' => 'Fee receipt', 'STORE_INVOICE' => 'Store invoice', 'STORE_PAYMENT' => 'Store payment',
                                   'STORE_PURCHASE' => 'Store purchase', 'STOCK_MOVE' => 'Stock entry',
                                   'PAY_RUN' => 'Payroll', 'PAY_PAYMENT' => 'Salary payment', 'PAY_ADVANCE' => 'Salary advance');

    private $settings = null;

    public function require_schema()
    {
        if (!$this->db->table_exists('acc_entry')) {
            show_error('The accounts tables are not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create them.', 500, 'Accounts module not installed');
        }
    }

    private static function r2($n) { return round((float)$n, 2); }

    /* ================================================================
       SETTINGS
       ================================================================ */
    public function settings()
    {
        if ($this->settings === null) {
            $this->settings = array();
            foreach ($this->db->get('acc_setting')->result() as $r) $this->settings[$r->asKey] = $r->asValue;
        }
        return $this->settings;
    }

    public function setting($k, $default = null)
    {
        $s = $this->settings();
        return isset($s[$k]) && $s[$k] !== '' ? $s[$k] : $default;
    }

    public function set_setting($k, $v)
    {
        $this->db->query('INSERT INTO acc_setting (asKey, asValue) VALUES (?, ?) ON DUPLICATE KEY UPDATE asValue = VALUES(asValue)', array($k, $v));
        $this->settings = null;
    }

    public function books_start()
    {
        return $this->setting('books_start', date('Y') . '-04-01');
    }

    /* financial year (April - March) of a date: "2026-27" */
    public static function fy($date)
    {
        $y = (int)substr($date, 0, 4);
        $m = (int)substr($date, 5, 2);
        $s = $m >= 4 ? $y : $y - 1;
        return $s . '-' . substr((string)($s + 1), 2);
    }

    public static function fy_range($date)
    {
        $s = (int)substr(self::fy($date), 0, 4);
        return array($s . '-04-01', ($s + 1) . '-03-31');
    }

    /* ================================================================
       GROUPS & LEDGERS
       ================================================================ */
    public function get_groups()
    {
        return $this->db->order_by('agOrder')->order_by('agName')->get('acc_group')->result();
    }

    public function get_group($id)
    {
        return $this->db->where('agId', (int)$id)->get('acc_group')->row();
    }

    public function group_name_exists($name, $exclude = 0)
    {
        $this->db->where('LOWER(agName)', mb_strtolower($name));
        if ($exclude) $this->db->where('agId !=', (int)$exclude);
        return $this->db->count_all_results('acc_group') > 0;
    }

    public function save_group($id, $data)
    {
        if ($id) return $this->db->where('agId', (int)$id)->update('acc_group', $data);
        $data['agOrder'] = (int)$this->db->select_max('agOrder')->get('acc_group')->row()->agOrder + 1;
        return $this->db->insert('acc_group', $data);
    }

    public function group_in_use($id)
    {
        return $this->db->where('alAgId', (int)$id)->count_all_results('acc_ledger') > 0
            || $this->db->where('agParentId', (int)$id)->count_all_results('acc_group') > 0;
    }

    public function delete_group($id)
    {
        return $this->db->where('agId', (int)$id)->where('agKey IS NULL', null, false)->delete('acc_group');
    }

    public function get_ledgers($active_only = false, $types = null)
    {
        $this->db->select('l.*, g.agName, g.agNature, g.agParentId')->from('acc_ledger l')->join('acc_group g', 'g.agId = l.alAgId');
        if ($active_only) $this->db->where('l.alActive', 1);
        if ($types) $this->db->where_in('l.alType', (array)$types);
        return $this->db->order_by('g.agOrder')->order_by('l.alName')->get()->result();
    }

    public function get_ledger($id)
    {
        return $this->db->select('l.*, g.agName, g.agNature')->from('acc_ledger l')->join('acc_group g', 'g.agId = l.alAgId')
                        ->where('l.alId', (int)$id)->get()->row();
    }

    public function ledger_name_exists($name, $exclude = 0)
    {
        $this->db->where('LOWER(alName)', mb_strtolower($name));
        if ($exclude) $this->db->where('alId !=', (int)$exclude);
        return $this->db->count_all_results('acc_ledger') > 0;
    }

    public function save_ledger($id, $data)
    {
        if ($id) return $this->db->where('alId', (int)$id)->update('acc_ledger', $data) ? (int)$id : 0;
        return $this->db->insert('acc_ledger', $data) ? (int)$this->db->insert_id() : 0;
    }

    public function ledger_in_use($id)
    {
        return $this->db->where('aeAlId', (int)$id)->count_all_results('acc_entry') > 0;
    }

    public function delete_ledger($id)
    {
        return $this->db->where('alId', (int)$id)->where('alSystemKey IS NULL', null, false)->delete('acc_ledger');
    }

    /* ledger id for a system key; creates it when missing */
    public function key_ledger($key, $name = null, $groupKey = null, $type = 'GENERAL')
    {
        $row = $this->db->select('alId')->where('alSystemKey', $key)->get('acc_ledger')->row();
        if ($row) return (int)$row->alId;
        if ($name === null) return 0;
        $g = $this->db->where('agKey', $groupKey)->get('acc_group')->row();
        if (!$g) return 0;
        // adopt an existing ledger of the same name in the same group, else make the name unique
        $same = $this->db->where('alName', $name)->get('acc_ledger')->row();
        if ($same && (int)$same->alAgId === (int)$g->agId && !$same->alSystemKey) {
            $this->db->where('alId', $same->alId)->update('acc_ledger', array('alSystemKey' => $key));
            return (int)$same->alId;
        }
        $n = $name; $i = 2;
        while ($this->ledger_name_exists($n)) $n = $name . ' (' . $i++ . ')';
        $this->db->insert('acc_ledger', array('alAgId' => $g->agId, 'alName' => mb_substr($n, 0, 150), 'alType' => $type, 'alSystemKey' => $key));
        return (int)$this->db->insert_id();
    }

    /* cash / bank ledger for a payment mode */
    public function mode_ledger($mode)
    {
        $id = (int)$this->setting($mode === 'Cash' ? 'cash_ledger' : 'bank_ledger', 0);
        return $id ?: $this->key_ledger($mode === 'Cash' ? 'CASH' : 'BANK');
    }

    /* ================================================================
       VOUCHERS
       ================================================================ */
    /**
     * $lines: [ ['ledger' => id, 'dr' => x, 'cr' => y, 'narration' => ''], ... ]  (must balance)
     * returns ['ok', 'msg', 'id']
     */
    public function save_voucher($id, $type, $date, $narration, $ref, $lines, $user, $source = null, $sourceId = null)
    {
        $dr = 0; $cr = 0; $clean = array();
        foreach ($lines as $l) {
            $d = self::r2($l['dr']); $c = self::r2($l['cr']);
            if ($d < 0) { $c -= $d; $d = 0; }          // negative amounts go to the other side
            if ($c < 0) { $d -= $c; $c = 0; }
            if ($d == 0 && $c == 0) continue;
            $dr += $d; $cr += $c;
            $clean[] = array('aeAlId' => (int)$l['ledger'], 'aeDr' => $d, 'aeCr' => $c,
                             'aeNarration' => isset($l['narration']) ? mb_substr((string)$l['narration'], 0, 255) : null);
        }
        if (!$clean) return array('ok' => false, 'msg' => 'Enter the amounts.');
        if (abs($dr - $cr) > 0.005) return array('ok' => false, 'msg' => 'Debit (' . number_format($dr, 2) . ') and credit (' . number_format($cr, 2) . ') must be equal.');

        $this->db->trans_begin();
        if ($id) {
            $this->db->where('avId', (int)$id)->update('acc_voucher', array(
                'avDate' => $date, 'avNarration' => $narration, 'avRef' => $ref, 'avAmount' => self::r2($dr), 'avUser' => $user));
            // keep reconciliation dates of lines that stay on the same ledger with the same amount
            $old = array();
            foreach ($this->db->where('aeAvId', (int)$id)->get('acc_entry')->result() as $e) {
                if ($e->aeClearDate) $old[$e->aeAlId . '|' . $e->aeDr . '|' . $e->aeCr] = $e->aeClearDate;
            }
            $this->db->where('aeAvId', (int)$id)->delete('acc_entry');
            foreach ($clean as &$c) {
                $k = $c['aeAlId'] . '|' . number_format($c['aeDr'], 2, '.', '') . '|' . number_format($c['aeCr'], 2, '.', '');
                if (isset($old[$k])) $c['aeClearDate'] = $old[$k];
            }
            unset($c);
            $avId = (int)$id;
            $no = $this->db->select('avNo')->where('avId', $avId)->get('acc_voucher')->row()->avNo;
        } else {
            $fy  = self::fy($date);
            $seq = (int)$this->db->query('SELECT COALESCE(MAX(avSeq),0) + 1 n FROM acc_voucher WHERE avType = ? AND avFy = ? FOR UPDATE', array($type, $fy))->row()->n;
            $no  = self::$types[$type]['prefix'] . '/' . substr($fy, 2) . '/' . str_pad($seq, 5, '0', STR_PAD_LEFT);
            $this->db->insert('acc_voucher', array(
                'avType' => $type, 'avFy' => $fy, 'avSeq' => $seq, 'avNo' => $no, 'avDate' => $date,
                'avNarration' => $narration, 'avRef' => $ref, 'avAmount' => self::r2($dr),
                'avSource' => $source, 'avSourceId' => $sourceId, 'avUser' => $user,
            ));
            $avId = (int)$this->db->insert_id();
        }
        foreach ($clean as $c) { $c['aeAvId'] = $avId; $this->db->insert('acc_entry', $c); }

        if ($this->db->trans_status() === false || !$avId) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not save the voucher.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Voucher ' . $no . ' saved.', 'id' => $avId);
    }

    public function get_voucher($id)
    {
        $v = $this->db->where('avId', (int)$id)->get('acc_voucher')->row();
        if (!$v) return null;
        $v->entries = $this->db->select('e.*, l.alName, l.alType, g.agName')->from('acc_entry e')
            ->join('acc_ledger l', 'l.alId = e.aeAlId')->join('acc_group g', 'g.agId = l.alAgId')
            ->where('e.aeAvId', (int)$id)->order_by('e.aeDr > 0', 'DESC', false)->order_by('e.aeId')->get()->result();
        return $v;
    }

    public function cancel_voucher($id, $reason)
    {
        return $this->db->where('avId', (int)$id)->where('avCancelled', 0)
                        ->update('acc_voucher', array('avCancelled' => 1, 'avCancelReason' => $reason)) && $this->db->affected_rows() > 0;
    }

    public function list_vouchers($f)
    {
        $where = array('1=1'); $p = array();
        if (!empty($f['from']))   { $where[] = 'v.avDate >= ?'; $p[] = $f['from']; }
        if (!empty($f['to']))     { $where[] = 'v.avDate <= ?'; $p[] = $f['to']; }
        if (!empty($f['type']))   { $where[] = 'v.avType = ?';  $p[] = $f['type']; }
        if (!empty($f['ledger'])) { $where[] = 'EXISTS (SELECT 1 FROM acc_entry x WHERE x.aeAvId = v.avId AND x.aeAlId = ?)'; $p[] = (int)$f['ledger']; }
        if (isset($f['auto']) && $f['auto'] !== '') $where[] = $f['auto'] === '1' ? 'v.avSource IS NOT NULL' : 'v.avSource IS NULL';
        if (!empty($f['q']))      { $where[] = '(v.avNo LIKE ? OR v.avNarration LIKE ? OR v.avRef LIKE ?)'; for ($i = 0; $i < 3; $i++) $p[] = '%' . $f['q'] . '%'; }
        $limit = isset($f['limit']) ? (int)$f['limit'] : 500;
        $sql = "SELECT v.*,
                  (SELECT GROUP_CONCAT(l.alName ORDER BY e.aeDr DESC SEPARATOR ', ') FROM acc_entry e JOIN acc_ledger l ON l.alId = e.aeAlId
                    WHERE e.aeAvId = v.avId AND e.aeDr > 0) AS dr_names,
                  (SELECT GROUP_CONCAT(l.alName ORDER BY e.aeCr DESC SEPARATOR ', ') FROM acc_entry e JOIN acc_ledger l ON l.alId = e.aeAlId
                    WHERE e.aeAvId = v.avId AND e.aeCr > 0) AS cr_names
                FROM acc_voucher v WHERE " . implode(' AND ', $where) . "
                ORDER BY v.avDate DESC, v.avId DESC LIMIT " . max(1, $limit);
        return $this->db->query($sql, $p)->result();
    }

    /* ================================================================
       BALANCES
       ================================================================ */
    /**
     * Every ledger with: open (signed, before $from), dr / cr (between $from and $to), close (signed).
     * Opening = ledger opening balance + entries before $from.
     */
    public function balances($from, $to)
    {
        $sql = "SELECT l.alId, l.alName, l.alCode, l.alType, l.alActive, l.alOpening, l.alOpeningType, l.alSystemKey,
                       g.agId, g.agName, g.agNature, g.agParentId, g.agOrder,
                       COALESCE(SUM(CASE WHEN v.avDate < ? THEN e.aeDr - e.aeCr END), 0) AS pre,
                       COALESCE(SUM(CASE WHEN v.avDate >= ? AND v.avDate <= ? THEN e.aeDr END), 0) AS dr,
                       COALESCE(SUM(CASE WHEN v.avDate >= ? AND v.avDate <= ? THEN e.aeCr END), 0) AS cr
                FROM acc_ledger l
                JOIN acc_group g ON g.agId = l.alAgId
                LEFT JOIN acc_entry e ON e.aeAlId = l.alId
                LEFT JOIN acc_voucher v ON v.avId = e.aeAvId AND v.avCancelled = 0
                GROUP BY l.alId, g.agId
                ORDER BY g.agOrder, l.alName";
        $rows = $this->db->query($sql, array($from, $from, $to, $from, $to))->result();
        foreach ($rows as $r) {
            $ob = (float)$r->alOpening * ($r->alOpeningType === 'Cr' ? -1 : 1);
            $r->open  = self::r2($ob + (float)$r->pre);
            $r->dr    = self::r2($r->dr);
            $r->cr    = self::r2($r->cr);
            $r->close = self::r2($r->open + $r->dr - $r->cr);
        }
        return $rows;
    }

    /* signed balance of one ledger on a date (end of day) */
    public function ledger_balance($alId, $asOf)
    {
        $l = $this->get_ledger($alId);
        if (!$l) return 0.0;
        $r = $this->db->query("SELECT COALESCE(SUM(e.aeDr - e.aeCr),0) b FROM acc_entry e JOIN acc_voucher v ON v.avId = e.aeAvId AND v.avCancelled = 0
                               WHERE e.aeAlId = ? AND v.avDate <= ?", array((int)$alId, $asOf))->row();
        return self::r2((float)$l->alOpening * ($l->alOpeningType === 'Cr' ? -1 : 1) + (float)$r->b);
    }

    /* sum of all opening balances (should be 0; otherwise shown as a difference) */
    public function opening_difference()
    {
        $r = $this->db->query("SELECT COALESCE(SUM(CASE WHEN alOpeningType = 'Cr' THEN -alOpening ELSE alOpening END),0) d FROM acc_ledger")->row();
        return self::r2($r->d);
    }

    /**
     * Ledger statement: opening + lines with the other ledgers of each voucher, running balance.
     */
    public function statement($alId, $from, $to)
    {
        $l = $this->get_ledger($alId);
        if (!$l) return null;
        $pre = $this->db->query("SELECT COALESCE(SUM(e.aeDr - e.aeCr),0) b FROM acc_entry e JOIN acc_voucher v ON v.avId = e.aeAvId AND v.avCancelled = 0
                                 WHERE e.aeAlId = ? AND v.avDate < ?", array((int)$alId, $from))->row();
        $open = self::r2((float)$l->alOpening * ($l->alOpeningType === 'Cr' ? -1 : 1) + (float)$pre->b);
        $rows = $this->db->query("SELECT e.aeId, e.aeDr, e.aeCr, e.aeNarration, e.aeClearDate, v.avId, v.avNo, v.avType, v.avDate, v.avNarration, v.avRef,
                    (SELECT GROUP_CONCAT(DISTINCT l2.alName SEPARATOR ', ') FROM acc_entry e2 JOIN acc_ledger l2 ON l2.alId = e2.aeAlId
                      WHERE e2.aeAvId = v.avId AND e2.aeAlId <> e.aeAlId) AS other
                FROM acc_entry e JOIN acc_voucher v ON v.avId = e.aeAvId AND v.avCancelled = 0
                WHERE e.aeAlId = ? AND v.avDate >= ? AND v.avDate <= ?
                ORDER BY v.avDate, v.avId, e.aeId", array((int)$alId, $from, $to))->result();
        return array('ledger' => $l, 'open' => $open, 'rows' => $rows);
    }

    /* receipts / payments through cash & bank, by the other account, for a period */
    public function receipts_payments($from, $to)
    {
        return $this->db->query("SELECT l.alId, l.alName, g.agName, g.agNature, SUM(e.aeCr) AS rec, SUM(e.aeDr) AS pay
                FROM acc_entry e
                JOIN acc_voucher v ON v.avId = e.aeAvId AND v.avCancelled = 0
                JOIN acc_ledger l ON l.alId = e.aeAlId
                JOIN acc_group g ON g.agId = l.alAgId
                WHERE v.avDate >= ? AND v.avDate <= ? AND l.alType NOT IN ('CASH','BANK')
                  AND EXISTS (SELECT 1 FROM acc_entry x JOIN acc_ledger xl ON xl.alId = x.aeAlId
                              WHERE x.aeAvId = v.avId AND xl.alType IN ('CASH','BANK'))
                GROUP BY l.alId, g.agId
                ORDER BY g.agOrder, l.alName", array($from, $to))->result();
    }

    /* entries of a period (day book) */
    public function day_book($from, $to, $type = '')
    {
        $p = array($from, $to);
        $t = '';
        if ($type !== '') { $t = ' AND v.avType = ?'; $p[] = $type; }
        return $this->db->query("SELECT v.avId, v.avNo, v.avType, v.avDate, v.avNarration, v.avRef, v.avSource,
                                        e.aeDr, e.aeCr, e.aeNarration, l.alName
                FROM acc_voucher v JOIN acc_entry e ON e.aeAvId = v.avId JOIN acc_ledger l ON l.alId = e.aeAlId
                WHERE v.avCancelled = 0 AND v.avDate >= ? AND v.avDate <= ?$t
                ORDER BY v.avDate, v.avId, (e.aeDr > 0) DESC, e.aeId", $p)->result();
    }

    /* ================================================================
       BANK RECONCILIATION
       ================================================================ */
    /* entries of a bank ledger up to $asOf that the bank had not cleared by $asOf */
    public function unreconciled($alId, $asOf)
    {
        return $this->db->query("SELECT e.aeId, e.aeDr, e.aeCr, e.aeClearDate, v.avId, v.avNo, v.avDate, v.avNarration, v.avRef, v.avType,
                    (SELECT GROUP_CONCAT(DISTINCT l2.alName SEPARATOR ', ') FROM acc_entry e2 JOIN acc_ledger l2 ON l2.alId = e2.aeAlId
                      WHERE e2.aeAvId = v.avId AND e2.aeAlId <> e.aeAlId) AS other
                FROM acc_entry e JOIN acc_voucher v ON v.avId = e.aeAvId AND v.avCancelled = 0
                WHERE e.aeAlId = ? AND v.avDate <= ? AND (e.aeClearDate IS NULL OR e.aeClearDate > ?)
                ORDER BY v.avDate, v.avId", array((int)$alId, $asOf, $asOf))->result();
    }

    /* $dates: [aeId => 'Y-m-d' | ''] (only entries of this ledger are touched) */
    public function save_clear_dates($alId, $dates)
    {
        $n = 0;
        foreach ($dates as $aeId => $d) {
            $this->db->where('aeId', (int)$aeId)->where('aeAlId', (int)$alId)->update('acc_entry', array('aeClearDate' => $d !== '' ? $d : null));
            $n += $this->db->affected_rows();
        }
        return $n;
    }

    /* ================================================================
       AUTOMATIC POSTING FROM FEES AND STORE
       ================================================================ */
    private function _auto($type, $date, $narration, $ref, $lines, $source, $sourceId)
    {
        $res = $this->save_voucher(0, $type, $date, mb_substr($narration, 0, 500), $ref ? mb_substr($ref, 0, 60) : null, $lines, null, $source, $sourceId);
        return $res['ok'] ? 1 : 0;
    }

    private function _cancel_where_source_cancelled($source, $table, $idCol, $cancelCol, $reason)
    {
        $this->db->query("UPDATE acc_voucher v JOIN $table s ON v.avSource = ? AND v.avSourceId = s.$idCol
                          SET v.avCancelled = 1, v.avCancelReason = ? WHERE s.$cancelCol = 1 AND v.avCancelled = 0", array($source, $reason));
        return $this->db->affected_rows();
    }

    /**
     * Posts everything not posted yet (from the books start date). Safe to call on every page:
     * an advisory lock stops two requests posting at the same time, and each source is posted once.
     */
    public function sync_sources()
    {
        $out = array('posted' => 0, 'cancelled' => 0, 'skipped' => false);
        if ($this->setting('auto_post', '1') !== '1') { $out['skipped'] = true; return $out; }
        $lock = $this->db->query("SELECT GET_LOCK('lhs_acc_sync', 0) l")->row();
        if (!$lock || (int)$lock->l !== 1) { $out['skipped'] = true; return $out; }

        $start = $this->books_start();
        try {
            if ($this->db->table_exists('fee_receipt')) {
                $out['posted']    += $this->_post_fee_receipts($start);
                $out['cancelled'] += $this->_cancel_where_source_cancelled('FEE_RECEIPT', 'fee_receipt', 'frId', 'frCancelled', 'Fee receipt cancelled');
            }
            if ($this->db->table_exists('store_invoice')) {
                $out['posted']    += $this->_post_store_invoices($start);
                $out['posted']    += $this->_post_store_payments($start);
                $out['posted']    += $this->_post_store_purchases($start);
                $out['posted']    += $this->_post_stock_moves($start);
                $out['cancelled'] += $this->_cancel_where_source_cancelled('STORE_INVOICE', 'store_invoice', 'sinId', 'sinCancelled', 'Store invoice cancelled');
                $out['cancelled'] += $this->_cancel_where_source_cancelled('STORE_PURCHASE', 'store_purchase', 'spId', 'spCancelled', 'Store purchase cancelled');
            }
            if ($this->db->table_exists('pay_batch_slip')) {
                $out['posted']    += $this->_post_payroll($start);
                $out['posted']    += $this->_post_salary_payments($start);
                $out['posted']    += $this->_post_salary_advances($start);
                $out['cancelled'] += $this->_cancel_where_source_cancelled('PAY_RUN', 'pay_approval', 'paId', 'paCancelled', 'Payroll reopened');
                $out['cancelled'] += $this->_cancel_where_source_cancelled('PAY_PAYMENT', 'pay_batch', 'pbId', 'pbCancelled', 'Salary payment cancelled');
                $out['cancelled'] += $this->_cancel_where_source_cancelled('PAY_ADVANCE', 'pay_advance', 'padId', 'padCancelled', 'Salary advance cancelled');
            }
        } finally {
            $this->db->query("SELECT RELEASE_LOCK('lhs_acc_sync')");
        }
        return $out;
    }

    private function _unposted($sql, $params)
    {
        return $this->db->query($sql, $params)->result();
    }

    private function _post_fee_receipts($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT r.*, s.smName, s.smAdmissionNo FROM fee_receipt r
                LEFT JOIN acc_voucher v ON v.avSource = 'FEE_RECEIPT' AND v.avSourceId = r.frId
                LEFT JOIN students_master s ON s.smId = r.frSmId AND s.smAmId = r.frAmId
                WHERE v.avId IS NULL AND r.frCancelled = 0 AND r.frDate >= ? ORDER BY r.frDate, r.frId LIMIT 500", array($start));
        foreach ($rows as $r) {
            $lines = array(array('ledger' => $this->mode_ledger($r->frMode), 'dr' => $r->frTotal, 'cr' => 0));
            $det = $this->db->query("SELECT d.frdFmId, m.fmName, SUM(d.frdAmount) amt, SUM(d.frdFine) fine
                                     FROM fee_receipt_detail d JOIN fee_master m ON m.fmId = d.frdFmId
                                     WHERE d.frdFrId = ? GROUP BY d.frdFmId, m.fmName", array($r->frId))->result();
            $fine = 0;
            foreach ($det as $d) {
                $key = 'FEE:' . substr(md5(mb_strtolower(trim($d->fmName))), 0, 20);
                $lines[] = array('ledger' => $this->key_ledger($key, $d->fmName, 'FEE_INCOME'), 'dr' => 0, 'cr' => $d->amt);
                $fine += (float)$d->fine;
            }
            if ($fine > 0) $lines[] = array('ledger' => $this->key_ledger('FEE_FINE', 'Late Fee / Fine', 'FEE_INCOME'), 'dr' => 0, 'cr' => $fine);
            $n += $this->_auto('RECEIPT', $r->frDate, 'Fee receipt ' . $r->frReceiptNo . ' - ' . $r->smName . ' (' . $r->smAdmissionNo . ')',
                               $r->frReference ?: $r->frReceiptNo, $lines, 'FEE_RECEIPT', $r->frId);
        }
        return $n;
    }

    private function _post_store_invoices($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT i.*, s.smName FROM store_invoice i
                LEFT JOIN acc_voucher v ON v.avSource = 'STORE_INVOICE' AND v.avSourceId = i.sinId
                LEFT JOIN students_master s ON s.smId = i.sinSmId AND s.smAmId = i.sinAmId
                WHERE v.avId IS NULL AND i.sinCancelled = 0 AND i.sinDate >= ? ORDER BY i.sinDate, i.sinId LIMIT 500", array($start));
        foreach ($rows as $r) {
            $cost = (float)$this->db->query("SELECT COALESCE(SUM(li.siiQty * it.siPurchasePrice),0) c FROM store_invoice_item li
                                             JOIN store_item it ON it.siId = li.siiSiId WHERE li.siiSinId = ?", array($r->sinId))->row()->c;
            $lines = array(
                array('ledger' => $this->key_ledger('STORE_DEBTORS', 'Store Debtors', 'DEBTORS'), 'dr' => $r->sinTotal, 'cr' => 0),
                array('ledger' => $this->key_ledger('STORE_SALES', 'Store Sales', 'STORE_INCOME'), 'dr' => 0, 'cr' => $r->sinSubtotal - $r->sinDiscount),
                array('ledger' => $this->key_ledger('GST_OUT', 'GST Output', 'TAXES'), 'dr' => 0, 'cr' => $r->sinTax),
            );
            if ($cost > 0) {
                $lines[] = array('ledger' => $this->key_ledger('COGS', 'Cost of Store Goods Sold', 'STORE_EXP'), 'dr' => $cost, 'cr' => 0);
                $lines[] = array('ledger' => $this->key_ledger('STOCK', 'Store Stock', 'STOCK'), 'dr' => 0, 'cr' => $cost);
            }
            if ((float)$r->sinTotal == 0 && $cost == 0) continue;
            $who = $r->sinSmId ? $r->smName : $r->sinCustomer;
            $n += $this->_auto('SALES', $r->sinDate, 'Store invoice ' . $r->sinNo . ' - ' . $who, $r->sinNo, $lines, 'STORE_INVOICE', $r->sinId);
        }
        return $n;
    }

    private function _post_store_payments($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT p.*, i.sinNo FROM store_payment p JOIN store_invoice i ON i.sinId = p.spaySinId
                LEFT JOIN acc_voucher v ON v.avSource = 'STORE_PAYMENT' AND v.avSourceId = p.spayId
                WHERE v.avId IS NULL AND p.spayDate >= ? ORDER BY p.spayDate, p.spayId LIMIT 500", array($start));
        foreach ($rows as $r) {
            $lines = array(
                array('ledger' => $this->mode_ledger($r->spayMode), 'dr' => $r->spayAmount, 'cr' => 0),
                array('ledger' => $this->key_ledger('STORE_DEBTORS', 'Store Debtors', 'DEBTORS'), 'dr' => 0, 'cr' => $r->spayAmount),
            );
            $n += $this->_auto('RECEIPT', $r->spayDate, 'Store payment for ' . $r->sinNo, $r->spayReference ?: $r->sinNo, $lines, 'STORE_PAYMENT', $r->spayId);
        }
        return $n;
    }

    private function _post_store_purchases($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT p.*, s.ssName FROM store_purchase p JOIN store_supplier s ON s.ssId = p.spSsId
                LEFT JOIN acc_voucher v ON v.avSource = 'STORE_PURCHASE' AND v.avSourceId = p.spId
                WHERE v.avId IS NULL AND p.spCancelled = 0 AND p.spDate >= ? ORDER BY p.spDate, p.spId LIMIT 500", array($start));
        foreach ($rows as $r) {
            $lines = array(
                array('ledger' => $this->key_ledger('STOCK', 'Store Stock', 'STOCK'), 'dr' => $r->spSubtotal, 'cr' => 0),
                array('ledger' => $this->key_ledger('GST_IN', 'GST Input', 'TAXES'), 'dr' => $r->spTax, 'cr' => 0),
                array('ledger' => $this->key_ledger('SUP:' . $r->spSsId, $r->ssName, 'CREDITORS'), 'dr' => 0, 'cr' => $r->spTotal),
            );
            $n += $this->_auto('PURCHASE', $r->spDate, 'Store purchase ' . $r->spNo . ' - ' . $r->ssName . ($r->spBillNo ? ' bill ' . $r->spBillNo : ''),
                               $r->spBillNo ?: $r->spNo, $lines, 'STORE_PURCHASE', $r->spId);
        }
        return $n;
    }

    private function _post_stock_moves($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT m.*, i.siName, i.siSize, i.siPurchasePrice FROM store_stock_move m JOIN store_item i ON i.siId = m.smvSiId
                LEFT JOIN acc_voucher v ON v.avSource = 'STOCK_MOVE' AND v.avSourceId = m.smvId
                WHERE v.avId IS NULL AND m.smvType IN ('OPEN','ADJUST') AND m.smvDate >= ? AND i.siPurchasePrice > 0
                ORDER BY m.smvDate, m.smvId LIMIT 500", array($start));
        foreach ($rows as $r) {
            $val = self::r2(abs((float)$r->smvQty) * (float)$r->siPurchasePrice);
            if ($val <= 0) continue;
            $stock = $this->key_ledger('STOCK', 'Store Stock', 'STOCK');
            $other = $r->smvType === 'OPEN' ? $this->key_ledger('OPENING_ADJ', 'Opening Balance Adjustment', 'RESERVES')
                                            : $this->key_ledger('STOCK_ADJ', 'Stock Adjustment (Loss / Gain)', 'STORE_EXP');
            $in = (float)$r->smvQty > 0;
            $lines = array(
                array('ledger' => $stock, 'dr' => $in ? $val : 0, 'cr' => $in ? 0 : $val),
                array('ledger' => $other, 'dr' => $in ? 0 : $val, 'cr' => $in ? $val : 0),
            );
            $what = $r->smvType === 'OPEN' ? 'Opening stock' : 'Stock adjustment';
            $n += $this->_auto('JOURNAL', $r->smvDate, $what . ' - ' . $r->siName . ($r->siSize ? ' (' . $r->siSize . ')' : '') . ' x ' . (float)$r->smvQty
                               . ($r->smvRemarks && $r->smvType !== 'OPEN' ? ' - ' . $r->smvRemarks : ''), null, $lines, 'STOCK_MOVE', $r->smvId);
        }
        return $n;
    }

    /* liability ledger of a payroll deduction / employer contribution (employee + employer PF go to one PF Payable) */
    private function _pay_liability($key, $pcId, $name)
    {
        $map = array('PF' => array('PF', 'PF Payable'), 'PF_ER' => array('PF', 'PF Payable'), 'ESI' => array('ESI', 'ESI Payable'),
                     'ESI_ER' => array('ESI', 'ESI Payable'), 'PT' => array('PT', 'Professional Tax Payable'), 'TDS' => array('TDS', 'TDS Payable'));
        if (isset($map[$key])) {
            return $this->key_ledger('PAY_LIAB:' . $map[$key][0], $map[$key][1], in_array($key, array('PT', 'TDS'), true) ? 'TAXES' : 'PROVISIONS');
        }
        return $this->key_ledger('PAY_LIAB:C' . (int)$pcId, $name . ' Payable', 'PROVISIONS');
    }

    private function _post_payroll($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT a.*, r.prMonth FROM pay_approval a JOIN pay_run r ON r.prId = a.paPrId
                LEFT JOIN acc_voucher v ON v.avSource = 'PAY_RUN' AND v.avSourceId = a.paId
                WHERE v.avId IS NULL AND a.paCancelled = 0 AND a.paDate >= ? ORDER BY a.paDate, a.paId LIMIT 500", array($start));
        foreach ($rows as $a) {
            $lines = array();
            // earnings by staff category
            foreach ($this->db->query("SELECT s.psCategory, SUM(l.pslAmount) amt FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId
                                       WHERE s.psPrId = ? AND l.pslType = 'EARNING' GROUP BY s.psCategory", array($a->paPrId))->result() as $e) {
                $lines[] = $e->psCategory === 'TEACHING'
                    ? array('ledger' => $this->key_ledger('PAY_TEACHING', 'Teaching Staff Salary', 'SALARIES'), 'dr' => $e->amt, 'cr' => 0)
                    : array('ledger' => $this->key_ledger('PAY_NONTEACHING', 'Non-Teaching Staff Salary', 'SALARIES'), 'dr' => $e->amt, 'cr' => 0);
            }
            // deductions, employer contributions, advance recovery
            $liab = array(); $er = 0;
            foreach ($this->db->query("SELECT l.pslKind, l.pslType, l.pslKey, l.pslPcId, MAX(l.pslName) nm, SUM(l.pslAmount) amt
                                       FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId
                                       WHERE s.psPrId = ? AND l.pslType <> 'EARNING' GROUP BY l.pslKind, l.pslType, l.pslKey, l.pslPcId", array($a->paPrId))->result() as $d) {
                if ($d->pslType === 'EMPLOYER') $er += $d->amt;
                if ($d->pslKind === 'ADV')       $l = $this->key_ledger('PAY_ADV', 'Staff Salary Advances', 'ADVANCES');
                elseif ($d->pslKind === 'EXTRA') $l = $this->key_ledger('PAY_OTHER_DED', 'Salary Deductions (Other)', 'OTHER_INCOME');
                else                             $l = $this->_pay_liability($d->pslKey, $d->pslPcId, $d->nm);
                $liab[$l] = (isset($liab[$l]) ? $liab[$l] : 0) + $d->amt;
            }
            if ($er > 0) $lines[] = array('ledger' => $this->key_ledger('PAY_ER', 'PF & ESI Contribution', 'SALARIES'), 'dr' => $er, 'cr' => 0);
            foreach ($liab as $l => $amt) $lines[] = array('ledger' => $l, 'dr' => 0, 'cr' => $amt);
            $net = (float)$this->db->query('SELECT COALESCE(SUM(psNet),0) n FROM pay_slip WHERE psPrId = ?', array($a->paPrId))->row()->n;
            $lines[] = array('ledger' => $this->key_ledger('PAY_PAYABLE', 'Salary Payable', 'PROVISIONS'), 'dr' => 0, 'cr' => $net);
            $n += $this->_auto('JOURNAL', $a->paDate, 'Salary for ' . date('F Y', strtotime($a->prMonth)), 'Payroll ' . date('m/Y', strtotime($a->prMonth)),
                               $lines, 'PAY_RUN', $a->paId);
        }
        return $n;
    }

    private function _post_salary_payments($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT b.*, r.prMonth FROM pay_batch b JOIN pay_run r ON r.prId = b.pbPrId
                LEFT JOIN acc_voucher v ON v.avSource = 'PAY_PAYMENT' AND v.avSourceId = b.pbId
                WHERE v.avId IS NULL AND b.pbCancelled = 0 AND b.pbDate >= ? ORDER BY b.pbDate, b.pbId LIMIT 500", array($start));
        foreach ($rows as $b) {
            $who = $this->db->query("SELECT COUNT(*) c, MAX(s.psName) nm FROM pay_batch_slip x JOIN pay_slip s ON s.psId = x.pbsPsId WHERE x.pbsPbId = ?", array($b->pbId))->row();
            $lines = array(
                array('ledger' => $this->key_ledger('PAY_PAYABLE', 'Salary Payable', 'PROVISIONS'), 'dr' => $b->pbAmount, 'cr' => 0),
                array('ledger' => $this->mode_ledger($b->pbMode), 'dr' => 0, 'cr' => $b->pbAmount),
            );
            $n += $this->_auto('PAYMENT', $b->pbDate, 'Salary paid for ' . date('F Y', strtotime($b->prMonth)) . ' - '
                               . ((int)$who->c === 1 ? $who->nm : $who->c . ' staff'), $b->pbRef, $lines, 'PAY_PAYMENT', $b->pbId);
        }
        return $n;
    }

    private function _post_salary_advances($start)
    {
        $n = 0;
        $rows = $this->_unposted("SELECT a.*, p.peName FROM pay_advance a JOIN pay_employee p ON p.peId = a.padPeId
                LEFT JOIN acc_voucher v ON v.avSource = 'PAY_ADVANCE' AND v.avSourceId = a.padId
                WHERE v.avId IS NULL AND a.padCancelled = 0 AND a.padDate >= ? ORDER BY a.padDate, a.padId LIMIT 500", array($start));
        foreach ($rows as $a) {
            $lines = array(
                array('ledger' => $this->key_ledger('PAY_ADV', 'Staff Salary Advances', 'ADVANCES'), 'dr' => $a->padAmount, 'cr' => 0),
                array('ledger' => $this->mode_ledger($a->padMode), 'dr' => 0, 'cr' => $a->padAmount),
            );
            $n += $this->_auto('PAYMENT', $a->padDate, 'Salary advance to ' . $a->peName, $a->padRef, $lines, 'PAY_ADVANCE', $a->padId);
        }
        return $n;
    }

    /* how many source records are still waiting to be posted */
    public function pending_counts()
    {
        $start = $this->books_start();
        $c = array();
        $q = function ($sql) use ($start) { return (int)$this->db->query($sql, array($start))->row()->n; };
        if ($this->db->table_exists('fee_receipt')) {
            $c['Fee receipts'] = $q("SELECT COUNT(*) n FROM fee_receipt r LEFT JOIN acc_voucher v ON v.avSource='FEE_RECEIPT' AND v.avSourceId=r.frId WHERE v.avId IS NULL AND r.frCancelled=0 AND r.frDate >= ?");
        }
        if ($this->db->table_exists('store_invoice')) {
            $c['Store invoices']  = $q("SELECT COUNT(*) n FROM store_invoice i LEFT JOIN acc_voucher v ON v.avSource='STORE_INVOICE' AND v.avSourceId=i.sinId WHERE v.avId IS NULL AND i.sinCancelled=0 AND i.sinTotal > 0 AND i.sinDate >= ?");
            $c['Store payments']  = $q("SELECT COUNT(*) n FROM store_payment p LEFT JOIN acc_voucher v ON v.avSource='STORE_PAYMENT' AND v.avSourceId=p.spayId WHERE v.avId IS NULL AND p.spayDate >= ?");
            $c['Store purchases'] = $q("SELECT COUNT(*) n FROM store_purchase p LEFT JOIN acc_voucher v ON v.avSource='STORE_PURCHASE' AND v.avSourceId=p.spId WHERE v.avId IS NULL AND p.spCancelled=0 AND p.spDate >= ?");
        }
        if ($this->db->table_exists('pay_batch_slip')) {
            $c['Approved payrolls'] = $q("SELECT COUNT(*) n FROM pay_approval a LEFT JOIN acc_voucher v ON v.avSource='PAY_RUN' AND v.avSourceId=a.paId WHERE v.avId IS NULL AND a.paCancelled=0 AND a.paDate >= ?");
            $c['Salary payments']   = $q("SELECT COUNT(*) n FROM pay_batch b LEFT JOIN acc_voucher v ON v.avSource='PAY_PAYMENT' AND v.avSourceId=b.pbId WHERE v.avId IS NULL AND b.pbCancelled=0 AND b.pbDate >= ?");
            $c['Salary advances']   = $q("SELECT COUNT(*) n FROM pay_advance a LEFT JOIN acc_voucher v ON v.avSource='PAY_ADVANCE' AND v.avSourceId=a.padId WHERE v.avId IS NULL AND a.padCancelled=0 AND a.padDate >= ?");
        }
        return $c;
    }

    public function posted_counts()
    {
        $out = array();
        foreach ($this->db->query("SELECT avSource, COUNT(*) n, SUM(avCancelled) c FROM acc_voucher GROUP BY avSource")->result() as $r) {
            $out[$r->avSource ?: 'MANUAL'] = array('n' => (int)$r->n, 'c' => (int)$r->c);
        }
        return $out;
    }
}
