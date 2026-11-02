<?php /* Receives: $current, $nextName, $next, $classes */ ?>
<style>
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin:18px 0}
.stat{background:#fff;border:1px solid var(--slate-200);border-radius:12px;padding:16px}
.stat .lbl{font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.4px}
.stat .val{font-size:30px;font-weight:700;margin-top:4px}
.stat.boys .val{color:#2563eb}.stat.girls .val{color:#db2777}.stat.total .val{color:#15803d}
.promo-box{padding:20px}
.promo-years{display:flex;gap:10px;align-items:center;margin-bottom:18px;flex-wrap:wrap}
.chip{padding:4px 12px;border-radius:999px;font-size:13px;font-weight:600;background:#f1f5f9}
.chip.cur{background:#dcfce7;color:#15803d}.chip.nxt{background:#e0e7ff;color:#4338ca}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.field-input{width:100%;max-width:320px;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;background:#fff}
.warn{color:#b45309;background:#fffbeb;border:1px solid #fde68a;padding:10px 12px;border-radius:8px;font-size:13px;margin-top:14px}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Promotion</h1>
      <p class="page-sub">Move a class to the next academic year</p>
    </div>
  </div>

  <div class="card promo-box">
    <?php if (!$current): ?>
      <div class="warn">No current academic year is set. Mark one year as current first.</div>
    <?php else: ?>
      <div class="promo-years">
        <span class="chip cur">Current: <?= html_escape($current->amYear) ?></span>
        <span>→</span>
        <span class="chip nxt">Next: <?= html_escape($nextName) ?></span>
      </div>

      <?php if (!$next): ?>
        <div class="warn">Academic year <b><?= html_escape($nextName) ?></b> does not exist. Add it in Academic Year before promoting.</div>
      <?php endif; ?>

      <label class="field-label" for="classSel">Class</label>
      <select id="classSel" class="field-input">
        <option value="">-- Select class --</option>
        <?php foreach ($classes as $c): ?>
          <option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option>
        <?php endforeach; ?>
      </select>

      <div class="stat-grid">
        <div class="stat boys"><div class="lbl">Boys</div><div class="val" id="sBoys">0</div></div>
        <div class="stat girls"><div class="lbl">Girls</div><div class="val" id="sGirls">0</div></div>
        <div class="stat total"><div class="lbl">Total Students</div><div class="val" id="sTotal">0</div></div>
      </div>

      <button class="btn btn-primary" type="button" id="promoteBtn" disabled>Promote to <?= html_escape($nextName) ?></button>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const sel = $('classSel'), btn = $('promoteBtn');
  if (!sel) return;

  const STATS_URL = '<?= site_url('promotion_stats') ?>';
  const RUN_URL   = '<?= site_url('promotion_run') ?>';
  const CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  const NEXT_OK   = <?= $next ? 'true' : 'false' ?>;
  const NEXT_NAME = '<?= html_escape($nextName) ?>';
  let total = 0;

  async function post(url, classId) {
    const fd = new FormData();
    fd.append('class_id', classId);
    fd.append(CSRF_NAME, CSRF_HASH);
    const res = await (await fetch(url, { method: 'POST', body: fd })).json();
    if (res.csrf) CSRF_HASH = res.csrf;
    return res;
  }

  async function loadStats() {
    total = 0;
    $('sBoys').textContent = $('sGirls').textContent = $('sTotal').textContent = 0;
    btn.disabled = true;
    if (!sel.value) return;
    try {
      const r = await post(STATS_URL, sel.value);
      if (!r.status) return;
      $('sBoys').textContent  = r.boys;
      $('sGirls').textContent = r.girls;
      $('sTotal').textContent = r.total;
      total = r.total;
      btn.disabled = !(NEXT_OK && total > 0);
    } catch (e) {}
  }
  sel.addEventListener('change', loadStats);

  btn.addEventListener('click', () => {
    const cls = sel.options[sel.selectedIndex].text;
    Swal.fire({
      title: 'Promote ' + cls + '?',
      text: total + ' students will be moved to ' + NEXT_NAME + '.',
      icon: 'question', showCancelButton: true,
      confirmButtonText: 'Yes, promote', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      btn.disabled = true;
      try {
        const res = await post(RUN_URL, sel.value);
        Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? 'Done' : 'Error', text: res.msg });
      } catch (e) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
      }
      loadStats();
    });
  });
})();
</script>