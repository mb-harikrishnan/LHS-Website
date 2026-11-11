<?php
/* Fee Allocation: class + division -> students x active fees grid
   $fees (active, with rates), $classes, $div_map, $filters, $students, $grid [smId][fmId]{amount,rate,paid},
   $defaults [fmId => amount for this class], $perm */
$can_save = !empty($perm['can_add']) || !empty($perm['can_edit']);

$fees_js = array();
foreach ($fees as $f) {
    $fees_js[] = array(
        'id'       => (int)$f->fmId,
        'name'     => $f->fmName,
        'variable' => $f->fmAmountType === 'VARIABLE',
        'rates'    => $f->rates,
        'inst'     => (int)$f->fmInstallments,
        'freq'     => isset(Fee_model::$frequencies[$f->fmFrequency]) ? Fee_model::$frequencies[$f->fmFrequency]['label'] : $f->fmFrequency,
        'default'  => isset($defaults[(int)$f->fmId]) ? $defaults[(int)$f->fmId] : (float)$f->fmAmount,
        'special'  => (int)$f->fmIsSpecial,
    );
}
$students_js = array();
foreach ($students as $s) {
    $students_js[] = array('id' => (int)$s->smId, 'adm' => $s->smAdmissionNo, 'name' => $s->smName, 'div' => $s->dmName);
}
?>
<style>
.alloc-wrap{overflow:auto;max-height:70vh}
.alloc{border-collapse:separate;border-spacing:0;font-size:13px;min-width:100%}
.alloc th,.alloc td{padding:7px 8px;border-bottom:1px solid #e2e8f0;border-right:1px solid #f1f5f9;background:#fff;white-space:nowrap;vertical-align:middle}
.alloc thead th{position:sticky;top:0;z-index:2;background:#f8fafc;font-size:12px;vertical-align:top}
.alloc .stick{position:sticky;left:0;z-index:1}
.alloc thead .stick{z-index:3}
.alloc .fee-h{min-width:170px}
.alloc .fee-h small{display:block;font-weight:400;color:#64748b;text-transform:none;letter-spacing:0}
.alloc .fee-h .bulk{display:flex;gap:4px;margin-top:6px;align-items:center}
.alloc .fee-h select{height:var(--control-h-sm);font-size:12px;padding:0 4px;border:1px solid #cbd5e1;border-radius:6px;max-width:120px}
.alloc .cell{display:flex;gap:5px;align-items:center}
.alloc .cell input[type=checkbox]{width:16px;height:16px;flex:none}
.alloc .cell input.amt{width:78px;height:var(--control-h-sm);padding:0 6px;border:1px solid #cbd5e1;border-radius:6px;text-align:right;font:inherit;font-size:12.5px}
.alloc .cell select{width:110px;height:var(--control-h-sm);padding:0 4px;border:1px solid #cbd5e1;border-radius:6px;font-size:12px}
.alloc .cell.off input.amt,.alloc .cell.off select{opacity:.35}
.alloc td.on{background:#f5f7ff}
.alloc .paid{display:block;font-size:11px;color:#047857}
.alloc .tot{font-weight:700;text-align:right}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Fee Allocation</h1>
      <p class="page-sub">Choose a class and division, then tick the fees for each student. Fees with a varying amount (e.g. conveyance) take a stop / distance rate or a typed amount.</p>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('fee_allocation') ?>">
        <div class="fb"><label for="cls">Class</label>
          <select class="field-input" name="class" id="cls" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= (int)$c->cmId ?>" <?= (string)$filters['class'] === (string)$c->cmId ? 'selected' : '' ?>><?= html_escape($c->cmName) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="fb"><label for="div">Division</label>
          <select class="field-input" name="division" id="div"><option value="">All Divisions</option></select></div>
        <button type="submit" class="btn btn-primary">Load Students</button>
      </form>
    </div>

    <?php if ($filters['class'] === ''): ?>
      <div class="notice">Select a class (and division) to load the students.</div>
    <?php elseif (empty($fees)): ?>
      <div class="notice">There are no active fees for this academic year. Add them in Fee Master first.</div>
    <?php elseif (empty($students)): ?>
      <div class="notice">No students found in this class / division for the current academic year.</div>
    <?php else: ?>
      <div class="alloc-wrap">
        <table class="alloc" id="grid"><thead></thead><tbody></tbody></table>
      </div>
      <div class="table-foot">
        <span class="muted small"><?= count($students) ?> students · Unticking removes the fee and its unpaid installments. Paid fees are locked.</span>
        <?php if ($can_save): ?><button type="button" class="btn btn-primary" id="saveBtn">Save Allocation</button><?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const DIV_MAP = <?= json_encode($div_map) ?>;
  const SEL_DIV = <?= json_encode((string)$filters['division']) ?>;
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const inr = n => Number(n || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

  function fillDiv(v) {
    const list = DIV_MAP[$('cls').value] || [];
    $('div').innerHTML = '<option value="">All Divisions</option>' + list.map(d => `<option value="${esc(d.id)}">${esc(d.name)}</option>`).join('');
    $('div').value = v || '';
  }
  $('cls').addEventListener('change', () => fillDiv(''));
  fillDiv(SEL_DIV);

  const grid = $('grid');
  if (!grid) return;

  const FEES = <?= json_encode($fees_js) ?>;
  const STUDENTS = <?= json_encode($students_js) ?>;
  const GRID = <?= json_encode((object)$grid) ?>;
  const CAN_SAVE = <?= $can_save ? 'true' : 'false' ?>;
  const feeById = {}; FEES.forEach(f => feeById[f.id] = f);
  const rateOf = (f, id) => f.rates.find(r => r.id === +id);

  /* ---------- header ---------- */
  grid.tHead.innerHTML = '<tr><th class="stick">#</th><th>Adm No</th><th>Name</th><th>Div</th>' +
    FEES.map(f => `<th class="fee-h" data-fee="${f.id}">
      <label class="check"><input type="checkbox" class="col-all" aria-label="Tick all for ${esc(f.name)}"> ${esc(f.name)}</label>
      <small>${esc(f.freq)} × ${f.inst}${f.special ? ' · Special' : ''}${f.variable ? ' · varies' : ' · ₹' + inr(f.default)}</small>
      ${f.variable && f.rates.length ? `<div class="bulk"><select class="col-rate"><option value="">Set rate…</option>${
        f.rates.map(r => `<option value="${r.id}">${esc(r.name)} (₹${inr(r.amount)})</option>`).join('')}</select>
        <button type="button" class="act-btn ed col-apply" title="Apply to ticked students">Apply</button></div>` : ''}
    </th>`).join('') + '<th class="tot">Year Total</th></tr>';

  /* ---------- rows ---------- */
  grid.tBodies[0].innerHTML = STUDENTS.map((s, i) => {
    const cur = GRID[s.id] || {};
    return `<tr data-s="${s.id}"><td class="stick">${i + 1}</td><td>${esc(s.adm)}</td><td>${esc(s.name)}</td><td>${esc(s.div)}</td>` +
      FEES.map(f => {
        const a = cur[f.id];
        const on = !!a, locked = a && a.paid > 0;
        const amt = a ? a.amount : (f.variable && f.rates.length ? '' : f.default);
        const sel = f.variable && f.rates.length ? `<select class="rate" ${on ? '' : 'tabindex="-1"'}><option value="">Typed amount</option>${
          f.rates.map(r => `<option value="${r.id}" ${a && a.rate === r.id ? 'selected' : ''}>${esc(r.name)}</option>`).join('')}</select>` : '';
        return `<td data-f="${f.id}" class="${on ? 'on' : ''}"><div class="cell ${on ? '' : 'off'}">
          <input type="checkbox" class="pick" ${on ? 'checked' : ''} ${locked ? 'disabled title="Already paid"' : ''}>
          ${sel}<input class="amt" inputmode="decimal" value="${amt}" placeholder="₹"></div>
          ${locked ? `<span class="paid">₹${inr(a.paid)} paid</span>` : ''}</td>`;
      }).join('') + '<td class="tot"></td></tr>';
  }).join('');

  /* ---------- behaviour ---------- */
  function setOn(td, on) {
    const pick = td.querySelector('.pick');
    if (pick.disabled) return;
    pick.checked = on;
    td.classList.toggle('on', on);
    td.querySelector('.cell').classList.toggle('off', !on);
  }
  function rowTotal(tr) {
    let t = 0;
    tr.querySelectorAll('td[data-f]').forEach(td => {
      if (td.querySelector('.pick').checked) t += (parseFloat(td.querySelector('.amt').value) || 0) * feeById[td.dataset.f].inst;
    });
    tr.querySelector('.tot').textContent = t ? '₹' + inr(t) : '—';
  }
  const allRows = () => Array.from(grid.tBodies[0].rows);
  allRows().forEach(rowTotal);

  grid.tBodies[0].addEventListener('change', e => {
    const td = e.target.closest('td[data-f]'); if (!td) return;
    const f = feeById[td.dataset.f];
    if (e.target.classList.contains('pick')) setOn(td, e.target.checked);
    if (e.target.classList.contains('rate')) {
      const r = rateOf(f, e.target.value);
      if (r) td.querySelector('.amt').value = r.amount;
      setOn(td, true);
    }
    rowTotal(td.closest('tr'));
  });
  grid.tBodies[0].addEventListener('input', e => {
    if (!e.target.classList.contains('amt')) return;
    const td = e.target.closest('td[data-f]');
    const f = feeById[td.dataset.f];
    // a typed amount that differs from the chosen rate becomes a typed amount
    const sel = td.querySelector('.rate');
    if (sel && sel.value) { const r = rateOf(f, sel.value); if (r && parseFloat(e.target.value) !== r.amount) sel.value = ''; }
    if (e.target.value.trim() !== '') setOn(td, true);
    rowTotal(td.closest('tr'));
  });

  grid.tHead.addEventListener('change', e => {
    if (!e.target.classList.contains('col-all')) return;
    const fid = e.target.closest('th').dataset.fee;
    allRows().forEach(tr => { setOn(tr.querySelector(`td[data-f="${fid}"]`), e.target.checked); rowTotal(tr); });
  });
  grid.tHead.addEventListener('click', e => {
    if (!e.target.classList.contains('col-apply')) return;
    const th = e.target.closest('th'), fid = th.dataset.fee, rid = th.querySelector('.col-rate').value;
    const r = rateOf(feeById[fid], rid);
    if (!r) return Swal.fire({icon: 'info', title: 'Select a rate', text: 'Choose the stop / distance rate to apply.'});
    let n = 0;
    allRows().forEach(tr => {
      const td = tr.querySelector(`td[data-f="${fid}"]`);
      if (!td.querySelector('.pick').checked) return;
      td.querySelector('.rate').value = r.id; td.querySelector('.amt').value = r.amount; n++;
      rowTotal(tr);
    });
    if (!n) Swal.fire({icon: 'info', title: 'No students ticked', text: 'Tick the students first, then apply the rate.'});
  });

  /* ---------- save ---------- */
  const saveBtn = $('saveBtn');
  if (!CAN_SAVE || !saveBtn) return;
  saveBtn.addEventListener('click', async () => {
    const fd = new FormData();
    FEES.forEach(f => fd.append('fees_listed[]', f.id));
    let err = '';
    allRows().forEach(tr => {
      const sid = tr.dataset.s;
      fd.append('listed[]', sid);
      tr.querySelectorAll('td[data-f]').forEach(td => {
        if (!td.querySelector('.pick').checked) return;
        const f = feeById[td.dataset.f], amt = td.querySelector('.amt').value.trim(), sel = td.querySelector('.rate');
        if (!/^\d+(\.\d{1,2})?$/.test(amt)) err = 'Enter a valid ' + f.name + ' amount for ' + tr.cells[2].textContent + '.';
        fd.append(`alloc[${sid}][${f.id}][amount]`, amt);
        if (sel && sel.value) fd.append(`alloc[${sid}][${f.id}][rate]`, sel.value);
      });
    });
    if (err) return Swal.fire({icon: 'error', title: 'Check amounts', text: err});
    fd.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');

    saveBtn.disabled = true;
    try {
      const res = await (await fetch('<?= site_url('fee_allocation_save') ?>', {method: 'POST', body: fd})).json();
      Swal.fire({icon: res.status ? 'success' : 'error', title: res.status ? 'Saved' : 'Error', text: res.msg})
        .then(() => { if (res.status) location.reload(); });
    } catch (e) { Swal.fire({icon: 'error', title: 'Error', text: 'Something went wrong.'}); }
    finally { saveBtn.disabled = false; }
  });
})();
</script>
