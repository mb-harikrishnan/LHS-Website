<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:440px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:10px 12px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:8px;background:#fff}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px}
.field-error{color:#dc2626;font-size:13px;margin:8px 0 0}

/* enable / disable switch */
.act{display:flex;align-items:center;gap:10px}
.switch{position:relative;width:44px;height:24px;flex:none}
.switch input{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;margin:0;z-index:1}
.switch .track{position:absolute;inset:0;background:#cbd5e1;border-radius:99px;transition:background .2s}
.switch .track::after{content:"";position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.3);transition:transform .2s}
.switch input:checked + .track{background:#22c55e}
.switch input:checked + .track::after{transform:translateX(20px)}
.switch input:focus-visible + .track{outline:2px solid #6366f1;outline-offset:2px}
.state{font-size:12px;font-weight:700;min-width:62px}
.state.on{color:#15803d}
.state.off{color:#94a3b8}
tr.is-off .v-title{color:#94a3b8}
[hidden]{display:none !important}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Role List</h1>
      <p class="page-sub">Manage roles and enable or disable them</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Role
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search role…" aria-label="Search roles">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th>Name</th>
            <th style="width:200px">Action</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No roles found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Role</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="rName">Role <span class="req">*</span></label>
        <input type="text" id="rName" class="field-input" placeholder="Enter role" maxlength="60">
        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError');
  let items = [
    { id: 1, name: 'Admin',      enabled: true },
    { id: 2, name: 'Teacher',    enabled: true },
    { id: 3, name: 'Accountant', enabled: true },
    { id: 4, name: 'Librarian',  enabled: false }
  ];
  let nextId = 5;
  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || i.name.toLowerCase().includes(q));
    $('rows').innerHTML = list.map((i, n) => `
      <tr class="${i.enabled ? '' : 'is-off'}">
        <td class="num">${n + 1}</td>
        <td class="v-title">${esc(i.name)}</td>
        <td>
          <div class="act">
            <label class="switch" title="${i.enabled ? 'Click to disable' : 'Click to enable'}">
              <input type="checkbox" data-toggle="${i.id}" ${i.enabled ? 'checked' : ''} aria-label="Enable or disable ${esc(i.name)}">
              <span class="track"></span>
            </label>
            <span class="state ${i.enabled ? 'on' : 'off'}">${i.enabled ? 'Enabled' : 'Disabled'}</span>
          </div>
        </td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} roles`;
  }

  function open() {
    form.reset();
    errorEl.hidden = true;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('rName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
  }

  $('openAddModal').addEventListener('click', open);
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const name = $('rName').value.trim();
    if (!name) { errorEl.textContent = 'Please enter a role.'; errorEl.hidden = false; return; }
    if (items.some(i => i.name.toLowerCase() === name.toLowerCase())) {
      errorEl.textContent = 'This role already exists.'; errorEl.hidden = false; return;
    }
    // TODO: fetch('/api/roles', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ name }) })
    items.push({ id: nextId++, name, enabled: true });   // new roles start enabled
    render();
    close();
  });

  // enable / disable
  $('rows').addEventListener('change', e => {
    const t = e.target.closest('[data-toggle]');
    if (!t) return;
    const item = items.find(i => i.id === +t.dataset.toggle);
    if (!item) return;
    item.enabled = t.checked;
    // TODO: fetch('/api/roles/' + item.id + '/status', { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ enabled: item.enabled }) })
    render();
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>