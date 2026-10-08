<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* =========================================================
       ROLES  (table: user_roles -> role_id, role_name, status)
       ========================================================= */

    // all roles (Role List page)
    public function get_roles_all()
    {
        return $this->db->order_by('role_id', 'ASC')
                        ->get('user_roles')
                        ->result();
    }

    // only active roles (Permission page dropdown)
    public function get_all_roles()
    {
        return $this->db->select('role_id, role_name')
                        ->from('user_roles')
                        ->where('status', 1)
                        ->order_by('role_name', 'ASC')
                        ->get()
                        ->result();
    }

    public function role_exists($role_id)
    {
        return $this->db->where('role_id', (int) $role_id)
                        ->count_all_results('user_roles') > 0;
    }

    public function role_name_exists($name, $exclude_id = 0)
    {
        $this->db->where('LOWER(role_name)', strtolower($name));
        if ($exclude_id) {
            $this->db->where('role_id !=', (int) $exclude_id);
        }
        return $this->db->count_all_results('user_roles') > 0;
    }

    public function insert_role($name)
    {
        $this->db->insert('user_roles', ['role_name' => $name, 'status' => 1]);
        return $this->db->insert_id();
    }

    public function update_role($role_id, $name)
    {
        return $this->db->where('role_id', (int) $role_id)
                        ->update('user_roles', ['role_name' => $name]);
    }

    public function set_role_status($role_id, $status)
    {
        return $this->db->where('role_id', (int) $role_id)
                        ->update('user_roles', ['status' => (int) $status]);
    }

    // deletes the role and its permission rows together
    public function delete_role($role_id)
    {
        $this->db->trans_start();
        $this->db->where('role_id', (int) $role_id)->delete('user_roles_menu_permissions');
        $this->db->where('role_id', (int) $role_id)->delete('user_roles');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* =========================================================
       MENUS  (table: menus -> menu_id, parent_menu_id, menu_name,
               display_name, menu_link, display_order, status)
       ========================================================= */

    // all menus (Menu List page), returned as parents with ->children
    public function get_menu_tree()
    {
        $rows = $this->db->order_by('display_order', 'ASC')
                         ->order_by('menu_id', 'ASC')
                         ->get('menus')
                         ->result();
        return $this->build_tree($rows);
    }

    // active menus only (Permission page)
    public function get_all_menus()
    {
        return $this->db->select('menu_id, parent_menu_id, menu_name, display_name, menu_link, display_order')
                        ->from('menus')
                        ->where('status', 1)
                        ->order_by('parent_menu_id', 'ASC')
                        ->order_by('display_order', 'ASC')
                        ->get()
                        ->result();
    }

    // all menus for the parent dropdown
    public function get_all_menus_parent()
    {
        $this->db->select('menu_id, parent_menu_id, menu_name, display_name');
        $this->db->order_by('menu_name', 'ASC');
        return $this->db->get('menus')->result();
    }

    public function get_menu($menu_id)
    {
        return $this->db->where('menu_id', (int) $menu_id)->get('menus')->row();
    }

    public function get_valid_menu_ids()
    {
        $ids = [];
        foreach ($this->db->select('menu_id')->get('menus')->result() as $m) {
            $ids[(int) $m->menu_id] = true;
        }
        return $ids;
    }

    public function menu_name_exists($name, $exclude_id = 0)
    {
        $this->db->where('menu_name', $name);
        if ($exclude_id) {
            $this->db->where('menu_id !=', (int) $exclude_id);
        }
        return $this->db->count_all_results('menus') > 0;
    }

    public function menu_has_children($menu_id)
    {
        return $this->db->where('parent_menu_id', (int) $menu_id)
                        ->count_all_results('menus') > 0;
    }

    public function get_next_display_order($parent_menu_id = null)
    {
        $this->db->select_max('display_order');
        if (empty($parent_menu_id)) {
            $this->db->where('parent_menu_id IS NULL');
        } else {
            $this->db->where('parent_menu_id', (int) $parent_menu_id);
        }
        $row = $this->db->get('menus')->row();

        return ($row && $row->display_order !== null) ? $row->display_order + 1 : 1;
    }

    public function insert_menu($data)
    {
        $this->db->insert('menus', $data);
        return $this->db->insert_id();
    }

    public function update_menu($menu_id, $data)
    {
        return $this->db->where('menu_id', (int) $menu_id)->update('menus', $data);
    }

    // disabling a parent also disables its sub-menus
    public function set_menu_status($menu_id, $status)
    {
        $this->db->trans_start();
        $this->db->where('menu_id', (int) $menu_id)->update('menus', ['status' => (int) $status]);
        if ((int) $status === 0) {
            $this->db->where('parent_menu_id', (int) $menu_id)->update('menus', ['status' => 0]);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // deletes the menu and its permission rows together
    public function delete_menu($menu_id)
    {
        $this->db->trans_start();
        $this->db->where('menu_id', (int) $menu_id)->delete('user_roles_menu_permissions');
        $this->db->where('menu_id', (int) $menu_id)->delete('menus');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* =========================================================
       PERMISSIONS  (table: user_roles_menu_permissions)
       ========================================================= */

    public function get_permissions_by_role($role_id)
    {
        $rows = $this->db->select('menu_id, can_view, can_add, can_edit, can_delete')
                         ->from('user_roles_menu_permissions')
                         ->where('role_id', (int) $role_id)
                         ->get()
                         ->result();

        $permissions = [];
        foreach ($rows as $row) {
            $permissions[$row->menu_id] = $row;
        }
        return $permissions;
    }

    // replace all permissions of a role in one transaction
    public function save_permissions($role_id, $permissions)
    {
        $this->db->trans_start();

        $this->db->where('role_id', (int) $role_id)
                 ->delete('user_roles_menu_permissions');

        if (!empty($permissions)) {
            $insert_data = [];
            foreach ($permissions as $menu_id => $perm) {
                if (empty($perm['can_view']) && empty($perm['can_add'])
                    && empty($perm['can_edit']) && empty($perm['can_delete'])) {
                    continue; // nothing ticked
                }
                $insert_data[] = [
                    'role_id'    => (int) $role_id,
                    'menu_id'    => (int) $menu_id,
                    'can_view'   => !empty($perm['can_view'])   ? 1 : 0,
                    'can_add'    => !empty($perm['can_add'])    ? 1 : 0,
                    'can_edit'   => !empty($perm['can_edit'])   ? 1 : 0,
                    'can_delete' => !empty($perm['can_delete']) ? 1 : 0,
                ];
            }
            if (!empty($insert_data)) {
                $this->db->insert_batch('user_roles_menu_permissions', $insert_data);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* =========================================================
       helper
       ========================================================= */

    private function build_tree($rows)
    {
        $parents  = [];
        $children = [];

        foreach ($rows as $row) {
            $row->children = [];
            if (empty($row->parent_menu_id)) {
                $parents[$row->menu_id] = $row;
            } else {
                $children[$row->parent_menu_id][] = $row;
            }
        }
        foreach ($parents as $id => $p) {
            if (isset($children[$id])) {
                $parents[$id]->children = $children[$id];
            }
        }
        return array_values($parents);
    }
}