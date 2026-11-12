<?php /* $run, $slips, $batches, $perm, $missing, $modes */
$draft = $run->prStatus === 'DRAFT';
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
$t = array('gross' => 0, 'ded' => 0, 'emp' => 0, 'net' => 0, 'paid' => 0);
foreach ($slips as $s) { $t['gross'] += $s->psGross; $t['ded'] += $s->psDeduction; $t['emp'] += $s->psEmployer; $t['net'] += $s->psNet; $t['paid'] += $s->psPaid; }
$bal = round($t['net'] - $t['paid'], 2);
$canPay = !$draft && $can_add && $bal > 0;
$label = Payroll_model::month_label($run->prMonth);
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
$num = function ($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); };
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Payroll — <?= html_escape($label) ?>
        <?= $draft ? '<span class="badge badge-amber">Draft</span>' : '<span class="badge badge-green">Approved</span>' ?></h1>
      <p class="page-sub"><?= (int)$run->prDays ?> days in the month.
        <?= $draft ? 'Enter loss-of-pay days and any extra earnings or deductions, then approve.' : 'Approved on ' . $dmy($run->prApprovedAt) . '. Payslips are locked; the salary is posted to accounts.' ?></p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('payroll') ?>">All Months</a>
      <a class="btn btn-secondary" href="<?= site_url('payslips/' . (int)$run->prId) ?>" target="_blank" rel="noopener">Print Payslips</a>
      <a class="btn btn-secondary" href="<?= site_url('reports/pay_salary_register') ?>?month=<?= date('Y-m', strtotime($run->prMonth)) ?>">Salary Register</a>
      <?php if ($draft && $can_edit): ?>
        <?php if ($this->db->table_exists('staff_attendance')): ?><button class="btn btn-secondary" type="button" id="attBtn">Load LOP from Attendance</button><?php endif; ?>
        <button class="btn btn-secondary" type="button" id="recalcBtn">Recalculate<?= $missing ? ' (+' . (int)$missing . ' new)' : '' ?></button>
        <button class="btn btn-primary" type="button" id="approveBtn">Approve</button>
      <?php endif; ?>
      <?php if (!$draft && $can_edit): ?><button class="btn btn-secondary" type="button" id="reopenBtn">Reopen</button><?php endif; ?>
      <?php if ($canPay): ?><button class="btn btn-primary" type="button" id="payBtn">Pay Salaries</button><?php endif; ?>
    </div>
  </div>

  <?php if ($draft && $missing): ?>
    <div class="card" style="padding:12px 18px;margin-bottom:14px;background:#fffbeb;border-color:#fde68a"><?= (int)$missing ?> staff member(s) on payroll for this month have no payslip yet. Click <strong>Recalculate</strong> to add them.</div>
  <?php endif; ?>

  <div class="card">
    <div class="sum-grid">
      <div class="sum-item"><small>Staff</small><strong><?= count($slips) ?></strong></div>
      <div class="sum-item"><small>Gross</small><strong><?= fee_money($t['gross']) ?></strong></div>
      <div class="sum-item"><small>Deductions</small><strong><?= fee_money($t['ded']) ?></strong></div>
      <div class="sum-item"><small>Employer PF / ESI</small><strong><?= fee_money($t['emp']) ?></strong></div>
      <div class="sum-item ok"><small>Net pay</small><strong><?= fee_money($t['net']) ?></strong></div>
      <div class="sum-item <?= !$draft && $bal > 0 ? 'warn' : '' ?>"><small>Paid / balance</small><strong><?= fee_money($t['paid']) ?></strong><span class="small"><?= fee_money($bal) ?> to pay</span></div>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr>
          <?php if ($canPay): ?><th style="width:34px"><input type="checkbox" id="chkAll" aria-label="Select all with balance"></th><?php endif; ?>
          <th>Staff</th><th class="num">Paid days</th><th class="num">LOP</th><th class="num">Gross</th><th class="num">Deductions</th>
          <th class="num">Net Pay</th><th class="num">Paid</th><th class="num">Balance</th><th>Paid by</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php $cat = null; foreach ($slips as $s): $b = round($s->psNet - $s->psPaid, 2);
          if ($s->psCategory !== $cat): $cat = $s->psCategory; ?>
            <tr class="row-group"><td colspan="<?= $canPay ? 11 : 10 ?>"><?= html_escape(Payroll_model::$categories[$cat]) ?> staff</td></tr>
          <?php endif; ?>
          <tr data-id="<?= (int)$s->psId ?>" data-name="<?= html_escape($s->psName) ?>" data-bal="<?= $b ?>">
            <?php if ($canPay): ?><td><?php if ($b > 0): ?><input type="checkbox" class="pick" aria-label="Pay <?= html_escape($s->psName) ?>"><?php endif; ?></td><?php endif; ?>
            <td><strong><?= html_escape($s->psName) ?></strong>
              <div class="small muted"><?= html_escape(trim(($s->psCode ? $s->psCode . ' · ' : '') . $s->psDesignation)) ?><?= $s->psRemarks ? ' · ' . html_escape($s->psRemarks) : '' ?></div></td>
            <td class="num"><?= $num($s->psPaidDays) ?> / <?= (int)$s->psDays ?></td>
            <td class="num <?= (float)$s->psLop > 0 ? 'warn-txt' : '' ?>"><?= $num($s->psLop) ?></td>
            <td class="num"><?= fee_money($s->psGross) ?><?= (float)$s->psExtraEarn > 0 ? '<div class="small muted">incl. ' . html_escape($s->psExtraEarnNote ?: 'other') . '</div>' : '' ?></td>
            <td class="num"><?= fee_money($s->psDeduction) ?></td>
            <td class="num"><strong><?= fee_money($s->psNet) ?></strong></td>
            <td class="num"><?= fee_money($s->psPaid) ?></td>
            <td class="num <?= $b > 0 && !$draft ? 'warn-txt' : '' ?>"><?= fee_money($b) ?></td>
            <td class="small"><?= html_escape($s->psPayMode) ?><?= $s->psPayMode === 'Bank' && $s->psAccountNo ? '<div class="muted">••' . html_escape(substr($s->psAccountNo, -4)) . '</div>' : '' ?></td>
            <td><div class="act-row">
              <?php if ($draft && $can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <a class="act-btn dl" href="<?= site_url('payslip/' . (int)$s->psId) ?>" target="_blank" rel="noopener">Payslip</a>
              <?php if ($draft && $can_del): ?><button class="act-btn rm" type="button" data-rm aria-label="Remove <?= html_escape($s->psName) ?> from this month">✕</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$slips): ?><div class="empty">No payslips in this payroll.</div><?php endif; ?>
    </div>
    <?php if ($draft && $can_del): ?>
      <div class="table-foot"><span class="muted small">A payroll that was never approved can be deleted.</span><button class="act-btn rm" type="button" id="deleteBtn">Delete this payroll</button></div>
    <?php endif; ?>
  </div>

  <?php if ($batches): ?>
  <div class="card" style="margin-top:18px">
    <div class="stu-head"><h3>Salary payments</h3></div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Date</th><th>Mode</th><th>Reference</th><th class="num">Staff</th><th class="num">Amount</th><th>Remarks</th><th></th></tr></thead>
        <tbody id="batches">
        <?php foreach ($batches as $b): ?>
          <tr data-id="<?= (int)$b->pbId ?>" class="<?= $b->pbCancelled ? 'row-cancel' : '' ?>">
            <td><?= $dmy($b->pbDate) ?></td><td><?= html_escape($b->pbMode) ?></td><td><?= html_escape($b->pbRef) ?></td>
            <td class="num"><?= (int)$b->staff ?></td><td class="num"><?= fee_money($b->pbAmount) ?></td>
            <td class="small"><?= html_escape($b->pbCancelled ? 'Cancelled: ' . $b->pbCancelReason : $b->pbRemarks) ?></td>
            <td><?php if (!$b->pbCancelled && $can_del): ?><button class="act-btn rm" type="button" data-cancel>Cancel</button><?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</main>

