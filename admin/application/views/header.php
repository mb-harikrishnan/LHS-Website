<?php
/* ─────────────────────────────────────────────
   PAGE DEFAULTS
   ───────────────────────────────────────────── */
if (!isset($pageTitle))          $pageTitle          = 'Dashboard';
if (!isset($breadcrumb))         $breadcrumb         = 'Dashboard';
if (!isset($activePage))         $activePage         = 'dashboard';
if (!isset($showGlobalSearch))   $showGlobalSearch   = true;
if (!isset($pageScripts))        $pageScripts        = [];
if (!isset($pageStylesheets))    $pageStylesheets    = [];
if (!isset($pageHeadScripts))    $pageHeadScripts    = [];

// Brand text (change here only)
$schoolNameMain = 'Little Hearts School';
$schoolNameSub  = 'School';

/* ─────────────────────────────────────────────
   SESSION / USER
   ───────────────────────────────────────────── */
$userId    = $this->session->userdata('id');
$firstName = $this->session->userdata('c_username') ?: 'Admin';
$roleId    = (int) $this->session->userdata('user_role_id');
$roleLabel = ($roleId === 1) ? 'Administrator' : $this->session->userdata('user_type');

// Initials from username (max 2 letters)
$initials = '';
foreach (preg_split('/\s+/', trim($firstName)) as $part) {
    if ($part !== '') $initials .= strtoupper(mb_substr($part, 0, 1));
    if (mb_strlen($initials) >= 2) break;
}
if ($initials === '') $initials = 'U';

/* ─────────────────────────────────────────────
   ACADEMIC YEAR + NEWS (notifications)
   ───────────────────────────────────────────── */
// $query = $this->db->query("SELECT amYear FROM academic_master WHERE amIsCurrent = 1");
// $row   = $query->row();
// $res   = $row->amYear ?? '-';

/* ACADEMIC YEARS + SELECTED YEAR */
$years = $this->db
    ->query("SELECT amId, amYear, amIsCurrent FROM academic_master WHERE amActive = 1 ORDER BY amYear DESC")
    ->result();

// Get current academic year (amIsCurrent = 1)
$currentYear = $this->db
    ->where('amIsCurrent', 1)
    ->get('academic_master')
    ->row();

$currentAmId = $currentYear ? (int)$currentYear->amId : 0;

// Get session selected academic year
$selectedAmId = (int)$this->session->userdata('selected_am_id');

// Check whether selected ID exists
$validIds = array_map(function ($y) {
    return (int)$y->amId;
}, $years);

// If no valid session, select current academic year
if (!$selectedAmId || !in_array($selectedAmId, $validIds, true)) {
    $selectedAmId = $currentAmId;

    $this->session->set_userdata('selected_am_id', $selectedAmId);
}

$query1 = $this->db->query("SELECT COUNT(*) AS total FROM school_news WHERE c_status = 'Y'");
$count  = $query1->row()->total ?? 0;

$query2 = $this->db->query("SELECT c_title, c_news, d_date FROM school_news WHERE c_status = 'Y' ORDER BY d_date DESC");
$result = $query2->result() ?? [];

/* ─────────────────────────────────────────────
   DYNAMIC SIDEBAR MENU (filtered by role)
   ───────────────────────────────────────────── */
if ($roleId === 1) {
    // Admin: all active menus
    $sqlMenu = "
        SELECT DISTINCT
            m.menu_id, m.parent_menu_id, m.menu_name, m.display_name,
            m.menu_link, m.display_order, m.status, m.`group` AS group_id
        FROM menus m
        WHERE m.status = 1
        ORDER BY m.display_order ASC
    ";
    $queryMenu = $this->db->query($sqlMenu);
} else {
    // Other roles: permitted menus + their parents
    $sqlMenu = "
        SELECT DISTINCT
            m.menu_id, m.parent_menu_id, m.menu_name, m.display_name,
            m.menu_link, m.display_order, m.status, m.`group` AS group_id
        FROM menus m
        INNER JOIN user_roles_menu_permissions p
            ON p.menu_id = m.menu_id AND p.role_id = ? AND p.can_view = 1
        WHERE m.status = 1

        UNION

        SELECT DISTINCT
            parent.menu_id, parent.parent_menu_id, parent.menu_name, parent.display_name,
            parent.menu_link, parent.display_order, parent.status, parent.`group` AS group_id
        FROM menus parent
        INNER JOIN menus child ON child.parent_menu_id = parent.menu_id
        INNER JOIN user_roles_menu_permissions p
            ON p.menu_id = child.menu_id AND p.role_id = ? AND p.can_view = 1
        WHERE parent.status = 1

        ORDER BY display_order ASC
    ";
    $queryMenu = $this->db->query($sqlMenu, [$roleId, $roleId]);
}
$allMenus = $queryMenu->result();

