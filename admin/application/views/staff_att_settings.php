<?php /* $settings, $types, $holidays (of $year), $year, $perm */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
$offs = $settings['week_off'] === '' ? array() : array_map('intval', explode(',', $settings['week_off']));
$num = function ($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); };
$months = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
?>
<style>
.set-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start}
.set-grid .card{margin:0}
.wk{display:flex;flex-wrap:wrap;gap:8px 16px}
@media (max-width:1100px){.set-grid{grid-template-columns:1fr}}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Holidays &amp; Leave Types</h1>
      <p class="page-sub">Working days, holidays and leave types used by staff attendance, leave balances and payroll loss of pay.</p></div>
  </div>

  <div class="set-grid">
    <div>
      <div class="card">
        <h3 class="section-title"><span class="section-title-bar"></span>Working days</h3>
        <form id="sForm" novalidate style="padding:0 20px 20px">
          <label class="field-label">Weekly off</label>
          <div class="wk">
            <?php foreach (Staff_attendance_model::$weekdays as $k => $l): ?>
              <label class="check"><input type="checkbox" name="week_off[]" value="<?= $k ?>" <?= in_array($k, $offs, true) ? 'checked' : '' ?> <?= $can_edit ? '' : 'disabled' ?>> <?= $l ?></label>
            <?php endforeach; ?>
          </div>
          <div class="grid" style="margin-top:14px">
            <div><label class="field-label" for="sSat">Saturdays</label><select class="field-input" id="sSat" name="sat_rule" <?= $can_edit ? '' : 'disabled' ?>>
              <?php foreach (Staff_attendance_model::$sat_rules as $k => $l): ?><option value="<?= $k ?>" <?= $settings['sat_rule'] === (string)$k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select>
              <span class="field-hint">Used when Saturday is not ticked as a weekly off.</span></div>
            <div><label class="field-label" for="sLate">Late after</label><input class="field-input" type="time" id="sLate" name="late_after" value="<?= html_escape($settings['late_after']) ?>" <?= $can_edit ? '' : 'disabled' ?>>
              <span class="field-hint">Arrival after this time is counted as late.</span></div>
            <div><label class="field-label" for="sYear">Leave year starts in</label><select class="field-input" id="sYear" name="year_start" <?= $can_edit ? '' : 'disabled' ?>>
              <?php foreach ($months as $k => $l): ?><option value="<?= $k ?>" <?= (int)$settings['year_start'] === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select>
              <span class="field-hint">Leave balances restart every year from this month.</span></div>
          </div>
          <?php if ($can_edit): ?><div style="margin-top:14px;text-align:right"><button class="btn btn-primary" type="submit">Save</button></div><?php endif; ?>
        </form>
      </div>

      <div class="card" style="margin-top:20px">
        <div class="stu-head"><h3>Leave types</h3><?php if ($can_add): ?><button class="btn btn-primary btn-sm" type="button" id="addType">+ Add</button><?php endif; ?></div>
        <div class="table-wrap">
          <table class="table compact">
            <thead><tr><th>Leave</th><th>Code</th><th class="num">Days / year</th><th>Paid</th><th>Staff can apply</th><th>Status</th><th></th></tr></thead>
            <tbody id="types">
            <?php foreach ($types as $t):
              $row = array('id' => (int)$t->sltId, 'name' => $t->sltName, 'code' => $t->sltCode, 'quota' => $t->sltQuota === null ? '' : $num($t->sltQuota),
                           'paid' => (int)$t->sltPaid, 'self' => (int)$t->sltSelf, 'order' => (int)$t->sltOrder, 'active' => (int)$t->sltActive); ?>
              <tr data-t="<?= html_escape(json_encode($row)) ?>" class="<?= $t->sltActive ? '' : 'muted' ?>">
                <td><strong><?= html_escape($t->sltName) ?></strong></td><td><?= html_escape($t->sltCode) ?></td>
                <td class="num"><?= $t->sltQuota === null ? 'No limit' : $num($t->sltQuota) ?></td>
                <td><?= $t->sltPaid ? 'Paid' : '<span class="warn-txt">Unpaid (loss of pay)</span>' ?></td>
                <td><?= $t->sltSelf ? 'Yes' : 'Office only' ?></td>
                <td><?= $t->sltActive ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' ?></td>
                <td><div class="act-row">
                  <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><?php endif; ?>
                  <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del aria-label="Delete <?= html_escape($t->sltName) ?>">✕</button><?php endif; ?>
                </div></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="stu-head"><h3>Holidays <?= (int)$year ?></h3>
        <div class="act-row">
          <a class="btn btn-secondary btn-sm" href="<?= site_url('staff_att_settings') ?>?year=<?= $year - 1 ?>" aria-label="Previous year">‹ <?= $year - 1 ?></a>
          <a class="btn btn-secondary btn-sm" href="<?= site_url('staff_att_settings') ?>?year=<?= $year + 1 ?>" aria-label="Next year"><?= $year + 1 ?> ›</a>
        </div>
      </div>
      <?php if ($can_add): ?>
      <form id="hForm" class="doc-add" novalidate>
        <input class="field-input" type="date" id="hFrom" aria-label="Holiday date" style="max-width:160px">
        <input class="field-input" type="date" id="hTo" aria-label="Last date (for a holiday period)" title="Last date, for a holiday period (optional)" style="max-width:160px">
        <input class="field-input" id="hName" maxlength="100" placeholder="e.g. Onam, Christmas vacation" aria-label="Holiday name" style="flex:1;min-width:160px">
        <button class="btn btn-primary" type="submit">Add</button>
      </form>
      <?php endif; ?>
      <div class="table-wrap">
        <table class="table compact">
          <thead><tr><th>Date</th><th>Day</th><th>Holiday</th><th></th></tr></thead>
          <tbody id="hols">
          <?php foreach ($holidays as $h): ?>
            <tr data-id="<?= (int)$h->shId ?>" data-name="<?= html_escape($h->shName . ' (' . date('d-m-Y', strtotime($h->shDate)) . ')') ?>" class="<?= $h->shDate < date('Y-m-d') ? 'muted' : '' ?>">
              <td><?= date('d-m-Y', strtotime($h->shDate)) ?></td><td><?= date('l', strtotime($h->shDate)) ?></td><td><?= html_escape($h->shName) ?></td>
              <td><?php if ($can_del): ?><button class="act-btn rm" type="button" data-del aria-label="Remove">✕</button><?php endif; ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php if (!$holidays): ?><div class="empty">No holidays added for <?= (int)$year ?>.</div><?php endif; ?>
      </div>
      <div class="table-foot"><span class="small muted">Weekly offs are not listed here; they come from Working days.</span></div>
    </div>
  </div>
