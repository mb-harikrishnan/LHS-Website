<?php /* $me (or null), $month, $perm; with $me: $off, $grid [date => row], $sum, $types, $bal [sltId], $year_label, $apps, $late_after */
$num = function ($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); };
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
$badge = array('PENDING' => 'badge-amber', 'APPROVED' => 'badge-green', 'REJECTED' => 'badge-red', 'CANCELLED' => 'badge-gray');
?>
<style>
.my-wrap{display:grid;grid-template-columns:1.3fr 1fr;gap:20px;align-items:start}
.mcal{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;padding:12px 20px 18px}
.mcal .h{font-size:11px;font-weight:700;color:#64748b;text-align:center}
.mcal .d{min-height:58px;border:1px solid #e2e8f0;border-radius:10px;padding:5px 6px;font-size:12px;background:#fff}
.mcal .d b{display:block;font-size:12px;color:#64748b}
.mcal .d span{display:inline-block;margin-top:3px;padding:1px 6px;border-radius:6px;font-weight:700;font-size:11.5px;color:#fff}
.mcal .d.off{background:#f8fafc}.mcal .d.off span{background:none;color:#94a3b8;font-weight:600;padding:0}
.mcal .d.today{border-color:#6366f1}
.s-P{background:#059669}.s-A{background:#e11d48}.s-HD{background:#d97706}.s-L{background:#6366f1}.s-HL{background:#8b5cf6}.s-OD{background:#0284c7}
.mcal .t{display:block;color:#64748b;font-size:10.5px;margin-top:2px}
@media (max-width:1100px){.my-wrap{grid-template-columns:1fr}}
@media (max-width:640px){.mcal{gap:3px;padding:10px}.mcal .d{min-height:46px;padding:3px}.mcal .t{display:none}}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">My Attendance &amp; Leave</h1>
      <p class="page-sub"><?= $me ? html_escape($me->peName . ($me->peDesignation ? ' · ' . $me->peDesignation : '')) : 'Your attendance, leave balance and leave requests.' ?></p></div>
    <?php if ($me && !empty($perm['can_add'])): ?><div class="page-actions"><button class="btn btn-primary" type="button" id="openApply">Apply for Leave</button></div><?php endif; ?>
  </div>

  <?php if (!$me): ?>
    <div class="card"><div class="notice">Your login is not linked to a staff record on payroll, so there is no attendance to show. Please contact the office.</div></div>
  <?php else:
    $first = strtotime($month); $days = (int)date('t', $first); $pad = (int)date('N', $first) - 1;
    $prev = date('Y-m', strtotime($month . ' -1 month')); $next = date('Y-m', strtotime($month . ' +1 month')); ?>
  <div class="my-wrap">
    <div class="card">
      <div class="stu-head"><h3><?= date('F Y', $first) ?></h3>
        <div class="act-row">
          <a class="btn btn-secondary btn-sm" href="<?= site_url('my_attendance') ?>?month=<?= $prev ?>" aria-label="Previous month">‹</a>
          <?php if ($next <= date('Y-m')): ?><a class="btn btn-secondary btn-sm" href="<?= site_url('my_attendance') ?>?month=<?= $next ?>" aria-label="Next month">›</a><?php endif; ?>
        </div>
      </div>
      <div class="sum-grid">
        <div class="sum-item"><small>Working days</small><strong><?= (int)$sum['working'] ?></strong></div>
        <div class="sum-item ok"><small>Present</small><strong><?= $num($sum['present']) ?></strong></div>
        <div class="sum-item <?= $sum['absent'] ? 'bad' : '' ?>"><small>Absent</small><strong><?= $num($sum['absent'] + $sum['half'] * 0.5) ?></strong></div>
        <div class="sum-item"><small>Leave</small><strong><?= $num($sum['leave_paid'] + $sum['leave_unpaid']) ?></strong></div>
        <div class="sum-item <?= $sum['late'] ? 'warn' : '' ?>"><small>Late</small><strong><?= (int)$sum['late'] ?></strong></div>
      </div>
      <div class="mcal" role="grid" aria-label="Attendance calendar">
        <?php foreach (array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun') as $d): ?><span class="h"><?= $d ?></span><?php endforeach; ?>
        <?php for ($i = 0; $i < $pad; $i++): ?><span></span><?php endfor; ?>
        <?php for ($d = 1; $d <= $days; $d++): $dt = date('Y-m', $first) . sprintf('-%02d', $d); $a = isset($grid[$dt]) ? $grid[$dt] : null;
          $isOff = isset($off[$dt]); ?>
          <div class="d <?= $isOff && !$a ? 'off' : '' ?> <?= $dt === date('Y-m-d') ? 'today' : '' ?>" title="<?= html_escape($a ? Staff_attendance_model::$status[$a->satStatus] . ($a->sltCode ? ' (' . $a->sltCode . ')' : '') : ($isOff ? $off[$dt] : ($dt <= date('Y-m-d') ? 'Not marked' : ''))) ?>">
            <b><?= $d ?></b>
            <?php if ($a): ?><span class="s-<?= $a->satStatus ?>"><?= $a->satStatus ?><?= $a->sltCode ? ' ' . html_escape($a->sltCode) : '' ?></span>
              <?php if ($a->satIn): ?><em class="t" style="font-style:normal"><?= substr($a->satIn, 0, 5) ?><?= $a->satOut ? '–' . substr($a->satOut, 0, 5) : '' ?><?= substr($a->satIn, 0, 5) > $late_after && in_array($a->satStatus, array('P', 'OD'), true) ? ' late' : '' ?></em><?php endif; ?>
            <?php elseif ($isOff): ?><span><?= html_escape(mb_strimwidth($off[$dt], 0, 14, '…')) ?></span><?php endif; ?>
          </div>
        <?php endfor; ?>
      </div>
      <p class="field-hint" style="padding:0 20px 16px">P present · A absent · HD half day · L leave · HL half-day leave · OD on duty</p>
    </div>

    <div>
      <div class="card">
        <div class="stu-head"><h3>Leave balance</h3><span class="small muted"><?= html_escape($year_label) ?></span></div>
        <div class="table-wrap">
          <table class="table compact">
            <thead><tr><th>Leave</th><th class="num">Entitled</th><th class="num">Taken</th><th class="num">Waiting</th><th class="num">Balance</th></tr></thead>
            <tbody>
            <?php foreach ($types as $t): $x = $bal[(int)$t->sltId]; ?>
              <tr><td><?= html_escape($t->sltName) ?></td>
                <td class="num"><?= $x['entitled'] === null ? '—' : $num($x['entitled']) ?></td>
                <td class="num"><?= $num($x['used']) ?></td><td class="num"><?= $x['pending'] ? $num($x['pending']) : '' ?></td>
                <td class="num"><strong><?= $x['balance'] === null ? 'No limit' : $num($x['balance']) ?></strong></td></tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card" style="margin-top:20px">
        <div class="stu-head"><h3>My leave requests</h3></div>
        <div class="table-wrap">
          <table class="table compact">
            <thead><tr><th>Leave</th><th>Dates</th><th class="num">Days</th><th>Status</th><th></th></tr></thead>
            <tbody id="apps">
            <?php foreach ($apps as $a): ?>
              <tr data-id="<?= (int)$a->laId ?>">
                <td><?= html_escape($a->sltCode) ?><?= $a->laReason ? '<div class="small muted">' . html_escape($a->laReason) . '</div>' : '' ?></td>
                <td class="small"><?= $dmy($a->laFrom) ?><?= $a->laHalf ? ' (half day)' : ($a->laTo !== $a->laFrom ? ' to ' . $dmy($a->laTo) : '') ?></td>
                <td class="num"><?= $num($a->laDays) ?></td>
                <td><span class="badge <?= $badge[$a->laStatus] ?>"><?= ucfirst(strtolower($a->laStatus)) ?></span><?= $a->laNote ? '<div class="small muted">' . html_escape($a->laNote) . '</div>' : '' ?></td>
                <td><?php if ($a->laStatus === 'PENDING'): ?><button class="act-btn ed" type="button" data-withdraw>Withdraw</button><?php endif; ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <?php if (!$apps): ?><div class="empty">No leave requests yet.</div><?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($perm['can_add'])): ?>
  <div class="modal-overlay" id="aModal" aria-hidden="true">
    <div class="modal" style="max-width:560px" role="dialog" aria-modal="true" aria-labelledby="aTitle">
      <div class="modal-head"><h2 id="aTitle">Apply for Leave</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
      <form id="aForm" novalidate>
        <div class="modal-body">
          <div class="grid" style="margin-top:12px">
            <div class="full"><label class="field-label" for="aType">Leave type <span class="req">*</span></label>
              <select class="field-input" id="aType"><option value="">Select</option>
                <?php foreach ($types as $t): if (!$t->sltSelf) continue; ?><option value="<?= (int)$t->sltId ?>"><?= html_escape($t->sltName . ($t->sltPaid ? '' : ' (unpaid)')) ?></option><?php endforeach; ?></select></div>
            <div><label class="field-label" for="aFrom">From <span class="req">*</span></label><input class="field-input" type="date" id="aFrom" min="<?= date('Y-m-d', strtotime('-30 days')) ?>"></div>
            <div><label class="field-label" for="aTo">To</label><input class="field-input" type="date" id="aTo"></div>
            <div class="full"><label class="check"><input type="checkbox" id="aHalf"> Half day (one date only)</label></div>
            <div class="full"><label class="field-label" for="aReason">Reason</label><input class="field-input" id="aReason" maxlength="255"></div>
            <div class="full small" id="aInfo" aria-live="polite"></div>
          </div>
          <p class="field-error" id="aErr" hidden></p>
        </div>
        <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Send Request</button></div>
      </form>
    </div>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</main>
<?php if ($me): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script src="<?= base_url('assets/js/leave_form.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const post = async (url, fd) => { try { return await U.post(url, fd); } catch (e) { return {status: false, msg: 'Your session may have expired. Reload the page and try again.'}; } };
  const btn = $('openApply');
  if (btn) {
    const m = U.modal('aModal');
    const form = LeaveForm({type: 'aType', from: 'aFrom', to: 'aTo', half: 'aHalf', reason: 'aReason', info: 'aInfo', err: 'aErr',
                            checkUrl: '<?= site_url('staff_leave_check') ?>', self: true});
    btn.addEventListener('click', () => { form.reset(); m.show(); $('aType').focus(); });
    $('aForm').addEventListener('submit', async e => {
      e.preventDefault();
      const fd = form.data(); if (!fd) return;
      const res = await post('<?= site_url('my_leave_apply') ?>', fd);
      if (!res.status) return form.error(res.msg);
      m.hide(); U.ok(res.msg);
    });
  }
  $('apps').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-id]'); if (!tr || !e.target.closest('[data-withdraw]')) return;
    const c = await Swal.fire({title: 'Withdraw this leave request?', icon: 'question', showCancelButton: true, confirmButtonText: 'Withdraw'});
    if (!c.isConfirmed) return;
    const fd = new FormData(); fd.append('id', tr.dataset.id);
    const res = await post('<?= site_url('my_leave_cancel') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
<?php endif; ?>
