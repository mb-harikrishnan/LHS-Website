<?php
/* $scope (perm, is_admin, class, division, assigned), $date, $class, $division, $rows (day sheet), $month (marked days of the month),
   $classes, $div_map, $status */
$p = $scope['perm'];
$can_save = !empty($p['can_add']) || !empty($p['can_edit']);
$marked = 0; foreach ($rows as $r) if ($r->saStatus !== null) $marked++;
$ym = substr($date, 0, 7);
$colors = array('P' => '#059669', 'A' => '#e11d48', 'H' => '#d97706', 'L' => '#6366f1');
?>
<style>
.att-opt{display:inline-flex;border:1px solid #cbd5e1;border-radius:8px;overflow:hidden}
.att-opt label{display:flex;align-items:center;justify-content:center;min-width:44px;height:var(--control-h-sm);padding:0 10px;font-size:13px;font-weight:600;cursor:pointer;border-right:1px solid #e2e8f0;color:#475569;background:#fff}
.att-opt label:last-child{border-right:0}
.att-opt input{position:absolute;opacity:0;pointer-events:none}
.att-opt input:checked + span{color:#fff}
.att-opt label:has(input:checked){color:#fff}
.att-opt label.P:has(input:checked){background:#059669}.att-opt label.A:has(input:checked){background:#e11d48}
.att-opt label.H:has(input:checked){background:#d97706}.att-opt label.L:has(input:checked){background:#6366f1}
.cal{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;padding:12px 20px 18px}
.cal .h{font-size:11px;font-weight:700;color:#64748b;text-align:center}
.cal a,.cal span.e{display:block;text-align:center;padding:6px 0;border-radius:8px;font-size:13px;text-decoration:none;color:#334155;border:1px solid transparent}
.cal a.m{background:#ecfdf5;color:#047857;font-weight:700}
.cal a.sel{border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.2)}
.cal a.f{color:#cbd5e1;pointer-events:none}
.att-wrap{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start}
@media (max-width:1100px){.att-wrap{grid-template-columns:1fr}}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Attendance</h1><p class="page-sub">Mark daily attendance. Everyone starts as Present — change only the absentees, then Save.</p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('reports/attendance_register') ?>?class=<?= html_escape($class) ?>&division=<?= html_escape($division) ?>&month=<?= html_escape($ym) ?>">Monthly Register</a>
    </div>
  </div>

  <?php if (!$scope['is_admin'] && !$scope['assigned']): ?>
    <div class="card"><div class="notice">No class and division is assigned to your account. Please contact the administrator.</div></div>
  <?php else: ?>
  <div class="att-wrap">
    <div class="card">
      <div class="table-toolbar">
        <form class="filter-bar" method="get" action="<?= site_url('attendance') ?>">
          <div class="fb"><label for="aDate">Date</label><input class="field-input" type="date" id="aDate" name="date" value="<?= html_escape($date) ?>" max="<?= date('Y-m-d') ?>"></div>
          <div class="fb"><label for="aCls">Class</label>
            <select class="field-input" id="aCls" name="class" <?= $scope['is_admin'] ? '' : 'disabled' ?>>
              <option value="">Select</option>
              <?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>" <?= (string)$class === (string)$c->cmId ? 'selected' : '' ?>><?= html_escape($c->cmName) ?></option><?php endforeach; ?>
            </select></div>
          <div class="fb"><label for="aDiv">Division</label><select class="field-input" id="aDiv" name="division" <?= $scope['is_admin'] ? '' : 'disabled' ?>></select></div>
          <button type="submit" class="btn btn-primary">Load</button>
        </form>
      </div>
      <?php if ($class === '' || $division === ''): ?>
        <div class="notice">Choose the class and division.</div>
      <?php elseif (!$rows): ?>
        <div class="notice">No students in this class / division.</div>
      <?php else: ?>
        <div class="table-toolbar" style="justify-content:space-between">
          <span><?= date('l, d M Y', strtotime($date)) ?> · <?= $marked ? '<span class="badge badge-green">Marked</span>' : '<span class="badge badge-amber">Not marked yet</span>' ?></span>
          <span class="muted" id="counts"></span>
        </div>
        <div class="table-wrap">
          <table class="table compact">
            <thead><tr><th>#</th><th>Adm No</th><th>Name</th><th>Status</th><th>Remark</th></tr></thead>
            <tbody id="rows">
            <?php foreach ($rows as $i => $r): $cur = $r->saStatus ?: 'P'; ?>
              <tr data-id="<?= (int)$r->smId ?>">
                <td><?= $i + 1 ?></td><td><?= html_escape($r->smAdmissionNo) ?></td><td><strong><?= html_escape($r->smName) ?></strong></td>
                <td><div class="att-opt">
                  <?php foreach ($status as $k => $label): ?>
                    <label class="<?= $k ?>" title="<?= $label ?>"><input type="radio" name="st<?= (int)$r->smId ?>" value="<?= $k ?>" <?= $cur === $k ? 'checked' : '' ?> <?= $can_save ? '' : 'disabled' ?>><?= $k ?></label>
                  <?php endforeach; ?>
                </div></td>
                <td><input class="field-input sm rmk" maxlength="100" value="<?= html_escape($r->saRemark) ?>" placeholder="optional" <?= $can_save ? '' : 'disabled' ?>></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php if ($can_save): ?>
        <div class="table-foot">
          <div class="act-row">
            <button type="button" class="btn btn-secondary btn-sm" id="allP">All present</button>
            <?php if (!empty($p['can_delete']) && $marked): ?><button type="button" class="btn btn-ghost btn-sm" id="clearDay">Clear day (holiday)</button><?php endif; ?>
          </div>
          <button type="button" class="btn btn-primary" id="saveBtn">Save Attendance</button>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <?php if ($class !== '' && $division !== ''):
      $first = strtotime($ym . '-01'); $days = (int)date('t', $first); $pad = (int)date('N', $first) - 1; ?>
    <div class="card">
      <h3 class="section-title"><span class="section-title-bar"></span><?= date('F Y', $first) ?></h3>
      <div class="cal">
        <?php foreach (array('M', 'T', 'W', 'T', 'F', 'S', 'S') as $d): ?><span class="h"><?= $d ?></span><?php endforeach; ?>
        <?php for ($i = 0; $i < $pad; $i++): ?><span class="e"></span><?php endfor; ?>
        <?php for ($d = 1; $d <= $days; $d++): $dt = $ym . '-' . sprintf('%02d', $d);
          $cls = (isset($month[$dt]) ? 'm ' : '') . ($dt === $date ? 'sel ' : '') . ($dt > date('Y-m-d') ? 'f' : '');
          $tip = isset($month[$dt]) ? 'Absent ' . $month[$dt]['A'] . ', half day ' . $month[$dt]['H'] . ', leave ' . $month[$dt]['L'] : 'Not marked'; ?>
          <a class="<?= trim($cls) ?>" title="<?= $tip ?>" href="<?= site_url('attendance') ?>?date=<?= $dt ?>&class=<?= html_escape($class) ?>&division=<?= html_escape($division) ?>"><?= $d ?></a>
        <?php endfor; ?>
      </div>
      <p class="field-hint" style="padding:0 20px 16px">Green = attendance marked. Click a day to view or change it.</p>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const DIV_MAP = <?= json_encode($div_map) ?>, SEL = <?= json_encode((string)$division) ?>;
  function fillDiv(v) {
    const list = DIV_MAP[$('aCls').value] || [];
    $('aDiv').innerHTML = '<option value="">Select</option>' + list.map(d => `<option value="${U.esc(d.id)}">${U.esc(d.name)}</option>`).join('');
    $('aDiv').value = v || '';
  }
  $('aCls').addEventListener('change', () => fillDiv(''));
  fillDiv(SEL);
  <?php if (!$scope['is_admin']): ?>
  // disabled selects are not submitted; send the fixed class / division as hidden fields
  document.querySelector('.filter-bar').insertAdjacentHTML('beforeend', '<input type="hidden" name="class" value="<?= html_escape($class) ?>"><input type="hidden" name="division" value="<?= html_escape($division) ?>">');
  <?php endif; ?>

  const rows = $('rows'); if (!rows) return;
  function counts() {
    const c = {P: 0, A: 0, H: 0, L: 0};
    rows.querySelectorAll('input[type=radio]:checked').forEach(i => c[i.value]++);
    $('counts').textContent = `Present ${c.P} · Absent ${c.A} · Half day ${c.H} · Leave ${c.L}`;
  }
  rows.addEventListener('change', counts); counts();
  const allP = $('allP'); if (allP) allP.addEventListener('click', () => { rows.querySelectorAll('input[value=P]').forEach(i => i.checked = true); counts(); });

  async function send(extra) {
    const fd = new FormData();
    fd.append('date', '<?= html_escape($date) ?>'); fd.append('class', '<?= html_escape($class) ?>'); fd.append('division', '<?= html_escape($division) ?>');
    if (extra) Object.keys(extra).forEach(k => fd.append(k, extra[k]));
    else rows.querySelectorAll('tr').forEach(tr => {
      const s = tr.querySelector('input[type=radio]:checked');
      fd.append('status[' + tr.dataset.id + ']', s ? s.value : 'P');
      fd.append('remark[' + tr.dataset.id + ']', tr.querySelector('.rmk').value.trim());
    });
    const res = await U.post('<?= site_url('attendance_save') ?>', fd);
    res.status ? U.ok(res.msg) : U.err(res.msg);
  }
  const save = $('saveBtn'); if (save) save.addEventListener('click', () => send(null));
  const clr = $('clearDay'); if (clr) clr.addEventListener('click', async () => {
    const c = await Swal.fire({title: 'Clear attendance for this day?', text: 'Use this when the day was a holiday.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Clear'});
    if (c.isConfirmed) send({clear: '1'});
  });
})();
</script>
