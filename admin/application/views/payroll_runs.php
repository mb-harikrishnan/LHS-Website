<?php /* $runs, $perm, $next (YYYY-MM), $on_payroll */
$can_add = !empty($perm['can_add']); ?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Monthly Payroll</h1>
      <p class="page-sub">Prepare the payroll of a month, enter loss-of-pay days, approve it and pay salaries. <?= (int)$on_payroll ?> staff on payroll this month.</p></div>
    <div class="page-actions">
      <a class="btn btn-secondary" href="<?= site_url('pay_staff') ?>">Salary Structure</a>
      <?php if ($can_add): ?><button class="btn btn-primary" type="button" id="openNew">+ Prepare Payroll</button><?php endif; ?>
    </div>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Month</th><th class="num">Staff</th><th class="num">Gross</th><th class="num">Deductions</th><th class="num">Net Pay</th><th class="num">Paid</th><th class="num">Balance</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($runs as $r): $bal = round($r->net - $r->paid, 2); ?>
          <tr>
            <td><a href="<?= site_url('payroll/' . (int)$r->prId) ?>"><strong><?= html_escape(Payroll_model::month_label($r->prMonth)) ?></strong></a></td>
            <td class="num"><?= (int)$r->staff ?></td>
            <td class="num"><?= fee_money($r->gross) ?></td>
            <td class="num"><?= fee_money($r->deduction) ?></td>
            <td class="num"><strong><?= fee_money($r->net) ?></strong></td>
            <td class="num"><?= fee_money($r->paid) ?></td>
            <td class="num <?= $bal > 0 && $r->prStatus === 'APPROVED' ? 'warn-txt' : '' ?>"><?= fee_money($bal) ?></td>
            <td><?php if ($r->prStatus === 'DRAFT'): ?><span class="badge badge-amber">Draft</span>
                <?php elseif ($bal <= 0): ?><span class="badge badge-green">Paid</span>
                <?php elseif ($r->paid > 0): ?><span class="badge badge-blue">Part paid</span>
                <?php else: ?><span class="badge badge-purple">Approved</span><?php endif; ?></td>
            <td><a class="act-btn ed" href="<?= site_url('payroll/' . (int)$r->prId) ?>">Open</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$runs): ?><div class="empty">No payroll yet. Set salaries in Salary Structure, then prepare the payroll of a month.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="nModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true" aria-labelledby="nTitle">
    <div class="modal-head"><h2 id="nTitle">Prepare Payroll</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="nForm" novalidate>
      <div class="modal-body">
        <div style="margin-top:12px"><label class="field-label" for="nMonth">Month <span class="req">*</span></label>
          <input class="field-input" type="month" id="nMonth" value="<?= html_escape($next) ?>"></div>
        <p class="small muted">A payslip is made for every staff member on payroll that month, from their current salary structure. Days before joining or after relieving are counted as loss of pay. You can change anything before approving.</p>
        <p class="field-error" id="nErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary" id="nBtn">Prepare</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const btn = $('openNew'); if (!btn) return;
  const m = U.modal('nModal');
  btn.addEventListener('click', () => { $('nErr').hidden = true; m.show(); $('nMonth').focus(); });
  $('nForm').addEventListener('submit', async e => {
    e.preventDefault();
    $('nBtn').disabled = true;
    const fd = new FormData(); fd.append('month', $('nMonth').value);
    const res = await U.post('<?= site_url('payroll_create') ?>', fd).catch(() => ({status: false, msg: 'Could not reach the server. Reload the page and try again.'}));
    $('nBtn').disabled = false;
    if (!res.status) { $('nErr').textContent = res.msg; $('nErr').hidden = false; return; }
    location.href = res.url;
  });
})();
</script>
