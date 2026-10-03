
<style>

.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:500px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:16px;background:#fff}
textarea.field-input{min-height:130px;resize:vertical}
.field-error{color:#dc2626;font-size:13px;margin:0}
</style>

<body>
<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Vacancy List</h1>
      <p class="page-sub">Open positions with their title and description</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Vacancy
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by title or description…" aria-label="Search vacancies">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr><th>#</th><th>Title</th><th>Description</th><th style="width:100px;text-align:right">Actions</th></tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No vacancies found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Vacancy</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="vTitle">Title <span class="req">*</span></label>
        <input type="text" id="vTitle" class="field-input" placeholder="e.g. PGT Mathematics Teacher" maxlength="120">

        <label class="field-label" for="vDesc">Description <span class="req">*</span></label>
        <textarea id="vDesc" class="field-input" placeholder="Qualifications, experience, how to apply…"></textarea>

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
    { id: 1, title: 'PGT Mathematics Teacher', desc: 'M.Sc. Mathematics with B.Ed. and at least 3 years of CBSE senior secondary teaching experience.' },
    { id: 2, title: 'Office Assistant', desc: 'Graduate with basic computer skills. Handles records, enquiries and front-desk support.' }
  ];
  let nextId = 3;
  const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || i.title.toLowerCase().includes(q) || i.desc.toLowerCase().includes(q));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td class="v-title">${esc(i.title)}</td>
        <td class="v-desc">${esc(i.desc)}</td>
        <td><div class="row-actions">
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete ${esc(i.title)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button></div></td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} vacancies`;
  }

  const open = () => { modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); $('vTitle').focus(); };
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); form.reset(); errorEl.hidden = true; };

  $('openAddModal').addEventListener('click', open);
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const title = $('vTitle').value.trim(), desc = $('vDesc').value.trim();
    if (!title || !desc) { errorEl.textContent = 'Please enter both a title and a description.'; errorEl.hidden = false; return; }
    // TODO: send to your backend, e.g.
    // fetch('/api/vacancies', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ title, description: desc }) })
    items.unshift({ id: nextId++, title, desc });
    render(); close();
  });

  $('rows').addEventListener('click', e => {
    const b = e.target.closest('[data-del]');
    if (!b || !confirm('Delete this vacancy?')) return;
    items = items.filter(i => i.id !== +b.dataset.del);
    render();
  });
  $('search').addEventListener('input', render);
  render();
})();
</script>
