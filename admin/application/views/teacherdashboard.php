<?php
/* Receives:
   $teacher  (emId, emName, emClass, emDiv, cmName)  - logged-in teacher
   $stats    array(total, boys, girls)               - students in teacher's class/division
   $exams    array of objects (emId, emDisplayName)  - running exams for teacher's class
   $news     array of objects (nwId, nwTitle, nwBody, nwDate)
*/
$has_class  = $teacher && (int)$teacher->emClass > 0;
$class_text = $has_class ? $teacher->cmName  : 'Not assigned';
?>
<style>
.td-hello{margin:0 0 20px}
.td-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:20px}
.td-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;display:flex;align-items:center;gap:14px}
.td-ico{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex:none}
.td-ico svg{width:22px;height:22px}
.td-ico.blue{background:#eff6ff;color:#2563eb}
.td-ico.green{background:#ecfdf5;color:#059669}
.td-ico.pink{background:#fdf2f8;color:#db2777}
.td-ico.amber{background:#fffbeb;color:#d97706}
.td-label{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;color:#64748b}
.td-value{font-size:24px;font-weight:700;color:#0f172a;line-height:1.2}
.td-value.sm{font-size:18px}
.td-cols{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.td-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden}
.td-card-head{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #e2e8f0}
.td-card-head h2{margin:0;font-size:16px}
.td-badge{background:#eef2ff;color:#4338ca;border-radius:999px;padding:2px 10px;font-size:12px;font-weight:700}
.td-list{list-style:none;margin:0;padding:0}
.td-list li{padding:14px 18px;border-bottom:1px solid #f1f5f9}
.td-list li:last-child{border-bottom:0}
.td-exam{display:flex;justify-content:space-between;align-items:center;gap:10px}
.td-exam strong{font-size:14px}
.td-live{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#059669}
.td-live::before{content:"";width:8px;height:8px;border-radius:50%;background:#10b981}
.td-news-title{font-weight:700;font-size:14px;margin:0 0 4px}
.td-news-body{margin:0 0 6px;font-size:13px;color:#475569;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.td-news-date{font-size:12px;color:#94a3b8}
.td-empty{padding:28px 18px;text-align:center;color:#94a3b8;font-size:14px}
.td-warn{background:#fffbeb;border:1px solid #fde68a;color:#92400e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:14px}
@media(max-width:800px){.td-cols{grid-template-columns:1fr}}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Welcome, <?= html_escape($teacher ? $teacher->emName : 'Teacher') ?></h1>
      <p class="page-sub">Your class overview, running exams and school news</p>
    </div>
  </div>

  <?php if (!$has_class): ?>
    <div class="td-warn">No class is assigned to you yet. Please contact the administrator.</div>
  <?php endif; ?>

  <div class="td-grid">
    <div class="td-stat">
      <div class="td-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg></div>
      <div><div class="td-label">Assigned Class</div><div class="td-value sm"><?= html_escape($class_text .'-'. $division) ?></div></div>
    </div>
    <div class="td-stat">
      <div class="td-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/></svg></div>
      <div><div class="td-label">Total Students</div><div class="td-value"><?= (int)$stats['total'] ?></div></div>
    </div>
    <div class="td-stat">
      <div class="td-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 16 0v1"/></svg></div>
      <div><div class="td-label">Boys</div><div class="td-value"><?= (int)$stats['boys'] ?></div></div>
    </div>
    <div class="td-stat">
      <div class="td-ico pink"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 16 0v1"/></svg></div>
      <div><div class="td-label">Girls</div><div class="td-value"><?= (int)$stats['girls'] ?></div></div>
    </div>
    <div class="td-stat">
      <div class="td-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
      <div><div class="td-label">Running Exams</div><div class="td-value"><?= count($exams) ?></div></div>
    </div>
  </div>

  <div class="td-cols">
    <section class="td-card">
      <div class="td-card-head">
        <h2>Running Exams</h2>
        <span class="td-badge"><?= count($exams) ?></span>
      </div>
      <?php if (empty($exams)): ?>
        <div class="td-empty">No exams are running for your class right now.</div>
      <?php else: ?>
        <ul class="td-list">
          <?php foreach ($exams as $e): ?>
            <li class="td-exam">
              <strong><?= html_escape($e->emDisplayName) ?></strong>
              <span class="td-live">Open</span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>

    <section class="td-card">
      <div class="td-card-head">
        <h2>School News</h2>
        <span class="td-badge"><?= count($news) ?></span>
      </div>
      <?php if (empty($news)): ?>
        <div class="td-empty">No news has been posted yet.</div>
      <?php else: ?>
        <ul class="td-list">
          <?php foreach ($news as $n): ?>
            <li>
              <p class="td-news-title"><?= html_escape($n->c_title) ?></p>
              <p class="td-news-body"><?= html_escape($n->c_news) ?></p>
              <span class="td-news-date"><?= html_escape(date('d M Y', strtotime($n->d_date))) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </div>
</main>