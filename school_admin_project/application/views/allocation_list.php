<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:560px;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal form{display:flex;flex-direction:column;min-height:0;flex:1}
.modal-body{padding:20px;overflow-y:auto;flex:1}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;margin-bottom:16px;background:#f8fafc}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}

/* subject picker */
.picker{position:relative;margin-bottom:16px}
.pick-box{display:flex;flex-wrap:wrap;gap:6px;min-height:46px;padding:7px 10px;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;cursor:pointer;align-items:center}
.pick-box.focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}
.pick-ph{color:#94a3b8;font-size:14px}
.chip{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;background:#2563eb;color:#fff;border-radius:6px;font-size:13px;font-weight:600;text-transform:uppercase}
.chip button{background:none;border:0;color:#fff;font-size:15px;line-height:1;cursor:pointer;padding:0}
.pick-menu{position:absolute;left:0;right:0;top:calc(100% + 4px);background:#fff;border:1px solid #cbd5e1;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,.12);max-height:200px;overflow-y:auto;z-index:5}
.pick-menu button{display:block;width:100%;text-align:left;padding:9px 12px;background:none;border:0;font:inherit;font-size:14px;cursor:pointer}
.pick-menu button:hover{background:#eef2ff}
.pick-empty{padding:10px 12px;color:#94a3b8;font-size:13px}

/* marks rows */
.mark-row{display:grid;grid-template-columns:150px 1fr;gap:12px;align-items:center;margin-bottom:12px}
.mark-row label{font-size:12px;font-weight:700;text-transform:uppercase}
.mark-row .field-input{margin:0}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.tag{display:inline-block;padding:3px 10px;margin:2px 4px 2px 0;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
.tag b{color:#1e293b;margin-left:4px}
[hidden]{display:none !important}
@media(max-width:480px){.mark-row{grid-template-columns:1fr;gap:4px}}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Mark Allocation</h1>
      <p class="page-sub">Set subject marks for each class and exam</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Mark Allocation
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search class, exam or subject…" aria-label="Search">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:70px">SL</th>
            <th style="width:100px">Class</th>
            <th style="width:120px">Exam</th>
            <th>Subject</th>
            <th style="width:80px;text-align:center">Edit</th>
            <th style="width:80px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No mark allocations found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Mark Allocation</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <label class="field-label" for="mClass">Select Class <span class="req">*</span></label>
        <select id="mClass" class="field-input"></select>

        <label class="field-label" for="mExam">Select Exam <span class="req">*</span></label>
        <select id="mExam" class="field-input"></select>

        <label class="field-label">Select Subjects <span class="req">*</span></label>
        <div class="picker" id="picker">
          <div class="pick-box" id="pickBox" tabindex="0"></div>
          <div class="pick-menu" id="pickMenu" hidden></div>
        </div>

        <div id="marksWrap" hidden>
          <label class="field-label">Subject Marks</label>
          <div id="marksList"></div>
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

  // Master data (load these from your backend later)
  const CLASSES  = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
  const EXAMS    = ['PA1','PA2','Half Yearly','PA3','Annual'];
  const SUBJECTS = ['Hindi','English','Malayalam','Maths','Physics','Chemistry','Biology','Social Science','Computer'];

  let items = [
    { id: 1, cls: 'II', exam: 'PA1', subjects: [{ name: 'Hindi', marks: 50 }, { name: 'Physics', marks: 50 }] }
  ];
  let nextId = 2, editId = null;
  let selected = [];      // chosen subject names (in order)
  let marks = {};         // subject -> marks typed so far

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const fill = (el, list, ph) => el.innerHTML = `<option value="">${ph}</option>` +
    list.map(v => `<option value="${esc(v)}">${esc(v)}</option>`).join('');
  fill($('mClass'), CLASSES, 'Select Class');
  fill($('mExam'), EXAMS, 'Select Exam');

  /* ---------- list ---------- */
  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || i.cls.toLowerCase().includes(q) ||
      i.exam.toLowerCase().includes(q) || i.subjects.some(s => s.name.toLowerCase().includes(q)));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td><strong>${esc(i.cls)}</strong></td>
        <td>${esc(i.exam)}</td>
        <td>${i.subjects.map(s => `<span class="tag">${esc(s.name)}<b>${s.marks}</b></span>`).join('')}</td>
        <td style="text-align:center">
          <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          </button>
        </td>
        <td style="text-align:center">
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button>
        </td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} allocations`;
  }

  /* ---------- subject picker ---------- */
  function saveMarks() {              // keep typed marks before re-rendering
    $('marksList').querySelectorAll('input[data-sub]').forEach(inp => { marks[inp.dataset.sub] = inp.value; });
  }
  function renderPicker() {
    $('pickBox').innerHTML = selected.length
      ? selected.map(s => `<span class="chip"><button type="button" data-rm="${esc(s)}" aria-label="Remove ${esc(s)}">&times;</button>${esc(s)}</span>`).join('')
      : '<span class="pick-ph">Click to select subjects</span>';
    const rest = SUBJECTS.filter(s => !selected.includes(s));
    $('pickMenu').innerHTML = rest.length
      ? rest.map(s => `<button type="button" data-add="${esc(s)}">${esc(s)}</button>`).join('')
      : '<div class="pick-empty">All subjects selected</div>';

    $('marksWrap').hidden = !selected.length;
    $('marksList').innerHTML = selected.map(s => `
      <div class="mark-row">
        <label for="mk-${esc(s)}">${esc(s)}</label>
        <input class="field-input" type="number" min="1" step="1" id="mk-${esc(s)}" data-sub="${esc(s)}"
               placeholder="Enter max marks" value="${marks[s] != null ? esc(marks[s]) : ''}">
      </div>`).join('');
  }
  const menuOpen = v => { $('pickMenu').hidden = !v; $('pickBox').classList.toggle('focus', v); };

  $('pickBox').addEventListener('click', e => {
    const rm = e.target.closest('[data-rm]');
    if (rm) {
      saveMarks();
      selected = selected.filter(s => s !== rm.dataset.rm);
      renderPicker();
      return;
    }
    menuOpen($('pickMenu').hidden);
  });
  $('pickMenu').addEventListener('click', e => {
    const add = e.target.closest('[data-add]');
    if (!add) return;
    saveMarks();
    selected.push(add.dataset.add);
    renderPicker();
  });
  document.addEventListener('click', e => { if (!$('picker').contains(e.target)) menuOpen(false); });

  /* ---------- modal ---------- */
  function open(item) {
    form.reset();
    errorEl.hidden = true;
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Mark Allocation' : 'Add Mark Allocation';
    $('saveBtn').textContent = item ? 'Update' : 'Save';
    $('mClass').value = item ? item.cls : '';
    $('mExam').value = item ? item.exam : '';
    selected = item ? item.subjects.map(s => s.name) : [];
    marks = {};
    if (item) item.subjects.forEach(s => { marks[s.name] = s.marks; });
    menuOpen(false);
    renderPicker();
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
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
    saveMarks();
    const cls = $('mClass').value, exam = $('mExam').value;
    if (!cls) return showError('Please select a class.');
    if (!exam) return showError('Please select an exam.');
    if (!selected.length) return showError('Please select at least one subject.');

    const subjects = [];
    for (const s of selected) {
      const m = Number(marks[s]);
      if (!marks[s] || !Number.isInteger(m) || m <= 0) return showError('Enter valid marks for ' + s + '.');
      subjects.push({ name: s, marks: m });
    }
    if (items.some(i => i.id !== editId && i.cls === cls && i.exam === exam))
      return showError('This class and exam already have an allocation. Edit it from the list.');

    if (editId) {
      // TODO: fetch('/api/mark-allocations/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ cls, exam, subjects }) })
      items = items.map(i => i.id === editId ? { id: i.id, cls, exam, subjects } : i);
    } else {
      // TODO: fetch('/api/mark-allocations', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ cls, exam, subjects }) })
      items.push({ id: nextId++, cls, exam, subjects });
    }
    items.sort((a, b) => CLASSES.indexOf(a.cls) - CLASSES.indexOf(b.cls) || EXAMS.indexOf(a.exam) - EXAMS.indexOf(b.exam));
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
    if (del && confirm('Delete this mark allocation?')) {
      // TODO: fetch('/api/mark-allocations/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>