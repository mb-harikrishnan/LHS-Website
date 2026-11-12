<?php /* $staff (with gross, deduction, net), $f, $perm, $components (active), $unlinked */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
$tot = array('basic' => 0, 'gross' => 0, 'net' => 0); $onPay = 0;
foreach ($staff as $s) if ($s->peActive) { $onPay++; $tot['basic'] += $s->peBasic; $tot['gross'] += $s->gross; $tot['net'] += $s->net; }
$compJs = array();
foreach ($components as $c) $compJs[] = array('id' => (int)$c->pcId, 'name' => $c->pcName, 'short' => $c->pcShort, 'type' => $c->pcType,
    'calc' => $c->pcCalc, 'value' => (float)$c->pcValue, 'def' => (int)$c->pcDefault);
?>
<style>
.comp-table td{vertical-align:middle}
.comp-table .cell-input{width:110px}
.comp-table tr.off td:not(:first-child){opacity:.45}
.pv{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.pv h4{margin:0 0 6px;font-size:12px;text-transform:uppercase;color:#64748b}
.pv div.l{display:flex;justify-content:space-between;font-size:13.5px;padding:3px 0;border-bottom:1px dashed #e2e8f0}
.pv div.t{display:flex;justify-content:space-between;font-weight:800;padding-top:6px}
.pv-net{margin-top:12px;padding:12px 14px;border-radius:10px;background:#ecfdf5;border:1px solid #a7f3d0;display:flex;justify-content:space-between;align-items:center;font-weight:800;font-size:18px}
@media (max-width:640px){.pv{grid-template-columns:1fr}}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Salary Structure</h1>
      <p class="page-sub">Staff on payroll with their basic pay, allowances, deductions and bank details. Amounts are for a full month without loss of pay.</p></div>
    <div class="page-actions">
      <?php if ($can_add && $unlinked): ?><button class="btn btn-secondary" type="button" id="importBtn">Add <?= count($unlinked) ?> employee(s) from Employees</button><?php endif; ?>
      <?php if ($can_add): ?><button class="btn btn-primary" type="button" id="openAdd">+ Add Staff</button><?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="sum-grid">
      <div class="sum-item"><small>On payroll</small><strong><?= $onPay ?></strong></div>
      <div class="sum-item"><small>Monthly basic</small><strong>₹ <?= fee_money($tot['basic'], 0) ?></strong></div>
      <div class="sum-item"><small>Monthly gross</small><strong>₹ <?= fee_money($tot['gross'], 0) ?></strong></div>
      <div class="sum-item ok"><small>Monthly net pay</small><strong>₹ <?= fee_money($tot['net'], 0) ?></strong></div>
    </div>
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('pay_staff') ?>">
        <div class="fb" style="flex:1;min-width:200px"><label for="fq">Search</label><input class="field-input" id="fq" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Name, code, phone or designation"></div>
        <div class="fb"><label for="fcat">Category</label><select class="field-input" id="fcat" name="category"><option value="">All</option>
          <?php foreach (Payroll_model::$categories as $k => $l): ?><option value="<?= $k ?>" <?= $f['category'] === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
        <div class="fb"><label for="fact">Status</label><select class="field-input" id="fact" name="active"><option value="">All</option>
          <option value="1" <?= $f['active'] === '1' ? 'selected' : '' ?>>On payroll</option><option value="0" <?= $f['active'] === '0' ? 'selected' : '' ?>>Not on payroll</option></select></div>
        <button class="btn btn-primary" type="submit">Show</button><a class="btn btn-secondary" href="<?= site_url('pay_staff') ?>">Reset</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Code</th><th>Name</th><th>Category</th><th>Joined</th><th class="num">Basic</th><th class="num">Gross</th><th class="num">Deductions</th><th class="num">Net Pay</th><th>Paid by</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($staff as $s): ?>
          <tr data-id="<?= (int)$s->peId ?>" data-name="<?= html_escape($s->peName) ?>">
            <td><?= html_escape($s->peCode) ?></td>
            <td><strong><?= html_escape($s->peName) ?></strong>
              <div class="small muted"><?= html_escape($s->peDesignation ?: '') ?><?= $s->peEmId ? ' · has login' : '' ?><?= $s->pePhone ? ' · ' . html_escape($s->pePhone) : '' ?></div></td>
            <td><?= html_escape(Payroll_model::$categories[$s->peCategory]) ?></td>
            <td><?= $dmy($s->peJoinDate) ?><?= $s->peLeaveDate ? '<div class="small warn-txt">Relieved ' . $dmy($s->peLeaveDate) . '</div>' : '' ?></td>
            <td class="num"><?= fee_money($s->peBasic) ?></td>
            <td class="num"><?= fee_money($s->gross) ?></td>
            <td class="num"><?= fee_money($s->deduction) ?></td>
            <td class="num"><strong><?= fee_money($s->net) ?></strong></td>
            <td><?= html_escape($s->pePayMode) ?><?= $s->pePayMode === 'Bank' && $s->peAccountNo ? '<div class="small muted">' . html_escape('••' . substr($s->peAccountNo, -4)) . '</div>' : '' ?></td>
            <td><?= $s->peActive ? '<span class="badge badge-green">On payroll</span>' : ((float)$s->peBasic > 0 ? '<span class="badge badge-gray">Not on payroll</span>' : '<span class="badge badge-amber">Set salary</span>') ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$staff): ?><div class="empty"><?= $f['q'] || $f['category'] || $f['active'] !== '' ? 'No staff match the filters.' : 'No staff on payroll yet. Add staff, or add your employees from the Employees list.' ?></div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="sModal" aria-hidden="true">
  <div class="modal" style="max-width:980px" role="dialog" aria-modal="true" aria-labelledby="sTitle">
    <div class="modal-head"><h2 id="sTitle">Add Staff</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="sForm" novalidate>
      <input type="hidden" id="sId" value="0">
      <div class="modal-body">
        <div class="sec-title">Staff details</div>
        <div class="grid g3">
          <div id="sLinkBox"><label class="field-label" for="sLink">From Employees (login)</label>
            <select class="field-input" id="sLink"><option value="">— Staff without a login —</option>
              <?php foreach ($unlinked as $u): ?><option value="<?= (int)$u->emId ?>" data-name="<?= html_escape($u->emName) ?>" data-phone="<?= html_escape($u->emPhoneNo) ?>" data-role="<?= html_escape($u->role_name) ?>"><?= html_escape($u->emName) ?> (<?= html_escape($u->role_name ?: '—') ?>)</option><?php endforeach; ?>
            </select></div>
          <div><label class="field-label" for="sName">Name <span class="req">*</span></label><input class="field-input" id="sName" maxlength="100"></div>
          <div><label class="field-label" for="sCode">Employee code</label><input class="field-input" id="sCode" maxlength="20" placeholder="e.g. LHS015"></div>
          <div><label class="field-label" for="sPhone">Phone</label><input class="field-input" id="sPhone" maxlength="10" inputmode="numeric"></div>
          <div><label class="field-label" for="sCat">Category <span class="req">*</span></label>
            <select class="field-input" id="sCat"><?php foreach (Payroll_model::$categories as $k => $l): ?><option value="<?= $k ?>"><?= $l ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="sDesig">Designation</label><input class="field-input" id="sDesig" maxlength="80" placeholder="e.g. Primary Teacher, Driver"></div>
          <div><label class="field-label" for="sJoin">Joining date</label><input class="field-input" type="date" id="sJoin"></div>
          <div><label class="field-label" for="sLeave">Relieving date</label><input class="field-input" type="date" id="sLeave"><span class="field-hint">Salary stops after this date.</span></div>
        </div>

        <div class="sec-title">Monthly salary</div>
        <div class="grid" style="grid-template-columns:1.4fr 1fr;align-items:start">
          <div>
            <div style="max-width:260px;margin-bottom:12px"><label class="field-label" for="sBasic">Basic pay (₹ per month) <span class="req">*</span></label><input class="field-input" id="sBasic" inputmode="decimal"></div>
            <div class="table-wrap" style="border:1px solid #e2e8f0;border-radius:10px">
              <table class="table compact comp-table">
                <thead><tr><th style="width:34px"></th><th>Component</th><th class="num">Value</th></tr></thead>
                <tbody id="compRows"></tbody>
              </table>
            </div>
            <span class="field-hint">Tick the components that apply. Change the value for this staff member if it differs from the default.</span>
          </div>
          <div class="card" style="padding:14px 16px;box-shadow:none;border:1px solid #e2e8f0">
            <div class="pv">
              <div><h4>Earnings</h4><div id="pvEarn"></div><div class="t"><span>Gross</span><span id="pvGross">0.00</span></div></div>
              <div><h4>Deductions</h4><div id="pvDed"></div><div class="t"><span>Total</span><span id="pvDedT">0.00</span></div></div>
            </div>
            <div class="pv-net"><span>Net pay</span><span id="pvNet">₹ 0.00</span></div>
            <div class="small muted" id="pvEmp" style="margin-top:8px"></div>
            <div class="small bad-txt" id="pvErr" hidden></div>
          </div>
        </div>

        <div class="sec-title">Payment &amp; statutory details</div>
        <div class="grid g3">
          <div><label class="field-label" for="sMode">Salary paid by <span class="req">*</span></label>
            <select class="field-input" id="sMode"><?php foreach (Payroll_model::$modes as $m): ?><option><?= $m ?></option><?php endforeach; ?></select></div>
          <div class="bank"><label class="field-label" for="sBank">Bank name</label><input class="field-input" id="sBank" maxlength="100"></div>
          <div class="bank"><label class="field-label" for="sAcct">Account number</label><input class="field-input" id="sAcct" maxlength="30" autocomplete="off"></div>
          <div class="bank"><label class="field-label" for="sIfsc">IFSC</label><input class="field-input" id="sIfsc" maxlength="11" placeholder="SBIN0001234" style="text-transform:uppercase"></div>
          <div><label class="field-label" for="sPan">PAN</label><input class="field-input" id="sPan" maxlength="10" style="text-transform:uppercase"></div>
          <div><label class="field-label" for="sUan">PF UAN</label><input class="field-input" id="sUan" maxlength="20"></div>
          <div><label class="field-label" for="sEsi">ESI number</label><input class="field-input" id="sEsi" maxlength="20"></div>
        </div>
        <div style="margin-top:14px"><label class="check"><input type="checkbox" id="sActive" checked> On payroll (include in monthly payroll)</label></div>
        <p class="field-error" id="sErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id), esc = U.esc;
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const COMPS = <?= json_encode($compJs) ?>;
  const TYPE = {EARNING: 'Earning', DEDUCTION: 'Deduction', EMPLOYER: 'Employer'};
  const m = U.modal('sModal');

  function buildComps(values) {   // values: {pcId: value} or null for a new staff member
    let last = '';
    $('compRows').innerHTML = COMPS.map(c => {
      const on = values ? Object.prototype.hasOwnProperty.call(values, c.id) : !!c.def;
      const v = values && on ? values[c.id] : c.value;
      const head = c.type !== last ? `<tr class="row-group"><td colspan="3">${TYPE[c.type]}s</td></tr>` : '';
      last = c.type;
      return head + `<tr data-id="${c.id}" class="${on ? '' : 'off'}">
        <td><input type="checkbox" class="c-on" ${on ? 'checked' : ''} aria-label="${esc(c.name)}"></td>
        <td>${esc(c.name)}</td>
        <td class="num">${c.calc === 'FIXED' ? '₹ ' : ''}<input class="cell-input c-val" inputmode="decimal" value="${v}" ${on ? '' : 'disabled'}>${c.calc === 'FIXED' ? '' : ' %'}</td></tr>`;
    }).join('');
  }
  $('compRows').addEventListener('change', e => {
    if (!e.target.classList.contains('c-on')) return;
    const tr = e.target.closest('tr'); tr.classList.toggle('off', !e.target.checked);
    tr.querySelector('.c-val').disabled = !e.target.checked;
    preview();
  });

  function fillStructure(fd) {
    fd.append('basic', $('sBasic').value.trim());
    $('compRows').querySelectorAll('tr[data-id]').forEach(tr => {
      if (!tr.querySelector('.c-on').checked) return;
      fd.append('comp[]', tr.dataset.id); fd.append('val[]', tr.querySelector('.c-val').value.trim());
    });
  }

  let pvTimer = null, pvSeq = 0;
  function preview() { clearTimeout(pvTimer); pvTimer = setTimeout(runPreview, 250); }
  async function runPreview() {
    const seq = ++pvSeq, fd = new FormData(); fillStructure(fd);
    let res;
    try { res = await U.post('<?= site_url('pay_staff_preview') ?>', fd); } catch (e) { return; }
    if (seq !== pvSeq) return;
    $('pvErr').hidden = res.status; if (!res.status) { $('pvErr').textContent = res.msg; return; }
    const c = res.calc, row = l => `<div class="l"><span>${esc(l.name)}</span><span>${U.inr(l.amount)}</span></div>`;
    $('pvEarn').innerHTML = c.lines.filter(l => l.type === 'EARNING').map(row).join('');
    $('pvDed').innerHTML = c.lines.filter(l => l.type === 'DEDUCTION').map(row).join('') || '<div class="l muted"><span>None</span><span></span></div>';
    $('pvGross').textContent = U.inr(c.gross); $('pvDedT').textContent = U.inr(c.deduction);
    $('pvNet').textContent = '₹ ' + U.inr(c.net);
    const er = c.lines.filter(l => l.type === 'EMPLOYER');
    $('pvEmp').textContent = er.length ? 'Paid by the school on top: ' + er.map(l => l.name + ' ' + U.inr(l.amount)).join(', ') + ' · Cost to school ₹ ' + U.inr(c.gross + c.employer) : '';
  }
  $('sBasic').addEventListener('input', preview);
  $('compRows').addEventListener('input', preview);

  function bankVis() { document.querySelectorAll('#sForm .bank').forEach(b => b.style.opacity = $('sMode').value === 'Bank' ? 1 : .5); }
  $('sMode').addEventListener('change', bankVis);

  $('sLink').addEventListener('change', () => {
    const o = $('sLink').selectedOptions[0];
    if (!o.value) return;
    $('sName').value = o.dataset.name || ''; $('sPhone').value = o.dataset.phone || '';
    if (!$('sDesig').value) $('sDesig').value = o.dataset.role || '';
    $('sCat').value = /teacher/i.test(o.dataset.role || '') ? 'TEACHING' : 'NON_TEACHING';
  });

  function fill(s) {
    const v = (k, d) => s && s[k] != null ? s[k] : (d || '');
    $('sId').value = s ? s.peId : 0; $('sTitle').textContent = s ? 'Edit Salary — ' + s.peName : 'Add Staff';
    $('sLinkBox').hidden = !!s; $('sLink').value = '';
    $('sName').value = v('peName'); $('sCode').value = v('peCode'); $('sPhone').value = v('pePhone');
    $('sCat').value = v('peCategory', 'TEACHING'); $('sDesig').value = v('peDesignation');
    $('sJoin').value = v('peJoinDate'); $('sLeave').value = v('peLeaveDate');
    $('sBasic').value = s ? (+s.peBasic || '') : '';
    $('sMode').value = v('pePayMode', 'Bank'); $('sBank').value = v('peBankName'); $('sAcct').value = v('peAccountNo');
    $('sIfsc').value = v('peIfsc'); $('sPan').value = v('pePan'); $('sUan').value = v('peUan'); $('sEsi').value = v('peEsiNo');
    $('sActive').checked = s ? (+s.peActive === 1 || +s.peBasic === 0) : true;
    $('sErr').hidden = true; bankVis();
  }

  async function open(id) {
    if (!id) { fill(null); buildComps(null); m.show(); runPreview(); $('sName').focus(); return; }
    const res = await (await fetch('<?= site_url('pay_staff_get') ?>?id=' + id)).json().catch(() => ({status: false, msg: 'Your session may have expired. Reload the page.'}));
    if (!res.status) return U.err(res.msg);
    fill(res.staff);
    buildComps(Object.keys(res.values).length || +res.staff.peBasic > 0 ? res.values : null);
    m.show(); runPreview(); $('sBasic').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(0));

  $('sForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    const map = {id: 'sId', name: 'sName', code: 'sCode', phone: 'sPhone', category: 'sCat', designation: 'sDesig', join_date: 'sJoin',
                 leave_date: 'sLeave', pay_mode: 'sMode', bank_name: 'sBank', account_no: 'sAcct', ifsc: 'sIfsc', pan: 'sPan', uan: 'sUan', esi_no: 'sEsi'};
    Object.keys(map).forEach(k => fd.append(k, $(map[k]).value.trim()));
    if (!$('sLinkBox').hidden && $('sLink').value) fd.append('em_id', $('sLink').value);
    if ($('sActive').checked) fd.append('active', 1);
    fillStructure(fd);
    const res = await U.post('<?= site_url('pay_staff_save') ?>', fd);
    if (!res.status) { $('sErr').textContent = res.msg; $('sErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });

  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-id]'); if (!tr) return;
    if (e.target.closest('[data-edit]')) return open(tr.dataset.id);
    if (e.target.closest('[data-del]')) {
      const x = await Swal.fire({title: 'Remove ' + tr.dataset.name + ' from payroll?', text: 'Only staff without payslips or advances can be removed.', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Remove'});
      if (!x.isConfirmed) return;
      const fd = new FormData(); fd.append('id', tr.dataset.id);
      const res = await U.post('<?= site_url('pay_staff_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });

  const imp = $('importBtn');
  if (imp) imp.addEventListener('click', async () => {
    const x = await Swal.fire({title: 'Add employees to payroll?', text: 'Every active employee with a login who is not on this list will be added. Then set each one\'s salary.',
      icon: 'question', showCancelButton: true, confirmButtonText: 'Add'});
    if (!x.isConfirmed) return;
    const res = await U.post('<?= site_url('pay_staff_import') ?>', new FormData());
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
