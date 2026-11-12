<?php /* $advances, $f, $perm, $staff (on payroll), $all_staff, $modes */
$can_add = !empty($perm['can_add']); $can_del = !empty($perm['can_delete']);
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
$t = array('amt' => 0, 'rec' => 0, 'out' => 0);
foreach ($advances as $a) if (!$a->padCancelled) { $t['amt'] += $a->padAmount; $t['rec'] += $a->recovered; $t['out'] += $a->outstanding; }
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Salary Advances</h1>
      <p class="page-sub">Advances given to staff are recovered in monthly instalments, deducted automatically in payroll.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openAdd">+ Give Advance</button></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="sum-grid">
      <div class="sum-item"><small>Advances</small><strong><?= fee_money($t['amt']) ?></strong></div>
      <div class="sum-item ok"><small>Recovered</small><strong><?= fee_money($t['rec']) ?></strong></div>
      <div class="sum-item <?= $t['out'] > 0 ? 'warn' : '' ?>"><small>Outstanding</small><strong><?= fee_money($t['out']) ?></strong></div>
    </div>
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('pay_advances') ?>">
        <div class="fb"><label for="fst">Staff</label><select class="field-input" id="fst" name="staff"><option value="">All staff</option>
          <?php foreach ($all_staff as $s): ?><option value="<?= (int)$s->peId ?>" <?= (int)$f['staff'] === (int)$s->peId ? 'selected' : '' ?>><?= html_escape($s->peName) ?></option><?php endforeach; ?></select></div>
        <div class="fb"><label for="fshow">Show</label><select class="field-input" id="fshow" name="show">
          <option value="">Outstanding only</option><option value="all" <?= $f['cancelled'] ? 'selected' : '' ?>>All, including closed and cancelled</option></select></div>
        <button class="btn btn-primary" type="submit">Show</button><a class="btn btn-secondary" href="<?= site_url('pay_advances') ?>">Reset</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Date</th><th>Staff</th><th class="num">Advance</th><th class="num">Monthly recovery</th><th>From</th><th class="num">Recovered</th><th class="num">Outstanding</th><th>Paid by</th><th>Status</th><th></th></tr></thead>
        <tbody id="rows">
        <?php foreach ($advances as $a): ?>
          <tr data-id="<?= (int)$a->padId ?>" class="<?= $a->padCancelled ? 'row-cancel' : '' ?>">
            <td><?= $dmy($a->padDate) ?></td>
            <td><strong><?= html_escape($a->peName) ?></strong><?= $a->padRemarks ? '<div class="small muted">' . html_escape($a->padRemarks) . '</div>' : '' ?></td>
            <td class="num"><?= fee_money($a->padAmount) ?></td>
            <td class="num"><?= fee_money($a->padInstallment) ?></td>
            <td><?= date('M Y', strtotime($a->padFromMonth)) ?></td>
            <td class="num"><?= fee_money($a->recovered) ?><?= $a->pending > 0 ? '<div class="small muted">+' . fee_money($a->pending) . ' in draft</div>' : '' ?></td>
            <td class="num"><strong><?= fee_money($a->outstanding) ?></strong></td>
            <td class="small"><?= html_escape($a->padMode) ?><?= $a->padRef ? '<div class="muted">' . html_escape($a->padRef) . '</div>' : '' ?></td>
            <td><?php if ($a->padCancelled): ?><span class="badge badge-gray" title="<?= html_escape($a->padCancelReason) ?>">Cancelled</span>
                <?php elseif ($a->outstanding <= 0): ?><span class="badge badge-green">Recovered</span>
                <?php else: ?><span class="badge badge-amber">Open</span><?php endif; ?></td>
            <td><?php if (!$a->padCancelled && $can_del && (float)$a->recovered == 0): ?><button class="act-btn rm" type="button" data-cancel>Cancel</button><?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$advances): ?><div class="empty">No advances to show.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="aModal" aria-hidden="true">
  <div class="modal" style="max-width:600px" role="dialog" aria-modal="true" aria-labelledby="aTitle">
    <div class="modal-head"><h2 id="aTitle">Give Salary Advance</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="aForm" novalidate>
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="aStaff">Staff <span class="req">*</span></label>
            <select class="field-input" id="aStaff"><option value="">Select staff</option>
              <?php foreach ($staff as $s): ?><option value="<?= (int)$s->peId ?>"><?= html_escape($s->peName . ($s->peCode ? ' (' . $s->peCode . ')' : '')) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="aDate">Date given <span class="req">*</span></label><input class="field-input" type="date" id="aDate" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"></div>
          <div><label class="field-label" for="aAmt">Advance amount (₹) <span class="req">*</span></label><input class="field-input" id="aAmt" inputmode="decimal"></div>
          <div><label class="field-label" for="aInst">Recover per month (₹) <span class="req">*</span></label><input class="field-input" id="aInst" inputmode="decimal"><span class="field-hint" id="aHint"></span></div>
          <div><label class="field-label" for="aFrom">Recover from month <span class="req">*</span></label><input class="field-input" type="month" id="aFrom" value="<?= date('Y-m') ?>"></div>
          <div><label class="field-label" for="aMode">Paid by <span class="req">*</span></label><select class="field-input" id="aMode"><?php foreach ($modes as $m): ?><option><?= html_escape($m) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="aRef">Reference</label><input class="field-input" id="aRef" maxlength="60" placeholder="UTR / cheque no"></div>
          <div class="full"><label class="field-label" for="aRem">Remarks</label><input class="field-input" id="aRem" maxlength="255"></div>
        </div>
        <p class="field-error" id="aErr" hidden></p>
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
  const post = async (url, fd) => { try { return await U.post(url, fd); } catch (e) { return {status: false, msg: 'Your session may have expired. Reload the page and try again.'}; } };
  const hint = () => {
    const a = +$('aAmt').value || 0, i = +$('aInst').value || 0;
    $('aHint').textContent = a > 0 && i > 0 && i <= a ? 'Recovered in ' + Math.ceil(a / i) + ' month(s).' : '';
  };
  $('aAmt').addEventListener('input', hint); $('aInst').addEventListener('input', hint);
  const add = $('openAdd');
  if (add) {
    const m = U.modal('aModal');
    add.addEventListener('click', () => { $('aErr').hidden = true; m.show(); $('aStaff').focus(); });
    $('aForm').addEventListener('submit', async e => {
      e.preventDefault();
      const fd = new FormData();
      fd.append('staff', $('aStaff').value); fd.append('date', $('aDate').value); fd.append('amount', $('aAmt').value.trim());
      fd.append('installment', $('aInst').value.trim()); fd.append('from_month', $('aFrom').value); fd.append('mode', $('aMode').value);
      fd.append('ref', $('aRef').value.trim()); fd.append('remarks', $('aRem').value.trim());
      const res = await post('<?= site_url('pay_advance_save') ?>', fd);
      if (!res.status) { $('aErr').textContent = res.msg; $('aErr').hidden = false; return; }
      m.hide(); U.ok(res.msg);
    });
  }
  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-id]'); if (!tr || !e.target.closest('[data-cancel]')) return;
    const reason = await U.askReason('Cancel this advance?'); if (!reason) return;
    const fd = new FormData(); fd.append('id', tr.dataset.id); fd.append('reason', reason);
    const res = await post('<?= site_url('pay_advance_cancel') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
