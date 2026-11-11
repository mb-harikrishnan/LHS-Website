<?php /* $notices [{title, body, d, cmName, dmName, kind}] */ ?>
<h1 class="pp-h1">Notices &amp; News</h1>
<section class="pp-card">
  <?php if ($notices): ?>
    <ul class="pp-list">
      <?php foreach ($notices as $n): ?>
        <li><div class="t"><?= html_escape($n->title) ?></div>
          <div class="m"><?= date('d M Y', strtotime($n->d)) ?> · <?= $n->kind === 'news' ? 'School news' : ($n->cmName ? 'For ' . html_escape(trim($n->cmName . ' ' . $n->dmName)) : 'All parents') ?></div>
          <div class="b"><?= html_escape($n->body) ?></div></li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?><div class="pp-empty">No notices right now.</div><?php endif; ?>
</section>
