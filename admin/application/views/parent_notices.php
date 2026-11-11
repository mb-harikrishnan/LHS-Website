<?php /* $notices, $perm, $classes, $div_map */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']); $today = date('Y-m-d'); ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Parent Notices</h1><p class="page-sub">Notices shown in the parent portal — to all parents, a class, or one division. Active School News is shown to parents too.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openAdd">+ Notice</button></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Date</th><th>Title</th><th>For</th><th>Show until</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($notices as $n):
          $row = array('id' => (int)$n->pnId, 'title' => $n->pnTitle, 'body' => $n->pnBody, 'date' => $n->pnDate, 'expiry' => (string)$n->pnExpiry,
                       'class' => (int)$n->pnCmId, 'division' => (int)$n->pnDmId, 'active' => (int)$n->pnActive);
          $live = $n->pnActive && $n->pnDate <= $today && (!$n->pnExpiry || $n->pnExpiry >= $today); ?>
          <tr data-n="<?= html_escape(json_encode($row)) ?>">
            <td><?= date('d-m-Y', strtotime($n->pnDate)) ?></td>
            <td><strong><?= html_escape($n->pnTitle) ?></strong><div class="small muted"><?= html_escape(mb_strimwidth($n->pnBody, 0, 90, '…')) ?></div></td>
            <td><?= $n->pnCmId ? html_escape(trim($n->cmName . ' ' . $n->dmName)) : 'All parents' ?></td>
            <td><?= $n->pnExpiry ? date('d-m-Y', strtotime($n->pnExpiry)) : '—' ?></td>
            <td><?= $live ? '<span class="badge badge-green">Showing</span>' : ($n->pnActive && $n->pnDate > $today ? '<span class="badge badge-info">Scheduled</span>' : '<span class="badge badge-gray">Hidden</span>') ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$notices): ?><div class="empty">No notices yet.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="nModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="nTitle">
    <div class="modal-head"><h2 id="nTitle">Add Notice</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="nForm" novalidate>
      <input type="hidden" id="nId" value="0">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="nT">Title <span class="req">*</span></label><input class="field-input" id="nT" maxlength="200"></div>
          <div class="full"><label class="field-label" for="nB">Message <span class="req">*</span></label><textarea class="field-input" id="nB" maxlength="5000" style="min-height:140px"></textarea></div>
          <div><label class="field-label" for="nD">Publish date</label><input class="field-input" type="date" id="nD"></div>
          <div><label class="field-label" for="nE">Show until (optional)</label><input class="field-input" type="date" id="nE"></div>
          <div><label class="field-label" for="nC">For class</label>
            <select class="field-input" id="nC"><option value="">All parents</option><?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="nDv">Division</label><select class="field-input" id="nDv"><option value="">All divisions</option></select></div>
          <label class="check"><input type="checkbox" id="nA" checked> Active</label>
        </div>
        <p class="field-error" id="nErr" hidden></p>
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
  const DIV_MAP = <?= json_encode($div_map) ?>, m = U.modal('nModal');
  function fillDiv(v) {
    const list = DIV_MAP[$('nC').value] || [];
    $('nDv').innerHTML = '<option value="">All divisions</option>' + list.map(d => `<option value="${U.esc(d.id)}">${U.esc(d.name)}</option>`).join('');
    $('nDv').value = v || ''; $('nDv').disabled = !$('nC').value;
  }
  $('nC').addEventListener('change', () => fillDiv(''));
  function open(n) {
    $('nErr').hidden = true; $('nId').value = n ? n.id : 0; $('nTitle').textContent = n ? 'Edit Notice' : 'Add Notice';
    $('nT').value = n ? n.title : ''; $('nB').value = n ? n.body : ''; $('nD').value = n ? n.date : '<?= $today ?>'; $('nE').value = n ? n.expiry : '';
    $('nC').value = n && n.class ? n.class : ''; fillDiv(n && n.division ? n.division : ''); $('nA').checked = n ? !!n.active : true;
    m.show(); $('nT').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(null));
  $('nForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    [['id', 'nId'], ['title', 'nT'], ['body', 'nB'], ['date', 'nD'], ['expiry', 'nE'], ['class', 'nC'], ['division', 'nDv']].forEach(([k, id]) => fd.append(k, $(id).value.trim()));
    if ($('nA').checked) fd.append('active', 1);
    const res = await U.post('<?= site_url('parent_notice_save') ?>', fd);
    if (!res.status) { $('nErr').textContent = res.msg; $('nErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });
  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const n = JSON.parse(tr.dataset.n);
    if (e.target.closest('[data-edit]')) return open(n);
    if (e.target.closest('[data-del]')) {
      const c = await Swal.fire({title: 'Delete this notice?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
      if (!c.isConfirmed) return;
      const fd = new FormData(); fd.append('id', n.id);
      const res = await U.post('<?= site_url('parent_notice_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });
})();
</script>
