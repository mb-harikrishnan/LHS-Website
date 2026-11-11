<?php
/* Parent portal layout (top).  $parent, $children, $child, $tab, $title */
$icons = array(
    'home'       => '<path d="m3 10 9-7 9 7v10a2 2 0 0 1-2 2h-4v-7H9v7H5a2 2 0 0 1-2-2Z"/>',
    'attendance' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
    'results'    => '<path d="M12 20V10M18 20V4M6 20v-6"/>',
    'fees'       => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>',
    'store'      => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/>',
    'notices'    => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
    'profile'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/>',
);
$tabs = array('home' => array('Home', 'parent'), 'attendance' => array('Attendance', 'parent/attendance'), 'results' => array('Results', 'parent/results'),
              'fees' => array('Fees', 'parent/fees'), 'store' => array('Store', 'parent/store'), 'notices' => array('Notices', 'parent/notices'),
              'profile' => array('Profile', 'parent/profile'));
$svg = function ($k) use ($icons) { return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $icons[$k] . '</svg>'; };
$initials = function ($n) { $p = preg_split('/\s+/', trim($n)); return strtoupper(mb_substr($p[0], 0, 1) . (isset($p[1]) ? mb_substr($p[1], 0, 1) : '')); };
$here = $tab === 'home' ? 'parent' : 'parent/' . $tab;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title><?= html_escape($title) ?> — Little Hearts School Parent Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/fee.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/parent.css') ?>">
</head>
<body class="pp">
<header class="pp-top">
  <div class="pp-bar">
    <a class="pp-brand" href="<?= site_url('parent') ?>">
      <span class="mark"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></svg></span>
      <span><strong>Little Hearts School</strong><small>Parent Portal</small></span>
    </a>
    <div class="pp-user">
      <span class="nm">Hi, <?= html_escape($parent['name']) ?></span>
      <a class="btn btn-secondary btn-sm" href="<?= site_url('parent/logout') ?>">Logout</a>
    </div>
  </div>
  <nav class="pp-tabs" aria-label="Sections">
    <?php foreach ($tabs as $k => $t): ?><a class="<?= $tab === $k ? 'on' : '' ?>" href="<?= site_url($t[1]) ?>"><?= $svg($k) ?><?= $t[0] ?></a><?php endforeach; ?>
  </nav>
</header>
<main class="pp-main">
  <?php if (count($children) > 1 && $tab !== 'home' && $tab !== 'notices'): ?>
    <div class="pp-kids" role="tablist" aria-label="Choose child">
      <?php foreach ($children as $c): ?>
        <a class="pp-kid <?= $child && (int)$c->smId === (int)$child->smId ? 'on' : '' ?>" href="<?= site_url('parent/child/' . (int)$c->smId) ?>?to=<?= $here ?>">
          <span class="av"><?= html_escape($initials($c->smName)) ?></span>
          <span><?= html_escape($c->smName) ?><small><?= html_escape($c->class) ?></small></span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
