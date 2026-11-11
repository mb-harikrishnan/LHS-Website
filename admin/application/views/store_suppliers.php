<?php /* $suppliers, $perm */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']); ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Suppliers</h1><p class="page-sub">Publishers, uniform tailors and stationery vendors you purchase from.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openAdd">+ Add Supplier</button></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>GSTIN</th><th>Address</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($suppliers as $n => $s):
          $row = array('id' => (int)$s->ssId, 'name' => $s->ssName, 'phone' => (string)$s->ssPhone, 'gstin' => (string)$s->ssGstin,
                       'address' => (string)$s->ssAddress, 'active' => (int)$s->ssActive); ?>
          <tr data-s="<?= html_escape(json_encode($row)) ?>">
            <td><?= $n + 1 ?></td><td><strong><?= html_escape($s->ssName) ?></strong></td><td><?= html_escape($s->ssPhone) ?></td>
            <td><?= html_escape($s->ssGstin) ?></td><td class="small"><?= html_escape($s->ssAddress) ?></td>
            <td><?= $s->ssActive ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$suppliers): ?><div class="empty">No suppliers yet.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="supModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true" aria-labelledby="supTitle">
    <div class="modal-head"><h2 id="supTitle">Add Supplier</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="supForm" novalidate>
      <input type="hidden" id="sId" value="0">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="sName">Name <span class="req">*</span></label><input class="field-input" id="sName" maxlength="150"></div>
          <div><label class="field-label" for="sPhone">Phone</label><input class="field-input" id="sPhone" maxlength="20"></div>
          <div><label class="field-label" for="sGst">GSTIN</label><input class="field-input" id="sGst" maxlength="15"></div>
          <div class="full"><label class="field-label" for="sAddr">Address</label><textarea class="field-input" id="sAddr" maxlength="500"></textarea></div>
          <label class="check"><input type="checkbox" id="sActive" checked> Active</label>
        </div>
        <p class="field-error" id="supErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const m = U.modal('supModal');
  function open(s) {
    $('supErr').hidden = true; $('sId').value = s ? s.id : 0; $('supTitle').textContent = s ? 'Edit Supplier' : 'Add Supplier';
    $('sName').value = s ? s.name : ''; $('sPhone').value = s ? s.phone : ''; $('sGst').value = s ? s.gstin : '';
    $('sAddr').value = s ? s.address : ''; $('sActive').checked = s ? !!s.active : true;
    m.show(); $('sName').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(null));
  $('supForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('sId').value); fd.append('name', $('sName').value.trim()); fd.append('phone', $('sPhone').value.trim());
    fd.append('gstin', $('sGst').value.trim()); fd.append('address', $('sAddr').value.trim());
    if ($('sActive').checked) fd.append('active', 1);
    const res = await U.post('<?= site_url('store_supplier_save') ?>', fd);
    if (!res.status) { $('supErr').textContent = res.msg; $('supErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });
  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const s = JSON.parse(tr.dataset.s);
    if (e.target.closest('[data-edit]')) return open(s);
    if (e.target.closest('[data-del]')) {
      const c = await Swal.fire({title: 'Delete ' + s.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
      if (!c.isConfirmed) return;
      const fd = new FormData(); fd.append('id', s.id);
      const res = await U.post('<?= site_url('store_supplier_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });
})();
</script>
