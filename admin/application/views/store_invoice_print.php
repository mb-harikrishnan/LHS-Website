<?php /* Printable store invoice.  $v (items, payments, paid, balance), $user_name */
$dmy = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; };
$hasTax = (float)$v->sinTax > 0;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invoice <?= html_escape($v->sinNo) ?></title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;color:#111;margin:0;background:#f1f5f9}
  .sheet{max-width:760px;margin:20px auto;background:#fff;padding:28px 32px;border:1px solid #cbd5e1}
  .head{text-align:center;border-bottom:2px solid #111;padding-bottom:10px;margin-bottom:14px}
  .head h1{margin:0;font-size:22px}.head p{margin:3px 0 0;font-size:13px}
  .title{display:inline-block;margin-top:8px;padding:3px 14px;border:1px solid #111;font-weight:700;font-size:13px;letter-spacing:1px}
  .meta{display:grid;grid-template-columns:1fr 1fr;gap:4px 24px;font-size:13.5px;margin-bottom:14px}
  .meta b{display:inline-block;min-width:100px}
  table{width:100%;border-collapse:collapse;font-size:13.5px}
  th,td{border:1px solid #333;padding:6px 8px;text-align:left}
  th{background:#f1f5f9}.num{text-align:right;white-space:nowrap}
  .tot td{font-weight:700}
  .words{margin:12px 0;font-size:13.5px}
  .pay{margin-top:14px;font-size:13px}
  .sign{display:flex;justify-content:space-between;margin-top:40px;font-size:13px}
  .cancel{color:#b91c1c;border:2px solid #b91c1c;padding:6px 10px;text-align:center;font-weight:700;margin-bottom:12px}
  .tools{text-align:center;margin:16px}.tools button{padding:8px 18px;font-size:14px;cursor:pointer}
  @media print{body{background:#fff}.sheet{margin:0;border:0;max-width:none}.tools{display:none}}
</style>
</head>
<body>
<div class="tools"><button onclick="window.print()">Print</button> <button onclick="window.close()">Close</button></div>
<div class="sheet">
  <div class="head"><h1>Little Hearts School</h1><p>School Store · Academic Year <?= html_escape($v->amYear) ?></p><span class="title">INVOICE</span></div>
  <?php if ($v->sinCancelled): ?><div class="cancel">CANCELLED on <?= $dmy($v->sinCancelledAt) ?> — <?= html_escape($v->sinCancelReason) ?></div><?php endif; ?>
  <div class="meta">
    <div><b>Invoice No</b>: <?= html_escape($v->sinNo) ?></div>
    <div><b>Date</b>: <?= $dmy($v->sinDate) ?></div>
    <?php if ($v->sinSmId): ?>
      <div><b>Student</b>: <?= html_escape($v->smName) ?></div>
      <div><b>Admission No</b>: <?= html_escape($v->smAdmissionNo) ?></div>
      <div><b>Class</b>: <?= html_escape(trim($v->cmName . ' ' . $v->dmName)) ?></div>
    <?php else: ?>
      <div><b>Customer</b>: <?= html_escape($v->sinCustomer) ?></div>
      <div><b>Phone</b>: <?= html_escape($v->sinPhone) ?></div>
    <?php endif; ?>
  </div>
  <table>
    <thead><tr><th>#</th><th>Item</th><th class="num">Qty</th><th class="num">Rate</th><?php if ($hasTax): ?><th class="num">Tax</th><?php endif; ?><th class="num">Amount (₹)</th></tr></thead>
    <tbody>
    <?php foreach ($v->items as $n => $it): ?>
      <tr><td><?= $n + 1 ?></td><td><?= html_escape($it->siiName) ?></td><td class="num"><?= (float)$it->siiQty ?></td>
        <td class="num"><?= fee_money($it->siiRate) ?></td>
        <?php if ($hasTax): ?><td class="num"><?= (float)$it->siiTaxPercent ? fee_money($it->siiTax) . ' (' . (float)$it->siiTaxPercent . '%)' : '-' ?></td><?php endif; ?>
        <td class="num"><?= fee_money($it->siiAmount + $it->siiTax) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <?php $span = $hasTax ? 5 : 4; ?>
      <?php if ((float)$v->sinDiscount > 0): ?>
        <tr><td colspan="<?= $span ?>" class="num">Sub total</td><td class="num"><?= fee_money($v->sinSubtotal + $v->sinTax) ?></td></tr>
        <tr><td colspan="<?= $span ?>" class="num">Less discount</td><td class="num">-<?= fee_money($v->sinDiscount) ?></td></tr>
      <?php endif; ?>
      <tr class="tot"><td colspan="<?= $span ?>" class="num">Total</td><td class="num"><?= fee_money($v->sinTotal) ?></td></tr>
      <tr><td colspan="<?= $span ?>" class="num">Paid</td><td class="num"><?= fee_money($v->paid) ?></td></tr>
      <tr class="tot"><td colspan="<?= $span ?>" class="num">Balance</td><td class="num"><?= fee_money($v->balance) ?></td></tr>
    </tfoot>
  </table>
  <div class="words"><b>Amount in words:</b> <?= html_escape(fee_words($v->sinTotal)) ?></div>
  <?php if ($v->payments): ?>
    <div class="pay"><b>Payments:</b>
      <?php foreach ($v->payments as $p): ?> <?= $dmy($p->spayDate) ?> — ₹<?= fee_money($p->spayAmount) ?> (<?= html_escape($p->spayMode) ?><?= $p->spayReference ? ' ' . html_escape($p->spayReference) : '' ?>);<?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php if ($v->sinRemarks): ?><div class="pay"><b>Remarks:</b> <?= html_escape($v->sinRemarks) ?></div><?php endif; ?>
  <div class="sign"><div>Billed by: <?= html_escape($user_name) ?></div><div>Signature</div></div>
</div>
<script>window.addEventListener('load', function () { if (location.hash !== '#noprint') setTimeout(function () { window.print(); }, 300); });</script>
</body>
</html>