<!-- edit payslip -->
<div class="modal-overlay" id="eModal" aria-hidden="true">
  <div class="modal" style="max-width:760px" role="dialog" aria-modal="true" aria-labelledby="eTitle">
    <div class="modal-head"><h2 id="eTitle">Edit Payslip</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="eForm" novalidate>
      <input type="hidden" id="eId">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px;grid-template-columns:1fr 1.2fr;align-items:start">
          <div>
            <label class="field-label" for="eLop">Loss-of-pay days</label>
            <input class="field-input" id="eLop" inputmode="decimal" placeholder="0">
            <span class="field-hint" id="eLopHint"></span>
            <div class="sec-title">Extra earning (this month only)</div>
            <div class="grid" style="grid-template-columns:120px 1fr;gap:8px">
              <input class="field-input" id="eEe" inputmode="decimal" placeholder="Amount" aria-label="Extra earning amount">
              <input class="field-input" id="eEen" maxlength="100" placeholder="e.g. Arrears, Bonus, Overtime" aria-label="Extra earning description">
            </div>
            <div class="sec-title">Extra deduction (this month only)</div>
            <div class="grid" style="grid-template-columns:120px 1fr;gap:8px">
              <input class="field-input" id="eEd" inputmode="decimal" placeholder="Amount" aria-label="Extra deduction amount">
              <input class="field-input" id="eEdn" maxlength="100" placeholder="e.g. Canteen, Damage recovery" aria-label="Extra deduction description">
            </div>
            <div style="margin-top:12px"><label class="field-label" for="eRem">Remarks</label><input class="field-input" id="eRem" maxlength="255"></div>
          </div>
          <div class="card" style="padding:12px 14px;box-shadow:none;border:1px solid #e2e8f0">
            <div class="small muted" style="margin-bottom:6px">Current payslip (updates when you save)</div>
            <table class="table compact"><tbody id="eLines"></tbody></table>
          </div>
        </div>
        <p class="field-error" id="eErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save &amp; Recalculate</button></div>
    </form>
  </div>
