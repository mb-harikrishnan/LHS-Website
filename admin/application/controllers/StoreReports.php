<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Store reports (menu: Store > Store Reports). Rendered by libraries/Report_view. */
class StoreReports extends CI_Controller
{
    private $amId;
    private $f;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Store_model', 'Store');
        $this->Store->require_schema();
        $this->load->model('Subject_Model');
        $this->load->library('Report_view');
        $this->load->helper('fee');
        $this->amId = $this->Store->get_am_id();

        $g = function ($k) { return trim((string)$this->input->get($k)); };
        $d = function ($k, $def) use ($g) {
            $v = $g($k); $x = DateTime::createFromFormat('Y-m-d', $v);
            return ($x && $x->format('Y-m-d') === $v) ? $v : $def;
        };
        $this->f = array(
            'q'        => mb_substr($g('q'), 0, 100),
            'from'     => $d('from', date('Y-m-01')),
            'to'       => $d('to', date('Y-m-d')),
            'class'    => ctype_digit($g('class')) ? $g('class') : '',
            'division' => ctype_digit($g('division')) ? $g('division') : '',
            'category' => (int)$g('category'),
            'supplier' => (int)$g('supplier'),
            'item'     => (int)$g('item'),
            'mode'     => in_array($g('mode'), Store_model::$pay_modes, true) ? $g('mode') : '',
            'group'    => in_array($g('group'), array('date', 'class', 'mode'), true) ? $g('group') : '',
            // unused by store reports but expected by the shared view
            'student' => 0, 'gender' => '', 'fee' => 0, 'fee_type' => '', 'as_of' => date('Y-m-d'), 'only_balance' => '1',
        );
    }

    private function _allowed()
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link('reports/store'));
        if (!empty($perm['can_view'])) return true;
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
        return false;
    }

    private function _dmy($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; }

    private function _sel_category()
    {
        $o = array();
        foreach ($this->Store->get_categories() as $c) $o[$c->scId] = $c->scName;
        return array('name' => 'category', 'label' => 'Category', 'options' => $o, 'value' => $this->f['category']);
    }

    private function _render($spec)
    {
        $spec['menu'] = 'STORE_REPORTS';
        $spec['hub']  = 'reports/store';
        $spec['year'] = $this->Store->year_label($this->amId);
        $this->report_view->output($spec, array(
            'f' => $this->f, 'classes' => $this->Subject_Model->get_all_classes(),
            'div_map' => $this->Subject_Model->get_class_division_map(), 'modes' => Store_model::$pay_modes,
        ));
    }

    /* ---------------- hub ---------------- */
    public function index()
    {
        if (!$this->_allowed()) return;
        $this->load->view('header', array('pageTitle' => 'Store Reports', 'activePage' => 'STORE_REPORTS',
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('report_hub', array('title' => 'Store Reports', 'groups' => array(
            'Stock' => array(
                array('reports/store_stock_summary', 'Stock Summary', 'Current stock and stock value of every item'),
                array('reports/store_low_stock', 'Low Stock', 'Items at or below their reorder level'),
                array('reports/store_stock_ledger', 'Stock Ledger', 'Every stock movement: opening, purchase, sale, adjustment'),
            ),
            'Sales' => array(
                array('reports/store_sales_register', 'Sales Register', 'Invoices between two dates'),
                array('reports/store_item_sales', 'Item-wise Sales', 'Quantity and amount sold per item, with margin'),
                array('reports/store_collections', 'Collections', 'Payments received, mode-wise'),
                array('reports/store_outstanding', 'Outstanding Invoices', 'Unpaid and part-paid invoices'),
            ),
            'Purchases' => array(
                array('reports/store_purchase_register', 'Purchase Register', 'Supplier bills between two dates'),
            ),
        )));
        $this->load->view('footer');
    }

    /* ---------------- stock ---------------- */
    public function stock_summary($low_only = false)
    {
        if (!$this->_allowed()) return;
        $rows = array();
        foreach ($this->Store->list_items(array('q' => $this->f['q'], 'category' => $this->f['category'], 'low' => $low_only, 'active' => '1')) as $i) {
            $rows[] = array(
                '_group' => $i->scName, 'code' => $i->siCode, 'name' => $i->siName, 'size' => $i->siSize, 'unit' => $i->siUnit,
                'stock' => (float)$i->siStock, 'reorder' => (float)$i->siReorderLevel, 'cost' => (float)$i->siPurchasePrice,
                'value' => round($i->siStock * $i->siPurchasePrice, 2), 'sale' => (float)$i->siSalePrice,
                'sale_value' => round($i->siStock * $i->siSalePrice, 2),
                'status' => (float)$i->siStock <= 0 ? 'Out of stock' : ((float)$i->siStock <= (float)$i->siReorderLevel ? 'Low' : ''),
            );
        }
        $this->_render(array(
            'title' => $low_only ? 'Low Stock' : 'Stock Summary',
            'route' => $low_only ? 'reports/store_low_stock' : 'reports/store_stock_summary',
            'filters' => array('q'), 'selects' => array($this->_sel_category()), 'group' => true,
            'note' => $low_only ? 'Active items whose stock is at or below the reorder level.' : 'Stock value at purchase price and at sale price.',
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Item'),
                array('key' => 'size', 'label' => 'Size'), array('key' => 'unit', 'label' => 'Unit'),
                array('key' => 'stock', 'label' => 'Stock', 'type' => 'money', 'total' => true),
                array('key' => 'reorder', 'label' => 'Reorder Level', 'type' => 'money'),
                array('key' => 'cost', 'label' => 'Cost', 'type' => 'money'),
                array('key' => 'value', 'label' => 'Value (Cost)', 'type' => 'money', 'total' => true),
                array('key' => 'sale', 'label' => 'Sale Price', 'type' => 'money'),
                array('key' => 'sale_value', 'label' => 'Value (Sale)', 'type' => 'money', 'total' => true),
                array('key' => 'status', 'label' => 'Status'),
            ),
            'rows' => $rows, 'count_label' => 'items',
        ));
    }

    public function low_stock()
    {
        $this->stock_summary(true);
    }

    public function stock_ledger()
    {
        if (!$this->_allowed()) return;
        $opts = array();
        foreach ($this->Store->list_items() as $i) $opts[$i->siId] = $i->siName . ($i->siSize ? ' (' . $i->siSize . ')' : '') . ' — ' . $i->siCode;
        $rows = array();
        $item = $this->f['item'];
        $bal  = $item ? $this->Store->stock_before($item, $this->f['from']) : null;
        if ($item) {
            $rows[] = array('date' => $this->_dmy($this->f['from']), 'type' => 'Opening balance', 'ref' => '', 'remarks' => '',
                            'in' => '', 'out' => '', 'balance' => $bal);
        }
        foreach ($this->Store->ledger($item, $this->f['from'], $this->f['to']) as $m) {
            $q = (float)$m->smvQty;
            if ($bal !== null) $bal += $q;
            $rows[] = array(
                'date' => $this->_dmy($m->smvDate),
                'item' => $m->siName . ($m->siSize ? ' (' . $m->siSize . ')' : ''),
                'type' => isset(Store_model::$move_types[$m->smvType]) ? Store_model::$move_types[$m->smvType] : $m->smvType,
                'ref' => $m->smvRemarks, 'in' => $q > 0 ? $q : '', 'out' => $q < 0 ? -$q : '',
                'balance' => $bal !== null ? $bal : '',
            );
        }
        $cols = array(array('key' => 'date', 'label' => 'Date'));
        if (!$item) $cols[] = array('key' => 'item', 'label' => 'Item');
        $cols = array_merge($cols, array(
            array('key' => 'type', 'label' => 'Type'), array('key' => 'ref', 'label' => 'Reference / Reason'),
            array('key' => 'in', 'label' => 'In', 'type' => 'money', 'total' => true),
            array('key' => 'out', 'label' => 'Out', 'type' => 'money', 'total' => true),
        ));
        if ($item) $cols[] = array('key' => 'balance', 'label' => 'Balance', 'type' => 'money');
        $this->_render(array(
            'title' => 'Stock Ledger', 'route' => 'reports/store_stock_ledger', 'filters' => array('from', 'to'),
            'selects' => array(array('name' => 'item', 'label' => 'Item', 'options' => $opts, 'value' => $item, 'all' => 'All items')),
            'note' => 'Movements from ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '. Choose an item to see the running balance.',
            'columns' => $cols, 'rows' => $rows, 'count_label' => 'movements',
        ));
    }

    /* ---------------- sales ---------------- */
    public function sales_register()
    {
        if (!$this->_allowed()) return;
        $f = $this->f;
        $rows = array();
        foreach ($this->Store->list_invoices($this->amId, $f) as $v) {
            if ($v->sinCancelled) continue;
            $row = array(
                'no' => $v->sinNo, 'date' => $this->_dmy($v->sinDate), 'customer' => $v->customer,
                'class' => $v->sinSmId ? trim($v->cmName . ' ' . $v->dmName) : 'Walk-in',
                'sub' => (float)$v->sinSubtotal, 'tax' => (float)$v->sinTax, 'disc' => (float)$v->sinDiscount,
                'total' => (float)$v->sinTotal, 'paid' => $v->paid, 'balance' => $v->balance, 'status' => $v->status,
                '_d' => $v->sinDate,
            );
            $rows[] = $row;
        }
        if ($f['group']) {
            $k  = $f['group'] === 'date' ? 'date' : 'class';
            $sk = $k === 'date' ? '_d' : 'class';
            usort($rows, function ($a, $b) use ($sk) { return strcmp($a[$sk] . $a['_d'] . $a['no'], $b[$sk] . $b['_d'] . $b['no']); });
            foreach ($rows as &$r) $r['_group'] = $r[$k];
            unset($r);
        } else {
            $rows = array_reverse($rows);   // oldest first
        }
        $this->_render(array(
            'title' => 'Sales Register', 'route' => 'reports/store_sales_register',
            'filters' => array('from', 'to', 'q', 'class', 'division', 'group'), 'group' => (bool)$f['group'],
            'note' => 'Invoices from ' . $this->_dmy($f['from']) . ' to ' . $this->_dmy($f['to']) . '. Cancelled invoices are excluded.',
            'columns' => array(
                array('key' => 'no', 'label' => 'Invoice No'), array('key' => 'date', 'label' => 'Date'),
                array('key' => 'customer', 'label' => 'Customer'), array('key' => 'class', 'label' => 'Class'),
                array('key' => 'sub', 'label' => 'Amount', 'type' => 'money', 'total' => true),
                array('key' => 'tax', 'label' => 'Tax', 'type' => 'money', 'total' => true),
                array('key' => 'disc', 'label' => 'Discount', 'type' => 'money', 'total' => true),
                array('key' => 'total', 'label' => 'Total', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'balance', 'label' => 'Balance', 'type' => 'money', 'total' => true),
                array('key' => 'status', 'label' => 'Status'),
            ),
            'rows' => $rows, 'count_label' => 'invoices',
        ));
    }

    public function item_sales()
    {
        if (!$this->_allowed()) return;
        $by = array();
        foreach ($this->Store->sales_lines($this->amId, $this->f) as $l) {
            $k = (int)$l->siiSiId;
            if (!isset($by[$k])) {
                $by[$k] = array('_group' => $l->scName ?: 'Other', 'code' => $l->siCode, 'name' => $l->siiName, 'unit' => $l->siUnit,
                                'qty' => 0, 'amount' => 0, 'tax' => 0, 'cost' => 0);
            }
            $by[$k]['qty']    += (float)$l->siiQty;
            $by[$k]['amount'] += (float)$l->siiAmount;
            $by[$k]['tax']    += (float)$l->siiTax;
            $by[$k]['cost']   += (float)$l->siiQty * (float)$l->siPurchasePrice;
        }
        $rows = array();
        foreach ($by as $r) {
            $r['margin'] = round($r['amount'] - $r['cost'], 2);
            $r['rate']   = $r['qty'] ? round($r['amount'] / $r['qty'], 2) : 0;
            $rows[] = $r;
        }
        $this->_render(array(
            'title' => 'Item-wise Sales', 'route' => 'reports/store_item_sales',
            'filters' => array('from', 'to', 'class'), 'selects' => array($this->_sel_category()), 'group' => true,
            'note' => 'Sold from ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '. Margin uses the current purchase price; invoice discounts are not split per item.',
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Item'), array('key' => 'unit', 'label' => 'Unit'),
                array('key' => 'qty', 'label' => 'Qty Sold', 'type' => 'money', 'total' => true),
                array('key' => 'rate', 'label' => 'Avg Rate', 'type' => 'money'),
                array('key' => 'amount', 'label' => 'Amount', 'type' => 'money', 'total' => true),
                array('key' => 'tax', 'label' => 'Tax', 'type' => 'money', 'total' => true),
                array('key' => 'cost', 'label' => 'Cost', 'type' => 'money', 'total' => true),
                array('key' => 'margin', 'label' => 'Margin', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'items',
        ));
    }

    public function collections()
    {
        if (!$this->_allowed()) return;
        $rows = array(); $modes = array();
        foreach ($this->Store->payments($this->amId, $this->f) as $p) {
            $rows[] = array(
                'date' => $this->_dmy($p->spayDate), '_d' => $p->spayDate, 'no' => $p->sinNo,
                'customer' => $p->smName ?: $p->sinCustomer, 'class' => trim($p->cmName . ' ' . $p->dmName),
                'mode' => $p->spayMode, 'ref' => $p->spayReference, 'amount' => (float)$p->spayAmount,
                'note' => $p->sinCancelled ? 'Invoice cancelled' : '',
            );
            $modes[$p->spayMode] = (isset($modes[$p->spayMode]) ? $modes[$p->spayMode] : 0) + (float)$p->spayAmount;
        }
        if ($this->f['group']) {
            $k = $this->f['group'] === 'mode' ? 'mode' : ($this->f['group'] === 'class' ? 'class' : 'date');
            usort($rows, function ($a, $b) use ($k) { return strcmp(($k === 'date' ? $a['_d'] : $a[$k]) . $a['_d'], ($k === 'date' ? $b['_d'] : $b[$k]) . $b['_d']); });
            foreach ($rows as &$r) $r['_group'] = $r[$k] !== '' ? $r[$k] : 'Walk-in';
            unset($r);
        }
        $note = 'Payments from ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '.';
        if ($modes) {
            $p = array(); foreach ($modes as $m => $v) $p[] = $m . ': ₹' . fee_money($v);
            $note .= ' Mode-wise: ' . implode(' · ', $p);
        }
        $this->_render(array(
            'title' => 'Store Collections', 'route' => 'reports/store_collections',
            'filters' => array('from', 'to', 'mode', 'group'), 'group' => (bool)$this->f['group'], 'note' => $note,
            'columns' => array(
                array('key' => 'date', 'label' => 'Date'), array('key' => 'no', 'label' => 'Invoice No'),
                array('key' => 'customer', 'label' => 'Customer'), array('key' => 'class', 'label' => 'Class'),
                array('key' => 'mode', 'label' => 'Mode'), array('key' => 'ref', 'label' => 'Reference'),
                array('key' => 'amount', 'label' => 'Amount', 'type' => 'money', 'total' => true),
                array('key' => 'note', 'label' => 'Note'),
            ),
            'rows' => $rows, 'count_label' => 'payments',
        ));
    }

    public function outstanding()
    {
        if (!$this->_allowed()) return;
        $f = $this->f; $f['from'] = ''; $f['to'] = '';
        $rows = array();
        foreach ($this->Store->list_invoices($this->amId, $f) as $v) {
            if ($v->sinCancelled || $v->balance <= 0) continue;
            $rows[] = array(
                '_group' => $v->sinSmId ? (trim($v->cmName . ' ' . $v->dmName) ?: 'Students') : 'Walk-in customers',
                '_s' => $v->sinSmId ? sprintf('%05d', (int)$v->smClass) . $v->dmName . $v->smName : 'zzzzz' . $v->sinCustomer,
                'no' => $v->sinNo, 'date' => $this->_dmy($v->sinDate), 'adm' => $v->smAdmissionNo, 'customer' => $v->customer,
                'phone' => $v->sinSmId ? $v->smMobile : $v->sinPhone,
                'total' => (float)$v->sinTotal, 'paid' => $v->paid, 'balance' => $v->balance,
                'days' => (int)floor((time() - strtotime($v->sinDate)) / 86400),
            );
        }
        usort($rows, function ($a, $b) { return strcmp($a['_s'], $b['_s']); });
        $this->_render(array(
            'title' => 'Outstanding Invoices', 'route' => 'reports/store_outstanding',
            'filters' => array('q', 'class', 'division'), 'group' => true,
            'note' => 'Unpaid and part-paid store invoices of this academic year.',
            'columns' => array(
                array('key' => 'no', 'label' => 'Invoice No'), array('key' => 'date', 'label' => 'Date'),
                array('key' => 'adm', 'label' => 'Adm No'), array('key' => 'customer', 'label' => 'Customer'),
                array('key' => 'phone', 'label' => 'Phone'),
                array('key' => 'total', 'label' => 'Total', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'balance', 'label' => 'Balance', 'type' => 'money', 'total' => true),
                array('key' => 'days', 'label' => 'Days', 'type' => 'int'),
            ),
            'rows' => $rows, 'count_label' => 'invoices',
        ));
    }

    /* ---------------- purchases ---------------- */
    public function purchase_register()
    {
        if (!$this->_allowed()) return;
        $f = $this->f; $f['cancelled'] = '0';
        $rows = array();
        foreach (array_reverse($this->Store->list_purchases($f)) as $p) {
            $rows[] = array('no' => $p->spNo, 'date' => $this->_dmy($p->spDate), 'supplier' => $p->ssName, 'bill' => $p->spBillNo,
                            'sub' => (float)$p->spSubtotal, 'tax' => (float)$p->spTax, 'total' => (float)$p->spTotal);
        }
        $sup = array();
        foreach ($this->Store->get_suppliers() as $s) $sup[$s->ssId] = $s->ssName;
        $this->_render(array(
            'title' => 'Purchase Register', 'route' => 'reports/store_purchase_register', 'filters' => array('from', 'to', 'q'),
            'selects' => array(array('name' => 'supplier', 'label' => 'Supplier', 'options' => $sup, 'value' => $this->f['supplier'])),
            'note' => 'Purchases from ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '. Cancelled purchases are excluded.',
            'columns' => array(
                array('key' => 'no', 'label' => 'Purchase No'), array('key' => 'date', 'label' => 'Date'),
                array('key' => 'supplier', 'label' => 'Supplier'), array('key' => 'bill', 'label' => 'Bill No'),
                array('key' => 'sub', 'label' => 'Amount', 'type' => 'money', 'total' => true),
                array('key' => 'tax', 'label' => 'Tax', 'type' => 'money', 'total' => true),
                array('key' => 'total', 'label' => 'Total', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'purchases',
        ));
    }
}