// Build parent -> children tree
$menuTree  = [];
$menuIndex = [];
foreach ($allMenus as $m) {
    if ($m->parent_menu_id === null) {
        $m->children = [];
        $menuTree[] = $m;
        $menuIndex[$m->menu_id] = $m;
    }
}
foreach ($allMenus as $m) {
    if ($m->parent_menu_id !== null && isset($menuIndex[$m->parent_menu_id])) {
        $menuIndex[$m->parent_menu_id]->children[] = $m;
    }
}

// Sections by group: 1 = Main Menu, 2 = Teachers
$menuSections = [
    1 => ['label' => 'Main Menu', 'items' => []],
    2 => ['label' => 'Teachers',  'items' => []],
];
foreach ($menuTree as $parent) {
    $gid = (int) ($parent->group_id ?? 1);
    if (!isset($menuSections[$gid])) {
        $menuSections[$gid] = ['label' => 'Other', 'items' => []];
    }
    $menuSections[$gid]['items'][] = $parent;
}

/* ─────────────────────────────────────────────
   HELPERS
   ───────────────────────────────────────────── */
if (!function_exists('menuUrl')) {
    function menuUrl($link) {
        if (!$link) return '#';
        return base_url(ltrim($link, '/'));
    }
}
if (!function_exists('isMenuActive')) {
    function isMenuActive($menuName, $activePage) {
        return strtolower((string) $menuName) === strtolower((string) $activePage);
    }
}
// Icon (inner SVG markup) picked by menu name keyword, with a fallback
if (!function_exists('menuIcon')) {
    function menuIcon($name) {
        $n = strtolower((string) $name);
        $icons = [
            'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
            'student'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/><path d="M15 3.13a4 4 0 0 1 0 7.75"/>',
            'news'      => '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V7"/><path d="M12 7h6M12 11h6M12 15h6"/>',
            'employee'  => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="M2 13h20"/>',
            'exam'      => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6"/>',
            'disclosure'=> '<path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.7-.9L9.2 3.9A2 2 0 0 0 7.5 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/>',
            'gallery'   => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/>',
        ];
        foreach ($icons as $key => $svg) {
            if (strpos($n, $key) !== false) return $svg;
        }
        return '<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/>'; // fallback
    }
}
// Render the whole nav (used for desktop sidebar AND mobile drawer)
if (!function_exists('renderSidebarNav')) {
    function renderSidebarNav($sections, $activePage) {
        ob_start();
        foreach ($sections as $section):
            if (empty($section['items'])) continue; ?>
            <div class="side-section-label"><?php echo htmlspecialchars($section['label']); ?></div>

            <?php foreach ($section['items'] as $parent):
                $hasChildren = !empty($parent->children);
                $childActive = false;
                if ($hasChildren) {
                    foreach ($parent->children as $c) {
                        if (isMenuActive($c->menu_name, $activePage)) { $childActive = true; break; }
                    }
                }
                $isActive = isMenuActive($parent->menu_name, $activePage) || $childActive;
                $icon = menuIcon($parent->menu_name . ' ' . $parent->display_name);
            ?>

                <?php if (!$hasChildren): ?>
                    <a href="<?php echo menuUrl($parent->menu_link); ?>" class="side-link<?php echo $isActive ? ' active' : ''; ?>">
                        <span class="side-ic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $icon; ?></svg>
                        </span>
                        <span class="side-txt"><?php echo htmlspecialchars($parent->display_name); ?></span>
                    </a>

                <?php else: ?>
                    <div class="side-group<?php echo $childActive ? ' open' : ''; ?>">
                        <button class="side-link<?php echo $isActive ? ' active' : ''; ?>" type="button" data-submenu>
                            <span class="side-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $icon; ?></svg>
                            </span>
                            <span class="side-txt"><?php echo htmlspecialchars($parent->display_name); ?></span>
                            <span class="side-chev">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </span>
                        </button>
                        <div class="side-subwrap">
                            <?php foreach ($parent->children as $child): ?>
                                <a href="<?php echo menuUrl($child->menu_link); ?>"
                                   class="side-sub<?php echo isMenuActive($child->menu_name, $activePage) ? ' active' : ''; ?>">
                                    <span class="side-sub-dot"></span><?php echo htmlspecialchars($child->display_name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach;
        endforeach;
        return ob_get_clean();
    }
}

$navHtml = renderSidebarNav($menuSections, $activePage);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pageTitle); ?> — <?php echo htmlspecialchars($schoolNameMain . ' ' . $schoolNameSub); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
  <?php foreach ($pageStylesheets as $stylesheet): ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($stylesheet); ?>">
  <?php endforeach; ?>
  <?php foreach ($pageHeadScripts as $script): ?>
    <script src="<?php echo htmlspecialchars($script); ?>"></script>
  <?php endforeach; ?>

  <style>
    /* Extras for dynamic menu + notifications (not in the original CSS) */
    .side-section-label {
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .06em; opacity: .6; padding: 16px 16px 6px;
    }
    .side-section-label:first-child { padding-top: 6px; }
    .side-sub.active { font-weight: 600; }
    .notif-wrap { position: relative; }
    .notif-badge {
      position: absolute; top: 2px; right: 2px; min-width: 16px; height: 16px;
      padding: 0 4px; border-radius: 8px; background: #e53935; color: #fff;
      font-size: 10px; line-height: 16px; text-align: center; font-weight: 600;
    }
    .notif-panel {
      display: none; position: absolute; right: 0; top: calc(100% + 8px);
      width: 320px; max-height: 380px; overflow-y: auto; z-index: 1000;
      background: #fff; color: #222; border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,.15);
    }
    .notif-panel.open { display: block; }
    .notif-panel h4 { margin: 0; padding: 14px 16px; font-size: 14px; border-bottom: 1px solid #eee; }
    .notif-item { padding: 12px 16px; border-bottom: 1px solid #f2f2f2; font-size: 13px; }
    .notif-item strong { display: block; margin-bottom: 2px; }
    .notif-item small { display: block; opacity: .6; margin-top: 4px; }
    .notif-empty { padding: 20px 16px; font-size: 13px; opacity: .6; text-align: center; }
    .year-chip {
      padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600;
      background: rgba(0,0,0,.06);
    }
  </style>
</head>
<body data-page="<?php echo htmlspecialchars($activePage); ?>">
  <div id="app">

    <!-- Desktop Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="side-brand">
        <span class="brand-mark">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
            <path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/>
          </svg>
        </span>
        <span class="side-brand-txt">
          <strong><?php echo htmlspecialchars($schoolNameMain); ?></strong>
          <small><?php echo htmlspecialchars($schoolNameSub); ?></small>
        </span>
      </div>

      <nav class="side-nav">
        <?php echo $navHtml; ?>
      </nav>

      <div class="side-user">
        <span class="avatar"><?php echo htmlspecialchars($initials); ?></span>
        <span class="side-user-txt">
          <strong><?php echo htmlspecialchars($firstName); ?></strong>
          <small><?php echo htmlspecialchars($roleLabel); ?></small>
        </span>
        <a href="<?php echo base_url('logout'); ?>" class="side-logout" title="Logout">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>
          </svg>
        </a>
      </div>
    </aside>

    <!-- Mobile Drawer Backdrop -->
    <div class="drawer-backdrop" id="drawer-backdrop"></div>

    <!-- Mobile Drawer (same dynamic menu) -->
    <aside class="sidebar drawer" id="drawer">
      <div class="side-brand">
        <span class="brand-mark">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
            <path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/>
          </svg>
        </span>
        <span class="side-brand-txt">
          <strong><?php echo htmlspecialchars($schoolNameMain); ?></strong>
          <small><?php echo htmlspecialchars($schoolNameSub); ?></small>
        </span>
        <button class="icon-btn side-close" id="drawer-close" aria-label="Close menu">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>

      <nav class="side-nav">
        <?php echo $navHtml; ?>
      </nav>

      <div class="side-user">
        <span class="avatar"><?php echo htmlspecialchars($initials); ?></span>
        <span class="side-user-txt">
          <strong><?php echo htmlspecialchars($firstName); ?></strong>
          <small><?php echo htmlspecialchars($roleLabel); ?></small>
        </span>
        <a href="<?php echo base_url('logout'); ?>" class="side-logout" title="Logout">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
        </a>
      </div>
    </aside>

    <!-- Main Content Column (closed in footer.php) -->
    <div class="main">

      <!-- Topbar Header -->
      <header class="topbar">
        <button class="icon-btn menu-toggle" id="menu-toggle" aria-label="Open menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <?php if ($showGlobalSearch): ?>
          <form class="topbar-search" onsubmit="event.preventDefault();">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="search" id="globalSearch" placeholder="Search students, news, employees…" aria-label="Search"
                   oninput="if (typeof globalSearchFilter === 'function') globalSearchFilter(this.value)">
          </form>
        <?php endif; ?>

        <div class="topbar-right">
          <!-- <span class="year-chip" title="Current academic year"><?php echo htmlspecialchars($res); ?></span> -->
          <!-- <select id="academic_year" class="year-select">
            <?php foreach ($years as $y): ?>
              <option value="<?php echo (int)$y->amId; ?>"
                <?php echo ((int)$y->amId === $selectedAmId) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($y->amYear); ?>
              </option>
            <?php endforeach; ?>
          </select> -->

          <div class="ydrop-wrap">
            <select id="academic_year" class="year-select">
              <?php foreach ($years as $y): ?>
                <option value="<?php echo (int)$y->amId; ?>"
                  <?php echo ((int)$y->amId === $selectedAmId) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($y->amYear); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="notif-wrap">
            <button class="icon-btn" id="notif-btn" type="button" aria-label="Notifications" title="Notifications">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
              <?php if ((int) $count > 0): ?>
                <span class="notif-badge"><?php echo (int) $count; ?></span>
              <?php endif; ?>
            </button>
            <div class="notif-panel" id="notif-panel">
              <h4>Notifications (<?php echo (int) $count; ?>)</h4>
              <?php if (empty($result)): ?>
                <div class="notif-empty">No notifications</div>
              <?php else: foreach ($result as $value): ?>
                <div class="notif-item">
                  <strong><?php echo htmlspecialchars($value->c_title); ?></strong>
                  <span><?php echo htmlspecialchars($value->c_news); ?></span>
                  <small><?php echo htmlspecialchars($value->d_date); ?></small>
                </div>
              <?php endforeach; endif; ?>
            </div>
          </div>

          <div class="profile-menu">
            <button class="profile-btn" id="profile-btn" type="button">
              <span class="avatar avatar-sm"><?php echo htmlspecialchars($initials); ?></span>
              <span class="profile-meta">
                <strong><?php echo htmlspecialchars($firstName); ?></strong>
                <small><?php echo htmlspecialchars($roleLabel); ?></small>
              </span>
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="profile-dropdown" id="profile-dropdown">
              <!-- <a href="<?php echo base_url('employee_list'); ?>">My Profile</a> -->
              <a href="<?php echo base_url('change_password'); ?>">Change Password</a>
              <hr>
              <a href="<?php echo base_url('logout'); ?>">Logout</a>
            </div>
          </div>
        </div>
      </header>

      <!-- PAGE CONTENT (opened here, closed in footer.php) -->
      <div class="page-content">

<script>
  // Notification panel toggle (submenu, drawer & profile dropdown stay with your existing assets/js)
  (function () {
    var btn = document.getElementById('notif-btn');
    var panel = document.getElementById('notif-panel');
    if (!btn || !panel) return;
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      panel.classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
      if (!panel.contains(e.target)) panel.classList.remove('open');
    });
  })();
