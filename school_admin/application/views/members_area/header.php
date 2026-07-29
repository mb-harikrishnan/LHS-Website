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
$initials =$this->session->userdata('c_username');


$sql ="SELECT amYear FROM academic_master WHERE amIsCurrent = 1 ";
$query = $this->db->query($sql);
$res = $query->row()->amYear ?? '-';

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
    /* Ensure sub-menu expands/collapses smoothly and parent highlights when open/active */
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
    width: 100px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #007bff;
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    border-radius: 4px; /* Remove this or set to 0 for a perfect square */
}
  </style>
</head>

<body>

  <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  <!-- Toast container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- ═══ LAYOUT ═══ -->
  <div class="layout">

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="sidebar" id="sidebar">
      <!-- Decorative elements -->
      <div class="sidebar-deco d1"></div>
      <div class="sidebar-deco d2"></div>
      <div class="sidebar-deco d3"></div>

      <!-- Header -->
      <div class="sb-header">
        <div class="sb-brand">
          <div class="sb-name">Little Heart School</div>
          <div class="sb-tag">Management Portal</div>
        </div>
      </div>

      <!-- User Card -->
      <div class="sb-user">
        <div class="sb-avatar">
          <img src="http://localhost:8000/assets/images/main_image/school_logo.png"
            id="headerProfileImage"
            style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
        </div>
        <div class="sb-user-info">
          <div class="sb-user-name"><?php echo $firstName; ?></div>
          <div class="sb-user-id"><?php echo $this->session->userdata('c_username'); ?></div>
        </div>
        <div class="sb-badge">Active</div>
      </div>

      <!-- Navigation -->
      <nav class="sb-nav">
        <div class="nav-section-label">PUBLIC ZONE</div>

        <!-- Dashboard -->
        <div class="nav-item<?php echo $activePage === 'dashboard' ? ' active' : ''; ?>" onclick="window.location='index.php'">
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

        <!-- Mandatory Disclosure -->
        <div class="nav-item" data-submenu="disclosure" onclick="toggleNav(this)">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="16" y1="13" x2="8" y2="13" />
              <line x1="16" y1="17" x2="8" y2="17" />
              <polyline points="10 9 9 9 8 9" />
            </svg>
          </div>
          <span class="nav-label">Mandatory Disclosure</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-disclosure">
          <div class="sub-item" data-url="general_information" onclick="window.location.href='general_information'">Document And Information</div>
          <div class="sub-item" data-url="Result_and_Staff" onclick="window.location.href='Result_and_Staff'">Result & Staff</div>
          <div class="sub-item" data-url="infrastructure" onclick="window.location.href='infrastructure'">Infrastructure video</div>
        </div>

        <!-- School News -->
        <div class="nav-item<?php echo $activePage === 'school-news' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('school_news'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="12" y1="1" x2="12" y2="23" />
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <span class="nav-label">School News</span>
        </div>

        <!-- Gallery -->
        <div class="nav-item<?php echo $activePage === 'school-gallery' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('gallery'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
          </div>
          <span class="nav-label">Gallery</span>
        </div>

        <!-- Co Curricular Activities -->
        <div class="nav-item" data-submenu="cocurricular" onclick="toggleNav(this)">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <circle cx="12" cy="12" r="10" />
              <polygon points="10 8 16 12 10 16 10 8" />
            </svg>
          </div>
          <span class="nav-label">Co Curricular Activities</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-cocurricular">
          <div class="sub-item" data-url="co_curricular_list" onclick="window.location.href='co_curricular_list'">Co Curricular Activities Main List</div>
          <div class="sub-item" data-url="activities_list" onclick="window.location.href='activities_list'">All Images</div>
        </div>

        <!-- Vacancy -->
        <div class="nav-item" data-submenu="vacancy" onclick="toggleNav(this)">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <line x1="19" y1="8" x2="19" y2="14" />
              <line x1="22" y1="11" x2="16" y2="11" />
            </svg>
          </div>
          <span class="nav-label">Vacancy</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-vacancy">
          <div class="sub-item" data-url="vaccancy_list" onclick="window.location.href='vaccancy_list'">Add Vacancy</div>
          <div class="sub-item" data-url="apply_members" onclick="window.location.href='apply_members'">Applications</div>
        </div>

        <!-- Add Downloads -->
        <div class="nav-item<?php echo $activePage === 'school-questionpaper_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('questionpaper_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
              <polyline points="7 10 12 15 17 10" />
              <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
          </div>
          <span class="nav-label">Add Downloads</span>
        </div>

        <!-- Add Homepage Slider -->
        <div class="nav-item<?php echo $activePage === 'school-slider_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('slider_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="3" width="18" height="14" rx="2" ry="2" />
              <line x1="8" y1="21" x2="16" y2="21" />
              <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
          </div>
          <span class="nav-label">Add Homepage Slider</span>
        </div>


        <div class="nav-item<?php echo $activePage === 'acdemics_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('accademic_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="3" width="18" height="14" rx="2" ry="2" />
              <line x1="8" y1="21" x2="16" y2="21" />
              <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
          </div>
          <span class="nav-label">Accademic list</span>
        </div>


        <div class="nav-item<?php echo $activePage === 'term_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('term_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="3" width="18" height="14" rx="2" ry="2" />
              <line x1="8" y1="21" x2="16" y2="21" />
              <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
          </div>
          <span class="nav-label">Term list</span>
        </div>


        <div class="nav-item<?php echo $activePage === 'user_role_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('user_role_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="3" width="18" height="14" rx="2" ry="2" />
              <line x1="8" y1="21" x2="16" y2="21" />
              <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
          </div>
          <span class="nav-label">User Role list</span>
        </div>

        <div class="sb-divider"></div>
        <div class="nav-section-label">TEACHERS ZONE</div>

        <!-- My Dashboard -->
        <div class="nav-item<?php echo $activePage === 'my-dashboard' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('#'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <span class="nav-label">My Dashboard</span>
        </div>

        <!-- Employees -->
        <div class="nav-item<?php echo $activePage === 'employees' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('employee_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <span class="nav-label">Employees</span>
        </div>

        <!-- Students -->
        <div class="nav-item" data-submenu="students" onclick="toggleNav(this)">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <span class="nav-label">Students</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-students">
          <div class="sub-item" data-url="<?php echo base_url('class_list'); ?>" onclick="window.location.href='<?php echo base_url('class_list'); ?>'">Class</div>
          <div class="sub-item" data-url="<?php echo base_url('divition_list'); ?>" onclick="window.location.href='<?php echo base_url('divition_list'); ?>'">Division</div>
          <div class="sub-item" data-url="class_divition_list" onclick="window.location.href='class_divition_list'">Class Division Allocation</div>
          <div class="sub-item" data-url="<?php echo base_url('students_list'); ?>" onclick="window.location.href='<?= base_url('students_list'); ?>'">Students</div>
        </div>

        <!-- Exams -->
        <div class="nav-item" data-submenu="exams" onclick="toggleNav(this)">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M9 11l3 3L22 4" />
              <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
            </svg>
          </div>
          <span class="nav-label">Exams</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-exams">
          <div class="sub-item" data-url="exam_list" onclick="window.location.href='<?= base_url('exam_list') ?>'">Exam Master</div>
          <div class="sub-item" data-url="allocation_list" onclick="window.location.href='<?= base_url('allocation_list')?>'">Exam Marks Allocation</div>
          <div class="sub-item" data-url="Marksentry_list" onclick="window.location.href='<?php echo base_url('Marksentry_list'); ?>'">Exam Marks Entry</div>
        </div>

        <!-- Change Password -->
        <div class="nav-item<?php echo $activePage === 'change-password' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('change_password'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <span class="nav-label">Change Password</span>
        </div>

      </nav>

      <!-- Logout -->
      <div class="sb-logout" onclick="window.location.href='<?php echo base_url('logout'); ?>'">
        <div class="nav-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
        </div>
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
          <!-- Dark Mode Toggle -->
          <button class="dark-toggle" id="darkToggle" onclick="toggleDarkMode()" title="Toggle dark mode">
            <div class="dark-toggle-thumb"></div>
          </button>

          <!-- Notifications -->
          <div style="position:relative">
            <div class="tb-icon-btn" id="notifBtn" onclick="toggleNotif()">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
              </svg>
              <div class="notif-dot" id="notifDot"></div>
            </div>
            <!-- Notification Panel -->
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

          <!-- User -->
          <div class="tb-user">
            <div class="tb-avatar"><?php echo $initials; ?></div>
            <span class="tb-uname"><?php echo $res; ?></span>
          </div>
        </div>
      </header>

      <!-- PAGE CONTENT (opened here, closed in footer.php) -->
      <div class="page-content">

<script>
  /**
   * Toggle a submenu open/closed and mark its parent nav-item active.
   * Closes any other open submenu first (accordion behavior).
   */
  function toggleNav(el) {
    const submenuId = 'subnav-' + el.getAttribute('data-submenu');
    const submenu = document.getElementById(submenuId);
    const isOpen = el.classList.contains('open');

    // Close all other open menus
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

  /**
   * On page load, check the current URL against every sub-item's data-url.
   * If it matches: mark the sub-item active, open its submenu,
   * and mark the parent nav-item active + open (this fixes "submenu click
   * should activate the whole main menu").
   */
  function highlightActiveMenu() {
    const currentPath = window.location.pathname.split('/').filter(Boolean).pop() || '';
    const currentHref = window.location.href;

    document.querySelectorAll('.sub-item').forEach(function (item) {
      const url = item.getAttribute('data-url');
      if (!url) return;

      const urlTail = url.split('/').filter(Boolean).pop();

      if (currentPath === urlTail || currentHref.indexOf(url) !== -1) {
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
  }

  document.addEventListener('DOMContentLoaded', highlightActiveMenu);
</script>