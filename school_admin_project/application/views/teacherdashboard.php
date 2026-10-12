<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$icons = array(
    'user'  => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>',
    'image' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="1.6"/><path d="m21 15-5-5L5 21"/>'
);
?>
<style>
.exam-sub{display:block;font-size:11.5px;color:var(--slate-500,#64748b);margin-top:2px}
.cls-chip{display:inline-block;padding:3px 10px;border-radius:99px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600;white-space:nowrap}
.subj-cell{max-width:260px;font-size:12.5px;color:#475569}
.subj-cell b{color:#0f172a}
.acc-list{display:grid;gap:12px;margin:0;padding:0;list-style:none}
.acc-list li{display:flex;justify-content:space-between;gap:12px;font-size:13.5px;padding-bottom:10px;border-bottom:1px solid #f1f5f9}
.acc-list li:last-child{border:0;padding-bottom:0}
.acc-list span{color:#64748b}
.acc-list strong{text-align:right}
.empty-row{text-align:center;color:#64748b;padding:28px 0}

/* scrollable Active Exams table */
.exam-scroll{max-height:380px;overflow:auto;scrollbar-width:thin;scrollbar-color:#cbd5e1 transparent}
.exam-scroll::-webkit-scrollbar{width:8px;height:8px}
.exam-scroll::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:99px}
.exam-scroll::-webkit-scrollbar-thumb:hover{background:#94a3b8}
.exam-scroll thead th{position:sticky;top:0;z-index:2;background:#f8fafc;box-shadow:0 1px 0 #e2e8f0}
</style>

<!-- Page Body -->
<main class="page">

  <!-- Hero -->
  <section class="hero hero-admin">
    <svg class="hero-orbit" viewBox="0 0 200 200" aria-hidden="true">
      <circle cx="100" cy="100" r="90" fill="none" stroke="rgba(255,255,255,.14)" stroke-width="1.5"/>
      <circle cx="100" cy="100" r="60" fill="none" stroke="rgba(255,255,255,.14)" stroke-width="1.5"/>
      <circle cx="100" cy="34" r="6" fill="rgba(255,255,255,.85)"/>
      <circle cx="152" cy="140" r="4" fill="rgba(255,255,255,.5)"/>
    </svg>
    <div class="hero-inner">
      <span class="hero-eyebrow">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9L12 3Z"/><path d="M19 15l.9 2.4L22 18l-2.1.6L19 21l-.9-2.4L16 18l2.1-.6L19 15Z"/></svg>
        <?= html_escape(strtoupper($role_name)) ?> PANEL
      </span>
      <h1 class="hero-title"><span id="greet">Good day</span>, <?= html_escape(ucfirst($user_name)) ?> 👋</h1>
      <p class="hero-sub">Here's what's happening at Vidyodaya Public School today.</p>
      <div class="hero-chips">
        <span class="hero-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          AY <?= html_escape($ay) ?>
        </span>
        <span class="hero-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <?= (int) $stats->exams ?> active <?= (int) $stats->exams === 1 ? 'exam' : 'exams' ?>
        </span>
        <span class="hero-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
          <span id="clock">--:--</span>
        </span>
      </div>
    </div>
  </section>

  <!-- Stats -->
  <div class="stat-grid">
    <?php
    $cards = array(
        array('Active Exams',   (int) $stats->exams,    $see_all ? 'across the school' : 'for your classes', '#4f46e5',
              '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6"/>'),
        array('Ongoing Now',    (int) $stats->ongoing,  'exams in progress', '#0d9488',
              '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'),
        array('Classes',        (int) $stats->classes,  'covered by active exams', '#ea580c',
              '<path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/>'),
        array('Subjects',       (int) $stats->subjects, 'in active exams', '#db2777',
              '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>')
    );
    foreach ($cards as $c): ?>
      <div class="stat-card">
        <span class="stat-bar" style="background:linear-gradient(90deg, <?= $c[3] ?>, transparent)"></span>
        <div class="stat-top">
          <span class="stat-ic" style="background:<?= $c[3] ?>1a;color:<?= $c[3] ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?= $c[4] ?></svg>
          </span>
        </div>
        <div class="stat-val"><?= $c[1] ?></div>
        <div class="stat-label"><?= $c[0] ?></div>
        <div class="stat-sub"><?= $c[2] ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Row 1: active exams + news -->
  <div class="dash-grid">
    <div>
      <div class="card section-card">
        <h2 class="section-title">
          <span class="section-title-bar"></span>
          Active Exams
        </h2>
        <div class="table-wrap exam-scroll">
          <table class="table">
            <thead>
              <tr>
                <th>Exam</th>
                <th>Class</th>
                <th>Subjects</th>
                <th>Max Marks</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($exams)): ?>
                <?php foreach ($exams as $e): ?>
                  <?php
                    if ((int) $e->emIsOngoing === 1)     { $st = 'Ongoing';   $cls = 'badge-ok'; }
                    elseif ((int) $e->emIsOpened === 1)  { $st = 'Open';      $cls = 'badge-purple'; }
                    else                                 { $st = 'Scheduled'; $cls = 'badge-muted'; }
                    $title = $e->emDisplayName !== '' && $e->emDisplayName !== null ? $e->emDisplayName : $e->emName;
                  ?>
                  <tr>
                    <td>
                      <strong><?= html_escape($title) ?></strong>
                      <span class="exam-sub"><?= html_escape($e->emName) ?><?= (int) $e->emIsGrade === 1 ? ' · Grade based' : '' ?></span>
                    </td>
                    <td>
                      <?php if ($e->class_id): ?>
                        <span class="cls-chip"><?= html_escape($e->class_name ?: ('Class #' . (int) $e->class_id)) ?></span>
                      <?php else: ?>
                        <span class="exam-sub">Not assigned</span>
                      <?php endif; ?>
                    </td>
                    <td class="subj-cell">
                      <?php if ((int) $e->subj_count > 0): ?>
                        <b><?= (int) $e->subj_count ?></b> · <?= html_escape($e->subjects) ?>
                      <?php else: ?>
                        —
                      <?php endif; ?>
                    </td>
                    <td><?= $e->max_mark !== null ? (int) $e->max_mark : '—' ?></td>
                    <td><span class="badge <?= $cls ?>"><?= $st ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="empty-row">No active exams<?= $see_all ? '' : ' for your classes' ?>.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div>
      <div class="card section-card">
        <h2 class="section-title">
          <span class="section-title-bar"></span>
          Recent School News
        </h2>
        <div class="section-body" style="display:flex;flex-direction:column;gap:12px;">
          <div class="mini-news">
            <div style="width:48px;height:48px;border-radius:10px;background:var(--brand-100);color:var(--brand-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">NEWS</div>
            <div>
              <strong>Annual Sports Meet 2026 Announced</strong>
              <span>3 days ago · Sports festival beginning February 12</span>
            </div>
          </div>

          <div class="mini-news">
            <div style="width:48px;height:48px;border-radius:10px;background:var(--emerald-100);color:var(--emerald-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">CBSE</div>
            <div>
              <strong>CBSE Board Exam Schedule Released</strong>
              <span>1 week ago · Practical and theory timetable available</span>
            </div>
          </div>

          <div class="mini-news">
            <div style="width:48px;height:48px;border-radius:10px;background:var(--amber-100);color:var(--amber-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">EXPO</div>
            <div>
              <strong>Science Exhibition Project Registration</strong>
              <span>2 weeks ago · Register before end of this month</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 2: quick actions + account -->
  <div class="dash-grid">
    <div>
      <div class="card section-card">
        <h2 class="section-title">
          <span class="section-title-bar"></span>
          Quick Actions
        </h2>
        <div class="section-body">
          <div class="qa-grid">
            <?php foreach ($actions as $a): ?>
              <a class="qa-tile" href="<?= html_escape($a['url']) ?>">
                <span class="qa-ic" style="background:<?= $a['color'] ?>1a;color:<?= $a['color'] ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?= isset($icons[$a['icon']]) ? $icons[$a['icon']] : '' ?></svg>
                </span>
                <span><?= html_escape($a['label']) ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="card section-card">
        <h2 class="section-title">
          <span class="section-title-bar"></span>
          My Account
        </h2>
        <div class="section-body">
          <ul class="acc-list">
            <li><span>Username</span><strong><?= html_escape($user_name) ?></strong></li>
            <li><span>Role</span><strong><?= html_escape($role_name) ?></strong></li>
            <li><span>Account type</span><strong><?= $is_employee ? 'Employee' : 'Administrator login' ?></strong></li>
            <li><span>Exam access</span><strong><?= $see_all ? 'All classes' : 'Assigned classes' ?></strong></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
(function () {
  const h = new Date().getHours();
  document.getElementById('greet').textContent = h < 12 ? 'Good morning' : (h < 17 ? 'Good afternoon' : 'Good evening');

  const clock = document.getElementById('clock');
  const tick = () => { clock.textContent = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' }); };
  tick(); setInterval(tick, 30000);
})();
</script>