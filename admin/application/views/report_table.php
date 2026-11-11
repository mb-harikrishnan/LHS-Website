<?php
/* Generic report renderer
   $r : title, route, year, filters[], columns[{key,label,type,total}], rows[], group(bool), note, need, count_label
   $f : current filter values;  $classes, $div_map, $fees, $modes */
$cols   = $r['columns'];
$ncols  = count($cols);
$has    = array_flip($r['filters']);
$totals = array();
foreach ($cols as $c) if (!empty($c['total'])) $totals[$c['key']] = 0;

$cell = function ($c, $row) {
    $v = isset($row[$c['key']]) ? $row[$c['key']] : '';
    $t = isset($c['type']) ? $c['type'] : 'text';
    if ($t === 'money') return ($v === '' || $v === null) ? '' : fee_money($v);
    if ($t === 'int')   return ($v === '' || $v === null) ? '' : (int)$v;
    return html_escape($v);
};
$isNum = function ($c) { return isset($c['type']) && in_array($c['type'], array('money', 'int'), true); };

/* one subtotal / total row */
$sumRow = function ($label, $sums, $count, $cls) use ($cols) {
    $h = '<tr class="' . $cls . '">';
    $labelDone = false;
    foreach ($cols as $c) {
        if (array_key_exists($c['key'], $sums)) {
            $h .= '<td class="num">' . fee_money($sums[$c['key']]) . '</td>';
        } elseif (!$labelDone) {
            $h .= '<td>' . html_escape($label) . ($count !== null ? ' (' . $count . ')' : '') . '</td>';
            $labelDone = true;
        } else {
            $h .= '<td></td>';
        }
    }
    return $h . '</tr>';
};

