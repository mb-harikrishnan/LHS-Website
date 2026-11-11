<?php
/* Financial statement (P&L, Balance Sheet, Receipts & Payments)
   $s: title, subtitle, route, mode (period|asof), from, to, as_of, detail,
       sections [ {title, rows [[label, amount, level, ledgerId]], total_label, total} ], summary [[label, amount, class]] */
$qs = $_GET; unset($qs['export']);
$csv = site_url($s['route']) . '?' . http_build_query(array_merge($qs, array('export' => 'csv')));
?>
<style>
.stmt{display:grid;grid-template-columns:1fr 1fr;gap:0;border-top:1px solid #e2e8f0}
.stmt section{padding:0}
.stmt section + section{border-left:1px solid #e2e8f0}
.stmt h3{margin:0;padding:12px 20px;background:#eef2ff;color:#312e81;font-size:14px;text-transform:uppercase;letter-spacing:.05em}
.stmt table{width:100%;border-collapse:collapse;font-size:13.5px}
.stmt td{padding:7px 20px;border-bottom:1px solid #f1f5f9}
.stmt td.num{text-align:right;white-space:nowrap;font-variant-numeric:tabular-nums}
.stmt tr.l0 td{font-weight:700;color:#0f172a}
.stmt tr.l1 td:first-child{padding-left:38px}
.stmt tr.l2 td:first-child{padding-left:58px;color:#475569}
.stmt tr.l1 td,.stmt tr.l2 td{font-size:13px}
.stmt tr.tot td{font-weight:800;border-top:2px solid #0f172a;background:#f8fafc}
.stmt a{color:inherit;text-decoration:none}.stmt a:hover{text-decoration:underline}
.stmt-sum{display:flex;justify-content:flex-end;gap:12px;padding:14px 20px;border-top:1px solid #e2e8f0;flex-wrap:wrap}
.stmt-sum .pill{padding:8px 14px;border-radius:10px;font-weight:700}
.stmt-sum .ok{background:#ecfdf5;color:#047857}.stmt-sum .bad{background:#fff1f2;color:#be123c}
@media (max-width:900px){.stmt{grid-template-columns:1fr}.stmt section + section{border-left:0;border-top:1px solid #e2e8f0}}
@media print{.stmt{grid-template-columns:1fr 1fr}.stmt td{padding:4px 8px}}
</style>
<main class="page">
  <div class="page-header no-print">
    <div><h1 class="page-title"><?= html_escape($s['title']) ?></h1><p class="page-sub"><?= html_escape($s['subtitle']) ?></p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('reports/accounts') ?>">All Reports</a>
      <a class="btn btn-secondary" href="<?= html_escape($csv) ?>">Excel (CSV)</a>
      <button class="btn btn-primary" type="button" onclick="window.print()">Print</button>
    </div>
  </div>
  <div class="card">
    <div class="table-toolbar no-print">
      <form class="filter-bar" method="get" action="<?= site_url($s['route']) ?>">
        <?php if ($s['mode'] === 'asof'): ?>
          <div class="fb"><label for="asof">As on</label><input class="field-input" type="date" id="asof" name="as_of" value="<?= html_escape($s['as_of']) ?>"></div>
        <?php else: ?>
          <div class="fb"><label for="from">From</label><input class="field-input" type="date" id="from" name="from" value="<?= html_escape($s['from']) ?>"></div>
          <div class="fb"><label for="to">To</label><input class="field-input" type="date" id="to" name="to" value="<?= html_escape($s['to']) ?>"></div>
        <?php endif; ?>
        <div class="fb"><label for="detail">View</label>
          <select class="field-input" id="detail" name="detail">
            <option value="1" <?= $s['detail'] === '1' ? 'selected' : '' ?>>Detailed (accounts)</option>
            <option value="0" <?= $s['detail'] === '0' ? 'selected' : '' ?>>Condensed (groups)</option>
          </select></div>
        <button type="submit" class="btn btn-primary">Show</button>
      </form>
    </div>
    <div class="print-head"><h2>Little Hearts School</h2><p><strong><?= html_escape($s['title']) ?></strong></p><p><?= html_escape($s['subtitle']) ?></p></div>
    <div class="stmt">
      <?php foreach ($s['sections'] as $sec): ?>
        <section>
          <h3><?= html_escape($sec['title']) ?></h3>
          <table>
            <?php foreach ($sec['rows'] as $r): ?>
              <tr class="l<?= (int)$r[2] ?>">
                <td><?= !empty($r[3]) ? '<a href="' . site_url('reports/acc_ledger') . '?ledger=' . (int)$r[3] . '&from=' . html_escape($s['mode'] === 'asof' ? '2000-01-01' : $s['from']) . '&to=' . html_escape($s['mode'] === 'asof' ? $s['as_of'] : $s['to']) . '">' . html_escape($r[0]) . '</a>' : html_escape($r[0]) ?></td>
                <td class="num"><?= fee_money($r[1]) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$sec['rows']): ?><tr><td class="muted">Nothing to show</td><td></td></tr><?php endif; ?>
            <tr class="tot"><td><?= html_escape($sec['total_label']) ?></td><td class="num">₹<?= fee_money($sec['total']) ?></td></tr>
          </table>
        </section>
      <?php endforeach; ?>
    </div>
    <?php if ($s['summary']): ?>
      <div class="stmt-sum"><?php foreach ($s['summary'] as $x): ?><span class="pill <?= html_escape($x[2]) ?>"><?= html_escape($x[0]) ?>: ₹<?= fee_money($x[1]) ?></span><?php endforeach; ?></div>
    <?php endif; ?>
  </div>
</main>
