
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionController extends CI_Controller
{


  function __construct()
  {
    parent::__construct();
    $this->load->model('Paper_model');
    $this->load->model('Slider_model');
    $this->load->model('Permissions_Model');

     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}

  }






 public function menu_list()
{
    $data['tree'] = $this->_menu_tree();

    $this->load->view('header');
    $this->load->view('menu_list', $data);
    $this->load->view('footer');
}

// parents with ->children attached (your get_menu_tree, made private)
private function _menu_tree()
{
    $rows = $this->db->order_by('display_order', 'ASC')
                     ->order_by('menu_id', 'ASC')
                     ->get('menus')->result();

    $parents = [];
    $children = [];
    foreach ($rows as $row) {
        if (empty($row->parent_menu_id)) {
            $parents[] = $row;
        } else {
            $children[$row->parent_menu_id][] = $row;
        }
    }
    foreach ($parents as $p) {
        $p->children = isset($children[$p->menu_id]) ? $children[$p->menu_id] : [];
    }
    return $parents;
}

// next display order inside a parent (or top level)
private function _next_order($parent_id)
{
    $this->db->select_max('display_order');
    if (empty($parent_id)) {
        $this->db->where('parent_menu_id IS NULL');
    } else {
        $this->db->where('parent_menu_id', $parent_id);
    }
    $row = $this->db->get('menus')->row();
    return ($row && $row->display_order !== null) ? $row->display_order + 1 : 1;
}

// reads and validates the posted form; returns [data, error]
private function _menu_input($self_id = 0)
{
    $name   = strtoupper(preg_replace('/\s+/', '_', trim($this->input->post('menu_name', TRUE))));
    $disp   = trim($this->input->post('display_name', TRUE));
    $link   = trim($this->input->post('menu_link', TRUE));
    $parent = (int) $this->input->post('parent_menu_id');
    $order  = (int) $this->input->post('display_order');

    if ($name === '')                         return [null, 'Please enter the menu name.'];
    if (!preg_match('/^[A-Z0-9_]+$/', $name)) return [null, 'Menu name can use only letters, numbers and underscore.'];
    if ($disp === '')                         return [null, 'Please enter the display name.'];
    if ($parent > 0 && $parent === $self_id)  return [null, 'A menu cannot be its own parent.'];

    $this->db->where('menu_name', $name);
    if ($self_id) $this->db->where('menu_id !=', $self_id);
    if ($this->db->get('menus')->num_rows() > 0) return [null, 'This menu name already exists.'];

    if ($parent > 0) {
        $p = $this->db->where('menu_id', $parent)->get('menus')->row();
        if (!$p || !empty($p->parent_menu_id)) return [null, 'Invalid parent menu.'];
    }

    if ($order < 1) $order = $this->_next_order($parent ?: null);

    return [[
        'parent_menu_id' => $parent > 0 ? $parent : null,
        'menu_name'      => $name,
        'display_name'   => $disp,
        'menu_link'      => $link !== '' ? $link : null,
        'display_order'  => $order
    ], null];
}

// ADD
public function menu_save()
{
    list($data, $err) = $this->_menu_input(0);
    if ($err) return $this->_jsons(false, $err);

    $data['status'] = 1;
    $this->db->insert('menus', $data);
    return $this->_jsons(true, 'Menu added successfully.');
}

// EDIT
public function menu_update()
{
    $id = (int) $this->input->post('menu_id');
    if ($id <= 0) return $this->_jsons(false, 'Invalid menu.');

    list($data, $err) = $this->_menu_input($id);
    if ($err) return $this->_jsons(false, $err);

    // a menu that has sub-menus must stay top level
    $has_kids = $this->db->where('parent_menu_id', $id)->count_all_results('menus') > 0;
    if ($has_kids && !empty($data['parent_menu_id'])) {
        return $this->_jsons(false, 'This menu has sub-menus, so it must stay a top-level menu.');
    }

    $this->db->where('menu_id', $id)->update('menus', $data);
    return $this->_jsons(true, 'Menu updated successfully.');
}

