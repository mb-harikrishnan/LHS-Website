<?php /* $child, $invoices, $balance, $store_on */
$badge = array('Paid' => 'badge-green', 'Partial' => 'badge-amber', 'Unpaid' => 'badge-red', 'Cancelled' => 'badge-gray'); ?>
<h1 class="pp-h1">Store — <?= html_escape($child->smName) ?></h1>
<?php if (!$store_on): ?>
  <div class="pp-card"><div class="pp-empty">Store details are not available.</div></div>
<?php else: ?>
  <section class="pp-card" style="margin-bottom:16px"><div class="pp-card-b">
    <div class="pp-stats"><div class="pp-stat <?= $balance > 0 ? 'warn' : 'ok' ?>"><small>Balance to pay</small><strong>₹<?= fee_money($balance) ?></strong><span>books, uniform, stationery</span></div>
      <div class="pp-stat"><small>Invoices</small><strong><?= count($invoices) ?></strong><span><?= html_escape($child->amYear) ?></span></div></div>
  </div></section>
  <section class="pp-card">
    <div class="pp-card-h"><h3>Invoices</h3></div>
    <ul class="pp-list">
      <?php foreach ($invoices as $v): ?>
        <li style="display:flex;justify-content:space-between;align-items:center;gap:10px">
          <div><div class="t"><?= html_escape($v->sinNo) ?> · ₹<?= fee_money($v->sinTotal) ?> <span class="badge <?= $badge[$v->status] ?>"><?= $v->status ?></span></div>
            <div class="m"><?= date('d M Y', strtotime($v->sinDate)) ?><?= $v->balance > 0 ? ' · balance ₹' . fee_money($v->balance) : '' ?></div></div>
          <a class="act-btn dl" target="_blank" href="<?= site_url('parent/invoice/' . (int)$v->sinId) ?>#noprint">View</a>
        </li>
      <?php endforeach; ?>
      <?php if (!$invoices): ?><li class="muted">No store invoices this year.</li><?php endif; ?>
    </ul>
  </section>
<?php endif; ?>