</div>

<!-- pay -->
<div class="modal-overlay" id="pModal" aria-hidden="true">
  <div class="modal" style="max-width:720px" role="dialog" aria-modal="true" aria-labelledby="pTitle">
    <div class="modal-head"><h2 id="pTitle">Pay Salaries — <?= html_escape($label) ?></h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="pForm" novalidate>
      <div class="modal-body">
        <div class="grid g4" style="margin-top:12px">
          <div><label class="field-label" for="pDate">Date <span class="req">*</span></label><input class="field-input" type="date" id="pDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
          <div><label class="field-label" for="pMode">Mode <span class="req">*</span></label><select class="field-input" id="pMode"><?php foreach ($modes as $m): ?><option><?= html_escape($m) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="pRef">Reference</label><input class="field-input" id="pRef" maxlength="60" placeholder="UTR / cheque no"></div>
          <div><label class="field-label" for="pRem">Remarks</label><input class="field-input" id="pRem" maxlength="255"></div>
        </div>
        <div class="table-wrap" style="margin-top:14px;max-height:340px;overflow:auto;border:1px solid #e2e8f0;border-radius:10px">
          <table class="table compact"><thead><tr><th>Staff</th><th class="num">Balance</th><th class="num">Pay now</th><th></th></tr></thead><tbody id="pRows"></tbody></table>
        </div>
        <div class="pay-total" style="padding:12px 0 0;border:0"><span class="muted" id="pCount"></span><span class="big" id="pTotal">0.00</span></div>
        <p class="field-error" id="pErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary" id="pSave">Save Payment</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id), esc = U.esc;
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const RUN = <?= (int)$run->prId ?>;
  const post = async (url, fd) => {
    try { return await U.post(url, fd); } catch (e) { return {status: false, msg: 'Your session may have expired. Reload the page and try again.'}; }
  };
  const act = async (url, extra) => {
    const fd = new FormData(); fd.append('id', RUN);
    if (extra) Object.keys(extra).forEach(k => fd.append(k, extra[k]));
    const res = await post(url, fd); res.status ? U.ok(res.msg) : U.err(res.msg);
  };
  const confirm = async (title, text, btn, danger) => (await Swal.fire({title, text, icon: danger ? 'warning' : 'question', showCancelButton: true,
    confirmButtonText: btn, confirmButtonColor: danger ? '#dc2626' : undefined})).isConfirmed;

  const on = (id, fn) => { const el = $(id); if (el) el.addEventListener('click', fn); };
  on('recalcBtn', async () => { if (await confirm('Recalculate payslips?', 'Every payslip is worked out again from the current salary structures and advances. Loss-of-pay days and extra amounts you entered are kept.', 'Recalculate')) act('<?= site_url('payroll_recalc') ?>', {add_missing: 1}); });
  on('attBtn', async () => {
    const r = await Swal.fire({title: 'Load loss-of-pay days from staff attendance?',
      html: 'Each payslip\'s LOP becomes: absent days + half days (½) + unpaid leave, plus days before joining / after relieving. Extra amounts you entered are kept.'
          + '<label style="display:flex;gap:8px;align-items:center;justify-content:center;margin-top:14px"><input type="checkbox" id="swUnmarked"> Also count unmarked working days as loss of pay</label>',
      icon: 'question', showCancelButton: true, confirmButtonText: 'Load',
      preConfirm: () => document.getElementById('swUnmarked').checked});
    if (r.isConfirmed) act('<?= site_url('payroll_load_attendance') ?>', {unmarked: r.value ? '1' : '0'});
  });
  on('approveBtn', async () => { if (await confirm('Approve payroll for <?= html_escape($label) ?>?', 'Payslips will be locked and the salary posted to accounts. You can reopen it until salaries are paid.', 'Approve')) act('<?= site_url('payroll_approve') ?>'); });
  on('reopenBtn', async () => { if (await confirm('Reopen this payroll?', 'It goes back to draft so you can change it. Its accounts entry is cancelled until you approve again.', 'Reopen', true)) act('<?= site_url('payroll_reopen') ?>'); });
  on('deleteBtn', async () => {
    if (!await confirm('Delete payroll for <?= html_escape($label) ?>?', 'All its payslips are deleted.', 'Delete', true)) return;
    const fd = new FormData(); fd.append('id', RUN);
    const res = await post('<?= site_url('payroll_delete') ?>', fd);
    if (!res.status) return U.err(res.msg);
    location.href = '<?= site_url('payroll') ?>';
  });

  /* ---------- edit payslip ---------- */
  const em = $('eModal') ? U.modal('eModal') : null;
  function showLines(s) {
    const sec = (title, type) => {
      const ls = s.lines.filter(l => l.pslType === type);
      if (!ls.length) return '';
      return `<tr class="row-group"><td colspan="2">${title}</td></tr>` + ls.map(l => `<tr><td>${esc(l.pslName)}</td><td class="num">${U.inr(l.pslAmount)}</td></tr>`).join('');
    };
    $('eLines').innerHTML = sec('Earnings', 'EARNING') + `<tr class="row-sub"><td>Gross</td><td class="num">${U.inr(s.psGross)}</td></tr>`
      + sec('Deductions', 'DEDUCTION') + `<tr class="row-sub"><td>Total deductions</td><td class="num">${U.inr(s.psDeduction)}</td></tr>`
      + `<tr class="row-total"><td>Net pay</td><td class="num">${U.inr(s.psNet)}</td></tr>` + sec('Paid by the school', 'EMPLOYER');
  }
  async function openEdit(id) {
    const res = await (await fetch('<?= site_url('payroll_slip') ?>?id=' + id)).json().catch(() => ({status: false, msg: 'Your session may have expired. Reload the page.'}));
    if (!res.status) return U.err(res.msg);
    const s = res.slip, n = v => (+v ? String(+v) : '');
    $('eId').value = s.psId; $('eTitle').textContent = 'Edit Payslip — ' + s.psName;
    $('eLop').value = n(s.psLop); $('eLopHint').textContent = 'Days in month: ' + s.psDays + '. Half days allowed, e.g. 1.5';
    $('eEe').value = n(s.psExtraEarn); $('eEen').value = s.psExtraEarnNote || '';
    $('eEd').value = n(s.psExtraDed); $('eEdn').value = s.psExtraDedNote || '';
    $('eRem').value = s.psRemarks || ''; $('eErr').hidden = true;
    showLines(s); em.show(); $('eLop').focus(); $('eLop').select();
  }
  if ($('eForm')) $('eForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('eId').value); fd.append('lop', $('eLop').value.trim());
    fd.append('extra_earn', $('eEe').value.trim()); fd.append('extra_earn_note', $('eEen').value.trim());
    fd.append('extra_ded', $('eEd').value.trim()); fd.append('extra_ded_note', $('eEdn').value.trim());
    fd.append('remarks', $('eRem').value.trim());
    const res = await post('<?= site_url('payroll_slip_save') ?>', fd);
    if (!res.status) { $('eErr').textContent = res.msg; $('eErr').hidden = false; return; }
    em.hide(); U.ok(res.msg);
  });

  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-id]'); if (!tr) return;
    if (e.target.closest('[data-edit]')) return openEdit(tr.dataset.id);
    if (e.target.closest('[data-rm]')) {
      if (!await confirm('Remove ' + tr.dataset.name + ' from this month?', 'No salary will be paid to them for <?= html_escape($label) ?>. Recalculate adds them back.', 'Remove', true)) return;
      const fd = new FormData(); fd.append('id', tr.dataset.id);
      const res = await post('<?= site_url('payroll_slip_remove') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });

  /* ---------- pay ---------- */
  const all = $('chkAll');
  if (all) all.addEventListener('change', () => document.querySelectorAll('#rows .pick').forEach(c => c.checked = all.checked));
  const pm = $('pModal') ? U.modal('pModal') : null;
  function payTotal() {
    let t = 0, n = 0;
    $('pRows').querySelectorAll('.p-amt').forEach(i => { t += +i.value || 0; n++; });
    $('pTotal').textContent = '₹ ' + U.inr(t); $('pCount').textContent = n + ' staff';
  }
  on('payBtn', () => {
    let picked = Array.from(document.querySelectorAll('#rows .pick:checked')).map(c => c.closest('tr'));
    if (!picked.length) picked = Array.from(document.querySelectorAll('#rows .pick')).map(c => c.closest('tr'));   // nothing ticked: everyone with a balance
    $('pRows').innerHTML = picked.map(tr => `<tr data-id="${tr.dataset.id}"><td>${esc(tr.dataset.name)}</td><td class="num">${U.inr(tr.dataset.bal)}</td>
      <td class="num"><input class="cell-input p-amt" inputmode="decimal" value="${U.r2(tr.dataset.bal)}" aria-label="Amount for ${esc(tr.dataset.name)}"></td>
      <td><button type="button" class="act-btn rm p-del" aria-label="Do not pay ${esc(tr.dataset.name)} now">✕</button></td></tr>`).join('');
    payTotal(); $('pErr').hidden = true; pm.show();
  });
  if ($('pRows')) {
    $('pRows').addEventListener('input', payTotal);
    $('pRows').addEventListener('click', e => { if (e.target.closest('.p-del')) { e.target.closest('tr').remove(); payTotal(); } });
  }
  if ($('pForm')) $('pForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', RUN); fd.append('date', $('pDate').value); fd.append('mode', $('pMode').value);
    fd.append('ref', $('pRef').value.trim()); fd.append('remarks', $('pRem').value.trim());
    if (!$('pRows').rows.length) { $('pErr').textContent = 'No staff selected.'; $('pErr').hidden = false; return; }
    for (const tr of $('pRows').rows) {
      const v = tr.querySelector('.p-amt').value.trim();
      if (!U.isNum(v) || +v <= 0) { $('pErr').textContent = 'Enter the amount for every staff member, or remove them with ✕.'; $('pErr').hidden = false; return; }
      fd.append('slip[]', tr.dataset.id); fd.append('amount[]', v);
    }
    $('pSave').disabled = true;
    const res = await post('<?= site_url('payroll_pay') ?>', fd);
    $('pSave').disabled = false;
    if (!res.status) { $('pErr').textContent = res.msg; $('pErr').hidden = false; return; }
    pm.hide(); U.ok(res.msg);
  });

  const bt = $('batches');
  if (bt) bt.addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-id]'); if (!tr || !e.target.closest('[data-cancel]')) return;
    const reason = await U.askReason('Cancel this salary payment?'); if (!reason) return;
    const fd = new FormData(); fd.append('id', tr.dataset.id); fd.append('reason', reason);
    const res = await post('<?= site_url('payroll_payment_cancel') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