// DELETE
public function menu_delete()
{
    $id = (int) $this->input->post('menu_id');
    if ($id <= 0) return $this->_jsons(false, 'Invalid menu.');

    if ($this->db->where('parent_menu_id', $id)->count_all_results('menus') > 0) {
        return $this->_jsons(false, 'This menu has sub-menus. Delete the sub-menus first.');
    }

    $this->db->where('menu_id', $id)->delete('menus');
    return $this->_jsons(true, 'Menu deleted successfully.');
}

// ACTIVE (1) / INACTIVE (0)
public function menu_status()
{
    $id     = (int) $this->input->post('menu_id');
    $status = (int) $this->input->post('status') === 1 ? 1 : 0;
    if ($id <= 0) return $this->_jsons(false, 'Invalid menu.');

    $this->db->where('menu_id', $id)->update('menus', ['status' => $status]);

    // disabling a parent also disables its sub-menus
    if ($status === 0) {
        $this->db->where('parent_menu_id', $id)->update('menus', ['status' => 0]);
    }
    return $this->_jsons(true, $status ? 'Menu enabled.' : 'Menu disabled.');
}

// helper
private function _jsons($success, $message)
{
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['success' => $success, 'message' => $message]));
}





/////////////////////////////////////////////


// (in __construct or here) make sure the model is loaded:
// $this->load->model('Permissions_Model');


  // PAGE
    public function add_menu_permission()
    {
        $data['roles'] = $this->Permissions_Model->get_all_roles();
        $data['menus'] = $this->build_menu_tree($this->Permissions_Model->get_all_menus());

        $this->load->view('header');
        $this->load->view('add_menu_permission', $data);
        $this->load->view('footer');
    }

    private function build_menu_tree($menus)
    {
        $parents  = [];
        $children = [];

        foreach ($menus as $menu) {
            $menu->children = [];
            if (empty($menu->parent_menu_id)) {
                $parents[$menu->menu_id] = $menu;
            } else {
                $children[$menu->parent_menu_id][] = $menu;
            }
        }

        foreach ($parents as $id => $p) {
            if (isset($children[$id])) {
                $parents[$id]->children = $children[$id];
            }
        }

        $parents = array_values($parents);
        usort($parents, function ($a, $b) {
            return $a->display_order - $b->display_order;
        });
        return $parents;
    }

    // AJAX: load saved permissions of one role
    public function get_role_permissions()
    {
        $role_id = (int) $this->input->post('role_id');
        if ($role_id <= 0) {
            return $this->_jsons(false, 'Invalid role.');
        }

        $rows = $this->Permissions_Model->get_permissions_by_role($role_id);

        $out = new stdClass();
        foreach ($rows as $menu_id => $p) {
            $out->$menu_id = [
                'v' => (int) $p->can_view,
                'a' => (int) $p->can_add,
                'e' => (int) $p->can_edit,
                'd' => (int) $p->can_delete
            ];
        }
        return $this->_jsons(true, '', ['permissions' => $out]);
    }

    // AJAX: save permissions of one role
    public function save_menu_permissions()
    {
        $role_id = (int) $this->input->post('role_id');
        $list    = json_decode($this->input->post('permissions'), true);

        if ($role_id <= 0 || !is_array($list)) {
            return $this->_jsons(false, 'Invalid data.');
        }
        if (!$this->Permissions_Model->role_exists($role_id)) {
            return $this->_jsons(false, 'Role not found.');
        }

        $valid = $this->Permissions_Model->get_valid_menu_ids();

        $permissions = [];
        foreach ($list as $menu_id => $p) {
            $menu_id = (int) $menu_id;
            if (!isset($valid[$menu_id])) continue;

            $v = !empty($p['v']) ? 1 : 0;
            $a = !empty($p['a']) ? 1 : 0;
            $e = !empty($p['e']) ? 1 : 0;
            $d = !empty($p['d']) ? 1 : 0;
            if ($a || $e || $d) $v = 1;   // add/edit/delete needs view

            $permissions[$menu_id] = [
                'can_view' => $v, 'can_add' => $a, 'can_edit' => $e, 'can_delete' => $d
            ];
        }

        return $this->Permissions_Model->save_permissions($role_id, $permissions)
            ? $this->_jsons(true, 'Permissions saved successfully.')
            : $this->_jsons(false, 'Failed to save permissions.');
    }

    // JSON helper

























/////////////////////////////////////////////








}