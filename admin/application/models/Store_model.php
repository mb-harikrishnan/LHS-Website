<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * School store: items, suppliers, kits, stock, purchases, sales invoices, payments.
 *
 * Stock: every change is a row in store_stock_move (+ in / - out); store_item.siStock is
 * the running total, updated in the same transaction.
 * Prices are before tax; tax % per item (0 for most books/stationery).
 */
class Store_model extends CI_Model
{
    public static $units = array('Nos', 'Set', 'Pair', 'Pack', 'Box', 'Book', 'Dozen', 'Metre');
    public static $pay_modes = array('Cash', 'UPI', 'Card', 'Cheque', 'Bank Transfer');
    public static $move_types = array(
        'OPEN' => 'Opening', 'PURCHASE' => 'Purchase', 'PUR_CANCEL' => 'Purchase cancelled',
        'SALE' => 'Sale', 'SALE_CANCEL' => 'Sale cancelled', 'ADJUST' => 'Adjustment',
    );

    public function require_schema()
    {
        if (!$this->db->table_exists('store_stock_move')) {
            show_error('The store module tables are not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create them.', 500, 'Store module not installed');
        }
    }

    public function get_am_id()
    {
        $amId = (int)$this->session->userdata('selected_am_id');
        if ($amId > 0) return $amId;
        $row = $this->db->select('amId')->where('amIsCurrent', 1)->get('academic_master')->row();
        return $row ? (int)$row->amId : 0;
    }

    public function year_label($amId)
    {
        $y = $this->db->select('amYear')->where('amId', (int)$amId)->get('academic_master')->row();
        return $y ? $y->amYear : (string)$amId;
    }

    private static function r2($n) { return round((float)$n, 2); }

    /* ================================================================
       CATEGORIES
       ================================================================ */
    public function get_categories($active_only = false)
    {
        if ($active_only) $this->db->where('scActive', 1);
        return $this->db->order_by('scOrder')->order_by('scName')->get('store_category')->result();
    }

    public function category_name_exists($name, $exclude = 0)
    {
        $this->db->where('LOWER(scName)', mb_strtolower($name));
        if ($exclude) $this->db->where('scId !=', (int)$exclude);
        return $this->db->count_all_results('store_category') > 0;
    }

    public function save_category($id, $name, $active)
    {
        $row = array('scName' => $name, 'scActive' => $active ? 1 : 0);
        if ($id) return $this->db->where('scId', (int)$id)->update('store_category', $row);
        return $this->db->insert('store_category', $row);
    }

    /* ================================================================
       ITEMS
       ================================================================ */
    public function list_items($f = array())
    {
        $this->db->select('i.*, c.scName, cm.cmName')
            ->from('store_item i')
            ->join('store_category c', 'c.scId = i.siScId', 'left')
            ->join('class_master cm', 'cm.cmId = i.siCmId', 'left');
        if (!empty($f['category'])) $this->db->where('i.siScId', (int)$f['category']);
        if (!empty($f['class']))    $this->db->where('i.siCmId', (int)$f['class']);
        if (!empty($f['q'])) {
            $this->db->group_start()->like('i.siName', $f['q'])->or_like('i.siCode', $f['q'])->group_end();
        }
        if (isset($f['active']) && $f['active'] !== '') $this->db->where('i.siActive', (int)$f['active']);
        if (!empty($f['low'])) $this->db->where('i.siStock <= i.siReorderLevel', null, false)->where('i.siActive', 1);
        return $this->db->order_by('c.scOrder')->order_by('i.siName')->order_by('i.siSize')->get()->result();
    }

    public function get_item($id)
    {
        return $this->db->where('siId', (int)$id)->get('store_item')->row();
    }

    public function item_code_exists($code, $exclude = 0)
    {
        $this->db->where('LOWER(siCode)', mb_strtolower($code));
        if ($exclude) $this->db->where('siId !=', (int)$exclude);
        return $this->db->count_all_results('store_item') > 0;
    }

