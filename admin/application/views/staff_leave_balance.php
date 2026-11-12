<?php /* $year (start date), $years, $category, $staff, $types, $bal [peId][sltId], $custom [peId][sltId], $perm */
$can_edit = !empty($perm['can_edit']);
$num = function ($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); };
?>
<style>
.bal-cell{display:block;width:100%;text-align:right;background:none;border:1px solid transparent;border-radius:6px;padding:4px 6px;font:inherit;cursor:default}
button.bal-cell{cursor:pointer}
button.bal-cell:hover{border-color:#c7d2fe;background:#eef2ff}
.bal-cell strong{font-size:14px}
.bal-cell small{display:block;color:#64748b;font-size:11px}
.bal-cell.custom small{color:#4f46e5}
.bal-cell.neg strong{color:#be123c}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Leave Balances</h1>
      <p class="page-sub">Balance = entitlement − leave taken in the leave year. <?= $can_edit ? 'Click a cell to change one staff member\'s entitlement (for example to add carried-forward leave).' : '' ?></p></div>
  </div>
  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('staff_leave_balance') ?>">
        <div class="fb"><label for="fy">Leave year</label><select class="field-input" id="fy" name="year">
          <?php foreach ($years as $k => $l): ?><option value="<?= $k ?>" <?= $k === $year ? 'selected' : '' ?>><?= html_escape($l) ?></option><?php endforeach; ?></select></div>
        <div class="fb"><label for="fc">Staff</label><select class="field-input" id="fc" name="category"><option value="">All staff</option>
          <?php foreach (Staff_attendance_model::$categories as $k => $l): ?><option value="<?= $k ?>" <?= $category === $k ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
        <button class="btn btn-primary" type="submit">Show</button>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Staff</th>
          <?php foreach ($types as $t): ?><th class="num" title="<?= html_escape($t->sltName) ?>"><?= html_escape($t->sltCode) ?><div class="small muted" style="font-weight:400"><?= $t->sltQuota === null ? 'no limit' : $num($t->sltQuota) . ' / yr' ?></div></th><?php endforeach; ?>
        </tr></thead>
        <tbody id="rows">
        <?php foreach ($staff as $s): ?>
          <tr data-id="<?= (int)$s->peId ?>" data-name="<?= html_escape($s->peName) ?>">
            <td><strong><?= html_escape($s->peName) ?></strong><div class="small muted"><?= html_escape($s->peDesignation ?: '') ?></div></td>
            <?php foreach ($types as $t): $x = $bal[(int)$s->peId][(int)$t->sltId]; $cust = isset($custom[(int)$s->peId][(int)$t->sltId]);
              $tag = $can_edit ? 'button type="button"' : 'span';
              $cls = 'bal-cell' . ($cust ? ' custom' : '') . ($x['balance'] !== null && $x['balance'] < 0 ? ' neg' : ''); ?>
              <td class="num"><<?= $tag ?> class="<?= $cls ?>" data-type="<?= (int)$t->sltId ?>" data-tname="<?= html_escape($t->sltName) ?>" data-ent="<?= $cust ? $num($x['entitled']) : '' ?>" data-def="<?= $t->sltQuota === null ? '' : $num($t->sltQuota) ?>">
                <?php if ($x['entitled'] === null): ?><strong><?= $x['used'] ? $num($x['used']) : '—' ?></strong><small><?= $x['used'] ? 'taken' : '' ?></small>
                <?php else: ?><strong><?= $num($x['balance']) ?></strong><small>of <?= $num($x['entitled']) ?><?= $x['used'] ? ' · taken ' . $num($x['used']) : '' ?><?= $x['pending'] ? ' · ' . $num($x['pending']) . ' waiting' : '' ?></small><?php endif; ?>
              </<?= $can_edit ? 'button' : 'span' ?>></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$staff): ?><div class="empty">No staff on payroll in this leave year.</div><?php endif; ?>
    </div>
    <div class="table-foot"><span class="small muted">Blue figures are set for that staff member; the others use the leave type's yearly days.</span>
      <a class="btn btn-secondary" href="<?= site_url('reports/staff_leave_balance') ?>?year=<?= html_escape($year) ?>">Print / Excel</a></div>
  </div>
</main>
<?php if ($can_edit): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI;
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  document.getElementById('rows').addEventListener('click', async e => {
    const b = e.target.closest('button.bal-cell'); if (!b) return;
    const tr = b.closest('tr');
    const r = await Swal.fire({title: b.dataset.tname + ' — ' + tr.dataset.name, input: 'text', inputValue: b.dataset.ent,
      inputLabel: 'Days for <?= html_escape(date('M Y', strtotime($year))) ?> leave year. Leave empty to use the default' + (b.dataset.def ? ' (' + b.dataset.def + ')' : ' (no limit)') + '.',
      showCancelButton: true, confirmButtonText: 'Save',
      inputValidator: v => v.trim() !== '' && !/^\d{1,3}(\.[05])?$/.test(v.trim()) ? 'Enter whole or half days, like 12 or 7.5' : undefined});
    if (!r.isConfirmed) return;
    const fd = new FormData();
    fd.append('staff', tr.dataset.id); fd.append('type', b.dataset.type); fd.append('year', '<?= html_escape($year) ?>'); fd.append('days', r.value.trim());
    let res; try { res = await U.post('<?= site_url('staff_leave_alloc_save') ?>', fd); } catch (x) { res = {status: false, msg: 'Your session may have expired. Reload the page.'}; }
    res.status ? U.ok(res.msg) : U.err(res.msg);
  });
})();
</script>
<?php endif; ?>
