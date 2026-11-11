<?php /* $title, $groups = [section => [[route, name, description], ...]] */ ?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title"><?= html_escape($title) ?></h1>
      <p class="page-sub">All reports use the academic year selected at the top. Each report can be printed or downloaded as Excel (CSV).</p>
    </div>
  </div>
  <?php foreach ($groups as $section => $items): ?>
    <div class="hub-title"><?= html_escape($section) ?></div>
    <div class="hub">
      <?php foreach ($items as $it): ?>
        <a href="<?= site_url($it[0]) ?>"><strong><?= html_escape($it[1]) ?></strong><span><?= html_escape($it[2]) ?></span></a>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</main>