</script>


<script>
(function () {
  var wrap   = document.querySelector('.ydrop-wrap');
  var select = document.getElementById('academic_year');
  if (!wrap || !select) return;

  /* ---------- build custom dropdown from the <select> ---------- */
  var btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'ydrop-btn';
  btn.innerHTML = '<span class="ydrop-label"></span>' +
    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
    'stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';
  btn.querySelector('.ydrop-label').textContent = select.options[select.selectedIndex].text;

  var ul = document.createElement('ul');
  ul.className = 'ydrop-menu';
  Array.prototype.forEach.call(select.options, function (opt, i) {
    var li = document.createElement('li');
    li.textContent = opt.text;
    li.dataset.index = i;
    if (i === select.selectedIndex) li.classList.add('active');
    ul.appendChild(li);
  });

  wrap.appendChild(btn);
  wrap.appendChild(ul);
  wrap.classList.add('enhanced');

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    wrap.classList.toggle('open');
  });

  ul.addEventListener('click', function (e) {
    var li = e.target.closest('li');
    if (!li) return;
    select.selectedIndex = li.dataset.index;
    ul.querySelectorAll('li').forEach(function (x) { x.classList.remove('active'); });
    li.classList.add('active');
    btn.querySelector('.ydrop-label').textContent = li.textContent;
    wrap.classList.remove('open');
    select.dispatchEvent(new Event('change', { bubbles: true }));
  });

  document.addEventListener('click', function () { wrap.classList.remove('open'); });

  /* ---------- switch academic year (fixed id) ---------- */
  select.addEventListener('change', function () {
    var body = new URLSearchParams();
    body.append('am_id', this.value);
    // If CSRF is enabled in CI:
    // body.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');

    fetch('<?php echo base_url("switch_year"); ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString()
    })
    .then(function (r) { return r.json(); })
    .then(function (d) { if (d.status) location.reload(); else alert(d.msg || 'Failed'); })
    .catch(function () { alert('Something went wrong'); });
  });
})();
</script>


