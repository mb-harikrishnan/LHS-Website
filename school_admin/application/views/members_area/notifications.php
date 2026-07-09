<?php
$pageTitle = 'Notifications';
$breadcrumb = 'Notifications';
$activePage = 'notifications';
$showGlobalSearch = false;
// $pageStylesheets = ['assets/css/notifications.css'];

?>

<link href="<?php echo base_url()?>/assets/css/notifications.css"  rel="stylesheet">

<!-- PAGE CONTENT -->
<div class="page-header">
  <div class="page-eyebrow">
    <div class="eyebrow-pulse"></div>
    System Updates
  </div>
  <h1 class="page-title">Recent <em>Notifications</em></h1>
  <p class="page-sub">Stay updated with the latest listings, airdrops, and system inflation rewards.</p>
</div>

<div class="notif-page-container">
  <div class="notif-page-header">
    <h2 class="notif-header-title">All <em>Alerts</em></h2>
    <div class="notif-header-actions">
      <!-- <button class="btn btn-gold btn-sm" onclick="showToast('All notifications marked as read!', 'gold')">Mark all read</button> -->
    </div>
  </div>

  <div class="notif-list-card">
    
  
    <?php foreach($all_news as $values){ ?>
    <!-- Item 7 (Red) -->
    <div class="notif-row">
      <div class="notif-icon-container">
        <div class="notif-icon-circle color-red">
          <!-- Alert / Shield Icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
        </div>
      </div>
      <div class="notif-content-area">
        <h3 class="notif-row-title"><?php echo $values->c_title ; ?></h3>
        <p class="notif-row-desc"><?php echo $values->c_news ; ?></p>
      </div>
      <!-- <div class="notif-meta-area">
        <span class="notif-time"><?php echo $values->d_date ; ?></span>
        <div class="notif-status-dot status-unread"></div>
      </div> -->
      <div class="notif-meta-area">
        <span class="notif-time">
            <?php 
                if(date('Y-m-d', strtotime($values->d_date)) == date('Y-m-d'))
                {
                    echo 'Today';
                }
                else
                {
                    echo date('d M Y', strtotime($values->d_date));
                }
            ?>
        </span>

        <div class="notif-status-dot status-unread"></div>
    </div>
    </div>
    <?php }?>


  </div>
</div>

  