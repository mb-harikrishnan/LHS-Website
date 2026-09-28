<?php
// Role-aware dashboard content (UI only - reads existing session data set by the auth layer)
$userRoleId = (int) ($this->session->userdata('user_role_id') ?? 0);
$firstName  = $this->session->userdata('c_username') ?? 'User';
$hour       = (int) date('G');
$greeting   = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
$today      = date('l, d M Y');

$quickLinks = [
    ['label' => 'Notifications',  'icon' => 'fa-bell',        'url' => base_url('notifications')],
    ['label' => 'Change Password','icon' => 'fa-key',         'url' => base_url('change_password')],
];
?>

<div class="dash-wrap">

  <!-- === WELCOME BANNER === -->
  <div class="dash-hero">
    <div class="dash-hero-deco d1"></div>
    <div class="dash-hero-deco d2"></div>
    <div class="dash-hero-body">
      <div class="dash-hero-left">
        <span class="dash-hero-badge"><i class="fa fa-school"></i> Little Heart School - Management Portal</span>
        <h1 class="dash-hero-title"><?php echo $greeting; ?>, <em><?php echo htmlspecialchars($firstName); ?></em></h1>
        <p class="dash-hero-sub">Here's a quick overview of your workspace. Use the sidebar to manage academics, students, staff and school content.</p>
      </div>
      <div class="dash-hero-right">
        <div class="dash-hero-date">
          <i class="fa fa-calendar-day"></i>
          <span><?php echo $today; ?></span>
        </div>
        <div class="dash-hero-role">
          <i class="fa fa-id-badge"></i>
          <span><?php echo $userRoleId === 1 ? 'Administrator' : ($userRoleId === 2 ? 'Teacher' : 'Staff'); ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- === QUICK ACTIONS (role-aware) === -->
  <div class="dash-actions">
    <?php if ($userRoleId === 1) { ?>
      <a class="dash-action-card" href="<?php echo base_url('add_student'); ?>">
        <span class="dac-icon blue"><i class="fa fa-user-plus"></i></span>
        <span class="dac-text"><strong>Admissions</strong><small>Add a new student</small></span>
      </a>
      <a class="dash-action-card" href="<?php echo base_url('add_exam'); ?>">
        <span class="dac-icon green"><i class="fa fa-file-pen"></i></span>
        <span class="dac-text"><strong>Exams</strong><small>Create &amp; schedule exams</small></span>
      </a>
      <a class="dash-action-card" href="<?php echo base_url('add_employee'); ?>">
        <span class="dac-icon purple"><i class="fa fa-user-tie"></i></span>
        <span class="dac-text"><strong>Staff</strong><small>Add teacher / employee</small></span>
      </a>
      <a class="dash-action-card" href="<?php echo base_url('add_news'); ?>">
        <span class="dac-icon amber"><i class="fa fa-bullhorn"></i></span>
        <span class="dac-text"><strong>Announcements</strong><small>Publish school news</small></span>
      </a>
    <?php } else { ?>
      <a class="dash-action-card" href="<?php echo base_url('marksentry_list'); ?>">
        <span class="dac-icon blue"><i class="fa fa-pen-to-square"></i></span>
        <span class="dac-text"><strong>Mark Entry</strong><small>Enter / edit student marks</small></span>
      </a>
      <a class="dash-action-card" href="<?php echo base_url('students_list'); ?>">
        <span class="dac-icon green"><i class="fa fa-users"></i></span>
        <span class="dac-text"><strong>My Students</strong><small>View class students</small></span>
      </a>
      <a class="dash-action-card" href="<?php echo base_url('exam_list'); ?>">
        <span class="dac-icon purple"><i class="fa fa-chart-line"></i></span>
        <span class="dac-text"><strong>Exams</strong><small>Exam schedules &amp; results</small></span>
      </a>
      <a class="dash-action-card" href="<?php echo base_url('notifications'); ?>">
        <span class="dac-icon amber"><i class="fa fa-bell"></i></span>
        <span class="dac-text"><strong>Notifications</strong><small>School announcements</small></span>
      </a>
    <?php } ?>
  </div>

  <!-- === INFORMATION CARDS === -->
  <div class="dash-grid">

    <div class="card dash-card">
      <div class="card-head">
        <div class="card-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Getting Started
        </div>
      </div>
      <div class="dash-card-body">
        <p class="dash-muted">Follow these steps to keep school records accurate and up to date:</p>
        <ul class="dash-checklist">
          <li><i class="fa fa-circle-check"></i> Keep class, division &amp; subject masters current each academic year.</li>
          <li><i class="fa fa-circle-check"></i> Review exam schedules and publish results on time.</li>
          <li><i class="fa fa-circle-check"></i> Update staff allocations when timetables change.</li>
          <li><i class="fa fa-circle-check"></i> Post announcements so parents &amp; teachers stay informed.</li>
        </ul>
      </div>
    </div>

    <div class="card dash-card">
      <div class="card-head">
        <div class="card-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          Shortcuts
        </div>
      </div>
      <div class="dash-card-body">
        <div class="dash-links">
          <?php foreach ($quickLinks as $ql) { ?>
            <a class="dash-link" href="<?php echo $ql['url']; ?>">
              <i class="fa <?php echo $ql['icon']; ?>"></i>
              <span><?php echo $ql['label']; ?></span>
              <i class="fa fa-chevron-right dash-link-arrow"></i>
            </a>
          <?php } ?>
          <a class="dash-link" href="<?php echo base_url('Marksentry_list'); ?>">
            <i class="fa fa-pen-to-square"></i>
            <span>Mark Entry</span>
            <i class="fa fa-chevron-right dash-link-arrow"></i>
          </a>
          <a class="dash-link" href="<?php echo base_url('logout'); ?>">
            <i class="fa fa-arrow-right-from-bracket"></i>
            <span>Sign Out</span>
            <i class="fa fa-chevron-right dash-link-arrow"></i>
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
/* === Dashboard (design-system aligned) === */
.dash-wrap{animation:fadeUp .45s ease both}
.dash-hero{
  position:relative;overflow:hidden;border-radius:var(--radius-lg);
  background:linear-gradient(120deg,var(--brand-900) 0%,var(--brand-700) 60%,var(--brand-600) 100%);
  color:#fff;box-shadow:var(--shadow);margin-bottom:24px;
}
.dash-hero-deco{position:absolute;border-radius:50%;pointer-events:none;border:1px solid rgba(255,255,255,.14)}
.dash-hero-deco.d1{top:-70px;right:-50px;width:220px;height:220px;background:rgba(255,255,255,.05)}
.dash-hero-deco.d2{bottom:-60px;left:32%;width:140px;height:140px}
.dash-hero-body{
  position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;
  gap:24px;padding:32px 36px;flex-wrap:wrap;
}
.dash-hero-badge{
  display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);
  border:1px solid rgba(255,255,255,.2);border-radius:var(--radius-pill);
  padding:5px 14px;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:14px;
}
.dash-hero-title{font-size:26px;font-weight:700;line-height:1.25;margin-bottom:8px}
.dash-hero-title em{font-style:normal;color:var(--accent-light)}
.dash-hero-sub{font-size:13.5px;color:rgba(255,255,255,.75);max-width:560px;line-height:1.6}
.dash-hero-right{display:flex;flex-direction:column;gap:10px;min-width:180px}
.dash-hero-date,.dash-hero-role{
  display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.1);
  border:1px solid rgba(255,255,255,.16);border-radius:var(--radius);
  padding:10px 16px;font-size:13px;font-weight:500;white-space:nowrap;
}
.dash-hero-date i,.dash-hero-role i{color:var(--accent-light)}
.dash-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
.dash-action-card{
  display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);
  border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);
  text-decoration:none;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease;
}
.dash-action-card:hover{transform:translateY(-3px);box-shadow:var(--shadow);border-color:var(--brand-100)}
.dac-icon{
  width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;
  font-size:16px;flex-shrink:0;
}
.dac-icon.blue{background:var(--brand-50);color:var(--brand-600)}
.dac-icon.green{background:var(--success-soft);color:var(--success)}
.dac-icon.purple{background:#F3E8FF;color:#7C3AED}
.dac-icon.amber{background:var(--accent-pale);color:var(--accent)}
.dac-text{display:flex;flex-direction:column;min-width:0}
.dac-text strong{font-size:13.5px;font-weight:600;color:var(--text)}
.dac-text small{font-size:11.5px;color:var(--text-light);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.dash-grid{display:grid;grid-template-columns:1.4fr 1fr;gap:20px}
.dash-card-body{padding:20px 24px}
.dash-muted{font-size:13px;color:var(--text-light);margin-bottom:12px}
.dash-checklist{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px}
.dash-checklist li{display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--text-mid);line-height:1.55}
.dash-checklist i{color:var(--success);margin-top:3px}
.dash-links{display:flex;flex-direction:column}
.dash-link{
  display:flex;align-items:center;gap:12px;padding:12px 6px;text-decoration:none;
  border-bottom:1px solid var(--border-soft);font-size:13.5px;color:var(--text-mid);
  transition:background .15s ease,color .15s ease;
}
.dash-link:last-child{border-bottom:none}
.dash-link:hover{color:var(--brand-600)}
.dash-link i:first-child{width:20px;text-align:center;color:var(--brand-600)}
.dash-link span{flex:1}
.dash-link-arrow{font-size:10px;color:var(--text-light);transition:transform .15s ease}
.dash-link:hover .dash-link-arrow{transform:translateX(3px);color:var(--brand-600)}
@media(max-width:1200px){.dash-actions{grid-template-columns:repeat(2,1fr)}.dash-grid{grid-template-columns:1fr}}
@media(max-width:600px){
  .dash-actions{grid-template-columns:1fr}
  .dash-hero-body{padding:24px 20px}
  .dash-hero-right{width:100%;flex-direction:row;flex-wrap:wrap}
  .dash-hero-title{font-size:22px}
}
</style>
