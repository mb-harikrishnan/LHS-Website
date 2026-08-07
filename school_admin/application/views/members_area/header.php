<?php
if (!isset($pageTitle)) $pageTitle = 'Dashboard';
if (!isset($breadcrumb)) $breadcrumb = 'Dashboard';
if (!isset($activePage)) $activePage = 'dashboard';
if (!isset($showGlobalSearch)) $showGlobalSearch = true;
if (!isset($pageScripts)) $pageScripts = [];
if (!isset($pageStylesheets)) $pageStylesheets = [];
if (!isset($pageHeadScripts)) $pageHeadScripts = [];

$userId    = $this->session->userdata('id');
$firstName = $this->session->userdata('c_username');
$initials  = 'AY';

$sql   = "SELECT amYear FROM academic_master WHERE amIsCurrent = 1 ";
$query = $this->db->query($sql);
$res   = $query->row()->amYear ?? '-';

$sql1   = "SELECT COUNT(*) as total FROM school_news WHERE c_status = 'Y' ";
$query1 = $this->db->query($sql1);
$count  = $query1->row()->total ?? 0;

$sql2   = "SELECT c_title,c_news,d_date FROM school_news WHERE c_status = 'Y' ";
$query2 = $this->db->query($sql2);
$result = $query2->result() ?? [];

/* ─────────────────────────────────────────────
   DYNAMIC SIDEBAR MENU — filtered by logged-in user's role
   ─────────────────────────────────────────────
   Tables involved:
     menus (menu_id, parent_menu_id, menu_name, display_name,
            menu_link, display_order, status)
     user_roles_menu_permissions (permission_id, role_id, menu_id,
            can_view, can_add, can_edit, can_delete)

   Logic:
     - Pull the current user's role_id from session.
     - Only include a menu row if the role has can_view = 1 for it.
     - ALSO include a parent row (even with no permission row of its
       own) if at least one of its children is permitted — otherwise
       a visible child's whole branch could disappear because the
       parent itself wasn't listed in the permissions table.
   ───────────────────────────────────────────── */
$roleId = (int) $this->session->userdata('user_role_id');

$sqlMenu = "SELECT DISTINCT m.menu_id, m.parent_menu_id, m.menu_name,
                   m.display_name, m.menu_link, m.display_order, m.status
            FROM menus m
            INNER JOIN user_roles_menu_permissions p
                    ON p.menu_id = m.menu_id
                   AND p.role_id = " . $roleId . "
                   AND p.can_view = 1
            WHERE m.status = 1

            UNION

            SELECT DISTINCT parent.menu_id, parent.parent_menu_id, parent.menu_name,
                   parent.display_name, parent.menu_link, parent.display_order, parent.status
            FROM menus parent
            INNER JOIN menus child
                    ON child.parent_menu_id = parent.menu_id
            INNER JOIN user_roles_menu_permissions p
                    ON p.menu_id = child.menu_id
                   AND p.role_id = " . $roleId . "
                   AND p.can_view = 1
            WHERE parent.status = 1

            ORDER BY display_order ASC";
$queryMenu = $this->db->query($sqlMenu);
$allMenus  = $queryMenu->result();

// Build a parent -> children tree (no grouping/zones, single flat list
// in display_order — add $m->menu_group back in if you need PUBLIC/TEACHERS
// sections later).
$menuTree  = [];
$menuIndex = []; // menu_id => reference, for attaching children

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

// Helper: build a full URL from a menu_link, stripping any leading slash
// so it plays nicely with base_url().
if (!function_exists('menuUrl')) {
    function menuUrl($link) {
        if (!$link) return '#';
        return base_url(ltrim($link, '/'));
    }
}

