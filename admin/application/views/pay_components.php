<?php /* $components, $perm */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
$fmtVal = function ($c) {
    if ($c->pcCalc === 'FIXED') return '₹ ' . fee_money($c->pcValue);
    $t = rtrim(rtrim(number_format((float)$c->pcValue, 2, '.', ''), '0'), '.') . '% of ' . array('BASIC' => 'Basic', 'WAGE' => 'PF wage', 'GROSS' => 'Gross')[$c->pcCalc];
    if ((float)$c->pcCeiling > 0) $t .= ' (up to ₹ ' . fee_money($c->pcCeiling, 0) . ')';
    return $t;
}; ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Salary Components</h1>
      <p class="page-sub">Allowances, deductions and employer contributions used in salary structures. The value here is the default; it can be changed for each staff member.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openAdd">+ Add Component</button></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Component</th><th>Short</th><th>Type</th><th>Default value</th><th>Rules</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php $last = null; foreach ($components as $c):
          if ($c->pcType !== $last): $last = $c->pcType; ?>
            <tr class="row-group"><td colspan="7"><?= html_escape(Payroll_model::$types[$c->pcType]) ?>s</td></tr>
          <?php endif;
          $row = array('id' => (int)$c->pcId, 'name' => $c->pcName, 'short' => $c->pcShort, 'type' => $c->pcType, 'calc' => $c->pcCalc,
                       'value' => (float)$c->pcValue, 'ceiling' => $c->pcCeiling === null ? '' : (float)$c->pcCeiling,
                       'gross_limit' => $c->pcGrossLimit === null ? '' : (float)$c->pcGrossLimit, 'prorate' => (int)$c->pcProrate,
                       'wage' => (int)$c->pcWage, 'default' => (int)$c->pcDefault, 'key' => (string)$c->pcKey, 'order' => (int)$c->pcOrder,
                       'active' => (int)$c->pcActive);
          $rules = array();
          if ($c->pcKey) $rules[] = Payroll_model::$key_labels[$c->pcKey];
          if ((float)$c->pcGrossLimit > 0) $rules[] = 'only when gross ≤ ₹ ' . fee_money($c->pcGrossLimit, 0);
          if ($c->pcCalc === 'FIXED') $rules[] = $c->pcProrate ? 'reduced for loss of pay' : 'full amount even with loss of pay';
          if ($c->pcWage) $rules[] = 'part of PF wage';
          if ($c->pcDefault) $rules[] = 'ticked for new staff'; ?>
          <tr data-c="<?= html_escape(json_encode($row)) ?>" class="<?= $c->pcActive ? '' : 'muted' ?>">
            <td><strong><?= html_escape($c->pcName) ?></strong></td>
            <td><?= html_escape($c->pcShort) ?></td>
            <td><?= html_escape(Payroll_model::$types[$c->pcType]) ?></td>
            <td><?= html_escape($fmtVal($c)) ?></td>
            <td class="small"><?= html_escape(implode(' · ', $rules)) ?></td>
            <td><?= $c->pcActive ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$components): ?><div class="empty">No components yet.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="cModal" aria-hidden="true">
  <div class="modal" style="max-width:640px" role="dialog" aria-modal="true" aria-labelledby="cTitle">
    <div class="modal-head"><h2 id="cTitle">Add Component</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="cForm" novalidate>
      <input type="hidden" id="cId" value="0">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div><label class="field-label" for="cName">Name <span class="req">*</span></label><input class="field-input" id="cName" maxlength="80" placeholder="e.g. House Rent Allowance"></div>
          <div><label class="field-label" for="cShort">Short name <span class="req">*</span></label><input class="field-input" id="cShort" maxlength="12" placeholder="e.g. HRA"></div>
          <div><label class="field-label" for="cType">Type <span class="req">*</span></label>
            <select class="field-input" id="cType"><?php foreach (Payroll_model::$types as $k => $l): ?><option value="<?= $k ?>"><?= html_escape($l) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="cCalc">Calculated as <span class="req">*</span></label>
            <select class="field-input" id="cCalc"><?php foreach (Payroll_model::$calcs as $k => $l): ?><option value="<?= $k ?>"><?= html_escape($l) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="cValue" id="cValueLbl">Default amount (₹)</label><input class="field-input" id="cValue" inputmode="decimal" value="0"></div>
          <div id="cCeilBox"><label class="field-label" for="cCeil">Wage ceiling (₹)</label><input class="field-input" id="cCeil" inputmode="decimal" placeholder="No ceiling">
            <span class="field-hint">The % is worked out on at most this wage (PF: 15,000).</span></div>
          <div id="cLimitBox"><label class="field-label" for="cLimit">Applies only up to gross (₹)</label><input class="field-input" id="cLimit" inputmode="decimal" placeholder="Always applies">
            <span class="field-hint">Skipped when the monthly gross is more (ESI: 21,000).</span></div>
          <div id="cKeyBox"><label class="field-label" for="cKey">Statutory type</label>
            <select class="field-input" id="cKey"><option value="">None</option><?php foreach (Payroll_model::$key_labels as $k => $l): ?><option value="<?= $k ?>" data-type="<?= Payroll_model::$keys[$k] ?>"><?= html_escape($l) ?></option><?php endforeach; ?></select>
            <span class="field-hint">Used by the PF / ESI statements and to post to the right payable account.</span></div>
          <div><label class="field-label" for="cOrder">Display order</label><input class="field-input" id="cOrder" type="number" min="0" max="999" value="0"></div>
          <div class="full" style="display:flex;flex-direction:column;gap:8px">
            <label class="check" id="cProrateBox"><input type="checkbox" id="cProrate" checked> Reduce for loss-of-pay days</label>
            <label class="check" id="cWageBox"><input type="checkbox" id="cWage"> Part of PF wage (like DA: PF is worked out on Basic + this)</label>
            <label class="check"><input type="checkbox" id="cDefault"> Tick for new staff automatically</label>
            <label class="check"><input type="checkbox" id="cActive" checked> Active</label>
          </div>
        </div>
        <p class="field-error" id="cErr" hidden></p>
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
  const m = U.modal('cModal');

  function sync() {
    const t = $('cType').value, earn = t === 'EARNING';
    Array.from($('cCalc').options).forEach(o => { o.hidden = earn && (o.value === 'WAGE' || o.value === 'GROSS'); });
    if ($('cCalc').selectedOptions[0].hidden) $('cCalc').value = 'FIXED';
    const fixed = $('cCalc').value === 'FIXED';
    $('cValueLbl').textContent = fixed ? 'Default amount (₹)' : 'Default percentage (%)';
    $('cCeilBox').hidden = fixed;
    $('cLimitBox').hidden = earn;
    $('cWageBox').hidden = !earn;
    $('cProrateBox').hidden = !fixed;
    Array.from($('cKey').options).forEach(o => { if (o.value) o.hidden = o.dataset.type !== t; });
    if ($('cKey').selectedOptions[0].hidden) $('cKey').value = '';
  }
  $('cType').addEventListener('change', sync);
  $('cCalc').addEventListener('change', sync);

  function open(c) {
    $('cErr').hidden = true; $('cId').value = c ? c.id : 0; $('cTitle').textContent = c ? 'Edit Component' : 'Add Component';
    $('cName').value = c ? c.name : ''; $('cShort').value = c ? c.short : '';
    $('cType').value = c ? c.type : 'EARNING'; $('cCalc').value = c ? c.calc : 'FIXED';
    $('cValue').value = c ? c.value : 0; $('cCeil').value = c ? c.ceiling : ''; $('cLimit').value = c ? c.gross_limit : '';
    $('cKey').value = c ? c.key : ''; $('cOrder').value = c ? c.order : 0;
    $('cProrate').checked = c ? !!c.prorate : true; $('cWage').checked = c ? !!c.wage : false;
    $('cDefault').checked = c ? !!c.default : false; $('cActive').checked = c ? !!c.active : true;
    sync(); m.show(); $('cName').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(null));

  $('cForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('cId').value); fd.append('name', $('cName').value.trim()); fd.append('short', $('cShort').value.trim());
    fd.append('type', $('cType').value); fd.append('calc', $('cCalc').value); fd.append('value', $('cValue').value.trim());
    if (!$('cCeilBox').hidden) fd.append('ceiling', $('cCeil').value.trim());
    if (!$('cLimitBox').hidden) fd.append('gross_limit', $('cLimit').value.trim());
    fd.append('key', $('cKey').value); fd.append('order', $('cOrder').value || 0);
    if ($('cProrate').checked) fd.append('prorate', 1);
    if ($('cWage').checked) fd.append('wage', 1);
    if ($('cDefault').checked) fd.append('default', 1);
    if ($('cActive').checked) fd.append('active', 1);
    const res = await U.post('<?= site_url('pay_component_save') ?>', fd);
    if (!res.status) { $('cErr').textContent = res.msg; $('cErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });

  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-c]'); if (!tr) return;
    const c = JSON.parse(tr.dataset.c);
    if (e.target.closest('[data-edit]')) return open(c);
    if (e.target.closest('[data-del]')) {
      const x = await Swal.fire({title: 'Delete ' + c.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
      if (!x.isConfirmed) return;
      const fd = new FormData(); fd.append('id', c.id);
      const res = await U.post('<?= site_url('pay_component_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });
})();
</script>
