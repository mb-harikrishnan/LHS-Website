<?php
/* Fee Master
   $fees (with class_amounts, rates, alloc_count), $classes, $years, $year, $am_id, $frequencies, $fine_types, $amount_types, $perm */
$can_add  = !empty($perm['can_add']);
$can_edit = !empty($perm['can_edit']);
$can_del  = !empty($perm['can_delete']);
$months   = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
$classes_js = array();
foreach ($classes as $c) $classes_js[] = array('id' => (string)$c->cmId, 'name' => $c->cmName);
?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Fee Master</h1>
      <p class="page-sub">Fees for academic year <strong><?= html_escape($year ? $year->amYear : '-') ?></strong>. Amount is for one installment.</p>
    </div>
    <div class="page-actions">
      <?php if ($can_add): ?>
        <button class="btn btn-secondary" type="button" id="openCopy">Copy from another year</button>
        <button class="btn btn-primary" type="button" id="openAdd">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
          Add Fee
        </button>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th><th>Fee Name</th><th>Collected</th><th class="num">Amount / Inst.</th><th class="num">Yearly Total</th>
            <th>First Due</th><th>Fine</th><th class="num">Students</th><th>Status</th><th>Action</th>
          </tr>
        </thead>
        <tbody id="rows">
        <?php foreach ($fees as $i => $f):
            $fq = isset($frequencies[$f->fmFrequency]) ? $frequencies[$f->fmFrequency]['label'] : $f->fmFrequency;
            $fine = '—';
            if ($f->fmFineType === 'FIXED')   $fine = '₹' . fee_money($f->fmFineAmount) . ' once';
            if ($f->fmFineType === 'PER_DAY') $fine = '₹' . fee_money($f->fmFineAmount) . '/day' . ((float)$f->fmFineMax > 0 ? ' (max ₹' . fee_money($f->fmFineMax) . ')' : '');
            if ($f->fmFineType !== 'NONE' && (int)$f->fmFineGraceDays > 0) $fine .= ' after ' . (int)$f->fmFineGraceDays . 'd';
            $variable = $f->fmAmountType === 'VARIABLE';
            $ramts = array_map(function ($x) { return $x->amount; }, $f->rates);
            $rmin = $ramts ? min($ramts) : null;
            $rmax = $ramts ? max($ramts) : null;
            $row = array(
              'id' => (int)$f->fmId, 'name' => $f->fmName, 'amount' => (float)$f->fmAmount, 'description' => (string)$f->fmDescription,
              'frequency' => $f->fmFrequency, 'installments' => (int)$f->fmInstallments, 'start_month' => (int)$f->fmStartMonth,
              'due_day' => (int)$f->fmDueDay, 'is_special' => (int)$f->fmIsSpecial, 'auto_assign' => (int)$f->fmAutoAssign,
              'fine_type' => $f->fmFineType, 'fine_amount' => (float)$f->fmFineAmount, 'fine_grace' => (int)$f->fmFineGraceDays,
              'fine_max' => (float)$f->fmFineMax, 'order' => (int)$f->fmOrder, 'active' => (int)$f->fmActive,
              'class_amounts' => (object)$f->class_amounts, 'alloc_count' => (int)$f->alloc_count,
              'amount_type' => $f->fmAmountType, 'rates' => $f->rates, 'rate_usage' => (object)$f->rate_usage,
            );
        ?>
          <tr data-f="<?= html_escape(json_encode($row)) ?>">
            <td><?= $i + 1 ?></td>
            <td>
              <strong><?= html_escape($f->fmName) ?></strong>
              <?php if ($f->fmIsSpecial): ?> <span class="badge badge-purple">Special</span><?php endif; ?>
              <?php if ($f->fmAutoAssign): ?> <span class="badge badge-info">Auto</span><?php endif; ?>
              <?php if ($f->fmAmountType === 'VARIABLE'): ?> <span class="badge badge-amber">Varies</span><?php endif; ?>
              <?php if ($variable): ?><div class="small muted">Varies per student<?= $f->rates ? ' · ' . count($f->rates) . ' rate(s)' : ' · amount typed per student' ?></div>
              <?php elseif ($f->class_amounts): ?><div class="small muted">Class-wise amounts set for <?= count($f->class_amounts) ?> class(es)</div><?php endif; ?>
            </td>
            <td><?= html_escape($fq) ?> × <?= (int)$f->fmInstallments ?></td>
            <?php if ($variable): ?>
              <td class="num"><?= $rmin === null ? 'Varies' : '₹' . fee_money($rmin) . ($rmax > $rmin ? ' – ₹' . fee_money($rmax) : '') ?></td>
              <td class="num">—</td>
            <?php else: ?>
              <td class="num">₹<?= fee_money($f->fmAmount) ?></td>
              <td class="num">₹<?= fee_money($f->fmAmount * $f->fmInstallments) ?></td>
            <?php endif; ?>
            <td><?= (int)$f->fmDueDay ?> <?= substr($months[(int)$f->fmStartMonth] ?? '', 0, 3) ?></td>
            <td class="small"><?= html_escape($fine) ?></td>
            <td class="num"><?= (int)$f->alloc_count ?></td>
            <td><?= $f->fmActive ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' ?></td>
            <td>
              <div class="act-row">
                <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
                <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($fees)): ?>
        <div class="empty">No fees for this academic year<?= $can_add ? '. Add one, or copy them from another year.' : '.' ?></div>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Add / Edit -->
