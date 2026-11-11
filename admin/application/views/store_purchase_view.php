<?php /* $p (with items), $perm */ ?>
<main class="page">
  <div class="page-header no-print">
    <div><h1 class="page-title">Purchase <?= html_escape($p->spNo) ?></h1>
      <p class="page-sub"><?= html_escape($p->ssName) ?> · <?= date('d-m-Y', strtotime($p->spDate)) ?><?= $p->spBillNo ? ' · Bill ' . html_escape($p->spBillNo) : '' ?></p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('store_purchases') ?>">Back</a>
      <button class="btn btn-secondary" type="button" onclick="window.print()">Print</button>
      <?php if (!empty($perm['can_delete']) && !$p->spCancelled): ?><button class="btn btn-danger" type="button" id="cancelBtn">Cancel Purchase</button><?php endif; ?>
    </div>
  </div>
  <div class="card">
    <div class="print-head"><h2>Little Hearts School — Store Purchase</h2><p><?= html_escape($p->spNo) ?> · <?= html_escape($p->ssName) ?> · <?= date('d-m-Y', strtotime($p->spDate)) ?></p></div>
    <?php if ($p->spCancelled): ?><div class="notice bad-txt">Cancelled — <?= html_escape($p->spCancelReason) ?></div><?php endif; ?>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>#</th><th>Code</th><th>Item</th><th class="num">Qty</th><th class="num">Rate</th><th class="num">Tax %</th><th class="num">Tax</th><th class="num">Amount</th></tr></thead>
        <tbody>
        <?php foreach ($p->items as $n => $it): ?>
          <tr><td><?= $n + 1 ?></td><td><?= html_escape($it->siCode) ?></td>
            <td><?= html_escape($it->siName . ($it->siSize ? ' (' . $it->siSize . ')' : '')) ?></td>
            <td class="num"><?= (float)$it->spiQty ?> <?= html_escape($it->siUnit) ?></td><td class="num"><?= fee_money($it->spiRate) ?></td>
            <td class="num"><?= (float)$it->spiTaxPercent ?></td><td class="num"><?= fee_money($it->spiTax) ?></td>
            <td class="num"><?= fee_money($it->spiAmount + $it->spiTax) ?></td></tr>
        <?php endforeach; ?>
          <tr class="row-total"><td colspan="6">Total</td><td class="num"><?= fee_money($p->spTax) ?></td><td class="num">₹<?= fee_money($p->spTotal) ?></td></tr>
        </tbody>
      </table>
    </div>
    <?php if ($p->spRemarks): ?><div style="padding:14px 20px" class="muted">Remarks: <?= html_escape($p->spRemarks) ?></div><?php endif; ?>
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
    const why = await U.askReason('Cancel purchase <?= html_escape($p->spNo) ?>? The quantities will be removed from stock.');
    if (!why) return;
    const fd = new FormData(); fd.append('id', <?= (int)$p->spId ?>); fd.append('reason', why);
    const res = await U.post('<?= site_url('store_purchase_cancel') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
