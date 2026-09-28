<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionsController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
        $this->load->model('Permissions_Model');

          if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}
		
	}


    public function accademic_list()
    {
        $data['academic'] = $this->db->get('academic_master')->result();

        $this->load->view('members_area/header');
        $this->load->view('members_area/accademic_list', $data);
        $this->load->view('members_area/footer');

    }

public function delete_accademic()
{
    $id = $this->input->post('id');

    $this->db->where('amId', $id);
    $result = $this->db->update('academic_master', ['amIsCurrent' => 0]);

    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}



public function add_academic()
{
    
    $this->load->view('members_area/header');
    $this->load->view('members_area/add_academic');
    $this->load->view('members_area/footer');
}



public function check_academic_year()
{
    $year = $this->input->post('academic_year');

    if(!preg_match('/^\d{4}-\d{2}$/',$year))
    {
        echo json_encode([
            "status"=>"invalid"
        ]);
        return;
    }

    $count = $this->db
        ->where('amYear',$year)
        ->count_all_results('academic_master');

    if($count>0)
    {
        echo json_encode([
            "status"=>"exists"
        ]);
    }
    else
    {
        echo json_encode([
            "status"=>"available"
        ]);
    }
}

public function insert_academic()
{
    $year = trim($this->input->post('academic_year'));

    // Format validation
    if (!preg_match('/^\d{4}-\d{2}$/', $year)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Academic Year format.'
        ]);
        return;
    }

    // Duplicate year check
    $exists = $this->db->where('amYear', $year)
                       ->count_all_results('academic_master');

    if ($exists > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Academic Year already exists.'
        ]);
        return;
    }

    // Check if an active year already exists
    $active = $this->db->where('amIsCurrent', 1)
                       ->count_all_results('academic_master');

    if ($active > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'An active Academic Year already exists. Please delete or update it first.'
        ]);
        return;
    }

    // Insert
    $result = $this->db->insert('academic_master', [
        'amYear'      => $year,
        'amIsCurrent' => 1
    ]);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Academic Year added successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ]);
    }
}







 public function term_list()
    {
        $data['term'] = $this->db->get('term_master')->result();

        $this->load->view('members_area/header');
        $this->load->view('members_area/term_list', $data);
        $this->load->view('members_area/footer');

    }
public function delete_term()
{
    $id = $this->input->post('id');

    $this->db->where('tmId', $id);   
    $result = $this->db->delete('term_master');

    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}




public function add_term()
{
    
    $this->load->view('members_area/header');
    $this->load->view('members_area/add_term');
    $this->load->view('members_area/footer');
}


public function check_term()
{
    $term = trim($this->input->post('term'));
    $code = trim($this->input->post('code'));

    $termExists = $this->db
        ->where('tmName',$term)
        ->count_all_results('term_master');

    $codeExists = $this->db
        ->where('tmCode',$code)
        ->count_all_results('term_master');

    echo json_encode([
        'term'=>$termExists ? 'exists' : 'available',
        'code'=>$codeExists ? 'exists' : 'available'
    ]);
}
public function insert_term()
{
    $term = trim($this->input->post('term'));
    $code = trim($this->input->post('code'));

    if($term=="" || $code=="")
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'All fields are required.'
        ]);
        return;
    }

    $exists = $this->db
        ->group_start()
            ->where('tmName',$term)
            ->or_where('tmCode',$code)
        ->group_end()
        ->count_all_results('term_master');

    if($exists>0)
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Term or Code already exists.'
        ]);
        return;
    }

    $result = $this->db->insert('term_master',[
        'tmName'=>$term,
        'tmCode'=>$code
    ]);

    if($result)
    {
        echo json_encode([
            'status'=>'success',
            'message'=>'Term added successfully.'
        ]);
    }
    else
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Something went wrong.'
        ]);
    }
}








 public function user_role_list()
    {
        $data['role'] = $this->db->get('user_roles')->result();

        $this->load->view('members_area/header');
        $this->load->view('members_area/user_role_list', $data);
        $this->load->view('members_area/footer');

    }



    public function update_role_status()
{
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('role_id', $id);
    $result = $this->db->update('user_roles', [
        'status' => $status
    ]);

    echo $result ? 1 : 0;
}



public function add_user_role()
{


     $this->load->view('members_area/header');
    $this->load->view('members_area/add_user_role');
    $this->load->view('members_area/footer');

}


public function check_role()
{
    $role = trim($this->input->post('role_name'));

    $exists = $this->db->where('role_name', $role)
                       ->count_all_results('user_roles');

    if($exists > 0)
    {
        echo json_encode([
            'status' => 'exists'
        ]);
    }
    else
    {
        echo json_encode([
            'status' => 'available'
        ]);
    }
}


