<?php /* $rows, $f, $perm, $sync, $ledgers, $types */
$can_add = !empty($perm['can_add']);
$total = 0; foreach ($rows as $v) if (!$v->avCancelled) $total += $v->avAmount;
$badge = array('RECEIPT' => 'badge-green', 'PAYMENT' => 'badge-red', 'CONTRA' => 'badge-info', 'JOURNAL' => 'badge-purple', 'SALES' => 'badge-amber', 'PURCHASE' => 'badge-gray');
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Vouchers</h1>
      <p class="page-sub">Receipts, payments, contra and journal entries. Fee and store transactions are posted automatically<?= !empty($sync['posted']) ? ' — ' . (int)$sync['posted'] . ' new posted just now' : '' ?>.</p></div>
    <?php if ($can_add): ?>
    <div class="page-actions">
      <a class="btn btn-primary" href="<?= site_url('acc_voucher_new') ?>?type=PAYMENT">+ Payment</a>
      <a class="btn btn-secondary" href="<?= site_url('acc_voucher_new') ?>?type=RECEIPT">+ Receipt</a>
      <a class="btn btn-secondary" href="<?= site_url('acc_voucher_new') ?>?type=CONTRA">+ Contra</a>
      <a class="btn btn-secondary" href="<?= site_url('acc_voucher_new') ?>?type=JOURNAL">+ Journal</a>
    </div>
    <?php endif; ?>
  </div>
  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('acc_vouchers') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Voucher no, narration or reference…">
        </div>
        <div class="fb"><label for="from">From</label><input class="field-input" type="date" id="from" name="from" value="<?= html_escape($f['from']) ?>"></div>
        <div class="fb"><label for="to">To</label><input class="field-input" type="date" id="to" name="to" value="<?= html_escape($f['to']) ?>"></div>
        <div class="fb"><label for="type">Type</label>
          <select class="field-input" id="type" name="type"><option value="">All</option>
            <?php foreach ($types as $k => $t): ?><option value="<?= $k ?>" <?= $f['type'] === $k ? 'selected' : '' ?>><?= $t['label'] ?></option><?php endforeach; ?>
          </select></div>
        <div class="fb"><label for="ledger">Account</label>
          <select class="field-input" id="ledger" name="ledger" style="max-width:220px"><option value="">All</option>
            <?php foreach ($ledgers as $l): ?><option value="<?= (int)$l->alId ?>" <?= (int)$f['ledger'] === (int)$l->alId ? 'selected' : '' ?>><?= html_escape($l->alName) ?></option><?php endforeach; ?>
          </select></div>
        <div class="fb"><label for="auto">Entry</label>
          <select class="field-input" id="auto" name="auto"><option value="">All</option>
            <option value="0" <?= $f['auto'] === '0' ? 'selected' : '' ?>>Manual</option><option value="1" <?= $f['auto'] === '1' ? 'selected' : '' ?>>Automatic</option>
          </select></div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a class="btn btn-secondary" href="<?= site_url('acc_vouchers') ?>">Clear</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Date</th><th>Voucher No</th><th>Type</th><th>Debit</th><th>Credit</th><th>Narration</th><th class="num">Amount</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $v): ?>
          <tr class="<?= $v->avCancelled ? 'row-cancel' : '' ?>">
            <td><?= date('d-m-Y', strtotime($v->avDate)) ?></td>
            <td><strong><?= html_escape($v->avNo) ?></strong></td>
            <td><span class="badge <?= $badge[$v->avType] ?>"><?= $types[$v->avType]['label'] ?></span>
              <?php if ($v->avSource): ?><div class="small muted">Auto · <?= html_escape(Accounts_model::$sources[$v->avSource]) ?></div><?php endif; ?></td>
            <td class="small"><?= html_escape($v->dr_names) ?></td>
            <td class="small"><?= html_escape($v->cr_names) ?></td>
            <td class="small"><?= html_escape($v->avNarration) ?><?= $v->avCancelled ? ' <span class="badge badge-red">Cancelled</span>' : '' ?></td>
            <td class="num"><strong><?= fee_money($v->avAmount) ?></strong></td>
            <td><a class="act-btn dl" href="<?= site_url('acc_voucher/' . (int)$v->avId) ?>">View</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$rows): ?><div class="empty">No vouchers in this period.</div><?php endif; ?>
    </div>
    <div class="table-foot"><span class="muted"><?= count($rows) ?> vouchers<?= count($rows) >= 500 ? ' (first 500 — narrow the filter)' : '' ?></span><strong>Total ₹<?= fee_money($total) ?></strong></div>
  </div>
</main>
