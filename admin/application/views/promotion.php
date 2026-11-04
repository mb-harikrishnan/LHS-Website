<?php /* Receives: $current, $nextName, $next, $classes (cmId, cmName, boys, girls, total), $pending */
$sumBoys = $sumGirls = $sumTotal = 0;
foreach ($classes as $c) { $sumBoys += (int)$c->boys; $sumGirls += (int)$c->girls; $sumTotal += (int)$c->total; }
?>
<style>
.pr-years{display:flex;gap:10px;align-items:center;margin:0 0 18px;flex-wrap:wrap}
.pr-chip{display:inline-block;padding:6px 14px;border-radius:999px;font-size:13px;font-weight:600;line-height:1.2;background:#f1f5f9;color:#334155}
.pr-chip.cur{background:#dcfce7;color:#15803d}
.pr-chip.nxt{background:#e0e7ff;color:#4338ca}
.pr-arrow{color:#94a3b8;font-weight:700}

.pr-warn{color:#b45309;background:#fffbeb;border:1px solid #fde68a;padding:10px 14px;border-radius:8px;font-size:13px;margin:0 0 16px}
.pr-done{color:#166534;background:#f0fdf4;border:1px solid #bbf7d0;padding:10px 14px;border-radius:8px;font-size:13px;margin:18px 0 0}

/* summary strip */
.pr-summary{display:flex;gap:12px;flex-wrap:wrap;margin:0 0 20px}
.pr-sum{flex:1 1 140px;max-width:220px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:12px 16px}
.pr-sum .l{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;color:#64748b}
.pr-sum .v{font-size:26px;font-weight:700;line-height:1.2;margin-top:2px}
.pr-sum.b .v{color:#2563eb}.pr-sum.g .v{color:#db2777}.pr-sum.t .v{color:#15803d}

.pr-title{font-size:13px;font-weight:600;color:#475569;margin:0 0 10px;text-transform:uppercase;letter-spacing:.4px}

/* class grid */
.pr-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px}
.pr-card{all:unset;box-sizing:border-box;cursor:pointer;display:block;background:#fff;border:1px solid #e2e8f0;
         border-radius:12px;padding:12px 14px;transition:transform .12s, box-shadow .12s, border-color .12s}
.pr-card:hover,.pr-card:focus-visible{transform:translateY(-2px);box-shadow:0 8px 18px rgba(15,23,42,.10);border-color:#a5b4fc}
.pr-card .nm{font-size:15px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pr-card .tot{font-size:26px;font-weight:700;color:#15803d;line-height:1.2;margin:6px 0 8px}
.pr-card .tot small{font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.3px;margin-left:4px}
.pr-card .bg{display:flex;gap:6px;font-size:12px;font-weight:600}
.pr-card .bg span{flex:1;text-align:center;border-radius:6px;padding:3px 0}
.pr-card .bg .b{background:#eff6ff;color:#2563eb}
.pr-card .bg .g{background:#fdf2f8;color:#db2777}

.pr-note{font-size:12px;color:#64748b;margin:14px 0 0}

.pr-footer{margin-top:24px;display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.pr-footer .sum{font-size:14px;color:#475569}
.pr-footer .sum b{color:#0f172a;font-size:18px}

/* modal (no library needed) */
.pr-modal{position:fixed;inset:0;background:rgba(15,23,42,.55);display:flex;align-items:center;justify-content:center;
          z-index:99999;padding:16px}
.pr-modal[hidden]{display:none}
.pr-dialog{position:relative;background:#fff;border-radius:16px;padding:26px 24px 24px;width:100%;max-width:380px;
           text-align:center;box-shadow:0 20px 50px rgba(15,23,42,.3)}
.pr-x{position:absolute;top:8px;right:12px;border:0;background:none;font-size:26px;line-height:1;color:#94a3b8;cursor:pointer}
.pr-x:hover{color:#0f172a}
.pr-dialog h2{font-size:24px;font-weight:700;color:#0f172a;margin:0 0 4px}
.pr-dialog .sub{font-size:13px;color:#64748b;margin:0 0 18px}
.pr-boxes{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.pr-box{border-radius:12px;padding:14px 4px}
.pr-box .l{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.3px;color:#64748b}
.pr-box .v{font-size:30px;font-weight:700;line-height:1.2}
.pr-box.b{background:#eff6ff}.pr-box.b .v{color:#2563eb}
.pr-box.g{background:#fdf2f8}.pr-box.g .v{color:#db2777}
.pr-box.t{background:#f0fdf4}.pr-box.t .v{color:#15803d}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Promotion</h1>
      <p class="page-sub">Move all classes to the next academic year</p>
    </div>
  </div>

  <?php if (!$current): ?>
    <div class="pr-warn">No current academic year is set. Mark one year as current first.</div>
  <?php else: ?>
    <?php $done = ((int)$current->c_status === 1); ?>

    <div class="pr-years">
      <span class="pr-chip cur">Current: <?= html_escape($current->amYear) ?></span>
      <span class="pr-arrow">&rarr;</span>
      <span class="pr-chip nxt">Next: <?= html_escape($nextName) ?></span>
    </div>

    <?php if (!$next): ?>
      <div class="pr-warn">Academic year <b><?= html_escape($nextName) ?></b> does not exist. Add it in Academic Year before promoting.</div>
    <?php endif; ?>

    <div class="pr-summary">
      <div class="pr-sum t"><div class="l">Students</div><div class="v"><?= $sumTotal ?></div></div>
      <div class="pr-sum b"><div class="l">Boys</div><div class="v"><?= $sumBoys ?></div></div>
      <div class="pr-sum g"><div class="l">Girls</div><div class="v"><?= $sumGirls ?></div></div>
    </div>

    <h3 class="pr-title">Classes (click a class for details)</h3>
    <div class="pr-grid">
      <?php foreach ($classes as $c): ?>
        <button type="button" class="pr-card"
                data-name="<?= html_escape($c->cmName) ?>"
                data-boys="<?= (int)$c->boys ?>"
                data-girls="<?= (int)$c->girls ?>"
                data-total="<?= (int)$c->total ?>">
          <div class="nm"><?= html_escape($c->cmName) ?></div>
          <div class="tot"><?= (int)$c->total ?><small>students</small></div>
          <div class="bg">
            <span class="b">B <?= (int)$c->boys ?></span>
            <span class="g">G <?= (int)$c->girls ?></span>
          </div>
        </button>
      <?php endforeach; ?>
    </div>
    <p class="pr-note">Class 12 is the final year and is not promoted, so it is not listed here.</p>

    <?php if ($done): ?>
      <div class="pr-done">
        Promotion for <b><?= html_escape($current->amYear) ?></b> is already completed. It cannot be done again.
      </div>
    <?php else: ?>
      <div class="pr-footer">
        <button type="button" class="btn btn-primary" id="promoteAllBtn"
                <?= ($next && $pending > 0) ? '' : 'disabled' ?>>
          Promote All Classes to <?= html_escape($nextName) ?>
        </button>
        <span class="sum">Students to promote: <b><?= (int)$pending ?></b></span>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</main>

<!-- class details modal -->
<div class="pr-modal" id="prModal" hidden>
  <div class="pr-dialog" role="dialog" aria-modal="true" aria-labelledby="prName">
    <button type="button" class="pr-x" id="prClose" aria-label="Close">&times;</button>
    <h2 id="prName"></h2>
    <p class="sub">Students in this class</p>
    <div class="pr-boxes">
      <div class="pr-box b"><div class="l">Boys</div><div class="v" id="prBoys">0</div></div>
      <div class="pr-box g"><div class="l">Girls</div><div class="v" id="prGirls">0</div></div>
      <div class="pr-box t"><div class="l">Total</div><div class="v" id="prTotal">0</div></div>
    </div>
  </div>
</div>

<script>
(function () {
  /* ---------- class modal ---------- */
  var modal = document.getElementById('prModal');
  document.body.appendChild(modal);               // avoid any parent z-index / overflow issues

  function openModal(d) {
    document.getElementById('prName').textContent  = d.name;
    document.getElementById('prBoys').textContent  = d.boys;
    document.getElementById('prGirls').textContent = d.girls;
    document.getElementById('prTotal').textContent = d.total;
    modal.hidden = false;
  }
  function closeModal() { modal.hidden = true; }

  document.querySelectorAll('.pr-card').forEach(function (card) {
    card.addEventListener('click', function () { openModal(card.dataset); });
  });
  document.getElementById('prClose').addEventListener('click', closeModal);
  modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

  /* ---------- promote all ---------- */
  var btn = document.getElementById('promoteAllBtn');
  if (!btn) return;

  var RUN_URL   = '<?= site_url('promotion_run_all') ?>';
  var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  var NEXT_NAME = '<?= html_escape($nextName) ?>';
  var TOTAL     = <?= (int)$pending ?>;

  function notify(ok, msg) {
    if (window.Swal) { Swal.fire({ icon: ok ? 'success' : 'error', title: ok ? 'Done' : 'Error', text: msg }); }
    else { alert(msg); }
  }

  async function run() {
    btn.disabled = true;
    try {
      var fd = new FormData();
      fd.append(CSRF_NAME, CSRF_HASH);
      var res = await (await fetch(RUN_URL, { method: 'POST', body: fd })).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      notify(!!res.status, res.msg);
      if (res.status) { setTimeout(function () { location.reload(); }, 1500); return; }
    } catch (e) {
      notify(false, 'Something went wrong.');
    }
    btn.disabled = false;
  }

  btn.addEventListener('click', function () {
    var text = TOTAL + ' students will be moved to ' + NEXT_NAME + '. This can be done only once.';
    if (window.Swal) {
      Swal.fire({ title: 'Promote all classes?', text: text, icon: 'question', showCancelButton: true,
                  confirmButtonText: 'Yes, promote all', cancelButtonText: 'Cancel' })
          .then(function (r) { if (r.isConfirmed) run(); });
    } else if (confirm('Promote all classes?\n\n' + text)) {
      run();
    }
  });
})();
</script>