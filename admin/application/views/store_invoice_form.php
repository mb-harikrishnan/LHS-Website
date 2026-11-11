<?php /* New invoice.  $items (picker), $kits (active, with items), $classes, $modes */
$kits_js = array();
foreach ($kits as $k) {
    $lines = array();
    foreach ($k->items as $it) $lines[] = array('id' => (int)$it->skiSiId, 'qty' => (float)$it->skiQty);
    $kits_js[] = array('id' => (int)$k->skId, 'name' => $k->skName, 'class' => (int)$k->skCmId, 'class_name' => (string)$k->cmName, 'items' => $lines);
}
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">New Invoice</h1><p class="page-sub">Sell to a student or a walk-in customer. Leave "Paid now" lower than the total for a part payment.</p></div>
    <div class="page-actions"><a class="btn btn-secondary" href="<?= site_url('store_invoices') ?>">Back to Invoices</a></div>
  </div>
  <div class="card">
    <div class="doc-head">
      <div><label class="field-label">Sold to</label>
        <div class="act-row" style="height:var(--control-h);align-items:center">
          <label class="check"><input type="radio" name="ctype" value="student" checked> Student</label>
          <label class="check"><input type="radio" name="ctype" value="walkin"> Walk-in</label>
        </div></div>
      <div class="stu-pick" id="stuWrap" style="grid-column:span 2"><label class="field-label" for="stuQ">Student <span class="req">*</span></label>
        <input class="field-input" id="stuQ" placeholder="Type name or admission no…" autocomplete="off">
        <div class="stu-drop" id="stuDrop" hidden></div>
        <span class="field-hint" id="stuInfo"></span></div>
      <div id="cusWrap" hidden><label class="field-label" for="cName">Customer Name <span class="req">*</span></label><input class="field-input" id="cName" maxlength="150"></div>
      <div id="phWrap" hidden><label class="field-label" for="cPhone">Phone</label><input class="field-input" id="cPhone" maxlength="20"></div>
      <div><label class="field-label" for="iDate">Date <span class="req">*</span></label><input class="field-input" type="date" id="iDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
    </div>
    <div class="doc-add">
      <input class="field-input" id="iPick" placeholder="Type item name or code, press Enter to add…" autocomplete="off">
      <?php if ($kits_js): ?>
        <select class="field-input" id="kitSel" style="width:auto;max-width:260px" aria-label="Kit">
          <option value="">Add a class kit…</option>
          <?php foreach ($kits_js as $k): ?><option value="<?= $k['id'] ?>"><?= html_escape($k['name']) ?><?= $k['class_name'] ? ' — ' . html_escape($k['class_name']) : '' ?></option><?php endforeach; ?>
        </select>
        <button type="button" class="btn btn-secondary" id="kitAdd">Add Kit</button>
      <?php endif; ?>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Item</th><th class="num">Qty</th><th class="num">Rate (₹)</th><th class="num">Tax %</th><th class="num">Amount</th><th></th></tr></thead>
        <tbody id="iLines"></tbody>
      </table>
      <div class="notice" id="iEmpty">No items added yet.</div>
    </div>
    <div class="doc-total">
      <div>
        <div class="grid">
          <div><label class="field-label" for="pPaid">Paid now (₹)</label><input class="field-input" id="pPaid" inputmode="decimal"></div>
          <div><label class="field-label" for="pMode">Payment Mode</label><select class="field-input" id="pMode"><?php foreach ($modes as $m): ?><option><?= html_escape($m) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="pRef">Reference / Txn No</label><input class="field-input" id="pRef" maxlength="100"></div>
          <div><label class="field-label" for="iRem">Remarks</label><input class="field-input" id="iRem" maxlength="500"></div>
        </div>
        <label class="check small" style="margin-top:10px"><input type="checkbox" id="payFull" checked> Paid in full</label>
      </div>
      <div class="tot-box">
        <div><span>Amount</span><strong id="tSub">0.00</strong></div>
        <div><span>Tax</span><strong id="tTax">0.00</strong></div>
        <div><span>Discount (₹)</span><input class="field-input sm" id="iDisc" inputmode="decimal" style="width:120px;text-align:right" placeholder="0"></div>
        <div class="grand"><span>Total</span><span>₹<span id="tTot">0.00</span></span></div>
        <div><span>Balance</span><strong id="tBal">0.00</strong></div>
        <button type="button" class="btn btn-primary btn-block" id="iSave" style="margin-top:12px">Save &amp; Print Invoice</button>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const ITEMS = <?= json_encode($items) ?>, KITS = <?= json_encode($kits_js) ?>;
  let student = null, totals = {sub: 0, tax: 0, count: 0};

  /* ---------- totals ---------- */
  const total = () => U.r2(Math.max(0, totals.sub + totals.tax - (parseFloat($('iDisc').value) || 0)));
  function refresh() {
    $('tSub').textContent = U.inr(totals.sub); $('tTax').textContent = U.inr(totals.tax); $('tTot').textContent = U.inr(total());
    if ($('payFull').checked) $('pPaid').value = total() || '';
    $('tBal').textContent = U.inr(Math.max(0, total() - (parseFloat($('pPaid').value) || 0)));
    $('iEmpty').hidden = totals.count > 0;
  }
  const ed = U.lines({tbody: $('iLines'), items: ITEMS, mode: 'sale', onChange: t => { totals = t; refresh(); }});
  U.picker($('iPick'), ITEMS, it => { const tr = ed.add(it.id, 1); if (tr) tr.querySelector('.l-qty').select(); }, true);
  $('iDisc').addEventListener('input', refresh);
  $('payFull').addEventListener('change', refresh);
  $('pPaid').addEventListener('input', () => { $('payFull').checked = false; refresh(); });

  /* ---------- kits ---------- */
  const kitAdd = $('kitAdd');
  if (kitAdd) kitAdd.addEventListener('click', () => {
    const k = KITS.find(x => x.id === +$('kitSel').value);
    if (!k) return U.err('Choose a kit.');
    let missing = 0;
    k.items.forEach(l => { if (!ed.add(l.id, l.qty)) missing++; });
    if (missing) U.err(missing + ' item(s) of this kit are inactive and were skipped.');
  });

  /* ---------- customer ---------- */
  document.querySelectorAll('[name=ctype]').forEach(r => r.addEventListener('change', () => {
    const walk = document.querySelector('[name=ctype]:checked').value === 'walkin';
    $('stuWrap').hidden = walk; $('cusWrap').hidden = !walk; $('phWrap').hidden = !walk;
  }));
  let timer;
  $('stuQ').addEventListener('input', () => {
    student = null; $('stuInfo').textContent = '';
    clearTimeout(timer);
    const q = $('stuQ').value.trim();
    if (q.length < 2) { $('stuDrop').hidden = true; return; }
    timer = setTimeout(async () => {
      const list = await (await fetch('<?= site_url('store_students') ?>?q=' + encodeURIComponent(q))).json();
      $('stuDrop').innerHTML = list.length ? list.map((s, i) => `<button type="button" data-i="${i}"><strong>${U.esc(s.name)}</strong>
        <span class="small muted"> · ${U.esc(s.adm)} · ${U.esc(s.class)}</span></button>`).join('') : '<div class="notice">No students found.</div>';
      $('stuDrop').hidden = false;
      $('stuDrop').onclick = e => {
        const b = e.target.closest('button'); if (!b) return;
        student = list[+b.dataset.i];
        $('stuQ').value = student.name + ' (' + student.adm + ')';
        $('stuDrop').hidden = true;
        $('stuInfo').innerHTML = U.esc(student.class) + (student.balance > 0 ? ' · <span class="bad-txt">Previous store balance ₹' + U.inr(student.balance) + '</span>' : '');
        const kit = KITS.find(k => k.class === student.class_id);
        if (kit && $('kitSel') && !totals.count) $('kitSel').value = kit.id;   // suggest the class kit
      };
    }, 250);
  });
  document.addEventListener('click', e => { if (!e.target.closest('#stuWrap')) $('stuDrop').hidden = true; });

  /* ---------- save ---------- */
  $('iSave').addEventListener('click', async () => {
    const walk = document.querySelector('[name=ctype]:checked').value === 'walkin';
    if (!walk && !student) return U.err('Select the student.');
    if (walk && !$('cName').value.trim()) return U.err('Enter the customer name.');
    const disc = $('iDisc').value.trim(), paid = $('pPaid').value.trim();
    if (disc && !U.isNum(disc)) return U.err('Enter a valid discount.');
    if (paid && !U.isNum(paid)) return U.err('Enter a valid amount paid.');
    if ((+paid || 0) > total() + 0.001) return U.err('Amount paid is more than the total.');
    if (+paid > 0 && $('pMode').value !== 'Cash' && !$('pRef').value.trim()) return U.err('Enter the payment reference / transaction no.');

    const fd = new FormData();
    fd.append('date', $('iDate').value);
    fd.append('student', walk ? 0 : student.id);
    fd.append('customer', walk ? $('cName').value.trim() : '');
    fd.append('phone', walk ? $('cPhone').value.trim() : '');
    fd.append('discount', disc); fd.append('paid', paid); fd.append('mode', $('pMode').value);
    fd.append('reference', $('pRef').value.trim()); fd.append('remarks', $('iRem').value.trim());
    const e = ed.fill(fd); if (e) return U.err(e);

    const c = await Swal.fire({title: 'Save invoice of ₹' + U.inr(total()) + '?', text: (+paid || 0) < total() ? 'Balance ₹' + U.inr(total() - (+paid || 0)) + ' will be outstanding.' : 'Paid in full.',
                               icon: 'question', showCancelButton: true, confirmButtonText: 'Save'});
    if (!c.isConfirmed) return;
    $('iSave').disabled = true;
    try {
      const res = await U.post('<?= site_url('store_invoice_save') ?>', fd);
      if (!res.status) return U.err(res.msg);
      window.open(res.print_url, '_blank');
      Swal.fire({icon: 'success', title: 'Saved', text: res.msg, timer: 1400, showConfirmButton: false}).then(() => location.reload());
    } catch (x) { U.err('Something went wrong.'); } finally { $('iSave').disabled = false; }
  });
})();
</script>
