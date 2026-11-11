<?php
/* Store items.  $items, $f, $perm, $categories, $classes, $units */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Store Items</h1>
      <p class="page-sub">Books, notebooks, uniforms and stationery. Add each uniform size as its own item so stock is kept per size.</p>
    </div>
    <div class="page-actions">
      <?php if ($can_add || $can_edit): ?><button class="btn btn-secondary" type="button" id="openCats">Categories</button><?php endif; ?>
      <?php if ($can_add): ?><button class="btn btn-primary" type="button" id="openAdd">+ Add Item</button><?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('store_items') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Item name or code…">
        </div>
        <select class="field-input" name="category" style="width:auto" aria-label="Category">
          <option value="">All Categories</option>
          <?php foreach ($categories as $c): ?><option value="<?= (int)$c->scId ?>" <?= (int)$f['category'] === (int)$c->scId ? 'selected' : '' ?>><?= html_escape($c->scName) ?></option><?php endforeach; ?>
        </select>
        <select class="field-input" name="class" style="width:auto" aria-label="Class">
          <option value="">All Classes</option>
          <?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>" <?= (int)$f['class'] === (int)$c->cmId ? 'selected' : '' ?>><?= html_escape($c->cmName) ?></option><?php endforeach; ?>
        </select>
        <select class="field-input" name="active" style="width:auto" aria-label="Status">
          <option value="">Active + Inactive</option>
          <option value="1" <?= $f['active'] === '1' ? 'selected' : '' ?>>Active</option>
          <option value="0" <?= $f['active'] === '0' ? 'selected' : '' ?>>Inactive</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a class="btn btn-secondary" href="<?= site_url('store_items') ?>">Clear</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Code</th><th>Item</th><th>Size</th><th>Category</th><th>Class</th><th>Unit</th>
          <th class="num">Cost</th><th class="num">Sale Price</th><th class="num">Tax %</th><th class="num">Stock</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($items as $i):
          $row = array('id' => (int)$i->siId, 'category' => (int)$i->siScId, 'code' => $i->siCode, 'name' => $i->siName,
                       'size' => (string)$i->siSize, 'class' => (int)$i->siCmId, 'unit' => $i->siUnit,
                       'purchase_price' => (float)$i->siPurchasePrice, 'sale_price' => (float)$i->siSalePrice,
                       'tax' => (float)$i->siTaxPercent, 'reorder' => (float)$i->siReorderLevel, 'active' => (int)$i->siActive);
          $low = (float)$i->siStock <= (float)$i->siReorderLevel; ?>
          <tr data-i="<?= html_escape(json_encode($row)) ?>">
            <td><?= html_escape($i->siCode) ?></td>
            <td><strong><?= html_escape($i->siName) ?></strong></td>
            <td><?= html_escape($i->siSize) ?></td>
            <td><?= html_escape($i->scName) ?></td>
            <td><?= html_escape($i->cmName) ?></td>
            <td><?= html_escape($i->siUnit) ?></td>
            <td class="num"><?= fee_money($i->siPurchasePrice) ?></td>
            <td class="num"><?= fee_money($i->siSalePrice) ?></td>
            <td class="num"><?= (float)$i->siTaxPercent ?: '—' ?></td>
            <td class="num <?= $low ? 'bad-txt' : '' ?>"><strong><?= (float)$i->siStock ?></strong></td>
            <td><?= $i->siActive ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$items): ?><div class="empty">No items found<?= $can_add ? '. Add your first item.' : '.' ?></div><?php endif; ?>
    </div>
    <div class="table-foot"><span class="muted"><?= count($items) ?> items</span></div>
  </div>
</main>

