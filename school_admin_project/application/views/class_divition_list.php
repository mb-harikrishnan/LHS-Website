<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Class Divisions</h1>
      <p class="page-sub">Assign divisions to each class</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Class Division
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search class or division…" aria-label="Search">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">Sl No</th>
            <th style="width:120px">Class</th>
            <th>Divisions</th>
            <th style="width:110px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No records found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Class Division</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="cSelect">Select Class <span class="req">*</span></label>
        <select id="cSelect" class="field-input"></select>

        <label class="field-label">Select Divisions <span class="req">*</span></label>
        <div class="div-box" id="divBox"></div>

        <p class="field-error" id="formError" hidden></p>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
      </div>
    </form>
  </div>
</div>

<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:760px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 8px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:18px;background:#fff}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px}
.field-input:disabled{background:#f1f5f9;cursor:not-allowed}
.div-box{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;padding:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px}
.div-chip{display:flex;align-items:center;gap:8px;padding:9px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer}
.div-chip:hover{border-color:#6366f1}
.div-chip input{width:16px;height:16px;cursor:pointer;accent-color:#4f46e5}
.div-chip:has(input:checked){border-color:#6366f1;background:#eef2ff}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.tag{display:inline-block;padding:3px 10px;margin:2px 4px 2px 0;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
[hidden]{display:none !important}
</style>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError');

  // Classes and divisions (load these from your backend later)
  const CLASSES = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
  const DIVISIONS = ['Pearl','Manikyam','Vydooryam','Marathakam','Indraneelam','Vajram','Science','Commerce'];

  let items = [
    { id: 1, cls: 'I',  divs: ['Pearl','Manikyam'] },
    { id: 2, cls: 'XI', divs: ['Science','Commerce'] }
  ];
  let nextId = 3;
  let editId = null;

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  // build dropdown + checkboxes
  $('cSelect').innerHTML = '<option value="">Select class</option>' +
    CLASSES.map(c => `<option value="${c}">${c}</option>`).join('');
  $('divBox').innerHTML = DIVISIONS.map(d =>
    `<label class="div-chip"><input type="checkbox" name="div" value="${d}"> ${d}</label>`).join('');

  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || i.cls.toLowerCase().includes(q) ||
      i.divs.some(d => d.toLowerCase().includes(q)));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td><strong>${esc(i.cls)}</strong></td>
        <td>${i.divs.map(d => `<span class="tag">${esc(d)}</span>`).join('')}</td>
        <td>
          <div class="row-actions" style="justify-content:flex-end;">
            <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit class ${esc(i.cls)}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </button>
            <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete class ${esc(i.cls)}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
            </button>
          </div>
        </td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} classes`;
  }

  function open(item) {
    form.reset();
    errorEl.hidden = true;
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Class Division' : 'Add Class Division';
    $('saveBtn').textContent = item ? 'Update' : 'Save';
    $('cSelect').value = item ? item.cls : '';
    $('cSelect').disabled = !!item;               // class cannot change while editing
    form.querySelectorAll('input[name="div"]').forEach(cb => {
      cb.checked = item ? item.divs.includes(cb.value) : false;
    });
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    editId = null;
  }
  function showError(msg) { errorEl.textContent = msg; errorEl.hidden = false; }

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const cls = $('cSelect').value;
    const divs = Array.from(form.querySelectorAll('input[name="div"]:checked')).map(cb => cb.value);

    if (!cls) return showError('Please select a class.');
    if (!divs.length) return showError('Please select at least one division.');
    if (items.some(i => i.id !== editId && i.cls === cls))
      return showError('This class already has divisions. Edit it from the list.');

    if (editId) {
      // TODO: fetch('/api/class-divisions/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ cls, divs }) })
      items = items.map(i => i.id === editId ? { id: i.id, cls, divs } : i);
    } else {
      // TODO: fetch('/api/class-divisions', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ cls, divs }) })
      items.push({ id: nextId++, cls, divs });
      items.sort((a, b) => CLASSES.indexOf(a.cls) - CLASSES.indexOf(b.cls));
    }
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
    if (del && confirm('Delete this class division?')) {
      // TODO: fetch('/api/class-divisions/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>