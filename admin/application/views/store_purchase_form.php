<?php /* New purchase.  $suppliers (active), $items (picker) */ ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">New Purchase</h1><p class="page-sub">Enter the supplier's bill. The quantities are added to stock when you save.</p></div>
    <div class="page-actions"><a class="btn btn-secondary" href="<?= site_url('store_purchases') ?>">Back to Purchases</a></div>
  </div>
  <div class="card">
    <div class="doc-head">
      <div><label class="field-label" for="pSup">Supplier <span class="req">*</span></label>
        <select class="field-input" id="pSup"><option value="">Select Supplier</option>
          <?php foreach ($suppliers as $s): ?><option value="<?= (int)$s->ssId ?>"><?= html_escape($s->ssName) ?></option><?php endforeach; ?>
        </select>
        <?php if (!$suppliers): ?><span class="field-hint">No suppliers yet — <a href="<?= site_url('store_suppliers') ?>">add one</a>.</span><?php endif; ?></div>
      <div><label class="field-label" for="pBill">Supplier Bill No</label><input class="field-input" id="pBill" maxlength="50"></div>
      <div><label class="field-label" for="pDate">Date <span class="req">*</span></label><input class="field-input" type="date" id="pDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
    </div>
    <div class="doc-add">
      <input class="field-input" id="pPick" placeholder="Type item name or code, press Enter to add…" autocomplete="off">
      <span class="muted small">Same item again adds to its quantity.</span>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Item</th><th class="num">Qty</th><th class="num">Rate (₹)</th><th class="num">Tax %</th><th class="num">Amount</th><th></th></tr></thead>
        <tbody id="pLines"></tbody>
      </table>
      <div class="notice" id="pEmpty">No items added yet.</div>
    </div>
    <div class="doc-total">
      <div>
        <label class="field-label" for="pRem">Remarks</label><textarea class="field-input" id="pRem" maxlength="500"></textarea>
        <label class="check small" style="margin-top:10px"><input type="checkbox" id="pCost" checked> Update item purchase price to these rates</label>
      </div>
      <div class="tot-box">
        <div><span>Amount</span><strong id="tSub">0.00</strong></div>
        <div><span>Tax</span><strong id="tTax">0.00</strong></div>
        <div class="grand"><span>Total</span><span>₹<span id="tTot">0.00</span></span></div>
        <button type="button" class="btn btn-primary btn-block" id="pSave" style="margin-top:12px">Save Purchase</button>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const ITEMS = <?= json_encode($items) ?>;
  const ed = U.lines({tbody: $('pLines'), items: ITEMS, mode: 'purchase', onChange: t => {
    $('tSub').textContent = U.inr(t.sub); $('tTax').textContent = U.inr(t.tax); $('tTot').textContent = U.inr(t.sub + t.tax);
    $('pEmpty').hidden = t.count > 0;
  }});
  U.picker($('pPick'), ITEMS, it => { const tr = ed.add(it.id, 1); if (tr) tr.querySelector('.l-qty').select(); }, true);

  $('pSave').addEventListener('click', async () => {
    if (!$('pSup').value) return U.err('Select the supplier.');
    const fd = new FormData();
    fd.append('supplier', $('pSup').value); fd.append('bill_no', $('pBill').value.trim()); fd.append('date', $('pDate').value);
    fd.append('remarks', $('pRem').value.trim()); if ($('pCost').checked) fd.append('update_cost', 1);
    const e = ed.fill(fd); if (e) return U.err(e);
    $('pSave').disabled = true;
    try {
      const res = await U.post('<?= site_url('store_purchase_save') ?>', fd);
      if (!res.status) return U.err(res.msg);
      Swal.fire({icon: 'success', title: 'Saved', text: res.msg, timer: 1400, showConfirmButton: false})
        .then(() => location.href = '<?= site_url('store_purchase') ?>/' + res.id);
    } catch (x) { U.err('Something went wrong.'); } finally { $('pSave').disabled = false; }
  });
})();
</script>
