<?php /* $parents (with children[]), $q, $perm, $counts {unlinked, no_phone}, $classes, $div_map, $portal_url */
$can_add = !empty($perm['can_add']); $can_edit = !empty($perm['can_edit']); $can_del = !empty($perm['can_delete']); ?>
<style>
.kid-chips{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
.kid-chip{display:inline-flex;align-items:center;gap:8px;padding:6px 6px 6px 12px;border-radius:10px;background:var(--brand-50);border:1px solid var(--brand-200);color:var(--brand-800);font-weight:600;font-size:13px}
.kid-chip small{font-weight:400;color:var(--slate-500)}
.kid-chip button{border:0;background:#fff;border-radius:6px;width:24px;height:24px;cursor:pointer;color:var(--slate-500)}
.kid-chip button:hover{background:var(--rose-50);color:var(--rose-600)}
.kid-find{display:grid;grid-template-columns:1fr 170px 150px;gap:8px}
.kid-status{font-size:12.5px;color:var(--slate-500);margin:8px 0}
.kid-results{border:1px solid var(--line);border-radius:10px;max-height:260px;overflow-y:auto}
.kid-results button{display:flex;justify-content:space-between;align-items:center;gap:10px;width:100%;text-align:left;padding:8px 12px;border:0;border-bottom:1px solid var(--slate-100);background:#fff;cursor:pointer;font:inherit}
.kid-results button:last-child{border-bottom:0}
.kid-results button:hover{background:var(--brand-50)}
.kid-results button small{display:block;font-size:12px;color:var(--slate-500)}
.kid-results .st{font-size:12px;font-weight:700;color:var(--brand-600);white-space:nowrap}
.kid-results button.added{background:var(--emerald-50)}
.kid-results button.added .st{color:var(--emerald-700)}
.kid-tools{margin-top:8px}
@media (max-width:640px){.kid-find{grid-template-columns:1fr}}
</style>
<main class="page">
  <div class="page-header">
    <div><h1 class="page-title">Parent Accounts</h1>
      <p class="page-sub">Parents sign in at <a href="<?= html_escape($portal_url) ?>" target="_blank"><?= html_escape($portal_url) ?></a> with their mobile number. One account can have several children.</p></div>
    <?php if ($can_add): ?>
    <div class="page-actions">
      <button class="btn btn-secondary" type="button" id="openBulk">Create from student phone numbers</button>
      <button class="btn btn-primary" type="button" id="openAdd">+ Parent</button>
    </div>
    <?php endif; ?>
  </div>
  <div class="card">
    <div class="sum-grid">
      <div class="sum-item ok"><small>Parent accounts</small><strong><?= count($parents) ?><?= $q !== '' ? ' found' : '' ?></strong></div>
      <div class="sum-item warn"><small>Students without a parent account</small><strong><?= (int)$counts['unlinked'] ?></strong></div>
      <div class="sum-item bad"><small>…of them without any phone number</small><strong><?= (int)$counts['no_phone'] ?></strong></div>
    </div>
    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('parent_accounts') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="q" value="<?= html_escape($q) ?>" placeholder="Parent name, mobile, student name or admission no…">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <a class="btn btn-secondary" href="<?= site_url('parent_accounts') ?>">Clear</a>
      </form>
    </div>
    <div class="table-wrap">
      <table class="table compact">
        <thead><tr><th>Parent</th><th>Mobile (login)</th><th>Children</th><th>Last login</th><th>Status</th><th>Action</th></tr></thead>
        <tbody id="rows">
        <?php foreach ($parents as $p):
          $row = array('id' => (int)$p->pmId, 'name' => $p->pmName, 'mobile' => $p->pmMobile, 'active' => (int)$p->pmActive, 'children' => $p->children);
          $locked = $p->pmLockUntil && strtotime($p->pmLockUntil) > time(); ?>
          <tr data-p="<?= html_escape(json_encode($row)) ?>">
            <td><strong><?= html_escape($p->pmName) ?></strong></td>
            <td><?= html_escape($p->pmMobile) ?></td>
            <td><?php foreach ($p->children as $c): ?><span class="badge badge-info" style="margin:2px"><?= html_escape($c['name']) ?> · <?= html_escape($c['class']) ?></span><?php endforeach; ?>
              <?php if (!$p->children): ?><span class="badge badge-red">No child linked</span><?php endif; ?></td>
            <td class="small"><?= $p->pmLastLogin ? date('d-m-Y H:i', strtotime($p->pmLastLogin)) : '<span class="muted">Never</span>' ?></td>
            <td><?= !$p->pmActive ? '<span class="badge badge-gray">Disabled</span>' : ($locked ? '<span class="badge badge-amber">Locked (wrong passwords)</span>' : '<span class="badge badge-green">Active</span>') ?></td>
            <td><div class="act-row">
              <?php if ($can_edit): ?><button class="act-btn ed" type="button" data-edit>Edit</button><button class="act-btn dl" type="button" data-reset>Reset password</button><?php endif; ?>
              <?php if ($can_del): ?><button class="act-btn rm" type="button" data-del>Delete</button><?php endif; ?>
            </div></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$parents): ?><div class="empty">No parent accounts<?= $q !== '' ? ' match the search' : ' yet' ?>.</div><?php endif; ?>
    </div>
  </div>
</main>

<div class="modal-overlay" id="parModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="parTitle">
    <div class="modal-head"><h2 id="parTitle">Add Parent</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <form id="parForm" novalidate autocomplete="off">
      <input type="hidden" id="pId" value="0">
      <div class="modal-body">
        <div class="grid g3" style="margin-top:12px">
          <div><label class="field-label" for="pName">Parent Name <span class="req">*</span></label><input class="field-input" id="pName" maxlength="150"></div>
          <div><label class="field-label" for="pMobile">Mobile (login) <span class="req">*</span></label><input class="field-input" id="pMobile" inputmode="numeric" maxlength="14"></div>
          <div><label class="field-label" for="pPass" id="pPassLbl">Password <span class="req">*</span></label>
            <div class="act-row" style="flex-wrap:nowrap"><input class="field-input" id="pPass" maxlength="64" autocomplete="new-password"><button type="button" class="btn btn-secondary" id="genPass" title="Generate">Generate</button></div></div>
        </div>
        <div class="sec-title">Children <span class="muted" id="kidCount" style="text-transform:none;font-weight:400"></span></div>
        <div id="kids" class="kid-chips"></div>
        <div class="kid-find">
          <input class="field-input" id="kidQ" placeholder="Search by student name or admission no…" autocomplete="off" aria-label="Search students">
          <select class="field-input" id="kidCls" aria-label="Class"><option value="">or pick a class…</option>
            <?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option><?php endforeach; ?></select>
          <select class="field-input" id="kidDiv" aria-label="Division" disabled><option value="">All divisions</option></select>
        </div>
        <div class="kid-status" id="kidStatus">Type at least 2 letters, or pick a class, then click students to add them. You can add several.</div>
        <div class="kid-results" id="kidRes" hidden></div>
        <div class="kid-tools" id="kidTools" hidden><button type="button" class="btn btn-secondary btn-sm" id="kidAddAll">Add all shown</button></div>
        <label class="check" style="margin-top:14px"><input type="checkbox" id="pActive" checked> Account active</label>
        <p class="field-error" id="parErr" hidden></p>
      </div>
      <div class="modal-foot"><button type="button" class="btn btn-secondary" data-close>Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="bulkModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="bulkTitle">
    <div class="modal-head"><h2 id="bulkTitle">Create accounts from phone numbers</h2><button class="modal-close" type="button" data-close aria-label="Close">&times;</button></div>
    <div class="modal-body">
      <div id="bulkAsk">
        <p style="margin-top:12px">For every student of this year without a parent account, the father's phone (else mother's, else student mobile) becomes the login.
          Brothers and sisters with the same number get <strong>one</strong> account. A random password is created for each new account and shown <strong>only once</strong> — download or print the list and give each parent their password.</p>
        <label class="field-label" for="bulkClass">Students of</label>
        <select class="field-input" id="bulkClass" style="max-width:260px"><option value="">All classes</option><?php foreach ($classes as $c): ?><option value="<?= (int)$c->cmId ?>"><?= html_escape($c->cmName) ?></option><?php endforeach; ?></select>
      </div>
      <div id="bulkOut" hidden>
        <p id="bulkMsg" class="ok-txt" style="margin-top:12px"></p>
        <div class="table-wrap"><table class="table compact" id="bulkTable"><thead><tr><th>Parent</th><th>Mobile</th><th>Children</th><th>Password</th></tr></thead><tbody></tbody></table></div>
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-secondary" data-close>Close</button>
      <button type="button" class="btn btn-secondary" id="bulkCsv" hidden>Download CSV</button>
      <button type="button" class="btn btn-secondary" id="bulkPrint" hidden>Print</button>
      <button type="button" class="btn btn-primary" id="bulkGo">Create accounts</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/store.js') ?>"></script>
<script>
(function () {
  const U = StoreUI, $ = id => document.getElementById(id);
  U.init({name: '<?= $this->security->get_csrf_token_name() ?>', hash: '<?= $this->security->get_csrf_hash() ?>'});
  const pm = U.modal('parModal'), bm = U.modal('bulkModal');
  let kids = [];
  const showErr = m => { $('parErr').textContent = m; $('parErr').hidden = false; };
  const genPw = () => { const c = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; let p = ''; const a = new Uint32Array(8); crypto.getRandomValues(a); a.forEach(x => p += c[x % c.length]); return p; };

  function drawKids() {
    $('kidCount').textContent = kids.length ? '(' + kids.length + ' linked)' : '';
    $('kids').innerHTML = kids.length ? kids.map((k, i) => `<span class="kid-chip">${U.esc(k.name)}<small>${U.esc(k.class)}${k.adm ? ' · ' + U.esc(k.adm) : ''}</small>
      <button type="button" data-i="${i}" aria-label="Remove ${U.esc(k.name)}">✕</button></span>`).join('')
      : '<span class="muted small">No child linked yet.</span>';
    markAdded();
  }
  $('kids').addEventListener('click', e => { const b = e.target.closest('button[data-i]'); if (b) { kids.splice(+b.dataset.i, 1); drawKids(); } });

  /* ---------- student search (results stay open so several can be added) ---------- */
  const DIV_MAP = <?= json_encode($div_map) ?>;
  let results = [], timer, seq = 0;
  const status = (m, cls) => { $('kidStatus').textContent = m; $('kidStatus').className = 'kid-status ' + (cls || ''); };
  const has = id => kids.some(k => k.id === id);
  function addKid(s) { if (!has(s.id)) { kids.push({id: s.id, name: s.name, class: s.class, adm: s.adm}); drawKids(); } }
  function markAdded() {
    $('kidRes').querySelectorAll('[data-r]').forEach(b => {
      const on = has(results[+b.dataset.r].id);
      b.classList.toggle('added', on); b.querySelector('.st').textContent = on ? '✓ Added' : '+ Add';
    });
    $('kidTools').hidden = !results.length || results.every(r => has(r.id));
  }
  function showResults(list) {
    results = list;
    $('kidRes').innerHTML = list.map((s, i) => `<button type="button" data-r="${i}"><span><strong>${U.esc(s.name)}</strong>
      <small>${U.esc(s.adm)} · ${U.esc(s.class)}${s.parents ? ' · <em>linked to ' + U.esc(s.parents) + '</em>' : ''}</small></span><span class="st"></span></button>`).join('');
    $('kidRes').hidden = !list.length;
    markAdded();
  }
  async function search() {
    const q = $('kidQ').value.trim(), cls = $('kidCls').value, div = $('kidDiv').value;
    if (q.length < 2 && !cls) { showResults([]); status('Type at least 2 letters, or pick a class, then click students to add them. You can add several.'); return; }
    const my = ++seq;
    status('Searching…');
    try {
      const r = await fetch('<?= site_url('parent_student_search') ?>?' + new URLSearchParams({q, class: cls, division: div}), {credentials: 'same-origin'});
      const text = await r.text();
      let res; try { res = JSON.parse(text); } catch (x) { throw new Error('Your session may have expired. Reload the page and sign in again.'); }
      if (my !== seq) return;   // a newer search is running
      if (!res.status) { showResults([]); return status(res.msg, 'bad-txt'); }
      showResults(res.rows);
      status(res.rows.length ? res.rows.length + ' student(s) found. Click to add — the list stays open.' : 'No students found in the current academic year.', res.rows.length ? '' : 'warn-txt');
    } catch (x) {
      if (my === seq) { showResults([]); status(x.message || 'Search failed. Please try again.', 'bad-txt'); }
    }
  }
  $('kidQ').addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(search, 250); });
  $('kidQ').addEventListener('keydown', e => {
    if (e.key !== 'Enter') return;
    e.preventDefault();   // Enter adds the first result instead of submitting the form
    const first = results.find(r => !has(r.id));
    if (first) { addKid(first); status(first.name + ' added.', 'ok-txt'); }
  });
  $('kidCls').addEventListener('change', () => {
    const list = DIV_MAP[$('kidCls').value] || [];
    $('kidDiv').innerHTML = '<option value="">All divisions</option>' + list.map(d => `<option value="${U.esc(d.id)}">${U.esc(d.name)}</option>`).join('');
    $('kidDiv').disabled = !$('kidCls').value;
    search();
  });
  $('kidDiv').addEventListener('change', search);
  $('kidRes').addEventListener('click', e => {
    const b = e.target.closest('[data-r]'); if (!b) return;
    const s = results[+b.dataset.r];
    if (has(s.id)) { kids = kids.filter(k => k.id !== s.id); drawKids(); }   // click again to remove
    else addKid(s);
  });
  $('kidAddAll').addEventListener('click', () => { results.forEach(addKid); });
  function resetSearch() {
    $('kidQ').value = ''; $('kidCls').value = ''; $('kidDiv').innerHTML = '<option value="">All divisions</option>'; $('kidDiv').disabled = true;
    showResults([]); status('Type at least 2 letters, or pick a class, then click students to add them. You can add several.');
  }

  function open(p) {
    $('parErr').hidden = true; $('pId').value = p ? p.id : 0; $('parTitle').textContent = p ? 'Edit Parent' : 'Add Parent';
    $('pName').value = p ? p.name : ''; $('pMobile').value = p ? p.mobile : ''; $('pPass').value = p ? '' : genPw();
    $('pPass').placeholder = p ? 'Leave blank to keep the current password' : '';
    $('pPassLbl').innerHTML = p ? 'New Password' : 'Password <span class="req">*</span>';
    $('pActive').checked = p ? !!p.active : true;
    kids = p ? p.children.map(c => ({id: c.id, name: c.name, class: c.class, adm: c.adm})) : [];
    resetSearch(); drawKids(); pm.show(); $('pName').focus();
  }
  const add = $('openAdd'); if (add) add.addEventListener('click', () => open(null));
  $('genPass').addEventListener('click', () => { $('pPass').value = genPw(); });

  $('parForm').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData();
    fd.append('id', $('pId').value); fd.append('name', $('pName').value.trim()); fd.append('mobile', $('pMobile').value.trim());
    fd.append('password', $('pPass').value); if ($('pActive').checked) fd.append('active', 1);
    kids.forEach(k => fd.append('children[]', k.id));
    const pw = $('pPass').value, isNew = +$('pId').value === 0;
    const res = await U.post('<?= site_url('parent_save') ?>', fd);
    if (!res.status) return showErr(res.msg);
    pm.hide();
    if (pw) {
      Swal.fire({icon: 'success', title: 'Saved', html: `Give these login details to the parent:<br><br>Mobile: <b>${U.esc($('pMobile').value.trim())}</b><br>Password: <b>${U.esc(pw)}</b>`})
        .then(() => location.reload());
    } else U.ok(res.msg);
  });

  $('rows').addEventListener('click', async e => {
    const tr = e.target.closest('tr'); if (!tr) return;
    const p = JSON.parse(tr.dataset.p);
    if (e.target.closest('[data-edit]')) return open(p);
    if (e.target.closest('[data-reset]')) {
      const c = await Swal.fire({title: 'Reset password for ' + p.name + '?', text: 'A new password will be created and the old one stops working.', icon: 'question', showCancelButton: true, confirmButtonText: 'Reset'});
      if (!c.isConfirmed) return;
      const fd = new FormData(); fd.append('id', p.id);
      const res = await U.post('<?= site_url('parent_reset_password') ?>', fd);
      if (!res.status) return U.err(res.msg);
      Swal.fire({icon: 'success', title: 'New password', html: `Mobile: <b>${U.esc(res.mobile)}</b><br>Password: <b>${U.esc(res.password)}</b><br><br><small>This is shown only once.</small>`}).then(() => location.reload());
    }
    if (e.target.closest('[data-del]')) {
      const c = await Swal.fire({title: 'Delete account of ' + p.name + '?', text: 'The parent will no longer be able to sign in. Student records are not affected.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete'});
      if (!c.isConfirmed) return;
      const fd = new FormData(); fd.append('id', p.id);
      const res = await U.post('<?= site_url('parent_delete') ?>', fd);
      res.status ? U.ok(res.msg) : U.err(res.msg);
    }
  });

  /* ---------- bulk ---------- */
  let bulkRows = [];
  const ob = $('openBulk');
  if (ob) ob.addEventListener('click', () => { $('bulkAsk').hidden = false; $('bulkOut').hidden = true; $('bulkGo').hidden = false; $('bulkCsv').hidden = true; $('bulkPrint').hidden = true; bm.show(); });
  $('bulkGo').addEventListener('click', async () => {
    $('bulkGo').disabled = true;
    const fd = new FormData(); fd.append('class', $('bulkClass').value);
    const res = await U.post('<?= site_url('parent_bulk_create') ?>', fd);
    $('bulkGo').disabled = false;
    if (!res.status) return U.err(res.msg);
    bulkRows = res.rows;
    $('bulkAsk').hidden = true; $('bulkOut').hidden = false; $('bulkGo').hidden = true;
    $('bulkMsg').textContent = res.msg;
    $('bulkTable').tBodies[0].innerHTML = bulkRows.map(r => `<tr><td>${U.esc(r.name)}</td><td>${U.esc(r.mobile)}</td><td class="small">${U.esc(r.children)}</td>
      <td>${r.password ? '<strong>' + U.esc(r.password) + '</strong>' : '<span class="muted">existing account</span>'}</td></tr>`).join('');
    $('bulkCsv').hidden = $('bulkPrint').hidden = !bulkRows.length;
    bm.el.addEventListener('click', e => { if (e.target.closest('[data-close]') || e.target === bm.el) location.reload(); }, {once: true});
  });
  $('bulkCsv').addEventListener('click', () => {
    const q = v => '"' + String(v).replace(/"/g, '""') + '"';
    const csv = '﻿' + [['Parent', 'Mobile', 'Children', 'Password']].concat(bulkRows.map(r => [r.name, r.mobile, r.children, r.password || '(existing)']))
      .map(r => r.map(q).join(',')).join('\r\n');
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type: 'text/csv'})); a.download = 'parent_logins_' + new Date().toISOString().slice(0, 10) + '.csv'; a.click();
  });
  $('bulkPrint').addEventListener('click', () => {
    const w = window.open('', '_blank');
    w.document.write('<title>Parent logins</title><style>body{font-family:Arial;font-size:13px}div{border:1px dashed #999;padding:12px;margin:8px 0;page-break-inside:avoid}</style>' +
      bulkRows.filter(r => r.password).map(r => `<div><b>Little Hearts School — Parent Portal</b><br>${U.esc(location.origin)}<?= html_escape(parse_url($portal_url, PHP_URL_PATH)) ?><br><br>
        Parent: ${U.esc(r.name)}<br>Children: ${U.esc(r.children)}<br>Login mobile: <b>${U.esc(r.mobile)}</b><br>Password: <b>${U.esc(r.password)}</b><br><small>Please change your password after signing in.</small></div>`).join(''));
    w.document.close(); w.print();
  });
})();
</script>