$qs = $_GET; unset($qs['export']);
$csv_url = site_url($r['route']) . '?' . http_build_query(array_merge($qs, array('export' => 'csv')));
?>
<main class="page">
  <div class="page-header no-print">
    <div>
      <h1 class="page-title"><?= html_escape($r['title']) ?></h1>
      <p class="page-sub">Academic year <?= html_escape($r['year']) ?><?= $r['note'] ? ' · ' . html_escape($r['note']) : '' ?></p>
    </div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url($r['menu'] === 'STUDENT_REPORTS' ? 'reports/students' : 'reports/fees') ?>">All Reports</a>
      <a class="btn btn-secondary" href="<?= html_escape($csv_url) ?>">Excel (CSV)</a>
      <button class="btn btn-primary" type="button" onclick="window.print()">Print</button>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar no-print">
      <form class="filter-bar" method="get" action="<?= site_url($r['route']) ?>">
        <?php if (isset($has['student']) && $f['student']): ?>
          <input type="hidden" name="student" value="<?= (int)$f['student'] ?>">
        <?php endif; ?>
        <?php if (isset($has['q'])): ?>
          <div class="fb" style="flex:1;min-width:180px"><label for="rq">Student</label>
            <input class="field-input" id="rq" name="q" value="<?= html_escape($f['q']) ?>" placeholder="Name or admission no"></div>
        <?php endif; ?>
        <?php if (isset($has['from'])): ?>
          <div class="fb"><label for="rfrom">From</label><input class="field-input" type="date" id="rfrom" name="from" value="<?= html_escape($f['from']) ?>"></div>
          <div class="fb"><label for="rto">To</label><input class="field-input" type="date" id="rto" name="to" value="<?= html_escape($f['to']) ?>"></div>
        <?php endif; ?>
        <?php if (isset($has['as_of'])): ?>
          <div class="fb"><label for="rasof">As on</label><input class="field-input" type="date" id="rasof" name="as_of" value="<?= html_escape($f['as_of']) ?>"></div>
        <?php endif; ?>
        <?php if (isset($has['class'])): ?>
          <div class="fb"><label for="rcls">Class</label>
            <select class="field-input" id="rcls" name="class">
              <option value="">All Classes</option>
              <?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>" <?= (string)$f['class'] === (string)$c->cmId ? 'selected' : '' ?>><?= html_escape($c->cmName) ?></option><?php endforeach; ?>
            </select></div>
        <?php endif; ?>
        <?php if (isset($has['division'])): ?>
          <div class="fb"><label for="rdiv">Division</label><select class="field-input" id="rdiv" name="division"><option value="">All</option></select></div>
        <?php endif; ?>
        <?php if (isset($has['gender'])): ?>
          <div class="fb"><label for="rgen">Gender</label>
            <select class="field-input" id="rgen" name="gender">
              <option value="">All</option>
              <option value="1" <?= $f['gender'] === '1' ? 'selected' : '' ?>>Male</option>
              <option value="0" <?= $f['gender'] === '0' ? 'selected' : '' ?>>Female</option>
            </select></div>
        <?php endif; ?>
        <?php if (isset($has['fee'])): ?>
          <div class="fb"><label for="rfee">Fee</label>
            <select class="field-input" id="rfee" name="fee">
              <option value="">All Fees</option>
              <?php foreach ($fees as $x): ?><option value="<?= (int)$x->fmId ?>" <?= (int)$f['fee'] === (int)$x->fmId ? 'selected' : '' ?>><?= html_escape($x->fmName) ?></option><?php endforeach; ?>
            </select></div>
        <?php endif; ?>
        <?php if (isset($has['fee_type'])): ?>
          <div class="fb"><label for="rft">Fee Type</label>
            <select class="field-input" id="rft" name="fee_type">
              <option value="">All</option>
              <option value="0" <?= $f['fee_type'] === '0' ? 'selected' : '' ?>>Regular</option>
              <option value="1" <?= $f['fee_type'] === '1' ? 'selected' : '' ?>>Special</option>
            </select></div>
        <?php endif; ?>
        <?php if (isset($has['mode'])): ?>
          <div class="fb"><label for="rmode">Mode</label>
            <select class="field-input" id="rmode" name="mode">
              <option value="">All</option>
              <?php foreach ($modes as $m): ?><option <?= $f['mode'] === $m ? 'selected' : '' ?>><?= html_escape($m) ?></option><?php endforeach; ?>
            </select></div>
        <?php endif; ?>
        <?php if (isset($has['group'])): ?>
          <div class="fb"><label for="rgrp">Group by</label>
            <select class="field-input" id="rgrp" name="group">
              <option value="">None</option>
              <?php foreach (array('date' => 'Date', 'class' => 'Class', 'mode' => 'Mode') as $k => $l): ?>
                <option value="<?= $k ?>" <?= $f['group'] === $k ? 'selected' : '' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select></div>
        <?php endif; ?>
        <?php if (isset($has['only_balance'])): ?>
          <div class="fb"><label for="rob">Students</label>
            <select class="field-input" id="rob" name="only_balance">
              <option value="1" <?= $f['only_balance'] === '1' ? 'selected' : '' ?>>With balance only</option>
              <option value="0" <?= $f['only_balance'] === '0' ? 'selected' : '' ?>>All</option>
            </select></div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Show</button>
        <a class="btn btn-secondary" href="<?= site_url($r['route']) ?>">Reset</a>
      </form>
    </div>

    <div class="print-head">
      <h2>Little Hearts School</h2>
      <p><strong><?= html_escape($r['title']) ?></strong> · Academic year <?= html_escape($r['year']) ?></p>
      <?php if ($r['note']): ?><p><?= html_escape($r['note']) ?></p><?php endif; ?>
      <p>Printed on <?= date('d-m-Y H:i') ?></p>
    </div>

    <?php if ($r['need']): ?>
      <div class="notice"><?= html_escape($r['need']) ?></div>
    <?php else: ?>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr>
          <?php foreach ($cols as $c): ?><th class="<?= $isNum($c) ? 'num' : '' ?>"><?= html_escape($c['label']) ?></th><?php endforeach; ?>
        </tr></thead>
        <tbody>
        <?php
          $grp = null; $gsum = $totals; $gcount = 0;
          foreach ($r['rows'] as $row):
            if ($r['group'] && $row['_group'] !== $grp):
              if ($grp !== null) echo $sumRow('Sub Total', $gsum, $gcount, 'row-sub');
              $grp = $row['_group']; $gsum = $totals; $gcount = 0; ?>
              <tr class="row-group"><td colspan="<?= $ncols ?>"><?= html_escape($grp) ?></td></tr>
          <?php endif;
            $gcount++;
            foreach ($totals as $k => $v) {
                $val = isset($row[$k]) ? (float)$row[$k] : 0;
                $totals[$k] += $val;
                $gsum[$k]   += $val;
            } ?>
          <tr>
            <?php foreach ($cols as $c): ?><td class="<?= $isNum($c) ? 'num' : '' ?>"><?= $cell($c, $row) ?></td><?php endforeach; ?>
          </tr>
        <?php endforeach;
          if ($r['group'] && $grp !== null) echo $sumRow('Sub Total', $gsum, $gcount, 'row-sub');
          if (!empty($r['rows']) && $totals) echo $sumRow('Grand Total', $totals, count($r['rows']), 'row-total');
        ?>
        </tbody>
      </table>
      <?php if (empty($r['rows'])): ?><div class="empty">No records found for the selected filters.</div><?php endif; ?>
    </div>
    <div class="table-foot no-print"><span class="muted"><?= count($r['rows']) ?> <?= html_escape($r['count_label']) ?></span></div>
    <?php endif; ?>
  </div>
</main>

<?php if (isset($has['division'])): ?>
<script>
(function () {
  const MAP = <?= json_encode($div_map) ?>, SEL = <?= json_encode((string)$f['division']) ?>;
  const cls = document.getElementById('rcls'), div = document.getElementById('rdiv');
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  function fill(v) {
    const list = cls ? (MAP[cls.value] || []) : [];
    div.innerHTML = '<option value="">All</option>' + list.map(d => `<option value="${esc(d.id)}">${esc(d.name)}</option>`).join('');
    div.value = v || '';
  }
  if (cls) cls.addEventListener('change', () => fill(''));
  fill(SEL);
})();
</script>
<?php endif; ?>