public function insert_role()
{
    $role = trim($this->input->post('role_name'));

    if($role == "")
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Role Name is required.'
        ]);
        return;
    }

    $exists = $this->db->where('role_name', $role)
                       ->count_all_results('user_roles');

    if($exists > 0)
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Role already exists.'
        ]);
        return;
    }

    $result = $this->db->insert('user_roles', [
        'role_name' => $role,
        'status'    => 1
    ]);

    if($result)
    {
        echo json_encode([
            'status' => 'success',
            'message' => 'Role added successfully.'
        ]);
    }
    else
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ]);
    }
}







 public function menu_list()
{
    $data['menu'] = $this->Permissions_Model->get_menu_tree();

    $this->load->view('members_area/header');
    $this->load->view('members_area/menu_list', $data);
    $this->load->view('members_area/footer');

}





 public function add_menu_permission()
    {
        $data['pageTitle']        = 'Menu Permissions';
        $data['breadcrumb']       = 'Menu Permissions';
        $data['activePage']       = 'menu_permissions';
        $data['showGlobalSearch'] = false;
 
        $data['roles'] = $this->Permissions_Model->get_all_roles();
        $data['menus'] = $this->build_menu_tree(
            $this->Permissions_Model->get_all_menus()
        );
            $this->load->view('members_area/header');
            $this->load->view('members_area/add_menu_permission', $data);
            $this->load->view('members_area/footer');
    }
 
    // GET  menu_permissions/get_permissions/{role_id}
    // Returns saved permissions for a role so the checkboxes can be pre-ticked
    public function get_permissions($role_id = 0)
    {
        $role_id = (int) $role_id;
        $permissions = $this->Permissions_Model->get_permissions_by_role($role_id);
 
        $out = [];
        foreach ($permissions as $menu_id => $p) {
            $out[$menu_id] = [
                'can_view'   => (int) $p->can_view,
                'can_add'    => (int) $p->can_add,
                'can_edit'   => (int) $p->can_edit,
                'can_delete' => (int) $p->can_delete,
            ];
        }
 
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => true, 'permissions' => $out]));
    }
 
    // POST menu_permissions/save
    public function save()
    {
        $role_id     = (int) $this->input->post('role_id');
        $permissions = $this->input->post('permissions'); // [menu_id => ['can_view'=>'1', ...]]
 
        if (!$role_id) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => false, 'message' => 'Please select a role.']));
            return;
        }
 
        $ok = $this->Permissions_Model->save_permissions($role_id, (array) $permissions);
 
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                'status'  => $ok ? 'success' : 'error',
                 'message' => $ok ? 'Permissions saved successfully.' : 'Failed to save permissions.'
             ]));
    }
 
    // Groups the flat menu list into parent -> children so the view can indent submenus
    private function build_menu_tree($menus)
    {
        $tree     = [];
        $children = [];
 
        foreach ($menus as $menu) {
            $menu->children = [];
            if ($menu->parent_menu_id === null) {
                $tree[$menu->menu_id] = $menu;
            } else {
                $children[$menu->parent_menu_id][] = $menu;
            }
        }
 
        foreach ($tree as $menu_id => $menu) {
            if (isset($children[$menu_id])) {
                $tree[$menu_id]->children = $children[$menu_id];
            }
        }
 
        return $tree;
    }








public function add_menu()
{
    $data['pageTitle']        = 'Add Menu';
    $data['breadcrumb']       = 'Add Menu';
    $data['activePage']       = 'add_menu';
    $data['showGlobalSearch'] = false;

    $data['menus'] = $this->Permissions_Model->get_all_menus_parent();

    if ($this->input->post()) {
        $this->form_validation->set_rules('menu_name', 'Menu Name', 'required|trim');
        $this->form_validation->set_rules('display_name', 'Display Name', 'required|trim');
        $this->form_validation->set_rules('menu_link', 'Menu Link', 'trim');
        $this->form_validation->set_rules('parent_menu_id', 'Parent Menu', 'trim');

        if ($this->form_validation->run() === TRUE) {

            $parent_menu_id = $this->input->post('parent_menu_id');
            $parent_menu_id = ($parent_menu_id === '' ) ? null : $parent_menu_id;

            $next_order = $this->Permissions_Model->get_next_display_order($parent_menu_id);

            $insert_data = array(
                'parent_menu_id' => $parent_menu_id,
                'menu_name'      => strtoupper(str_replace(' ', '_', $this->input->post('menu_name'))),
                'display_name'   => $this->input->post('display_name'),
                'menu_link'      => $this->input->post('menu_link') ?: null,
                'display_order'  => $next_order,
                'status'         => 1
            );

            $inserted_id = $this->Permissions_Model->insert_menu($insert_data);

            if ($inserted_id) {
                $this->session->set_flashdata('success', 'Menu added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add menu.');
            }

            redirect('add_menu');
        }
    }

    $this->load->view('members_area/header');
    $this->load->view('members_area/add_menu', $data);
    $this->load->view('members_area/footer');
}







public function toggle_menu_status()
{
    $menu_id = $this->input->post('menu_id');
    $status  = $this->input->post('status');

    $this->db->where('menu_id', $menu_id)
             ->update('menus', ['status' => $status]);

    echo json_encode(['success' => true]);
}




public function edit_menu($menu_id = null)
{
    if (!$menu_id) {
        redirect('menu_list');
    }

    // Handle form submission
    if ($this->input->post()) {

        $this->form_validation->set_rules('menu_name', 'Menu Name', 'required|trim');
        $this->form_validation->set_rules('display_name', 'Display Name', 'required|trim');

        if ($this->form_validation->run() === TRUE) {

            $posted_id = $this->input->post('menu_id', true);

            $data = [
                'menu_name'      => $this->input->post('menu_name', true),
                'display_name'   => $this->input->post('display_name', true),
                'menu_link'      => $this->input->post('menu_link', true),
                'parent_menu_id' => $this->input->post('parent_menu_id', true) ?: null,
                'display_order'  => $this->input->post('display_order', true) ?: 0,
            ];

            $updated = $this->Permissions_Model->update_menu($posted_id, $data);

            if ($updated) {
                $this->session->set_flashdata('success', 'Menu updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update menu.');
            }

            redirect('menu_list');
        }
    }

    // Load the menu being edited + full menu list for the parent dropdown
    $data['menu']  = $this->Permissions_Model->get_menu_by_id($menu_id);
    $data['menus'] = $this->Permissions_Model->get_all_menus_s(); // flat list, no children needed here

    if (!$data['menu']) {
        $this->session->set_flashdata('error', 'Menu not found.');
        redirect('menu_list');
    }

    $this->load->view('members_area/header', $data);
    $this->load->view('members_area/edit_menu', $data);
    $this->load->view('members_area/footer');
}










}