// Helper: is this menu item the active page?
if (!function_exists('isMenuActive')) {
    function isMenuActive($menuName, $activePage) {
        return strtolower($menuName) === strtolower($activePage);
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Little Heart School — <?php echo htmlspecialchars($pageTitle); ?></title>
  <meta name="description" content="Radha Madhav Growth premium business dashboard for team management, income tracking, and growth analytics.">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css">
  <?php if (!empty($pageStylesheets)) {
    foreach ($pageStylesheets as $stylesheet) { ?>
      <link rel="stylesheet" href="<?php echo htmlspecialchars($stylesheet); ?>">
  <?php }
  } ?>
  <?php if (!empty($pageHeadScripts)) {
    foreach ($pageHeadScripts as $script) { ?>
      <script src="<?php echo htmlspecialchars($script); ?>"></script>
  <?php }
  } ?>

  <style>
    .sub-nav {
      max-height: 0;
      overflow: hidden;
      transition: max-height .3s ease;
    }
    .sub-nav.open {
      max-height: 500px;
    }
    .nav-item.open .nav-arrow svg,
    .nav-item.active .nav-arrow svg {
      transform: rotate(90deg);
      transition: transform .2s ease;
    }
    .nav-item.active,
    .nav-item.open {
      background: rgba(212, 175, 55, 0.12);
      color: var(--gold, #d4af37);
    }
    .sub-item.active {
      color: var(--gold, #d4af37);
      font-weight: 600;
    }

    .tb-avatar {
      width: 50px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #007bff;
      color: #fff;
      font-weight: bold;
      font-size: 18px;
      border-radius: 4px;
    }
  </style>
</head>

<body>

  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>
  <div class="toast-container" id="toastContainer"></div>

  <div class="layout">

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-deco d1"></div>
      <div class="sidebar-deco d2"></div>
      <div class="sidebar-deco d3"></div>

      <div class="sb-header">
        <div class="sb-brand">
          <div class="sb-name">Little Heart School</div>
          <div class="sb-tag">Management Portal</div>
        </div>
      </div>

      <div class="sb-user">
        <div class="sb-avatar">
          <img src="http://localhost:8000/assets/images/main_image/school_logo.png"
            id="headerProfileImage"
            style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
        </div>
        <div class="sb-user-info">
          <div class="sb-user-name"><?php echo $firstName; ?></div>
        </div>
        <div class="sb-badge">Active</div>
      </div>

      <!-- Navigation (dynamic, filtered by role permissions) -->
      <nav class="sb-nav">

        <!-- Dashboard is always first / static -->
        <div class="nav-item<?php echo $activePage === 'dashboard' ? ' active' : ''; ?>"
             onclick="window.location='<?php echo base_url('index.php'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="3" width="7" height="7" />
              <rect x="14" y="3" width="7" height="7" />
              <rect x="14" y="14" width="7" height="7" />
              <rect x="3" y="14" width="7" height="7" />
            </svg>
          </div>
          <span class="nav-label">Dashboard</span>
        </div>

        <?php foreach ($menuTree as $parent):
            $hasChildren    = !empty($parent->children);
            $isActiveParent = isMenuActive($parent->menu_name, $activePage);
        ?>

          <?php if (!$hasChildren): ?>
            <!-- Leaf item: direct link -->
            <div class="nav-item<?php echo $isActiveParent ? ' active' : ''; ?>"
                 data-url="<?php echo menuUrl($parent->menu_link); ?>"
                 onclick="window.location.href=this.dataset.url">
              <span class="nav-label"><?php echo htmlspecialchars($parent->display_name); ?></span>
            </div>

          <?php else: ?>
            <!-- Parent with submenu -->
            <div class="nav-item<?php echo $isActiveParent ? ' active' : ''; ?>"
                 data-submenu="menu-<?php echo $parent->menu_id; ?>"
                 onclick="toggleNav(this)">
              <span class="nav-label"><?php echo htmlspecialchars($parent->display_name); ?></span>
              <span class="nav-arrow">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                  <polyline points="9 18 15 12 9 6" />
                </svg>
              </span>
            </div>
            <div class="sub-nav" id="subnav-menu-<?php echo $parent->menu_id; ?>">
              <?php foreach ($parent->children as $child): ?>
                <div class="sub-item"
                     data-url="<?php echo menuUrl($child->menu_link); ?>"
                     onclick="window.location.href=this.dataset.url">
                  <?php echo htmlspecialchars($child->display_name); ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        <?php endforeach; ?>

        <div class="sb-divider"></div>

        <!-- Change Password stays static (not a content menu item) -->
        <div class="nav-item<?php echo $activePage === 'change-password' ? ' active' : ''; ?>"
             onclick="window.location='<?php echo base_url('change_password'); ?>'">
          <span class="nav-label">Change Password</span>
        </div>

      </nav>

      <!-- Logout -->
      <div class="sb-logout" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
        <span>Sign Out</span>
      </div>
    </aside>

    <!-- ═══ MAIN AREA ═══ -->
    <main class="main" id="main">

      <!-- TOPBAR -->
      <header class="topbar">
        <div class="topbar-left">
          <button class="menu-btn" id="menuBtn" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6" />
              <line x1="3" y1="12" x2="21" y2="12" />
              <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
          </button>
          <div class="breadcrumb">
            <span>Portal / </span><strong><?php echo htmlspecialchars($breadcrumb); ?></strong>
          </div>
          <?php if ($showGlobalSearch): ?>
            <div class="search-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
              </svg>
              <input type="text" id="globalSearch" placeholder="Search members, reports…" oninput="globalSearchFilter(this.value)">
            </div>
          <?php endif; ?>
        </div>
        <div class="topbar-right">
          <button class="dark-toggle" id="darkToggle" onclick="toggleDarkMode()" title="Toggle dark mode">
            <div class="dark-toggle-thumb"></div>
          </button>

          <div style="position:relative">
            <div class="tb-icon-btn" id="notifBtn" onclick="toggleNotif()">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
              </svg>
              <div class="notif-dot" id="notifDot"></div>
            </div>
            <div class="notif-panel" id="notifPanel">
              <div class="notif-head">
                <h4>Notifications <span id="notifCount" style="color:var(--gold);font-weight:700;font-size:12px;margin-left:4px"><?php echo $count ?></span></h4>
                <span class="notif-mark" onclick="markAllRead()">Mark all read</span>
              </div>
              <div class="notif-list" id="notifList">
                <?php foreach ($result as $value) { ?>
                  <div class="notif-item unread">
                    <div class="notif-dot-item"></div>
                    <div class="notif-item-text">
                      <strong><?php echo $value->c_title; ?></strong>
                      <span><?php echo $value->c_news; ?> </span>
                      <span><?php echo $value->d_date; ?> </span>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>

          <div class="tb-user">
            <div class="tb-avatar"><?php echo $initials; ?></div>
            <span class="tb-uname"><?php echo $res; ?></span>
          </div>
        </div>
      </header>

      <!-- PAGE CONTENT (opened here, closed in footer.php) -->
      <div class="page-content">

<script>
  function toggleNav(el) {
    const submenuId = 'subnav-' + el.getAttribute('data-submenu');
    const submenu = document.getElementById(submenuId);
    const isOpen = el.classList.contains('open');

    document.querySelectorAll('.nav-item.open').forEach(function (item) {
      item.classList.remove('open');
    });
    document.querySelectorAll('.sub-nav.open').forEach(function (nav) {
      nav.classList.remove('open');
    });

    if (!isOpen) {
      el.classList.add('open', 'active');
      if (submenu) submenu.classList.add('open');
    } else {
      el.classList.remove('active');
    }
  }

  function highlightActiveMenu() {
    const currentPath = window.location.pathname.replace(/\/+$/, '');

    document.querySelectorAll('.sub-item').forEach(function (item) {
      const url = item.getAttribute('data-url');
      if (!url) return;

      let urlPath;
      try {
        urlPath = new URL(url, window.location.origin).pathname.replace(/\/+$/, '');
      } catch (e) {
        urlPath = url.replace(/\/+$/, '');
      }
      if (!urlPath) return;

      const isMatch =
        currentPath === urlPath ||
        currentPath.endsWith(urlPath) ||
        currentPath.startsWith(urlPath + '/');

      if (isMatch) {
        item.classList.add('active');

        const subnav = item.closest('.sub-nav');
        if (subnav) {
          subnav.classList.add('open');
          const submenuName = subnav.id.replace('subnav-', '');
          const parentNavItem = document.querySelector('.nav-item[data-submenu="' + submenuName + '"]');
          if (parentNavItem) {
            parentNavItem.classList.add('active', 'open');
          }
        }
      }
    });

    document.querySelectorAll('.nav-item[data-url]').forEach(function (item) {
      const url = item.getAttribute('data-url');
      if (!url) return;

      let urlPath;
      try {
        urlPath = new URL(url, window.location.origin).pathname.replace(/\/+$/, '');
      } catch (e) {
        urlPath = url.replace(/\/+$/, '');
      }
      if (!urlPath) return;

      const isMatch =
        currentPath === urlPath ||
        currentPath.endsWith(urlPath) ||
        currentPath.startsWith(urlPath + '/');

      if (isMatch) {
        item.classList.add('active');
      }
    });
  }

  document.addEventListener('DOMContentLoaded', highlightActiveMenu);
</script>