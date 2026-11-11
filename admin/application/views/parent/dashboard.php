<?php /* $cards [{child, att{year,month}, fee, store, result}], $notices, $parent */
$pct = function ($a) { return $a['pct'] === null ? '—' : $a['pct'] . '%'; };
?>
<h1 class="pp-h1">Welcome, <?= html_escape($parent['name']) ?></h1>

<div class="pp-grid">
  <?php foreach ($cards as $c): $ch = $c['child']; $fee = $c['fee']; $ay = $c['att']['year']; $am = $c['att']['month'];
    $sel = site_url('parent/child/' . (int)$ch->smId); ?>
    <section class="pp-card">
      <div class="pp-card-h">
        <div><h3><?= html_escape($ch->smName) ?></h3>
          <div class="small muted"><?= html_escape($ch->class) ?> · Adm <?= html_escape($ch->smAdmissionNo) ?><?= $ch->is_current ? '' : ' · ' . html_escape($ch->amYear) ?></div></div>
        <span class="badge badge-info"><?= html_escape($ch->amYear) ?></span>
      </div>
      <div class="pp-card-b">
        <div class="pp-stats">
          <a class="pp-stat <?= $ay['pct'] === null ? '' : ($ay['pct'] < 75 ? 'bad' : 'ok') ?>" href="<?= $sel ?>?to=parent/attendance">
            <small>Attendance (year)</small><strong><?= $pct($ay) ?></strong>
            <span>This month <?= $pct($am) ?><?= $am['A'] ? ' · ' . $am['A'] . ' absent' : '' ?></span>
            <?php if ($ay['pct'] !== null): ?><div class="pp-bar-pct <?= $ay['pct'] < 75 ? 'low' : '' ?>"><span style="width:<?= min(100, $ay['pct']) ?>%"></span></div><?php endif; ?>
          </a>
          <a class="pp-stat <?= ($fee['due_now'] + $fee['old']) > 0 ? 'warn' : 'ok' ?>" href="<?= $sel ?>?to=parent/fees">
            <small>Fee due now</small><strong>₹<?= fee_money($fee['due_now'] + $fee['old']) ?></strong>
            <span><?= $fee['next'] ? 'Next ₹' . fee_money($fee['next']->balance) . ' on ' . date('d M', strtotime($fee['next']->sfdDueDate)) : ($fee['balance'] > 0 ? 'Balance ₹' . fee_money($fee['balance']) : 'All clear') ?></span>
          </a>
          <a class="pp-stat" href="<?= $sel ?>?to=parent/results">
            <small>Latest result</small>
            <?php if ($c['result']): $r = $c['result']; ?>
              <strong><?= $r->pct !== null ? $r->pct . '% · ' . $r->grade : 'Grades' ?></strong><span><?= html_escape($r->exam) ?></span>
            <?php else: ?><strong>—</strong><span>No results published yet</span><?php endif; ?>
          </a>
          <a class="pp-stat <?= $c['store'] > 0 ? 'warn' : '' ?>" href="<?= $sel ?>?to=parent/store">
            <small>Store balance</small><strong>₹<?= fee_money($c['store']) ?></strong><span><?= $c['store'] > 0 ? 'Books / uniform due' : 'Nothing due' ?></span>
          </a>
        </div>
        <?php if ($fee['fine'] > 0): ?><p class="small bad-txt" style="margin:10px 0 0">Late fine so far: ₹<?= fee_money($fee['fine']) ?> — pay soon to avoid more fine.</p><?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="pp-card">
    <div class="pp-card-h"><h3>Latest notices</h3><a class="small" href="<?= site_url('parent/notices') ?>">View all</a></div>
    <?php if ($notices): ?>
      <ul class="pp-list">
        <?php foreach ($notices as $n): ?>
          <li><div class="t"><?= html_escape($n->title) ?></div>
            <div class="m"><?= date('d M Y', strtotime($n->d)) ?><?= $n->cmName ? ' · ' . html_escape(trim($n->cmName . ' ' . $n->dmName)) : '' ?></div>
            <div class="b"><?= html_escape(mb_strimwidth($n->body, 0, 160, '…')) ?></div></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?><div class="pp-empty">No notices right now.</div><?php endif; ?>
  </section>
</div>
