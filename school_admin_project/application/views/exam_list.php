<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:520px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;margin-bottom:16px;background:#f8fafc}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}
.opts{display:flex;flex-wrap:wrap;gap:8px 18px;margin-bottom:6px}
.opts label{display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;text-transform:uppercase;cursor:pointer}
.opts input{width:16px;height:16px;accent-color:#4f46e5;cursor:pointer}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
[hidden]{display:none !important}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Exams</h1>
      <p class="page-sub">Manage exams and their display order</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Exam
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by name or abbreviation…" aria-label="Search exams">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:70px">#SL</th>
            <th>Abbreviation</th>
            <th>Name</th>
            <th style="width:130px">Display Order</th>
            <th style="width:80px;text-align:center">Edit</th>
            <th style="width:80px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No exams found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Exam</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="eName">Exam Name <span class="req">*</span></label>
        <input type="text" id="eName" class="field-input" placeholder="Enter Exam Name" maxlength="100">

        <label class="field-label" for="eAbbr">Abbreviation of Exam <span class="req">*</span></label>
        <input type="text" id="eAbbr" class="field-input" placeholder="Enter Abbreviation" maxlength="20">

        <label class="field-label" for="eTerm">Term <span class="req">*</span></label>
        <select id="eTerm" class="field-input"></select>

        <label class="field-label">Options</label>
        <div class="opts">
          <label><input type="checkbox" id="oOpened"> Opened</label>
          <label><input type="checkbox" id="oOngoing"> Ongoing</label>
          <label><input type="checkbox" id="oGrade"> Grade</label>
          <label><input type="checkbox" id="oActive"> Active</label>
          <label><input type="checkbox" id="oClosed"> Closed</label>
        </div>

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

  const TERMS = ['Term 1', 'Term 2', 'Term 3'];   // load from your backend later

  let items = [
    { id: 1, name: 'First Term Exam',  abbr: 'FT', term: 'Term 1', order: 1,
      opened: true, ongoing: false, grade: true, active: true, closed: false },
    { id: 2, name: 'Second Term Exam', abbr: 'ST', term: 'Term 2', order: 2,
      opened: true, ongoing: false, grade: true, active: true, closed: false }
  ];
  let nextId = 3, editId = null;

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  $('eTerm').innerHTML = '<option value="">-- Select Term --</option>' +
    TERMS.map(t => `<option value="${t}">${t}</option>`).join('');

  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items
      .filter(i => !q || i.name.toLowerCase().includes(q) || i.abbr.toLowerCase().includes(q))
      .sort((a, b) => a.order - b.order);
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td><strong>${esc(i.abbr)}</strong></td>
        <td>${esc(i.name)}</td>
        <td>${i.order}</td>
        <td style="text-align:center">
          <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          </button>
        </td>
        <td style="text-align:center">
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button>
        </td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} exams`;
  }

  function open(item) {
    form.reset();
    errorEl.hidden = true;
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Exam' : 'Add Exam';
    $('saveBtn').textContent = item ? 'Update' : 'Save';
    $('eName').value = item ? item.name : '';
    $('eAbbr').value = item ? item.abbr : '';
    $('eTerm').value = item ? item.term : '';
    // defaults for a new exam match your screenshot: Opened, Grade, Active ticked
    $('oOpened').checked  = item ? item.opened  : true;
    $('oOngoing').checked = item ? item.ongoing : false;
    $('oGrade').checked   = item ? item.grade   : true;
    $('oActive').checked  = item ? item.active  : true;
    $('oClosed').checked  = item ? item.closed  : false;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    $('eName').focus();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    editId = null;
  }
  const showError = msg => { errorEl.textContent = msg; errorEl.hidden = false; };

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const data = {
      name: $('eName').value.trim(),
      abbr: $('eAbbr').value.trim(),
      term: $('eTerm').value,
      opened: $('oOpened').checked, ongoing: $('oOngoing').checked,
      grade: $('oGrade').checked, active: $('oActive').checked, closed: $('oClosed').checked
    };
    if (!data.name) return showError('Please enter the exam name.');
    if (!data.abbr) return showError('Please enter the abbreviation.');
    if (!data.term) return showError('Please select a term.');
    if (items.some(i => i.id !== editId && i.abbr.toLowerCase() === data.abbr.toLowerCase()))
      return showError('This abbreviation already exists.');

    if (editId) {
      // TODO: fetch('/api/exams/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(data) })
      items = items.map(i => i.id === editId ? Object.assign({}, i, data) : i);
    } else {
      // TODO: fetch('/api/exams', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(data) })
      const order = items.reduce((m, i) => Math.max(m, i.order), 0) + 1;  // next display order
      items.push(Object.assign({ id: nextId++, order }, data));
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
    if (del && confirm('Delete this exam?')) {
      // TODO: fetch('/api/exams/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>