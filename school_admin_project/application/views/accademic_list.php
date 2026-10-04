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
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:8px;background:#fff}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px}
.field-hint{font-size:12px;color:#94a3b8;margin:0 0 8px}
.field-error{color:#dc2626;font-size:13px;margin:8px 0 0}
[hidden]{display:none !important}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Academic Year</h1>
      <p class="page-sub">Manage academic years</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Academic Year
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search year…" aria-label="Search academic year">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th>Academic Year</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No academic years found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Academic Year</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="yName">Academic Year <span class="req">*</span></label>
        <input type="text" id="yName" class="field-input" placeholder="e.g. 2026-27" maxlength="7">
        <p class="field-hint">Format: 2026-27</p>
        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
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
  let items = [
    { id: 1, year: '2024-25' },
    { id: 2, year: '2025-26' },
    { id: 3, year: '2026-27' }
  ];
  let nextId = 4;
  let editId = null;
  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || i.year.toLowerCase().includes(q));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td class="v-title">${esc(i.year)}</td>
        <td>
          <div class="row-actions" style="justify-content:flex-end;">
            <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit ${esc(i.year)}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </button>
            <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete ${esc(i.year)}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
            </button>
          </div>
        </td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} academic years`;
  }

  function open(item) {
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Academic Year' : 'Add Academic Year';
    $('saveBtn').textContent = item ? 'Update' : 'Save';
    $('yName').value = item ? item.year : '';
    errorEl.hidden = true;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('yName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    form.reset();
    errorEl.hidden = true;
    editId = null;
  }
  function showError(msg) { errorEl.textContent = msg; errorEl.hidden = false; }

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const year = $('yName').value.trim();
    if (!year) return showError('Please enter the academic year.');
    if (!/^\d{4}-\d{2}$/.test(year)) return showError('Use the format 2026-27.');
    const start = +year.slice(0, 4), end = +year.slice(5);
    if ((start + 1) % 100 !== end) return showError('The end year must follow the start year (e.g. 2026-27).');
    if (items.some(i => i.id !== editId && i.year === year)) return showError('This academic year already exists.');

    if (editId) {
      // TODO: fetch('/api/academic-years/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ year }) })
      items = items.map(i => i.id === editId ? { id: i.id, year } : i);
    } else {
      // TODO: fetch('/api/academic-years', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ year }) })
      items.push({ id: nextId++, year });
    }
    items.sort((a, b) => a.year.localeCompare(b.year));
    render();
    close();
  });

  $('rows').addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]');
    const del = e.target.closest('[data-del]');
    if (ed) {
      const item = items.find(i => i.id === +ed.dataset.edit);
      if (item) open(item);
    }
    if (del && confirm('Delete this academic year?')) {
      // TODO: fetch('/api/academic-years/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>