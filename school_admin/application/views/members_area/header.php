<?php
if (!isset($pageTitle)) $pageTitle = 'Dashboard';
if (!isset($breadcrumb)) $breadcrumb = 'Dashboard';
if (!isset($activePage)) $activePage = 'dashboard';
if (!isset($showGlobalSearch)) $showGlobalSearch = true;
if (!isset($pageScripts)) $pageScripts = [];
if (!isset($pageStylesheets)) $pageStylesheets = [];
if (!isset($pageHeadScripts)) $pageHeadScripts = [];

$userId = $this->session->userdata('id');

$firstName = $this->session->userdata('c_username');


//  $sql1 = "SELECT c_title,c_news,d_date  FROM news  WHERE c_status='A' LIMIT 3";
//   $qry1 = $this->db->query($sql1);
//   $result =$qry1->result();

$count = 1;






// Take first 2 letters
$initials = strtoupper(substr($firstName, 0, 2));

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
</head>

<body>

  <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  <!-- Toast container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- â• â• â•  LAYOUT â• â• â•  -->
  <div class="layout">

    <!-- â•â•â• SIDEBAR â•â•â• -->
    <aside class="sidebar" id="sidebar">
      <!-- Decorative elements -->
      <div class="sidebar-deco d1"></div>
      <div class="sidebar-deco d2"></div>
      <div class="sidebar-deco d3"></div>

      <!-- Header -->
      <div class="sb-header">
        <!-- <img src="http://localhost:8000/assets/images/main_image/school_logo.png"
          id="headerProfileImage"
          style="width:100%;height:100%;object-fit:cover;border-radius:50%;"> -->
        <div class="sb-brand">
          <div class="sb-name">Little Heart School</div>
          <div class="sb-tag">Management Portal</div>
        </div>
      </div>

      <!-- User Card -->
      <div class="sb-user">
        <!-- <div class="sb-avatar">RM</div> -->
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

        <div class="nav-item" onclick="toggleNav(this)">
          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg> -->
          </div>
          <span class="nav-label">Mandatory Disclosure</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-0">
          <div class="sub-item active" onclick="window.location.href='general_information'">Document And Information</div>
          <div class="sub-item" onclick="window.location.href='Result_and_Staff'">Result & Staff</div>
          <div class="sub-item " onclick="window.location.href='infrastructure'">Infrastructure video</div>
        </div>

        <div class="nav-item<?php echo $activePage === 'school-news' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('school_news'); ?>'">

          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                stroke-width="2" stroke-linecap="round">

                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>

            </svg> -->
          </div>

          <span class="nav-label">School News</span>

        </div>

        <div class="nav-item<?php echo $activePage === 'school-gallery' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('gallery'); ?>'">

          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                stroke-width="2" stroke-linecap="round">

                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>

            </svg> -->
          </div>

          <span class="nav-label">Gallery</span>

        </div>



        <div class="nav-item" onclick="toggleNav(this)">
          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg> -->
          </div>
          <span class="nav-label">Co Curricular Activities</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-0">
          <div class="sub-item active" onclick="window.location.href='co_curricular_list'">Co Curricular Activities Main List</div>
          <div class="sub-item" onclick="window.location.href='activities_list'">All Images</div>
        </div>





        <div class="nav-item" onclick="toggleNav(this)">
          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg> -->
          </div>
          <span class="nav-label">Vacancy</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-0">
          <div class="sub-item active" onclick="window.location.href='vaccancy_list'">Add Vacancy</div>
          <div class="sub-item" onclick="window.location.href='apply_members'">Applications</div>
        </div>

        <div class="nav-item<?php echo $activePage === 'school-questionpaper_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('questionpaper_list'); ?>'">

          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                stroke-width="2" stroke-linecap="round">

                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>

            </svg> -->
          </div>

          <span class="nav-label">Add Downloads</span>

        </div>

        <div class="nav-item<?php echo $activePage === 'school-slider_list' ? ' active' : ''; ?>"
          onclick="window.location.href='<?php echo base_url('slider_list'); ?>'">

          <div class="nav-icon">
            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                stroke-width="2" stroke-linecap="round">

                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>

            </svg> -->
          </div>

          <span class="nav-label">Add Homepage Slider</span>

        </div>


        <!-- <div class="nav-item" onclick="setActive(this)">
        <div class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
        </div>
        <span class="nav-label">Growth Analytics</span>
      </div> -->

        <!-- <div class="nav-item<?php echo $activePage === 'genealogy' ? ' active' : ''; ?>" onclick="window.location='genealogy.php'">
        <div class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <circle cx="12" cy="5" r="3"/>
            <circle cx="5" cy="19" r="3"/>
            <circle cx="19" cy="19" r="3"/>
            <line x1="12" y1="8" x2="5" y2="16"/>
            <line x1="12" y1="8" x2="19" y2="16"/>
          </svg>
        </div>
        <span class="nav-label">Board Plan</span>
      </div> -->

        <!-- <div class="nav-item<?php echo $activePage === 'reports' ? ' active' : ''; ?>" onclick="window.location='reports.php'">
        <div class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
          </svg>
        </div>
        <span class="nav-label">Reports</span>
      </div> -->

        <!-- <div class="nav-item<?php echo in_array($activePage, ['purchase-package', 'activation-logs', 'checkout']) ? ' active open' : ''; ?>" onclick="toggleNav(this)">
        <div class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
            <line x1="12" y1="22.08" x2="12" y2="12"/>
          </svg>
        </div>
        <span class="nav-label">Package Activation</span>
        <span class="nav-arrow">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
      </div> -->
        <!-- <div class="sub-nav<?php echo in_array($activePage, ['purchase-package', 'activation-logs', 'checkout']) ? ' open' : ''; ?>" id="subnav-2"<?php echo in_array($activePage, ['purchase-package', 'activation-logs', 'checkout']) ? ' style="max-height:300px"' : ''; ?>>
        <div class="sub-item<?php echo $activePage === 'purchase-package' ? ' active' : ''; ?>" onclick="window.location='purchase-package.php'">Purchase Package</div>
        <div class="sub-item<?php echo $activePage === 'activation-logs' ? ' active' : ''; ?>" onclick="window.location='activation-logs.php'">Activation Logs</div>
      </div> -->

        <div class="sb-divider"></div>
        <div class="nav-section-label">TEACHERS ZONE</div>

        <div class="nav-item<?php echo $activePage === 'change-password' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('#'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <span class="nav-label">My Dashboard</span>
        </div>

        <div class="nav-item<?php echo $activePage === 'change-password' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('employee_list'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <span class="nav-label">Employees</span>
        </div>

        <div class="nav-item" onclick="toggleNav(this)">
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
        <div class="sub-nav" id="subnav-0">
          <div class="sub-item active" onclick="window.location.href='#'">Class</div>
          <div class="sub-item active" onclick="window.location.href='#'">Division</div>
          <div class="sub-item active" onclick="window.location.href='class_divition_list'">Class Division Allocation</div>
          <div class="sub-item active" onclick="window.location.href='#'">Students</div>

        </div>

        <div class="nav-item" onclick="toggleNav(this)">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <span class="nav-label">Exams</span>
          <span class="nav-arrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="9 18 15 12 9 6" />
            </svg>
          </span>
        </div>
        <div class="sub-nav" id="subnav-0">
          <div class="sub-item active" onclick="window.location.href='exam_list'">Exam Master</div>
          <div class="sub-item" onclick="window.location.href='add_subject'">Exam Marks Allocation</div>
          <div class="sub-item" onclick="window.location.href='#'">Exam Marks Entry</div>
        </div>



        <div class="nav-item<?php echo $activePage === 'change-password' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('change_password'); ?>'">
          <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </div>
          <span class="nav-label">Change Password</span>
        </div>

        <!-- <div class="nav-item<?php echo $activePage === 'profile' ? ' active' : ''; ?>" onclick="window.location='<?php echo base_url('profile') ?>'">
        <div class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/>
          </svg>
        </div>
        <span class="nav-label">My Profile</span>
      </div> -->
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

    <!-- â•â•â• MAIN AREA â•â•â• -->
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
              <input type="text" id="globalSearch" placeholder="Search members, reportsâ€¦" oninput="globalSearchFilter(this.value)">
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
            <div class="tb-avatar">

              <?php echo $initials; ?>

            </div>
            <span class="tb-uname"><?php echo $firstName; ?></span>
          </div>
        </div>
      </header>

      <!-- PAGE CONTENT (opened here, closed in footer.php) -->
      <div class="page-content">