<div class="modal-overlay" id="formModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="formTitle">
    <div class="modal-head"><h2 id="formTitle">Add Fee</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="feeForm" novalidate>
      <input type="hidden" id="fId" value="0">
      <div class="modal-body">
        <div class="sec-title">Fee</div>
        <div class="grid">
          <div><label class="field-label" for="fName">Fee Name <span class="req">*</span></label>
            <input class="field-input" id="fName" maxlength="100" placeholder="e.g. Tuition Fee"></div>
          <div><label class="field-label" for="fAType">Amount <span class="req">*</span></label>
            <select class="field-input" id="fAType">
              <?php foreach ($amount_types as $k => $l): ?><option value="<?= $k ?>"><?= html_escape($l) ?></option><?php endforeach; ?>
            </select></div>
          <div><label class="field-label" for="fAmount" id="amtLabel">Amount per Installment (₹) <span class="req">*</span></label>
            <input class="field-input" id="fAmount" inputmode="decimal" placeholder="0.00">
            <span class="field-hint" id="yearTotal"></span></div>
          <div class="full"><label class="field-label" for="fDesc">Description</label>
            <input class="field-input" id="fDesc" maxlength="500"></div>
        </div>

        <div class="sec-title">Collection Schedule</div>
        <div class="grid g4">
          <div><label class="field-label" for="fFreq">Collected <span class="req">*</span></label>
            <select class="field-input" id="fFreq">
              <?php foreach ($frequencies as $k => $q): ?><option value="<?= $k ?>" data-max="<?= $q['max'] ?>" data-def="<?= $q['default'] ?>"><?= $q['label'] ?></option><?php endforeach; ?>
            </select></div>
          <div><label class="field-label" for="fInst">Installments <span class="req">*</span></label>
            <input class="field-input" id="fInst" type="number" min="1" max="12"></div>
          <div><label class="field-label" for="fMonth">First Installment Month</label>
            <select class="field-input" id="fMonth">
              <?php foreach ($months as $n => $m): ?><option value="<?= $n ?>"><?= $m ?></option><?php endforeach; ?>
            </select></div>
          <div><label class="field-label" for="fDay">Due Day of Month</label>
            <input class="field-input" id="fDay" type="number" min="1" max="28"></div>
        </div>
        <p class="field-hint" id="schedHint"></p>

        <div class="sec-title">Late Fine</div>
        <div class="grid g4">
          <div><label class="field-label" for="fFineType">Fine</label>
            <select class="field-input" id="fFineType">
              <?php foreach ($fine_types as $k => $l): ?><option value="<?= $k ?>"><?= $l ?></option><?php endforeach; ?>
            </select></div>
          <div data-fine><label class="field-label" for="fFineAmt">Fine Amount (₹)</label>
            <input class="field-input" id="fFineAmt" inputmode="decimal"></div>
          <div data-fine><label class="field-label" for="fGrace">Grace Days</label>
            <input class="field-input" id="fGrace" type="number" min="0" max="365"></div>
          <div data-fine-max><label class="field-label" for="fFineMax">Maximum Fine (₹)</label>
            <input class="field-input" id="fFineMax" inputmode="decimal" placeholder="0 = no limit"></div>
        </div>

        <div id="rateSec" hidden>
          <div class="sec-title">Rates <span class="muted" style="text-transform:none;font-weight:400">(stops or distance slabs — amount per installment)</span></div>
          <table class="table compact" style="margin-bottom:8px">
            <thead><tr><th>Stop / Distance</th><th class="num" style="width:160px">Amount (₹)</th><th class="num" style="width:90px">Students</th><th style="width:60px"></th></tr></thead>
            <tbody id="rateRows"></tbody>
          </table>
          <button type="button" class="btn btn-secondary btn-sm" id="addRate">+ Add rate</button>
          <p class="field-hint">Students are given one of these rates in Fee Allocation or the student form, or an amount typed for them. Leave the list empty to type every student's amount.</p>
        </div>

        <div id="classSec">
          <div class="sec-title">Class-wise Amount <span class="muted" style="text-transform:none;font-weight:400">(leave blank to use the amount above)</span></div>
          <div class="grid g4" id="classAmounts"></div>
        </div>

        <div class="sec-title">Options</div>
        <div class="grid">
          <label class="check"><input type="checkbox" id="fAuto"> Select automatically when adding a student</label>
          <label class="check"><input type="checkbox" id="fSpecial"> Special fee (shown in Special Fee registers)</label>
          <label class="check"><input type="checkbox" id="fActive" checked> Active</label>
          <div><label class="field-label" for="fOrder">Display Order</label><input class="field-input" id="fOrder" type="number" min="0" style="max-width:120px"></div>
          <label class="check full" id="applyWrap" hidden><input type="checkbox" id="fApply"> Also update the amount for students already allotted this fee (paid installments are not reduced)</label>
        </div>

        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Copy -->
