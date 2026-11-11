<?php /* $rows, $f, $perm, $modes */
$can_add = !empty($perm['can_add']); $can_del = !empty($perm['can_delete']);
$sum = array('total' => 0, 'paid' => 0, 'bal' => 0);
foreach ($rows as $v) if (!$v->sinCancelled) { $sum['total'] += $v->sinTotal; $sum['paid'] += $v->paid; $sum['bal'] += $v->balance; }
$badge = array('Paid' => 'badge-green', 'Partial' => 'badge-amber', 'Unpaid' => 'badge-red', 'Cancelled' => 'badge-gray');
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Sales / Invoices</h1><p class="page-sub">Store invoices of this academic year. Part-paid invoices can be settled later with Add Payment.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><a class="btn btn-primary" href="<?= site_url('store_invoice_new') ?>">+ New Invoice</a></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="sum-grid">
      <div class="sum-item"><small>Invoiced</small><strong>₹<?= fee_money($sum['total']) ?></strong></div>
      <div class="sum-item ok"><small>Received</small><strong>₹<?= fee_money($sum['paid']) ?></strong></div>
      <div class="sum-item bad"><small>Outstanding</small><strong>₹<?= fee_money($sum['bal']) ?></strong></div>
    </div>
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('store_invoices') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Invoice no, student, admission no or customer…">
        </div>
        <div class="fb"><label for="from">From</label><input class="field-input" type="date" id="from" name="from" value="<?= html_escape($f['from']) ?>"></div>
        <div class="fb"><label for="to">To</label><input class="field-input" type="date" id="to" name="to" value="<?= html_escape($f['to']) ?>"></div>
        <div class="fb"><label for="st">Status</label>
          <select class="field-input" id="st" name="status"><option value="">All</option>
            <?php foreach (array_keys($badge) as $s): ?><option <?= $f['status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
          </select></div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a class="btn btn-secondary" href="<?= site_url('store_invoices') ?>">Clear</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Invoice No</th><th>Date</th><th>Customer</th><th>Class</th><th class="num">Total</th><th class="num">Paid</th><th class="num">Balance</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($rows as $v): ?>
          <tr class="<?= $v->sinCancelled ? 'row-cancel' : '' ?>" data-id="<?= (int)$v->sinId ?>" data-no="<?= html_escape($v->sinNo) ?>" data-bal="<?= $v->balance ?>">
            <td><strong><?= html_escape($v->sinNo) ?></strong></td><td><?= date('d-m-Y', strtotime($v->sinDate)) ?></td>
            <td><?= html_escape($v->customer) ?><?= $v->sinSmId ? '<div class="small muted">' . html_escape($v->smAdmissionNo) . '</div>' : '' ?></td>
            <td><?= $v->sinSmId ? html_escape(trim($v->cmName . ' ' . $v->dmName)) : '<span class="muted">Walk-in</span>' ?></td>
            <td class="num"><?= fee_money($v->sinTotal) ?></td><td class="num"><?= fee_money($v->paid) ?></td>
            <td class="num"><strong><?= fee_money($v->balance) ?></strong></td>
            <td><span class="badge <?= $badge[$v->status] ?>"><?= $v->status ?></span></td>
            <td><div class="act-row">
              <a class="act-btn dl" target="_blank" href="<?= site_url('store_invoice/' . (int)$v->sinId) ?>#noprint">View</a>
              <?php if ($can_add && !$v->sinCancelled && $v->balance > 0): ?><button class="act-btn ed" type="button" data-pay>Add Payment</button><?php endif; ?>
              <?php if ($can_del && !$v->sinCancelled): ?><button class="act-btn rm" type="button" data-cancel>Cancel</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$rows): ?><div class="empty">No invoices found.</div><?php endif; ?>
    </div>
    <div class="table-foot"><span class="muted"><?= count($rows) ?> invoices</span></div>
  </div>
</main>

<div class="modal-overlay" id="payModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true" aria-labelledby="payTitle">
    <div class="modal-head"><h2 id="payTitle">Add Payment</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="payForm" novalidate>
      <input type="hidden" id="pId">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div><label class="field-label" for="pAmt">Amount (₹) <span class="req">*</span></label><input class="field-input" id="pAmt" inputmode="decimal"></div>
          <div><label class="field-label" for="pDate">Date</label><input class="field-input" type="date" id="pDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
          <div><label class="field-label" for="pMode">Mode</label><select class="field-input" id="pMode"><?php foreach ($modes as $m): ?><option><?= html_escape($m) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="pRef">Reference / Txn No</label><input class="field-input" id="pRef" maxlength="100"></div>
        </div>
        <p class="field-error" id="payErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save Payment</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const m = U.modal('payModal');
  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    if (e.target.closest('[data-pay]')) {
      $('pId').value = tr.dataset.id; $('payTitle').textContent = 'Add Payment — ' + tr.dataset.no;
      $('pAmt').value = tr.dataset.bal; $('pRef').value = ''; $('payErr').hidden = true;
      m.show(); $('pAmt').select();
    }
    if (e.target.closest('[data-cancel]')) {
      const why = await U.askReason('Cancel invoice ' + tr.dataset.no + '? Items go back to stock.');
      if (!why) return;
      const fd = new FormData(); fd.append('id', tr.dataset.id); fd.append('reason', why);
      const res = await U.post('<?= site_url('store_invoice_cancel') ?>', fd);
      res.status ? Swal.fire({icon: 'success', title: 'Cancelled', text: res.msg}).then(() => location.reload()) : U.err(res.msg);
    }
  });
  $('payForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('pId').value); fd.append('amount', $('pAmt').value.trim()); fd.append('date', $('pDate').value);
    fd.append('mode', $('pMode').value); fd.append('reference', $('pRef').value.trim());
    const res = await U.post('<?= site_url('store_payment_save') ?>', fd);
    if (!res.status) { $('payErr').textContent = res.msg; $('payErr').hidden = false; return; }
    m.hide(); U.ok(res.msg);
  });
})();
</script>
