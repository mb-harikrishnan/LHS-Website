<?php
/* Fee Register: find a student, see dues (incl. previous years), collect full / partial payment with fine
   $classes, $div_map, $modes, $student (preselect id), $perm, $year */
$can_collect = !empty($perm['can_add']);
?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Fee Register</h1>
      <p class="page-sub">Collect fees for <?= html_escape($year ? $year->amYear : '') ?> — monthly, quarterly or annual installments, partial payments and old balance.</p>
    </div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('fee_management') ?>">Receipts</a>
    </div>
  </div>

  <div class="reg-layout">
    <!-- student finder -->
    <div class="card">
      <div class="table-toolbar" style="flex-direction:column;align-items:stretch">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" id="q" placeholder="Name or admission no…" aria-label="Find student" autocomplete="off">
        </div>
        <div class="act-row">
          <select class="field-input" id="fCls" style="flex:1" aria-label="Class">
            <option value="">All Classes</option>
            <?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option><?php endforeach; ?>
          </select>
          <select class="field-input" id="fDiv" style="flex:1" aria-label="Division"><option value="">All Div.</option></select>
        </div>
      </div>
      <div class="stu-list" id="stuList"><div class="notice">Type a name or choose a class.</div></div>
    </div>

    <!-- student fee card -->
    <div class="card" id="panel">
      <div class="notice" id="panelEmpty">Select a student to see the fee details.</div>
      <div id="panelBody" hidden>
        <div class="stu-head">
          <div>
            <h3 id="sName"></h3>
            <div class="muted small" id="sMeta"></div>
          </div>
          <div class="act-row">
            <a class="btn btn-secondary btn-sm" id="ledgerLink" href="#" target="_blank">Student Fee Register</a>
          </div>
        </div>
        <div class="sum-grid">
          <div class="sum-item"><small>Total Fee (year)</small><strong id="sTotal"></strong></div>
          <div class="sum-item ok"><small>Paid</small><strong id="sPaid"></strong></div>
          <div class="sum-item warn"><small>Due till date</small><strong id="sDue"></strong></div>
          <div class="sum-item"><small>Balance (year)</small><strong id="sBal"></strong></div>
          <div class="sum-item bad"><small>Old Balance</small><strong id="sOld"></strong></div>
          <div class="sum-item"><small>Fine</small><strong id="sFine"></strong></div>
        </div>

        <div class="table-toolbar">
          <?php if ($can_collect): ?>
          <div class="act-row" style="align-items:center">
            <button type="button" class="btn btn-secondary btn-sm" id="selDue">Select dues till date</button>
            <button type="button" class="btn btn-secondary btn-sm" id="selAll">Select all</button>
            <button type="button" class="btn btn-ghost btn-sm" id="selNone">Clear</button>
          </div>
          <div class="act-row" style="align-items:center;margin-left:auto">
            <input class="field-input" id="recv" style="width:140px" inputmode="decimal" placeholder="Amount received" aria-label="Amount received">
            <label class="check small"><input type="checkbox" id="withFine" checked> with fine</label>
            <button type="button" class="btn btn-primary btn-sm" id="distribute">Distribute</button>
          </div>
          <?php endif; ?>
          <label class="check small"><input type="checkbox" id="showPaid"> Show paid installments</label>
        </div>

        <div class="table-wrap">
          <table class="table compact">
            <thead><tr>
              <th style="width:36px"></th><th>Fee</th><th>Period</th><th>Due Date</th>
              <th class="num">Amount</th><th class="num">Paid</th><th class="num">Balance</th>
              <th class="num">Fine</th><th class="num">Pay Now</th>
            </tr></thead>
            <tbody id="dues"></tbody>
          </table>
          <div class="notice" id="noDues" hidden>No fees allotted to this student. Allot fees from the student form or Fee Allocation.</div>
        </div>

        <?php if ($can_collect): ?>
        <div class="pay-bar">
          <div><label class="field-label" for="rDate">Receipt Date</label><input class="field-input" type="date" id="rDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
          <div><label class="field-label" for="rMode">Payment Mode</label>
            <select class="field-input" id="rMode"><?php foreach ($modes as $m): ?><option><?= html_escape($m) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="rRef">Reference / Txn No</label><input class="field-input" id="rRef" maxlength="100"></div>
          <div><label class="field-label" for="rRem">Remarks</label><input class="field-input" id="rRem" maxlength="500"></div>
        </div>
        <div class="pay-total">
          <div class="muted">Fee <strong id="tAmt">0.00</strong> + Fine <strong id="tFine">0.00</strong></div>
          <div class="act-row" style="align-items:center">
            <span class="big">₹<span id="tTotal">0.00</span></span>
            <button type="button" class="btn btn-primary" id="saveBtn">Save &amp; Print Receipt</button>
          </div>
        </div>
        <?php endif; ?>

        <div class="sec-title" style="margin:6px 20px 0">Receipts this year</div>
        <div class="table-wrap">
          <table class="table compact"><thead><tr><th>Receipt No</th><th>Date</th><th>Mode</th><th class="num">Amount</th><th></th></tr></thead>
            <tbody id="rcpts"></tbody></table>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const URLS = {
    students: '<?= site_url('fee_students') ?>',
    dues: '<?= site_url('fee_student_dues') ?>',
    collect: '<?= site_url('fee_collect') ?>',
    receipt: '<?= site_url('fee_receipt') ?>/',
    ledger: '<?= site_url('reports/student_fee_register') ?>'
  };
  const CAN_COLLECT = <?= $can_collect ? 'true' : 'false' ?>;
  const DIV_MAP = <?= json_encode($div_map) ?>;
  const CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  let CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const inr = n => Number(n || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  const r2 = n => Math.round((+n || 0) * 100) / 100;
  const dmy = d => d ? d.split('-').reverse().join('-') : '';
  const today = '<?= date('Y-m-d') ?>';

  let current = null;   // loaded student id
  let DUES = [];

  /* ---------- finder ---------- */
  function fillDiv() {
    const list = DIV_MAP[$('fCls').value] || [];
    $('fDiv').innerHTML = '<option value="">All Div.</option>' + list.map(d => `<option value="${esc(d.id)}">${esc(d.name)}</option>`).join('');
  }
  let timer;
  function findStudents() {
    clearTimeout(timer);
    timer = setTimeout(async () => {
      const q = $('q').value.trim(), c = $('fCls').value, d = $('fDiv').value;
      if (!q && !c) { $('stuList').innerHTML = '<div class="notice">Type a name or choose a class.</div>'; return; }
      const p = new URLSearchParams({q, class: c, division: d});
      const list = await (await fetch(URLS.students + '?' + p)).json();
      $('stuList').innerHTML = list.length ? list.map(s =>
        `<button type="button" class="stu-item${s.id === current ? ' active' : ''}" data-id="${s.id}">
           <strong>${esc(s.name)}</strong><small>${esc(s.adm)} · ${esc(s.class)}</small></button>`).join('')
        : '<div class="notice">No students found.</div>';
    }, 250);
  }
  $('q').addEventListener('input', findStudents);
  $('fCls').addEventListener('change', () => { fillDiv(); findStudents(); });
  $('fDiv').addEventListener('change', findStudents);
  $('stuList').addEventListener('click', e => {
    const b = e.target.closest('.stu-item'); if (!b) return;
    document.querySelectorAll('.stu-item').forEach(x => x.classList.toggle('active', x === b));
    loadStudent(+b.dataset.id);
  });

  /* ---------- student dues ---------- */
  async function loadStudent(id) {
    current = id;
    const date = $('rDate') ? $('rDate').value : today;
    const res = await (await fetch(URLS.dues + '?' + new URLSearchParams({student: id, date}))).json();
    if (!res.status) return Swal.fire({icon: 'error', title: 'Error', text: res.msg});
    const s = res.student, m = res.summary;
    $('panelEmpty').hidden = true; $('panelBody').hidden = false;
    $('sName').textContent = s.name;
    $('sMeta').textContent = [s.adm, s.class, s.father ? 'Parent: ' + s.father : '', s.mobile ? 'Ph: ' + s.mobile : ''].filter(Boolean).join(' · ');
    $('ledgerLink').href = URLS.ledger + '?student=' + s.id;
    $('sTotal').textContent = '₹' + inr(m.total);
    $('sPaid').textContent = '₹' + inr(m.paid);
    $('sDue').textContent = '₹' + inr(m.due_now);
    $('sBal').textContent = '₹' + inr(m.balance);
    $('sOld').textContent = '₹' + inr(m.old);
    $('sFine').textContent = '₹' + inr(m.fine);
    DUES = res.dues;
    renderDues();
    $('rcpts').innerHTML = res.receipts.length ? res.receipts.map(r =>
      `<tr class="${r.cancelled ? 'row-cancel' : ''}"><td>${esc(r.no)}</td><td>${dmy(r.date)}</td><td>${esc(r.mode)}</td>
       <td class="num">₹${inr(r.total)}</td><td>${r.cancelled ? '<span class="badge badge-red">Cancelled</span>' :
       `<a class="act-btn dl" target="_blank" href="${URLS.receipt + r.id}">Print</a>`}</td></tr>`).join('')
      : '<tr><td colspan="5" class="muted">No receipts yet.</td></tr>';
  }

  function renderDues() {
    const showPaid = $('showPaid').checked;
    const rows = DUES.filter(d => showPaid || d.balance > 0);
    $('noDues').hidden = DUES.length > 0;
    $('dues').innerHTML = rows.map(d => {
      const open = d.balance > 0;
      const tag = !d.current ? ` <span class="badge badge-red">${esc(d.year)}</span>` : '';
      const st = !open ? '<span class="badge badge-green">Paid</span>' : (d.paid > 0 ? '<span class="badge badge-amber">Part paid</span>' : '');
      return `<tr data-id="${d.id}">
        <td>${open && CAN_COLLECT ? `<input type="checkbox" class="pick" aria-label="Select">` : ''}</td>
        <td>${esc(d.fee)}${d.special ? ' <span class="badge badge-purple">Special</span>' : ''}${tag}</td>
        <td>${esc(d.period)} ${st}</td>
        <td class="${d.overdue ? 'bad-txt' : ''}">${dmy(d.due_date)}</td>
        <td class="num">${inr(d.amount)}</td>
        <td class="num">${inr(d.paid)}</td>
        <td class="num"><strong>${inr(d.balance)}</strong></td>
        <td class="num">${open && CAN_COLLECT ? `<input class="cell-input fine" inputmode="decimal" value="" placeholder="${d.fine ? inr(d.fine) : '0'}" data-def="${d.fine}">` : (d.fine ? inr(d.fine) : '—')}</td>
        <td class="num">${open && CAN_COLLECT ? `<input class="cell-input pay" inputmode="decimal" value="" data-max="${d.balance}">` : ''}</td>
      </tr>`;
    }).join('');
    totals();
  }
  $('showPaid').addEventListener('change', renderDues);

  /* ---------- selecting / distributing ---------- */
  const rowEls = () => Array.from(document.querySelectorAll('#dues tr')).filter(tr => tr.querySelector('.pay'));
  const due = tr => DUES.find(d => d.id === +tr.dataset.id);
  function setRow(tr, on, amt, fine) {
    const d = due(tr);
    tr.querySelector('.pick').checked = on;
    tr.querySelector('.pay').value = on ? r2(amt != null ? amt : d.balance) : '';
    tr.querySelector('.fine').value = on ? r2(fine != null ? fine : d.fine) : '';
  }
  function totals() {
    let a = 0, f = 0;
    rowEls().forEach(tr => { a += +tr.querySelector('.pay').value || 0; f += +tr.querySelector('.fine').value || 0; });
    if (!$('tAmt')) return;
    $('tAmt').textContent = inr(a); $('tFine').textContent = inr(f); $('tTotal').textContent = inr(a + f);
  }
  const tbody = $('dues');
  tbody.addEventListener('change', e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    if (e.target.classList.contains('pick')) setRow(tr, e.target.checked);
    totals();
  });
  tbody.addEventListener('input', e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    if (e.target.classList.contains('pay') || e.target.classList.contains('fine')) {
      const any = (+tr.querySelector('.pay').value || 0) + (+tr.querySelector('.fine').value || 0) > 0;
      tr.querySelector('.pick').checked = any;
    }
    totals();
  });

  if (CAN_COLLECT) {
    $('selDue').addEventListener('click', () => {
      const date = $('rDate').value || today;
      rowEls().forEach(tr => setRow(tr, due(tr).due_date <= date)); totals();
    });
    $('selAll').addEventListener('click', () => { rowEls().forEach(tr => setRow(tr, true)); totals(); });
    $('selNone').addEventListener('click', () => { rowEls().forEach(tr => setRow(tr, false)); totals(); });

    // oldest installment first; fine of a row is taken before its fee when "with fine" is ticked
    $('distribute').addEventListener('click', () => {
      let left = r2($('recv').value);
      if (!(left > 0)) return Swal.fire({icon: 'info', title: 'Amount', text: 'Enter the amount received.'});
      const withFine = $('withFine').checked;
      rowEls().forEach(tr => {
        const d = due(tr);
        if (left <= 0) return setRow(tr, false);
        const fine = withFine ? Math.min(d.fine, left) : 0; left = r2(left - fine);
        const amt = Math.min(d.balance, left); left = r2(left - amt);
        if (amt + fine > 0) setRow(tr, true, amt, fine); else setRow(tr, false);
      });
      totals();
      if (left > 0) Swal.fire({icon: 'warning', title: 'Extra amount', text: '₹' + inr(left) + ' is more than all the dues and was not used.'});
    });

    $('rDate').addEventListener('change', () => { if (current) loadStudent(current); });

    $('saveBtn').addEventListener('click', async () => {
      const fd = new FormData();
      let total = 0, err = '';
      rowEls().forEach(tr => {
        const d = due(tr), a = r2(tr.querySelector('.pay').value), f = r2(tr.querySelector('.fine').value);
        if (a < 0 || f < 0 || isNaN(a) || isNaN(f)) err = 'Amounts cannot be negative.';
        if (a > d.balance + 0.001) err = d.fee + ' ' + d.period + ': pay amount is more than the balance.';
        if (a + f > 0) { fd.append('pay[' + d.id + ']', a); fd.append('fine[' + d.id + ']', f); total += a + f; }
      });
      if (err) return Swal.fire({icon: 'error', title: 'Check amounts', text: err});
      if (total <= 0) return Swal.fire({icon: 'info', title: 'Nothing to collect', text: 'Select installments or enter the amount to pay.'});
      if ($('rMode').value !== 'Cash' && !$('rRef').value.trim()) return Swal.fire({icon: 'info', title: 'Reference', text: 'Enter the reference / transaction number.'});

      const ok = await Swal.fire({title: 'Collect ₹' + inr(total) + '?', text: $('sName').textContent + ' · ' + $('rMode').value,
                                  icon: 'question', showCancelButton: true, confirmButtonText: 'Save'});
      if (!ok.isConfirmed) return;

      fd.append('student', current); fd.append('date', $('rDate').value); fd.append('mode', $('rMode').value);
      fd.append('reference', $('rRef').value.trim()); fd.append('remarks', $('rRem').value.trim());
      fd.append(CSRF_NAME, CSRF_HASH);
      $('saveBtn').disabled = true;
      try {
        const res = await (await fetch(URLS.collect, {method: 'POST', body: fd})).json();
        if (res.csrf) CSRF_HASH = res.csrf;
        if (!res.status) return Swal.fire({icon: 'error', title: 'Not saved', text: res.msg});
        window.open(res.print_url, '_blank');
        Swal.fire({icon: 'success', title: 'Saved', text: res.msg, timer: 1600, showConfirmButton: false});
        $('recv').value = ''; $('rRef').value = ''; $('rRem').value = '';
        loadStudent(current);
      } catch (e) { Swal.fire({icon: 'error', title: 'Error', text: 'Something went wrong.'}); }
      finally { $('saveBtn').disabled = false; }
    });
  }

  const PRE = <?= (int)$student ?>;
  if (PRE) loadStudent(PRE);
})();
</script>
