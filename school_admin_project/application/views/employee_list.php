<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:680px;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal form{display:flex;flex-direction:column;min-height:0;flex:1}
.modal-body{padding:20px;overflow-y:auto;flex:1}
.modal-foot{display:flex;justify-content:flex-end;align-items:center;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 18px}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;background:#f8fafc}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}
.field-input:disabled{opacity:.6;cursor:not-allowed}
.hint{font-size:12px;color:#94a3b8;margin:5px 0 0}
.pw-wrap{position:relative}
.pw-wrap .field-input{padding-right:44px}
.eye{position:absolute;right:6px;top:50%;transform:translateY(-50%);background:none;border:0;padding:6px;cursor:pointer;color:#64748b;display:flex}
.eye:hover{color:#4f46e5}
.field-error{color:#dc2626;font-size:13px;margin:0 auto 0 0}
.mask{letter-spacing:2px;color:#94a3b8}
.tag{display:inline-block;padding:3px 10px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
.sub-t{display:block;font-size:12px;color:#64748b;margin-top:2px}
[hidden]{display:none !important}
@media(max-width:600px){.grid{grid-template-columns:1fr}}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Employee List</h1>
      <p class="page-sub">Manage employees, their classes and roles</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Employee
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search name, mobile, designation, class…" aria-label="Search employees">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:60px">SL</th>
            <th style="width:110px">Date</th>
            <th>Name</th>
            <th style="width:110px">Password</th>
            <th>Mobile</th>
            <th>Designation</th>
            <th>Class</th>
            <th>Division</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No employees found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Employee</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate autocomplete="off">
      <div class="modal-body">
        <div class="grid">
          <div>
            <label class="field-label" for="eName">Name <span class="req">*</span></label>
            <input id="eName" class="field-input" placeholder="Enter Name" maxlength="80">
          </div>
          <div>
            <label class="field-label" for="ePw">Password <span class="req" id="pwReq">*</span></label>
            <div class="pw-wrap">
              <input id="ePw" type="password" class="field-input" placeholder="Enter Password" autocomplete="new-password">
              <button type="button" class="eye" id="eyeBtn" aria-label="Show password">
                <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <p class="hint" id="pwHint" hidden>Leave empty to keep the current password.</p>
          </div>
          <div>
            <label class="field-label" for="eMobile">Mobile <span class="req">*</span></label>
            <input id="eMobile" class="field-input" placeholder="Enter Mobile Number" inputmode="numeric" maxlength="10">
          </div>
          <div>
            <label class="field-label" for="eDesig">Designation <span class="req">*</span></label>
            <input id="eDesig" class="field-input" placeholder="Enter Designation" maxlength="60">
          </div>
          <div>
            <label class="field-label" for="eClass">Class <span class="req">*</span></label>
            <select id="eClass" class="field-input"></select>
          </div>
          <div>
            <label class="field-label" for="eDiv">Division <span class="req">*</span></label>
            <select id="eDiv" class="field-input"></select>
          </div>
          <div>
            <label class="field-label" for="eRole">User Roles <span class="req">*</span></label>
            <select id="eRole" class="field-input"></select>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <p class="field-error" id="formError" hidden></p>
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError');

  // Master data: load from your Class Division and Role List pages later
  const CLASSES = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
  const DIVISIONS = { default: ['Pearl','Manikyam','Vydooryam','Marathakam','Indraneelam','Vajram'],
                      XI: ['Science','Commerce'], XII: ['Science','Commerce'] };
  const ROLES = ['Admin','Teacher','Accountant','Librarian'];   // enabled roles only

  const today = () => { const d = new Date(), p = n => String(n).padStart(2, '0');
    return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()); };

  let items = [
    { id:1, date: today(), name:'Anitha Nair', mobile:'9876543210', desig:'Class Teacher', cls:'V', div:'Pearl', role:'Teacher' }
  ];
  let nextId = 2, editId = null;

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const fmtDate = d => d.split('-').reverse().join('-');
  const divsFor = c => DIVISIONS[c] || DIVISIONS.default;
  const fill = (el, list, ph, val) => { el.innerHTML = `<option value="">${ph}</option>` +
    list.map(v => `<option value="${esc(v)}">${esc(v)}</option>`).join(''); el.value = val || ''; };

  fill($('eClass'), CLASSES, 'Select Option');
  fill($('eRole'), ROLES, 'Select Option');
  fill($('eDiv'), [], 'Select Class first'); $('eDiv').disabled = true;

  $('eClass').addEventListener('change', () => {
    const c = $('eClass').value;
    fill($('eDiv'), c ? divsFor(c) : [], c ? 'Select Option' : 'Select Class first');
    $('eDiv').disabled = !c;
  });

  /* ---------- list ---------- */
  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || [i.name, i.mobile, i.desig, i.cls, i.div, i.role].some(v => v.toLowerCase().includes(q)));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td>${fmtDate(i.date)}</td>
        <td><strong>${esc(i.name)}</strong><span class="sub-t">${esc(i.role)}</span></td>
        <td><span class="mask">••••••••</span></td>
        <td>${esc(i.mobile)}</td>
        <td>${esc(i.desig)}</td>
        <td>${esc(i.cls)}</td>
        <td><span class="tag">${esc(i.div)}</span></td>
        <td><div class="row-actions" style="justify-content:flex-end;">
          <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          </button>
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button>
        </div></td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} employees`;
  }

  /* ---------- modal ---------- */
  const showError = msg => { errorEl.textContent = msg; errorEl.hidden = false; };

  function open(item) {
    form.reset();
    errorEl.hidden = true;
    $('ePw').type = 'password';
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Employee' : 'Add Employee';
    $('saveBtn').textContent = item ? 'Update' : 'Save';
    $('pwReq').hidden = !!item;
    $('pwHint').hidden = !item;
    $('eName').value = item ? item.name : '';
    $('eMobile').value = item ? item.mobile : '';
    $('eDesig').value = item ? item.desig : '';
    $('eClass').value = item ? item.cls : '';
    fill($('eDiv'), item ? divsFor(item.cls) : [], item ? 'Select Option' : 'Select Class first', item ? item.div : '');
    $('eDiv').disabled = !item;
    $('eRole').value = item ? item.role : '';
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('eName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    editId = null;
  }

  $('eyeBtn').addEventListener('click', () => {
    const show = $('ePw').type === 'password';
    $('ePw').type = show ? 'text' : 'password';
    $('eyeBtn').setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    $('eyeBtn').style.color = show ? '#4f46e5' : '';
  });

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const d = {
      name: $('eName').value.trim(), mobile: $('eMobile').value.trim(),
      desig: $('eDesig').value.trim(), cls: $('eClass').value,
      div: $('eDiv').value, role: $('eRole').value
    };
    const pw = $('ePw').value;

    if (!d.name) return showError('Please enter the name.');
    if (!editId && !pw) return showError('Please enter a password.');
    if (pw && pw.length < 6) return showError('Password must be at least 6 characters.');
    if (!/^\d{10}$/.test(d.mobile)) return showError('Mobile number must be 10 digits.');
    if (items.some(i => i.id !== editId && i.mobile === d.mobile)) return showError('This mobile number is already used.');
    if (!d.desig) return showError('Please enter the designation.');
    if (!d.cls) return showError('Please select a class.');
    if (!d.div) return showError('Please select a division.');
    if (!d.role) return showError('Please select a user role.');

    // The password is never kept in the list. Send it to the server, which must hash it.
    if (editId) {
      // TODO: fetch('/api/employees/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(Object.assign({}, d, pw ? { password: pw } : {})) })
      items = items.map(i => i.id === editId ? Object.assign({}, i, d) : i);
    } else {
      // TODO: fetch('/api/employees', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(Object.assign({}, d, { password: pw })) })
      items.unshift(Object.assign({ id: nextId++, date: today() }, d));
    }
    render();
    close();
  });

  $('rows').addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]'), del = e.target.closest('[data-del]');
    if (ed) { const it = items.find(i => i.id === +ed.dataset.edit); if (it) open(it); }
    if (del && confirm('Delete this employee?')) {
      // TODO: fetch('/api/employees/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>