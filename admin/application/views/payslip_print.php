<?php /* $slips (each with ->lines, prMonth, prStatus) */
$num = function ($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); };
$first = $slips ? $slips[0] : null;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $first ? 'Payslip ' . html_escape(count($slips) === 1 ? $first->psName . ' ' : '') . html_escape(Payroll_model::month_label($first->prMonth)) : 'Payslip' ?></title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;color:#111;margin:0;background:#f1f5f9}
  .sheet{max-width:780px;margin:20px auto;background:#fff;padding:26px 30px;border:1px solid #cbd5e1;position:relative}
  .head{text-align:center;border-bottom:2px solid #111;padding-bottom:10px;margin-bottom:14px}
  .head h1{margin:0;font-size:22px}.head p{margin:3px 0 0;font-size:13px}
  .title{display:inline-block;margin-top:8px;padding:3px 14px;border:1px solid #111;font-weight:700;font-size:13px;letter-spacing:1px}
  .meta{display:grid;grid-template-columns:1fr 1fr;gap:4px 24px;font-size:13px;margin-bottom:14px}
  .meta b{display:inline-block;min-width:110px}
  table{width:100%;border-collapse:collapse;font-size:13px}
  th,td{border:1px solid #333;padding:6px 8px;text-align:left;vertical-align:top}
  th{background:#f1f5f9}.num{text-align:right;white-space:nowrap}
  .tot td{font-weight:700;background:#f8fafc}
  .net{margin:14px 0 4px;display:flex;justify-content:space-between;align-items:center;border:2px solid #111;padding:10px 14px;font-size:17px;font-weight:800}
  .words{font-size:13px;margin-bottom:10px}
  .er{font-size:12px;color:#334155}
  .foot{display:flex;justify-content:space-between;margin-top:36px;font-size:12.5px}
  .draft{position:absolute;top:40%;left:0;right:0;text-align:center;font-size:90px;font-weight:800;color:rgba(220,38,38,.12);transform:rotate(-20deg);pointer-events:none}
  .tools{text-align:center;margin:16px}.tools button{padding:8px 18px;font-size:14px;cursor:pointer}
  @media print{body{background:#fff}.sheet{margin:0 auto;border:0;max-width:none;page-break-after:always}.sheet:last-child{page-break-after:auto}.tools{display:none}}
</style>
</head>
<body>
<div class="tools"><button onclick="window.print()">Print</button> <button onclick="window.close()">Close</button></div>
<?php if (!$slips): ?><div class="sheet">No payslips.</div><?php endif; ?>
<?php foreach ($slips as $s):
  $earn = array(); $ded = array(); $emp = array();
  foreach ($s->lines as $l) {
      if ($l->pslType === 'EARNING') $earn[] = $l; elseif ($l->pslType === 'DEDUCTION') $ded[] = $l; else $emp[] = $l;
  }
  $rows = max(count($earn), count($ded)); ?>
<div class="sheet">
  <?php if ($s->prStatus === 'DRAFT'): ?><div class="draft">DRAFT</div><?php endif; ?>
  <div class="head"><h1>Little Hearts School</h1><p>Salary for <?= html_escape(Payroll_model::month_label($s->prMonth)) ?></p><span class="title">PAYSLIP</span></div>
  <div class="meta">
    <div><b>Name</b>: <?= html_escape($s->psName) ?></div>
    <div><b>Employee code</b>: <?= html_escape($s->psCode ?: '—') ?></div>
    <div><b>Designation</b>: <?= html_escape($s->psDesignation ?: '—') ?></div>
    <div><b>Category</b>: <?= html_escape(Payroll_model::$categories[$s->psCategory]) ?></div>
    <div><b>Days in month</b>: <?= (int)$s->psDays ?></div>
    <div><b>Paid days</b>: <?= $num($s->psPaidDays) ?><?= (float)$s->psLop > 0 ? ' (loss of pay ' . $num($s->psLop) . ')' : '' ?></div>
    <div><b>Paid by</b>: <?= html_escape($s->psPayMode) ?><?= $s->psPayMode === 'Bank' && $s->psAccountNo ? ' · A/c ' . html_escape($s->psAccountNo) : '' ?></div>
    <div><b>Bank / IFSC</b>: <?= html_escape(trim(($s->psBankName ?: '') . ($s->psIfsc ? ' / ' . $s->psIfsc : '')) ?: '—') ?></div>
    <div><b>PAN</b>: <?= html_escape($s->psPan ?: '—') ?></div>
    <div><b>PF UAN / ESI</b>: <?= html_escape(($s->psUan ?: '—') . ' / ' . ($s->psEsiNo ?: '—')) ?></div>
  </div>
  <table>
    <thead><tr><th>Earnings</th><th class="num">Amount (₹)</th><th>Deductions</th><th class="num">Amount (₹)</th></tr></thead>
    <tbody>
    <?php for ($i = 0; $i < $rows; $i++): $e = isset($earn[$i]) ? $earn[$i] : null; $d = isset($ded[$i]) ? $ded[$i] : null; ?>
      <tr>
        <td><?= $e ? html_escape($e->pslName) : '' ?></td><td class="num"><?= $e ? fee_money($e->pslAmount) : '' ?></td>
        <td><?= $d ? html_escape($d->pslName) : '' ?></td><td class="num"><?= $d ? fee_money($d->pslAmount) : '' ?></td>
      </tr>
    <?php endfor; ?>
      <tr class="tot"><td>Gross Earnings</td><td class="num"><?= fee_money($s->psGross) ?></td><td>Total Deductions</td><td class="num"><?= fee_money($s->psDeduction) ?></td></tr>
    </tbody>
  </table>
  <div class="net"><span>Net Pay</span><span>₹ <?= fee_money($s->psNet) ?></span></div>
  <div class="words"><b>In words:</b> <?= html_escape(fee_words($s->psNet)) ?></div>
  <?php if ($emp): ?>
    <div class="er">Paid by the school in addition to the salary:
      <?= html_escape(implode(', ', array_map(function ($l) { return $l->pslName . ' ₹ ' . fee_money($l->pslAmount); }, $emp))) ?></div>
  <?php endif; ?>
  <?php if ($s->psRemarks): ?><div class="er" style="margin-top:6px">Remarks: <?= html_escape($s->psRemarks) ?></div><?php endif; ?>
  <div class="foot"><span>Employee signature</span><span>Principal / Accounts</span></div>
</div>
<?php endforeach; ?>
</body>
</html>
