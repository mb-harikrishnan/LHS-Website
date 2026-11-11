<?php /* $l (bank ledger), $as_of, $rows (unreconciled entries), $perm, $book (signed balance per books) */
$can = !empty($perm['can_edit']) || !empty($perm['can_add']);
$dep = 0; $chq = 0;
foreach ($rows as $r) { $dep += $r->aeDr; $chq += $r->aeCr; }
$bankBal = $book - $dep + $chq;
$drcr = function ($n) { return fee_money(abs($n)) . ($n < 0 ? ' Cr' : ''); };
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Bank Reconciliation — <?= html_escape($l->alName) ?></h1>
      <p class="page-sub">Enter the date each entry appears on the bank statement. Entries without a bank date are not yet cleared.</p></div>
    <div class="page-actions"><a class="btn btn-secondary" href="<?= site_url('acc_banking') ?>">Back to Banking</a></div>
  </div>
  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('acc_reconcile/' . (int)$l->alId) ?>">
        <div class="fb"><label for="asof">Statement date</label><input class="field-input" type="date" id="asof" name="as_of" value="<?= html_escape($as_of) ?>"></div>
        <button type="submit" class="btn btn-primary">Show</button>
      </form>
    </div>
    <div class="sum-grid">
      <div class="sum-item"><small>Balance as per books</small><strong>₹<?= $drcr($book) ?></strong></div>
      <div class="sum-item warn"><small>Less: deposits not yet credited</small><strong>₹<?= fee_money($dep) ?></strong></div>
      <div class="sum-item warn"><small>Add: payments not yet debited</small><strong>₹<?= fee_money($chq) ?></strong></div>
      <div class="sum-item ok"><small>Balance as per bank (expected)</small><strong>₹<?= $drcr($bankBal) ?></strong></div>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Date</th><th>Voucher</th><th>Particulars</th><th>Cheque / Ref</th><th class="num">Deposit</th><th class="num">Withdrawal</th><th>Bank Date</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($rows as $r): ?>
          <tr data-ae="<?= (int)$r->aeId ?>" data-min="<?= html_escape($r->avDate) ?>">
            <td><?= date('d-m-Y', strtotime($r->avDate)) ?></td>
            <td><a href="<?= site_url('acc_voucher/' . (int)$r->avId) ?>"><?= html_escape($r->avNo) ?></a></td>
            <td class="small"><?= html_escape($r->other) ?><?= $r->avNarration ? '<div class="muted">' . html_escape($r->avNarration) . '</div>' : '' ?></td>
            <td class="small"><?= html_escape($r->avRef) ?></td>
            <td class="num"><?= $r->aeDr > 0 ? fee_money($r->aeDr) : '' ?></td>
            <td class="num"><?= $r->aeCr > 0 ? fee_money($r->aeCr) : '' ?></td>
            <td><input type="date" class="field-input sm clr" style="width:150px" min="<?= html_escape($r->avDate) ?>" value="<?= html_escape($r->aeClearDate) ?>" <?= $can ? '' : 'disabled' ?>></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$rows): ?><div class="empty">Everything up to <?= date('d-m-Y', strtotime($as_of)) ?> is reconciled.</div><?php endif; ?>
    </div>
    <?php if ($rows && $can): ?>
    <div class="table-foot">
      <button type="button" class="btn btn-secondary btn-sm" id="fillAll">Set statement date on all</button>
      <button type="button" class="btn btn-primary" id="saveBtn">Save Bank Dates</button>
    </div>
    <?php endif; ?>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const save = $('saveBtn'); if (!save) return;
  $('fillAll').addEventListener('click', () => document.querySelectorAll('.clr').forEach(i => { if (!i.value) i.value = '<?= html_escape($as_of) ?>'; }));
  save.addEventListener('click', async () => {
    const fd = new FormData(); fd.append('ledger', <?= (int)$l->alId ?>);
    let err = '';
    document.querySelectorAll('#rows tr').forEach(tr => {
      const v = tr.querySelector('.clr').value;
      if (v && v < tr.dataset.min) err = 'A bank date cannot be before its voucher date.';
      fd.append('clear[' + tr.dataset.ae + ']', v);
    });
    if (err) return U.err(err);
    const res = await U.post('<?= site_url('acc_reconcile_save') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
