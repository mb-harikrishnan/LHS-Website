<?php /* $groups, $balances (all ledgers with close), $ledgers (full rows), $perm, $natures, $opening_diff */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']);
$byGroup = array();
foreach ($balances as $b) $byGroup[$b->agId][] = $b;
$children = array(); $tops = array();
foreach ($groups as $g) { if ($g->agParentId) $children[$g->agParentId][] = $g; else $tops[$g->agNature][] = $g; }
$ledgerMeta = array();
foreach ($ledgers as $l) $ledgerMeta[$l->alId] = $l;   // bank details etc.
$drcr = function ($n) { return $n == 0 ? '—' : fee_money(abs($n)) . ($n > 0 ? ' Dr' : ' Cr'); };
$groups_js = array();
foreach ($groups as $g) $groups_js[] = array('id' => (int)$g->agId, 'name' => $g->agName, 'nature' => $g->agNature, 'parent' => (int)$g->agParentId, 'system' => $g->agKey ? 1 : 0);

$renderLedgers = function ($gid, $indent) use ($byGroup, $ledgerMeta, $drcr, $can_edit, $can_del) {
    if (empty($byGroup[$gid])) return;
    foreach ($byGroup[$gid] as $b) {
        $m = $ledgerMeta[$b->alId];
        $row = array('id' => (int)$b->alId, 'name' => $m->alName, 'group' => (int)$m->alAgId, 'code' => (string)$m->alCode, 'type' => $m->alType,
                     'opening' => (float)$m->alOpening, 'opening_type' => $m->alOpeningType, 'bank_name' => (string)$m->alBankName,
                     'account_no' => (string)$m->alAccountNo, 'ifsc' => (string)$m->alIfsc, 'phone' => (string)$m->alPhone,
                     'notes' => (string)$m->alNotes, 'active' => (int)$m->alActive, 'system' => $m->alSystemKey ? 1 : 0);
        echo '<tr data-l="' . html_escape(json_encode($row)) . '"' . ($m->alActive ? '' : ' class="muted"') . '>'
           . '<td style="padding-left:' . (16 + $indent * 22) . 'px">' . html_escape($m->alName)
           . ($m->alType !== 'GENERAL' ? ' <span class="badge badge-info">' . ($m->alType === 'BANK' ? 'Bank' : 'Cash') . '</span>' : '')
           . ($m->alSystemKey ? ' <span class="badge badge-gray" title="Used by automatic posting">Auto</span>' : '')
           . ($m->alActive ? '' : ' <span class="badge badge-gray">Inactive</span>') . '</td>'
           . '<td class="small muted">' . html_escape($m->alCode) . '</td>'
           . '<td class="num small">' . ((float)$m->alOpening ? fee_money($m->alOpening) . ' ' . $m->alOpeningType : '—') . '</td>'
           . '<td class="num"><strong>' . $drcr($b->close) . '</strong></td><td><div class="act-row">'
           . '<a class="act-btn dl" href="' . site_url('reports/acc_ledger') . '?ledger=' . (int)$b->alId . '">Statement</a>'
           . ($can_edit ? '<button class="act-btn ed" type="button" data-edit>Edit</button>' : '')
           . ($can_del && !$m->alSystemKey ? '<button class="act-btn rm" type="button" data-del>Delete</button>' : '')
           . '</div></td></tr>';
    }
};
$groupTotal = function ($gid) use (&$groupTotal, $byGroup, $children) {
    $t = 0;
    if (!empty($byGroup[$gid])) foreach ($byGroup[$gid] as $b) $t += $b->close;
    if (!empty($children[$gid])) foreach ($children[$gid] as $c) $t += $groupTotal($c->agId);
    return $t;
};
?>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Chart of Accounts</h1><p class="page-sub">Groups and accounts with today's balance. "Auto" accounts are used by fee and store posting.</p></div>
    <div class="page-actions">
      <?php if ($can_add): ?>
        <button class="btn btn-secondary" type="button" id="openGroup">+ Group</button>
        <button class="btn btn-primary" type="button" id="openLedger">+ Account</button>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($opening_diff != 0): ?>
    <div class="card" style="padding:12px 20px;margin-bottom:16px;border-color:#fde68a;background:#fffbeb">
      Opening balances differ by <strong><?= $drcr($opening_diff) ?></strong>. Total debit and credit openings should be equal; the difference shows on the Balance Sheet until corrected.
    </div>
  <?php endif; ?>
  <div class="card">
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Account</th><th>Code</th><th class="num">Opening</th><th class="num">Balance Today</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($natures as $nk => $nlabel): if (empty($tops[$nk])) continue; ?>
          <tr class="row-total"><td colspan="5"><?= html_escape($nlabel) ?></td></tr>
          <?php foreach ($tops[$nk] as $g): ?>
            <tr class="row-group" data-g="<?= (int)$g->agId ?>"><td colspan="3"><?= html_escape($g->agName) ?>
              <?php if ($can_edit): ?> <button class="act-btn ed" type="button" data-gedit style="height:24px;margin-left:6px">Edit</button><?php endif; ?></td>
              <td class="num"><?= $drcr($groupTotal($g->agId)) ?></td><td></td></tr>
            <?php if (!empty($children[$g->agId])): foreach ($children[$g->agId] as $c): ?>
              <tr class="row-sub" data-g="<?= (int)$c->agId ?>"><td colspan="3" style="padding-left:30px"><?= html_escape($c->agName) ?>
                <?php if ($can_edit): ?> <button class="act-btn ed" type="button" data-gedit style="height:24px;margin-left:6px">Edit</button><?php endif; ?>
                <?php if ($can_del && !$c->agKey): ?> <button class="act-btn rm" type="button" data-gdel style="height:24px">Delete</button><?php endif; ?></td>
                <td class="num"><?= $drcr($groupTotal($c->agId)) ?></td><td></td></tr>
              <?php $renderLedgers($c->agId, 2); ?>
            <?php endforeach; endif; ?>
            <?php $renderLedgers($g->agId, 1); ?>
          <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<div class="modal-overlay" id="ledModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="ledTitle">
    <div class="modal-head"><h2 id="ledTitle">Add Account</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="ledForm" novalidate>
      <input type="hidden" id="lId" value="0">
      <div class="modal-body">
        <div class="grid g3" style="margin-top:12px">
          <div style="grid-column:span 2"><label class="field-label" for="lName">Account Name <span class="req">*</span></label><input class="field-input" id="lName" maxlength="150"></div>
          <div><label class="field-label" for="lCode">Code</label><input class="field-input" id="lCode" maxlength="20"></div>
          <div style="grid-column:span 2"><label class="field-label" for="lGroup">Group <span class="req">*</span></label><select class="field-input" id="lGroup"></select></div>
          <div><label class="field-label" for="lType">Type</label>
            <select class="field-input" id="lType"><option value="GENERAL">General</option><option value="CASH">Cash</option><option value="BANK">Bank</option></select></div>
          <div><label class="field-label" for="lOpen">Opening Balance (₹)</label><input class="field-input" id="lOpen" inputmode="decimal" placeholder="0"></div>
          <div><label class="field-label" for="lOpenType">Dr / Cr</label><select class="field-input" id="lOpenType"><option>Dr</option><option>Cr</option></select></div>
          <div><label class="field-label" for="lPhone">Phone</label><input class="field-input" id="lPhone" maxlength="20"></div>
        </div>
        <div class="grid g3" id="bankBox" hidden style="margin-top:12px">
          <div><label class="field-label" for="lBank">Bank Name</label><input class="field-input" id="lBank" maxlength="100"></div>
          <div><label class="field-label" for="lAcno">Account No</label><input class="field-input" id="lAcno" maxlength="40"></div>
          <div><label class="field-label" for="lIfsc">IFSC</label><input class="field-input" id="lIfsc" maxlength="20"></div>
        </div>
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="lNotes">Notes</label><input class="field-input" id="lNotes" maxlength="255"></div>
          <label class="check"><input type="checkbox" id="lActive" checked> Active</label>
        </div>
        <p class="field-hint">Opening balance is as on the books start date. Assets and expenses are normally Dr; liabilities, capital and income Cr.</p>
        <p class="field-error" id="ledErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="grpModal" aria-hidden="true">
  <div class="modal sm" role="dialog" aria-modal="true" aria-labelledby="grpTitle">
    <div class="modal-head"><h2 id="grpTitle">Add Group</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="grpForm" novalidate>
      <input type="hidden" id="gId" value="0">
      <div class="modal-body">
        <div class="grid" style="margin-top:12px">
          <div class="full"><label class="field-label" for="gName">Group Name <span class="req">*</span></label><input class="field-input" id="gName" maxlength="100"></div>
          <div><label class="field-label" for="gParent">Under</label><select class="field-input" id="gParent"></select></div>
          <div><label class="field-label" for="gNature">Nature</label>
            <select class="field-input" id="gNature"><?php foreach ($natures as $k => $l): ?><option value="<?= $k ?>"><?= html_escape($l) ?></option><?php endforeach; ?></select></div>
        </div>
        <p class="field-error" id="grpErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const GROUPS = <?= json_encode($groups_js) ?>;
  const NAT = <?= json_encode($natures) ?>;
  const lm = U.modal('ledModal'), gm = U.modal('grpModal');
  const byId = {}; GROUPS.forEach(g => byId[g.id] = g);
  const label = g => (g.parent ? byId[g.parent].name + ' › ' : '') + g.name;

  $('lGroup').innerHTML = Object.keys(NAT).map(n => `<optgroup label="${U.esc(NAT[n])}">` +
    GROUPS.filter(g => g.nature === n).map(g => `<option value="${g.id}">${U.esc(label(g))}</option>`).join('') + '</optgroup>').join('');
  $('gParent').innerHTML = '<option value="">— Main group —</option>' + GROUPS.filter(g => !g.parent).map(g => `<option value="${g.id}">${U.esc(g.name)}</option>`).join('');
  $('lType').addEventListener('change', () => $('bankBox').hidden = $('lType').value !== 'BANK');
  $('gParent').addEventListener('change', () => { const p = byId[$('gParent').value]; if (p) $('gNature').value = p.nature; $('gNature').disabled = !!p; });

  function openLedger(l) {
    $('ledErr').hidden = true; $('lId').value = l ? l.id : 0; $('ledTitle').textContent = l ? 'Edit Account' : 'Add Account';
    $('lName').value = l ? l.name : ''; $('lCode').value = l ? l.code : ''; if (l) $('lGroup').value = l.group;
    $('lType').value = l ? l.type : 'GENERAL'; $('lOpen').value = l && l.opening ? l.opening : ''; $('lOpenType').value = l ? l.opening_type : 'Dr';
    $('lPhone').value = l ? l.phone : ''; $('lBank').value = l ? l.bank_name : ''; $('lAcno').value = l ? l.account_no : ''; $('lIfsc').value = l ? l.ifsc : '';
    $('lNotes').value = l ? l.notes : ''; $('lActive').checked = l ? !!l.active : true;
    $('bankBox').hidden = $('lType').value !== 'BANK';
    lm.show(); $('lName').focus();
  }
  function openGroup(g) {
    $('grpErr').hidden = true; $('gId').value = g ? g.id : 0; $('grpTitle').textContent = g ? 'Edit Group' : 'Add Group';
    $('gName').value = g ? g.name : ''; $('gParent').value = g && g.parent ? g.parent : ''; $('gNature').value = g ? g.nature : 'EXPENSE';
    $('gParent').disabled = !!(g && g.system); $('gNature').disabled = !!(g && (g.system || g.parent));
    gm.show(); $('gName').focus();
  }
  const ol = $('openLedger'); if (ol) ol.addEventListener('click', () => openLedger(null));
  const og = $('openGroup'); if (og) og.addEventListener('click', () => openGroup(null));

  $('ledForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    [['id', 'lId'], ['name', 'lName'], ['code', 'lCode'], ['group', 'lGroup'], ['type', 'lType'], ['opening', 'lOpen'], ['opening_type', 'lOpenType'],
     ['phone', 'lPhone'], ['bank_name', 'lBank'], ['account_no', 'lAcno'], ['ifsc', 'lIfsc'], ['notes', 'lNotes']].forEach(([k, id]) => fd.append(k, $(id).value.trim()));
    if ($('lActive').checked) fd.append('active', 1);
    const res = await U.post('<?= site_url('acc_ledger_save') ?>', fd);
    if (!res.status) { $('ledErr').textContent = res.msg; $('ledErr').hidden = false; return; }
    lm.hide(); U.ok(res.msg);
  });
  $('grpForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('gId').value); fd.append('name', $('gName').value.trim()); fd.append('parent', $('gParent').value); fd.append('nature', $('gNature').value);
    const res = await U.post('<?= site_url('acc_group_save') ?>', fd);
    if (!res.status) { $('grpErr').textContent = res.msg; $('grpErr').hidden = false; return; }
    gm.hide(); U.ok(res.msg);
  });

  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    if (tr.dataset.l) {
      const l = JSON.parse(tr.dataset.l);
      if (e.target.closest('[data-edit]')) return openLedger(l);
      if (e.target.closest('[data-del]')) {
        const c = await Swal.fire({title: 'Delete ' + l.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
        if (!c.isConfirmed) return;
        const fd = new FormData(); fd.append('id', l.id);
        const res = await U.post('<?= site_url('acc_ledger_delete') ?>', fd);
        res.status ? U.ok(res.msg) : U.err(res.msg);
      }
    } else if (tr.dataset.g) {
      const g = byId[tr.dataset.g];
      if (e.target.closest('[data-gedit]')) return openGroup(g);
      if (e.target.closest('[data-gdel]')) {
        const c = await Swal.fire({title: 'Delete group ' + g.name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
        if (!c.isConfirmed) return;
        const fd = new FormData(); fd.append('id', g.id);
        const res = await U.post('<?= site_url('acc_group_delete') ?>', fd);
        res.status ? U.ok(res.msg) : U.err(res.msg);
      }
    }
  });
})();
</script>
