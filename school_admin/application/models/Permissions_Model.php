<?php
Class Permissions_Model extends CI_Model
{


public function get_menu_tree()
{
    $query  = $this->db->order_by('parent_menu_id', 'ASC')
                        ->order_by('display_order', 'ASC')
                        ->get('menus');

    $rows = $query->result();

    // Split into parents and children
    $parents  = [];
    $children = [];

    foreach ($rows as $row) {
        if ($row->parent_menu_id === null) {
            $parents[] = $row;
        } else {
            $children[$row->parent_menu_id][] = $row;
        }
    }

    // Attach children to each parent
    foreach ($parents as $parent) {
        $parent->children = isset($children[$parent->menu_id])
                             ? $children[$parent->menu_id]
                             : [];
    }

    return $parents;
}










  public function get_all_roles()
    {
        return $this->db->select('role_id, role_name')
                         ->from('user_roles')
                         ->where('status', 1)
                         ->order_by('role_name', 'ASC')
                         ->get()
                         ->result();
    }

    // Get all active menus, parents first, ordered by display_order
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

    // Get saved permissions for a given role, keyed by menu_id
    public function get_permissions_by_role($role_id)
    {
        $rows = $this->db->select('menu_id, can_view, can_add, can_edit, can_delete')
                          ->from('user_roles_menu_permissions')
                          ->where('role_id', $role_id)
                          ->get()
                          ->result();

        $permissions = [];
        foreach ($rows as $row) {
            $permissions[$row->menu_id] = $row;
        }
        return $permissions;
    }

    // Replace all permissions for a role in one transaction
    public function save_permissions($role_id, $permissions)
    {
        $this->db->trans_start();

        $this->db->where('role_id', $role_id)
                  ->delete('user_roles_menu_permissions');

        if (!empty($permissions)) {
            $insert_data = [];
            foreach ($permissions as $menu_id => $perm) {
                // Skip rows where nothing at all was ticked
                if (empty($perm['can_view']) && empty($perm['can_add'])
                    && empty($perm['can_edit']) && empty($perm['can_delete'])) {
                    continue;
                }

                $insert_data[] = [
                    'role_id'    => $role_id,
                    'menu_id'    => (int) $menu_id,
                    'can_view'   => !empty($perm['can_view']) ? 1 : 0,
                    'can_add'    => !empty($perm['can_add']) ? 1 : 0,
                    'can_edit'   => !empty($perm['can_edit']) ? 1 : 0,
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





// Get all menus for the parent dropdown
public function get_all_menus_parent()
{
    $this->db->select('menu_id, parent_menu_id, menu_name, display_name');
    $this->db->order_by('menu_name', 'ASC');
    $query = $this->db->get('menus');
    return $query->result();
}

// Get next display_order for a given parent (NULL = top-level)
public function get_next_display_order($parent_menu_id = null)
{
    $this->db->select_max('display_order');
    if (empty($parent_menu_id)) {
        $this->db->where('parent_menu_id IS NULL');
    } else {
        $this->db->where('parent_menu_id', $parent_menu_id);
    }
    $query = $this->db->get('menus');
    $row = $query->row();

    return ($row && $row->display_order !== null) ? $row->display_order + 1 : 1;
}

// Insert new menu
public function insert_menu($data)
{
    $this->db->insert('menus', $data);
    return $this->db->insert_id();
}


















}