</main>

<div class="modal-overlay" id="tModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true" aria-labelledby="tTitle">
    <div class="modal-head"><h2 id="tTitle">Leave Type</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="tForm" novalidate>
      <input type="hidden" id="tId" value="0">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="tName">Name <span class="req">*</span></label><input class="field-input" id="tName" maxlength="60"></div>
          <div><label class="field-label" for="tCode">Code <span class="req">*</span></label><input class="field-input" id="tCode" maxlength="6" style="text-transform:uppercase"></div>
          <div><label class="field-label" for="tQuota">Days per year</label><input class="field-input" id="tQuota" inputmode="decimal" placeholder="No limit"></div>
          <div><label class="field-label" for="tOrder">Order</label><input class="field-input" type="number" id="tOrder" min="0" max="999" value="0"></div>
          <div class="full" style="display:flex;flex-direction:column;gap:8px">
            <label class="check"><input type="checkbox" id="tPaid" checked> Paid leave (untick for loss of pay)</label>
            <label class="check"><input type="checkbox" id="tSelf" checked> Staff can apply from My Attendance</label>
            <label class="check"><input type="checkbox" id="tActive" checked> Active</label>
          </div>
        </div>
        <p class="field-error" id="tErr" hidden></p>
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
  const confirmDel = async t => (await Swal.fire({title: t, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'})).isConfirmed;

  const sf = $('sForm');
  if (sf) sf.addEventListener('submit', async e => {
    e.preventDefault();
    const res = await post('<?= site_url('staff_att_settings_save') ?>', new FormData(sf));
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });

  const tm = U.modal('tModal');
  function openType(t) {
    $('tErr').hidden = true; $('tId').value = t ? t.id : 0; $('tTitle').textContent = t ? 'Edit Leave Type' : 'Add Leave Type';
    $('tName').value = t ? t.name : ''; $('tCode').value = t ? t.code : ''; $('tQuota').value = t ? t.quota : '';
    $('tOrder').value = t ? t.order : 0; $('tPaid').checked = t ? !!t.paid : true; $('tSelf').checked = t ? !!t.self : true;
    $('tActive').checked = t ? !!t.active : true; tm.show(); $('tName').focus();
  }
  const at = $('addType'); if (at) at.addEventListener('click', () => openType(null));
  $('tForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('tId').value); fd.append('name', $('tName').value.trim()); fd.append('code', $('tCode').value.trim());
    fd.append('quota', $('tQuota').value.trim()); fd.append('order', $('tOrder').value || 0);
    if ($('tPaid').checked) fd.append('paid', 1); if ($('tSelf').checked) fd.append('self', 1); if ($('tActive').checked) fd.append('active', 1);
    const res = await post('<?= site_url('staff_leave_type_save') ?>', fd);
    if (!res.status) { $('tErr').textContent = res.msg; $('tErr').hidden = false; return; }
    tm.hide(); U.ok(res.msg);
  });
  $('types').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-t]'); if (!tr) return;
    const t = JSON.parse(tr.dataset.t);
    if (e.target.closest('[data-edit]')) return openType(t);
    if (e.target.closest('[data-del]') && await confirmDel('Delete ' + t.name + '?')) {
      const fd = new FormData(); fd.append('id', t.id);
      const res = await post('<?= site_url('staff_leave_type_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });

  const hf = $('hForm');
  if (hf) hf.addEventListener('submit', async e => {
    e.preventDefault();
    if (!$('hFrom').value) return U.err('Enter the holiday date.');
    if (!$('hName').value.trim()) return U.err('Enter the holiday name.');
    const fd = new FormData(); fd.append('from', $('hFrom').value); fd.append('to', $('hTo').value); fd.append('name', $('hName').value.trim());
    const res = await post('<?= site_url('staff_holiday_save') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
  $('hols').addEventListener('click', async e => {
    const tr = e.target.closest('tr[data-id]'); if (!tr || !e.target.closest('[data-del]')) return;
    if (!await confirmDel('Remove ' + tr.dataset.name + '?')) return;
    const fd = new FormData(); fd.append('id', tr.dataset.id);
    const res = await post('<?= site_url('staff_holiday_delete') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
