<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Variables the controller can pass (all optional, sample data is used otherwise):
 *   $children  array  all children of the parent (id, name, class, division) - used for the switcher tabs
 *   $child     array  the child being shown (id, name, class, division, roll_no)
 *   $fees      array  each: id, title, due_date (Y-m-d), amount, paid
 *   $payments  array  each: receipt_no, date (Y-m-d), title, mode, amount, status (Success|Failed|Pending)
 *   $academic_year string e.g. '2026 - 27'
 */
$academic_year = isset($academic_year) ? $academic_year : '2026 - 27';

$children = isset($children) ? $children : array(
    array('id' => 1, 'name' => 'Aarya Ramesh',   'class' => '5', 'division' => 'A'),
    array('id' => 2, 'name' => 'Adithya Ramesh', 'class' => '2', 'division' => 'B'),
);
$child = isset($child) ? $child : array('id' => 1, 'name' => 'Aarya Ramesh', 'class' => '5', 'division' => 'A', 'roll_no' => 12);

$fees = isset($fees) ? $fees : array(
    array('id' => 1, 'title' => 'Admission / Annual Fee',   'due_date' => '2026-06-05', 'amount' => 8000,  'paid' => 8000),
    array('id' => 2, 'title' => 'Tuition Fee - Term 1',     'due_date' => '2026-06-10', 'amount' => 12000, 'paid' => 12000),
    array('id' => 3, 'title' => 'Tuition Fee - Term 2',     'due_date' => '2026-09-10', 'amount' => 12000, 'paid' => 12000),
    array('id' => 4, 'title' => 'Transport Fee - Nov',      'due_date' => '2026-11-05', 'amount' => 1500,  'paid' => 0),
    array('id' => 5, 'title' => 'Tuition Fee - Term 3',     'due_date' => '2026-11-15', 'amount' => 12000, 'paid' => 6000),
    array('id' => 6, 'title' => 'Tuition Fee - Term 4',     'due_date' => '2027-02-10', 'amount' => 12000, 'paid' => 0),
);

$payments = isset($payments) ? $payments : array(
    array('receipt_no' => 'RCP-10452', 'date' => '2026-11-02', 'title' => 'Tuition Fee - Term 3', 'mode' => 'UPI',          'amount' => 6000,  'status' => 'Success'),
    array('receipt_no' => 'RCP-09871', 'date' => '2026-09-08', 'title' => 'Tuition Fee - Term 2', 'mode' => 'Net Banking',  'amount' => 12000, 'status' => 'Success'),
    array('receipt_no' => 'RCP-09870', 'date' => '2026-09-08', 'title' => 'Tuition Fee - Term 2', 'mode' => 'Card',         'amount' => 12000, 'status' => 'Failed'),
    array('receipt_no' => 'RCP-08215', 'date' => '2026-06-07', 'title' => 'Tuition Fee - Term 1', 'mode' => 'Cash',         'amount' => 12000, 'status' => 'Success'),
    array('receipt_no' => 'RCP-08102', 'date' => '2026-06-03', 'title' => 'Admission / Annual Fee', 'mode' => 'Cheque',     'amount' => 8000,  'status' => 'Success'),
);

