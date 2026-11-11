<?php /* $child, $fee {due_now, balance, old, fine, paid, total, next, dues[]}, $receipts, $fees_on */
$today = date('Y-m-d');
?>
<h1 class="pp-h1">Fees — <?= html_escape($child->smName) ?></h1>
<?php if (!$fees_on || !$fee): ?>
  <div class="pp-card"><div class="pp-empty">Fee details are not available.</div></div>
<?php else: ?>
  <section class="pp-card" style="margin-bottom:16px">
    <div class="pp-card-b">
      <div class="pp-stats" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
        <div class="pp-stat <?= ($fee['due_now'] + $fee['old']) > 0 ? 'bad' : 'ok' ?>"><small>Due now</small><strong>₹<?= fee_money($fee['due_now'] + $fee['old']) ?></strong>
          <span><?= $fee['old'] > 0 ? 'incl. ₹' . fee_money($fee['old']) . ' previous year' : 'installments due till today' ?></span></div>
        <div class="pp-stat"><small>Year total</small><strong>₹<?= fee_money($fee['total']) ?></strong><span><?= html_escape($child->amYear) ?></span></div>
        <div class="pp-stat ok"><small>Paid</small><strong>₹<?= fee_money($fee['paid']) ?></strong><span><?= count($receipts) ?> receipt(s)</span></div>
        <div class="pp-stat <?= $fee['fine'] > 0 ? 'warn' : '' ?>"><small>Late fine</small><strong>₹<?= fee_money($fee['fine']) ?></strong><span>as of today</span></div>
      </div>
      <?php if ($fee['next']): ?><p class="small" style="margin:12px 0 0">Next installment: <strong>₹<?= fee_money($fee['next']->balance) ?></strong> (<?= html_escape($fee['next']->fmName) ?>, <?= html_escape($fee['next']->sfdPeriod) ?>) due on <strong><?= date('d M Y', strtotime($fee['next']->sfdDueDate)) ?></strong>.</p><?php endif; ?>
      <p class="field-hint" style="margin-top:8px">Fees can be paid at the school office. Receipts appear here once payment is recorded.</p>
    </div>
  </section>

  <section class="pp-card" style="margin-bottom:16px">
    <div class="pp-card-h"><h3>Installments</h3></div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Fee</th><th>Period</th><th>Due date</th><th class="num">Amount</th><th class="num">Paid</th><th class="num">Balance</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($fee['dues'] as $d):
          $st = $d->balance <= 0 ? array('Paid', 'badge-green') : ($d->sfdDueDate < $today ? array('Overdue', 'badge-red') : ($d->paid > 0 ? array('Part paid', 'badge-amber') : array('Upcoming', 'badge-gray'))); ?>
          <tr>
            <td><?= html_escape($d->fmName) ?><?= isset($d->fmrName) && $d->fmrName ? '<div class="small muted">' . html_escape($d->fmrName) . '</div>' : '' ?><?= (int)$d->sfdAmId !== (int)$child->smAmId ? ' <span class="badge badge-red">' . html_escape($d->amYear) . '</span>' : '' ?></td>
            <td><?= html_escape($d->sfdPeriod) ?></td><td><?= date('d-m-Y', strtotime($d->sfdDueDate)) ?></td>
            <td class="num"><?= fee_money($d->sfdAmount) ?></td><td class="num"><?= fee_money($d->paid) ?></td>
            <td class="num"><strong><?= fee_money(max(0, $d->balance)) ?></strong><?= $d->fine > 0 ? '<div class="small warn-txt">+ fine ' . fee_money($d->fine) . '</div>' : '' ?></td>
            <td><span class="badge <?= $st[1] ?>"><?= $st[0] ?></span></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$fee['dues']): ?><div class="pp-empty">No fees allotted.</div><?php endif; ?>
    </div>
  </section>

  <section class="pp-card">
    <div class="pp-card-h"><h3>Receipts</h3></div>
    <ul class="pp-list">
      <?php foreach ($receipts as $r): ?>
        <li style="display:flex;justify-content:space-between;align-items:center;gap:10px">
          <div><div class="t"><?= html_escape($r->frReceiptNo) ?> · ₹<?= fee_money($r->frTotal) ?></div><div class="m"><?= date('d M Y', strtotime($r->frDate)) ?> · <?= html_escape($r->frMode) ?></div></div>
          <a class="act-btn dl" target="_blank" href="<?= site_url('parent/receipt/' . (int)$r->frId) ?>#noprint">View</a>
        </li>
      <?php endforeach; ?>
      <?php if (!$receipts): ?><li class="muted">No receipts yet this year.</li><?php endif; ?>
    </ul>
  </section>
<?php endif; ?>
