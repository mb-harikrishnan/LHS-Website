<?php /* $rows, $f, $perm, $suppliers */
$total = 0; foreach ($rows as $p) if (!$p->spCancelled) $total += $p->spTotal; ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Purchases</h1><p class="page-sub">Supplier bills. Saving a purchase adds the items to stock.</p></div>
    <?php if (!empty($perm['can_add'])): ?><div class="page-actions"><a class="btn btn-primary" href="<?= site_url('store_purchase_new') ?>">+ New Purchase</a></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('store_purchases') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Purchase no, bill no or supplier…">
        </div>
        <div class="fb"><label for="from">From</label><input class="field-input" type="date" id="from" name="from" value="<?= html_escape($f['from']) ?>"></div>
        <div class="fb"><label for="to">To</label><input class="field-input" type="date" id="to" name="to" value="<?= html_escape($f['to']) ?>"></div>
        <div class="fb"><label for="sup">Supplier</label>
          <select class="field-input" id="sup" name="supplier"><option value="">All</option>
            <?php foreach ($suppliers as $s): ?><option value="<?= (int)$s->ssId ?>" <?= (int)$f['supplier'] === (int)$s->ssId ? 'selected' : '' ?>><?= html_escape($s->ssName) ?></option><?php endforeach; ?>
          </select></div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a class="btn btn-secondary" href="<?= site_url('store_purchases') ?>">Clear</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Purchase No</th><th>Date</th><th>Supplier</th><th>Bill No</th><th class="num">Amount</th><th class="num">Tax</th><th class="num">Total</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $p): ?>
          <tr class="<?= $p->spCancelled ? 'row-cancel' : '' ?>">
            <td><strong><?= html_escape($p->spNo) ?></strong></td><td><?= date('d-m-Y', strtotime($p->spDate)) ?></td>
            <td><?= html_escape($p->ssName) ?></td><td><?= html_escape($p->spBillNo) ?></td>
            <td class="num"><?= fee_money($p->spSubtotal) ?></td><td class="num"><?= fee_money($p->spTax) ?></td>
            <td class="num"><strong><?= fee_money($p->spTotal) ?></strong></td>
            <td><?= $p->spCancelled ? '<span class="badge badge-red">Cancelled</span>' : '<span class="badge badge-green">Received</span>' ?></td>
            <td><a class="act-btn dl" href="<?= site_url('store_purchase/' . (int)$p->spId) ?>">View</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$rows): ?><div class="empty">No purchases found.</div><?php endif; ?>
    </div>
    <div class="table-foot"><span class="muted"><?= count($rows) ?> purchases</span><strong>Total ₹<?= fee_money($total) ?></strong></div>
  </div>
</main>
