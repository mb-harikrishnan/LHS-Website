<?php
/* Fee Allocation: allot one fee to the students of a class (+ division)
   $fees, $classes, $div_map, $filters, $fee, $students, $default_amount, $perm */
$can_save = !empty($perm['can_add']) || !empty($perm['can_edit']);
?>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Fee Allocation</h1>
      <p class="page-sub">Allot a fee to the students of a class. Fees can also be selected while adding or editing a student.</p>
    </div>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('fee_allocation') ?>">
        <div class="fb"><label for="fee">Fee</label>
          <select class="field-input" name="fee" id="fee" required>
            <option value="">Select Fee</option>
            <?php foreach ($fees as $f): ?>
              <option value="<?= (int)$f->fmId ?>" <?= (int)$filters['fee'] === (int)$f->fmId ? 'selected' : '' ?>><?= html_escape($f->fmName) ?><?= $f->fmActive ? '' : ' (inactive)' ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="fb"><label for="cls">Class</label>
          <select class="field-input" name="class" id="cls" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= (int)$c->cmId ?>" <?= (string)$filters['class'] === (string)$c->cmId ? 'selected' : '' ?>><?= html_escape($c->cmName) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="fb"><label for="div">Division</label>
          <select class="field-input" name="division" id="div"><option value="">All Divisions</option></select></div>
        <button type="submit" class="btn btn-primary">Load Students</button>
      </form>
    </div>

    <?php if (!$fee || $filters['class'] === ''): ?>
      <div class="notice">Select a fee and a class to list the students.</div>
    <?php elseif (empty($students)): ?>
      <div class="notice">No students found in this class for the current academic year.</div>
    <?php else: ?>
      <form id="allocForm">
        <input type="hidden" name="fee" value="<?= (int)$fee->fmId ?>">
        <div class="table-toolbar" style="justify-content:space-between">
          <div>
            <strong><?= html_escape($fee->fmName) ?></strong>
            <span class="muted small">· default for this class ₹<?= fee_money($default_amount) ?> per installment × <?= (int)$fee->fmInstallments ?></span>
          </div>
          <div class="act-row">
            <input class="field-input" id="bulkAmt" style="width:120px" inputmode="decimal" value="<?= html_escape($default_amount) ?>" aria-label="Amount">
            <button type="button" class="btn btn-secondary btn-sm" id="applyAmt">Set amount for selected</button>
          </div>
        </div>
        <div class="table-wrap">
          <table class="table compact">
            <thead><tr>
              <th style="width:40px"><input type="checkbox" id="checkAll" aria-label="Select all"></th>
              <th>#</th><th>Adm No</th><th>Name</th><th>Division</th><th class="num">Amount / Inst.</th><th class="num">Paid</th><th>Status</th>
            </tr></thead>
            <tbody>
            <?php foreach ($students as $i => $s):
              $on = !empty($s->sfaId);
              $locked = $on && $s->paid > 0; ?>
              <tr>
                <td>
                  <input type="hidden" name="listed[]" value="<?= (int)$s->smId ?>">
                  <input type="checkbox" class="pick" data-id="<?= (int)$s->smId ?>" <?= $on ? 'checked' : '' ?> <?= $locked ? 'disabled title="Fee already paid; cannot remove"' : '' ?>>                </td>
                <td><?= $i + 1 ?></td>
                <td><?= html_escape($s->smAdmissionNo) ?></td>
                <td><?= html_escape($s->smName) ?></td>
                <td><?= html_escape($s->dmName) ?></td>
                <td class="num"><input class="cell-input amt" data-id="<?= (int)$s->smId ?>" inputmode="decimal"
                     value="<?= html_escape($on ? $s->sfaAmount : $default_amount) ?>"></td>
                <td class="num"><?= $s->paid > 0 ? '₹' . fee_money($s->paid) : '—' ?></td>
                <td><?= $on ? '<span class="badge badge-green">Allotted</span>' : '<span class="badge badge-gray">Not allotted</span>' ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="table-foot">
          <span class="muted small">Unticking a student removes the fee and its unpaid installments.</span>
          <?php if ($can_save): ?><button type="submit" class="btn btn-primary" id="saveBtn">Save Allocation</button><?php endif; ?>
        </div>
      </form>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const DIV_MAP = <?= json_encode($div_map) ?>;
  const SEL_DIV = <?= json_encode((string)$filters['division']) ?>;
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function fillDiv(v) {
    const list = DIV_MAP[$('cls').value] || [];
    $('div').innerHTML = '<option value="">All Divisions</option>' + list.map(d => `<option value="${esc(d.id)}">${esc(d.name)}</option>`).join('');
    $('div').value = v || '';
  }
  $('cls').addEventListener('change', () => fillDiv(''));
  fillDiv(SEL_DIV);

  const form = $('allocForm');
  if (!form) return;
  const picks = () => Array.from(form.querySelectorAll('.pick:not(:disabled)'));
  $('checkAll').addEventListener('change', e => picks().forEach(p => p.checked = e.target.checked));
  $('applyAmt').addEventListener('click', () => {
    const v = $('bulkAmt').value.trim();
    form.querySelectorAll('.pick:checked').forEach(p => {
      const inp = form.querySelector('.amt[data-id="' + p.dataset.id + '"]'); if (inp) inp.value = v;
    });
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('fee', form.querySelector('[name=fee]').value);
    form.querySelectorAll('[name="listed[]"]').forEach(i => fd.append('listed[]', i.value));
    let bad = false;
    form.querySelectorAll('.pick:checked').forEach(p => {
      const v = form.querySelector('.amt[data-id="' + p.dataset.id + '"]').value.trim();
      if (!/^\d+(\.\d{1,2})?$/.test(v)) bad = true;
      fd.append('amount[' + p.dataset.id + ']', v);
    });
    if (bad) return Swal.fire({icon: 'error', title: 'Check amounts', text: 'Enter a valid amount for every selected student.'});
    fd.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');
    $('saveBtn').disabled = true;
    try {
      const res = await (await fetch('<?= site_url('fee_allocation_save') ?>', {method: 'POST', body: fd})).json();
      Swal.fire({icon: res.status ? 'success' : 'error', title: res.status ? 'Saved' : 'Error', text: res.msg})
        .then(() => { if (res.status) location.reload(); });
    } catch (err) { Swal.fire({icon: 'error', title: 'Error', text: 'Something went wrong.'}); }
    finally { $('saveBtn').disabled = false; }
  });
})();
</script>
