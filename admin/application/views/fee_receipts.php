<?php
/* Receipts list.  $rows, $links, $total, $start, $per, $filters, $perm */
$can_cancel = !empty($perm['can_delete']);
$from = $total > 0 ? $start + 1 : 0;
$to   = min($start + $per, $total);
?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Fee Receipts</h1>
      <p class="page-sub">All receipts of the selected academic year. Cancelling a receipt puts the amount back into the student's dues.</p>
    </div>
    <div class="page-actions"><a class="btn btn-primary" href="<?= site_url('fee_collection') ?>">Collect Fee</a></div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('fee_management') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($filters['q']) ?>" placeholder="Receipt no, student name or admission no…">
        </div>
        <div class="fb"><label for="from">From</label><input class="field-input" type="date" id="from" name="from" value="<?= html_escape($filters['from']) ?>"></div>
        <div class="fb"><label for="to">To</label><input class="field-input" type="date" id="to" name="to" value="<?= html_escape($filters['to']) ?>"></div>
        <div class="fb"><label for="status">Status</label>
          <select class="field-input" id="status" name="status">
            <option value="">All</option>
            <option value="0" <?= $filters['status'] === '0' ? 'selected' : '' ?>>Active</option>
            <option value="1" <?= $filters['status'] === '1' ? 'selected' : '' ?>>Cancelled</option>
          </select></div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a class="btn btn-secondary" href="<?= site_url('fee_management') ?>">Clear</a>
      </form>
    </div>

    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Receipt No</th><th>Date</th><th>Adm No</th><th>Student</th><th>Class</th><th>Mode</th>
          <th class="num">Fee</th><th class="num">Fine</th><th class="num">Total</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($rows as $r): $cx = (int)$r->frCancelled === 1; ?>
          <tr data-id="<?= (int)$r->frId ?>" data-no="<?= html_escape($r->frReceiptNo) ?>">
            <td><strong><?= html_escape($r->frReceiptNo) ?></strong></td>
            <td><?= date('d-m-Y', strtotime($r->frDate)) ?></td>
            <td><?= html_escape($r->smAdmissionNo) ?></td>
            <td><?= html_escape($r->smName) ?></td>
            <td><?= html_escape(trim($r->cmName . ' ' . $r->dmName)) ?></td>
            <td><?= html_escape($r->frMode) ?><?= $r->frReference ? '<div class="small muted">' . html_escape($r->frReference) . '</div>' : '' ?></td>
            <td class="num"><?= fee_money($r->frAmount) ?></td>
            <td class="num"><?= fee_money($r->frFine) ?></td>
            <td class="num"><strong><?= fee_money($r->frTotal) ?></strong></td>
            <td><?= $cx ? '<span class="badge badge-red" title="' . html_escape($r->frCancelReason) . '">Cancelled</span>' : '<span class="badge badge-green">Active</span>' ?></td>
            <td><div class="act-row">
              <a class="act-btn dl" target="_blank" href="<?= site_url('fee_receipt/' . (int)$r->frId) ?>#noprint">View</a>
              <?php if ($can_cancel && !$cx): ?><button class="act-btn rm" type="button" data-cancel>Cancel</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($rows)): ?><div class="empty">No receipts found.</div><?php endif; ?>
    </div>
    <div class="table-foot">
      <span><?= $total > 0 ? "Showing {$from}&ndash;{$to} of {$total} receipts" : 'Showing 0 receipts' ?></span>
      <?= $links ?>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  let CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  document.getElementById('rows').addEventListener('click', async e => {
    if (!e.target.closest('[data-cancel]')) return;
    const tr = e.target.closest('tr');
    const r = await Swal.fire({
      title: 'Cancel receipt ' + tr.dataset.no + '?', input: 'text', inputLabel: 'Reason', inputPlaceholder: 'Reason for cancelling',
      icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Cancel Receipt', cancelButtonText: 'Back',
      inputValidator: v => !v.trim() ? 'Enter the reason.' : undefined
    });
    if (!r.isConfirmed) return;
    const fd = new FormData();
    fd.append('id', tr.dataset.id); fd.append('reason', r.value.trim());
    fd.append('<?= $this->security->get_csrf_token_name() ?>', CSRF_HASH);
    const res = await (await fetch('<?= site_url('fee_receipt_cancel') ?>', {method: 'POST', body: fd})).json();
    if (res.csrf) CSRF_HASH = res.csrf;
    Swal.fire({icon: res.status ? 'success' : 'error', title: res.status ? 'Cancelled' : 'Error', text: res.msg})
      .then(() => { if (res.status) location.reload(); });
  });
})();
</script>
