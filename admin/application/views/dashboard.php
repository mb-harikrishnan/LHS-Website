<?php date_default_timezone_set('Asia/Kolkata'); ?>

<style>
  /* Scrollers */
  .exam-scroll,
  .news-scroll {
    max-height: 260px;
    overflow-y: auto;
    overflow-x: auto;
    scrollbar-width: thin;
  }
  .exam-scroll::-webkit-scrollbar,
  .news-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
  .exam-scroll::-webkit-scrollbar-thumb,
  .news-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }

  .exam-scroll thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #fff;
  }

  /* Equal-height cards in the grid */
  .dash-grid > div > .card { height: 100%; }

  /* Full-width quick actions: 4 tiles in one row, wraps on small screens */
  .qa-wide { margin-bottom: 20px; }
  .qa-wide .qa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
  }
</style>

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
        ADMIN PANEL
      </span>
      <h1 class="hero-title">Good day, <?= $this->session->userdata('c_username') ?> 👋</h1>
      <p class="hero-sub">Here's what's happening at Vidyodaya Public School today.</p>
      <div class="hero-chips">
        <span class="hero-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          AY <?= $academic ?>
        </span>
        <span class="hero-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
          <?= date('h:i A') ?>
        </span>
      </div>
    </div>
  </section>

  <!-- Stats -->
  <div class="stat-grid">
    <div class="stat-card">
      <span class="stat-bar" style="background:linear-gradient(90deg, #4f46e5, transparent)"></span>
      <div class="stat-top">
        <span class="stat-ic" style="background:#4f46e51a;color:#4f46e5">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></svg>
        </span>
      </div>
      <div class="stat-val"><?= $all_students_count ?></div>
      <div class="stat-label">Total Students</div>
    </div>

    <div class="stat-card">
      <span class="stat-bar" style="background:linear-gradient(90deg, #0d9488, transparent)"></span>
      <div class="stat-top">
        <span class="stat-ic" style="background:#0d94881a;color:#0d9488">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="M2 13h20"/></svg>
        </span>
      </div>
      <div class="stat-val"><?= $all_employee_count ?></div>
      <div class="stat-label">Employees</div>
    </div>

    <div class="stat-card">
      <span class="stat-bar" style="background:linear-gradient(90deg, #ea580c, transparent)"></span>
      <div class="stat-top">
        <span class="stat-ic" style="background:#ea580c1a;color:#ea580c">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V7"/><path d="M12 7h6M12 11h6M12 15h6M12 19h6"/></svg>
        </span>
      </div>
      <div class="stat-val"><?= $school_news ?></div>
      <div class="stat-label">News Published</div>
      <div class="stat-sub">this academic term</div>
    </div>

    <div class="stat-card">
      <span class="stat-bar" style="background:linear-gradient(90deg, #db2777, transparent)"></span>
      <div class="stat-top">
        <span class="stat-ic" style="background:#db27771a;color:#db2777">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6"/></svg>
        </span>
        <span class="stat-trend">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/></svg>
          On track
        </span>
      </div>
      <div class="stat-val"><?= $exam_master ?></div>
      <div class="stat-label">Exams Scheduled</div>
    </div>
  </div>

  <!-- Quick Actions (full width) -->
  <div class="card section-card qa-wide">
    <h2 class="section-title">
      <span class="section-title-bar"></span>
      Quick Actions
    </h2>
    <div class="section-body">
      <div class="qa-grid">
        <a class="qa-tile" href="<?= base_url('students_list') ?>">
          <span class="qa-ic" style="background:#7c3aed1a;color:#7c3aed">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/><path d="M15 3.13a4 4 0 0 1 0 7.75"/></svg>
          </span>
          <span>Student Directory</span>
        </a>

        <a class="qa-tile" href="<?= base_url('Marksentry_list') ?>">
          <span class="qa-ic" style="background:#0891b21a;color:#0891b2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/></svg>
          </span>
          <span>Mark Entry</span>
        </a>

        <a class="qa-tile" href="<?= base_url('employee_list') ?>">
          <span class="qa-ic" style="background:#ea580c1a;color:#ea580c">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V7"/><path d="M12 7h6M12 11h6M12 15h6M12 19h6"/></svg>
          </span>
          <span>Employees</span>
        </a>

        <a class="qa-tile" href="<?= base_url('apply_members') ?>">
          <span class="qa-ic" style="background:#0d94881a;color:#0d9488">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="M2 13h20"/></svg>
          </span>
          <span>Job Applications</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Exams + News -->
  <div class="dash-grid">

    <!-- Upcoming Exams -->
    <div>
      <div class="card section-card">
        <h2 class="section-title">
          <span class="section-title-bar"></span>
          Upcoming Exams
        </h2>
        <div class="table-wrap exam-scroll">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Exam</th>
                <th>Class</th>
                <th>Division</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($active_exam_list)): ?>
                <?php foreach ($active_exam_list as $i => $exam): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($exam->emDisplayName) ?></strong></td>
                    <td><?= htmlspecialchars($exam->class_name ?? '-') ?></td>
                    <td><?= htmlspecialchars($exam->division_name ?? '-') ?></td>
                    <td><span class="badge badge-ok">Active</span></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" style="text-align:center;">No active exams found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Recent School News -->
    <div>
      <div class="card section-card">
        <h2 class="section-title">
          <span class="section-title-bar"></span>
          Recent School News
        </h2>

        <div class="section-body news-scroll" style="display:flex;flex-direction:column;gap:12px;">
          <?php if (!empty($all_school_news)): ?>
            <?php
              $themes = [
                ['bg' => 'var(--brand-100)',   'fg' => 'var(--brand-700)'],
                ['bg' => 'var(--emerald-100)', 'fg' => 'var(--emerald-700)'],
                ['bg' => 'var(--amber-100)',   'fg' => 'var(--amber-700)'],
              ];
            ?>
            <?php foreach ($all_school_news as $i => $news): ?>
              <?php
                $t = $themes[$i % count($themes)];

                $days = (int) floor((time() - strtotime($news->d_date)) / 86400);
                if ($days < 0)       $when = date('M d, Y', strtotime($news->d_date));
                elseif ($days === 0) $when = 'Today';
                elseif ($days === 1) $when = 'Yesterday';
                elseif ($days < 7)   $when = $days . ' days ago';
                elseif ($days < 30)  $when = floor($days / 7) . ' week' . (floor($days / 7) > 1 ? 's' : '') . ' ago';
                else                 $when = date('M d, Y', strtotime($news->d_date));

                $preview = mb_strimwidth(strip_tags($news->c_news), 0, 70, '...');
              ?>
              <div class="mini-news">
                <div style="width:48px;height:48px;border-radius:10px;background:<?= $t['bg'] ?>;color:<?= $t['fg'] ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">
                  NEWS
                </div>
                <div>
                  <strong><?= htmlspecialchars($news->c_title) ?></strong>
                  <span><?= $when ?> · <?= htmlspecialchars($preview) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="text-align:center;color:#64748b;">No news published yet</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</main>