<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  .exd-wrap {
    --ink:        #1b2033;
    --ink-soft:   #6b7080;
    --line:       #e8e7e2;
    --card:       #ffffff;
    --card-line:  #e6e5df;

    /* pulled from the portal's own chrome: navy sidebar, gold "active" badge */
    --navy:       #1e2a5c;
    --navy-tint:  #eef0f7;
    --green:      #15803d;
    --green-fill: #e6f6ec;
    --green-line: #b7e2c5;

    --blue:       #1a56c4;
    --blue-fill:  #eaf1fc;
    --blue-line:  #c9dbf6;

    --closed:     #8b8d95;

    background: #ffffff;
    color: var(--ink);
    font-family: 'Inter', -apple-system, sans-serif;
    padding: 36px 40px 64px;
    min-height: 100vh;
    box-sizing: border-box;
  }

  .exd-wrap * { box-sizing: border-box; }

  .exd-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 22px;
    margin-bottom: 8px;
  }

  .exd-head h1 {
    font-family: 'Source Serif 4', Georgia, serif;
    font-weight: 600;
    font-size: 28px;
    margin: 0 0 6px;
    letter-spacing: -0.01em;
    color: var(--navy);
  }

  .exd-head p {
    margin: 0;
    color: var(--ink-soft);
    font-size: 14px;
  }

  .exd-live-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy-tint);
    color: var(--navy);
    padding: 8px 14px 8px 10px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    margin-top: 2px;
  }

 .exd-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--green);
  animation: exd-pulse 1.8s ease-in-out infinite;
}

@keyframes exd-pulse {
  0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(21,128,61,.35); }
  50%      { opacity: .55; box-shadow: 0 0 0 5px rgba(21,128,61,0); }
}

  @media (prefers-reduced-motion: reduce) {
    .exd-dot { animation: none; }
  }

  .exd-legend {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    padding: 14px 0 22px;
    border-bottom: 1px solid var(--line);
    margin-bottom: 28px;
  }

  .exd-legend-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12.5px;
    color: var(--ink-soft);
  }

  .exd-legend-swatch {
    width: 9px;
    height: 9px;
    border-radius: 2px;
  }

  .exd-term {
    margin-bottom: 34px;
  }

  .exd-term-head {
    display: flex;
    align-items: baseline;
    gap: 10px;
    margin: 0 0 14px;
  }

  .exd-term-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
  }

  .exd-term-count {
    font-size: 12.5px;
    color: var(--ink-soft);
  }

  .exd-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 12px;
  }

  .exd-card {
    background: var(--card);
    border: 1px solid var(--card-line);
    border-radius: 10px;
    padding: 14px 16px 15px;
    position: relative;
    overflow: hidden;
    transition: border-color .15s ease, box-shadow .15s ease;
  }

  .exd-card:hover {
    border-color: #d4d2ca;
    box-shadow: 0 1px 6px rgba(20,20,30,.05);
  }

  .exd-card-bar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: transparent;
  }

  .exd-card.is-ongoing .exd-card-bar { background: var(--green); }
  .exd-card.is-open .exd-card-bar    { background: var(--blue); }

  .exd-card-name {
    font-weight: 600;
    font-size: 14.5px;
    margin: 6px 0 2px;
    line-height: 1.3;
    color: var(--ink);
  }

  .exd-card-code {
    font-size: 11.5px;
    color: var(--ink-soft);
    margin: 0 0 13px;
  }

  .exd-status-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  .exd-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 9px;
    border-radius: 6px;
  }

  .exd-status.is-ongoing {
    background: var(--green-fill);
    color: var(--green);
    border: 1px solid var(--green-line);
  }

  .exd-status.is-open {
    background: var(--blue-fill);
    color: var(--blue);
    border: 1px solid var(--blue-line);
  }

  .exd-status.is-closed {
    background: transparent;
    color: var(--closed);
    border: 1px solid var(--line);
  }

  .exd-status-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
  }

  .exd-graded-tag {
    font-size: 11px;
    color: var(--ink-soft);
  }

  .exd-empty {
    color: var(--ink-soft);
    font-size: 14px;
    padding: 24px 0;
  }
</style>

<div class="exd-wrap">

  <div class="exd-head">
    <div>
      <h1>Exam dashboard</h1>
      <p>All active assessments, grouped by term.</p>
    </div>

    <?php if (!empty($ongoing_count)): ?>
      <span class="exd-live-chip">
        <span class="exd-dot"></span>
        <?php echo $ongoing_count; ?> exam<?php echo ($ongoing_count > 1) ? 's' : ''; ?> live now
      </span>
    <?php endif; ?>
  </div>

  <div class="exd-legend">
    <span class="exd-legend-item"><span class="exd-legend-swatch" style="background:#15803d;"></span>Ongoing</span>
    <span class="exd-legend-item"><span class="exd-legend-swatch" style="background:#1a56c4;"></span>Open</span>
    <span class="exd-legend-item"><span class="exd-legend-swatch" style="background:#c7c6c0;"></span>Not started</span>
  </div>

  <?php if (empty($exam_groups)): ?>

    <p class="exd-empty">No active exams have been set up yet.</p>

  <?php else: ?>

    <?php foreach ($exam_groups as $term_id => $exams): ?>
      <?php
        $term_ongoing = 0;
        foreach ($exams as $e) { if ($e->emIsOngoing == 1) { $term_ongoing++; } }
      ?>
      <div class="exd-term">
        <div class="exd-term-head">
          <span class="exd-term-label">Term <?php echo html_escape($term_id); ?></span>
          <span class="exd-term-count">
            <?php echo count($exams); ?> exams<?php echo $term_ongoing ? ', ' . $term_ongoing . ' ongoing' : ''; ?>
          </span>
        </div>

        <div class="exd-grid">
          <?php foreach ($exams as $exam): ?>
            <?php
              $is_ongoing = ($exam->emIsOngoing == 1);
              $is_opened  = ($exam->emIsOpened == 1);
              $card_class = 'exd-card';
              if ($is_ongoing) { $card_class .= ' is-ongoing'; }
              elseif ($is_opened) { $card_class .= ' is-open'; }
            ?>
            <div class="<?php echo $card_class; ?>">
              <span class="exd-card-bar"></span>
              <p class="exd-card-name"><?php echo html_escape($exam->emDisplayName); ?></p>
              <p class="exd-card-code"><?php echo html_escape($exam->emName); ?></p>

              <div class="exd-status-row">
                <?php if ($is_ongoing): ?>
                  <span class="exd-status is-ongoing">
                    <span class="exd-status-dot"></span> Ongoing
                  </span>
                <?php elseif ($is_opened): ?>
                  <span class="exd-status is-open">
                    <span class="exd-status-dot"></span> Open
                  </span>
                <?php else: ?>
                  <span class="exd-status is-closed">
                    <span class="exd-status-dot"></span> Not started
                  </span>
                <?php endif; ?>

                <?php if ($exam->emIsGrade == 1): ?>
                  <span class="exd-graded-tag">Grading enabled</span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

  <?php endif; ?>

</div>