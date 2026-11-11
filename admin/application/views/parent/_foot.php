<?php /* Parent portal layout (bottom): mobile navigation */
$short = array('home' => array('Home', 'parent'), 'attendance' => array('Attendance', 'parent/attendance'), 'results' => array('Results', 'parent/results'),
               'fees' => array('Fees', 'parent/fees'), 'notices' => array('Notices', 'parent/notices'), 'profile' => array('More', 'parent/profile'));
$icons = array(
    'home' => '<path d="m3 10 9-7 9 7v10a2 2 0 0 1-2 2h-4v-7H9v7H5a2 2 0 0 1-2-2Z"/>',
    'attendance' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
    'results' => '<path d="M12 20V10M18 20V4M6 20v-6"/>',
    'fees' => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>',
    'notices' => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
    'profile' => '<circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>',
);
?>
</main>
<nav class="pp-nav" aria-label="Sections">
  <?php foreach ($short as $k => $t): $on = $tab === $k || ($k === 'profile' && $tab === 'store'); ?>
    <a class="<?= $on ? 'on' : '' ?>" href="<?= site_url($t[1]) ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?= $icons[$k] ?></svg><?= $t[0] ?></a>
  <?php endforeach; ?>
</nav>
</body>
</html>
