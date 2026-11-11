<?php /* $child, $exams [{exam, term, is_grade, subjects[{name,mark,max,grade}], total, max, pct, grade}] */ ?>
<h1 class="pp-h1">Exam Results — <?= html_escape($child->smName) ?></h1>
<?php if (!$exams): ?>
  <div class="pp-card"><div class="pp-empty">No results have been published for <?= html_escape($child->class) ?> yet.</div></div>
<?php else: ?>
  <?php if (count($exams) > 1): ?>
    <section class="pp-card" style="margin-bottom:16px">
      <div class="pp-card-h"><h3>Progress</h3></div>
      <div class="pp-card-b">
        <?php foreach ($exams as $e): if ($e->pct === null) continue; ?>
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-top:8px"><span><?= html_escape($e->exam) ?></span><strong><?= $e->pct ?>% · <?= $e->grade ?></strong></div>
          <div class="pp-bar-pct <?= $e->pct < 40 ? 'low' : '' ?>"><span style="width:<?= min(100, $e->pct) ?>%"></span></div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
  <div class="pp-grid">
    <?php foreach (array_reverse($exams) as $e): ?>
      <section class="pp-card">
        <div class="pp-card-h">
          <div><h3><?= html_escape($e->exam) ?></h3><?php if ($e->term): ?><div class="small muted"><?= html_escape($e->term) ?></div><?php endif; ?></div>
          <?php if ($e->pct !== null): ?><span class="badge <?= $e->pct >= 60 ? 'badge-green' : ($e->pct >= 40 ? 'badge-amber' : 'badge-red') ?>"><?= $e->pct ?>% · <?= $e->grade ?></span><?php endif; ?>
        </div>
        <table class="table compact">
          <thead><tr><th>Subject</th><th class="num"><?= $e->is_grade ? 'Grade' : 'Marks' ?></th><?php if (!$e->is_grade): ?><th class="num">Max</th><th class="num">Grade</th><?php endif; ?></tr></thead>
          <tbody>
          <?php foreach ($e->subjects as $s): $absent = !$e->is_grade && $s->mark !== null && $s->mark !== '' && !is_numeric($s->mark); ?>
            <tr><td><?= html_escape($s->name) ?></td>
              <td class="num"><strong class="<?= $absent ? 'bad-txt' : '' ?>"><?= ($s->mark === null || $s->mark === '') ? '—' : html_escape($s->mark) ?></strong></td>
              <?php if (!$e->is_grade): ?><td class="num muted"><?= $s->max !== null ? (int)$s->max : '' ?></td><td class="num"><?= html_escape($s->grade) ?></td><?php endif; ?></tr>
          <?php endforeach; ?>
          <?php if (!$e->is_grade && $e->max > 0): ?>
            <tr class="row-total"><td>Total</td><td class="num"><?= rtrim(rtrim(number_format($e->total, 2, '.', ''), '0'), '.') ?></td><td class="num"><?= (int)$e->max ?></td><td class="num"><?= $e->grade ?></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </section>
    <?php endforeach; ?>
  </div>
  <p class="field-hint" style="margin-top:14px">Grades: A+ 90%+, A 80%+, B+ 70%+, B 60%+, C+ 50%+, C 40%+, D 30%+, E below 30%. For any query please contact the class teacher.</p>
<?php endif; ?>
