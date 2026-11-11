<?php /* $v (with entries), $perm, $types, $user_name */
$manual = !$v->avSource;
$dr = 0; $cr = 0; foreach ($v->entries as $e) { $dr += $e->aeDr; $cr += $e->aeCr; }
?>
<main class="page">
  <div class="page-header no-print">
    <div><h1 class="page-title"><?= html_escape($types[$v->avType]['label']) ?> Voucher <?= html_escape($v->avNo) ?></h1>
      <p class="page-sub"><?= date('d-m-Y', strtotime($v->avDate)) ?><?= $v->avSource ? ' · posted automatically from ' . html_escape(Accounts_model::$sources[$v->avSource]) : '' ?></p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('acc_vouchers') ?>">Back</a>
      <button class="btn btn-secondary" type="button" onclick="window.print()">Print</button>
      <?php if ($manual && !$v->avCancelled && !empty($perm['can_edit']) && in_array($v->avType, array('PAYMENT', 'RECEIPT', 'CONTRA', 'JOURNAL'), true)): ?>
        <a class="btn btn-secondary" href="<?= site_url('acc_voucher_edit/' . (int)$v->avId) ?>">Edit</a>
      <?php endif; ?>
      <?php if ($manual && !$v->avCancelled && !empty($perm['can_delete'])): ?><button class="btn btn-danger" type="button" id="cancelBtn">Cancel Voucher</button><?php endif; ?>
    </div>
  </div>
  <div class="card">
    <div class="print-head">
      <h2>Little Hearts School</h2>
      <p><strong><?= html_escape(strtoupper($types[$v->avType]['label'])) ?> VOUCHER</strong></p>
      <p>No. <?= html_escape($v->avNo) ?> · Date <?= date('d-m-Y', strtotime($v->avDate)) ?><?= $v->avRef ? ' · Ref ' . html_escape($v->avRef) : '' ?></p>
    </div>
    <?php if ($v->avCancelled): ?><div class="notice bad-txt">Cancelled — <?= html_escape($v->avCancelReason) ?></div><?php endif; ?>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Account</th><th>Group</th><th>Note</th><th class="num">Debit (₹)</th><th class="num">Credit (₹)</th></tr></thead>
        <tbody>
        <?php foreach ($v->entries as $e): ?>
          <tr><td><?= $e->aeCr > 0 ? '&nbsp;&nbsp;&nbsp;&nbsp;To ' : '' ?><strong><?= html_escape($e->alName) ?></strong></td><td class="small muted"><?= html_escape($e->agName) ?></td>
            <td class="small"><?= html_escape($e->aeNarration) ?></td>
            <td class="num"><?= $e->aeDr > 0 ? fee_money($e->aeDr) : '' ?></td><td class="num"><?= $e->aeCr > 0 ? fee_money($e->aeCr) : '' ?></td></tr>
        <?php endforeach; ?>
          <tr class="row-total"><td colspan="3">Total</td><td class="num"><?= fee_money($dr) ?></td><td class="num"><?= fee_money($cr) ?></td></tr>
        </tbody>
      </table>
    </div>
    <div style="padding:14px 20px">
      <p><strong>Narration:</strong> <?= html_escape($v->avNarration ?: '—') ?></p>
      <p><strong>Amount in words:</strong> <?= html_escape(fee_words($v->avAmount)) ?></p>
      <?php if ($v->avRef): ?><p class="no-print"><strong>Reference:</strong> <?= html_escape($v->avRef) ?></p><?php endif; ?>
      <p class="muted small">Entered by <?= html_escape($user_name) ?></p>
    </div>
    <div class="print-head" style="padding:40px 20px 10px">
      <div style="display:flex;justify-content:space-between;font-size:12px"><span>Prepared by</span><span>Checked by</span><span>Approved by</span><span>Receiver's signature</span></div>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, b = document.getElementById('cancelBtn');
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  if (!b) return;
  b.addEventListener('click', async () => {
    const why = await U.askReason('Cancel voucher <?= html_escape($v->avNo) ?>?');
    if (!why) return;
    const fd = new FormData(); fd.append('id', <?= (int)$v->avId ?>); fd.append('reason', why);
    const res = await U.post('<?= site_url('acc_voucher_cancel') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