<div class="modal-overlay" id="copyModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true">
    <div class="modal-head"><h2>Copy Fees</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <div class="modal-body">
      <p class="muted small">Copies every fee (with class-wise amounts) from the selected year into <strong><?= html_escape($year ? $year->amYear : '') ?></strong>. Fees with the same name are skipped.</p>
      <label class="field-label" for="copyFrom">Copy from</label>
      <select class="field-input" id="copyFrom">
        <?php foreach ($years as $y): if ((int)$y->amId === (int)$am_id) continue; ?>
          <option value="<?= (int)$y->amId ?>"><?= html_escape($y->amYear) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="modal-foot"><button type="button" class="btn" data-close>Cancel</button><button type="button" class="btn btn-primary" id="copyBtn">Copy</button></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  let CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  const CLASSES = <?= json_encode($classes_js) ?>;
  const MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const STEP = {MONTHLY:1, QUARTERLY:3, HALF_YEARLY:6, ANNUAL:12, ONE_TIME:12};
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const show = m => m.classList.add('open'), hide = m => m.classList.remove('open');
  const formModal = $('formModal'), copyModal = $('copyModal'), errEl = $('formError');
  const showErr = m => { errEl.textContent = m; errEl.hidden = false; };
  const inr = n => Number(n || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

  $('classAmounts').innerHTML = CLASSES.map(c =>
    `<div><label class="field-label" for="ca${esc(c.id)}">${esc(c.name)}</label>
     <input class="field-input" id="ca${esc(c.id)}" data-class="${esc(c.id)}" inputmode="decimal"></div>`).join('');

  function refreshHints() {
    const f = $('fFreq').value, opt = $('fFreq').selectedOptions[0], max = +opt.dataset.max;
    $('fInst').max = max;
    if (+$('fInst').value > max) $('fInst').value = max;
    $('fInst').disabled = max === 1;
    if (max === 1) $('fInst').value = 1;
    const n = +$('fInst').value || 1, m = +$('fMonth').value, d = +$('fDay').value || 10;
    const months = [];
    for (let i = 0; i < n; i++) months.push(MONTHS[(m - 1 + i * STEP[f]) % 12]);
    $('schedHint').textContent = 'Due dates: ' + months.map(x => d + ' ' + x).join(', ');
    const amt = parseFloat($('fAmount').value) || 0;
    $('yearTotal').textContent = n > 1 ? `${n} installments → ₹${inr(amt * n)} for the year` : '';
    const variable = $('fAType').value === 'VARIABLE';
    $('rateSec').hidden = !variable;
    $('classSec').hidden = variable;
    $('amtLabel').innerHTML = variable ? 'Default Amount (₹) <span class="muted">(optional)</span>' : 'Amount per Installment (₹) <span class="req">*</span>';
    if (variable) $('yearTotal').textContent = 'Used when a student has no rate selected.';
    const ft = $('fFineType').value;
    document.querySelectorAll('[data-fine]').forEach(x => x.hidden = ft === 'NONE');
    document.querySelector('[data-fine-max]').hidden = ft !== 'PER_DAY';
  }
  ['fFreq','fInst','fMonth','fDay','fAmount','fFineType','fAType'].forEach(id => $(id).addEventListener('input', refreshHints));
  $('fAType').addEventListener('change', refreshHints);

  /* ---------- rates editor ---------- */
  let USAGE = {};
  function rateRow(r) {
    const used = r && r.id && USAGE[r.id] ? USAGE[r.id] : 0;
    const tr = document.createElement('tr');
    tr.dataset.id = r && r.id ? r.id : 0;
    tr.innerHTML = `<td><input class="field-input r-name" maxlength="100" placeholder="e.g. Kottayam Town / 0-5 km" value="${esc(r ? r.name : '')}"></td>
      <td class="num"><input class="field-input r-amt" inputmode="decimal" style="text-align:right" value="${r ? r.amount : ''}"></td>
      <td class="num">${used || '—'}</td>
      <td><button type="button" class="act-btn rm r-del" ${used ? 'disabled title="Used by students"' : ''}>✕</button></td>`;
    $('rateRows').appendChild(tr);
    return tr;
  }
  $('addRate').addEventListener('click', () => rateRow(null).querySelector('.r-name').focus());
  $('rateRows').addEventListener('click', e => { const b = e.target.closest('.r-del'); if (b && !b.disabled) b.closest('tr').remove(); });
  $('fFreq').addEventListener('change', () => { $('fInst').value = $('fFreq').selectedOptions[0].dataset.def; refreshHints(); });

  function openForm(f) {
    errEl.hidden = true;
    $('fId').value = f ? f.id : 0;
    $('formTitle').textContent = f ? 'Edit Fee' : 'Add Fee';
    $('fName').value = f ? f.name : '';
    $('fAType').value = f ? f.amount_type : 'FIXED';
    $('fAmount').value = f ? (f.amount_type === 'VARIABLE' && !f.amount ? '' : f.amount) : '';
    USAGE = f && f.rate_usage ? f.rate_usage : {};
    $('rateRows').innerHTML = '';
    (f && f.rates ? f.rates : []).forEach(rateRow);
    $('fDesc').value = f ? f.description : '';
    $('fFreq').value = f ? f.frequency : 'MONTHLY';
    $('fInst').value = f ? f.installments : 12;
    $('fMonth').value = f ? f.start_month : 6;
    $('fDay').value = f ? f.due_day : 10;
    $('fFineType').value = f ? f.fine_type : 'NONE';
    $('fFineAmt').value = f && f.fine_amount ? f.fine_amount : '';
    $('fGrace').value = f ? f.fine_grace : 0;
    $('fFineMax').value = f && f.fine_max ? f.fine_max : '';
    $('fAuto').checked = f ? !!f.auto_assign : false;
    $('fSpecial').checked = f ? !!f.is_special : false;
    $('fActive').checked = f ? !!f.active : true;
    $('fOrder').value = f ? f.order : 0;
    $('fApply').checked = false;
    $('applyWrap').hidden = !(f && f.alloc_count > 0);
    document.querySelectorAll('[data-class]').forEach(i => {
      const v = f && f.class_amounts ? f.class_amounts[i.dataset.class] : undefined;
      i.value = v !== undefined ? v : '';
    });
    refreshHints();
    show(formModal); $('fName').focus();
  }

  const addBtn = $('openAdd'); if (addBtn) addBtn.addEventListener('click', () => openForm(null));
  const copyOpen = $('openCopy'); if (copyOpen) copyOpen.addEventListener('click', () => show(copyModal));
  [formModal, copyModal].forEach(m => {
    m.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => hide(m)));
    m.addEventListener('click', e => { if (e.target === m) hide(m); });
  });

  async function post(url, fd) {
    fd.append(CSRF_NAME, CSRF_HASH);
    const res = await (await fetch(url, {method: 'POST', body: fd})).json();
    if (res.csrf) CSRF_HASH = res.csrf;
    return res;
  }
  const done = res => Swal.fire({icon: 'success', title: 'Done', text: res.msg, timer: 1500, showConfirmButton: false}).then(() => location.reload());

  $('feeForm').addEventListener('submit', async e => {
    e.preventDefault();
    if (!$('fName').value.trim()) return showErr('Enter the fee name.');
    const variable = $('fAType').value === 'VARIABLE';
    const amtTxt = $('fAmount').value.trim();
    if (!(variable && amtTxt === '') && !/^\d+(\.\d{1,2})?$/.test(amtTxt)) return showErr('Enter a valid amount.');
    let rateErr = '';
    const rateLines = variable ? Array.from($('rateRows').querySelectorAll('tr')).map(tr => ({
      id: tr.dataset.id, name: tr.querySelector('.r-name').value.trim(), amount: tr.querySelector('.r-amt').value.trim()
    })).filter(r => r.name !== '' || r.amount !== '') : [];
    rateLines.forEach(r => {
      if (!r.name) rateErr = 'Every rate needs a stop / distance name.';
      else if (!/^\d+(\.\d{1,2})?$/.test(r.amount)) rateErr = 'Enter a valid amount for ' + r.name + '.';
    });
    if (rateErr) return showErr(rateErr);
    const fd = new FormData();
    fd.append('id', $('fId').value);
    fd.append('name', $('fName').value.trim());
    fd.append('amount', amtTxt);
    fd.append('amount_type', $('fAType').value);
    rateLines.forEach(r => { fd.append('rate_id[]', r.id); fd.append('rate_name[]', r.name); fd.append('rate_amount[]', r.amount); });
    fd.append('description', $('fDesc').value.trim());
    fd.append('frequency', $('fFreq').value);
    fd.append('installments', $('fInst').value);
    fd.append('start_month', $('fMonth').value);
    fd.append('due_day', $('fDay').value);
    fd.append('fine_type', $('fFineType').value);
    fd.append('fine_amount', $('fFineAmt').value.trim());
    fd.append('fine_grace', $('fGrace').value || 0);
    fd.append('fine_max', $('fFineMax').value.trim());
    fd.append('order', $('fOrder').value || 0);
    if ($('fAuto').checked) fd.append('auto_assign', 1);
    if ($('fSpecial').checked) fd.append('is_special', 1);
    if ($('fActive').checked) fd.append('active', 1);
    if ($('fApply').checked) fd.append('apply_existing', 1);
    document.querySelectorAll('[data-class]').forEach(i => { if (i.value.trim() !== '') fd.append('class_amount[' + i.dataset.class + ']', i.value.trim()); });

    $('saveBtn').disabled = true;
    try {
      const res = await post('<?= site_url('fee_save') ?>', fd);
      if (!res.status) return showErr(res.msg);
      hide(formModal); done(res);
    } catch (err) { showErr('Something went wrong. Please try again.'); }
    finally { $('saveBtn').disabled = false; }
  });

  $('copyBtn').addEventListener('click', async () => {
    const fd = new FormData(); fd.append('from_year', $('copyFrom').value);
    const res = await post('<?= site_url('fee_copy') ?>', fd);
    hide(copyModal);
    if (!res.status) return Swal.fire({icon: 'error', title: 'Error', text: res.msg});
    done(res);
  });

  $('rows').addEventListener('click', e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const f = JSON.parse(tr.dataset.f);
    if (e.target.closest('[data-edit]')) return openForm(f);
    if (e.target.closest('[data-del]')) {
      Swal.fire({title: 'Delete ' + f.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'})
        .then(async r => {
          if (!r.isConfirmed) return;
          const fd = new FormData(); fd.append('id', f.id);
          const res = await post('<?= site_url('fee_delete') ?>', fd);
          if (!res.status) return Swal.fire({icon: 'error', title: 'Error', text: res.msg});
          done(res);
        });
    }
  });
})();
</script>
