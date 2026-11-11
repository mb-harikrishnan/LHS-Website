<?php /* $settings, $cashbank, $pending, $posted, $perm, $sources */
$can = !empty($perm['can_edit']) || !empty($perm['can_add']);
$pendingTotal = array_sum($pending);
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Account Settings</h1><p class="page-sub">Books start date, where collections are deposited, and automatic posting from Fees and Store.</p></div>
  </div>

  <div class="card section-card">
    <h3 class="section-title"><span class="section-title-bar"></span>Settings</h3>
    <div class="section-body">
      <div class="grid g3">
        <div><label class="field-label" for="sStart">Books start date</label>
          <input class="field-input" type="date" id="sStart" value="<?= html_escape(isset($settings['books_start']) ? $settings['books_start'] : '') ?>" <?= $can ? '' : 'disabled' ?>>
          <span class="field-hint">Opening balances are as on this date. Fee / store entries before it are not posted.</span></div>
        <div><label class="field-label" for="sCash">Cash collections go to</label>
          <select class="field-input" id="sCash" <?= $can ? '' : 'disabled' ?>>
            <?php foreach ($cashbank as $l): ?><option value="<?= (int)$l->alId ?>" <?= (string)$l->alId === (string)(isset($settings['cash_ledger']) ? $settings['cash_ledger'] : '') ? 'selected' : '' ?>><?= html_escape($l->alName) ?></option><?php endforeach; ?>
          </select></div>
        <div><label class="field-label" for="sBank">UPI / card / cheque / transfer go to</label>
          <select class="field-input" id="sBank" <?= $can ? '' : 'disabled' ?>>
            <?php foreach ($cashbank as $l): ?><option value="<?= (int)$l->alId ?>" <?= (string)$l->alId === (string)(isset($settings['bank_ledger']) ? $settings['bank_ledger'] : '') ? 'selected' : '' ?>><?= html_escape($l->alName) ?></option><?php endforeach; ?>
          </select></div>
        <label class="check full"><input type="checkbox" id="sAuto" <?= (isset($settings['auto_post']) ? $settings['auto_post'] : '1') === '1' ? 'checked' : '' ?> <?= $can ? '' : 'disabled' ?>>
          Post fee receipts and store transactions to accounts automatically</label>
      </div>
      <?php if ($can): ?><div style="margin-top:16px"><button type="button" class="btn btn-primary" id="saveBtn">Save Settings</button></div><?php endif; ?>
    </div>
  </div>

  <div class="card section-card">
    <h3 class="section-title"><span class="section-title-bar"></span>Automatic posting</h3>
    <div class="section-body">
      <table class="table compact">
        <thead><tr><th>From</th><th class="num">Vouchers posted</th><th class="num">Cancelled</th></tr></thead>
        <tbody>
        <?php foreach ($sources as $k => $label): $p = isset($posted[$k]) ? $posted[$k] : array('n' => 0, 'c' => 0); ?>
          <tr><td><?= html_escape($label) ?></td><td class="num"><?= $p['n'] ?></td><td class="num"><?= $p['c'] ?></td></tr>
        <?php endforeach; ?>
          <tr><td>Manual vouchers</td><td class="num"><?= isset($posted['MANUAL']) ? $posted['MANUAL']['n'] : 0 ?></td><td class="num"><?= isset($posted['MANUAL']) ? $posted['MANUAL']['c'] : 0 ?></td></tr>
        </tbody>
      </table>
      <p style="margin-top:14px">Waiting to be posted:
        <?php if (!$pendingTotal): ?><span class="badge badge-green">Nothing — up to date</span>
        <?php else: foreach ($pending as $k => $n): if (!$n) continue; ?><span class="badge badge-amber"><?= html_escape($k) ?>: <?= (int)$n ?></span> <?php endforeach; endif; ?>
      </p>
      <p class="field-hint">Posting also runs whenever Vouchers, Chart of Accounts, Banking or Finance Reports is opened.</p>
      <button type="button" class="btn btn-secondary" id="syncBtn">Post now</button>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const save = $('saveBtn');
  if (save) save.addEventListener('click', async () => {
    const fd = new FormData();
    fd.append('books_start', $('sStart').value); fd.append('cash_ledger', $('sCash').value); fd.append('bank_ledger', $('sBank').value);
    if ($('sAuto').checked) fd.append('auto_post', 1);
    const res = await U.post('<?= site_url('acc_settings_save') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
  $('syncBtn').addEventListener('click', async () => {
    $('syncBtn').disabled = true;
    const res = await U.post('<?= site_url('acc_sync') ?>', new FormData());
    res.status ? U.ok(res.msg) : U.err(res.msg);
    $('syncBtn').disabled = false;
  });
})();
</script>