<div class="modal-overlay" id="itemModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="itemTitle">
    <div class="modal-head"><h2 id="itemTitle">Add Item</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="itemForm" novalidate>
      <input type="hidden" id="iId" value="0">
      <div class="modal-body">
        <div class="grid g3" style="margin-top:12px">
          <div><label class="field-label" for="iCat">Category <span class="req">*</span></label>
            <select class="field-input" id="iCat"><?php foreach ($categories as $c): if (!$c->scActive) continue; ?><option value="<?= (int)$c->scId ?>"><?= html_escape($c->scName) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="iCode">Item Code <span class="req">*</span></label><input class="field-input" id="iCode" maxlength="40"></div>
          <div><label class="field-label" for="iUnit">Unit</label>
            <select class="field-input" id="iUnit"><?php foreach ($units as $u): ?><option><?= html_escape($u) ?></option><?php endforeach; ?></select></div>
          <div class="full" style="grid-column:1/3"><label class="field-label" for="iName">Item Name <span class="req">*</span></label><input class="field-input" id="iName" maxlength="150" placeholder="e.g. English Reader / School Shirt"></div>
          <div><label class="field-label" for="iSize">Size / Variant</label><input class="field-input" id="iSize" maxlength="30" placeholder="e.g. 32, M, 200 pages"></div>
          <div><label class="field-label" for="iClass">For Class</label>
            <select class="field-input" id="iClass"><option value="">Any class</option><?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="iCost">Purchase Price (₹)</label><input class="field-input" id="iCost" inputmode="decimal"></div>
          <div><label class="field-label" for="iSale">Sale Price (₹) <span class="req">*</span></label><input class="field-input" id="iSale" inputmode="decimal"></div>
          <div><label class="field-label" for="iTax">Tax %</label><input class="field-input" id="iTax" inputmode="decimal" placeholder="0"></div>
          <div><label class="field-label" for="iReorder">Reorder Level</label><input class="field-input" id="iReorder" inputmode="decimal" placeholder="Warn when stock falls to"></div>
          <div id="openWrap"><label class="field-label" for="iOpen">Opening Stock</label><input class="field-input" id="iOpen" inputmode="decimal" placeholder="0"></div>
          <div style="display:flex;align-items:flex-end"><label class="check"><input type="checkbox" id="iActive" checked> Active</label></div>
        </div>
        <p class="field-hint">Prices are before tax. Stock changes after saving come from Purchases, Sales and Stock adjustments.</p>
        <p class="field-error" id="itemErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary" id="itemSave">Save</button></div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="catModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true">
    <div class="modal-head"><h2>Categories</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <div class="modal-body">
      <table class="table compact" style="margin-top:10px"><tbody>
        <?php foreach ($categories as $c): ?>
          <tr data-c="<?= (int)$c->scId ?>"><td><input class="field-input c-name" value="<?= html_escape($c->scName) ?>" maxlength="60"></td>
            <td><label class="check small"><input type="checkbox" class="c-act" <?= $c->scActive ? 'checked' : '' ?>> Active</label></td>
            <td><button type="button" class="act-btn ed c-save">Save</button></td></tr>
        <?php endforeach; ?>
        <tr data-c="0"><td><input class="field-input c-name" placeholder="New category" maxlength="60"></td><td></td>
          <td><button type="button" class="act-btn dl c-save">Add</button></td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const itemM = U.modal('itemModal'), catM = U.modal('catModal');
  const showErr = m => { $('itemErr').textContent = m; $('itemErr').hidden = false; };

  async function suggestCode() {
    if (+$('iId').value) return;
    const r = await (await fetch('<?= site_url('store_item_code') ?>?category=' + $('iCat').value)).json();
    $('iCode').value = r.code;
  }
  $('iCat').addEventListener('change', suggestCode);

  function open(i) {
    $('itemErr').hidden = true;
    $('iId').value = i ? i.id : 0;
    $('itemTitle').textContent = i ? 'Edit Item' : 'Add Item';
    if (i) $('iCat').value = i.category;
    $('iCode').value = i ? i.code : '';
    $('iName').value = i ? i.name : '';
    $('iSize').value = i ? i.size : '';
    $('iClass').value = i && i.class ? i.class : '';
    $('iUnit').value = i ? i.unit : 'Nos';
    $('iCost').value = i ? i.purchase_price : '';
    $('iSale').value = i ? i.sale_price : '';
    $('iTax').value = i && i.tax ? i.tax : '';
    $('iReorder').value = i && i.reorder ? i.reorder : '';
    $('iOpen').value = '';
    $('openWrap').hidden = !!i;
    $('iActive').checked = i ? !!i.active : true;
    if (!i) suggestCode();
    itemM.show(); $('iName').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(null));
  const cats = $('openCats'); if (cats) cats.addEventListener('click', catM.show);

  $('itemForm').addEventListener('submit', async e => {
    e.preventDefault();
    if (!$('iName').value.trim()) return showErr('Enter the item name.');
    if (!U.isNum($('iSale').value)) return showErr('Enter a valid sale price.');
    const fd = new FormData();
    [['id', 'iId'], ['category', 'iCat'], ['code', 'iCode'], ['name', 'iName'], ['size', 'iSize'], ['class', 'iClass'], ['unit', 'iUnit'],
     ['purchase_price', 'iCost'], ['sale_price', 'iSale'], ['tax', 'iTax'], ['reorder', 'iReorder'], ['opening', 'iOpen']]
      .forEach(([k, id]) => fd.append(k, $(id).value.trim()));
    if ($('iActive').checked) fd.append('active', 1);
    $('itemSave').disabled = true;
    try {
      const res = await U.post('<?= site_url('store_item_save') ?>', fd);
      if (!res.status) return showErr(res.msg);
      itemM.hide(); U.ok(res.msg);
    } catch (x) { showErr('Something went wrong.'); } finally { $('itemSave').disabled = false; }
  });

  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const i = JSON.parse(tr.dataset.i);
    if (e.target.closest('[data-edit]')) return open(i);
    if (e.target.closest('[data-del]')) {
      const c = await Swal.fire({title: 'Delete ' + i.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
      if (!c.isConfirmed) return;
      const fd = new FormData(); fd.append('id', i.id);
      const res = await U.post('<?= site_url('store_item_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });

  $('catModal').addEventListener('click', async e => {
    const b = e.target.closest('.c-save'); if (!b) return;
    const tr = b.closest('tr'), fd = new FormData();
    fd.append('id', tr.dataset.c); fd.append('name', tr.querySelector('.c-name').value.trim());
    const act = tr.querySelector('.c-act'); fd.append('active', act && !act.checked ? '0' : '1');
    const res = await U.post('<?= site_url('store_category_save') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