    /* suggested next code: <category prefix><number> */
    public function next_code($scId)
    {
        $c = $this->db->where('scId', (int)$scId)->get('store_category')->row();
        $p = $c ? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $c->scName), 0, 3)) : 'ITM';
        $n = $this->db->where('siScId', (int)$scId)->count_all_results('store_item') + 1;
        do { $code = $p . str_pad($n++, 4, '0', STR_PAD_LEFT); } while ($this->item_code_exists($code));
        return $code;
    }

    public function save_item($id, $data, $opening = 0, $user = null)
    {
        $this->db->trans_start();
        if ($id) {
            $this->db->where('siId', (int)$id)->update('store_item', $data);
        } else {
            $data['siStock'] = 0;
            $data['siUser']  = $user;
            $this->db->insert('store_item', $data);
            $id = (int)$this->db->insert_id();
            if ($opening > 0) $this->move($id, $opening, 'OPEN', null, null, date('Y-m-d'), 'Opening stock', $user);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $id : 0;
    }

    public function item_has_moves($id)
    {
        return $this->db->where('smvSiId', (int)$id)->where('smvType !=', 'OPEN')->count_all_results('store_stock_move') > 0;
    }

    public function delete_item($id)
    {
        $this->db->trans_start();
        $this->db->where('smvSiId', (int)$id)->delete('store_stock_move');
        $this->db->where('skiSiId', (int)$id)->delete('store_kit_item');
        $this->db->where('siId', (int)$id)->delete('store_item');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* compact list for the line editors */
    public function items_for_picker()
    {
        $rows = $this->db->select('i.siId, i.siCode, i.siName, i.siSize, i.siUnit, i.siSalePrice, i.siPurchasePrice, i.siTaxPercent, i.siStock, c.scName')
            ->from('store_item i')->join('store_category c', 'c.scId = i.siScId', 'left')
            ->where('i.siActive', 1)->order_by('i.siName')->order_by('i.siSize')->get()->result();
        $out = array();
        foreach ($rows as $r) {
            $out[] = array(
                'id' => (int)$r->siId, 'code' => $r->siCode,
                'name' => $r->siName . ($r->siSize !== null && $r->siSize !== '' ? ' (' . $r->siSize . ')' : ''),
                'cat' => $r->scName, 'unit' => $r->siUnit, 'sale' => (float)$r->siSalePrice,
                'cost' => (float)$r->siPurchasePrice, 'tax' => (float)$r->siTaxPercent, 'stock' => (float)$r->siStock,
            );
        }
        return $out;
    }

    /* ================================================================
       STOCK
       ================================================================ */
    /* one stock change; keeps siStock in step */
    public function move($siId, $qty, $type, $refType, $refId, $date, $remarks, $user)
    {
        $qty = self::r2($qty);
        if ($qty == 0) return;
        $this->db->insert('store_stock_move', array(
            'smvSiId' => (int)$siId, 'smvDate' => $date, 'smvType' => $type, 'smvQty' => $qty,
            'smvRefType' => $refType, 'smvRefId' => $refId, 'smvRemarks' => $remarks, 'smvUser' => $user,
        ));
        $this->db->set('siStock', 'siStock + (' . $qty . ')', false)->where('siId', (int)$siId)->update('store_item');
    }

    public function adjust($siId, $qty, $date, $reason, $user)
    {
        $this->db->trans_start();
        $this->move($siId, $qty, 'ADJUST', null, null, $date, $reason, $user);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* rebuild siStock from the ledger (maintenance) */
    public function recompute_stock()
    {
        $this->db->query("UPDATE store_item i LEFT JOIN (SELECT smvSiId, SUM(smvQty) q FROM store_stock_move GROUP BY smvSiId) m
                          ON m.smvSiId = i.siId SET i.siStock = COALESCE(m.q, 0)");
    }

    public function ledger($siId, $from = '', $to = '')
    {
        $this->db->select('m.*, i.siName, i.siSize, i.siCode')->from('store_stock_move m')
            ->join('store_item i', 'i.siId = m.smvSiId');
        if ($siId) $this->db->where('m.smvSiId', (int)$siId);
        if ($from) $this->db->where('m.smvDate >=', $from);
        if ($to)   $this->db->where('m.smvDate <=', $to);
        return $this->db->order_by('m.smvDate')->order_by('m.smvId')->get()->result();
    }

    /* stock of an item before a date (for ledger opening balance) */
    public function stock_before($siId, $date)
    {
        $r = $this->db->select('COALESCE(SUM(smvQty),0) q', false)->where('smvSiId', (int)$siId)
                      ->where('smvDate <', $date)->get('store_stock_move')->row();
        return $r ? (float)$r->q : 0.0;
    }

    /* ================================================================
       SUPPLIERS
       ================================================================ */
    public function get_suppliers($active_only = false)
    {
        if ($active_only) $this->db->where('ssActive', 1);
        return $this->db->order_by('ssName')->get('store_supplier')->result();
    }

    public function get_supplier($id)
    {
        return $this->db->where('ssId', (int)$id)->get('store_supplier')->row();
    }

    public function save_supplier($id, $data)
    {
        if ($id) return $this->db->where('ssId', (int)$id)->update('store_supplier', $data);
        return $this->db->insert('store_supplier', $data);
    }

    public function supplier_in_use($id)
    {
        return $this->db->where('spSsId', (int)$id)->count_all_results('store_purchase') > 0;
    }

    public function delete_supplier($id)
    {
        return $this->db->where('ssId', (int)$id)->delete('store_supplier');
    }

    /* ================================================================
       KITS (class-wise sets)
       ================================================================ */
    public function get_kits($active_only = false)
    {
        $this->db->select('k.*, c.cmName')->from('store_kit k')->join('class_master c', 'c.cmId = k.skCmId', 'left');
        if ($active_only) $this->db->where('k.skActive', 1);
        $kits = $this->db->order_by('k.skCmId')->order_by('k.skName')->get()->result();
        if (!$kits) return $kits;

        $items = $this->db->select('ki.skiSkId, ki.skiSiId, ki.skiQty, i.siName, i.siSize, i.siSalePrice, i.siTaxPercent, i.siActive')
            ->from('store_kit_item ki')->join('store_item i', 'i.siId = ki.skiSiId')
            ->where_in('ki.skiSkId', array_map(function ($k) { return (int)$k->skId; }, $kits))
            ->order_by('ki.skiId')->get()->result();
        $by = array();
        foreach ($items as $it) $by[$it->skiSkId][] = $it;
        foreach ($kits as $k) {
            $k->items = isset($by[$k->skId]) ? $by[$k->skId] : array();
            $k->total = 0;
            foreach ($k->items as $it) $k->total += $it->skiQty * $it->siSalePrice * (1 + $it->siTaxPercent / 100);
        }
        return $kits;
    }

    public function get_kit($id)
    {
        return $this->db->where('skId', (int)$id)->get('store_kit')->row();
    }

    /* $items: [siId => qty] */
    public function save_kit($id, $data, $items)
    {
        $this->db->trans_start();
        if ($id) {
            $this->db->where('skId', (int)$id)->update('store_kit', $data);
        } else {
            $this->db->insert('store_kit', $data);
            $id = (int)$this->db->insert_id();
        }
        $this->db->where('skiSkId', (int)$id)->delete('store_kit_item');
        foreach ($items as $siId => $qty) {
            $this->db->insert('store_kit_item', array('skiSkId' => $id, 'skiSiId' => (int)$siId, 'skiQty' => $qty));
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $id : 0;
    }

    public function delete_kit($id)
    {
        $this->db->trans_start();
        $this->db->where('skiSkId', (int)$id)->delete('store_kit_item');
        $this->db->where('skId', (int)$id)->delete('store_kit');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* ================================================================
       DOCUMENT NUMBERS
       ================================================================ */
    /* next sequence of a table for the year (call inside a transaction) */
    private function _next_seq($table, $amCol, $seqCol, $amId)
    {
        $r = $this->db->query("SELECT COALESCE(MAX($seqCol),0) + 1 AS n FROM $table WHERE $amCol = ? FOR UPDATE", array((int)$amId))->row();
        return (int)$r->n;
    }

    /* line totals: [qty, rate, tax%] -> amount (before tax), tax */
    public static function line($qty, $rate, $taxPct)
    {
        $amount = self::r2($qty * $rate);
        return array('amount' => $amount, 'tax' => self::r2($amount * $taxPct / 100));
    }

    /* ================================================================
       PURCHASES (stock in)
       ================================================================ */
    /**
     * $head : supplier, bill_no, date, remarks
     * $lines: [ ['item' => siId, 'qty' => , 'rate' => , 'tax' => %], ... ]
     */
    public function save_purchase($amId, $head, $lines, $update_cost, $user)
    {
        $this->db->trans_begin();
        $seq = $this->_next_seq('store_purchase', 'spAmId', 'spSeq', $amId);
        $no  = 'PUR/' . $this->year_label($amId) . '/' . str_pad($seq, 5, '0', STR_PAD_LEFT);

        $sub = 0; $tax = 0; $rows = array();
        foreach ($lines as $l) {
            $t = self::line($l['qty'], $l['rate'], $l['tax']);
            $sub += $t['amount']; $tax += $t['tax'];
            $rows[] = array('spiSiId' => (int)$l['item'], 'spiQty' => $l['qty'], 'spiRate' => $l['rate'],
                            'spiTaxPercent' => $l['tax'], 'spiTax' => $t['tax'], 'spiAmount' => $t['amount']);
        }
        $this->db->insert('store_purchase', array(
            'spAmId' => (int)$amId, 'spSeq' => $seq, 'spNo' => $no, 'spSsId' => (int)$head['supplier'],
            'spBillNo' => $head['bill_no'], 'spDate' => $head['date'], 'spSubtotal' => self::r2($sub),
            'spTax' => self::r2($tax), 'spTotal' => self::r2($sub + $tax), 'spRemarks' => $head['remarks'], 'spUser' => $user,
        ));
        $spId = (int)$this->db->insert_id();
        foreach ($rows as $r) {
            $r['spiSpId'] = $spId;
            $this->db->insert('store_purchase_item', $r);
            $this->move($r['spiSiId'], $r['spiQty'], 'PURCHASE', 'PUR', $spId, $head['date'], $no, $user);
            if ($update_cost) $this->db->where('siId', $r['spiSiId'])->update('store_item', array('siPurchasePrice' => $r['spiRate']));
        }
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not save the purchase.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Purchase ' . $no . ' saved. Stock updated.', 'id' => $spId);
    }

    public function get_purchase($id)
    {
        $p = $this->db->select('p.*, s.ssName, s.ssPhone, s.ssGstin')->from('store_purchase p')
            ->join('store_supplier s', 's.ssId = p.spSsId', 'left')->where('p.spId', (int)$id)->get()->row();
        if (!$p) return null;
        $p->items = $this->db->select('pi.*, i.siCode, i.siName, i.siSize, i.siUnit')->from('store_purchase_item pi')
            ->join('store_item i', 'i.siId = pi.spiSiId', 'left')->where('pi.spiSpId', (int)$id)->order_by('pi.spiId')->get()->result();
        return $p;
    }

    public function list_purchases($f)
    {
        $this->db->select('p.*, s.ssName')->from('store_purchase p')->join('store_supplier s', 's.ssId = p.spSsId', 'left');
        if (!empty($f['from']))     $this->db->where('p.spDate >=', $f['from']);
        if (!empty($f['to']))       $this->db->where('p.spDate <=', $f['to']);
        if (!empty($f['supplier'])) $this->db->where('p.spSsId', (int)$f['supplier']);
        if (!empty($f['q']))        $this->db->group_start()->like('p.spNo', $f['q'])->or_like('p.spBillNo', $f['q'])->or_like('s.ssName', $f['q'])->group_end();
        if (isset($f['cancelled']) && $f['cancelled'] !== '') $this->db->where('p.spCancelled', (int)$f['cancelled']);
        return $this->db->order_by('p.spDate', 'DESC')->order_by('p.spId', 'DESC')->get()->result();
    }

    /* cancel = take the stock back out; refused when the stock has already been sold */
    public function cancel_purchase($id, $reason, $user)
    {
        $p = $this->get_purchase($id);
        if (!$p || $p->spCancelled) return array('ok' => false, 'msg' => 'Purchase not found or already cancelled.');
        $this->db->trans_begin();
        foreach ($p->items as $it) {
            $cur = $this->db->query('SELECT siStock FROM store_item WHERE siId = ? FOR UPDATE', array($it->spiSiId))->row();
            if (!$cur || (float)$cur->siStock < (float)$it->spiQty) {
                $this->db->trans_rollback();
                return array('ok' => false, 'msg' => $it->siName . ': only ' . ($cur ? (float)$cur->siStock : 0) . ' in stock, cannot take back ' . (float)$it->spiQty . '. Use a stock adjustment instead.');
            }
            $this->move($it->spiSiId, -$it->spiQty, 'PUR_CANCEL', 'PUR', $p->spId, date('Y-m-d'), $p->spNo . ' cancelled', $user);
        }
        $this->db->where('spId', $p->spId)->update('store_purchase', array('spCancelled' => 1, 'spCancelReason' => $reason));
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not cancel.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Purchase ' . $p->spNo . ' cancelled. Stock reduced.');
    }

    /* ================================================================
       SALES INVOICES
       ================================================================ */
    /**
     * $head : date, student (smId|0), customer, phone, discount, remarks
     * $lines: [ ['item' => siId, 'qty' => , 'rate' => ], ... ]   (tax % taken from the item)
     * $pay  : ['amount' => , 'mode' => , 'reference' => ]   (amount 0 = credit sale)
     */
    public function save_invoice($amId, $head, $lines, $pay, $user)
    {
        $this->db->trans_begin();

        // lock items, check stock
        $ids = array_map(function ($l) { return (int)$l['item']; }, $lines);
        $items = array();
        foreach ($this->db->query('SELECT * FROM store_item WHERE siId IN (' . implode(',', $ids) . ') FOR UPDATE')->result() as $i) $items[(int)$i->siId] = $i;
        $need = array();
        foreach ($lines as $l) $need[(int)$l['item']] = (isset($need[(int)$l['item']]) ? $need[(int)$l['item']] : 0) + $l['qty'];
        foreach ($need as $siId => $q) {
            if (!isset($items[$siId])) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'An item was not found. Reload the page.'); }
            if ((float)$items[$siId]->siStock < $q - 0.001) {
                $this->db->trans_rollback();
                return array('ok' => false, 'msg' => $items[$siId]->siName . ($items[$siId]->siSize ? ' (' . $items[$siId]->siSize . ')' : '')
                    . ': only ' . (float)$items[$siId]->siStock . ' in stock.');
            }
        }

        $sub = 0; $tax = 0; $rows = array();
        foreach ($lines as $l) {
            $it = $items[(int)$l['item']];
            $t  = self::line($l['qty'], $l['rate'], (float)$it->siTaxPercent);
            $sub += $t['amount']; $tax += $t['tax'];
            $rows[] = array('siiSiId' => (int)$it->siId, 'siiName' => $it->siName . ($it->siSize ? ' (' . $it->siSize . ')' : ''),
                            'siiQty' => $l['qty'], 'siiRate' => $l['rate'], 'siiTaxPercent' => (float)$it->siTaxPercent,
                            'siiTax' => $t['tax'], 'siiAmount' => $t['amount']);
        }
        $discount = min(self::r2($head['discount']), self::r2($sub + $tax));
        $total    = self::r2($sub + $tax - $discount);
        if ($pay['amount'] > $total + 0.001) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Amount paid is more than the invoice total.'); }

        $seq = $this->_next_seq('store_invoice', 'sinAmId', 'sinSeq', $amId);
        $no  = 'INV/' . $this->year_label($amId) . '/' . str_pad($seq, 5, '0', STR_PAD_LEFT);
        $this->db->insert('store_invoice', array(
            'sinAmId' => (int)$amId, 'sinSeq' => $seq, 'sinNo' => $no, 'sinDate' => $head['date'],
            'sinSmId' => $head['student'] ?: null, 'sinCustomer' => $head['customer'], 'sinPhone' => $head['phone'],
            'sinSubtotal' => self::r2($sub), 'sinTax' => self::r2($tax), 'sinDiscount' => $discount, 'sinTotal' => $total,
            'sinRemarks' => $head['remarks'], 'sinUser' => $user,
        ));
        $sinId = (int)$this->db->insert_id();
        foreach ($rows as $r) {
            $r['siiSinId'] = $sinId;
            $this->db->insert('store_invoice_item', $r);
            $this->move($r['siiSiId'], -$r['siiQty'], 'SALE', 'INV', $sinId, $head['date'], $no, $user);
        }
        if ($pay['amount'] > 0) {
            $this->db->insert('store_payment', array('spaySinId' => $sinId, 'spayDate' => $head['date'], 'spayAmount' => self::r2($pay['amount']),
                'spayMode' => $pay['mode'], 'spayReference' => $pay['reference'], 'spayUser' => $user));
        }
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not save the invoice.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Invoice ' . $no . ' saved.', 'id' => $sinId);
    }

    public function invoice_paid($sinId)
    {
        $r = $this->db->select('COALESCE(SUM(spayAmount),0) p', false)->where('spaySinId', (int)$sinId)->get('store_payment')->row();
        return $r ? (float)$r->p : 0.0;
    }

    public function get_invoice($id)
    {
        $v = $this->db->select('v.*, s.smAdmissionNo, s.smName, s.smMobile, c.cmName, d.dmName, y.amYear')
            ->from('store_invoice v')
            ->join('students_master s', 's.smId = v.sinSmId AND s.smAmId = v.sinAmId', 'left')
            ->join('class_master c', 'c.cmId = s.smClass', 'left')
            ->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->join('academic_master y', 'y.amId = v.sinAmId', 'left')
            ->where('v.sinId', (int)$id)->get()->row();
        if (!$v) return null;
        $v->items    = $this->db->where('siiSinId', (int)$id)->order_by('siiId')->get('store_invoice_item')->result();
        $v->payments = $this->db->where('spaySinId', (int)$id)->order_by('spayDate')->order_by('spayId')->get('store_payment')->result();
        $v->paid     = 0;
        foreach ($v->payments as $p) $v->paid += (float)$p->spayAmount;
        $v->balance  = $v->sinCancelled ? 0 : self::r2($v->sinTotal - $v->paid);
        return $v;
    }

    /* invoices with paid / balance / status */
    public function list_invoices($amId, $f)
    {
        $where = array('v.sinAmId = ?'); $p = array((int)$amId);
        if (!empty($f['from']))    { $where[] = 'v.sinDate >= ?'; $p[] = $f['from']; }
        if (!empty($f['to']))      { $where[] = 'v.sinDate <= ?'; $p[] = $f['to']; }
        if (!empty($f['class']))   { $where[] = 's.smClass = ?'; $p[] = (int)$f['class']; }
        if (!empty($f['division'])){ $where[] = 's.smDiv = ?'; $p[] = (int)$f['division']; }
        if (!empty($f['student'])) { $where[] = 'v.sinSmId = ?'; $p[] = (int)$f['student']; }
        if (!empty($f['q'])) {
            $where[] = '(v.sinNo LIKE ? OR v.sinCustomer LIKE ? OR s.smName LIKE ? OR s.smAdmissionNo LIKE ?)';
            for ($i = 0; $i < 4; $i++) $p[] = '%' . $f['q'] . '%';
        }
        $sql = "SELECT v.*, s.smAdmissionNo, s.smName, s.smMobile, s.smClass, c.cmName, d.dmName,
                       COALESCE(pay.paid, 0) AS paid
                FROM store_invoice v
                LEFT JOIN students_master s ON s.smId = v.sinSmId AND s.smAmId = v.sinAmId
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                LEFT JOIN (SELECT spaySinId, SUM(spayAmount) paid FROM store_payment GROUP BY spaySinId) pay ON pay.spaySinId = v.sinId
                WHERE " . implode(' AND ', $where) . "
                ORDER BY v.sinDate DESC, v.sinId DESC";
        $rows = $this->db->query($sql, $p)->result();
        $out = array();
        foreach ($rows as $r) {
            $r->paid    = (float)$r->paid;
            $r->balance = $r->sinCancelled ? 0 : self::r2($r->sinTotal - $r->paid);
            $r->status  = $r->sinCancelled ? 'Cancelled' : ($r->balance <= 0 ? 'Paid' : ($r->paid > 0 ? 'Partial' : 'Unpaid'));
            $r->customer = $r->sinSmId ? $r->smName : $r->sinCustomer;
            if (!empty($f['status']) && $r->status !== $f['status']) continue;
            $out[] = $r;
        }
        return $out;
    }

    public function add_payment($sinId, $amount, $date, $mode, $ref, $user)
    {
        $this->db->trans_begin();
        $v = $this->db->query('SELECT * FROM store_invoice WHERE sinId = ? FOR UPDATE', array((int)$sinId))->row();
        if (!$v || $v->sinCancelled) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Invoice not found or cancelled.'); }
        $bal = self::r2($v->sinTotal - $this->invoice_paid($sinId));
        if ($amount > $bal + 0.001) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Amount is more than the balance ₹' . number_format($bal, 2) . '.'); }
        $this->db->insert('store_payment', array('spaySinId' => (int)$sinId, 'spayDate' => $date, 'spayAmount' => self::r2($amount),
            'spayMode' => $mode, 'spayReference' => $ref, 'spayUser' => $user));
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Payment of ₹' . number_format($amount, 2) . ' saved.');
    }

    /* cancel = items back to stock; payments stay on record (refund them separately) */
    public function cancel_invoice($sinId, $reason, $user)
    {
        $v = $this->get_invoice($sinId);
        if (!$v || $v->sinCancelled) return array('ok' => false, 'msg' => 'Invoice not found or already cancelled.');
        $this->db->trans_start();
        foreach ($v->items as $it) {
            $this->move($it->siiSiId, $it->siiQty, 'SALE_CANCEL', 'INV', $v->sinId, date('Y-m-d'), $v->sinNo . ' cancelled', $user);
        }
        $this->db->where('sinId', $v->sinId)->update('store_invoice', array(
            'sinCancelled' => 1, 'sinCancelReason' => $reason, 'sinCancelledBy' => $user, 'sinCancelledAt' => date('Y-m-d H:i:s')));
        $this->db->trans_complete();
        if (!$this->db->trans_status()) return array('ok' => false, 'msg' => 'Could not cancel.');
        $msg = 'Invoice ' . $v->sinNo . ' cancelled and items returned to stock.';
        if ($v->paid > 0) $msg .= ' ₹' . number_format($v->paid, 2) . ' was paid — refund it to the customer.';
        return array('ok' => true, 'msg' => $msg);
    }

    /* unpaid balance of a student's store invoices (all years) */
    public function student_balance($smId)
    {
        $r = $this->db->query("SELECT COALESCE(SUM(v.sinTotal - COALESCE(p.paid,0)),0) bal
                               FROM store_invoice v
                               LEFT JOIN (SELECT spaySinId, SUM(spayAmount) paid FROM store_payment GROUP BY spaySinId) p ON p.spaySinId = v.sinId
                               WHERE v.sinSmId = ? AND v.sinCancelled = 0", array((int)$smId))->row();
        return $r ? max(0, (float)$r->bal) : 0.0;
    }

    /* ================================================================
       REPORT DATA
       ================================================================ */
    public function sales_lines($amId, $f)
    {
        $where = array('v.sinAmId = ?', 'v.sinCancelled = 0'); $p = array((int)$amId);
        if (!empty($f['from']))     { $where[] = 'v.sinDate >= ?'; $p[] = $f['from']; }
        if (!empty($f['to']))       { $where[] = 'v.sinDate <= ?'; $p[] = $f['to']; }
        if (!empty($f['category'])) { $where[] = 'i.siScId = ?';   $p[] = (int)$f['category']; }
        if (!empty($f['class']))    { $where[] = 's.smClass = ?';  $p[] = (int)$f['class']; }
        $sql = "SELECT li.*, v.sinDate, v.sinNo, i.siCode, i.siUnit, i.siPurchasePrice, c.scName
                FROM store_invoice_item li
                JOIN store_invoice v ON v.sinId = li.siiSinId
                LEFT JOIN store_item i ON i.siId = li.siiSiId
                LEFT JOIN store_category c ON c.scId = i.siScId
                LEFT JOIN students_master s ON s.smId = v.sinSmId AND s.smAmId = v.sinAmId
                WHERE " . implode(' AND ', $where) . "
                ORDER BY c.scOrder, li.siiName";
        return $this->db->query($sql, $p)->result();
    }

    public function payments($amId, $f)
    {
        $where = array('v.sinAmId = ?'); $p = array((int)$amId);
        if (!empty($f['from'])) { $where[] = 'pm.spayDate >= ?'; $p[] = $f['from']; }
        if (!empty($f['to']))   { $where[] = 'pm.spayDate <= ?'; $p[] = $f['to']; }
        if (!empty($f['mode'])) { $where[] = 'pm.spayMode = ?';  $p[] = $f['mode']; }
        $sql = "SELECT pm.*, v.sinNo, v.sinCustomer, v.sinCancelled, s.smName, s.smAdmissionNo, c.cmName, d.dmName
                FROM store_payment pm
                JOIN store_invoice v ON v.sinId = pm.spaySinId
                LEFT JOIN students_master s ON s.smId = v.sinSmId AND s.smAmId = v.sinAmId
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                WHERE " . implode(' AND ', $where) . "
                ORDER BY pm.spayDate, pm.spayId";
        return $this->db->query($sql, $p)->result();
    }
}
