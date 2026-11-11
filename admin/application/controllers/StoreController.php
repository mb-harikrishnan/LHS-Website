<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * School store: Items, Suppliers, Class Kits, Stock, Purchases, Sales Invoices
 * Permissions follow the menu link of each screen (Menu Permission page).
 */
class StoreController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Store_model', 'Store');
        $this->Store->require_schema();
        $this->load->model('Subject_Model');
        $this->load->helper('fee');
    }

    /* ================================================================
       helpers
       ================================================================ */
    private function _perm($link)
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link($link));
        return is_array($perm) ? $perm : array();
    }

    private function _json($status, $msg, $extra = array())
    {
        $this->output->set_content_type('application/json')->set_output(json_encode(array_merge(
            array('status' => (bool)$status, 'msg' => $msg, 'csrf' => $this->security->get_csrf_hash()), $extra)));
    }

    private function _page($view, $data, $title, $active)
    {
        $this->load->view('header', array('pageTitle' => $title, 'activePage' => $active,
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    private function _deny()
    {
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
    }

    private function _user()  { return (int)$this->session->userdata('id'); }
    private function _clean($v) { return trim(preg_replace('/\s+/', ' ', (string)$v)); }

    private function _date($v)
    {
        $d = DateTime::createFromFormat('Y-m-d', (string)$v);
        return ($d && $d->format('Y-m-d') === $v) ? $v : '';
    }

    /* '' -> null, invalid -> false, else float (2 decimals, >= 0) */
    private function _num($v, $max = 99999999)
    {
        $v = trim((string)$v);
        if ($v === '') return null;
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/', $v) || (float)$v > $max) return false;
        return round((float)$v, 2);
    }

    private function _post_only()
    {
        return $this->input->method() === 'post';
    }

    /* ================================================================
       ITEMS
       ================================================================ */
    public function store_items()
    {
        $perm = $this->_perm('store_items');
        if (empty($perm['can_view'])) return $this->_deny();
        $f = array(
            'q'        => trim((string)$this->input->get('q')),
            'category' => (int)$this->input->get('category'),
            'class'    => (int)$this->input->get('class'),
            'active'   => in_array((string)$this->input->get('active'), array('0', '1'), true) ? (string)$this->input->get('active') : '',
        );
        $this->_page('store_items', array(
            'items' => $this->Store->list_items($f), 'f' => $f, 'perm' => $perm,
            'categories' => $this->Store->get_categories(), 'classes' => $this->Subject_Model->get_all_classes(),
            'units' => Store_model::$units,
        ), 'Store Items', 'STORE_ITEMS');
    }

    public function store_item_code()
    {
        $this->output->set_content_type('application/json')
             ->set_output(json_encode(array('code' => $this->Store->next_code((int)$this->input->get('category')))));
    }

    public function store_item_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('store_items');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Store->get_item($id)) return $this->_json(false, 'Item not found.');

        $cat   = (int)$this->input->post('category');
        $code  = strtoupper($this->_clean($this->input->post('code')));
        $name  = $this->_clean($this->input->post('name'));
        $size  = $this->_clean($this->input->post('size'));
        $class = (int)$this->input->post('class');
        $unit  = (string)$this->input->post('unit');
        $cost  = $this->_num($this->input->post('purchase_price'));
        $sale  = $this->_num($this->input->post('sale_price'));
        $tax   = $this->_num($this->input->post('tax'), 100);
        $reord = $this->_num($this->input->post('reorder'));
        $open  = $this->_num($this->input->post('opening'));

        $cats = array();
        foreach ($this->Store->get_categories() as $c) $cats[(int)$c->scId] = true;
        if (!isset($cats[$cat]))                                  return $this->_json(false, 'Select a category.');
        if ($code === '' || mb_strlen($code) > 40 || !preg_match('/^[A-Z0-9\-\/_.]+$/', $code)) return $this->_json(false, 'Enter an item code (letters, numbers, - / _ . only).');
        if ($this->Store->item_code_exists($code, $id))          return $this->_json(false, 'This item code is already used.');
        if ($name === '' || mb_strlen($name) > 150)             return $this->_json(false, 'Enter the item name (max 150 characters).');
        if (mb_strlen($size) > 30)                               return $this->_json(false, 'Size must be 30 characters or fewer.');
        if ($class && !$this->Subject_Model->class_exists_by_id($class)) return $this->_json(false, 'Select a valid class.');
        if (!in_array($unit, Store_model::$units, true))         return $this->_json(false, 'Select the unit.');
        if ($sale === null || $sale === false)                   return $this->_json(false, 'Enter a valid sale price.');
        if ($cost === false || $reord === false || $open === false) return $this->_json(false, 'Purchase price, reorder level and opening stock must be valid numbers.');
        if ($tax === false)                                      return $this->_json(false, 'Tax % must be between 0 and 100.');

        $row = array(
            'siScId' => $cat, 'siCode' => $code, 'siName' => $name, 'siSize' => $size !== '' ? $size : null,
            'siCmId' => $class ?: null, 'siUnit' => $unit, 'siPurchasePrice' => (float)$cost, 'siSalePrice' => $sale,
            'siTaxPercent' => (float)$tax, 'siReorderLevel' => (float)$reord, 'siActive' => $this->input->post('active') ? 1 : 0,
        );
        $ok = $this->Store->save_item($id, $row, $id ? 0 : (float)$open, $this->_user());
        return $this->_json((bool)$ok, $ok ? ($id ? 'Item updated.' : 'Item added.') : 'Could not save. Please try again.');
    }

    public function store_item_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_items');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $id = (int)$this->input->post('id');
        if (!$this->Store->get_item($id)) return $this->_json(false, 'Item not found.');
        if ($this->Store->item_has_moves($id)) return $this->_json(false, 'This item has purchases or sales. Mark it inactive instead.');
        $ok = $this->Store->delete_item($id);
        return $this->_json($ok, $ok ? 'Item deleted.' : 'Delete failed.');
    }

    public function store_category_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_items');
        if (empty($perm['can_add']) && empty($perm['can_edit'])) return $this->_json(false, 'You do not have permission to do this.');
        $id   = (int)$this->input->post('id');
        $name = $this->_clean($this->input->post('name'));
        if ($name === '' || mb_strlen($name) > 60) return $this->_json(false, 'Enter a category name (max 60 characters).');
        if ($this->Store->category_name_exists($name, $id)) return $this->_json(false, 'This category already exists.');
        $ok = $this->Store->save_category($id, $name, $this->input->post('active') !== '0');
        return $this->_json($ok, $ok ? 'Category saved.' : 'Could not save.');
    }

    /* ================================================================
       SUPPLIERS
       ================================================================ */
    public function store_suppliers()
    {
        $perm = $this->_perm('store_suppliers');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->_page('store_suppliers', array('suppliers' => $this->Store->get_suppliers(), 'perm' => $perm),
                     'Suppliers', 'STORE_SUPPLIERS');
    }

    public function store_supplier_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('store_suppliers');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Store->get_supplier($id)) return $this->_json(false, 'Supplier not found.');

        $name  = $this->_clean($this->input->post('name'));
        $phone = trim((string)$this->input->post('phone'));
        $gst   = strtoupper(trim((string)$this->input->post('gstin')));
        $addr  = trim((string)$this->input->post('address'));
        if ($name === '' || mb_strlen($name) > 150)                  return $this->_json(false, 'Enter the supplier name.');
        if ($phone !== '' && !preg_match('/^[0-9+\- ]{6,20}$/', $phone)) return $this->_json(false, 'Enter a valid phone number.');
        if ($gst !== '' && !preg_match('/^[0-9A-Z]{15}$/', $gst))   return $this->_json(false, 'GSTIN must be 15 letters / digits.');
        if (mb_strlen($addr) > 500)                                  return $this->_json(false, 'Address is too long.');

        $ok = $this->Store->save_supplier($id, array('ssName' => $name, 'ssPhone' => $phone, 'ssGstin' => $gst,
            'ssAddress' => $addr, 'ssActive' => $this->input->post('active') ? 1 : 0));
        return $this->_json($ok, $ok ? 'Supplier saved.' : 'Could not save.');
    }

    public function store_supplier_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_suppliers');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $id = (int)$this->input->post('id');
        if ($this->Store->supplier_in_use($id)) return $this->_json(false, 'This supplier has purchases. Mark it inactive instead.');
        $ok = $this->Store->delete_supplier($id);
        return $this->_json($ok, $ok ? 'Supplier deleted.' : 'Delete failed.');
    }

    /* ================================================================
       CLASS KITS
       ================================================================ */
    public function store_kits()
    {
        $perm = $this->_perm('store_kits');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->_page('store_kits', array(
            'kits' => $this->Store->get_kits(), 'items' => $this->Store->items_for_picker(),
            'classes' => $this->Subject_Model->get_all_classes(), 'perm' => $perm,
        ), 'Class Kits', 'STORE_KITS');
    }

    public function store_kit_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('store_kits');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Store->get_kit($id)) return $this->_json(false, 'Kit not found.');

        $name  = $this->_clean($this->input->post('name'));
        $class = (int)$this->input->post('class');
        if ($name === '' || mb_strlen($name) > 100) return $this->_json(false, 'Enter the kit name.');
        if ($class && !$this->Subject_Model->class_exists_by_id($class)) return $this->_json(false, 'Select a valid class.');

        $items = array();
        $ids = (array)$this->input->post('item');
        $qty = (array)$this->input->post('qty');
        foreach ($ids as $i => $siId) {
            $siId = (int)$siId;
            $q = $this->_num(isset($qty[$i]) ? $qty[$i] : '', 9999);
            if (!$siId) continue;
            if (!$this->Store->get_item($siId)) return $this->_json(false, 'An item in the kit was not found.');
            if (!$q) return $this->_json(false, 'Enter a quantity for every item.');
            $items[$siId] = (isset($items[$siId]) ? $items[$siId] : 0) + $q;
        }
        if (!$items) return $this->_json(false, 'Add at least one item to the kit.');

        $ok = $this->Store->save_kit($id, array('skName' => $name, 'skCmId' => $class ?: null,
            'skActive' => $this->input->post('active') ? 1 : 0), $items);
        return $this->_json((bool)$ok, $ok ? 'Kit saved.' : 'Could not save.');
    }

    public function store_kit_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_kits');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $ok = $this->Store->delete_kit((int)$this->input->post('id'));
        return $this->_json($ok, $ok ? 'Kit deleted.' : 'Delete failed.');
    }

    /* ================================================================
       STOCK
       ================================================================ */
    public function store_stock()
    {
        $perm = $this->_perm('store_stock');
        if (empty($perm['can_view'])) return $this->_deny();
        $f = array(
            'q' => trim((string)$this->input->get('q')), 'category' => (int)$this->input->get('category'),
            'low' => $this->input->get('low') === '1', 'active' => '1',
        );
        $this->_page('store_stock', array(
            'items' => $this->Store->list_items($f), 'f' => $f, 'perm' => $perm,
            'categories' => $this->Store->get_categories(),
        ), 'Stock', 'STORE_STOCK');
    }

    public function store_adjust()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_stock');
        if (empty($perm['can_edit']) && empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to adjust stock.');

        $item = $this->Store->get_item((int)$this->input->post('item'));
        $dir  = (string)$this->input->post('direction');
        $qty  = $this->_num($this->input->post('qty'), 999999);
        $why  = $this->_clean($this->input->post('reason'));
        $date = $this->_date((string)$this->input->post('date'));
        if (!$item)                                   return $this->_json(false, 'Item not found.');
        if (!in_array($dir, array('in', 'out'), true)) return $this->_json(false, 'Choose add or remove.');
        if (!$qty)                                    return $this->_json(false, 'Enter the quantity.');
        if ($why === '' || mb_strlen($why) > 255)     return $this->_json(false, 'Enter the reason (damaged, lost, count correction …).');
        if ($date === '' || $date > date('Y-m-d'))    return $this->_json(false, 'Enter a valid date (not in the future).');
        if ($dir === 'out' && $qty > (float)$item->siStock) return $this->_json(false, 'Only ' . (float)$item->siStock . ' in stock.');

        $ok = $this->Store->adjust($item->siId, $dir === 'out' ? -$qty : $qty, $date, $why, $this->_user());
        return $this->_json($ok, $ok ? 'Stock adjusted.' : 'Could not save.');
    }

    /* ================================================================
       PURCHASES
       ================================================================ */
    public function store_purchases()
    {
        $perm = $this->_perm('store_purchases');
        if (empty($perm['can_view'])) return $this->_deny();
        $f = array(
            'from' => $this->_date((string)$this->input->get('from')), 'to' => $this->_date((string)$this->input->get('to')),
            'supplier' => (int)$this->input->get('supplier'), 'q' => trim((string)$this->input->get('q')),
        );
        $this->_page('store_purchases', array(
            'rows' => $this->Store->list_purchases($f), 'f' => $f, 'perm' => $perm, 'suppliers' => $this->Store->get_suppliers(),
        ), 'Purchases', 'STORE_PURCHASES');
    }

    public function store_purchase_new()
    {
        $perm = $this->_perm('store_purchases');
        if (empty($perm['can_add'])) return $this->_deny();
        $this->_page('store_purchase_form', array(
            'suppliers' => $this->Store->get_suppliers(true), 'items' => $this->Store->items_for_picker(),
        ), 'New Purchase', 'STORE_PURCHASES');
    }

    public function store_purchase_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_purchases');
        if (empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');

        $sup  = $this->Store->get_supplier((int)$this->input->post('supplier'));
        $date = $this->_date((string)$this->input->post('date'));
        $bill = $this->_clean($this->input->post('bill_no'));
        $rem  = trim((string)$this->input->post('remarks'));
        if (!$sup)                                 return $this->_json(false, 'Select the supplier.');
        if ($date === '' || $date > date('Y-m-d')) return $this->_json(false, 'Enter a valid purchase date (not in the future).');
        if (mb_strlen($bill) > 50 || mb_strlen($rem) > 500) return $this->_json(false, 'Bill no or remarks is too long.');

        $lines = $this->_lines(true);
        if (is_string($lines)) return $this->_json(false, $lines);

        $res = $this->Store->save_purchase($this->Store->get_am_id(),
            array('supplier' => $sup->ssId, 'bill_no' => $bill, 'date' => $date, 'remarks' => $rem),
            $lines, (bool)$this->input->post('update_cost'), $this->_user());
        return $this->_json($res['ok'], $res['msg'], array('id' => $res['ok'] ? $res['id'] : 0));
    }

    /* posted item[] qty[] rate[] (tax[] for purchases) -> validated lines or error */
    private function _lines($with_tax)
    {
        $ids  = (array)$this->input->post('item');
        $qtys = (array)$this->input->post('qty');
        $rates= (array)$this->input->post('rate');
        $taxs = (array)$this->input->post('tax');
        $lines = array();
        foreach ($ids as $i => $siId) {
            $siId = (int)$siId;
            if (!$siId) continue;
            $it = $this->Store->get_item($siId);
            if (!$it) return 'An item was not found. Reload the page.';
            $q = $this->_num(isset($qtys[$i]) ? $qtys[$i] : '', 999999);
            $r = $this->_num(isset($rates[$i]) ? $rates[$i] : '');
            $t = $with_tax ? $this->_num(isset($taxs[$i]) ? $taxs[$i] : '0', 100) : 0;
            if (!$q)                       return 'Enter the quantity for ' . $it->siName . '.';
            if ($r === null || $r === false) return 'Enter a valid rate for ' . $it->siName . '.';
            if ($t === false)              return 'Tax % for ' . $it->siName . ' must be between 0 and 100.';
            $lines[] = array('item' => $siId, 'qty' => $q, 'rate' => $r, 'tax' => (float)$t);
        }
        if (!$lines) return 'Add at least one item.';
        if (count($lines) > 300) return 'Too many lines in one document.';
        return $lines;
    }

    public function store_purchase_view($id = 0)
    {
        $perm = $this->_perm('store_purchases');
        if (empty($perm['can_view'])) return $this->_deny();
        $p = $this->Store->get_purchase((int)$id);
        if (!$p) show_404();
        $this->_page('store_purchase_view', array('p' => $p, 'perm' => $perm), 'Purchase ' . $p->spNo, 'STORE_PURCHASES');
    }

    public function store_purchase_cancel()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_purchases');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to cancel purchases.');
        $why = $this->_clean($this->input->post('reason'));
        if ($why === '' || mb_strlen($why) > 255) return $this->_json(false, 'Enter the reason for cancelling.');
        $res = $this->Store->cancel_purchase((int)$this->input->post('id'), $why, $this->_user());
        return $this->_json($res['ok'], $res['msg']);
    }

    /* ================================================================
       SALES INVOICES
       ================================================================ */
    public function store_invoices()
    {
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_view'])) return $this->_deny();
        $f = array(
            'from'   => $this->_date((string)$this->input->get('from')),
            'to'     => $this->_date((string)$this->input->get('to')),
            'q'      => trim((string)$this->input->get('q')),
            'status' => in_array((string)$this->input->get('status'), array('Paid', 'Partial', 'Unpaid', 'Cancelled'), true) ? (string)$this->input->get('status') : '',
        );
        $this->_page('store_invoices', array(
            'rows' => $this->Store->list_invoices($this->Store->get_am_id(), $f), 'f' => $f, 'perm' => $perm,
            'modes' => Store_model::$pay_modes,
        ), 'Sales / Invoices', 'STORE_INVOICES');
    }

    public function store_invoice_new()
    {
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_add'])) return $this->_deny();
        $this->_page('store_invoice_form', array(
            'items'   => $this->Store->items_for_picker(),
            'kits'    => $this->Store->get_kits(true),
            'classes' => $this->Subject_Model->get_all_classes(),
            'modes'   => Store_model::$pay_modes,
        ), 'New Invoice', 'STORE_INVOICES');
    }

    /* student search for the invoice form */
    public function store_students()
    {
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_add'])) return $this->output->set_content_type('application/json')->set_output('[]');
        $this->load->model('Fee_model');
        $rows = $this->Fee_model->search_students(trim((string)$this->input->get('q')), $this->Store->get_am_id(), '', '', 20);
        $out = array();
        foreach ($rows as $r) {
            $out[] = array('id' => (int)$r->smId, 'adm' => $r->smAdmissionNo, 'name' => $r->smName, 'class_id' => (int)$r->smClass,
                           'class' => trim($r->cmName . ' ' . $r->dmName), 'balance' => $this->Store->student_balance($r->smId));
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function store_invoice_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');

        $amId  = $this->Store->get_am_id();
        $date  = $this->_date((string)$this->input->post('date'));
        $smId  = (int)$this->input->post('student');
        $cust  = $this->_clean($this->input->post('customer'));
        $phone = trim((string)$this->input->post('phone'));
        $disc  = $this->_num($this->input->post('discount'));
        $paid  = $this->_num($this->input->post('paid'));
        $mode  = (string)$this->input->post('mode');
        $ref   = $this->_clean($this->input->post('reference'));
        $rem   = trim((string)$this->input->post('remarks'));

        if ($date === '' || $date > date('Y-m-d')) return $this->_json(false, 'Enter a valid invoice date (not in the future).');
        if ($smId) {
            $this->load->model('Fee_model');
            if (!$this->Fee_model->get_student($smId, $amId)) return $this->_json(false, 'Student not found in this academic year.');
            $cust = '';
        } elseif ($cust === '' || mb_strlen($cust) > 150) {
            return $this->_json(false, 'Select a student or enter the customer name.');
        }
        if ($phone !== '' && !preg_match('/^[0-9+\- ]{6,20}$/', $phone)) return $this->_json(false, 'Enter a valid phone number.');
        if ($disc === false || $paid === false) return $this->_json(false, 'Discount and amount paid must be valid numbers.');
        if ($paid && !in_array($mode, Store_model::$pay_modes, true)) return $this->_json(false, 'Select the payment mode.');
        if ($paid && $mode !== 'Cash' && $ref === '') return $this->_json(false, 'Enter the payment reference / transaction no.');
        if (mb_strlen($ref) > 100 || mb_strlen($rem) > 500) return $this->_json(false, 'Reference or remarks is too long.');

        $lines = $this->_lines(false);
        if (is_string($lines)) return $this->_json(false, $lines);

        $res = $this->Store->save_invoice($amId,
            array('date' => $date, 'student' => $smId, 'customer' => $cust, 'phone' => $phone, 'discount' => (float)$disc, 'remarks' => $rem),
            $lines, array('amount' => (float)$paid, 'mode' => $mode ?: 'Cash', 'reference' => $ref), $this->_user());
        return $this->_json($res['ok'], $res['msg'], array('id' => $res['ok'] ? $res['id'] : 0,
            'print_url' => $res['ok'] ? site_url('store_invoice/' . $res['id']) : ''));
    }

    /* printable invoice (no admin layout) */
    public function store_invoice($id = 0)
    {
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_view'])) return $this->_deny();
        $v = $this->Store->get_invoice((int)$id);
        if (!$v) show_404();
        $this->load->view('store_invoice_print', array('v' => $v, 'user_name' => $this->_user_name($v->sinUser)));
    }

    private function _user_name($id)
    {
        if (!$id) return '';
        $e = $this->db->select('emName')->where('emUserId', (int)$id)->get('employee_master')->row();
        if ($e && $e->emName !== '') return $e->emName;
        $u = $this->db->select('name')->where('user_id', (int)$id)->get('user_login')->row();
        return $u ? $u->name : '';
    }

    public function store_payment_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        $amt  = $this->_num($this->input->post('amount'));
        $date = $this->_date((string)$this->input->post('date'));
        $mode = (string)$this->input->post('mode');
        $ref  = $this->_clean($this->input->post('reference'));
        if (!$amt)                                     return $this->_json(false, 'Enter the amount.');
        if ($date === '' || $date > date('Y-m-d'))     return $this->_json(false, 'Enter a valid date.');
        if (!in_array($mode, Store_model::$pay_modes, true)) return $this->_json(false, 'Select the payment mode.');
        if ($mode !== 'Cash' && $ref === '')           return $this->_json(false, 'Enter the reference / transaction no.');
        $res = $this->Store->add_payment((int)$this->input->post('id'), $amt, $date, $mode, mb_substr($ref, 0, 100), $this->_user());
        return $this->_json($res['ok'], $res['msg']);
    }

    public function store_invoice_cancel()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('store_invoices');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to cancel invoices.');
        $why = $this->_clean($this->input->post('reason'));
        if ($why === '' || mb_strlen($why) > 255) return $this->_json(false, 'Enter the reason for cancelling.');
        $res = $this->Store->cancel_invoice((int)$this->input->post('id'), $why, $this->_user());
        return $this->_json($res['ok'], $res['msg']);
    }
}
