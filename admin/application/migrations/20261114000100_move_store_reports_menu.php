<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Store Reports moves from the Store menu to the Reports menu (after Student / Fee Reports). */
class Migration_Move_store_reports_menu extends CI_Migration
{
    public function up()
    {
        $this->_move('REPORTS', 3);
    }

    public function down()
    {
        $this->_move('STORE', 7);
    }

    private function _move($parentName, $order)
    {
        $parent = $this->db->select('menu_id')->where('menu_name', $parentName)->get('menus')->row();
        if (!$parent) return;
        $this->db->where('menu_name', 'STORE_REPORTS')->update('menus', array(
            'parent_menu_id' => (int)$parent->menu_id,
            'display_order'  => $order,
        ));
    }
}
