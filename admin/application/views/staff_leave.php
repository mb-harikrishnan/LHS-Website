<?php /* $apps, $f, $perm, $staff, $types, $pending */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
$badge = array('PENDING' => 'badge-amber', 'APPROVED' => 'badge-green', 'REJECTED' => 'badge-red', 'CANCELLED' => 'badge-gray');
$num = function ($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); };
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Leave Requests</h1>
      <p class="page-sub">Approve or reject leave applied by staff, or record leave for them. Approved leave is marked in attendance automatically.</p></div>
    <?php if ($can_add): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openAdd">+ Record Leave</button></div><?php endif; ?>
  </div>
  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('staff_leave') ?>">
        <div class="fb"><label for="fSt">Status</label><select class="field-input" id="fSt" name="status">
          <?php foreach (array('PENDING' => 'Waiting (' . (int)$pending . ')', 'APPROVED' => 'Approved', 'REJECTED' => 'Rejected', 'CANCELLED' => 'Cancelled', 'ALL' => 'All') as $k => $l): ?>
            <option value="<?= $k ?>" <?= $f['status'] === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
        <div class="fb"><label for="fStaff">Staff</label><select class="field-input" id="fStaff" name="staff"><option value="">All staff</option>
          <?php foreach ($staff as $s): ?><option value="<?= (int)$s->peId ?>" <?= (int)$f['staff'] === (int)$s->peId ? 'selected' : '' ?>><?= html_escape($s->peName) ?></option><?php endforeach; ?></select></div>
        <div class="fb"><label for="fType">Leave type</label><select class="field-input" id="fType" name="type"><option value="">All</option>
          <?php foreach ($types as $t): ?><option value="<?= (int)$t->sltId ?>" <?= (int)$f['type'] === (int)$t->sltId ? 'selected' : '' ?>><?= html_escape($t->sltName) ?></option><?php endforeach; ?></select></div>
        <button class="btn btn-primary" type="submit">Show</button><a class="btn btn-secondary" href="<?= site_url('staff_leave') ?>">Reset</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Staff</th><th>Leave</th><th>From</th><th>To</th><th class="num">Days</th><th>Reason</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($apps as $a): ?>
          <tr data-id="<?= (int)$a->laId ?>" data-name="<?= html_escape($a->peName) ?>" data-status="<?= $a->laStatus ?>">
            <td><strong><?= html_escape($a->peName) ?></strong><div class="small muted"><?= html_escape($a->peDesignation ?: '') ?><?= $a->laSelf ? ' · applied by staff' : ' · by office' ?></div></td>
            <td><?= html_escape($a->sltName) ?><?= $a->sltPaid ? '' : ' <span class="small warn-txt">(unpaid)</span>' ?></td>
            <td><?= $dmy($a->laFrom) ?></td><td><?= $a->laHalf ? 'Half day' : $dmy($a->laTo) ?></td>
            <td class="num"><?= $num($a->laDays) ?></td>
            <td class="small"><?= html_escape($a->laReason) ?><?= $a->laNote ? '<div class="muted">Note: ' . html_escape($a->laNote) . '</div>' : '' ?></td>
            <td><span class="badge <?= $badge[$a->laStatus] ?>"><?= ucfirst(strtolower($a->laStatus)) ?></span></td>
            <td><div class="act-row">
              <?php if ($a->laStatus === 'PENDING' && $can_edit): ?>
                <button class="act-btn dl" type="button" data-act="approve">Approve</button>
                <button class="act-btn rm" type="button" data-act="reject">Reject</button>
              <?php endif; ?>
              <?php if (in_array($a->laStatus, array('PENDING', 'APPROVED'), true) && $can_del): ?><button class="act-btn ed" type="button" data-act="cancel">Cancel</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$apps): ?><div class="empty"><?= $f['status'] === 'PENDING' ? 'No leave requests are waiting for approval.' : 'No leave requests found.' ?></div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="lModal" aria-hidden="true">
  <div class="modal" style="max-width:600px" role="dialog" aria-modal="true" aria-labelledby="lTitle">
    <div class="modal-head"><h2 id="lTitle">Record Leave</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="lForm" novalidate>
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="lStaff">Staff <span class="req">*</span></label>
            <select class="field-input" id="lStaff"><option value="">Select staff</option>
              <?php foreach ($staff as $s): ?><option value="<?= (int)$s->peId ?>"><?= html_escape($s->peName . ($s->peDesignation ? ' — ' . $s->peDesignation : '')) ?></option><?php endforeach; ?></select></div>
          <div class="full"><label class="field-label" for="lType">Leave type <span class="req">*</span></label>
            <select class="field-input" id="lType"><option value="">Select</option>
              <?php foreach ($types as $t): ?><option value="<?= (int)$t->sltId ?>"><?= html_escape($t->sltName . ($t->sltPaid ? '' : ' (unpaid)')) ?></option><?php endforeach; ?></select></div>
          <div><label class="field-label" for="lFrom">From <span class="req">*</span></label><input class="field-input" type="date" id="lFrom"></div>
          <div><label class="field-label" for="lTo">To</label><input class="field-input" type="date" id="lTo"></div>
          <div class="full"><label class="check"><input type="checkbox" id="lHalf"> Half day (one date only)</label></div>
          <div class="full"><label class="field-label" for="lReason">Reason</label><input class="field-input" id="lReason" maxlength="255"></div>
          <div class="full small" id="lInfo" aria-live="polite"></div>
          <?php if ($can_edit): ?><div class="full"><label class="check"><input type="checkbox" id="lApprove" checked> Approve now and mark in attendance</label></div><?php endif; ?>
        </div>
        <p class="field-error" id="lErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script src="<?= base_url('assets/js/leave_form.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const post = async (url, fd) => { try { return await U.post(url, fd); } catch (e) { return {status: false, msg: 'Your session may have expired. Reload the page and try again.'}; } };

  const add = $('openAdd');
  if (add) {
    const m = U.modal('lModal');
    const form = LeaveForm({staff: 'lStaff', type: 'lType', from: 'lFrom', to: 'lTo', half: 'lHalf', reason: 'lReason', info: 'lInfo', err: 'lErr',
                            checkUrl: '<?= site_url('staff_leave_check') ?>'});
    add.addEventListener('click', () => { form.reset(); m.show(); $('lStaff').focus(); });
    $('lForm').addEventListener('submit', async e => {
      e.preventDefault();
      const fd = form.data(); if (!fd) return;
      if ($('lApprove') && $('lApprove').checked) fd.append('approve', '1');
      const res = await post('<?= site_url('staff_leave_save') ?>', fd);
      if (!res.status) return form.error(res.msg);
      m.hide(); U.ok(res.msg);
    });
  }

  $('rows').addEventListener('click', async e => {
    const b = e.target.closest('[data-act]'); if (!b) return;
    const tr = b.closest('tr'), act = b.dataset.act, name = tr.dataset.name;
    const cfg = {
      approve: {title: 'Approve leave of ' + name + '?', label: 'Note (optional)', req: false, btn: 'Approve', color: '#059669'},
      reject:  {title: 'Reject leave of ' + name + '?', label: 'Reason', req: true, btn: 'Reject', color: '#dc2626'},
      cancel:  {title: 'Cancel leave of ' + name + '?', label: 'Reason', req: true, btn: 'Cancel leave', color: '#dc2626',
                text: tr.dataset.status === 'APPROVED' ? 'The days it marked in attendance become unmarked again.' : ''},
    }[act];
    const r = await Swal.fire({title: cfg.title, text: cfg.text || '', input: 'text', inputLabel: cfg.label, showCancelButton: true,
      confirmButtonText: cfg.btn, confirmButtonColor: cfg.color, cancelButtonText: 'Back',
      inputValidator: v => cfg.req && !v.trim() ? 'Enter the reason.' : undefined});
    if (!r.isConfirmed) return;
    const fd = new FormData(); fd.append('id', tr.dataset.id); fd.append('act', act); fd.append('note', r.value.trim());
    const res = await post('<?= site_url('staff_leave_decide') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
