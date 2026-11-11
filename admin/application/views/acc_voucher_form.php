<?php /* $type, $v (null or voucher with entries), $ledgers [{id,name,group,type,nature}], $types, $books_start, $default_cash, $default_bank */
$labels = array(
    'PAYMENT' => array('acct' => 'Paid from (Cash / Bank)', 'lines' => 'Paid to / Expense account', 'hint' => 'Money going out: salaries, bills, supplier payments, refunds.'),
    'RECEIPT' => array('acct' => 'Received into (Cash / Bank)', 'lines' => 'Received from / Income account', 'hint' => 'Money coming in other than fees and store sales (those are posted automatically): donations, interest, grants.'),
    'CONTRA'  => array('acct' => 'From (Cash / Bank)', 'lines' => '', 'hint' => 'Moving money between cash and bank: cash deposited into bank, cash withdrawn from bank, bank to bank transfer.'),
    'JOURNAL' => array('acct' => '', 'lines' => 'Account', 'hint' => 'Adjustments without cash or bank: depreciation, provisions, corrections, opening entries.'),
);
$L = $labels[$type];
$entries = array();
if ($v) foreach ($v->entries as $e) $entries[] = array('ledger' => (int)$e->aeAlId, 'dr' => (float)$e->aeDr, 'cr' => (float)$e->aeCr, 'note' => (string)$e->aeNarration, 'cb' => in_array($e->alType, array('CASH', 'BANK'), true));
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title"><?= $v ? 'Edit ' . html_escape($v->avNo) : 'New ' . $types[$type]['label'] . ' Voucher' ?></h1><p class="page-sub"><?= html_escape($L['hint']) ?></p></div>
    <div class="page-actions">
      <?php if (!$v): foreach (array('PAYMENT', 'RECEIPT', 'CONTRA', 'JOURNAL') as $t): ?>
        <a class="btn <?= $t === $type ? 'btn-primary' : 'btn-secondary' ?>" href="<?= site_url('acc_voucher_new') ?>?type=<?= $t ?>"><?= $types[$t]['label'] ?></a>
      <?php endforeach; endif; ?>
      <a class="btn btn-secondary" href="<?= site_url('acc_vouchers') ?>">Back</a>
    </div>
  </div>

  <div class="card">
    <div class="doc-head">
      <div><label class="field-label" for="vDate">Date <span class="req">*</span></label>
        <input class="field-input" type="date" id="vDate" min="<?= html_escape($books_start) ?>" value="<?= html_escape($v ? $v->avDate : date('Y-m-d')) ?>"></div>
      <?php if ($type !== 'JOURNAL'): ?>
        <div><label class="field-label" for="vAcct"><?= html_escape($L['acct']) ?> <span class="req">*</span></label><select class="field-input" id="vAcct"></select></div>
      <?php endif; ?>
      <?php if ($type === 'CONTRA'): ?>
        <div><label class="field-label" for="vTo">To (Cash / Bank) <span class="req">*</span></label><select class="field-input" id="vTo"></select></div>
        <div><label class="field-label" for="vAmt">Amount (₹) <span class="req">*</span></label><input class="field-input" id="vAmt" inputmode="decimal"></div>
      <?php endif; ?>
      <div><label class="field-label" for="vRef"><?= $type === 'JOURNAL' ? 'Reference' : 'Cheque / Txn / Bill No' ?></label><input class="field-input" id="vRef" maxlength="60" value="<?= html_escape($v ? $v->avRef : '') ?>"></div>
    </div>

    <?php if ($type !== 'CONTRA'): ?>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th style="min-width:260px"><?= html_escape($L['lines']) ?></th>
          <?php if ($type === 'JOURNAL'): ?><th class="num">Debit (₹)</th><th class="num">Credit (₹)</th><?php else: ?><th class="num">Amount (₹)</th><?php endif; ?>
          <th>Line note</th><th></th></tr></thead>
        <tbody id="lines"></tbody>
      </table>
    </div>
    <div class="doc-add"><button type="button" class="btn btn-secondary btn-sm" id="addLine">+ Add line</button></div>
    <?php endif; ?>

    <div class="doc-total">
      <div><label class="field-label" for="vNarr">Narration</label><textarea class="field-input" id="vNarr" maxlength="500" placeholder="e.g. Salary for October 2026"><?= html_escape($v ? $v->avNarration : '') ?></textarea></div>
      <div class="tot-box">
        <?php if ($type === 'JOURNAL'): ?>
          <div><span>Total Debit</span><strong id="tDr">0.00</strong></div>
          <div><span>Total Credit</span><strong id="tCr">0.00</strong></div>
          <div><span>Difference</span><strong id="tDiff">0.00</strong></div>
        <?php else: ?>
          <div class="grand"><span>Total</span><span>₹<span id="tTot">0.00</span></span></div>
        <?php endif; ?>
        <button type="button" class="btn btn-primary btn-block" id="vSave" style="margin-top:12px">Save Voucher</button>
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
  const TYPE = <?= json_encode($type) ?>, ID = <?= $v ? (int)$v->avId : 0 ?>;
  const LEDGERS = <?= json_encode($ledgers) ?>, ENTRIES = <?= json_encode($entries) ?>;

  // <option>s grouped by account group; cbOnly = cash / bank accounts
  function options(cbOnly, value) {
    const groups = {};
    LEDGERS.filter(l => !cbOnly || l.type === 'CASH' || l.type === 'BANK').forEach(l => (groups[l.group] = groups[l.group] || []).push(l));
    return '<option value="">Select account</option>' + Object.keys(groups).map(g => `<optgroup label="${U.esc(g)}">` +
      groups[g].map(l => `<option value="${l.id}" ${+value === l.id ? 'selected' : ''}>${U.esc(l.name)}</option>`).join('') + '</optgroup>').join('');
  }

  /* ---------- header accounts ---------- */
  const acctEntry = ENTRIES.find(e => e.cb && (TYPE === 'PAYMENT' || TYPE === 'CONTRA' ? e.cr > 0 : e.dr > 0));
  if ($('vAcct')) $('vAcct').innerHTML = options(true, acctEntry ? acctEntry.ledger : <?= (int)$default_cash ?>);
  if (TYPE === 'CONTRA') {
    const toE = ENTRIES.find(e => e.dr > 0);
    $('vTo').innerHTML = options(true, toE ? toE.ledger : <?= (int)$default_bank ?>);
    if (toE) $('vAmt').value = toE.dr;
  }

  /* ---------- lines ---------- */
  const body = $('lines');
  function addLine(l) {
    const tr = document.createElement('tr');
    tr.innerHTML = `<td><select class="field-input sm l-acc">${options(false, l ? l.ledger : '')}</select></td>` +
      (TYPE === 'JOURNAL'
        ? `<td class="num"><input class="cell-input l-dr" inputmode="decimal" value="${l && l.dr ? l.dr : ''}"></td><td class="num"><input class="cell-input l-cr" inputmode="decimal" value="${l && l.cr ? l.cr : ''}"></td>`
        : `<td class="num"><input class="cell-input l-amt" inputmode="decimal" value="${l ? (l.dr || l.cr || '') : ''}"></td>`) +
      `<td><input class="field-input sm l-note" maxlength="255" value="${U.esc(l ? l.note : '')}"></td>
       <td><button type="button" class="act-btn rm l-del" aria-label="Remove line">✕</button></td>`;
    body.appendChild(tr);
    return tr;
  }
  function totals() {
    if (!body) { return; }
    if (TYPE === 'JOURNAL') {
      let d = 0, c = 0;
      body.querySelectorAll('tr').forEach(tr => { d += +tr.querySelector('.l-dr').value || 0; c += +tr.querySelector('.l-cr').value || 0; });
      $('tDr').textContent = U.inr(d); $('tCr').textContent = U.inr(c);
      $('tDiff').textContent = U.inr(Math.abs(d - c)); $('tDiff').className = Math.abs(d - c) > 0.005 ? 'bad-txt' : 'ok-txt';
    } else {
      let t = 0; body.querySelectorAll('.l-amt').forEach(i => t += +i.value || 0);
      $('tTot').textContent = U.inr(t);
    }
  }
  if (body) {
    const own = TYPE === 'JOURNAL' ? ENTRIES : ENTRIES.filter(e => e !== acctEntry);
    if (own.length) own.forEach(addLine); else { addLine(null); if (TYPE === 'JOURNAL') addLine(null); }
    $('addLine').addEventListener('click', () => { addLine(null).querySelector('.l-acc').focus(); });
    body.addEventListener('input', e => {
      if (TYPE === 'JOURNAL') {   // a line is either debit or credit
        const tr = e.target.closest('tr');
        if (e.target.classList.contains('l-dr') && e.target.value) tr.querySelector('.l-cr').value = '';
        if (e.target.classList.contains('l-cr') && e.target.value) tr.querySelector('.l-dr').value = '';
      }
      totals();
    });
    body.addEventListener('click', e => { if (e.target.closest('.l-del') && body.rows.length > 1) { e.target.closest('tr').remove(); totals(); } });
    totals();
  }

  /* ---------- save ---------- */
  $('vSave').addEventListener('click', async () => {
    const fd = new FormData();
    fd.append('id', ID); fd.append('type', TYPE); fd.append('date', $('vDate').value);
    fd.append('narration', $('vNarr').value.trim()); fd.append('ref', $('vRef').value.trim());
    if ($('vAcct')) fd.append('account', $('vAcct').value);
    if (TYPE === 'CONTRA') {
      fd.append('to', $('vTo').value); fd.append('amount', $('vAmt').value.trim());
      if (!U.isNum($('vAmt').value)) return U.err('Enter a valid amount.');
    } else {
      let err = '';
      body.querySelectorAll('tr').forEach(tr => {
        const acc = tr.querySelector('.l-acc').value;
        const vals = TYPE === 'JOURNAL' ? [tr.querySelector('.l-dr').value.trim(), tr.querySelector('.l-cr').value.trim()] : [tr.querySelector('.l-amt').value.trim()];
        if (!acc && vals.every(x => !x)) return;
        vals.forEach(x => { if (x && !U.isNum(x)) err = 'Amounts must be numbers with up to 2 decimals.'; });
        fd.append('ledger[]', acc); fd.append('line_note[]', tr.querySelector('.l-note').value.trim());
        if (TYPE === 'JOURNAL') { fd.append('dr[]', vals[0]); fd.append('cr[]', vals[1]); } else fd.append('amount[]', vals[0]);
      });
      if (err) return U.err(err);
    }
    $('vSave').disabled = true;
    try {
      const res = await U.post('<?= site_url('acc_voucher_save') ?>', fd);
      if (!res.status) return U.err(res.msg);
      Swal.fire({icon: 'success', title: 'Saved', text: res.msg, timer: 1300, showConfirmButton: false})
        .then(() => location.href = '<?= site_url('acc_voucher') ?>/' + res.id);
    } catch (x) { U.err('Something went wrong.'); } finally { $('vSave').disabled = false; }
  });
})();
</script>