/* Indian number format: 1,25,000 */
if (!function_exists('pf_money')) {
    function pf_money($n)
    {
        $n = (int) round($n);
        $s = (string) abs($n);
        if (strlen($s) > 3) {
            $last = substr($s, -3);
            $rest = substr($s, 0, -3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $s = $rest . ',' . $last;
        }
        return "\u{20B9} " . ($n < 0 ? '-' : '') . $s;
    }
}

$today = date('Y-m-d');
$total = $paid = $due = 0;
$overdue_total = 0;
$next_due = null;

foreach ($fees as $k => $f) {
    $balance = max(0, $f['amount'] - $f['paid']);
    if ($balance <= 0)                 $status = 'paid';
    elseif ($f['due_date'] < $today)   $status = 'overdue';
    elseif ($f['paid'] > 0)            $status = 'partial';
    else                               $status = 'pending';

    $fees[$k]['balance'] = $balance;
    $fees[$k]['status']  = $status;

    $total += $f['amount'];
    $paid  += min($f['paid'], $f['amount']);
    $due   += $balance;
    if ($status === 'overdue') $overdue_total += $balance;
    if ($balance > 0 && ($next_due === null || $f['due_date'] < $next_due)) $next_due = $f['due_date'];
}
$percent_paid = $total > 0 ? round(($paid / $total) * 100) : 0;

$status_label = array('paid' => 'Paid', 'partial' => 'Part paid', 'pending' => 'Pending', 'overdue' => 'Overdue');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .pf {
        --pf-ink: #1e1b4b; --pf-primary: #4338ca; --pf-muted: #64748b; --pf-border: #dcdcf5;
        --ok: #16a34a; --ok-bg: #e3f7ec; --bad: #e11d48; --bad-bg: #ffe4e8;
        --warn: #d97706; --warn-bg: #fff1d6; --info: #4338ca; --info-bg: #eceaff;
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: var(--pf-ink); max-width: 1200px; margin: 0 auto; padding: 16px; box-sizing: border-box;
    }
    .pf *, .pf *::before, .pf *::after { box-sizing: inherit; }
    .pf a { text-decoration: none; color: inherit; }

    .pf-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .pf-top h1 { margin: 0; font-size: 26px; font-weight: 700; }
    .pf-top p  { margin: 4px 0 0; font-size: 14px; color: var(--pf-muted); }
    .pf-back { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border: 1px solid var(--pf-border);
               border-radius: 8px; font-weight: 600; font-size: 14px; color: var(--pf-primary); background: #fff; }
    .pf-back:hover, .pf-back:focus-visible { background: #f1f0ff; }

    /* Child switcher */
    .pf-tabs { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
    .pf-tab { padding: 9px 18px; border: 1px solid var(--pf-border); border-radius: 999px; background: #fff; font-size: 14px; font-weight: 600; color: var(--pf-muted); }
    .pf-tab:hover, .pf-tab:focus-visible { background: #f1f0ff; }
    .pf-tab--on { background: var(--pf-primary); border-color: var(--pf-primary); color: #fff; }
    .pf-tab--on:hover { background: var(--pf-primary); }

    /* Summary */
    .pf-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 16px; }
    .pf-stat { display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 12px; }
    .pf-stat i { flex: none; width: 48px; height: 48px; border-radius: 50%; display: grid; place-items: center; font-size: 20px; }
    .pf-stat b { display: block; font-size: 22px; line-height: 1.15; }
    .pf-stat span { font-size: 13px; color: var(--pf-muted); }
    .pf-stat--total { background: #eef0ff; } .pf-stat--total i { background: #cfd4ff; color: #3730a3; }
    .pf-stat--paid  { background: var(--ok-bg); } .pf-stat--paid i { background: #bdeacf; color: var(--ok); }
    .pf-stat--due   { background: var(--bad-bg); } .pf-stat--due i { background: #fecdd3; color: var(--bad); }
    .pf-stat--next  { background: var(--warn-bg); } .pf-stat--next i { background: #fde1a6; color: var(--warn); }

    /* Pay banner */
    .pf-pay {
        display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
        padding: 18px 22px; margin-bottom: 16px; border-radius: 14px; color: #fff;
        background: linear-gradient(110deg, #4f46e5, #7c5cf0);
    }
    .pf-pay h2 { margin: 0 0 4px; font-size: 18px; }
    .pf-pay p  { margin: 0; font-size: 14px; color: #e3e1ff; }
    .pf-pay__bar { height: 8px; width: 260px; max-width: 100%; margin-top: 12px; background: rgba(255,255,255,.28); border-radius: 4px; overflow: hidden; }
    .pf-pay__bar i { display: block; height: 100%; background: #fff; border-radius: 4px; }
    .pf-pay__btn { padding: 12px 26px; border-radius: 10px; background: #fff; color: var(--pf-primary); font-weight: 700; font-size: 15px; white-space: nowrap; }
    .pf-pay__btn:hover, .pf-pay__btn:focus-visible { background: #f1f0ff; }
    .pf-pay__btn i { margin-right: 8px; }
    .pf-clear { display: flex; align-items: center; gap: 10px; font-weight: 600; }

    /* Cards and tables */
    .pf-card { background: #fff; border: 1px solid var(--pf-border); border-radius: 14px; padding: 18px 20px; margin-bottom: 16px; }
    .pf-card__head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px; }
    .pf-card__head h2 { margin: 0; font-size: 18px; font-weight: 700; }
    .pf-card__head small { color: var(--pf-muted); font-size: 13px; }
    .pf-scroll { overflow-x: auto; }
    .pf-table { width: 100%; border-collapse: collapse; font-size: 14px; min-width: 640px; }
    .pf-table th { text-align: left; padding: 10px 12px; background: #f4f4fb; color: var(--pf-muted); font-weight: 600; font-size: 13px; }
    .pf-table th:first-child { border-radius: 8px 0 0 8px; } .pf-table th:last-child { border-radius: 0 8px 8px 0; }
    .pf-table td { padding: 13px 12px; border-bottom: 1px solid #eceefb; vertical-align: middle; }
    .pf-table tr:last-child td { border-bottom: 0; }
    .pf-num { text-align: right !important; white-space: nowrap; }
    .pf-table td.pf-title { font-weight: 600; }
    .pf-table tfoot td { font-weight: 700; background: #fafaff; border-top: 2px solid var(--pf-border); border-bottom: 0; }

    .pf-badge { display: inline-block; padding: 4px 11px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .pf-badge--paid, .pf-badge--success { background: var(--ok-bg);   color: var(--ok); }
    .pf-badge--overdue, .pf-badge--failed { background: var(--bad-bg); color: var(--bad); }
    .pf-badge--partial, .pf-badge--pending { background: var(--warn-bg); color: var(--warn); }

    .pf-act { display: inline-block; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; }
    .pf-act--pay { background: var(--pf-primary); color: #fff; }
    .pf-act--pay:hover, .pf-act--pay:focus-visible { background: #3730a3; }
    .pf-act--dl  { background: #ddd8fb; color: #2e2a7a; }
    .pf-act--dl:hover, .pf-act--dl:focus-visible { background: #cbc4f7; }
    .pf-act i { margin-right: 5px; font-size: 12px; }
    .pf-dash { color: #aab0c6; }
    .pf-empty { padding: 28px; text-align: center; color: var(--pf-muted); }

    @media (max-width: 960px) { .pf-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px) {
        .pf-stats { grid-template-columns: 1fr; }
        .pf-pay__btn { width: 100%; text-align: center; }
    }
</style>

<div class="pf">

    <!-- Header -->
    <div class="pf-top">
        <div>
            <h1>Fees &amp; Payments</h1>
            <p>Academic year <?= html_escape($academic_year); ?></p>
        </div>
        <a class="pf-back" href="<?= site_url('parent/dashboard'); ?>"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <!-- Child switcher -->
    <?php if (count($children) > 1): ?>
    <nav class="pf-tabs" aria-label="Choose child">
        <?php foreach ($children as $c): ?>
            <a class="pf-tab<?= (int) $c['id'] === (int) $child['id'] ? ' pf-tab--on' : ''; ?>" href="<?= site_url('parent/fees/' . $c['id']); ?>">
                <?= html_escape($c['name']); ?> (<?= html_escape($c['class'] . '-' . $c['division']); ?>)
            </a>
        <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <!-- Summary -->
    <section class="pf-stats">
        <div class="pf-stat pf-stat--total"><i class="fa-solid fa-file-invoice"></i><div><b><?= pf_money($total); ?></b><span>Total fees</span></div></div>
        <div class="pf-stat pf-stat--paid"><i class="fa-solid fa-circle-check"></i><div><b><?= pf_money($paid); ?></b><span>Paid</span></div></div>
        <div class="pf-stat pf-stat--due"><i class="fa-solid fa-hourglass-half"></i><div><b><?= pf_money($due); ?></b><span>Balance due</span></div></div>
        <div class="pf-stat pf-stat--next"><i class="fa-regular fa-calendar"></i><div><b><?= $next_due ? date('d M Y', strtotime($next_due)) : '-'; ?></b><span>Next due date</span></div></div>
    </section>

    <!-- Pay banner -->
    <section class="pf-pay">
        <div>
            <?php if ($due > 0): ?>
                <h2><?= pf_money($due); ?> to pay for <?= html_escape($child['name']); ?></h2>
                <p><?= $overdue_total > 0 ? pf_money($overdue_total) . ' of this is overdue. ' : ''; ?>Pay online with UPI, card or net banking.</p>
            <?php else: ?>
                <h2 class="pf-clear"><i class="fa-solid fa-circle-check"></i> All fees are paid</h2>
                <p>There is nothing pending for <?= html_escape($child['name']); ?>. Thank you!</p>
            <?php endif; ?>
            <div class="pf-pay__bar" title="<?= $percent_paid; ?>% paid"><i style="width:<?= $percent_paid; ?>%"></i></div>
        </div>
        <?php if ($due > 0): ?>
            <a class="pf-pay__btn" href="<?= site_url('parent/pay/' . $child['id']); ?>"><i class="fa-solid fa-lock"></i>Pay <?= pf_money($due); ?></a>
        <?php endif; ?>
    </section>

    <!-- Fee structure -->
    <section class="pf-card">
        <div class="pf-card__head">
            <h2>Fee details</h2>
            <small><?= $percent_paid; ?>% paid</small>
        </div>
        <div class="pf-scroll">
            <table class="pf-table">
                <thead>
                    <tr>
                        <th>Fee</th><th>Due date</th>
                        <th class="pf-num">Amount</th><th class="pf-num">Paid</th><th class="pf-num">Balance</th>
                        <th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($fees as $f): ?>
                    <tr>
                        <td class="pf-title"><?= html_escape($f['title']); ?></td>
                        <td><?= date('d M Y', strtotime($f['due_date'])); ?></td>
                        <td class="pf-num"><?= pf_money($f['amount']); ?></td>
                        <td class="pf-num"><?= pf_money($f['paid']); ?></td>
                        <td class="pf-num"><?= $f['balance'] > 0 ? '<b>' . pf_money($f['balance']) . '</b>' : '<span class="pf-dash">-</span>'; ?></td>
                        <td><span class="pf-badge pf-badge--<?= $f['status']; ?>"><?= $status_label[$f['status']]; ?></span></td>
                        <td class="pf-num">
                            <?php if ($f['balance'] > 0): ?>
                                <a class="pf-act pf-act--pay" href="<?= site_url('parent/pay/' . $child['id'] . '/' . $f['id']); ?>">Pay now</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="pf-num"><?= pf_money($total); ?></td>
                        <td class="pf-num"><?= pf_money($paid); ?></td>
                        <td class="pf-num"><?= pf_money($due); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <!-- Payment history -->
    <section class="pf-card">
        <div class="pf-card__head">
            <h2>Payment history</h2>
            <small><?= count($payments); ?> transaction<?= count($payments) === 1 ? '' : 's'; ?></small>
        </div>
        <?php if (empty($payments)): ?>
            <div class="pf-empty">No payments have been made yet.</div>
        <?php else: ?>
        <div class="pf-scroll">
            <table class="pf-table">
                <thead>
                    <tr><th>Receipt no.</th><th>Date</th><th>Paid for</th><th>Mode</th><th class="pf-num">Amount</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td class="pf-title"><?= html_escape($p['receipt_no']); ?></td>
                        <td><?= date('d M Y', strtotime($p['date'])); ?></td>
                        <td><?= html_escape($p['title']); ?></td>
                        <td><?= html_escape($p['mode']); ?></td>
                        <td class="pf-num"><?= pf_money($p['amount']); ?></td>
                        <td><span class="pf-badge pf-badge--<?= strtolower($p['status']); ?>"><?= html_escape($p['status']); ?></span></td>
                        <td class="pf-num">
                            <?php if ($p['status'] === 'Success'): ?>
                                <a class="pf-act pf-act--dl" href="<?= site_url('receipt/' . $p['receipt_no']); ?>"><i class="fa-solid fa-download"></i>Receipt</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>

</div>