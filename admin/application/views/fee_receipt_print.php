<?php
/* Printable fee receipt.  $r = receipt (student, lines, user_name, amYear) */
$s = $r->student;
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Receipt <?= html_escape($r->frReceiptNo) ?></title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;color:#111;margin:0;background:#f1f5f9}
  .sheet{max-width:760px;margin:20px auto;background:#fff;padding:28px 32px;border:1px solid #cbd5e1}
  .head{text-align:center;border-bottom:2px solid #111;padding-bottom:10px;margin-bottom:14px}
  .head h1{margin:0;font-size:22px;letter-spacing:.5px}
  .head p{margin:3px 0 0;font-size:13px}
  .title{display:inline-block;margin-top:8px;padding:3px 14px;border:1px solid #111;font-weight:700;font-size:13px;letter-spacing:1px}
  .meta{display:grid;grid-template-columns:1fr 1fr;gap:4px 24px;font-size:13.5px;margin-bottom:14px}
  .meta b{display:inline-block;min-width:110px}
  table{width:100%;border-collapse:collapse;font-size:13.5px}
  th,td{border:1px solid #333;padding:6px 8px;text-align:left}
  th{background:#f1f5f9}
  .num{text-align:right;white-space:nowrap}
  tfoot td{font-weight:700}
  .words{margin:12px 0;font-size:13.5px}
  .sign{display:flex;justify-content:space-between;margin-top:46px;font-size:13px}
  .cancel{color:#b91c1c;border:2px solid #b91c1c;padding:6px 10px;text-align:center;font-weight:700;margin-bottom:12px}
  .tools{text-align:center;margin:16px}
  .tools button{padding:8px 18px;font-size:14px;cursor:pointer}
  @media print{body{background:#fff}.sheet{margin:0;border:0;max-width:none}.tools{display:none}}
</style>
</head>
<body>
<div class="tools"><button onclick="window.print()">Print</button> <button onclick="window.close()">Close</button></div>
<div class="sheet">
  <div class="head">
    <h1>Little Hearts School</h1>
    <p>Academic Year <?= html_escape($r->amYear) ?></p>
    <span class="title">FEE RECEIPT</span>
  </div>

  <?php if ((int)$r->frCancelled === 1): ?>
    <div class="cancel">CANCELLED on <?= $dmy($r->frCancelledAt) ?> — <?= html_escape($r->frCancelReason) ?></div>
  <?php endif; ?>

  <div class="meta">
    <div><b>Receipt No</b>: <?= html_escape($r->frReceiptNo) ?></div>
    <div><b>Date</b>: <?= $dmy($r->frDate) ?></div>
    <div><b>Student</b>: <?= html_escape($s ? $s->smName : '') ?></div>
    <div><b>Admission No</b>: <?= html_escape($s ? $s->smAdmissionNo : '') ?></div>
    <div><b>Class</b>: <?= html_escape($s ? trim($s->cmName . ' ' . $s->dmName) : '') ?></div>
    <div><b>Payment Mode</b>: <?= html_escape($r->frMode) ?><?= $r->frReference ? ' (' . html_escape($r->frReference) . ')' : '' ?></div>
  </div>

  <table>
    <thead><tr><th>#</th><th>Particulars</th><th>Period</th><th class="num">Amount (₹)</th><th class="num">Fine (₹)</th><th class="num">Total (₹)</th></tr></thead>
    <tbody>
    <?php foreach ($r->lines as $i => $l): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= html_escape($l->fmName) ?><?= ((int)$l->frdAmId !== (int)$r->frAmId) ? ' <small>(' . html_escape($l->amYear) . ')</small>' : '' ?></td>
        <td><?= html_escape($l->sfdPeriod) ?></td>
        <td class="num"><?= fee_money($l->frdAmount) ?></td>
        <td class="num"><?= (float)$l->frdFine ? fee_money($l->frdFine) : '-' ?></td>
        <td class="num"><?= fee_money($l->frdAmount + $l->frdFine) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot><tr><td colspan="3" class="num">Total</td><td class="num"><?= fee_money($r->frAmount) ?></td><td class="num"><?= fee_money($r->frFine) ?></td><td class="num"><?= fee_money($r->frTotal) ?></td></tr></tfoot>
  </table>

  <div class="words"><b>Amount in words:</b> <?= html_escape(fee_words($r->frTotal)) ?></div>
  <?php if ($r->frRemarks): ?><div class="words"><b>Remarks:</b> <?= html_escape($r->frRemarks) ?></div><?php endif; ?>

  <div class="sign">
    <div>Collected by: <?= html_escape($r->user_name) ?></div>
    <div>Signature</div>
  </div>
</div>
<script>window.addEventListener('load', function () { if (location.hash !== '#noprint') setTimeout(function () { window.print(); }, 300); });</script>
</body>
</html>
