<?php /* $child, $ym, $days ['Y-m-d' => [status, remark]], $sum {year, month}, $month_sum, $status */
$first = strtotime($ym . '-01'); $n = (int)date('t', $first); $pad = (int)date('N', $first) - 1;
$prev = date('Y-m', strtotime('-1 month', $first)); $next = date('Y-m', strtotime('+1 month', $first));
$ms = isset($month_sum[$child->smId]) ? $month_sum[$child->smId] : array('P' => 0, 'A' => 0, 'H' => 0, 'L' => 0, 'days' => 0, 'pct' => null);
$ys = $sum['year'];
$notes = array(); foreach ($days as $d => $v) if ($v[1]) $notes[$d] = $v;
?>
<h1 class="pp-h1">Attendance — <?= html_escape($child->smName) ?></h1>
<div class="pp-grid">
  <section class="pp-card">
    <div class="pp-card-h">
      <a class="btn btn-secondary btn-sm" href="?month=<?= $prev ?>" aria-label="Previous month">‹</a>
      <h3><?= date('F Y', $first) ?></h3>
      <?php if ($next <= date('Y-m')): ?><a class="btn btn-secondary btn-sm" href="?month=<?= $next ?>" aria-label="Next month">›</a><?php else: ?><span style="width:32px"></span><?php endif; ?>
    </div>
    <div class="pp-cal">
      <?php foreach (array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun') as $h): ?><span class="h"><?= $h ?></span><?php endforeach; ?>
      <?php for ($i = 0; $i < $pad; $i++): ?><span></span><?php endfor; ?>
      <?php for ($d = 1; $d <= $n; $d++): $dt = $ym . '-' . sprintf('%02d', $d); $s = isset($days[$dt]) ? $days[$dt][0] : ''; ?>
        <span class="d <?= $s ?> <?= $dt === date('Y-m-d') ? 'today' : '' ?>" title="<?= $s ? html_escape($status[$s]) : 'Not marked / holiday' ?>"><?= $d ?><?= $s ? '<b>' . $s . '</b>' : '' ?></span>
      <?php endfor; ?>
    </div>
    <div class="pp-legend"><span><i style="background:#d1fae5"></i>Present</span><span><i style="background:#ffe4e6"></i>Absent</span>
      <span><i style="background:#fef3c7"></i>Half day</span><span><i style="background:#e0e7ff"></i>Leave</span><span><i style="background:#f8fafc;border:1px solid #e2e8f0"></i>Holiday / not marked</span></div>
  </section>

  <section class="pp-card">
    <div class="pp-card-h"><h3>Summary</h3></div>
    <div class="pp-card-b">
      <div class="pp-stats">
        <div class="pp-stat <?= $ms['pct'] !== null && $ms['pct'] < 75 ? 'bad' : 'ok' ?>"><small>This month</small><strong><?= $ms['pct'] === null ? '—' : $ms['pct'] . '%' ?></strong>
          <span><?= $ms['P'] ?> present · <?= $ms['A'] ?> absent<?= $ms['H'] ? ' · ' . $ms['H'] . ' half' : '' ?><?= $ms['L'] ? ' · ' . $ms['L'] . ' leave' : '' ?></span></div>
        <div class="pp-stat <?= $ys['pct'] !== null && $ys['pct'] < 75 ? 'bad' : 'ok' ?>"><small>This year</small><strong><?= $ys['pct'] === null ? '—' : $ys['pct'] . '%' ?></strong>
          <span><?= $ys['days'] ?> working days · <?= $ys['A'] ?> absent</span></div>
      </div>
      <?php if ($ys['pct'] !== null && $ys['pct'] < 75): ?><p class="small bad-txt" style="margin-top:12px">Attendance is below 75%. Please make sure your child attends regularly.</p><?php endif; ?>
    </div>
    <?php if ($notes): ?>
      <div class="pp-card-h" style="border-top:1px solid var(--slate-100)"><h3>Teacher remarks</h3></div>
      <ul class="pp-list"><?php foreach ($notes as $d => $v): ?><li><div class="t"><?= date('d M', strtotime($d)) ?> · <?= html_escape($status[$v[0]]) ?></div><div class="b"><?= html_escape($v[1]) ?></div></li><?php endforeach; ?></ul>
    <?php endif; ?>
  </section>
</div>
