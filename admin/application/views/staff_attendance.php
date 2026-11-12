<?php /* $date, $category, $rows (staff with ->att), $off [date => reason] of the month, $marks [date => [n, a]], $types, $perm, $late_after, $pending */
$can_save = !empty($perm['can_add']) || !empty($perm['can_edit']);
$offToday = isset($off[$date]) ? $off[$date] : '';
$ym = substr($date, 0, 7);
$prev = date('Y-m-d', strtotime($date . ' -1 day')); $next = date('Y-m-d', strtotime($date . ' +1 day'));
$q = $category ? '&category=' . rawurlencode($category) : '';
$marked = 0; foreach ($rows as $r) if ($r->att) $marked++;
?>
<style>
.att-opt{display:inline-flex;border:1px solid #cbd5e1;border-radius:8px;overflow:hidden}
.att-opt label{display:flex;align-items:center;justify-content:center;min-width:40px;height:var(--control-h-sm);padding:0 8px;font-size:12.5px;font-weight:700;cursor:pointer;border-right:1px solid #e2e8f0;color:#475569;background:#fff}
.att-opt label:last-child{border-right:0}
.att-opt input{position:absolute;opacity:0;pointer-events:none}
.att-opt label:has(input:focus-visible){outline:2px solid #6366f1;outline-offset:-2px}
.att-opt label:has(input:checked){color:#fff}
.att-opt label.P:has(input:checked){background:#059669}.att-opt label.A:has(input:checked){background:#e11d48}
.att-opt label.HD:has(input:checked){background:#d97706}.att-opt label.L:has(input:checked){background:#6366f1}
.att-opt label.HL:has(input:checked){background:#8b5cf6}.att-opt label.OD:has(input:checked){background:#0284c7}
.att-opt input:disabled + span{cursor:not-allowed}
.sa-table input[type=time]{width:110px}
.sa-table select.lt{width:150px}
.late{color:#b45309;font-size:11px;font-weight:700}
.cal{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;padding:12px 20px 18px}
.cal .h{font-size:11px;font-weight:700;color:#64748b;text-align:center}
.cal a,.cal span.e{display:block;text-align:center;padding:6px 0;border-radius:8px;font-size:13px;text-decoration:none;color:#334155;border:1px solid transparent}
.cal a.m{background:#ecfdf5;color:#047857;font-weight:700}
.cal a.o{background:#f1f5f9;color:#94a3b8}
.cal a.sel{border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.2)}
.cal a.f{color:#cbd5e1;pointer-events:none}
.att-wrap{display:grid;grid-template-columns:1fr 290px;gap:20px;align-items:start}
.off-banner{margin:0 20px 12px;padding:10px 14px;border-radius:10px;background:#f1f5f9;border:1px solid #e2e8f0;color:#334155}
@media (max-width:1200px){.att-wrap{grid-template-columns:1fr}}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Staff Attendance</h1>
      <p class="page-sub">Everyone starts as Present on a working day — change only the exceptions, then Save. P present · A absent · HD half day · L leave · HL half-day leave · OD on duty.</p></div>
    <div class="page-actions">
      <?php if ($pending): ?><a class="btn btn-secondary" href="<?= site_url('staff_leave') ?>"><?= (int)$pending ?> leave request(s) waiting</a><?php endif; ?>
      <a class="btn btn-secondary" href="<?= site_url('reports/staff_att_register') ?>?month=<?= html_escape($ym) ?>">Monthly Register</a>
    </div>
  </div>

  <div class="att-wrap">
    <div class="card">
      <div class="table-toolbar">
        <form class="filter-bar" method="get" action="<?= site_url('staff_attendance') ?>">
          <a class="btn btn-secondary" href="<?= site_url('staff_attendance') ?>?date=<?= $prev . $q ?>" aria-label="Previous day">‹</a>
          <div class="fb"><label for="sDate">Date</label><input class="field-input" type="date" id="sDate" name="date" value="<?= html_escape($date) ?>" max="<?= date('Y-m-d') ?>"></div>
          <?php if ($next <= date('Y-m-d')): ?><a class="btn btn-secondary" href="<?= site_url('staff_attendance') ?>?date=<?= $next . $q ?>" aria-label="Next day">›</a><?php endif; ?>
          <div class="fb"><label for="sCat">Staff</label><select class="field-input" id="sCat" name="category"><option value="">All staff</option>
            <?php foreach (Staff_attendance_model::$categories as $k => $l): ?><option value="<?= $k ?>" <?= $category === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
          <button type="submit" class="btn btn-primary">Load</button>
        </form>
      </div>
      <div class="table-toolbar" style="justify-content:space-between">
        <span><strong><?= date('l, d M Y', strtotime($date)) ?></strong> · <?= $marked ? '<span class="badge badge-green">Marked ' . $marked . ' / ' . count($rows) . '</span>' : '<span class="badge badge-amber">Not marked yet</span>' ?></span>
        <span class="muted" id="counts"></span>
      </div>
      <?php if ($offToday): ?><div class="off-banner"><strong>Not a working day:</strong> <?= html_escape($offToday) ?>. Mark only staff who came to work.</div><?php endif; ?>

      <?php if (!$rows): ?>
        <div class="notice">No staff on payroll for this date. Staff come from Payroll &gt; Salary Structure (tick "On payroll").</div>
      <?php else: ?>
      <div class="table-wrap">
        <table class="table compact sa-table">
          <thead><tr><th>Staff</th><th>Status</th><th>Leave type</th><th>In</th><th>Out</th><th>Remark</th></tr></thead>
          <tbody id="rows">
          <?php $cat = null; foreach ($rows as $r): $a = $r->att; $lock = $a && $a->satLaId;
            $cur = $a ? $a->satStatus : ($offToday ? '' : 'P');
            if ($r->peCategory !== $cat): $cat = $r->peCategory; ?>
              <tr class="row-group"><td colspan="6"><?= html_escape(Staff_attendance_model::$categories[$cat]) ?> staff</td></tr>
            <?php endif; ?>
            <tr data-id="<?= (int)$r->peId ?>" <?= $lock ? 'data-lock="1"' : '' ?>>
              <td><strong><?= html_escape($r->peName) ?></strong><div class="small muted"><?= html_escape(trim(($r->peCode ? $r->peCode . ' · ' : '') . $r->peDesignation)) ?></div></td>
              <td><div class="att-opt" role="radiogroup" aria-label="Status of <?= html_escape($r->peName) ?>">
                <?php foreach (Staff_attendance_model::$status as $k => $label): ?>
                  <label class="<?= $k ?>" title="<?= $label ?>"><input type="radio" name="st<?= (int)$r->peId ?>" value="<?= $k ?>" <?= $cur === $k ? 'checked' : '' ?> <?= $can_save && !$lock ? '' : 'disabled' ?>><?= $k ?></label>
                <?php endforeach; ?>
              </div><?= $lock ? '<div class="small muted">From approved leave request</div>' : '' ?></td>
              <td><select class="field-input sm lt" aria-label="Leave type" <?= $can_save && !$lock ? '' : 'disabled' ?>>
                <option value="">—</option>
                <?php foreach ($types as $t): ?><option value="<?= (int)$t->sltId ?>" <?= $a && (int)$a->satSltId === (int)$t->sltId ? 'selected' : '' ?>><?= html_escape($t->sltCode . ' — ' . $t->sltName) ?></option><?php endforeach; ?>
              </select></td>
              <td><input class="field-input sm t-in" type="time" value="<?= $a && $a->satIn ? substr($a->satIn, 0, 5) : '' ?>" aria-label="In time" <?= $can_save && !$lock ? '' : 'disabled' ?>><div class="late" hidden>Late</div></td>
              <td><input class="field-input sm t-out" type="time" value="<?= $a && $a->satOut ? substr($a->satOut, 0, 5) : '' ?>" aria-label="Out time" <?= $can_save && !$lock ? '' : 'disabled' ?>></td>
              <td><input class="field-input sm rmk" maxlength="100" value="<?= $a ? html_escape($a->satRemark) : '' ?>" placeholder="optional" aria-label="Remark" <?= $can_save && !$lock ? '' : 'disabled' ?>></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php if ($can_save): ?>
      <div class="table-foot">
        <div class="act-row">
          <button type="button" class="btn btn-secondary btn-sm" id="allP">All present</button>
          <?php if (!empty($perm['can_delete']) && $marked): ?><button type="button" class="btn btn-ghost btn-sm" id="clearDay">Clear day</button><?php endif; ?>
        </div>
        <button type="button" class="btn btn-primary" id="saveBtn">Save Attendance</button>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </div>

    <?php $first = strtotime($ym . '-01'); $days = (int)date('t', $first); $pad = (int)date('N', $first) - 1; ?>
    <div class="card">
      <h3 class="section-title"><span class="section-title-bar"></span><?= date('F Y', $first) ?></h3>
      <div class="cal">
        <?php foreach (array('M', 'T', 'W', 'T', 'F', 'S', 'S') as $d): ?><span class="h"><?= $d ?></span><?php endforeach; ?>
        <?php for ($i = 0; $i < $pad; $i++): ?><span class="e"></span><?php endfor; ?>
        <?php for ($d = 1; $d <= $days; $d++): $dt = $ym . '-' . sprintf('%02d', $d);
          $cls = (isset($marks[$dt]) ? 'm ' : (isset($off[$dt]) ? 'o ' : '')) . ($dt === $date ? 'sel ' : '') . ($dt > date('Y-m-d') ? 'f' : '');
          $tip = isset($off[$dt]) ? $off[$dt] . '. ' : '';
          $tip .= isset($marks[$dt]) ? $marks[$dt]['n'] . ' marked, ' . $marks[$dt]['a'] . ' absent' : 'Not marked'; ?>
          <a class="<?= trim($cls) ?>" title="<?= html_escape($tip) ?>" href="<?= site_url('staff_attendance') ?>?date=<?= $dt . $q ?>"><?= $d ?></a>
        <?php endfor; ?>
      </div>
      <p class="field-hint" style="padding:0 20px 16px">Green = marked · Grey = holiday or weekly off. Click a day to open it.</p>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const rows = $('rows'); if (!rows) return;
  const LATE = '<?= html_escape($late_after) ?>', DATE = '<?= html_escape($date) ?>';
  const post = async (url, fd) => { try { return await U.post(url, fd); } catch (e) { return {status: false, msg: 'Your session may have expired. Reload the page and try again.'}; } };
  const staffRows = () => rows.querySelectorAll('tr[data-id]');

  function sync(tr) {
    const s = tr.querySelector('input[type=radio]:checked'), v = s ? s.value : '';
    const lt = tr.querySelector('.lt'), leave = v === 'L' || v === 'HL', away = v === 'A' || v === 'L';
    if (!tr.dataset.lock) {
      lt.disabled = !leave; if (!leave) lt.value = '';
      tr.querySelector('.t-in').disabled = away; tr.querySelector('.t-out').disabled = away;
    }
    const tin = tr.querySelector('.t-in').value;
    tr.querySelector('.late').hidden = !(tin && tin > LATE && (v === 'P' || v === 'OD'));
  }
  function counts() {
    const c = {P: 0, A: 0, HD: 0, L: 0, HL: 0, OD: 0}; let none = 0;
    staffRows().forEach(tr => { const s = tr.querySelector('input[type=radio]:checked'); s ? c[s.value]++ : none++; });
    $('counts').textContent = `Present ${c.P} · Absent ${c.A} · Half day ${c.HD} · Leave ${c.L + c.HL} · On duty ${c.OD}` + (none ? ` · Not marked ${none}` : '');
  }
  staffRows().forEach(sync); counts();
  rows.addEventListener('change', e => { const tr = e.target.closest('tr[data-id]'); if (tr) sync(tr); counts(); });
  rows.addEventListener('input', e => { const tr = e.target.closest('tr[data-id]'); if (tr && e.target.classList.contains('t-in')) sync(tr); });

  const allP = $('allP');
  if (allP) allP.addEventListener('click', () => {
    staffRows().forEach(tr => { if (tr.dataset.lock) return; tr.querySelector('input[value=P]').checked = true; sync(tr); }); counts();
  });

  const save = $('saveBtn');
  if (save) save.addEventListener('click', async () => {
    const fd = new FormData(); fd.append('date', DATE); let n = 0;
    for (const tr of staffRows()) {
      if (tr.dataset.lock) continue;
      const s = tr.querySelector('input[type=radio]:checked'); if (!s) continue;
      const id = tr.dataset.id, lt = tr.querySelector('.lt').value;
      if ((s.value === 'L' || s.value === 'HL') && !lt) {
        tr.querySelector('.lt').focus();
        return U.err('Select the leave type for ' + tr.querySelector('strong').textContent + '.');
      }
      fd.append('status[' + id + ']', s.value); fd.append('type[' + id + ']', lt);
      fd.append('in[' + id + ']', tr.querySelector('.t-in').value); fd.append('out[' + id + ']', tr.querySelector('.t-out').value);
      fd.append('remark[' + id + ']', tr.querySelector('.rmk').value.trim()); n++;
    }
    if (!n) return U.err('Select a status for at least one staff member.');
    save.disabled = true;
    const res = await post('<?= site_url('staff_attendance_save') ?>', fd);
    save.disabled = false;
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });

  const clr = $('clearDay');
  if (clr) clr.addEventListener('click', async () => {
    const c = await Swal.fire({title: 'Clear attendance for this day?', text: 'All marks of this day are removed. Leave from approved requests is kept.', icon: 'warning',
      showCancelButton: true, confirmButtonText: 'Clear', confirmButtonColor: '#dc2626'});
    if (!c.isConfirmed) return;
    const fd = new FormData(); fd.append('date', DATE); fd.append('clear', '1');
    const res = await post('<?= site_url('staff_attendance_save') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
