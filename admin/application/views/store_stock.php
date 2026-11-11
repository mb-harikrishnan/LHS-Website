<?php /* $items (active), $f, $perm, $categories */
$can_adj = !empty($perm['can_add']) || !empty($perm['can_edit']);
$value = 0; $low = 0; $out = 0;
foreach ($items as $i) {
    $value += $i->siStock * $i->siPurchasePrice;
    if ((float)$i->siStock <= 0) $out++;
    elseif ((float)$i->siStock <= (float)$i->siReorderLevel) $low++;
}
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Stock</h1><p class="page-sub">Current stock of active items. Use Adjust for damaged, lost or counted stock.</p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('reports/store_stock_ledger') ?>">Stock Ledger</a>
      <a class="btn btn-primary" href="<?= site_url('store_purchase_new') ?>">+ Purchase</a>
    </div>
  </div>

  <div class="card">
    <div class="sum-grid">
      <div class="sum-item"><small>Items</small><strong><?= count($items) ?></strong></div>
      <div class="sum-item"><small>Stock value (cost)</small><strong>₹<?= fee_money($value) ?></strong></div>
      <div class="sum-item warn"><small>Low stock</small><strong><?= $low ?></strong></div>
      <div class="sum-item bad"><small>Out of stock</small><strong><?= $out ?></strong></div>
    </div>
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('store_stock') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Item name or code…">
        </div>
        <select class="field-input" name="category" style="width:auto" aria-label="Category">
          <option value="">All Categories</option>
          <?php foreach ($categories as $c): ?><option value="<?= (int)$c->scId ?>" <?= (int)$f['category'] === (int)$c->scId ? 'selected' : '' ?>><?= html_escape($c->scName) ?></option><?php endforeach; ?>
        </select>
        <label class="check small"><input type="checkbox" name="low" value="1" <?= $f['low'] ? 'checked' : '' ?>> Low stock only</label>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a class="btn btn-secondary" href="<?= site_url('store_stock') ?>">Clear</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Code</th><th>Item</th><th>Category</th><th>Unit</th><th class="num">Stock</th><th class="num">Reorder</th>
          <th class="num">Cost</th><th class="num">Value</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($items as $i):
          $st = (float)$i->siStock;
          $name = $i->siName . ($i->siSize ? ' (' . $i->siSize . ')' : ''); ?>
          <tr data-id="<?= (int)$i->siId ?>" data-name="<?= html_escape($name) ?>" data-stock="<?= $st ?>">
            <td><?= html_escape($i->siCode) ?></td><td><strong><?= html_escape($name) ?></strong></td>
            <td><?= html_escape($i->scName) ?></td><td><?= html_escape($i->siUnit) ?></td>
            <td class="num"><strong><?= $st ?></strong></td><td class="num"><?= (float)$i->siReorderLevel ?></td>
            <td class="num"><?= fee_money($i->siPurchasePrice) ?></td><td class="num"><?= fee_money($st * $i->siPurchasePrice) ?></td>
            <td><?= $st <= 0 ? '<span class="badge badge-red">Out of stock</span>' : ($st <= (float)$i->siReorderLevel ? '<span class="badge badge-amber">Low</span>' : '<span class="badge badge-green">OK</span>') ?></td>
            <td><div class="act-row">
              <?php if ($can_adj): ?><button class="act-btn ed" type="button" data-adj>Adjust</button><?php endif; ?>
              <a class="act-btn dl" href="<?= site_url('reports/store_stock_ledger') ?>?item=<?= (int)$i->siId ?>&from=<?= date('Y-01-01', strtotime('-1 year')) ?>">Ledger</a>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$items): ?><div class="empty">No items found.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="adjModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true" aria-labelledby="adjTitle">
    <div class="modal-head"><h2 id="adjTitle">Adjust Stock</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="adjForm" novalidate>
      <input type="hidden" id="aItem">
      <div class="modal-body">
        <p class="muted" id="aInfo" style="margin-top:12px"></p>
        <div class="grid">
          <div><label class="field-label" for="aDir">Adjustment</label>
            <select class="field-input" id="aDir"><option value="out">Remove from stock</option><option value="in">Add to stock</option></select></div>
          <div><label class="field-label" for="aQty">Quantity <span class="req">*</span></label><input class="field-input" id="aQty" inputmode="decimal"></div>
          <div><label class="field-label" for="aDate">Date</label><input class="field-input" type="date" id="aDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
          <div><label class="field-label" for="aWhy">Reason <span class="req">*</span></label>
            <input class="field-input" id="aWhy" list="whyList" maxlength="255"><datalist id="whyList"><option>Damaged</option><option>Lost</option><option>Physical count correction</option><option>Returned to supplier</option><option>Free issue</option></datalist></div>
        </div>
        <p class="field-error" id="adjErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const m = U.modal('adjModal');
  $('rows').addEventListener('click', e => {
    if (!e.target.closest('[data-adj]')) return;
    const tr = e.target.closest('tr');
    $('aItem').value = tr.dataset.id; $('aInfo').textContent = tr.dataset.name + ' — current stock ' + tr.dataset.stock;
    $('aQty').value = ''; $('aWhy').value = ''; $('adjErr').hidden = true;
    m.show(); $('aQty').focus();
  });
  $('adjForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('item', $('aItem').value); fd.append('direction', $('aDir').value); fd.append('qty', $('aQty').value.trim());
    fd.append('date', $('aDate').value); fd.append('reason', $('aWhy').value.trim());
    const res = await U.post('<?= site_url('store_adjust') ?>', fd);
    if (!res.status) { $('adjErr').textContent = res.msg; $('adjErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });
})();
</script>
