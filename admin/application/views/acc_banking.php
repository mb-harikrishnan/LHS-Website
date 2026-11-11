<?php /* $accounts (cash / bank ledgers with balance, uncleared), $as_of, $perm */
$cash = 0; $bank = 0;
foreach ($accounts as $a) { if ($a->alType === 'CASH') $cash += $a->balance; else $bank += $a->balance; }
$drcr = function ($n) { return fee_money(abs($n)) . ($n < 0 ? ' Cr' : ''); };
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Banking</h1><p class="page-sub">Cash and bank balances as per books on <?= date('d-m-Y', strtotime($as_of)) ?>. Reconcile bank accounts against the bank statement.</p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('acc_voucher_new') ?>?type=CONTRA">Deposit / Withdraw / Transfer</a>
      <a class="btn btn-primary" href="<?= site_url('acc_ledgers') ?>">+ Add Bank Account</a>
    </div>
  </div>
  <div class="card">
    <div class="sum-grid">
      <div class="sum-item ok"><small>Cash in hand</small><strong>₹<?= $drcr($cash) ?></strong></div>
      <div class="sum-item"><small>Bank balance (books)</small><strong>₹<?= $drcr($bank) ?></strong></div>
      <div class="sum-item"><small>Total</small><strong>₹<?= $drcr($cash + $bank) ?></strong></div>
    </div>
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('acc_banking') ?>">
        <div class="fb"><label for="asof">As on</label><input class="field-input" type="date" id="asof" name="as_of" value="<?= html_escape($as_of) ?>"></div>
        <button type="submit" class="btn btn-primary">Show</button>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Account</th><th>Type</th><th>Bank / Account No</th><th class="num">Balance</th><th class="num">Not yet cleared</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($accounts as $a): ?>
          <tr class="<?= $a->alActive ? '' : 'muted' ?>">
            <td><strong><?= html_escape($a->alName) ?></strong><?= $a->alActive ? '' : ' <span class="badge badge-gray">Inactive</span>' ?></td>
            <td><?= $a->alType === 'BANK' ? 'Bank' : 'Cash' ?></td>
            <td class="small"><?= html_escape(trim($a->alBankName . ' ' . $a->alAccountNo . ($a->alIfsc ? ' · ' . $a->alIfsc : ''))) ?></td>
            <td class="num"><strong>₹<?= $drcr($a->balance) ?></strong></td>
            <td class="num"><?= $a->alType === 'BANK' ? ($a->uncleared ? '<span class="badge badge-amber">' . (int)$a->uncleared . ' entries</span>' : '<span class="badge badge-green">All cleared</span>') : '—' ?></td>
            <td><div class="act-row">
              <a class="act-btn dl" href="<?= site_url($a->alType === 'BANK' ? 'reports/acc_bank_book' : 'reports/acc_cash_book') ?>?ledger=<?= (int)$a->alId ?>">Book</a>
              <?php if ($a->alType === 'BANK'): ?><a class="act-btn ed" href="<?= site_url('acc_reconcile/' . (int)$a->alId) ?>?as_of=<?= html_escape($as_of) ?>">Reconcile</a><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$accounts): ?><div class="empty">No cash or bank accounts.</div><?php endif; ?>
    </div>
  </div>
</main>
