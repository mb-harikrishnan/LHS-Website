<?php
$pageTitle = 'Menu Permissions';
$breadcrumb = 'Menu Permissions';
$activePage = 'menu_permissions';
$showGlobalSearch = false;
?>

<link rel="stylesheet" href="<?php echo base_url('assets/css/exam.css'); ?>">


<style>
  .perm-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  .perm-table th, .perm-table td { padding: 8px 10px; border-bottom: 1px solid #eee; text-align: center; }
  .perm-table th:first-child, .perm-table td:first-child { text-align: left; }
  .perm-table .menu-name { display: flex; align-items: center; gap: 8px; }
  .perm-table .child-row .menu-name { padding-left: 28px; color: #555; }
  .perm-table tbody tr:hover { background: #fafafa; }
  .perm-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
 
  .role-picker { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
  .role-picker label { font-weight: 600; color: var(--blue, #1e3a8a); font-size: 14px; }
 
  #roleSelect {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    min-width: 260px;
    padding: 9px 36px 9px 14px;
    font-size: 14px;
    color: #1f2937;
    background-color: #fff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  #roleSelect:hover { border-color: #9ca3af; }
  #roleSelect:focus {
    outline: none;
    border-color: var(--blue, #1e3a8a);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
  }
</style>

<!-- Menu Permissions Card -->
<div class="card">
  <div class="card-head">
    <div class="card-title">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round">
        <path d="M9 12l2 2 4-4"/>
        <circle cx="12" cy="12" r="10"/>
      </svg>
      Menu Permissions
    </div>
  </div>

  <form id="permissionForm" method="post">
    <div class="role-picker">
      <label for="roleSelect">Select Role:</label>
      <select id="roleSelect" name="role_id" class="form-control" required>
        <option value="">-- Select Role --</option>
        <?php foreach ($roles as $role): ?>
          <option value="<?php echo $role->role_id; ?>"><?php echo htmlspecialchars($role->role_name); ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <table class="perm-table" id="permTable">
      <thead>
        <tr>
          <th>Menu</th>
          <th><input type="checkbox" data-col="can_view" class="col-all"> View</th>
          <th><input type="checkbox" data-col="can_add" class="col-all"> Add</th>
          <th><input type="checkbox" data-col="can_edit" class="col-all"> Edit</th>
          <th><input type="checkbox" data-col="can_delete" class="col-all"> Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($menus as $menu): ?>
          <tr data-menu-id="<?php echo $menu->menu_id; ?>">
            <td>
              <div class="menu-name">
                <input type="checkbox" class="row-all">
                <?php echo htmlspecialchars($menu->display_name); ?>
              </div>
            </td>
            <td><input type="checkbox" class="perm-cb" data-col="can_view" name="permissions[<?php echo $menu->menu_id; ?>][can_view]" value="1"></td>
            <td><input type="checkbox" class="perm-cb" data-col="can_add" name="permissions[<?php echo $menu->menu_id; ?>][can_add]" value="1"></td>
            <td><input type="checkbox" class="perm-cb" data-col="can_edit" name="permissions[<?php echo $menu->menu_id; ?>][can_edit]" value="1"></td>
            <td><input type="checkbox" class="perm-cb" data-col="can_delete" name="permissions[<?php echo $menu->menu_id; ?>][can_delete]" value="1"></td>
          </tr>
          <?php foreach ($menu->children as $child): ?>
            <tr class="child-row" data-menu-id="<?php echo $child->menu_id; ?>">
              <td>
                <div class="menu-name">
                  <input type="checkbox" class="row-all">
                  <?php echo htmlspecialchars($child->display_name); ?>
                </div>
              </td>
              <td><input type="checkbox" class="perm-cb" data-col="can_view" name="permissions[<?php echo $child->menu_id; ?>][can_view]" value="1"></td>
              <td><input type="checkbox" class="perm-cb" data-col="can_add" name="permissions[<?php echo $child->menu_id; ?>][can_add]" value="1"></td>
              <td><input type="checkbox" class="perm-cb" data-col="can_edit" name="permissions[<?php echo $child->menu_id; ?>][can_edit]" value="1"></td>
              <td><input type="checkbox" class="perm-cb" data-col="can_delete" name="permissions[<?php echo $child->menu_id; ?>][can_delete]" value="1"></td>
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="perm-actions">
      <button type="submit" class="card-action" id="saveBtn">
        <i class="fa fa-save"></i> Save Permissions
      </button>
    </div>
  </form>
</div>

<script>
(function () {
  var baseUrl   = '<?php echo base_url(); ?>';
  var roleSelect = document.getElementById('roleSelect');
  var form       = document.getElementById('permissionForm');
  var table      = document.getElementById('permTable');

  function clearAllChecks() {
    table.querySelectorAll('.perm-cb, .row-all, .col-all').forEach(function (cb) {
      cb.checked = false;
    });
  }

  // Load saved permissions whenever the role changes
  roleSelect.addEventListener('change', function () {
    clearAllChecks();
    var roleId = roleSelect.value;
    if (!roleId) return;

    fetch(baseUrl + 'get_permissions/' + roleId)
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (!data.status) return;
        Object.keys(data.permissions).forEach(function (menuId) {
          var perm = data.permissions[menuId];
          var row = table.querySelector('tr[data-menu-id="' + menuId + '"]');
          if (!row) return;
          ['can_view', 'can_add', 'can_edit', 'can_delete'].forEach(function (col) {
            if (perm[col] == 1) {
              var cb = row.querySelector('.perm-cb[data-col="' + col + '"]');
              if (cb) cb.checked = true;
            }
          });
        });
      })
      .catch(function (err) { console.error('Failed to load permissions:', err); });
  });

  // Row master checkbox -> tick view/add/edit/delete for that menu
  table.addEventListener('change', function (e) {
    if (e.target.classList.contains('row-all')) {
      var row = e.target.closest('tr');
      row.querySelectorAll('.perm-cb').forEach(function (cb) {
        cb.checked = e.target.checked;
      });
    }
    // Column master checkbox -> tick that permission down the whole table
    if (e.target.classList.contains('col-all')) {
      var col = e.target.dataset.col;
      table.querySelectorAll('.perm-cb[data-col="' + col + '"]').forEach(function (cb) {
        cb.checked = e.target.checked;
      });
    }
  });

  // Submit via AJAX
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (!roleSelect.value) {
      alert('Please select a role first.');
      return;
    }

    var formData = new FormData(form);

    fetch(baseUrl + 'save', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        alert(data.message);
      })
      .catch(function (err) {
        console.error('Save failed:', err);
        alert('Something went wrong while saving.');
      });
  });
})();
</script>