<style>
 .ydrop-wrap { position: relative; display: inline-block; }

/* native select stays in the DOM (value + change event) but is hidden once JS runs */
.ydrop-wrap.enhanced .year-select { display: none; }

/* fallback styling if JS fails */
.year-select {
  appearance: none; -webkit-appearance: none;
  padding: 8px 38px 8px 18px;
  font-size: 14px; font-weight: 600; color: #1e1b4b;
  background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234338ca' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 14px center / 14px;
  border: 1.5px solid #c7c9f5; border-radius: 999px; cursor: pointer; outline: none;
}

/* custom dropdown */
.ydrop-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 16px;
  font-size: 14px; font-weight: 600; color: #1e1b4b;
  background: #fff; border: 1.5px solid #c7c9f5; border-radius: 999px;
  cursor: pointer; transition: all .2s;
}
.ydrop-btn:hover, .ydrop-wrap.open .ydrop-btn {
  border-color: #4338ca; box-shadow: 0 0 0 3px rgba(99,102,241,.2);
}
.ydrop-btn svg { color: #4338ca; transition: transform .2s; }
.ydrop-wrap.open .ydrop-btn svg { transform: rotate(180deg); }

.ydrop-menu {
  position: absolute; top: calc(100% + 8px); right: 0; min-width: 100%;
  margin: 0; padding: 6px; list-style: none;
  background: #fff; border-radius: 14px;
  box-shadow: 0 12px 30px rgba(30,27,75,.25);
  opacity: 0; visibility: hidden; transform: translateY(-6px);
  transition: all .18s ease; z-index: 1000;

  /* scroll */
  max-height: 100px;          /* about 4 items; increase for more */
  overflow-y: auto;
  overscroll-behavior: contain;   /* page doesn't scroll when the list reaches its end */
  scrollbar-width: thin;                      /* Firefox */
  scrollbar-color: #a5a8f0 transparent;       /* Firefox */
}

/* Chrome / Edge / Safari scrollbar */
.ydrop-menu::-webkit-scrollbar { width: 6px; }
.ydrop-menu::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
.ydrop-menu::-webkit-scrollbar-thumb { background: #c7c9f5; border-radius: 999px; }
.ydrop-menu::-webkit-scrollbar-thumb:hover { background: #4338ca; }
.ydrop-wrap.open .ydrop-menu { opacity: 1; visibility: visible; transform: none; }

.ydrop-menu li {
  padding: 9px 14px; font-size: 14px; font-weight: 500; color: #312e81;
  border-radius: 9px; cursor: pointer; white-space: nowrap;
}
.ydrop-menu li:hover { background: #eef0ff; }
.ydrop-menu li.active { background: #4338ca; color: #fff; }
</style>