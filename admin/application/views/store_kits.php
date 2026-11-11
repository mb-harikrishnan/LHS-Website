<?php /* Class kits.  $kits (with items, total), $items (picker list), $classes, $perm */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']); ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Class Kits</h1><p class="page-sub">Ready sets such as "Class V Book Set" or "Boys Uniform Set". A kit adds all its items to an invoice in one click.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openAdd">+ Add Kit</button></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Kit</th><th>Class</th><th>Items</th><th class="num">Kit Price</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($kits as $k):
          $lines = array(); $names = array();
          foreach ($k->items as $it) {
            $lines[] = array('id' => (int)$it->skiSiId, 'qty' => (float)$it->skiQty);
            $names[] = html_escape($it->siName . ($it->siSize ? ' (' . $it->siSize . ')' : '')) . ' × ' . (float)$it->skiQty . ($it->siActive ? '' : ' <span class="badge badge-gray">inactive</span>');
          }
          $row = array('id' => (int)$k->skId, 'name' => $k->skName, 'class' => (int)$k->skCmId, 'active' => (int)$k->skActive, 'items' => $lines); ?>
          <tr data-k="<?= html_escape(json_encode($row)) ?>">
            <td><strong><?= html_escape($k->skName) ?></strong></td>
            <td><?= html_escape($k->cmName ?: 'Any') ?></td>
            <td class="small"><?= implode('<br>', $names) ?></td>
            <td class="num">₹<?= fee_money($k->total) ?></td>
            <td><?= $k->skActive ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$kits): ?><div class="empty">No kits yet.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="kitModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="kitTitle">
    <div class="modal-head"><h2 id="kitTitle">Add Kit</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="kitForm" novalidate>
      <input type="hidden" id="kId" value="0">
      <div class="modal-body">
        <div class="grid g3" style="margin-top:12px">
          <div><label class="field-label" for="kName">Kit Name <span class="req">*</span></label><input class="field-input" id="kName" maxlength="100"></div>
          <div><label class="field-label" for="kClass">Class</label>
            <select class="field-input" id="kClass"><option value="">Any class</option><?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option><?php endforeach; ?></select></div>
          <div style="display:flex;align-items:flex-end"><label class="check"><input type="checkbox" id="kActive" checked> Active</label></div>
        </div>
        <div class="sec-title">Items</div>
        <input class="field-input" id="kPick" placeholder="Type item name or code to add…" autocomplete="off">
        <table class="table compact" style="margin-top:8px"><thead><tr><th>Item</th><th class="num">Qty</th><th></th></tr></thead><tbody id="kLines"></tbody></table>
        <p class="field-error" id="kitErr" hidden></p>
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
  const ITEMS = <?= json_encode($items) ?>;
  const m = U.modal('kitModal');
  const ed = U.lines({tbody: $('kLines'), items: ITEMS, mode: 'kit'});
  U.picker($('kPick'), ITEMS, it => ed.add(it.id, 1), false);

  function open(k) {
    $('kitErr').hidden = true; $('kId').value = k ? k.id : 0; $('kitTitle').textContent = k ? 'Edit Kit' : 'Add Kit';
    $('kName').value = k ? k.name : ''; $('kClass').value = k && k.class ? k.class : ''; $('kActive').checked = k ? !!k.active : true;
    ed.clear(); (k ? k.items : []).forEach(l => ed.add(l.id, l.qty));
    m.show(); $('kName').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(null));
  $('kitForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('kId').value); fd.append('name', $('kName').value.trim()); fd.append('class', $('kClass').value);
    if ($('kActive').checked) fd.append('active', 1);
    const err = ed.fill(fd);
    if (err) { $('kitErr').textContent = err; $('kitErr').hidden = false; return; }
    const res = await U.post('<?= site_url('store_kit_save') ?>', fd);
    if (!res.status) { $('kitErr').textContent = res.msg; $('kitErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });
  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const k = JSON.parse(tr.dataset.k);
    if (e.target.closest('[data-edit]')) return open(k);
    if (e.target.closest('[data-del]')) {
      const c = await Swal.fire({title: 'Delete ' + k.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
      if (!c.isConfirmed) return;
      const fd = new FormData(); fd.append('id', k.id);
      const res = await U.post('<?= site_url('store_kit_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });
})();
</script>
