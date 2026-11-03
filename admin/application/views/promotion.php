<?php /* Receives: $current, $nextName, $next, $classes (cmId, cmName, boys, girls, total), $pending */ ?>
<style>
.promo-years{display:flex;gap:10px;align-items:center;margin:0 0 20px;flex-wrap:wrap}
.chip{padding:4px 12px;border-radius:999px;font-size:13px;font-weight:600;background:#f1f5f9}
.chip.cur{background:#dcfce7;color:#15803d}.chip.nxt{background:#e0e7ff;color:#4338ca}
.warn{color:#b45309;background:#fffbeb;border:1px solid #fde68a;padding:10px 12px;border-radius:8px;font-size:13px;margin:0 0 16px}

.class-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px}
.class-card{background:#fff;border:1px solid var(--slate-200);border-radius:16px;padding:20px;
            box-shadow:0 1px 3px rgba(15,23,42,.06);transition:transform .15s, box-shadow .15s}
.class-card:hover{transform:translateY(-3px);box-shadow:0 10px 24px rgba(15,23,42,.10)}
.class-name{font-size:20px;font-weight:700;margin:0 0 14px;color:#0f172a}
.class-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.cs{text-align:center;border-radius:10px;padding:10px 4px}
.cs .lbl{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;color:#64748b}
.cs .val{font-size:24px;font-weight:700;margin-top:2px}
.cs.boys{background:#eff6ff}.cs.boys .val{color:#2563eb}
.cs.girls{background:#fdf2f8}.cs.girls .val{color:#db2777}
.cs.total{background:#f0fdf4}.cs.total .val{color:#15803d}

.promo-footer{margin-top:28px;display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.promo-footer .sum{font-size:14px;color:#475569}
.promo-footer .sum b{color:#0f172a;font-size:18px}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Promotion</h1>
      <p class="page-sub">Move all classes to the next academic year</p>
    </div>
  </div>

  <?php if (!$current): ?>
    <div class="warn">No current academic year is set. Mark one year as current first.</div>
  <?php else: ?>
    <?php $done = ((int)$current->c_status === 1); ?>

    <div class="promo-years">
      <span class="chip cur">Current: <?= html_escape($current->amYear) ?></span>
      <span>→</span>
      <span class="chip nxt">Next: <?= html_escape($nextName) ?></span>
    </div>

    <?php if (!$next): ?>
      <div class="warn">Academic year <b><?= html_escape($nextName) ?></b> does not exist. Add it in Academic Year before promoting.</div>
    <?php endif; ?>

    <div class="class-grid">
      <?php foreach ($classes as $c): ?>
        <div class="class-card">
          <h3 class="class-name"><?= html_escape($c->cmName) ?></h3>
          <div class="class-stats">
            <div class="cs boys"><div class="lbl">Boys</div><div class="val"><?= (int)$c->boys ?></div></div>
            <div class="cs girls"><div class="lbl">Girls</div><div class="val"><?= (int)$c->girls ?></div></div>
            <div class="cs total"><div class="lbl">Total</div><div class="val"><?= (int)$c->total ?></div></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($done): ?>
      <div class="warn" style="margin-top:20px">
        Promotion for <b><?= html_escape($current->amYear) ?></b> is already completed. It cannot be done again.
      </div>
    <?php endif; ?>

    <div class="promo-footer">
      <button type="button" class="btn btn-primary" id="promoteAllBtn"
              <?= (!$done && $next && $pending > 0) ? '' : 'disabled' ?>>
        Promote All Classes to <?= html_escape($nextName) ?>
      </button>
      <span class="sum">Students to promote: <b><?= $done ? 0 : (int)$pending ?></b></span>
    </div>
  <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const btn = document.getElementById('promoteAllBtn');
  if (!btn) return;

  const RUN_URL   = '<?= site_url('promotion_run_all') ?>';
  const CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  const NEXT_NAME = '<?= html_escape($nextName) ?>';
  const TOTAL     = <?= (int)$pending ?>;

  btn.addEventListener('click', () => {
    Swal.fire({
      title: 'Promote all classes?',
      text: TOTAL + ' students will be moved to ' + NEXT_NAME + '. This can be done only once.',
      icon: 'question', showCancelButton: true,
      confirmButtonText: 'Yes, promote all', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      btn.disabled = true;
      try {
        const fd = new FormData();
        fd.append(CSRF_NAME, CSRF_HASH);
        const res = await (await fetch(RUN_URL, { method: 'POST', body: fd })).json();
        if (res.csrf) CSRF_HASH = res.csrf;

        Swal.fire({ icon: res.status ? 'success' : 'error',
                    title: res.status ? 'Done' : 'Error', text: res.msg });

        if (res.status) { setTimeout(() => location.reload(), 1200); return; }
      } catch (e) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
      }
      btn.disabled = false;
    });
  });
})();
</script>